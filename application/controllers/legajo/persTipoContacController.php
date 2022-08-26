<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class persTipoContacController extends Controller
{

    public function index()
    {
        $persTipoContac = DB::table('ficha.personas_tipo_contacto')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
        return response()->json(['data' => $persTipoContac]);
    }

    public function crearTipoContac($request)
    {
        $data = DB::table('ficha.personas_tipo_contacto as ptc')
            ->where([['ptc.ipersid', '=', $request->ipersid], ['ptc.itipocontacid', '=', $request->itipocontacid], ['ptc.bhabilitado', '=', 1]])
            ->get();
        if (count($data) > 0) {
            $iperstipocontacid = DB::table('ficha.personas_tipo_contacto as ptc')
                ->where([['ptc.ipersid', '=', $request->ipersid], ['ptc.itipocontacid', '=', $request->itipocontacid], ['ptc.bhabilitado', '=', 1]])
                ->update(
                    [
                        "ipersid" => $request->ipersid,
                        "itipocontacid" => $request->itipocontacid,
                        "cdescripcion" => $request->cdescripcion,
                        "iestado" => $request->iestado ?? 1,
                        "bhabilitado" => $request->bhabilitado ?? 1,
                        "cusuariosis" => $request->cusuariosis,
                        "dfechasis" => $request->dfechasis,
                        "cequiposis" => $request->cequiposis,
                        "cipsis" => $request->cipsis,
                        "copenusr" => $request->copenusr,
                        "cmacnicsis" => $request->cmacnicsis,
                    ]
                );
                $iperstipocontacid = $data[0]->iperstipocontacid; 
        } else {
            $iperstipocontacid = DB::table('ficha.personas_tipo_contacto')->insertGetId(
                [
                    "ipersid" => $request->ipersid,
                    "itipocontacid" => $request->itipocontacid,
                    "cdescripcion" => $request->cdescripcion,
                    "iestado" => $request->iestado ?? 1,
                    "bhabilitado" => $request->bhabilitado ?? 1,
                    "cusuariosis" => $request->cusuariosis,
                    "dfechasis" => $request->dfechasis,
                    "cequiposis" => $request->cequiposis,
                    "cipsis" => $request->cipsis,
                    "copenusr" => $request->copenusr,
                    "cmacnicsis" => $request->cmacnicsis,
                ],
                'iperstipocontacid'
            );
        }

        return $iperstipocontacid;
    }

    public function saveInfo(Request $request)
    {

        $iperstipocontacid = $this->crearTipoContac($request);

        if ($iperstipocontacid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}
