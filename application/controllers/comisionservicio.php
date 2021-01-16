<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class comisionservicio extends CI_Controller {
    
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

        $this->load->library( array('App/comision','App/persona','App/documento'));
        
    }
    
    public function view(){
         
         $codigo = $this->input->post('codigo');
      
         if($this->input->post('oid') == '1')
         {
             $id =  $codigo;
         }
         else
         {
             $id= $this->comision->get_id($codigo);
         }



         $info = $this->comision->view($id);
      
         $documentos = $this->documento->get_list(FUENTETIPODOC_COMISION, $id);
 

         foreach($info as  $k => $v) 
         {
            if( trim($info[$k]) == '' ) $info[$k] = '-------';
         }           

         $this->load->view('escalafon/view_comision', array('info' => $info, 'documentos' => $documentos));
        
    }
    
     public function delete()
     {

        if( $this->user->has_key('TRABAJADOR_COMISIONSERVICIO_DEL') )
        {  
             
             $this->load->library(array('App/hojaasistencia'));

             $codigo = $this->input->post('codigo');
             
             $id = $this->comision->get_id($codigo);
             $op = $this->comision->desactivar($id);


             $info = $this->comision->view($id);
             $indiv_id = $info['pers_id'];
          
             $params = array(
                             'indiv_id'  => $indiv_id,
                             'tiporegistro_id'   =>  ASISDET_COMISIONSERV,
                             'registro_id'       => $id
                            );  

             $this->hojaasistencia->eliminar_evento_dia($params);



             $response =  array(
                
                 'result' =>   ($op)? '1' : '0',
                 'mensaje'  => ($op)? ' Regristro eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $codigo )
            );
            
            echo json_encode($response);
        
        }
        else
        {  

             $response =  array(
                
                 'result' =>   '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );
            
            echo json_encode($response);

        }
    }
    
}
  