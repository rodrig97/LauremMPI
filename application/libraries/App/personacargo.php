<?php

class personacargo extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'pecar_id',
                                    'code'  => 'pecar_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pecar_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_cargo';
    protected $_PREF_TABLE= 'perscargo'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

}