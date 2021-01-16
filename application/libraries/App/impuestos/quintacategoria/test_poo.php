<?PHP 


class Planilla{

}

class Trabajador{

}

class TrabajadorPlanillaFactory(){

}

class HojaAsistencia{}

TrabajadorPlanilla = new TrabajadorPlanillaFactory(Trabajador);

Planilla.agregar_trabajador(TrabajadorPlanilla);


CollectionTrabajadorPlanilla = new Collection(TrabajadorPlanilla);

CollectionTrabajadorPlanilla.on('delete_trbajador')