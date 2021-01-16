<?php

 
class proyecto extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'afp_id',
                                    'code'  => 'afp_key',
                                    'name'  => 'afp_nombre',
                                    'descripcion' => '',
                                    'state' => 'afp_estado'
                            );
    
    protected $_SCHEMA = 'pip';
    protected $_TABLE = 'meta';
    protected $_PREF_TABLE= 'METAPIP'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
}
