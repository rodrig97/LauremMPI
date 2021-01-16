<?php

class hojadiariodetalle extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hdd_id',
                                    'code'  => 'hdd_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hdd_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia_empleado_dia_d';
    protected $_PREF_TABLE= 'hojadiariodetalle'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
 

}
