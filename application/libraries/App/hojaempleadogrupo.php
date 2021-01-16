<?php

class hojaempleadogrupo extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hoaeg_id',
                                    'code'  => 'hoaeg_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hoaeg_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planillas.hojaasistencia_emp_grupos';
    protected $_PREF_TABLE= 'hojagrupo'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }

 
}
