<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class tipoCapacitController extends Controller {

    public function index(){
        $tipocontrato = DB::table('tipo_contrato')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $tipocontrato]);
    }

    public function crearTipoContrato($request){

        $itipocontid = DB::table('tipo_contrato')->insertGetId(
            [
                "ctipocontdsc" => $request->ctipocontdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'itipocontid'
        );
        return $itipocontid;
    }

    public function saveInfo(Request $request){
        
        $itipocontid = $this->crearTipoContrato($request);

        if ($itipocontid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}