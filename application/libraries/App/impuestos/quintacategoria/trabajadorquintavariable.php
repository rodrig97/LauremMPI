<?PHP 

class TrabajadorQuintaVariable extends TrabajadorQuinta{
 	
 	public function set_remuneracion(){

 		// Obtener cuanto ha cobrado por mes y promediarlo
 		 
 		$sql = "SELECT * FROM (
	 				SELECT pla.pla_mes, SUM(plaec_value) as total_mes 
	 				FROM planillas.conceptos cc  
					INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.conc_id = cc.conc_id  
		            INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
		            INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ? AND pla_mes_int < ?
		            INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id = ? 

	                WHERE cc.conc_estado = 1 AND cc.conc_tipo = ".TIPOCONCEPTO_INGRESO."  AND cc.conc_afecto_quinta = ".QUINTA_TIPO_CONCEPTO_PROYECTABLE."

					GROUP BY pla.pla_mes 
					ORDER BY pla.pla_mes
				) as data 
				WHERE data.total_mes > 0
				ORDER BY data.pla_mes; 
 			  ";

 		$rs = $this->db->query($sql, array($this->indiv_id, $this->anio, $this->mes, ESTADOPLANILLA_FINALIZADO ))->result_array();

 		$total = 0;
 		$remuneraciones  = array();

 		foreach ($rs as $reg) {
 		
 			 $total+=$reg['total_mes'];
 			 array_push($remuneraciones, $reg['total_mes']);
 		
 		}
 		
 	    $remuneracionesMasRecientes = array_reverse($remuneraciones);
 
 		if(sizeof($remuneracionesMasRecientes) >= 3){

 			$promedio = (($remuneracionesMasRecientes[0] * 1) + ($remuneracionesMasRecientes[1]*1) + ($remuneracionesMasRecientes[2]*1)) / 3;

 		}else{

 			if(sizeof($remuneracionesMasRecientes) > 0){

				$promedio = array_sum($remuneracionesMasRecientes) / sizeof($remuneracionesMasRecientes); 			
 				
 			}else{

 				$promedio = 0;
 			}
			
 		}

 		// if(sizeof($rs) > 0 ){
 		// 	$promedio = $total/sizeof($rs);
 		
 		// } else{
 		// 	$promedio = 0;
 		// }
 
 		$this->remuneracion_mensual = $promedio;

 	}

 	public function set_gratificacion(){
 	
 		list($tipo_grati, $monto_julio, $monto_diciembre) = $this->configuracion_gratificacion;
 		
 		if($tipo_grati == 1){ // Tipo grati contrato 
 			
 			$this->gratificacion_proyectada = $this->remuneracion_mensual;

 		}
 		else if($tipo_grati == 2){ // Tipo aguinaldo 

 			$this->gratificacion_proyectada = ($this->mes < 7) ? $monto_julio : $monto_diciembre;
 		
 		}else{

 			$this->gratificacion_proyectada = 0;
 		}


 	}
 
} 