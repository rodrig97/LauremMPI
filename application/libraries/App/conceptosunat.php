<?php

 
class conceptosunat extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'cosu_id',
                                    'code'  => 'cosu_key',
                                    'name'  => 'cosu_descripcion',
                                    'descripcion' => '',
                                    'state' => 'cosu_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'conceptos_sunat';
    protected $_PREF_TABLE= 'COSUNAT'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function get_list()
    {
            $sql  = " SELECT * FROM planillas.conceptos_sunat WHERE cosu_estado =  1 ORDER BY cosu_codigo, cosu_descripcion";
            $rs = $this->_CI->db->query($sql,array())->result_array();

            return $rs;
    }

   // public function 
 
}
