<?php

class cuartacategoria  extends Table{

    private $planilla_id = 0;
     
    public function __construct(){
        
        parent::__construct();
        
    }
    

    private function get_ingresos_planilla($indiv_id){

        // Obtener cuanto ha cobrado por mes y promediarlo
         
        $sql = "SELECT SUM(plaec_value) as total_planilla 
                FROM planillas.conceptos cc  
                INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.conc_id = cc.conc_id  
                INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
                INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_id = ? 

                WHERE cc.conc_estado = 1 AND cc.conc_afecto_cuarta = 1
 
              ";

        $rs = $this->_CI->db->query($sql, array($indiv_id, $this->planilla_id ))->result_array();
        
        return $rs[0]['total_planilla'];

    }


    public function procesar($planilla_id){ 

        $this->planilla_id = $planilla_id;

        $this->_CI->load->library(array('App/planillaempleado', 
                                        'App/planilla', 
                                        'App/planillaempleadoconcepto',
                                        'App/planillaempleadovariable',
                                        'App/impuestos/cuartacategoriaconfiguracion'
                                        ));
        


        $rows_trabajadores_por_planilla = $this->_CI->planillaempleado->get_list($planilla_id, false, array('tipopago' => TIPOPAGO_PLANILLA));

        $planilla_info = $this->_CI->planilla->get($planilla_id);
        
        $plati_id = $planilla_info['plati_id'];    

        $configuracion_tipo_trabajador = $this->_CI->cuartacategoriaconfiguracion->get($plati_id);
  

        if(sizeof($configuracion_tipo_trabajador) > 1){
          
            $anio = $planilla_info['pla_anio'];
            $mes_id = $planilla_info['pla_mes_int'];
           

            foreach ($rows_trabajadores_por_planilla as $trabajador_detalle) {


                 $descuento_planilla = 0;

                 $ingresos_afectos_planilla = $this->get_ingresos_planilla($trabajador_detalle['indiv_id']);

//                 var_dump($ingresos_afectos_planilla, $trabajador_detalle['indiv_suspension_cuarta'], $trabajador_detalle['suspension_anio'], $anio);
             
                 $monto_minimo_obligatorio = ($configuracion_tipo_trabajador['cuartaconf_monto_tope'] != '' ? $configuracion_tipo_trabajador['cuartaconf_monto_tope'] : 0);
  
                 if( $ingresos_afectos_planilla <  $monto_minimo_obligatorio || ( trim($trabajador_detalle['indiv_suspension_cuarta']) == '1' && trim($trabajador_detalle['suspension_anio'])  == trim($anio) ) ){

                    //echo 'Aqui TAA';

                 } else { 

                     $porcentaje_descuento = ($configuracion_tipo_trabajador['cuartaconf_porcentaje'] == '' ? 0 : $configuracion_tipo_trabajador['cuartaconf_porcentaje'] );

                     $descuento_planilla = $ingresos_afectos_planilla * $porcentaje_descuento / 100;

                     $values = array(
                                     'plaemp_id' => $trabajador_detalle['detalle_id'], 
                                     'conc_id' => $configuracion_tipo_trabajador['cuarta_conc'],
                                     'plaec_value' => '0',
                                     'plaec_procedencia' => PROCENDENCIA_CONCEPTO_POR_ESTAR_RELACIONADO,
                                     'plaec_displayprint' => '1',
                                     'copc_id' =>  '0',
                                     'tarea_id' => '0',
                                     'plaec_value' => $descuento_planilla,
                                     'plaec_value_pre' => $descuento_planilla,
                                     'plaec_calculado' => '1'
                                     );

                    $this->_CI->planillaempleadoconcepto->registrar($values, $trabajador_detalle['indiv_id'], '2', $static_data);

                    //var_dump($configuracion_tipo_trabajador['cuarta_vari']);

                    $data = array( 'plaemp_id'          =>  $trabajador_detalle['detalle_id'], 
                                   'vari_id'            =>  $configuracion_tipo_trabajador['cuarta_vari'],
                                   'plaev_valor'        =>  $descuento_planilla,
                                   'plaev_procedencia'  =>  PROCENDENCIA_VARIABLE_SISTEMA,
                                   'plaev_displayprint' =>  1);

                    
                    $this->_CI->planillaempleadovariable->registrar($data, false, array());

                }


            }

        }


    }
    
    public function delete_calculo_cuarta_planilla($pla_id){
        
        $this->_CI->load->library(array('App/planilla','App/impuestos/cuartacategoriaconfiguracion'));
    
        $planilla_info = $this->_CI->planilla->get($pla_id);

        $plati_id = $planilla_info['plati_id'];

        $configuracion_tipo_trabajador = $this->_CI->cuartacategoriaconfiguracion->get($plati_id);

        if(sizeof($configuracion_tipo_trabajador) > 0){
        
            $sql = " DELETE FROM planillas.planilla_empleado_concepto 
                     WHERE conc_id IN (?) AND 
                           plaemp_id IN (  SELECT plaemp_id 
                                           FROM planillas.planilla_empleados plaemp 
                                           WHERE plaemp.pla_id IN (?) )
                      ";

            $this->_CI->db->query($sql, array(  $configuracion_tipo_trabajador['cuarta_conc'], $pla_id));
         
            $sql = " DELETE FROM planillas.planilla_empleado_variable
                     WHERE vari_id IN (?) AND 
                           plaemp_id IN (  SELECT plaemp_id 
                                           FROM planillas.planilla_empleados plaemp 
                                           WHERE plaemp.pla_id IN (?) )
                      ";

            $this->_CI->db->query($sql, array(  $configuracion_tipo_trabajador['cuarta_vari'], $pla_id));
        }

    }

}