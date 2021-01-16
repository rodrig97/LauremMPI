<?php

class trabajadordia extends Table{
    
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


    public function get( $params = array() ){
        
        $sql = " SELECT * FROM planillas.hojaasistencia_emp_dia WHERE indiv_id = ? AND hoaed_fecha = ? AND hoaed_estado = 1 LIMIT 1";

        list($rs) = $this->_CI->db->query($sql, array($params['indiv_id'], $params['fecha']) )->result_array();

        return $rs;
    }

    public function registrar($params = array() ){

        $registro_actual = $this->get(array('indiv_id' => $params['indiv_id'], 'fecha' => $params['fecha'] ));

        if( sizeof($registro_actual) > 0 ) {

            $params['detalle_id'] = $registro_actual['hoaed_id'];
            $ok = $this->actualizar_existente($params);
        
        } else {

            $ok = $this->registrar_nuevo($params);
        }

        return $ok;

    }

    public function getDefaultConfig( $paramsMethod = array() ){
 
        $indivId = $paramsMethod['indiv_id'];
        $fechaAsistencia = $paramsMethod['fecha'];

        $sql = " SELECT  EXTRACT('dow' FROM(?::date)) as dia, 
                         plati_horario.hor_id, 
                         plati_estado_dia.platide_laborable, 
                         plati_estado_dia.hatd_id,
                         hor.hor_alias,
                         hor.hor_hora1_e,
                         hor.hor_hora1_s 

                 FROM public.individuo indiv 
                 LEFT JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                 LEFT JOIN  planillas.planillatipo_dia_horario plati_horario ON plati_horario.plati_id = persla.plati_id AND plati_horario.dia_id  = EXTRACT('dow' FROM(?::date)) AND platidh_estado = 1
                 LEFT JOIN planillas.planillatipo_dia_estado plati_estado_dia ON plati_estado_dia.plati_id = persla.plati_id AND plati_estado_dia.dia_id  = EXTRACT('dow' FROM(?::date)) AND platide_estado = 1
                 LEFT JOIN planillas.hojaasistencia_horarios hor On plati_horario.hor_id = hor.hor_id 
                 WHERE indiv.indiv_id = ?
                 LIMIT 1  ";

        list($rsDiaInfo) = $this->_CI->db->query($sql, array( $fechaAsistencia, $fechaAsistencia, $fechaAsistencia, $indivId ) )->result_array();
        
        return $rsDiaInfo;
    }

    public function registrar_nuevo( $paramsMethod = array() ){

       
        $indivId = $paramsMethod['indiv_id'];
        $fechaAsistencia = $paramsMethod['fecha'];
        
        $rsDiaInfo = $this->getDefaultConfig( array('indiv_id' => $indivId, 'fecha' => $fechaAsistencia));
 

        $horarioId      = ( trim($paramsMethod['horario_id']) == '') ? $rsDiaInfo['hor_id'] : $paramsMethod['horario_id'];
        $esDiaLaborable = ( trim($paramsMethod['dia_laborable']) == '') ? $rsDiaInfo['platide_laborable'] : $paramsMethod['dia_laborable'];  
        $estadoDelDia   = ( trim($paramsMethod['estado_dia']) == '') ? $rsDiaInfo['hatd_id'] : $paramsMethod['estado_dia']; 

        $observacionDia = $paramsMethod['observacion']; 

        $valuesToSql = array($esDiaLaborable, $estadoDelDia, $fechaAsistencia, $indivId, $horarioId);
 
        if( trim($paramsMethod['hoae_hora1_e']) != '') {

            $sqlFieldsToInsert.= ' hoae_hora1_e, ';
            $sqlParamsQuery.= ' ?, ';
            $valuesToSql[] = trim($paramsMethod['hoae_hora1_e']);
        }

        if( trim($paramsMethod['hoae_hora1_s']) != ''){
            $sqlFieldsToInsert.= ' hoae_hora1_s, ';
            $sqlParamsQuery.= ' ?, ';
            $valuesToSql[] = trim($paramsMethod['hoae_hora1_s']);
        }

        $valuesToSql[] = $observacionDia;

        $sql = " INSERT INTO planillas.hojaasistencia_emp_dia( hoaed_laborable, hatd_id, hoaed_fecha, 
                                                               indiv_id, hor_id, ".$sqlFieldsToInsert." hoaed_obs ) 

                 VALUES( ?, ?, ?, 
                         ?, ?, ".$sqlParamsQuery." ? ) ";
 
        return ($this->_CI->db->query($sql, $valuesToSql ) ? true : false);

    }

    public function actualizar_existente( $params = array() ){


        $values = array();  

        if( trim($params['estado_dia']) != ''){ 
        
            $sql_e = "  hatd_id = ?, ";
            $values[] = trim($params['estado_dia']); 
        }

        
        if( trim($params['hoae_hora1_e']) != '')
        {
            $sql_e.= ' hoae_hora1_e = ?, '; 
            $values[] = trim($params['hoae_hora1_e']);
        }
        else {

            $sql_e.= ' hoae_hora1_e = null, ';
        }


        if( trim($params['hoae_hora1_s']) != '')
        {
            $sql_e.= ' hoae_hora1_s = ?, '; 
            $values[] = trim($params['hoae_hora1_s']);
        }
        else {

            $sql_e.= ' hoae_hora1_s = null, ';
        }

 
        $sql_e.= ' hoaed_obs = ?, '; 
        $values[] = trim($params['observacion']);
       
        
         $sql = " UPDATE planillas.hojaasistencia_emp_dia
                  SET 
                      ".$sql_e."
                      hoaed_fechaupdate = now()  

                  WHERE hoaed_id = ? "; 
 
         $values[] = $params['detalle_id']; 

        
         $this->_CI->db->query($sql, $values );

 
         return ($this->_CI->db->query($sql, $values ) ? true : false);

    }


}