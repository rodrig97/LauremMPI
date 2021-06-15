<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');
 

class escalafon extends CI_Controller {
    
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
          

        $this->load->library(array( 
                                     'App/persona',
                                     'App/tipoplanilla',
                                     'App/descansomedico',
                                     'App/ocupacion',
                                     'App/licencia',
                                     'Catalogos/reniec',
                                     'Catalogos/afp',
                                     'Catalogos/banco',
                                     'Catalogos/brevet',
                                     'Catalogos/pais',
                                     'Catalogos/departamento',
                                     'Catalogos/dependencia',
                                     'Catalogos/categoriarem',
                                     'Catalogos/meta',
                                     'Catalogos/cargo',
                                     'Catalogos/categorialaboral',
                                     'Catalogos/tipoestudio',
                                     'Catalogos/centroestudio',
                                     'Catalogos/especialidad',
                                     'Catalogos/parentesco',
                                     'Catalogos/ubicacion'
                            ));
          
    }
  
    
    public function ui_registrar(){
  

       if( $this->user->has_key('TRABAJADOR_REGISTRAR_NUEVO') )
       {       

             $this->load->library(array('Catalogos/ubicacion'));
     
             $dni            = trim($this->input->post('dni'));
             $datos_reniec   = $this->reniec->buscar_dni($dni);
             $afps           = $this->afp->load_for_combo(false); // 
             $bancos         = $this->banco->load_for_combo(false);
             $brevetes       = $this->brevet->load_for_combo(false);
             $departamentos  = $this->ubicacion->get_departamentos();
             $tipos_empleado = $this->tipoplanilla->load_for_combo(false,'plati_tipoempleado');
             $tipo_individuo = ($this->input->post('tipoindividuo') == '') ? TIPOINDIVIDUO_TRABAJADOR : $this->input->post('tipoindividuo');
             $dni_editable   = ($this->input->post('tipoindividuo') == TIPOINDIVIDUO_TRABAJADOR ) ? 'true' : 'false';
           
             $ciudades = $this->ubicacion->get_ciudades();
           
             $this->load->view('escalafon/v_nuevo_view', array( 
                                                                'dni'            => $dni, 
                                                                'reniec'         => $datos_reniec,
                                                                'afps'           => $afps, 
                                                                'bancos'         => $bancos, 
                                                                'brevetes'       => $brevetes, 
                                                                'departamentos'  => $departamentos, 
                                                                'tipo_empleados' => $tipos_empleado,
                                                                'tipo_individuo' => $tipo_individuo,
                                                                'dni_editable'   => $dni_editable,
                                                                'ciudades'       => $ciudades 
                                                                ));
        
        }
        else{


            die(PERMISO_RESTRINGICO_MENSAJE);
        }
    }
    
    public function ui_full_info()
    {
        

         if( trim($this->input->post('from')) == 'asistencia' && $this->user->has_key('ASISTENCIAS_ACCESOESCALAFON_DESDEHOJA') == FALSE  )
         {
               die(PERMISO_RESTRINGICO_MENSAJE);
         }


         $emp_key        = trim($this->input->post('empkey'));
         $emp_id         = $this->persona->get_id($emp_key);
         
         $pers_info      = $this->persona->get_info($emp_id);
         
         $estado_trabajo = ( $pers_info['vigente'] == '1') ? ' VIGENTE ' : ' INACTIVO ';
         
         $afps           = $this->afp->load_for_combo(false);
         $bancos         = $this->banco->load_for_combo(false);
         $brevetes       = $this->brevet->load_for_combo(false);
         $departamentos  = $this->ubicacion->get_departamentos();
         
         $dependencias   = $this->dependencia->get_list();
         $cargos         = $this->cargo->get_list(); 
         $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
        // $metas          = $this->meta->get_list(); 
         
         
         $tipoestudio    =  $this->tipoestudio->load_for_combo(true);
         
         $categorias     = $this->categorialaboral->get_list(); 
         
         $especialidades = $this->especialidad->load_for_combo(false);
         $centrosestudio = $this->centroestudio->load_for_combo(false);
         
         $parentescos    = $this->parentesco->load_for_combo(true);
    
         
         $ciudades       = $this->ubicacion->get_ciudades();
         
         $categorias_rem = $this->categoriarem->load_for_combo(true);


         $beneficiario = $this->persona->es_beneficiario_judicial($emp_id);

          $ciudades = $this->ubicacion->get_ciudades();
         
          
         $tipo_licencias = $this->licencia->get_tipos(array());

         $tipos_descanso_medico = $this->descansomedico->get_tipos(); 

         $anios = $this->anioeje->get_list() ;

        $this->load->view('escalafon/v_registrar_fullinfo', array(   'anios'         => $anios,
                                                                     'pers_info'      => $pers_info,
                                                                     'afps'           => $afps, 
                                                                     'bancos'         => $bancos, 
                                                                     'brevetes'       => $brevetes, 
                                                                     'departamentos'  => $departamentos,  
                                                                     'ciudades'  => $ciudades,  
                                                                     'tipo_empleados' => $tipos_empleado, 
                                                                     'dependencias'   => $dependencias, 
                                                                     //'metas'          => $metas,  
                                                                     'cargos'         => $cargos,
                                                                     'categorias'     => $categorias,
                                                                     'tipoestudio'    => $tipoestudio,
                                                                     'especialidades' => $especialidades,
                                                                     'centroestudio'  => $centrosestudio,
                                                                     'parentescos'    => $parentescos,
                                                                     'ciudades'       => $ciudades,
                                                                     'categoriasrem'  => $categoriasrem,
                                                                     'estado_trabajo' => $estado_trabajo,
                                                                     'beneficiario'   => $beneficiario,
                                                                     'tipos_descanso_medico' => $tipos_descanso_medico,
                                                                     'tipo_licencias' => $tipo_licencias
                                                                     )
                                                                );
    }

    
    
    public function ui_personal_registrado(){
        
        
        $dependencias   = $this->dependencia->get_list();
        $cargos         = $this->cargo->get_list(); 
        $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_nombre'); 
         
        $this->load->view('escalafon/p_personal_registrado', array(
                                                                   'tipo_empleados' => $tipos_empleado, 
                                                                   'dependencias' => $dependencias, 
                                                                   'cargos' => $cargos 
                                                                  ));
        
    }
  

    public function registrar_nuevo()
    {
       
      if( $this->user->has_key('TRABAJADOR_REGISTRAR_NUEVO') )
      { 
 
            $this->db->trans_begin();

            $err     = false;
            $mensaje = '';
            $data    = array();
         
            $data =  $this->input->post();

            foreach ($data as $key => $value)
            {
                $data[$key] = trim($value);
            }
            
            $this->load->library('App/situacionlaboral');
  
            $t_x =  $this->persona->get_by_dni($data['dni']);


            if($t_x['indiv_id'] != '')
            {

                  $response =  array(
                    
                     'result'  =>   '0',
                     'mensaje' =>   'EL DNI YA ESTA REGISTRADO',
                     'data'    =>    array( 
                                            'key'     => $code, 
                                            'nombres' => $nombres, 
                                            'dni'     => $dni  
                                          )
                  );
                  
                  echo json_encode($response);

                  die();
            }

           list($data['distrito'], $data['provincia'], $data['departamento'] ) = explode('-', $data['ciudad']);
           
           $NEWDATA = array(
                                               'indiv_appaterno'         => strtoupper($data['paterno']),
                                               'indiv_apmaterno'         => strtoupper($data['materno']),
                                               'indiv_nombres'           => strtoupper($data['nombres']),
                                               'indiv_sexo'              => $data['sexo'],
                                               'indiv_ciudado_origentxt' => $data['ciudad'],
                                               'indiv_nacionalidad'      => $data['nacionalidad'],
                                               'indiv_fechanac'          => $data['fechanac'],
                                               'indiv_dni'               => $data['dni'],
                                               'indiv_ruc'               => $data['ruc'],
                                               'indiv_libmilitar_tipo'   => $data['tipolibreta'],
                                               'indiv_libmilitar_cod'    => $data['codlibreta'],
                                               'indiv_brevete_tipo'      => $data['tipobrevete'],
                                               'indiv_brevete_cod'       => $data['codbrevete'],
                                               'indiv_direccion1'        => $data['direccion1'],
                                               'indiv_direccion2'        => $data['direccion2'],
                                               'indiv_telefono'          => $data['fono'],
                                               'indiv_celular'           => $data['celular'],
                                               'indiv_email'             => $data['email'],
                                               'indiv_estadocivil'       => $data['estadocivil'],
                                               'dpto_id'                 => $data['departamento'],
                                               'prvn_id'                 => $data['provincia'],
                                               'dstt_id'                 => $data['distrito'],
                                               'indti_id'                => $data['tipoindividuo'],
                                               'indiv_essalud'          => $data['essalud']
                             );
            
            
            list($id,$code) = $this->persona->registrar($NEWDATA,true);
             
            $situ =  $this->tipoplanilla->get_id( trim($data['situlaboral']) );
            

            if($id != false && $id != null)
            {
                if(   $data['tipoindividuo'] == TIPOINDIVIDUO_TRABAJADOR )
                { 


                      $values = array(
                                        'pers_id'             => $id,  
                                        'plati_id'            => $situ,
                                        'persla_fechaini'     => $data['fechaini'],
                                        'persla_vigente'      => '1',
                                        'persla_generado'     => '1',
                                        'persla_porcompletar' => '1' 
                                  );
                   


                      if( $data['fechafin'] == '')
                      {
                          $values['persla_terminoindefinido'] = '1';
                      } 
                      else{

                          $values['persla_fechafin'] = trim($data['fechafin']);
                      }

                      if( $data['montocontrato'] != '')
                      { 

                          if(is_numeric($data['montocontrato']) == FALSE) $data['montocontrato'] = '0';
                          
                          $values['persla_montocontrato'] = trim($data['montocontrato']);
                      } 

                    
                     // $values['cargo_id'] 


                      $this->situacionlaboral->registrar($values, false);

                            
                      if($data['tipopension'] == PENSION_AFP ) 
                      {
                                
                                $afp      =  $data['afp'];  
                                $afp      =  $this->afp->get_id($afp);
                                $modo_afp =  $data['modoafp'];
                                $aplica_invalidez = $data['aplica_invalidez'];
                      }
                      else{
                             
                              $modo_afp = '0';
                              $afp      = '0';
                              $aplica_invalidez = '1';
                              $data['codpension'] = '';
                      }

                      if( AFP_QUITARINVALIDEZ_AUTOMATICO ) $aplica_invalidez = '1';

                      $this->persona->add_pension( $id, array(
                                                                  'afp_id'      => $afp,
                                                                  'pentip_id'   => $data['tipopension'],
                                                                  'peaf_codigo' => $data['codpension'],
                                                                  'peaf_jubilado' => $data['estajubilado'],
                                                                  'afm_id'      => $modo_afp,
                                                                  'peaf_invalidez' => $aplica_invalidez
                                                              ) 
                                                  );

                      

                }
                
                
                if($data['hascbanco']=='1')
                {
                    
                    $banco_id = $this->banco->get_id( $data['banco']);
                    
                    $this->persona->add_cuentadeposito( $id, array( 
                                                                    'ebanco_id'           => $banco_id,
                                                                    'pecd_cuentabancaria' => $data['bancocod'] 
                                                                    )); 
                }

              /*  if($data['hasessalud']=='1')
                {
                    $this->persona->add_essalud( $id, array(  'persa_codigo' => $data['essaludcod'] )); 
                }*/
                
                
            }
          
        
            $nombres = $data['paterno'].' '.$data['materno'].' '.$data['nombres'];
            $dni     = $data['dni'];

            $ok = true;

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            
            }
            else
            {
                $this->db->trans_commit();
                $ok = true;
            }     

            $response =  array(
                
                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' =>  ($ok)? 'Registro completado satisfactoriamente' : 'Ocurrio un error durante la operacion',
                 'data'    =>  array('key' => $code, 'nombres' => $nombres, 'dni' => $dni  )
            );
            

            echo json_encode($response);

       }
       else{

           $ok = false; 

           $response =  array(
               
                'result'  =>  '0',
                'mensaje' =>  PERMISO_RESTRINGICO_MENSAJE,
                'data'    =>  array('key' => $code, 'nombres' => $nombres, 'dni' => $dni  )
           );
           

           echo json_encode($response);
       }
        
    }
    
    


    public function actualizar_infopersonal()
    {
      
      if( $this->user->has_key('TRABAJADOR_MODIFICAR_INFO_PERSONAL') )
      {   

           $this->db->trans_begin();


           $err = false;
           $mensaje = '';
           $data = array();
         
           $data =  $this->input->post();
           
           foreach ($data as $key => $value)
           {
               $data[$key] = trim($value);
           }

           $empkey = trim( $data['empkey']);
           
           $emp_id = $this->persona->get_id($empkey);
           
           $info_actual = $this->persona->get_info($emp_id);
             
           
           if($data['hasruc'] == '0') $data['ruc'] = '';
           
           if($data['hasbrevete'] == '0'){
                 $data['tipobrevete'] = '0';
                 $data['codbrevete'] = '';
           }
           
           if($data['haslibreta'] == '0'){
                 $data['tipolibreta'] = '0';
                 $data['codlibreta'] = '';
           } 

            
          if($data['hascbanco']=='1')
          {
              
              if( trim($info_actual['ebanco_id'])!= $data['banco']  || trim($info_actual['pecd_cuentabancaria'])!= trim($data['bancocod']) ){

                  $banco_id = $this->banco->get_id( $data['banco']);
                  $this->persona->add_cuentadeposito( $emp_id, array( 'ebanco_id' => $banco_id,
                                                                      'pecd_cuentabancaria' => $data['bancocod'] )); 
              }

          }
          else
          {
              $this->persona->remove_cuenta($emp_id);
          }
            
          
            
       /*   
          if($data['hasessalud']=='1')
          {
              if( trim($info_actual['persa_codigo'])!= trim($data['essaludcod']) ){

                   $this->persona->add_essalud( $emp_id, array(  'persa_codigo' => $data['essaludcod'] )); 
               
              }
          }
          else{
              $this->persona->remove_essalud($emp_id);
          }*/


           list($data['distrito'], $data['provincia'], $data['departamento'] ) = explode('-', $data['ciudad']);
           
           $t_x =  $this->persona->get_by_dni($data['dni']);
 
           if( trim($t_x['indiv_id']) != '' && $t_x['indiv_id'] != $info_actual['indiv_id'])
           {

                 $response =  array(
                   
                    'result'  =>   '0',
                    'mensaje' =>   'EL DNI YA ESTA REGISTRADO',
                    'data'    =>    array( 
                                           'key'     => $code, 
                                           'nombres' => $nombres, 
                                           'dni'     => $dni  
                                         )
                 );
                 
                 echo json_encode($response);

                 die();
           }
           
           $NEWDATA = array(
                                    'indiv_appaterno'            => strtoupper($data['paterno']),
                                    'indiv_apmaterno'            => strtoupper($data['materno']),
                                    'indiv_nombres'              => strtoupper($data['nombres']),
                                    'indiv_sexo'                 => $data['sexo'],
                                    'indiv_estadocivil'          => $data['estadocivil'],
                                    //  'pers_ciudad_origen'     => $data['ciudad'], // Hasta obtener el catalogo de ciudades
                                    //  'pers_ciudado_origentxt' => $data['ciudad'],
                                    'indiv_nacionalidad'         => $data['nacionalidad'],
                                    'indiv_fechanac'             => $data['fechanac'],
                                    'indiv_dni'                  => $data['dni'],
                                    'indiv_ruc'                  => $data['ruc'],
                                    'indiv_libmilitar_tipo'      => $data['tipolibreta'],
                                    'indiv_libmilitar_cod'       => $data['codlibreta'],
                                    'indiv_brevete_tipo'         => $data['tipobrevete'],
                                    'indiv_brevete_cod'          => $data['codbrevete'],
                                    'indiv_direccion1'           => $data['direccion1'],
                                    //   'pers_direccion1_obs'   => $data['dir1_obs'],
                                    'indiv_direccion2'           => $data['direccion2'],
                                    //   'pers_direccion2_obs'   => $data['dir2_obs'],
                                    'indiv_telefono'             => $data['fono'],
                                    'indiv_celular'              => $data['celular'],
                                    'indiv_email'                => $data['email'],
                                    'dpto_id'                    => $data['departamento'],
                                    'prvn_id'                    => $data['provincia'],
                                    'dstt_id'                    => $data['distrito'],
                                    'indiv_essalud'              => $data['essalud']
                             );
            
            
           $rs = $this->persona->actualizar($emp_id,$NEWDATA,false);

 
 
          $nueva_afp = $this->afp->get_id($data['afp']);

            if( $info_actual['pentip_id']!= $data['tipopension'] 
               || $info_actual['afp_id'] !=  $nueva_afp 
               || $info_actual['afm_id'] !=  $data['modoafp'] 
               || trim($info_actual['peaf_codigo']) !=  trim($data['codpension'])
               || trim($info_actual['peaf_jubilado']) !=  trim($data['estajubilado'])
               || trim($info_actual['peaf_invalidez']) !=  trim($data['aplica_invalidez']) 
               || trim($info_actual['indiv_fechanac']) !=  trim($data['fechanac']) 

           ){

                   if($data['tipopension'] == PENSION_AFP ) 
                   {
                           $afp =  $data['afp'];
                           $afp = $this->afp->get_id($afp);
                           $modo_afp = $data['modoafp'];
                           $aplica_invalidez = $data['aplica_invalidez'];
                   }
                   else
                   {
                           $afp =  '0';  
                           $modo_afp = '0';
                           $aplica_invalidez = '1';
                           $data['codpension'] = '';
                   }



                   if( AFP_QUITARINVALIDEZ_AUTOMATICO ) $aplica_invalidez = '1';

                  $this->persona->add_pension( $emp_id, array(
                                                               'afp_id'      => $afp,
                                                               'pentip_id'   => $data['tipopension'],
                                                               'peaf_codigo' => $data['codpension'],
                                                               'peaf_jubilado' => $data['estajubilado'],
                                                               'afm_id'      => $modo_afp,
                                                               'peaf_invalidez' => $aplica_invalidez

                                                               )  ); 
           }
           
         

            
          
            if($this->db->trans_status() === FALSE)
            {
                
                $rs = false; 
            }
            else
            {
     
                $rs = true; 
            }


            $response =  array(
                
                 'result' =>  ($rs != false && $rs != null)? '1' : '0',
                 'mensaje'  => ($rs != false && $rs != null)? 'Registro actualizado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $empkey )
            );
            
            echo json_encode($response);

        }
        else
        {

            $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  =>  PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array('key' => $empkey )
            );
            
            echo json_encode($response);

        }
            
        
    }
    
    public function registrar_comision()
    {
        
        if( $this->user->has_key('TRABAJADOR_COMISIONSERVICIO_EDITAR') )
        {  
        
            $this->load->library('App/comision','App/licencia');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }


            $licencias_del_trabajador =  $this->licencia->getLicenciasDia(array('indiv_id' => $emp_id, 
                                                                                 'modo_busqueda_fecha_alternativo' => true,
                                                                                 'fechadesde' => $data['fechaini'], 
                                                                                 'fechahasta' => $data['fechafin'],
                                                                                 'incluir' => array('vacaciones', 'descanso_medico', 'comision_servicio', 'licencia') 
                                                                                ));

             

            if( sizeof($licencias_del_trabajador) > 0 ){
            
                $response =  array(
                     
                      'result' =>  '0',
                      'mensaje'  => 'No se pudo registrar, El trabajador ya tiene una licencia registrada en el periodo ',
                      'data' => array('key' => $key )
                 );
                 
                 echo json_encode($response);

                 return true; 
            }

            

            list($data['distrito'], $data['provincia'], $data['departamento']) = explode('-', $data['ciudad']);

            $values = array(
                            'pers_id'         => $emp_id,
                            'peco_documento'  => $data['documento'], 
                            'peco_fechadocu'  => $data['fechadoc'],
                            'peco_motivo'     => $data['motivo'],
                            'peco_fechadesde' => $data['fechaini'],
                            'peco_fechahasta' => $data['fechafin'],
                            'peco_emisor'     => $data['autoriza'],
                            'departamento'    => $data['departamento'],
                            'provincia'       => $data['provincia'],
                            'distrito'        => $data['distrito']
                            //'lugar' => $data['documento']
                    
                            );

 

           if($data['sisgedo_doc']!= ''){
                 
                 $values['doc_sisgedo'] = $data['sisgedo_doc'];
                 $values['doc_codigo']  = $data['sisgedo_codigo'];
                 $values['doc_tipo']    = $data['sisgedo_tipodoc'];
                 $values['doc_asunto']  = $data['sisgedo_asunto'];
                 $values['doc_firma']   = $data['sisgedo_firma'];
                 $values['doc_fecha']   = $data['sisgedo_fecha'];

            } 
            
            
            list($id,$key) = $this->comision->registrar($values, true);
            

            $this->load->library(array('App/hojaasistencia'));


            $params = array(
                            'indiv_id'  => $emp_id,
                            'fechaini'       => $data['fechaini'],
                            'fechafin'       => $data['fechafin'],
                            'registro'  => $id,
                            'tipo'      => ASISDET_COMISIONSERV
                           );

            $this->hojaasistencia->registrar_evento_dia( $params );



/*            $fecha_ini =  strtotime($data['fechaini']);
            $fecha_fin =  strtotime($data['fechafin']);
 
            $dia = date('j', $fecha_fin);
            $mes = date('n', $fecha_fin);
            $ano = date('Y', $fecha_fin);
            $mk_limite  =  mktime(0,0,0,$mes,$dia,$ano);  
 
            $dia = date('j', $fecha_ini);
            $mes = date('n', $fecha_ini);
            $ano = date('Y', $fecha_ini);
            $mk_inicio  =  mktime(0,0,0,$mes,$dia,$ano);  

            $mk_current = $mk_inicio;

            while($mk_current <= $mk_limite )
            { 
  
              $n_fecha    =  date("d/m/Y",mktime(0,0,0,$mes,$dia,$ano)); 

              $dia+=1;
              $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);
            }*/


            $response =  array(
                
                 'result'   =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  =>  ($id != false && $id != null)? 'Comision de servicio registrada correctamente' : 'Ocurrio un error durante la operacion',
                 'data'     =>  array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
            
            echo json_encode($response);

       }
       else
       {
             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' =>  array()
            );
            
            echo json_encode($response);

       }
        
    }
    
    
    public function registrar_licencia()
    { 

       if( $this->user->has_key('TRABAJADOR_LICENCIAS_EDITAR') )
       {  
         
            $this->load->library('App/licencia');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }
            
            $motivo = ($data['motivo'] == '1') ? 1 : 0;
            $motivo_des =  trim($data['motivo_des']);


            $licencias_del_trabajador =  $this->licencia->getLicenciasDia(array('indiv_id' => $emp_id, 
                                                                                 'modo_busqueda_fecha_alternativo' => true,
                                                                                 'fechadesde' => $data['fechaini'], 
                                                                                 'fechahasta' => $data['fechafin'],
                                                                                 'incluir' => array('vacaciones', 'descanso_medico', 'comision_servicio', 'licencia') 
                                                                                ));

            
            
            if( sizeof($licencias_del_trabajador) > 0 ){
            
                $response =  array(
                     
                      'result' =>  '0',
                      'mensaje'  => 'No se pudo registrar, El trabajador ya tiene una licencia registrada en el periodo ',
                      'data' => array('key' => $key )
                 );
                 
                 echo json_encode($response);

                 return true; 
            }
            
            
            $values = array(
                            'pers_id' => $emp_id,
                            'peli_documento' => $data['documento'], 
                       
                            'peli_emisor' => $data['autoriza'],
                            'peli_fechavigencia' => $data['fechaini'],
                            'peli_fechacaducidad' => $data['fechafin'],
                            'peli_essalud' => $motivo,
                            'peli_observacion' => $motivo_des,
                            'peli_tipolicencia' => $data['tipo'],
                            'peli_descripcion' => $data['descripcion']
                            //'lugar' => $data['documento']
                    
                            );


           if($data['sisgedo_doc']!= ''){
                 
                 $values['doc_sisgedo'] = $data['sisgedo_doc'];
                 $values['doc_codigo']  = $data['sisgedo_codigo'];
                 $values['doc_tipo']    = $data['sisgedo_tipodoc'];
                 $values['doc_asunto']  = $data['sisgedo_asunto'];
                 $values['doc_firma']   = $data['sisgedo_firma'];
                 $values['doc_fecha']   = $data['sisgedo_fecha'];

            } 
            
            
            
           list($id,$key) = $this->licencia->registrar($values, true);


           $this->load->library(array('App/hojaasistencia'));

           $params = array(
                           'indiv_id'  => $emp_id, 
                           'fechaini'       => $data['fechaini'],
                           'fechafin'       => $data['fechafin'],
                           'registro'  => $id,
                           'tipo'      => ASISDET_LICENCIASIND
                          );

           $this->hojaasistencia->registrar_evento_dia( $params );

            
             $response =  array(
                
                 'result' =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? 'Licencia registrada correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
            
            echo json_encode($response);

        }
        else
        {

             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );
            
            echo json_encode($response);
        }
        
        
    }
    


    public function registrar_descansomedico()
    { 

       if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_EDITAR') )
       {  
         
            $this->load->library('App/descansomedico','App/licencia');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }


            $licencias_del_trabajador =  $this->licencia->getLicenciasDia(array('indiv_id' => $emp_id, 
                                                                                 'modo_busqueda_fecha_alternativo' => true,
                                                                                 'fechadesde' => $data['fechaini'], 
                                                                                 'fechahasta' => $data['fechafin'],
                                                                                 'incluir' => array('vacaciones', 'descanso_medico', 'comision_servicio', 'licencia') 
                                                                                ));

            
            
            if( sizeof($licencias_del_trabajador) > 0 ){
            
                $response =  array(
                     
                      'result' =>  '0',
                      'mensaje'  => 'No se pudo registrar, El trabajador ya tiene una licencia registrada en el periodo ',
                      'data' => array('key' => $key )
                 );
                 
                 echo json_encode($response);

                 return true; 
            }
            
           
            $values = array(
                            'pers_id'         => $emp_id,
                            'perdm_documento' => $data['documento'], 
                            'perdm_emisor'    => $data['autoriza'], 
                            'tdm_id'      => $data['tipo'],
                            'perdm_obs'       => $data['descripcion'],
                            'perdm_fechaini'  => $data['fechaini'],
                            'perdm_fechafin'  => $data['fechafin']
                    
                            );
 
             
            
           list($id,$key) = $this->descansomedico->registrar($values, true);
            

           $this->load->library(array('App/hojaasistencia'));

           $params = array(
                           'indiv_id'  => $emp_id,
                           'fechaini'       => $data['fechaini'],
                           'fechafin'       => $data['fechafin'],
                           'registro'  => $id,
                           'tipo'      => ASISDET_DESCANSOMEDICO
                          );

           $this->hojaasistencia->registrar_evento_dia( $params );

             $response =  array(
                
                 'result' =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? 'Descanso medico registrado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
            
            echo json_encode($response);

        }
        else
        {

             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );
            
            echo json_encode($response);
        }
        
        
    }
     

    public function registrar_vacaciones()
    { 

       if( $this->user->has_key('TRABAJADOR_VACACIONES_EDITAR') )
       {  
         
            $this->load->library('App/vacacionestrabajador','App/licencia');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }
            

            $licencias_del_trabajador =  $this->licencia->getLicenciasDia(array('indiv_id' => $emp_id, 
                                                                                 'modo_busqueda_fecha_alternativo' => true,
                                                                                 'fechadesde' => $data['fechaini'], 
                                                                                 'fechahasta' => $data['fechafin'],
                                                                                 'incluir' => array('vacaciones', 'descanso_medico', 'comision_servicio', 'licencia') 
                                                                                ));

            
            
            if( sizeof($licencias_del_trabajador) > 0 ){
            
                $response =  array(
                     
                      'result' =>  '0',
                      'mensaje'  => 'No se pudo registrar, El trabajador ya tiene una licencia registrada en el periodo ',
                      'data' => array('key' => $key )
                 );
                 
                 echo json_encode($response);

                 return true; 
            }
           
            $values = array(
                            'pers_id'         => $emp_id,
                            'perva_documento' => $data['documento'], 
                            'perva_autoriza'    => $data['autoriza'], 
                            'perva_obs'       => $data['descripcion'],
                            'perva_fechaini'  => $data['fechaini'],
                            'perva_fechafin'  => $data['fechafin']
                    
                            );
    
             
            
           list($id,$key) = $this->vacacionestrabajador->registrar($values, true);


           
           $this->load->library(array('App/hojaasistencia'));

           $params = array(
                           'indiv_id'  => $emp_id,
                           'fechaini'       => $data['fechaini'],
                           'fechafin'       => $data['fechafin'],
                           'registro'  => $id,
                           'tipo'      => ASISDET_VACACIONES
                          );

           $this->hojaasistencia->registrar_evento_dia( $params );
            
             $response =  array(
                
                 'result' =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? ' Registrado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
            
            echo json_encode($response);

        }
        else
        {

             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );
            
            echo json_encode($response);
        }
        
        
    }



    public function registrar_permiso()
    {
        
        if( $this->user->has_key('TRABAJADOR_PERMISOS_EDITAR') )
        {   
        
            $this->load->library('App/permiso');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }
             
            $values = array(
                            'pers_id' => $emp_id,
                            'pepe_documento' => $data['documento'], 
                            'pepe_fechadocu' => $data['fechadoc'],
                            'pepe_emisor' => $data['autoriza'],
                            'pepe_fechadesde' => $data['fechaini'],
                            'pepe_horaini' => $data['horaini'],
                            'pepe_horafin' => $data['horafin'],
                            'pepe_motivo' => $data['motivo'] 
                            //'lugar' => $data['documento']
                    
                            );


              if($data['sisgedo_doc']!= ''){
                 
                 $values['doc_sisgedo'] = $data['sisgedo_doc'];
                 $values['doc_codigo']  = $data['sisgedo_codigo'];
                 $values['doc_tipo']    = $data['sisgedo_tipodoc'];
                 $values['doc_asunto']  = $data['sisgedo_asunto'];
                 $values['doc_firma']   = $data['sisgedo_firma'];
                 $values['doc_fecha']   = $data['sisgedo_fecha'];

            } 
            
            
            list($id,$key) = $this->permiso->registrar($values, true);
            
             $response =  array(
                
                 'result' =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? 'Permiso registrado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
            
            echo json_encode($response);


        }
        else
        {

            $response =  array(
               
                'result' =>  0,
                'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                'data' => array()
           );
           
           echo json_encode($response);

        }
        
    }
    
    
    public function registrar_falta()
    {
        

        if( $this->user->has_key('TRABAJADOR_FALTASTAR_EDITAR') )
        {  
            
            $this->load->library('App/falta');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }
         

            
            $values = array(
                            'pers_id' => $emp_id,
                            'pefal_desde' => $data['fechaini'], 
                            'pefal_hasta' => $data['fechafin'], 
                            'pefal_justificada' => $data['justificada'],
                            'pefal_justificacion' => trim($data['justificacion']),
                            'pefal_observacion' => trim($data['observacion'])
                           
                            );
            
            
           list($id,$key) = $this->falta->registrar($values, true);
            
             $response =  array(
                
                 'result' =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? $tipo_msm.' registrada correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
        }
        else
        {
             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );

        }


        echo json_encode($response);
        
        
    } 


    
    public function registrar_tardanza()
    {
        

        if( $this->user->has_key('TRABAJADOR_FALTASTAR_EDITAR') )
        {  
            
            $this->load->library('App/tardanza');
            
            $emp_key = trim($this->input->post('empkey'));
            
            $emp_id = $this->persona->get_id($emp_key);
            
            $data =  $this->input->post();
            
            foreach($data as $k => $dat){
                $data[$k] = trim($dat);
            }
         

            
            $values = array(
                            'pers_id' => $emp_id,
                            'peft_fecha' => $data['fecha'], 
                            'peft_minutos' => $data['minutos'], 
                            'peft_segundos' => $data['segundos'],
                            'peft_justificacion' => trim($data['justificacion']) 
                           
                            );
            
            
           list($id,$key) = $this->tardanza->registrar($values, true);
            
             $response =  array(
                
                 'result' =>  ($id != '' && $id != null )? '1' : '0',
                 'mensaje'  => ($id != false && $id != null)? $tipo_msm.' registrada correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
            );
        }
        else
        {
             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );

        }


        echo json_encode($response);
        
        
    } 
 
      
    
    public function registrar_academico(){


        if( $this->user->has_key('TRABAJADOR_ACADEMICO_EDITAR') )
        { 
              
              $this->load->library('App/academico');
              
              $emp_key = trim($this->input->post('empkey'));
              
              $emp_id = $this->persona->get_id($emp_key);
              
              $data =  $this->input->post();
              
              foreach($data as $k => $dat){
                  $data[$k] = trim($dat);
              }
              
              
              $ocupacion_label = '';
              $ocupacion_id = '0';
               
              $data['ubicacion'] = '1';
              
              $values = array(
                              'pers_id'            => $emp_id,
                              'tiest_id'           => $data['tipo'],
                              'perac_nombre'       => strtoupper($data['nombre']),  
                              'carpro_id'          => (($data['carrera'] != '' )  ? $data['carrera'] : '0'),    //carrera
                              'especi_id'          => (($data['especialidad'] != '') ? $data['especialidad'] : '0'),    //especialidad
                              'cees_id'            => (($data['centroestudios'] != '') ? $data['centroestudios'] : '0'),  //centro d eestudio
                              'ubig_id'            => $data['ubicacion'], 
                              'perac_modalidad'    => (($data['modalidad'] != '') ? $data['modalidad'] : '0'),
                              'perac_situacion'    => (($data['situacion'] != '') ? $data['situacion'] : '0'),
                              'perac_estadotitulo' => $data['titulo'],
                              'perac_anioestudio'  => $data['anioestudio'],
                              'perac_fecini'       => $data['fechaini'],
                              'perac_fecfin'       => $data['fechafin'],
                              'perac_horas'        => $data['horasacademicas'],
                              'perac_descripcion'  => $data['descripcion']
                             
                              ); 

             if($data['carrera_nombre'] != ''){

                $this->load->library(array('Catalogos/carreraprofesional'));
                list(  $values['carpro_id'] , $key ) = $this->carreraprofesional->registrar( array( 'carpro_nombre' => strtoupper($data['carrera_nombre']) ), true);
             }

               if($data['especialidad_nombre'] != ''){

                $this->load->library(array('Catalogos/especialidad'));
                list(  $values['especi_id'] , $key ) = $this->especialidad->registrar( array( 'especi_nombre' => strtoupper($data['especialidad_nombre']) ), true);
             }
           

             if($data['centroestudios_nombre'] != ''){

                $this->load->library(array('Catalogos/centroestudio'));
                list(  $values['cees_id'] , $key ) = $this->centroestudio->registrar( array( 'cees_nombre' => strtoupper($data['centroestudios_nombre']) ), true);
             }
        

              list($id,$key) = $this->academico->registrar($values, true);
              
               $response =  array(
                  
                   'result' =>  ($id != '' && $id != null )? '1' : '0',
                   'mensaje'  => ($id != false && $id != null)? ' Registro realizado correctamente' : 'Ocurrio un error durante la operacion',
                   'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
              );

        }
        else
        {

             $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );

        }
        
        echo json_encode($response);
         
    }
    
    
    public function registrar_familiar()
    {
        
       if( $this->user->has_key('TRABAJADOR_FAMILIAR_EDITAR') )
       { 

              $this->load->library(array('App/familiar','Catalogos/ocupacionfamiliar'));
              
              $emp_key =  trim($this->input->post('empkey'));
              
              $emp_id  =  $this->persona->get_id($emp_key);
              
              $data    =  $this->input->post();
              

              foreach($data as $k => $dat){
                  $data[$k] = trim($dat);
              }
               

              if( trim($this->input->post('ocupacion')) == '')
              {

                  list($ocupacion_id, $ocupa_key) = $this->ocupacionfamiliar->registrar(array(  'ocupa_nombre' => trim($this->input->post('ocupacion_nombre')) ),true);
       
              }
              else{

                  $ocupacion_id = trim($this->input->post('ocupacion'));
              }
       

              $data['pefa_vivo'] = '1';
                
              $data['pefa_estudiante'] = '0';
                
              if( $data['parentesco'] == FAMILIAR_HIJO )
              {
               
                  if($data['estudiante'] == '1')
                  {
                     $data['pefa_estudiante'] = '1';
                  }

              }

              $values = array(
                              'pers_id'           => $emp_id,
                              'paren_id'          => $data['parentesco'],
                              'pefa_apellpaterno' => $data['paterno'], 
                              'pefa_apellmaterno' => $data['materno'],
                              'pefa_nombres'      => $data['nombres'],
                              'pefa_estadocivil'  => $data['estadocivil'], 
                              'pefa_fechanace'    => $data['fechanac'], 
                              'pefa_observacion'  => $data['observacion'],
                              'pefa_sexo'         => $data['sexo'],
                              'ocupa_id'          => $ocupacion_id,
                              'pefa_vivo'         => $data['pefa_vivo'],
                              'pefa_dni'          => trim($data['dni']),
                              'pefa_estudiante'   => $data['pefa_estudiante']
                              
                              );
              
            
              list($id,$key) = $this->familiar->registrar($values, true);

              $response =  array(
                  
                   'result' =>  ($id != '' && $id != null )? '1' : '0',
                   'mensaje'  => ($id != false && $id != null)? ' Familiar registrado correctamente' : 'Ocurrio un error durante la operacion',
                   'data' => array('key' => $key, 'id' => $id, 'empkey' => $emp_key )
              );
        

        }
        else
        {

            $response =  array(
                
                 'result' =>  '0',
                 'mensaje'  => PERMISO_RESTRINGICO_MENSAJE,
                 'data' => array()
            );
          

        }
        echo json_encode($response);
        
        
        
        
    }
    
    
    public function activar_desactivar_estudiante()
    {

        $this->load->library(array('App/familiar','Catalogos/ocupacionfamiliar'));


        $datos = $this->input->post();

        $codigo = $this->input->post('codigo');
        
        $id = $this->familiar->get_id($codigo);

        $ok = $this->familiar->estudiante_cambio_estado(array('familiar_id' => $id));

        $response =  array(
            
             'result' =>  ($ok) ? '1' : '0',
             'mensaje'  => ($ok) ? ' Operacion realizada correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array()
        );
    
        echo json_encode($response);
        
    }

    
    public function get_info_dni(){
        
        $dni =  trim($this->input->post('dni'));
     
        $info = $this->persona->get_some_info($dni);
       //  var_dump($info);
        if(trim($info['indiv_id'])!='')
        {
             
            $nombre = trim($info['indiv_nombres']).' '.trim($info['indiv_appaterno']).' '.trim($info['indiv_apmaterno']); 
            
            $situ_actual = ( trim($info['tipo_planilla']) != '' ? trim($info['tipo_planilla']) : '-------' );
            $result = 1;
            $mensaje = 'DNI registrado';
            $html = '   
                         <div align="center" class="dvpadding3">   
                              <span class="sp12b"> DNI registrado </span>
                         </div>
                         <table>
                            <tr> 
                                <td width="50"> <span class="sp12b"> DNI</span></td>
                                <td width="10"> <span class="sp12b">: </span></td>
                                <td width="180"><span class="sp12">'.$dni.'</span></td>
                            </tr>
                            <tr>
                                <td> <span class="sp12b">Nombre </span></td>
                                <td> <span class="sp12b">: </span></td>
                                <td><span class="sp12">'.$nombre.'</span></td>
                            </tr>
                            <tr>
                                <td> <span class="sp12b">Tipo </span></td>
                                <td> <span class="sp12b">: </span> </td>
                                <td><span class="sp12">'.$situ_actual.'</span></td>
                            </tr>
                         </table>
                         
                         <div align="center">    
                      ';
                  
                         

                        $html.='              
                                       <input type="hidden" class="hddata_container" value="'.trim($info['indiv_key']).'" />
                                        <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                    
                                                    '.$this->resources->getImage('page_edit.png',array('width' => '14', 'height' => '14'),false).'
                                                 <label class="lbl12">Visualizar y Editar</label>
                                                 <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_showinffullper_click(this,evt);     
                                                 </script>
                                         </button> 

                               ';
            

                  $html.='  </div>  ';



        }
        else
        {
        
            $result = 0;
            $mensaje = 'DNI no registrado';
        
            $html = '   
                         <div align="center" class="dvpadding3">   
                              <span class="sp12b"> DNI NO registrado</span>
                         </div>
                         
                         <table>
                            <tr> 
                                <td width="50"> <span class="sp12b"> DNI</span></td>
                                <td width="10"> <span class="sp12b">: </span></td>
                                <td width="180"><span class="sp12">'.$dni.'</span></td>
                            </tr>
                         </table>
                         <br/>
                         <div align="center">    
                            <input type="hidden" class="hddata_container" value="'.trim($dni).'" />
                        ';


            if( $this->user->has_key('TRABAJADOR_REGISTRAR_NUEVO') )
            {             

               $html.='      
                              <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          
                                          '.$this->resources->getImage('page_edit.png',array('width' => '14', 'height' => '14'),false).'
                                       <label class="lbl12">Registrar nuevo</label>
                                       <script type="dojo/method" event="onClick" args="evt">
                                             Persona.Ui.btn_showregister_click(this,evt);
                                       </script>
                               </button>

                      ';

            }


            $html.=' 
                         </div>
                          <br/>
                    ';
        }
        
      
      
        $response =  array(
            
             'result'  =>  $result,
             'mensaje' =>  $mensaje,
             'data'    =>  array('html' => $html)
        );
        
        echo json_encode($response); 
        
    }
      
    public function get()
    {
       header("Content-Type: application/json");
              
       $data = $this->input->get();

       $tipo = $data['tipoview'];
       $values = array();

       $params = array();

       $meses = array( '1' =>  'ENERO',
                       '2' => 'FEBRERO',
                       '3' => 'MARZO',
                       '4' => 'ABRIL',
                       '5' => 'MAYO',
                       '6' => 'JUNIO',
                       '7' => 'JULIO',
                       '8' => 'AGOSTO',
                       '9' => 'SEPTIEMBRE',
                       '10' => 'OCTUBRE',
                       '11' => 'NOVIEMBRE',
                       '12' => 'DICIEMBRE'
                     );

     
       $params['busquedaporfecha'] = $data['busquedaporfecha'];
   
       if( trim($data['desde']) != '' )
       {
           $params['desde'] =  trim($data['desde']); 
       }
       else
       {
          $params['desde'] =  '';
       }

       if( trim($data['hasta']) != '' )
       {
           $params['hasta'] =  trim($data['hasta']); 
       }
       else
       {
          $params['hasta'] =  '';
       }

       if( trim($data['agruparpor']) != '0'  )
       {
          $params['agrupar'] =  trim($data['agruparpor']);

          $params['valoracumulado'] =  (is_numeric($data['valoracumulado']) ? $data['valoracumulado'] : 0 );
       }  
       else
       {
           $params['agrupar'] = '';
           $params['valoracumulado'] = '';
       }

       if( trim($data['dni']) != ''  )
       {
           $pers_info = $this->persona->get_by_dni(trim($data['dni']) );
           $params['indiv_id'] = $pers_info['indiv_id'];
          
           if($pers_info['indiv_id'] == '')
           {
             echo json_encode($response);
             die();
           }
       }  


       if($tipo == 5) // descanso medico
       {

           if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_VER') )
           { 
                 
                header("Content-Type: application/json");
                
                
                $this->load->library('App/descansomedico'); 
                
                $rs =  $this->descansomedico->get_by_filtro($params);
                
                $total = sizeof($rs);
                $start = 0;
                $end =  $total;
                
                header("Content-Range: " . "items ".$start."-".$end."/".$total);     
                $data = array();
                $c = 1;
 
                if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
                {

                    foreach($rs as $registro)
                    {
                          $data['id'] =   trim($registro['indice']);
                          $data['numeral'] = $c;
                          $data['trabajador_nombre'] =  $registro['trabajador'];
                          $data['trabajador_dni'] =  $registro['trabajador_dni'];
                          $data['trabajador_regimen'] =   $registro['regimen'];
                          $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                          $data['total'] = $registro['total'];
                          $response[] = $data;
                          $c++;
                    }
   
                }
                else
                {
                 
                    foreach($rs as $registro)
                    {
                 /*        
                        $data['id'] =   trim($registro['perdm_key']);
                        $data['numeral'] = $c;
                        $data['col2'] = (trim($registro['perdm_documento']) != '') ?  trim($registro['perdm_documento']) : '-------';
                        $data['col2'] = (trim($registro['perdm_documento']) != '') ?  trim($registro['perdm_documento']) : '-------';
                        $data['col3'] = _get_date_pg($registro['perdm_fechaini']);
                        $data['col4'] = _get_date_pg($registro['perdm_fechafin']);
                        $data['col5'] = $registro['tipo'];
                        $data['col6'] = $registro['dias'];
                        $data['col7'] = (trim($registro['perdm_obs']) == '') ? '------- ' : trim($registro['perdm_obs']);
                        $response[] = $data;
                        $c++;*/

                        $data['id'] =   trim($registro['perdm_key']);
                        $data['numeral'] = $c;
                        $data['trabajador_nombre'] =  $registro['trabajador'];
                        $data['trabajador_dni'] =  $registro['trabajador_dni'];
                        $data['trabajador_regimen'] =   $registro['regimen'];
                        $data['documento'] =  trim($registro['perdm_documento']) == '' ? '-----' : $registro['perdm_documento']; 
                        $data['desde'] = _get_date_pg($registro['perdm_fechaini']);
                        $data['hasta'] = _get_date_pg($registro['perdm_fechafin']);
                        $data['dias'] = $registro['dias'];
                        $data['descripcion'] =   (trim($registro['perdm_obs']) == '') ? '-----' : trim($registro['perdm_obs']);
                        $response[] = $data;
                        $c++;
                    }


                }


            }
             
  
       }
       else if($tipo == 2) // Licencias 
       {

           if($data['tipolic'] != '0' && $data['tipolic'] != '' )
           {
               $params['tipo'] = $data['tipolic'];
           }
           else
           {
              $params['tipo'] = '';
           }

          if( $this->user->has_key('TRABAJADOR_LICENCIAS_VER') )
          { 
                
               header("Content-Type: application/json");
                
               $this->load->library('App/licencia'); 
               
               $rs =  $this->licencia->get_by_filtro($params);
               
               $total = sizeof($rs);
               $start = 0;
               $end =  $total;
               
               header("Content-Range: " . "items ".$start."-".$end."/".$total);     
               $data = array();
               $c = 1;
          
               if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
               {

                   foreach($rs as $registro)
                   {
                         $data['id'] =   trim($registro['indice']);
                         $data['numeral'] = $c;
                         $data['trabajador_nombre'] =  $registro['trabajador'];
                         $data['trabajador_dni'] =  $registro['trabajador_dni'];
                         $data['trabajador_regimen'] =   $registro['regimen'];
                         $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                         $data['total'] = $registro['total'];
                         $response[] = $data;
                         $c++;
                   }
          
               }
               else
               {
                
                   foreach($rs as $registro)
                   {
           
                       $data['id'] =   trim($registro['peli_key']);
                       $data['numeral'] = $c;
                       $data['trabajador_nombre'] =  $registro['trabajador'];
                       $data['trabajador_dni'] =  $registro['trabajador_dni'];
                       $data['trabajador_regimen'] =   $registro['regimen'];
                       $data['documento'] = trim($registro['peli_documento']) == '' ? '-----' : $registro['peli_documento']; 
                       $data['desde'] = _get_date_pg($registro['peli_fechavigencia']);
                       $data['hasta'] = _get_date_pg($registro['peli_fechacaducidad']);
                       $data['dias'] = $registro['dias'];
                       $data['tipo'] = (trim($registro['tipo']) == '') ? '-----' : trim($registro['tipo']); 
                       $data['observacion'] = (trim($registro['peli_observacion']) == '') ? '-----' : trim($registro['peli_observacion']); 
                       $response[] = $data;
                       $c++;
                   }


               }


           }
       }
       else if($tipo == 3) // Permisos
       {

           if($data['tipolic'] != '0' && $data['tipolic'] != '' )
           {
               $params['tipo'] = $data['tipolic'];
           }
           else
           {
              $params['tipo'] = '';
           }

          if( $this->user->has_key('TRABAJADOR_PERMISOS_VER') )
          { 
                
               header("Content-Type: application/json");
                
               $this->load->library('App/permiso'); 
               
               $rs =  $this->permiso->get_by_filtro($params);
               
               $total = sizeof($rs);
               $start = 0;
               $end =  $total;
               
               header("Content-Range: " . "items ".$start."-".$end."/".$total);     
               $data = array();
               $c = 1;
          
               if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
               {

                   foreach($rs as $registro)
                   {
                         $data['id'] =   trim($registro['indice']);
                         $data['numeral'] = $c;
                         $data['trabajador_nombre'] =  $registro['trabajador'];
                         $data['trabajador_dni'] =  $registro['trabajador_dni'];
                         $data['trabajador_regimen'] =   $registro['regimen'];
                         $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                         $data['total'] = $registro['total'];
                         $response[] = $data;
                         $c++;
                   }
          
               }
               else
               {
                
                   foreach($rs as $registro)
                   {
           
                       $data['id'] =   trim($registro['pepe_key']);
                       $data['numeral'] = $c;
                       $data['trabajador_nombre'] =  $registro['trabajador'];
                       $data['trabajador_dni'] =  $registro['trabajador_dni'];
                       $data['trabajador_regimen'] =   $registro['regimen'];
                       $data['documento'] = trim($registro['pepe_documento']) == '' ? '-----' : $registro['pepe_documento'];
                       $data['desde']     = _get_date_pg($registro['pepe_fechadesde']);
                       $data['hsalida']   =  ($registro['pepe_horaini']);
                       $data['hingreso']  = ( trim($registro['pepe_horafin']) == '' ? '-----' : $registro['pepe_horafin'] );
                       $response[] = $data;
                       $c++;
                   }


               }


           }
       }
       else if($tipo == 1) // Comision de servicio 
       {

          if($data['ciudad'] != '0'){
              
              list($params['distrito'], $params['provincia'], $params['departamento'] ) = explode('-', $data['ciudad']);
          }

          if( $this->user->has_key('TRABAJADOR_COMISIONSERVICIO_VER') )
          { 
                
               header("Content-Type: application/json");
                
               $this->load->library('App/comision'); 
               
               $rs =  $this->comision->get_by_filtro($params);
               
               $total = sizeof($rs);
               $start = 0;
               $end =  $total;
               
               header("Content-Range: " . "items ".$start."-".$end."/".$total);     
               $data = array();
               $c = 1;
          
               if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
               {

                   foreach($rs as $registro)
                   {
                         $data['id'] =   trim($registro['indice']);
                         $data['numeral'] = $c;
                         $data['trabajador_nombre'] =  $registro['trabajador'];
                         $data['trabajador_dni'] =  $registro['trabajador_dni'];
                         $data['trabajador_regimen'] =   $registro['regimen'];
                         $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                         $data['total'] = $registro['total'];
                         $response[] = $data;
                         $c++;
                   }
          
               }
               else
               {
                
                   foreach($rs as $registro)
                   {
           
                       $data['id'] =   trim($registro['peco_key']);
                       $data['numeral'] = $c;
                       $data['trabajador_nombre'] =  $registro['trabajador'];
                       $data['trabajador_dni'] =  $registro['trabajador_dni'];
                       $data['trabajador_regimen'] =   $registro['regimen'];
                       $data['documento'] = trim($registro['peco_documento']) == '' ? '-----' : $registro['peco_documento'];
                       $data['desde'] = _get_date_pg($registro['peco_fechadesde']);
                       $data['hasta'] = _get_date_pg($registro['peco_fechahasta']); 
                       $data['col6'] = (trim($registro['peco_motivo']) != '' ? trim($registro['peco_motivo']) : '------'); 
                       $data['col3'] = (trim($registro['destino']) != '' ? trim($registro['destino']) : '------'); 
                        
                       $response[] = $data;
                       $c++;
                   }


               }


           }
       }
       else if($tipo == 6) //  Faltas
       {
 
          if( $this->user->has_key('TRABAJADOR_FALTASTAR_VER') )
          { 
                
               header("Content-Type: application/json");
                
               $this->load->library('App/falta'); 
               
               $rs =  $this->falta->get_by_filtro($params);
               
               $total = sizeof($rs);
               $start = 0;
               $end =  $total;
               
               header("Content-Range: " . "items ".$start."-".$end."/".$total);     
               $data = array();
               $c = 1;
          
               if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
               {

                   foreach($rs as $registro)
                   {
                         $data['id'] =   trim($registro['indice']);
                         $data['numeral'] = $c;
                         $data['trabajador_nombre'] =  $registro['trabajador'];
                         $data['trabajador_dni'] =  $registro['trabajador_dni'];
                         $data['trabajador_regimen'] =   $registro['regimen'];
                         $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                         $data['total'] = $registro['total'];
                         $response[] = $data;
                         $c++;
                   }
          
               }
               else
               {
                
                   foreach($rs as $registro)
                   {
           
                       $data['id'] =   trim($registro['pefal_key']);
                       $data['numeral'] = $c;
                       $data['trabajador_nombre'] =  $registro['trabajador'];
                       $data['trabajador_dni'] =  $registro['trabajador_dni'];
                       $data['trabajador_regimen'] =   $registro['regimen'];
                       $data['desde'] = _get_date_pg($registro['pefal_desde']);
                       $data['hasta'] = _get_date_pg($registro['pefal_hasta']); 
                     
                       $data['justificada'] = (trim($registro['pefal_justificada']) == '1') ? 'Si' : 'No';
                       $data['justificacion'] = (trim($registro['pefal_justificacion']) != '') ? trim($registro['pefal_justificacion']) : '-------'; 
                         
                       $response[] = $data;
                       $c++;
                   }


               }


           }
       }
       else if($tipo == 7) //  Tard
       {
       
          if( $this->user->has_key('TRABAJADOR_FALTASTAR_VER') )
          { 
                
               header("Content-Type: application/json");
                
               $this->load->library('App/tardanza'); 
               
               $rs =  $this->tardanza->get_by_filtro($params);
               
               $total = sizeof($rs);
               $start = 0;
               $end =  $total;
               
               header("Content-Range: " . "items ".$start."-".$end."/".$total);     
               $data = array();
               $c = 1;
          
               if(  $params['agrupar']  != '0' &&  $params['agrupar']  != ''  )
               {

                   foreach($rs as $registro)
                   {
                         $data['id'] =   trim($registro['indice']);
                         $data['numeral'] = $c;
                         $data['trabajador_nombre'] =  $registro['trabajador'];
                         $data['trabajador_dni'] =  $registro['trabajador_dni'];
                         $data['trabajador_regimen'] =   $registro['regimen'];
                         $data['periodo'] = ($params['agrupar'] == '2') ? $meses[$registro['periodo']] : $registro['periodo'];
                         $data['total'] = $registro['total'];
                         $response[] = $data;
                         $c++;
                   }
          
               }
               else
               {
                
                   foreach($rs as $registro)
                   {
           
                       $data['id'] =   trim($registro['peft_key']);
                       $data['numeral'] = $c;
                       $data['trabajador_nombre'] =  $registro['trabajador'];
                       $data['trabajador_dni'] =  $registro['trabajador_dni'];
                       $data['trabajador_regimen'] =   $registro['regimen'];
                       $data['desde'] = _get_date_pg($registro['peft_fecha']);
                       $data['minutos'] =   $registro['minutos'];
                       $data['justificacion'] = (trim($registro['peft_justificacion']) != '') ? trim($registro['peft_justificacion']) : '-------'; 
                         
                       $response[] = $data;
                       $c++;
                   }


               }


           }
       }

       echo json_encode($response);

    } 
     
    
    public function get_comisiones(){



       header("Content-Type: application/json");
               

      if( $this->user->has_key('TRABAJADOR_COMISIONSERVICIO_VER') )
      {  
         
        	  $start = 1;
        	  $end = 10;
      
            $this->load->library('App/comision'); 
              
            $datos = $this->input->get();

            $pers_key = $this->input->get('empleado');
            $pers_id = $this->persona->get_id($pers_key);
             

            $query = array();

            if($datos['explorador'] == '1'){

                if($datos['desde'] != '') $query['fechaini'] = $datos['desde'];
                if($datos['hasta'] != '' && $datos['conrango'] != '') $query['fechafin'] = $datos['hasta'];

                if($datos['ciudad'] != '0'){
                    
                    list($query['distrito'], $query['provincia'], $query['departamento'] ) = explode('-', $q['ciudad']);
                }
            } 

            if($datos['anio'] != '' && $datos['anio'] != '0'){
               $query['anio'] = $datos['anio'];
            }

            $comisiones =  $this->comision->get_comisiones($pers_id, $query);
            $c = 1;
            
            $total = sizeof($comisiones);
            
            header("Content-Range: " . "items ".$start."-".$total."/".$total);     
            $data = array();
            $response = array();
       
            foreach($comisiones as $comision){
                
                $data['col1'] = $c;
                $data['id'] =   trim($comision['peco_key']);
                $data['trabajador'] = (trim($comision['trabajador']) != '') ? trim($comision['trabajador']) : '------';
                $data['col2'] = (trim($comision['documento']) != '') ? trim($comision['documento']) : '------';
                $data['col3'] = trim($comision['destino']);
                $data['col4'] = _get_date_pg($comision['peco_fechadesde']);
                $data['col5'] = _get_date_pg($comision['peco_fechahasta']);
                $data['col6'] = (trim($comision['peco_motivo']) != '' ) ? trim($comision['peco_motivo']) : '------';
                $response[] = $data;
                $c++;
            }
            
            echo json_encode($response) ;

       }
       else
       {

           echo json_encode(array());
       }
        
    }
    
    
        
    public function get_licencias()
    {


       if( $this->user->has_key('TRABAJADOR_LICENCIAS_VER') )
       { 
             
            header("Content-Type: application/json");
            
            $start = 0;
            
            $this->load->library('App/licencia'); 
            
             $pers_key = $this->input->get('empleado');
             $pers_id = $this->persona->get_id(trim($pers_key) );
            

            $datos= $this->input->get(); 
            $query = array();

            if($datos['explorador'] == '1')
            {

                if($datos['desde'] != '') $query['fechaini'] = $datos['desde'];
                if($datos['hasta'] != '' && $datos['conrango'] != '') $query['fechafin'] = $datos['hasta'];
                if($datos['tipolic'] != '0') $query['tipo'] = $datos['tipolic'];
              //     if($q['salud'] != '-1') $query['salud'] = $q['salud'];
            }

            if($datos['anio'] != '' && $datos['anio'] != '0'){
               $query['anio'] = $datos['anio'];
            }


            $rs =  $this->licencia->get_licencias($pers_id, $query);
            $c = 1;
             
            $total = sizeof($rs);
             
            $end =  $total;
            
            header("Content-Range: " . "items ".$start."-".$end."/".$total);     
            $data = array();
            $response = array();    
            
            
            
            foreach($rs as $registro){
                
                $salud = ($registro['peli_essalud']=='1') ? 'Si' : 'No';
                
                $data['col1'] = $c;
                $data['id'] =   trim($registro['peli_key']);
                $data['trabajador'] = (trim($registro['trabajador']) != '') ? trim($registro['trabajador']) : '------';
                $data['col2'] = (trim($registro['documento']) != '') ?  trim($registro['documento']) : '-------';
                $data['col3'] = _get_date_pg($registro['peli_fechavigencia']);
                $data['col4'] = _get_date_pg($registro['peli_fechacaducidad']);
                $data['col5'] = $registro['tipo_licencia'];
                $data['col6'] = (trim($registro['peli_descripcion']) == '') ? '------- ' : trim($registro['peli_descripcion']);
                
                $response[] = $data;
                $c++;
            }
        }
        else
        {
           $response = array();
        }

        echo json_encode($response) ;
        
    }


    public function get_vacaciones()
    {


       if( $this->user->has_key('TRABAJADOR_VACACIONES_VER') )
          { 
                
               header("Content-Type: application/json");
               
               $start = 0;
               
               $this->load->library('App/vacacionestrabajador'); 
               
                $pers_key = $this->input->get('empleado');
                $pers_id = $this->persona->get_id(trim($pers_key) );
               

               $datos= $this->input->get(); 
               $query = array();

               if($datos['explorador'] == '1')
               {

                   if($datos['desde'] != '') $query['fechaini'] = $datos['desde'];
                   if($datos['hasta'] != '' && $datos['conrango'] != '') $query['fechafin'] = $datos['hasta'];
                
               }

               if($datos['anio'] != '' && $datos['anio'] != '0'){
                  $query['anio'] = $datos['anio'];
               }


               $rs =  $this->vacacionestrabajador->get_vacaciones($pers_id, $query);
               $c = 1;
                
               $total = sizeof($rs);
                
               $end =  $total;
               
               header("Content-Range: " . "items ".$start."-".$end."/".$total);     
               $data = array();
               $response = array();    
               
               
               
               foreach($rs as $registro){
                   
                   $salud = ($registro['peli_essalud']=='1') ? 'Si' : 'No';
                   
                   $data['col1'] = $c;
                   $data['id'] =   trim($registro['perva_key']);
                   $data['trabajador'] = (trim($registro['trabajador']) != '') ? trim($registro['trabajador']) : '------';
                   $data['col2'] = (trim($registro['perva_documento']) != '') ?  trim($registro['perva_documento']) : '-------';
                   $data['col3'] = _get_date_pg($registro['perva_fechaini']);
                   $data['col4'] = _get_date_pg($registro['perva_fechafin']); 
                   $data['col5'] = (trim($registro['perva_obs']) == '') ? '------- ' : trim($registro['perva_obs']);
                   
                   $response[] = $data;
                   $c++;
               }
           }
           else
           {
              $response = array();
           }

           echo json_encode($response) ;

    }
    


    public function get_descansosmedicos(){


       if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_VER') )
       { 
             
            header("Content-Type: application/json");
            
            $start = 0;
            
            $this->load->library('App/descansomedico'); 
            
            $pers_key = $this->input->get('empleado');
            $pers_id = $this->persona->get_id(trim($pers_key) );
            

            $datos= $this->input->get(); 
            $query = array();

            if($datos['explorador'] == '1')
            {

                if($datos['desde'] != '') $query['fechaini'] = $datos['desde'];
                if($datos['hasta'] != '' && $datos['conrango'] != '') $query['fechafin'] = $datos['hasta'];

                if($datos['tipolic'] != '0') $query['tipo'] = $datos['tipolic'];

                if($datos['salud'] != '-1') $query['salud'] = $datos['salud'];
            }
  
            if($datos['anio'] != '' && $datos['anio'] != '0'){
               $query['anio'] = $datos['anio'];
            }

            $rs =  $this->descansomedico->get_descansos($pers_id, $query);
            $c = 1;
             
            $total = sizeof($rs);
             
            $end =  $total;
            
            header("Content-Range: " . "items ".$start."-".$end."/".$total);     
            $data = array();
            $response = array();     
            
            foreach($rs as $registro)
            {
                 
                $data['id'] =   trim($registro['perdm_key']);
                $data['col1'] = $c;
                $data['col2'] = (trim($registro['perdm_documento']) != '') ?  trim($registro['perdm_documento']) : '-------';
                $data['col3'] = _get_date_pg($registro['perdm_fechaini']);
                $data['col4'] = _get_date_pg($registro['perdm_fechafin']);
                $data['col5'] = $registro['tipo'];
                $data['col6'] = $registro['dias'];

                $data['num'] = $c;
                $data['documento'] = (trim($registro['perdm_documento']) != '') ?  trim($registro['perdm_documento']) : '-------';
                $data['desde'] = _get_date_pg($registro['perdm_fechaini']);
                $data['hasta'] = _get_date_pg($registro['perdm_fechafin']); 
                $data['dias'] = $registro['dias']; 
                $response[] = $data;
                $c++;
            }


        }
        else
        {
           $response = array();
        }

        echo json_encode($response) ;
        
    }
    
    
        
    public function get_permisos(){

             
            header("Content-Type: application/json");

        if( $this->user->has_key('TRABAJADOR_PERMISOS_VER') )
        {   
             
            $start = 0;
            
            $this->load->library('App/permiso'); 
            $pers_key = $this->input->get('empleado');
            $pers_id = $this->persona->get_id(trim($pers_key) );
             
            $q= $this->input->get(); 
            $params = array();

            if($q['explorador'] == '1'){

                if($q['desde'] != '') $params['fechaini'] = $q['desde'];
                if($q['hasta'] != '' && $q['conrango'] != '') $params['fechafin'] = $q['hasta'];

            } 

            $rs =  $this->permiso->get_permisos($pers_id, $params);
            $c = 1;
             
            $total = sizeof($rs);
             
            $end =  $total;
            
            header("Content-Range: " . "items ".$start."-".$end."/".$total);     
            $data     = array();
            $response = array();    
            
            $salud    = ($registro['peli_essalud']=='1') ? 'Si' : 'No';
            
            foreach($rs as $registro){
                
                $data['col1']     =  $c;
                $data['id']       =  trim($registro['pepe_key']);
                $data['trabajador']     =  (trim($registro['trabajador']) != '') ? trim($registro['trabajador']) : '-------';
                $data['col2']     =  (trim($registro['documento']) != '') ? trim($registro['documento']) : '-------';
                $data['col3']     =  _get_date_pg($registro['pepe_fechadesde']);
                $data['hsalida']  =  $registro['pepe_horaini'];
                $data['col4']     =  _get_date_pg($registro['pepe_fechahasta']);
                $data['hingreso'] =  $registro['pepe_horafin'];
                $data['col5']     =  (trim($registro['pepe_motivo']) != '') ? trim($registro['pepe_motivo']) : '-------';
         
                $response[] = $data;
                $c++;
            }

        }
        else
        {

          $response = array();

        }
        
        echo json_encode($response) ;
        
    }
    
    
    
    
    public function get_faltas()
    {
         
        header("Content-Type: application/json");
    

        if( $this->user->has_key('TRABAJADOR_FALTASTAR_VER') )
        {  


            $start = 0;
            
            $this->load->library('App/falta'); 
            
             $pers_key = $this->input->get('empleado');
      //       var_dump($pers_key);
             $pers_id = $this->persona->get_id(trim($pers_key) );
             
            $q= $this->input->get(); 
            $params = array();

            if($q['explorador'] == '1'){

                if($q['desde'] != '') $params['fechaini'] = $q['desde'];
                if($q['hasta'] != '' && $q['conrango'] != '') $params['fechafin'] = $q['hasta'];
                if($q['tipoft'] != '') $params['tipo'] = $q['tipoft'];

            } 

            $rs =  $this->falta->get_faltas($pers_id, $params);
            $c = 1;
         //   var_dump($rs);
            
            $total = sizeof($rs);
             
            $end =  $total;
            
            header("Content-Range: " . "items ".$start."-".$end."/".$total);     
            $data = array();
            $response = array();    
            
        
            
            foreach($rs as $registro){
                
                  
                $data['id'] =   trim($registro['pefal_key']);
                $data['trabajador']     =  (trim($registro['trabajador']) != '') ? trim($registro['trabajador']) : '-------';
                $data['col1'] = $c;
                $data['col2'] = _get_date_pg($registro['pefal_desde']);
                $data['col3'] = _get_date_pg($registro['pefal_hasta']);
                $data['col4'] = (trim($registro['pefal_justificada']) == '1') ? 'Si' : 'No';
                $data['col5'] = (trim($registro['pefal_justificacion']) != '') ? trim($registro['pefal_justificacion']) : '-------'; 
                $data['col6'] = (trim($registro['pefal_observacion']) != '') ? trim($registro['pefal_observacion']) : '-------'; 
         
                $response[] = $data;
                $c++;
            }
        }
        else
        {

           $response = array();
        }
        echo json_encode($response) ;
        
    }
    


    public function get_tardanzas()
    {
         
        header("Content-Type: application/json");
    

        if( $this->user->has_key('TRABAJADOR_FALTASTAR_VER') )
        {  


            $start = 0;
            
            $this->load->library('App/tardanza'); 
            
            $pers_key = $this->input->get('empleado');
    
            $pers_id = $this->persona->get_id(trim($pers_key) );
             
            $q= $this->input->get(); 
            $params = array();

            if($q['explorador'] == '1'){

                if($q['desde'] != '') $params['fechaini'] = $q['desde'];
                if($q['hasta'] != '' && $q['conrango'] != '') $params['fechafin'] = $q['hasta'];
                if($q['tipoft'] != '') $params['tipo'] = $q['tipoft'];

            } 

            $rs =  $this->tardanza->get_tardanzas($pers_id, $params);
            $c = 1;
         //   var_dump($rs);
            
            $total = sizeof($rs);
             
            $end =  $total;
            
            header("Content-Range: " . "items ".$start."-".$end."/".$total);     
            $data = array();
            $response = array();    
             

            foreach($rs as $registro){
                
                $tiempo = $registro['peft_horas'].':'.$registro['peft_minutos'].':'.$registro['peft_segundos'];
                
                $data['id'] =   trim($registro['peft_key']);
                $data['trabajador']     =  (trim($registro['trabajador']) != '') ? trim($registro['trabajador']) : '-------';
                $data['col1'] = $c;
                $data['col2'] = _get_date_pg($registro['peft_fecha']);
                $data['col3'] = (trim($registro['peft_minutos']) != '') ? trim($registro['peft_minutos']) : '-------';  
                $data['col4'] = (trim($registro['peft_justificacion']) != '') ? trim($registro['peft_justificacion']) : '-------'; 
             
                $response[] = $data;
                $c++;
            }
        }
        else
        {

           $response = array();
        }
        echo json_encode($response) ;
        
    }
    
    
    
    public function get_historial(){
         
        header("Content-Type: application/json");
        
        $start = 0;
        
        $this->load->library('App/situacionlaboral'); 
        
        $pers_key = $this->input->get('empleado');
 
        $pers_id = $this->persona->get_id(trim($pers_key) );
 
        $rs =  $this->situacionlaboral->get_historial($pers_id);
        $c = 1;
    
        $total = sizeof($rs);
         
        $end =  $total;
        
        header("Content-Range: " . "items ".$start."-".$end."/".$total);     
        $data = array();
        $response = array();    
         
        foreach($rs as $registro){
            
            $vigente =  ($registro['persla_vigente']=='1') ? 'Si' :  (  ($registro['persla_vigente']=='0')  ? 'No' : 'N.E' );
            
            $data['id'] =   trim($registro['persla_key']);
            $data['col1'] = $c;
            $data['col2'] = trim($registro['situ_nombre']);
            $data['area'] = (trim($registro['depe_abre']) != '') ? trim($registro['depe_abre']) : '--------';
            $data['cargo'] = (trim($registro['cargo_nombre']) != '') ? trim($registro['cargo_nombre']) : '--------';
            $data['col4'] =  (trim($registro['persla_fechaini']) != '') ? trim(_get_date_pg($registro['persla_fechaini'])) : '--------';  
            $data['col5'] =   (trim($registro['persla_fechafin']) != '') ? trim(_get_date_pg($registro['persla_fechafin'])) : '--------'; 
            $data['col6'] =  (trim($registro['persla_fechacese']) != '') ? trim(_get_date_pg($registro['persla_fechacese'])) : '--------';  
            $data['col7'] = $vigente;
            $data['vigente'] = trim($registro['persla_vigente']);
            $data['ultimo'] = trim($registro['persla_ultimo']);
          
            $response[] = $data;
            $c++;
        }
        
        echo json_encode($response) ;
        
    }
    
    
    
    
    public function get_academico(){
         

        if( $this->user->has_key('TRABAJADOR_ACADEMICO_VER') )
        {  

            header("Content-Type: application/json");
            
            $start = 0;
            
            $this->load->library('App/academico'); 
            
             $pers_key = $this->input->get('empleado');
      //       var_dump($pers_key);
             $pers_id = $this->persona->get_id(trim($pers_key) );
             
        //   var_dump($pers_id);
            
            $rs =  $this->academico->get_academico($pers_id);
            $c = 1;
         //   var_dump($rs);
            
            $total = sizeof($rs);
             
            $end =  $total;
            
            header("Content-Range: " . "items ".$start."-".$end."/".$total);     
            $data = array();
            $response = array();    
            
       
            
            foreach($rs as $registro){
                
                $nombres = trim($registro['pefa_apellpaterno']).' '.trim($registro['pefa_apellmaterno']).' '.trim($registro['pefa_nombres']);
                $estado  = $registro['situacion'];
                $periodo = _get_date_pg(trim($registro['perac_fecini'])).' - '._get_date_pg(trim($registro['perac_fecfin']));  // trim($registro['perac_fecini']);


                if( in_array( $registro['tiest_id'], array('3','4','5') )  ){
                     $estado =  (trim($registro['estado_titulo']) != '')  ? trim($registro['estado_titulo']) : '-------';
                }

                $nombre_estudio = '';

                if( in_array( $registro['tiest_id'], array('1','2') )  ){
                     $nombre_estudio =  (trim($registro['tiest_nombre']) != '')  ? trim($registro['carpro_nombre']) : '-------';
                }

                if( in_array( $registro['tiest_id'], array('3','4','5') )  ){
                     $nombre_estudio =  (trim($registro['carpro_nombre']) != '')  ? trim($registro['carpro_nombre']) : '-------';
                }
     
                if( in_array( $registro['tiest_id'], array('6','7','8','9','10','11','12','13','14') )  ){
                     $nombre_estudio =  (trim($registro['perac_nombre']) != '')  ? trim($registro['perac_nombre']).' ('.trim($registro['especi_nombre']).')'  : '-------';
                }

                $data['id'] =   trim($registro['perac_key']);
                $data['col1'] = $c;
                $data['col2'] =  trim($registro['tiest_nombre']);  //trim($registro['tiest_nombre']);
                $data['col3'] =  $nombre_estudio;
                $data['col4'] =  trim($registro['cees_nombre']);
                $data['col5'] =  $estado;
                $data['col6'] =  $periodo;
              
                $response[] = $data;
                $c++;
            }
        

        }
        else
        {

           $response = array();
        }

        echo json_encode($response) ;
        
    }
    
    
    
    
    public function get_familiares(){
         
       header("Content-Type: application/json");

       if( $this->user->has_key('TRABAJADOR_FAMILIAR_VER') )
       { 
           
          $start = 0;
          
          $this->load->library('App/familiar'); 
          
          $pers_key = $this->input->get('empleado');
            
          $pers_id  = $this->persona->get_id(trim($pers_key) );
            
          $rs       =  $this->familiar->get_familia($pers_id);
          $c        = 1;
            
          $total    = sizeof($rs);
          $end      =  $total;
            
          header("Content-Range: " . "items ".$start."-".$end."/".$total);     
          $data = array();
          $response = array();    
          
     
          
          foreach($rs as $registro){
              
              $nombres = trim($registro['pefa_apellpaterno']).' '.trim($registro['pefa_apellmaterno']).' '.trim($registro['pefa_nombres']);
              
              // $estado_civil =  ($registro['persla_actual']=='1') ? 'Si' : 'No';
              
              $data['id'] =   trim($registro['pefa_key']);
              $data['col1'] = $c;
              $data['col2'] = trim($registro['paren_nombre']);
              $data['col3'] = $nombres;
              $data['col4'] = trim($registro['pefa_dni']);
              $data['col5'] = trim($registro['estciv_nombre']);
              $data['col6'] = trim($registro['edad']).' años';


              $estudiante = ( ($registro['pefa_estudiante'] == '1' ? 'Si' : 'No'));
              $data['col7'] =  $estudiante;

              $response[] = $data;
              $c++;
           }
        
        }
        else
        {
            $response = array();
        }


        echo json_encode($response) ;
        
    }
    
    
    public function get_trabajadores()
    {
        
        
        header("Content-Type: application/json");
        
        $start = 0;
         
        $map_fields = array(
                            'col2' => 'indiv_appaterno',
                            'col3' => 'indiv_apmaterno',
                            'col4' => 'indiv_nombres',
                            'col5' => 'indiv_dni',
                            'col6' => 'tipo_trabajador',
                            'col7' => 'depe_nombre',
                            'col8' => 'cargo_nombre',
                            'col9' => 'vigente',
                            'col11' => 'ultimo_pago'
                            );
        
        $params =  array();

        $params['anio_eje'] =  $this->usuario['anio_ejecucion'];
        
        if(trim($this->input->get('dependencia')) != '0'){
        
           $params['depe_id'] = trim($this->input->get('dependencia'));
        
        }
        
        if(trim($this->input->get('lugar_de_trabajo')) != '0' && trim($this->input->get('lugar_de_trabajo')) != ''){
        
           $params['lugar_de_trabajo'] = trim($this->input->get('lugar_de_trabajo'));
        
        }

        if(trim($this->input->get('situlaboral')) != '0'){
        
           $params['situ_lab'] = trim($this->input->get('situlaboral'));
        
        }
        
        if(trim($this->input->get('cargo')) != '0'){
        
           $params['cargo'] = trim($this->input->get('cargo'));
        
        }
        
        if(trim($this->input->get('dni')) != ''){
        
           $params['dni'] = trim($this->input->get('dni'));
        
        }
        
        
        if(trim($this->input->get('materno')) != ''){
        
           $params['materno'] = strtoupper(trim($this->input->get('materno')));
        
        }
         if(trim($this->input->get('paterno')) != ''){
        
           $params['paterno'] = strtoupper( trim($this->input->get('paterno')) );
        
        }
        if(trim($this->input->get('nombres')) != ''){
        
           $params['nombres'] = strtoupper( trim($this->input->get('nombres')) );
        
        }

        $params['vigente'] =  $this->input->get('vigente');
        

        $datos = $this->input->get();
        
        $params['tienecuenta'] = (trim($datos['tienecuenta']) != '' ? trim($datos['tienecuenta']) : '0');
        $params['banco']       = (trim($datos['banco']) != '' ? trim($datos['banco']) : '0');
        $params['tipopension'] = (trim($datos['tipopension']) != '' ? trim($datos['tipopension']) : '0');
        $params['afp']         = (trim($datos['afp']) != '' ? trim($datos['afp']) : '0');
        $params['modoafp']     = (trim($datos['modoafp']) != '') ? trim($datos['modoafp']) : '0';
        $params['tarea']       = (trim($datos['tarea'])!= '' ? trim($datos['tarea']) : '0');
        
        if($datos['fuente']!='' && $datos['fuente']!='0')
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
                list($params['fuente'], $params['tiporecurso']) = explode('-', $datos['fuente']);
            }
        }
        else
        {
            $params['fuente'] = '';
            $params['tiporecurso'] = '';
        }


        if($datos['grupo'] != '0' && $datos['grupo'] != '')
        {
           $params['grupo'] = $datos['grupo'];
        }

        if($datos['from'] != '' )
        {
           if($datos['from'] == 'hojaasistencia')
           {
               $params['vigente'] = '1'; 
               $params['vigente_estricto'] = true;
               $params['from'] = 'hojaasistencia';
           }
        }


        $total =  $this->persona->get_count_trabajadores($params);
        
        if(isset($_SERVER["HTTP_RANGE"])){
            preg_match('/(\d+)-(\d+)/',$_SERVER["HTTP_RANGE"], $matches);

            $start = $matches[1];
            $end = $matches[2];
            if($end > $total){
                    $end = $total;
            }
        }
      

       if(trim($datos['fechalimite']) != '' ) $params['fecha_limite'] = $datos['fechalimite'];
  
              
        $params['offset'] = $start; 
        $params['limit'] = ($end-$start+1);
        
        
        $rs =  $this->persona->get_trabajadores($params);
     
        $c = $start +1;
    
  
        header("Content-Range: " . "items ".$start."-".$end."/".$total);
        
        $data = array();
        $response = array();    
     

        foreach($rs as $registro){
            
         //   $nombres = trim($registro['indiv_nombres']).' '.trim($registro['indiv_appaterno']).' '.trim($registro['indiv_apmaterno']);
            $depe_proyect = (trim($registro['depe_nombre']) == '') ?  'No especificado' :  $registro['depe_nombre'];  
            $cargo =   (trim($registro['cargo_nombre']) == '') ?  'No especificado' :  $registro['cargo_nombre'];
            $vigente=  (trim($registro['vigente']) == '2') ? 'N.E'  : ( (trim($registro['vigente']) == '1') ? 'SI' : 'NO');
            
            $data['id'] =   trim($registro['indiv_key']);
            $data['col1'] = $c;
            $data['num'] = $c;
            $data['col2'] = trim($registro['indiv_appaterno']);
            $data['col3'] = trim($registro['indiv_apmaterno']);
            $data['col4'] = trim($registro['indiv_nombres']);
            $data['col5'] = trim($registro['indiv_dni']);
            $data['col6'] = (trim($registro['tipo_trabajador']) != '' ? trim($registro['tipo_trabajador']) : '-------');
            $data['col7'] = $depe_proyect;
            $data['col8'] = $cargo;
            $data['col9'] = $vigente;
            $data['col10'] =  (trim($registro['termino_de_contrato']) != '') ? _get_date_pg($registro['termino_de_contrato']) : '---';
            $data['contrato_vencido'] =  $registro['contrato_vencido'];
            $data['activo'] = $registro['vigente'];
            $data['trabajador'] = trim($registro['indiv_appaterno']).' '.trim($registro['indiv_apmaterno']).' '.trim($registro['indiv_nombres']);
            $data['dni'] = $registro['indiv_dni'];
            $data['tipo_trabajador'] = $registro['tipo_trabajador'];
            $data['lugar_de_trabajo'] = ($registro['indiv_lugar_de_trabajo'] == LUGAR_DE_TRABAJO_PALACIO ? 'Palacion Municipal' : 'Perifericos');
            $data['col11'] = ( $registro['ultimo_pago'] ? _get_date_pg($registro['ultimo_pago']) : '---' );
         
            $response[] = $data;
            $c++;
        }
        
        echo json_encode($response);
        
        
    }
    

    public function provide($tipo, $parametro, $no_especificar){

        header("Content-Type: application/json");
        
        $start = 0;
        
        if($tipo=='individuos')
        { 

              $datos = $this->input->get();

              $nombre = strtoupper(trim($this->input->get('name')));
             

              if($datos['plati_id'] != '0' && strpos($datos['plati_id'], ',') == 0){
                  $plati_id = $this->tipoplanilla->get_id($datos['plati_id']);
              }else{
                  $plati_id = $datos['plati_id'];
              }

              $rs = $this->persona->get_list(array('nombre' => $nombre, 'limit' => 30, 'plati_id' => $plati_id ) ); //, 'limit' => '30'
               
              $response = array();    
               
              if($no_especificar == 'no_especificar')
              {
                  $data = array();
                  $data['value'] = '0';
                  $data['name'] = 'No especificar';
                  $response[] = $data;
              }  

              foreach($rs as $reg){
                
                  $data =  array();

                  $data['value'] =  $reg['indiv_key'];
                  $data['name'] = ($reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$reg['indiv_nombres'].' ('.$reg['indiv_dni'].')');

                    
                  $response[] = $data;
                  $c++;
              }
        
        }
        elseif($tipo=='especialidades'){


            $this->load->library(array('Catalogos/especialidad'));

            $nombre = strtoupper($this->input->get('name'));
            
            $rs= $this->especialidad->get_list(array('nombre' => $nombre));

            $response = array();    
               
            foreach($rs as $reg){
                
                  $data =  array();

                  $data['value'] =  $reg['especi_id'];
                  $data['name'] =   $reg['especi_nombre'];

                    
                  $response[] = $data;
                  $c++;
            }


        }
        elseif($tipo=='carreras'){


            $this->load->library(array('Catalogos/carreraprofesional'));

            $nombre = strtoupper($this->input->get('name'));
            
            $rs= $this->carreraprofesional->get_list(array('nombre' => $nombre));

            $response = array();    
               
            foreach($rs as $reg){
                
                  $data =  array();

                  $data['value'] =  $reg['carpro_id'];
                  $data['name'] =   $reg['carpro_nombre'];

                    
                  $response[] = $data;
                  $c++;
            }

        } 
        elseif($tipo=='centroestudio'){


            $this->load->library(array('Catalogos/centroestudio'));
            $nombre = strtoupper($this->input->get('name'));
            

            $rs= $this->centroestudio->get_list(array('nombre' => $nombre));

            $response = array();    
               
            foreach($rs as $reg){
                
                  $data =  array();

                  $data['value'] =  $reg['cees_id'];
                  $data['name'] =   $reg['cees_nombre'];

                    
                  $response[] = $data;
                  $c++;
            }

        }





        echo json_encode($response) ;

    }


    public function actualizacion_de_datos(){

        $this->load->view('escalafon/p_actualizacion_datos',array());
 
    }
    

    public function get_trabajadores_table(){


        $this->load->library(array('Catalogos/ubicacion'));

         $data = $this->input->post();
         $trabajadores =  $this->persona->get_list();

          // $this->load->view('escalafon/v_trabajadores_completar', array('trabajadores' => $trabajadores, 'ciudades' => $ciudades));
     
    }


    public function explorar_por_fechas($tipo= '')
    {
          
        if($tipo== '')
        {

                $this->load->library(array('Catalogos/ubicacion','App/licencia'));

                $ciudades = $this->ubicacion->get_ciudades();
                
                $tipo_licencias = $this->licencia->get_tipos(array());

                $this->load->view('escalafon/p_explorarporfechas', array( 'ciudades' => $ciudades, 'tipo_licencias' => $tipo_licencias )); 


        }
        else if($tipo=='view')
        {

            $data = $this->input->get();

            $clases_tables = array(

                '1' => 'dvcomision_table',
                '2' => 'dvlicencia_table',
                '3' => 'dvpermiso_table',
                '4' => 'dvfaltardanza_table',
                '5' => 'dvdescanso_table'
            );

            $titulos = array(

                '1' => 'Registro de comisiones de servicio',
                '2' => 'Registro de licencias',
                '3' => 'Registro de permisos',
                '4' => 'Registro de faltas y tardanzas',
                '5' => 'Registro de Descansos Médicos'

            );

            $data = $this->input->post();

            $header = array('titulo' => $titulos[$data['tipoview']] );

            $class_table = $clases_tables[$data['tipoview']];

            $this->load->view('escalafon/v_expxfechas_center', array( 'tipo' => $data['tipoview'], 
                                                                      'class_table' => $class_table, 'header' => $header ));
        } 
    }


    public function gestion_rapida_de_conceptos()
    {

        $this->load->library(array('App/concepto','App/empleadoconcepto'));

        $empkey = trim($this->input->post('empkey'));

        $indiv_id = $this->persona->get_id($empkey);
        $pers_info = $this->persona->get_info($indiv_id);

        $plati_id = $pers_info['tipo_trabajador'];
 
        $conceptos = $this->empleadoconcepto->get_acceso_rapido($indiv_id, $plati_id);
 
        $this->load->view('escalafon/v_ar_gestionar_conceptos', array('conceptos'  => $conceptos, 
                                                                      'persona'    => $pers_info));

    }


    public function ver_actualizar_periodo_trabajo(){


        $this->load->view('v_actualizar_periodo_trabajo' , array() );
    
    }

    public function ver_situlaboral_nuevo(){



        if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
        {   
 
            $emp_key = trim($this->input->post('empkey'));
            $emp_id = $this->persona->get_id($emp_key);
        
            $pers_info = $this->persona->get_info($emp_id);
     
            $dependencias   = $this->dependencia->get_list();
          //  $cargos         = $this->cargo->get_list(); 
            $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
            //$metas          = $this->meta->get_list($this->usuario['anio_ejecucion']); 

            $ocupaciones = $this->ocupacion->get_list();
          
            $this->load->view('escalafon/v_histolaboral_nuevo' ,  array(
                                                                       'pers_info'      => $pers_info,
                                                                       'departamentos'  => $departamentos,  
                                                                       'tipo_empleados' => $tipos_empleado, 
                                                                       'dependencias'   => $dependencias, 
                                                                       'ocupaciones'    => $ocupaciones
                                                                       //'metas'          => $metas,  
                                                                     //  'cargos'         => $cargos
                                                                        ));
        }
        else
        {

            echo PERMISO_RESTRINGICO_MENSAJE;

        }    

    }



    public function ver_situlaboral_editar()
    {
     
        if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
        {  

            $this->load->library('App/situacionlaboral');

            $key_situlaboral = $this->input->post('codigo');
            $id_situlaboral  = $this->situacionlaboral->get_id($key_situlaboral);
            
            $info_registro   = $this->situacionlaboral->view($id_situlaboral);

            if($info_registro['persla_fechacese'] == '')
            {

              $pers_info = $this->persona->get_info($info_registro['pers_id']);
              
              $dependencias   = $this->dependencia->get_list();
              $cargos         = $this->cargo->get_list(); 
              $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
             //// $metas          = $this->meta->get_list(); 

              $ocupaciones = $this->ocupacion->get_list();
              
              $this->load->view('escalafon/v_histolaboral_editar' ,  array(
                                                                           'datos_registro' => $info_registro,   
                                                                           'pers_info'      => $pers_info,
                                                                           'departamentos'  => $departamentos,  
                                                                           'tipo_empleados' => $tipos_empleado, 
                                                                           'dependencias'   => $dependencias, 
                                                                          // 'metas'          => $metas,  
                                                                           'cargos'         => $cargos,
                                                                           'ocupaciones'    => $ocupaciones
                                                                          ));

            }
            else{


               echo ' <b> El registro no se puede editar porque el trabajador ya esta cesado </b> ';

               echo " <input type='hidden' 
                             value='El registro no se puede editar porque el trabajador ya esta cesado' class='on_unload_window' /> ";

            }
            

        }
        else
        {

            echo PERMISO_RESTRINGICO_MENSAJE;

        }    

    }


    public function gestion_historial_laboral()
    {

       $dependencias   = $this->dependencia->get_list();
       $cargos         = $this->cargo->get_list(); 
       $tipos_empleado = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
      // $metas          = $this->meta->get_list(); 
       $categorias     = $this->categorialaboral->get_list();
       
       $this->load->view('planillas/p_gestion_historiallaboral', array(
                                                                  
                                                                  'tipo_empleados' => $tipos_empleado, 
                                                                  'dependencias' => $dependencias, 
                                                                  'metas' => $metas,  
                                                                  'cargos' => $cargos,
                                                                  'categorias' => $categorias 
                                                               
                                                                 ));
 
    }

 

}