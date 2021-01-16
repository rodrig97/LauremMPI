<?php
 

class planillaempleadoconceptomov extends Table{
     
    
    protected $_FIELDS=array(   
                                    'id'    => 'plaecm_id',
                                    'code'  => 'plaecm_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plaecm_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_empleado_concepto_mov';
    protected $_PREF_TABLE= 'PLACONMOV'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
     
}