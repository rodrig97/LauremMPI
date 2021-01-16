<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class faltas extends CI_Controller {
    
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
        
        $this->user->set_keys( $this->usuario['syus_id'] );   

        $this->load->library(array( 'App/persona','App/falta','App/documento' ));
       // ,'Catalogos/provincia','Catalogos/distrito'
         
    }
    
    public function view(){
        
         $codigo = $this->input->post('codigo');
         $id= $this->falta->get_id($codigo);
         $info = $this->falta->view($id);
 
         $documentos = $this->documento->get_list(FUENTETIPODOC_FALTAR, $id);

         $this->load->view('escalafon/view_falta', array('info' => $info, 'documentos' => $documentos));
        
        
    }
    
    public function delete(){
         

        if( $this->user->has_key('TRABAJADOR_FALTASTAR_DEL') )
        { 

             $codigo = $this->input->post('codigo');
             $id= $this->falta->get_id($codigo);
             $op = $this->falta->desactivar($id);
 
             $response =  array(
                
                 'result' =>   ($op)? '1' : '0',
                 'mensaje'  => ($op)? ' Regristro eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $codigo )
            );
        }
        else
        {

             $response =  array(
                
                 'result' =>    '0',
                 'mensaje'  =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );

        }
            
        echo json_encode($response);
        
    }
    
}