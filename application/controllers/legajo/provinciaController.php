<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class provinciaController extends Controller {

    public function index(){
        $provincia = DB::table('provincia')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $provincia]);
    }

    public function crearCrearProvincia($request){

        $iprovid = DB::table('provincia')->insertGetId(
            [
                "idptoid" => $request->idptoid,
                "cprovdsc" => $request->cprovdsc,
                "cprovabrev" => $request->cprovabrev,
                "cprovcode" => $request->cprovcode,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado,
            ],
            'iprovid'
        );
        return $iprovid;
    }

    public function saveInfo(Request $request){
        
        $iprovid = $this->crearCrearProvincia($request);

        if ($iprovid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}