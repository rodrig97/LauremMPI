<?php

class permisodestino extends Table{
     
    protected $_FIELDS=array(   
                                    'id'          => 'perde_id',
                                    'code'        => 'perde_key',
                                    'name'        => 'perde_nombre',
                                    'descripcion' => '',
                                    'state'       => 'perde_estado'
                            );
    
    protected $_SCHEMA     = 'rh';
    protected $_TABLE      = 'permiso_destino';
    protected $_PREF_TABLE = 'PERDEST'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }


    public function get_list()
    {
        $sql = " SELECT * FROM rh.permiso_destino WHERE perde_estado = 1 ORDER BY perde_nombre ";

        return $this->_CI->db->query($sql, array())->result_array();
        
    }


}