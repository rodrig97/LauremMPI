<?php
 
if ( !defined('BASEPATH')) exit('<br/><b>Esta trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class about extends CI_Controller
{
    
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
    
    public function acerca_de()
    {
            $this->load->view('about/acercade');
    }  

}