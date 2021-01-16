<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class asistenciabiometrico extends CI_Controller {
    
    public $usuario;
    
    public function __construct()
    {
    
        parent::__construct();
        
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else{
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  
        
        $this->load->library(array( 'App/xlsimportacion','App/biometrico'));  
    
    }
 

    public function explorar()
    { 
      
        $file_key = $this->input->post('view'); // Id del archivo subido 
        $file_id  = $this->xlsimportacion ->get_id($file_key);
        
        $bio = $this->input->post('biometrico');  

        $bio_info = $this->biometrico->get_info_biometrico($bio, 1);

        $bio_id = $bio_info['biom_id'];

        list($ok, $html_table, $log, $data) = $this->biometrico->explorar($file_id, array('biom_id' => $bio_id ));

        $params = array();

        $params['bio_key'] = $bio_info['biom_key'];
        $params['file_key'] = $file_key;

        if($ok)
        {
            $html_result = $this->load->view('planillas/v_xls_import_biometrico', $params, true);     
        }
        else
        {
            $params = array('log' => $log);
            $html_result = $this->load->view('planillas/v_xls_detalle_observaciones', $params, true);     
        }
 

        echo json_encode( array(
                                'html_table'        => $html_table, 
                                'html_result'       => $html_result  
                                ));
 
    }

 

    public function biometricos()
    {

         $this->load->library(array('App/biometrico'));
    
         $biometricos = $this->biometrico->get_list();
    
         $this->load->view('planillas/v_biometricos', array('biometricos' => $biometricos) ) ;
    }  


    public function biometrico_panel()
    {
        
        $data = $this->input->post();

        $params = array();

        $key = trim($data['biometrico']);

        $params['biometrico_info'] =  $this->biometrico->get_info_biometrico( $key , 1 );
    
        $this->load->view('planillas/p_panel_biometrico' , $params );
 
    }
 
    public function cargar_datos()
    {
  
       $data = $this->input->post();

       $bio_id = $this->biometrico->get_id($data['biokey']);

       $file_key = trim($data['filekey']);
       $file_id  = $this->xlsimportacion ->get_id($file_key);
        
       $descripcion = strtoupper(trim($data['obervacion']));

       $params = array('biometrico' => $bio_id, 'file_id' => $file_id, 'descripcion' => $descripcion); 

       $rs = $this->biometrico->cargar_datos($params);
 
       $response =  array(
            
             'result' =>  ($rs) ? '1' : '0',
             'mensaje'  => ($rs) ?  'Registros del reloj biometrico cargados correctamente' : 'Ocurrio un problema durante la operación',
             'html_result' => ($rs) ?  'Registros del reloj biometrico cargados correctamente' : 'Ocurrio un problema durante la operación',
             'data' => array('key' => '' )
        );
        
        echo json_encode($response);


    }

}