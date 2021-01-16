<?php

 
class falta extends Table{
     
    protected $_FIELDS=array(   
     
                                    'id'    => 'pefal_id',
                                    'code'  => 'pefal_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pefal_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_falta';
    protected $_PREF_TABLE= 'perfaltarmpi'; 
    
    public function __construct()
    {
        
        parent::__construct();
        
    }
    
    public function get_faltas($pers = '0', $params = array() )
    {
            
         $sql = "SELECT ft.*,
                 (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres ) as trabajador,
                         ind.indiv_dni as trabajador_dni 
 

             FROM rh.persona_falta ft
             LEFT JOIN public.individuo ind ON ft.pers_id = ind.indiv_id   
             WHERE  pefal_estado = 1  ";
         
         $query  = array();

         if($pers != '0'  ||  $pers != '' ){

                  $sql.=" AND pers_id = ? ";
                  $query[] = $pers;
        } 


       if($params['justificada'] != '')
       {
                  $sql.=" AND pefal_justificada = ? ";
                  $query[] = $params['justificada'];
       } 

       
        if($params['fechaini'] != ''){

            if($params['fechafin']  != ''){

                $sql.=" AND ( pefal_desde  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else{

                $sql.=" AND ( pefal_desde > ? ) ";
                $query[] = $params['fechaini'];
            }

        }

            $sql.=" ORDER BY pefal_desde ";

         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id)
    {
                  
         $sql = 'SELECT faltar.*,
                    perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni 
                 FROM rh.persona_falta faltar
                 LEFT JOIN public.individuo perso ON faltar.pers_id = perso.indiv_id
                 WHERE pefal_id = ?  LIMIT 1';
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
    }


    public function get_by_filtro($params = array())
    {       

          $query = array();

          $fecha = ($params['busquedaporfecha'] == '1') ? 'pefal_desde' : 'pefal_fechareg';
    
           if($params['agrupar'] == '')
           {
    
                $sql =  " SELECT 
                                fal.*,  
                                ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                (pefal_desde - pefal_hasta + 1 ) as minutos  
    

                           FROM rh.persona_falta fal 
                           INNER JOIN public.individuo indiv ON fal.pers_id = indiv.indiv_id
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                           WHERE  fal.pefal_estado = 1  ";

                if( trim($params['indiv_id']) != '' )
                {

                    $sql.=" AND fal.pers_id  = ?";
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

                $sql.= " ORDER BY  indiv.indiv_appaterno , indiv.indiv_apmaterno , indiv.indiv_nombres, pefal_desde ";
                  
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
                                    (fal.pers_id || '_' || EXTRACT( ".$agrupar_por."  FROM ".$fecha.") ) as indice ,  
                                     fal.pers_id as indiv_id,  
                                     EXTRACT( ".$agrupar_por."  FROM ".$fecha.") as periodo, 
                                     SUM(".($params['busquedaporfecha']=='1' ? "(pefal_hasta - pefal_desde + 1 )" : "1" ).") as total  
                               FROM rh.persona_falta fal
                               WHERE  fal.pefal_estado = 1  
                            
                               ";


                               if( trim($params['indiv_id']) != '' )
                               {
                                   $sql.=" AND fal.pers_id = ?";
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
                                GROUP BY indice, fal.pers_id, periodo
                                ORDER BY fal.pers_id,periodo 
                            ) as data 
                            INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                            INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                            INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 

                            WHERE data.total >= ? 

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
