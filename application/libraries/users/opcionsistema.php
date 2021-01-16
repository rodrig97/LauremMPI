<?php

class Opcionsistema extends Table{
    
    private $_areas;
    private $_permisos;
    private $_cargos; 
    
    protected $_FIELDS=array(   
                                    'id'          => 'so_id',
                                    'code'        => 'so_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'so_estado'
                            );
    
    protected $_TABLE      = 'system_opciones';
    protected $_PREF_TABLE = 'sopc'; 
    
    protected $_SCHEMA     = 'rh';
    
    public function __construct()
    {
        parent::__construct();
        $this->_CI->load->library(array('users/sesiones'));
      
        $this->_permisos = array();    
    }   
    

}