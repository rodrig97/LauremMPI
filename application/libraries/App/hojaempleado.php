<?php

class hojaempleado extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hoae_id',
                                    'code'  => 'hoae_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hoae_estado'
                            );
    
    protected $_SCHEMA     = 'planillas';
    protected $_TABLE      = 'hojaasistencia_emp';
    protected $_PREF_TABLE = 'hojadetalle'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

    public function get($id)
    {
        $sql = " SELECT hoe.*,
                        indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, indiv.indiv_dni,
                        platica.platica_nombre as categoria 

                 FROM planillas.hojaasistencia_emp hoe
                 INNER JOIN public.individuo indiv ON hoe.indiv_id = indiv.indiv_id  
                 LEFT JOIN planillas.planilla_tipo_categoria platica ON hoe.platica_id = platica.platica_id 
                 WHERE  hoae_id = ?  ";
        
        $rs = $this->_CI->db->query($sql, array($id))->result_array(); 
        return $rs[0];
    }
    
 
  

    public function registrar($hoja_id = 0 , $indiv_id = 0, $fecha_inicio , $fecha_termino_trab, $platica_id = 0 , $grupo = 0  )
    {  
        
         $this->_CI->load->library(array('App/hojaasistencia','App/hojaempleadodiario'));  
         $this->_CI->db->trans_begin();

         $hoja_info =  $this->_CI->hojaasistencia->get($hoja_id);

         $config = $this->_CI->hojaasistencia->get_plati_config(trim($hoja_info['plati_id']));

         $fecha_inicio_hoja =  strtotime($hoja_info['hoa_fechaini']);
         $fecha_inicio =  ($fecha_inicio != '') ? $fecha_inicio :  $hoja_info['hoa_fechaini'];
         
         $colocar_hora_por_defecto = $config['hora_asistencia_pordefecto'];


         $sql = " SELECT  hoae.* 
                  FROM planillas.hojaasistencia_emp hoae 
                  WHERE hoae.hoa_id = ? AND hoae.indiv_id = ?  AND hoae.platica_id = ? 
                        AND hoae.hoae_estado = 1  LIMIT 1 ";
         
         $rs = $this->_CI->db->query($sql, array($hoja_id, $indiv_id, $platica_id) )->result_array();  
          
         if( sizeof($rs) == 0 )  // Si el trabajador no esta vinculado a la hoja 
         {  

             // INSERTAMOS EL REGISTRO EN HOJA_EMPLEADO
             $values = array('hoa_id'           => $hoja_id,
                             'indiv_id'         => $indiv_id,
                             'platica_id'       => $platica_id,
                             'hoagru_id'        => $grupo,
                             'hoae_fechainicio' => $fecha_inicio,
                             'hoae_fechafin'    => $fecha_termino_trab );

             list($hoae_id,$hoae_key) = parent::registrar($values, true);


             $sql = " SELECT  hoae.*, 
                              persla.persla_fechaini, 
                              persla.persla_fechafin, 
                              persla.persla_fechacese 
                      FROM planillas.hojaasistencia_emp hoae
                      LEFT JOIN rh.persona_situlaboral persla ON hoae.indiv_id = persla.pers_id AND persla.persla_ultimo = 1 AND persla.persla_estado = 1

                      WHERE hoae.hoa_id = ? AND hoae.indiv_id = ?  AND hoae.hoae_estado = 1  LIMIT 1 ";
             

             $rs = $this->_CI->db->query($sql, array($hoja_id, $indiv_id ) )->result_array();
             $info_trabajador = $rs[0];
 
             $reg_insert  = $this->get($hoae_id);

             $fecha_inicio = $reg_insert['hoae_fechainicio'];
             $fecha_termino_trab = $reg_insert['hoae_fechafin']; 

             $fecha_hasta  = strtotime($hoja_info['hoa_fechafin']);
             $fecha_inicio = strtotime($fecha_inicio);

             $fecha_inicio_contrato = strtotime($info_trabajador['persla_fechaini']);
             $dia = date('j',$fecha_inicio_contrato); 
             $mes = date('n',$fecha_inicio_contrato);
             $ano = date('Y',$fecha_inicio_contrato);
             $mk_inicio_contrato  =  mktime(0,0,0,$mes,$dia,$ano);
        
             $fecha_fin_contrato = strtotime($info_trabajador['persla_fechafin']);
             $dia = date('j',$fecha_fin_contrato);
             $mes = date('n',$fecha_fin_contrato);
             $ano = date('Y',$fecha_fin_contrato);
             $mk_fin_contrato  =  mktime(0,0,0,$mes,$dia,$ano);
 
        

             $dia = date('j',$fecha_inicio);
             $mes = date('n',$fecha_inicio);
             $ano = date('Y',$fecha_inicio);
             $mk_inicio_trabajador  =  mktime(0,0,0,$mes,$dia,$ano);
        

             $dia = date('j',$fecha_hasta);
             $mes = date('n',$fecha_hasta);
             $ano = date('Y',$fecha_hasta);
             $mk_limite  =  mktime(0,0,0,$mes,$dia,$ano);  
             $fecha_termino_trab = strtotime($fecha_termino_trab);

             $dia = date('j',$fecha_termino_trab);
             $mes = date('n',$fecha_termino_trab);
             $ano = date('Y',$fecha_termino_trab);
             $mk_limite_tra  =  mktime(0,0,0,$mes,$dia,$ano);  
              

             $dia = date('j',$fecha_inicio_hoja);
             $mes = date('n',$fecha_inicio_hoja);
             $ano = date('Y',$fecha_inicio_hoja);
             $mk_inicio_hoja  =  mktime(0,0,0,$mes,$dia,$ano);


             //$mk_current = $mk_inicio_hoja;
            
             $mk_current = $mk_inicio_trabajador;

             while($mk_current <= $mk_limite )  // va recorriendo y generando dia a dia desde el inicio de la hoja hasta el final
             {
        
                $n_fecha    =  date("d/m/Y",mktime(0,0,0,$mes,$dia,$ano)); 

                // Comprobar que el día no tenga una importacion 

                $n_fecha_tt =  date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)); 
                $dia_sem    =  date('N', strtotime($n_fecha_tt) );

                $sql = " SELECT hor.*, ide.hatd_id, ide.ide_laborable 
                         FROM planillas.individuo_dia_horario idh
                         LEFT JOIN planillas.hojaasistencia_horarios hor ON idh.hor_id = hor.hor_id 
                         LEFT JOIN planillas.individuo_dia_estado ide ON idh.dia_id = ide.dia_id AND idh.indiv_id = ide.indiv_id 
                         WHERE idh.indiv_id = ? AND idh.dia_id = EXTRACT( 'dow' FROM(?::date))
                         LIMIT 1  ";

                 list($horario_dia) = $this->_CI->db->query($sql, array($indiv_id, $n_fecha_tt ) )->result_array();


                 $sql = " SELECT * 
                          FROM planillas.hojaasistencia_emp_dia hoae
                          WHERE indiv_id = ? AND hoaed_fecha = ?  AND  hoaed_estado = 1 
                          LIMIT 1";

                 list($data_fecha) = $this->_CI->db->query($sql, array($indiv_id, $n_fecha_tt ) )->result_array();
                 

                // El tipo de dia debe salir de la instancia empleado_dia_estado
                // si el dia es luego del inicio del trabajador en la hoja y antes del ultimo dia del trabajador en la hoja 
                if(  $mk_current <= $mk_limite_tra )
                {
                    $hatd_id = $horario_dia['hatd_id'];
                }
                else
                {
                    $hatd_id = ASISDET_NOCONSIDERADO;
                }


               if(sizeof($data_fecha) == 0) // Si no existe un registro del día 
               {
                   
                   $values = array(
                                    'hoae_id'     => $hoae_id,
                                    'hatd_id'     => $hatd_id, 
                                    'indiv_id'    => $indiv_id,
                                    'platica_id'  => $platica_id,
                                    'hoa_id'      => $hoja_id,
                                    'hoaed_fecha' => $n_fecha_tt,
                                    'hoaed_laborable' => $horario_dia['ide_laborable'], 
                                    'hor_id'      => $horario_dia['hor_id']
                                  );


                   if($colocar_hora_por_defecto == '1' && $horario_dia['hor_hora1_e'] != '')
                   {

                       $values['hoae_hora1_e'] = $horario_dia['hor_hora1_e'];  
                       $values['hoae_hora1_s'] = $horario_dia['hor_hora1_s']; 

                       if($horario_dia['hor_hora2_e'] != '')
                       {
                           $values['hoae_hora2_e'] = $horario_dia['hor_hora2_e'];                              
                           $values['hoae_hora2_s'] = $horario_dia['hor_hora2_s'];
                       }
           
                   }

                   $this->_CI->hojaempleadodiario->registrar($values); // Se inserta un nuevo registro del dia
                
               }
               else if( $data_fecha['hoae_id'] == '0' ||  ($data_fecha['hoae_id'] != '0' &&  $data_fecha['hatd_id'] == ASISDET_NOCONSIDERADO)  )  
               {  
                    /* 
                        Si existe un registro del día pero no esta asociado a ninguna HOJA o esta vinculado a una hoja 
                        pero como dia no considerado,  con esto vinculamos descansos medicos si añadimos explicitamente el dia 
                    */
                   
                     $values =  array($hoae_id, $hoja_id, $platica_id );
                     $sql_e =  "";

                      // Importante solo seteamos el estado en caso el dia figure como no considerado para prevenir alterar los DM y similares
                     if( $data_fecha['hatd_id'] == ASISDET_NOCONSIDERADO )
                     {
                         $sql_e = ", hatd_id = ? ";
                         $values[] = $hatd_id;
                     }  


                     $sql =" UPDATE planillas.hojaasistencia_emp_dia 
                             SET hoae_id = ?, 
                                 hoa_id = ?, 
                                 platica_id = ? 
                                 ".$sql_e.", 
                                 hoaed_laborable = ?,
                                 hor_id = ?
                             
                             WHERE  indiv_id = ? AND 
                                    hoaed_fecha = ? AND hoaed_estado = 1 "; 

                     // Tratamiento aparte si es una FALTA
                     $values[] = $horario_dia['ide_laborable']; 
                     $values[] = $horario_dia['hor_id']; 

                     $values[] = $indiv_id;
                     $values[] = $n_fecha_tt;
                      
             
                     $this->_CI->db->query($sql, $values);

               }
 
   
                $dia+=1;
                $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);

             }  // Terminamos de recorrer los dias    


             if($this->_CI->db->trans_status() === FALSE) 
             {
                 $this->_CI->db->trans_rollback();
                 return false;
                 
             }
             else
             {
                     
                 $this->_CI->db->trans_commit();
                 return true;
             }
      

        }
        else
        {

            return true;
        }

        

    }

 


    public function delete($detalle)
    {


        $this->_CI->db->trans_begin();

        $sql_importados = "  SELECT  dia.hoae_id, SUM(hoaed_importado) as registros_importados 
                             FROM planillas.hojaasistencia_emp_dia dia 
                             WHERE dia.hoaed_estado = 1 AND dia.hoae_id = ? 
                             GROUP BY dia.hoae_id  ";
        list($rs) = $this->_CI->db->query($sql_importados, array( $detalle ))->result_array();   

        if( ($rs['registros_importados']*1) > 0 )
        {
            return false;
        }

        $sql ="  DELETE FROM planillas.hojaasistencia_emp_dia  
                 WHERE hoae_id  = ? AND registro_id = 0 AND tiporegistro_id = 0 AND biom_id = 0 AND hoaed_estado = 1";     
        $this->_CI->db->query($sql, array($detalle) );   

        $sql ="  UPDATE planillas.hojaasistencia_emp_dia 
                 SET hoae_id = 0, 
                     platica_id = 0
                 WHERE hoae_id = ?  ";   
    
        $this->_CI->db->query($sql, array($detalle) ); 

        $sql ="  DELETE FROM planillas.hojaasistencia_emp  WHERE hoae_id  = ?";     
        $this->_CI->db->query($sql, array($detalle) );   
      



        if($this->_CI->db->trans_status() === FALSE) 
        {
            $this->_CI->db->trans_rollback();
            return false;
            
        }
        else{
                
            $this->_CI->db->trans_commit();
            return true;
        } 
    }

    public function actualizar_categoria($params = array())
    {
  
        $this->_CI->db->trans_begin();

        $sql =" SELECT indiv_id, hoa_id
                FROM planillas.hojaasistencia_emp 
                WHERE hoae_id = ? LIMIT 1 "; 
        
        
        list($rs) = $this->_CI->db->query($sql, array( $params['detalle'] ))->result_array();
       
        $indiv_id = $rs['indiv_id'];
        $hoja_id = $rs['hoa_id'];

        // Comprobar de que no haya registros importados

        $sql_importados = "  SELECT  dia.hoae_id, SUM(hoaed_importado) as registros_importados 
                             FROM planillas.hojaasistencia_emp_dia dia 
                             WHERE dia.hoaed_estado = 1 AND dia.hoae_id = ? 
                             GROUP BY dia.hoae_id  ";
        list($rs) = $this->_CI->db->query($sql_importados, array( $params['detalle'] ))->result_array();   

        if( ($rs['registros_importados']*1) > 0 )
        {
            return false;
        }


        $sql = " SELECT hoae_id 
                 FROM planillas.hojaasistencia_emp 
                 WHERE hoa_id = ? AND  indiv_id = ? AND platica_id = ? AND hoae_estado = 1 
                 LIMIT 1 ";
       
        $rs = $this->_CI->db->query($sql, array( $hoja_id, $indiv_id , $params['nuevacategoria'] ))->result_array();

        $hoae_id_existe = trim($rs[0]['hoae_id']);

        if($hoae_id_existe == '')
        {
 
            $sql = " UPDATE planillas.hojaasistencia_emp 
                     SET    platica_id = ?  
                     WHERE  hoae_id = ? AND platica_id = ?
                   ";

            $this->_CI->db->query($sql, array( $params['nuevacategoria'], $params['detalle'], $params['categoria'] ) );

            $sql = " UPDATE planillas.hojaasistencia_emp_dia
                     SET    platica_id = ?  
                     WHERE  hoae_id = ? AND platica_id = ? 
                   ";

            $this->_CI->db->query($sql, array( $params['nuevacategoria'], $params['detalle'], $params['categoria'] ) );

       }
       else
       {


           $sql = " UPDATE planillas.hojaasistencia_emp_dia
                    SET    hoae_id = ?  
                    WHERE  hoae_id = ?  
                  ";

           $this->_CI->db->query($sql, array( $hoae_id_existe, $params['detalle']  ) );


           $sql = " DELETE FROM planillas.hojaasistencia_emp WHERE hoae_id = ?  ";
           $this->_CI->db->query($sql, array(  $params['detalle']  ) );

       }


        if($this->_CI->db->trans_status() === FALSE) 
        {
            $this->_CI->db->trans_rollback();
            return false;
            
        }
        else{
                
            $this->_CI->db->trans_commit();
            return true;
        } 

    }

}