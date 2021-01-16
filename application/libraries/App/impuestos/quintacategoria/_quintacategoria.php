<?PHP 

// Es importante llevar un control de cuanto se va reteniendo en el mes

// Que sepa programar no tiene S./ 800.00 + Cuenta en Treehouse o Platzi + Cuenta de Github + Cuenta de Trello 
// Programador con experiencia en proyectos personales, pueden ser desde trabajos universitarios
// o cosas que haya hecho para si mismo, en general, Programador con codigo que mostrar S./1200.00

// Programador con experiencia que haya trabajado en proyectos de software S./3000 - S./3800 


// 3 meses por recibo, luego incorporación a Planilla.
// Advertencia: No estamos buscando un trabajador, estamos en la busqueda 


class QuintaCategoria{
 
	public function __construct($trabajador){

	}
 
	public function get_remuneracion_mensual_proyeccion(){

	}

	public function get_gratificaciones(){

	}

	// Tomamos los ingresos del documento puede ser una planilla de rem o vacaiones o grati ETC
	public function get_ingresos_documento(){

	}

	// PLANILLA + GRATIFICACION + VACACIONES + X...
	public function get_ingresos_anteriores(){

	}

	public function get_ingresos_ejercicio(){

	}


}
  
class QuintaCategoriaBuilder{
 	
 	private $QuintaCategoria;

 	public function __construct($QuintaCategoriaObj){

 		$this->QuintaCategoria = $QuintaCategoriaObj;
 	}

	public function proyeccion(){

	}
}

class QuintaCategoriaPlanilla{

	private $planilla_id;

	private $trabajador_id;

	public function __construct(){

	}

	public function get_ingresos_documento(){

	}

}