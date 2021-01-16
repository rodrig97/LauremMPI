<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class archivosimportacion extends CI_Controller {
    
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
 
         
    } 


    public function guardar_archivo(){

 
        $this->load->library('App/xlsimportacion');

        $config = array();
        $data = array();

        $params = $this->input->post();


        $current_view = $params['current_view'];

        if(trim($current_view)!='')
        { 
            $current_id = $this->xlsimportacion->get_id($current_view);
            $this->xlsimportacion->destroy($current_id);
        }

        list($id, $key ) = $this->xlsimportacion->registrar(array('archim_descripcion' => trim($params['descripcion'])), true);
       
        if( $id != '' && $id !== FALSE  ){
    
            $nombre_file = md5('FILEKEY'.$key); 
            $config['upload_path']   = './docsmpi/importar/';
            $config['allowed_types'] = '*';
            $config['max_size']      = '2048';
            $config['max_width']     = '0';
            $config['max_height']    = '0';
            $config['file_name']     =  $nombre_file;

            $this->load->library('upload', $config);
                   
            $file_field = 'adjunto';
                 
            if ( $this->upload->do_upload($file_field ))
            {
                $upload_data = $this->upload->data();
                 
                $ok = $this->xlsimportacion->actualizar($id, array( 'archim_file' => $upload_data['file_name']) , false );

                $data['exito'] = '1'; 
                $data['data']    = array('key' => $key); 
                $data['mensaje'] = 'El archivo fue cargado correctamente';

            }
            else
            {
                $data['exito'] = '0'; 
                $data['mensaje'] = $this->upload->display_errors();
            }
 
        }
        else{

            $data['exito'] = '0'; 
            $data['mensaje'] = 'Ocurrio un error durante la operación';
        }

         
        $this->load->view('planillas/xls_view_onupload', array('data' => $data, 
                                                              'call_function' =>  $params['call'] ));

  
    }

    public function delete_archivo()
    {

    }

    

}