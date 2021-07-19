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

        if ( trim($info['perac_fecini']) == trim($info['perac_fecfin']) ) {
            $info['periodo'] = _get_date_pg(trim($info['perac_fecini']));
        } else {
            $info['periodo'] = _get_date_pg(trim($info['perac_fecini'])).' - '._get_date_pg(trim($info['perac_fecfin']));
        }

        $info['nombre_estudio'] = ( trim($info['carpro_nombre']) != '' ? trim($info['carpro_nombre']) : trim($info['especi_nombre']) );

        $info['centro_estudio'] = ( trim($info['cees_nombre']) != '' ? trim($info['cees_nombre']) : trim($info['perac_nombre']) );

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

    public function get_carreras() {

        $this->load->library('Catalogos/carreraprofesional');

        $cod_institucion = trim($this->input->post('view'));
        $params = array(
            'centro_estudios' => $cod_institucion,
        );

        if($cod_institucion != '') {
            $carreras_tmp = $this->carreraprofesional->get_list($params);
        }

        $carreras = array();

        foreach($carreras_tmp as $index => $carrera){
            $carreras[$index]['id'] = trim( $carrera['carpro_id']);
            $carreras[$index]['name'] = trim( $carrera['carpro_nombre']);
        }

        echo json_encode($carreras);

    }

    public function get_centros(){

        $this->load->library('Catalogos/centroestudio');

        $tiest = trim($this->input->post('view'));

        $tipo_estudio = ( $tiest > 13 ) ? 13 : $tiest;
        $params = array(
            'tipo_estudio' => $tipo_estudio,
        );

        if($tipo_estudio != '') {
            $centros_tmp = $this->centroestudio->get_list($params);
        }

        $centros = array();

        foreach($centros_tmp as $index => $centro){
            $centros[$index]['id'] = trim( $centro['cees_id']);
            $centros[$index]['name'] = trim( $centro['cees_nombre']);
        }

        echo json_encode($centros);
    }

}