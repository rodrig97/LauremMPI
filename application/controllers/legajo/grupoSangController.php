<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class grupoSangController extends Controller {

    public function index(){
        $grupoSang = DB::table('grupo_sanguineo')
            ->get();
            return response()->json(['data' => $grupoSang]);
    }

    public function creargrupoSang($request){

        $igruposangid = DB::table('grupo_sanguineo')->insertGetId(
            [
                "cgruposangdsc" => $request->cgruposangdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'igruposangid'
        );
        return $igruposangid;
    }

    public function saveInfo(Request $request){
        
        $igruposangid = $this->creargrupoSang($request);

        if ($igruposangid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}