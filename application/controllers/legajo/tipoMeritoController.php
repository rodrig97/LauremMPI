<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class TipoMeritoController extends Controller
{

    public function index()
    {
        $itipo_meritoid = DB::table('tipo_merito')
            ->get();
        return response()->json(['data' => $itipo_meritoid]);
    }

    public function crearTipoMerito($request)
    {

        $itipo_meritoid = DB::table('tipo_merito')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "ctipo_merito"  => $request->ctipo_merito,
                "iestado"  => $request->iestado,
                "bhabilitado"  => $request->bhabilitado,
                "cusuariosis"  => $request->cusuariosis,
                "dfechasis"  => $request->dfechasis,
                "cequiposis"  => $request->cequiposis, 
                "cipsis"  => $request->cipsis,
                "copenusr"  => $request->copenusr,
                "cmacnicsis"  => $request->cmacnicsis,
            ],
            'itipo_meritoid'
        );
        return $itipo_meritoid;
    }

    public function saveInfo(Request $request)
    {

        $itipo_meritoid = $this->crearTipoMerito($request);

        if ($itipo_meritoid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

}
