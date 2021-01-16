<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tareos  extends CI_Controller {

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
        
          $this->load->library(array('App/tareo'));
    }


    public function provide(){  

        
        $this->load->library(array('App/planilla'));

        $planilla_id = $this->planilla->get_id($this->input->get('planilla'));  
        $pla_info = $this->planilla->get($planilla_id);  
        $tarea_id = $pla_info['tarea_id'];


        $start  = 1;
        $tareos = $this->tareo->get_List($tarea_id);
        $total  = sizeof($tareos);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     
        $c = 1;
        foreach($tareos as $tareo){
 
              $data['id'] =   trim($tareo['tareo_id']);
              $data['col1'] = $c;
              $data['col2'] = trim($tareo['tarea_codigo']);
              $data['col3'] = trim($tareo['tarea_nombre']); 
              $data['col4'] = trim($tareo['fecha_inicio']);
              $data['col5'] = trim($tareo['fecha_termino']);
              $data['col6'] = trim($tareo['num_emps']);
       
              $response[] = $data;
              $c++;
        }

       echo json_encode($response);          
 
    }

    public function detalle_tareo(){

       $tareo_id = $this->input->get('tareo'); 
       $detalle_tareo = $this->tareo->get_detalle($tareo_id);
       $total  = sizeof($detalle_tareo);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     
        $c = 1;
        foreach($detalle_tareo as $reg){
 
              $data['id'] =   trim($reg['indiv_id']);
              $data['col1'] = $c;
              $data['col2'] = trim($reg['indiv_dni']);
              $data['col3'] = trim($reg['trabajador']);
              $data['col4'] = trim($reg['ocupacion']); 
              $response[] = $data;
              $c++;
        }

       echo json_encode($response);

    }


    public function view_detalle_tareo(){
     
       $tareo_id = $this->input->post('tareo'); 
       $tareo_info = $this->tareo->get_info($tareo_id);

       $detalle_tareo = $this->tareo->get_detalle($tareo_id);


       $this->load->view('planillas/v_tareo_viewdetalle', array('info' => $tareo_info, 'detalle' => $detalle_tareo ));

    }
  
}
