<?php

class categorialaboral extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'catemp_id',
                                    'code'  => 'catemp_key',
                                    'name'  => 'catemp_nombre',
                                    'descripcion' => '',
                                    'state' => 'catemp_estado' 
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'categorias';
    protected $_PREF_TABLE= 'MPICATEGO'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_list(){
          
        $sql = 'SELECT * FROM rh.categorias WHERE catemp_estado = 1 ORDER BY catemp_nombre ';
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
        
    }
    
    
    
}



?>
