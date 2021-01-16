<?php

class grupoempleado extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'gremp_id',
                                    'code'  => 'gremp_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'gremp_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'grupo_empleado';
    protected $_PREF_TABLE= 'gruemp'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
 
    public function get_list()
    {
        $sql = " SELECT * FROM planillas.grupo_empleado
                 WHERE gremp_estado = 1 
                 ORDER BY gremp_nombre 
               ";

        $rs = $this->_CI->db->query($sql, array($sql))->result_array();

        return $rs; 
    }

    

 }