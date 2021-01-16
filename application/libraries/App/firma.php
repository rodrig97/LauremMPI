<?php

class firma extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'firma_id',
                                    'code'  => 'firma_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'firma_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'firmas';
    protected $_PREF_TABLE= 'FIRMA'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }

    public function get_actual($params = array())
    {

        $sql =" SELECT * FROM planillas.firmas WHERE firma_actual = 1 LIMIT 1 ";
        
        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs[0];
    }
}