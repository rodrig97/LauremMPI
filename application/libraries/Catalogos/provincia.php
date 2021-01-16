<?php

class provincia extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'provi_id',
                                    'code'  => 'provi_key',
                                    'name'  => 'provi_nombre',
                                    'descripcion' => '',
                                    'state' => 'provi_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'provincia';
    protected $_PREF_TABLE= 'PROVIMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
      
    
    
}



?>
