<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class tipoCapacitController extends Controller {

    public function index(){
        $tipocontac = DB::table('tipo_contacto')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $tipocontac]);
    }

    public function crearTipoContacto($request){

        $itipocontacid = DB::table('tipo_contacto')->insertGetId(
            [
                "ctipocapacdsc" => $request->ctipocapacdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'itipocontacid'
        );
        return $itipocontacid;
    }

    public function saveInfo(Request $request){
        
        $itipocontacid = $this->crearTipoContacto($request);

        if ($itipocontacid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}