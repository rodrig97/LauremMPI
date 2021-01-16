<?php

class dependencia extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'area_id',
                                    'code'  => 'area_key',
                                    'name'  => 'area_nombre',
                                    'descripcion' => '',
                                    'state' => 'area_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'area';
    protected $_PREF_TABLE= 'DEPARMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_list(){
          
        $sql = "Select * from public.area where trim(area_nombre) != '' order by area_nombre";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
        
    }
    
    
    
}



?>
