<?php

class hojaempleadodiario extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hoaed_id',
                                    'code'  => 'hoaed_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hoaed_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia_emp_dia';
    protected $_PREF_TABLE= 'hojadiario'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


    public function get_info_dia($hoja_id , $indiv_id, $fecha )
    {

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
 
          
          $horas_trabajadas_doble_horario = ' hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e ';
          $tardanzas_doble_horario        = ' hoae_hora1_e - hor_hora1_e  + hoae_hora2_e - hor_hora2_e  ';

           $sql = " SELECT dia.*, 
                           hor.*,
                           platica.platica_nombre,
                           htp.htp_registrar_marcacion_horas,
                           ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id ) as planilla_importado,
                           ht.hatd_nombre,
                           (CASE WHEN hor.hor_numero_horarios = 2 THEN  EXTRACT( 'hour' FROM (".$horas_trabajadas_doble_horario.") ) || '_' ||  EXTRACT( 'minutes' FROM (".$horas_trabajadas_doble_horario.")  ) ELSE  EXTRACT( 'hour' FROM (hoae_hora1_s - hoae_hora1_e ) ) || '_' ||  EXTRACT( 'minutes' FROM (hoae_hora1_s - hoae_hora1_e ) )  END ) as asistencia,
                          
                           (CASE WHEN hor.hor_numero_horarios = 2 THEN  EXTRACT( 'hour' FROM (".$tardanzas_doble_horario.") ) || '_' ||  EXTRACT( 'minutes' FROM (".$tardanzas_doble_horario.") ) ELSE  EXTRACT( 'hour' FROM ( hoae_hora1_e - hor_hora1_e  ) ) || '_' ||  EXTRACT( 'minutes' FROM ( hoae_hora1_e - hor_hora1_e  ) )  END ) as tardanzas,

                          ( CASE WHEN ( ".$sql_calculo_horas_trabajadas." ) is null OR ( hoae_hora1_e is null OR hoae_hora1_s is null ) THEN  
                              0  
                          ELSE  
                              ROUND( (".$sql_calculo_horas_trabajadas." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )::numeric , 2 )
                          END   )  as horas_contabilizadas,

                          plaasis.plaasis_fecreg,

                          COALESCE(  permisos.minutos  , '0')  as permisos

                    
                    FROM planillas.hojaasistencia_emp_dia dia  
                    LEFT JOIN planillas.hojaasistencia_emp det ON dia.hoae_id = det.hoae_id 
                    LEFT JOIN planillas.hojaasistencia hoa ON det.hoa_id = hoa.hoa_id 
                    LEFT JOIN planillas.planilla_tipo_categoria platica ON dia.platica_id = platica.platica_id 
                    LEFT JOIN planillas.planillas pla ON dia.pla_id = pla.pla_id 
                    LEFT JOIN planillas.hojadiario_tipo ht ON dia.hatd_id = ht.hatd_id 
                    LEFT JOIN planillas.hojadiario_tipo_plati htp ON hoa.plati_id = htp.plati_id AND ht.hatd_id = htp.hatd_id 
                    LEFT JOIN planillas.hojaasistencia_horarios hor On dia.hor_id = hor.hor_id 
                    LEFT JOIN planillas.planilla_asistencia_importacion plaasis ON dia.plaasis_id = plaasis.plaasis_id 
                    LEFT JOIN ( 
 
                          SELECT pp.pers_id as indiv_id, pepe_fechadesde as fecha,  SUM(( EXTRACT( 'hour' FROM (pepe_horafin - pepe_horaini ) ) * 60 ) + EXTRACT( 'minutes' FROM (pepe_horafin - pepe_horaini ) ) ) as minutos
                          FROM rh.persona_permiso pp 
                          INNER JOIN rh.persona_permiso_movimiento ppm ON pp.pepe_id = ppm.pepe_id AND ppm.pepem_estado = 1 AND ppm.ppest_id >= 3 
                          WHERE pp.pepe_estado = 1 AND pp.pers_id = ? AND pp.pepe_fechadesde  = ? 
                          GROUP BY indiv_id, pepe_fechadesde 

                     ) as permisos  ON  det.indiv_id = permisos.indiv_id AND dia.hoaed_fecha = permisos.fecha

                    WHERE hoaed_estado = 1 ";

           $values = array($indiv_id,$fecha);
                    
            if($hoja_id != '0')
            {
                 $sql.="  AND det.hoa_id = ?  ";
                 $values[] = $hoja_id;
            }
      
           $sql.=" AND dia.indiv_id = ? AND dia.hoaed_fecha = ? 
                          LIMIT 1 ";

          $values[] = $indiv_id;
          $values[] = $fecha;

           $rs = $this->_CI->db->query($sql,  $values )->result_array();
            
           return $rs[0];    
    
    } 


    public function actualizar_info_dia($hoja_id, $indiv_id, $fecha, $params = array() )
    {
  
        $this->_CI->load->library(array('App/hojaasistencia'));

        $sql =" SELECT hoa.plati_id, det.* 
                FROM planillas.hojaasistencia hoa 
                LEFT JOIN planillas.hojaasistencia_emp det ON hoa.hoa_id = det.hoa_id 
                WHERE hoa.hoa_id = ? AND indiv_id = ? AND platica_id = ? AND hoae_estado = 1 LIMIT 1";
  
        list($rs) = $this->_CI->db->query($sql, array($hoja_id,$indiv_id, $params['categoria'] ))->result_array();

        $cambio_detalle = false;

        $hoae_id = $rs['hoae_id'];
        $plati_id = $rs['plati_id'];

        $config = $this->_CI->hojaasistencia->get_plati_config($plati_id);

        // Si no existe un registro del trabajador en la hoja con esa categoria 
        if( $hoae_id == 0 )
        {
            $sql = " INSERT INTO planillas.hojaasistencia_emp(hoa_id, indiv_id, platica_id) VALUES(?,?,?) ";
            $this->_CI->db->query($sql, array($hoja_id,$indiv_id, $params['categoria'] ));
            $hoae_id=$this->_CI->db->insert_id();

            $sql =" UPDATE planillas.hojaasistencia_emp SET hoae_key = ?  WHERE hoae_id = ?";
            $this->_CI->db->query($sql, array( md5($hoae_id.'hojaxz13x7'), $hoae_id ) );

            $cambio_detalle = true;
        }
        
        // Buscamos la información del dia

        $sql = " SELECT det.hoae_id, dia.hoaed_id, hor.*  
                 FROM  planillas.hojaasistencia_emp det 
                 LEFT JOIN  planillas.hojaasistencia_emp_dia dia  ON det.hoae_id = dia.hoae_id 
                 LEFT JOIN planillas.hojaasistencia_horarios hor ON dia.hor_id = hor.hor_id 
                 WHERE det.indiv_id = ? AND dia.hoaed_fecha = ? AND hoaed_estado = 1 LIMIT 1  ";

        $rs = $this->_CI->db->query($sql, array( $indiv_id, $fecha ) )->result_array();
        $horario_dia = $rs[0];

        $hoaed_id = trim($rs[0]['hoaed_id']);
         
        // Sino se encuentra datos por ese dia
        if( $hoaed_id == '')
        {
              
            // obtenemos los datos del horario de trabajo
            $sql = " SELECT hor.* 
                     FROM planillas.individuo_dia_horario idh
                     LEFT JOIN planillas.hojaasistencia_horarios hor ON idh.hor_id = hor.hor_id 
                     WHERE idh.indiv_id = ? AND idh.dia_id = EXTRACT( 'dow' FROM(?::date))
                     LIMIT 1  ";

            list($horario_dia) = $this->_CI->db->query($sql, array($indiv_id, $fecha ) )->result_array();

            // Creamos el registro del dia 
            $sql = " INSERT INTO  planillas.hojaasistencia_emp_dia(hoae_id, hoa_id, indiv_id, platica_id, hoaed_fecha, hatd_id, hor_id ) 
                     VALUES(?,?,?,?,?,?,?) "; 

            $this->_CI->db->query($sql, array($hoae_id, $hoja_id, $indiv_id, $reg['categoria'], $fecha, '0', $horario_dia['hor_id'] ));
            $hoaed_id =$this->_CI->db->insert_id();
            $sql =" UPDATE   planillas.hojaasistencia_emp_dia SET hoaed_key = ?  WHERE hoaed_id = ? ";
            $this->_CI->db->query($sql, array( md5($hoaed_id.'detallediariohojaxxs'), $hoaed_id ) ); 
 
        } 
 
        // preparamos los datos a registrar por el dia 
        $values = array('hatd_id'     => $params['tipo'],
                        'platica_id'  => $params['categoria'],
                        'hoaed_obs'   => str_replace("'", "", trim($params['observacion']))
                        );

        if($cambio_detalle)
        {

           
        }

        $values['hoae_id'] = $hoae_id;
         
        // buscar los tipos de registro de los cuales se espera registren horas

        $sql = " SELECT hatd_id 
                 FROM planillas.hojadiario_tipo_plati 
                 WHERE htp_registrar_marcacion_horas = 1 AND plati_id = ? 
                 ORDER BY hatd_id ";

        $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();

        $tipos_p = array();

        foreach ($rs as $reg)
        {
            $tipos_p[] = $reg['hatd_id'];
        }


 
        if( in_array($params['tipo'], $tipos_p ) )
        {

          /*  $values['hoaed_tardanzas'] = ($params['tardanzas'] != '') ? $params['tardanzas'] :  '0';
             $values['hoaed_permisos']  =  ($params['permisos'] != '') ? $params['permisos'] :  '0'; */
            
            $marcaciones = array(
                                'hora1' => array(trim($params['hora1']), 'hoae_hora1_e', 'hor_hora1_e', '<' ),
                                'hora2' => array(trim($params['hora2']), 'hoae_hora1_s', 'hor_hora1_s', '>' ),
                                'hora3' => array(trim($params['hora3']), 'hoae_hora2_e', 'hor_hora2_e', '<' ),
                                'hora4' => array(trim($params['hora4']), 'hoae_hora2_s', 'hor_hora2_s', '>' )
                           );
 

            foreach ($marcaciones as $key => $dt)
            {
                if($dt[0]!='')
                {
                    $values[$dt[1]] = $dt[0];

                    if($config['ajustar_marcaciones_alhorario'] == '1') 
                    {

                      list($hora,$minutos,$segundos) = explode(':', substr($dt[0],1,5) );

                      list($hora_m,$minutos_m,$segundos_m) = explode(':',$horario_dia[$dt[2]] );
                      
                      if( trim($dt[3]) == '<')
                      {
                        if(  mktime($hora,$minutos,0,0,0,0) < mktime($hora_m,$minutos_m,0,0,0,0) )
                        {
                           $values[$dt[1]] = $horario_dia[$dt[2]];
                        }
                      }
                      else
                      {

                        if(  mktime($hora,$minutos,0,0,0,0) > mktime($hora_m,$minutos_m,0,0,0,0) )
                        {
                           $values[$dt[1]] = $horario_dia[$dt[2]];
                        }
                      }

                    }
                } 
                else
                {
                    $values[$dt[1]] =  array(' null ');
                }

            }
 
        }    


        $sql = " UPDATE  planillas.hojaasistencia_emp_dia 
                  SET hoaed_fechaupdate = now() 
               ";



        foreach ($values as $key => $value) {
            
            $sql.=" ,".$key." = ";
            if(is_array($value))
            {
               $sql.= $value[0]." "; 
            }
            else
            {
               $sql.= "'".$value."' ";
            }
        }

        $sql.=" WHERE hoaed_id = ".$hoaed_id;
        
      
        $rs = $this->_CI->db->query($sql, array());

        return ($rs) ? true : false;
       // return $this->actualizar( $hoaed_id, $values,false);
    }


    public function get_dia_id($indiv_id, $fecha )
    {

        

    }

    public function get_dia_horario_estado_defecto( $indiv_id, $fecha )
    {
          
          $sql =" SELECT  *,
                           ide.ide_laborable as hoaed_laborable   

                  FROM (   SELECT column1 as indiv_id 
                            FROM( VALUES ( ? ::integer) ) as t  ) as indiv  
                  LEFT JOIN planillas.individuo_dia_horario idh ON indiv.indiv_id = idh.indiv_id AND EXTRACT( 'dow' FROM( ?::date) ) = idh.dia_id    
                  LEFT JOIN planillas.hojaasistencia_horarios hoho2 ON idh.hor_id = hoho2.hor_id   
                  LEFT JOIN planillas.individuo_dia_estado ide ON indiv.indiv_id = ide.indiv_id AND EXTRACT( 'dow' FROM( ?::date) ) = ide.dia_id 
                  LIMIT 1  ";

           list($rs)  = $this->_CI->db->query($sql, array($indiv_id, $fecha, $fecha) )->result_array();
 
           return $rs;
    }


/*
    public function registrar($values, $return){


    //    $this->_CI->load->library(array('App/hojadiariodetalle'));

        list($n_id, $n_key) = parent::registrar($values, true);
        /*
        $v = array('hoaed_id' => $n_id,
                   'hatd_id' => '1',  /* TIPO ASISTENCIA
                   'hdd_value' => '1');

       $this->_CI->hojadiariodetalle->registrar($v, false);
     
       return ($return) ? array($n_id, $n_key) : true;

    }*/

}
