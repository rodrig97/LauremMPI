<?php

 
class brevet extends Table{
     
   protected $_FIELDS=array(   
                                    'id'    => 'brevet_id',
                                    'code'  => 'brevet_key',
                                    'name'  => 'brevet_nombre',
                                    'descripcion' => '',
                                    'state' => 'brevet_estado'
                            );
    
    protected $_SCHEMA = 'public';
    protected $_TABLE = 'tipo_brevets';
    protected $_PREF_TABLE= 'BREVETEMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
     
}
