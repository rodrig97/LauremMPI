<?php
 
if(!defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class calculosrouter extends CI_Controller {
    
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

        $this->user->set_keys( $this->usuario['syus_id'] );    
        
        $this->load->library(array('App/persona','App/calculoparametro', 'App/calculoconcepto', 'App/tipoplanilla','App/anioeje'));
 
    }

    public function registrar_parametro(){

        $data = $this->input->post();

        $datos = array('plati_id' => $data['tipoplanilla'],
                       'calculotipo_id' => $data['tipocalculo'],
                       'vari_id' => $data['parametro']);

        $ok = $this->calculoparametro->registrar($datos);

        $response =  array(
            
             'result' =>  ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Parametro registrado correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $key, 'id' => $id )
        ); 
        
        echo json_encode($response);     
    }

    public function quitar_parametro(){

        $data = $this->input->post();
        
        $codigo_id = $this->calculoparametro->get_id($data['codigo']);

        $ok = $this->calculoparametro->desactivar($codigo_id);

        $response =  array(
            
             'result' =>  ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Operación completada correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $key, 'id' => $id )
        ); 
        
        echo json_encode($response);   

    }

    public function get_parametros(){

        $datos = $this->input->get();

        $rs = $this->calculoparametro->get(array('plati_id' => $datos['tipoplanilla'], 'calculotipo_id' => $datos['tipocalculo']));
        $c = 1;
        $data = array();
        $response = array();

        foreach($rs as $reg){
            $data = arraY();

            $data['id'] =   trim($reg['calpar_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($reg['vari_nombre']); 
            $response[] = $data;
            $c++;
        }

        echo json_encode($response);

    }


    
    public function registrar_concepto(){

        $data = $this->input->post();

        $datos = array('plati_id' => $data['tipoplanilla'],
                       'calculotipo_id' => $data['tipocalculo'],
                       'conc_id' => $data['concepto']);

        $ok = $this->calculoconcepto->registrar($datos);

        $response =  array(
            
             'result' =>  ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' concepto registrado correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $key, 'id' => $id )
        ); 
        
        echo json_encode($response);     
    }

    public function quitar_concepto(){

        $data = $this->input->post();
        
        $codigo_id = $this->calculoconcepto->get_id($data['codigo']);
    
        $ok = $this->calculoconcepto->desactivar($codigo_id);

        $response =  array(
            
             'result' =>  ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Operación completada correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $key, 'id' => $id )
        ); 
        
        echo json_encode($response);   

    }

    public function get_conceptos(){

        $datos = $this->input->get();

        $rs = $this->calculoconcepto->get(array('plati_id' => $datos['tipoplanilla'], 'calculotipo_id' => $datos['tipocalculo']));
        $c = 1;
        $data = array();
        $response = array();

        foreach($rs as $reg){
            $data = arraY();

            $data['id'] =   trim($reg['calconc_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($reg['conc_nombre']); 
            $response[] = $data;
            $c++;
        }

        echo json_encode($response);

    }


}