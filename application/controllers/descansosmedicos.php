<?PHP
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class descansosmedicos extends CI_Controller {
    
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
        
        $this->load->library(array('App/descansomedico','App/documento'));
         
    }
    
    public function view()
    {
        
        $codigo = $this->input->post('codigo');
        
        if($this->input->post('oid') == '1')
        {
            $id =  $codigo;
        }
        else
        {
            $id= $this->descansomedico->get_id($codigo);
        }

        $info = $this->descansomedico->view($id);
  
        $documentos = $this->documento->get_list(FUENTETIPODOC_DESCANSOMEDICO, $id);
        $this->load->view('escalafon/view_descanso', array('info' => $info, 'documentos' => $documentos));
    }
    
    public function delete()
    {
     
        if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_DEL') )
        {  
             $this->load->library(array('App/hojaasistencia'));

 
             $codigo = $this->input->post('codigo');
             $id= $this->descansomedico->get_id($codigo);
             
             $info = $this->descansomedico->view($id);
             $indiv_id = $info['pers_id'];

             $op = $this->descansomedico->desactivar($id);

             $params = array(
                             'indiv_id'  => $indiv_id,
                             'tiporegistro_id'   =>  ASISDET_DESCANSOMEDICO,
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
                 'mensaje'  =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );
            
            echo json_encode($response);
        }

    }   


    public function descansos_medicos_trabajador(){

        $this->load->library(array('App/persona'));

        $datos = $this->input->post();
 
        $indiv_info = $this->persona->get_some_info($datos['trabajador'], 'id');

        $anio = $datos['anio'];

        $this->load->view('planillas/v_resumen_descansosmedicos_anio', array('indiv_info' => $indiv_info, 'anio' => $anio) );

    }
    
}
  