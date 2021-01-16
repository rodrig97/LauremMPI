<?PHP 

class TrabajadorQuintaFijo extends TrabajadorQuinta{

 	public function set_remuneracion(){
  
 		$sql = " SELECT SUM(empvar_value) as remuneracion_mensual 
 				 FROM planillas.variables vari  
                 INNER JOIN planillas.empleado_variable empvar ON vari.vari_id = empvar.vari_id AND empvar_estado = 1 AND indiv_id = ? 

                 WHERE vari_estado = 1 AND vari.plati_id = ? AND vari.vari_conc_afecto_cuarta_quinta = 1 
 			   ";
 		list($rs) = $this->db->query($sql, array($this->indiv_id, $this->plati_id ))->result_array();

 	 	$this->remuneracion_mensual	= ($rs['remuneracion_mensual'] == '' ? 0 : ($rs['remuneracion_mensual']*1));
 	} 

 	public function set_gratificacion(){

 		list($tipo_grati, $monto_julio, $monto_diciembre) = $this->configuracion_gratificacion;
 
 		if($tipo_grati == QUINTA_PROYECCION_GRATIFICACION_REM){ // Tipo grati contrato 
 			
 			$this->gratificacion_proyectada = $this->remuneracion_mensual;

 		}
 		else if($tipo_grati == QUINTA_PROYECCION_GRATIFICACION_AGUINALDO){ // Tipo aguinaldo 

 			$this->gratificacion_proyectada = ($this->mes < 7) ? $monto_julio : $monto_diciembre;
 	
 		}else{

 			$this->gratificacion_proyectada = 0;
 		}

 	}

}