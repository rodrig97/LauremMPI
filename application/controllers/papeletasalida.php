<?php
 /* 
    @CAMBIOS3008
*/ 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
classxx papeletasalida extends CI_Controller {
    
    public $usuario;
    
    public function __construct()
    {
        parent::__construct();
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  

        $this->user->set_keys( $this->usuario['syus_id'] );    
        
        $this->load->library(array('App/persona','App/hojaasistencia','App/tipoplanilla','App/anioeje'));
 
    }

    public function nueva()
    { 

    }

    public function registrar_papeleta()
    {

    }

    public function mis_papeletas()
    {

    }


    public function bandeja_aprobacion()
    {

    }

    public function aprobar()
    {


    }



}