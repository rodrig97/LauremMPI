<?php

 
class licencia extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'peli_id',
                                    'code'  => 'peli_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'peli_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_licencia';
    protected $_PREF_TABLE= 'licmpi'; 
    
    public function __construct()
    {
        
        parent::__construct();
        
    }
   
    public function registrar_desde_hoja($tipo = 0, $params = array() )
    {
        
       // $this->registrar()


         $values = array(
                         'pers_id'            => $params['indiv_id'], 
                         'peli_fechavigencia' => $params['fechaini'],
                         'peli_tipolicencia'  => $tipo,
                         'peli_descripcion'   => $params['descripcion'] 

                         );


        if($params['fechafin'] != '')
        {

            $values['peli_fechacaducidad'] = $params['fechafin'];
        }
 
         
        list($id,$key) = $this->registrar($values, true);


        return ($id) ? true : false; 
    }   

    public function get_licencias($pers, $params = array() )
    {
            
         $sql =  "SELECT
                        lic.*,
                        ind.indiv_dni as trabajador_dni, 
                        tiplic.tipolic_nombre as tipo_licencia,
                        tiplic.tpolic_nombrecorto as tipo_corto, 

                        (  coalesce(peli_documento,'') || ' - ' ||  coalesce(peli_emisor,'')  ) as  documento,
                         (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres ) as trabajador

                 FROM rh.persona_licencia lic 
                 LEFT JOIN public.individuo ind ON lic.pers_id = ind.indiv_id    
                 LEFT JOIN rh.tipo_licencia tiplic ON lic.peli_tipolicencia = tiplic.tipolic_id
                 where  peli_estado = 1 
                ";


        
        if($pers != '0'  ||  $pers != '' )
        {
              $sql.=" AND pers_id = ? ";
              $query[] = $pers;
        } 


        if($params['fechaini'] != '')
        {

            if($params['fechafin']  != '')
            {
                $sql.=" AND ( peli_fechavigencia  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else{

                $sql.=" AND ( peli_fechavigencia > ? ) ";
                $query[] = $params['fechaini'];
            }

        }


        if(  $params['tipo'] != '')
        {
            $sql.="  AND  peli_tipolicencia = ?";
            $query[] = $params['tipo'];
        }


        if(  $params['salud'] != '')
        {

            $sql.="  AND  peli_essalud = ?";
            $query[] = $params['salud'];
        }


        if($params['anio'] != '' && $params['anio'] != '0'){
            $sql.=" AND EXTRACT('year' FROM peli_fechavigencia ) = ?";
            $query[] = $params['anio'];
        }


        $sql.= " order by peli_fechavigencia";
         
         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }


    public function get_by_filtro($params = array())
    {       

          $query = array();

          $fecha = ($params['busquedaporfecha'] == '1') ? 'peli_fechavigencia' : 'peli_fecharegistro';
 
           if($params['agrupar'] == '')
           {
 
                $sql =  " SELECT 
                                lic.*,  
                                tipolic.tipolic_nombre as tipo,
                                ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                (peli_fechacaducidad - peli_fechavigencia + 1) as dias 

                           FROM rh.persona_licencia lic 
                           INNER JOIN public.individuo indiv ON lic.pers_id = indiv.indiv_id
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                           LEFT JOIN  rh.tipo_licencia tipolic ON lic.peli_tipolicencia = tipolic.tipolic_id
                           WHERE  lic.peli_estado = 1  ";

                if( trim($params['indiv_id']) != '' )
                {

                    $sql.=" AND lic.pers_id  = ?";
                    $query[] = $params['indiv_id'];

                }   


                if( trim($params['tipo']) != '' )
                {
                    $sql.=" AND lic.peli_tipolicencia  = ?";
                    $query[] = $params['tipo'];
                }


                 if($params['desde'] != '')
                 {
                     if($params['hasta']  != '')
                     {

                         $sql.=" AND ( ".$fecha."  between ? AND ? ) ";
                         $query[] = $params['desde'];
                         $query[] = $params['hasta'];
                     }
                     else
                     {

                         $sql.=" AND ( ".$fecha." > ? ) ";
                         $query[] = $params['desde'];
                     }

                 }

                $sql.= " ORDER BY  indiv.indiv_appaterno , indiv.indiv_apmaterno , indiv.indiv_nombres, peli_fechavigencia";
                  
          }
          else
          {     

                //  Agrupar por 1: año  2:mes  

                  $agrupar_por = ($params['agrupar'] == '1') ? 'year' : 'month';


                  $sql =  " SELECT ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                data.*

                            FROM (  
                               SELECT   
                                    (lic.pers_id || '_' || EXTRACT( ".$agrupar_por."  FROM ".$fecha.") ) as indice ,  
                                     lic.pers_id as indiv_id,  
                                     EXTRACT( ".$agrupar_por."  FROM ".$fecha.") as periodo, 
                                     SUM(".($params['busquedaporfecha']=='1' ? "(peli_fechavigencia - peli_fechacaducidad + 1)" : "1" ).") as total  
                               FROM rh.persona_licencia lic
                               WHERE  lic.peli_estado = 1  
                            
                               ";


                               if( trim($params['indiv_id']) != '' )
                               {
                                   $sql.=" AND lic.pers_id = ?";
                                   $query[] = $params['indiv_id'];
                               }

                                if($params['desde'] != '')
                                {
                                    if($params['hasta']  != '')
                                    {

                                        $sql.=" AND ( ".$fecha."  between ? AND ? ) ";
                                        $query[] = $params['desde'];
                                        $query[] = $params['hasta'];
                                    }
                                    else
                                    {

                                        $sql.=" AND ( ".$fecha." > ? ) ";
                                        $query[] = $params['desde'];
                                    }

                                }

                  $sql.=" 
                                GROUP BY indice, lic.pers_id, periodo
                                ORDER BY lic.pers_id,periodo 
                            ) as data 
                            INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                            INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                            INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 

                            WHERE data.total > ? 

                          ";
                            $query[] = $params['valoracumulado'];

                  $sql.= " 
                          
                            ORDER BY  indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres
                          ";
         

          }

    
          $rs =  $this->_CI->db->query($sql, $query)->result_array();
          
          return $rs;
    }
    
    
    public function view($id)
    {
         
         $sql =  "SELECT lic.*,
                         tiplic.tipolic_nombre as tipo_licencia,
                         tiplic.hatd_id,
                         
                         perso.indiv_nombres,
                         perso.indiv_appaterno,
                         perso.indiv_apmaterno,
                         perso.indiv_dni,

                        COALESCE(peli_documento,'')  as  documento, 
                        COALESCE(peli_emisor,'') as autoriza

                 FROM rh.persona_licencia lic 
                 LEFT JOIN public.individuo perso ON lic.pers_id = perso.indiv_id
                 LEFT JOIN rh.tipo_licencia tiplic ON lic.peli_tipolicencia = tiplic.tipolic_id
                 where peli_id = ? LIMIT 1";
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
        
    }
    

    public function get_tipos($params = array())
    {
        $sql = " SELECT * FROM rh.tipo_licencia 
                 WHERE tpolic_estado  = 1 ";

        $values = array();

        if( trim($params['id']) != ''){
            $sql.=" AND tipolic_id = ? ";
            $values[] = trim($params['id']);
        }

        $sql.=" ORDER BY tipolic_orden ";  
        
        $rs = $this->_CI->db->query($sql,$values)->result_array();
        return $rs; 
    }    
  

    public function getLicenciasDia( $params = array() ){

      //  var_dump($params);

        $fechaDesde = '';
        $fechaHasta = '';

        $modo_busqueda = ($params['modo_busqueda_fecha_alternativo'] == true) ? true : false; 
         
        if( $params['anio'] == '' && ($params['fecha_registro_sistema'] == '' || $params['fecha_registro_sistema'] == false) ){

            $fechaDesde = $params['fechadesde'];
            $fechaHasta = $params['fechahasta'];
             
        }

        if($params['fecha_registro_sistema'] == true){

            $fechaDesdeRegistroSistema = $params['fechadesde'];
            $fechaHastaRegistroSistema = $params['fechahasta'];
        }

        if($params['anio'] != ''){

        }

        $agrupar_por = '';
        if($params['agrupar_por'] != ''){
            $agrupar_por = $params['agrupar_por'];
        }

        $indivId = $params['indiv_id'];

        $tipo_trabajador = $params['tipotrabajador'];

        $incluir = $params['incluir'];

        if( sizeof($incluir) == 0){
            return array();
        }

        $sqlVacaciones = "SELECT   'VC_' || vac.perva_id as id,
                                   'Vacaciones'::text as tipo,
                                    perva_fechaini  as desde,
                                    perva_fechafin as hasta,
                                    EXTRACT( 'year' FROM perva_fechaini)::text as anio,
                                    (perva_fechafin - perva_fechaini + 1) as dias,
                                    vac.perva_obs as observacion,
                                    COALESCE(vac.perva_fechareg, perva_fechaini) as fecha_registro,
                                    vac.pers_id as indiv_id 

                           FROM rh.persona_vacaciones vac  ";

        $sqlVacaciones.=" WHERE vac.perva_estado = 1  ";
 

        if($modo_busqueda == false){

            if($fechaDesde != '' && $fechaHasta == ''){
     
                $sqlVacaciones.="  AND ('".$fechaDesde."' BETWEEN vac.perva_fechaini AND vac.perva_fechafin ) ";   
     
            }
            else if($fechaHasta != '' && $fechaDesde == ''){
     
                $sqlVacaciones.=" AND ('".$fechaHasta."' BETWEEN vac.perva_fechaini AND vac.perva_fechafin ) ";  
            
            }
            else if($fechaHasta != '' && $fechaDesde != ''){
     
                $sqlVacaciones.=" AND ( ( vac.perva_fechaini BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' ) OR ( vac.perva_fechafin BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' )  ) ";  
            }
     
        }
        else
        {
            $sqlVacaciones.="  AND ( ('".$fechaDesde."' BETWEEN vac.perva_fechaini AND vac.perva_fechafin ) OR ('".$fechaHasta."' BETWEEN vac.perva_fechaini AND vac.perva_fechafin )  )";   
        }  

        $sqlDescansosMedicos =  " SELECT 'DM_' || des.perdm_id as id,
                                         'Descanso Medico'::text as tipo,
                                         perdm_fechaini  as desde,
                                         perdm_fechafin as hasta,
                                         EXTRACT( 'year' FROM perdm_fechaini)::text as anio,
                                         (perdm_fechafin - perdm_fechaini + 1) as dias,
                                         des.perdm_documento as observacion,
                                         COALESCE(des.perdm_fecreg, perdm_fechaini) as fecha_registro,
                                         des.pers_id as indiv_id
 
                                  FROM rh.persona_descansomedico des  ";
 

        $sqlDescansosMedicos.="   WHERE  des.perdm_estado = 1 ";
    

        if($modo_busqueda == false){


            if($fechaDesde != '' && $fechaHasta == ''){
            
                $sqlDescansosMedicos.="  AND ('".$fechaDesde."' BETWEEN des.perdm_fechaini AND des.perdm_fechafin ) ";   
            
            }
            else if($fechaHasta != '' && $fechaDesde == ''){
            
                $sqlDescansosMedicos.=" AND ('".$fechaHasta."' BETWEEN des.perdm_fechaini AND des.perdm_fechafin ) ";  
            
            }
            else if($fechaHasta != '' && $fechaDesde != ''){
            
                $sqlDescansosMedicos.=" AND ( ( des.perdm_fechaini BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' ) OR ( des.perdm_fechafin BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' )  ) ";  
            }
        
        }
        else
        {
            $sqlDescansosMedicos.="  AND (  ('".$fechaDesde."' BETWEEN des.perdm_fechaini AND des.perdm_fechafin ) OR ('".$fechaHasta."' BETWEEN des.perdm_fechaini AND des.perdm_fechafin ) )";   
        } 




        $sqlComision = "SELECT  'CS_' || comi.peco_id as id,
                                 'Comision de servicio'::text as tipo,
                                 peco_fechaDesde  as desde,
                                 peco_fechahasta as hasta,
                                 EXTRACT( 'year' FROM peco_fechaDesde)::text as anio,
                                 (peco_fechahasta - peco_fechaDesde + 1) as dias,
                                 comi.peco_motivo as observacion,
                                 comi.peco_fecharegistro as fecha_registro,
                                 comi.pers_id as indiv_id

                    FROM rh.persona_comision comi 
                    "; 


        $sqlComision.="   WHERE  comi.peco_estado = 1 ";


        if($modo_busqueda == false){
     
            if($fechaDesde != '' && $fechaHasta == ''){
            
                $sqlComision.="  AND ('".$fechaDesde."' BETWEEN comi.peco_fechaDesde AND comi.peco_fechahasta ) ";   
            
            }
            else if($fechaHasta != '' && $fechaDesde == ''){
            
                $sqlComision.=" AND ('".$fechaHasta."' BETWEEN comi.peco_fechaDesde AND comi.peco_fechahasta ) ";  
            
            }
            else if($fechaHasta != '' && $fechaDesde != ''){
            
                $sqlComision.=" AND ( ( comi.peco_fechaDesde BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' ) OR ( comi.peco_fechahasta BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' )  ) ";  
            }
         
        }
        else
        {
            $sqlComision.="  AND ( ('".$fechaDesde."' BETWEEN comi.peco_fechaDesde AND comi.peco_fechahasta ) OR ('".$fechaHasta."' BETWEEN comi.peco_fechaDesde AND comi.peco_fechahasta )  )";   
        } 
  

        $sqlLicencias =  " SELECT  'LC_' || lic.peli_id as id,
                                     tiplic.tipolic_nombre::text as tipo,
                                     peli_fechavigencia  as desde,
                                     peli_fechacaducidad as hasta, 
                                     EXTRACT( 'year' FROM peli_fechavigencia)::text as anio,
                                     (peli_fechacaducidad - peli_fechavigencia + 1) as dias,
                                     lic.peli_observacion as observacion,
                                     lic.peli_fecharegistro as fecha_registro,
                                     lic.pers_id as indiv_id 

                           FROM rh.persona_licencia lic  
                           LEFT JOIN rh.tipo_licencia tiplic ON lic.peli_tipolicencia = tiplic.tipolic_id
                         ";
   
        $sqlLicencias.="   WHERE  lic.peli_estado = 1 ";
 
        if($params['tipo_licencia'] != '0' && $params['tipo_licencia'] != ''){
            $sqlLicencias.=" AND tipolic_id = ".$params['tipo_licencia'];           
        } 



        if($modo_busqueda == false){

            if($fechaDesde != '' && $fechaHasta == ''){
            
                $sqlLicencias.="  AND ('".$fechaDesde."' BETWEEN lic.peli_fechavigencia AND lic.peli_fechacaducidad ) ";   
            
            }
            else if($fechaHasta != '' && $fechaDesde == ''){
            
                $sqlLicencias.=" AND ('".$fechaHasta."' BETWEEN lic.peli_fechavigencia AND lic.peli_fechacaducidad ) ";  
            
            }
            else if($fechaHasta != '' && $fechaDesde != ''){
            
                $sqlLicencias.=" AND ( ( lic.peli_fechavigencia BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' ) OR ( lic.peli_fechacaducidad BETWEEN '".$fechaDesde."' AND '".$fechaHasta."' )  ) ";  
            }
        }
        else
        {
            $sqlLicencias.="  AND ( ('".$fechaDesde."' BETWEEN lic.peli_fechavigencia AND lic.peli_fechacaducidad ) OR ('".$fechaHasta."' BETWEEN lic.peli_fechavigencia AND lic.peli_fechacaducidad ) )";   
        }


        $arraySqlIncluir = array();
        if(in_array('vacaciones', $incluir)) $arraySqlIncluir[] = $sqlVacaciones;
        if(in_array('descanso_medico', $incluir)) $arraySqlIncluir[] = $sqlDescansosMedicos;
        if(in_array('comision_servicio', $incluir)) $arraySqlIncluir[] = $sqlComision;
        if(in_array('licencia', $incluir)) $arraySqlIncluir[] = $sqlLicencias;
        
        $sqlUnionALL = implode(' UNION ALL ', $arraySqlIncluir);
 
        $sql =" SELECT  
                    (indiv.indiv_appaterno  || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres) as trabajador_nombre,  
                     indiv.indiv_dni as trabajador_dni, 
                     plati.plati_nombre as tipo_trabajador,
              ";

        if($agrupar_por == 'anio'){
        
            $sql.=" datos.anio,    
                    datos.tipo,
                    SUM( datos.hasta - datos.desde + 1) as numero_dias ";
        
        }
        else
        {
            $sql.=" datos.*, datos.dias as numero_dias ";
        }
         
        $sql.=" 
                FROM (
                
                ".$sqlUnionALL."

                ) as datos  
                LEFT JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id 
                INNER JOIN rh.persona_situlaboral persla ON datos.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1 
                INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id
             
                WHERE true 
              ";  

        if($tipo_trabajador != ''){

             $sql.=" AND persla.plati_id IN (".$tipo_trabajador.")";
                
        }

        if( trim($params['anio']) != ''){

            $sql.=" AND datos.anio = '".trim($params['anio'])."'";
        }


        if($params['fecha_registro_sistema'] == true){

            if( $fechaDesdeRegistroSistema != '' && $fechaHastaRegistroSistema != ''){
             
                $sql.=" AND ( ( datos.fecha_registro::date BETWEEN '".$fechaDesdeRegistroSistema."'::date AND '".$fechaHastaRegistroSistema."'::date ) ) ";  
            }
            else if( $fechaDesdeRegistroSistema != '' && $fechaHastaRegistroSistema == '' ){

                $sql.=" AND ( '".$fechaDesdeRegistroSistema."'::date > datos.fecha_registro::date  )";
            }
            else if( $fechaDesdeRegistroSistema == '' && $fechaHastaRegistroSistema != '' ){

                $sql.=" AND ( '".$fechaDesdeRegistroSistema."'::date < datos.fecha_registro::date  )";
            }

        }

        if(trim($indivId) != ''){

            $sql.=" AND datos.indiv_id = ".$indivId;
        }
 
        if($agrupar_por == 'anio'){

            $sql.=" GROUP BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, indiv.indiv_dni, plati.plati_nombre, datos.anio, datos.tipo ";
        
            $sql.=" ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, anio, tipo ";
        }
        else
        {
            $sql.=" ORDER BY datos.desde desc, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres   ";
        }


        if($agrupar_por == 'anio' ){

            $sql =" SELECT * FROM (".$sql.") as datos_agrupados  ";

                if(is_numeric($params['valoracumulado'])){

                    if($params['poracumulado'] == '1'){
                     
                        $sql.=" WHERE datos_agrupados.numero_dias >= ".$params['valoracumulado'];
                    }
                    else if($params['poracumulado'] == '2')
                    {

                        $sql.=" WHERE datos_agrupados.numero_dias <= ".$params['valoracumulado'];
                    }

                }

            $sql.=" 
                   ORDER BY trabajador_nombre, datos_agrupados.anio 
                 ";

        }

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;

    }


}
