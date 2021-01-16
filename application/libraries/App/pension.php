<?php

 
class pension extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'peaf_id',
                                    'code'  => 'peaf_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'peaf_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_pension';
    protected $_PREF_TABLE= 'PENSION'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
}
