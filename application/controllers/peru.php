<?php
 
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class peru extends CI_Controller {
    
      public function __construct(){
          
          
          parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
       
        $this->load->library('Catalogos/ubicacion');
    }
  
    
    public function departamentos(){
        
        $rs =$this->ubicacion->get_departamentos();
        echo json_encode($rs);
        
    }
    
    public function provincias(){
         
        $depar = trim($this->input->post('param_select'));
        
       $rs =$this->ubicacion->get_provincias($depar);
        echo json_encode($rs);
        
    }
    
    public function distritos(){
        
        $depar = trim($this->input->post('departamento'));
        $prov = trim($this->input->post('param_select'));
        
        $rs =$this->ubicacion->get_distritos($depar,$prov);
        echo json_encode($rs);
        
    }

    public function ciudades(){

         $rs = $this->ubicacion->get_ciudades();
              
    }
    
}