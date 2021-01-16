<?php
 
/*  @Nombre     : Conceptos remunerativos controlador
 *  @Tipo       : Controller 
 *  @Autores    : Giordhano Valdez Linares
 *  @Ultima Modificacion : 06/06/2012
 *  @Descripcion: 
 *  
 *  @Requerimientos : 
 * 
 *  @Excepciones Detectadas:
 *     
 */

if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class conceptos extends CI_Controller{
    
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
         
        $this->load->library( array('App/concepto','App/variable','App/conceptooperacion'));
        
    }
    
    
    public function nuevo_concepto()
    {
          
         $this->load->library(array('App/partida','App/tipoplanilla','App/grupovc','App/conceptosunat', 'App/cuentacontable' ));
         
         $partidas      = $this->partida->listar($null,$this->usuario['ano_eje'], array('tipo_transaccion' => '2'));
         $tipos         = $this->tipoplanilla->get_all();
         $grupos        = $this->grupovc->get_list();
         $conceptosunat = $this->conceptosunat->get_list();
 
         $cuentasContables =  $this->cuentacontable->get(array());

         $this->load->view('planillas/v_concepto_nuevo', array(
                                                               'partidas_presupuestales' => $partidas, 
                                                               'tipos_planilla'          => $tipos,
                                                               'grupos'                  => $grupos,
                                                               'conceptosunat'           => $conceptosunat,
                                                               'cuentasContables'        => $cuentasContables

                                                               ));
         
     }
     
     
     public function modificar_concepto()
     {
         
         $this->load->library(array('App/partida','App/tipoplanilla','App/grupovc','App/conceptosunat', 'App/cuentacontable'));
         
         $conc_k                = $this->input->post('view');
         $conc_id               = $this->concepto->get_id($conc_k);
         
         $partidas              = $this->partida->listar($null,$this->usuario['ano_eje'], array('tipo_transaccion' => '2'));
         $tipos                 = $this->tipoplanilla->get_all();
         
         
         $concepto_info         = $this->concepto->get($conc_id);
         $tipo_planilla         = $concepto_info['plati_id'];
         
         $grupos                = $this->grupovc->get_list();
         $conceptosunat         = $this->conceptosunat->get_list();
         $conceptos_y_variables = $this->tipoplanilla->get_variables_conceptos($tipo_planilla, '');
         
         $concepto_ecuacion_e   = $this->concepto->print_ecuacion_encode($conc_id, $tipo_planilla);
         $operando_conceptos    = $this->concepto->get_operando_conceptos($conc_id);
         
          
         $concepto_ecuacion = $this->concepto->print_ecuacion($conc_id, $tipo_planilla);
         $cuentasContables =  $this->cuentacontable->get(array());
          
         
         $this->load->view('planillas/v_concepto_modificar', array(
                                                                   'partidas_presupuestales' => $partidas,
                                                                   'tipos_planilla'          => $tipos,
                                                                   'grupos'                  => $grupos,
                                                                   'concepto_info'           => $concepto_info,
                                                                   'ecuacion'                => $concepto_ecuacion,
                                                                   'ecuacion_encode'         => $concepto_ecuacion_e,
                                                                   'operando_conceptos'      => $operando_conceptos,
                                                                   'conceptos_y_variables'   => $conceptos_y_variables,
                                                                   'conceptosunat'           => $conceptosunat,
                                                                   'cuentasContables'        => $cuentasContables
                                                                  ));
          
     }
     
    
     public function guardar()
     {  

         $this->db->trans_begin();
         
         $data = $this->input->post();
         
         foreach($data as $d => $v) $data[$d] = trim($v);
          
         $this->load->library(array('App/tipoplanilla','App/conceptoprecon','App/grupovc'));
         
         $plati_id =$this->tipoplanilla->get_id( trim($data['tipoplanilla']) );
         

         if($data['grupo'] == '' && $data['grupo_label'] != '')
         {

            $values                    = array('gvc_nombre' => trim($data['grupo_label'])); 
            list($grupo_id,$grupo_key) =  $this->grupovc->registrar($values,true);
         } 
         else
         {
            $grupo_id = $data['grupo'];
         }

         // Guardamos la info del concepto
         
         if($data['afecto'] == '0')
         {
            $tipo = '0';
            $cosu = '0';
            $afecto = '0';
         }
         else
         {
            $tipo = $data['tipo'];
            $cosu = $data['conceptosunat'];
            $afecto = '1';
         }

         $values = array(
             
                'conc_nombre'        => strtoupper(trim($data['nombreconcepto'])),
                'conc_nombrecorto'   => strtoupper(trim($data['nombrecorto'])),
                'conc_tipo'          => $tipo,
                'conc_displayprint'  => $data['mostrarimpresion'],
                'conc_descripcion'   => strtoupper(trim($data['descripcion'])),
                'plati_id'           => $plati_id,
                'gvc_id'             => $grupo_id,
                'conc_esxdefecto'    => (trim($data['predeterminado']) != '') ? $data['predeterminado'] : '0',
                'conc_accesodirecto' => (trim($data['acceso_directo']) != '') ? $data['acceso_directo'] : '0',
                'conc_afecto'        => $afecto,
                'conc_max_x_mes'     => (trim($data['cantmaxmes']) != '' && trim($data['cantmaxmes']) != '0') ? $data['cantmaxmes'] : '0',
                'cosu_id'            => $cosu,
                'conc_planillon_nombre' => (trim($data['nombreplanillon']) == '') ? substr(trim($data['nombrecorto']), 0,6) : substr(trim($data['nombreplanillon']), 0,6),
                'conc_orden' => (trim($data['posicionimpresion']) != '' && is_numeric($data['posicionimpresion'])  ) ? $data['posicionimpresion'] : '0',
                'conc_afecto_cuarta'    => (trim($data['afecto_a_cuarta']) != '') ? $data['afecto_a_cuarta'] : '0',
                'conc_afecto_quinta'    => (trim($data['afecto_a_quinta']) != '') ? $data['afecto_a_quinta'] : '0'
                
         );
         
 

         list($concepto_id,$concepto_key) = $this->concepto->registrar($values,true);
         
         // Guardamos las operaciones
         $rs = false;
         
         
         if($concepto_id !== false && $concepto_id !== '' && $concepto_id !== null)
         {
           
               list($rs, $mensaje_error_ecuacion) =   list($rs, $mensaje_error_ecuacion) =  $this->conceptooperacion->guardar_ecuacion($concepto_id, $data['ecuacion'], '1' );
    

               if(($data['partida'] != '' && $data['partida'] != '0') || ( $data['cuentadebe'] != '0' && $data['cuentadebe'] != '' && $data['cuentahaber'] != '0' && $data['cuentahaber'] != '' ) )
               {
                    $d_par = explode('-',$data['partida']);

                    $partida_anio = ($d_par[0] != '' ? $d_par[0] : '');
                    $partida_id   = ($d_par[1] != '' ? $d_par[1] : '');

                    $cuentadebe = ($data['cuentadebe'] != '0' && $data['cuentadebe'] != '' ? $data['cuentadebe'] : '0');
                    $cuentahaber = ($data['cuentahaber'] != '0' && $data['cuentahaber'] != '' ? $data['cuentahaber'] : '0');

                    $this->conceptoprecon->registrar(array('conc_id' => $concepto_id,
                                                           'ano_eje' => $partida_anio,
                                                           'cuentadebe_id' => $cuentadebe,
                                                           'cuentahaber_id' => $cuentahaber,
                                                           'id_clasificador' => $partida_id 
                                                           ));
               }
        
         }




         if ($this->db->trans_status() === FALSE || $rs == FALSE)
         {
             $this->db->trans_rollback();
             
             $mensaje = 'Ocurrio un error durante la operacion';

             if($rs == false) $mensaje = $mensaje_error_ecuacion;

             $ok =false;
         
         }
         else
         {
             $this->db->trans_commit();
             $mensaje = '  Concepto remunerativo guardado correctamente';
             $ok = true;
         }     
        

        $response =  array(
            
             'result'  =>   ($ok)? '1' : '0',
             'mensaje' =>   $mensaje,
             'data'    =>   array('concepto_key' => $concepto_key )
        );
        
        echo json_encode($response);
          
     }
     
     
     
     public function actualizar()
     {
      
      
         $this->load->library(array('App/tipoplanilla',
                                    'App/conceptoprecon', 
                                    'App/grupovc'));

         $data = $this->input->post();
         
         foreach($data as $d => $v) $data[$d] = trim($v);
         
         
         $this->db->trans_begin();
  
         $conc_k =  $data['view'];

  
         if($data['grupo']== '' && $data['grupo_label'] != '')
         {

            $values = array('gvc_nombre' => trim($data['grupo_label'])); 
            list($grupo_id,$grupo_key) =  $this->grupovc->registrar($values,true);

         } 
         else
         {
            $grupo_id = $data['grupo'];
         }
            

         if($data['afecto'] == '0')
         {
            $tipo = '0';
            $cosu = '0';
            $afecto = '0';
         }
         else
         {
            $tipo = $data['tipo'];
            $cosu = $data['conceptosunat'];
            $afecto = '1';
         }


         $values = array(
             
               'conc_nombre'        => strtoupper(trim($data['nombreconcepto'])),
               'conc_nombrecorto'   => strtoupper(trim($data['nombrecorto'])),
               'conc_tipo'          => $tipo,
               'conc_displayprint'  => $data['mostrarimpresion'],
               'conc_descripcion'   => strtoupper(trim($data['descripcion'])),
               'conc_esxdefecto'    => (trim($data['predeterminado']) != '') ? $data['predeterminado'] : '0',
               'conc_accesodirecto' => (trim($data['acceso_directo']) != '') ? $data['acceso_directo'] : '0', 
               'conc_afecto'        => $afecto,
               'conc_max_x_mes'     => (trim($data['cantmaxmes']) != '' && trim($data['cantmaxmes']) != '0') ? $data['cantmaxmes'] : '0',
               'gvc_id'             => $grupo_id,
               'cosu_id'            => $cosu,
               'conc_planillon_nombre' => (trim($data['nombreplanillon']) == '') ? substr(trim($data['nombrecorto']), 0,20) : substr(trim($data['nombreplanillon']), 0,20),
               'conc_orden' => (trim($data['posicionimpresion']) != '' && is_numeric($data['posicionimpresion']) ) ? $data['posicionimpresion'] : '1000',
               'conc_afecto_cuarta'    => (trim($data['afecto_a_cuarta']) != '') ? $data['afecto_a_cuarta'] : '0',
               'conc_afecto_quinta'    => (trim($data['afecto_a_quinta']) != '') ? $data['afecto_a_quinta'] : '0'
          );
 
 

        $concepto_id = $this->concepto->get_id($conc_k);
         
        $this->concepto->actualizar( $conc_k ,$values,true); // se guardan los datos del concepto 
 

        list($rs, $mensaje_error_ecuacion, $nueva_ecuacion_id) =  $this->conceptooperacion->guardar_ecuacion($concepto_id, $data['ecuacion'], '1' );  // Actualizar ecuacion
        
        
        
        if(($data['partida'] != '' && $data['partida'] != '0') || ( $data['cuentadebe'] != '0' && $data['cuentadebe'] != '') ||  ( $data['cuentahaber'] != '0' && $data['cuentahaber'] != '' ) )
        {
            $d_par = explode('-',$data['partida']);

            $partida_anio = ($d_par[0] != '' ? $d_par[0] : '');
            $partida_id   = ($d_par[1] != '' ? $d_par[1] : '');
 
            $cuentadebe = ($data['cuentadebe'] != '0' && $data['cuentadebe'] != '' ? $data['cuentadebe'] : '0');
            $cuentahaber = ($data['cuentahaber'] != '0' && $data['cuentahaber'] != '' ? $data['cuentahaber'] : '0');
                

            $this->conceptoprecon->registrar(array(
                                                       'conc_id'         => $concepto_id,
                                                       'ano_eje'         => $partida_anio,
                                                       'cuentadebe_id' => $cuentadebe,
                                                       'cuentahaber_id' => $cuentahaber,
                                                       'id_clasificador' => $partida_id 
                                                   ));
        }  
        

        if($data['partida'] == '0' && $data['cuentadebe'] == '0' && $data['cuentahaber'] == '0' )
        {
         
            $this->conceptoprecon->desactivar($concepto_id);
            $partida_id   = '';
        
        }


        $values['clasificador'] = $partida_id;

        $this->concepto->actualizar_planillas($concepto_id, $nueva_ecuacion_id, $values);
        
       
        if ($this->db->trans_status() === FALSE || $rs == FALSE)
        {
            $this->db->trans_rollback();
            
            $mensaje = 'Ocurrio un error durante la operacion';

            if($rs == false) $mensaje = $mensaje_error_ecuacion;

            $ok =false;
       
        }
        else
        {
            $this->db->trans_commit();
            $mensaje = ' Concepto remunerativo actualizado correctamente';
            $ok = true;
        }     
         

        $response =  array(
            
             'result'  =>   ($ok)? '1' : '0',
             'mensaje' =>   $mensaje,
             'data'    =>   array('about' => $conc_k )
        );
        

        echo json_encode($response);
         
         
     }
     
     
     public function imprime_ecuacion(){
        
           $MEMORY = array();
         /*
           $prueba = array('1','2','3','4','5');
           
           var_dump($prueba);
           foreach($prueba as $x){
               echo " $x ";
           }
           
           echo "<br/>";
           
           array_splice($prueba,1,3,'resplace here');
           
           foreach($prueba as $x){
               echo " $x ";
           } 
               var_dump($prueba);
           */
           
           
          echo $this->concepto->print_ecuacion('56');
           
     } 
     
     public function provide($tipo)
     {
           
         
        $this->load->library(array('App/tipoplanilla'));  
         
        header("Content-Type: application/json");
         
         if($tipo == 'main')
         {
          
            $start = 1;
            $end = 10;
            
            $params = array();
             
            $params['nombre'] = strtoupper(trim($this->input->get('nombre')));
            

            if(trim($this->input->get('tipoplanilla')) != '0' )   $params['tipoplanilla'] = $this->tipoplanilla->get_id(trim($this->input->get('tipoplanilla')));
              
            if(trim($this->input->get('grupo')) != '' && trim($this->input->get('grupo')) != '0' ) $params['grupo'] = trim($this->input->get('grupo'));
           

            if($this->input->get('tipo') != '0')
            {
                if($this->input->get('tipo')  == '99')
                {
                    $params['tipoconcepto'] = '0';    
                    $params['afecto'] = '0';  
                }    
                else
                {
                    $params['tipoconcepto'] = $this->input->get('tipo');
                }
            }
            else
            {
               $params['tipoconcepto'] = '';    
            } 


            if($this->input->get('partida') != '0')
            {
                if($this->input->get('partida')  == '99')
                {
                    $params['partida'] = '0';    
                }
                else{
                    $params['partida'] = $this->input->get('partida');
                }    
                
            }
            else
            {
                $params['partida'] = '';      
            }     



            if($this->input->get('sunat') != '0')
            {
                if($this->input->get('sunat')  == '-1')
                {
                    $params['sunat'] = '0';    
                }
                else{
                    $params['sunat'] = $this->input->get('sunat');
                }    
                
            }
            else
            {
                $params['sunat'] = '';      
            }       


            if($this->input->get('predeterminado') != '99')
            {
                $params['predeterminado'] = $this->input->get('predeterminado');
            }
            else
            {
                $params['predeterminado'] = '';      
            }     
    

            if($this->input->get('afecto_cuarta') != '2')
            {
                $params['afecto_cuarta'] = $this->input->get('afecto_cuarta');
            }
            else
            {
                $params['afecto_cuarta'] = '';      
            }     
            
            
            if($this->input->get('afecto_quinta') != '5')
            {
                $params['afecto_quinta'] = $this->input->get('afecto_quinta');
            }
            else
            {
                $params['afecto_quinta'] = '';      
            }     
            
            

            $conceptos = $this->concepto->get_list($params);

            $c = 1;

            $total = sizeof($conceptos);

            header("Content-Range: " . "items ".$start."-".$total."/".$total);     
            $data = array();
            $response = array();
            foreach($conceptos as $concepto){
                  
                
                $data['id'] =   trim($concepto['conc_key']);
                $data['col1'] = $c;
                $data['col2'] = trim($concepto['conc_nombre']);  
                $data['col3'] = ( trim($concepto['conc_afecto']) == '0' ? 'No Afecto' :  trim($concepto['concepto_tipo']));
                $data['col4'] = trim($concepto['tipo_planilla']);
                $data['col5'] = trim($concepto['grupo_nombre']);
                $data['col6'] = (trim($concepto['conc_descripcion']) != '') ? trim($concepto['conc_descripcion']) : ' ------- ';
                 
                $response[] = $data;
                $c++;
            }

            echo json_encode($response) ;
            
            
         }
         else if($tipo=='reportexconcepto')
         {  

             $response = array();


             $plati_key = $this->input->get('planillatipo');
             $plati_id =  $this->tipoplanilla->get_id($plati_key);

             $name     = trim($this->input->get('name'));
             $t        = explode('*',$name);
             $name     = $t[0];

             $tipo_concepto = trim($this->input->get('tipoconcepto'));

             $conceptos = $this->concepto->get_list_reporte(array('plati_id' => $plati_id, 
                                                                  'nombre' => $name,
                                                                  'tipoconcepto' => $tipo_concepto )); 
     
    

             if($tipo_concepto == TIPOCONCEPTO_DESCUENTO) 
             {

                 $data =    array(
                                    'value'      => 'grupo_'.GRUPOVC_AFP,
                                    'name'       => 'AFP ( GRUPO )',
                                    'otro_dato'  => 'AFP ( GRUPO )'
                                 );

                 $response[] = $data;
                
                 $data =    array(
                                    'value'      => 'grupo_'.GRUPOVC_RETENCIONJUDICIAL,
                                    'name'       => 'RETENCION JUDICIAL ( GRUPO )',
                                    'otro_dato'  => 'RET.JUDI ( GRUPO )'
                                 );
                
                  $response[] = $data;
             }



             foreach($conceptos as $reg)
             {

                 $data =    array(
                                    'value'      => 'concepto_'.trim($reg['conc_key']),
                                    'name'       => trim($reg['conc_nombre']),
                                    'otro_dato'  => trim($reg['conc_nombrecorto'])
                                 );

                 $response[] = $data;
                 
             }
             

             echo json_encode($response);

         }
          
         
     }
     
     
     public function view()
     {
          
         $concepto_key = trim($this->input->post('concepto'));
         
         $concepto_id = $this->concepto->get_id($concepto_key);
         
         $concepto_info = $this->concepto->get($concepto_id);
         
         $ecuacion =  $this->concepto->print_ecuacion($concepto_id);
         
         $operando_conceptos    = $this->concepto->get_operando_conceptos($concepto_id);
         
        $concepto_info['predeterminado'] =  ($concepto_info['conc_esxdefecto'] == '1') ? 'SI' : 'NO';
        $concepto_info['obligatorio'] =  ($concepto_info['conc_obligatorio'] == '1') ? 'SI' : 'NO';
        $concepto_info['afecto'] =  ($concepto_info['conc_afecto'] == '1') ? 'SI' : 'NO';
        $concepto_info['restriccion'] =  (trim($concepto_info['cmm_id']) == '') ? 'NO' : 'SI';
    
         
         $this->load->view('planillas/v_concepto_info', array('concepto_info' => $concepto_info, 'ecuacion' => $ecuacion, 'operando_conceptos' => $operando_conceptos));
     }
     
     
     
      public function guardar_detalleplanilla(){
        
         $plaec_key = trim($this->input->post('conc_k'));
         $newest    = $this->input->post('ns');
         
         $this->load->library(array('App/planillaempleadoconcepto'));
     


         $rs = $this->planillaempleadoconcepto->actualizar_marcado($plaec_key, array('plaec_marcado' => $newest ), true);
         
         $response =  array(

                 'result' =>  ($rs)? '1' : '0',
                 'mensaje'  => ($rs)? 'Valor actualizado correctamente' : 'Ocurrio un error durante la operacion',
                 'data' => array('key' => $p_k )
          );
       
          echo json_encode($response);
        
    }
     
    
     public function vincular_a_detalle()
     {
     
         $this->load->library(array('App/planilla',
                                    'App/empleadoafectacion',
                                    'App/planillaempleado',
                                    'App/planillaempleadoconcepto'));
           
         $conc_key        = $this->input->post('conc');
         $plaemp_k        = $this->input->post('detalle');
         
         $conc_id         = $this->concepto->get_id($conc_key);
         $conc            = $this->concepto->get($conc_id);
           
         $data_detalle    = $this->planillaempleado->get($plaemp_k, true);
         $empleado_id     = $data_detalle['indiv_id'];
           
         $plani_info   = $this->planilla->get($data_detalle['pla_id']);
           
         $for_all_detalle = $this->input->post('forall');
            

         $static_data = array(
                           'y_values' => array()
                        ); 

         $static_data['y_values']['platica_id'] = $data_detalle['platica_id'];
         

       /*   
         if($plani_info['pla_afectacion_presu'] == '1') // Datos de afectacion especificados en la planilla
         {
             $tarea_id        = ($plani_info['tarea_id']  != '') ? $plani_info['tarea_id'] : '0'; 
             $fuente_id       = ($plani_info['fuente_id'] != '') ? $plani_info['fuente_id'] : '0';
             $recurso_id      = ($plani_info['tipo_recurso'] != '') ? $plani_info['tipo_recurso'] : '';
             $clasificador_id = ($rs_plati['clasificador_id'] != '') ? $rs_plati['clasificador_id'] : '';
         }
         else if($plani_info['pla_afectacion_presu'] == '2')
         {
             $afectacion_info = $this->empleadoafectacion->get($empleado_id);
             $tarea_id        = $afectacion_info['tarea_id'];
             $fuente_id       = $afectacion_info['fuente_id'];
             $recurso_id      = $afectacion_info['tipo_recurso'];
             $clasificador_id = ''; 
         }*/
         
         // 

     
         $tarea_id        = $data_detalle['tarea_id'];
         $fuente_id       = $data_detalle['fuente_id'];
         $recurso_id      = $data_detalle['tipo_recurso'];

         $clasificador_id = ''; 
  
         if($plani_info['pla_afectacion_presu'] ==  PLANILLA_AFECTACION_ESPECIFICADA ) // Datos de afectacion especificados en la planilla
         {

            if($plani_info['pla_especificar_clasificador'] == '1')
            { 
             
               $clasificador_id = ($plani_info['clasificador_id'] != '') ? $plani_info['clasificador_id'] : '';
            }
            else
            {
               $clasificador_id = '';
            }
         }
       

         if($for_all_detalle == '0')
         { 
        
               $values = array(
                               'plaemp_id'          => $data_detalle['plaemp_id'], 
                               'conc_id'            => $conc_id,
                               'plaec_value'        => '0',
                               'plaec_procedencia'  => PROCENDENCIA_CONCEPTO_DE_LA_PLANILLA,
                               'plaec_displayprint' => $conc['conc_displayprint'],
                               'copc_id'            =>  ($conc['afectacion_id'] != '') ? $conc['afectacion_id'] : '0',
                               'tarea_id'           => $tarea_id,
                               'fuente_id'          => $fuente_id,
                               'tipo_recurso'       => $recurso_id,
                               'clasificador_id'    =>  ( ($clasificador_id != '') ?  $clasificador_id : trim($conc['id_clasificador']) ),
                               'indiv_id_b'         =>  ($conc['indiv_id_b'] != '') ? $conc['indiv_id_b'] : '0',
                               'conc_afecto'        => $conc['conc_afecto'],
                               'conc_ecuacion_id'   => $conc['conc_ecuacion_id'],
                               'conc_tipo'          => $conc['conc_tipo'],
                               'gvc_id'             => $conc['gvc_id'],
                               'conc_max_x_mes'     => $conc['conc_max_x_mes']
                               );
            
            
               
               $ok =  $this->planillaempleadoconcepto->registrar($values , $empleado_id,  PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR, $static_data );
        
          }
          elseif($for_all_detalle == '1')
          {

              $planilla         = $this->input->post('planilla');
              $pla_id           = $this->planilla->get_id($planilla);
              $detalle_planilla = $this->planillaempleado->get_list($pla_id);
               
              $c = 0;  

              foreach($detalle_planilla as $reg)
              {

                   $empleado_id = $reg['indiv_id'];
               
                   $values = array(
                                   'plaemp_id'          => $reg['detalle_id'], 
                                   'conc_id'            => $conc_id,
                                   'plaec_value'        => '0',
                                   'plaec_procedencia'  => PROCENDENCIA_CONCEPTO_DE_LA_PLANILLA,
                                   'plaec_displayprint' => $conc['conc_displayprint'],
                                   'copc_id'            =>  ($conc['afectacion_id'] != '') ? $conc['afectacion_id'] : '0',
                                   'tarea_id'           => $tarea_id,
                                   'fuente_id'          => $fuente_id,
                                   'tipo_recurso'       => $recurso_id,
                                   'clasificador_id'    =>  ( ($clasificador_id != '') ?  $clasificador_id : trim($conc['id_clasificador']) ),
                                   'indiv_id_b'         =>  ($conc['indiv_id_b'] != '') ? $conc['indiv_id_b'] : '0',
                                   'conc_afecto'        => $conc['conc_afecto'],
                                   'conc_ecuacion_id'   => $conc['conc_ecuacion_id'],
                                   'conc_tipo'          => $conc['conc_tipo'],
                                   'gvc_id'             => $conc['gvc_id'],
                                   'conc_max_x_mes'     => $conc['conc_max_x_mes']
                                   );
               
               
                    $ok =  $this->planillaempleadoconcepto->registrar($values , $empleado_id, PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR, $static_data );

                    if($ok) $c++;

              }

          }
       
         $response =  array(

                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' => ($ok)? 'Concepto agregado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array('key' => $p_k, 'cant' => $c )
          );
       
          echo json_encode($response);
     
           
        
     } 
     


     public function vincular_beneficiario(){


        $this->load->library(array('App/empleadoconcepto','App/persona'));
        
        $empcon_key  = trim($this->input->post('detalle'));          
        $empcon_id   = $this->empleadoconcepto->get_id($empcon_key);    
        $empcon_info = $this->empleadoconcepto->get($empcon_id);
        
        $concepto_id = $empcon_info['conc_id'];
        
        $conc_info   = $this->concepto->get($concepto_id);
  
        $conc_info['ecuacion'] =  $this->concepto->print_ecuacion($concepto_id);
  
        $trabajadores = $this->persona->get_list();  
 
        $this->load->view('planillas/v_vincular_beneficiario', array('concepto_info' => $conc_info, 'trabajadores' => $trabajadores, 'key' => $empcon_key  ) );

     }


     public function vincular_a_beneficiario(){

          $this->load->library(array('App/empleadoconcepto','App/empleadoconceptobe','App/persona'));
         
          $data      = $this->input->post();
          $empcon_id = $this->empleadoconcepto->get_id($data['empconkey']);
          $indiv_id  = $this->persona->get_id($data['trabajador']);

          $ok = $this->empleadoconceptobe->registrar(array(
                                                     'empcon_id'       => $empcon_id,
                                                     'indiv_id_b'      => $indiv_id,
                                                     'ecb_descripcion' => $data['descripcion']));
      
          $response =  array(

                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' => ($ok)? 'Beneficiario registrado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array('key' => $p_k )
          );
       
          echo json_encode($response);
     }


     public function modificacion_valores_planilla(){

         $this->load->library(array('App/planilla'));


         $planilla_id = $this->planilla->get_id(trim($this->input->post('planilla')));
         
         $conceptos   = $this->planilla->get_conceptos_involucrados($planilla_id);
         $variables   = $this->planilla->get_variables_involucradas($planilla_id);

         $this->load->view( 'planillas/v_planilla_modificarvalores', array('variables' => $variables,
                                                                           'conceptos' => $conceptos));

     }


     public function actualizar_detalle_planilla(){

        $this->load->library(array('App/planilla'));

        $planilla_id  = $this->planilla->get_id(trim($this->input->post('planilla')));
        $conc_id      = $this->concepto->get_id(trim($this->input->post('concepto')));   
        $nuevo_estado = $this->input->post('estado');
        
        $ok           = $this->concepto->actualizar_estado_planilla($planilla_id, $conc_id, $nuevo_estado);

        $response =  array(

                 'result'  =>  ($ok)? '1' : '0',
                 'mensaje' => ($ok)? 'Concepto actualizado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
        );
       
          echo json_encode($response);

  
     }


     public function view_calculo()
     {  

        $this->load->library(array('App/planillaempleadoconcepto'));

        $key = trim($this->input->post('key'));

        $id          = $this->planillaempleadoconcepto->get_id($key);
        $info        = $this->planillaempleadoconcepto->get_full_info($id);
        $movimientos = $this->planillaempleadoconcepto->get_movimientos($id);

        $conc_id   = $info['conc_id'];
 
        $formula   = $this->concepto->print_ecuacion($conc_id, false, $info['conc_ecuacion_id']);
        
        $calculado = $this->concepto->preview_ecuacion($id);
      
        $this->load->view('planillas/v_concepto_calculado', array('formula' => $formula, 
                                                                  'calculado' => $calculado, 
                                                                  'info' => $info, 
                                                                  'movimientos' => $movimientos ));
 

     }


     public function gestion_rapida()
     {


          $this->load->library(array('App/persona','App/empleadoconcepto'));

          $data     = $this->input->post();
          
          $indiv_id = $this->persona->get_id($data['trabajador']);
          $conc_id  = $this->concepto->get_id($data['concepto']); 
          
          $estado   = ($data['estado']==='1') ? true : false;

          if($estado)
          {   
             $ok = $this->empleadoconcepto->registrar($indiv_id, $conc_id);
          } 
          else{
             $ok = $this->empleadoconcepto->desvincular_concepto($indiv_id, $conc_id);
          }  


          $response =  array(

                 'result'  =>  ($ok)  ? '1' : '0',
                 'mensaje' =>  ($ok)  ? 'Concepto actualizado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
          );
       
          echo json_encode($response);
     }
  

     public function delete(){


          $data    = $this->input->post();
          
          $conc_id = $this->concepto->get_id($data['view']);
          
          $ok      = $this->concepto->delete($conc_id);
 
          $this->concepto->actualizar_planillas($conc_id, 0, array(), TRUE);


          $response =  array(

                 'result'  =>  ($ok)  ? '1' : '0',
                 'mensaje' =>  ($ok)  ? 'Concepto eliminado correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
          );
       
          echo json_encode($response);

     }    


     public function configurar_restriccion_montos(){

        $data          = $this->input->post();
        
        $conc_id       = $this->concepto->get_id($data['view']);
        
        $concepto_info = $this->concepto->get($conc_id);

        $this->load->view('planillas/v_concepto_minmax', array('concepto_info' => $concepto_info));

     }

     public function actualizar_ajuste_minmax(){


         $data = $this->input->post();

         $conc_id = $this->concepto->get_id($data['view']);

         if($data['minimo'] == ''){
           $data['minimo'] = '0';
         }          

         if($data['maximo'] == ''){
           $data['maximo'] = '1000000';
         }        

         $values = array(
                        'concepto'     => $conc_id,
                        'minimo'       => $data['static_minimo'],
                        'maximo'       => $data['static_maximo'],
                        'modo_calculo' => $data['modo_calculo'],
                        'ajuste_de'    => $data['ajustar'],
                        'obs'          => $data['obs']

                        );
  
 
         $ok = $this->concepto->registrar_min_max($values);

          $response =  array(

                 'result'  =>  ($ok)  ? '1' : '0',
                 'mensaje' =>  ($ok)  ? 'Restricción registrada correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
          );
       
          echo json_encode($response);

     }


     public function quitar_ajuste_minmax(){


         $data    = $this->input->post();
         
         $conc_id = $this->concepto->get_id($data['view']);
 
         $ok      = $this->concepto->quitar_min_max(array('concepto' => $conc_id));


         $response =  array(

                 'result'  =>  ($ok)  ? '1' : '0',
                 'mensaje' =>  ($ok)  ? 'Restricción retirada correctamente' : 'Ocurrio un error durante la operacion',
                 'data'    => array()
          );
       
          echo json_encode($response);

     }

     public function condiciones()
     {

         $this->load->view('planillas/v_concepto_condiciones', array() );
     }


}
  