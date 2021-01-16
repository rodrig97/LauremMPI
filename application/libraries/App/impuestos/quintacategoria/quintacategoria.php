<?PHP 

class QuintaCategoria{

	public $trabajadorQuinta;
	public $mes;

	private $factor_descuento_neta_anual_proyectada = 7;
	private $valor_uit;
	private $monto_fijo_tope;
	public  $calculo_factor = 0;
	public  $gratificaciones_proyectadas  = 0;
	public  $remuneracion_neta_anual_proyectada = 0;
 	
 	public function __construct($trabajadorQuinta, $uit_valor, $monto_fijo_tope){ 

 		$this->trabajadorQuinta = $trabajadorQuinta;
 		$this->monto_fijo_tope = $monto_fijo_tope;
 		$this->valor_uit = $uit_valor;

 		$this->mes = $this->trabajadorQuinta->mes;

 		$this->total_proyeccion = 0;
 		$this->remuneracion_neta_anual_proyectada = 0;
 		$this->impuesto_anual = 0;
 	}	

 	public function proyeccion(){

 		$meses = 12;
 		$mes_factor = $meses - $this->mes; 

 		$remuneraciones_proyectadas = $this->trabajadorQuinta->remuneracion_mensual * $mes_factor;
 	
 		$this->trabajadorQuinta->remuneraciones_proyectadas = $remuneraciones_proyectadas;

 		$ingresos_del_mes = $this->trabajadorQuinta->ingresos_del_mes;
 		
 		// Si es antes de julio se proyectan las dos gratificaciones  
 		$gratificaciones_proyectadas = 0;
 		
 		if( $this->mes < 7){
 		
 			$gratificaciones_proyectadas = $this->trabajadorQuinta->gratificacion_proyectada * 2;
 		
 		}
 		else if( $this->mes < 12 ){

 			$gratificaciones_proyectadas = $this->trabajadorQuinta->gratificacion_proyectada; 
 		}


 		// Si es diciembre no se proyecta ninguna grati 
 		$ingresos_imponibles_anteriores = $this->trabajadorQuinta->ingresos_historicos;

 		$total_proyeccion =  $this->trabajadorQuinta->constancias_ingresos +  $ingresos_imponibles_anteriores + $ingresos_del_mes  + $remuneraciones_proyectadas + $gratificaciones_proyectadas;
 	 	
 	 	$this->gratificaciones_proyectadas = $gratificaciones_proyectadas;
 		$this->total_proyeccion = $total_proyeccion;

 	 	return $total_proyeccion;
 	}

 	public function remuneracion_neta_anual_proyectada($total_proyeccion){
 
 		$remuneracion_neta_anual_proyectada = $total_proyeccion - $this->monto_fijo_tope;
 		
 		$this->remuneracion_neta_anual_proyectada = $remuneracion_neta_anual_proyectada; 

 		return $remuneracion_neta_anual_proyectada;
 	}

 	public function calculo_impuesto_anual($remuneracion_neta_anual_proyectada){

 		// Hasta 5 UIT 	8%
 		// Más de 5 UIT  hasta 20 UIT 14%
 		// Más de 20 UIT hasta 35 UIT 	17%
 		// Más de 35 UIT hasta 45 UIT 	20%
 		// Más de 45 UIT 	30%

 		$res = 0;

 		$techo1 = 5 * $this->valor_uit;
 		$techo2 = 20 * $this->valor_uit;
 		$techo3 = 35 * $this->valor_uit;
 		$techo4 = 45 * $this->valor_uit;
 		 
 		$impuesto_anual = 0;
   
 		if($remuneracion_neta_anual_proyectada > $techo1){

 			$impuesto_anual+= ($techo1 * 0.08); 

 		}else{ 

 			$impuesto_anual+= $remuneracion_neta_anual_proyectada * 0.08; 
 		}
 		  
 		
 		if( $remuneracion_neta_anual_proyectada > $techo1 && $remuneracion_neta_anual_proyectada <= $techo2  ){ 
 			

 			$aplicar = $remuneracion_neta_anual_proyectada - $techo1;

 			if($aplicar > $techo2){

 				$impuesto_anual+= ($techo2 * 0.14);
 			
 			}else{ 

 				$impuesto_anual+= $aplicar * 0.14; 
 			}

 		}

 		if( $remuneracion_neta_anual_proyectada > $techo2 && $remuneracion_neta_anual_proyectada <= $techo3  ){ 


 			$aplicar = $remuneracion_neta_anual_proyectada - $techo2;

 			if($aplicar > $techo3){

 				$impuesto_anual+= ($techo3 * 0.17); 

 			}else{ 

 				$impuesto_anual+= $aplicar * 0.17; 
 			}

 		}
 		if( $remuneracion_neta_anual_proyectada > $techo3 && $remuneracion_neta_anual_proyectada <= $techo4  ){ 


 			$aplicar = $remuneracion_neta_anual_proyectada - $techo3;

 			if($aplicar > $techo4){

 				$impuesto_anual+= ($techo4 * 0.20); 

 			}else{ 

 				$impuesto_anual+= $aplicar * 0.20; 
 			}
 		}   	
 		
 		if($remuneracion_neta_anual_proyectada > $techo4){

 			$aplicar = $remuneracion_neta_anual_proyectada - $techo4;

 			$impuesto_anual+= $aplicar * 0.30; 
 		}	

 		$this->impuesto_anual = $impuesto_anual;

 		return $impuesto_anual;
 	}

 	public function monto_retencion($impuesto_anual){
 		/*
	En los meses de enero a marzo, el impuesto anual se divide entre doce.
	En el mes de abril, al impuesto anual se le deducen las retenciones efectuadas de enero a marzo. El resultado se divide entre 9.
	En los meses de mayo a julio, al impuesto anual se le deducen las retenciones efectuadas en los meses de enero a abril. El resultado se divide entre 8.
	En agosto, al impuesto anual se le deducen las retenciones efectuadas en los meses de enero a julio. El resultado se divide entre 5.
	En los meses de septiembre a noviembre, al impuesto anual se le deducen las retenciones efectuadas en los meses de enero a agosto. El resultado se divide entre 4.
	En diciembre, con motivo de la regularización anual, al impuesto anual se le deducirá las retenciones efectuadas en los meses de enero a noviembre del mismo ejercicio.
 		*/

		 $cociente = 1;

		  
		 if( $this->mes >= 1 && $this->mes <=3 ){
		 	$cociente = 12;
		 }
		 else if( $this->mes == 4 ){
		 	$cociente = 9;
		 }
		 else if( $this->mes >= 5 && $this->mes <= 7 ){
		 	$cociente = 8;
		 }
		 else if( $this->mes == 8 ){
		 	$cociente = 5;
		 }
		 else if( $this->mes >= 9 && $this->mes <= 11 ){
		 	$cociente = 4;
		 }
		 else if( $this->mes == 12 ){
		 	$cociente = 1;
		 }


		 if(  ($this->trabajadorQuinta->constancias_descuentos + $this->trabajadorQuinta->retenciones_anteriores) > $impuesto_anual ){
		 	return 0;
		 }

		 $this->calculo_factor = $cociente;
		
		 $impuesto_calcular = $impuesto_anual - ($this->trabajadorQuinta->constancias_descuentos + $this->trabajadorQuinta->retenciones_anteriores);

		 $monto_retencion = $impuesto_calcular / $cociente;

		 return round($monto_retencion,2);
 	}

}