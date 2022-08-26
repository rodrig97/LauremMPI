<?php

namespace App\Http\Controllers;


class personasController extends Controller
{

    public $estadoCivil;

    // public function __construct(estadoCivController $estadoCivil)
    // {
    //     $this->estadoCivil = $estadoCivil;
    // }

    public function index()
    {
       
       /* $personas = DB::table('personas')
           
            ->leftJoin('centro_estudios', 'centro_estudios.icentestudid', '=', 'personas.ipersid')
            ->leftJoin('pais', 'personas.ipaisid', '=', 'pais.ipaisid')
            ->leftJoin('departamento', 'personas.idptoid', '=', 'departamento.idptoid')
            ->leftJoin('provincia', 'personas.iprovid', '=', 'provincia.iprovid')
            ->leftJoin('tipo_capacitacion', 'tipo_capacitacion.itipocapacid', '=', 'personas.ipersid')
            ->leftJoin('centro_estudios', 'centro_estudios.icentestudid', '=', 'personas.ipersid')
            ->leftJoin('distrito', 'personas.idistid', '=', 'distrito.idistid')
            ->leftJoin('grupo_sanguineo', 'personas.igruposangid', '=', 'grupo_sanguineo.igruposangid')
           
            ->leftJoin('modalidad_contrato', 'modalidad_contrato.imodalcontid', '=', 'personas.ipersid')
            ->leftJoin('tipo_seguro', 'personas.itiposegid', '=', 'tipo_seguro.itiposegid')
            ->leftJoin('personas', 'personas.ipersid', '=', 'detalle_familia.ipersid')
            ->leftJoin('personas', 'personas.ipersid', '=', 'documento_identidad.ipersid')
            ->leftJoin('personas', 'personas.ipersid', '=', 'ficha_contrato.ipersid')
            ->leftJoin('personas', 'personas.ipersid', '=', 'personas_tipo_capacitacion.ipersid')
            ->leftJoin('personas', 'personas.ipersid', '=', 'personas_tipo_contacto.ipersid')
            ->leftJoin('personas', 'personas.ipersid', '=', 'personas_tipo_estudios.ipersid')
            ->leftJoin('tipo_capacitacion', 'tipo_capacitacion.itipocapacid', '=', 'personas.ipersid')
            ->leftJoin('centro_estudios', 'centro_estudios.icentestudid', '=', 'personas.ipersid')
            ->leftJoin('tipo_seguro', 'tipo_seguro.itiposegid', '=', 'personas.ipersid')
            ->get();
        return response()->json(['data' => $personas]);*/
    }



    public function createPersonas($request)
    {

        /*$date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');

        $ipersid = DB::table('ficha.personas')->insertGetId(
            [
                "cpersnombre" => $request->cpersnombre,
                "cperspaterno" => $request->cperspaterno,
                "cpersmaterno" => $request->cpersmaterno,
                "dfechanac" => $request->dfechanac,
                "ipaisid" => $request->ipaisid ?? NULL,
                "idptoid" => $request->idptoid ?? NULL,
                "iprovid" => $request->iprovid ?? NULL,
                "idistid" => $request->idistid ?? NULL,
                "igruposangid" => $request->igruposangid ?? NULL,
                "iestcivilid" => $request->iestcivilid ?? NULL,
                "itiposegid" => $request->itiposegid ?? NULL,
                "cnroseg" => $request->cnroseg ?? NULL,
                "iestado" => $request->iestado ?? 1,
                "bhabilitado" => $request->bhabilitado ?? 1,
                "cusuariosis" => $request->cusuariosis ?? NULL,
                "dfechasis" => $fecha ?? NULL,
                "cequiposis" => $request->cequiposis ?? 'equipo',
                "cipsis" => $request->ip() ?? NULL,
                "copenusr" => $request->copenusr ?? 'N',
                "cmacnicsis" => $request->cmacnicsis ?? NULL,
                "ingr_por" => $request->ingr_por ?? NULL,
                "reposicion" => $request->reposicion ?? NULL,
                "acta_repos" => $request->acta_repos ?? NULL,
                "cond_labor" => $request->cond_labor ?? NULL,
                "mediante1" => $request->mediante1 ?? NULL,
                "de_fecha" => $request->de_fecha ?? NULL,
                "cargo_nombro" => $request->cargo_nombro ?? NULL,
                "unidad_org_nomb" => $request->unidad_org_nomb ?? NULL,
                "dependiente" => $request->dependiente ?? NULL,
                "regimen_lab" => $request->regimen_lab ?? NULL,
                "apartirdel" => $request->apartirdel ?? NULL,
                "rotadoa" => $request->rotadoa ?? NULL,
                "mediante2" => $request->mediante2 ?? NULL,
                "profesion" => $request->profesion ?? NULL,
                "dirigidoa" => $request->dirigidoa ?? NULL,
                "fecha_ingr" => $request->fecha_ingr ?? NULL,
                "fecha_nomb" => $request->fecha_nomb ?? NULL,
                "nro_resol" => $request->nro_resol ?? NULL,
                "fecha_resol" => $request->fecha_resol ?? NULL,
                "creferencia" => $request->creferencia ?? NULL,
                "nro_contr" => $request->nro_contr ?? NULL,
                "nro_hijos" => $request->nro_hijos ?? NULL,
                "ape_nom_cony" => $request->ape_nom_cony ?? NULL,
                "fam_instit" => $request->fam_instit ?? NULL,
                "comentario" => $request->comentario ?? NULL,
                "area" => $request->area ?? NULL,
                "documento" => $request->documento ?? NULL,
                "cestado" => $request->cestado ?? NULL,
                "nombarch" => $request->nombarch ?? NULL
            ],
            'ipersid'
        );
        //crearEstadoCivil($request);
        return $ipersid;*/
    }

    public function updatePersonas($request)
    {

        /*$date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');

        $ipersid = DB::table('ficha.personas')
            ->where('ipersid', $request->ipersid)
            ->update(
                [
                    "cpersnombre" => $request->cpersnombre,
                    "cperspaterno" => $request->cperspaterno,
                    "cpersmaterno" => $request->cpersmaterno,
                    "dfechanac" => $request->dfechanac,
                    "ipaisid" => $request->ipaisid ?? NULL,
                    "idptoid" => $request->idptoid ?? NULL,
                    "iprovid" => $request->iprovid ?? NULL,
                    "idistid" => $request->idistid ?? NULL,
                    "igruposangid" => $request->igruposangid ?? NULL,
                    "iestcivilid" => $request->iestcivilid ?? NULL,
                    "itiposegid" => $request->itiposegid ?? NULL,
                    "cnroseg" => $request->cnroseg ?? NULL,
                    "iestado" => $request->iestado ?? 1,
                    "bhabilitado" => $request->bhabilitado ?? 1,
                    "cusuariosis" => $request->cusuariosis ?? NULL,
                    "dfechasis" => $fecha ?? NULL,
                    "cequiposis" => $request->cequiposis ?? 'equipo',
                    "cipsis" => $request->ip() ?? NULL,
                    "copenusr" => $request->copenusr ?? 'E',
                    "cmacnicsis" => $request->cmacnicsis ?? NULL,
                    "ingr_por" => $request->ingr_por ?? NULL,
                    "reposicion" => $request->reposicion ?? NULL,
                    "acta_repos" => $request->acta_repos ?? NULL,
                    "cond_labor" => $request->cond_labor ?? NULL,
                    "mediante1" => $request->mediante1 ?? NULL,
                    "de_fecha" => $request->de_fecha ?? NULL,
                    "cargo_nombro" => $request->cargo_nombro ?? NULL,
                    "unidad_org_nomb" => $request->unidad_org_nomb ?? NULL,
                    "dependiente" => $request->dependiente ?? NULL,
                    "regimen_lab" => $request->regimen_lab ?? NULL,
                    "apartirdel" => $request->apartirdel ?? NULL,
                    "rotadoa" => $request->rotadoa ?? NULL,
                    "mediante2" => $request->mediante2 ?? NULL,
                    "profesion" => $request->profesion ?? NULL,
                    "dirigidoa" => $request->dirigidoa ?? NULL,
                    "fecha_ingr" => $request->fecha_ingr ?? NULL,
                    "fecha_nomb" => $request->fecha_nomb ?? NULL,
                    "nro_resol" => $request->nro_resol ?? NULL,
                    "fecha_resol" => $request->fecha_resol ?? NULL,
                    "creferencia" => $request->creferencia ?? NULL,
                    "nro_contr" => $request->nro_contr ?? NULL,
                    "nro_hijos" => $request->nro_hijos ?? NULL,
                    "ape_nom_cony" => $request->ape_nom_cony ?? NULL,
                    "fam_instit" => $request->fam_instit ?? NULL,
                    "comentario" => $request->comentario ?? NULL,
                    "area" => $request->area ?? NULL,
                    "documento" => $request->documento ?? NULL,
                    "cestado" => $request->cestado ?? NULL,
                    "nombarch" => $request->nombarch ?? NULL
                ]
            );

        $ipersid =  $request->ipersid;
        return $ipersid;*/
    }


    public function saveInfo()
    { 
        /*
        $ipersid = $this->createPersonas($request);
        if ($ipersid > 0) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);*/
    }

    /////////////////////////////////

    /* public function list()
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
                ,p.cnroseg
                ,p.ingr_por
                ,p.reposicion
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
                ,p.nro_hijos
                ,p.ape_nom_cony
                ,p.fam_instit
                ,p.comentario
                ,p.area
                ,p.documento
                ,p.nombarch

                ,p.cestado
                ,dii.itipodocidentid
                ,dii.cnrodocident
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
                ,(
                    SELECT MAX(di.cnrodocident) 
                    FROM
                                ficha.documento_identidad AS di 
                    WHERE
                                p.ipersid = di.ipersid AND di.itipodocidentid = 1 AND di.cnrodocident ='" . $request->cpersdni . "' 
                     
                
                ) AS cpersdni
                ,(
                    SELECT MAX(di.cnrodocident) 
                    FROM
                                ficha.documento_identidad AS di 
                    WHERE
                                p.ipersid = di.ipersid AND di.itipodocidentid = 2
                    
                
                ) AS cpersruc
                ,(
                    SELECT MAX(di.cnrodocident) 
                    FROM
                                ficha.documento_identidad AS di 
                    WHERE
                                p.ipersid = di.ipersid AND di.itipodocidentid = 4
                     
                
                ) AS cperslicencia
                ,(
                    SELECT MAX(di.cnrodocident) 
                    FROM
                                ficha.documento_identidad AS di 
                    WHERE
                                p.ipersid = di.ipersid AND di.itipodocidentid = 7
                     
                
                ) AS cperslibreta
                

                FROM ficha.personas as p
                LEFT JOIN ficha.documento_identidad as dii ON dii.ipersid = p.ipersid
                WHERE 
                dii.cnrodocident = '".$request->cpersdni."' AND dii.itipodocidentid= '".$request->itipodocidentid.
                
               "'"
            );

            // $data =  DB::table('ficha.personas')
            //     ->leftJoin('ficha.documento_identidad', 'ficha.personas.ipersid', '=', 'ficha.documento_identidad.ipersid')
            //     ->where([['ficha.documento_identidad.itipodocidentid', '=', $request->itipodocidentid], ['ficha.documento_identidad.cnrodocident', '=', $request->cpersdni]])
            //     ->select('ficha.personas.ipersid', 'ficha.personas.cpersnombre', 'ficha.personas.cperspaterno', 'ficha.personas.cpersmaterno', 'ficha.personas.dfechanac', 'ficha.personas.ipaisid', 'ficha.personas.idptoid', 'ficha.personas.iprovid', 'ficha.personas.idistid', 'ficha.personas.igruposangid', 'ficha.personas.iestcivilid', 'ficha.personas.itiposegid', 'ficha.personas.cnroseg')
            //     ->groupBy('ficha.personas.ipersid')
            //     ->get();

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
        
    }*/

    public function store()
    {

       /* $date = Carbon::now()->locale("es_ES");
        $fecha = $date->format('Y-m-d H:i:s');
        switch ($request->opcion) {
            case 'GUARDAR':
                $ipersid = $this->createPersonas($request);
                break;
            case 'ACTUALIZAR':
                $ipersid = $this->updatePersonas($request);
                break;
            default:
                break;
        }
        if ($ipersid > 0) {

            $request->ipersid = $ipersid;

            if ($request->cpersdireccion) {
                $request->itipocontacid = 1;
                $request->cdescripcion = $request->cpersdireccion;
                $data = new persTipoContacController();
                $data = $data->crearTipoContac($request);
            }
            if ($request->cperscorreo) {
                $request->itipocontacid = 2;
                $request->cdescripcion = $request->cperscorreo;
                $data = new persTipoContacController();
                $data = $data->crearTipoContac($request);
            }
            if ($request->cperstelefono) {
                $request->itipocontacid = 3;
                $request->cdescripcion = $request->cperstelefono;
                $data = new persTipoContacController();
                $data = $data->crearTipoContac($request);
            }
            if ($request->cperscelular) {
                $request->itipocontacid = 4;
                $request->cdescripcion = $request->cperscelular;
                $data = new persTipoContacController();
                $data = $data->crearTipoContac($request);
            }

            if ($request->cpersdni) {
                $request->itipodocidentid = 1;
                $request->cnrodocident = $request->cpersdni;
                $data = new docuIndentidadController();
                $data = $data->crearDocIndentidad($request);
            }

            if ($request->cpersruc) {
                $request->itipodocidentid = 2;
                $request->cnrodocident = $request->cpersruc;
                $data = new docuIndentidadController();
                $data = $data->crearDocIndentidad($request);
            }

            if ($request->cperslicencia) {
                $request->itipodocidentid = 4;
                $request->cnrodocident = $request->cperslicencia;
                $data = new docuIndentidadController();
                $data = $data->crearDocIndentidad($request);
            }

            if ($request->cperslibreta) {
                $request->itipodocidentid = 7;
                $request->cnrodocident = $request->cperslibreta;
                $data = new docuIndentidadController();
                $data = $data->crearDocIndentidad($request);
            }

            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'data' => $ipersid];
            $codeResponse = 200;
        } else {
            $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.', 'data' => null];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);*/
    }

    public function get_info($pers_id)
    {

        $sql = " SELECT pers.*, 
                        pers.dpto_id as departamento, 
                        pers.prvn_id as provincia, 
                        pers.dstt_id as distrito, 
                        pensi.peaf_id, 
                        pensi.peaf_codigo,
                        pensi.peaf_codigo as afp_codigo,
                        pensi.afp_id, 
                        afp.afp_nombre as afp, 
                        pensi.pentip_id, 
                        pensi.afm_id,
                        pensi.peaf_jubilado,
                        pensi.peaf_invalidez,
                        cuenta.pecd_id,
                        cuenta.pecd_cuentabancaria, 
                        cuenta.ebanco_id,
                        essa.persa_id,essa.persa_codigo, essa.persa_codigo as essalud_codigo, ecivil.estciv_nombre as estado_civil,
		                    (select count(pefa_id) FROM rh.persona_familia fami WHERE paren_id = 1 AND pefa_estado = 1 AND pers.indiv_id = fami.pers_id   ) as n_hijos,
		                    historial.persla_id,
                        historial.plati_id as tipo_trabajador, 
                        historial.gremp_id as grupo_empleado,
                        historial.persla_fechaini as fecha_inicio, historial.persla_fechafin as fecha_cese, historial.persla_vigente as vigente,
                        distri.dstt_nombre as ciudad_origen, plati.plati_nombre as tipo_empleado, plati.plati_key,
                        historial.ocu_id as ocupacion_id,
                        historial.persla_quinta_gratificacionproyeccion,
                        ocu.ocu_nombre as ocupacion_nombre,
                        nacion.nacion_gentilicio as nacionalidad,
                        COALESCE(date_part('YEAR', age(now(), pers.indiv_fechanac) ),0) as edad, 
                        
                        (CASE WHEN historial.persla_vigente = 1 THEN
                              'ACTIVO'
                        ELSE 
                              'INACTIVO'      
                        END) as estado_trabajador 
                        
		
                FROM public.individuo pers
                LEFT JOIN  rh.persona_pension pensi ON pensi.pers_id = pers.indiv_id AND pensi.peaf_estado = 1 
		            LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id		
                LEFT JOIN  rh.persona_cuenta_deposito cuenta  ON cuenta.pers_id = pers.indiv_id AND cuenta.pecd_estado = 1  
                LEFT JOIN  rh.persona_essalud essa  ON essa.pers_id = pers.indiv_id AND persa_estado = 1  
		            LEFT JOIN  rh.estadocivil ecivil ON pers.indiv_estadocivil = ecivil.estciv_id
                LEFT JOIN  rh.persona_situlaboral historial  ON pers.indiv_id = historial.pers_id AND historial.persla_ultimo = 1 AND historial.persla_estado = 1          
                LEFT JOIN  planillas.planilla_tipo plati ON historial.plati_id = plati.plati_id 
                LEFT JOIN  public.distrito distri ON distri.dpto_id = pers.dpto_id AND distri.prvn_id = pers.prvn_id AND distri.dstt_id = pers.dstt_id
                LEFT JOIN  public.nacionalidad nacion ON pers.indiv_nacionalidad = nacion.nacion_id 
                LEFT JOIN  planillas.ocupacion ocu ON historial.ocu_id = ocu.ocu_id 
                WHERE  pers.indiv_id = ? Limit 1     
               ";

        $rs = $this->_CI->db->query($sql, array($pers_id))->result_array();
        return $rs[0];
    }
    
}
