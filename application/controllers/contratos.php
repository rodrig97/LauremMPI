<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class contratos extends CI_Controller {
    
    public $usuario;
    
    public function __construct(){

        parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
        $this->load->library(array( 'App/persona'));
  
    }

    public function nuevo(){
            

        $this->load->view('escalafon/v_contrato_nuevo', array());
    }

}
