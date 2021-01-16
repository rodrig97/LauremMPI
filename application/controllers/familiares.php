<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class familiares extends CI_Controller {
    
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
        
         $this->load->library(array( 'App/persona',
                                     'App/familiar'
                            ));
       // ,'Catalogos/provincia','Catalogos/distrito'
         
    }
    
    public function view(){
        
            
         $codigo = $this->input->post('codigo');
         $id= $this->familiar->get_id($codigo);
         
         $info = $this->familiar->view($id);
            
         $info['estudiante'] = 'No';

         if($info['pefa_estudiante'] == '1') $info['estudiante'] = 'Si';

         $this->load->view('escalafon/view_familiar', array('info' => $info));
    }
    
        
    
    public function delete(){
         

        if( $this->user->has_key('TRABAJADOR_FAMILIAR_DEL') )
        { 


             $codigo = $this->input->post('codigo');
             $id= $this->familiar->get_id($codigo);
            // var_dump($id);
             $op = $this->familiar->desactivar($id);
             $response =  array(
                
                 'result' =>   ($op)? '1' : '0',
                 'mensaje'  => ($op)? ' Regristro eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $codigo )
            );
            
            echo json_encode($response);
        

        }
        else{

              $response =  array(
                 
                  'result' =>   '0',
                  'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                  'data' => array()
             );
             
             echo json_encode($response);


        }   
    }
    
}