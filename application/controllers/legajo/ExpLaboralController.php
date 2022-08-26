<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ExpLaboralController extends Controller
{

    public function index()
    {
        $persTipoCapac = DB::table('exp_laboral')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
        return response()->json(['data' => $persTipoCapac]);
    }

    public function crearExpLaboral($request)
    {

        $iexp_laboralid = DB::table('ficha.exp_laboral')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "ipaisid" => $request->ipaisid,
                "idptoid" => $request->idptoid,
                "iprovid" => $request->iprovid,
                "idistid" => $request->idistid,
                "ccargos_desempenados" => $request->ccargos_desempenados,
                "lugar_laburo" => $request->lugar_laburo,
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
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ],
            'iexp_laboralid'
        );
        return $iexp_laboralid;
    }

    public function saveInfo(Request $request)
    {

        $iexp_laboralid = $this->crearExpLaboral($request);

        if ($iexp_laboralid > 0) {
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
                el.iexp_laboralid
                ,el.ipersid
                ,el.ipaisid
                ,el.idptoid
                ,el.iprovid
                ,el.idistid
                ,el.ccargos_desempenados
                ,el.dfechainicio
                ,el.dfechatermino
                ,el.lugar_laburo
                ,el.documento
                ,el.nombarch

                FROM
                ficha.exp_laboral as el
                WHERE
                el.ipersid ='" . $request->ipersid . "'
                and el.bhabilitado = 1
                ORDER BY el.iexp_laboralid ASC
                "
            );
            // ->where([['iestado','=', 1],['bhabilitado','=', 1]])
            // ->get();

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function store(Request $request)
    {
        try {
            $date = Carbon::now()->locale("es_ES");
            $fecha = $date->format('Y-m-d H:i:s');
            switch ($request->opcion) {
                case 'AGREGAR':
                    $iexp_laboralid = $this->crearExpLaboral($request);
                    break;
                case 'ACTUALIZAR':
                    $iexp_laboralid = $this->updateExpLaboral($request);
                    break;
                case 'ELIMINAR':
                    $iexp_laboralid = $this->deleteExpLaboral($request);
                    break;
                default:
                    break;
            };

            if ($iexp_laboralid > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $iexp_laboralid];
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

    public function updateExpLaboral($request)
    {
        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $iexp_laboralid = DB::table('ficha.exp_laboral')
            ->where('iexp_laboralid', $request->iexp_laboralid)
            ->update([
                "ipersid" => $request->ipersid,
                "ipaisid" => $request->ipaisid,
                "idptoid" => $request->idptoid,
                "iprovid" => $request->iprovid,
                "idistid" => $request->idistid,
                "ccargos_desempenados" => $request->ccargos_desempenados,
                "lugar_laburo" => $request->lugar_laburo,
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
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ]);

        $iexp_laboralid =  $request->iexp_laboralid;
        return $iexp_laboralid;
    }

    public function deleteExpLaboral($request)
    {
        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $iexp_laboralid = DB::table('ficha.exp_laboral')
            ->where('iexp_laboralid', $request->iexp_laboralid)
            ->update([

                "bhabilitado" => $request->bhabilitado ?? 0,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'D',
                "cmacnicsis" => $request->cmacnicsis ?? NULL
            ]);

        $iexp_laboralid =  $request->iexp_laboralid;
        return $iexp_laboralid;
    }
}
