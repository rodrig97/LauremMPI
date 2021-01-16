<?php

class quintacategoriadao  extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => 'qcr_id',
                                    'code'  => 'qcr_key',
                                    'name'  => 'qcr_nombre',
                                    'descripcion' => '',
                                    'state' => 'qcr_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'quintacategoria_retenciones';
    protected $_PREF_TABLE= 'QUINTART'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
 
    public function get($params = array()){
    

        $sql = " SELECT 

                    documentos.tipo_documento,
                    tipla.tipla_nombre,
                    documentos.id,
                    documentos.codigo,
                    mes.mes_nombre,
                    qr.*  

                 FROM planillas.quintacategoria_retenciones qr 
                 LEFT JOIN public.mes ON qr.mes_id = mes.mes_id 
                 LEFT JOIN ( 
                    
                    SELECT (".QUINTACATEGORIA_PROCEDENCIA_PLANILLA.") as tipo_documento,
                           pla.pla_id as id,
                           ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || '-' || pla.plati_id )  as codigo
                    FROM planillas.planillas pla 
                    WHERE pla_estado = 1 AND pla.pla_anio = ?

                 ) as documentos  ON qr.qcr_tipoprocedencia_id = documentos.tipo_documento AND qr.procedencia_id = documentos.id 
                 LEFT JOIN planillas.tipo_planilla tipla ON documentos.tipo_documento = tipla.tipla_id 
                 WHERE qcr_estado = 1 ";
    
        $values = array();

        if($params['planilla'] != ''){
            
            $sql.=" AND ( qr.qcr_tipoprocedencia_id = 1 AND procedencia_id = ? )";
            $values = array($params['anio'], $params['planilla']);
 
            if( trim($params['detalle_id']) != ''){ 
                $sql.=" AND qr.procedencia_id_detalle = ? ";
                $values[]= $params['detalle_id'];
            }


        }else{

            if($params['id'] == ''){
            
                $sql.=" AND qr.indiv_id = ? AND qr.mes_id = ?  ";
                $values = array($params['anio'], $params['indiv_id'], $params['mes_id']);
            
            }
            else{ 
                
                $sql.=" AND qr.qcr_id = ? ";
                $values = array($params['anio'], $params['id']);
            }

        }

        $sql.="  ORDER BY qr.qcr_id ";

        $rs = $this->_CI->db->query($sql, $values )->result_array();

        return $rs;

    }

    // Ingreso por mes vs retencion por mes 

    public function getTablaTrabajador($params = array())
    {
        
        $anio = $params['anio'];
        //$mes_id = $params['mes_id'];

        $sql_constancias_retencion = "SELECT anio, indiv_id, SUM(qcoa_ingresos) as ingresos, SUM(qcoa_descuento) as descuento
                                      FROM planillas.quintacategoria_constancias_anteriores 
                                      WHERE qcoa_estado = 1 AND anio = '".$anio."'
                                      GROUP BY anio, indiv_id
                                      ORDER BY anio, indiv_id "; 

        // Ingresos por mes 
        $sql_ingresos_mes =" SELECT pla.pla_anio, plaemp.indiv_id, pla.pla_mes_int as mes_id, SUM(plaec_value) as total_ingresos_mes 
                            
                             FROM planillas.conceptos cc
                             INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.conc_id = cc.conc_id  
                             INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 
                             INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ''".$anio."''  
                             INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id >= ".ESTADOPLANILLA_FINALIZADO." 
                             WHERE cc.conc_estado = 1 AND cc.conc_afecto_quinta IN (".QUINTA_TIPO_CONCEPTO_PROYECTABLE.",".QUINTA_TIPO_CONCEPTO_NOPROYECTABLE.") 
                             GROUP BY plaemp.indiv_id, pla.pla_anio, pla.pla_mes_int ";


        // Get ultimo registro quinta_categoria_retencion
        $sql_registro_quinta = " SELECT qr.*
                                 FROM (
                                     SELECT qr.anio, qr.indiv_id, qr.mes_id, max(qcr_id) as qcr_id
                                     FROM planillas.quintacategoria_retenciones qr 
                                     WHERE qcr_estado = 1 AND qr.anio = ''".$anio."''  
                                     GROUP BY qr.anio, qr.indiv_id, qr.mes_id 
                                 ) as rows
                                 INNER JOIN planillas.quintacategoria_retenciones qr ON rows.qcr_id = qr.qcr_id 
                               ";
  
        // Retenciones acumuladas por mes 
        $sql_retenciones_delmes = "SELECT anio, indiv_id, mes_id, SUM(qcr_retencion) as total_retenciones_mes
                                   FROM planillas.quintacategoria_retenciones 
                                   WHERE qcr_estado = 1 AND anio = ''".$anio."''  
                                   GROUP BY anio, indiv_id, mes_id 
                                   ORDER BY anio, indiv_id, mes_id ";
       

        $sql =" SELECT   main.indiv_id, main.indiv_dni, main.indiv_appaterno, main.indiv_apmaterno, main.indiv_nombres, main.mes_id, 

                       ( COALESCE(ingresos_mes.total_ingresos_mes,0)::text || ''_'' || COALESCE(retenciones_mes.total_retenciones_mes,0)::text || ''_'' || COALESCE(registro_quinta.qcr_trabajador_remuneraccion,0)::text || ''_'' || COALESCE(registro_quinta.qcr_ingresos_proyectados,0)::text || ''_'' || COALESCE(registro_quinta.qcr_remuneracion_neta_anual_proyectada,0)::text ) as detalle_mes 
                       
                      FROM( 
                            SELECT trabajadores.indiv_id, trabajadores.indiv_dni, trabajadores.indiv_appaterno, trabajadores.indiv_apmaterno, trabajadores.indiv_nombres, mes.mes_id
                            FROM (
                                SELECT indiv.indiv_id, indiv.indiv_dni, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
                                FROM public.individuo indiv 
                                INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                                WHERE indiv.indiv_estado = 1 
                                ";

                                if($params['dni'] == ''){

                                    if($params['plati_id'] != '0' && $params['plati_id'] != ''){

                                        $sql.=" AND persla.plati_id = ".$params['plati_id'];
                                    }

                                    if($params['vigente'] === true){

                                        $sql.=" AND persla.persla_fechacese is null ";
                                    }

                                    if($params['vigente'] === false){

                                        $sql.=" AND persla.persla_fechacese is not null ";
                                    }
                                    
                                }else{

                                    $sql.=" AND indiv.indiv_dni = ''".$params['dni']."''";

                                }
 
        $sql.="  
                                ORDER BY  indiv.indiv_id 
                            ) as trabajadores,
                            public.mes
                            WHERE mes.mes_id > 0
                      ) as main  
                      LEFT JOIN (".$sql_ingresos_mes.") as ingresos_mes ON main.indiv_id = ingresos_mes.indiv_id AND main.mes_id = ingresos_mes.mes_id
                      LEFT JOIN (".$sql_registro_quinta.") as registro_quinta  ON main.indiv_id = registro_quinta.indiv_id AND main.mes_id = registro_quinta.mes_id 
                      LEFT JOIN (".$sql_retenciones_delmes.") as retenciones_mes  ON main.indiv_id = retenciones_mes.indiv_id AND main.mes_id = retenciones_mes.mes_id  

                 ORDER BY main.indiv_appaterno, main.indiv_apmaterno, main.indiv_nombres, main.mes_id
                 ";


        $meses = $this->_CI->db->query('SELECT mes_nombre FROM public.mes WHERE mes_id > 0 ORDER BY mes_id ')->result_array();
        
        $headers_meses = '';

        foreach($meses as $reg){
            $headers_meses.= ', "'.$reg['mes_nombre'].'" text';
        }

         $sql_ct = " SELECT * 
                     FROM crosstab('".$sql."', 
                                   'SELECT mes_id FROM public.mes WHERE mes_id > 0 ORDER BY mes_id ' 
                                  ) AS ct( \"indiv_id\" numeric,
                                           \"indiv_dni\" text,
                                           \"indiv_appaterno\" text,
                                           \"indiv_apmaterno\" text,
                                           \"indiv_nombres\" text
                                   ".$headers_meses." )";

        $sql_rs = "SELECT rs.*, 
                          COALESCE(constancias.ingresos,0) as constancias_ingresos,
                          COALESCE(constancias.descuento,0) as constancias_descuento,
                          plati.plati_nombre 
                    FROM (".$sql_ct.") as rs 
                    INNER JOIN rh.persona_situlaboral persla ON rs.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1 
                    INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id
                    LEFT JOIN (".$sql_constancias_retencion.") as constancias ON rs.indiv_id = constancias.indiv_id 
                 ";

        if($params['solo_con_retenciones'] == true){

            // Retenciones acumuladas del anio  
            $sql_retenciones_anuales = "SELECT anio, indiv_id, SUM(qcr_retencion) as total_retenciones_anio
                                        FROM planillas.quintacategoria_retenciones 
                                        WHERE qcr_estado = 1 AND anio = '".$anio."'
                                        GROUP BY anio, indiv_id  
                                        ORDER BY anio, indiv_id  ";

            $sql_rs.=" 
                      INNER JOIN (".$sql_retenciones_anuales.") as retenciones_anio ON rs.indiv_id = retenciones_anio.indiv_id AND retenciones_anio.total_retenciones_anio > 0 ";


        }
        


         $sql_rs.=" 
                    ORDER BY rs.indiv_appaterno, rs.indiv_apmaterno, rs.indiv_nombres           
                    ";

         $rs = $this->_CI->db->query($sql_rs, array())->result_array();


         return $rs;


    }

    public function procesar($planilla_id){
 

            // Aqui debe calcular la quinta categoria 
            // Get Todos lo trabajadores
            // PROCESAR QUINTA CATEGORIA
            // $planilla = new planilla();
            // $trabajador = new trabajador();
            // $quintacategoria = new quintaCategoriaTrabajadorFactory($trabajador);

            $this->_CI->load->library(array('App/planillaempleado', 
                                            'App/planilla', 
                                            'App/impuestos/quintacategoria/quintacategoriaconfiguracion',
                                            'App/impuestos/quintacategoria/trabajadorquintafactory',
                                            'App/impuestos/quintacategoria/trabajadorquinta',
                                            'App/impuestos/quintacategoria/TrabajadorQuintaPlanilla',
                                            'App/impuestos/quintacategoria/TrabajadorQuintaVariable',
                                            'App/impuestos/quintacategoria/quintacategoria',
                                            'App/impuestos/quintacategoria/QuintaCategoriaDirector',
                                            'App/impuestos/quintacategoria/quintacategoriadao',
                                            'App/planillaempleadoconcepto',
                                            'App/planillaempleadovariable'
                                            ));
            
 

            $rows_trabajadores_por_planilla = $this->_CI->planillaempleado->get_list($planilla_id, false, array('tipopago' => TIPOPAGO_PLANILLA));

         
            $planilla_info = $this->_CI->planilla->get($planilla_id);
            
            $plati_id = $planilla_info['plati_id'];    

            $configuracion_tipo_trabajador = $this->_CI->quintacategoriaconfiguracion->get($plati_id);


            if(sizeof($configuracion_tipo_trabajador) > 1){
             
   
                $anio = $planilla_info['pla_anio'];
                $mes_id = $planilla_info['pla_mes_int'];
                 
                $factor_descuento_neta_anual_proyectada = 7;
                $monto_fijo_tope = VALOR_UIT * $factor_descuento_neta_anual_proyectada;

                foreach ($rows_trabajadores_por_planilla as $trabajador_detalle) {

                     $trabajador_detalle['remuneracion_por_promedio'] = false;

                     if( $configuracion_tipo_trabajador['calculo_base_promedio_remuneraciones'] == '1' ){

                        $trabajador_detalle['remuneracion_por_promedio'] = true;
     
                     }
                     // else{

                     //    $trabajador_detalle['configuracion_gratificacion'] = array($configuracion_tipo_trabajador['qcco_tipo_gratificacion'], $configuracion_tipo_trabajador['qcco_aguinaldo_julio'], $configuracion_tipo_trabajador['qcco_aguinaldo_diciembre']);
                        
                     // }

                     $tipo_calculo_gratificacion = $configuracion_tipo_trabajador['qcco_tipo_gratificacion'];

                     if($plati_id == TIPOPLANILLA_CONTRATADOS){
                        $tipo_calculo_gratificacion = ( trim($trabajador_detalle['persla_quinta_gratificacionproyeccion']) != '' ? $trabajador_detalle['persla_quinta_gratificacionproyeccion'] : QUINTA_PROYECCION_GRATIFICACION_REM);
                     }
                     
                     $trabajador_detalle['configuracion_gratificacion'] = array( $tipo_calculo_gratificacion, $configuracion_tipo_trabajador['qcco_aguinaldo_julio'], $configuracion_tipo_trabajador['qcco_aguinaldo_diciembre']); // Tipo = Gratif, 0 julio, 0 diciembre

                     $trabajador_detalle['plati_id'] = $plati_id;

                     $calcular_quinta = true;
                     
                     $anio = $planilla_info['pla_anio'];
                     $trabajadorQuintaFactory = new TrabajadorQuintaFactory($this->_CI->db, $trabajador_detalle, $anio, $mes_id );

                     $trabajadorQuinta = $trabajadorQuintaFactory->factory();
                   
                     if($configuracion_tipo_trabajador['calculo_al_alcanzar_topeminimo'] == '1'){
                       
                        if($trabajadorQuinta->ingresos_almes < $monto_fijo_tope  ){

                            $calcular_quinta = false;
                        }

                     }  
 

                     if($calcular_quinta){ 


                        $quintaCategoria = new quintacategoria($trabajadorQuinta, VALOR_UIT, $monto_fijo_tope);

                      //  var_dump($quintaCategoria->trabajadorQuinta->remuneracion_mensual);

                        $QuintaCategoriaDirector = new QuintaCategoriaDirector($quintaCategoria);
                
                        $monto_retencion_mensual = $QuintaCategoriaDirector->calcular();


                        if( $trabajadorQuinta->retencion_mes < $monto_retencion_mensual ){ // Si el monto retenido en el mes es menor que la retencion calculada
                          
                            $retencion_planilla  = $monto_retencion_mensual - $trabajadorQuinta->retencion_mes;
                        
                        }else{
                         
                            $retencion_planilla = 0;
                        }
            

                         $values = array(
                                         'plaemp_id' => $trabajador_detalle['detalle_id'], 
                                         'conc_id' => $configuracion_tipo_trabajador['conc_grati'],
                                         'plaec_value' => '0',
                                         'plaec_procedencia' => PROCENDENCIA_CONCEPTO_POR_ESTAR_RELACIONADO,
                                         'plaec_displayprint' => '1',
                                         'copc_id' =>  '0',
                                         'tarea_id' => '0',
                                         'plaec_value' => $retencion_planilla,
                                         'plaec_value_pre' => $retencion_planilla,
                                         'plaec_calculado' => '1'
                                         );

                          
                        $this->_CI->planillaempleadoconcepto->registrar($values, $trabajador_detalle['indiv_id'], '2', $static_data);

                        
                        $data = array( 'plaemp_id'          =>  $trabajador_detalle['detalle_id'], 
                                       'vari_id'            =>  $configuracion_tipo_trabajador['vari_grati'],
                                       'plaev_valor'        =>  $retencion_planilla,
                                       'plaev_procedencia'  =>  PROCENDENCIA_VARIABLE_SISTEMA,
                                       'plaev_displayprint' =>  1);

                        
                        $this->_CI->planillaempleadovariable->registrar($data, false, array());

                        $this->_CI->quintacategoriadao->registrar(array('anio' => $anio,
                                                                        'mes_id' => $mes_id,   
                                                                        'indiv_id' => $trabajador_detalle['indiv_id'],
                                                                        'qcr_tipoprocedencia_id' => QUINTACATEGORIA_PROCEDENCIA_PLANILLA,
                                                                        'procedencia_id' => $planilla_id, 
                                                                        'procedencia_id_detalle' => $trabajador_detalle['detalle_id'],
                                                                        'qcr_trabajador_remuneraccion' => $quintaCategoria->trabajadorQuinta->remuneracion_mensual,
                                                                        'qcr_ingresos_proyectados' =>$quintaCategoria->total_proyeccion,
                                                                        'qcr_ingresos_delmes' => $quintaCategoria->trabajadorQuinta->ingresos_del_mes,
                                                                        'qcr_ingresos_anteriores' =>$quintaCategoria->trabajadorQuinta->ingresos_historicos,
                                                                        'qcr_remuneracion_neta_anual_proyectada' =>$quintaCategoria->remuneracion_neta_anual_proyectada,
                                                                        'qcr_impuesto_anual' => $quintaCategoria->impuesto_anual,
                                                                        'qcr_impuesto_delmes' => $monto_retencion_mensual,
                                                                        'qcr_retencion_delmes_acum' => $quintaCategoria->trabajadorQuinta->retencion_mes,
                                                                        'qcr_retenciones_anteriores' => $quintaCategoria->trabajadorQuinta->retenciones_anteriores,
                                                                        'qcr_calculo_factor' => $quintaCategoria->calculo_factor,
                                                                        'qcr_periodo_retenciones' =>  $quintaCategoria->trabajadorQuinta->periodo_retenciones,
                                                                        'qcr_retencion' => $retencion_planilla,
                                                                        'qcr_montominimo_desc' => $monto_fijo_tope,
                                                                        'qcr_gratificacion_monto_proyectado' => $quintaCategoria->gratificaciones_proyectadas,
                                                                        'qcr_proyeccion_remuneraciones' => $quintaCategoria->trabajadorQuinta->remuneraciones_proyectadas,
                                                                        'qcr_constancia_ingresos' => $quintaCategoria->trabajadorQuinta->constancias_ingresos,
                                                                        'qcr_constancia_descuento' => $quintaCategoria->trabajadorQuinta->constancias_descuentos
                                                                        ));


                    //    }
                
                
                     }
                

                }

            }
          

    }
    
    public function delete_calculo_quinta_planilla($pla_id){
        
        $this->_CI->load->library(array('App/planilla','App/impuestos/quintacategoria/quintacategoriaconfiguracion'));
 
        $planilla_info = $this->_CI->planilla->get($pla_id);

        $plati_id = $planilla_info['plati_id'];

        $configuracion_tipo_trabajador = $this->_CI->quintacategoriaconfiguracion->get($plati_id);
  
        if(sizeof($configuracion_tipo_trabajador) > 0){
 
            $sql = " DELETE FROM planillas.planilla_empleado_concepto 
                     WHERE conc_id IN (?) AND 
                           plaemp_id IN (  SELECT plaemp_id 
                                           FROM planillas.planilla_empleados plaemp 
                                           WHERE plaemp.pla_id IN (?) )
                      ";

            $this->_CI->db->query($sql, array(  $configuracion_tipo_trabajador['conc_grati'], $pla_id));
         
            $sql = " DELETE FROM planillas.planilla_empleado_variable
                     WHERE vari_id IN (?) AND 
                           plaemp_id IN (  SELECT plaemp_id 
                                           FROM planillas.planilla_empleados plaemp 
                                           WHERE plaemp.pla_id IN (?) )
                      ";

            $this->_CI->db->query($sql, array(  $configuracion_tipo_trabajador['vari_grati'], $pla_id));

            $sql = 'DELETE FROM planillas.quintacategoria_retenciones WHERE qcr_tipoprocedencia_id = ? AND procedencia_id = ? ';

            $this->_CI->db->query($sql, array(QUINTACATEGORIA_PROCEDENCIA_PLANILLA, $pla_id));
            
        }


    }

}