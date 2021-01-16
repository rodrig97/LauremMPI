<?PHP 

class TrabajadorQuintaPlanilla extends TrabajadorQuinta{

 	public function set_remuneracion($plaemp_id = 0){

 		$sql = "SELECT SUM(CASE WHEN plaec.conc_tipo = 1 THEN plaec_value ELSE 0 END) as ingresos_boleta ,
 					   SUM(CASE WHEN plaec.conc_tipo = 2 THEN plaec_value ELSE 0 END) as descuentos_boleta 
				FROM planillas.planilla_empleado_concepto plaec
 	 			INNER JOIN planillas.conceptos cc  ON plaec.conc_id = cc.conc_id AND cc.conc_estado = 1  AND cc.conc_afecto_quinta = ".QUINTA_TIPO_CONCEPTO_PROYECTABLE." 
	            WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaemp_id = ?  
 			  ";
 

 		list($rs) = $this->db->query($sql, array($plaemp_id))->result_array();

 		$ingresos = ($rs['ingresos_boleta'] != '' ? $rs['ingresos_boleta'] : 0);
 		$descuentos = ($rs['descuentos_boleta'] != '' ? $rs['descuentos_boleta'] : 0);

 		$remuneracion_mensual = $ingresos - $descuentos;
 		if($remuneracion_mensual < 0) $remuneracion_mensual = 0;

 		$this->remuneracion_mensual = $remuneracion_mensual;

 	} 

 	public function set_gratificacion(){

 		list($tipo_grati, $monto_julio, $monto_diciembre) = $this->configuracion_gratificacion;
 
 		if($tipo_grati == 1){ // Tipo grati contrato 

			$sql = " SELECT SUM(empvar_value) as remuneracion_gratificacion 
					 FROM planillas.variables vari  
	                 INNER JOIN planillas.empleado_variable empvar ON vari.vari_id = empvar.vari_id AND empvar_estado = 1 AND indiv_id = ? 

	                 WHERE vari_estado = 1 AND vari.plati_id = ? AND vari.vari_conc_afecto_cuarta_quinta = 1 
				   ";

			list($rs) = $this->db->query($sql, array($this->indiv_id, $this->plati_id ) )->result_array();

		 	$this->gratificacion_proyectada = ($rs['remuneracion_gratificacion'] == '' ? 0 : ($rs['remuneracion_gratificacion']*1));


 			// $this->gratificacion_proyectada = $this->remuneracion_mensual;

 		}
 		else if($tipo_grati == 2){ // Tipo aguinaldo 

 			$this->gratificacion_proyectada = ($this->mes < 7) ? $monto_julio : $monto_diciembre;
 	
 		}else{

 			$this->gratificacion_proyectada = 0;
 		}

 	}

}