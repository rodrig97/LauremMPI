<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class trabajadores extends CI_Controller 
{
    
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
        
        $this->load->library(array( 'App/persona'));
  
    }



    public function gestionar_datos($nivel =  '')
    {
           

          if($nivel == 'trabajador')
          {
 
              $this->load->library(array('App/tarea', 
                                         'App/fuentefinanciamiento',
                                         'App/persona',
                                         'App/empleadoafectacion',
                                         'App/concepto',
                                         'App/empleadoconcepto' 
                                        ));
                  

                $pers_key        = trim($this->input->post('trabajador'));
                $pers_id         = $this->persona->get_id($pers_key);
                $trabajador_info = $this->persona->get_info($pers_id);
                $plati_id        = $trabajador_info['tipo_trabajador'];
          
                $afectacion_info = $this->empleadoafectacion->get($pers_id, $this->usuario['anio_ejecucion'] );  // AFECTACION PRESUPUESAL
 
                $tarea_id        = $afectacion_info['tarea_id'];
                $fuente_id       = $afectacion_info['fuente_id'];
                $recurso_id      = $afectacion_info['tipo_recurso'];
 
                $tareas          = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ) );
 
              $conceptos = array();

              $conceptos['ingresos']     =  $this->empleadoconcepto->get_list($pers_id,TIPOCONCEPTO_INGRESO, $plati_id);  // CONCEPTOS YA PERTENECIENTES AL TRABAJADOR
              $conceptos['descuentos']   =  $this->empleadoconcepto->get_list($pers_id,TIPOCONCEPTO_DESCUENTO, $plati_id);
              $conceptos['aportaciones'] =  $this->empleadoconcepto->get_list($pers_id,TIPOCONCEPTO_APORTACION, $plati_id);

              $variables = $this->empleadoconcepto->get_all_variables($pers_id, $plati_id);
   

              $estado_trabajo = ( $trabajador_info['vigente'] == '1') ? ' VIGENTE ' : ' INACTIVO ';

              $this->load->view('planillas/v_trabajador_gestionardatos', array(
                                                                               'anio_ejecucion'  =>  $this->usuario['anio_ejecucion'],
                                                                               'tareas'          => $tareas,
                                                                               'trabajador_info' => $trabajador_info,
                                                                               'variables'       => $variables,
                                                                               'conceptos'       => $conceptos,
                                                                               'tareas'          => $tareas,
                                                                               'pers_key'        => $pers_key,
                                                                               'afectacion'      => $afectacion_info,
                                                                               'estado_trabajo'  => $estado_trabajo)  );
             

          }
          else if($nivel == 'directo')
          {


                $this->load->library(array('App/persona',  'Catalogos/afp',  'Catalogos/banco', 'App/concepto','App/grupoempleado', 'App/ocupacion','App/tipoplanilla'   ));

                $afps      = $this->afp->load_for_combo(false);
                $bancos    = $this->banco->load_for_combo(false);
                
                $pers_key  = trim($this->input->post('trabajador'));
                $pers_id   = $this->persona->get_id($pers_key); 
                $pers_info = $this->persona->get_info($pers_id);
                
                $plati_id  = $pers_info['tipo_trabajador'];

                $plati_info = $this->tipoplanilla->get($plati_id);

                $conceptos_directo = $this->concepto->get_just_directos(array('indiv_id' => $pers_id, 'plati_id' => $plati_id ));
                
                $grupos = $this->grupoempleado->get_list();

                $ocupaciones = $this->ocupacion->get_list();

                $this->load->view('planillas/v_edicion_rapida', array(  
                                                                   'pers_info' => $pers_info, 
                                                                   'afps'      => $afps, 
                                                                   'bancos'    => $bancos,
                                                                   'conceptos' => $conceptos_directo,
                                                                   'grupos'    => $grupos,
                                                                   'ocupaciones' => $ocupaciones,
                                                                   'plati_info' => $plati_info 

                                                                   ) );
             

          }
          else if($nivel == '' || $nivel=='gestion_rapida' )
          { 


                $edicion_directa = ($nivel=='gestion_rapida') ? true : false;

                $this->load->library(array(
                                         'Catalogos/dependencia',
                                         'Catalogos/cargo',
                                         'App/tipoplanilla',
                                         'Catalogos/meta',
                                         'Catalogos/categorialaboral',
                                         'App/tarea', 'Catalogos/afp', 'App/fuentefinanciamiento',  'Catalogos/banco', 'App/grupoempleado'  ));
               

                $dependencias    = $this->dependencia->get_list();
                $cargos          = $this->cargo->get_list(); 
                $tipos_empleado  = $this->tipoplanilla->load_for_combo(true,'plati_tipoempleado'); 
                $metas           = $this->meta->get_list(); 
                $categorias      = $this->categorialaboral->get_list(); 

                $grupos = $this->grupoempleado->get_list();

                $data = array();           

                $data['afps']         = $this->afp->load_for_combo(true);
                $data['bancos']       = $this->banco->get_list();
                $data['tareas']       = $this->tarea->get_list(array('ano_eje' => $this->usuario['anio_ejecucion'] ) ); 
                $data['fuentes']      = $this->fuentefinanciamiento->get_ff_tr( array('anio_eje' =>  $this->usuario['anio_ejecucion'] ) );

                $this->load->view('planillas/p_trabajadores_gestionardatos', array(  
                                                                                   'edicion_directa' => $edicion_directa,
                                                                                   'tipo_empleados'  => $tipos_empleado, 
                                                                                   'dependencias'    => $dependencias, 
                                                                                   'metas'           => $metas,  
                                                                                   'cargos'          => $cargos,
                                                                                   'categorias'      => $categorias,
                                                                                   'afps'            => $data['afps'],
                                                                                   'bancos'          => $data['bancos'],
                                                                                   'tareas'          => $data['tareas'],
                                                                                   'fuentes'         => $data['fuentes'],
                                                                                   'grupos'          => $grupos

                                                                                    ));

          }   

    }


    public function actualizar_info_ar()
    {
        
        $this->load->library(array('Catalogos/afp','Catalogos/banco', 'App/grupoempleado','App/situacionlaboral'));

        $data = $this->input->post();

        foreach($data as $d => $v){
           $data[$d] = trim($v);
        }

        $indiv_id = $this->persona->get_id(trim($data['view']));

        $info_actual = $this->persona->get_info($indiv_id);

        $persla_id = $info_actual['persla_id'];

        $this->db->trans_begin();
 
        $afp = $data['afp'];  
        $afp = $this->afp->get_id($afp);

         if( ( $info_actual['pentip_id'] != $data['tipopension'] )                      || 
             ( $data['tipopension'] == PENSION_AFP  && $info_actual['afp_id'] !=  $afp )             || 
             ( $data['tipopension'] == PENSION_AFP  && $info_actual['afm_id'] !=  $data['modoafp'])  || 
             ( trim($info_actual['pafp_codigo']) !=  trim($data['codpension']) )   || 
             ( trim($info_actual['peaf_jubilado']) !=  trim($data['estajubilado']) )  || 
             ( trim($info_actual['peaf_invalidez']) !=  trim($data['aplica_invalidez']) ) 
           ){
           
            if($data['tipopension'] == PENSION_AFP )
            {
                           
                    $modo_afp = $data['modoafp'];
                    $aplica_invalidez = $data['aplica_invalidez'];
            }
            else{
                    $afp      = '0';  
                    $modo_afp = '0';
                    $aplica_invalidez ='1';
            }
    
            if( AFP_QUITARINVALIDEZ_AUTOMATICO ) $aplica_invalidez = '1';

           $this->persona->add_pension( $indiv_id, array(
                                                        'afp_id'      => $afp,
                                                        'pentip_id'   => $data['tipopension'],
                                                        'peaf_codigo' => $data['codpension'],
                                                        'peaf_jubilado' => $data['estajubilado'],
                                                        'afm_id'      => $modo_afp,
                                                        'peaf_invalidez' => $aplica_invalidez  

                                                        )  ); 
        }            
 

       // var_dump(trim($info_actual['ebanco_id']),$data['banco'], trim($info_actual['pecd_cuentabancaria']), trim($data['bancocod'])   );
          
        if($data['hascbanco']=='1')
        {
            $banco_id = $this->banco->get_id($data['banco']);

            if( (trim($info_actual['ebanco_id'])!= $banco_id ) || ( trim($info_actual['pecd_cuentabancaria'])!= trim($data['bancocod']) ) )
            {
 
                $this->persona->add_cuentadeposito( $indiv_id, array( 'ebanco_id' => $banco_id,
                                                                'pecd_cuentabancaria' => $data['bancocod'] )); 
            }
        }
        else{
            $this->persona->remove_cuenta($indiv_id);
        }
        

        if( trim($data['quinta_proyectar_gratificacion']) != ''){

            $values_persla = array('persla_quinta_gratificacionproyeccion' => trim($data['quinta_proyectar_gratificacion']) );
            $this->situacionlaboral->actualizar($persla_id, $values_persla, false);
        }
        
     /*   if($data['hasessalud']=='1')
        {
            if( trim($info_actual['persa_codigo'])!= trim($data['essaludcod']) ){

                 $this->persona->add_essalud( $indiv_id, array(  'persa_codigo' => $data['essaludcod'] )); 
             
            }
        }
        else{
            $this->persona->remove_essalud($indiv_id);
        }
*/

       
        
        if($data['grupo']!='0')
        { 

            $grupo_id = 0;

            if( $data['grupo'] == '' )
            {

               if(trim($data['grupo_label']) != '')
               {

                  list($grupo_id) = $this->grupoempleado->registrar( array('gremp_nombre' => trim(strtoupper($data['grupo_label'])) ) , true);
                 
               }

            }
            else
            {
                $grupo_id = $data['grupo'];
            }
            
            $this->persona->actualizar_grupo($indiv_id, $grupo_id);
        }

    
       $ocupacion = trim($data['ocupacion']); 

       if($ocupacion=='' &&  trim($data['ocupacion_label']) != '')
       {

            $this->load->library(array('App/ocupacion'));

            $ocu_nombre = strtoupper(trim($data['ocupacion_label']));
 
           list($ocupacion, $ocupacion_key) = $this->ocupacion->registrar(array('ocu_nombre' => $ocu_nombre), true);

       }


       if($info_actual['ocupacion_id'] != $ocupacion)
       {
          // var_dump($info_actual);             
           $this->persona->actualizar_ocupacion(array('indiv_id' => $indiv_id, 'ocupacion' => $ocupacion ));
       }


      if( trim($data['suspension_cuarta']) != ''){

            $valores = array('indiv_suspension_cuarta' => $data['suspension_cuarta'], 
                             'indiv_suspension_fecha' =>  $data['fecha_suspension'] );

            $this->persona->actualizar($indiv_id, $valores, false);

       }
             


        if($this->db->trans_status() === FALSE)
        {
            $mensaje = 'Ocurrio un problema durante la operación';
            $this->db->trans_rollback();
            $rs = false; 
        }
        else
        {
            $mensaje = 'Información actualizada correctamente';
            $this->db->trans_commit();
            $rs = true; 
        }


        echo json_encode( array('result' => ($rs ? '1' : '0'), 'mensaje' => $mensaje )    );
        

    }


    public function add_concepto(){


          $empkey     = $this->input->post('trabajador');
          $emp_id     =  $this->persona->get_id($empkey);
          
          $tra_info   = $this->persona->get_info($emp_id); 
          
          
          $trabajador = $tra_info['indiv_appaterno'].' '.$tra_info['indiv_apmaterno'].' '.$tra_info['indiv_nombres'];

          $planilla_tipo = array('key' => $tra_info['plati_key'], 'nombre' => $tra_info['tipo_empleado']);
         //echo $det_key;
         
        $this->load->view('planillas/v_trabajador_addconcepto', array('trabajador_info' => $tra_info, 
                                                                      'key' => $empkey, 
                                                                      'trabajador' => $trabajador,
                                                                      'planilla_tipo' => $planilla_tipo, 
                                                                      'modo' => 'from_trabajador' ));

    }




    public function vincular_concepto()
    {
    
        $this->load->library(array('App/empleadoconcepto', 'App/concepto'));

        $trabajador = trim($this->input->post('trabajador'));
        $concepto   = trim($this->input->post('concepto'));
        
        $tra_id     = $this->persona->get_id($trabajador);
        $conc_id    = $this->concepto->get_id($concepto);

        $ok         = $this->empleadoconcepto->registrar($tra_id,$conc_id);
   
        $response =  array(
            
             'result' =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok) ? ' Concepto vinculado guardado correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('concepto_key' => $concepto )
        );
        
        echo json_encode($response);

    }
      

     public function guardarvariable(){

        $this->load->library(array('App/empleadovariable','App/variable'));
        
        $tra_key  = $this->input->post('tra');
        $vari_key = $this->input->post('vari'); 
        $valor    = $this->input->post('valor');


        $indiv_id = $this->persona->get_id($tra_key);
        $vari_id = $this->variable->get_id($vari_key);
     //   var_dump($indiv_id, $vari_id, $valor);


         $data = array(
                       'indiv_id'            => $indiv_id,
                       'vari_id'             => $vari_id,
                       'empvar_value'        => $valor    );  
 
        
        $ok = $this->empleadovariable->registrar($data, false);

       // $ok = $this->empleadovariable->registrar($indiv_id, $vari_id, $valor);

        $response =  array(
            
             'result' =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok) ? ' Valor actualizado correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array( 'valor' => $valor )
        );
        
        echo json_encode($response);



     }  


     public function desvincular_concepto(){

 
          $this->load->library(array('App/concepto','App/empleadoconcepto'));

          $empcon_key  = trim($this->input->post('eck'));
          $empcon_id = $this->empleadoconcepto->get_id($empcon_key); 

          $ok =  $this->empleadoconcepto->desvincular_concepto($empcon_id); 

        $response =  array(
            
             'result' =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok) ? ' Concepto desvinculado del trabajador' : 'Ocurrio un error durante la operacion',
             'data' => array( 'valor' => $valor )
        );
        
        echo json_encode($response);

     }



     public function actualizar_presupuestal()
     {
 
           $this->load->library(array('App/empleadoafectacion'));

           $indiv_id    = $this->persona->get_id(trim($this->input->post('tra')));
           $tarea_id    = trim($this->input->post('tarea'));
           $fuente_r_id = trim($this->input->post('fuente')); 
           $ano_eje = trim($this->input->post('anio')); 
           
           $t           = explode('-', $fuente_r_id);
           $fuente_id   = $t[0];
           $recurso_id  = $t[1];
  
           $ok =  $this->empleadoafectacion->registrar(array('indiv_id' => $indiv_id,
                                                             'tarea_id' => $tarea_id,
                                                             'fuente_id' => $fuente_id,
                                                             'tipo_recurso' => $recurso_id,
                                                             'ano_eje' => $ano_eje ));


           $response =  array(
              
               'result'  => ($ok)? '1' : '0',
               'mensaje' => ($ok) ? ' Datos actualizados' : 'Ocurrio un error durante la operacion',
               'data'    => array( 'valor' => $valor )
   
           );
           
          echo json_encode($response);


     }  



     public function calendarizar_variable(){



        $this->load->view('planillas/v_variable_calendarizar');
     }
}