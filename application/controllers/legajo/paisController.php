<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class paisController extends Controller {

    public function index(){
        $pais = DB::table('pais')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $pais]);
    }

    public function crearPais($request){

        $ipaisid = DB::table('pais')->insertGetId(
            [
                "cpaisdsc" => $request->cpaisdsc,
                "cpaisabrev" => $request->cpaisabrev,
                "cpaiscode" => $request->cpaiscode,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'ipaisid'
        );
        return $ipaisid;
    }

    public function saveInfo(Request $request){
        
        $ipaisid = $this->crearPais($request);

        if ($ipaisid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}