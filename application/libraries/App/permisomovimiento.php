<?php

 
class permisomovimiento extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'pepem_id',
                                    'code'  => 'pepem_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pepem_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_permiso_movimiento';
    protected $_PREF_TABLE= 'perspermov'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function registrar($values = array(), $return_id = true)
    {

         $sql =" UPDATE rh.persona_permiso_movimiento SET pepem_estado = 0 WHERE pepe_id = ? ";

         $this->_CI->db->query($sql, array($values['pepe_id']));

         return parent::registrar($values, $return_id);

    }
    
}