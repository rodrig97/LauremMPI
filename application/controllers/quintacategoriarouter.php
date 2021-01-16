<?php
 
if(!defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class quintacategoriarouter extends CI_Controller {
    
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
        
        $this->load->library(array('App/persona','App/tipoplanilla','App/anioeje'));
 
    }

    public function configuracion(){

        $this->load->view('impuestos/quintacategoria/configuracion');
    }
   
    public function configuracion_detalle(){
                                            
        $this->load->library(array('App/tipoplanilla', 'App/planillatipoasistencia', 'App/impuestos/quintacategoria/quintacategoriaconfiguracion','App/datoparametrodestino', 'App/impuestos/cuartacategoriaconfiguracion'));

        $data = $this->input->post();
        $plati_key = trim($data['view']);

        $plati_id =  $this->tipoplanilla->get_id($plati_key);
        $plati_info = $this->tipoplanilla->get($plati_id);

        $plati_id = $plati_info['plati_id'];
 
        $configuracion_tipo_trabajador = $this->quintacategoriaconfiguracion->get($plati_id);

        $configuracion_tipo_trabajador_cuarta = $this->cuartacategoriaconfiguracion->get($plati_id);

        $this->load->view('impuestos/quintacategoria/configuracion_detalle', array('plati_info' => $plati_info, 
                                                                                   'plati_id' => $plati_id,
                                                                                   'configuracion_tipo_trabajador_cuarta' => $configuracion_tipo_trabajador_cuarta,
                                                                                   'configuracion_tipo_trabajador' => $configuracion_tipo_trabajador ));
    }


    public function registro_retenciones(){

        
        $tipo_planillas = $this->tipoplanilla->get_all();

        $this->load->view('impuestos/quintacategoria/panel_registro', array('tipo_planillas' => $tipo_planillas));

    }


    public function trabajadores(){

        $this->load->library(array('App/impuestos/quintacategoria/quintacategoriadao'));

        $datos = $this->input->post();

        $params = array('anio' => $datos['anio']);

        if(trim($datos['situlaboral']) != '' && trim($datos['situlaboral']) != '0'){
            $params['plati_id'] = trim($datos['situlaboral']);
        }

        if(trim($datos['dni']) != '' && trim($datos['dni']) != '0'){
            $params['dni'] = trim($datos['dni']);
        }


        if(trim($datos['vigente']) != '' && trim($datos['vigente']) == '1'){
            $params['vigente'] = true;
        }

        if(trim($datos['vigente']) != '' && trim($datos['vigente']) == '0'){
            $params['vigente'] = false;
        }

        if(trim($datos['solo_con_retencion']) != '' && trim($datos['solo_con_retencion']) == '1'){
            $params['solo_con_retenciones'] = true;
        }


        $rs = $this->quintacategoriadao->getTablaTrabajador($params);
 
        $this->load->view('impuestos/quintacategoria/panel_registro_trabajadores', array('detalle'=>$rs, 'anio' => $params['anio']));
    }

    public function detalle_mes_retenciones(){

        $data = $this->input->post();
    
        $indiv_info =  $this->persona->get_some_info( $data['trabajador'] ,'id');

        $anio = $data['anio'];
        $mes_id = $data['mes'];
        $indiv_id = $data['trabajador'];
 
        $this->load->view('impuestos/quintacategoria/retenciones_mes', array('indiv_info' => $indiv_info, 'anio' => $anio, 'mes_id' => $mes_id, 'indiv_id' => $indiv_id));
    }


    public function get_retenciones_mes(){
 
        $this->load->library(array('App/impuestos/quintacategoria/quintacategoriadao'));

        $datos = $this->input->get();
 

        $indiv_id = $datos['trabajador'];
        $anio = $datos['anio'];
        $mes_id = $datos['mes'];
        

        $rs = $this->quintacategoriadao->get(array('anio' => $anio, 'indiv_id' => $indiv_id, 'mes_id' => $mes_id));

        $response = array();
        $c = 1;
 
        foreach($rs as $reg) {   
            
              $data = array();
              $data['id']   = trim($reg['qcr_key']); 
              $data['col1'] = $c;
              $data['col2'] = trim($reg['tipla_nombre']);
              $data['col3'] = trim($reg['codigo']);
              $data['col4'] = number_format($reg['qcr_retencion'],2);
              
              $response[] = $data;
              $c++;
        }
        

        echo json_encode($response); 
    }

    public function detalle_calculo_retencion(){

        $this->load->library(array('App/impuestos/quintacategoria/quintacategoriadao','App/persona'));

        $datos = $this->input->post();

        $qr_id = $this->quintacategoriadao->get_id($datos['view']);
 
        list($qr_info) = $this->quintacategoriadao->get(array( 'anio' => $datos['anio'], 'id' => $qr_id));
        
        $indiv_info = $this->persona->get_some_info($qr_info['indiv_id'], 'id');

        $this->load->view('impuestos/quintacategoria/v_retencion_calculo', array('indiv_info' => $indiv_info, 'qr_info' => $qr_info ));
    }

    public function detalle_calculo_retencion_planilla(){
 
        $this->load->library(array('App/planilla','App/impuestos/quintacategoria/quintacategoriadao','App/persona'));

        $datos = $this->input->post();

        $pla_id = $this->planilla->get_id($datos['planilla']); 

        $detalle_id = trim($datos['detalle']); 
    
        $pla_info = $this->planilla->get($pla_id);
 
        list($qr_info) = $this->quintacategoriadao->get(array( 'anio' => $pla_info['pla_anio'], 
                                                               'planilla' => $pla_id, 
                                                               'detalle_id' => $detalle_id ));
        
        if( is_array($qr_info) == false || sizeof($qr_info) == 0){

            echo '<span class="sp11"> No se realizo retención de quinta </span>';
            die();
        }

        $indiv_info = $this->persona->get_some_info($qr_info['indiv_id'], 'id');

        $this->load->view('impuestos/quintacategoria/v_retencion_calculo', array('indiv_info' => $indiv_info, 'qr_info' => $qr_info ));
 

    }

    public function getRetencionesConstancias(){

        $this->load->library(array('App/impuestos/quintacategoria/retencionconstanciaanterior'));

        $datos = $this->input->get();

        $indiv_id = 0;

        if( trim($datos['trabajador']) != '' ){ 

            $indiv_id = $this->persona->get_id(trim($datos['trabajador']));
        }

        $rs = $this->retencionconstanciaanterior->get( array('anio' => $datos['anio'], 'indiv_id' => $indiv_id ));

        $response = array();

        $c = 0;

        foreach ($rs as $reg) {
            
            $c++;

            $reg = array( 'id' => $reg['qcoa_key'],
                          'num' => $c,
                          'anio' => $reg['anio'],
                          'trabajador' => $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres'],
                          'descripcion' => $reg['qcoa_descripcion'],
                          'ingresos' => number_format($reg['qcoa_ingresos'],2),
                          'descuentos' => number_format($reg['qcoa_descuento'],2)
                        );


            $response[] = $reg;
        }

        echo json_encode($response);

    }

    public function constancias_de_retencion(){
 
        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;

        $this->load->view('impuestos/quintacategoria/v_retenciones_anteriores', array('anios' => $anios));
    }


    public function nueva_retencion_constancia_anterior(){ 
 
        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;

        $this->load->view('impuestos/quintacategoria/v_nueva_retencion_anterior', array('anios' => $anios));

    }

    public function registrar_constancia_retencion(){

        $this->load->library(array('App/impuestos/quintacategoria/retencionconstanciaanterior'));

        $datos = $this->input->post(); 

        $indiv_id = $this->persona->get_id($datos['trabajador']);

        $values = array(
                        'anio'              => $datos['anio'],
                        'indiv_id'          => $indiv_id,
                        'qcoa_descripcion'  => trim($datos['descripcion']),
                        'qcoa_ingresos'     => ( is_numeric($datos['ingreso']) ?$datos['ingreso'] : 0 ),
                        'qcoa_descuento'    => ( is_numeric($datos['descuento']) ?$datos['descuento'] : 0 )
                       );

        $ok = $this->retencionconstanciaanterior->registrar($values);

        $response =  array(
            
             'result'  => ($ok) ? '1' : '0',
             'mensaje' => ($ok) ?  ' Proceso completado correctamente ' : 'Ocurrio un error durante la operacion',
             'data'    => array('key' => '' )
        );

        echo json_encode($response);
         
    }

    public function eliminar_constancia_retencion(){

        $this->load->library(array('App/impuestos/quintacategoria/retencionconstanciaanterior'));

        $datos = $this->input->post(); 

        $id = $this->retencionconstanciaanterior->get_id($datos['view']);
 
        $ok = $this->retencionconstanciaanterior->desactivar($id);

        $response =  array(
            
             'result'  => ($ok) ? '1' : '0',
             'mensaje' => ($ok) ?  ' Proceso completado correctamente ' : 'Ocurrio un error durante la operacion',
             'data'    => array('key' => '' )
        );

        echo json_encode($response);
    }
    
}