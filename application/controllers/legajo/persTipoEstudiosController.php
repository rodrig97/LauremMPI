<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class persTipoEstudiosController extends Controller
{

    public function index()
    {
        $persTipoContac = DB::table('personas_tipo_contacto')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
        return response()->json(['data' => $persTipoContac]);
    }

    public function crearTipoEstudios($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');

        $iperstipoestudid = DB::table('ficha.personas_tipo_estudios')->insertGetId(
            [
                "itipoestudid" => $request->itipoestudid,
                "ipersid" => $request->ipersid,
                "icentestudid" => $request->icentestudid,
                "ipaisid" => $request->ipaisid,
                "idptoid" => $request->idptoid,
                "iprovid" => $request->iprovid,
                "idistid" => $request->idistid,
                "dfechainicio" => $request->dfechainicio,
                "dfechatermino" => $request->dfechatermino,
                "iestado" => $request->iestado ?? 1,
                "bhabilitado" => $request->bhabilitado ?? 1,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'N',
                "cmacnicsis" => $request->cmacnicsis ?? NULL,
                "ccolegiaturanro" =>$request->ccolegiaturanro ?? NULL,
                "clugar" =>$request->clugar ?? NULL,
                "cgrado_titulo" =>$request->cgrado_titulo ?? NULL,
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ],
            'iperstipoestudid'
        );
        return $iperstipoestudid;
    }

    public function updateTipoEstudios($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $iperstipoestudid = DB::table('ficha.personas_tipo_estudios')
            ->where('iperstipoestudid', $request->iperstipoestudid)
            ->update([
                "itipoestudid" => $request->itipoestudid,
                "ipersid" => $request->ipersid,
                "icentestudid" => $request->icentestudid,
                "ipaisid" => $request->ipaisid,
                "idptoid" => $request->idptoid,
                "iprovid" => $request->iprovid,
                "idistid" => $request->idistid,
                "dfechainicio" => $request->dfechainicio,
                "dfechatermino" => $request->dfechatermino,
                "iestado" => $request->iestado ?? 1,
                "bhabilitado" => $request->bhabilitado ?? 1,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'E',
                "cmacnicsis" => $request->cmacnicsis ?? NULL,
                "ccolegiaturanro" =>$request->ccolegiaturanro ?? NULL,
                "clugar" =>$request->clugar ?? NULL,
                "cgrado_titulo" =>$request->cgrado_titulo ?? NULL,
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ]);

        $iperstipoestudid =  $request->iperstipoestudid;
        return $iperstipoestudid;
    }

    public function deleteTipoEstudios($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $iperstipoestudid = DB::table('ficha.personas_tipo_estudios')
            ->where('iperstipoestudid', $request->iperstipoestudid)
            ->update([

                "bhabilitado" => $request->bhabilitado ?? 0,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'D',
                "cmacnicsis" => $request->cmacnicsis ?? NULL
            ]);

        $iperstipoestudid =  $request->iperstipoestudid;
        return $iperstipoestudid;
    }

    public function saveInfo(Request $request)
    {

        $iperstipoestudid = $this->crearTipoEstudios($request);

        if ($iperstipoestudid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    ////////////////////////
    public function list(Request $request)
    {
        try {
            $data =  DB::select(
                "
                SELECT
                 pte.iperstipoestudid
                ,pte.itipoestudid
                ,pte.ipersid
                ,pte.icentestudid
                ,pte.ipaisid
                ,pte.idptoid
                ,pte.iprovid
                ,pte.idistid
                ,pte.dfechainicio
                ,pte.dfechatermino
                ,pte.ccolegiaturanro
                ,pte.clugar
                ,pte.cgrado_titulo
                ,pte.documento
                ,pte.nombarch
                ,ce.ccentestuddsc

                FROM
                ficha.personas_tipo_estudios as pte
                LEFT JOIN ficha.centro_estudios AS ce ON pte.icentestudid = ce.icentestudid
                WHERE
                pte.ipersid ='" . $request->ipersid . "'
                and pte.bHabilitado = 1
                ORDER BY pte.iperstipoestudid ASC
                "
            );
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        //CONVERT BIT ... QUITAR and pte.bHabilitado = 1
        return response()->json($response, $codeResponse);
    }

    public function store(Request $request)
    {
        try {
            $date = Carbon::now()->locale("es_ES");
            $fecha = $date->format('Y-m-d H:i:s');
            switch ($request->opcion) {
                case 'AGREGAR':
                    $iperstipoestudid = $this->crearTipoEstudios($request);
                    break;
                case 'ACTUALIZAR':
                    $iperstipoestudid = $this->updateTipoEstudios($request);
                    break;
                case 'ELIMINAR':
                    $iperstipoestudid = $this->deleteTipoEstudios($request);
                    break;
                default:
                    break;
            };

            if ($iperstipoestudid > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $iperstipoestudid];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.', 'data' => null];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
