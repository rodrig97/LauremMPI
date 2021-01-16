<?php
 
if ( !defined('BASEPATH')) exit('<br/><b>Esta trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class impuestos extends CI_Controller
{
    
    public $usuario;
    
    public function __construct()
    {
        
        parent::__construct();
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else
        {
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  

        $this->user->set_keys( $this->usuario['syus_id'] );    

        $this->load->library(array('App/persona','App/planilla','App/tipoplanilla', 'App/anioeje','App/impuesto'));
    }

    public function quinta_categoria()
    {   
        $this->load->library(array( 'Catalogos/dependencia','Catalogos/cargo' ,'App/grupoempleado'));
        
        $anios = $this->anioeje->get_list();

        $dependencias   = $this->dependencia->get_list();
        $cargos         = $this->cargo->get_list(); 
        $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
        

       $this->load->view('impuestos/p_quinta_categoria', array( 
                                                                'anios'          => $anios,
                                                                'dependencias'   => $dependencias, 
                                                                'cargos'         => $cargos,
                                                                'tipo_empleados' => $tipos_empleado 
                                                                ));
    }

    public function buscar_trabajadores_quinta()
    {
        header("Content-Type: application/json");
        
        $start = 0;
         

      

        $data = $this->input->post();          
        
        $params['anio'] = '2014';

        $total = $this->impuesto->busqueda_trabajadores_quinta($params, true);
        
        if(isset($_SERVER["HTTP_RANGE"])){
            preg_match('/(\d+)-(\d+)/',$_SERVER["HTTP_RANGE"], $matches);

            $start = $matches[1];
            $end = $matches[2];
            if($end > $total){
                    $end = $total;
            }
        }


        $params['offset'] = $start; 
        $params['limit'] = ($end-$start+1);
        

        $rs=  $this->impuesto->busqueda_trabajadores_quinta($params);

        
        header("Content-Range: " . "items ".$start."-".$end."/".$total);
        
        $data = array();
        $response = array();   
        
        $c = $start +1;
    

        foreach($rs as $registro)
        {
            
            $data['id'] =   trim($registro['indiv_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($registro['indiv_appaterno']).' '.trim($registro['indiv_apmaterno']).' '.trim($registro['indiv_nombres']);
            $data['col3'] = trim($registro['indiv_dni']);
            $data['col4'] = trim($registro['plati_nombre']);
            $data['col5'] = _get_date_pg($registro['persla_fechafin']);
            $data['col6'] = $registro['persla_contrato'];
            $data['col7'] = $registro['rem_base'];
            $data['col8'] = $registro['rem_descuento'];
            $data['col9'] = $vigente;
            $data['col10'] =  (trim($registro['termino_de_contrato']) != '') ? _get_date_pg($registro['termino_de_contrato']) : '---';
            $data['contrato_vencido'] =  $registro['contrato_vencido'];
           
            $response[] = $data;
            $c++;
        }

        echo json_encode($response);

    }

    public function view_resumen_quinta()
    {

        $data = $this->input->post();
    //    var_dump($data);

        $data = $this->impuesto->getResumenQuinta();
 
        $this->load->view('impuestos/v_resumenquinta' , array('data' => $data));
     
    }

    public function afp()
    {

        echo ' Estas en AFP ';
    }

    public function sunat()
    { 
        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ; 

        $this->load->view('impuestos/p_sunat', array('anios' => $anios));
    }

    public function sunat_planillas($modo = 'seleccionar')
    {

        $data = $this->input->post();

        $planillas_key = explode('_', trim($data['planillas']) );
        array_shift($planillas_key);
 
        $planillas = $this->planilla->get_codigos( $planillas_key,  false);
 
        $planillas_id = array();

        foreach ($planillas as $reg)
        {
            $planillas_id[] = $reg['pla_id'];
        }


        $ok = $this->impuesto->sunat_planillas(array('planillas' => $planillas_id), $modo);
 
        $response =  array(
                            
                            'result'  => ($ok) ? '1' : '0',
                            'mensaje' => ($ok) ? 'Operación realizada correctamente' : 'No se pudo realizar la operación',
                            'data'    => array()
                       );
                       
        echo json_encode($response);


    }

    public function sunat_tregistro($tipo)
    {

        $data = $this->input->get();

        $meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 
        $pos  = -1;
        $anio = $data['anio'];
        $mes  = $data['mes'];
  
        foreach ($meses as $k => $v)
        {
            if($data['mes'] == $v)
            {
                $pos = $k;
                break;
            }
        }
            
        if($pos != 0)
        {
            $mes_anterior_pos = $pos;
        }
        else
        {
            $mes_anterior_pos = 11;
            $anio = $anio*1;
            $anio_anterior = $anio - 1;
            $anio_anterior.='';
        }
            
        $mes_anterior = $meses[$mes_anterior_pos];

        $modo = $tipo; // ALTAS ||  BAJAS

        $params = array(
                           'mes1' => trim($mes),
                           'mes2' => trim($mes_anterior),
                           'anio' => trim($anio),
                           'anio_anterior' => trim($anio_anterior),
                           'modo' => $modo

                       );

         $rs = $this->persona->historico_ingresos_mes($params);

         
         $indice_mes_actual =  ($params['anio'].'_'.$params['mes1']);

         if($mes != '01')
         {
            $indice_mes_anterior =  ($params['anio'].'_'.$params['mes2']);
         }
         else
         {
            $indice_mes_anterior =  ($params['anio_anterior'].'_12');
         }
 
        $response = array(); 
        $c = 1;
        
        foreach($rs as $reg)
        {
        
              $data['id']   = trim($reg['persla_key']);
              $data['col1'] = $c;
              $data['col2'] = trim($reg['trabajador']);
              $data['col3'] = trim($reg['indiv_dni']);
              $data['col4'] = trim($reg['plati_nombre']);
              $data['col5'] = (trim($reg['persla_fechaini']) != '') ?  _get_date_pg(trim($reg['persla_fechaini'])) : '----';
              $data['col6'] = (trim($reg['persla_fechafin']) != '') ?  _get_date_pg(trim($reg['persla_fechafin'])) : '----'; 
              $data['col7'] = ($reg['persla_vigente'] == 0 ? 'Si' : 'No');
              $data['col8'] = (trim($reg[$indice_mes_anterior]) != '') ?  number_format($reg[$indice_mes_anterior],2) : '0.00';
              $data['col9'] = (trim($reg[$indice_mes_actual]) != '') ?  number_format($reg[$indice_mes_actual],2) : '0.00';
              $data['col11'] = (trim($reg['observacion']) != '') ?  trim($reg['observacion']) : '----';    
            
              $response[] = $data;  
              $c++;
        }

       
        echo json_encode($response) ;
    }
    


    public function view_resumen_trabajador_pdt()
    {

        
        $this->load->library(array('App/tipoplanilla', 'App/concepto'));

        $data = $this->input->post();
             
        $params = array('anio' => '2014', 'mes' => '02' , 'indiv_id' => '26587' );
        
        $indiv_id = $params['indiv_id'];

        $indiv_info = $this->persona->get_some_info($indiv_id,'id');
 

        $config = $this->tipoplanilla->get_conceptos_sistema($indiv_info['plati_id']);

        var_dump($config['conc_pensionable'], $indiv_info['plati_id']);

        $data = $this->impuesto->analisis_pdt_trabajador($params);

        $ecuacion_pensionable = $this->concepto->print_ecuacion($config['conc_pensionable'], false, 0 );

      // var_dump($data); 

        $conceptos_pensionable = $this->concepto->get_conceptos_ecuacion($config['conc_pensionable']);

        $tabla_conceptos_pensionable = $this->concepto->get_info_conceptos($conceptos_pensionable, array( 'plati_id' => 2) );

        $tabla_conceptos_sincasilla = $this->concepto->get_info_conceptos($conceptos_pensionable, array( 'plati_id' => 2, 'not_in' => true, 'conceptosunat_obligatorio' => true , 'conc_tipo' => TIPOCONCEPTO_INGRESO ));


        //var_dump($conceptos_pensionable);

        $this->load->view('impuestos/v_analisispdt_trabajador', array('data' => $data, 
                                                                      'ecuacion_pensionable' => $ecuacion_pensionable, 
                                                                      'conceptos_pensionable' => $conceptos_pensionable,
                                                                      'tabla_conceptos_pensionable' => $tabla_conceptos_pensionable,
                                                                      'tabla_conceptos_sincasilla' => $tabla_conceptos_sincasilla ));

        // POR MES agrupar sus casillas 
       
        // Visualizar la formula del pensionable 
            

        // Analisis de las formulas usadas en el pensionable 

    }

    public function view_pdtpensionable()
    {

        $this->load->library(array('App/tipoplanilla'));

        $tipos =  $this->tipoplanilla->get_all();

        $this->load->view('impuestos/v_pdt_tipoplanilla', array('tipos' => $tipos));
    
    }

    public function view_pdtpensionable_regimen()
    {
       
       $this->load->library(array('App/tipoplanilla','App/concepto'));

       $data = $this->input->post();
    
       $plati_id = $this->tipoplanilla->get_id($data['view']);

       $config = $this->tipoplanilla->get_conceptos_sistema($plati_id);

       $ecuacion_pensionable = $this->concepto->print_ecuacion($config['conc_pensionable'], false, 0 );

       $ecuacion_essalud = $this->concepto->print_ecuacion($config['conc_essalud'], false, 0 );

       $ecuacion_sctr = $this->concepto->print_ecuacion($config['conc_sctr'], false, 0 );

       $ecuacion_onp = $this->concepto->print_ecuacion($config['conc_onp'], false, 0 );
       
    
        // ECUACION ESSALUD , ECUACION SCTR, ECUACION ONP 

       $conceptos_pensionable = $this->concepto->get_conceptos_ecuacion($config['conc_pensionable']);

       $tabla_conceptos_pensionable = $this->concepto->get_info_conceptos($conceptos_pensionable, array( 'plati_id' => $plati_id) );

       $tabla_conceptos_sincasilla = $this->concepto->get_info_conceptos($conceptos_pensionable, array( 'plati_id' => $plati_id, 'not_in' => true, 'conceptosunat_obligatorio' => true , 'conc_tipo' => TIPOCONCEPTO_INGRESO ));
  


        $this->load->view( 'impuestos/v_pdt_tipoplanilla_detalle',  array('tabla_conceptos_pensionable' => $tabla_conceptos_pensionable,
                                                                          'tabla_conceptos_sincasilla' => $tabla_conceptos_sincasilla,
                                                                          'ecuacion_pensionable' => $ecuacion_pensionable,
                                                                          'ecuacion_essalud' => $ecuacion_essalud,
                                                                          'ecuacion_onp' => $ecuacion_onp,
                                                                          'ecuacion_sctr' => $ecuacion_sctr
                                                                          ) );

    }

}