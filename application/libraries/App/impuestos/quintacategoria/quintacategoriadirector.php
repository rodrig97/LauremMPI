<?PHP 

class QuintaCategoriaDirector{
 	
	public $QuintaCategoriaObject;

 	public function __construct($QuintaCategoriaObject){

 		$this->QuintaCategoriaObject = $QuintaCategoriaObject;

 	}

 	public function calcular(){	
 
 		$proyeccion = $this->QuintaCategoriaObject->proyeccion();
 		 
 		$remuneracion_neta_anual_proyectada = $this->QuintaCategoriaObject->remuneracion_neta_anual_proyectada($proyeccion);
 	 
 		if($remuneracion_neta_anual_proyectada > 0){
 			
 			$calculo_impuesto_anual = $this->QuintaCategoriaObject->calculo_impuesto_anual($remuneracion_neta_anual_proyectada);
 			$monto_retencion = $this->QuintaCategoriaObject->monto_retencion($calculo_impuesto_anual);
  		
  			return $monto_retencion;
 		}
  		else{
  			return 0;
  		}


 	}

}