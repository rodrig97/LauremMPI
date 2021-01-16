<?php
 
if ( ! defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

class permisos extends CI_Controller {
    
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
        
         $this->load->library(array( 'App/persona','App/permiso','App/documento' ));
       // ,'Catalogos/provincia','Catalogos/distrito'
    }
    
    public function view()
    {
         $codigo = $this->input->post('codigo');   
         $id     = $this->permiso->get_id($codigo);
         $info   = $this->permiso->view($id);
 
         $documentos = $this->documento->get_list(FUENTETIPODOC_PERMISOS, $id);
         $this->load->view('escalafon/view_permiso', array('info' => $info,'documentos' => $documentos));
        
    }
    
     public function delete(){
          
         $codigo = $this->input->post('codigo');
         
         $id= $this->permiso->get_id($codigo); 

         $op = $this->permiso->desactivar($id);

         $response =  array(
            
             'result' =>   ($op)? '1' : '0',
             'mensaje'  => ($op)? ' Registro eliminado correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $codigo )
        );

        echo json_encode($response);

    }


    public function nueva_solicitud()
    {

        $this->load->library(array('App/permisomotivo','App/permisodestino'));

        $motivos =  $this->permisomotivo->get_list(array()); 

        $destinos =  $this->permisodestino->get_list(array()); 
 

        $this->load->view('escalafon/v_permisosolicitud_nueva.php', array('motivos' => $motivos, 'destinos' => $destinos)); 
    }

    public function registrar_solicitud()
    {
        $this->load->library(array('App/permisomotivo','App/permisodestino','App/permiso', 'App/persona', 'App/permisomovimiento'));
       
        $this->db->trans_begin();


        $data = $this->input->post();
         
        $motivo_id = $this->permisomotivo->get_id($data['motivo']); 

        $solicita_id = $this->persona->get_id($data['solicita']);
        $autoriza_id = $this->persona->get_id($data['autoriza']);

 
        if( trim($data['destino_label']) != '' )
        {

            list($destino_id, $perde_key) = $this->permisodestino->registrar(  array( 
                                                                                        'perde_nombre' => strtoupper(trim($data['destino_label'])) 
                                                                                  ), 
                                                                             true  );
        }       
        else
        {
              $destino_id = $this->permisodestino->get_id($data['destino']);
        }

        // 1 solicita
        // 2 Autoriza
        // 3 Aprobado

        $fecha = date('Y').'-'.date('m').'-'.date('d');
        $hora  = date('H').':'.date('i').':00';

        $values = array(
                            'pers_id'  =>  $solicita_id,
                            'perde_id'  => $destino_id,
                            'permot_id'  => $motivo_id,
                            'pepe_fechadesde'=> $fecha,
                            'pepe_horaini'   => $hora,
                            'pepe_documento_ref' => strtoupper(trim($data['documento'])),
                            'pepe_nota'       => strtoupper(trim($data['observacion'])),
                            'indiv_autoriza' => $autoriza_id 
                      );

        list($pepe_id, $pepe_key) = $this->permiso->registrar($values, true);


        // SI esta configurado workflow entonces se registra 1 solo registro en movimiento ( SOLICITA)
        $perde_id = '';

        $values = array(
                         'pepe_id' => $pepe_id, 
                         'indiv_id_firma' => $solicita_id,
                         'ppest_id' => ASIDET_PERMISOESTADO_SOLICITADO 
                        );


        $this->permisomovimiento->registrar($values, true);

        if(ASIDET_PERMISOFLUJO_ON == FALSE)
        {

             // Caso contrario se registran dos SOLICITA Y AUTORIZA  , EL APRUEBA ES EL UNICO PARA LA BANDEJA  
              
             $values = array(
                             'pepe_id' => $pepe_id, 
                             'indiv_id_firma' => $autoriza_id,
                             'ppest_id' => ASIDET_PERMISOESTADO_AUTORIZADO 
                             );

             $this->permisomovimiento->registrar($values, true);

        }
 


        if($this->db->trans_status() === FALSE) 
        {
            $this->db->trans_rollback();
            $ok = false;
                
        }else{
                    
            $this->db->trans_commit();
            $ok = true;
        } 
 
        $response =  array(
            
             'result' =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Solicitud registrada correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $codigo )
        );

        echo json_encode($response);
    }
    

    public function registrar_papeleta(){
        
        $this->load->library(array('App/permisomotivo','App/permiso', 'App/persona'));
          
        $data = $this->input->post();
         
        $motivo_id = $data['motivo'];

        $solicita_id = $this->persona->get_id($data['solicita']);
        $autoriza_id = $this->persona->get_id($data['autoriza']);
        
        $fecha = $data['fecha'];
        
        $hora_salida = $data['horasalida'];
        $hora_retorno = $data['horaretorno'];

        $values = array(
                            'pers_id'  =>  $solicita_id, 
                            'indiv_autoriza' => $autoriza_id, 
                            'permot_id'  => $motivo_id,
                            'pepe_fechadesde'=> $fecha, 
                            'pepe_documento_ref' => strtoupper(trim($data['documento'])),
                            'pepe_nota'       => strtoupper(trim($data['observacion']))
                      );

         if( trim($hora_salida) != ''){
             $values['pepe_horaini'] = $hora_salida;
         }

         if( trim($hora_retorno) != ''){
             $values['pepe_horafin'] = $hora_retorno;
         }

        $ok = $this->permiso->registrar($values);

  
        $response =  array(
            
             'result' =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Papeleta registrada correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $codigo )
        );

        echo json_encode($response);

    }  


    public function aprobacion()
    {
         
         $this->load->library(array('App/permisoestado'));

         $estados = $this->permisoestado->get_list(array('panel' => '1'));

         $this->load->view('planillas/p_permisos_aprobacion', array('estados' => $estados));

    }

    public function get_solicitudes()
    { 
         header("Content-Type: application/json");

         $data = $this->input->get();  
            
         $params = array();

         if(trim($data['solicita']) != '' && trim($data['solicita']) != '0'  )
         {
             $params['indiv_id'] = $this->persona->get_id(trim($data['solicita']));     
         } 

         if(trim($data['estado']) != '' && trim($data['estado']) != '0'  )
         {
              $params['estado'] = trim($data['estado']);
         } 


         if(trim($data['motivo']) != '' && trim($data['motivo']) != '0'  )
         {
              $params['motivo'] = trim($data['motivo']);
         } 

         if(trim($data['retorno']) == '0')
         {
              $params['sin_retorno'] = true;
         } 

         if(trim($data['fecha_desde']) != '' )
         {
              $params['fechadesde'] = trim($data['fecha_desde']);
         }
         else{ 
              $params['fechadesde'] = trim($data['fecha_hasta']);
         }

         if(trim($data['fecha_hasta']) != '')
         {
              $params['fechahasta'] = trim($data['fecha_hasta']);
         }
         else{

              $params['fechahasta'] = trim($data['fecha_desde']);
         }


         $rs = $this->permiso->get( $params );

         $data     = array();
         $response = array();

         $c = 1;

         foreach($rs as $reg)
         {
             $data['id']   =   trim($reg['pepe_key']);
             $data['col1'] =  $c;
             $data['col2'] =  trim($reg['trabajador']);
             $data['col3'] =  trim($reg['indiv_dni']);
             $data['col4'] =  _get_date_pg(trim($reg['pepe_fechadesde']));
             $data['col5'] =  trim($reg['pepe_horaini']);
             $data['col6'] =  trim($reg['pepe_horafin']) != ''  ? trim($reg['pepe_horafin']) : '-----';
             $data['col7'] =  trim($reg['motivo']) != '' ? trim($reg['motivo']) : '--------'; 
             $data['estado'] = trim($reg['estado_abrev']); 
         
             $response[] = $data;
             $c++;
         }

         echo json_encode($response) ;

    }

    public function get_permisos_dia()
    {

         $data = $this->input->get();
    
 
         $response = array();

         $indiv_id = $this->persona->get_id($data['trabajador']);

         $params['fecha'] = $data['fecha'];
         $params['indiv_id'] = $indiv_id;
 
         $c = 1;
    
         $rs = $this->permiso->get( $params );

         foreach($rs as $reg)
         {
             $data['id']   =   trim($reg['pepe_key']);
             $data['col1'] =  $c;
             $data['col2'] =  trim($reg['pepe_horaini']);
             $data['col3'] =  trim($reg['pepe_horafin']) != ''  ? trim($reg['pepe_horafin']) : '-----';
             $data['col4'] =  trim($reg['motivo']); 
             $data['col5'] =  trim($reg['tiempo_minutos']) != ''  ? trim($reg['tiempo_minutos']) : '-----';  
         
             $response[] = $data;
             $c++;
         }

         echo json_encode($response) ;

    }



    public function ver_detalle() 
    {
        
        $data = $this->input->post();

        $permiso_id = trim($this->permiso->get_id($data['view']));

        list($solicitud_info) = $this->permiso->get( array('id' => $permiso_id) );

        $this->load->view('planillas/v_permisos_detallesolicitud', array('solicitud' => $solicitud_info) );

    }


    public function autorizar()
    {

        $this->load->library(array('App/permisomovimiento'));
        
        $data = $this->input->post();
    
        $ok = $this->permisomovimiento->registrar( array( 'pepe_id' => $permiso_id, 'ppest_id' => ASIDET_PERMISOESTADO_AUTORIZADO ) );

    }



    public function aprobar()
    {   

        $this->load->library(array('App/permisomovimiento'));

        header("Content-Type: application/json");

        $data = $this->input->post();
        
        $permiso_Key = $data['view']; 

        $permiso_id = $this->permiso->get_id($permiso_Key);
     
        $obs = strtoupper(trim($data['observacion']));

        $ok = $this->permisomovimiento->registrar( array( 'pepe_id' => $permiso_id, 'ppest_id' => ASIDET_PERMISOESTADO_APROBADO, 'pepem_obs' => $obs ) );
 
        $response =  array(
            
             'result'   =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Solicitud aprobada correctamente' : 'Ocurrio un error durante la operacion',
             'data'     => array()
        );

        echo json_encode($response);



    }


    public function registrar_retorno()
    {
        $this->load->library(array('App/permisomovimiento'));

        header("Content-Type: application/json");

        $data = $this->input->post();
        
        $permiso_id = $this->permiso->get_id($data['view']);

        $values = array(
                           'pepe_horafin' => $data['horaretorno'] 
                       );

        $ok = $this->permiso->actualizar($permiso_id , $values, false );

        $obs = strtoupper(trim($data['observacion']));

        $ok = $this->permisomovimiento->registrar( array( 'pepe_id' => $permiso_id, 'ppest_id' => ASIDET_PERMISOESTADO_RETORNO, 'pepem_obs' => $obs ) );
 
        $response =  array(
            
             'result'   => ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Retorno registrado correctamente' : 'Ocurrio un error durante la operacion',
             'data'     => array()
        );

        echo json_encode($response);

    }   


    public function mis_permisos()
    {

        $this->load->view('planillas/p_permisos_mispermisos', array());

    }

    
    public function detalle_del_dia()
    {

         $data = $this->input->post();

         $indiv_id = $this->persona->get_id($data['trabajador']);
         $fecha  = $data['dia'];
         
         $indiv_info = $this->persona->get_info($indiv_id);

         $trabajador = $indiv_info['indiv_nombres'].' '.$indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'];

         $fecha_label = get_fecha_larga($fecha);


         $this->load->view('planillas/v_permisos_detalledeldia', array('indiv_key' => $data['trabajador'], 'fecha' => $data['dia'], 'fecha_label' => $fecha_label, 'trabajador' => $trabajador ) );   
    }

    public function permisos_registrados(){


        $this->load->library(array('App/permisomotivo','App/permisodestino','App/tipoplanilla'));

        $datos = $this->input->post(); 

        $fechadesde = $datos['fechadesde'];
        $fechahasta = $datos['fechahasta'];

        $motivos = $this->permisomotivo->get_list(array()); 

        if($datos['planillatipo'] == '0'){

            $tiposPlanilla = $this->tipoplanilla->get_all( 1, array('tipo_registro_asistencia' => $datos['tipo_registro_asistencia'])); 
            $plati_id = array();

            foreach ($tiposPlanilla as $tp) {
                array_push($plati_id, $tp['plati_id']);
            }

            $in_plati = implode(',', $plati_id);
        }
        else{

            $in_plati = $datos['planillatipo'];
        }

        $this->load->view('planillas/v_permisos_registrados' , array('motivos' => $motivos, 
                                                                     'plati_id' => $in_plati,
                                                                     'fechadesde' => $fechadesde, 
                                                                     'fechahasta' => $fechahasta ) 
                         );

    }
 

    public function nuevo_permiso()
    {
        $this->load->library(array('App/permisomotivo','App/permisodestino','App/tipoplanilla'));

        $motivos =  $this->permisomotivo->get_list(array()); 
        
        $datos = $this->input->post();

        if($datos['planillatipo'] == '0'){

            $tiposPlanilla = $this->tipoplanilla->get_all( 1, array('tipo_registro_asistencia' => $datos['tipo_registro_asistencia'])); 
            $plati_id = array();

            foreach ($tiposPlanilla as $tp) {
                array_push($plati_id, $tp['plati_id']);
            }

            $in_plati = implode(',', $plati_id);
        }
        else{

            $in_plati = $datos['planillatipo'];
        }

        $this->load->view('escalafon/v_permiso_registrarnuevo.php', array('motivos' => $motivos,  
                                                                          'plati_id' => $in_plati,
                                                                          'destinos' => $destinos)); 
    }
  

    public function visualizar_permiso(){
 
        $this->load->library(array('App/permisomotivo','App/permisodestino'));

        $datos = $this->input->post();

        $permiso_key = $this->input->post('view');   
        $permiso_id     = $this->permiso->get_id($permiso_key);
        //var_dump($permiso_id);

        list($info_permiso)  = $this->permiso->get(array('id' => $permiso_id));
          
        $this->load->view('escalafon/v_permiso_visualizar.php', array('info_permiso' => $info_permiso));

    }


     public function eliminar(){
          
         $permisoKey = $this->input->post('view');
         
         $permisoId = $this->permiso->get_id($permisoKey); 

         $ok = $this->permiso->desactivar($permisoId);

         $response =  array(
            
             'result' =>   ($ok)? '1' : '0',
             'mensaje'  => ($ok)? ' Registro eliminado correctamente' : 'Ocurrio un error durante la operacion',
             'data' => array('key' => $permisoKey )
        );

        echo json_encode($response);

    }



}