<?php

class datoparametrodestino extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hdpd_id',
                                    'code'  => 'hdpd_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hdpd_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia_dato_parametrodestino';
    protected $_PREF_TABLE= 'datoparametroxdestinoxx'; 
     
    public function __construct()
    {
        parent::__construct();
    }
 

    public function registrar($params = array()){

         $sql = " SELECT * FROM planillas.hojaasistencia_dato_parametrodestino 
                  WHERE hdpd_tipo = ? AND 
                        hdpd_tipo_id = ? AND  
                        plati_id = ? AND hdpd_estado = 1";

        list($rs) = $this->_CI->db->query($sql, array($params['hdpd_tipo'], $params['hdpd_tipo_id'], $params['plati_id']))->result_array();

        if(sizeof($rs) > 0 ){

           return $this->actualizar( $rs['hdpd_id'], $params, false);

        } else {
 
           return parent::registrar($params);
        }


    }

    public function get($params = array() ){

        $sql = " SELECT * 
                 FROM planillas.hojaasistencia_dato_parametrodestino 
                 WHERE hdpd_tipo = ?  AND hdpd_tipo_id = ? AND plati_id = ? LIMIT 1"; 

        list($rs) = $this->_CI->db->query($sql, array($params['tipo'], $params['id'], $params['plati_id']))->result_array();

        return $rs;
    }


    public function get_estaticos($params = array()){

        $sql = " SELECT * 
                 FROM planillas.hojaasistencia_dato_parametrodestino 
                 WHERE hdpd_estado = 1 AND hdpd_tipo = ".TAREO_TIPO_ESTATICO." AND plati_id = ? ";
 
        $rs = $this->_CI->db->query($sql, array($params['plati_id']))->result_array();

        $data = array();

        foreach ($rs as $reg) {
        
            $data[$reg['hdpd_tipo_id_key']] = $reg;
        }

        return $data;
    }

}