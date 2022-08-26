<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class cargaFamiliarController extends Controller {

    public function index(){
        $icarga_familiarid = DB::table('carga_familiar')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
            return response()->json(['data' => $icarga_familiarid]);
    }

    public function crearCargaFamiliar($request){

        $icarga_familiarid = DB::table('ficha.carga_familiar')->insertGetId(
            [
                "ipersid" => $request->ipersid,
                "cape_nom_conyug" => $request->cape_nom_conyug,
                "ccel_conyug" => $request->ccel_conyug,
                "cape_nom_hijos" => $request->cape_nom_hijos,
                "cfechanac_hijos" => $request->cfechanac_hijos,
                "ape_nom_padre" => $request->ape_nom_padre,
                "cel_padre" => $request->cel_padre,
                "ape_nom_madre" => $request->ape_nom_madre,
                "cel_madre" => $request->cel_madre,
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
            'icarga_familiarid'
        );
        return $icarga_familiarid;
    }

    public function saveInfo(Request $request){
        
        $icarga_familiarid = $this->crearCargaFamiliar($request);

        if ($icarga_familiarid > 0){
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
                 cf.icarga_familiarid
                ,cf.ipersid
                ,cf.cape_nom_conyug
                ,cf.ccel_conyug
                ,cf.cape_nom_hijos
                ,cf.cfechanac_hijos
                ,cf.ape_nom_padre
                ,cf.cel_padre
                ,cf.ape_nom_madre
                ,cf.cel_madre
                ,cf.documento
                ,cf.nombarch

                FROM
                ficha.carga_familiar as cf
                
                WHERE
                cf.ipersid ='" . $request->ipersid . "'
                and cf.bhabilitado = 1
                ORDER BY cf.icarga_familiarid ASC
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
                    $icarga_familiarid = $this->crearCargaFamiliar($request);
                    break;
                case 'ACTUALIZAR':
                    $icarga_familiarid = $this->updateCargaFamiliar($request);
                    break;
                case 'ELIMINAR':
                    $icarga_familiarid = $this->deleteCargaFamiliar($request);
                    break;
                default:
                    break;
            };

            if ($icarga_familiarid > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $icarga_familiarid];
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

    public function updateCargaFamiliar($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $icarga_familiarid = DB::table('ficha.carga_familiar')
            ->where('icarga_familiarid', $request->icarga_familiarid)
            ->update([
                "ipersid" => $request->ipersid,
                "cape_nom_conyug" => $request->cape_nom_conyug,
                "ccel_conyug" => $request->ccel_conyug,
                "cape_nom_hijos" => $request->cape_nom_hijos,
                "cfechanac_hijos" => $request->cfechanac_hijos,
                "ape_nom_padre" => $request->ape_nom_padre,
                "cel_padre" => $request->cel_padre,
                "ape_nom_madre" => $request->ape_nom_madre,
                "cel_madre" => $request->cel_madre,
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

        $icarga_familiarid =  $request->icarga_familiarid;
        return $icarga_familiarid;
    }

    public function deleteCargaFamiliar($request)
    {

        $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        $icarga_familiarid = DB::table('ficha.carga_familiar')
            ->where('icarga_familiarid', $request->icarga_familiarid)
            ->update([
                "bhabilitado" => $request->bhabilitado ?? 0,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'D',
                "cmacnicsis" => $request->cmacnicsis ?? NULL
            ]);

        $icarga_familiarid =  $request->icarga_familiarid;
        return $icarga_familiarid;
    }

}