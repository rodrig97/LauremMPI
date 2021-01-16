<?php

class planillaasistencia extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'plaasis_id',
                                    'code'  => 'plaasis_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plaasis_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_asistencia_importacion';
    protected $_PREF_TABLE= 'plaxioasis'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    } 


    public function importar( $params = array() )
    {
        /*
           @Nota : Parte de esta consulta SQL se repite en hojaasistencia.get_asistencias_importar
        */ 


        $this->_CI->db->trans_begin();


        $this->_CI->load->library(array('App/hojaasistencia', 'App/planillaempleadovariable', 'App/planillaempleado', 'App/variable_metodos'));
          
        $pla_id = $params['planilla'];

        $in_hojas = implode(',', $params['hojas']);


        $fecha_ini = $params['fecha_inicio'];
        $fecha_fin = $params['fecha_fin'];

        $sql_tra = " ";
        foreach($params['trabajadores'] as $i => $tra)
        {   
            if($i > 0) $sql_tra.=" , ";
            $sql_tra.=" ( ".$tra[0].",".$tra[1].") ";
        }

        $sql_trabajadores_seleccionados = "  SELECT  column1 as indiv_id, column2 as platica_id  
                                             FROM ( VALUES ".$sql_tra." ) as trabajadores_seleccionados  ";

 
        $verif_hora1_e =  " (CASE WHEN hoae_hora1_e < hor.hor_hora1_e THEN
                               hor.hor_hora1_e
                             ELSE 
                               hoae_hora1_e
                             END )  ";


        $verif_hora1_s =  " (CASE WHEN hoae_hora1_s > hor.hor_hora1_s THEN
                               hor.hor_hora1_s
                          ELSE 
                               hoae_hora1_s
                          END )  ";


        $sql_calculo_horas_trabajadas = "COALESCE( EXTRACT( 'hour' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                        (COALESCE( EXTRACT( 'minutes' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";



        $sql_importar =  " SELECT  datos.*, plaemp.plaemp_id,  vari.vari_displayprint

                           FROM (  

                                SELECT hoae.indiv_id, hoae.platica_id, htp.hatd_id, htp.vari_id, htp.htp_metodo_mascara,

                                        SUM( CASE WHEN htp.htp_importar_horas = 1 THEN 

                                                    ( CASE WHEN ( ".$sql_calculo_horas_trabajadas." ) is null  OR ( hoae_hora1_e is null OR hoae_hora1_s is null ) THEN  
                                                        0  
                                                    ELSE  
                                                        (".$sql_calculo_horas_trabajadas." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                                                    END   )  
                                              
                                              WHEN htp.htp_importar_horas = 2 THEN 
                                              
                                                       (  COALESCE( EXTRACT( 'hour' FROM ( hor.hor_hora1_s - hor.hor_hora1_e ) ) , 0) + 
                                                          (COALESCE( EXTRACT( 'minutes' FROM ( hor.hor_hora1_s - hor.hor_hora1_e ) ) , 0) / 60 )  - hor_descontar_horas
                                                        )
                                              
                                              WHEN htp.htp_importar_horas = 3 THEN 

                                                        (  COALESCE( EXTRACT( 'hour' FROM ( hor.hor_hora1_s2 - hor.hor_hora1_e2 ) ) , 0) + 
                                                           (COALESCE( EXTRACT( 'minutes' FROM ( hor.hor_hora1_s2 - hor.hor_hora1_e2 ) ) , 0) / 60 )  - hor_descontar_horas
                                                         )

                                              ELSE
                                                     1 

                                              END ) as total
            
                                  FROM planillas.hojaasistencia hoa  
                                  INNER JOIN  planillas.hojaasistencia_emp hoae ON hoa.hoa_id = hoae.hoa_id AND hoae.hoae_estado = 1 
                                  INNER JOIN  planillas.hojaasistencia_emp_dia hoaed ON hoae.hoae_id = hoaed.hoae_id AND hoaed.hoaed_estado = 1 AND hoaed_laborable = 1 AND hoaed_importado = 0
                                  INNER JOIN planillas.hojaasistencia_horarios hor ON hoaed.hor_id = hor.hor_id
                                  INNER JOIN  planillas.hojadiario_tipo_plati htp ON hoaed.hatd_id = htp.hatd_id AND htp.plati_id = hoa.plati_id AND htp_estado = 1 
                                  WHERE hoa.hoa_id IN(".$in_hojas.")  AND  ( hoaed.hoaed_fecha between '".$fecha_ini."' AND '".$fecha_fin."' ) AND htp.vari_id != 0 
                                  GROUP BY hoae.indiv_id, hoae.platica_id, htp.hatd_id, vari_id,htp.htp_metodo_mascara 
                                  ORDER BY hoae.indiv_id, hoae.platica_id, htp.hatd_id, htp.vari_id

                          ) as datos 
                          INNER JOIN (".$sql_trabajadores_seleccionados." ) as trabajadores_seleccionados ON  datos.indiv_id = trabajadores_seleccionados.indiv_id AND datos.platica_id = trabajadores_seleccionados.platica_id 
                          LEFT JOIN planillas.planilla_empleados plaemp ON datos.indiv_id = plaemp.indiv_id AND datos.platica_id = plaemp.platica_id AND plaemp_estado = 1 AND plaemp.pla_id = ?
                          LEFT JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 
                          LEFT JOIN planillas.variables vari ON datos.vari_id = vari.vari_id  


                        ";

        $rs_importar = $this->_CI->db->query($sql_importar, array($pla_id))->result_array();
        
         $total_registros = sizeof($rs_importar);

         foreach($rs_importar as $reg)
         {
              if( trim($reg['plaemp_id']) == '')
              {
                  
                 list($plaemp_id, $plaemp_key) = $this->_CI->planillaempleado->registrar( $pla_id, $reg['indiv_id'] , $reg['platica_id'], TRUE, $params_extra );  

              }
              else
              {
                  $plaemp_id = trim($reg['plaemp_id']);
              }

              $valor = $reg['total'];

              if(trim($reg['htp_metodo_mascara']) != '' && trim($reg['htp_metodo_mascara']) != '0')
              {
                   $valor = $reg['total'];  
                   $parametros = array();

                   $valor =  call_user_func_array( array($this->_CI->variable_metodos, $reg['htp_metodo_mascara'] ), 
                                                                      array($parametros) );
              }

              $ok =  $this->_CI->planillaempleadovariable->registrar( array(
                                                                             'plaemp_id'          => $plaemp_id,
                                                                             'vari_id'            => $reg['vari_id'],
                                                                             'plaev_valor'        => $valor,
                                                                             'plaev_procedencia'  => PROCENDENCIA_VARIABLE_HOJAASISTENCIA,
                                                                             'plaev_displayprint' => $reg['vari_displayprint'] 
                                                                            ), false, array(), true);
         }  


         $memo_sistema = substr(('Hojas de asistencia: '.$in_hojas.' entre '.$fecha_ini.' y el '.$fecha_fin.' un total de '.$total_registros.' registros'), 0,99 );

        list($plaasis_id, $plaasis_key) =  $this->registrar(array('pla_id' => $pla_id, 
                                                                  'user_id' => $params['user_id'],
                                                                  'plaasis_memo' => $memo_sistema                  
                                                                  ), true );
 
         $sql = " UPDATE   planillas.hojaasistencia_emp_dia hoaed
                  SET plaemp_id = plaemp.plaemp_id , pla_id = plaemp.pla_id, plaasis_id = ".$plaasis_id.", hoaed_importado = 1 
                  FROM planillas.hojaasistencia hoa, 
                       planillas.hojaasistencia_emp hoae,
                       (".$sql_trabajadores_seleccionados." ) as trabajadores_seleccionados,
                       planillas.planilla_empleados plaemp 

                  WHERE hoaed.hoaed_estado = 1 AND 
                        hoaed.hoae_id = hoae.hoae_id AND 
                        hoa.hoa_id = hoae.hoa_id AND 
                        hoae.hoae_estado = 1 AND 
                        trabajadores_seleccionados.indiv_id = hoae.indiv_id AND 
                        trabajadores_seleccionados.platica_id = hoae.platica_id AND 
                        plaemp.indiv_id = hoae.indiv_id AND 
                        plaemp.platica_id = hoae.platica_id AND plaemp.pla_id = ".$pla_id." AND 

                        hoa.hoa_id IN(".$in_hojas.")  AND  ( hoaed.hoaed_fecha between '".$fecha_ini."' AND '".$fecha_fin."' )

                ";

          $this->_CI->db->query($sql, array()); 
  

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


    public function revertir($plaasis_id)
    { 

        $this->_CI->db->trans_begin();

        $sql = " UPDATE  planillas.hojaasistencia_emp_dia  
                 SET plaemp_id = 0, 
                     pla_id = 0, 
                     plaasis_id = 0, 
                     hoaed_importado = 0
                  
                 WHERE plaasis_id = ? 

               ";

        $ok = $this->_CI->db->query($sql, array($plaasis_id)); 


        $sql =" UPDATE planillas.planilla_asistencia_importacion SET plaasis_descripcion = plaasis_descripcion || ' [IMPORTACION REVERTIDA]', plaasis_estado = 0 WHERE plaasis_id = ?  ";

        $ok = $this->_CI->db->query($sql, array($plaasis_id)); 

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

    public function get( $pla_id = 0 )
    {
        $sql = " SELECT pai.*, 
                       ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres  ) as usuario
                 FROM planillas.planilla_asistencia_importacion  pai
                 LEFT JOIN public.usuario usur ON pai.user_id = usur.usur_id 
                 LEFT JOIN public.individuo indiv ON usur.indiv_id = indiv.indiv_id 
                 WHERE plaasis_estado = 1 AND pla_id = ?    

                ORDER BY plaasis_fecreg;
              ";
     
       $rs = $this->_CI->db->query($sql, array($pla_id) )->result_array();
      
       return $rs;
    }


}