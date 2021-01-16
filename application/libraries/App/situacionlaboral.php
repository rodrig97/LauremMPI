<?php
 
class situacionlaboral extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'          => 'persla_id',
                                    'code'        => 'persla_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'persla_estado'
                            );
    
    protected $_SCHEMA     = 'rh';
    protected $_TABLE      = 'persona_situlaboral';
    protected $_PREF_TABLE = 'SITULABMPI'; 
    
    
    public function __construct(){
        
        parent::__construct();
        
    }

    public function comprobar_periodo($data , $persla_id = false)
    {
         
            /*  Buscando conceptos que intercepten al que se esta ingresando         */  
            $sql = " SELECT count(*) as total_registros FROM rh.persona_situlaboral persa 
                     WHERE persa.persla_estado = 1 AND persa.pers_id = ?  "; 
         
            $params = array($data['pers_id']);                
        
            if($persla_id !== FALSE )
            {

                $sql.=" AND persa.persla_id != ? ";
                $params[] = $persla_id;
            }

            if( ($data['persla_fechaini'] != '' && $data['persla_fechafin'] != '') && $data['persla_vigente'] == '0' )
            {


                 $sql.= "  AND  (    (  persa.persla_fechaini >= ? AND persa.persla_fechafin <= ? ) OR
                                     (  persa.persla_fechaini <= ? AND persa.persla_fechafin >= ? ) OR  
                                     (  persa.persla_fechaini <= ? AND persa.persla_fechafin is null AND persa.persla_vigente = 1  ) OR 
                                     (  persa.persla_fechafin is not null AND ( persa.persla_fechaini <= ? AND persa.persla_fechafin >= ? ) )  OR 
                                     (  persa.persla_fechafin is not null AND ( persa.persla_fechaini <= ? AND persa.persla_fechafin >= ? ) )  OR
                                     (  persa.persla_fechaini <= ? AND persa.persla_fechacese is null AND persa.persla_vigente = 1 )     
                                )  ";
        
                  array_push($params,  
                                  $data['persla_fechaini'], $data['persla_fechafin'], 
                                  $data['persla_fechaini'], $data['persla_fechafin'], 
                                  $data['persla_fechaini'], $data['persla_fechafin'],
                                  $data['persla_fechaini'],
                                  $data['persla_fechaini'],$data['persla_fechaini'],
                                  $data['persla_fechafin'],$data['persla_fechafin'],
                                  $data['persla_fechaini'] );               

            }
            else if( ($data['persla_fechaini'] != '' && $data['persla_fechafin'] == '' ) || $data['persla_vigente'] == '1'  )
            {

                $sql.=" AND (  
                             ( persa.persla_fechaini >= ? OR persa.persla_fechafin >= ? ) OR 
                             ( persa.persla_fechaini <= ? AND  persa.persla_fechacese is null AND persa.persla_vigente = 1 )      
                            )   
                      ";

                     array_push($params,
                                $data['persla_fechaini'], $data['persla_fechaini'],
                                $data['persla_fechaini'] );      

            }
            else
            {

                return array(false, ' Algo no salio bien.');
            }
      
            $rs = $this->_CI->db->query($sql, $params )->result_array();
            
            $total = $rs[0]['total_registros'];

            return ($total == '0') ? array(true, '') : array(false, ' El periodo de trabajo especificado muestra inconscistencias respecto a su historial laboral'  );
      
    }   

    
    public function registrar($data, $return_id = false)
    {
         
        $pers_id = $data['pers_id'];
    
        list($result, $mensaje) = $this->comprobar_periodo($data);     
 
        if($result)
        { 
            
            if($data['persla_vigente'] == '1')
            {

                   $sql = " UPDATE rh.persona_situlaboral 
                            
                            SET persla_vigente = 0,
                                persla_fecmod = now()  
                                 
                            WHERE pers_id = ? AND ( persla_vigente = 1)";
                  
                   $this->_CI->db->query($sql,array($pers_id));
                   $data['persla_ultimo'] = '1';

                   list($pentip_id) = $this->_CI->persona->get_tipo_pension($pers_id); 
 
                   $grupo = ($pentip_id == PENSION_AFP) ? GRUPOVC_AFP : GRUPOVC_ONP;      

                   $this->_CI->persona->remove_conceptos_pension_planillas($pers_id);

                   $this->_CI->persona->add_conceptos_pension_planillas($pers_id, $data['plati_id'], $grupo);



            }


            $result = parent::registrar($data, $return_id );

            $this->actualizar_ultimo($data['pers_id']);

            return $result;    
        }
        else
        {
          
            return array(false, false, $mensaje );
        } 
         

    }
    

    public function actualizar_datos( $persla_id, $data )
    {
  
        list($result, $mensaje) = $this->comprobar_periodo($data, $persla_id);     
 
        if($result)
        {
         
            if($data['persla_vigente'] == '1')
            {
      
                   $sql = " UPDATE rh.persona_situlaboral 
                            
                            SET persla_vigente = 0,
                                persla_fecmod = now()  
                                 
                            WHERE pers_id = ? AND ( persla_vigente = 1)";

                  
                   $this->_CI->db->query($sql,array($pers_id));
                   $data['persla_ultimo'] = '1';

                   list($pentip_id) = $this->_CI->persona->get_tipo_pension($pers_id); 
 
                   $grupo = ($pentip_id == PENSION_AFP) ? GRUPOVC_AFP : GRUPOVC_ONP;      

                   $this->_CI->persona->remove_conceptos_pension_planillas($pers_id);

                   $this->_CI->persona->add_conceptos_pension_planillas($pers_id, $data['plati_id'], $grupo);

            }
            

            $rs = parent::actualizar( $persla_id, $data, false);

            $this->actualizar_ultimo($data['pers_id']);
  
            if($data['persla_terminoindefinido'] == '1')
            {

                $sql = " UPDATE rh.persona_situlaboral 
                         SET persla_fechafin = null  WHERE persla_id = ?  ";

                $this->_CI->db->query($sql, array($persla_id));
            }


            if($data['persla_vigente'] == '1')
            {

                $sql = " UPDATE rh.persona_situlaboral 
                         SET persla_fechacese = null  WHERE persla_id = ?  ";

                $this->_CI->db->query($sql, array($persla_id));
            }
 
            return array($rs, '');
        }
        else{

            return array(false, $mensaje );
        }    

    }

    
    public function get_historial($pers, $ex = array() )
    {
         
            
         $sql = "SELECT situ.*, 

                        (CASE WHEN doc_sisgedo != '' THEN

                             doc_sisgedo || ' (' || doc_tipo || ')' 

                         ELSE

                            persla_doc || ' - ' ||  persla_docaut

                         END   ) as  documento,

                        tip.plati_tipoempleado as situ_nombre,
                        depes.area_nombre as depe_nombre,
                        depes.area_abrev as depe_abre,
                        cargos.cargo_nombre 

                 FROM rh.persona_situlaboral situ 
                 LEFT JOIN planillas.planilla_tipo tip ON situ.plati_id = tip.plati_id
                 LEFT JOIN public.cargo cargos ON situ.cargo_id = cargos.cargo_id
                 LEFT JOIN public.area depes ON situ.depe_id = depes.area_id


                 WHERE pers_id = ? AND persla_estado = 1 

                 ORDER BY persla_fechaini asc

                        ";
         
         $rs =  $this->_CI->db->query($sql, array($pers))->result_array();
         
         return $rs;
         
    }
    
    
    public function view($id)
    {
     

         $sql = "SELECT situ.*,
                        situ.persla_fechacese as fecha_cese,
                         perso.indiv_nombres,
                         perso.indiv_appaterno,
                         perso.indiv_apmaterno,perso.indiv_dni,

                       (CASE WHEN doc_sisgedo != '' THEN

                             doc_sisgedo || ' (' || doc_tipo || ')'  || ' Asunto: ' || doc_asunto || ' Firma: ' || doc_firma || ' ' || doc_fecha

                         ELSE

                              persla_doc || ' - ' ||  persla_docaut

                         END   ) as  documento,

                        tip.plati_tipoempleado as situ_nombre,
                        depes.area_nombre as depe_nombre,
                        cargos.cargo_nombre as cargo_nombre 

                            
                        FROM rh.persona_situlaboral situ 
                        LEFT JOIN public.individuo perso ON situ.pers_id = perso.indiv_id
                        LEFT JOIN planillas.planilla_tipo tip ON situ.plati_id = tip.plati_id
                        LEFT JOIN public.cargo cargos ON situ.cargo_id = cargos.cargo_id
                        LEFT JOIN public.area depes ON situ.depe_id = depes.area_id
                          
 
                        where situ.persla_id = ?  and persla_estado = 1 LIMIT 1

                        ";
         
         $rs =  $this->_CI->db->query($sql, array($id))->result_array();
         
         return $rs[0];
        
        
    }
    

    public function validar_fecha_cese($params)
    {
    
        $sql = " SELECT * FROM rh.persona_situlaboral WHERE persla_id = ? AND persla_fechaini < ?  ";
        $rs_cese_ok  = $this->_CI->db->query($sql, array($params['registro'], $params['fechacese'] ))->result_array();
         
        return (sizeof($rs_cese_ok) == 1) ? true : false;
    }


    public function cesar($params)
    {
  
        if( $this->validar_fecha_cese($params))
        {
            $sql = "UPDATE rh.persona_situlaboral 
                    SET persla_fechacese = ?, persla_vigente = 0, persla_obs_cese = ? 
                    WHERE persla_id = ?";

           $rs = $this->_CI->db->query($sql, array($params['fechacese'],$params['observacion'], $params['registro']) );
        }
        
        return ($rs) ? true : false;
    }
 

    public function activar_directo($params)
    {
 
        $sql = "UPDATE rh.persona_situlaboral SET  persla_vigente = 1,  persla_fechacese = null
                WHERE pers_id = ? ANd persla_ultimo = 1 ";
  
        $rs = $this->_CI->db->query($sql, array($params['indiv_id']) );

        return ($rs) ? true : false;
    }
    

    public function eliminar_registro($persla_id)
    {
        

       $this->_CI->db->trans_begin();

            $sql = " SELECT * FROM rh.persona_situlaboral WHERE persla_id = ? LIMIT 1";
            $rs = $this->_CI->db->query($sql, array($persla_id))->result_array();
            $pers_id = $rs[0]['pers_id'];

            $this->desactivar($persla_id);

            $this->actualizar_ultimo($pers_id);


        if($this->_CI->db->trans_status() === FALSE) 
        {
                $this->_CI->db->trans_rollback();
                return false;
                
        }else{
                    
                $this->_CI->db->trans_commit();
                return true;
        } 


    }


    public function actualizar_ultimo($pers_id)
    {


        $this->_CI->db->trans_begin();

        $sql = " UPDATE rh.persona_situlaboral SET persla_ultimo = 0 WHERE pers_id = ? ";

        $this->_CI->db->query($sql, array($pers_id));

        $sql = " UPDATE rh.persona_situlaboral SET persla_ultimo = 1 
                 WHERE persla_id = ( SELECT persla_id FROM rh.persona_situlaboral persa 
                                     WHERE persa.pers_id = ? AND persa.persla_estado = 1
                                     ORDER BY persla_fechaini desc 
                                     LIMIT 1 )";

        $this->_CI->db->query($sql, array($pers_id));


        if($this->_CI->db->trans_status() === FALSE) 
        {
                $this->_CI->db->trans_rollback();
                return false;
                
        }else{
                    
                $this->_CI->db->trans_commit();
                return true;
        } 

    }
    

    public function get_contratos($params = array(), $count = false)
    {

        $sql = "  SELECT ";

        if($count == FALSE )
        {
            $sql.= " persla.*, 
                     indiv_appaterno,
                     indiv_apmaterno,
                     indiv_nombres,
                     indiv_dni,
                     cargo.cargo_nombre, 
                     area.area_nombre, 
                     plati.plati_nombre,
                
                    (CASE WHEN persla_fechafin is not null THEN 

                     EXTRACT( 'days' FROM (persla_fechafin - now()) )

                     ELSE
                            0
                     END )  as dias_faltantes

                    ";
        }
        else
        {
            $sql.=" count(*) as total"; 
        }

        $sql.= " 

                  FROM rh.persona_situlaboral persla
                  INNER JOIN public.individuo indiv ON persla.pers_id = indiv.indiv_id 
                  LEFT JOIN public.area ON persla.depe_id  = area.area_id
                  LEFT JOIN public.cargo ON persla.cargo_id = cargo.cargo_id 
                  INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
              ";


       if($params['considerar_remuneracion'] != '0')
       {

            $sql_por_remuneracion = " SELECT   plaemp.persla_id as pago_contrato_id, "; 
              
            $sql_por_remuneracion.= ($params['considerar_remuneracion'] == '1') ? " MAX(mes.mes_id) as ultimo_mes " : "  SUM(plaec_value) as monto ";

            $sql_por_remuneracion.="  FROM planillas.planilla_empleados plaemp 
                                      INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.pla_anio = '".$params['anio_eje']."'"; 
                 
            $sql_por_remuneracion.= ($params['considerar_remuneracion'] == '2') ?  " AND pla.pla_mes = '".$params['mes_remuneracion']."'" :  '';
 

            $sql_por_remuneracion.="
                                      INNER JOIN public.mes ON pla.pla_mes = mes.mes_eje 
                                      INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADO_PLANILLA_CERRADA."
                                      
                                      INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1  AND plaec.conc_afecto = 1 AND plaec.conc_tipo = 1  AND plaec.plaec_value > 0
                                      WHERE  plaemp.plaemp_estado = 1 
                                      GROUP BY plaemp.persla_id 
                                      ";      

                $sql.=" LEFT JOIN ( ".$sql_por_remuneracion." ) as data_remuneraciones ON  persla.persla_id = data_remuneraciones.pago_contrato_id  ";

        }                          

        $sql.=" 
                  WHERE  persla.persla_estado = 1  ";



                  if($params['considerar_registros'] == '1')
                  {
                      $sql.=" AND persla.persla_ultimo = 1 ";
                  } 


                  if($params['estado_registro'] == '1')
                  {
                      $sql.=" AND persla.persla_vigente = 1 ";
                  } 
                  else if($params['estado_registro'] == '2')
                  {
                      $sql.=" AND persla.persla_vigente = 0 ";
                  } 


                  if($params['regimen'] != '')
                  {
                     $sql.=" AND persla.plati_id = ? ";
                     $values[] = $params['regimen'];
                  } 
 
                  if($params['area'] != '')
                  {
                     $sql.=" AND persla.depe_id = ? ";
                     $values[] = $params['area'];
                  }  

                  if($params['cargo'] != '')
                  {
                     $sql.=" AND persla.cargo_id = ? ";
                     $values[] = $params['cargo'];
                  } 

                  if($params['grupo'] != '')
                  { 

                  } 

                  if($params['dni'] != '')
                  {
                     $sql.=" AND indiv.indiv_dni = ? ";
                     $values[] = $params['dni'];
                  } 


                  if($params['fecha1'][0] != '')
                  {
                      $sql.=" AND ( persla.persla_fechaini    ";

                      if($params['fecha1'][1] == 'entre' && $params['fecha1'][2] != '')
                      {

                            $sql.=" between ? AND ? ";
                            $values[] = $params['fecha1'][0];
                            $values[] = $params['fecha1'][2];
                      } 
                      else if($params['fecha1'][1] == 'antes')
                      {
                            $sql.=" < ? ";
                            $values[] = $params['fecha1'][0];
                      }
                      else if($params['fecha1'][1] == 'despues')
                      {
                            $sql.=" > ? ";
                            $values[] = $params['fecha1'][0];
                      }

                      $sql.=" ) ";
                  }  


                  if($params['fecha2'][0] != '' && $params['fecha2'][3] == '0')
                  {
                      $sql.=" AND ( persla.persla_fechafin    ";

                      if($params['fecha2'][1] == 'entre' && $params['fecha2'][2] != '')
                      {

                            $sql.=" between ? AND ? ";
                            $values[] = $params['fecha2'][0];
                            $values[] = $params['fecha2'][2];
                      } 
                      else if($params['fecha2'][1] == 'antes')
                      {
                            $sql.=" < ? ";
                            $values[] = $params['fecha2'][0];
                      }
                      else if($params['fecha2'][1] == 'despues')
                      {
                            $sql.=" > ? ";
                            $values[] = $params['fecha2'][0];
                      }

                      $sql.=" ) ";
                  } 
                  else if($params['fecha2'][3] == '1')
                  {

                        $sql.=" AND persla.persla_terminoindefinido = '1' ";

                  }


                  if( $params['montocontrato'][0] != ''  && is_numeric($params['montocontrato'][0]) )
                  {

                        $sql.=" AND (persla.persla_montocontrato   ";

                            if( $params['montocontrato'][2] != ''  && is_numeric($params['montocontrato'][2]) &&  $params['montocontrato'][1] == 'entre' )
                            {
                                
                                $sql.=" between ? AND ? ";
                                $values[] = $params['montocontrato'][1];
                                $values[] = $params['montocontrato'][2];
                            } 
                            else if($params['montocontrato'][1] == 'menor')
                            {
                                $sql.= " < ? ";
                                $values[] = $params['montocontrato'][0];
                            }
                            else if($params['montocontrato'][1] == 'mayor')
                            {
                                $sql.= " > ? ";
                                $values[] = $params['montocontrato'][0];
                            }

                        $sql.=" ) ";

                  }


                  if($params['considerar_remuneracion'] != '0')
                  {
                    
                     if($params['considerar_remuneracion'] == '1')
                     {
                        $i_meses = array('01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6,
                                          '07' => 7, '08' => 8, '09' => 9, '10' => 10, '11' => 11, '12' =>  12
                                        );

                        $ultimo_mes = $i_meses[$params['mes_remuneracion']];

                        $sql.=" AND ( data_remuneraciones.ultimo_mes is null OR data_remuneraciones.ultimo_mes <= ".$ultimo_mes." ) "; 

                     }
                     else
                     {
                         $sql.=" AND ( data_remuneraciones.monto is not null AND data_remuneraciones.monto > 0) ";
                     }
                     /* $sql.=" AND data_remuneraciones.monto > 0 ";

                      $sql.=" AND data_remuneraciones.monto = 0 ";*/
                  }


                if($count == FALSE)
                {
                
                   $sql.= "  ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres, persla.persla_fechaini ";  

                  if($params['limit']>0) $sql.= "  LIMIT ".$params['limit'];
                    
                  if($params['offset'] > 0 && $params['limit'] > 0 )
                  {
                     $sql.= " OFFSET ".$params['offset']; 
                  }
                
                }

        $rs = $this->_CI->db->query($sql, $values )->result_array();

        if($count)
        {
            $rs = $rs[0]['total'];
        }

       


        return $rs;
    }    


    public function get_detalle_contrato($persla_ids = array())
    {
        

          $id_in = implode(',', $persla_ids); 

          $sql = "  SELECT data.*,
                           indiv.indiv_nombres, 
                           indiv.indiv_apmaterno,
                           indiv.indiv_appaterno,
                           indiv.indiv_dni,
                           plati.plati_nombre,
                           area.area_nombre,
                           area.area_abrev,
                           cargo.cargo_nombre,
                           cargo.cargo_abrev

                    FROM (

                        SELECT plaemp.indiv_id, 
                               persla.plati_id,  
                               persla.depe_id,
                               persla.cargo_id,  
                               pla.ano_eje,
                               meta.sec_func,
                               meta.nombre as proyecto, 
                               platica.platica_nombre, 
                               MIN(pla.pla_fecini) as fecha_ini, 
                               MAX(pla.pla_fecfin) as fecha_fin, 
                               ROUND(SUM(plaec_value)) as duracion 
                        
                        FROM planillas.planilla_empleados plaemp 
                        INNER JOIN rh.persona_situlaboral persla ON plaemp.persla_id = persla.persla_id
                        INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 
                        INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADO_PLANILLA_CERRADA."
                        INNER JOIN planillas.conceptos_sistema cosi ON pla.plati_id = cosi.plati_id 
                        INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1  AND cosi.conc_asistencia =  plaec.conc_id 
                        LEFT JOIN planillas.planilla_tipo_categoria platica ON plaemp.platica_id = platica.platica_id 
                        LEFT JOIN  sag.tarea ON plaemp.tarea_id = tarea.tarea_id 
                        LEFT JOIN pip.meta ON tarea.sec_func = meta.sec_func AND meta.ano_eje = pla.ano_eje   
                        WHERE plaec.plaec_value > 0  AND plaemp.persla_id IN (".$id_in.")
                        GROUP BY plaemp.indiv_id, persla.plati_id, persla.depe_id,
                               persla.cargo_id, pla.ano_eje, meta.sec_func, meta.nombre, platica.platica_nombre
              
                    ) AS data 
                    INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                    INNER JOIN planillas.planilla_tipo plati ON data.plati_id = plati.plati_id
                    LEFT JOIN public.area ON data.depe_id = area.area_id 
                    LEFT JOIN public.cargo cargo ON data.cargo_id = cargo.cargo_id 

                    ORDER BY indiv.indiv_appaterno, indiv_apmaterno, indiv_nombres, plati_nombre, platica_nombre, data.fecha_ini 
                      
                 ";

        $rs = $this->_CI->db->query($sql, array() )->result_array();

        return $rs;
    } 

    public function count_individuos_historiales($id_s = array() , $is_keys = false )
    {

        $in_persla_id = implode("','", $id_s);

        $k = ($is_keys) ? 'persla_key' : 'persla_id';

        $sql = " SELECT count(distinct(pers_id)) as total_trabajadores 
                 FROM rh.persona_situlaboral persla 
                 WHERE persla.".$k." IN ('".$in_persla_id."') AND persla.persla_estado = 1
               ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs[0]['total_trabajadores'];

    }


    public function get_multiple_id_persla( $persla_keys = array() )
    {
        $in_persla_id = implode("','", $persla_keys);
 
        $sql = " SELECT persla_id
                 FROM rh.persona_situlaboral persla 
                 WHERE persla.persla_key IN ('".$in_persla_id."') AND persla.persla_estado = 1
               ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        $ids = array();

        foreach ($rs as $reg)
        {
            $ids[] = $reg['persla_id'];
        }
 
        return  $ids;
    }


    public function get_multiple_info_trabajador( $persla_keys = array(), $solo_vigentes = true  )
    {
        $in_persla_id = implode("','", $persla_keys);
    
        $sql = " SELECT persla.*, indiv.indiv_appaterno, indiv_apmaterno, indiv_nombres, indiv_dni, plati.plati_nombre   
                 FROM rh.persona_situlaboral persla 
                 INNER JOIN public.individuo indiv ON persla.pers_id = indiv.indiv_id 
                 INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                 WHERE persla.persla_key IN ('".$in_persla_id."') AND persla.persla_estado = 1 
               ";

        if($solo_vigentes == true)
        {
            $sql.="  AND persla_vigente = 1 AND persla_ultimo = 1 ";
        }

        $sql.=" ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres ";

        $rs = $this->_CI->db->query($sql, array())->result_array();
 
        return  $rs;
    }
    


    public function get_fecha_minima_maxima( $persla_keys = array(), $params = array() )
    {
        $in_persla_id = implode("','", $persla_keys);
        
        $solo_vigentes = ($params['solo_vigentes'] == '' ||  $params['solo_vigentes'] == null) ? true : false; 

        $campo =  ($params['fecha'] == 'inicio') ? 'persla_fechaini' : 'persla_fechafin';
  
        $sql_m =  ($params['modo'] == 'minima') ? "MIN(".$campo.") as resultado" : "MAX(".$campo.") as resultado"; 
 
        $sql = " SELECT ".$sql_m."
                 FROM rh.persona_situlaboral persla 
                 WHERE persla.persla_key IN ('".$in_persla_id."') AND persla.persla_estado = 1 
               ";

        if($solo_vigentes == true)
        {
            $sql.="  AND persla_vigente = 1 AND persla_ultimo = 1 ";
        }

        $rs = $this->_CI->db->query($sql, array())->result_array();
    
        return  $rs[0]['resultado'];
    }
    

    public function cesar_masivo($params = array())
    {   

        if(sizeof($params['persla_ids']) == 0 )
        {
            return true;
        }
        else
        {
            $in_persla = implode(',', $params['persla_ids']); 
        }

        $sql = "UPDATE rh.persona_situlaboral 
                SET persla_fechacese = ?, persla_vigente = 0, persla_obs_cese = ? 
                WHERE  persla_fechaini < ?  AND persla_id  IN (".$in_persla.") ";

       $rs = $this->_CI->db->query($sql, array($params['fechacese'], trim($params['observacion']), $params['fechacese']  ) );
         
       return ($rs) ? true : false;
    }


}
