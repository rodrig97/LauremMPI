<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class detalleFamController extends Controller {

    public function index(){
        $detalleFam = DB::table('detalle_familia')
            ->get();
            return response()->json(['data' => $detalleFam]);
    }

    public function crearDepart($request){

        $idetfamid = DB::table('detalle_familia')->insertGetId(
            [
                "ifichcontid" => $request->ifichcontid,
                "ipersid" => $request->ipersid,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado,
                "cusuariosis" => $request->cusuariosis,
                "dfechasis" => $request->dfechasis,
                "cequiposis" => $request->cequiposis,
                "cipsis" => $request->cipsis,
                "copenusr" => $request->copenusr,
                "cmacnicsis" => $request->cmacnicsis
            ],
            'idetfamid'
        );
        return $idetfamid;
    }

    public function saveInfo(Request $request){
        
        $idetfamid = $this->crearDepart($request);

        if ($idetfamid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}