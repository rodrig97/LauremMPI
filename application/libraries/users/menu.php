<?php

class Menu extends Table{
    
    private $_areas;
    private $_permisos;
    private $_cargos; 
    
    protected $_FIELDS=array(   
                                    'id'          => 'sysmnu_id',
                                    'code'        => 'sysmnu_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'sysmnu_estado'
                            );
    
    protected $_TABLE      = 'system_menu';
    protected $_PREF_TABLE = 'sysmnu'; 
    
    protected $_SCHEMA     = 'rh';
    
    public function __construct()
    {
        parent::__construct();
        $this->_CI->load->library(array('users/sesiones'));
      
        $this->_permisos = array();    
    }   
    

}