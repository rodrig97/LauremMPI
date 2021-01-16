<?php

 
class comision extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'peco_id',
                                    'code'  => 'peco_key',
                                    'name'  => 'peco_documento',
                                    'descripcion' => '',
                                    'state' => 'peco_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_comision';
    protected $_PREF_TABLE= 'comi'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_comisiones($pers = '0', $params = array() )
    {

         $query = array();   
            
         $sql = "SELECT comi.*, 
                        dis.dstt_nombre as destino,

                        (CASE WHEN doc_sisgedo != '' THEN

                             doc_sisgedo || ' (' || doc_tipo || ')' 

                         ELSE

                             peco_documento || ' ' || peco_emisor   

                         END   ) as  documento,


                         (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres ) as trabajador,
                         ind.indiv_dni as trabajador_dni 


                 FROM rh.persona_comision comi
                 LEFT JOIN public.individuo ind ON comi.pers_id = ind.indiv_id     
                 LEFT JOIN public.distrito dis ON comi.departamento = dis.dpto_id AND comi.provincia = dis.prvn_id AND comi.distrito = dis.dstt_id    
                
                 WHERE peco_estado = 1   ";

         if($pers != '0'  ||  $pers != '' ){

                  $sql.=" AND pers_id = ? ";
                  $query[] = $pers;
        } 


        if($params['fechaini'] != ''){

            if($params['fechafin']  != ''){

                $sql.=" AND ( peco_fechadesde  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else{

                $sql.=" AND ( peco_fechadesde > ? ) ";
                $query[] = $params['fechaini'];
            }

        }

        if($params['distrito'] != ''){
                
               $sql.="  AND ( comi.departamento = ?  AND comi.provincia = ?  AND comi.distrito = ? )  ";   
               $query[] = $params['departamento'];
               $query[] = $params['provincia'];
               $query[] = $params['distrito'];

        }


        if($params['anio'] != '' && $params['anio'] != '0'){
            $sql.=" AND EXTRACT('year' FROM peco_fechadesde ) = ?";
            $query[] = $params['anio'];
        }


            $sql.=" ORDER BY peco_fechadesde ";
         
         //echo $sql;
         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id){
        
               
         $sql = "SELECT comi.*, dis.dstt_nombre as destino,

                        (CASE WHEN doc_sisgedo != '' THEN

                             doc_sisgedo || ' (' || doc_tipo || ')'  || ' Asunto: ' || doc_asunto || ' Firma: ' || doc_firma || ' ' || doc_fecha

                         ELSE

                             peco_documento || ' ' || peco_emisor

                         END   ) as  documento,  

                perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni 
                FROM rh.persona_comision comi
                LEFT JOIN \"public\".individuo perso ON comi.pers_id = perso.indiv_id
                LEFT JOIN public.distrito dis ON comi.departamento = dis.dpto_id AND comi.provincia = dis.prvn_id AND comi.distrito = dis.dstt_id    

                    where peco_id = ? limit 1 ";


         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
    }


    public function get_by_filtro($params = array())
    {       

          $query = array();

          $fecha = ($params['busquedaporfecha'] == '1') ? 'peco_fechadesde' : 'peco_fecharegistro';
    
           if($params['agrupar'] == '')
           {
    
                $sql =  " SELECT 
                                com.*,  
                                ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                dis.dstt_nombre as destino,
                                (peco_fechahasta - peco_fechadesde + 1 ) as minutos  
 

                           FROM rh.persona_comision com 
                           LEFT JOIN public.distrito dis ON com.departamento = dis.dpto_id AND com.provincia = dis.prvn_id AND com.distrito = dis.dstt_id    
                           INNER JOIN public.individuo indiv ON com.pers_id = indiv.indiv_id
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                           WHERE  com.peco_estado = 1  ";

                if( trim($params['indiv_id']) != '' )
                {

                    $sql.=" AND com.pers_id  = ?";
                    $query[] = $params['indiv_id'];

                }   
        

                if($params['distrito'] != ''){
                        
                       $sql.="  AND ( com.departamento = ?  AND com.provincia = ?  AND com.distrito = ? )  ";   
                       $query[] = $params['departamento'];
                       $query[] = $params['provincia'];
                       $query[] = $params['distrito'];

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

                $sql.= " ORDER BY  indiv.indiv_appaterno , indiv.indiv_apmaterno , indiv.indiv_nombres, peco_fechadesde ";
                  
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
                                    (com.pers_id || '_' || EXTRACT( ".$agrupar_por."  FROM ".$fecha.") ) as indice ,  
                                     com.pers_id as indiv_id,  
                                     EXTRACT( ".$agrupar_por."  FROM ".$fecha.") as periodo, 
                                     SUM(".($params['busquedaporfecha']=='1' ? "(peco_fechahasta - peco_fechadesde + 1 )" : "1" ).") as total  
                               FROM rh.persona_comision com
                               WHERE  com.peco_estado = 1  
                            
                               ";


                               if( trim($params['indiv_id']) != '' )
                               {
                                   $sql.=" AND com.pers_id = ?";
                                   $query[] = $params['indiv_id'];
                               }

                               if($params['distrito'] != ''){
                                       
                                      $sql.="  AND ( com.departamento = ?  AND com.provincia = ?  AND com.distrito = ? )  ";   
                                      $query[] = $params['departamento'];
                                      $query[] = $params['provincia'];
                                      $query[] = $params['distrito'];

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
                                GROUP BY indice, com.pers_id, periodo
                                ORDER BY com.pers_id,periodo 
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


}
