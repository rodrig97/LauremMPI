<?php
 
if ( ! defined('BASEPATH')) exit('Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado');

 
class main extends CI_Controller {
    
    public $usuario;
    
    public function __construct()
    {
        parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else
        {
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }   

        $this->user->set_keys( $this->usuario['syus_id'] );   
   
    }
    
    public function index()
    {
          
        $this->load->view('inicio', array('usuario' =>  $this->usuario, 
                                          'permisos' => $permisos ));
    }
    
    
    public function inicio()
    {
        

    }
    
    public function white_board(){
       
    }
    
}