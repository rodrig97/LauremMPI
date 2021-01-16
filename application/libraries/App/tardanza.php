<?php

 
class tardanza extends Table{
     
    protected $_FIELDS=array(   
     
                                    'id'    => 'peft_id',
                                    'code'  => 'peft_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'peft_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_tardanzas';
    protected $_PREF_TABLE= 'perfaltarmpi'; 
    
    public function __construct()
    {
        
        parent::__construct();
        
    }
    
    public function get_tardanzas($pers = '0', $params = array() )
    {
            
         $sql = "SELECT ft.*,
                 (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres ) as trabajador,
                         ind.indiv_dni as trabajador_dni 
  
                 FROM rh.persona_tardanzas ft
                 LEFT JOIN public.individuo ind ON ft.pers_id = ind.indiv_id   
                 WHERE  peft_estado = 1  
                ";
         
         $query  = array();

         if($pers != '0'  ||  $pers != '' ){

                  $sql.=" AND pers_id = ? ";
                  $query[] = $pers;
        } 

 

        if($params['fechaini'] != ''){

            if($params['fechafin']  != ''){

                $sql.=" AND ( peft_fecha  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else{

                $sql.=" AND ( peft_fecha > ? ) ";
                $query[] = $params['fechaini'];
            }

        }

            $sql.=" ORDER BY peft_fecha desc ";

         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id)
    {
                  
         $sql = 'SELECT tar.*,
                    perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni 
                 FROM rh.persona_tardanzas tar
                 LEFT JOIN public.individuo perso ON tar.pers_id = perso.indiv_id
                 WHERE peft_id = ?  LIMIT 1';
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
    }


    public function get_by_filtro($params = array())
    {       

          $query = array();

          $fecha = ($params['busquedaporfecha'] == '1') ? 'peft_fecha' : 'peft_fechareg';
    
           if($params['agrupar'] == '')
           {
    
                $sql =  " SELECT 
                                tar.*,  
                                ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                (peft_minutos) as minutos  
    

                           FROM rh.persona_tardanzas tar 
                           INNER JOIN public.individuo indiv ON tar.pers_id = indiv.indiv_id
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                           WHERE  tar.peft_estado = 1  ";

                if( trim($params['indiv_id']) != '' )
                {

                    $sql.=" AND per.pers_id  = ?";
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

                $sql.= " ORDER BY  indiv.indiv_appaterno , indiv.indiv_apmaterno , indiv.indiv_nombres, peft_fecha ";
                  
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
                                    (tar.pers_id || '_' || EXTRACT( ".$agrupar_por."  FROM ".$fecha.") ) as indice ,  
                                     tar.pers_id as indiv_id,  
                                     EXTRACT( ".$agrupar_por."  FROM ".$fecha.") as periodo, 
                                     SUM(peft_minutos) as total  
                               FROM rh.persona_tardanzas tar
                               WHERE  tar.peft_estado = 1  
                            
                               ";


                               if( trim($params['indiv_id']) != '' )
                               {
                                   $sql.=" AND tar.pers_id = ?";
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
                                GROUP BY indice, tar.pers_id, periodo
                                ORDER BY tar.pers_id,periodo 
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
