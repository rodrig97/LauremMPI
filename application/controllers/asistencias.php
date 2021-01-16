<?php
 /* 
    @CAMBIOS3008
*/ 
if(!defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class asistencias extends CI_Controller {
    
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
        
        $this->load->library(array('App/persona','App/hojaasistencia','App/tipoplanilla','App/anioeje'));
 
    }

    public function nueva_hoja()
    {

        $this->load->library(array('App/tarea'));
  
        $tipos = $this->tipoplanilla->get_all($estado, array('tipo_registro_asistencia' => '1')); 

        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;
 
        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));
         
        $this->load->view('planillas/v_asistencias_nuevahoja', array('tipos' =>$tipos, 'tareas' => $tareas, 'anios' => $anios));
        
    }

    public function registrar_hoja()
    {
        
        $data = $this->input->post();
        if($data === false) die('');

        foreach($data as $i =>  $x) $data[$i] = trim($x);

        $plati_id = $this->tipoplanilla->get_id($data['planillatipo']);

        $rs_plati = $this->tipoplanilla->get($plati_id);

        $data['tienecategorias'] = ($rs_plati['plati_tiene_categoria'] == '1' ? '1' : '0');

        $data['tarea'] = ($data['tarea'] == '') ? 0 : $data['tarea'];


        list($id,$key) = $this->hojaasistencia->registrar( array(
                                                                 'anio_eje'        => $this->usuario['anio_ejecucion'],
                                                                 'hoa_anio'        => $data['anio'],
                                                                 'hoa_fechaini'    => $data['fechadesde'],
                                                                 'hoa_fechafin'    => $data['fechahasta'],
                                                                 'plati_id'        => $plati_id,
                                                                 'hoa_descripcion' => $data['descripcion'],
                                                                 'tarea_id'        => $data['tarea'],
                                                                 'hoa_tienecategorias' => $data['tienecategorias'],
                                                                 'user_id'          => $this->usuario['user_id']
                                                                 ), true);

       $response =  array(
            
             'result' =>  ($id != false && $id != null)? '1' : '0',
             'mensaje'  => ($id != false && $id != null)? 'Hoja de asistencia registrada satisfactoriamente' : $mensaje_error,
             'data' => array('key' => $key )
        );
        
        echo json_encode($response);

    }


    public function buscar_trabajador()
    {
 
        $this->load->library(array('App/tarea','Catalogos/dependencia','Catalogos/cargo', 'App/planillatipocategoria', 'App/hojaasistenciagrupo'));
        
        $asis_key = trim($this->input->post('view'));
        
        $asis_id  = $this->hojaasistencia->get_id($asis_key);
        
        $hoja_info = $this->hojaasistencia->get($asis_id);
         
        $tipos = $this->tipoplanilla->get_all();
        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));
       
        $dependencias = $this->dependencia->get_list();
        $cargos = $this->cargo->get_list(); 
        $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 

        $config =  $this->hojaasistencia->get_plati_config($hoja_info['plati_id']); 

        
        if($hoja_info['hoa_tienecategorias'] == '1')
        {

             $categorias =   $this->planillatipocategoria->get_list($hoja_info['plati_id']);
        }

        if($config['grupo_trabajadores'] == '1')
        {

            $grupos =  $this->hojaasistenciagrupo->get_list(); 
        }
        else
        {
            $grupos = array();
        }

        
        $this->load->view('planillas/v_asistencia_add_detalle', array('hoja_info'      => $hoja_info,
                                                                      'tiposplanilla'  => $tipos,
                                                                      'dependencias'   => $dependencias, 
                                                                      'cargos'         => $cargos,
                                                                      'tipo_empleados' => $tipos_empleado, 
                                                                      'tareas'         => $tareas,
                                                                      'categorias'     => $categorias,
                                                                      'grupos'         => $grupos,
                                                                      'config'         => $config

                                                                       ));



    }



    public function provide($tipo = '')
    {

        $this->load->library(array('App/tipoplanilla'));

        $params = $this->input->get();

        foreach($params as $i =>  $x) $params[$i] = trim($x);
        
        $params['user_id'] = $this->usuario['user_id'];

        $params['restringir_a_codigo'] = true;
 
        if( $tipo=='importacion' ||  $this->user->has_key('ASISTENCIAS_ACCESOCOMPLETO_SOLOVER') ||  $this->user->has_key('ASISTENCIAS_ACCESOCOMPLETO') || SUPERACTIVO === TRUE  ) // Si tiene el permiso de supervi
        {    
            $params['user_id'] = '';
        }

        if($tipo=='importacion')
        {
              $params['restringir_a_codigo'] = false;
              $params['orderby'] = 'fecha-tarea';
             // $params['estado'] = HOJAASIS_ESTADO_TERMINADO;  
        } 

        if( trim($params['from']) != '')
        {
             $from_id = $this->hojaasistencia->get_id(trim($params['from']));

             $params['hoja_desde'] = $from_id;
        }
        
        $rs = $this->hojaasistencia->get_list($params);  

        $c = 1;
        $start = 1;
        $end = 10;
        $total = sizeof($rs);

            header("Content-Range: " . "items ".$start."-".$total."/".$total);     
            $data = array();
            $response = array();

       
           if($tipo == '')
           {

                foreach($rs as $reg)
                {
                     
                    $data['id']   =   trim($reg['hoa_key']);
                    $data['col1'] =  $c;
                    $data['col2'] =  trim($reg['hoa_codigo']); 
                    $data['col3'] =  trim($reg['estado']);
                    $data['col4'] =  trim($reg['tipo_planilla']);
                    $data['col5'] =  (trim($reg['proyecto']) == '') ? '--------------' : trim($reg['proyecto']);
                    $data['col6'] =  (trim($reg['hoa_descripcion']) == '') ? '--------------' : trim($reg['hoa_descripcion']);
                    $data['col7'] =  _get_date_pg(trim($reg['hoa_fechaini']));
                    $data['col8'] =  _get_date_pg(trim($reg['hoa_fechafin']));
                    $data['col9'] =  trim($reg['cant_trab']);
                    $data['col10'] =  trim($reg['usuario']);
                    $data['info_import'] =  trim($reg['porcentaje_importacion']);

                    $response[] = $data;
                    $c++;
                }
           }
           else if($tipo == 'importacion')
           {

                 foreach($rs as $reg)
                 {
                     
                    $data['id']   =   trim($reg['hoa_key']);
                    $data['col1'] =  $c;
                    $data['col2'] =  trim($reg['hoa_codigo']);  
                    $data['col3'] =  _get_date_pg(trim($reg['hoa_fechaini']));
                    $data['col4'] =  _get_date_pg(trim($reg['hoa_fechafin']));
                    $data['col5'] =   trim($reg['cant_trab']);
                    $data['col6'] =   trim($reg['meta_codigo']);
                    $data['info_import'] =  trim($reg['porcentaje_importacion']);
                    $response[] = $data;
                    $c++;
                }

           }

           echo json_encode($response); 


    } 

    public function trabajadores_hoja()
    {

        $data = $this->input->get();

        $hoja_fuente = $this->hojaasistencia->get_id($data['view']);

        $hoja_destino = $this->hojaasistencia->get_id($data['hoja']);

        $params = array();

        $params['hoja'] = $hoja_fuente;

        $rs =  $this->hojaasistencia->get_trabajadores($params);

        $response = array();
        $c = 1;

        foreach($rs as $reg)
        {
             
            $data['id']   =   trim($reg['indiv_key']).'-'.$reg['platica_id'];
            $data['col1'] =  $c;
            $data['col2'] =  trim($reg['indiv_appaterno']).' '.trim($reg['indiv_apmaterno']).' '.trim($reg['indiv_nombres']); 
            $data['col3'] =  trim($reg['indiv_dni']);
            $data['col4'] =  trim($reg['platica_nombre']); 

            $response[] = $data;
            $c++;
        } 

        echo json_encode($response); 
 
    }

    public function elaborar(){

    }

    /* */
    public function ui_view()
    {
            
        $hoja_key = $this->input->post('view');
        $hoja_id = $this->hojaasistencia->get_id($hoja_key);

        $hoja_asistencia = $this->hojaasistencia->get($hoja_id);
        // Obtener el estado de la hoja
        $estado_hoja = $hoja_asistencia['estado_id'];
 
        $config =  $this->hojaasistencia->get_plati_config($hoja_asistencia['plati_id']);

        $modo = ($this->input->post('from') != '') ? $this->input->post('from') : 'edicion_completa'; // Opcion por defecto
            

      /*  if($estado_hoja == HOJAASIS_ESTADO_ELABORAR)
        {*/
             $this->load->view('planillas/p_asistencias_elaborarhoja', array('hoja_info' => $hoja_asistencia, 'config' => $config, 'modo' => $modo));
 /*       }
        else
        {
             $this->load->view('planillas/p_asistencias_hojaelaborada', array('hoja_info' => $hoja_asistencia, 'config' => $config, 'from' => $from));
        }*/

       
    }
 
   

    public function registro_de_hojas(){

       $this->load->library(array('App/tarea','App/tipoasistencia'));

        $tipos = $this->tipoplanilla->get_all();
        $estados = $this->tipoasistencia->get_list();

        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));

        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;

        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));

        $this->load->view('planillas/v_asistencias_registro', array('tipos' =>$tipos, 'tareas' => $tareas, 'estados' => $estados, 'anios' => $anios));     
    }


    public function agregar_empleado()
    {

        $this->load->library(array('App/hojaempleado', 'App/planillatipocategoria', 'App/hojaasistenciagrupo'));

        $data = $this->input->post();

        $asis_key  = $this->input->post('p_c');

        /* IMPLEMENTAR PARA AGREGAR MULTIPLES TRABAJADORES*/
        $emps_key  = $this->input->post('e_c');
        $t_emp_key = explode('_',$emps_key);
        array_shift($t_emp_key);

        $fechainicio = (trim($this->input->post('fechainicio'))== '') ?  '':  $this->input->post('fechainicio');
        $fechafin = (trim($this->input->post('fechafin'))== '') ?  '':  $this->input->post('fechafin');
 
        $asis_id = $this->hojaasistencia->get_id($asis_key);

        $hoja_info =  $this->hojaasistencia->get($asis_id); 

        $categoria_id = '0';

        if($hoja_info['hoa_tienecategorias'] == '1')
        {   
       
            $categoria_key = trim($this->input->post('categoria'));
            
            if($categoria_key != '0')
            {
               $categoria_id = $this->planillatipocategoria->get_id($categoria_key);
            }

        }

        $grupo_id = '0'; 

        if( trim($data['grupo_label']) != '')
        {
            if($data['nuevo_grupo'] == '1')
            {
                 list($grupo_id, $grupo_key) =  $this->hojaasistenciagrupo->registrar(array('hoagru_nombre' => strtoupper(trim($data['grupo_label'])) ), true);
            }  
        }
 

        foreach($t_emp_key  as $emp_key)
        {
                 $indiv_id = $this->persona->get_id($emp_key);
                 $registrado =  $this->hojaempleado->registrar($asis_id, $indiv_id, $fechainicio,  $fechafin, $categoria_id, $grupo_id);
        }
    
       $response =  array(
            
             'result' =>  ($registrado != false && $registrado != null)? '1' : '0',
             'mensaje'  => ($registrado != false && $registrado != null)? 'Trabajador registrado' : 'No pudo completarse la operación.',
             'data' => array('hojakey' => $asis_key )
        );
        
        echo json_encode($response);

    }

    public function quitar_empleado()
    {
   
       $this->load->library(array('App/hojaempleado'));
     
       $hoja_key =  $this->input->post('hoja');
       $hoja_id = $this->hojaasistencia->get_id($hoja_key);

       $detalle_key =  $this->input->post('detalle');
       $detalle_id = $this->hojaempleado->get_id($detalle_key);

       $rs =  $this->hojaempleado->delete($detalle_id);

        $response =  array(
            
             'result' =>  ($rs)? '1' : '0',
             'mensaje'  => ($rs)? 'Operacion completada' : 'No pudo completarse la operación.',
             'data' => array('hojakey' => $hoja_key )
        );
        
        echo json_encode($response);

    }


    public function get_calendario($tipo =  '')
    {

         $this->load->library(array('App/hojadiariotipo','App/planillatipocategoria'));
 
         $data =  $this->input->post(); 
         
         $hoja_key = $data['hoja'];
         $hoja_id  = $this->hojaasistencia->get_id($hoja_key);

         $hoja_info = $this->hojaasistencia->get($hoja_id);

         $plati_id =  $hoja_info['plati_id'];


         $config =  $this->hojaasistencia->get_plati_config($plati_id); 

 
         $orden = (trim($data['orden']) == '' ? '1' : trim($data['orden']) );
         $modo_ver = (trim($data['ver_modo']) == '' ? '1' : trim($data['ver_modo']) );
 
 
         $params = array( 'orden' => $orden, 
                          'modo_ver' => $modo_ver );
     
         $estados_dia = $this->hojadiariotipo->get($plati_id);   
         $rs_estados_dia = array();


         foreach($estados_dia as $st)
         {
             $rs_estados_dia[$st['hatd_id']] = $st;
         } 
 

         $estructura = array(
                        array( 
                                 array(
                                     array('label' => '#',          'field' => 'col1'),
                                     array('label' => 'Trabajador', 'field' => 'col2'),
                                     array('label' => 'DNI',                'field' => 'col3'),
                                     array('label' => 'Categoria/Ocupacion',  'field' => 'col4')
                                 )
                             ),

                        array(

                              array()
                         )

                     );

         $solo_mostrar_en_resumen = true;
         $estados_dia = $this->hojadiariotipo->get( $plati_id, false, false, false, $solo_mostrar_en_resumen  );   

         $col_id = 5;

         foreach ($estados_dia as $reg)
         {   
              $estructura[1][0][] = array('label' => strtoupper(trim($reg['hatd_label'])), 
                                          'field' => ('col'.$col_id));    
              $col_id++;
         }



         $categorias =   $this->planillatipocategoria->get_list($plati_id);

/*
         if( $tipo == '' )
         {
 */
            $calendario_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_hoja($hoja_id, $params, true) : array();


            $resumen_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_hoja($hoja_id, $params, false, true ) : array();
    
            $acceso_escalafon = ($this->user->has_key('ASISTENCIAS_ACCESOESCALAFON_DESDEHOJA')) ? '1' : '0';

	        $this->load->view('planillas/v_asistencias_hoja_contenedor', array('calendario' => $calendario_data, 
                                                                              'resumen' => $resumen_data,
                                                                              'hoja_info' => $hoja_info, 
                                                                              'rs_estados_dia' => $rs_estados_dia,
                                                                              'params' => $params,
                                                                              'estructura_tabla_resumen' => $estructura,
                                                                              'categorias' => $categorias,
                                                                              'config' => $config,
                                                                              'acceso_escalafon' => $acceso_escalafon
                                                                              ));
/*       
         }
         else if($tipo == 'resumen')
         {
              $calendario_data =  $this->hojaasistencia->get_calendar_con_resumen($hoja_id);
          
         }
*/
    }

    public function explorar_obreros()
    {   

        $this->load->library(array('App/tarea','Catalogos/dependencia'));
        
        $tipos = $this->tipoplanilla->get_all(1, array('tipo_registro_asistencia' => '1')); 

        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;
        
        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));

        $dependencias   = $this->dependencia->get_list();

        $this->load->view('planillas/p_asistencias_explorar', array('tipos' =>$tipos, 
                                                                    'tareas' => $tareas, 
                                                                    'dependencias'   => $dependencias,  
                                                                    'anios' => $anios));
    }



    public function explorar_empleados()
    {   

        $this->load->library(array('App/tarea','Catalogos/dependencia'));
        
        $tipos = $this->tipoplanilla->get_all(1, array('tipo_registro_asistencia' => '2')); 

        $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;
        
        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));

        $dependencias   = $this->dependencia->get_list();

        $this->load->view('planillas/p_asistencias_explorar_empleados', array('tipos' =>$tipos, 
                                                                              'tareas' => $tareas, 
                                                                              'dependencias'   => $dependencias,  
                                                                              'anios' => $anios));
    }


    public function get_registro_asistencia()
    {

        $this->load->library(array('App/hojadiariotipo',
                                   'App/tipoplanilla',
                                   'App/planillatipocategoria',
                                   'App/planillaasistencia'));
 
        $data =  $this->input->post(); 
 
        if($data['detalle_importacion'] != '1')
        { 


                if($data['planillatipo'] != '0')
                {
                    $plati_id = $this->tipoplanilla->get_id($data['planillatipo']);
                    $config =  $this->hojaasistencia->get_plati_config($plati_id); 
                }
                else{   
 
                    $tiposPlanilla = $this->tipoplanilla->get_all( 1, array('tipo_registro_asistencia' => $data['tipo_registro_asistencia'])); 
                    $plati_id = array();

                    foreach ($tiposPlanilla as $tp) {
                        array_push($plati_id, $tp['plati_id']);
                    }
 
                    $config =  $this->hojaasistencia->get_plati_config($plati_id[0]); 

                }
 
                $params = array(); 
               
                $params['tipobusqueda'] = $data['tipobusqueda'];
 

                if($params['tipobusqueda'] == '1')
                {
                    $params['plati_id'] = $plati_id; 
                    $params['tarea_id'] = '';
                    $params['area_id'] = '';
                    $params['hoja'] = '';
                    $params['trabajador'] = '';
                }   
                else if($params['tipobusqueda'] == '2')
                {
                    $params['plati_id'] = '';
                    $params['tarea_id'] =  $data['tarea'];
                    $params['area_id'] = '';
                    $params['hoja'] = '';
                    $params['trabajador'] = '';
                } 
                else if($params['tipobusqueda'] == '3')
                {
                    $params['plati_id'] = '';
                    $params['tarea_id'] =  '';
                    $params['area_id'] = $data['dependencia'];
                    $params['hoja'] = '';
                    $params['trabajador'] = '';
                }
                else if($params['tipobusqueda'] == '4')
                {
                    $params['plati_id'] = '';
                    $params['tarea_id'] =  '';
                    $params['area_id'] = '';
                    $params['hoja'] =  $data['codigohoja'];
                    $params['trabajador'] = '';
                }
                else if($params['tipobusqueda'] == '5')
                {
                    $params['plati_id'] = '';
                    $params['tarea_id'] =  '';
                    $params['area_id'] = '';
                    $params['hoja'] =   '';
                    $params['dni'] = xss_clean(trim($data['dni']));
                }
                else
                {
                    $params['plati_id'] = $plati_id; 
                    $params['tarea_id'] = '';
                    $params['area_id'] = '';
                    $params['hoja'] = '';
                    $params['trabajador'] = '';
                }


                $params['lugar_de_trabajo'] = trim($data['lugar_de_trabajo']);
            
                $params['mostraractivos'] = $data['mostraractivos'];

                $params['fechadesde'] = $data['fechadesde'];
                $params['fechahasta'] = $data['fechahasta'];


 
        }
        else
        {

                $importacion_key = $data['importacion'];
                $importacion_id = $this->planillaasistencia->get_id($importacion_key);
                
                $params['plati_id'] = ''; 
                $params['tarea_id'] = '';
                $params['area_id'] = '';
                $params['hoja'] = '';
                $params['trabajador'] = '';

                $params['importacion'] = $importacion_id; 

                /*$params['fechadesde'] = '2014-08-04';
                $params['fechahasta'] = '2014-08-10';
                */
                $params['fechadesde'] = $data['fechadesde'];
                $params['fechahasta'] = $data['fechahasta'];
        }


        $estados_dia = $this->hojadiariotipo->getAll();
        $rs_estados_dia = array();

        foreach($estados_dia as $st)
        {
            $rs_estados_dia[$st['hatd_id']] = $st;
        } 



        $params['modo_ver'] = (trim($data['ver_modo']) == '' ? '1' : trim($data['ver_modo']) );
        
        if($data['ver_modo'] == '8'){
            $params['incluir_permisos'] = true;
            $params['considerar_solo_permisos'] = false;
        }

        $params['count'] = true;

        $total_registros = $this->hojaasistencia->get_registro_asistencia($params, true, false);

        $params['count'] = false;

        $data['pagina'] = ($data['pagina'] != '') ? ($data['pagina'] * 1) : 1;

        $params['limit'] = 30;

        if($data['pagina'] > 1)
        {
            $params['offset'] = $params['limit'] * ($data['pagina'] - 1);
        }

        $contador_inicial = ($data['pagina'] - 1) * $params['limit'];

        $calendario_data = $this->hojaasistencia->get_registro_asistencia($params, true, false);

    //      $resumen_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_hoja($hoja_id, $params, false, true ) : array();

        $this->load->view('planillas/v_asistencias_registroasistencia_contenedor', array( 'calendario' => $calendario_data, 
                                                                                          'resumen' => $resumen_data, 
                                                                                          'rs_estados_dia' => $rs_estados_dia,
                                                                                          'total_registros' => $total_registros,
                                                                                          'params' => $params,
                                                                                          'estructura_tabla_resumen' => $estructura,
                                                                                          'categorias' => $categorias,
                                                                                          'config' => $config,
                                                                                          'paginaactual' => $data['pagina'],
                                                                                          'contador_inicial' => $contador_inicial 
                                                                                          ));

 
               

    }

 
    public function editar_diario()
    {
 

        $this->load->library(array('App/hojadiariotipo', 
                                   'App/hojaempleadodiario', 
                                   'App/planillatipocategoria'));
      
        $detalle_hoja    = trim($this->input->post('trabajador') );
 
     

        $sql = " SELECT he.*, indiv_key, hoa.plati_id 
                 FROM planillas.hojaasistencia_emp he
                 LEFT JOIN planillas.hojaasistencia hoa ON he.hoa_id = hoa.hoa_id 
                 INNER JOIN public.individuo indiv ON he.indiv_id = indiv.indiv_id 
                 WHERE hoae_key = ? 
                 LIMIT 1 ";
        
        list($rs) =  $this->db->query($sql, array($detalle_hoja) )->result_array(); 

        $platica_id = $rs['platica_id'];
        $plati_id = $rs['plati_id']; 

        $config =  $this->hojaasistencia->get_plati_config($plati_id); 

        $trabajador_id     =  $rs['indiv_id'];
        $trabajador_info   =  $this->persona->get_some_info($trabajador_id,'id');

        $trabajador_key = $rs['indiv_key'];
        $hoae_id = $rs['hoae_id'];


        $trabajador_nombre = trim($trabajador_info['nombre_completo']).' (DNI: '.$trabajador_info['indiv_dni'].')';

        $hoja_key = $this->input->post('hoja');
        $hoja_id = $this->hojaasistencia->get_id($hoja_key);

        $fecha = trim($this->input->post('fecha'));

        // COMPARAR SI ESTA EN EL RANGO DE FECHA DE CONTRATO

        $sql = " SELECT  persla_id, persla_fechaini, persla_fechafin  
                 FROM rh.persona_situlaboral persla 
                 WHERE persla.pers_id = ?  AND 
                       persla.persla_estado = 1 AND 
                       persla.persla_ultimo = 1  AND  
                       ( persla.persla_fechaini <= ? AND  ( persla.persla_fechafin is null OR ? <= persla.persla_fechafin  )  ) 
              ";

        list($rs) = $this->db->query($sql , array($trabajador_id, $fecha, $fecha) )->result_array();

        $sin_contrato = false;
       // SI el rango de fechas no esta entre su periodo de contrato 
        if(sizeof($rs) == 0 )
        {
            $sin_contrato = true;
        } 


        $dia_info = $this->hojaasistencia->info_trabajador_dia($trabajador_id, $fecha);

 
 
        if( trim($dia_info['hoaed_id']) != '' && $hoja_id != $dia_info['hoa_id'] && $dia_info['hatd_id'] != ASISDET_NOCONSIDERADO  && $dia_info['registro_id'] == '0' )
        {
            $html_mensaje = " <b> Existe un registro del trabajador por este día en OTRA HOJA DE ASISTENCIA.  </b> <br/> ";
            
            $html_mensaje.=" <div class='dv_busqueda_personalizada'> 
                                <table class='_tableppading4'> 
                                    <tr> 
                                        <td width='30'> <span class='sp12b'> Hoja  </span> </td>
                                        <td width='10'> <span class='sp12b'> : </span> </td>
                                        <td> <span class='sp12b'> ".$dia_info['hoa_codigo']." </span> </td>
                                    </tr>
                                    <tr> 
                                        <td> <span class='sp12b'> Proyecto  </span> </td>
                                        <td> <span class='sp12b'> : </span> </td>
                                        <td> <span class='sp12'> ".$dia_info['meta_codigo'].' - '.$dia_info['meta_nombre']." </span> </td>
                                    </tr>
                                    <tr> 
                                        <td> <span class='sp12b'> Responsable del tareo  </span> </td>
                                        <td> <span class='sp12b'> : </span> </td>
                                        <td> <span class='sp12'> ".$dia_info['responsable_hoja']." </span> </td>
                                    </tr>
                                    <tr> 
                                        <td> <span class='sp12b'> Categoria  </span> </td>
                                        <td> <span class='sp12b'> : </span> </td>
                                        <td> <span class='sp12'> ".$dia_info['platica_nombre']." </span> </td>
                                    </tr>
                                    <tr> 
                                        <td> <span class='sp12b'> Estado   </span> </td>
                                        <td> <span class='sp12b'> : </span> </td>
                                        <td> <span class='sp12'> ".$dia_info['estado_nombre']." </span> </td>
                                    </tr>
                                </table> 
                             </div> ";

            echo $html_mensaje;
            die();
        }

        if( trim($dia_info['hoaed_id']) != '' && $hoae_id != $dia_info['hoae_id'] && $dia_info['hatd_id'] != ASISDET_NOCONSIDERADO && $dia_info['registro_id'] == '0' )
        {
            $html_mensaje = " <b> Ya existe un registro del trabajador por este día con otra categoria.  <b/>";
            $html_mensaje.=" <div class='dv_busqueda_personalizada' style='margin:5px 0px 0px 0px;'> 
                                <table class='_tableppading4'> 
                                  
                                    <tr> 
                                        <td width='130'> <span class='sp12b'> Categoria registrada   </span> </td>
                                        <td width='10'> <span class='sp12b'> : </span> </td>
                                        <td> <span class='sp12'> ".$dia_info['platica_nombre']." </span> </td>
                                    </tr>
                                  
                                </table> 
                             </div> ";
            echo $html_mensaje;
            die();
        }

 

        if( $dia_info['registro_id'] != '0' && $dia_info['registro_id'] != ''  && $dia_info['tiporegistro_id'] != '0' && $dia_info['tiporegistro_id'] != ''  )
        {

            $html_mensaje.=" <div class='window_container'> 
                                    <input type='text' id='hddiario_desdeescalafon' value='".$dia_info['registro_id']."|".$dia_info['tiporegistro_id']."'/>  
                             </div> ";

            echo $html_mensaje;

            die();
        }

 
        $hoja_info = $this->hojaasistencia->get($hoja_id);  
 

        $_DIAS = array(
                      '1' => 'L',
                      '2' => 'M',
                      '3' => 'M',
                      '4' => 'J',
                      '5' => 'V',
                      '6' => 'S',
                      '7' => 'D'  );

        $_DIAS_L = array(
                      '1' => 'Lunes',
                      '2' => 'Martes',
                      '3' => 'Miercoles',
                      '4' => 'Jueves',
                      '5' => 'Viernes',
                      '6' => 'Sabado',
                      '7' => 'Domingo'  );


        $_MESES = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
 
     
 
        $dia_trabajador_hoja_info =  $this->hojaempleadodiario->get_info_dia( $hoja_id, $trabajador_id, $fecha );
             
        if(sizeof($dia_trabajador_hoja_info) == 0)
        {

            $dia_trabajador_hoja_info =  $this->hojaempleadodiario->get_dia_horario_estado_defecto($trabajador_id, $fecha);

            $dia_trabajador_hoja_info['hoae_hora1_e'] = $dia_trabajador_hoja_info['hor_hora1_e'];
            $dia_trabajador_hoja_info['hoae_hora1_s'] = $dia_trabajador_hoja_info['hor_hora1_s'];
            $dia_trabajador_hoja_info['hoae_hora2_e'] = $dia_trabajador_hoja_info['hor_hora2_e'];
            $dia_trabajador_hoja_info['hoae_hora2_s'] = $dia_trabajador_hoja_info['hor_hora2_s'];

            $estado_actual_del_dia = '0';
        }
        else
        {
            $estado_actual_del_dia = $dia_trabajador_hoja_info['hatd_id'];
        }

            $ft =  strtotime($fecha);
            $anio = date('Y', $ft );
            $mes = $_MESES[date('n', $ft)];
            $dia = date('d', $ft);
            $dial = $_DIAS_L[date('N', $ft)];
            $fecha_larga =  $dial.', '.$dia.' de '.$mes.' del '.$anio;      

 
         $fecha_corta = _get_date_pg($fecha);   
            
         $registro_desde_hoja = true;
         $estados_dia = $this->hojadiariotipo->get($plati_id, $registro_desde_hoja );

         $categorias = array();

         if($hoja_info['hoa_tienecategorias'] == '1')
         {
            $categorias = $this->planillatipocategoria->get_list($hoja_info['plati_id']);
         }

         if( $dia_trabajador_hoja_info['plaasis_fecreg'] != '')
         {
             $fecha_importacion =  _get_date_pg($dia_trabajador_hoja_info['plaasis_fecreg']);
         }
         else
         {
            $fecha_importacion  = ' ------ ';
         }
        
         $solo_conregistro_marcaciones_diario = true;       
         $rs_estados_dia_con_marcaciones = $this->hojadiariotipo->get($plati_id, false, false, $solo_conregistro_marcaciones_diario, false);
         $estados_dia_con_marcaciones = array();

         foreach ($rs_estados_dia_con_marcaciones as $est)
         {
            $estados_dia_con_marcaciones[] = $est['hatd_id'];
         }



         $params =  array('estados_dia'   => $estados_dia, 
                             'trabajador'     => $trabajador_nombre,
                             'trabajador_key' => $trabajador_key,
                             'fecha_larga'    => $fecha_larga,
                             'fecha_corta'    => $fecha_corta,
                             'fecha'          => $fecha,
                             'hoja'           => $hoja_key,
                             'diario_info'    => $dia_trabajador_hoja_info,
                             'hoja_info'      => $hoja_info,
                             'categorias'     => $categorias,
                             'platica_id'     => $platica_id,
                             'config'         => $config,
                             'sin_contrato'   => $sin_contrato,
                             'fecha_importacion' => $fecha_importacion,
                             'estados_dia_con_marcaciones' => $estados_dia_con_marcaciones,
                             'mostrar_full_info' => '0',
                             'estado_actual_del_dia' => $estado_actual_del_dia
                         );
        
         // Si todavia no ha sido importado entonces se puede modificar.
 
         if(($dia_trabajador_hoja_info['hoaed_importado'] == '0' || $dia_trabajador_hoja_info['hoaed_importado'] == '')  )
         {  

            if($this->user->has_key('ASISTENCIAS_ACCESOCOMPLETO_SOLOVER') == TRUE && SUPERACTIVO === FALSE )
            {
                $this->load->view('planillas/v_asistencias_diario_visualizar', $params); 
            }
            else
            {
                $this->load->view('planillas/v_asistencias_diario_modificar', $params);
            }
         }
         else
         {  

            $this->load->view('planillas/v_asistencias_diario_visualizar', $params);    
         }

    }   


    public function permisos()
    {
         $this->load->view('planillas/v_asistencias_permiso_registrar');
    }

    public function visualizar_detalle_dia()
    { 

        
         $this->load->library(array('App/hojadiariotipo', 
                                    'App/hojaempleadodiario', 
                                    'App/planillatipocategoria'));
 
        $data = $this->input->post();

        $indiv_id = $this->persona->get_id(trim($data['trabajador']));
      
        $indiv_info = $this->persona->get_some_info($indiv_id, 'id');
        $plati_id = $indiv_info['plati_id'];

        $config = $this->hojaasistencia->get_plati_config($plati_id); 

        $fecha = $data['fecha'];

 
        if($config['tipo_registro_asistencia'] == TIPOREGISTRO_ASISTENCIA_TAREO){

            $dia_info = $this->hojaasistencia->info_trabajador_dia($indiv_id, $fecha);
          
            $dia_trabajador_hoja_info =  $this->hojaempleadodiario->get_info_dia( 0, $indiv_id, $fecha );
             
            if(sizeof($dia_trabajador_hoja_info) == 0)
            {
                $dia_trabajador_hoja_info =  $this->hojaempleadodiario->get_dia_horario_estado_defecto($indiv_id, $fecha);
            }

        }
        else{
 
            $dia_info = $this->hojaasistencia->info_trabajador_dia_modulo_asistencia($indiv_id, $fecha);

        }
 

        if( $dia_info['plaasis_fecreg'] != '')
        {
            $fecha_importacion =  _get_date_pg($dia_info['plaasis_fecreg']);
        }
        else
        {
            $fecha_importacion  = ' ------ ';
        }

        
        $trabajador_nombre = $dia_info['indiv_appaterno'].' '.$dia_info['indiv_apmaterno'].' '.$dia_info['indiv_nombres'];

        $trabajador_key = $dia_info['indiv_key'];


        $_DIAS = array(
                      '1' => 'L',
                      '2' => 'M',
                      '3' => 'M',
                      '4' => 'J',
                      '5' => 'V',
                      '6' => 'S',
                      '7' => 'D'  );

        $_DIAS_L = array(
                      '1' => 'Lunes',
                      '2' => 'Martes',
                      '3' => 'Miercoles',
                      '4' => 'Jueves',
                      '5' => 'Viernes',
                      '6' => 'Sabado',
                      '7' => 'Domingo'  );


        $_MESES = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
         
        $ft =  strtotime($fecha);
        $anio = date('Y', $ft );
        $mes = $_MESES[date('n', $ft)];
        $dia = date('d', $ft);
        $dial = $_DIAS_L[date('N', $ft)];
        $fecha_larga =  $dial.', '.$dia.' de '.$mes.' del '.$anio;      
    
        $fecha_corta = _get_date_pg($fecha);   


        if( $dia_info['registro_id'] != '0' && $dia_info['registro_id'] != ''  && $dia_info['tipoescalafon_view'] != '0' && $dia_info['tipoescalafon_view'] != ''  )
        {

            $html_mensaje.=" <div class='window_container'> 
                                    <input type='text' id='hddiario_desdeescalafon' value='".$dia_info['registro_id']."|".$dia_info['tipoescalafon_view']."'/>  
                             </div> ";

            echo $html_mensaje;

            die();
        }


        $registro_desde_hoja = true;
        $estados_dia = $this->hojadiariotipo->get($plati_id, $registro_desde_hoja );
 
        $solo_conregistro_marcaciones_diario = true;       
        $rs_estados_dia_con_marcaciones = $this->hojadiariotipo->get($plati_id, false, false, $solo_conregistro_marcaciones_diario, false);
        $estados_dia_con_marcaciones = array();

        foreach ($rs_estados_dia_con_marcaciones as $est)
        {
           $estados_dia_con_marcaciones[] = $est['hatd_id'];
        }   

 
        $params =  array(   'trabajador'     => $trabajador_nombre,
                            'trabajador_key' => $trabajador_key,
                            'fecha_larga'    => $fecha_larga,
                            'fecha_corta'    => $fecha_corta,
                            'fecha'          => $fecha,
                            'hoja'           => $hoja_key,
                            'diario_info'    => $dia_info,
                            'hoja_info'      => $hoja_info,  
                            'config'         => $config, 
                            'fecha_importacion' => $fecha_importacion,
                            'estado_actual_del_dia' => $dia_info['estado_dia_id'],
                            'mostrar_full_info'        => '1'
                        );


            
         //   $this->load->view('planillas/v_asistencias_diario_modificar', $params);

        
        if($config['tipo_registro_asistencia'] == TIPOREGISTRO_ASISTENCIA_TAREO){
 
           $this->load->view('planillas/v_asistencias_diario_visualizar', $params);

        }
        else{

            if( trim($dia_info['hoaed_id']) == '' ){
 
                $this->load->library(array('App/trabajadordia'));

                $configuracion_dia_por_defecto = $this->trabajadordia->getDefaultConfig(array('indiv_id' => $indiv_id, 'fecha' => $fecha ));
                
                $indiv_info = $this->persona->get_some_info( $indiv_id, 'id');

                $trabajador_nombre = $indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'].' '.$indiv_info['indiv_nombres'];
                $trabajador_key = $indiv_info['indiv_key'];

                $params['estado_actual_del_dia'] = ASISDET_NOCONSIDERADO;
                $params['trabajador'] = $trabajador_nombre;
                $params['trabajador_key'] = $trabajador_key;
                $params['diario_info']['hoaed_laborable'] = $configuracion_dia_por_defecto['platide_laborable'];
                $params['diario_info']['hor_alias'] = $configuracion_dia_por_defecto['hor_alias']; 
                $params['diario_info']['hor_hora1_e'] = $configuracion_dia_por_defecto['hor_hora1_e'];
                $params['diario_info']['hor_hora1_s'] = $configuracion_dia_por_defecto['hor_hora1_s'];

                $params['diario_info']['hoae_hora1_e'] = $configuracion_dia_por_defecto['hor_hora1_e'];
                $params['diario_info']['hoae_hora1_s'] = $configuracion_dia_por_defecto['hor_hora1_s'];
 
            }

           $this->load->view('planillas/v_asistencias_moduloasistencia_editar_diario', $params);
           
        }
  
    }
 

    public function registrar_detalle_dia()
    {

        $this->load->library(array('App/hojaempleadodiario', 'App/planillatipocategoria'));

            $trabajador_key = $this->input->post('trabajador');
            $indiv_id = $this->persona->get_id($trabajador_key);
 
            $hoja_key = $this->input->post('hoja');
            $hoja_id = $this->hojaasistencia->get_id($hoja_key);
   
            $tipo_diario  = $this->input->post('estado');    
          
            $platica_key = $this->input->post('categoria');

            if($platica_key != '')
            {

                $platica_id = $this->planillatipocategoria->get_id($platica_key);
            }
            else
            {
                $platica_id = 0;
            }

            $chhasta = $this->input->post('chhasta');
  
            $fecha = $this->input->post('dia');
            $fecha_hasta = strtotime($this->input->post('fechahasta'));
   

            $data = $this->input->post();
 

            if(in_array( $tipo_diario, array(ASISDET_LICENCIAGOCE, ASISDET_LICENCIASINGOCE, ASISDET_LICENCIA_SINDICAL  ) ))
            {

                $tipos_licencias = array( ASISDET_LICENCIAGOCE => TIPOLICENCIA_CONGOCE, 
                                          ASISDET_LICENCIASINGOCE =>  TIPOLICENCIA_SINGOCE,
                                          ASISDET_LICENCIA_SINDICAL =>  TIPOLICENCIA_SINDICAL 
                                        );

                $tipo_licencia = $tipos_licencias[$tipo_diario];

                $this->load->library(array('App/licencia'));


                 $values = array(
                              
                                 'pers_id'             => $indiv_id, 
                                 'peli_fechavigencia'  => $fecha,
                                 'peli_tipolicencia'   => $tipo_licencia,
                                 'peli_descripcion'    => trim($data['obs']),
                                 'peli_documento'      => '[Desde Control de asistencia]' 
                              
                              );
             
                $values['peli_fechacaducidad'] =  ($chhasta == '1') ? $data['fechafin'] : $fecha; 

                list($licencia_id,$key) = $this->licencia->registrar($values, true);
                //  $this->licencia->registrar_desde_hoja($tipo_licencia , array('indiv_id' => $indiv_id ) );
            }
            


            if($chhasta !=  '1')
            {

                $values = array(
                                'categoria'   => $platica_id,
                                'tipo'        => $tipo_diario, 
                                'tardanzas'   => ($tipo_diario == ASISDET_ASISTENCIA )  ? trim($this->input->post('tardanzas')) : '0',
                                'permisos'    => ($tipo_diario == ASISDET_ASISTENCIA )  ? trim($this->input->post('permisos')) : '0',
                                'observacion' => trim($this->input->post('obs') ));
                
                if( trim($data['hora1']) != '')  $values['hora1'] = $data['hora1'];
                if( trim($data['hora2']) != '')  $values['hora2'] = $data['hora2'];
                if( trim($data['hora3']) != '')  $values['hora3'] = $data['hora3'];
                if( trim($data['hora4']) != '')  $values['hora4'] = $data['hora4'];



               $ok =  $this->hojaempleadodiario->actualizar_info_dia($hoja_id, $indiv_id, $fecha, $values);


            }
            else
            {
 
                $dia = date('j',$fecha_hasta);
                $mes = date('n',$fecha_hasta);
                $ano = date('Y',$fecha_hasta);
                $mk_limite  =  mktime(0,0,0,$mes,$dia,$ano);
               

                $fecha_inicio = strtotime($fecha);
                $dia = date('j',$fecha_inicio);
                $mes = date('n',$fecha_inicio);
                $ano = date('Y',$fecha_inicio);
                $mk_inicio  =  mktime(0,0,0,$mes,$dia,$ano);

              
                $mk_current = $mk_inicio;
         
                while($mk_current <= $mk_limite )
                {

                   $n_fecha =  date("d/m/Y",mktime(0,0,0,$mes,$dia,$ano)); 

                   $values = array(
                            'categoria'   => $platica_id,
                            'tipo'        => $tipo_diario, 
                            'tardanzas'   => ($tipo_diario == ASISDET_ASISTENCIA )  ? trim($this->input->post('tardanzas')) : '0',
                            'permisos'    => ($tipo_diario == ASISDET_ASISTENCIA )  ? trim($this->input->post('permisos')) : '0',
                            'observacion' => trim($this->input->post('obs') ));
         
                   $ok =  $this->hojaempleadodiario->actualizar_info_dia($hoja_id, $indiv_id, $n_fecha, $values);
 
                   $dia+=1;
                   $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);

                }

            }
 

 
             $response =  array(
                  
                   'result' =>  ($ok)? '1' : '0',
                   'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
                   'data' => array('key' => $key )
              );
              
              echo json_encode($response);
       
    }


    public function actualizar_detalle_dia_modulo_asistencia()
    {

        $this->load->library(array('App/hojaempleadodiario', 'App/planillatipocategoria', 'App/trabajadordia'));

        $data = $this->input->post();
        
        $trabajador_key = $data['trabajador'];
        $indiv_id = $this->persona->get_id($trabajador_key);
    
        $estado_dia  = $data['estado'];    
        $fecha = $data['dia'];

        $hora1 = trim($data['hora1']) != '' ? trim($data['hora1']) : '';
        $hora2 = trim($data['hora2']) != '' ? trim($data['hora2']) : '';

        $observacionDia = trim($data['obs']);

        $values = array('indiv_id' => $indiv_id, 
                        'estado_dia' => $estado_dia,
                        'fecha' => $fecha,
                        'hoae_hora1_e' => $hora1,
                        'hoae_hora1_s' => $hora2,
                        'observacion' => $observacionDia
                        );

        $ok = $this->trabajadordia->registrar($values);


        $response =  array(
              
               'result' =>  ($ok)? '1' : '0',
               'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
               'data' => array('key' => $key )
        );
          
        echo json_encode($response);
       
    }




    public function finalizar_registro(){
      
         $hoja_key = $this->input->post('hoja_key');
         $hoja_id = $this->hojaasistencia->get_id($hoja_key);


         $ok = $this->hojaasistencia->setEstado($hoja_id, HOJAASIS_ESTADO_TERMINADO );

         $this->hojaasistencia->set_minutos_tardanzas($hoja_id);

         $response =  array(
              
               'result' =>  ($ok)? '1' : '0',
               'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
               'data' => array('hoja_key' => $hoja_key )
          );
          
          echo json_encode($response);

    } 


    public function eliminar_hoja(){


         $hoja_key = $this->input->post('hoja_key');
         $hoja_id = $this->hojaasistencia->get_id($hoja_key);

         $ok = $this->hojaasistencia->eliminar($hoja_id);
         
         $response =  array(
                  
                   'result' =>  ($ok)? '1' : '0',
                   'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
                   'data' => array('hoja_key' => $hoja_key )
              );
              
              echo json_encode($response);


    }



   
 

    public function devolver(){
      
        $hoja_key = $this->input->post('hoja');
        $hoja_id = $this->hojaasistencia->get_id($hoja_key);


        $ok = $this->hojaasistencia->setEstado($hoja_id, HOJAASIS_ESTADO_ELABORAR, 'Hoja de asistencia devuelta' );

        $response =  array(
                  
                   'result' =>  ($ok)? '1' : '0',
                   'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
                   'data' => array('hoja_key' => $hoja_key )
         );
              
              echo json_encode($response);

    }


    public function test(){


          /* $this->hojaasistencia->get_calendar_con_resumen(29);
          $this->hojaasistencia->get_resumen_hoja(29);
             */
           $this->hojaasistencia->get_calendar(29);
         
    }


    public function hoja()
    {


        $this->load->library(array('App/hojadiariotipo','App/planilla'));
        
        $hoja_id = $this->input->post('hoja');
        $pla_key = $this->input->post('planilla');

        $modo = $this->input->post('view');

        $show_extra_buttons = false;
        
        if($modo == '1'){ // fet vista ocn botones desvincular e imprimir

            $pla_id = $this->planilla->get_id($pla_key);

            $pla_vinculada =  $this->hojaasistencia->get_planilla_vinculada($hoja_id);

            if($pla_id == $pla_vinculada)
            {
        
                 $show_extra_buttons  = true;

            }

        }


        if($this->input->post('view')=='2'){

           $hoja_id = $this->hojaasistencia->get_id($hoja_id);

        }
        
        $hoja_info   = $this->hojaasistencia->get($hoja_id);
        $estados_dia = $this->hojadiariotipo->get(1,1, $hoja_info['plati_id']);

        $rs_estados = array();
        foreach($estados_dia as $st) $rs_estados[$st['hatd_id']] = $st;
          
      $calendario_data = ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_calendar($hoja_id) : array();

    //    $resumen_data =  ($hoja_info['cant_trab'] > 0) ?  $this->hojaasistencia->get_resumen_hoja($hoja_id) : array() ;
     
        $this->load->view('planillas/v_view_hoja', array('calendario' => $calendario_data, 
                                                         'resumen' => $resumen_data ,
                                                         'hoja_info' => $hoja_info, 
                                                         'rs_estados_dias' => $rs_estados, 
                                                         'show_extra' => $show_extra_buttons)); 
      
    }

    public function iniciar_calendario_sistema()
    { 
            $anio = '2014';

             $fecha_inicio =  strtotime( $anio.'-01-01');
               
             $fecha_limite  = strtotime( $anio.'-12-31');
             $dia = date('j',$fecha_limite);
             $mes = date('n',$fecha_limite);
             $ano = date('Y',$fecha_limite);
             $mk_limite  =  mktime(0,0,0,$mes,$dia,$ano);  
            
             $dia = date('j',$fecha_inicio);
             $mes = date('n',$fecha_inicio);
             $ano = date('Y',$fecha_inicio);
             $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);

             while($mk_current <= $mk_limite )
             {

                $n_fecha =  date("d/m/Y",mktime(0,0,0,$mes,$dia,$ano)); 
                $n_fecha_tt =  date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)); 
                $dia_sem = date('N', strtotime($n_fecha_tt) );

                $sql =" INSERT INTO public.calendario(ano_eje, caldia_dia, caldia_tipo ) VALUES(?,?,? ) ";
                $this->db->query($sql, array( $anio, $n_fecha, '0'));

                  $dia+=1;
                  $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);

             }
            
    }

    public function cambiar_categoria_detalle()
    { 
        
        $this->load->library(array('App/hojaempleado', 'App/planillatipocategoria'));

        $hoja_key   = $this->input->post('hoja');
        $categoria  = $this->input->post('categoria');

        $detalle_key = $this->input->post('detalle');

        $hoja_id   = $this->hojaasistencia->get_id($hoja_key);
        $hoja_info = $this->hojaasistencia->get($hoja_id);

        $detalle_id = $this->hojaempleado->get_id($detalle_key);
        $detalle_info = $this->hojaempleado->get($detalle_id);

        $indiv_id = $detalle_info['indiv_id'];

        $categorias = $this->planillatipocategoria->get_list($hoja_info['plati_id']);
 
        $this->load->view('planillas/v_hoja_cambiarcategoria', array('detalle_info' => $detalle_info, 
                                                                     'detalle' => $detalle_key, 
                                                                     'hoja' => $hoja_key, 
                                                                     'categorias' => $categorias,
                                                                     'actual' => $categoria ) );
    }

    public function actualizar_categoria()
    {
        
        $this->load->library(array('App/hojaempleado', 'App/planillatipocategoria'));

        $data = $this->input->post();

        $detalle_id = $this->hojaempleado->get_id($data['detalle']);

        $platica_id = $this->planillatipocategoria->get_id($data['categoria']);

        $categoria_actual = $data['actual'];    

        $hoja_id = $this->hojaasistencia->get_id($data['hoja']);

        $params = array( 
                         'hoja' => $hoja_id,
                         'detalle' => $detalle_id, 
                         'categoria' => $categoria_actual,
                         'nuevacategoria' => $platica_id
                       );

        $ok = $this->hojaempleado->actualizar_categoria($params);

        $response =  array(
                  
                   'result' =>  ($ok)? '1' : '0',
                   'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
                   'data' => array('hoja_key' => $hoja_key )
         );
              
         echo json_encode($response);

    }

    public function get_importacion_config()
    {

        
       $this->load->library(array('App/hojadiariotipo','App/planilla', 
                                  'App/planillatipocategoria', 
                                  'App/fuentefinanciamiento', 
                                  'App/tarea',
                                  'App/hojaasistenciagrupo'));
       
    
       $hoja_key  = $this->input->post('hoja');
       $views = $hoja_key;
       $hojas_key = explode('_',$hoja_key);
       array_shift($hojas_key);

       $hojas_ids = array();

       foreach ($hojas_key as $key)
       {
            $hojas_ids[] =  $this->hojaasistencia->get_id($key);
       }

       $hojas_info = $this->hojaasistencia->get_info_hojas($hojas_ids);

       $rango_fechas = $this->hojaasistencia->get_rango_de_fechas_hojas($hojas_ids);

       $pla_key   = $this->input->post('planilla');
       $pla_id    = $this->planilla->get_id($pla_key);
       $planilla_info = $this->planilla->get($pla_id);

       $plati_id = $planilla_info['plati_id'];
          
       $anio        = $this->usuario['anio_ejecucion'];
       $categorias  = $this->planillatipocategoria->get_list($plati_id);    
       $grupos      = $this->hojaasistenciagrupo->get_list();  
       $tareas      = $this->tarea->get_list(array('ano_eje' => $anio ));

       if(CONECCION_AFECTACION_PRESUPUESTAL)
       {
          $fuentes = $this->fuentefinanciamiento->get_ff_tr(array('anio_eje' => $anio ));    
       }
       else
       {
          $fuentes = $this->fuentefinanciamiento->get_all($anio);    
       }
    
       $this->load->view('planillas/v_asistencias_importacion_planilla_config', array( 'hojas_info' => $hojas_info,
                                                                                       'rango_fechas' => $rango_fechas,  
                                                                                       'planilla' => $pla_key,
                                                                                       'views' => $views,
                                                                                       'tareas' => $tareas,
                                                                                       'fuentes' => $fuentes,
                                                                                       'categorias' => $categorias,
                                                                                       'grupos' => $grupos
                                                                                       ));
    

    }
    
    public function get_table_resumen_importacion()
    {   


        header("Content-Type: application/json");

        $data = $this->input->get();

        foreach ($data as $k => $v )
        {
            $data[$k] = trim($v);
        }

        if($data['return'] == '1')
        {   

            $this->load->library(array('App/planilla'));

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


            $rs_table =  $this->hojaasistencia->get_asistencias_importar($params);

            $rs = array();
            $c = 1;
                 
            foreach ($rs_table as $reg)
            {
                $data = array(
                                'id' => $reg['indiv_key'].'|'.$reg['platica_id'],
                                'col1' => $c,
                                'col2' => (trim($reg['indiv_appaterno']).' '.trim($reg['indiv_apmaterno']).' '.trim($reg['indiv_nombres'])),
                                'col3' => $reg['indiv_dni'],
                                'col4' => $reg['platica_nombre']  
                             );

                $pos_col = 5;

                foreach ($reg as $k => $d)
                {
                
                     $t = explode('_', $k); 
                    
                     if($t[0] == 'dato')
                     {  
                         $data['col'.$pos_col] = $d;

                         $pos_col++;
                     }

                }

                $c++;
                
                $rs[] = $data;
            }


        }
        else
        {
            $rs = array();
        }


        echo json_encode($rs);
    }


    public function get_table_hoja_resumen()
    {   

        header("Content-Type: application/json");

        $data = $this->input->get();

        foreach ($data as $k => $v )
        {
            $data[$k] = trim($v);
        }


        $this->load->library(array('App/planilla'));

        $params = array();
  
        $params['categoria'] = $data['categoria'];
        $params['trabajador'] = $data['trabajador'];
        $params['grupo'] = $data['grupo'];
        $params['orden'] = $data['orden'];
        $params['ver_modo'] = $data['ver_modo'];


        $hoja_id =  $this->hojaasistencia->get_id($data['hoja']);

        $cargar_calendario = false;
        $cargar_resumen = true;
 
        $rs_table =  $this->hojaasistencia->get_hoja( $hoja_id,  $params, $cargar_calendario, $cargar_resumen );

        $rs = array();
        $c = 1;
             
        foreach ($rs_table as $reg)
        {
            $data = array(
                            'id' => $reg['indiv_key'].'|'.$reg['platica_id'],
                            'col1' => $c,
                            'col2' => trim($reg['trabajador']),
                            'col3' => $reg['indiv_dni'],
                            'col4' => $reg['platica_nombre']  
                         );

            $pos_col = 5;

            foreach ($reg as $k => $d)
            {
            
                 $t = explode('_', $k); 
                
                 if($t[0] == 'dato')
                 {  
                     $data['col'.$pos_col] = $d;

                     $pos_col++;
                 }

            }

            $c++;
            
            $rs[] = $data;
        }

 


        echo json_encode($rs);
    }
 
    public function importacion_trabajadores()
    {   

        $this->load->library(array('App/tarea', 'App/tipoplanilla'));

        $data = $this->input->post();

        $hoja_key = $data['hoja'];
        $hoja_id = $this->hojaasistencia->get_id($hoja_key);

        $hoja_info = $this->hojaasistencia->get($hoja_id);

        $tipo = $this->tipoplanilla->get($hoja_info['plati_id']);
    
        $tipos = array($tipo);

        $tareas = $this->tarea->get_list(array('ano_eje' => $this->usuario['ano_eje']));

     //   $anios = $this->anioeje->get_list( array('modo' => 'REGISTRAR', 'usuario'=> $this->usuario['syus_id']  ) ) ;

        $anios = array( array('ano_eje' => $hoja_info['anio_eje'] ) );
 
        $config =  $this->hojaasistencia->get_plati_config($hoja_info['plati_id']); 

 
        $this->load->view('planillas/v_asistencias_importartrabajadores', array('tipos' =>$tipos, 
                                                                                'tareas' => $tareas, 
                                                                                'estados' => $estados, 
                                                                                'anios' => $anios, 
                                                                                'hoja_key' => $hoja_key,
                                                                                'hoja_info' => $hoja_info,
                                                                                'config' => $config ) );
    }
 

    public function importar_trabajadores()
    {

        $this->load->library(array('App/hojaempleado', 'App/planillatipocategoria'));
      
        $data = $this->input->post();

        // hoja
            
         $fechainicio = (trim($data['fechainiciotrabajo'])== '') ?  '':  $data['fechainiciotrabajo'];
         $fechafin = (trim($data['fechafintrabajo'])== '') ?  '':  $data['fechafintrabajo'];
        
         $hoja_id = $this->hojaasistencia->get_id($data['hoja']);
         $hoja_info =  $this->hojaasistencia->get($hoja_id); 

         $hoja_fuente = $this->hojaasistencia->get_id($data['view']);
         $params = array();

         $params['hoja'] = $hoja_fuente;

         
         $rs = $this->hojaasistencia->get_trabajadores($params); 

         $c = 0;
         foreach($rs as $reg)
         {

                 $registrado =  $this->hojaempleado->registrar($hoja_id, $reg['indiv_id'], $fechainicio,  $fechafin, $reg['platica_id']);

                 if($registrado) $c++;
         }
        
        $response =  array(
             
              'result' =>  ($registrado != false && $registrado != null)? '1' : '0',
              'mensaje'  => ($registrado != false && $registrado != null)? 'Trabajador registrado' : 'No pudo completarse la operación.',
              'data' => array('hojakey' => $asis_key )
         );
         
         echo json_encode($response);

        

    }   

    public function registrar_permiso()
    {

            
    }


    public function horario_trabajador()
    {

        $this->load->library(array('App/hojadiariotipo','App/tipoplanilla'));

        $data = $this->input->post();   

        if($data['modo'] == 'regimen')
        { 

            $plati_id = $this->tipoplanilla->get_id(trim($data['view']));
            $info = $this->tipoplanilla->get($plati_id);
        }       
        else
        {
            $indiv_id = $this->persona->get_id($data['view']);
            $info = $this->persona->get_info($indiv_id);
            $plati_id = $indiv_info['tipo_trabajador'];
        }
        
 
        $estados_dia = $this->hojadiariotipo->get($plati_id, false, false, false, false, true);   

        $rs_estados_dia = array();
        foreach($estados_dia as $st)
        {
            $rs_estados_dia[$st['hatd_id']] = $st;
        } 


        $this->load->view('planillas/v_asistencias_editar_horario', array('estados_dia' => $estados_dia, 'view_info' => $info, 'modo' => $data['modo'] ));
    }

    public function horario_nuevo()
    {   

        $data = $this->input->post();


        $this->load->view('planillas/v_asistencias_horario_nuevo', array() );
    }

    public function actualizar_horario_trabajador()
    {

    }

    public function configuracion()
    {

        $this->load->view('planillas/p_asistencias_configuracion', array());

    }

    public function get_estados_del_dia()
    {   

        $this->load->library(array('App/hojadiariotipo', 'App/tipoplanilla'));

        $data = $this->input->get();

        if($data['modo'] == 'tipoplanilla')
        {   
            $plati_id = $this->tipoplanilla->get_id($data['view']);

            $p = array('visualizar' => $data['visualizar']);

            $tipos = $this->hojadiariotipo->get_all_plati( $plati_id, $p);
        }
        else
        {
            $tipos = $this->hojadiariotipo->getAll();
        }


        $response = array();
        $c = 1;

        foreach ($tipos as $reg)
        {   
            $data = array();

            $data['id']   = trim($reg['hatd_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($reg['hatd_nombre']);
            $data['col3'] = trim($reg['hatd_nombrecorto']);
            $data['col4'] = (trim($reg['hatd_label']) != '') ? trim($reg['hatd_label']) : '--------';
            $data['col5'] =  ($reg['hatd_escalafon'] == '1') ? 'Si' : 'No';
            $data['plati_activado'] = (trim($reg['htp_id']) != '') ? 'Si' : 'No';
                
            $response[] = $data;  
            $c++;                 
        }

        echo json_encode($response);
    }

    public function estado_dia_view()
    {
        $this->load->library(array('App/hojadiariotipo'));
        $data = $this->input->post();

        $hatd_id = $this->hojadiariotipo->get_id($data['view']);

        $tipos = $this->hojadiariotipo->getAll();

        $max_tipos = sizeof($tipos);

        $info = $this->hojadiariotipo->view($hatd_id);

        $this->load->view('planillas/v_asistencias_estadodia_view', array('info' => $info, 'maxtipos' => $max_tipos) );
    }   


    public function nuevo_estadodia()
    {
        $this->load->library(array('App/hojadiariotipo'));
        $data = $this->input->post();
 
        $tipos = $this->hojadiariotipo->getAll();
        $max_tipos = sizeof($tipos);
  
        $this->load->view('planillas/v_asistencias_estadodia_nuevo', array( 'maxtipos' => $max_tipos) );
    }   


    public function registrar_tipoestadodia()
    {
         $this->load->library(array('App/hojadiariotipo'));

         $data = $this->input->post();

         $err = false;
         $mensaje_err = '';

         if( trim($data['label']) == '')
         {  
            $err = true;
            $mensaje_err = ' El alias es obligatorio';
         }
         else
         {
            $rs =  $this->hojadiariotipo->get_params(array('label' => strtoupper(trim($data['label']))  ) );

            if(sizeof($rs) > 0)
            {

                $err = true;
                $mensaje_err = ' El alias ya esta registrado, no puede repetirse dicho campo';
            }

         }

         if($err)
         {

             $response =  array(
                  'result' =>  '0',
                  'mensaje'  => $mensaje_err,
                  'data' => array()
             );
             
             echo json_encode($response);

             die();
            
         }


 
 
          $values = array('hatd_nombre' =>  strtoupper(trim($data['nombre'])),
                          'hatd_nombrecorto' => strtoupper(trim($data['nombre_corto'])),
                          'hatd_label' => strtoupper(trim($data['label'])),
                          'hatd_color' => $data['color'],  
                          'hatd_orden' => $data['orden']

                         );  

         $ok =  $this->hojadiariotipo->registrar($values);

         $response =  array(
              
               'result' =>  ($ok)? '1' : '0',
               'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
               'data' => array()
          );
          
          echo json_encode($response);
    }

    public function actualizar_tipoestadodia()
    {   

        $this->load->library(array('App/hojadiariotipo'));

        $data = $this->input->post();

        $key = $data['view'];

        if( trim($data['label']) == '')
        {  
           $err = true;
           $mensaje_err = ' El alias es obligatorio';
        }
        else
        {
           $rs =  $this->hojadiariotipo->get_params(array('label' => strtoupper(trim($data['label']))  ) );

           if(sizeof($rs) > 0 && $rs[0]['hatd_key'] != $key )
           {
               $err = true;
               $mensaje_err = ' El alias ya esta registrado, no puede repetirse dicho campo';
           }

        }

        if($err)
        {

            $response =  array(
                 'result' =>  '0',
                 'mensaje'  => $mensaje_err,
                 'data' => array()
            );
            
            echo json_encode($response);

            die();
           
        }



         $values = array('hatd_nombre' =>  strtoupper(trim($data['nombre'])),
                         'hatd_nombrecorto' => strtoupper(trim($data['nombre_corto'])),
                         'hatd_label' => strtoupper(trim($data['label'])),
                         'hatd_color' => $data['color'],  
                         'hatd_orden' => $data['orden']

                        ); 
 
       

        $ok =  $this->hojadiariotipo->actualizar($key, $values);

        $response =  array(
             
              'result' =>  ($ok)? '1' : '0',
              'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
              'data' => array('key' => $key )
         );
         
         echo json_encode($response);

    }


    public function get_horarios()
    {
        $this->load->library(array('App/horario'));

        $data = $this->input->post(); 

        $horarios = $this->horario->get_list();

        $response = array();
        $c = 1;

        foreach ($horarios as $reg)
        {   
            $data = array();

            $data['id']   = trim($reg['hor_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($reg['hor_alias']); 

            $horario = trim($reg['hor_hora1_e']).' - '.trim($reg['hor_hora1_s']);

            if($reg['hor_numero_horarios'] == '2')
            {
                $horario.=" | ".trim($reg['hor_hora2_e']).' - '.trim($reg['hor_hora2_s']);
            }

            $data['col3'] = $horario;
 
               
            $response[] = $data;  
            $c++;                 
        }

        echo json_encode($response);
 

    }

    public function nuevo_horario()
    {   
        $data = $this->input->post();


        $this->load->view('planillas/v_asistencias_horario_nuevo', array() );
    }
    
    public function registrar_horario()
    {
        $this->load->library(array('App/horario'));

        $data = $this->input->post();

        $values = array();  

        $values['hor_alias'] = strtoupper(trim($data['descripcion']));
        $values['hor_entredosdias'] = trim($data['horariodosdias']); // Ejemplo: Si el horario comeinza el lunes a las 9pm y termina el martes a las 7am
        $values['hor_hora1_e'] = trim($data['hora1']);
        $values['hor_hora1_s'] = trim($data['hora2']);
         
        if(trim($data['hora_a1']) != '')    // Horario alternativo
        {
            $values['hor_hora1_e2'] = trim($data['hora_a1']);
            $values['hor_hora1_s2'] = trim($data['hora_a2']);
        }


        if($data['numero_horarios'] == '1') // Horario corrido
        {
            $values['hor_descontar_horas'] = ( is_numeric($data['horas_descontar']) == true ? trim($data['horas_descontar']) : '0' );
            $values['hor_descontar_despuesde'] = trim($data['hora_ref']);
        }
        else // Horario Maniana - Tarde
        {   
            $values['hor_hora2_e'] = trim($data['hora3']);
            $values['hor_hora2_s'] = trim($data['hora4']);
        }
    

        if($data['tardanza'] == '1')
        {
            $values['hor_ini_tardanza'] = trim($data['hora_tardanza']);
        }
        else
        {
            $values['hor_ini_tardanza'] = trim($data['hora1']); // Si no se especifica el horario de inicio de tardanza entonces se utiliza la hora1
        } 
 

        if($data['faltaportardanza'] == '1')
        {
            $values['hor_hora1_max_ft'] = trim($data['hora_ft']);
            $values['hor_faltaportardanza'] = '1';
        }
        else
        { 
            $values['hor_faltaportardanza'] = '0';
        } 


        $ok = $this->horario->registrar($values);


        $response =  array(
             
              'result' =>  ($ok)? '1' : '0',
              'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
              'data' => array('key' => $key )
         );
         
         echo json_encode($response);
    }

    public function horario_view()
    {

        $this->load->library(array('App/horario'));

        $data = $this->input->post();

        $hor_id = $this->horario->get_id($data['view']);

        $info = $this->horario->get($hor_id);

        $this->load->view('planillas/v_asistencias_horario_view', array('info' => $info) );
    }


    public function planilla_tipo_configurar()
    {   
        $this->load->library(array('App/tipoplanilla', 'App/planillatipoasistencia'));

        $data = $this->input->post();
        $plati_key = trim($data['view']);

        $plati_id =  $this->tipoplanilla->get_id($plati_key);
        $plati_info = $this->tipoplanilla->get($plati_id);

        $config = $this->planillatipoasistencia->get($plati_id);
         
        $horario_defecto = $this->tipoplanilla->get_horario_defecto(array('plati_id' => $plati_id));
 
        $this->load->view('planillas/v_asistencias_planillatipo_configurar', array('plati_info' => $plati_info, 'config' => $config, 'horario_defecto' => $horario_defecto ));
    }

    public function planilla_tipo_configurar_actualizar()
    {

        $this->load->library(array('App/planillatipoasistencia', 'App/tipoplanilla' ));

        $data = $this->input->post();
        $plati_key = trim($data['view']);

        $plati_id =  $this->tipoplanilla->get_id($plati_key);

        if($plati_id != '')
        {
             $values = array();

             $values['tipo_registro_asistencia'] =  trim($data['tipo_registro_asistencia']) != '' ? trim($data['tipo_registro_asistencia']) : '0' ; 
             $values['registro_asistencia_diario'] =  trim($data['registro_asistencia_diario']) != '' ? trim($data['registro_asistencia_diario']) : '0' ; 
             $values['hora_asistencia_pordefecto'] =  trim($data['hora_asistencia_pordefecto']) != '' ? trim($data['hora_asistencia_pordefecto']) : '0' ; 
             // hora limite 
             $values['cierre_tareo_manual']           =  trim($data['cierre_tareo_manual']) != '' ? trim($data['cierre_tareo_manual']) : '0' ; 
             $values['ajustar_marcaciones_alhorario'] =  trim($data['ajustar_marcaciones_alhorario']) != '' ? trim($data['ajustar_marcaciones_alhorario']) : '0' ; 
             $values['diario_tipo_horatrabajadas']    =  trim($data['diario_tipo_horatrabajadas']) != '' ? trim($data['diario_tipo_horatrabajadas']) : '0' ; 
             // maximo de marcacaciones
             $values['grupo_trabajadores'] =  trim($data['grupo_trabajadores']) != '' ? trim($data['grupo_trabajadores']) : '0' ; 
             $values['importacion_buscar_por_ap'] =  trim($data['importacion_buscar_por_ap']) != '' ? trim($data['importacion_buscar_por_ap']) : '0' ; 
             $values['biometrico_habilitado'] =  trim($data['biometrico_habilitado']) != '' ? trim($data['biometrico_habilitado']) : '0' ; 
             // Tolerancia dia

             $ok = $this->planillatipoasistencia->registrar($plati_id, $values);


        }
        else
        {
            $ok = false;
        }
 

        $response =  array(
             
              'result' =>  ($ok)? '1' : '0',
              'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
              'data' => array('key' => $key )
         );
         
         echo json_encode($response);

    }

    public function planilla_tipo_configurar_estadodia()
    {
        $this->load->library(array('App/planillatipoasistencia', 'App/tipoplanilla', 'App/hojadiariotipo', 'App/hojadiariotipoplati', 'App/variable' ));

        $data = $this->input->post();

        $plati_key = trim($data['view']); 
        $plati_id =  $this->tipoplanilla->get_id($plati_key);

        $estado_key = $data['estado'];
        $estado_id = $this->hojadiariotipo->get_id($estado_key);    
                
        $dia_info = $this->hojadiariotipo->view($estado_id);

        $variables = $this->variable->get_list(array('tipoplanilla' => $plati_id));

        $estado_plati_info = $this->hojadiariotipoplati->get(array('plati_id' => $plati_id, 'hatd_id' => $estado_id));

        $this->load->view('planillas/v_asistencias_planillatipo_diaestado_config', array( 'dia_info' => $dia_info,  'estado_key' => $estado_key, 'plati_key' => $plati_key, 'variables' => $variables, 'estado_plati_info' => $estado_plati_info ));
    }


    public function estadodia_plati_config_actualizar()
    {

        $this->load->library(array('App/hojadiariotipoplati', 'App/tipoplanilla', 'App/hojadiariotipo', 'App/variable'));

        $data = $this->input->post();
        $plati_key = trim($data['view']);
        $plati_id =  $this->tipoplanilla->get_id($plati_key);


        $estado_key = $data['estado'];
        $estado_id = $this->hojadiariotipo->get_id($estado_key);   

        if($plati_id != '')
        {
             $values = array();
 
             $values['plati_id'] = $plati_id;
             $values['hatd_id'] =  $estado_id;

             $values['htp_edicionenhoja'] =  trim($data['disponible_en_hoja']) != '' ? trim($data['disponible_en_hoja']) : '0' ; 
             $values['htp_mostrarenresumen'] =  trim($data['mostrarenresumen']) != '' ? trim($data['mostrarenresumen']) : '0' ; 
             $values['htp_registrar_marcacion_horas']    =  trim($data['con_marcacion_de_horas']) != '' ? trim($data['con_marcacion_de_horas']) : '0' ; 
             
             if($data['importar'] == '1')
             {
                 $values['htp_importar_horas']           =  trim($data['importardato']) != '' ? trim($data['importardato']) : '0' ; 

                 if( trim($data['variabledestino']) != '')
                 {
                      $data['variabledestino'] =  $this->variable->get_id( trim($data['variabledestino']));
                      $values['vari_id'] =  trim($data['variabledestino']) != '' ? trim($data['variabledestino']) : '0' ; 
                 }

                 $values['htp_metodo_mascara'] =  trim($data['htp_metodo_mascara']) != '' ? trim($data['htp_metodo_mascara']) : '0' ; 
                 $values['htp_mascara_parametro'] =  trim($data['htp_mascara_parametro']) != '' ? trim($data['htp_mascara_parametro']) : '0' ; 
             } 
             else
             {
                 $values['htp_importar_horas'] = '0';
                 $values['vari_id'] = '0';
                 $values['htp_metodo_mascara'] = '';
                 $values['htp_mascara_parametro'] = '';
             }

             $ok = $this->hojadiariotipoplati->registrar($values);


        }
        else
        {
            $ok = false;
        }
    

        $response =  array(
             
              'result' =>  ($ok)? '1' : '0',
              'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
              'data' => array('key' => $key )
         );
         
         echo json_encode($response);

    }

    public function data($get = '')
    {

        $this->load->library(array('App/horario', 'App/tipoplanilla', 'App/hojadiariotipo', 'App/hojadiariotipoplati', 'App/variable' ));


        $data = $this->input->get(); 

        $response = array();

         if($get == 'diaestados')
         {


            $name     = trim($this->input->get('name'));
            $t        = explode('*',$name);
            $name     = $t[0];
            
            $plati_key = $this->input->get('view');
            $plati_id =  $this->tipoplanilla->get_id($plati_key);
            
            $rs = $this->hojadiariotipo->get_all_plati($plati_id, array('visualizar' => '1') );
          

            foreach($rs as $reg)
            {
        
                $data =    array(
                                   'value'      => trim($reg['hatd_key']),
                                   'name'       => trim($reg['hatd_nombre']),
                                   'otro_dato'  => trim($reg['alias'])
                                );

                $response[] = $data;
                $c++;
            }
        

            echo json_encode($response);

         }
         else if($get == 'horarios')
         {

             $rs = $this->horario->get_list();
              

             foreach($rs as $reg)
             {
             
                 $data =    array(
                                    'value'      => trim($reg['hor_key']),
                                    'name'       => trim($reg['hor_alias']),
                                    'otro_dato'  => trim($reg['alias'])
                                 );

                 $response[] = $data;
                 $c++;
             }
             

             echo json_encode($response);
         }
         else
         {

         }

    }   

    public function planillatipo_horario_modificar()
    {


        $this->load->library(array('App/horario', 'App/tipoplanilla', 'App/hojadiariotipo', 'App/hojadiariotipoplati', 'App/variable' ));

        $data = $this->input->post();

        $plati_key = trim($data['view']);
        $plati_id =  $this->tipoplanilla->get_id($plati_key);

        $plati_info = $this->tipoplanilla->get($plati_id);

        $horario_defecto = $this->tipoplanilla->get_horario_defecto(array('plati_id' => $plati_id));

        $estados_dias = $this->hojadiariotipo->get_all_plati($plati_id, array('visualizar' => '1') );
        
        $horarios = $this->horario->get_list();

        $this->load->view('planillas/v_asistencias_planillatipo_horario_config', array( 'plati_info' => $plati_info, 
                                                                                        'dias' => $horario_defecto, 
                                                                                        'estados_dias' => $estados_dias, 
                                                                                        'horarios' => $horarios ) );

    }


    public function registrar_horario_pordefecto()
    {

         $this->load->library(array('App/horario', 'App/tipoplanilla', 'App/hojadiariotipo', 
                                    'App/hojadiariotipoplati', 'App/planillatipodiahorario', 'App/planillatipodiaestado' ));
          
         $data = $this->input->post();
 
         $plati_id = $this->tipoplanilla->get_id($data['view']); 

         $dias_horario = array();
         $d = 0;
         foreach($data['horario'] as $horario_dia)
         {
            
            $hor_id = $this->horario->get_id($horario_dia); 
            $dias_horario[] = array('dia' => $d, 'hor_id' => $hor_id);
            $d++;
         }

         $ok = $this->planillatipodiahorario->actualizar($plati_id, $dias_horario);
        
         $dias_estado = array();
         $d = 0;
         foreach($data['estado'] as $indx => $estado_dia)
         {
            
            $hatd_id = $this->hojadiariotipo->get_id($estado_dia); 
            $dias_estado[] = array('dia' => $d, 'hatd_id' => $hatd_id, 'laborable' => $data['laborable'][$indx] );
            $d++;
         }

     
         $ok = $this->planillatipodiaestado->actualizar($plati_id, $dias_estado);
 
         $this->planillatipodiahorario->actualizar_horario_trabajadores($plati_id, array());
         
         $this->planillatipodiaestado->actualizar_estado_trabajadores($plati_id, array());


         $response =  array(
              
               'result' =>  ($ok)? '1' : '0',
               'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
               'data' => array('key' => '' )
          );
          
          echo json_encode($response);


    }

    public function exportar_asistencia_excel()
    {


         $this->load->library(array('App/hojadiariotipo',
                                    'App/tipoplanilla',
                                    'App/planillatipocategoria'));
  
         $data =  $this->input->post(); 
  
         $plati_id = $this->tipoplanilla->get_id($data['planillatipo']);

         $config =  $this->hojaasistencia->get_plati_config($plati_id); 


         $params = array(); 
        
         $params['tipobusqueda'] = $data['tipobusqueda'];

        
         if($params['tipobusqueda'] == '1')
         {
             $params['plati_id'] = $plati_id; 
             $params['tarea_id'] = '';
             $params['area_id'] = '';
             $params['hoja'] = '';
             $params['trabajador'] = '';
         }   
         else if($params['tipobusqueda'] == '2')
         {
             $params['plati_id'] = '';
             $params['tarea_id'] =  $data['tarea'];
             $params['area_id'] = '';
             $params['hoja'] = '';
             $params['trabajador'] = '';
         } 
         else if($params['tipobusqueda'] == '3')
         {
             $params['plati_id'] = '';
             $params['tarea_id'] =  '';
             $params['area_id'] = $data['dependencia'];
             $params['hoja'] = '';
             $params['trabajador'] = '';
         }
         else if($params['tipobusqueda'] == '4')
         {
             $params['plati_id'] = '';
             $params['tarea_id'] =  '';
             $params['area_id'] = '';
             $params['hoja'] =  $data['codigohoja'];
             $params['trabajador'] = '';
         }
         else if($params['tipobusqueda'] == '5')
         {
             $params['plati_id'] = '';
             $params['tarea_id'] =  '';
             $params['area_id'] = '';
             $params['hoja'] =   '';
             $params['dni'] = xss_clean(trim($data['dni']));
         }
         else
         {
             $params['plati_id'] = $plati_id; 
             $params['tarea_id'] = '';
             $params['area_id'] = '';
             $params['hoja'] = '';
             $params['trabajador'] = '';
         }
     
         $params['mostraractivos'] = $data['mostraractivos'];

         $params['fechadesde'] = $data['fechadesde'];
         $params['fechahasta'] = $data['fechahasta'];

         $estados_dia = $this->hojadiariotipo->getAll();
         $rs_estados_dia = array();

         foreach($estados_dia as $st)
         {
             $rs_estados_dia[$st['hatd_id']] = $st;
         } 

         $params['modo_ver'] = (trim($data['ver_modo']) == '' ? '1' : trim($data['ver_modo']) );
   
         $params['count'] = false;
 
         $params['limit'] = 0;
         $params['offset'] = 0;

         $contador_inicial =  1;

         $params['incluir_tareas'] = true;

         $calendario_data = $this->hojaasistencia->get_registro_asistencia($params, true, false);
        
         $generado = true;

         $this->load->view('exportar/xls_registrodeasistencia', array( 'calendario' => $calendario_data, 
                                                                       'resumen' => $resumen_data, 
                                                                       'rs_estados_dia' => $rs_estados_dia,
                                                                       'total_registros' => $total_registros,
                                                                       'params' => $params,  
                                                                       'config' => $config,
                                                                       'paginaactual' => $data['pagina'],
                                                                       'contador_inicial' => $contador_inicial,
                                                                       'file_xls' => 'docsmpi/exportar/resumen_asistencia_excel.xls'
                                                                      )); 
         

         $result = array(
                           'result' => ( $generado ? '1' : '0'), 
                           'file'   => ( $generado ? ('resumen_asistencia_excel.xls') : '' )
                        );

         echo json_encode($result);
                 
    }


    public function print_calendario_asistencia()
    {
          

    }

    public function revertir_importacion()
    {


        $this->load->library(array('App/planillaasistencia'));

         $datos = $this->input->post();

         $plaasi_key = $datos['view'];

         $plaasi_id = $this->planillaasistencia->get_id($plaasi_key);

         $rs = $this->planillaasistencia->revertir($plaasi_id);

         $response =  array(
             
                      'result'  =>  ($rs) ? '1' : '0',
                      'mensaje' =>  ($rs) ?  'Operacion realizada correctamente' : 'Ocurrio un error durante la operacion',
                      'data'    =>  array('key' => $datos['view'] )
                 );
                 
         echo json_encode($response);

    }


    public function generar_calendario()
    {



        $dia = date('j',strtotime('2017-12-31'));
        $mes = date('n',strtotime('2017-12-31'));
        $ano = date('Y',strtotime('2017-12-31'));

        $mk_fin_hoja  =  mktime(0,0,0,$mes,$dia,$ano);
        
        $mk_limite = $mk_fin_hoja;

        var_dump($mk_limite);



        $dia = date('j',strtotime('2015-01-01'));
        $mes = date('n',strtotime('2015-01-01'));
        $ano = date('Y',strtotime('2015-01-01'));
        $mk_inicio_hoja  =  mktime(0,0,0,$mes,$dia,$ano);
        
        $mk_current = $mk_inicio_hoja;


        while($mk_current <= $mk_limite )  // va recorriendo y generando dia a dia desde el inicio de la hoja hasta el final
        {
            $n_fecha    =  date("d/m/Y",mktime(0,0,0,$mes,$dia,$ano)); 

            echo $n_fecha.'<br/>';


            $sql = " INSERT INTO public.calendario(ano_eje, caldia_dia) VALUES(?,?)";

            $this->db->query($sql, array($ano, $n_fecha));


            $dia++;
            $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);
        }

    }


    public function registro_diario(){

    }

    public function registro_licencias(){


        $this->load->library(array('App/licencia'));

        $datos = $this->input->post();  
        
        $anios = $this->anioeje->get_list() ;
        
        $tipo_licencias = $this->licencia->get_tipos(array());

        $tipoTrabajador  = $this->tipoplanilla->get_all();

        $this->load->view('planillas/p_registro_licencias', array( 'tipoTrabajador' => $tipoTrabajador, 'anios' => $anios, 'tipo_licencias' => $tipo_licencias));

    }

    public function configurar_reporte_asistencia(){

        $this->load->view('planillas/v_configurar_reporte_asistencia', array( 'tipoTrabajador' => $tipoTrabajador, 'anios' => $anios, 'tipo_licencias' => $tipo_licencias));
   
    }
 
    public function actualizacion_de_fechas_sincierre(){

        $datos = $this->input->post();
 
        if($datos['planillatipo'] != '0')
        {
            $plati_id = $this->tipoplanilla->get_id($datos['planillatipo']); 

            $plati_info = $this->tipoplanilla->get($plati_id);

            $in_plati = $plati_id;
        }
        else{   
        
            $tiposPlanilla = $this->tipoplanilla->get_all( 1, array('tipo_registro_asistencia' => $datos['tipo_registro_asistencia'])); 
            $plati_id = array();

            foreach ($tiposPlanilla as $tp) {
                array_push($plati_id, $tp['plati_id']);
            }
            $in_plati = implode(',', $plati_id);
         
        }


        $rs = $this->hojaasistencia->getDiasSinHoraSalida(array('fecha_inicio' => $datos['fechadesde'], 'fecha_fin' => $datos['fechahasta'],'plati_id' => $in_plati ));

        $this->load->view('planillas/v_actualizacion_fechacierre', array('rs' => $rs, 'datos' => $datos, 'plati_nombre' => $plati_info['plati_nombre']  ));

    }

    public function actualizar_hora_salida_dia(){   
        
        $datos =  $this->input->post();

        $valores_hora = explode(' ',  $datos['hora']);
        $hora = $valores_hora[4];

        $ok = $this->hojaasistencia->actualizar_hora_salida_dia(array('dia' => $datos['dia'], 'indiv_id' => $datos['trabajador'], 'hora_salida' => $hora ));
 
        $response =  array(
            
                     'result'  =>  ($ok) ? '1' : '0',
                     'mensaje' =>  ($ok) ?  'Operacion realizada correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $datos['view'] )
                );
                
        echo json_encode($response);
    }

    public function actualizar_falta_dia(){

        $datos =  $this->input->post();


        $ok = $this->hojaasistencia->actualizar_falta_dia(array('dia' => $datos['dia'], 'indiv_id' => $datos['trabajador'] ));
        
        $response =  array(
            
                     'result'  =>  ($ok) ? '1' : '0',
                     'mensaje' =>  ($ok) ?  'Operacion realizada correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $datos['view'] )
                );
                
        echo json_encode($response);
                
    }
 
    public function especificar_lugar_trabajo(){
        
      
        $tipos_empleado = $this->tipoplanilla->get_all(1, array('tipo_registro_asistencia' => '2')); 

         
        $this->load->view('planillas/p_especificar_lugar_de_trabajo', array(
                                                                   'tipos' => $tipos_empleado, 
                                                                   'dependencias' => $dependencias, 
                                                                   'cargos' => $cargos 
                                                                  ));
        
    }

    public function actualizar_lugar_trabajo(){

        $this->load->library(array('App/persona'));

        $datos = $this->input->post();

        $keys = explode('_', $datos['trabajadores']);

        $ok = $this->persona->actualizar_lugar_de_trabajo($datos['lugar_de_trabajo'], $keys );
 
        $response =  array(
            
                     'result'  =>  ($ok) ? '1' : '0',
                     'mensaje' =>  ($ok) ?  'Operacion realizada correctamente' : 'Ocurrio un error durante la operacion',
                     'data'    =>  array('key' => $datos['view'] )
                );
                
        echo json_encode($response);
    }

}

 