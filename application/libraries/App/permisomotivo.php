<?php

class permisomotivo extends Table{
     
    protected $_FIELDS=array(   
                                    'id'          => 'permot_id',
                                    'code'        => 'permot_key',
                                    'name'        => 'permot_nombre',
                                    'descripcion' => '',
                                    'state'       => 'permot_estado'
                            );
    
    protected $_SCHEMA     = 'rh';
    protected $_TABLE      = 'permiso_motivo';
    protected $_PREF_TABLE = 'PERMOT'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }


    public function get_list()
    {
        $sql = " SELECT * FROM rh.permiso_motivo 
                 WHERE permot_estado = 1 
                 ORDER BY permot_orden ";

        return $this->_CI->db->query($sql, array())->result_array();
 

    }
}