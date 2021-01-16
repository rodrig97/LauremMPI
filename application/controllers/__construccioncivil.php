<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class tabladatosvariables extends CI_Controller 
{
    
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
        
        
        $this->load->helper(array('formatoDB_helper','form', 'url'));

        $this->load->library(array('App/planillatipocategoria'));
         
    } 


    public function gestionar_montos(){ 

         $table_montos = $this->planillatipocategoria->get_table_montos();

     //    var_dump($table_montos);

         $this->load->view('planillas/v_conscivil_gestionarmontos', array('table' => $table_montos));
    }


    public function actualizar_montos()
    {
        

        $datos = $this->input->post('data');  
        $datos = explode('_',$datos);
 
        $vari_id = explode('-', $datos[0]);
        $vari_id = $vari_id[1]; 
        

        $data = array(

                    '1' => $datos[1], // Operario
                    '2' => $datos[2], // Oficial
                    '3' => $datos[3],  // Peon
					'8' => $datos[4],  // maestro
                    '9' => $datos[5],  // guardian
                    '10' => $datos[6]  // guardian

                );
 
 
         $ok = $this->planillatipocategoria->actualizar_montos($vari_id, $data);


          $response =  array(

                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' => ($ok)? ' Datos actualizados correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array('key' => '' )
          );
       
          echo json_encode($response);
     

    }


    public function importacion(){


        $this->load->view('planillas/p_construccionc_importacion', array()); 

    } 
 

    public function view_xls_asistencia()
    {

        $this->load->library(array('App/xlsimportacion','App/class/xls_cs_asistencia'));
        
        $key = $this->input->post('view');
        
        $id  = $this->xls_cs_asistencia ->get_id($key);

        list($ok, $html_table, $log) = $this->xls_cs_asistencia->explorar($id);


        $html_info = ' <ul> ';
        
        foreach($log as $reg){

            $html_info.= ' <li> REGISTRO: '.$reg['registro']. ' - '.$reg['mensaje'].' </li> ';
 
        }

        $html_info.= ' </ul>';
           
        // $this->xls_cs_asistencia->explorar($id);
           
        echo json_encode( array('html_table' => $html_table, 'html_result' => $html_info) );

    }


 
}
