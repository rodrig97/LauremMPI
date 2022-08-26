<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class centroEstudiosController extends Controller
{

    public function index()
    {
        $icentestudid = DB::table('centro_estudios')
            ->get();
        return response()->json(['data' => $icentestudid]);
    }

    public function crearCentroEstudios($request)
    {

        $icentestudid = DB::table('centro_estudios')->insertGetId(
            [
                "ccentestuddsc" => $request->ccentestuddsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado,
                "cusuariosis" => $request->cusuariosis,
                "dfechasis" => $request->dfechasis,
                "cequiposis" => $request->cequiposis,
                "cipsis" => $request->cipsis,
                "copenusr" => $request->copenusr,
                "cmacnicsis" => $request->cmacnicsis
            ],
            'icentestudid'
        );
        return $icentestudid;
    }

    public function saveInfo(Request $request)
    {

        $icentestudid = $this->crearCentroEstudios($request);

        if ($icentestudid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

}
