<?php

class reporte extends Table{
    
    protected $_FIELDS=array(   
                                    'id'          => 'rep_id',
                                    'code'        => 'rep_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'rep_estado'
                            );
    
    protected $_SCHEMA     = 'planillas';
    protected $_TABLE      = 'reportes';
    protected $_PREF_TABLE = 'reporte'; 
     
    public function __construct(){
          
        parent::__construct();
          
    }
     
}