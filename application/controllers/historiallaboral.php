<?php

if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');
  
class historiallaboral extends CI_Controller {
    
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
        
         $this->load->library(array( 'App/persona',
                                     'App/situacionlaboral',
                                     'App/documento',
                                     'App/ocupacion'
                                    ));
        
        $this->user->set_keys( $this->usuario['syus_id'] );   
    }


    public function registrar_situlab()
    {
     
        $this->load->library(array('App/situacionlaboral','App/personacargo', 'App/planillatipodiahorario') );
         
        $emp_key  = trim($this->input->post('empkey'));
        $indiv_id = $this->persona->get_id($emp_key);
        
        $data     = $this->input->post();
        
        foreach($data as $k => $dat)
        {
            $data[$k] = trim($dat);
        }
        

        $data['vigente'] = ($data['vigente'] == '' ? '0' : $data['vigente']);


        $values = array(
                        'pers_id'                  => $indiv_id,
                        'plati_id'                 => $data['situlaboral'],
                        'persla_doc'               => $data['documento'], 
                        'persla_docaut'            => $data['autoriza'],
                        'meta_id'                  => $data['proyecto'],
                        'meta_nombre'              => $data['proyecto_label'],
                        'persla_fechaini'          => $data['fechaini'],
                        'depe_id'                  => $data['dependencia'],
                        'persla_vigente'           => $data['vigente'],
                        'persla_plaza'             => $data['persla_plaza'],
                        'persla_descripcion'       => $data['descripcion'],
                        'persla_terminoindefinido' => ($data['terminoindefinido'] != '' )  ? '1' : '0'
                         
                        );


        // 'persla_carnet_presento'       => $data['descripcion'],
        // 'persla_carnet_fechainicio'       => $data['descripcion'],
        // 'persla_carnet_fechafin'       => $data['descripcion'],
        // 'persla_carnet_numero'       => $data['descripcion'],

        if($data['situlaboral'] == TIPOPLANILLA_CONSCIVIL){

            $values['persla_carnet_presento']  = (trim($data['carnet_presento']) == '' ? '0' : trim($data['carnet_presento']) );
            
            if(trim($data['carnet_presento']) == '1' ){
                
                $values['persla_carnet_fechainicio']= trim($data['fechacarnet_desde']);
                $values['persla_carnet_fechafin']  = trim($data['fechacarnet_hasta']);
                $values['persla_carnet_numero']  = trim($data['fechacarnet_numero']);
            }
        }


        if($data['montocontrato'] != '')
        {

            if(is_numeric($data['montocontrato']) == FALSE) $data['montocontrato'] = '0';  
            $values['persla_montocontrato'] = $data['montocontrato'];
        }

  
        if( $data['terminoindefinido'] == '' )
        {
             $values['persla_fechafin'] = $data['fechatermino'];
        }

        if(trim($data['ocupacion_label']) != '')
        {
           
          $values_ocupacion = array('ocu_nombre' => strtoupper(trim($data['ocupacion_label'])) ); 
          list($ocu_id, $ocu_key) = $this->ocupacion->registrar($values_ocupacion, true);
          
        }
        else
        {
            $ocu_id = trim($data['ocupacion']);
        }

        if($ocu_id != '0' && $ocu_id != '')
        {
            $values['ocu_id'] = $ocu_id;
        }


        if( $data['sisgedo_doc']!= ''){
             
             $values['doc_sisgedo'] = $data['sisgedo_doc'];
             $values['doc_codigo']  = $data['sisgedo_codigo'];
             $values['doc_tipo']    = $data['sisgedo_tipodoc'];
             $values['doc_asunto']  = $data['sisgedo_asunto'];
             $values['doc_firma']   = $data['sisgedo_firma'];
             $values['doc_fecha']   = $data['sisgedo_fecha'];
 
        } 
        
        // Verificando si el trabajador tiene un tipo de pension registrado, caso contrario ponemos ONP

        list($tipo_pension, $data_pension) = $this->persona->get_tipo_pension($indiv_id);
        
        if( trim($tipo_pension) == '' || trim($tipo_pension) == '0' ){
 
            $this->persona->add_pension( $indiv_id, array(
                                                        'afp_id'      => 0,
                                                        'pentip_id'   => PENSION_SNP,
                                                        'peaf_codigo' => '',
                                                        'peaf_jubilado' => '0',
                                                        'afm_id'      => 0,
                                                        'peaf_invalidez' => '1'
                                                    ));

        }
 
        list($persla_id,$persa_key, $mensaje) = $this->situacionlaboral->registrar($values, true);


        $this->planillatipodiahorario->actualizar_horario_trabajador($data['situlaboral'], $indiv_id);
         


        if($persla_id === FALSE)
        {
           $rs = false;
           if($mensaje == '') $mensaje = 'Ocurrio un error durante la operacion';
        }
        else
        {
/*
            if($data['cargo'] != '' && $data['cargo'] != '0' )
            {
                
                $values = array(
                                'indiv_id'  => $indiv_id, 
                                'cargo_id'  => $data['cargo'], 
                                'persla_id' => $persla_id
                             );

                $this->personacargo->registrar($values);

            }*/

            $rs = true;
            $mensaje = 'Registro realizado correctamente';
         
        }  
 

        $response =  array(
            
             'result'  =>  ($rs)? '1' : '0',
             'mensaje' =>  $mensaje,
             'data'    =>  array('key' => $persa_key, 'id' => $persla_id, 'empkey' => $emp_key )
        );
        
        echo json_encode($response);
  

    }
    
      
    public function actualizar_situacionlaboral(){
        

        if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
        {  
 
                $this->load->library(array('App/situacionlaboral','App/personacargo','App/planillatipodiahorario') );
                 
                $data    =  $this->input->post();
                
                foreach($data as $k => $dat){
                    $data[$k] = trim($dat);
                }
                  
                $key = trim($this->input->post('view'));
                $id = $this->situacionlaboral->get_id($key);
         
                $data['vigente'] = ($data['vigente'] == '' ? '0' : $data['vigente']);
 
                $values = array(
                                'plati_id'           => $data['situlaboral'],
                                'persla_doc'         => $data['documento'], 
                                'persla_docaut'      => $data['autoriza'],
                                'meta_id'            => $data['proyecto'],
                                'meta_nombre'        => $data['proyecto_label'],
                                'cargo_id'           => $data['cargo'],
                                'persla_fechaini'    => $data['fechaini'],
                                'depe_id'            => $data['dependencia'],
                                'persla_vigente'     => $data['vigente'],
                                'persla_plaza'       => $data['plaza'],
                                'persla_descripcion' => $data['descripcion'],
                                'persla_terminoindefinido' => ($data['terminoindefinido'] == '' )  ? '0' : '1'
                                 
                                );


                if($data['situlaboral'] == TIPOPLANILLA_CONSCIVIL){

                    $values['persla_carnet_presento']  = (trim($data['carnet_presento']) == '' ? '0' : trim($data['carnet_presento']) );
                    
                    if(trim($data['carnet_presento']) == '1' ){
                        
                        $values['persla_carnet_fechainicio']= trim($data['fechacarnet_desde']);
                        $values['persla_carnet_fechafin']  = trim($data['fechacarnet_hasta']);
                        $values['persla_carnet_numero']  = trim($data['fechacarnet_numero']);
                    }
                    else{

                        $values['persla_carnet_fechainicio']= NULL;
                        $values['persla_carnet_fechafin']  = NULL;
                        $values['persla_carnet_numero']  = '';
                    }
                }

                $info = $this->situacionlaboral->view($id);

                $values['pers_id'] = $info['pers_id'];


                if($data['montocontrato'] != '')
                {

                    if(is_numeric($data['montocontrato']) == FALSE) $data['montocontrato'] = '0';  
                    $values['persla_montocontrato'] = $data['montocontrato'];
                }
                
          
                if( $data['terminoindefinido'] == '' )
                {
                    $values['persla_fechafin'] = $data['fechatermino'];
                } 

                if(trim($data['ocupacion_label']) != '')
                {
                   
                  $values_ocupacion = array('ocu_nombre' => strtoupper(trim($data['ocupacion_label'])) ); 
                  list($ocu_id, $ocu_key) = $this->ocupacion->registrar($values_ocupacion, true);
                  
                }
                else
                {
                    $ocu_id = trim($data['ocupacion']);
                }

                if($ocu_id != '0')
                {
                    $values['ocu_id'] = $ocu_id;
                }

                if( $data['sisgedo_doc']!= ''){
                     
                     $values['doc_sisgedo'] = $data['sisgedo_doc'];
                     $values['doc_codigo']  = $data['sisgedo_codigo'];
                     $values['doc_tipo']    = $data['sisgedo_tipodoc'];
                     $values['doc_asunto']  = $data['sisgedo_asunto'];
                     $values['doc_firma']   = $data['sisgedo_firma'];
                     $values['doc_fecha']   = $data['sisgedo_fecha'];
         
                } 
                
                $rs = false; 

                list( $rs , $mensaje ) = $this->situacionlaboral->actualizar_datos($id, $values );


                $this->planillatipodiahorario->actualizar_horario_trabajador($data['situlaboral'], $info['pers_id'] );

                if($rs === FALSE)
                {
                   $rs = false;

                   if($mensaje == '') $mensaje = 'Ocurrio un error durante la operacion';

                }
                else{

                  /*  if($data['cargo'] != '' && $data['cargo'] != '0' )
                    {
                        
                        $values = array(
                                        'indiv_id' => $indiv_id, 
                                        'cargo_id' => $data['cargo'], 
                                        'persla_id' => $id
                                        );

                        $this->personacargo->registrar($values);

                    }*/

                    $rs = true;
                    $mensaje = 'Datos actualizados correctamente';
                 
                }  
         

                $response =  array(
                    
                     'result'  =>  ($rs)? '1' : '0',
                     'mensaje' =>  $mensaje,
                     'data'    =>  array('key' => $key )
                );
                
                echo json_encode($response);

        }
        else
        {
              $response =  array(
                  
                   'result'  =>  0,
                   'mensaje' =>  PERMISO_RESTRINGICO_MENSAJE,
                   'data'    =>  array('key' => $key )
              );
              
              echo json_encode($response);
        }

 
    } 

    
    public function view()
    {
    
         $this->load->helper('formatoDB');

         $codigo          = $this->input->post('codigo');
         $id              = $this->situacionlaboral->get_id($codigo);
         $info            = $this->situacionlaboral->view($id);
         $info['vigente'] = ($info['persla_vigente']=='1') ? ' Si ': 'No'; 


         if(  $info['persla_fechafin'] == '' &&   $info['persla_vigente'] == '1' )
         {
              
              $info['fecha_hasta'] = 'Actualmente, ';   

              if( $info['persla_terminoindefinido'] == '0' && $info['persla_fechafin'] != ''  )
              {
                 $info['fecha_hasta'].= ' Hasta el '._get_date_pg(trim($info['persla_fechatermino'])).' (Termino del contrato)';        
              } 
              else{
                 $info['fecha_hasta']= ' Contrato indefinido ';  
              }

         }  
         else
         {

            $info['fecha_hasta'] = ' ------ '; 

            if($info['persla_fechafin'] != '' )
            {
                 $info['fecha_hasta'] =  _get_date_pg(trim($info['persla_fechafin']));
            }

         }
        
         
         $info['fecha_cese'] = ' ------ ';
         
         if($info['persla_fechacese'] != '' )
         { 
         
             $info['fecha_cese'] =  _get_date_pg(trim($info['persla_fechacese']));
         
          }
        
         $documentos = $this->documento->get_list(FUENTETIPODOC_HISTORIAL, $id);
 

         $this->load->view('escalafon/view_historial', array('info' => $info, 'documentos' => $documentos));
    }
    

    public function delete($id)
    {
         
         if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_DEL') )
         {  


             $codigo = $this->input->post('codigo');
             $id     = $this->situacionlaboral->get_id($codigo);
             
             $op     = $this->situacionlaboral->eliminar_registro($id);
             
             $response =  array(
                
                 'result'  =>   ($op)? '1' : '0',
                 'mensaje' => ($op)? ' Regristro eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array('key' => $codigo )
             );
            
            echo json_encode($response);
        
        }
        else
        {
              $response =  array(
                  
                   'result'  =>  0,
                   'mensaje' =>  PERMISO_RESTRINGICO_MENSAJE,
                   'data'    =>  array('key' => $key )
              );
              
              echo json_encode($response);
        }
    }
    

    public function retirar($indiv_id)
    {   

        if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
        {  


                $key  = trim($this->input->post('view'));

                $id   = $this->situacionlaboral->get_id($key);

                $info = $this->situacionlaboral->view($id);
         
                if($info['persla_vigente'] == '1')
                {

                    $this->load->view('planillas/v_trabajador_retirar', array('info' => $info));
                }
                else{

                    echo ' <b> El registro no esta vigente por eso no se puede cesar. </b> ';
                    echo " <input type='hidden' 
                                 value='El registro no esta vigente por eso no se puede cesar ' class='on_unload_window' /> ";
                }
        }
        else
        {
             echo PERMISO_RESTRINGICO_MENSAJE;
        }

    }

 
    public function cesar()
    {
    
        if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
        {  

            $data      = $this->input->post();
            $persla_id =  $this->situacionlaboral->get_id(trim($data['view']));
            
            $params = array(
                    'fechacese'   => trim($data['fechacese']),
                    'observacion' => trim($data['observacion']),
                    'registro'    => $persla_id  
            );
 
            if($this->situacionlaboral->validar_fecha_cese($params))
            {

                $ok =  $this->situacionlaboral->cesar($params);
                $mensaje = ($ok) ? 'Información del Cese actualizada correctamente' : 'Ocurrio un error durante la operacion'; 
            }
            else
            {
                $ok = false;
                $mensaje = ' La fecha de cese no es valida ';
            } 
      
            $response =  array(
                
                 'result' =>   ($ok)? '1' : '0',
                 'mensaje'  => $mensaje,
                 'data' => array('view' => $data['view'] )
            );
            
            echo json_encode($response);
        }
        else
        {

            $response =  array(
                
                 'result'   =>  '0',
                 'mensaje'  =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data'     =>  array('view' => $data['view'] )
            );
            
            echo json_encode($response);

        }

    }
 

    public function activar_directo(){
            

        if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
        {  

            $data     = $this->input->post();
            
            $indiv_id =  $this->persona->get_id(trim($data['empkey']));
            
            $info     = $this->persona->get_info($indiv_id);
            
            $nombre   = trim($info['indiv_appaterno']).' '.trim($info['indiv_apmaterno']).' '.trim($info['indiv_nombres']);

            $params = array(
                     
                    'indiv_id'    => $indiv_id  
            );


            if($info['vigente'] == '0')
            {

                $ok      =  $this->situacionlaboral->activar_directo($params);
                $mensaje = ($ok) ? ' - '.$nombre.' activado correctamente' : 'Ocurrio un problema con '.$nombre;
            }
            else
            {

                $ok = true;
                $mensaje = 'El Trabajador '.$nombre.' ya esta activo';
            }

            $response =  array(
                
                 'result'  => ($ok) ? '1' : '0',
                 'mensaje' => ($ok) ? 'Trabajador '.$nombre.' activado correctamente' : 'Ocurrio un error durante la operacion con '.$nombre,
                 'data'    => array('view' => $data['empkey'], 'mensaje' => $mensaje )
            );
            
            echo json_encode($response);    
        }
        else
        {

            $response =  array(
                
                 'result'   =>  '0',
                 'mensaje'  =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data'     =>  array('view' => $data['view'] )
            );
            
            echo json_encode($response);

        }

        
    }


    public function get_contratos()
    {

        header("Content-Type: application/json");

        $start = 0;
        $datos = $this->input->get();
 
        $params = array();

        $params['fecha1'] = array('','', '');
        $params['fecha2'] = array('','', '', '0');

        $operadores = array(
                            '1' => 'antes', // Antes de
                            '2' => 'despues',  // Despues de 
                            '3' => 'entre'  // Entre
                            );


        $params['estado_registro'] = $datos['estado_tipo'];

        $params['anio_eje'] =  $this->usuario['anio_ejecucion'];

        if( $datos['estado_modo'] == '1' ) // CONTRATO VENCIDO 
        {   
            $t_indefinido = '0';
            $params['fecha2'] = array(  (date('Y').'-'.date('m').'-'.date('d')),'antes', '',$t_indefinido);
 

        }
        else if( $datos['estado_modo'] == '2' ) // QUE EMPIEZEN 
        {   
 
            $params['fecha1'][0] = $datos['estado_modo_fechaini_fecha1'];
             
            $params['fecha1'][1] = $operadores[$datos['estado_modo_fechaini_sel']];

            if($datos['estado_modo_fechaini_sel'] == '3') // ENTRE
            {
                $params['fecha1'][2] = $datos['estado_modo_fechaini_fecha2'];        
            }


            if($datos['estado_yqterminen']=='1')
            {

                if($datos['estado_modo_fechahasta_sel'] != '4') // INDEFINIDO
                {

                    $params['fecha2'][0] = $datos['estado_modo_fechahasta_fecha1']; 
                    
                    $params['fecha2'][1] = $operadores[$datos['estado_modo_fechahasta_sel']];

                    if($datos['estado_modo_fechahasta_sel'] == '3') // ENTRE
                    {
                        $params['fecha2'][2] = $datos['estado_modo_fechahasta_fecha2'];        
                    }
                }
                else
                {
                    $params['fecha2'][3] = '1';
                }
            }
 
        }
        else if(  $datos['estado_modo'] == '3' ) // QUE TERMINEN 
        {

            
            if($datos['estado_modo_fechahasta_sel'] != '4') // INDEFINIDO
            {

                $params['fecha2'][0] = $datos['estado_modo_fechahasta_fecha1']; 
                
                $params['fecha2'][1] = $operadores[$datos['estado_modo_fechahasta_sel']];

                if($datos['estado_modo_fechahasta_sel'] == '3') // ENTRE
                {
                    $params['fecha2'][2] = $datos['estado_modo_fechahasta_fecha2'];        
                }
            }
            else
            {
                $params['fecha2'][3] = '1';
            }

        } 

        if($datos['regimen'] != '0')
        {
            $params['regimen'] = $datos['regimen'];
        }

        if($datos['grupo'] != '0')
        {
            $params['grupo'] = $datos['grupo'];
        }

        if($datos['dependencia'] != '0')
        {
            $params['area'] = $datos['dependencia'];
        }

        if($datos['cargo'] != '0')
        {
            $params['cargo'] = $datos['cargo'];
        }

        if($datos['dni'] != '' && strlen($datos['dni']) == 8 )
        {
            $params['dni'] = $datos['dni'];
        }

        if( $datos['sel_montocontrato_comparar'] != '0' )
        {
 
            $comparar = array('1' => 'mayor',
                              '2' => 'menor',
                              '3' => 'entre'
                            );    

            if(trim($datos['montocontrato1']) != '' && is_numeric($datos['montocontrato1']) )
            {
                 $params['montocontrato'] = array($datos['montocontrato1'],  $comparar[$datos['sel_montocontrato_comparar']] );

                if(   $datos['sel_montocontrato_comparar'] == '3'  )
                {   
                     if(trim($datos['montocontrato2']) != '' && is_numeric($datos['montocontrato2']) )
                     {
                        $params['montocontrato'][] = $datos['montocontrato2'];
                        
                     }
                     else
                     {
                        $params['montocontrato'][] = $datos['montocontrato1'];
                     }

                }
            }
             
        }


        if($datos['considerar_remuneracion'] != '0')
        {
            $params['considerar_remuneracion'] = $datos['considerar_remuneracion'];
            $params['mes_remuneracion'] = $datos['mes_remuneracion'];
        }
        else
        {
            $params['considerar_remuneracion'] = '0';
            $params['mes_remuneracion']  = '';
        }

       $params['considerar_registros'] = ($datos['considerar_registros'] != '') ? $datos['considerar_registros'] : '1';
    


        $response = array();

        $count = true;

        $total = $this->situacionlaboral->get_contratos( $params, $count);
 

        if(isset($_SERVER["HTTP_RANGE"]))
        {
            preg_match('/(\d+)-(\d+)/',$_SERVER["HTTP_RANGE"], $matches);

            $start = $matches[1];
            $end = $matches[2];
            if($end > $total){
                    $end = $total;
            }
        }

        $params['offset'] = $start; 
        $params['limit'] = ($end-$start+1);
        
        $count = false;

        $contratos = $this->situacionlaboral->get_contratos( $params, $count );

        
        $c = $start +1;
        
     

        header("Content-Range: " . "items ".$start."-".$end."/".$total);
  
        foreach($contratos as $reg)
        {
  
              $data['id']   = trim($reg['persla_key']);
              $data['col1'] = $c;
              $data['col2'] = trim($reg['indiv_appaterno']).' '.trim($reg['indiv_apmaterno']).' '.trim($reg['indiv_nombres']);
              $data['col3'] = trim($reg['indiv_dni']);
              $data['col4'] = (trim($reg['persla_fechaini']) != '') ?  _get_date_pg(trim($reg['persla_fechaini'])) : '----';
              $data['col5'] = (trim($reg['persla_fechafin']) != '') ?  _get_date_pg(trim($reg['persla_fechafin'])) : '----'; 
              $data['col6'] = number_format(trim($reg['persla_montocontrato']),2);
              $data['col7'] = trim($reg['plati_nombre']);
              $data['col8'] = (trim($reg['area_abrev']) != '') ?  trim($reg['area_abrev']) : '----';
              $data['col9'] = (trim($reg['cargo_nombre']) != '') ?  trim($reg['cargo_nombre']) : '----'; 
              $data['col10'] = (trim($reg['cargo_nombre']) != '') ?  trim($reg['cargo_nombre']) : '----'; 
              
              $data['col11'] = ( ($reg['dias_faltantes'] * 1) < 0 ? ($reg['dias_faltantes'] * -1) : $reg['dias_faltantes']);

           //   $data['col12'] = trim($reg['Activo']);
 
              if(($reg['dias_faltantes']*1) < 0 && $reg['persla_vigente'] == 1)
              {
                  $data['contrato_vencido'] = '1';
              }
              else
              {
                  $data['contrato_vencido'] = '0';
              }



              if($reg['persla_vigente'] == 0)
              {
                  $data['cesado'] = '1';
                  $data['col11'] = '0';
              }
              else
              {
                  $data['cesado'] = '0';
              }
                 
              $response[] = $data;  
              $c++;
        }
    

        echo json_encode($response) ;

    }

    public function cese_masivo()
    {

        $data = $this->input->post();

        $views = trim($data['view']);
        $id_s =  explode('_', $data['view']);
        array_shift($id_s);

        $info_trabajadores =  $this->situacionlaboral->get_multiple_info_trabajador($id_s);

        $fecha_minima = $this->situacionlaboral->get_fecha_minima_maxima($id_s,array( 'fecha' => 'inicio', 
                                                                                      'modo'  => 'minima'));

       

        $this->load->view('planillas/v_cese_masivo', array('info_trabajadores' => $info_trabajadores, 
                                                           'views' => $views,
                                                           'fecha_minima' => $fecha_minima) );
    }

    public function cesar_masivo()
    {
        
        $data = $this->input->post();

        $views = trim($data['view']);
        $id_s =  explode('_', $data['view']);
        array_shift($id_s);
 
        $persla_ids = $this->situacionlaboral->get_multiple_id_persla($id_s);
 
        $params = array('fechacese'   => $data['fechacese'],
                        'observacion' => strtoupper(trim($data['observacion'])),
                        'persla_ids'  => $persla_ids
                       );

        $ok = $this->situacionlaboral->cesar_masivo($params);

        $response =  array(
            
             'result'  => ($ok) ? '1' : '0',
             'mensaje' => ($ok) ? ' Cese realizado correctamente ' : 'Ocurrio un error durante la operacion con ',
             'data'    => array('view' => '', 'mensaje' => '' )
        );
        
        echo json_encode($response);    

    }

    public function elaborar_certificado()
    {

        $data = $this->input->post();

        $views = trim($data['view']);
        $id_s =  explode('_', $data['view']);
        array_shift($id_s);

        $total_trabajadores = $this->situacionlaboral->count_individuos_historiales($id_s, true);

             
        if($total_trabajadores == 1 )
        {
            echo ' ';
        }
        else
        {

        }



        $this->load->view('planillas/v_certificados_elaborar', array('views' => $views) );
    }

    public function certificados_list()
    {

        $data = $this->input->get();

        $views = trim($data['views']);
        $id_s =  explode('_', $data['views']);
        array_shift($id_s); 
 

        $persla_ids = $this->situacionlaboral->get_multiple_id_persla($id_s);
  
        $detalle_cerficados = $this->situacionlaboral->get_detalle_contrato($persla_ids);
        $c = 1;
       
        foreach($detalle_cerficados as $reg)
        {
            
              $data['id']    = $c;
              $data['col1']  = $c;
              $data['col2']  =   trim($reg['indiv_appaterno']).' '.trim($reg['indiv_apmaterno']).' '.trim($reg['indiv_nombres']);
              $data['col11'] =   trim($reg['indiv_dni']);
              $data['col3']  =   trim($reg['plati_nombre']);
              $data['col4']  =   (trim($reg['area_abrev']) != '' ? trim($reg['area_abrev']) : '------');
              $data['col5']  =   (trim($reg['proyecto']) != '') ? (trim($reg['ano_eje']).' - '.' '.trim($reg['sec_func']).' '.trim($reg['proyecto'])) : '-----------';
              $data['col6']  =   (trim($reg['platica_nombre']) != '' ? trim($reg['platica_nombre']) : '------'); 
              $data['col7']  =   (trim($reg['ocupacion']) != '' ? trim($reg['ocupacion']) : '------');   
              $data['col8']  =   _get_date_pg(trim($reg['fecha_ini']));
              $data['col9']  =   _get_date_pg(trim($reg['fecha_fin']));
              $data['col10'] =   trim($reg['duracion']);

        
              $response[] = $data;  
              $c++;
        }
        

        echo json_encode($response) ;


    }

    public function view_pdf()
    {

         
    }

    public function generar_ceritifcado()
    {

    }

}