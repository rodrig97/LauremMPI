<?php

class pais extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'pais_id',
                                    'code'  => 'pais_key',
                                    'name'  => 'pais_nombre',
                                    'descripcion' => '',
                                    'state' => 'pais_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'pais';
    protected $_PREF_TABLE= 'PAISMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
      
    
    
}



?>
