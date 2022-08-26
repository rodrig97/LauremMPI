<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class tipoEstudiosController extends Controller {

    public function index(){
        $tiposeg = DB::table('tipo_seguro')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $tiposeg]);
    }

    public function crearTipoSeguro($request){

        $itiposegid = DB::table('tipo_estudios')->insertGetId(
            [
                "ctiposegdsc" => $request->ctiposegdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'itiposegid'
        );
        return $itiposegid;
    }

    public function saveInfo(Request $request){
        
        $itiposegid = $this->crearTipoSeguro($request);

        if ($itiposegid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}