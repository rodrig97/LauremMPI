<?php

 
class permiso extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'pepe_id',
                                    'code'  => 'pepe_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pepe_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'persona_permiso';
    protected $_PREF_TABLE= 'permisompi'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    public function get_permisos($pers = '0',$params = array() )
    {
            
         $sql = "SELECT perm.*,
               
                 (CASE WHEN doc_sisgedo != '' THEN
 
                             doc_sisgedo || ' (' || doc_tipo || ')' 

                         ELSE

                            pepe_documento || ' - ' ||  pepe_emisor

                         END   ) as  documento,
                            (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres ) as trabajador,
                         ind.indiv_dni as trabajador_dni 
 

                 FROM rh.persona_permiso perm
                   LEFT JOIN public.individuo ind ON perm.pers_id = ind.indiv_id     
                
                 where   pepe_estado = 1  ";
            

        $query  = array();

         if($pers != '0'  ||  $pers != '' ){

                  $sql.=" AND pers_id = ? ";
                  $query[] = $pers;
        } 


        if($params['fechaini'] != ''){

            if($params['fechafin']  != ''){

                $sql.=" AND ( pepe_fechadesde  between ? AND ? ) ";
                $query[] = $params['fechaini'];
                $query[] = $params['fechafin'];
            }
            else{

                $sql.=" AND ( pepe_fechadesde > ? ) ";
                $query[] = $params['fechaini'];
            }

        }

            $sql.=" ORDER BY pepe_fechadesde ";

         $rs =  $this->_CI->db->query($sql, $query)->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id){
        
         $sql = "SELECT permi.*, 
                        perso.indiv_nombres,perso.indiv_appaterno,perso.indiv_apmaterno,perso.indiv_dni,

                        (CASE WHEN doc_sisgedo != '' THEN

                             doc_sisgedo || ' (' || doc_tipo || ')'  || ' Asunto: ' || doc_asunto || ' Firma: ' || doc_firma || ' ' || doc_fecha

                         ELSE

                             pepe_documento || ' - ' ||  pepe_emisor

                         END   ) as  documento

                 FROM rh.persona_permiso permi
                 LEFT JOIN public.individuo perso ON permi.pers_id = perso.indiv_id
                 where pepe_id = ?  LIMIT 1";
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
         
    }
    

    public function get_by_filtro($params = array())
    {       

          $query = array();

          $fecha = ($params['busquedaporfecha'] == '1') ? 'pepe_fechadesde' : 'pepe_fecharegistro';
    
           if($params['agrupar'] == '')
           {
    
                $sql =  " SELECT 
                                per.*,  
                                ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador, 
                                indiv.indiv_dni as trabajador_dni,
                                plati.plati_nombre as regimen,
                                (pepe_horafin - pepe_horaini ) as minutos 

                           FROM rh.persona_permiso per 
                           INNER JOIN public.individuo indiv ON per.pers_id = indiv.indiv_id 
                           INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                           INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                           WHERE  per.pepe_estado = 1  ";

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

                $sql.= " ORDER BY  indiv.indiv_appaterno , indiv.indiv_apmaterno , indiv.indiv_nombres, pepe_fechadesde";
                  
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
                                    (per.pers_id || '_' || EXTRACT( ".$agrupar_por."  FROM ".$fecha.") ) as indice ,  
                                     per.pers_id as indiv_id,  
                                     EXTRACT( ".$agrupar_por."  FROM ".$fecha.") as periodo, 
                                     SUM(".($params['busquedaporfecha']=='1' ? "(pepe_horafin - pepe_horaini )" : "1" ).") as total  
                               FROM rh.persona_permiso per
                               WHERE  per.pepe_estado = 1  
                            
                               ";


                               if( trim($params['indiv_id']) != '' )
                               {
                                   $sql.=" AND per.pers_id = ?";
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
                                GROUP BY indice, per.pers_id, periodo
                                ORDER BY per.pers_id,periodo 
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


    public function getConEstadoYMovimiento( $params = array() )
    {

         $sql = "  SELECT pepe.*,   
                          ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador, 
                          ( indiv2.indiv_appaterno || ' ' || indiv2.indiv_apmaterno || ' ' || indiv2.indiv_nombres ) as autoriza,
                          indiv.indiv_dni,
                          permot_nombre as motivo, 
                          perde.perde_nombre as destino,
                          ppe.ppest_id as estado_id,
                          ppe.ppest_nombre as estado,
                          ppe.ppest_abrev as estado_abrev,
                          pepem.pepem_obs as estado_obs,
                          
                         ( CASE WHEN pepe_horafin is not null THEN 
                           
                            ( EXTRACT( 'hour' FROM (pepe_horafin - pepe_horaini ) ) * 60 ) + EXTRACT( 'minutes' FROM (pepe_horafin - pepe_horaini ) ) 

                          ELSE 
                              0
                          END ) as tiempo_minutos

                   FROM rh.persona_permiso pepe 
                   INNER JOIN rh.persona_permiso_movimiento pepem ON pepe.pepe_id = pepem.pepe_id AND pepem.pepem_estado = 1 
                   INNER JOIN rh.persona_permiso_estado ppe ON pepem.ppest_id = ppe.ppest_id 
                   INNER JOIN rh.permiso_motivo pemo ON pepe.permot_id = pemo.permot_id 
                   INNER JOIN rh.permiso_destino perde ON pepe.perde_id = perde.perde_id 
                   INNER JOIN public.individuo indiv ON pepe.pers_id = indiv.indiv_id  
                   LEFT JOIN public.individuo indiv2 ON pepe.indiv_autoriza = indiv2.indiv_id 
                   WHERE pepe_estado = 1 ";

          $values = array();

          if(trim($params['id']) != '')
          {
               $sql.=" AND pepe.pepe_id = ? ";
               $values[] = $params['id'];
          }

          if( trim($params['fechadesde']) != '' && trim($params['fechahasta']) != '')
          {
              $sql.=" AND pepe.pepe_fechadesde between ? AND ? ";
              $values[] = $params['fechadesde'];
              $values[] = $params['fechahasta'];
          }

          if( trim($params['estado']) != '')
          {
              $operador = ($params['considerar_estado_mayor'] === true ) ? ' >= ' : ' = ';
              $sql.=" AND pepem.ppest_id ".$operador." ? ";
              $values[] = $params['estado'];
          }

          if( trim($params['indiv_id']) != '')
          {
              $sql.=" AND pepe.pers_id = ? ";
              $values[] = $params['indiv_id'];
          }
    
          $rs = $this->_CI->db->query($sql, $values )->result_array();

          return $rs;

    } 


    public function get( $params = array() )
    {

         $sql = "  SELECT pepe.*,   
                          ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador, 
                          ( indiv2.indiv_appaterno || ' ' || indiv2.indiv_apmaterno || ' ' || indiv2.indiv_nombres ) as autoriza,
                          indiv.indiv_dni,
                          permot_nombre as motivo, 
                          pepe.pepe_nota as observacion,
                          
                         ( CASE WHEN pepe_horafin is not null THEN 
                           
                            ( EXTRACT( 'hour' FROM (pepe_horafin - pepe_horaini ) ) * 60 ) + EXTRACT( 'minutes' FROM (pepe_horafin - pepe_horaini ) ) 

                          ELSE 
                              0
                          END ) as tiempo_minutos

                   FROM rh.persona_permiso pepe   
                   INNER JOIN rh.permiso_motivo pemo ON pepe.permot_id = pemo.permot_id  
                   INNER JOIN public.individuo indiv ON pepe.pers_id = indiv.indiv_id  
                   LEFT JOIN public.individuo indiv2 ON pepe.indiv_autoriza = indiv2.indiv_id 
                   WHERE pepe_estado = 1 ";

          $values = array();

          if(trim($params['id']) != '')
          {
               $sql.=" AND pepe.pepe_id = ? ";
               $values[] = $params['id'];
          }

          if( trim($params['fechadesde']) != '' && trim($params['fechahasta']) != '')
          {
              $sql.=" AND pepe.pepe_fechadesde between ? AND ? ";
              $values[] = $params['fechadesde'];
              $values[] = $params['fechahasta'];
          }
 
          if( trim($params['fecha']) != '')
          {
              $sql.=" AND pepe.pepe_fechadesde = ?  ";
              $values[] = $params['fecha']; 
          }

          if( trim($params['sin_retorno']) == true)
          {
              $sql.=" AND pepe.pepe_horafin IS NULL "; 
          }
 
          if( trim($params['motivo']) != '' && trim($params['motivo']) != '0')
          {
              $sql.=" AND pepe.permot_id = ? ";
              $values[] = $params['motivo'];
          }

          if( trim($params['indiv_id']) != '')
          {
              $sql.=" AND pepe.pers_id = ? ";
              $values[] = $params['indiv_id'];
          }
   
          $rs = $this->_CI->db->query($sql, $values )->result_array();

          return $rs;

    } 
  


}
