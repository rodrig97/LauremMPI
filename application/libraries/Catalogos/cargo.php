<?php

class cargo extends Table{
    
       
     
    protected $_FIELDS=array(   
                                   /* 'id'    => 'codi',
                                    'code'  => 'depar_key',
                                    'name'  => 'deta',
                                    'descripcion' => '',
                                    'state' => 'depar_estado'*/
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'cargo';
    protected $_PREF_TABLE= 'CARGOMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_list(){
          
        $sql = "SELECT cargo_id as codi, 
                       cargo_nombre as deta,
                       cargo.* 
                FROM public.cargo 
                WHERE cargo_estado = '1' 
                ORDER BY cargo_id asc ";
        $rs =  $this->_CI->db->query($sql)->result_array();
        return $rs;
        
    }
    
    
    
}



?>
