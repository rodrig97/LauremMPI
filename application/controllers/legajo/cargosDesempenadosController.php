<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class cargosDesempenadosController extends Controller
{

    public function index()
    {
        $icargos_desempenadosid = DB::table('cargos_desempenados')
            ->get();
        return response()->json(['data' => $icargos_desempenadosid]);
    }

    public function crearCargosDesemp($request)
    {

        $icargos_desempenadosid = DB::table('cargos_desempenados')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "ccargo_desempenado" => $request->ccargo_desempenado,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado,
                "cusuariosis" => $request->cusuariosis,
                "dfechasis" => $request->dfechasis,
                "cequiposis" => $request->cequiposis, 
                "cipsis" => $request->cipsis,
                "copenusr" => $request->copenusr,
                "cmacnicsis" => $request->cmacnicsis,
            ],
            'icargos_desempenadosid'
        );
        return $icargos_desempenadosid;
    }

    public function saveInfo(Request $request)
    {

        $icargos_desempenadosid = $this->crearCargosDesemp($request);

        if ($icargos_desempenadosid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

}
