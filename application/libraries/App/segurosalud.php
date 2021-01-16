<?php

 /*
    YA NO SE USA
*/ 
class segurosalud extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'persa_id',
                                    'code'  => 'persa_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'persa_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_essalud';
    protected $_PREF_TABLE= 'IPSSS'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
}
