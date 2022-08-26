<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class distritoController extends Controller {

    public function index(){
        $distrito = DB::table('distrito')
            ->get();
            return response()->json(['data' => $distrito]);
    }

    public function crearDistrito($request){

        $idistid = DB::table('distrito')->insertGetId(
            [
                "iprovid" => $request->iprovid,
                "cdistdsc" => $request->cdistdsc,
                "cdistabrev" => $request->cdistabrev,
                "cdistcode" => $request->cdistcode,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'idistid'
        );
        return $idistid;
    }

    public function saveInfo(Request $request){
        
        $idistid = $this->crearDistrito($request);

        if ($idistid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}