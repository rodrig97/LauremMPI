<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function listPaisDepartamentoProvinciaDistrito(Request $request)
    {

        $paises =  DB::table('ficha.pais as p')
            ->select('p.ipaisid', 'p.cpaisdsc')
            ->where([['p.bhabilitado', '=', 1]])
            ->get();


        $departamentos =  DB::table('ficha.departamento as d')
            ->select('d.idptoid', 'd.ipaisid', 'd.cdptodsc')
            ->where([['d.bhabilitado', '=', 1]])
            ->get();

        $provincias =  DB::table('ficha.provincia as p')
            ->select('p.iprovid', 'p.idptoid', 'p.cprovdsc')
            ->where([['p.bhabilitado', '=', 1]])
            ->get();

        $distritos = DB::table('ficha.distrito as d')
            ->select('d.idistid', 'd.iprovid', 'd.cdistdsc')
            ->where([['d.bhabilitado', '=', 1]])
            ->get();

        return Response::json([
            'paises' => $paises,
            'departamentos' => $departamentos,
            'provincias' => $provincias,
            'distritos' => $distritos,
        ]);
    }

    function getCode($dni)
	{
		if ($dni != "" || strlen($dni) == 8) {
			$suma = 0;
			$hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
			$suma = 5;
			for ($i = 2; $i < 10; $i++) {
				$suma += ($dni[$i - 2] * $hash[$i]);
			}
			$entero = (int) ($suma / 11);

			$digito = 11 - ($suma - $entero * 11);

			if ($digito == 10) {
				$digito = 0;
			} else if ($digito == 11) {
				$digito = 1;
			}
			return $digito;
		}
		return "";
	}

    public function getInfoReniec(Request $request){
        
        // return $request->all();
        $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
 
         $data = file_get_contents("http://10.0.0.10/apps/webapp/api/consulta_dni/index.php?consultar=". $request->dni, false, $context);
         $GeneralController = new GeneralController();
         $verificar = $GeneralController->getCode($request->dni);
         $response="";
         if($request->codigo != $verificar)
             {
                 $response = ['validated' => false, 'mensaje' => 'Verifique el código o el DNI ingresado', 'data' => null];
             }
             else{
                 $response = ['validated' => true, 'mensaje' => 'Codigo correcto', 'data' => $data];
             }
             return response($response);
 
         // $base = "10.0.0.10/apps/webapp/api/consulta_dni/index.php";
         // $params = array(
         //     'consultar' => $dni,
         // );
         // $url = $base . '?' . http_build_query($params);
         
         // $dc = curl_init();
         // curl_setopt($dc, CURLOPT_URL, $url);
         // curl_setopt($dc, CURLOPT_RETURNTRANSFER, true);
         // curl_setopt($dc, CURLOPT_FOLLOWLOCATION, true);
         // curl_setopt($dc, CURLINFO_HEADER_OUT, true);
         // curl_setopt($dc, CURLOPT_CONNECTTIMEOUT, 5);
         // curl_setopt($dc, CURLOPT_TIMEOUT, 5);
 
         // if ( curl_error($dc) ) {
         //     $respuesta['error'] = true;
         // } else {
         //     $rsp1 = curl_exec($dc);
         //     $rsp1 = json_decode($rsp1);
         //     $respuesta['apPrimer'] = "";
         //     $respuesta['apSegundo'] = "";
         //     $respuesta['prenombres'] = ".";
         //     $respuesta['direccion'] = "";
         // }
 
         // curl_close($dc);
 
         // return $respuesta; 
 }

    public function listSanguineo(Request $request)
    {

        $sanguineo = DB::table('ficha.grupo_sanguineo as gs')
            ->select('gs.igruposangid', 'gs.cgruposangdsc')
            ->where([['gs.bhabilitado', '=', 1]])
            ->get();

        return Response::json([
            'sanguineo' => $sanguineo,

        ]);
    }

    public function subirArchivo(Request $request)
    {
         $this->validate(
            $request,
            [
                'file' => 'required|mimes:pdf,jpeg,png'
            ],
            [
                'file.required' => 'Es necesario que cargue un archivo',
                'file.mimes' => 'El archivo debe ser formato PDF, JPEG o PNG; seleccione otro archivo.',
            ]
        );

        if ($request->hasFile('file')) {
           
            $file = $request->file('file');
            $path = $request->file("file")->store('', ['disk' => 'file']);
            return response()->json($path);
        } else {
            abort(503, 'No se adjuntaron archivos');
        }
    }

    public function listSeguro(Request $request)
    {

        $seguro = DB::table('ficha.tipo_seguro as ts')
            ->select('ts.itiposegid', 'ts.ctiposegdsc')
            ->where([['ts.bhabilitado', '=', 1]])
            ->get();

        return Response::json([
            'seguro' => $seguro,

        ]);
    }

    public function listEstadoCivil(Request $request)
    {
        $estado_civil = DB::table('ficha.estado_civil as ec')
            ->select('ec.iestcivilid', 'ec.cestcivildsc')
            ->where([['ec.bhabilitado', '=', 1]])
            ->get();

        return Response::json([
            'estado_civil' => $estado_civil,

        ]);
    }

    public function listTipoEstudiosCentroEstudios(Request $request)
    {   
        $tipo_estudios = DB::table('ficha.tipo_estudios as te')
            ->select('te.itipoestudid', 'te.ctipoestuddsc', 'te.bformacion')
            ->where([['te.bhabilitado', '=', 1]])
            ->get();

        $centro_estudios = DB::table('ficha.centro_estudios as ce')
            ->select('ce.icentestudid', 'ce.ccentestuddsc')
            ->where([['ce.bhabilitado', '=', 1]])
            ->get();

        return Response::json([
            'tipo_estudios' => $tipo_estudios,
            'centro_estudios' => $centro_estudios,

        ]);
    }

    public function listTipoCapacitacionCentroEstudios(Request $request)
    {   
        $tipo_capacitacion = DB::table('ficha.tipo_capacitacion as tc')
            ->select('tc.itipocapacid', 'tc.ctipocapacdsc')
            ->where([['tc.bhabilitado', '=', 1]])
            ->get();

        $centro_estudios = DB::table('ficha.centro_estudios as ce')
            ->select('ce.icentestudid', 'ce.ccentestuddsc')
            ->where([['ce.bhabilitado', '=', 1]])
            ->get();

        return Response::json([
            'tipo_capacitacion' => $tipo_capacitacion,
            'centro_estudios' => $centro_estudios,

        ]);
    }

    public function listxipersid(Request $request)
    {
        try {
            $data =  DB::select(
                "
                SELECT
                 p.ipersid
                ,p.cpersnombre
                ,p.cperspaterno
                ,p.cpersmaterno
                ,p.dfechanac
                ,p.ipaisid
                ,p.idptoid
                ,p.iprovid
                ,p.idistid
                ,p.igruposangid
                ,p.iestcivilid
                ,p.itiposegid
                ,p.cestado
                ,p.cnroseg
                ,p.ingr_por
                ,p.acta_repos
                ,p.cond_labor
                ,p.mediante1
                ,p.de_fecha
                ,p.cargo_nombro
                ,p.unidad_org_nomb
                ,p.dependiente
                ,p.regimen_lab
                ,p.apartirdel
                ,p.rotadoa
                ,p.mediante2
                ,p.profesion
                ,p.dirigidoa
                ,p.fecha_ingr
                ,p.fecha_nomb
                ,p.nro_resol
                ,p.fecha_resol
                ,p.creferencia
                ,p.nro_contr
                ,p.comentario
                ,p.area
                ,(
                    SELECT MAX(di.cnrodocident) 
                    FROM
                                ficha.documento_identidad AS di 
                    WHERE
                                p.ipersid = di.ipersid AND di.itipodocidentid = 1 
                     
                
                ) AS cpersdni

                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 1
                   
                
                ) AS cpersdireccion
                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 2
                    
                
                ) AS cperscorreo
                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 3
                    
                
                ) AS cperstelefono

                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 4
                     
                
                ) AS cperscelular

                FROM
                ficha.personas as p
                LEFT JOIN ficha.documento_identidad as di ON p.ipersid = di.ipersid
                WHERE
                p.ipersid ='" . $request->ipersid . "'
                and p.bhabilitado = 1
                ORDER BY p.ipersid ASC
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

    public function listAll(Request $request)
    {
        try {
            $data =  DB::select(
                "
                SELECT
                 p.ipersid
                ,p.cpersnombre
                ,p.cperspaterno
                ,p.cpersmaterno
                ,p.dfechanac
                ,p.ipaisid
                ,p.idptoid
                ,p.iprovid
                ,p.idistid
                ,p.igruposangid
                ,p.iestcivilid
                ,p.itiposegid
                ,p.cestado
                ,p.cnroseg
                ,p.ingr_por
                ,p.acta_repos
                ,p.cond_labor
                ,p.mediante1
                ,p.de_fecha
                ,p.cargo_nombro
                ,p.unidad_org_nomb
                ,p.dependiente
                ,p.regimen_lab
                ,p.apartirdel
                ,p.rotadoa
                ,p.mediante2
                ,p.profesion
                ,p.dirigidoa
                ,p.fecha_ingr
                ,p.fecha_nomb
                ,p.nro_resol
                ,p.fecha_resol
                ,p.creferencia
                ,p.nro_contr
                ,pte.iperstipoestudid
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
                ,ptc.iperstipocapacid
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
                ,el.iexp_laboralid
                ,el.ipersid
                ,el.ipaisid
                ,el.idptoid
                ,el.iprovid
                ,el.idistid
                ,el.ccargos_desempenados
                ,el.dfechainicio
                ,el.dfechatermino
                ,el.lugar_laburo
                ,m.imeritosid
                ,m.ipersid
                ,m.ctipomerito
                ,m.cdocumentotipo
                ,m.cdocumentonro
                ,m.cdocumentofecha
                ,m.cmotivo
                ,d.idemeritosid
                ,d.ipersid
                ,d.sancion
                ,d.cdocumentoresolucion
                ,d.cdocumentonro
                ,d.cfecha_ini
                ,d.cfecha_fin
                ,cf.icarga_familiarid
                ,cf.ipersid
                ,cf.cape_nom_conyug
                ,cf.ccel_conyug
                ,cf.cape_nom_hijos
                ,cf.cfechanac_hijos
                ,cf.ape_nom_padre
                ,cf.cel_padre
                ,cf.ape_nom_madre
                ,cf.cel_madre
                ,(
                    SELECT MAX(di.cnrodocident) 
                    FROM
                                ficha.documento_identidad AS di 
                    WHERE
                                p.ipersid = di.ipersid AND di.itipodocidentid = 1 
                     
                
                ) AS cpersdni

                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 1
                   
                
                ) AS cpersdireccion
                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 2
                    
                
                ) AS cperscorreo
                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 3
                    
                
                ) AS cperstelefono

                ,(
                    SELECT MAX(ptc.cdescripcion) 
                    FROM
                                ficha.personas_tipo_contacto AS ptc 
                    WHERE
                                p.ipersid = ptc.ipersid AND ptc.itipocontacid = 4
                     
                
                ) AS cperscelular

                FROM
                ficha.personas as p
                LEFT JOIN ficha.documento_identidad as di ON p.ipersid = di.ipersid
                LEFT JOIN ficha.personas_tipo_estudios as pte ON p.ipersid = pte.ipersid
                LEFT JOIN ficha.personas_tipo_capacitacion as ptc ON p.ipersid = ptc.ipersid
                LEFT JOIN ficha.exp_laboral as el ON p.ipersid = el.ipersid
                LEFT JOIN ficha.meritos as m ON p.ipersid = m.ipersid
                LEFT JOIN ficha.demeritos as d ON p.ipersid = d.ipersid
                LEFT JOIN ficha.carga_familiar as cf ON p.ipersid = cf.ipersid
                WHERE
                p.ipersid ='" . $request->ipersid . "'
                and p.bhabilitado = 1
                ORDER BY p.ipersid ASC
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
    /*
    LEFT JOIN ficha.personas_tipo_estudios as pte ON p.ipersid = pte.ipersid
    LEFT JOIN ficha.personas_tipo_capacitacion as ptc ON p.ipersid = ptc.ipersid
    LEFT JOIN ficha.exp_laboral as el ON p.ipersid = el.ipersid
    LEFT JOIN ficha.meritos as m ON p.ipersid = m.ipersid
    LEFT JOIN ficha.demeritos as d ON p.ipersid = d.ipersid
    LEFT JOIN ficha.carga_familiar as cf ON p.ipersid = cf.ipersid
    */
}
