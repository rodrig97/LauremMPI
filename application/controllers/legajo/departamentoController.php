<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class departamentoController extends Controller {

    public function index(){
        $dept = DB::table('departamento')
            ->get();
            return response()->json(['data' => $dept]);
    }

    public function crearDepart($request){

        $idptoid = DB::table('departamento')->insertGetId(
            [
                "ipaisid" => $request->ipaisid,
                "cdptodsc" => $request->cdptodsc,
                "cdptoabrev" => $request->cdptoabrev,
                "cdptocode" => $request->cdptocode,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado,
            ],
            'idptoid'
        );
        return $idptoid;
    }

    public function saveInfo(Request $request){
        
        $idptoid = $this->crearDepart($request);

        if ($idptoid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}