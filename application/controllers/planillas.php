<?php
 
if ( !defined('BASEPATH')) exit('<br/><b>Esta trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class planillas extends CI_Controller
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

        $this->load->library(array('App/persona','App/planilla','App/tipoplanilla', 'App/anioeje'));
    }
  
    
    public function registrar()
    {
         
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        {    

            $this->load->library('App/planillamovimiento'); 
             
            $data = $this->input->post();  
            
            foreach($data as $k => $x) $data[$k] = trim($x);
       
            $plati_id   = $this->tipoplanilla->get_id(trim($data['planillatipo']));
            $plati_info = $this->tipoplanilla->get($plati_id);


            $tarea_id = 0;
            $error    = false;
             
            
            $anio_id = ( trim($data['anio']) == '') ? '0' : trim($data['anio']) ;
            $mes_id  = ( trim($data['mes']) == '') ? '0' : trim($data['mes']) ;
            
       
            if($data['afectacion_especificada'] == 1)
            {
                    $data_ff      = explode('-',trim($data['fuente_financiamiento']));
                    $fuente_id    = trim($data_ff[0]);
                    $recurso_id   = trim($data_ff[1]);
                    

                    if($data['especificar_clasificador'] == '1')
                    {

                        $clasificador = trim($data['clasificador']);
                    }
                    else
                    {
                        $clasificador = '';
                    }

                    if($data['tarea'] != '0')  $tarea_id  = ( trim($data['tarea']) == '') ? '0' : trim($data['tarea']) ;
                    
                    if($fuente_id == '' || $recurso_id == '' || $tarea_id == '0' || $tarea_id == '' ) $error = true;
            }
            else
            {
                $fuente_id    = '';
                $recurso_id   = '';
                $tarea_id     = 0;
                $clasificador = '';
            } 
     

            $codigo_planilla = $this->planilla->get_siguiente_codigo($plati_id, $anio_id, $data['tipo']);

            $codigo_planilla = sprintf('%03d',$codigo_planilla);

            
            if($data['considerar_categorias'] == '1')
            {
                $con_categorias = $plati_info['plati_tiene_categoria'];
            }
            else
            {
                $con_categorias = '0';
            }



            $meses_num =array('01' => 1, '02' => 2, '03' => 3,
                          '04' => 4, '05' => 5, '06' => 6,
                          '07' => 7, '08' => 8, '09' => 9,
                          '10' => 10, '11' => 11, '12' => 12 ); 

            

            $values = array(
                                'plati_id'             =>  $plati_id, 
                                'pla_anio'             =>  $anio_id,
                                'ano_eje'              =>  $this->usuario['anio_ejecucion'],
                                'pla_mes'              =>  $mes_id,     
                                'pla_mes_int'          =>  $meses_num[trim($mes_id)],                       
                                'tarea_id'             =>  $tarea_id,
                                'fuente_id'            =>  $fuente_id,
                                'tipo_recurso'         =>  $recurso_id,             
                                'pla_descripcion'      =>  $data['descripcion'],
                                'pla_afectacion_presu' =>  $data['afectacion_especificada'],
                                'pla_tipo'             =>  $data['tipo'],
                                'pla_codigo'           =>  $codigo_planilla,
                                'clasificador_id'      =>  $clasificador,
                                'pla_tiene_categoria'  =>  $con_categorias,
                                'pla_afectadinero_modo'  =>  $plati_info['plati_afectadinero_modo'],
                                'pla_especificar_clasificador' => $data['especificar_clasificador'],
                                'pla_semana'        => $data['semana']
                            );


            
            if($data['conintervalo'] != '' && $data['conintervalo'] != null )
            {
            
                if($data['fechadesde'] != '' && $data['fechadesde'] != null) $values['pla_fecini'] = $data['fechadesde'];

                if($data['fechahasta'] != '' && $data['fechahasta'] != null) $values['pla_fecfin'] = $data['fechahasta'];

            }
            
            $mensaje_error = 'Ocurrio un error durante la operacion'; 
            
            if(!$error)
            {
                list($id,$key) = $this->planilla->registrar($values,true);


                if($id != false && $id != null){
                    $this->planillamovimiento->registrar(array('pla_id' => $id, 
                                                               'plaes_id' => ESTADOPLANILLA_ELABORADA, 
                                                               'plamo_descripcion' => '' ));


                    $this->planilla->procesar_variables($id);

                }
            }
            else{
                $id = false;
                $mensaje_error = 'Ocurrio un error durante la operacion';
            }
            
            $response =  array(
                
                 'result' =>  ($id != false && $id != null)? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? 'Planilla registrada satisfactoriamente' : $mensaje_error,
                 'data' => array('key' => $key )
            );
        
        }
        else
        {

            $response =  array(
                
                 'result' =>   '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array('key' => $key )
            );

        }
        
        echo json_encode($response);
        
            
    }
    
    
    public function ui_registrar_new()
    {
         
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        {    

            $this->load->library(array('App/tarea','App/fuentefinanciamiento','App/partida'));
            
            $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;

            $tipos  = $this->tipoplanilla->get_all();
            $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ));
             
            if(CONECCION_AFECTACION_PRESUPUESTAL == false)
            {
                $partidas      = $this->partida->listar( $null, $this->usuario['anio_ejecucion'], array('tipo_transaccion' => '2'));    
            }


            if( sizeof($anios) > 0 )
            {
  
               $this->load->view('planillas/v_planilla_crear', array( 'anios' => $anios, 'tipos' =>$tipos, 'tareas' => $tareas,   'partidas_presupuestales' => $partidas));

            }
            else
            {

               echo '<b> Usted no puede registrar planillas porque no tiene asignado un año de trabajo  </b>';
            }

        }
        else
        {

             echo PERMISO_RESTRINGICO_MENSAJE;
        }

    }
    
    public function ui_elaborar()
    {
     
        $tipos = $this->tipoplanilla->get_all();
        
        $this->load->view('planillas/p_elaborar', array('tipos' =>$tipos));
        
    } 
    
    
    public function ui_registro_planillas()
    {
        if( $this->user->has_key('PLANILLAS_ELABORAR') || $this->input->post('modo') == REGISTROPLANILLAS_DECLARARSUNAT  )
        {  

            $this->load->library(array('App/tarea','App/planillaestado'));  

            $modo = ( trim($this->input->post('modo')) != '' ) ? trim( $this->input->post('modo')) : '0';
        
            $tipos   = $this->tipoplanilla->get_all();
            $tareas  = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));
            $estados = $this->planillaestado->get_list();

            $anios = $this->anioeje->get_list( array('modo' => 'VER', 'usuario'=> $this->usuario['syus_id']  ) ) ;


            $_MESES = array( 
                                '01' => 'ENERO',
                                '02' => 'FEBRERO',
                                '03' => 'MARZO',
                                '04' => 'ABRIL',
                                '05' => 'MAYO',
                                '06' => 'JUNIO',
                                '07' => 'JULIO',
                                '08' => 'AGOSTO',
                                '09' => 'SEPTIEMBRE',
                                '10' => 'OCTUBRE',
                                '11' => 'NOVIEMBRE',
                                '12' => 'DICIEMBRE'
                            );

            if($modo == REGISTROPLANILLAS_DECLARARSUNAT )
            {
               
               $planilla_estados = array(

                                 '0' => 'ELIMINADA',
                                 '1' => 'ELABORAR',
                                 '2' => 'PROCESADA',
                                 '3' => 'FINALIZADA',
                                 '4' => 'DEVENGADO',
                                 '5' => 'GIRADO',
                                 '9' => 'ANULADA' 

                              ); 

               $params= array(
                                'anio' => $this->input->post('anio'),
                                'mes'  => $this->input->post('mes'),
                                'mes_label' => $_MESES[$this->input->post('mes')],        
                                'estadoplanilla' => ESTADOPLANILLA_MINIMO_SUNAT,
                                'estadoplanilla_label' => $planilla_estados[ESTADOPLANILLA_MINIMO_SUNAT]
                             );



            } 

            $this->load->view('planillas/v_planillas_registro', array( 'anios' => $anios, 
                                                                       'tipos' =>$tipos, 
                                                                       'tareas' => $tareas, 
                                                                       'estados' => $estados,
                                                                       'modo'   => $modo,
                                                                       'params' => $params 

                                                                       ));
        }
        else
        {

             echo PERMISO_RESTRINGICO_MENSAJE;
        }
    }
    

    public function ui_gestionar_conceptos()
    {
        
        $this->load->library(array('App/grupovc','App/partida','App/conceptosunat'));    
    
        $tipos  = $this->tipoplanilla->get_all();
        $grupos = $this->grupovc->get_list();
    
        $clasificadores   = $this->partida->listar($null,$this->usuario['ano_eje'], array('tipo_transaccion' => '2'));

        $conceptosunat = $this->conceptosunat->get_list();

        $this->load->view('planillas/p_conceptosvariables_gestionar' , array('tipos_planilla' =>$tipos, 
                                                                             'grupos' => $grupos, 
                                                                             'clasificadores' => $clasificadores,
                                                                             'conceptosunat' => $conceptosunat ));
        
    }
    
    
    public function registro_planillas($mode_get = 'main')
    {
     
            $this->load->librarY(array('App/tipoplanilla'));
        
            header("Content-Type: application/json");
            
            
            $data   = $this->input->get();
             
            $start = 1;
            $end = 10;
            
       
            $response = array();
            $c = 1;

            $params  = array();
            

            if($mode_get == 'main' || $mode_get == 'reporter' || $mode_get == 'seleccionadas_sunat' )
            {
               


                if($data['estado'] != '' && $data['estado'] != '0')
                {
                    $params['estado'] = $data['estado'];
                    
                }

                if($data['tipoplanilla'] != '' && $data['tipoplanilla'] != '0')
                {
                    $params['tipo'] =  $this->tipoplanilla->get_id(trim( $data['tipoplanilla'])); // tipoplanilla
                }

                if($data['anio'] != '' && $data['anio'] != '0')
                {
                    $params['anio'] = $data['anio'];
                }

                if($data['mes'] != '' && $data['mes'] != '0')
                {
                    $params['mes'] = $data['mes'];
                }
             

                if($data['tarea'] != '' && $data['tarea'] != '0')
                {
                    $params['tarea'] = $data['tarea'];
                }
 
               if($data['descripcion'] != '' && $data['descripcion'] != '0')
                {
                    $params['descripcion'] = $data['descripcion'];
                }
                
                if( trim($data['siaf']) != '' && is_numeric(trim($data['siaf'])) )
                {

                    $params = array( 'siaf' => trim($data['siaf']), 'anio' =>  $data['anio'] );

                }

                if( trim($data['codigo']) != '')
                {

                    $params = array( 'codigo' => trim($data['codigo']) );

                }

                if( trim($data['semana']) != '' )
                 {
                     $params['semana'] = trim($data['semana']);
                 }

                if($mode_get == 'seleccionadas_sunat')
                {
                   $params['solo_seleccionadas_sunat'] = '1';

                   $params['orderby_plati'] = '1';
                } 
            
                $planillas = $this->planilla->get_list($params);

             
                $total = sizeof($planillas);

                header("Content-Range: " . "items ".$start."-".$total."/".$total);     
 
                $data = array();

                if($mode_get=='main' || $mode_get == 'seleccionadas_sunat')
                {

                      foreach($planillas as $planilla)
                      {
                                
                            $afectecion_label = trim($planilla['tarea_nombre']);

                            if(strlen($afectecion_label) > 40 ){

                               $afectecion_label = '..'.substr($afectecion_label,22,40); 
                               if(strlen($afectecion_label)>40) $afectecion_label.=".."; 
                               $afectecion_label=$afectacion_codigo.' '.$afectecion_label;

                            } 
                            else{

                               $afectecion_label  = substr($afectecion_label,0,40);
                               if(strlen($afectecion_label)>40) $afectecion_label.=".."; 

                            }

                            $afectecion_label= trim($planilla['tarea_codigo']).' '.$afectecion_label;

                            $data['id']   = trim($planilla['pla_key']);
                            $data['col1'] = $c;
                            $data['col2'] = trim($planilla['pla_codigo']);
                            $data['col3'] = trim($planilla['tipo']);
                            $data['col4'] = (trim($planilla['pla_descripcion']) != '') ? trim($planilla['pla_descripcion']) : '--------';
                            $data['col5'] = (trim($planilla['tarea_nombre']) != '') ?  $afectecion_label : '----';
                            $data['col6'] = trim($planilla['pla_anio']);
                            $data['col7'] = trim($planilla['mes']);
                            $data['col8'] = trim($planilla['estado']);
                            $data['col9'] = trim($planilla['num_emps']);
                               
                            $response[] = $data;  
                            $c++;
                      }

                }
                else if($mode_get == 'reporter')
                {

                      foreach($planillas as $planilla)
                      {
                         

                           $afectecion_label = trim($planilla['tarea_nombre']);

                           if(strlen($afectecion_label) > 40 ){

                              $afectecion_label = '..'.substr($afectecion_label,22,40); 
                              if(strlen($afectecion_label)>40) $afectecion_label.=".."; 
                              $afectecion_label=$afectacion_codigo.' '.$afectecion_label;

                           } 
                           else{

                              $afectecion_label  = substr($afectecion_label,0,40);
                              if(strlen($afectecion_label)>40) $afectecion_label.=".."; 

                           }

                           $afectecion_label= trim($planilla['tarea_codigo']).' '.$afectecion_label;  

                           $data['id']   = trim($planilla['pla_key']);
                           $data['col1'] = $c;
                           $data['col2'] = trim($planilla['pla_codigo']);
                           $data['col3'] = trim($planilla['pla_anio']);
                           $data['col4'] = trim($planilla['mes']);
                           $data['col5'] = trim($planilla['tipo']);
                           $data['col6'] = (trim($planilla['pla_descripcion']) != '') ? trim($planilla['pla_descripcion']) : '--------';
                           $data['col7'] = (trim($planilla['tarea_nombre']) != '') ?    $afectecion_label : '----';
                           $data['col8'] = trim($planilla['estado']);
                           $data['col9'] = trim($planilla['num_emps']);
                           
                            $response[] = $data;  
                            $c++;
                      
                      }


                }
             
            } 
            else if($mode_get=='preview')
            {
                  
                  $tipo =  $this->input->get('tipo');
                  $tipo = $this->tipoplanilla->get_id($tipo);
                  
                  $q = array('tipo' => $tipo);

                  if( $this->input->get('just')  == 'procesadas'){
                       $q['estado'] = ESTADOPLANILLA_PROCESADA;
                  }


                  $planillas = $this->planilla->get_list($q);
                  
                  $total = sizeof($planillas);

                  header("Content-Range: " . "items ".$start."-".$total."/".$total);     

                  $data = array();
 
                  foreach($planillas as $planilla){

                            

                        $afectecion_label = trim($planilla['tarea_nombre']);

                        if(strlen($afectecion_label) > 40 ){

                           $afectecion_label = '..'.substr($afectecion_label,22,40); 
                           if(strlen($afectecion_label)>40) $afectecion_label.=".."; 
                           $afectecion_label=$afectacion_codigo.' '.$afectecion_label;

                        } 
                        else{

                           $afectecion_label  = substr($afectecion_label,0,40);
                           if(strlen($afectecion_label)>40) $afectecion_label.=".."; 

                        }

                        $afectecion_label= trim($planilla['tarea_codigo']).' '.$afectecion_label;


                        $data['id']   = trim($planilla['pla_key']);
                        $data['col1'] = $c;
                        $data['col6'] = trim($planilla['pla_anio']);
                        $data['col7'] = trim($planilla['mes']);
                        $data['col2'] = trim($planilla['pla_codigo']);
                        $data['col4'] = (trim($planilla['tarea_nombre']) != '') ?   $afectecion_label : '----'; 
                        $data['col3'] = trim($planilla['estado']);
                        $data['col5'] = (trim($planilla['pla_descripcion']) != '') ? trim($planilla['pla_descripcion']) : '--------';
                        $data['col8'] = trim($planilla['tipo']);
                        $data['col9'] = trim($planilla['num_emps']);
                        
                        $response[] = $data;
                        $c++;
                  }
                   
            }

             
            
            echo json_encode($response) ;
           
            
    }
    
   
    public function ui_view()
    {
    
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 
            
            $this->load->library(array( 'App/tarea', 
                                        'Catalogos/afp', 
                                        'App/fuentefinanciamiento',  
                                        'Catalogos/banco', 
                                        'App/concepto',
                                        'Catalogos/dependencia', 
                                        'Catalogos/cargo', 
                                        'App/grupoempleado',
                                        'App/tipoplanilla',
                                        'App/planillaasistencia'  ));
     
            $data = $this->input->post();
            foreach($data as $k => $x) $data[$k] =  trim($x);
            
            $plani_id = $this->planilla->get_id($data['codigo']);
                     
            $plani_info = $this->planilla->get($plani_id);
        
            $data = array();           

            $data['afps']         = $this->afp->load_for_combo(true);
            $data['bancos']       = $this->banco->get_list();
            $data['tareas']       = $this->tarea->get_list(array('ano_eje' =>  $this->usuario['anio_ejecucion'] ) );
            $data['conceptos']    = $this->concepto->get_list( array('tipoplanilla' =>  $plani_info['plati_id']));
            $data['fuentes']      = $this->fuentefinanciamiento->get_ff_tr(array('anio_eje' =>  $this->usuario['anio_ejecucion'] ));

            $data['dependencias'] = $this->dependencia->get_list();
            $data['cargos']       = $this->cargo->get_list(); 

            $data['plani_info']   = $plani_info;


            $data['grupos']       = $this->grupoempleado->get_list();

            $siaf_info = $this->planilla->getNumerosSiaf($plani_id);        
            $data['siafNros'] = $siaf_info;
            
            $data['importaciones_asistencia'] = $this->planillaasistencia->get($plani_id);
 
            $plati_info = $this->tipoplanilla->get($plani_info['plati_id']);
            
            $data['plati_calcula_cuarta'] = $plati_info['plati_calcula_cuarta'];
            $data['plati_calcula_quinta'] = $plati_info['plati_calcula_quinta'];

            if($plani_info['estado_id'] == ESTADOPLANILLA_ELABORADA )
            {   
                $this->load->view('planillas/p_planilla_elaborar', $data);
            }
            else
            {   
     
                $data['validacion_info'] = $this->planilla->validar();
     
                // Verificacion de pensiones 
                $rs_problema_pension = $this->planilla->comprobar_pension($plani_id);

                $problemas_pension = '0';

                if(sizeof($rs_problema_pension) > 0 )
                {
                   $problemas_pension = '1';
                }

                $html_pp = '<span class="sp11"> Trabajadores con observaciones por ONP o AFP: </span> <br/> <ul style="margin:0px 0px 0px 0px; padding:0px 0px 0px 0px;">';

                foreach ($rs_problema_pension as $reg) {

                     $html_pp.= "<li> <span class='sp11'> ".$reg['indiv_appaterno']." ".$reg['indiv_apmaterno']." ".$reg['indiv_nombres']." </span> </li>";
                
                }

                $html_pp.="</ul>";
                
                $data['problemas_pension'] = $problemas_pension;
                $data['problemas_pension_mensaje'] = $html_pp;

                $this->load->view('planillas/p_planilla_procesada', $data);
            }
       
       }
       else
       {
            echo PERMISO_RESTRINGICO_MENSAJE;
       }
    }
    
    
    public function ui_view_procesada()
    {
        
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 

            $data = $this->input->post();
         
            foreach($data as $k => $x) $data[$k] =  trim($x);
            
            $plani_id = $this->planilla->get_id($data['codigo']);
            
            $plani_info = $this->planilla->get($plani_id);

            $this->load->view('planillas/p_planilla_procesada', array('plani_info' => $plani_info ));


        }
        else
        {
             echo PERMISO_RESTRINGICO_MENSAJE;
        }
    
    } 


    public function provide($item, $subitem)
    {
      
        header("Content-Type: application/json");
         
        $response = array();
        
        $datos = $this->input->get();

        if($item=='detalle')
        {
            
            $this->load->library(array('App/planillaempleado'));
            
            $data = $this->input->get();
            foreach($data as $k => $v) $data[$k] = trim($v);
            
            $plani_id    = $this->planilla->get_id($data['view']);
            $pla_info    = $this->planilla->get($plani_id);
            $by_indiv_id = false;    


            if($this->planilla->existe($plani_id) == FALSE)
            {

                $response =  array(
                    
                     'result'  => '0',
                     'mensaje' =>  'La planilla no esta operativa',
                     'data'    => array()
                );
                
                echo json_encode($response);

                die();
            }
            


            if($pla_info['pla_tiene_categoria'] == '1')
            {
                 $by_indiv_id =  true;
            }
 
            if($datos['fuente']!='0')
            {
                
                if($datos['fuente'] == 'no')
                {
                    $datos['fuente'] = 'no';
                    $datos['tiporecurso'] = 'no';
                }
                if($datos['fuente'] == 'si')
                {
                    $datos['fuente'] = 'si';
                    $datos['tiporecurso'] = 'si';
                }
                else
                {
                    list($datos['fuente'], $datos['tiporecurso']) = explode('-', $datos['fuente']);
                }
            }
 

            foreach ($datos as $key => $value) 
            {
                
                $datos[$key] = trim($value);
            }


            $detalle = $this->planillaempleado->get_list($plani_id, $by_indiv_id, $datos);
            
            $c     = 1;
            $start = 1;
            $end   = 10;
            $total = sizeof($detalle);

            header("Content-Range: " . "items ".$start."-".$total."/".$total);     

            $data     = array();
            $response = array();


            foreach($detalle as $detemp)
            {
                $data['id']   =   trim($detemp['detalle_key']);
                $data['col1'] =  $c;
                $data['col2'] =  trim($detemp['dni']);
                $data['col3'] =  trim($detemp['indiv_appaterno']);
                $data['col4'] =  trim($detemp['indiv_apmaterno']);
                $data['col5'] =  trim($detemp['nombres']);
                $data['col6'] =  trim($detemp['tipo_empleado']);
                $data['col7'] =  trim($detemp['tarea']);
                $data['col8'] =  trim($detemp['fuente']); 
           
                $response[] = $data;
                $c++;
            }

            echo json_encode($response) ;
            
            
            
        }
        else if($item=='detalle_procesada')
        {

            $data = $this->input->get();
            foreach($data as $k => $v) $data[$k] = trim($v);
            
            $plani_id = $this->planilla->get_id($data['view']);
            $detalle  = $this->planilla->get_detalle_resumen_full($plani_id);

            if($this->planilla->existe($plani_id) == FALSE)
            {

                $response =  array(
                    
                     'result'  => '0',
                     'mensaje' =>  'La planilla no esta operativa',
                     'data'    => array()
                );
                
                echo json_encode($response);

                die();
            }
            
            $c     = 1;
            $start = 1;
            $end   = 10;
            $total = sizeof($detalle);

            header("Content-Range: " . "items ".$start."-".$total."/".$total);     
            $data     = array();
            $response = array();

            foreach($detalle as $detemp)
            {
                 
                 $data['id']    =   trim($detemp['plaemp_key']);
                 $data['col1']  =  $c;
                 
                 $data['col2']  =  trim($detemp['indiv_dni']);
                 $data['col3']  =  trim($detemp['indiv_appaterno']).' '.trim($detemp['indiv_apmaterno']).' '.trim($detemp['indiv_nombres']);
                 
                 $data['col4']  =  (trim($detemp['platica_nombre']) != '') ? $detemp['platica_nombre']: ' ------- ';    
                 
                 $data['col5']  =  sprintf('%.2f',$detemp['ingresos']);
                 $data['col6']  =  sprintf('%.2f',$detemp['descuentos']);


                 $neto = $detemp['ingresos'] - $detemp['descuentos'];

                 $data['col7']  =  sprintf('%.2f',$neto);

                 $data['col8']  =  sprintf('%.2f',$detemp['aportacion']);
                 
                 $total_gasto   = $detemp['ingresos'] + $detemp['aportacion'];
                 
                 $data['col9']  =  sprintf('%.2f', $total_gasto );
                 $data['col10']  =  trim($detemp['tarea']);
                 $data['col11'] =  trim($detemp['fuente']); 
                  
                 $response[]    = $data;
                $c++;
            }

            echo json_encode($response) ;
            


        }
        else if($item == 'conceptos_y_variables')
        {
              
                $name     = trim($this->input->get('name'));
                $t        = explode('*',$name);
                $p = (trim($t[0]) == '') ? trim($t[1]) : trim($t[0]);
                $name     = strtoupper($p);
 
                $tipo_pla = $this->input->get('fortipo');
                $tipo_pla =  $this->tipoplanilla->get_id($tipo_pla);
                $detalle  = $this->tipoplanilla->get_variables_conceptos($tipo_pla, $name);


                foreach($detalle as $det)
                {
 
                    $data =    array(
                                       'value'      => trim($det['codigo']),
                                       'name'       => trim($det['nombre']),
                                       'otro_dato'  => trim($det['alias'])
                                    );

                    $response[] = $data;
                    $c++;
                }
 

            echo json_encode($response);
        }
        
    }
    
    
    public function buscar_trabajador()
    {
         
         $this->load->library(array('App/tarea','Catalogos/dependencia','Catalogos/cargo','Catalogos/afp','Catalogos/banco','App/fuentefinanciamiento','App/grupoempleado'));
        
         $pla_key        = trim($this->input->post('view'));
         
         $pla_id         = $this->planilla->get_id($pla_key);
         
         $pla_info       = $this->planilla->get($pla_id);
         
         $tipos          = $this->tipoplanilla->get_all();
          
         $dependencias   = $this->dependencia->get_list();
         $cargos         = $this->cargo->get_list(); 
         $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
        
         $data = array();           

         $data['afps']         = $this->afp->load_for_combo(true);
         $data['bancos']       = $this->banco->get_list();
         $data['tareas']       = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ) ); 
         $data['fuentes']      = $this->fuentefinanciamiento->get_ff_tr( array('anio_eje' =>  $this->usuario['anio_ejecucion'] ) );   
        
         $grupos = $this->grupoempleado->get_list();

         

         $this->load->view('planillas/v_planilla_add_detalle', array(
                                                                    'plani_info'     => $pla_info,
                                                                    'tiposplanilla'  => $tipos,
                                                                    'dependencias'   => $dependencias, 
                                                                    'cargos'         => $cargos,
                                                                    'tipo_empleados' => $tipos_empleado, 
                                                                    'afps'           => $data['afps'],
                                                                    'bancos'         => $data['bancos'],
                                                                    'tareas'         => $data['tareas'],
                                                                    'fuentes'        => $data['fuentes'],
                                                                    'grupos'         => $grupos

                                                                    ));
        
    }
    
    
    
    public function agregar_empleado()
    {
         
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 

            $this->load->library(array('App/planillaempleado'));  
            
            $planilla_key = trim($this->input->post('p_c'));
            $empleado_key = trim($this->input->post('e_c'));
            
            $planilla_id = $this->planilla->get_id($planilla_key);

            if($this->planilla->existe($planilla_id) == FALSE)
            {

                $response =  array(
                    
                     'result'  => '0',
                     'mensaje' =>  'La planilla no esta operativa',
                     'data'    => array()
                );
                
                echo json_encode($response);

                die();
            }
             
            $empleados = explode('_', $empleado_key);
            array_shift($empleados);
             
            $count = 0;
            $a_vincular = sizeof($empleados);


            foreach($empleados as $emp_key)
            { 

               $emp_id = $this->persona->get_id($emp_key); 
               if($this->planillaempleado->registrar($planilla_id, $emp_id)){
                    $count++;

               } 
              
            }

            $id =true;

            $response =  array(
                
                 'result'  => ($id != false && $id != null)? '1' : '0',
                 'mensaje' => ($id != false && $id != null)?  ( $count.' empleados vinculados correctamente') : 'Ocurrio un error durante la operacion',
                 'data'    => array('key' => $key )
            );
        
        }
        else
        {

            $response =  array(
                
                 'result'  => '0',
                 'mensaje' =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data'    => array('key' => $key )
            );

        }


        echo json_encode($response);
        
        
    }


    public function quitar_empleado($tipo='especifico')
    {


        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 


            $this->load->library(array('App/planillaempleado'));

            if($tipo== 'especifico')
            {

                $detalle_key  = trim($this->input->post('detalle'));
                
                $pla_key      = trim($this->input->post('pla'));
                $pla_info     = $this->planilla->get($this->planilla->get_id($pla_key));
                
                $pla_id = $pla_info['pla_id'];
                
                $all_by_indiv =  false;

                if($pla_info['pla_tiene_categoria'] == '1' )
                {
                     $all_by_indiv =  true;  
                     $detalle_id   = $this->persona->get_id($detalle_key);
                }
                else
                {
                     $detalle_id = $this->planillaempleado->get_id($detalle_key);
                }


                $rs =  $this->planillaempleado->quitar_empleado($detalle_id, $all_by_indiv, $pla_id);

            }
            else if($tipo=='todos')
            {
                   
                  $pla_key = trim($this->input->post('pla'));
                  $pla_id  = $this->planilla->get_id($pla_key);
                  $rs      = $this->planilla->reset_planilla($pla_id);
     
            }


            $response =  array(
                
                 'result'  =>  ($rs)? '1' : '0',
                 'mensaje' => ($rs)? 'Detalle Eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
            );
            
        }
        else
        {
            $response =  array(
                
                 'result'  =>  '0',
                 'mensaje' =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data'    => array()
            );

        }

        echo json_encode($response);

    }
    

    public function quitar_detalle_conceptos()
    {

        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 

            $pla_key = $this->input->post('pla');
            $pla_id  = $this->planilla->get_id($pla_key);
            
            $rs      = $this->planilla->delete_all_detalle($pla_id);

            $response =  array(
                
                 'result'  => ($rs) ? '1' : '0',
                 'mensaje' => ($rs) ? 'Detalle Eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
            );
        }
        else
        {
            $response =  array(
                
                 'result'  =>  '0',
                 'mensaje' =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data'    => array()
            );

        }

        echo json_encode($response);

    }

    
    public function get_planillaempleado_detalle()
    {

        $this->load->library(array('App/planillaempleado', 'App/planillatipocategoria', 'App/ocupacion'));
         
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 
                 
                $codigo_plaemp = trim($this->input->post('codigo'));
                 
                $pla_key   = trim($this->input->post('pla_key'));
                $pla_id    = $this->planilla->get_id($pla_key);
                $rs_pla    = $this->planilla->get($pla_id);
                
                if($this->planilla->existe($pla_id) == FALSE)
                {
                    die('La planilla no esta operativa');
                }
 
                $estado_id =  $rs_pla['estado_id'];
                $plati_id  =  $rs_pla['plati_id'];

                $tiene_categorias = $rs_pla['pla_tiene_categoria'];    
                $tiene_categorias = ($tiene_categorias == '1') ? true : false;


                $pla_emp = $this->planillaempleado->get($codigo_plaemp, true);


                 if( $tiene_categorias && $pla_emp == null && $estado_id == ESTADOPLANILLA_ELABORADA ) // Si tiene categorias y no se encontro el plaemp entonces se asume que se envio un indiv_key
                 {
                      
                      $indiv_id      = $this->persona->get_id($codigo_plaemp);
                      $codigo_plaemp = $this->planillaempleado->get_first_plaempKey($indiv_id, $pla_id);
                      $pla_emp       = $this->planillaempleado->get($codigo_plaemp, true);
                 }


              
                if($pla_emp == null)
                {
                    
                    $mensaje = 'No se encontro el registro: '.$codigo_plaemp.' perteneciente al detalle de trabajador para la planilla: '.$pla_key;
                    $this->load->view('error_data', array('mensaje' => $mensaje));
                      
                } 
                else{ 
               
                    $plaemp_id =  $pla_emp['plaemp_id'];
                    $indiv_id  = $pla_emp['indiv_id'];
                    
                  
                    $variables              = array();
                    $para_impresion         = false;
                    $variables['variables'] = $this->planillaempleado->get_planillaempleado_variables($plaemp_id, true,  $para_impresion );
                    $variables['datos']     = $this->planillaempleado->get_planillaempleado_variables($plaemp_id, false, $para_impresion );

                    $solo_marcados = ($estado_id == ESTADOPLANILLA_ELABORADA) ? 0 : 1 ;
         
                    $conceptos                 = array();
                    $conceptos['ingresos']     = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_INGRESO, $solo_marcados );
                    $conceptos['descuentos']   = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_DESCUENTO, $solo_marcados);
                    $conceptos['aportaciones'] = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_APORTACION, $solo_marcados);

                    $conceptos['noafectos'] = $this->planillaempleado->get_planillaempleado_conceptos($plaemp_id, TIPOCONCEPTO_NOAFECTO,  $solo_marcados);

                    


                    if($pla_emp['tipo_pension'] == '0')
                    {

                        $pension = ' ::: NO AFILIADO A SISTEMA DE PENSIONES ';
         
                    } 
                    else if($pla_emp['tipo_pension'] == '1')
                    {

                        $pension = ' ::: ONP ';
         
                    }
                    else{

                        $pension = ' ::: AFP: '.$pla_emp['afp_nombre'].' - '.$pla_emp['modo_afp'];
                    }      


                    if($pla_emp['jubilado'] == '1') $pension.="  JUBILADO";
                    

                    $ocupaciones = $this->ocupacion->get_list();

                    $plati_info = $this->tipoplanilla->get($plati_id);
                    $plati_cuarta = $plati_info['plati_calcula_cuarta'];

                    $data_view = array(
                                           'detalle'           => $pla_emp,    
                                           'trabajador_nombre' => $pla_emp['empleado'].' (DNI: '.$pla_emp['empleado_dni'].') '.$pension,
                                           'variables'         => $variables,
                                           'conceptos'         => $conceptos,
                                           'tipo_planilla'     => $plati_id,
                                           'tiene_categorias'  => $tiene_categorias,
                                           'ocupaciones'       => $ocupaciones,
                                           'estado_planilla'   => $estado_id,
                                           'plati_cuarta'    => $plati_cuarta
                                      );
         

                    if($tiene_categorias)
                    {

                       $data_view['categorias'] = $this->planillatipocategoria->get_list($plati_id);  // TIPOS DE TRABAJADOR
                       $data_view['trabajador_categorias'] = $this->planillaempleado->get_categorias_planilla_trabajador($pla_id, $indiv_id); // GET TIPO X 

                       $id_tt = array(); 
                       $a_2   = array(); 

                       foreach( $data_view['categorias'] as $reg )
                       {
                              
                              $id_tt[] = $reg['platica_id'];  
                       }
         
                       foreach( $data_view['trabajador_categorias'] as $k => $reg)
                       {
          
                              unset($data_view['categorias'][array_search( $reg['platica_id'],$id_tt)]);    
                       }
                   
                    }



                    if($estado_id == ESTADOPLANILLA_ELABORADA)
                    {
                          
                        $this->load->view('planillas/v_planillaempleado_detalle', $data_view );
                    
                    }
                    else
                    {
                        
                        $this->load->view('planillas/v_planillaempleado_detalle_pro', $data_view );
                    }

                }
        }
        else
        {
             echo PERMISO_RESTRINGICO_MENSAJE;
        }
    }
    
    
    public function actualizacion_afectacion_detalle()
    {   

            $this->load->library(array('App/tarea','App/persona','App/planillaempleado'));

            $datos = $this->input->post();

            $planilla_key = trim($datos['planilla']);
    
            if($datos['categorias'] == '1')
            {
                $indiv_key    = trim($datos['view']);
                $indiv_id     = $this->persona->get_id($indiv_key);
            
                $planilla_id  = $this->planilla->get_id($planilla_key);
 
                $plaemp_key   = $this->planillaempleado->get_first_plaempKey($indiv_id, $planilla_id);

            }    
            else
            {
                $plaemp_key = trim($datos['view']);
                       
            }
    
            $info = $this->planillaempleado->get($plaemp_key, true); 

            $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ));

            $this->load->view('planillas/v_planilla_actualizar_afectacion_detalle', array('tareas' => $tareas, 'info' => $info ));

 
    }


    public function actualizar_afectacion_detalle()
    {

        $this->load->library(array('App/persona'));

        $datos        = $this->input->post();
        
        $planilla_key = trim($datos['planilla']);
        $indiv_key    = trim($datos['individuo']);       
        
        $planilla_id  = $this->planilla->get_id($planilla_key);
        $indiv_id     = $this->persona->get_id($indiv_key);
        
        $tarea        = $datos['tarea'];

        list($fuente, $tipo_recurso) = explode('-', trim($datos['fuente_financiamiento']) );


        $rs = $this->planilla->actualizar_afectacion_detalle(array(

                                                                'tarea'       => $tarea,
                                                                'fuente'      => $fuente,
                                                                'tiporecurso' => $tipo_recurso,
                                                                'individuo'   => $indiv_id,
                                                                'planilla'    => $planilla_id
        
                                                             ));


        $response =  array(
               
               'result'  =>  ($rs)? '1' : '0',
               'mensaje' =>  ($rs)? 'Afectacion del trabajador actualizada correctamente' : 'Ocurrio un error durante la operacion',
               'data'    =>  array()
        );
        
        echo json_encode($response);


    }
    
    
    public function procesar_planilla($planilla_id)
    {
         
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        {  
            // Buscar los empleados de la planilla
             
              $this->load->library(array('App/planillamovimiento'));
            
              $p_k =  trim($this->input->post('codigo'));
              $p_id = $this->planilla->get_id($p_k);
 
              $calcular_quinta =  (trim($this->input->post('calcular_quinta')) == '1') ? true : false;

              $calcular_cuarta =  (trim($this->input->post('calcular_cuarta')) == '1') ? true : false;
 
              if($this->planilla->existe($p_id) == FALSE)
              {

                  $response =  array(
                      
                       'result'  => '0',
                       'mensaje' =>  'La planilla no esta operativa',
                       'data'    => array()
                  );
                  
                  echo json_encode($response);

                  die();
              }

            
              $mensaje_error = 'Ocurrio un error durante la operacion';

              list($rs, $mensaje_error, $data) = $this->planilla->procesar($p_id, false ); // PLANILLA_ID, SI HAY AFECTACION PRESUPUESTAL
              
              if($rs)
              {

                  $this->planillamovimiento->registrar(array('pla_id' => $p_id, 
                                                             'plaes_id' => ESTADOPLANILLA_PROCESADA, 
                                                             'plamo_descripcion' => '' ));
         
                  
                  if(MODULO_QUINTA_CATEGORIA && PROCESAR_QUINTA_CATEGORIA && $calcular_quinta){ 

                        $this->load->library(array('App/impuestos/quintacategoria/quintacategoriadao'));
                        $this->quintacategoriadao->procesar($p_id);
                  }


                  if(MODULO_CUARTA_CATEGORIA && $calcular_cuarta){ 

                        $this->load->library(array('App/impuestos/cuartacategoria'));
                        $this->cuartacategoria->procesar($p_id);
                  }

                  /*
                  **
                  *****  Actualizar la afectación presupuestal segun corresponda. 
                  **
                  ****
                  */
                  
                  $this->planilla->sinc_afectacion_planilla($p_id);

              }      

               // Verificacion de negativos
               $comprobar_negativos = TRUE;
               $negativos = $this->planilla->get_detalle_resumen_full($p_id, $comprobar_negativos);

               $data_negativos = '0';

               if(sizeof($negativos) > 0 )
               {
                  $data_negativos = '1';
               }


               // Verificacion de pensiones 
               $rs_problema_pension = $this->planilla->comprobar_pension($p_id);

               $problemas_pension = '0';

               if(sizeof($rs_problema_pension) > 0 )
               {
                  $problemas_pension = '1';
               }

               $html_pp = '<span class="sp11"> Los trabajadores no tienen monto de pensión: </span> <br/> <ul>';

               foreach ($rs_problema_pension as $reg) {

                    $html_pp.= "<li> <span class='sp11'> ".$reg['indiv_appaterno']." ".$reg['indiv_apmaterno']." ".$reg['indiv_nombres']." </span> </li>";
 
               }

               $html_pp.="</ul>";
 
              
              $response =  array(

                     'result'   =>  ($rs)? '1' : '0',
                     'mensaje'  =>  ($rs)? 'Planilla procesada correctamente' : $mensaje_error,
                     'data'     =>  array('key' => $p_k, 
                                          'negativos' => $data_negativos,
                                          'problemas_pension' => $problemas_pension,
                                          'problemas_pension_mensaje' => $html_pp

                                          )
              );
        
        }
        else
        {
            
            $response =  array(

                   'result'   =>  '0',
                   'mensaje'  =>  PERMISO_RESTRINGICO_MENSAJE,
                   'data'     =>  array()
            );

        }
        
        echo json_encode($response);
         
    }

  
    public function ver_afectacion()
    {
           
        $pla_key    = trim($this->input->post('codigo')); 
        $pla_id     = $this->planilla->get_id($pla_key);

        $pla_info = $this->planilla->get($pla_id);
          
        if($this->planilla->existe($pla_id) == FALSE)
        {
 
            die('La planilla no esta operativa');
        }

        $this->load->view('planillas/v_planilla_afectacion', array(
                                                                   'view'       => $pla_key, 
                                                                   'afectacion' => $afectacion, 
                                                                   'afectados'  => $afectados,
                                                                   'planilla_info' => $pla_info

                                                                   ));
        
    }
    
    public function get_afectacion()
    {
        
        $pla_key = trim($this->input->get('codigo')); 
        $pla_id = $this->planilla->get_id($pla_key);
         
        $params = array();

        if( trim($this->input->get('tarea')) != '0' ){

             $params['tarea']= trim($this->input->get('tarea'));

        }

       if( trim($this->input->get('partida')) != '0' ){

             $c = explode('-', trim($this->input->get('partida')) );   
             $params['partida']=  $c[0];
             $params['ano_eje']=  $c[1];

        }

      
        $resumen = $this->planilla->get_afectacion_presupuestal($pla_id, $params);
              
        header("Content-Type: application/json");
             
        $start = 1;
        $end = 10;

        $data = array();
        $response = array();
        $c = 1;
    

        $total = sizeof($resumen);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     

        foreach($resumen as $reg){

           // $planilla['num_emps']= 0;

            $data['id'] =   'afectacion-'.$c;
            $data['col1'] = $c;
            $data['col2'] = trim($reg['tarea_codigo']).' - '.trim($reg['tarea_nombre']);
            $data['col3'] = trim($reg['partida']);
            
            $data['col4'] = trim($reg['fuente_codigo']).' - '.trim($reg['fuente']);


            $data['col5'] = sprintf("%.2f",trim($reg['total']));
            $data['col6'] = sprintf("%.2f",trim($reg['disponible']));

            $diferencia =   $reg['disponible'] - $reg['total'];

            $data['col7'] = sprintf("%.2f",trim($diferencia));
           // $data['col8'] = trim($reg['']);


            $response[] = $data;  
            $c++;
        }
 

            
        echo json_encode($response);
        
        
    }


    public function afectacion_presupuestal()
    {   
 
        $this->load->library(array('App/planillamovimiento')); 
        $pla_key= $this->input->post('planilla');

        $pla_id = $this->planilla->get_id($pla_key);

/*        $response =  array(
                    
                    'result'  => '0',
                    'mensaje' => 'Estamos dando mantenimiento a esta funcionalidad',
                    'data'    => array('key' => $pla_key     )
               );
               
        echo json_encode($response);
 
        die();
 */ 

        if(CONECCION_AFECTACION_PRESUPUESTAL)
        {
            
             $ok = $this->planilla->afectar_presupuestalmente($pla_id);
        }
        else
        {
            $ok =true;
        }
       
        if($ok)
        {

            $this->planillamovimiento->registrar(array('pla_id' => $pla_id, 
                                                       'plaes_id' => ESTADOPLANILLA_FINALIZADO, 
                                                       'plamo_descripcion' => '' ));
        
        }      

        $response =  array(
                    
                    'result'  => ($ok)? '1' : '0',
                    'mensaje' => ($ok) ?  'Afectacion Presupuestal realizada correctamente' : 'No se pudo realizar la operación',
                    'data'    => array('key' => $pla_key 

                       )
               );
               
        echo json_encode($response);

    }
    
    /*
    public function ver_resumen($tipo = ''){
        
      
        $pla_key = trim($this->input->post('codigo')); 
        $pla_id = $this->planilla->get_id($pla_key);
        
        $resumen = $this->planilla->get_resumen_conceptos($pla_id);
        
        $conceptos = array(); 
        $conceptos['ingresos']  = array();
        $conceptos['descuentos']  = array();
        $conceptos['aportaciones']  = array();
        
        $tipo_i = 0;
        $tipos  = array(1 => 'ingresos',
                        2 => 'descuentos',
                        3 => 'aportaciones');
        
        foreach($resumen as $x => $reg){
             
              $tipo = $tipos[$reg['conc_tipo']];
              $conceptos[$tipo][] =  $reg;
               
        }
        unset($resumen);
       /* 
      var_dump($conceptos);
             echo 'PLANILLA: '.$pla_id;
        
     
        
        $this->load->view('planillas/v_planilla_resumenconceptos', array('conceptos' => $conceptos));
        
    
        
        
        
    } 
    */
     
    
    public function get_resumen_conceptos($tipo)
    {
        
        $pla_key = trim($this->input->get('view')); 
        $pla_id = $this->planilla->get_id($pla_key);
        
        
        header("Content-Type: application/json");
            
            
        $start = 1;
        $end = 10;

        $data = array();
        $response = array();
        $c = 1;
        
        $tipo_id = '0';
        
        if($tipo=='ingresos')
        {
             $tipo_id = '1';
        }
        else if($tipo=='descuentos')
        {
            $tipo_id = '2';
        }
        else if($tipo=='aportaciones'){
            $tipo_id = '3';
        }
        

        $resumen = $this->planilla->get_resumen_conceptos($pla_id,$tipo_id);

        $total = sizeof($resumen);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     

        foreach($resumen as $reg){

           // $planilla['num_emps']= 0;

            $data['id'] =   'conc-'.trim($reg['concepto_id']);
            $data['col1'] = $c;
            $data['col2'] = trim($reg['conc_nombre']);
            $data['col3'] = trim($reg['partida']);
            $data['col4'] =  sprintf('%.2f',trim($reg['monto']));


            $response[] = $data;  
            $c++;
        }
 

            
        echo json_encode($response);
            
    }
    
    
   
    public function get_x_concepto_trabajadores()
    {
        
        $concepto = trim($this->input->get('concepto')); 
        $concs    = explode('-',$concepto);
        $conc_id  = $concs[1];
        
        $planilla = trim($this->input->get('planilla')); 
        $pla_id   = $this->planilla->get_id($planilla);
 
        
        header("Content-Type: application/json");
            
            
        $start = 1;
        $end = 10;

        $data = array();
        $response = array();
        $c = 1;
         
        $resumen = $this->planilla->get_concepto_trabajadores($pla_id,$conc_id);
 
        $total = sizeof($resumen);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     

        foreach($resumen as $reg){

   

            $data['id'] =   'detplacon-'.$c;
            $data['col1'] = $c;
            $data['col2'] = trim($reg['conc_nombre']);
            $data['col3'] = trim($reg['indiv_dni']);
            $data['col4'] = trim($reg['indiv_appaterno']);
            $data['col5'] = trim($reg['indiv_apmaterno']);
            $data['col6'] = trim($reg['indiv_nombres']);
            $data['col7'] =  sprintf('%.2f',trim($reg['monto']));


            $response[] = $data;  
            $c++;
        }
 

            
        echo json_encode($response);
            
    }
    
    
    public function importacion($tipo){
        

        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        {  

            if($tipo=='otra_planilla')
            { 
                $pla_key = trim($this->input->post('planilla'));
                $pla_id = $this->planilla->get_id($pla_key);
             
                $this->load->view('planillas/v_planilla_importar_otraplanilla');
            } 
            else if($tipo=='hojaasistencia')
            {   

                $this->load->library(array('App/tarea','App/hojaasistencia', 'App/hojadiariotipo'  ));

                $pla_key = trim($this->input->post('planilla'));
                $pla_id  = $this->planilla->get_id($pla_key);
                $planilla_info = $this->planilla->get($pla_id);

                $plati_id = trim($planilla_info['plati_id']);

                $config =  $this->hojaasistencia->get_plati_config($plati_id); 
                
                $anio   = $this->usuario['anio_ejecucion'];
                  
                $tareas     = $this->tarea->get_list(array('ano_eje' => $anio ));     

                
                $estructura = array(
                               array( 
                                        array(
                                            'Selector',
                                            array('label' => '#',          'field' => 'col1'),
                                            array('label' => 'Trabajador', 'field' => 'col2'),
                                            array('label' => 'DNI',        'field' => 'col3'),
                                            array('label' => 'Categoria',  'field' => 'col4')
                                        )
                                    ),

                               array(

                                     array()
                                )

                            );

                $solo_para_importar = true;
              
                $estados_dia = $this->hojadiariotipo->get($plati_id, false, $solo_para_importar );   

                $col_id = 5;
                foreach ($estados_dia as $reg)
                {   
                     $estructura[1][0][] = array('label' => strtoupper(trim($reg['hatd_label'])), 
                                                 'field' => ('col'.$col_id));    
                     $col_id++;
                }

 
                $this->load->view('planillas/v_asistencias_importacion_planilla', array( 'anio'     => $anio,  
                                                                                         'tareas'   => $tareas, 
                                                                                         'planilla_info' => $planilla_info,
                                                                                         'config'   => $config,
                                                                                         'estruct'  => $estructura 

                                                                                        ) );   

            }
            else
            {

            }

        }
        else
        {
             echo PERMISO_RESTRINGICO_MENSAJE;
        }

    }


    public function importar($tipo)
    { 

        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        {  

            if($tipo== 'otra_planilla')
            { 
                $this->load->library('App/planillaempleado');

                $pla_target = trim($this->input->post('target'));

                $pla_source = trim($this->input->post('source'));

                $id_source = $this->planilla->get_id($pla_source);
                $id_target = $this->planilla->get_id($pla_target);
 
                $a_migrar =  $this->planillaempleado->get_list($id_source);

                foreach($a_migrar as $k => $det )
                {

                     $this->planillaempleado->registrar($id_target, trim($det['indiv_id']) );
       
                }
                $ok = true;
 
            } 
            else if($tipo== 'hoja_asistencia')
            {

                $this->load->library(array('App/hojaasistencia', 'App/persona', 'App/planillaasistencia'));
 
                $data = $this->input->post();

                foreach ($data as $key => $value)
                {
                    $data[$key] = trim($value);
                }

                $params = array();

                $pla_key   = $data['planilla'];
                $pla_id    = $this->planilla->get_id($pla_key);
                $planilla_info = $this->planilla->get($pla_id);
                $plati_id = $planilla_info['plati_id'];

                $params['planilla'] = $pla_id;
                $params['plati_id'] = $plati_id;
                
                $views  = $data['hojas'];
                $hojas_key = explode('_',$views);
                array_shift($hojas_key);

                $hojas_ids = array();

                foreach ($hojas_key as $key)
                {
                     $hojas_ids[] =  $this->hojaasistencia->get_id($key);
                }    

                $params['anio'] =  $this->usuario['anio_ejecucion'];
                $params['hojas'] =  $hojas_ids;
                
                $params['fecha_inicio'] = $data['fechadesde'];
                $params['fecha_fin'] = $data['fechahasta'];
                

                $params['categoria'] = $data['categoria'];
                $params['dni'] = $data['dni'];
                $params['grupo'] = $data['grupo'];

                $trabajadores = explode('_', $data['trabajadores']);
                array_shift($trabajadores);
                
                $trabajadores_a_importar = array();

                foreach ($trabajadores as $indiv_data)
                {
                   list($indiv_key, $platica_id) = explode('|', $indiv_data);
                   $indiv_id = $this->persona->get_id($indiv_key);

                   $trabajadores_a_importar[] = array($indiv_id, $platica_id);
                }
                
                $params['trabajadores'] = $trabajadores_a_importar;

                $params['user_id'] = $this->usuario['user_id'];
 
                $ok = $this->planillaasistencia->importar($params); 
 
            }
            else
            {
                    $ok = false;
            }


           $response =  array(
            
                     'result'  =>  ($ok)? '1' : '0',
                     'mensaje' => ($ok) ?  'Registros importados correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    => array('key' => $key )
                );
        }
        else
        {

            $response =  array(
             
                      'result'  => '0',
                      'mensaje' => PERMISO_RESTRINGICO_MENSAJE,
                      'data'    => array('key' => $key )
                 );
        }        
       
        echo json_encode($response);

    }


    public function detalle_otraplanilla(){


        $pla_id = $this->planilla->get_id( trim($this->input->post('planilla')) );
        $planilla_info = $this->planilla->get($pla_id);
      //  var_dump($planilla_info);

        $this->load->view('planillas/v_importar_otradetalle', array('info' => $planilla_info ) );



    }

    public function desvincular_hoja()
    {

        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 

            $pla_id = $this->planilla->get_id($this->input->post('planilla'));
            $ok = $this->planilla->desvincular_hoja($pla_id);

           $response =  array(
            
                     'result'  =>  ($ok)? '1' : '0',
                     'mensaje' => ($ok) ?  'Operacion completada correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    => array('key' => $key )
                );
         }
         else
         {

             $response =  array(
              
                       'result'  => '0',
                       'mensaje' => PERMISO_RESTRINGICO_MENSAJE,
                       'data'    => array('key' => $key )
                  );
         }        
        
         echo json_encode($response);

    }

    public function cancelar_proceso()
    {
        
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 

             $this->db->trans_begin();

             $pla_key = trim($this->input->post('key'));
             $pla_id  = $this->planilla->get_id($pla_key);
 
             if($this->planilla->existe($pla_id) == FALSE)
             {

                 $response =  array(
                     
                      'result'  => '0',
                      'mensaje' =>  'La planilla no esta operativa',
                      'data'    => array()
                 );
                 
                 echo json_encode($response);

                 die();
             }


             $ok  = $this->planilla->cancelar_proceso($pla_id); 

             
             if(MODULO_QUINTA_CATEGORIA){

                 $this->load->library(array('App/impuestos/quintacategoria/quintacategoriadao'));

                 $this->quintacategoriadao->delete_calculo_quinta_planilla($pla_id);
             }


             if(MODULO_CUARTA_CATEGORIA){

                 $this->load->library(array('App/impuestos/cuartacategoria'));

                 $this->cuartacategoria->delete_calculo_cuarta_planilla($pla_id);
             }
             
             $this->planilla->actualizar_calculo_conceptos($pla_id);
             $this->planilla->actualizar_datos_trabajador_planilla($pla_id);


            if($this->db->trans_status() === FALSE) 
            {
                $this->db->trans_rollback();
                $ok = false;
                    
            }else{
                        
                $this->db->trans_commit();
                $ok = true;
            } 

             $response =  array(
                         
                         'result'  => ($ok)? '1' : '0',
                         'mensaje' => ($ok) ?  'Proceso cancelado' : 'Ocurrio un error durante la operacion',
                         'data'    => array('key' => $pla_key 

                            )
                      );
        
       }
       else
       {

           $response =  array(
            
                     'result'  => '0',
                     'mensaje' => PERMISO_RESTRINGICO_MENSAJE,
                     'data'    => array('key' => $key )
                );
       }        
             

         echo json_encode($response);

    }


    public function anular_proceso()
    {

         if( $this->user->has_key('PLANILLAS_ELABORAR') )
         { 
 
              $data = $this->input->post();

              $pla_key = trim($this->input->post('key'));
              $pla_id  = $this->planilla->get_id($pla_key);
        
              if($this->planilla->existe($pla_id) == FALSE)
              {

                  $response =  array(
                      
                       'result'  => '0',
                       'mensaje' =>  'La planilla no esta operativa',
                       'data'    => array()
                  );
                  
                  echo json_encode($response);

                  die();
              }
  
              $params = array('motivo' => trim($data['motivo']));

              $ok  = $this->planilla->anular_proceso($pla_id, $params);
             
  
              $response =  array(
                            
                            'result'  => ($ok)? '1' : '0',
                            'mensaje' => ($ok) ?  'Proceso anulado correctamente' : 'Ocurrio un error durante la operacion',
                            'data'    => array('key' => $pla_key 

                               )
                         );
         
        }
        else
        {

            $response =  array(
             
                      'result'  => '0',
                      'mensaje' => PERMISO_RESTRINGICO_MENSAJE,
                      'data'    => array('key' => $key )
                 );
        }        
              

          echo json_encode($response);

    }

    public function eliminar()
    {
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 


            $pla_key = trim($this->input->post('key'));
            $pla_id  = $this->planilla->get_id($pla_key);

            $ok = $this->planilla->eliminar($pla_id);

            $response =  array(
                
                         'result' =>  ($ok)? '1' : '0',
                         'mensaje'  => ($ok) ?  'Planilla eliminada' : 'Ocurrio un error durante la operacion',
                         'data' => array('key' => $pla_key )
                    );
        
        }
        else
        {

            $response =  array(
             
                      'result'  => '0',
                      'mensaje' => PERMISO_RESTRINGICO_MENSAJE,
                      'data'    => array('key' => $key )
                 );
        }        

         echo json_encode($response);

    }


    public function set_categoria_trabajador()
    {
        
        if( $this->user->has_key('PLANILLAS_ELABORAR') )
        { 
              
              $this->load->library(array('App/planillaempleado'));
               
              $plaemp_key = $this->input->post('detalle');
              $plaemp_id  = $this->planillaempleado->get_id($plaemp_key);
              
              $categoria  = $this->input->post('tipo');
              
              $det_info   = $this->planillaempleado->get($plaemp_id);
              
              $indiv_id   = $det_info['indiv_id'];
              $pla_id     = $det_info['pla_id'];
             
              $afectacion_individuo = array(); 

            if($det_info['platica_id'] == '0' )
            {     
                // Si el trabajador no tiene ninguna categoria y esta es la primera
                $this->planillaempleado->quitar_empleado($plaemp_id);
            
            }  
            
            list($plaemp_id, $plaemp_key)  =  $this->planillaempleado->registrar( $pla_id, $indiv_id, $categoria, true );

          
            $response =  array(
                
                         'result'  =>  ($plaemp_id) ? '1' : '0',
                         'mensaje' =>  ($plaemp_id) ?  'Categoria asignada al trabajador correctamente' : 'Ocurrio un error durante la operacion',
                         'data'    =>  array('key' => $plaemp_key )
                    );

        
        }
        else
        {

            $response =  array(
             
                      'result'  => '0',
                      'mensaje' => PERMISO_RESTRINGICO_MENSAJE,
                      'data'    => array('key' => $key )
                 );
        }        
                
        echo json_encode($response);

    } 

    public function anulacion()
    {

        $this->load->view('planillas/v_planilla_anular');

    }

    public function anular_planilla()
    {
            
    }
    

    public function boletas_individuales()
    {   

        $datos = $this->input->post();
        $dni = '';
 
        if($datos['trabajador_key'] != ''){

            $indiv_id = $this->persona->get_id($datos['trabajador_key']);
            $indiv_info = $this->persona->get_info($indiv_id);  
            $dni = $indiv_info['indiv_dni'];
        }

        $this->load->view('planillas/v_boletasindividuales', array('dni' => $dni));
    }


    public function registro_boletas()  
    {

        header("Content-Type: application/json");

        $meses = array('01' => 'ENERO',
                        '02' => 'FEBRERO',
                        '03' => 'MARZO',
                        '04' => 'ABRIL',
                        '05' => 'MAYO',
                        '06' => 'JUNIO',
                        '07' => 'JULIO',
                        '08' => 'AGOSTO',
                        '09' => 'SEPTIEMBRE',
                        '10' => 'OCTUBRE',
                        '11' => 'NOVIEMBRE',
                        '12' => 'DICIEMBRE');

        $this->load->library(array('App/planillaempleado'));

        $data = $this->input->get();

        $indiv_dni = trim($data['dni']);
        $rs_persona = $this->persona->get_some_info($indiv_dni, 1);
        $indiv_id = $rs_persona['indiv_id'];

    
        $start = 1;
        $end = 10;
          
        $params  = array();
       
  
        $rs = $this->planillaempleado->registro_boletas($indiv_id, $params);  
    
   /*     $planillas = $this->planilla->get_list($params);

        $total = sizeof($planillas);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     
        
        $tipo =  $this->input->get('tipo');
        $tipo = $this->tipoplanilla->get_id($tipo);
        
        $q = array('tipo' => $tipo);

        if( $this->input->get('just')  == 'procesadas'){
             $q['estado'] = ESTADOPLANILLA_PROCESADA;
        }


        $planillas = $this->planilla->get_list($q);
        
        $total = sizeof($planillas);

        header("Content-Range: " . "items ".$start."-".$total."/".$total);     
        */

        $response = array();
        $c = 1;


        foreach($rs as $reg)
        {   
    
            if($reg['pla_fecini'] != '')
            {
               $periodo = $reg['pla_fecini'].' al '.$reg['pla_fecfin'];   
            }
            else
            {
                $periodo =  '-----';
            }

            if( trim($reg['ocupacion']) == '' && trim($reg['ocupacion_label']) == '' )
            {
               $ocupacion = trim($reg['categoria']);  
            }
            else
            {
                $ocupacion = ( trim($reg['ocupacion_label']) == '' ) ?  trim($reg['ocupacion']) : trim($reg['ocupacion_label']);

            }

            if($ocupacion == '') $ocupacion = ' ------- ';

              $data['id']   = trim($reg['plaemp_key']);
              $data['planilla']   = trim($reg['pla_key']);
              $data['col1'] = $c;
              $data['col2'] = trim($reg['indiv_dni']);
              $data['col3'] = trim($reg['pla_anio']);
              $data['col4'] = $meses[trim($reg['pla_mes'])];
              $data['col5'] = trim($reg['pla_codigo']);
              $data['col6'] = $periodo;
              $data['col7'] = trim($reg['plati_nombre']);
              $data['col8'] = $ocupacion;
              $data['col9'] = trim($reg['ingresos']);
              $data['col10'] = trim($reg['descuentos']);
              $data['col11'] = trim($reg['neto']);
              $data['col12'] = trim($reg['aportacion']);
              
              $response[] = $data;
              $c++;
        }
 

        echo json_encode($response) ;
        

    }


    public function registrar_siaf()
    {
         $pla_key = $this->input->post('planilla');   

         $modo = $this->input->post('modo');

         if($modo != 'multiple')
         {
            $pla_id = $this->planilla->get_id($pla_key);
            $fuentes = $this->planilla->get_afectacion_fuentes($pla_id);
            $planillas = array();
            $modo = '';
         }
         else
         {
            $pla_keys = explode('_',$pla_key);
            array_shift($pla_keys);
            $pla_id = array();

            foreach ($pla_keys as $key)
            {
                 $pla_id[] =  $this->planilla->get_id($key);
            }    

            $planillas = $this->planilla->get_codigos($pla_id);

            $fuentes = $this->planilla->get_afectacion_fuentes($pla_id);
         }



         $this->load->view('planillas/v_planilla_registrar_siaf', array('fuentes' => $fuentes, 'planillas' => $planillas, 'modo' => $modo, 'keys' => $pla_key ));
    }

    public function actualizar_siaf()
    {

        $this->load->library(array('App/planillasiaf'));

        $data = $this->input->post();   

        $pla_id = array();

        if($data['modo'] == 'multiple')
        {   
            $pla_key = $this->input->post('keys');   
            $pla_keys = explode('_',$pla_key);
            array_shift($pla_keys);

            foreach ($pla_keys as $key)
            {
                 $pla_id[] =  $this->planilla->get_id($key);
            }    
            
        }
        else
        {
             $view = $this->planilla->get_id($data['view']);
             $pla_id[] = $view;
        }

        list($fuente_id, $tipo_recurso) = explode('_', trim($data['ff']) );
        $siaf = trim($data['siaf']);

        if(  !is_numeric($siaf) || $fuente_id == '' ||  $tipo_recurso == '')
        {
 
            $response =  array(
                
                         'result'  =>  '0',
                         'mensaje' =>  'Algo no esta bien',
                         'data'    =>  array('key' => '' )
                    );
                    
            echo json_encode($response);

            die();
        }


        // INICIO BUCLE     
        foreach ($pla_id as $pla)
        { 
            $params = array();
            $params['pla_id']       =  $pla;
            $params['fuente_id']    =  $fuente_id;
            $params['tipo_recurso'] =  $tipo_recurso;
            $params['siaf']         =  $siaf;
            $params['ano_eje'] = $this->usuario['anio_ejecucion'];

            list($plasi, $plasi_key) = $this->planillasiaf->registrar($params, true);
 
             
        }

        // FIN BUCLE 

        $response =  array(
            
                     'result'  =>  ($plasi) ? '1' : '0',
                     'mensaje' =>  ($plasi) ?  'Numero Siaf guardado satisfactoriamente' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $plasi_key )
                );
                
        echo json_encode($response);



    }


    public function modificar_afectacion()
    {

         $this->load->library(array('App/tarea','App/fuentefinanciamiento','App/partida'));

         $view = $this->input->post('view');

         $pla_id = $this->planilla->get_id($view);

         $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ));
          
         if(CONECCION_AFECTACION_PRESUPUESTAL == false)
         {
             $partidas      = $this->partida->listar($null,$this->usuario['ano_eje'], array('tipo_transaccion' => '2'));    
         }
         else
         {
             $partidas = array();
         }
         

         $planilla_info =  $this->planilla->get($pla_id);

         $this->load->view('planillas/v_planilla_modificar_afectacion', array( 'plani_info' => $planilla_info, 
                                                                               'tareas' => $tareas,
                                                                               'partidas_presupuestales' => $partidas ));   

    }


    public function actualizar_afectacionplanilla()
    {

        $data = $this->input->post();

        $planilla_id = $this->planilla->get_id($data['view']);

        if($data['afectacion_especificada'] == 1)
        {
                $data_ff      = explode('-',trim($data['fuente_financiamiento']));
                $fuente_id    = trim($data_ff[0]);
                $recurso_id   = trim($data_ff[1]);
                

                if($data['especificar_clasificador'] == '1')
                {

                    $clasificador = trim($data['clasificador']);
                }
                else
                {
                    $clasificador = '';
                }

                if($data['tarea'] != '0')  $tarea_id  = ( trim($data['tarea']) == '') ? '0' : trim($data['tarea']) ;
                
                if($fuente_id == '' || $recurso_id == '' || $tarea_id == '0' || $tarea_id == '' ) $error = true;
        }
        else
        {
            $fuente_id    = '';
            $recurso_id   = '';
            $tarea_id     = 0;
            $clasificador = '';
        } 



        $params =  array(   'tipo_afectacion'=> $data['afectacion_especificada'], 
                            'tarea'          => $tarea_id,
                            'fuente'         => $fuente_id,
                            'tipo_recurso'   => $recurso_id,
                            'clasificador'   => $clasificador,
                            'especificar_clasificador' => $data['especificar_clasificador']     );


        $rs = $this->planilla->actualizar_afectacion_planilla($planilla_id, $params);

        $response =  array(
            
                     'result'  =>  ($rs) ? '1' : '0',
                     'mensaje' =>  ($rs) ?  'Actualizacion realizada correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $data['view'] )
                );
                
        echo json_encode($response);



    }   


    public function modificar_descripcion()
    {
        $view = $this->input->post('view');

        $pla_id = $this->planilla->get_id($view);

         $planilla_info =  $this->planilla->get($pla_id);

         $this->load->view('planillas/v_planilla_actualizar_descripcion', array( 'plani_info' => $planilla_info, 'tareas' => $tareas ));   

    }

    public function actualizar_descripcionplanilla()
    {

        $data = $this->input->post();

        $pla_id = $this->planilla->get_id($data['view']);
        $descripcion = strtoupper(trim($data['descripcion']));
         

        $rs = $this->planilla->actualizar($pla_id , array('pla_descripcion' => $descripcion) , false);
            
        $response =  array(
            
                     'result'  =>  ($rs) ? '1' : '0',
                     'mensaje' =>  ($rs) ?  'Actualizacion realizada correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $data['view'] )
                );
                
        echo json_encode($response);


    }


    public function actualizar_ocupacion_boleta()
    {

        $this->load->library(array('App/ocupacion','App/planillaempleado'));

        $data = $this->input->post();

        $view = $data['detalle'];

        $plaemp_id = $this->planillaempleado->get_id($view);

        if($data['modo'] == '1')
        {

            if( trim($dat['ocupacion']) == '')
            {

                list($ocupacion_id, $ocupa_key) = $this->ocupacion->registrar(array(  'ocu_nombre' => strtoupper(trim($data['ocupacion_label'])) ),true);
            
            }
            else{

                $ocupacion_id = trim($data['ocupacion']);
            }

            // Actualizar
            
           $rs = $this->planillaempleado->actualizar_ocupacion(array('id' => $plaemp_id, 'modo' => '1', 'valor' => $ocupacion_id));

        }
        else if($data['modo'] == '2')
        {   
            // x
            $label =  trim($data['ocupacion_label']);

           $rs = $this->planillaempleado->actualizar_ocupacion(array('id' => $plaemp_id, 'modo' => '2', 'valor' => $label));

        }
        else
        {
            $rs = true;
        }



        $rs = true;

        $response =  array(
            
                     'result'  =>  ($rs) ? '1' : '0',
                     'mensaje' =>  ($rs) ?  'Ocupación actualizada' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $data['view'] )
                );
                
        echo json_encode($response);


    }

    public function reporte_por_conceptos()
    {

        $tipos  = $this->tipoplanilla->get_all();
        
        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;


        $this->load->view('planillas/v_planilla_reportedescuentos', array( 'tipos' => $tipos, 'anios' => $anios ) );     

    }

    public function reporte_por_concepto_mes()
    {

        $this->load->library(array('App/tarea','App/grupovc'));

        $tipos  = $this->tipoplanilla->get_all();
        
        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;

        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ));

        $grupos        = $this->grupovc->get_list();

        $this->load->view('planillas/v_planilla_reporteconcepto_mes', array( 'tipos' => $tipos, 'anios' => $anios, 'tareas' => $tareas, 'grupos' => $grupos ) );     

    }

    public function inject_conxcepto()
    {


         $this->load->library('App/concepto'); 
         
         $sql ="  SELECT plaec_id 
                          FROM planillas.planilla_empleados plaemp 
                          LEFT JOIN  planillas.planilla_empleado_concepto pec ON pec.plaemp_id = plaemp.plaemp_id
                          
                    WHERE  pec.conc_id = 552
                    
                   ";

         
                            
         $empleados_conceptos = $this->db->query($sql, array($planilla_id))->result_array();
         
          foreach($empleados_conceptos as $empleado_concepto)
          {
             
            $this->concepto->procesar($empleado_concepto['plaec_id']);
              
          } 


    }

    public function asistencias_importadas()
    {   

        $this->load->library(array('App/planillaasistencia'));

        $data = $this->input->post();

        $pla_id =  $this->planilla->get_id($data['planilla']); 

        $planilla_info = $this->planilla->get($pla_id);

        $planilla_importaciones = $this->planillaasistencia->get($pla_id);
 
        $this->load->view('planillas/v_planilla_registroimportaciones', array('importaciones' => $planilla_importaciones, 'planilla_estado' => $planilla_info['estado_id'] ));
    }


    public function registro_importaciones()
    {


        header("Content-Type: application/json");
         

        $this->load->library(array('App/planillaasistencia'));

        $data = $this->input->get();

        $pla_id = $this->planilla->get_id($data['planilla']);

        $rs =  $this->planillaasistencia->get($pla_id);

        $c = 1;

        $response = array();

        foreach($rs as $reg)
        {   
            $data = array();

            $data['id']   =  trim($reg['plaasis_key']);
            $data['col1'] =  $c;
            $data['col2'] =  _get_date_pg(trim($reg['plaasis_fecreg'])).' '._get_date_pg(trim($reg['plaasis_fecreg']), 'hora');
            $data['col3'] =  trim($reg['plaasis_memo']);
            $data['col4'] =  trim($reg['usuario']); 
        
            $response[] = $data;
            $c++;
        }


        echo json_encode($response);

    }


    public function resumen_descuentos_medicos(){

        $datos = $this->input->post();
            
        $pla_id = $this->planilla->get_id($datos['planilla']);
     
        $planilla_info = $this->planilla->get($pla_id);

        $anio = $planilla_info['pla_anio'];
        
        $this->load->view('planillas/v_resumen_descansosmedicos_planilla', array('anio' => $anio));
    }

 
    public function detalle_hoja_importada()
    {
    
    }
   

    public function get_descansos_medicos_anio(){

        $this->load->library(array('App/descansomedico'));

        $datos = $this->input->get();

        $planilla_id = $this->planilla->get_id($datos['planilla']);

        $rs = $this->descansomedico->get_descansos_medicos_planilla_anio($planilla_id);
        
        $response = array();
        $c = 1;
   
        foreach($rs as $reg)
        {   
            $data = array();

            $data['id']   =  trim($reg['indiv_id']);
            $data['num'] =  $c;
            $data['trabajador'] =  trim($reg['trabajador_nombre']);
            $data['dni'] =  trim($reg['trabajador_dni']);
            $data['anio'] =  trim($reg['pla_anio']); 
            $data['dias_dm'] =  trim($reg['dias_dm_escalafon']); 
            $data['planilla_dm'] =  trim($reg['dm_planilla']); 
            $data['horas_anio_dm'] =  (trim($reg['dm_anio_horas'])=='' ? '----' : trim($reg['dm_anio_horas'])); 
            $data['dias_anio_dm'] =   (trim($reg['dm_anio_dias'])=='' ? '----' : trim($reg['dm_anio_dias']));
        
            $response[] = $data;
            $c++;
        }


        echo json_encode($response);

    }


    public function white_view()
    {
        /* VISTA EN BLANCO */
        
    }
    
/*
    public function cuadrar_pensionable()
    {

            $this->planilla->procesar();
    }*/

}