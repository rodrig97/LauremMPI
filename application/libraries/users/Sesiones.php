<?php


class Sesiones extends Table{
    
     protected $_FIELDS=array(   
                                    'id'    => 'usse_id',
                                    'code'  => 'usse_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'usse_estado'
                            );
    
    protected $_TABLE = 'user_sessions';
    protected $_PREF_TABLE= 'SESION'; 
    protected $_SCHEMA = 'public';
    
    public function __construct(){
         parent::__construct();
        // $this->_CI->load->library('sesiones');
    }   
    
    
    public function register($usser){
        
       
       return $this->registrar( array('user_id' =>  $usser ),true);
        
    }
    
    
}


?>
