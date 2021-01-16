<?PHP
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class licencias extends CI_Controller {
    
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
        
        $this->load->library(array('App/licencia','App/documento'));
         
    }
    
    public function view(){
        
         $codigo = $this->input->post('codigo');
   
         if($this->input->post('oid') == '1')
         {
             $id =  $codigo;
         }
         else
         {
             $id= $this->licencia->get_id($codigo);
         }

         $info = $this->licencia->view($id);

         $documentos = $this->documento->get_list(FUENTETIPODOC_LICENCIAS, $id);

         $this->load->view('escalafon/view_licencia', array('info' => $info, 'documentos' => $documentos));
        
    }
    
    public function delete(){
         

        if( $this->user->has_key('TRABAJADOR_LICENCIAS_DEL') )
        {  
             $this->load->library(array('App/hojaasistencia'));


             $codigo = $this->input->post('codigo');
             $id= $this->licencia->get_id($codigo);
            // var_dump($id);
             $op = $this->licencia->desactivar($id); 
             
             $info = $this->licencia->view($id);
             $indiv_id = $info['pers_id'];
             
             $params = array(
                             'indiv_id'  => $indiv_id,
                             'tiporegistro_id'   =>  ASISDET_LICENCIASIND,
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
    
}
  