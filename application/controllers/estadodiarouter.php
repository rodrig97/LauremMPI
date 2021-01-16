<?php
 
if(!defined('BASEPATH')) exit('<br/><b>Estas trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class estadodiarouter extends CI_Controller {
    
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

    public function nuevo_registro(){
  
        $this->load->library(array( 
                                     'App/persona',
                                     'App/tipoplanilla',
                                     'App/descansomedico',
                                     'App/ocupacion',
                                     'App/licencia',
                                     'Catalogos/departamento',
                                     'Catalogos/ubicacion' 
                            ));

        $tipos_descanso_medico = $this->descansomedico->get_tipos(); 

        $ciudades = $this->ubicacion->get_ciudades();

        $tipo_licencias = $this->licencia->get_tipos(array());

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


        $this->load->view('planillas/v_registrodiario_nuevo', array('tipos_descanso_medico' => $tipos_descanso_medico, 
                                                                    'tipo_licencias' => $tipo_licencias,
                                                                    'plati_id' => $in_plati,
                                                                    'ciudades' => $ciudades));
    }


    public function registrar_dia_estado_trabajador(){

        // <option value="vac_1">  Vacaciones </option>
        // <option value="desm_1">  Descansos Médicos </option>
        // <option value="comc_1">  Comisión de Servicios </option>
        // <option value="falta">  Falta Injustificada </option>
        // <option value="lic_cita_medica">  Citas Médicas </option>
        // <option value="lic_perm_particular">  Permiso Particular </option>
        // <option value="lic_perm_onomastico">  Permiso por onomástico </option>
        // <option value="lic_sindical">  Licencia Sindical </option>
        // <option value="lic_citacion_judicial">  Citación Judicial </option>
        // <option value="lic_capacitacion">  Licencia por capacitación </option>
        // <option value="lic_paternidad">  Licencia por Maternidad/Paternidad </option>
        // <option value="lic_fallecimiento">  Licencia por fallecimiento </option>
        // <option value="lic_suspension">  Suspensión Temporal </option>
        // <option value="lic_goce_haber">  Licencia con goce de Haber </option>
        // <option value="lic_sin_goce_haber">  Licencia sin goce de Haber </option>
 
        $this->load->library(array('App/hojaasistencia', 'App/licencia'));

        $datos = $this->input->post();

        $indiv_id = $this->persona->get_id(trim($datos['trabajador']));

        $estado_id = $datos['estado_dia'];

        list($estado_tipo, $detalle_id) = explode('_', $estado_id);
 
        $licencias_del_trabajador =  $this->licencia->getLicenciasDia(array('indiv_id' => $indiv_id, 
                                               'modo_busqueda_fecha_alternativo' => true,
                                               'fechadesde' => $datos['fecha_desde'], 
                                               'fechahasta' => $datos['fecha_hasta'],
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


        if($estado_tipo == 'vac'){


             $this->load->library('App/vacacionestrabajador');
              
             $values = array(
                             'pers_id'         => $indiv_id,
                             'perva_documento' => $datos['documento'], 
                             'perva_autoriza'  => $datos['autoriza'], 
                             'perva_obs'       => $datos['observacion'],
                             'perva_fechaini'  => $datos['fecha_desde'],
                             'perva_fechafin'  => $datos['fecha_hasta']
                     
                             );
             
            list($registro_id,$key) = $this->vacacionestrabajador->registrar($values, true);

            $tipo_estado_dia = ASISDET_VACACIONES;  

        } 
        else if($estado_tipo == 'desm'){



             $this->load->library('App/descansomedico');
              
             $values = array(
                             'pers_id'         => $indiv_id,
                             'perdm_documento' => $datos['documento'], 
                             'perdm_emisor'    => $datos['autoriza'], 
                             'tdm_id'          => $datos['tipo'],
                             'perdm_obs'       => $datos['observacion'],
                             'perdm_fechaini'  => $datos['fecha_desde'],
                             'perdm_fechafin'  => $datos['fecha_hasta']
                     
                             );
            
              
             
            list($registro_id,$key) = $this->descansomedico->registrar($values, true);
            
            $tipo_estado_dia = ASISDET_DESCANSOMEDICO; 

        } 
        else if($estado_tipo == 'comc'){

             $this->load->library('App/comision');
              
             list($data['distrito'], $data['provincia'], $data['departamento']) = explode('-', $data['destino']);

             $values = array(
                             'pers_id'         => $indiv_id,
                             'peco_documento'  => $datos['documento'],  
                             'peco_motivo'     => $datos['observacion'],
                             'peco_fechadesde' => $datos['fecha_desde'],
                             'peco_fechahasta' => $datos['fecha_hasta'],
                             'peco_emisor'     => $datos['autoriza'],
                             'departamento'    => $datos['departamento'],
                             'provincia'       => $datos['provincia'],
                             'distrito'        => $datos['distrito'] 
                             );
 
             
             list($registro_id,$key) = $this->comision->registrar($values, true);
             
             $tipo_estado_dia = ASISDET_COMISIONSERV;  

        } 
        else if($estado_tipo == 'falta'){

             $this->load->library('App/falta');
              
             $values = array(
                             'pers_id'           => $indiv_id,
                             'pefal_desde'       => $datos['fecha_desde'], 
                             'pefal_hasta'       => $datos['fecha_hasta'], 
                             'pefal_justificada' => '0', 
                             'pefal_observacion' => trim($datos['observacion'])
                            );
                             
            list($registro_id,$key) = $this->falta->registrar($values, true);

            $tipo_estado_dia = ASISDET_FALTA; 

        }
        else if($estado_tipo == 'lic'){
 
           $this->load->library('App/licencia');

           list($rs_licencia_info) = $this->licencia->get_tipos(array('id' => $detalle_id));
 
           $values = array(
                           'pers_id' => $indiv_id,
                           'peli_documento' => $datos['documento'], 
                           'peli_emisor' => $datos['autoriza'],
                           'peli_fechavigencia' => $datos['fecha_desde'],
                           'peli_fechacaducidad' => $datos['fecha_hasta'],
                           'peli_tipolicencia' => $detalle_id,
                           'peli_descripcion' => $datos['observacion'] 
                           );


            list($registro_id,$key) = $this->licencia->registrar($values, true);

            $tipo_estado_dia = $rs_licencia_info['hatd_id'];
 


        }
        else{ 

        }   

        if($indiv_id != '' && is_numeric($indiv_id) && $tipo_estado_dia != '' && is_numeric($tipo_estado_dia) ){

            $this->load->library(array('App/hojaasistencia'));

            $params = array(
                            'indiv_id'  => $indiv_id,
                            'fechaini'  => $datos['fecha_desde'],
                            'fechafin'  => $datos['fecha_hasta'],
                            'registro'  => $registro_id,
                            'tipo'      => $tipo_estado_dia
                           );
 

            $this->hojaasistencia->registrar_evento_dia( $params );
        }

        $ok = true;

        $response =  array(
             
              'result' =>  ($ok)? '1' : '0',
              'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
              'data' => array('key' => $key )
         );
         
         echo json_encode($response);


    }
  

    public function incidencias_registradas(){ 

        $this->load->library(array('App/licencia'));
        //tipo_registro_asistencia
        $datos = $this->input->post(); 

        $fechadesde = $datos['fechadesde'];
        $fechahasta = $datos['fechahasta'];
 
        $tipo_licencias = $this->licencia->get_tipos(array());

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

        $this->load->view('planillas/v_incidenciasdia_registro' , array('tipo_licencias' => $tipo_licencias, 
                                                                         'plati_id' => $in_plati,
                                                                         'fechadesde' => $fechadesde, 
                                                                         'fechahasta' => $fechahasta ) );

    }


    public function get_licencias_dia(){


        $this->load->library(array('App/licencia'));

        $datos = $this->input->get();

        $parametros = array();

        $parametros['tipo_licencia'] = '0';

        if( trim($datos['fecha_desde']) != ''){

            $parametros['fechadesde'] = trim($datos['fecha_desde']);
        }
        
        if( trim($datos['fecha_hasta']) != ''){

            $parametros['fechahasta'] = trim($datos['fecha_hasta']);
        }


        if( trim($datos['tipo']) != ''){
            
            $tipo_codigo = trim($datos['tipo']);

            $todosLosTipos = false;
 
            if($tipo_codigo != '0'){
                list($tipo, $tipo_id) = explode('_', $tipo_codigo );
            } else { 
                $tipo = '0';
                $todosLosTipos = true;
            }
 
            $incluir = array();
             
            if( $tipo == 'vac' || $todosLosTipos ){
                $incluir[] = 'vacaciones';
            }
            

            if( $tipo == 'desm' || $todosLosTipos ){
                $incluir[] = 'descanso_medico';
            }
            

            if( $tipo == 'comc' || $todosLosTipos ){
                $incluir[] = 'comision_servicio';
            }
            
            if( $tipo == 'lic' || $todosLosTipos ){
                $incluir[] = 'licencia';
                $parametros['tipo_licencia'] = (trim($tipo_id)!= '' ? trim($tipo_id) : '0' );
            }

            $parametros['incluir'] = $incluir;
 
        }

        if( trim($datos['trabajador']) != ''){

            $indiv_id = $this->persona->get_id(trim($datos['trabajador']));
            $parametros['indiv_id'] = $indiv_id;
        }


        $rs = $this->licencia->getLicenciasDia($parametros);
        
        $response = array();     
        
        $c = 1;
        foreach($rs as $registro){
             
            $data = array();
            $data['col1'] = $c;
            $data['id'] =   trim($registro['id']); 
            $data['col2'] = trim($registro['trabajador_nombre']);
            $data['col3'] = trim($registro['trabajador_dni']);
            $data['col4'] = trim($registro['tipo']);
            $data['col5'] = _get_date_pg($registro['desde']);
            $data['col6'] = _get_date_pg($registro['hasta']); 
            $data['col7'] = trim($registro['dias']);
            $response[] = $data;
            $c++;
        }
 
        echo json_encode($response);
        

    }


    public function get_licencias_desdepanel_dia(){


        $this->load->library(array('App/licencia'));

        $datos = $this->input->get();

        $parametros = array();

        $parametros['tipo_licencia'] = '0';

        $parametros['anio'] = '';

        if($datos['tipoPeriodo'] == 'anio'){

            $parametros['anio'] = $datos['anio'];

        } 


        $parametros['fecha_registro_sistema'] = false;
        if($datos['tipoPeriodo'] == 'registro_sistema'){

            $parametros['fecha_registro_sistema'] = true;
        } 

 
        if( trim($datos['fecha_desde']) != ''){

            $parametros['fechadesde'] = trim($datos['fecha_desde']);
        }
        
        if( trim($datos['fecha_hasta']) != ''){

            $parametros['fechahasta'] = trim($datos['fecha_hasta']);
        }


        if( trim($datos['tipo']) != ''){
            
            $tipo_codigo = trim($datos['tipo']);

            $todosLosTipos = false;
    
            if($tipo_codigo != '0'){
                list($tipo, $tipo_id) = explode('_', $tipo_codigo );
            } else { 
                $tipo = '0';
                $todosLosTipos = true;
            }
    
            $incluir = array();
             
            if( $tipo == 'vac' || $todosLosTipos ){
                $incluir[] = 'vacaciones';
            }
            

            if( $tipo == 'desm' || $todosLosTipos ){
                $incluir[] = 'descanso_medico';
            }
            

            if( $tipo == 'comc' || $todosLosTipos ){
                $incluir[] = 'comision_servicio';
            }
            
            if( $tipo == 'lic' || $todosLosTipos ){
                $incluir[] = 'licencia';
                $parametros['tipo_licencia'] = (trim($tipo_id)!= '' ? trim($tipo_id) : '0' );
            }

            $parametros['incluir'] = $incluir;
    
        }

        if($datos['filtrar_por'] == 'trabajador'){

            if( trim($datos['trabajador']) != ''){

                $indiv_id = $this->persona->get_id(trim($datos['trabajador']));
                $parametros['indiv_id'] = $indiv_id;
            }
            
        }
        else if($datos['filtrar_por'] == 'tipotrabajador'){

            $parametros['tipotrabajador'] = $datos['tipotrabajador'];
        }


        
        if( trim($datos['agruparpor']) == 'anio'){

            $parametros['agrupar_por'] = trim($datos['agruparpor']);
            $parametros['poracumulado']= trim($datos['poracumulado']);
            $parametros['valoracumulado']= trim($datos['valoracumulado']);
        }
        

        $rs = $this->licencia->getLicenciasDia($parametros);
        
        $response = array();     
        
        $c = 1;
        foreach($rs as $registro){
             
            $data = array();
            $data['col1'] = $c;
            $data['id'] =   trim($registro['id']); 
            $data['col2'] = trim($registro['trabajador_nombre']);
            $data['col3'] = trim($registro['trabajador_dni']);
            $data['tipotrabajador'] = trim($registro['tipo_trabajador']);
            $data['col4'] = trim($registro['tipo']);
            $data['col5'] = _get_date_pg($registro['desde']);
            $data['col6'] = _get_date_pg($registro['hasta']); 
            $data['anio'] = trim($registro['anio']);
            $data['col7'] = trim($registro['numero_dias']);
            $data['fecha_registro'] = _get_date_pg(trim($registro['fecha_registro']));
            $data['observacion'] = (trim($registro['observacion']) == '' ? '------' : trim($registro['observacion']) );
            $response[] = $data;
            $c++;
        }
    
        echo json_encode($response);
        

    }



    public function eliminar_registro(){

        $this->load->library(array('App/hojaasistencia'));
 
        $datos = $this->input->post();  

        $estado_id = $datos['view'];

        list($estado_tipo, $detalle_id) = explode('_', $estado_id);
        

        if($estado_tipo == 'VC'){
 
            $this->load->library('App/vacacionestrabajador');
            
            $ok = $this->vacacionestrabajador->desactivar($detalle_id);

            $tipo_estado_dia = ASISDET_VACACIONES;  

        } 
        else if($estado_tipo == 'DM'){
 
            $this->load->library('App/descansomedico');
            
            $ok = $this->descansomedico->desactivar($detalle_id);
            
            $tipo_estado_dia = ASISDET_DESCANSOMEDICO; 

        } 
        else if($estado_tipo == 'CS'){

             $this->load->library('App/comision');
               
             $ok = $this->comision->desactivar($detalle_id);
              
             $tipo_estado_dia = ASISDET_COMISIONSERV;  

        }  
        else if($estado_tipo == 'LC'){
        
           $this->load->library('App/licencia');
           
           $licencia_info = $this->licencia->view($detalle_id);

           $ok = $this->licencia->desactivar($detalle_id);
           
           $tipo_estado_dia = $licencia_info['hatd_id'];
         
        }
        else{ 

        }   

        if($detalle_id != '' && is_numeric($detalle_id) && $tipo_estado_dia != '' && is_numeric($tipo_estado_dia) ){

            $this->load->library(array('App/hojaasistencia'));

            $params = array(
                            'tiporegistro_id'  => $tipo_estado_dia,
                            'registro_id'  => $detalle_id 
                           );
        

            $this->hojaasistencia->eliminar_incidencia_asistencia( $params );
        }

        $ok = true;

        $response =  array(
             
              'result' =>  ($ok)? '1' : '0',
              'mensaje'  => ($ok)? ' Operacion realizada satisfactoriamente' : 'No pudo completarse la operación',
              'data' => array('key' => $key )
         );
         
         echo json_encode($response);



    }

}