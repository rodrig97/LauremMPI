<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class fichaContratoController extends Controller {

    public function index(){
        $fichcont = DB::table('ficha_contrato')
            //->leftJoin('modalidad_contrato', 'ficha_contrato.ifichcontid', '=', 'modalidad_contrato.imodalcontid')
            ->get();
            return response()->json(['data' => $fichcont]);
    }

    public function crearFichaContrato($request){

        $ifichcontid = DB::table('ficha_contrato')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "imodalcontid" => $request->imodalcontid,
                "itipocontid" => $request->itipocontid,
                "cnrocont" => $request->cnrocont,
                "cnrores" => $request->cnrores,
                "dfechaing" => $request->dfechaing,
                "dfechanombr" => $request->dfechanombr,
                "dfechares" => $request->dfechares,
                "bfamiliar" => $request->bfamiliar,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado,
                "cusuariosis" => $request->cusuariosis,
                "dfechasis" => $request->dfechasis,
                "cequiposis" => $request->cequiposis,
                "cipsis" => $request->cipsis,
                "copenusr" => $request->copenusr,
                "cmacnicsis" => $request->cmacnicsis,
            ],
            'ifichcontid'
        );
        return $ifichcontid;
    }

    public function saveInfo(Request $request){
        
        $ifichcontid = $this->crearFichaContrato($request);

        if ($ifichcontid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}