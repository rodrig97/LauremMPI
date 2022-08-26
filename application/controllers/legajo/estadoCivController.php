<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class estadoCivController extends Controller {

    public function index(){
        $estCiv = DB::table('estado_civil')
            ->get();
            return response()->json(['data' => $estCiv]);
    }

    public function crearEstadoCivil($request){

        $iestcivilid = DB::table('estado_civil')->insertGetId(
            [
                "cestcivildsc" => $request->cestcivildsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'iestcivilid'
        );
        return $iestcivilid;
    }

    public function saveInfo(Request $request){
        
        $iestcivilid = $this->crearEstadoCivil($request);

        if ($iestcivilid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}