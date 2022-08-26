<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DemeritosController extends Controller {

    public function index(){
        $persTipoCapac = DB::table('demeritos')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $persTipoCapac]);
    }

    public function crearDemeritos($request){

        $idemeritosid = DB::table('ficha.demeritos')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "sancion" => $request->sancion,
                "cdocumentoresolucion" => $request->cdocumentoresolucion,
                "cdocumentonro" => $request->cdocumentonro,
                "iestado" => $request->iestado ?? 1,
                "bhabilitado" => $request->bhabilitado ?? 1,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'N',
                "cmacnicsis" => $request->cmacnicsis ?? NULL,
                "cfecha_ini" => $request->cfecha_ini ?? NULL,
                "cfecha_fin" => $request->cfecha_fin ?? NULL,
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ],
            'idemeritosid'
        );
        return $idemeritosid;
    }

    public function saveInfo(Request $request){
        
        $idemeritosid = $this->crearDemeritos($request);

        if ($idemeritosid > 0){
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
                 d.idemeritosid
                ,d.ipersid
                ,d.sancion
                ,d.cdocumentoresolucion
                ,d.cdocumentonro
                ,d.cfecha_ini
                ,d.cfecha_fin
                ,d.documento
                ,d.nombarch

                FROM
                ficha.demeritos as d
                
                WHERE
                d.ipersid ='" . $request->ipersid . "'
                and d.bhabilitado = 1
                ORDER BY d.idemeritosid ASC
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
                    $idemeritosid = $this->crearDemeritos($request);
                    break;
                case 'ACTUALIZAR':
                    $idemeritosid = $this->updateDemeritos($request);
                    break;
                case 'ELIMINAR':
                    $idemeritosid = $this->deleteDemeritos($request);
                    break;
                default:
                    break;
            };

            if ($idemeritosid > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $idemeritosid];
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

    public function updateDemeritos($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $idemeritosid = DB::table('ficha.demeritos')
            ->where('idemeritosid', $request->idemeritosid)
            ->update([
                "ipersid" => $request->ipersid,
                "sancion" => $request->sancion,
                "cdocumentoresolucion" => $request->cdocumentoresolucion,
                "cdocumentonro" => $request->cdocumentonro,
                "iestado" => $request->iestado ?? 1,
                "bhabilitado" => $request->bhabilitado ?? 1,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'N',
                "cmacnicsis" => $request->cmacnicsis ?? NULL,
                "cfecha_ini" => $request->cfecha_ini ?? NULL,
                "cfecha_fin" => $request->cfecha_fin ?? NULL,
                "documento" => $request->documento ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ]);

        $idemeritosid =  $request->idemeritosid;
        return $idemeritosid;
    }

    public function deleteDemeritos($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $idemeritosid = DB::table('ficha.demeritos')
            ->where('idemeritosid', $request->idemeritosid)
            ->update([
                "bhabilitado" => $request->bhabilitado ?? 0,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'D',
                "cmacnicsis" => $request->cmacnicsis ?? NULL
            ]);

        $idemeritosid =  $request->idemeritosid;
        return $idemeritosid;
    }

}