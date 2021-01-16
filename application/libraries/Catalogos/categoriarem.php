<?php

class categoriarem extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'catre_id',
                                    'code'  => 'catre_key',
                                    'name'  => 'catre_nombre',
                                    'descripcion' => '',
                                    'state' => 'catre_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'cat_remunerativa';
    protected $_PREF_TABLE= 'categoriasrem'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

}