<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class persTipoCapacController extends Controller {

    public function index(){
        $persTipoCapac = DB::table('personas_tipo_capacitacion')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $persTipoCapac]);
    }

    public function crearTipoCapac($request){

        /*$iperstipocapacid = DB::table('ficha.personas_tipo_capacitacion')->insertGetId(
            [
                "itipocapacid" => $request->itipocapacid,
                "ipersid" => $request->ipersid,
                "icentestudid" => $request->icentestudid,
                "ipaisid" => $request->ipaisid,
                "idptoid" => $request->idptoid,
                "iprovid" => $request->iprovid,
                "idistid" => $request->idistid,
                "cdenominacion" => $request->cdenominacion,
                "ihoras" => $request->ihoras,
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
                "clugar_cap" => $request->clugar_cap ?? NULL,
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ],
            'iperstipocapacid'
        );
        return $iperstipocapacid;*/
       
    }

    public function saveInfo(Request $request){
        
        $iperstipocapacid = $this->crearTipoCapac($request);

        if ($iperstipocapacid > 0){
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
                 ptc.iperstipocapacid
                ,ptc.itipocapacid
                ,ptc.ipersid
                ,ptc.icentestudid
                ,ptc.ipaisid
                ,ptc.idptoid
                ,ptc.iprovid
                ,ptc.idistid
                ,ptc.cdenominacion
                ,ptc.ihoras
                ,ptc.dfechainicio
                ,ptc.dfechatermino
                ,ptc.clugar_cap
                ,ptc.documento
                ,ptc.nombarch
                ,ce.ccentestuddsc
                
                FROM
                ficha.personas_tipo_capacitacion as ptc
                LEFT JOIN ficha.centro_estudios AS ce ON ptc.icentestudid = ce.icentestudid
                WHERE
                ptc.ipersid ='" . $request->ipersid . "'
                and ptc.bHabilitado = 1
                ORDER BY ptc.iperstipocapacid ASC
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
                    $iperstipocapacid = $this->crearTipoCapac($request);
                    break;
                case 'ACTUALIZAR':
                    $iperstipocapacid = $this->updateTipoCapac($request);
                    break;
                case 'ELIMINAR':
                    $iperstipocapacid = $this->deleteTipoCapac($request);
                    break;
                default:
                    break;
            };

            if ($iperstipocapacid > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $iperstipocapacid];
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

    public function updateTipoCapac($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $iperstipocapacid = DB::table('ficha.personas_tipo_capacitacion')
            ->where('iperstipocapacid', $request->iperstipocapacid)
            ->update([
                "itipocapacid" => $request->itipocapacid,
                "ipersid" => $request->ipersid,
                "icentestudid" => $request->icentestudid,
                "ipaisid" => $request->ipaisid,
                "idptoid" => $request->idptoid,
                "iprovid" => $request->iprovid,
                "idistid" => $request->idistid,
                "cdenominacion" => $request->cdenominacion,
                "ihoras" => $request->ihoras,
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
                "clugar_cap" => $request->clugar_cap ?? NULL,
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ]);

        $iperstipocapacid =  $request->iperstipocapacid;
        return $iperstipocapacid;
    }

    public function deleteTipoCapac($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $iperstipocapacid = DB::table('ficha.personas_tipo_capacitacion')
            ->where('iperstipocapacid', $request->iperstipocapacid)
            ->update([

                "bhabilitado" => $request->bhabilitado ?? 0,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'D',
                "cmacnicsis" => $request->cmacnicsis ?? NULL
            ]);

        $iperstipocapacid =  $request->iperstipocapacid;
        return $iperstipocapacid;
    }

}