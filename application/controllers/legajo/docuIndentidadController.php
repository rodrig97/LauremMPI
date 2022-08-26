<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class docuIndentidadController extends Controller
{

    public function index()
    {
        $docIdent = DB::table('ficha.documento_identidad')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
        return response()->json(['data' => $docIdent]);
    }

    public function crearDocIndentidad($request)
    {
        $data = DB::table('ficha.documento_identidad as di')
            ->where([['di.ipersid', '=', $request->ipersid], ['di.itipodocidentid', '=', $request->itipodocidentid], ['di.bhabilitado', '=', 1]])
            ->get();

        if (count($data) > 0) {
            $idocidentid = DB::table('ficha.documento_identidad as di')
                ->where([['di.ipersid', '=', $request->ipersid], ['di.itipodocidentid', '=', $request->itipodocidentid], ['di.bhabilitado', '=', 1]])
                ->update(
                    [
                        "ipersid" => $request->ipersid,
                        "itipodocidentid" => $request->itipodocidentid,
                        "cnrodocident" => $request->cnrodocident,
                        "codDNI" => $request->codDNI,
                        "iestado" => $request->iestado ?? 1,
                        "bhabilitado" => $request->bhabilitado ?? 1,
                        "cusuariosis" => $request->cusuariosis,
                        "dfechasis" => $request->dfechasis,
                        "cipsis" => $request->cipsis,
                        "copenusr" => $request->copenusr,
                        "cmacnicsis" => $request->cmacnicsis,
                    ],
                );
            $idocidentid = $data[0]->idocidentid;
        } else {
            $idocidentid = DB::table('ficha.documento_identidad')->insertGetId(
                [
                    "ipersid" => $request->ipersid,
                    "itipodocidentid" => $request->itipodocidentid,
                    "cnrodocident" => $request->cnrodocident,
                    "codDNI" => $request->codDNI,
                    "iestado" => $request->iestado ?? 1,
                    "bhabilitado" => $request->bhabilitado ?? 1,
                    "cusuariosis" => $request->cusuariosis,
                    "dfechasis" => $request->dfechasis,
                    "cipsis" => $request->cipsis,
                    "copenusr" => $request->copenusr,
                    "cmacnicsis" => $request->cmacnicsis,
                ],
                'idocidentid'
            );
        }

        return $idocidentid;
    }

    public function saveInfo(Request $request)
    {

        $idocidentid = $this->crearDocIndentidad($request);

        if ($idocidentid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
