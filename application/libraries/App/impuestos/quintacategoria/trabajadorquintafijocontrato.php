<?PHP 

class TrabajadorQuintaFijoContrato extends TrabajadorQuinta{

 	public function set_remuneracion(){

 		$monto_contrato = $this->monto_contrato;
 		$monto_por_mes = 0;

 		if($this->periodo_de_pago == PERIODOPAGO_DIARIO){
 			 $monto_por_mes = $monto_contrato * 30;
 		}
 		else if($this->periodo_de_pago == PERIODOPAGO_SEMANA){
 			$monto_por_mes = $monto_contrato * 4;
 		}
 		else if($this->periodo_de_pago == PERIODOPAGO_QUINCENA){
 			$monto_por_mes = $monto_contrato * 2;
 		}
 		else if($this->periodo_de_pago == PERIODOPAGO_MENSUAL){
 			$monto_por_mes = $monto_contrato;
 		}
 		else
 		{
 			$monto_por_mes = 0;
 		}

 	 	$this->remuneracion_mensual	= $monto_por_mes;
 
 	} 

 	public function set_gratificacion(){

 		$this->gratificacion_proyectada = $this->remuneracion_mensual;
 	}

}