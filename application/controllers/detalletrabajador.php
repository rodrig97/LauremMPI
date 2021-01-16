<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class detalletrabajador extends CI_Controller {
    
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
        
          $this->load->library(array('App/persona','App/planilla','App/tipoplanilla', 'App/planillaempleado'));
    }
    
    
    public function add_variable_planilla(){
         
        
        $this->load->view('planillas/v_trabajador_addvariable');
        
    }
    
   public function add_concepto_planilla(){
         
        $det_key       = $this->input->post('detalle');
        
        $detalle       = $this->planillaempleado->get($det_key,true);
        $planilla      = $this->planilla->get($detalle['pla_id']);
        $trabajador    = $detalle['empleado'];
        $planilla_tipo = array('key' => $planilla['tipo_key'], 'nombre' => $planilla['tipo']);

        
        $this->load->view('planillas/v_trabajador_addconcepto', array(
                                                                      'trabajador'    => $trabajador ,
                                                                      'detalle'       => $detalle,
                                                                      'key'           => $det_key , 
                                                                      'planilla'      => $planilla, 
                                                                      'planilla_tipo' => $planilla_tipo, 
                                                                      'modo'          => 'from_detalle'));
        
        
        
    }
    /*
    public function agregar_detalle(){
        
        
        $this->load->library(array('App/planillaempleadoconcepto'));
        
        $conc_k  = $this->input->post('conc');
        $det  = $this->input->post('detalle');
        
        $det_info = $this->planilalempleado->get($det ,true);
        $indiv_id  = $det_info['indiv_id'];
        $values = array();
        
        $this->planillaempleadoconcepto->registrar($values,$indiv_id,0);
        var_dump($conc_k,$det);
        
        
        
    }*/
    
    public function getionar_conceptos(){
        
    }
    
    
}

?>