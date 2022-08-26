<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class tipoDocIdentController extends Controller
{

    public function index()
    {
        $itipodocidentid = DB::table('tipo_documento_identidad')
            //->join('est_civil', 'personas.per_id', '=', 'est_civil.per_id')
            ->get();
        return response()->json(['data' => $itipodocidentid]);
    }

    public function crearTipoDocumentoIdent($request)
    {

        $itipodocidentid = DB::table('tipo_documento_identidad')->insertGetId(
            [
                "ctipocontdsc" => $request->ctipocontdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'itipodocidentid'
        );
        return $itipodocidentid;
    }

    public function saveInfo(Request $request)
    {

        $itipodocidentid = $this->crearTipoDocumentoIdent($request);

        if ($itipodocidentid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function list(Request $request)
    {
        try {
            $data =  DB::table('ficha.tipo_documento_identidad')
            ->where([['iestado','=', 1],['bhabilitado','=', 1]])
            ->get();
            
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
