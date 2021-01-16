<?php

class TrabajadorQuintaFactory{

 	private $params;
 	private $db;

	public function __construct($db, $params, $anio, $mes){	
 
		$this->db = $db;
		$this->params = $params;
		$this->anio = $anio;
		$this->mes = $mes;

	}

	public function factory(){ 


		if( $this->params['remuneracion_por_promedio'] == true ){
		
			$objetoTrabajadorQuinta = new TrabajadorQuintaVariable($this->db, $this->anio,  $this->mes, $this->params['indiv_id'], $this->params['plati_id'], $this->params['persla_periodopago'], $this->params['persla_montocontrato'], $this->params['configuracion_gratificacion'] );

		}else{

			$objetoTrabajadorQuinta = new TrabajadorQuintaPlanilla($this->db, $this->anio, $this->mes, $this->params['indiv_id'], $this->params['plati_id'], $this->params['persla_periodopago'], $this->params['persla_montocontrato'], $this->params['configuracion_gratificacion'] );
		}
		
		$objetoTrabajadorQuinta->set_constancias_montos();
		$objetoTrabajadorQuinta->set_remuneracion($this->params['detalle_id']);
		$objetoTrabajadorQuinta->set_gratificacion();
		$objetoTrabajadorQuinta->set_ingresos_historicos();
		$objetoTrabajadorQuinta->set_ingresos_mes($this->params['detalle_id']);
		$objetoTrabajadorQuinta->set_ingresos_almes();
		$objetoTrabajadorQuinta->set_retenciones_anteriores();
		$objetoTrabajadorQuinta->set_retencion_mes();
		

		return $objetoTrabajadorQuinta;
	}

}