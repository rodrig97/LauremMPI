<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class estudios extends CI_Controller {
    
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

         $this->load->library(array( 'App/persona','App/academico','App/documento' ));
       // ,'Catalogos/provincia','Catalogos/distrito'
         
    }
    
    public function view(){
       
        $codigo = $this->input->post('codigo');
        $id= $this->academico->get_id($codigo);
        $info = $this->academico->view($id);

        $estado  = $info['situacion'];
        $periodo = _get_date_pg(trim($info['perac_fecini'])).' - '._get_date_pg(trim($info['perac_fecfin']));  // trim($info['perac_fecini']);


         if( in_array( $info['tiest_id'], array('3','4','5') )  ){
                 $estado =  (trim($info['estado_titulo']) != '')  ? trim($info['estado_titulo']) : '-------';
         }

        
         $nombre_estudio = '';

            if( in_array( $info['tiest_id'], array('1','2') )  ){
                 $nombre_estudio =  (trim($info['tiest_nombre']) != '')  ? trim($info['carpro_nombre']) : '-------';
            }

            if( in_array( $info['tiest_id'], array('3','4','5') )  ){
                 $nombre_estudio =  (trim($info['carpro_nombre']) != '')  ? trim($info['carpro_nombre']) : '-------';
            }
 
            if( in_array( $info['tiest_id'], array('6','7','8','9','10','11','12','13','14') )  ){
                 $nombre_estudio =  (trim($info['perac_nombre']) != '')  ? trim($info['perac_nombre']).' ('.trim($info['especi_nombre']).')'  : '-------';
            }

            $info['nombre_estudio'] = $nombre_estudio;
            $info['periodo'] = $periodo;

     $documentos = $this->documento->get_list(FUENTETIPODOC_ACADEMICO, $id);

        $this->load->view('escalafon/view_academico', array('info' => $info, 'documentos' => $documentos));
        
    }
    
    
    public function delete(){
        

        if( $this->user->has_key('TRABAJADOR_ACADEMICO_DEL') )
        { 


             $codigo = $this->input->post('codigo');
             $id= $this->academico->get_id($codigo);
            // var_dump($id);
             $op = $this->academico->desactivar($id);
             $response =  array(
                
                 'result' =>   ($op)? '1' : '0',
                 'mensaje'  => ($op)? ' Regristro eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $codigo )
            );
        
        }
        else
        {   

             $response =  array(
                
                 'result' =>   '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            ); 

        }

        echo json_encode($response);
        
    }
    
}