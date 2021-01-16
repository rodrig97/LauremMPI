<?php

class usuario extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'pla_id',
                                    'code'  => 'pla_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pla_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planillas';
    protected $_PREF_TABLE= 'PLANILLA'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
 

}