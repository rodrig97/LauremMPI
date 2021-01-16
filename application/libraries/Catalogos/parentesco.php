<?php

class parentesco extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'paren_id',
                                    'code'  => 'paren_key',
                                    'name'  => 'paren_nombre',
                                    'descripcion' => '',
                                    'state' => 'paren_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'parentescos';
    protected $_PREF_TABLE= 'parentescompi'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
    
}
 
