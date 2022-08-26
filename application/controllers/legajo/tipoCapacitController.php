<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class tipoCapacitController extends Controller {

    public function index(){
        $tipocapac = DB::table('tipo_capacitacion')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $tipocapac]);
    }

    public function crearTipoCapacitacion($request){

        $itipocapacid = DB::table('tipo_capacitacion')->insertGetId(
            [
                "ctipocapacdsc" => $request->ctipocapacdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'itipocapacid'
        );
        return $itipocapacid;
    }

    public function saveInfo(Request $request){
        
        $itipocapacid = $this->crearTipoCapacitacion($request);

        if ($itipocapacid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}