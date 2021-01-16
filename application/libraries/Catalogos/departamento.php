<?php

class departamento extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'dpto_id',
                                    'code'  => '',
                                    'name'  => 'dpto_nombre',
                                    'descripcion' => '',
                                    'state' => 'dpto_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'departamento';
    protected $_PREF_TABLE= 'DEPARMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
      
    
    
}



?>
