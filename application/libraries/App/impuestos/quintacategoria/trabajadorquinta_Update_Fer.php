<?PHP 

class TrabajadorQuinta{

	protected $db;
	public $indiv_id;
	public $periodo_de_pago; 
	public $monto_contrato;
	public $plati_id;
	public $anio;
	public $mes;
 
	public $remuneracion_mensual;
	public $gratificacion_proyectada;
	public $ingresos_historicos;

	public $registro_retencion_mes;
	public $retencion_mes;
	public $ingresos_almes;
	public $retenciones_anteriores;
	public $periodo_retenciones;
	public $configuracion_gratificacion;
	public $_CI;

	public function __construct($db, $anio, $mes, $indiv_id, $plati_id, $periodo_de_pago, $monto_contrato, $configuracion_gratificacion ){

		$this->db = $db;
		$this->indiv_id = $indiv_id;
		$this->plati_id = $plati_id;
		$this->periodo_de_pago = $periodo_de_pago;
		$this->monto_contrato = $monto_contrato;
		$this->configuracion_gratificacion = $configuracion_gratificacion;
		$this->anio = $anio;
		$this->mes = $mes;

		$this->remuneracion_mensual = 0;
		$this->ingresos_historicos = 0;
		$this->retencion_mes = 0;
		$this->ingresos_del_mes = 0;
		$this->registro_retencion_mes = array();
		$this->retenciones_anteriores = array();
		$this->ingresos_almes = 0;
   
	}
 
 
 	public function set_ingresos_historicos(){

 		$sql = "SELECT SUM(plaec_value) as total_ingresos 
 	 		 	FROM planillas.conceptos cc 
				INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.conc_id = cc.conc_id  
	            INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
	            INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ? AND pla_mes_int < ?
	            INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id = ? 

                WHERE cc.conc_estado = 1 AND cc.conc_afecto_quinta = 1
 
 			  ";

 		list($rs) = $this->db->query($sql, array($this->indiv_id, $this->anio, $this->mes, ESTADOPLANILLA_FINALIZADO ))->result_array();

 		$this->ingresos_historicos = ($rs['total_ingresos'] != '' ? $rs['total_ingresos'] : 0);

 	}


 	public function set_ingresos_mes($detalle_planilla = 0){

 		$sql = "SELECT SUM(plaec_value) as total_ingresos 
 	 			FROM planillas.conceptos cc 
				INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.conc_id = cc.conc_id  
	            INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
	            INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = ? AND pla_mes_int = ?
	            INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id >= ? 

                WHERE cc.conc_estado = 1 AND cc.conc_afecto_quinta = 1 
				";

				if($detalle_planilla != 0 && $detalle_planilla != ''){

					$sql.=" AND plaemp.plaemp_id <= ".$detalle_planilla;
				}

		$sql.=" 
				GROUP BY pla.pla_mes 
 			  ";

 		list($rs) = $this->db->query($sql, array($this->indiv_id, $this->anio, $this->mes, ESTADOPLANILLA_PROCESADA ))->result_array();

 		$this->ingresos_del_mes = ($rs['total_ingresos'] != '' ? $rs['total_ingresos'] : 0);

 	}

 	public function set_ingresos_almes(){

 		$this->ingresos_almes = ($this->ingresos_historicos + $this->ingresos_del_mes);
 	}

 	public function set_retencion_mes(){
 

 		$sql = "SELECT anio, mes_id, SUM(qcr_retencion) as retencion_mes
 				FROM planillas.quintacategoria_retenciones 
 				WHERE qcr_estado = 1 AND anio = ? AND mes_id = ? AND indiv_id = ?
 				GROUP BY anio, mes_id ";

 	 	list($rs) = $this->db->query($sql, array($this->anio, $this->mes, $this->indiv_id))->result_array();
 	 
 	 	$this->retencion_mes = ($rs['retencion_mes'] == '' ? 0 : $rs['retencion_mes']); 

 	}

 	public function set_retenciones_anteriores(){


 		if( $this->mes >= 1 && $this->mes <=3 ){
 			
 			$periodo = '0';
 			$periodo_retenciones = '';
 		}
 		else if( $this->mes == 4 ){
 			
 			$periodo = '1,3';
 			$periodo_retenciones  = 'Enero -  Marzo';	
 		}
 		else if( $this->mes >= 5 && $this->mes <= 7 ){
 		 	$periodo = '1,4';
 		 	$periodo_retenciones =  'Enero - Abril';
 		}
 		else if( $this->mes == 8 ){
 			$periodo = '1,7';
 			$periodo_retenciones = 'Enero - Julio';
 		}
 		else if( $this->mes >= 9 && $this->mes <= 11 ){
 			$periodo = '1,8';
 			$periodo_retenciones = 'Enero - Agosto';
 		}
 		else if( $this->mes == 12 ){
 			$periodo = '1,11';
 			$periodo_retenciones = 'Enero - Noviembre';
 		}



 		$sql = "SELECT anio, SUM(qcr_retencion) as retenciones_anteriores
 				FROM planillas.quintacategoria_retenciones 
 				WHERE qcr_estado = 1 AND anio = ? AND mes_id in (".$periodo.") AND indiv_id = ?
 				GROUP BY anio, mes_id ";

 	 	list($rs) = $this->db->query($sql, array( $this->anio, $this->indiv_id))->result_array();

 	 	// $meses = array();

 	 	// foreach ($rs as $reg) {

 	 	// 	$meses[trim($reg['mes_id'])] = ($reg['retenciones_anteriores']*1);
 	 	// }
 
 	 	// for($i=1; $i<=12; $i++){
 	 	// 	if($meses[trim($i)] == '') $meses[trim($i)] = 0;
 	 	// }

 	 	//$this->retenciones_anteriores = $reg;
 		$this->retenciones_anteriores = ($rs['retenciones_anteriores'] == '' ? 0 : $rs['retenciones_anteriores']); 
 		$this->periodo_retenciones = $periodo_retenciones;
 	
 	}

 	public function save_retencion(){

 		
 		 
 	}

}