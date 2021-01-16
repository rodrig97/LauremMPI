<?php

class ocupacionfamiliar extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'ocupa_id',
                                    'code'  => 'ocupa_key',
                                    'name'  => 'ocupa_nombre',
                                    'descripcion' => '',
                                    'state' => 'ocupa_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'ocupaciones';
    protected $_PREF_TABLE= 'ocupasmpi'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
    
}
 
