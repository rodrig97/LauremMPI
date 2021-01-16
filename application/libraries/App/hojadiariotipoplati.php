<?php

class hojadiariotipoplati extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'htp_id',
                                    'code'  => 'htp_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'htp_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojadiario_tipo_plati';
    protected $_PREF_TABLE= 'hojadiaplati'; 
    
    protected $sql_base =  "";
     
    public function __construct()
    {          
        parent::__construct();
      
    }

    public function registrar($values = array(), $return_id = false)
    {
        $sql = " UPDATE  planillas.hojadiario_tipo_plati 
                 SET htp_estado = 0, htp_fecha_update = now() 
                 WHERE plati_id = ? AND hatd_id = ? AND htp_estado = 1 ";

        $this->_CI->db->query($sql, array($values['plati_id'], $values['hatd_id']));         

       return parent::registrar($values, $return_id);
    }

    public function get( $params = array() )
    {

        $plati_id = trim($params['plati_id']) != '' ? trim($params['plati_id']) : '0';
        $hatd_id = trim($params['hatd_id']) != '' ? trim($params['hatd_id']) : '0';

        $sql = " SELECT * FROM planillas.hojadiario_tipo_plati 
                 WHERE plati_id = ? AND hatd_id = ? AND htp_estado = 1  LIMIT 1";

        $rs = $this->_CI->db->query($sql, array($plati_id, $hatd_id))->result_array();

        return $rs[0];

    }
 
}