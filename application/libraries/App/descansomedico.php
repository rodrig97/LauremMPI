<?php

 
class descansomedico extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'perdm_id',
                                    'code'  => 'perdm_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'perdm_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_descansomedico';
    protected $_PREF_TABLE= 'descm'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
   
    public function get_descansos($pers, $params = array() )
    {
            
         $sql =  " SELECT *,
                         tipdes.tdm_nombre as tipo,
                         perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni,

                         (perdm_fechafin - perdm_fechaini + 1) as dias
  
                 FROM rh.persona_descansomedico des 
                 LEFT JOIN public.individuo perso ON des.pers_id = perso.indiv_id
                 LEFT JOIN rh.tipo_descansomedico tipdes ON des.tdm_id = tipdes.tdm_id
                 WHERE  des.perdm_estado = 1 
                ";


        
        if($pers != '0'  ||  $pers != '' )
        {
            $sql.=" AND des.pers_id = ? ";
            $query[] = $pers;
        } 


        if($params['fechaini'] != '')
        {

            if($params['fechafin']  != '')
            {

                $sql.=" AND ( perdm_fechaini  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else
            {

                $sql.=" AND ( perdm_fechaini > ? ) ";
                $query[] = $params['fechaini'];
            }

        }


        if(  $params['tipo'] != '')
        {

            $sql.="  AND  tdm_id = ?";
            $query[] = $params['tipo'];
        }
 
        if($params['anio'] != '' && $params['anio'] != '0'){
            $sql.=" AND EXTRACT('year' FROM perdm_fechaini ) = ?";
            $query[] = $params['anio'];
        }

        $sql.= " order by perdm_fechaini";
         
         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }
    
    
    public function get_by_filtro($params = array())
    {       

          $query = array();

          $fecha = ($params['busquedaporfecha'] == '1') ? 'perdm_fechaini' : 'perdm_fecreg';
 
           if($params['agrupar'] == '')
           {
 
                $sql =  " SELECT 
                                des.*,  
                                tipdes.tdm_nombre as tipo,
                                ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                (perdm_fechafin - perdm_fechaini + 1) as dias 

                           FROM rh.persona_descansomedico des 
                           INNER JOIN public.individuo indiv ON des.pers_id = indiv.indiv_id
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                           LEFT JOIN rh.tipo_descansomedico tipdes ON des.tdm_id = tipdes.tdm_id
                           WHERE  des.perdm_estado = 1  ";

                if( trim($params['indiv_id']) != '' )
                {

                    $sql.=" AND des.pers_id  = ?";
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

                $sql.= " ORDER BY indiv.indiv_appaterno , indiv.indiv_apmaterno , indiv.indiv_nombres, perdm_fechaini";
                  
          }
          else
          {     

                //  Agrupar por 1: año  2:mes  

                  $agrupar_por = ($params['agrupar'] == '1') ? 'year' : 'month';


                  $sql =  " SELECT   

                               ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                data.*

                            FROM (  
                               SELECT (des.pers_id || '_' || EXTRACT( ".$agrupar_por."  FROM ".$fecha.") ) as indice ,   des.pers_id as indiv_id,  EXTRACT( ".$agrupar_por."  FROM ".$fecha.") as periodo, SUM(".($params['busquedaporfecha']=='1' ? "(perdm_fechafin - perdm_fechaini + 1)" : "1" ).") as total  
                               FROM rh.persona_descansomedico des
                               WHERE  des.perdm_estado = 1  
                            
                               ";


                               if( trim($params['indiv_id']) != '' )
                               {
                                   $sql.=" AND des.pers_id = ?";
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
                                GROUP BY indice, des.pers_id,periodo
                                ORDER BY des.pers_id,periodo 
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

    public function view($id){
        
        
         $sql =  "SELECT *,
                         tipdes.tdm_nombre as tipo,
                         perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni,

                         (perdm_fechafin - perdm_fechaini + 1) as dias
  
                 FROM rh.persona_descansomedico des 
                 LEFT JOIN public.individuo perso ON des.pers_id = perso.indiv_id
                 LEFT JOIN rh.tipo_descansomedico tipdes ON des.tdm_id = tipdes.tdm_id
                 where des.perdm_id = ? LIMIT 1";
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
        
    }
    

    public function get_tipos()
    {

        $sql = "SELECT * FROM rh.tipo_descansomedico  WHERE tdm_estado = 1 ORDER BY tdm_nombre ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;
    }
 

    public function get_descansos_medicos_planilla_anio($planilla_id){
 

        $variables_dm = array(417, 421, 424, 425, 427, 453, 551, 552, 563, 565);
        $in_variables = implode(',', $variables_dm);

        $unidad_horasID = 6;
        $unidad_DiasID = 5;

        $sql_totalanio_horas.="   SELECT  pla.pla_anio as anio,
                                          plaemp.indiv_id,
                                          SUM(plaev.plaev_valor) as dm_anio_horas

                                FROM planillas.planilla_empleados plaemp 
                                INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 
                                INNER JOIN planillas.planilla_movimiento plamo ON plamo.pla_id = pla.pla_id AND plamo_estado = 1 AND plamo.plaes_id >= ".ESTADOPLANILLA_PROCESADA." 
                                INNER JOIN planillas.planilla_empleado_variable plaev ON plaemp.plaemp_id = plaev.plaemp_id AND plaev_estado = 1 AND plaev.vari_id IN (".$in_variables.")  
                                INNER JOIN planillas.variables vari ON plaev.vari_id = vari.vari_id AND vari.vau_id = ".$unidad_horasID."                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                                WHERE plaemp.plaemp_estado = 1 AND plaev.plaev_valor > 0  
                                GROUP BY pla_anio, plaemp.indiv_id 
                          ";  
        
        $sql_totalanio_dias.="   SELECT  pla.pla_anio as anio,
                                         plaemp.indiv_id,
                                         SUM(plaev.plaev_valor) as dm_anio_dias

                                FROM planillas.planilla_empleados plaemp 
                                INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 
                                INNER JOIN planillas.planilla_movimiento plamo ON plamo.pla_id = pla.pla_id AND plamo_estado = 1 AND plamo.plaes_id >= ".ESTADOPLANILLA_PROCESADA." 
                                INNER JOIN planillas.planilla_empleado_variable plaev ON plaemp.plaemp_id = plaev.plaemp_id AND plaev_estado = 1 AND plaev.vari_id IN (".$in_variables.")                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                INNER JOIN planillas.variables vari ON plaev.vari_id = vari.vari_id AND vari.vau_id = ".$unidad_DiasID." 
                                WHERE plaemp.plaemp_estado = 1 AND plaev.plaev_valor > 0  
                                GROUP BY pla_anio, plaemp.indiv_id 
                          ";  



        $sql_planilla.="    SELECT  pla.pla_anio,
                                    plaemp.indiv_id,
                                    SUM(plaev.plaev_valor) as dm_planilla

                            FROM planillas.planilla_empleados plaemp 
                            INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 
                            INNER JOIN planillas.planilla_empleado_variable plaev ON plaemp.plaemp_id = plaev.plaemp_id AND plaev_estado = 1 AND plaev.vari_id IN (".$in_variables.")                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                            WHERE plaemp.plaemp_estado = 1 AND plaemp.pla_id = ?  AND plaev.plaev_valor > 0  
                            GROUP BY pla_anio, plaemp.indiv_id 
                      ";  

        $sql_dm ="  SELECT des.pers_id as indiv_id,  
                           EXTRACT( 'year' FROM perdm_fechaini)::text as anio,
                          SUM((perdm_fechafin - perdm_fechaini + 1)) as dias_dm_escalafon 
                    FROM rh.persona_descansomedico des  
                    WHERE des.perdm_estado = 1 
                    GROUP BY des.pers_id, anio
                    ORDER BY des.pers_id, anio";


        $sql = "SELECT  
                    (indiv.indiv_appaterno  || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres) as trabajador_nombre,  
                     indiv.indiv_dni as trabajador_dni, 
                     planilla.indiv_id,
                     planilla.pla_anio,
                     planilla.dm_planilla,
                     descansomedico.dias_dm_escalafon,
                     dm_horas_anio.dm_anio_horas,
                     dm_dias_anio.dm_anio_dias

                FROM  ( ".$sql_planilla." ) as planilla  
                LEFT JOIN (
                    ".$sql_dm." 
                ) as descansomedico ON planilla.indiv_id = descansomedico.indiv_id AND planilla.pla_anio = descansomedico.anio
                LEFT JOIN (
                    ".$sql_totalanio_horas."
                ) as dm_horas_anio ON planilla.indiv_id = dm_horas_anio.indiv_id AND planilla.pla_anio = dm_horas_anio.anio
                LEFT JOIN (
                    ".$sql_totalanio_dias."
                ) as dm_dias_anio ON planilla.indiv_id = dm_dias_anio.indiv_id AND planilla.pla_anio = dm_dias_anio.anio
                LEFT JOIN public.individuo indiv ON planilla.indiv_id = indiv.indiv_id 
                ORDER BY indiv.indiv_appaterno, indiv_apmaterno, indiv.indiv_nombres 
               "; 

        $rs = $this->_CI->db->query($sql, array($planilla_id))->result_array();

        return $rs;


    }
  
}
