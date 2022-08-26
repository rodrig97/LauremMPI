<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class personasController extends Controller {
    
    public function index()
    {
        //$tramites = personas::all();
        $personas = DB::table('tipo_seguro')
            ->get();
        return response()->json(['data' => $personas]);
    }

    public function createTipoSeguro($request){

        // $date = Carbon::now()->locale("es_ES");
        // $fecha = $date->format('Y-m-d H:i:s');

        $itiposegid = DB::table('tipo_seguro')->insertGetId(
            [
                "ctiposegdsc" => $request->ctiposegdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'itiposegid'
        );
        //$this->createEstadoCiv($request, $per_id);
        return $itiposegid;
    }

    

    public function saveInfo(Request $request){
        
        $itiposegid = $this->createTipoSeguro($request);

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