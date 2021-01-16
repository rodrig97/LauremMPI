<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class archivosescalafon extends CI_Controller {
    
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
        $this->load->library(array('App/documento'));
         
    } 
      
    public function subir($tipo){
         

        $tipos = array(
                        'comision'  => FUENTETIPODOC_COMISION , 
                        'historial' =>  FUENTETIPODOC_HISTORIAL, 
                        'permiso'   =>  FUENTETIPODOC_PERMISOS, 
                        'licencia'  => FUENTETIPODOC_LICENCIAS,
                        'faltar'    =>  FUENTETIPODOC_FALTAR,
                        'academico' => FUENTETIPODOC_ACADEMICO,
                        'descanso'  => FUENTETIPODOC_DESCANSOMEDICO,
                        'vacaciones'  => FUENTETIPODOC_VACACIONES

                         );
        $params = $this->input->post('data');

        list($tipo, $id) = explode('-',$params);    

        $tipo_id = $tipos[$tipo];
        $descripcion = trim($this->input->post('descripcion'));

        $b    = rand(1,9);
        $b2   = rand(1,9);
        $name = md5($tipo_id.$b.$b2);
        $data = array();
    
        $config['upload_path']   = './docsmpi/escalafon/';
        $config['allowed_types'] = 'txt|doc|word|docx|xls|xlsx|xl|pdf|jpg|jpe|jpeg|png';
        $config['max_size']      = '2048';
        $config['max_width']     = '0';
        $config['max_height']    = '0';
        $config['file_name']     =  $name;

        $this->load->library('upload', $config);
               
        $file_field = 'adjunto';
             
		if ( !$this->upload->do_upload($file_field ))
		{
			$data['exito'] = '0'; 
            $data['error'] = $this->upload->display_errors();
                        
		}
		else
		{
            $upload_data = $this->upload->data();
            $file_data   = $upload_data['file_type'].' | '.$upload_data['file_ext'].' | '.$upload_data['file_size']; 
                         
            $data['file_name'] = $upload_data['file_name'];
   
            list($doc_id, $doc_key) = $this->documento->registrar(array(
                                                                        'doc_descripcion' => $descripcion,
                                                                        'fuente_tipo'     => $tipo_id, 
                                                                        'fuente_id'       => $id, 
                                                                        'doc_path'        => $data['file_name']

                                                                        ), true);          
    
            $data['id_file_adj'] = $doc_id;
            $data['key_file_adj'] = $doc_key;
                        
            $data['exito'] = '1';
                        
			
		}
         
        $this->load->view('escalafon/gestor_archivos', array( 'data' => $data ) );
        
        
    }


    public function delete(){
        
        $id = trim($this->input->post('key'));
        
        $rs = $this->documento->eliminar($id,false,true);
  

        $response =  array(
             
             'result'  => ($rs) ? '1' : '0',
             'mensaje' => ($rs) ? ' El archivo fue elimado correctamente' : 'Ocurrio un error durante la operacion',
             'data'    => array('key' => $id )
        );
        
        echo json_encode($response);    
        
        
        
    }
        
    public function index(){
        
    }

    public function view_subir(){

        $data = trim($this->input->post('data'));   
        $wn= explode('-', $data);

        $this->load->view('v_subirarchivo', array('data' => $data ));

    }
 
}   