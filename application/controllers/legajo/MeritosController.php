<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MeritosController extends Controller {

    public function index(){
        $imeritosid = DB::table('meritos')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $imeritosid]);
    }

    public function crearMeritos($request){

        $imeritosid = DB::table('ficha.meritos')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "ctipomerito" => $request->ctipomerito,
                "cdocumentotipo" => $request->cdocumentotipo,
                "cdocumentonro" => $request->cdocumentonro,
                "cdocumentofecha" => $request->cdocumentofecha,
                "cmotivo" => $request->cmotivo,
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
            'imeritosid'
        );
        return $imeritosid;
    }

    public function saveInfo(Request $request){
        
        $imeritosid = $this->crearMeritos($request);

        if ($imeritosid > 0){
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
                 m.imeritosid
                ,m.ipersid
                ,m.ctipomerito
                ,m.cdocumentotipo
                ,m.cdocumentonro
                ,m.cdocumentofecha
                ,m.cmotivo
                ,m.documento
                ,m.nombarch

                FROM
                ficha.meritos as m
                
                WHERE
                m.ipersid ='" . $request->ipersid . "'
                and m.bhabilitado = 1
                ORDER BY m.imeritosid ASC
                ");
            
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
                    $imeritosid = $this->crearMeritos($request);
                    break;
                case 'ACTUALIZAR':
                    $imeritosid = $this->updateMeritos($request);
                    break;
                case 'ELIMINAR':
                    $imeritosid = $this->deleteMeritos($request);
                    break;
                default:
                    break;
            };

            if ($imeritosid > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $imeritosid];
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

    public function updateMeritos($request)
    {
        
        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $imeritosid = DB::table('ficha.meritos')
            ->where('imeritosid', $request->imeritosid)
            ->update([
                "ipersid" => $request->ipersid,
                "ctipomerito" => $request->ctipomerito,
                "cdocumentotipo" => $request->cdocumentotipo,
                "cdocumentonro" => $request->cdocumentonro,
                "cdocumentofecha" => $request->cdocumentofecha,
                "cmotivo" => $request->cmotivo,
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

        $imeritosid =  $request->imeritosid;
        return $imeritosid;
    }

    public function deleteMeritos($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $imeritosid = DB::table('ficha.meritos')
            ->where('imeritosid', $request->imeritosid)
            ->update([
                "bhabilitado" => $request->bhabilitado ?? 0,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'D',
                "cmacnicsis" => $request->cmacnicsis ?? NULL
            ]);

        $imeritosid =  $request->imeritosid;
        return $imeritosid;
    }

}