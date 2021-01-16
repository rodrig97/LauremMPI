<?php
  
class empleadoconceptobe extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'ecb_id',
                                    'code'  => 'ecb_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'ecb_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'empleado_concepto_beneficiario';
    protected $_PREF_TABLE= 'EMBXBEN'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


}