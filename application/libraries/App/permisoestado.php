<?php

class permisoestado extends Table{
     
    protected $_FIELDS=array(    
                                    'id'          => 'ppest_id',
                                    'code'        => 'ppest_key',
                                    'name'        => 'ppest_nombre',
                                    'descripcion' => '',
                                    'state'       => 'ppest_estado'
                            );
    
    protected $_SCHEMA     = 'rh';
    protected $_TABLE      = 'persona_permiso_estado';
    protected $_PREF_TABLE = 'PEREST'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }


    public function get_list( $params = array() )
    {
        $sql = " SELECT * FROM rh.persona_permiso_estado WHERE ppest_estado = 1  ";

        if($params['panel'] == '1')
        {
            $sql.=" AND ppest_panel = 1 ";
        }

        $sql.=" ORDER BY ppest_id ";

        return $this->_CI->db->query($sql, array())->result_array();
        
    }


}