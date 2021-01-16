<?php

class tipoestudio extends Table{
    
       
     
    protected $_FIELDS=array(   
                                    'id'    => 'tiest_id',
                                    'code'  => 'tiest_key',
                                    'name'  => 'tiest_nombre',
                                    'descripcion' => '',
                                    'state' => 'tiest_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'tipo_estudio';
    protected $_PREF_TABLE= 'tipoestudiompi'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
    
}



?>
