<?php

class planilla extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'pla_id',
                                    'code'  => 'pla_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'pla_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planillas';
    protected $_PREF_TABLE= 'PLANILLA'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }

    
    public function get($plani_id, $by_codigo = false)
    {
        
         $sql = " SELECT 
                            pla.*, 
                            ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                            mes.mes_nombre as mes,
                            plati.plati_nombre as tipo, 
                            plati.plati_key as tipo_key, 
                            mov.plaes_id as estado_id, 
                            est.plaes_nombre as estado, 
							CASE WHEN cast(tarea.ano_eje as int) >= 2021 
								THEN ( tarea.ano_eje || '-' || tarea.sec_func )
								ELSE ( tarea.ano_eje || '-' || tarea.sec_func || '-' || tarea.tarea_nro )
							END as tarea_codigo,
                            tarea.tarea_nombre,
                
                            (SELECT count(plaemp_id) 
                             FROM planillas.planilla_empleados plaem          
                             WHERE plaem.pla_id = pla.pla_id AND plaem.plaemp_estado = 1 ) as num_emps,
                             tarea.sec_func as meta,
                             pla.pla_tiene_categoria,
                             (clasi.tipo_transaccion ||'.'|| clasi.generica || '.' || clasi.subgenerica || '.' || clasi.subgenerica_det || '.' || clasi.especifica || '.' || clasi.especifica_det || ' - ' || clasi.descripcion ) as clasificador,
                             (clasi.tipo_transaccion ||'.'|| clasi.generica || '.' || clasi.subgenerica || '.' || clasi.subgenerica_det || '.' || clasi.especifica || '.' || clasi.especifica_det ) as clasificador_d,
                             ff.nombre as fuente_nombre,
                             ff.abrev as fuente_abrev
             
                 FROM planillas.planillas pla 
                 LEFT JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id
                 LEFT JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1   
                 LEFT JOIN planillas.planilla_estados est ON mov.plaes_id = est.plaes_id
                 LEFT JOIN public.mes ON pla.pla_mes = mes.mes_eje
                 LEFT JOIN sag.tarea ON pla.ano_eje = tarea.ano_eje AND  pla.tarea_id = tarea.tarea_id 
                 LEFT JOIN pip.fuente_financ ff ON pla.fuente_id = ff.fuente_financ AND pla.ano_eje = ff.ano_eje 
                 LEFT JOIN pip.especifica_det clasi ON clasi.id_clasificador = pla.clasificador_id AND pla.ano_eje = clasi.ano_eje 
                                     
            WHERE  
        ";
         
         if($by_codigo == FALSE )
         {

            $sql.=" pla.pla_id = ? ";
         
         }
         else
         {

            $sql.=" ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id   ) = ?   ";
         }
           
         $rs = $this->_CI->db->query($sql, array($plani_id) )->result_array();
          
         return $rs[0];
        
    }
    
     
    public function get_list($params = array())
    {
          
         $p = array();
         
         $sql = "  SELECT
             
                 pla.*, 
                 ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes  || pla.pla_codigo || pla.pla_tipo || pla.plati_id ) as pla_codigo,
                 mes.mes_nombre as mes,
                 plati.plati_nombre as tipo,  
                 est.plaes_nombre as estado, 
				 CASE WHEN cast(tarea.ano_eje as int) >= 2021 
					THEN tarea.sec_func 
					ELSE (tarea.sec_func || '-' || tarea.tarea_nro ) 
				 END as tarea_codigo,
                 tarea.tarea_nombre,
                 mov.plaes_id as estado_id, 
                 ( SELECT count(plaemp_id) FROM planillas.planilla_empleados plaem WHERE plaem.pla_id = pla.pla_id AND plaem.plaemp_estado = 1 ) as num_emps
                    
                 FROM planillas.planillas pla 
                 LEFT JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id
                 LEFT JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1       
                 LEFT JOIN planillas.planilla_estados est ON mov.plaes_id = est.plaes_id
                 LEFT JOIN public.mes ON pla.pla_mes = mes.mes_eje
                 LEFT JOIN sag.tarea ON pla.ano_eje = tarea.ano_eje AND  pla.tarea_id = tarea.tarea_id 
                                     
             WHERE pla.pla_estado != 0
        ";


        if( trim($params['solo_seleccionadas_sunat']) == '1')
        {
            $sql.=" AND pla.sunat_seleccionada = 1 "; 
        }


         if( trim($params['codigo']) != '')
         {
              $sql.=" AND ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id   ) = ? ";
             $p[] = $params['codigo'];
         }
         
         if( trim($params['tipo']) != ''){
             $sql.=" AND pla.plati_id = ? ";
             $p[] = $params['tipo'];
         }
         

         if( trim($params['siaf']) != '')
         {
             $sql.=" AND pla.pla_id IN ( SELECT pla_id FROM planillas.planilla_siaf plasi WHERE plasiaf_estado = 1 AND plasi.siaf = ? AND plasi.ano_eje = ? ORDER BY plasiaf_id ) ";
             $p[] = $params['siaf'];
             $p[] = $params['anio'];
             $sql.=" AND mov.plaes_id  = ? ";
             $p[] = ESTADOPLANILLA_FINALIZADO;
         }
         else
         {

            if( trim($params['estado']) != '')
            {
                $sql.=" AND mov.plaes_id  = ? ";
                $p[] = $params['estado'];
            }

         }
 
         if( trim($params['anio']) != ''){
             $sql.=" AND pla.pla_anio  = ? ";
             $p[] = $params['anio'];
         }         

         if( trim($params['mes']) != ''){
             $sql.=" AND pla.pla_mes  = ? ";
             $p[] = $params['mes'];
         }

           if( trim($params['tarea']) != ''){
             $sql.=" AND pla.tarea_id  = ? ";
             $p[] = $params['tarea'];
         }
 
         if( trim($params['semana']) != '')
         {
             $sql.=" AND pla.pla_semana = ? ";
             $p[] = $params['semana'];
         }     

          if( trim($params['descripcion']) != ''){
             $sql.=" AND pla.pla_descripcion like ? ";
             $p[] = '%'.$params['descripcion'].'%';
         }



      
        if($params['orderby_plati'] == '1')
        {
          $sql.=" ORDER BY  pla.plati_id, pla_id desc    ";
          
        }
        else
        {
          $sql.=" ORDER BY estado_id asc, pla_id desc    ";
          
        }

 
             
         $rs = $this->_CI->db->query($sql, $p )->result_array();
         
         
         return $rs;
    }


    private function actualizar_total_de_hijos($planilla_id, $edad, $vari_id)
    {

        $sql =" UPDATE planillas.planilla_empleado_variable plaev
                SET plaev_valor  = COALESCE(total_hijos.total,0)
                FROM planillas.planilla_empleados plaemp, 
                    
                     (  
                          SELECT pers_id, count(pefa_id) as total
                          FROM rh.persona_familia pefa
                          WHERE pefa_estado = 1 AND paren_id = 4 AND pefa_estudiante = 1
                          AND COALESCE(date_part('YEAR', age(now(), pefa.pefa_fechanace) ),0) <= ?
                          GROUP BY pefa.pers_id 
                   ) as total_hijos 
                   
                WHERE plaev.plaev_estado = 1 AND 
                      plaev.plaemp_id = plaemp.plaemp_id AND 
                      plaemp.plaemp_estado = 1 AND plaemp.indiv_id = total_hijos.pers_id 
                     AND plaemp.pla_id = ? AND plaev.vari_id = ? ";


          $this->_CI->db->query($sql, array($edad, $planilla_id, $vari_id));

        return true;
    }
    
    
    public function procesar($planilla_id,$con_afectacion = true)
    {
            
        $this->_CI->db->trans_begin();
           
        $this->_CI->load->library( array('App/concepto', 'App/concepto_metodos'));    


        // Reload el valor de las variables dinamicas 
        // Total de hijos construccion civil      
        $this->actualizar_total_de_hijos($planilla_id, EDAD_MAXIMA_ESCOLARIDAD, VARIABLE_TOTAL_HIJOS_CONSTRUCCION_CIVIL);
  
           // Procesar conceptos por metodo antes de .. 
            
          $sql ="  SELECT  pla_mes, 
                           pla.plati_id, 
                           plaemp.plaemp_id, 
                           plaemp.indiv_id,
                           plaec_id, 
                           conc_metodo  
                           FROM planillas.planilla_empleados plaemp 
                           LEFT JOIN  planillas.planilla_empleado_concepto pec ON pec.plaemp_id = plaemp.plaemp_id
                           INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 
                           INNER JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id 
                           
                     WHERE plaemp.plaemp_estado = 1 AND 
                           plaemp.pla_id = ? AND 
                           pec.plaec_marcado = 1 AND conc.conc_metodo_momento = 1 AND conc_metodo != ''  

                     
                     ORDER BY plaemp.pla_id, plaemp.indiv_id, pec.conc_afecto asc, pec.conc_id  ";
  
          $empleados_conceptos = $this->_CI->db->query($sql, array($planilla_id))->result_array();


          foreach($empleados_conceptos as $empleado_concepto)
          {
             
             $parametros = array(
                                 'mes' => $empleado_concepto['pla_mes'], 
                                 'plaec_id' => $empleado_concepto['plaec_id'], 
                                 'plaemp_id' => $empleado_concepto['plaemp_id'], 
                                 'indiv_id' => $empleado_concepto['indiv_id'] 

                                 );
       
            $value =  call_user_func_array( array($this->_CI->concepto_metodos, $empleado_concepto['conc_metodo'] ), 
                                                               array($parametros) );
             

          } 


           // Proceso de conceptos normales
           
           $sql ="  SELECT plaec_id 
                            FROM planillas.planilla_empleados plaemp 
                            LEFT JOIN  planillas.planilla_empleado_concepto pec ON pec.plaemp_id = plaemp.plaemp_id
                            INNER JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id 
                            
                      WHERE plaemp.plaemp_estado = 1 AND 
                            plaemp.pla_id = ? AND 
                            pec.plaec_marcado = 1 AND conc.conc_metodo_momento = 0

                      
                      ORDER BY plaemp.pla_id, plaemp.indiv_id, pec.conc_afecto asc, pec.conc_id  ";

 
                              
           $empleados_conceptos = $this->_CI->db->query($sql, array($planilla_id))->result_array();
          
            foreach($empleados_conceptos as $empleado_concepto)
            {
               
              $this->_CI->concepto->procesar($empleado_concepto['plaec_id']);
                
            } 


            $this->_CI->db->trans_commit();


           // $this->_CI->db->trans_begin();

            // Procesar conceptos por metodo despues de .. 

            $sql ="  SELECT  pla.pla_anio,
                             pla_mes, 
                             pla.plati_id, 
                             plaemp.pla_id,
                             plaemp.plaemp_id, 
                             plaemp.indiv_id,
                             plaec_id, 
                             pec.conc_id,
                             conc_metodo  
                             FROM planillas.planilla_empleados plaemp 
                             LEFT JOIN  planillas.planilla_empleado_concepto pec ON pec.plaemp_id = plaemp.plaemp_id
                             INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 
                             INNER JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id 
                             
                       WHERE plaemp.plaemp_estado = 1 AND 
                             plaemp.pla_id = ? AND 
                             pec.plaec_marcado = 1 AND conc.conc_metodo_momento = 2 AND conc_metodo != ''  

                       
                       ORDER BY plaemp.pla_id, plaemp.indiv_id, plaemp.plaemp_id asc, pec.conc_id asc  ";
            
            $empleados_conceptos = $this->_CI->db->query($sql, array($planilla_id))->result_array();


            foreach($empleados_conceptos as $empleado_concepto)
            {
               
               $parametros = array(
                                   'anio' => $empleado_concepto['pla_anio'],
                                   'mes' => $empleado_concepto['pla_mes'], 
                                   'plati_id' => $empleado_concepto['plati_id'], 
                                   'plaec_id' => $empleado_concepto['plaec_id'], 
                                   'pla_id'   => $empleado_concepto['pla_id'],
                                   'plaemp_id' => $empleado_concepto['plaemp_id'], 
                                   'indiv_id' => $empleado_concepto['indiv_id'] 

                                   );
            
              $value =  call_user_func_array( array($this->_CI->concepto_metodos, $empleado_concepto['conc_metodo'] ), 
                                                                 array($parametros) );
               

            } 
 
          
 
       /* 
            if($this->_CI->db->trans_status() === FALSE) 
            {
                $this->_CI->db->trans_rollback();
                return array(false, '', array());
                
            }else{
                    
                $this->_CI->db->trans_commit();
            } 
            */ 
          return array(true, '', array());
    }



    public function actualizar_afectacion_planilla($planilla_id, $params)
    {

         $this->_CI->db->trans_begin();


          $sql = " UPDATE planillas.planillas 
                   SET pla_afectacion_presu = ?,
                       tarea_id = ?,
                       fuente_id = ?,
                       tipo_recurso = ?,
                       clasificador_id = ?,
                       pla_especificar_clasificador = ? 

                   WHERE pla_id = ? ";


          $rs = $this->_CI->db->query($sql, array($params['tipo_afectacion'], $params['tarea'], $params['fuente'], $params['tipo_recurso'], $params['clasificador'], $params['especificar_clasificador'], $planilla_id) );

          if($rs)
          {

              $this->sinc_afectacion_planilla($planilla_id, false );
          }


        if($this->_CI->db->trans_status() === FALSE) 
        {
            $this->_CI->db->trans_rollback();
            return false;
                
        }else{
                    
            $this->_CI->db->trans_commit();
            return true;
        } 

    }

    public function sinc_afectacion_planilla($planilla_id, $considerar_modificaciones = true )
    {



        $planilla_info = $this->_CI->planilla->get($planilla_id); 

        if($planilla_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA )
        {
             
                if($planilla_info['pla_especificar_clasificador'] == '1')
                {
          

                        if($considerar_modificaciones)
                        {


                                $sql = " UPDATE planillas.planilla_empleados plaemp 
                                         SET tarea_id = pla.tarea_id, 
                                             fuente_id = pla.fuente_id, 
                                             tipo_recurso = pla.tipo_recurso, 
                                             empre_id = 0
                                          FROM planillas.planillas pla 
                                          WHERE plaemp.pla_id = pla.pla_id AND plaemp.plaemp_afectacion_actualizada = 0  AND pla.pla_id = ? 
                                
                                       ";

                                $this->_CI->db->query($sql, array($planilla_id));


                                $sql = " UPDATE planillas.planilla_empleados plaemp 
                                          SET clasificador_id = pla.clasificador_id 
                                          FROM planillas.planillas pla 
                                          WHERE plaemp.pla_id = pla.pla_id AND pla.pla_id = ? 
                                
                                       ";

                                $this->_CI->db->query($sql, array($planilla_id));

                        }
                        else
                        {

                             
                            $sql = " UPDATE planillas.planilla_empleados plaemp 
                                     SET tarea_id = pla.tarea_id, 
                                         fuente_id = pla.fuente_id, 
                                         tipo_recurso = pla.tipo_recurso, 
                                         clasificador_id = pla.clasificador_id,
                                         plaemp_afectacion_actualizada = 0,
                                         empre_id = 0
                                      FROM planillas.planillas pla 
                                      WHERE plaemp.pla_id = pla.pla_id   AND pla.pla_id = ? 
                            
                                   ";

                            $this->_CI->db->query($sql, array($planilla_id));
       

                        }

                            $sql =  " UPDATE planillas.planilla_empleado_concepto plaec 
                                      SET tarea_id = plaemp.tarea_id, 
                                          fuente_id = plaemp.fuente_id, 
                                          tipo_recurso = plaemp.tipo_recurso,
                                          clasificador_id = plaemp.clasificador_id,
                                          copc_id = 0

                                       FROM planillas.planilla_empleados plaemp 
                                       WHERE plaec.plaemp_id = plaemp.plaemp_id  AND plaemp.pla_id = ?  
                                     "; 
                            
                            $this->_CI->db->query($sql, array($planilla_id));


                }
                else
                {


                    $sql = " UPDATE planillas.planilla_empleados plaemp 
                             SET tarea_id = pla.tarea_id, 
                                 fuente_id = pla.fuente_id, 
                                 tipo_recurso = pla.tipo_recurso,
                                 empre_id = 0  
                              FROM planillas.planillas pla 
                              WHERE plaemp.pla_id = pla.pla_id AND  plaemp.plaemp_afectacion_actualizada = 0  AND pla.pla_id = ? 
                    
                           ";

                    $this->_CI->db->query($sql, array($planilla_id));



                    $sql =  " UPDATE planillas.planilla_empleado_concepto plaec 
                              SET tarea_id = plaemp.tarea_id, 
                                  fuente_id = plaemp.fuente_id, 
                                  tipo_recurso = plaemp.tipo_recurso
                           
                               FROM planillas.planilla_empleados plaemp 
                               WHERE plaec.plaemp_id = plaemp.plaemp_id  AND plaemp.pla_id = ?  
                             "; 
                    
                    $this->_CI->db->query($sql, array($planilla_id));
                    
                   
                    $sql = " UPDATE planillas.planilla_empleado_concepto plaec 
                             SET clasificador_id = cpc.id_clasificador,
                                 copc_id = cpc.copc_id 
                             FROM planillas.conceptos_presu_cont cpc,
                                  planillas.planilla_empleados plaemp 
                             WHERE plaec.plaemp_id = plaemp.plaemp_id  
                                   AND plaemp.pla_id = ? 
                                   AND plaec.conc_id = cpc.conc_id  
                                   AND copc_estado = 1
                           ";

                    $this->_CI->db->query($sql, array($planilla_id));

                  
                }
        
        }
        else
        {
            
            if($considerar_modificaciones)
            {

                  $sql = " UPDATE planillas.planilla_empleados plaemp 
                           SET tarea_id = empre.tarea_id, 
                               fuente_id = empre.fuente_id, 
                               tipo_recurso = empre.tipo_recurso, 
                               empre_id = empre.empre_id
                           FROM  planillas.empleado_presupuestal empre,
                                 planillas.planillas pla  

                           WHERE plaemp.pla_id = pla.pla_id AND 
                                 plaemp.indiv_id = empre.indiv_id AND   
                                 plaemp.plaemp_afectacion_actualizada = 0 AND 
                                 empre.empre_estado = 1  AND 
                                 pla.ano_eje = empre.ano_eje AND 
                                 plaemp.pla_id = ?  
                  
                         ";

                  $this->_CI->db->query($sql, array($planilla_id));
            }
            else
            {

                    

                 $sql = "  UPDATE planillas.planilla_empleados plaemp 
                           SET tarea_id = empre.tarea_id, 
                               fuente_id = empre.fuente_id, 
                               tipo_recurso = empre.tipo_recurso, 
                               empre_id = empre.empre_id,
                               plaemp_afectacion_actualizada = 0
                           FROM  planillas.empleado_presupuestal empre, 
                                 planillas.planillas pla  

                           WHERE  plaemp.pla_id = pla.pla_id AND 
                                  plaemp.indiv_id = empre.indiv_id AND 
                                  empre.empre_estado = 1 AND 
                                  pla.ano_eje = empre.ano_eje AND 
                                  plaemp.pla_id = ?  
                 
                        ";

                 $this->_CI->db->query($sql, array($planilla_id));
            }


            $sql =  " UPDATE planillas.planilla_empleado_concepto plaec 
                      SET tarea_id = plaemp.tarea_id, 
                          fuente_id = plaemp.fuente_id, 
                          tipo_recurso = plaemp.tipo_recurso
                   
                       FROM planillas.planilla_empleados plaemp 
                       WHERE plaec.plaemp_id = plaemp.plaemp_id  AND plaemp.pla_id = ?  
                     "; 
            
            $this->_CI->db->query($sql, array($planilla_id));

        

           $sql = " UPDATE planillas.planilla_empleado_concepto plaec 
                    SET clasificador_id = cpc.id_clasificador,
                        copc_id = cpc.copc_id 
                    FROM planillas.conceptos_presu_cont cpc,
                         planillas.planilla_empleados plaemp 
                    WHERE plaec.plaemp_id = plaemp.plaemp_id  
                          AND plaemp.pla_id = ? 
                          AND plaec.conc_id = cpc.conc_id  
                          AND copc_estado = 1
                  ";

            $this->_CI->db->query($sql, array($planilla_id));



        }



    }

    
    public function comprobar_sistema_pensiones()
    {


    }



    /*
    public function getEstado($planilla_id, $is_key = false){
        
        
        
         $sql = " SELECT mov.plaes_id, est.plaes_nombre FROM planillas.planillas pla 
                                  LEFT JOIN planillas.planilla_movimiento mov ON mov.pla_id = pla.pla_id AND mov.plamo_estado = 1 
                                  LEFT JOIN planillas.planilla_estados est ON mov.plaes_id = est.plaes_id  
                                  WHERE ";  
         
          $sql.= ($is_key) ? " pla.pla_key = ? " : " pla.pla_id = ? ";
          
       $rs = $this->_CI->db->query($sql,array($planilla_id))->result_array();
        
       return $rs[0]['plaes_id'];
         
        
    }*/
    
    
    public function get_afectacion_presupuestal($pla_id = 0, $params = array() )
    {

        $this->_CI->load->library(array('App/planilla'));
        
        $values = array($pla_id);

        $planilla_info = $this->_CI->planilla->get($pla_id);

        $mododinero_afectacion = $planilla_info['pla_afectadinero_modo'];

 

        if(CONECCION_AFECTACION_PRESUPUESTAL)
        { 

              if($mododinero_afectacion == PLATI_AFECTARDINERO_MODO_SALDO )
              {

					// FROMAN 03.10.2016 17:28
                    $sql_disponible = "   SELECT  t_s.ano_eje, 
                                       t_s.tarea_id, 
                                       t_s.fuente_financ, 
                                       t_s.tipo_recurso, 
                                       t_s.id_clasificador,                                      
									   
                                      (CASE
											WHEN (SUM(t_s.monto_pia + t_s.monto_mov_ha + COALESCE(s_query.monto_retorno,0) - (t_s.monto_mov_de + t_s.monto_egreso + t_s.monto_egreso_ctb)) > 0) THEN 
												SUM(t_s.monto_pia + t_s.monto_mov_ha + COALESCE(s_query.monto_retorno,0) - (t_s.monto_mov_de + t_s.monto_egreso + t_s.monto_egreso_ctb))
											ELSE 
												0
										END) AS disponible
							FROM sag.tarea_saldo t_s
							   left join (select tarea,fte_fto,tip_recur,clasificador_id,sum(COALESCE(monto_retorno,0)) as monto_retorno from sag.subsidy_return group by tarea,fte_fto,tip_recur,clasificador_id) as s_query 
									on cast(s_query.tarea as int)=t_s.tarea_id and s_query.fte_fto=t_s.fuente_financ and s_query.tip_recur=t_s.tipo_recurso and s_query.clasificador_id=t_s.id_clasificador

                               WHERE t_s.tareacomp_id = 0
                               GROUP BY t_s.ano_eje, t_s.tarea_id, t_s.fuente_financ, t_s.tipo_recurso, t_s.id_clasificador
                               ORDER BY t_s.ano_eje, t_s.tarea_id, t_s.fuente_financ, t_s.tipo_recurso, t_s.id_clasificador
     
                           ";

              } 
              else if($mododinero_afectacion == PLATI_AFECTARDINERO_MODO_PREAFECTACION )
              { 

                    $sql_disponible = " SELECT  
                                              pre.ano_eje, pre.tarea_id, pre.fuente_financ, pre.tipo_recurso, pre_det.id_clasificador, 
                                               SUM(pre_det.preaclasif_monto - pre_det.preaclasif_monto_gasto)  as disponible

                                        FROM sag.preafecta pre
                                        LEFT JOIN sag.preafecta_clasif pre_det ON pre.prea_id = pre_det.prea_id 
                                        WHERE pre_det.preaclasif_monto > pre_det.preaclasif_monto_gasto AND tareacomp_id = 0 AND pre.prea_estado = '1' AND pre_det.preaclasif_estado = '1' 

                                        GROUP BY pre.ano_eje, pre.tarea_id, pre.fuente_financ, pre.tipo_recurso, pre_det.id_clasificador

                                        ORDER BY pre.ano_eje, pre.tarea_id, pre.fuente_financ, pre.tipo_recurso, pre_det.id_clasificador


                                     ";


              }

        }


          $sql = "   SELECT  

                            afectacion.*,
                            tarea.tarea_nombre,
							CASE WHEN cast(tarea.ano_eje as int) >= 2021 
								THEN tarea.sec_func
								ELSE ( tarea.sec_func || '-' || tarea.tarea_nro )
							END as tarea_codigo,
                            ( afectacion.fuente_id || '-' || afectacion.tipo_recurso ) as fuente_codigo,
                            ff.nombre as fuente,
                            tr.nombre as tiporecurso,
                            (par.tipo_transaccion ||'.'|| par.generica || '.' || par.subgenerica || '.' || par.subgenerica_det || '.' || par.especifica || '.' || par.especifica_det || ' - ' || par.descripcion ) as partida, -- saldo de la partida
                            
                            afectacion.total as gasto
                           
                    ";
                    
                  if(CONECCION_AFECTACION_PRESUPUESTAL)
                  {             
                      $sql.="        ,disponible.disponible ";

                  }
                  

           $sql.=" 
                         FROM ( 

                                SELECT  
                                        pla.ano_eje, plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.clasificador_id, SUM(plaec_value) as total

                                FROM planillas.planilla_empleado_concepto plaec 
                                LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaec.plaemp_id AND plaemp_estado = 1
                                LEFT JOIN planillas.planillas pla ON pla.pla_id = plaemp.pla_id 

                                WHERE pla.pla_id = ? AND
                                      plaec.plaec_estado = 1 AND 
                                      plaec.plaec_calculado = 1 AND 
                                      plaec.plaec_marcado = 1 AND 
                                      plaec.plaec_value > 0 AND 
                                      ( plaec.conc_tipo = ".TIPOCONCEPTO_INGRESO." OR plaec.conc_tipo = ".TIPOCONCEPTO_APORTACION." )

                                GROUP BY pla.ano_eje,plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.clasificador_id   

                                ORDER BY pla.ano_eje,plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.clasificador_id 
          

                         ) as afectacion
                         
                ";


                 if(CONECCION_AFECTACION_PRESUPUESTAL)
                 { 

              $sql.="    

                         LEFT JOIN (
                            
                                    ".$sql_disponible."

                               

                         ) as disponible ON afectacion.ano_eje = disponible.ano_eje 
                                            AND afectacion.tarea_id = disponible.tarea_id 
                                            AND afectacion.fuente_id = disponible.fuente_financ 
                                            AND afectacion.tipo_recurso = disponible.tipo_recurso 
                                            AND afectacion.clasificador_id = disponible.id_clasificador 
                 
                     ";                       

                  } 

           $sql.=" 

                     LEFT JOIN sag.tarea ON tarea.tarea_id = afectacion.tarea_id
                     LEFT JOIN pip.fuente_financ ff ON afectacion.fuente_id = ff.fuente_financ AND afectacion.ano_eje = ff.ano_eje
                     LEFT JOIN pip.tipo_recurso tr ON afectacion.tipo_recurso = tr.tipo_recurso AND afectacion.ano_eje = tr.ano_eje AND ff.fuente_financ = tr.fuente_financ 
                     LEFT JOIN pip.especifica_det par ON afectacion.clasificador_id = par.id_clasificador AND afectacion.ano_eje = par.ano_eje

                 "; 


          /*
          $w = false;

          if($params['tarea'] !=''){
             $sql.= " WHERE a_p.tarea_id = ? ";
             $values[] = $params['tarea']; 
             $w = true;
          }


         if($params['partida'] !=''){

             $sql.= ($w) ? ' AND ' : ' WHERE ';
             $sql.= "   a_p.id_clasificador = ?  AND a_p.ano_eje = ? ";
             $values[] = $params['partida']; 
             $values[] = $params['ano_eje']; 
             
          }


        $sql .="         
                ORDER  BY a_p.tarea_id, a_p.id_clasificador , a_p.fuente_id
 

         "; */


        $rs = $this->_CI->db->query($sql,  $values )->result_array();
        
        return $rs;
        
         
    }
    

    public function get_resumen_contable($params = array()){


        // Conceptos debe 

        //             DEBE
        // Conceptos  

       // Conceptos           HABER 

        $values = array();

        $columna_cpc = ($params['modo'] == 'debe') ? 'cuentadebe_id' : 'cuentahaber_id';

        $sql = "SELECT  plaec.fuente_id, 
                        plaec.tipo_recurso,  
                        cc.ccont_codigo, 
                        cc.ccont_nombre,  
                        SUM(COALESCE(plaec_value,0)) as total

                FROM planillas.planilla_empleado_concepto plaec 
                LEFT JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id 
                LEFT JOIN planillas.conceptos_presu_cont cpc ON plaec.conc_id = cpc.conc_id AND cpc.copc_estado = 1  
                LEFT JOIN planillas.concepto_tipo conc_tipo ON plaec.conc_tipo = conc_tipo.concti_id 
                LEFT JOIN planillas.cuenta_contable cc ON cpc.".$columna_cpc." = cc.ccont_id
                LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaec.plaemp_id AND plaemp_estado = 1
                LEFT JOIN planillas.planillas pla ON pla.pla_id = plaemp.pla_id 

                WHERE pla.pla_id = ? AND
                      plaec.plaec_estado = 1 AND 
                      plaec.plaec_calculado = 1 AND 
                      plaec.plaec_marcado = 1 AND 
                      plaec.plaec_value > 0  
              ";
              
              $values[] = $params['pla_id'];

              $sql.= " AND plaec.fuente_id = ? AND plaec.tipo_recurso = ? ";
              
              $values[] = $params['fuente'];
              $values[] = $params['tiporecurso'];
     
              if($params['modo'] == 'debe'){

                 $sql.="  AND ( cpc.cuentadebe_id is not null AND  cpc.cuentadebe_id > 0) "; 
              }
              else
              {
                 $sql.=" AND ( cpc.cuentahaber_id is not null AND cpc.cuentahaber_id > 0 ) ";
              }

        
        $sql.=" GROUP BY plaec.fuente_id, plaec.tipo_recurso, cc.ccont_codigo, cc.ccont_nombre 
                ORDER BY plaec.fuente_id, plaec.tipo_recurso, cc.ccont_codigo, cc.ccont_nombre ";



        $rs = $this->_CI->db->query($sql, $values)->result_array();

        return $rs;

    }


    public function get_resumen_contable_siaf($params = array()){


        // Conceptos debe 

        //             DEBE
        // Conceptos  

       // Conceptos    HABER 

        $values = array();

        $columna_cpc = ($params['modo'] == 'debe') ? 'cuentadebe_id' : 'cuentahaber_id';

        $sql = "SELECT  plasi.siaf, 
                        cc.ccont_codigo, 
                        cc.ccont_nombre,  
                        SUM(COALESCE(plaec_value,0)) as total

                FROM planillas.planilla_siaf plasi
                INNER JOIN planillas.planillas pla ON plasi.pla_id = pla.pla_id 
                INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plaes_id = ? AND plamo.plamo_estado = 1 
                INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp_estado = 1
                INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec_estado = 1 AND plaec.fuente_id = plasi.fuente_id AND plaec.tipo_recurso = plasi.tipo_recurso
                INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id 
                LEFT JOIN planillas.conceptos_presu_cont cpc ON plaec.conc_id = cpc.conc_id AND cpc.copc_estado = 1  
                LEFT JOIN planillas.cuenta_contable cc ON cpc.".$columna_cpc." = cc.ccont_id 

                WHERE plasi.plasiaf_estado = 1 AND 
                      plasi.ano_eje = ? AND plasi.siaf = ? AND 
                      plaec.conc_tipo = ? AND
                      plaec.plaec_calculado = 1 AND 
                      plaec.plaec_marcado = 1 AND 
                      plaec.plaec_value > 0  
              ";

              $values[] = ESTADOPLANILLA_FINALIZADO;
              $values[] = $params['anio'];
              $values[] = $params['siaf'];
              $values[] = $params['conc_tipo'];
 
     
              // if($params['modo'] == 'debe'){

              //    $sql.="  AND ( cpc.cuentadebe_id is not null AND  cpc.cuentadebe_id > 0) "; 
              // }
              // else
              // {
              //    $sql.=" AND ( cpc.cuentahaber_id is not null AND cpc.cuentahaber_id > 0 ) ";
              // }

        
        $sql.=" GROUP BY plasi.siaf, cc.ccont_codigo, cc.ccont_nombre 
                ORDER BY plasi.siaf, cc.ccont_codigo, cc.ccont_nombre ";



        $rs = $this->_CI->db->query($sql, $values)->result_array();

        return $rs;

    }

    public function get_neto_planilla($params = array()){


      $sql = "     SELECT  datos.pla_id, ( datos.ingresos - datos.descuentos ) as neto 
                       

                     FROM ( 

                            SELECT 
                                  pla.pla_id,

                                  SUM((CASE WHEN plaec.conc_tipo = 1 THEN     

                                                plaec.plaec_value 
                                       ELSE 
                                                0 
                                       END )) as ingresos,

                                  SUM((CASE WHEN plaec.conc_tipo = 2 THEN     

                                           plaec.plaec_value 

                                       ELSE 
                                           0 
                                       END )) as descuentos,


                                  SUM((CASE WHEN plaec.conc_tipo = 3 THEN    

                                            plaec.plaec_value 
                                      ELSE 
                                           0 
                                      END )) as aportacion 


                            FROM planillas.planillas pla
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id  AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   
      
                            WHERE pla.pla_id  = ?  AND plaec.fuente_id = ? AND plaec.tipo_recurso = ? 
                            GROUP BY pla.pla_id  

                  ) as datos 
  
      ";

      $values = array($params['pla_id'], $params['fuente'], $params['tiporecurso']); 

      list($rs) = $this->_CI->db->query($sql, $values )->result_array();

      return $rs; 

    }


    public function get_neto_planilla_siaf($params = array()){


      $sql = "     SELECT  datos.pla_id, ( datos.ingresos - datos.descuentos ) as neto 
                       

                     FROM ( 

                            SELECT 
                                  pla.pla_id,

                                  SUM((CASE WHEN plaec.conc_tipo = 1 THEN     

                                                plaec.plaec_value 
                                       ELSE 
                                                0 
                                       END )) as ingresos,

                                  SUM((CASE WHEN plaec.conc_tipo = 2 THEN     

                                           plaec.plaec_value 

                                       ELSE 
                                           0 
                                       END )) as descuentos,


                                  SUM((CASE WHEN plaec.conc_tipo = 3 THEN    

                                            plaec.plaec_value 
                                      ELSE 
                                           0 
                                      END )) as aportacion 


                            FROM planillas.planillas pla 
                            INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plaes_id = ".ESTADOPLANILLA_FINALIZADO." AND plamo.plamo_estado = 1 
                            INNER JOIN planillas.planilla_siaf plasi ON plasi.pla_id = pla.pla_id AND plasi.plasiaf_estado = 1 AND plasi.ano_eje = ? AND plasi.siaf = ?
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id  AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1 AND plaec.fuente_id = plasi.fuente_id AND plaec.tipo_recurso = plasi.tipo_recurso   
                            WHERE pla.pla_estado = 1 
                            GROUP BY pla.pla_id  

                  ) as datos 
    
      ";

      $values = array($params['anio'], $params['siaf']); 

      list($rs) = $this->_CI->db->query($sql, $values )->result_array();

      return $rs; 

    }



    /*
    public function get_detalle_afectacion($TIPO = 'TAREAS', $planilla_id){


        if($TIPO == 'TAREAS')
        {


            $sql = "SELECT  
                        resumen.tarea_id,
                         (tarea.sec_func || '-' || tarea.tarea_nro || ' ' || tarea.tarea_nombre ) as tarea
                    FROM 
              
                        ( SELECT  pec.tarea_id 
                             FROM planillas.planilla_empleado_concepto pec
                             LEFT JOIN planillas.planilla_empleados pemp ON pec.plaemp_id = pemp.plaemp_id  
                             WHERE pemp.pla_id = ?  AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1
                         GROUP BY pec.tarea_id   ) as resumen

                    LEFT JOIN sag.tarea ON resumen.tarea_id = tarea.tarea_id 
                    ORDER BY tarea;
                ";
        }    
        else if($TIPO=='PARTIDAS'){

            $sql = " SELECT resumen.*,  
                            (par.tipo_transaccion ||'.'|| par.generica || '.' || par.subgenerica || '.' || par.subgenerica_det || '.' || par.especifica || '.' || par.especifica_det || ' - ' || par.descripcion ) as partida 
                    FROM 
                    ( 
                      SELECT   pec.clasificador_id, pec.ano_eje
                              FROM planillas.planilla_empleado_concepto pec
                              LEFT JOIN planillas.planilla_empleados pemp ON pec.plaemp_id = pemp.plaemp_id 
                              WHERE pemp.pla_id = ?  AND pec.plaec_estado = 1
                              GROUP BY  pec.clasificador_id, pec.ano_eje
                    ) as resumen 
                    LEFT JOIN  pip.especifica_det par ON  resumen.id_clasificador = par.id_clasificador AND resumen.ano_eje = par.ano_eje

                    ORDER BY partida; ";

        }

        $rs = $this->_CI->db->query($sql,array($planilla_id))->result_array();
         
 
        return $rs;


    }
    */

    public function get_afectacion_fuentes($planilla)
    {

       
       if(is_array($planilla) === false)
       { 

           $sql = " SELECT 

                       afectacion.fuente_id, 
                       afectacion.tipo_recurso,  
                       ff.abrev as fuente_abrev, 
                       ff.nombre as fuente_nombre, 
                       tr.nombre as tipo_recurso_nombre,
                       plasi.plasiaf_id,
                       plasi.siaf

                   FROM (  
                       
                       SELECT  distinct pla.pla_id, pla.ano_eje, plaec.fuente_id, plaec.tipo_recurso
                                                             FROM planillas.planilla_empleado_concepto plaec
                                                             INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1
                                                             INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_id = ?
                                                    
                                                   WHERE  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1 AND plaec.conc_tipo IN (".TIPOCONCEPTO_INGRESO.",".TIPOCONCEPTO_APORTACION.")
                   
                   ) as afectacion 
                   LEFT JOIN pip.fuente_financ ff ON afectacion.fuente_id = ff.fuente_financ AND afectacion.ano_eje = ff.ano_eje    
                   LEFT JOIN pip.tipo_recurso tr ON afectacion.tipo_recurso = tr.tipo_recurso AND  tr.fuente_financ = ff.fuente_financ AND afectacion.ano_eje = tr.ano_eje  
                   LEFT JOIN planillas.planilla_siaf plasi ON afectacion.pla_id = plasi.pla_id  AND afectacion.fuente_id = plasi.fuente_id AND afectacion.tipo_recurso = plasi.tipo_recurso AND plasi.plasiaf_estado = 1
                  ";

            $rs = $this->_CI->db->query($sql, array($planilla))->result_array();

       }
       else
       {


           $in_planillas = implode(',', $planilla );
          
              $sql = " SELECT  
                          afectacion.fuente_id, 
                          afectacion.tipo_recurso,  
                          ff.abrev as fuente_abrev, 
                          ff.nombre as fuente_nombre, 
                          tr.nombre as tipo_recurso_nombre

                      FROM (  
                          
                          SELECT pla.ano_eje, plaec.fuente_id, plaec.tipo_recurso
                          FROM planillas.planilla_empleado_concepto plaec
                          INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_id  IN (".$in_planillas.")
                                                       
                           WHERE  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1  AND plaec.conc_tipo IN (".TIPOCONCEPTO_INGRESO.",".TIPOCONCEPTO_APORTACION.")    

                           GROUP BY  pla.ano_eje, plaec.fuente_id, plaec.tipo_recurso  

                      ) as afectacion 
                      LEFT JOIN pip.fuente_financ ff ON afectacion.fuente_id = ff.fuente_financ AND afectacion.ano_eje = ff.ano_eje    
                      LEFT JOIN pip.tipo_recurso tr ON afectacion.tipo_recurso = tr.tipo_recurso AND  tr.fuente_financ = ff.fuente_financ AND afectacion.ano_eje = tr.ano_eje  
                      ";

              $rs = $this->_CI->db->query($sql, array() )->result_array();
        }

 
 
        return $rs;
 
    }


    public function generar_siaf()
    {

    }

    public function afectar_presupuestalmente($pla_id, $retornar_dinero = false )
    {

         $planilla_info = $this->_CI->planilla->get($pla_id);
 
         $this->_CI->db->trans_begin();


          $sql_gastoplanilla = "  SELECT  
                                          pla.ano_eje, plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.clasificador_id, SUM(plaec_value) as total

                                  FROM planillas.planilla_empleado_concepto plaec 
                                  LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaec.plaemp_id AND plaemp_estado = 1
                                  LEFT JOIN planillas.planillas pla ON pla.pla_id = plaemp.pla_id 

                                  WHERE pla.pla_id = ? AND
                                        plaec.plaec_estado = 1 AND 
                                        plaec.plaec_calculado = 1 AND 
                                        plaec.plaec_value > 0 AND 
                                        ( plaec.conc_tipo = ".TIPOCONCEPTO_INGRESO." OR plaec.conc_tipo = ".TIPOCONCEPTO_APORTACION." )

                                  GROUP BY pla.ano_eje,plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.clasificador_id    
                                  ORDER BY pla.ano_eje,plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.clasificador_id 
                               ";
        
         $afectacion_planilla = $this->_CI->db->query($sql_gastoplanilla, array($pla_id))->result_array();


         if($planilla_info['estado_id'] == ESTADOPLANILLA_PROCESADA ||( $retornar_dinero == true && $planilla_info['estado_id'] == ESTADOPLANILLA_FINALIZADO) )
         {

                $planilla_info = $this->_CI->planilla->get($pla_id);

                $mododinero_afectacion = $planilla_info['pla_afectadinero_modo'];


                if($mododinero_afectacion == PLATI_AFECTARDINERO_MODO_PREAFECTACION )
                { 
                     
                    
                    if($retornar_dinero == false)
                    {
                       $signo = '+';
                       $saldo_key = 'disponible';

                    }
                    else
                    {
                        $signo = '-';
                        $saldo_key = 'preaclasif_monto_gasto';
                    }

                    foreach($afectacion_planilla as $planilla_clasificador)
                    {

                        $total_gasto_clasificador = $planilla_clasificador['total'] * 1.00;

                        $sql =  " SELECT 
                                     pre_det.prea_id, pre_det.preaclasif_item, 
                                     pre.ano_eje, pre.tarea_id, pre.fuente_financ, pre.tipo_recurso, 
                                     pre_det.id_clasificador, ( pre_det.preaclasif_monto - pre_det.preaclasif_monto_gasto ) as disponible, 
                                     preaclasif_monto_gasto

                                  FROM sag.preafecta pre 
                                  LEFT JOIN sag.preafecta_clasif pre_det ON pre.prea_id = pre_det.prea_id 

                                  WHERE pre_det.preaclasif_estado = '1' 
                                        AND pre.prea_estado = '1' 
                                       

                                        AND pre.ano_eje = ? 
                                        AND pre.tarea_id = ?
                                        AND pre.fuente_financ = ? 
                                        AND pre.tipo_recurso = ? 
                                        AND pre_det.id_clasificador = ?  

                                  ORDER BY prea_id
                                ";


                        $saldo_clasificador = $this->_CI->db->query($sql, array($planilla_clasificador['ano_eje'], 
                                                                                $planilla_clasificador['tarea_id'], 
                                                                                $planilla_clasificador['fuente_id'], 
                                                                                $planilla_clasificador['tipo_recurso'], 
                                                                                $planilla_clasificador['clasificador_id']) )->result_array();

                        if(sizeof($saldo_clasificador) == 0)
                        {
                             $this->_CI->db->trans_rollback();
                             return false;
                        }                        

                         
                        foreach($saldo_clasificador as $saldo) // Un item x detalle de prefectacion
                        {

                            $disponible = $saldo[$saldo_key] * 1.00;


                            if($total_gasto_clasificador <= $disponible)
                            {
                                $gasto = $total_gasto_clasificador;
                            }
                            else
                            {
                                $gasto = $disponible;
                            }

                            if($total_gasto_clasificador > 0) // Si es menor o cero no hay necesidad de seguir descontando
                            { 

                                $sql = " UPDATE sag.preafecta_clasif 
                                         SET preaclasif_monto_gasto = preaclasif_monto_gasto ".$signo." ? 
                                         WHERE prea_id = ? AND preaclasif_item = ?  ";

                                $ok = $this->_CI->db->query($sql, array( $gasto, $saldo['prea_id'], $saldo['preaclasif_item'] ) );
                                
                                if($ok)
                                { 
                                    $total_gasto_clasificador-= $gasto;
                                }
                             
                            }


                        }

                        if($total_gasto_clasificador > 0)  
                        {   
                          
                            $this->_CI->db->trans_rollback();
                            return false;
                        }
 
                    }
 
                    if($this->_CI->db->trans_status() === FALSE) 
                    {
                        $this->_CI->db->trans_rollback();
                        return false;
                            
                    }else{
                                
                        $this->_CI->db->trans_commit();
                        return true;
                    } 


               } 
               else
               {

                      // SINO ES PREAFECTACION, DEL SALDO PRESUPUESTAL

                      /* $sql = " sag.tarea_saldo_update_amount_egress(_ano_eje, _sec_ejec, _tarea_id , _tareacomp_id, _fuente_financ, _tipo_recurso, _id_clasificador, _tipo_mov , _monto numeric, _usuracce_id )
                                RETURNS integer AS
                              $BODY$";*/
                
                    $xp = ($retornar_dinero == false) ? 1 : -1;

                    $tipo_mov = '';

                   
                    foreach($afectacion_planilla as $planilla_clasificador)
                    {

                        $total_gasto_clasificador = $planilla_clasificador['total'] * $xp;
   
                         /*  $valor = $this->_CI->db->call_function('sag.tarea_saldo_update_amount_egress', $planilla_clasificador['ano_eje'], $sec_ejec, $planilla_clasificador['tarea_id'], 0, $planilla_clasificador['fuente_id'], $planilla_clasificador['tipo_recurso'], $planilla_clasificador['clasificador_id'], 'D', $total_gasto_clasificador, '0' );*/
 
                        $sql = "  SELECT sag.tarea_saldo_update_amount_egress(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

              
                        $this->_CI->db->query($sql, array($planilla_clasificador['ano_eje'], SEC_EJEC, $planilla_clasificador['tarea_id'], 0, $planilla_clasificador['fuente_id'], $planilla_clasificador['tipo_recurso'], $planilla_clasificador['clasificador_id'], $tipo_mov, $total_gasto_clasificador, '0')  );
                       


                    }


                    if($this->_CI->db->trans_status() === FALSE) 
                    {
                        $this->_CI->db->trans_rollback();
                        return false;
                            
                    }
                    else
                    {
                                
                        $this->_CI->db->trans_commit();
                        return true;
                    } 

               }


         }
         else
         {

             $this->_CI->db->trans_rollback();
             return true;
         } 

    }


    public function get_resumen_conceptos($pla_id = '0', $tipo = '0' , $afectacion = array() )
    {
     

        $params = array($pla_id);
     

        $sql = "SELECT  rs_conc.conc_id,  
                        rs_conc.conc_id as concepto_id,

                        ( CASE WHEN gr.gvc_nombre is null THEN 
                                 conc_nombre
                          ELSE  
                                 gr.gvc_nombre || ' - ' || conc_nombre
                          END   ) as conc_nombre,  
                                
                          conc.conc_nombrecorto as nombre_corto,  
                          conc.conc_tipo,
                          rs_conc.monto as monto 
                        
                    FROM ( 
    
                             SELECT t_rs.conc_id, 
                                    t_rs.conc_tipo, 
                                    SUM(t_rs.monto) as monto   

                             FROM (

                                       SElECT ( CASE WHEN ggvc.gvcg_id is null THEN
                                                  pec.conc_id   
                                                    ELSE
                                                         ggvc.conc_id_ref           
                                                    END
                                                ) as conc_id,
                                                pec.conc_tipo,    
                                     ";
                                         
                                            if( sizeof($afectacion) > 0 )
                                            {

                                                $sql.=" pec.fuente_id, pec.tipo_recurso,  ";
             
                                            }        
             

                                            $sql.= "        

                                                    pec.plaec_value as monto
                                            
                                             FROM planillas.planilla_empleado_concepto pec 
                                             INNER JOIN planillas.planilla_empleados pemp ON pec.plaemp_id = pemp.plaemp_id AND pemp.plaemp_estado = 1
                                             LEFT JOIN planillas.conceptos concs ON pec.conc_id = concs.conc_id
                                             LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON concs.gvc_id = ggvc.gvc_id  AND  gvcg_estado = 1                                
                                             WHERE pemp.pla_id =  ? AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1

                                            ";
                              
                                             if(sizeof($afectacion) > 0 )
                                             {
                                                
                                                $sql.= " AND pec.fuente_id = ? AND pec.tipo_recurso = ? ";
                                                $params[] = $afectacion['fuente'];
                                                $params[] = $afectacion['tiporecurso'];
                                             }   
             
                    $sql.= "     

                                         ) t_rs

                             GROUP BY  conc_id, t_rs.conc_tipo 

                             ";
                                         
                            if(sizeof($afectacion) > 0 )
                            {

                                  $sql.=" , t_rs.fuente_id, t_rs.tipo_recurso  ";
                                         
                            }        
                             

                    $sql.= "     


                         ) as rs_conc 
 
                     
                     LEFT JOIN planillas.conceptos conc ON rs_conc.conc_id = conc.conc_id  
                     LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = conc.gvc_id  
                     
                ";
        
        if($tipo != '0'){
         
            $sql.= " WHERE rs_conc.conc_tipo = ? ";
            $params[] = $tipo;
        }
         
        $sql.= " ORDER BY conc_nombre, rs_conc.conc_tipo   ";
             


        $rs = $this->_CI->db->query($sql,$params)->result_array();
        
        return $rs;
        
        
    }
    
    public function get_concepto_trabajadores($planilla,$concepto)
    {
        
        $sql = " SELECT    

                    pec.conc_id,
                    pec.conc_id as concepto_id,
 

                    ( CASE WHEN gr.gvc_nombre is null THEN 
                              conc_nombre
                      ELSE  
                             gr.gvc_nombre || ' - ' || conc_nombre
                      
                      END   ) as conc_nombre, 
                
                    conc.conc_tipo, 
                    pec.plaec_value as monto,
                    
                    (par.tipo_transaccion ||'.'|| par.generica || '.' || par.subgenerica || '.' || par.subgenerica_det || '.' || par.especifica || '.' || par.especifica_det || ' - ' || par.descripcion ) as partida,
                    ind.indiv_nombres, ind.indiv_appaterno,ind.indiv_apmaterno, ind.indiv_dni,

                    ( CASE WHEN gr.gvc_nombre is null THEN 
                             '------- '
                     ELSE  
                             gr.gvc_nombre
                       
                     END   ) as grupo_nombre  

        				 FROM planillas.planilla_empleado_concepto pec
        				 LEFT JOIN planillas.planilla_empleados pemp ON pec.plaemp_id = pemp.plaemp_id 
		             LEFT JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id   
                 LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = conc.gvc_id  
                 LEFT JOIN pip.especifica_det par ON pec.clasificador_id = par.id_clasificador AND pec.ano_eje = par.ano_eje  
				         LEFT JOIN public.individuo ind ON pemp.indiv_id = ind.indiv_id	
                 
                 WHERE pemp.pla_id = ? AND pec.conc_id = ?  AND pec.plaec_estado = 1 
                                 
                 ORDER BY  indiv_appaterno, indiv_apmaterno, indiv_nombres, conc_nombre
              ";
        
        $rs = $this->_CI->db->query($sql,array($planilla,$concepto))->result_array();

        return $rs;
        
    }
 
    public function cancelar_proceso($pla_id = '0')
    {

        $this->_CI->load->library(array('App/planillamovimiento'));

        // Reload el valor de las variables dinamicas 
        // Total de hijos construccion civil      
        $this->actualizar_total_de_hijos($pla_id, EDAD_MAXIMA_ESCOLARIDAD, VARIABLE_TOTAL_HIJOS_CONSTRUCCION_CIVIL);
        

        $this->_CI->planillamovimiento->registrar(array('pla_id' => $pla_id, 
                                                        'plaes_id' => ESTADOPLANILLA_ELABORADA, 
                                                        'plamo_descripcion' => 'CANCELACION DE PROCESO DE LA PLANILLA' ));


        $sql = " UPDATE planillas.planilla_empleado_concepto_mov 
                 SET    plaecm_estado = 0 
                 WHERE  plaec_id IN ( SELECT pec.plaec_id 
                                      FROM planillas.planilla_empleado_concepto pec 
                                      INNER JOIN planillas.planilla_empleados pe  ON pec.plaemp_id = pe.plaemp_id AND pe.plaemp_estado = 1
                                      WHERE pe.pla_id = ?  ) ";


        $rs = $this->_CI->db->query($sql, array($pla_id));  


        $sql = " UPDATE planillas.planilla_empleado_concepto 
                 SET    plaec_value = 0, plaec_calculado = 0, plaec_value_pre = 0
                 WHERE  plaemp_id IN ( SELECT plaemp_id FROM planillas.planilla_empleados pe WHERE pe.plaemp_estado = 1 AND pe.pla_id = ?  ) ";


        $rs = $this->_CI->db->query($sql, array($pla_id));  



        return ($rs )  ? true : false;

    }





    public function anular_proceso($pla_id = '0', $params = array() )
    {

        $this->_CI->load->library(array('App/planillamovimiento'));


        $retornar_dinero = true;
        
        if(CONECCION_AFECTACION_PRESUPUESTAL)
        {

          $ok = $this->afectar_presupuestalmente($pla_id, $retornar_dinero);
        }
        else
        {
          $ok = true;
        }

        if($ok == true)
        { 
            
            $this->_CI->planillamovimiento->registrar(array('pla_id' => $pla_id, 
                                                            'plaes_id' => ESTADOPLANILLA_PROCESADA, 
                                                            'plamo_descripcion' => ' [PROCESO ANULADO] ' ));
     

            $sql = " UPDATE planillas.planilla_empleado_concepto 
                     SET    plaec_afectado_value = plaec_value 
                     WHERE  plaemp_id IN ( SELECT plaemp_id FROM planillas.planilla_empleados pe WHERE pe.plaemp_estado = 1 AND pe.pla_id = ?  ) ";


            $ok = $this->_CI->db->query($sql, array($pla_id));  

        }


        return ($ok)  ? true : false;

    }

    
    
    public function eliminar($pla_id = 0)
    {
 
        $this->_CI->load->library(array('App/planillamovimiento'));

        $this->_CI->db->trans_begin();

        $sql = " UPDATE planillas.planilla_empleado_concepto
                 SET plaec_estado = 0 
                 WHERE 
                     plaemp_id IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE pla_id = ? ) ";

        $this->_CI->db->query($sql, array($pla_id));

        

        $sql = " UPDATE planillas.planilla_empleado_variable
                 SET plaev_estado = 0 
                 WHERE 
                     plaemp_id IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE pla_id = ? ) ";
        $this->_CI->db->query($sql, array($pla_id));
                      

        $sql = " UPDATE planillas.planilla_empleados SET plaemp_estado = 0 WHERE pla_id = ? ";
        $this->_CI->db->query($sql, array($pla_id));

        $sql = " UPDATE planillas.planillas SET pla_estado = 0 WHERE pla_id = ? ";             
        $this->_CI->db->query($sql, array($pla_id));
         

        $this->_CI->planillamovimiento->registrar(array('pla_id' => $pla_id, 
                                                        'plaes_id' => ESTADOPLANILLA_ANULADA, 
                                                        'plamo_descripcion' => 'ANULACION DE PLANILLA' ));



        $sql = " UPDATE  planillas.hojaasistencia_emp_dia 
                 SET pla_id = 0, plaemp_id = 0, hoaed_importado = 0, plaasis_id = 0
                 WHERE pla_id = ? AND  hoaed_estado = 1 ";

        $rs = $this->_CI->db->query($sql, array($pla_id));  


        if($this->_CI->db->trans_status() === FALSE) 
        {
            $this->_CI->db->trans_rollback();
            return false;
                
        }else{
                    
            $this->_CI->db->trans_commit();
            return true;
        } 


    }



    public function actualizar_calculo_conceptos($planilla_id)
    {
 
        $this->_CI->load->library(array('App/planillaempleadovariable'));

        $sql ="  SELECT 
                        dd3.*,
                        empvar.empvar_id,
                      
                        ( CASE WHEN empvar.empvar_id is null THEN
                          vars.vari_valordefecto
                                ELSE    
                          empvar.empvar_value
                          END ) as valor,

                        ( CASE WHEN empvar.empvar_id is null THEN
                          vars.vari_displayprint
                                ELSE    
                          empvar.empvar_displayprint
                          END ) as displayprint,
                          vars.vtd_id,
                          vtd.y_value_key 

                FROM (

                    SELECT dd1.plaemp_id as plaemp_id, dd1.platica_id, dd1.indiv_id, dd1.variable, dd2.variable as variable_planilla FROM (
                     
                        SELECT * FROM (

                            SELECT plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id,  ops.coops_operando1 as variable  
                            FROM planillas.planillas pla 
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1 
                            INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 
                            INNER JOIN planillas.conceptos_ops ops ON concs.conc_id = ops.conc_id AND ops.coops_ecuacion_id = concs.conc_ecuacion_id AND ops.coops_operando1_t = 1 AND ops.coops_estado = 1
                            WHERE pla.pla_id = ?
                             
                             
                            UNION ALL 

                            SELECT plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id, ops.coops_operando2 as variable  
                            FROM planillas.planillas pla 
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1 
                            INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 
                            INNER JOIN planillas.conceptos_ops ops ON concs.conc_id = ops.conc_id AND ops.coops_ecuacion_id = concs.conc_ecuacion_id AND ops.coops_operando2_t = 1 AND ops.coops_estado = 1
                            WHERE pla.pla_id = ?
                            ORDER BY plaemp_id, platica_id, variable 
                         
                        ) as d1 
                        GROUP BY  d1.plaemp_id, d1.platica_id, d1.indiv_id, d1.variable
                         
                    ) as dd1 

                    LEFT JOIN (

                        SELECT * FROM (

                            SELECT plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id, ops.coops_operando1 as variable  
                            FROM planillas.planillas pla 
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1 
                            INNER JOIN planillas.conceptos_ops ops ON plaec.conc_id = ops.conc_id AND ops.coops_ecuacion_id = plaec.conc_ecuacion_id AND ops.coops_operando1_t = 1 AND ops.coops_estado = 1
                            WHERE pla.pla_id = ? 
                             
                             
                            UNION ALL 

                            SELECT plaemp.plaemp_id, plaemp.platica_id, plaemp.indiv_id, ops.coops_operando2 as variable  
                            FROM planillas.planillas pla 
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1 
                            INNER JOIN planillas.conceptos_ops ops ON plaec.conc_id = ops.conc_id AND ops.coops_ecuacion_id = plaec.conc_ecuacion_id AND ops.coops_operando2_t = 1 AND ops.coops_estado = 1
                            WHERE pla.pla_id = ?
                            ORDER BY plaemp_id, platica_id, indiv_id, variable 
                         
                        ) as d1 
                        GROUP BY  d1.plaemp_id, d1.platica_id, d1.indiv_id, d1.variable

                     ) as dd2  ON dd1.plaemp_id = dd2.plaemp_id AND dd1.variable = dd2.variable 

                 ) as dd3  

                 LEFT JOIN planillas.variables vars ON dd3.variable = vars.vari_id 
                 LEFT JOIN planillas.empleado_variable empvar ON dd3.indiv_id = empvar.indiv_id AND empvar.vari_id = vars.vari_id 
                 LEFT JOIN planillas.variables_tabla_datos vtd ON vars.vtd_id = vtd.vtd_id   
                 WHERE variable_planilla is null 
 
              ";


        $variables_a_vincular = $this->_CI->db->query($sql, array($planilla_id, $planilla_id, $planilla_id, $planilla_id) )->result_array();


        foreach($variables_a_vincular as $vari)
        {

                   $static_data['y_values'] = array('platica_id' => $vari['platica_id']); 

                   $data = array( 'plaemp_id'          =>  $vari['plaemp_id'], 
                                  'vari_id'            =>  $vari['variable'],
                                  'plaev_valor'        =>  $vari['valor'],
                                  'plaev_procedencia'  =>  ( ($vari['empvar_id'] != '' && $vari['empvar_id'] != '0' ) ? PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO : PROCENDENCIA_VARIABLE_VALOR_XDEFECTO ),
                                  'plaev_displayprint' =>  $vari['displayprint']);

                   $static_data['vtd_id']      = $vari['vtd_id'];
                   $static_data['y_value_key'] = $vari['y_value_key'];
             
                   $this->_CI->planillaempleadovariable->registrar($data, false, $static_data);

        } 

        
        $sql = "  UPDATE planillas.planilla_empleado_concepto plaec 
                  SET conc_ecuacion_id = (SELECT conc_ecuacion_id 
                                            FROM planillas.conceptos concs WHERE plaec.conc_id = concs.conc_id )
                  WHERE 
                        plaec.plaec_estado = 1 AND
                        plaec.conc_ecuacion_id != (SELECT conc_ecuacion_id FROM planillas.conceptos concs WHERE plaec.conc_id = concs.conc_id )
                        AND plaec.plaemp_id IN ( SELECT plaemp.plaemp_id 
                                                 FROM planillas.planilla_empleados plaemp 
                                                 WHERE plaemp.pla_id = ? AND plaemp.plaemp_estado = 1 
                                                )  

                ";
 

       $rs = $this->_CI->db->query($sql, array($planilla_id) ); 
  

        $sql = " SELECT * FROM planillas.planillas WHERE pla_id = ? LIMIT 1";
        $rs = $this->_CI->db->query($sql, array($pla_id))->result_array();    
        $pla_info = $rs[0];

        if($pla_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE )
        { 

            $sql = " UPDATE planillas.planilla_empleado_concepto plaec 
                     SET clasificador_id = ( SELECT id_clasificador 
                                             FROM planillas.conceptos concs 
                                             LEFT JOIN planillas.conceptos_presu_cont copc ON concs.conc_id = copc.conc_id AND copc.copc_estado = 1
                                             WHERE plaec.conc_id = concs.conc_id )

                     WHERE plaec.plaec_estado = 1 AND
                           plaec.plaemp_id IN ( SELECT plaemp.plaemp_id 
                                                FROM planillas.planilla_empleados plaemp 
                                                WHERE plaemp.pla_id =? AND plaemp.plaemp_estado = 1 
                                              )  

                   ";

            $this->_CI->db->query($sql, array($planilla_id));

            /* Recuperar tarea y fuente de los trabajadores */
            $sql = " UPDATE planillas.planilla_empleado_concepto plaec 
                     SET clasificador_id = ( SELECT tarea_id, fuente_id 
                                             FROM  )

                     WHERE plaec.plaec_estado = 1 AND
                           plaec.plaemp_id IN ( SELECT plaemp.plaemp_id 
                                                FROM planillas.planilla_empleados plaemp 
                                                WHERE plaemp.pla_id =? AND plaemp.plaemp_estado = 1 
                                              )  


                   "; 

        }
        

    }


    /*
        @Descripcion : 
        @Autor       : 
        @Fecha       : 
    */

    public function actualizar_datos_trabajador_planilla($planilla_id)
    {

        //Actualizando cuenta bancaria 

        $sql = " UPDATE planillas.planilla_empleados plaemp 
                 SET pecd_id = pcd.pecd_id 
                 FROM rh.persona_cuenta_deposito pcd 
                 WHERE plaemp.indiv_id = pcd.pers_id AND 
                       pcd.pecd_estado = 1 AND 
                       plaemp.pecd_id != pcd.pecd_id AND 
                       plaemp.pla_id = ?  
               ";

        $this->_CI->db->query($sql, array($planilla_id) );      
  
 
        
        // Actualizando pension 
 
        $this->_CI->load->library(array('Catalogos/afp'));

        $sql = " UPDATE planillas.planilla_empleados plaemp 
                 SET peaf_id = pensi.peaf_id 
                 FROM rh.persona_pension pensi 
                 WHERE plaemp.indiv_id = pensi.pers_id AND 
                       pensi.peaf_estado = 1 AND 
                       plaemp.peaf_id != pensi.peaf_id AND 
                       plaemp.pla_id = ?    ";

        $this->_CI->db->query($sql, array($planilla_id) );      

        $this->_CI->afp->actualizar_valores_planillas( array('planilla' => $planilla_id) );

 
    }




    public function vincular_hoja($hoja_id, $planilla_id){

         $this->_CI->load->library(array('App/hojaasistencia'));

         $sql =  " UPDATE planillas.planillas SET hoja_asociada = ? WHERE pla_id = ?";
         $this->_CI->db->query($sql, array($hoja_id, $planilla_id));
     
         $this->_CI->hojaasistencia->setEstado($hoja_id,HOJAASIS_ESTADO_IMPORTADO, (' IMPORTADA A LA PLANILLA: '.$planilla_id) );


    }

/*
    public function get_resumen_x_individuo(){


         $sql ="  SELECT  plae.indiv_id, 

                    SUM(COALESCE(data_ingresos.monto,0)) as ingresos , 
                    SUM(COALESCE(data_descuentos.monto,0)) as descuentos, 
                    SUM(COALESCE(data_aportacion.monto,0)) as aportacion   

                     FROM planillas.planillas pla
                     LEFT JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1
                     LEFT JOIN planillas.planilla_empleados plae ON pla.pla_id = plae.pla_id AND plae.plaemp_estado = 1
                     LEFT JOIN 
                     (
                         SELECT  plaec.plaemp_id,SUM( COALESCE(plaec_value,0)) as monto 
                         FROM planillas.planilla_empleado_concepto plaec 
                             INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id AND conc.conc_tipo = ".TIPOCONCEPTO_INGRESO." 
                         WHERE plaec_estado = 1 AND plaec_marcado = 1 
                         GROUP BY plaec.plaemp_id 
                    
                     ) as data_ingresos ON plae.plaemp_id = data_ingresos.plaemp_id

                     LEFT JOIN 
                     (
                         SELECT  plaec.plaemp_id, SUM( COALESCE(plaec_value,0)) as monto 
                             FROM planillas.planilla_empleado_concepto plaec 
                             INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id AND conc.conc_tipo =  ".TIPOCONCEPTO_DESCUENTO."
                         WHERE plaec_estado = 1 AND plaec_marcado = 1 
                         GROUP BY plaec.plaemp_id 
                    
                     ) as data_descuentos ON plae.plaemp_id = data_descuentos.plaemp_id


                      LEFT  JOIN 
                     (
                         SELECT  plaec.plaemp_id, SUM( COALESCE(plaec_value,0)) as monto 
                             FROM planillas.planilla_empleado_concepto plaec 
                             INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id AND conc.conc_tipo = ".TIPOCONCEPTO_APORTACION."
                         WHERE plaec_estado = 1 AND plaec_marcado = 1 
                         GROUP BY plaec.plaemp_id 
                    
                     ) as data_aportacion ON plae.plaemp_id = data_aportacion.plaemp_id


                WHERE movs.plaes_id = ".ESTADOPLANILLA_PROCESADA."     
                 
                GROUP BY plae.indiv_id 
            ";


           $rs = $this->_CI->db->query($sql, array())->result_array();

           return $rs;
    }
    */

    public function get_detalle_resumen_full($planilla_id, $comprobar_negativos = false)
    {
   
        $sql = "  SELECT 
                          plaemp.plaemp_id,
                          plaemp.plaemp_key,
                          indiv.indiv_id,
                          platica.platica_nombre,
                          indiv.indiv_appaterno,
                          indiv.indiv_apmaterno,
                          indiv.indiv_nombres,
                          indiv.indiv_dni,
                        
                          SUM((CASE WHEN plaec.conc_tipo = 1 THEN     

                                        plaec.plaec_value 
                                    ELSE 
                                                        0 
                                  END )) as ingresos,

                          SUM((CASE WHEN plaec.conc_tipo = 2 THEN     

                                   plaec.plaec_value 

                               ELSE 
                                   0 
                               END )) as descuentos,


                          SUM((CASE WHEN plaec.conc_tipo = 3 THEN    

                                    plaec.plaec_value 
                              ELSE 
                                   0 
                              END )) as aportacion,

                         CASE WHEN cast(tarea.ano_eje as int) >= 2021
							THEN tarea.sec_func
							ELSE (tarea.sec_func || '-'|| tarea.tarea_nro) 
						 END as tarea,
                         (plaemp.fuente_id || '-' || plaemp.tipo_recurso) as fuente 


                    FROM planillas.planillas pla
                    INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id  AND plaemp.plaemp_estado = 1
                    LEFT JOIN  planillas.planilla_tipo_categoria platica ON plaemp.platica_id = platica.platica_id 
                    INNER JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id 
                    INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   
                    INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id >= ".ESTADOPLANILLA_PROCESADA."    
                    LEFT JOIN sag.tarea ON plaemp.tarea_id = tarea.tarea_id
                    WHERE pla.pla_id = ?
                    GROUP BY plaemp.plaemp_id, plaemp.plaemp_key,indiv.indiv_id, tarea.sec_func, tarea.tarea_nro,plaemp.fuente_id, plaemp.tipo_recurso, platica.platica_nombre, indiv.indiv_appaterno,indiv.indiv_apmaterno,indiv.indiv_nombres, indiv_dni, tarea.ano_eje
                    ORDER BY indiv_appaterno, indiv_apmaterno, indiv.indiv_nombres, plaemp.plaemp_id, platica_nombre
 
        ";


        if($comprobar_negativos == true)
        {
            $sql_tmp = $sql;
            $sql =  " SELECT * FROM ( ".$sql_tmp." ) as datos 

                                WHERE datos.ingresos < datos.descuentos;  

                                ";

        }

        $rs = $this->_CI->db->query($sql, array($planilla_id) )->result_array();

        return $rs;

    }
  

    public function get_resumen_neto_por_abono($planilla_id)
    {


        $sql = "     SELECT 
                       estado, count(estado) as cantidad, SUM( datos.ingresos - datos.descuentos ) as total 
                         

                       FROM ( 

                              SELECT 
                                    plaemp.indiv_id,

                                    ( CASE WHEN plaemp.pecd_id is null OR plaemp.pecd_id = 0 THEN     

                                                  0
                                       ELSE 
                                                  1
                                       END ) estado ,

                                    SUM((CASE WHEN plaec.conc_tipo = 1 THEN     

                                                  plaec.plaec_value 
                                              ELSE 
                                                                  0 
                                            END )) as ingresos,

                                    SUM((CASE WHEN plaec.conc_tipo = 2 THEN     

                                             plaec.plaec_value 

                                         ELSE 
                                             0 
                                         END )) as descuentos,


                                    SUM((CASE WHEN plaec.conc_tipo = 3 THEN    

                                              plaec.plaec_value 
                                        ELSE 
                                             0 
                                        END )) as aportacion 


                              FROM planillas.planillas pla
                              INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id  AND plaemp.plaemp_estado = 1
                              INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   
                              INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id >= 2   
                              WHERE pla.pla_id  = ? 
                              GROUP BY plaemp.indiv_id, plaemp.pecd_id 
                              ORDER BY plaemp.indiv_id

                    ) as datos 

                     GROUP BY estado 

                     ORDER BY estado
        
        ";

        $rs = $this->_CI->db->query($sql, array($planilla_id))->result_array();

        return $rs;

    }

/*
    
    public function get_resumen_montos($planilla_id)
    {

            $sql =" SELECT   
                          SUM((CASE WHEN conc_tipo = 1 THEN     

                             plaec.plaec_value 
                           ELSE 
                                    0 
                           END )) as ingresos,

                          SUM((CASE WHEN conc_tipo = 2 THEN     

                             plaec.plaec_value 
                           ELSE 
                                    0 
                           END )) as descuentos,

                           SUM((CASE WHEN conc_tipo = 3 THEN    

                             plaec.plaec_value 
                           ELSE 
                                    0 
                           END )) as aportacion 
                           
                    FROM planillas.planilla_empleado_concepto plaec
                    INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1 
                    INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 
                                     
                    WHERE pla.pla_id = ? AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   

                    LIMIT 1
         
                ";

            $rs = $this->_CI->db->query($sql, array($planilla_id))->result_array();

            return $rs;
    }*/

/*
    public function get_resumen_x_conceptos($planilla_id){



        $sql =" 


             ";


    }*/

    public function desvincular_hoja($planilla_id){
            
        $this->_CI->load->library(array('App/hojaasistencia'));
        $sql = " SELECT hoja_asociada FROM planillas.planillas WHERE pla_id = ?";

        $rs = $this->_CI->db->query($sql, array($planilla_id))->result_array();

        $hoja_id = $rs[0]['hoja_asociada'];
        
        $ok = $this->_CI->hojaasistencia->setEstado($hoja_id, HOJAASIS_ESTADO_TERMINADO, 'HOJA DESVINCULADA DE LA PLANILLA: '.$pla_id);
    
        if($ok){
       
           $sql =  " UPDATE planillas.planillas SET hoja_asociada =  0 WHERE pla_id = ?";
           $this->_CI->db->query($sql, array($planilla_id));
        }

        return $ok;

    }   

    public function reset_planilla($planilla_id){

        $this->_CI->db->trans_begin();

        $sql = "DELETE FROM planillas.planilla_empleado_concepto 
                WHERE  plaemp_id  IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE pla_id = ?  ) ";
        $this->_CI->db->query($sql,array($planilla_id));
        
        $sql = "DELETE FROM planillas.planilla_empleado_variable  
                WHERE plaemp_id  IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE pla_id = ?  ) ";
        $this->_CI->db->query($sql,array($planilla_id));
        
        $sql = "DELETE FROM planillas.planilla_empleados WHERE pla_id = ?  ";
        $this->_CI->db->query($sql,array($planilla_id));
         
        $sql = "UPDATE planillas.hojaasistencia_emp_dia SET pla_id = 0, plaemp_id = 0, hoaed_importado = 0, plaasis_id = 0 WHERE pla_id = ? ";
        $this->_CI->db->query($sql, array($planilla_id));

       if($this->_CI->db->trans_status() === FALSE) 
       {
                $this->_CI->db->trans_rollback();
                return false;
                
        }else{
                    
             $this->_CI->db->trans_commit();
             return true;
       } 
            
    }

    public function delete_all_detalle($planilla_id){

        
        $this->_CI->db->trans_begin();

        $sql = "DELETE FROM planillas.planilla_empleado_concepto 
                WHERE  plaemp_id  IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE pla_id = ?  ) ";
        $this->_CI->db->query($sql,array($planilla_id));
        
        $sql = "DELETE FROM planillas.planilla_empleado_variable  
                WHERE plaemp_id  IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE pla_id = ?  ) ";
        $this->_CI->db->query($sql,array($planilla_id));

        $sql = "UPDATE planillas.hojaasistencia_emp_dia SET pla_id = 0, plaemp_id = 0, hoaed_importado = 0, plaasis_id = 0 WHERE pla_id = ? ";
        $this->_CI->db->query($sql, array($planilla_id));


        if($this->_CI->db->trans_status() === FALSE) 
        {
                $this->_CI->db->trans_rollback();
                return false;
                
        }else{
                    
             $this->_CI->db->trans_commit();
             return true;
       } 


    }

    public function get_conceptos_involucrados($planilla_id)
    {

        $sql =" SELECT   
                    distinct(plaec.conc_id),conc.conc_nombre,conc.conc_key      
                FROM planillas.planilla_empleado_concepto plaec
                INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1 AND pla_id = ?
                INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id 
                WHERE plaec.plaec_estado = 1
                ORDER BY conc.conc_nombre
             
              ";

        $rs=  $this->_CI->db->query($sql, array($planilla_id))->result_array();

        return $rs;
    }
    
    public function get_variables_involucradas($planilla_id)
    {   

        $sql =" SELECT   
                    distinct(plaev.vari_id),vari.vari_nombre,vari.vari_key      
                FROM planillas.planilla_empleado_variable plaev
                INNER JOIN planillas.planilla_empleados plaemp ON plaev.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1 AND pla_id = ?
                INNER JOIN planillas.variables vari ON plaev.vari_id = vari.vari_id 
                WHERE plaev.plaev_estado = 1
                ORDER BY vari.vari_nombre 
                ";

        $rs=  $this->_CI->db->query($sql, array($planilla_id))->result_array();

        return $rs;       
    }


    public function get_siguiente_codigo( $plati_id, $anio, $tipo = '1' )
    {
        
        $this->_CI->db->trans_begin(); 

        
        $sql =" SELECT codigo_current 
                FROM planillas.codigos 
                WHERE plati_id = ? AND pla_tipo = ? AND ano_eje = ? FOR UPDATE";

        $rs = $this->_CI->db->query($sql, array($plati_id, $tipo, $anio))->result_array();
       
        if(sizeof($rs)==0)
        {

             $sql ="INSERT INTO planillas.codigos (plati_id, pla_tipo, codigo_current, ano_eje) VALUES ( ? , ? , '1', ? )";
             $this->_CI->db->query($sql, array($plati_id, $tipo, $anio));
 
             $nuevo_codigo = '1';
        }
        else{
             
             $sql =" UPDATE planillas.codigos 
                     SET codigo_current = codigo_current + 1 
                     WHERE plati_id = ? AND pla_tipo = ? AND ano_eje = ? ";

             $this->_CI->db->query($sql, array($plati_id, $tipo, $anio));

             $nuevo_codigo = $rs[0]['codigo_current']++; 
        }
 
        if($this->_CI->db->trans_status() === FALSE) 
        {
              $this->_CI->db->trans_rollback();
             return false;
                
        }else{
                    
             $this->_CI->db->trans_commit();
             return $nuevo_codigo;
       } 
    }


    public function tabla_afps($planilla_id, $params = array() )
    {  


        $planilla_info = $this->get($planilla_id);

        $sql_conceptos = " SELECT conc_nombrecorto FROM planillas.conceptos concs WHERE concs.gvc_id IN(".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") AND conc_estado = 1 AND plati_id = ".$planilla_info['plati_id']." AND conc_afecto = 1 ORDER BY conc_nombrecorto ";

        $rs  = $this->_CI->db->query($sql_conceptos, array())->result_array();

        $headers = '';

        if(sizeof($rs) == 0) return array();
         
        foreach($rs as $reg)
        {
            $headers.= ', "'.$reg['conc_nombrecorto'].'" numeric(10,2)';
        }
 
        
        $params['fuente']       = ( $params['fuente'] == '') ? '0' : $params['fuente'];
        $params['tipo_recurso'] = ( $params['tipo_recurso'] == '') ? '0' : $params['tipo_recurso']; 


        $sql = "    SELECT afp.afp_nombre, tabla.* 
                    FROM (

                        SELECT * FROM
                         crosstab(
                                    '
                                        SELECT afp.afp_id, resumen.conc_id, COALESCE(resumen.total_afp,0) FROM (
                                        
                                            SELECT pension.afp_id, plaec.conc_id, SUM(plaec.plaec_value) as total_afp
                                                FROM planillas.planilla_empleado_concepto plaec
                                                INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1
                                                INNER JOIN rh.persona_pension pension ON pension.peaf_id = plaemp.peaf_id AND pentip_id = 2
                                                INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_id = ".$planilla_id."
                                                     
                                            WHERE  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1 
                                                   AND plaec.gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") 
                                                   AND plaec.fuente_id = ''".trim($params['fuente'])."'' AND plaec.tipo_recurso = ''".trim($params['tiporecurso'])."'' 
                                                   

                                            GROUP BY pension.afp_id, plaec.conc_id
                                            
                                        ) as resumen 
                                        RIGHT JOIN rh.afp ON resumen.afp_id = afp.afp_id  
                                        WHERE  afp_estado = 1
                                        ORDER BY afp_id,conc_id
                                    ',

                                    ' SELECT conc_id FROM planillas.conceptos concs WHERE concs.gvc_id IN(".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") AND conc_estado = 1 AND plati_id = ".$planilla_info['plati_id']."  AND conc_afecto = 1 ORDER BY conc_nombrecorto ' 
                        )
                        AS ct( \"afp_id\"  numeric(10,2)
                               ".$headers." )

                    ) as tabla 
                    LEFT JOIN rh.afp ON tabla.afp_id = afp.afp_id


             ";


        $rs = $this->_CI->db->query($sql, array())->result_array();

        
        return $rs;

    } 


    /*   
        RESUMEN DE AFECTACION POR FUENTE - PLANILLA - CLASIFICADOR - META 
        RESUMEN DE AFECTACION POR FUENTE - PLANILLA - CLASIFICADOR - META - TAREA
        RESUMEN DE AFECTACION POR FUENTE - CLASIFICADOR - META 
        RESUMEN DE AFECTACION POR FUENTE - CLASIFICADOR - META - TAREA
 
    */

    public function get_resumen_presupuestal($params = array(), $fuente_especifica  = true, $planilla = true,  $tarea = false,  $modo = 'fuente_planilla_clasificador_meta')
    {

        $in_planillas = implode(',', $params['planillas']);
 
        $sql = "  SELECT  data.*,  ";
 
        if($planilla)
        {

           $sql.=  " ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo, ";
            
        }
 
        $sql.= "  (par.tipo_transaccion ||'.'|| par.generica || '.' || par.subgenerica || '.' || par.subgenerica_det || '.' || par.especifica || '.' || par.especifica_det   ) as clasificador_codigo,
         
                  par.descripcion as clasificador_nombre,
                  meta.nombre as meta_nombre
               ";

              if($tarea)
              {
                 $sql.=" , tarea.tarea_nro ";
              }       

        $sql.="  
              FROM (  

                SELECT  pla.ano_eje, 
                        plaec.fuente_id, 
                        plaec.tipo_recurso,
                        (plaec.fuente_id || '-' || plaec.tipo_recurso) as fuente_financiamiento    ";

                        if($planilla)
                        {
                            $sql.=" ,pla.pla_id ";   
                        }
 
                        $sql.=" ,plaec.clasificador_id
                                ,tarea.sec_func ";
 
                
                      if($tarea)
                      {
                          $sql.=" ,tarea.tarea_id ";   
                      }


        $sql.="       ,SUM(plaec.plaec_value) as total
                        
                        FROM planillas.planilla_empleado_concepto plaec
                        INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1
                        INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id  
                        INNER JOIN sag.tarea ON plaec.tarea_id = tarea.tarea_id

 
                    WHERE  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1 ";

                        if($fuente_especifica )
                        { 

                            $sql.=" AND plaec.fuente_id = '".trim($params['fuente'])."' AND plaec.tipo_recurso = '".trim($params['tiporecurso'])."'"; 

                        }
 

        $sql.="                AND plaec.conc_tipo in (".TIPOCONCEPTO_INGRESO.",".TIPOCONCEPTO_APORTACION.")   
                               AND pla.pla_id in (".$in_planillas.")   AND pla.pla_estado = 1

                    GROUP BY pla.ano_eje,  plaec.fuente_id, plaec.tipo_recurso ";

                    if($planilla)
                    {
                        $sql.=" ,pla.pla_id ";   
                    }

                    $sql.=" ,plaec.clasificador_id
                            ,tarea.sec_func ";

                    if($tarea)
                    {
                        $sql.=" ,tarea.tarea_id ";   
                    }
 

        $sql.="   

                  ) as data 
              
              ";


        if($planilla)
        { 

             $sql.="        
                      LEFT JOIN planillas.planillas pla ON data.pla_id = pla.pla_id 
                   ";
        }

        $sql.="   LEFT JOIN pip.especifica_det par ON data.clasificador_id = par.id_clasificador AND data.ano_eje = par.ano_eje
                  LEFT JOIN pip.meta ON meta.sec_func = data.sec_func AND  meta.ano_eje = data.ano_eje 
               ";
 
                 if($tarea)
                 {
                     $sql.=" LEFT JOIN sag.tarea ON tarea.tarea_id = data.tarea_id ";
                 }   
                  

        $sql.="   
                WHERE data.total > 0

                ORDER BY data.ano_eje, data.fuente_id, data.tipo_recurso, ";

        
                 if($planilla)
                 {          
                     $sql.=" data.pla_id, ";
                 }

                 $sql.=" data.clasificador_id, data.sec_func "; 

                 if($tarea)
                 {
                    $sql.=" ,data.tarea_id "; 
                 }


        $rs =  $this->_CI->db->query( $sql, array() )->result_array();
        
        return $rs;

    }
    

    public function resumen_totales($planillas)
    {

        

    }


    public function actualizar_afectacion_detalle($params = array())
    {

        $this->_CI->db->trans_begin();
        

            $sql = " UPDATE planillas.planilla_empleados 
                     SET  tarea_id = ? , fuente_id = ? ,  tipo_recurso = ?, plaemp_afectacion_actualizada = 1 
                     WHERE indiv_id = ? AND pla_id = ?     ";


            $this->_CI->db->query($sql, array(  $params['tarea'], $params['fuente'], $params['tiporecurso'], 
                                                $params['individuo'], $params['planilla'] ));       



            $sql = " UPDATE planillas.planilla_empleado_concepto 
                     SET tarea_id = ?, fuente_id = ? , tipo_recurso = ?
                     WHERE plaemp_id IN (

                            SELECT plaemp_id FROM planillas.planilla_empleados plaemp
                            WHERE plaemp.pla_id = ? AND plaemp.indiv_id = ? 

                     )  ";

            $this->_CI->db->query($sql, array(  $params['tarea'], $params['fuente'], $params['tiporecurso'],
                                                 $params['planilla'], $params['individuo'] ));       


        if($this->_CI->db->trans_status() === FALSE) 
        {
            $this->_CI->db->trans_rollback();
            return false;
            
        }else{
                
            $this->_CI->db->trans_commit();
            return true;
        } 

    }

    public function get_conceptos_sin_clasificador()
    {

        $sql = "SELECT distinct plaec.conc_id, plaec.clasificador_id 
                FROM planillas.planillas pla
                LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1
                WHERE pla.pla_id = ?  AND ( plaec.clasificador_id = '' OR plaec.clasificador_id is null )
                 
               ";
    }

    public function get_codigos($planillas, $by_id = true)
    {

        $w = ($by_id) ? ' pla_id ' : 'pla_key';

        $sql_pla = implode("','", $planillas);

        $sql_pla="'".$sql_pla."'";

        $sql = " SELECT 
                        pla.pla_id,
                        ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo

                 FROM planillas.planillas pla
                 WHERE  ".$w." in (".$sql_pla.")  

                 ORDER BY pla.plati_id, pla.pla_tipo, pla.pla_id desc
               ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;
    }


    public function get_codigos_by_siaf($anio, $siaf)
    { 

        $sql = " SELECT 
                        pla.pla_id,
                        ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo

                 FROM planillas.planillas pla 
                 INNER JOIN planillas.planilla_siaf plasi ON pla.pla_id = plasi.pla_id 
                 INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plaes_id = ?  AND plamo.plamo_estado = 1 
                 WHERE plasi.ano_eje = ? AND plasi.siaf = ? AND plasiaf_estado = 1 AND pla_estado = 1 
                 ORDER BY pla.plati_id, pla.pla_tipo, pla.pla_id desc
               ";

        $rs = $this->_CI->db->query($sql, array(ESTADOPLANILLA_FINALIZADO, $anio,$siaf))->result_array();

        return $rs;
    }

    public function contar_trabajadores( $planillas = array() )
    {

        $sql_pla = implode(",", $planillas);

        $sql =" SELECT count(distinct(plaemp.indiv_id)) as total
                FROM planillas.planillas pla 
                LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id 
                WHERE plaemp_estado = 1 AND pla.pla_id IN (".$sql_pla.")
              ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs[0]['total'];

    }



    public function procesar_variables($pla_id)
    {   

        $this->_CI->load->library(array('App/variable_metodos'));
        $pla_info = $this->_CI->planilla->get($pla_id);
 

        $sql = " SELECT * FROM planillas.variables 
                 WHERE vari_estado = 1 AND vari_procesadaxplanilla = 1  AND plati_id = ? ";

        $rs = $this->_CI->db->query($sql, array($pla_info['plati_id']))->result_array();


        foreach($rs as $reg) 
        {
           
            /*
                CODIGO ESTATICO
            */  

           $variable_metodo = trim(strtolower($reg['vari_metodo_php']));

           $parametros = array();

           if($variable_metodo == 'numero_dias_planilla' || $variable_metodo == 'numero_domingos_planilla' )
           {    

                if(trim($pla_info['pla_fecini']) != '' && trim($pla_info['pla_fecini']) != '')
                {
                    $parametros[] = trim($pla_info['pla_fecini']);
                    $parametros[] = trim($pla_info['pla_fecfin']);  
                }

           }

           $value =  call_user_func_array( array($this->_CI->variable_metodos, $variable_metodo ), 
                                                              array($parametros) );
            
           $value = ($value === '' || $value === FALSE || $value === null ) ? 0 : $value;

           $sql = "  INSERT INTO planillas.planilla_variable( pla_id, vari_id, plava_value ) VALUES(?, ?, ?) ";
           $this->_CI->db->query($sql, array($pla_id, $reg['vari_id'], $value ) );
 
        }

    }


    public function getNumerosSiaf($pla_id)
    {

        $sql = " SELECT * FROM planillas.planilla_siaf WHERE plasiaf_estado = 1 AND pla_id = ? ORDER BY fuente_id, tipo_recurso ";
        $rs = $this->_CI->db->query($sql, array($pla_id))->result_array();
        
        return $rs;
    }

    public function existe($pla_id)
    {

        $sql =" SELECT pla_estado FROm planillas.planillas where pla_id = ? ";
        list($rs) =  $this->_CI->db->query($sql, array($pla_id))->result_array();
        
        return ($rs['pla_estado'] == 1 ) ? true : false;
    }
  
    public function comprobar_pension($pla_id){

        // cada trabajador debe tener ONP o AFP >  0

        $sql_detalle = " SELECT  plaemp.plaemp_id, 
                        plaemp.indiv_id,
                        pp.peaf_id,
                        pp.pentip_id,
                        pp.peaf_jubilado,
                        SUM(CASE WHEN ( conc.gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") ) THEN plaec_value ELSE 0 END) as monto_afp,
                        SUM(CASE WHEN ( conc.gvc_id IN (".GRUPOVC_ONP.") ) THEN plaec_value ELSE 0 END) as monto_onp

                FROM planillas.planillas pla
                LEFT JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1
                LEFT JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id  
                LEFT JOIN rh.persona_pension pp ON pp.peaf_id = plaemp.peaf_id  
                LEFT JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1 
                LEFT JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id AND conc.conc_afecto = 1 AND conc.gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.",".GRUPOVC_ONP.")
                WHERE pla.pla_id = ?  
                GROUP BY plaemp.plaemp_id, plaemp.indiv_id, pp.peaf_id, pp.pentip_id, peaf_jubilado ";


        $sql =" SELECT indiv.indiv_appaterno, 
                       indiv.indiv_apmaterno, 
                       indiv.indiv_nombres, 
                       indiv.indiv_dni,
                       detalle.* 
                FROM (".$sql_detalle.") as detalle 
                LEFT JOIN public.individuo indiv ON detalle.indiv_id = indiv.indiv_id 
                WHERE detalle.peaf_id is null 
                      OR (detalle.peaf_jubilado = 0 AND detalle.monto_afp = 0 AND detalle.monto_onp = 0 ) 
                      OR (detalle.peaf_jubilado = 0 AND detalle.pentip_id = 1 AND detalle.monto_onp = 0  )
                      OR (detalle.peaf_jubilado = 0 AND detalle.pentip_id = 2 AND detalle.monto_afp = 0  )
                      OR (detalle.peaf_jubilado = 1 AND (detalle.monto_afp > 0 OR detalle.monto_onp > 0) )";


        $rs = $this->_CI->db->query($sql, array($pla_id))->result_array();

        return $rs;

    }

    public function validar()
    {

        $errores = array();

        $observaciones = array();


        // Verificar que el trabajador no tenga mas de X dias en un periodo de trabajo

        // Que la cantidad de desacansos medicos en el año no sea mayor a 21
 
        // Corroborar que todos los trabajadores tienen tarea y fuente
        /*
        $sql = " SELECT * FROM planillas.planilla_empleado_concepto plaec 
                          LEFT JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id 
                          LEFT JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id 

                 WHERE plaec.conc_tipo IN () AND ( 
                       ( plaec.tarea_id = '' OR plaec.tarea_id = 0 OR plaec.tarea_id is null ) OR
                       ( plaec.fuente_id = '' OR plaec.fuente_id = 0 OR plaec.fuente_id is null ) OR
                       ( plaec.tipo_recurso = '' OR plaec.tipo_recurso = 0 OR plaec.tipo_recurso is null ) OR
                       ( plaec.id_clasificador = '' OR plaec.id_clasificador = 0 OR plaec.id_clasificador is null )  )     
 
              ";
    
        $rs = $this->_CI->db->query($sql, array() )->result_array();
  */



        // Comprobar que los conceptos tengan la misma tarea y fuente que el trabajador

        // Comprobar que tenga todos los conceptos de AFP o ONP 

        // Avisar si los conceptos de ONP o AFP son = a cero      
    

    }
}