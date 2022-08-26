<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class modalContratoController extends Controller {

    public function index(){
        $modalContrat = DB::table('modalidad_contrato')
            //->leftJoin('personas', 'modalidad_contrato.imodalcontid', '=', 'personas.per_id')
            ->get();
            return response()->json(['data' => $modalContrat]);
    }

    public function crearModalContrato($request){

        $imodalcontid = DB::table('modalidad_contrato')->insertGetId(
            [
                "cmodalcontdsc" => $request->cmodalcontdsc,
                "iestado" => $request->iestado,
                "bhabilitado" => $request->bhabilitado
            ],
            'imodalcontid'
        );
        return $imodalcontid;
    }

    public function saveInfo(Request $request){
        
        $imodalcontid = $this->crearModalContrato($request);

        if ($imodalcontid > 0){
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }
}