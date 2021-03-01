<?php

class hojaasistencia extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'hoa_id',
                                    'code'  => 'hoa_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'hoa_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'hojaasistencia';
    protected $_PREF_TABLE= 'hojaasistencia'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }

    public function get($hoja_id)
    {
    
          $sql =" SELECT  hojas.*, 
                         (  substring( hojas.anio_eje from 3 for 2) || hojas.hoa_id ||  '-' ||hojas.plati_id )  as hoa_codigo , 
                          plati.plati_nombre as tipo_planilla, 
                          est.hoae_nombre as estado, 
                          hojas.hoa_estado as estado_id, 
                          (hoa_fechafin - hoa_fechaini + 1) as  nro_dias,
						  CASE WHEN hoa_anio::integer < 2021 
                            THEN (tarea.sec_func || ' - ' || tarea.tarea_nro) 
                            ELSE tarea.sec_func 	
						  END as tarea_codigo,
						  CASE WHEN hoa_anio::integer < 2021 
                            THEN ( tarea.sec_func || ' - ' || tarea.tarea_nro || ' ' || tarea.tarea_nombre )
                            ELSE ( tarea.sec_func || ' - ' || tarea.tarea_nombre ) 	
						  END as proyecto,
                          meta.nombre as meta_nombre,
                          (  SELECT count(distinct(indiv_id)) FROM planillas.hojaasistencia_emp empdet 
                             WHERE empdet.hoae_estado = 1 AND hoa_id =  ?
                           ) as cant_trab 

                 FROM planillas.hojaasistencia hojas 
                 LEFT JOIN planillas.planilla_tipo plati ON hojas.plati_id =  plati.plati_id 
                 LEFT JOIN planillas.hoa_estados est ON hojas.hoa_estado = est.hoae_id 
                 LEFT JOIN sag.tarea ON hojas.tarea_id = tarea.tarea_id 
                 LEFT JOIN pip.meta ON tarea.sec_func = meta.sec_func AND tarea.ano_eje = meta.ano_eje 
                 WHERE hojas.hoa_id = ?  LIMIT 1
                            
                ";


          $rs = $this->_CI->db->query($sql, array($hoja_id,$hoja_id))->result_array();
    
          return $rs[0];                               
    }
      
    public function get_list($params = array())
    {
 
          $values = array();

          $sql =" SELECT   hojas.*, (  substring( hojas.anio_eje from 3 for 2) || hojas.hoa_id ||  '-' ||hojas.plati_id ) as hoa_codigo , 
                          plati.plati_nombre as tipo_planilla, est.hoae_nombre as estado, hojas.hoa_estado as estado_id, 
                          CASE WHEN hoa_anio::integer < 2021 
                            THEN ( tarea.sec_func || ' - ' || tarea.tarea_nro || ' ' || tarea.tarea_nombre ) 
                            ELSE ( tarea.sec_func || ' - ' || tarea.tarea_nombre ) 
						  END as proyecto,
						  CASE WHEN hoa_anio::integer < 2021 
                            THEN ( tarea.sec_func || ' - ' || tarea.tarea_nro)
                            ELSE tarea.sec_func	
                          END as meta_codigo,
                          (hoa_fechafin - hoa_fechaini + 1) as  nro_dias,
                   ( 
                      SELECT count(hoae_id) 
                      FROM planillas.hojaasistencia_emp empdet
                      WHERE empdet.hoae_estado = 1 AND hoa_id =  hojas.hoa_id
                    
                    ) as cant_trab,
                    
                    info_importacion.*,

                    (CASE WHEN info_importacion.hoa_id is not NULL THEN  
                        ROUND( info_importacion.total_importados / info_importacion.total_dias * 100 )::text || '%' 

                     ELSE

                         ' --- '

                     END ) as porcentaje_importacion,

                  
      
                   ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres )  as usuario
      
                   FROM planillas.hojaasistencia hojas 
                   LEFT JOIN planillas.planilla_tipo plati ON hojas.plati_id =  plati.plati_id 
                   LEFT JOIN planillas.hoa_estados est ON hojas.hoa_estado = est.hoae_id
                   LEFT JOIN sag.tarea ON hojas.tarea_id = tarea.tarea_id 
                   LEFT JOIN public.usuario usur ON hojas.user_id = usur.usur_id 
                   LEFT JOIN public.individuo indiv ON usur.indiv_id = indiv.indiv_id
                   LEFT JOIN (

                        SELECT  dia.hoa_id,
                                COALESCE(count(dia.hoaed_id),0)::double precision total_dias, 
                                COALESCE(SUM(CASE WHEN dia.plaemp_id != 0 THEN 1 ELSE 0 END),0)::double precision total_importados
                        FROM planillas.hojaasistencia_emp_dia dia  
                        INNER JOIN planillas.hojaasistencia hoa ON dia.hoa_id = hoa.hoa_id 
                        INNER JOIN planillas.hojadiario_tipo_plati htp ON dia.hatd_id = htp.hatd_id AND hoa.plati_id = htp.plati_id AND htp.vari_id != 0
                        WHERE hoaed_estado = 1 AND dia.hoaed_laborable = 1  AND dia.hatd_id != 0
                        GROUP BY dia.hoa_id 
                      
                   ) as info_importacion ON hojas.hoa_id = info_importacion.hoa_id 

                   WHERE hoa_estado != 0 
                 "; 

            if(trim($params['hoja_desde']) != '')
            {
                $sql.=" AND hojas.hoa_id NOT IN (?) ";
                $values[] = trim($params['hoja_desde']);
            }

            if( trim($params['codigo'])  != '' && $params['restringir_a_codigo']  )
            {
               $sql.=" AND (  substring( hojas.anio_eje from 3 for 2) || hojas.hoa_id ||  '-' ||hojas.plati_id ) = ? ";
               $values[] =  $params['codigo']; 
            }
            else
            { 

                  if( trim($params['codigo'])  != '' && $params['restringir_a_codigo'] == false  )
                  {
                     $sql.=" AND (  substring( hojas.anio_eje from 3 for 2) || hojas.hoa_id ||  '-' ||hojas.plati_id ) = ? ";
                     $values[] =  $params['codigo']; 

                  }

                   if(trim($params['tarea']) != '0'  && trim($params['tarea'])  != ''  )
                   {
                      $sql.=" AND hojas.tarea_id = ? ";
                      $values[] =  $params['tarea']; 
                   }
                   else
                   {
                      if($params['user_id'] != '')
                      { 

                        
                        $sql.=" AND user_id = ? ";
                        $values[] =  $params['user_id']; 
                      }
                   }

                         
                   if(trim($params['estado']) != '0' && trim($params['estado'])  != ''  )
                   { 
                      $sql.= " AND hojas.hoa_estado = 1 ";   
                    //  $values[] = $params['estado'];
                   }

         


                   if($params['tipoplanilla'] != '0'  && $params['tipoplanilla']  != '' )
                   { 
                      $sql.= " AND hojas.plati_id = ? ";   
                      $values[] = $params['tipoplanilla'];
                   }

                   if(trim($params['descripcion']) != ''   && $params['descripcion']  != '' )
                   { 
                      $sql.= " AND hojas.hoa_descripcion like ? ";   
                      $values[] = '%'.trim($params['descripcion']).'%';
                   }

                   if(trim($params['anio']) != '0'  && $params['anio']  != ''  )
                   {
                      $sql.=" AND hoa_anio = ? ";
                      $values[] =  $params['anio']; 
                   }
                
                
                   if(trim($params['mes']) != '0'   && $params['mes']  != ''  )
                   { 
                       $sql.= " AND ( ( EXTRACT( month FROM hoa_fechaini) =   ? ) OR ( EXTRACT( month FROM hoa_fechafin) =   ? ) )";
                       $values[] =  $params['mes'];
                       $values[] =  $params['mes'];
                   }


                   if(trim($params['mostrar']) != '0'   && $params['mostrar']  != ''  )
                   { 
                       if($params['mostrar'] == '1') // Pendientes de importar
                       {
                           $sql.= " AND ( ROUND( info_importacion.total_importados / info_importacion.total_dias * 100 )  < 100 ) ";

                       }
                       else if($params['mostrar'] == '2') // Totalmente importadas
                       {
                          $sql.= " AND ( ROUND( info_importacion.total_importados / info_importacion.total_dias * 100 )  >= 100 ) ";
                       }
                      
                   }
           }
          
          if($params['orderby'] == '')
          {

             $sql .= " ORDER BY hoa_estado, hoa_fechaini desc    ";
          }
          else if($params['orderby'] == 'fecha-tarea')
          {
              $sql.=" ORDER BY hoa_fechaini desc, hojas.tarea_id";
          }
          
           $rs = $this->_CI->db->query($sql, $values)->result_array();
     
           return $rs;                               

    }

 

    public function get_hoja( $hoja_id,  $params = array(), $calendario = false, $resumen = false )
    { 
        /* Obtenemos el rango de fechas de la hoja de asistencia para generar el calendario */
  
        $sql = " SELECT * FROM planillas.hojaasistencia WHERE hoa_id = ? LIMIT 1 ";
        list($rs) = $this->_CI->db->query($sql, array($hoja_id))->result_array();

        $fecha_ini = $rs['hoa_fechaini'];
        $fecha_fin = $rs['hoa_fechafin'];
        
        $dias_calendario = "";
        
        $dias = $this->_CI->db->query(" SELECT * FROM public.calendario 
                                        WHERE caldia_dia between ? AND ? 
                                        ORDER BY caldia_dia ",  array( $fecha_ini, $fecha_fin) )->result_array();
         
        foreach($dias as $k => $dia)
        {
            if($k>0)  $dias_calendario.=',';
            $dias_calendario.=' "'.$dia['caldia_dia'].'" text'; 

        }  

        $verif_hora1_e =  " (CASE WHEN hoae_hora1_e < hor.hor_hora1_e THEN
                               hor.hor_hora1_e
                          ELSE 
                               hoae_hora1_e
                          END )  ";


        $verif_hora1_s =  " (CASE WHEN hoae_hora1_s > hor.hor_hora1_s THEN
                               hor.hor_hora1_s
                          ELSE 
                               hoae_hora1_s
                          END )  ";


        $sql_calculo_horas_contabilizadas = "COALESCE( EXTRACT( 'hour' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                        (COALESCE( EXTRACT( 'minutes' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";


        $sql_calculo_horas_contabilizadas_c = "COALESCE( EXTRACT( ''hour'' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                        (COALESCE( EXTRACT( ''minutes'' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";

        
        $sql_horas_contabilizadas =  " SELECT hoae.hoae_id, 
                                              SUM(
                                                  CASE WHEN ( ".$sql_calculo_horas_contabilizadas." ) is null  OR ( hoae_hora1_e is null OR hoae_hora1_s is null )  THEN  
                                                      0  
                                                  ELSE  
                                                      (".$sql_calculo_horas_contabilizadas." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                                                  END  
                                                ) as horas_trabajadas
                      
                                      FROM planillas.hojaasistencia hoa  
                                      INNER JOIN planillas.hojaasistencia_emp hoae ON hoa.hoa_id = hoae.hoa_id AND hoae.hoae_estado = 1 
                                      INNER JOIN planillas.hojaasistencia_emp_dia hoaed ON hoae.hoae_id = hoaed.hoae_id AND hoaed.hoaed_estado = 1 AND hoaed.hatd_id = ".ASISDET_ASISTENCIA."
                                      INNER JOIN planillas.hojaasistencia_horarios hor ON hoaed.hor_id = hor.hor_id 
                                      WHERE hoa.hoa_id =  ".$hoja_id."
                                      GROUP BY hoae.hoae_id 
                                      ORDER BY hoae.hoae_id   ";


        $sql_base_calendario = "    SELECT * FROM crosstab('
                                          SELECT   
                                              det.hoae_key,
                                              det.hoae_id,
                                              ind.indiv_id,
                                              ind.indiv_key, 
                                              ind.indiv_dni,
                                              ( COALESCE(ind.indiv_appaterno,'''') || '' '' || COALESCE(ind.indiv_apmaterno,'''') || '' '' || COALESCE(ind.indiv_nombres,'''') ) as trabajador,  
                                              coalesce(platica.platica_id,0),
                                              platica.platica_nombre as categoria, 
                                              dia.hoaed_fecha,
                                              ( COALESCE(dia.hatd_id,''0'') || ''_'' || 
                                                COALESCE(hoaed_obs,'''') || ''_'' || 
                                                COALESCE( (CASE WHEN hor.hor_numero_horarios = 2 THEN  
                                                                EXTRACT( ''hour'' FROM (hoae_hora1_e - hor.hor_hora1_e  + hoae_hora2_e - hor.hor_hora2_e) ) || ''_'' ||  
                                                                EXTRACT( ''minutes'' FROM (  hoae_hora1_e - hor.hor_hora1_e  + hoae_hora2_e - hor.hor_hora2_e ) ) 

                                                           ELSE  
                                                                EXTRACT( ''hour'' FROM ( hoae_hora1_e - hor.hor_hora1_e  ) ) || ''_'' ||  
                                                                EXTRACT( ''minutes'' FROM ( hoae_hora1_e - hor.hor_hora1_e  ) )  

                                                           END ),''0_0'') || ''_'' || 

                                                COALESCE(hoaed_permisos,''0'') || ''_'' ||  

                                                (CASE WHEN ( ( persla.persla_terminoindefinido = 0 AND ( hoaed_fecha < persla.persla_fechaini OR  persla.persla_fechafin < hoaed_fecha) ) OR (persla.persla_terminoindefinido = 1 AND hoaed_fecha < persla.persla_fechaini)) THEN 
                                                    1 
                                                 ELSE 0 END   ) || ''_'' || 

                                                dia.hoaed_laborable || ''_'' || 
                                                dia.biom_id  || ''_'' ||  

                                                (CASE WHEN hor.hor_numero_horarios = 2 THEN  

                                                    COALESCE( EXTRACT( ''hour'' FROM (hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e) ), 0) || ''_'' || 
                                                    COALESCE( EXTRACT( ''minutes'' FROM (hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e) ),0) 

                                                 ELSE 

                                                    COALESCE( EXTRACT( ''hour'' FROM (hoae_hora1_s - hoae_hora1_e ) ) , 0) || ''_'' || 
                                                    COALESCE( EXTRACT( ''minutes'' FROM (hoae_hora1_s - hoae_hora1_e ) ) , 0)  

                                                  END ) || ''_'' ||     

                                                  ( CASE WHEN (  $sql_calculo_horas_contabilizadas_c ) is null  OR ( hoae_hora1_e is null OR hoae_hora1_s is null )  THEN  
                                                      0  
                                                  ELSE  
                                                      ROUND( ( $sql_calculo_horas_contabilizadas_c  - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )::numeric , 2 )
                                                  END   )  || ''_'' || 

                                                  COALESCE(dia.hoae_hora1_e, ''00:00:00'') || ''_'' || COALESCE(dia.hoae_hora1_s, ''00:00:00'')  || ''_'' || 
                                                  COALESCE(dia.hoae_hora2_e,  ''00:00:00'') || ''_'' || COALESCE(dia.hoae_hora2_s, ''00:00:00'') || ''_'' || 
                                                  
                                                  dia.hoaed_importado || ''_'' || dia.pla_id || ''_'' || 

                                                  COALESCE(( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id ), '''')  || ''_'' ||  

                                                  COALESCE(  permisos.minutos  , ''0'') 
                                                   

                    
                                               ) as asistencia

                                               FROM planillas.hojaasistencia_emp det
                                               LEFT JOIN planillas.planilla_tipo_categoria platica On det.platica_id = platica.platica_id 
                                               LEFT JOIN planillas.hojaasistencia_emp_dia dia ON det.hoae_id = dia.hoae_id  AND dia.hoaed_estado = 1 
                                               LEFT JOIN public.individuo ind ON det.indiv_id = ind.indiv_id 
                                               LEFT JOIN planillas.hojaasistencia_horarios hor On dia.hor_id = hor.hor_id 
                                               LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_ultimo = 1
                                               LEFT JOIN planillas.planillas pla ON dia.pla_id = pla.pla_id 
                                               LEFT JOIN ( 


                                                     SELECT pp.pers_id as indiv_id, pepe_fechadesde as fecha,  SUM(( EXTRACT( ''hour'' FROM (pepe_horafin - pepe_horaini ) ) * 60 ) + EXTRACT( ''minutes'' FROM (pepe_horafin - pepe_horaini ) ) ) as minutos
                                                     FROM rh.persona_permiso pp 
                                                     INNER JOIN rh.persona_permiso_movimiento ppm ON pp.pepe_id = ppm.pepe_id AND ppm.pepem_estado = 1 AND ppm.ppest_id >= 3 
                                                     WHERE pp.pepe_estado = 1 AND pp.pepe_fechadesde  between ''".$fecha_ini."'' AND ''".$fecha_fin."'' 
                                                     GROUP BY indiv_id, pepe_fechadesde 

                                                ) as permisos  ON  det.indiv_id = permisos.indiv_id AND dia.hoaed_fecha = permisos.fecha
                                            
                                              WHERE det.hoa_id = ".$hoja_id." AND det.hoae_estado = 1   
                                              ORDER BY trabajador, categoria, hoaed_fecha  ',  

                                        '  SELECT caldia_dia FROM public.calendario WHERE caldia_dia between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ORDER BY caldia_dia  ' )
                                      AS
                                      (   
                                             indice text,
                                             hoae_id int,
                                             indiv_id int,
                                             indiv_key text,
                                             dni text,
                                             trabajador text, 
                                             platica_id int,
                                             categoria text,
                                             ".$dias_calendario."
                                           
                                      ) ";
 
  
        
        $sql_importados = "  SELECT  dia.hoae_id, SUM(hoaed_importado) as registros_importados 
                             FROM planillas.hojaasistencia_emp_dia dia 
                             WHERE dia.hoaed_estado = 1 
                             GROUP BY dia.hoae_id   
                           ";

        $sql_calendario  = " SELECT  calendario.*, importados.registros_importados 

                              FROM ( 
                                
                                ".$sql_base_calendario."
                    
                              ) as calendario 
                              LEFT JOIN (".$sql_importados.") as importados ON calendario.hoae_id = importados.hoae_id ";

  
 
        
        /* 
           RESUMEN DE LA HOJA 
        */

       $plati_id  = 9;

       $sql_tipos = " SELECT hatd_label 
                      FROM planillas.hojadiario_tipo ht
                      INNER JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = ?
                      WHERE hatd_estado = 1 AND  htp.htp_mostrarenresumen = 1
                      ORDER BY ht.hatd_orden asc ";

       $rs = $this->_CI->db->query($sql_tipos, array($plati_id) )->result_array();

       $sql_tipos_header = '';
       
       foreach($rs as $reg)
       {
           $sql_tipos_header.= ', "dato_'.$reg['hatd_label'].'" numeric(10,2)';
       }


       if($params['ver_modo'] != '1')
       {
            $sql_visualizar = "    SUM( CASE WHEN htp.htp_importar_horas = 1 THEN 

                                                   ( CASE WHEN ( ".$sql_calculo_horas_contabilizadas_c." ) is null  OR ( hoae_hora1_e is null OR hoae_hora1_s is null ) THEN  
                                                       0  
                                                   ELSE  
                                                       (".$sql_calculo_horas_contabilizadas_c." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                                                   END   )  
                                             
                                             WHEN htp.htp_importar_horas = 2 THEN 
                                             
                                                      (  COALESCE( EXTRACT( ''hour'' FROM ( hor.hor_hora1_s - hor.hor_hora1_e ) ) , 0) + 
                                                         (COALESCE( EXTRACT( ''minutes'' FROM ( hor.hor_hora1_s - hor.hor_hora1_e ) ) , 0) / 60 )  - hor_descontar_horas
                                                       )
                                             
                                             WHEN htp.htp_importar_horas = 3 THEN 

                                                       (  COALESCE( EXTRACT( ''hour'' FROM ( hor.hor_hora1_s2 - hor.hor_hora1_e2 ) ) , 0) + 
                                                          (COALESCE( EXTRACT( ''minutes'' FROM ( hor.hor_hora1_s2 - hor.hor_hora1_e2 ) ) , 0) / 60 )  - hor_descontar_horas
                                                        )

                                             ELSE
                                                    1 

                                             END ) as total  ";
       }
       else
       {

            $sql_visualizar =" SUM(1) as total ";
       }

       $sql_resumen = " SELECT  * FROM crosstab( '    

                                SELECT  (hoae.indiv_id || ''_''|| hoae.platica_id) as indice_resumen, hoae.indiv_id, hoae.platica_id, hoae.hoagru_id, ht.hatd_label, 

                                      ".$sql_visualizar."
           
                                 FROM planillas.hojaasistencia hoa  
                                 INNER JOIN  planillas.hojaasistencia_emp hoae ON hoa.hoa_id = hoae.hoa_id AND hoae.hoae_estado = 1 
                                 INNER JOIN  planillas.hojaasistencia_emp_dia hoaed ON hoae.hoae_id = hoaed.hoae_id AND hoaed.hoaed_estado = 1 AND hoaed_laborable = 1 
                                 INNER JOIN  planillas.hojaasistencia_horarios hor ON hoaed.hor_id = hor.hor_id
                                 INNER JOIN  planillas.hojadiario_tipo_plati htp ON hoaed.hatd_id = htp.hatd_id AND htp.plati_id = hoa.plati_id AND htp_estado = 1 AND htp.htp_mostrarenresumen = 1
                                 INNER JOIN  planillas.hojadiario_tipo ht ON htp.hatd_id = ht.hatd_id   
                                 WHERE hoa.hoa_id IN(".$hoja_id.")   
                                 GROUP BY hoae.indiv_id, hoae.platica_id, hoae.hoagru_id, ht.hatd_label, ht.hatd_orden    
                                 ORDER BY hoae.indiv_id, hoae.platica_id, hoae.hoagru_id, ht.hatd_label, ht.hatd_orden asc ', 

                                 ' SELECT hatd_label FROM planillas.hojadiario_tipo ht
                                   INNER JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = $plati_id 
                                   WHERE hatd_estado = 1 AND htp.htp_mostrarenresumen = 1 ORDER BY ht.hatd_orden asc  ' )
                                 AS( indice_resumen text,
                                     indiv_id int,
                                     platica_id int,
                                     hoagru_id int
                                     ".$sql_tipos_header."
                                   )

                            "; 
 
     
        if($calendario == true && $resumen == false)
        {
            $sql = $sql_calendario;

            if( trim($params['orden']) == '1' )
            {
               $sql.=" ORDER BY calendario.trabajador, calendario.categoria ";
             
            }
            else
            {
               $sql.=" ORDER BY  calendario.categoria, calendario.trabajador  ";
            }

        }
        else if($calendario == false && $resumen == true)
        {
         
            $sql = "  SELECT   
                          indiv.indiv_key,
                          indiv.indiv_dni,
                          ( COALESCE(indiv.indiv_appaterno,'') || ' ' || COALESCE(indiv.indiv_apmaterno,'') || ' ' || COALESCE(indiv.indiv_nombres,'') ) as trabajador,  
                          platica.platica_nombre,
                          dataresumen.*

                      FROM (".$sql_resumen.") as dataresumen 
                      LEFT JOIN public.individuo indiv ON dataresumen.indiv_id = indiv.indiv_id 
                      LEFT JOIN planillas.planilla_tipo_categoria platica On dataresumen.platica_id = platica.platica_id 
                      
                      WHERE indiv.indiv_estado = 1   
                   ";

            if($params['categoria'] != '' && $params['categoria'] != '0')
            {

                $sql.=" AND dataresumen.platica_id = ? ";
                $values[] = $params['categoria'];

            }

            if($params['trabajador'] != '')
            {

                $sql.=" AND  ( COALESCE(indiv.indiv_appaterno,'') || ' ' || COALESCE(indiv.indiv_apmaterno,'') || ' ' || COALESCE(indiv.indiv_nombres,'') ) like ? ";
                $values[] = '%'.strtoupper($params['trabajador']).'%';

            }


            if( trim($params['orden']) == '1' )
            {
               $sql.=" ORDER BY trabajador, platica.platica_nombre ";
             
            }
            else
            {
               $sql.=" ORDER BY platica.platica_nombre, trabajador  ";
            }

        }
        else if($calendario == true && $resumen == true)
        {

            $sql = " SELECT calendario.*, resumen.* 
                     FROM (".$sql_base_calendario.") as calendario
                     LEFT JOIN (".$sql_resumen.") as resumen ON calendario.indiv_id = resumen.indiv_id AND calendario.platica_id = resumen.platica_id 

                   ";

            if( trim($params['orden']) == '1' )
            {
               $sql.=" ORDER BY calendario.trabajador, calendario.categoria ";
             
            }
            else
            {
               $sql.=" ORDER BY  calendario.categoria, calendario.trabajador  ";
            }


        }
        else
        {

        }


        //echo $sql;
        $rs= $this->_CI->db->query($sql, $values)->result_array();

        return $rs; 
        

    }
 


    public function get_registro_asistencia( $params = array(), $calendario = false, $resumen = false )
    {
       
        /* Obtenemos el rango de fechas de la hoja de asistencia para generar el calendario */

        // $t = "1__0_2_0_0_1_1_7_57_7.71666666666667_07:32:00_15:29:00_00:00:00_00:00:00_0_0__0_0-0_45";
        // $rs = explode('_', $t);
        // var_dump($rs);
        // die();

        $fecha_ini = $params['fechadesde'];
        $fecha_fin = $params['fechahasta'];
         
        $dias_calendario = "";
        
        $dias = $this->_CI->db->query(" SELECT * FROM public.calendario 
                                        WHERE caldia_dia between ? AND ? 
                                        ORDER BY caldia_dia ",  array( $fecha_ini, $fecha_fin) )->result_array();
         
        foreach($dias as $k => $dia)
        {
            if($k>0)  $dias_calendario.=',';
            $dias_calendario.=' "'.$dia['caldia_dia'].'" text'; 

        } 
    


        $verif_hora1_e =  " (CASE WHEN hoae_hora1_e < hor.hor_hora1_e THEN
                               hor.hor_hora1_e
                          ELSE 
                               hoae_hora1_e
                          END )  ";


        $verif_hora1_s =  " (CASE WHEN hoae_hora1_s > hor.hor_hora1_s THEN
                               hor.hor_hora1_s
                          ELSE 
                               hoae_hora1_s
                          END )  ";


        $sql_calculo_horas_contabilizadas = "COALESCE( EXTRACT( 'hour' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                        (COALESCE( EXTRACT( 'minutes' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";


        $sql_calculo_horas_contabilizadas_c = "COALESCE( EXTRACT( ''hour'' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                        (COALESCE( EXTRACT( ''minutes'' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";

        
 
       
       $desde_trabajadores = false;


      if(trim($params['mostraractivos']) === '2' )
       {
  
           $sql_solo_trabajadores_con_asistencia  = " SELECT distinct(dia.indiv_id) 
                                                      FROM planillas.hojaasistencia_emp_dia dia 
                                                      WHERE  hoaed_estado =  1 AND ( dia.hoaed_fecha between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ) ";
     

           $sql_desde_trabajdores  = "SELECT indiv.* 
                                      FROM (".$sql_solo_trabajadores_con_asistencia.") con_asistencia  
                                      INNER JOIN public.individuo indiv ON con_asistencia.indiv_id = indiv.indiv_id 
                                      INNER JOIN rh.persona_situlaboral persla ON persla.pers_id = indiv.indiv_id AND persla_estado = 1 AND persla_ultimo = 1 
                                      WHERE indiv.indiv_estado = 1   ";
       }
       else
       {

            $sql_desde_trabajdores  = "SELECT indiv.* 
                                       FROM  public.individuo indiv  
                                       INNER JOIN rh.persona_situlaboral persla ON persla.pers_id = indiv.indiv_id AND persla_estado = 1 AND persla_ultimo = 1 
                                       WHERE indiv.indiv_estado = 1   ";

       }

       if(trim($params['dni']) != '' && trim($params['dni']) != '0' )
       {
          $sql_desde_trabajdores.=" AND indiv.indiv_dni = ''".$params['dni']."''"; 
          $desde_trabajadores = true;
       }


       if( is_array($params['plati_id']) || (trim($params['plati_id']) != '' && trim($params['plati_id']) != '0') ) 
       {

         if(is_array($params['plati_id'])){

            if(sizeof($params['plati_id']) == 0){

               $in_plati = '0';
            
            }else{

               $in_plati = implode(',', $params['plati_id']);
            }
   
         }
         else
         {
              $in_plati = trim($params['plati_id']);
         } 
         
         $sql_desde_trabajdores.=" AND persla.plati_id IN (".$in_plati.")"; 
         $desde_trabajadores = true;
         
       }


       if(trim($params['lugar_de_trabajo']) != '' && trim($params['lugar_de_trabajo']) != '0' )
       {
          $sql_desde_trabajdores.=" AND indiv.indiv_lugar_de_trabajo = ".$params['lugar_de_trabajo']; 
          $desde_trabajadores = true;
       }
 
       if(trim($params['area_id']) != '' && trim($params['area_id']) != '0' )
       {
          $sql_desde_trabajdores.=" AND persla.depe_id = ".$params['area_id']; 
          $desde_trabajadores = true;
       }

       if(trim($params['mostraractivos']) === '1' )
       {
          $sql_desde_trabajdores.=" AND persla.persla_vigente = 1 "; 
       }
  
      $sql_desde_trabajdores.= "   ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres ";


      if($params['count'] === false)
      {

        if( $params['limit'] > 0 ) $sql_desde_trabajdores.= "  LIMIT ".$params['limit'];
          
        if( $params['offset'] > 0 && $params['limit'] > 0 )
        {
            
           $sql_desde_trabajdores.= " OFFSET ".$params['offset']; 
           
        }
      }
 

        $sql_calendario  = " SELECT  ";

        if( $params['count'] === false )
        {
             $sql_calendario.="   calendario.*, 
                                  plati_nombre as regimen_actual, 
                                  plati_abrev as regimen_actual_abrev
                                  ";

            if($params['incluir_tareas'] == true){

                $sql_calendario.=" ,tareas.* ";
            }

        }
        else
        {
            $sql_calendario.=" count(*) as total ";
        }

        $sql_calendario.=" 

                              FROM ( 

                                    SELECT * FROM crosstab(' 
                                          SELECT   
                         ";

                          if($params['mostrar_categorias'] ==  true)
                          {
                              $sql_calendario.=" (ind.indiv_key || ''|'' || dia.platica_id ) as indice, ";
                          }
                          else
                          {
                              $sql_calendario.=" ind.indiv_key as indice, ";
                          }


        $sql_calendario.=" 
                                              ind.indiv_id,
                                              ind.indiv_key, 
                                              ind.indiv_dni,
                                              ( COALESCE(ind.indiv_appaterno,'''') || '' '' || COALESCE(ind.indiv_apmaterno,'''') || '' '' || COALESCE(ind.indiv_nombres,'''') ) as trabajador,  
                                              coalesce(platica.platica_id,0),
                                              platica.platica_nombre as categoria, 
                                              dia.hoaed_fecha,
                                          
                                              ( COALESCE(dia.hatd_id,''0'') || ''_'' || 
                                              
                                                COALESCE(hoaed_obs,'''') || ''_'' || 
                                              
                                                COALESCE( (CASE WHEN hor.hor_numero_horarios = 2 THEN  
                                                           
                                                                EXTRACT( ''hour'' FROM (hoae_hora1_e - hor.hor_hora1_e  + hoae_hora2_e - hor.hor_hora2_e) ) || ''_'' ||  
                                                           
                                                                EXTRACT( ''minutes'' FROM (  hoae_hora1_e - hor.hor_hora1_e  + hoae_hora2_e - hor.hor_hora2_e ) ) 

                                                           ELSE  
                                                           
                                                                EXTRACT( ''hour'' FROM ( hoae_hora1_e - hor.hor_ini_tardanza  ) ) || ''_'' ||  
                                                           
                                                                EXTRACT( ''minutes'' FROM ( hoae_hora1_e - hor.hor_ini_tardanza  ) )  

                                                           END ),''0_0'') || ''_'' || 

                                                COALESCE(hoaed_permisos,''0'') || ''_'' ||  

                                                (CASE WHEN ( persla.persla_id is NULL) THEN 
                                                    1 
                                                 ELSE 0 END   ) || ''_'' || 

                                                dia.hoaed_laborable || ''_'' || 
                                               
                                                dia.biom_id  || ''_'' ||  

                                                (CASE WHEN hor.hor_numero_horarios = 2 THEN  

                                                    COALESCE( EXTRACT( ''hour'' FROM (hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e) ), 0) || ''_'' || 
                                               
                                                    COALESCE( EXTRACT( ''minutes'' FROM (hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e) ),0) 

                                                 ELSE 

                                                    COALESCE( EXTRACT( ''hour'' FROM (hoae_hora1_s - hoae_hora1_e ) ) , 0) || ''_'' || 
                                             
                                                    COALESCE( EXTRACT( ''minutes'' FROM (hoae_hora1_s - hoae_hora1_e ) ) , 0)  

                                                  END ) || ''_'' ||     

                                              ( CASE WHEN (  $sql_calculo_horas_contabilizadas_c ) is null  OR ( hoae_hora1_e is null OR hoae_hora1_s is null )  THEN  
                                                    0  
                                                ELSE  
                                                    ( $sql_calculo_horas_contabilizadas_c  - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                                                END   )  || ''_'' || 

                                                COALESCE(dia.hoae_hora1_e, ''00:00:00'') || ''_'' || 

                                                COALESCE(dia.hoae_hora1_s, ''00:00:00'')  || ''_'' || 
                                                
                                                COALESCE(dia.hoae_hora2_e,  ''00:00:00'') || ''_'' || 

                                                COALESCE(dia.hoae_hora2_s, ''00:00:00'') || ''_'' || 
                                                
                                                dia.hoaed_importado || ''_'' || 

                                                dia.pla_id || ''_'' || 

                                                COALESCE(( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id ), '''')  || ''_'' ||  

                                                 COALESCE(dia.hoa_id,0) || ''_'' || 

                                                 (  substring(  COALESCE(hoa.anio_eje,'''') from 3 for 2) || COALESCE(hoa.hoa_id,0) ||  ''-'' ||COALESCE(hoa.plati_id,0) )  
                              ";


        if($params['incluir_permisos'] == true){

            $sql_calendario.="               || ''_'' ||  COALESCE(descuentos_permisos.minutos_a_descontar,0) ";

        }

        $sql_calendario.="  
                    
                                               ) as asistencia  
                                        "; 


                      if($desde_trabajadores == true)
                      {
                           $sql_calendario.=" FROM (".$sql_desde_trabajdores.") as ind 
                                              LEFT JOIN planillas.hojaasistencia_emp_dia dia ON ind.indiv_id = dia.indiv_id  AND  hoaed_estado = 1 AND ( dia.hoaed_fecha between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ) 
                                            ";
                      } 
                      else
                      {
                           $sql_calendario.=" FROM planillas.hojaasistencia_emp_dia dia 
                                              LEFT JOIN public.individuo ind ON dia.indiv_id = ind.indiv_id   ";
                      }
        


          $sql_calendario.="                  LEFT JOIN planillas.hojaasistencia_horarios hor ON dia.hor_id = hor.hor_id 
                                              LEFT JOIN planillas.planilla_tipo_categoria platica ON dia.platica_id = platica.platica_id    
                                              LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_estado = 1 AND ( (persla.persla_terminoindefinido = 0 AND (dia.hoaed_fecha BETWEEN persla.persla_fechaini AND persla.persla_fechafin) ) OR 
                                                                                                                                                   (persla.persla_terminoindefinido = 1 AND persla.persla_fechacese is NULL AND dia.hoaed_fecha > persla.persla_fechaini ) OR 
                                                                                                                                                   (persla.persla_terminoindefinido = 1 AND persla.persla_fechacese is not NULL AND (dia.hoaed_fecha BETWEEN persla.persla_fechaini AND persla.persla_fechacese) )  )
                                              LEFT JOIN planillas.planillas pla ON dia.pla_id = pla.pla_id 
                                              LEFT JOIN planillas.hojaasistencia hoa ON dia.hoa_id = hoa.hoa_id 
                           ";

            if($params['incluir_permisos'] == true){

                 if($params['considerar_solo_permisos'] == true){
                 
                     $sql_calendario.=" INNER JOIN ";
                 }
                 else{

                     $sql_calendario.=" LEFT JOIN ";
                 }

                 $sql_calendario.="  (
                                           
                                      SELECT  
                                             datos_permisos.indiv_id,
                                             datos_permisos.dia,  
                                             
                                             SUM( COALESCE( (CASE WHEN permot_congoce = 0 THEN 

                                                 ( (Extract(''hours'' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( ''minutes'' FROM (permiso_hora_regreso - permiso_hora_inicio) ) ) 

                                         /* Cuando la licencia es con goce pero ha superado el limite de minutos, se descuenta el numero de minutos permitidos  */  
                                              WHEN  ( (Extract(''hours'' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( ''minutes'' FROM (permiso_hora_regreso - permiso_hora_inicio) ) ) > permot_limiteminutos THEN

                                          ( ( (Extract(''hours'' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( ''minutes'' FROM (permiso_hora_regreso - permiso_hora_inicio) ) )  - permot_limiteminutos )

                                        /* CUando la licencia es con goce y no ha superado el limite de minutos */
                                              ELSE 

                                                 ( (Extract(''hours'' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( ''minutes'' FROM (permiso_hora_regreso - permiso_hora_inicio) ) )  
                                       
                                              END), 0)  )as minutos_a_descontar

                                      FROM (

                                            SELECT pp.pers_id as indiv_id, 
                                                   pepe_fechadesde as dia,
                                                   mot.permot_id,
                                                   mot.permot_congoce,
                                                   mot.permot_horainicio,
                                                   mot.permot_horafin,
                                                   mot.permot_limiteminutos,
                                                     (CASE WHEN hor_dia.hor_id is not null THEN 

                                                      CASE WHEN pp.pepe_horaini < hor_dia.hor_hora1_e THEN 
                                                          hor_dia.hor_hora1_e
                                                      ELSE 
                                                          pp.pepe_horaini  
                                                      END 
                                                                              
                                                                         ELSE  
                                                      CASE WHEN pp.pepe_horaini < hor_plati.hor_hora1_e THEN 
                                                          hor_plati.hor_hora1_e
                                                      ELSE 
                                                          pp.pepe_horaini  
                                                      END 

                                                                         END) as permiso_hora_inicio,
                                                                         
                                                                         (CASE WHEN hor_dia.hor_id is not null THEN 

                                                      CASE WHEN pp.pepe_horafin > hor_dia.hor_hora1_s THEN 
                                                         hor_dia.hor_hora1_s
                                                      ELSE 
                                                          pp.pepe_horafin  
                                                      END 
                                                                              
                                                                         ELSE  
                                                      CASE WHEN pp.pepe_horafin > hor_plati.hor_hora1_s THEN 
                                                          hor_plati.hor_hora1_s
                                                      ELSE 
                                                          pp.pepe_horafin  
                                                      END 

                                                   END) as permiso_hora_regreso
                                                                      
                                             FROM rh.persona_permiso pp 
                                             INNER JOIN rh.permiso_motivo mot ON pp.permot_id = mot.permot_id  
                                             INNER JOIN rh.persona_situlaboral persla ON pp.pers_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                                             LEFT JOIN planillas.hojaasistencia_emp_dia dia ON pp.pers_id = dia.indiv_id  AND pp.pepe_fechadesde = dia.hoaed_fecha AND  dia.hoaed_estado = 1  
                                             LEFT JOIN planillas.hojaasistencia_horarios hor_dia ON dia.hor_id = hor_dia.hor_id 
                                             LEFT JOIN planillas.planillatipo_dia_horario ptdh ON ptdh.plati_id = persla.plati_id AND EXTRACT(''dow'' from pp.pepe_fechadesde) = ptdh.dia_id 
                                             LEFT JOIN planillas.hojaasistencia_horarios hor_plati ON ptdh.hor_id = hor_plati.hor_id 
                      
                                             WHERE pepe_estado = 1  AND ( mot.permot_congoce = 0 OR mot.permot_limiteminutos > 0 )   AND ( pp.pepe_fechadesde between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ) 

                                       ) as datos_permisos
                                       GROUP BY datos_permisos.indiv_id, datos_permisos.dia 
                                       ORDER BY datos_permisos.indiv_id, datos_permisos.dia 

                                ) as descuentos_permisos ON ind.indiv_id = descuentos_permisos.indiv_id AND dia.hoaed_fecha = descuentos_permisos.dia 
  
                      ";
            }     


         $sql_calendario.="   WHERE ind.indiv_estado = 1 ";

          if($desde_trabajadores == false)
          { 

              if($params['hoja'] == '' || $params['hoja'] == '0'){

                  $sql_calendario.="  AND hoaed_estado = 1  AND ( dia.hoaed_fecha between ''".$fecha_ini."'' AND ''".$fecha_fin."'' )  ";
              }
              

              if($params['tarea_id'] != '' && $params['tarea_id'] != '0')
              {
                 $sql_calendario.=" AND hoa.tarea_id = ".$params['tarea_id'];
              } 

              if($params['importacion'] != '' && $params['importacion'] != '0')
              {
                 $sql_calendario.=" AND dia.plaasis_id = ".$params['importacion'];
              }

              if($params['hoja'] != '' && $params['hoja'] != '0')
              {
                 $sql_calendario.=" AND (  substring( hoa.anio_eje from 3 for 2) || hoa.hoa_id ||  ''-'' ||hoa.plati_id ) = ''".$params['hoja']."''";
              }
              

          }

           $sql_calendario.=" 
                              ORDER BY trabajador, categoria, hoaed_fecha  ',  

                        '  SELECT caldia_dia FROM public.calendario WHERE caldia_dia between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ORDER BY caldia_dia  ' )
                      AS
                      (   
                             indice text,
                             indiv_id int,
                             indiv_key text,
                             dni text,
                             trabajador text, 
                             platica_id int,
                             categoria text,
                             ".$dias_calendario."
                           
                      ) 
    
              ) as calendario 

              LEFT JOIN rh.persona_situlaboral persla_actual ON persla_actual.pers_id = calendario.indiv_id AND persla_estado = 1 AND persla_ultimo = 1 
              LEFT JOIN planillas.planilla_tipo plati ON persla_actual.plati_id = plati.plati_id 
 
           "; 


      if($params['incluir_tareas'] == true){

           $sql_calendario.=" 
                            LEFT JOIN ( 
                                   SELECT * 
                                   FROM crosstab(' 
                                 
                                       SELECT distinct(hoae.indiv_id),''tarea'' ,( t.sec_func ||''-''||t.tarea_nro  ) as tarea_codigo                         
                                       FROM planillas.hojaasistencia hoa  
                                       INNER JOIN  planillas.hojaasistencia_emp hoae ON hoa.hoa_id = hoae.hoa_id AND hoae.hoae_estado = 1 
                                       INNER JOIN  planillas.hojaasistencia_emp_dia hoaed ON hoae.hoae_id = hoaed.hoae_id AND hoaed.hoaed_estado = 1 AND hoaed_laborable = 1 
                                       INNER JOIN sag.tarea t ON  hoa.tarea_id = t.tarea_id
                                       WHERE hoaed.hoaed_fecha between ''".$fecha_ini."'' AND ''".$fecha_fin."''
                                       ORDER BY hoae.indiv_id, tarea_codigo  

                                      ') as 
                                       (indiv_id  int, 
                                        tarea_1  text,
                                        tarea_2  text,
                                        tarea_3  text,
                                        tarea_4  text,
                                        tarea_5  text,
                                        tarea_6  text,
                                        tarea_7  text) 
                           ) as tareas ON calendario.indiv_id = tareas.indiv_id

                            ";    

        }

        if($params['count'] === false)
        {

            if( trim($params['orden']) == '1' || trim($params['orden']) == '' )
            {
               $sql_calendario.=" ORDER BY calendario.trabajador, calendario.categoria ";
            }
            else
            {
               $sql_calendario.=" ORDER BY  calendario.categoria, calendario.trabajador  ";
            }
        }

        if($desde_trabajadores == false)
        {

          if( $params['limit'] > 0 ) $sql_calendario.= "  LIMIT  ".$params['limit'];
            
          if( $params['offset'] > 0 && $params['limit'] > 0 )
          {
              
             $sql_calendario.= " OFFSET ".$params['offset']; 
             
          }
       }
        
        /* 
           RESUMEN DE LA HOJA 
        */
 
       $sql_tipos = " SELECT hatd_id 
                      FROM planillas.hojadiario_tipo ht
                      WHERE hatd_estado = 1 AND hatd_mostrar_resumen = 1
                      ORDER BY ht.hatd_orden asc ";

       $rs = $this->_CI->db->query($sql_tipos, array() )->result_array();

       $sql_tipos_header = '';
       
       foreach($rs as $reg)
       {
           $sql_tipos_header.= ', "dato_'.$reg['hatd_id'].'" numeric(10,2)';
       }

 
       $sql_visualizar =" SUM(1) as total ";

       $sql_resumen = " SELECT * FROM crosstab( '    

                                SELECT hoaed.indiv_id  as indice_resumen,
                                       hoaed.indiv_id, 
                                       ht.hatd_id, 

                                      ".$sql_visualizar."
           
                                 FROM planillas.hojaasistencia_emp_dia hoaed  
                                 INNER JOIN  planillas.hojaasistencia_horarios hor ON hoaed.hor_id = hor.hor_id
                                 INNER JOIN  planillas.hojadiario_tipo ht ON hoaed.hatd_id = ht.hatd_id AND hatd_mostrar_resumen = 1
                                 WHERE  hoaed_estado =  1 AND ( hoaed.hoaed_fecha between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ) 
                                 GROUP BY hoaed.indiv_id, ht.hatd_id, ht.hatd_orden    
                                 ORDER BY hoaed.indiv_id, ht.hatd_id, ht.hatd_orden asc ', 

                                 ' SELECT hatd_id 
                                   FROM planillas.hojadiario_tipo ht 
                                   WHERE hatd_estado = 1 AND hatd_mostrar_resumen = 1 
                                   ORDER BY ht.hatd_orden asc  ' )
                                 AS( indice_resumen text,
                                     indiv_id int
                                     ".$sql_tipos_header."
                                   )

                            "; 
    
        

        if($calendario == true && $resumen == false)
        {
            $sql = $sql_calendario;

         /*   if( trim($params['orden']) == '1' )
            {
               $sql.=" ORDER BY calendario.trabajador, calendario.categoria ";
             
            }
            else
            {
               $sql.=" ORDER BY  calendario.categoria, calendario.trabajador  ";
            }*/

        }
        else if($calendario == true && $resumen == true)
        { 

            $sql.=" SELECT * 
                    FROM (".$sql_calendario.") as datos
                    LEFT JOIN (".$sql_resumen.") as resumen ON datos.indiv_id = resumen.indiv_id  

                  ";
         
            // $sql = "  SELECT   
            //               indiv.*,
            //               ( COALESCE(indiv.indiv_appaterno,'') || ' ' || COALESCE(indiv.indiv_apmaterno,'') || ' ' || COALESCE(indiv.indiv_nombres,'') ) as trabajador,  
            //               dataresumen.*

            //           FROM (".$sql_resumen.") as dataresumen 
            //           LEFT JOIN public.individuo indiv ON dataresumen.indiv_id = indiv.indiv_id  
            //           WHERE indiv.indiv_estado = 1   
            //        ";
 
            // if($params['trabajador'] != '')
            // {
            //     $sql.=" AND  ( COALESCE(indiv.indiv_appaterno,'') || ' ' || COALESCE(indiv.indiv_apmaterno,'') || ' ' || COALESCE(indiv.indiv_nombres,'') ) like ? ";
            //     $values[] = '%'.strtoupper($params['trabajador']).'%';

            // }


            // if( trim($params['orden']) == '1' )
            // {
            //    $sql.=" ORDER BY trabajador, platica.platica_nombre ";
             
            // }
            // else
            // {
            //    $sql.=" ORDER BY platica.platica_nombre, trabajador  ";
            // }

        }
        else
        {

        }

        // echo '<pre>';
        // var_dump($sql);
        // die();
        // echo '</pre>';

        $rs= $this->_CI->db->query($sql, $values)->result_array();

        return $rs; 
        

    }



    public function setEstado($hoja_id, $new_estado, $obs = ''){


        $sql =" UPDATE planillas.hojaasistencia SET hoa_estado = ?  WHERE hoa_id = ? ";

        $rs = $this->_CI->db->query($sql, array($new_estado, $hoja_id ));
  
        $nv = array();  
        $nv[] = $hoja_id;
        $nv[] = $new_estado;
        $nv[] = $obs;

        $sql =" INSERT INTO planillas.hojaasistencia_movimiento(hoa_id,hoae_id,homo_descripcion) VALUES( ?, ?, ? ) ";
        $this->_CI->db->query($sql,$nv);

        return ($rs) ? true : false; 
 
    }


    public function eliminar($hoja_id){

        $this->_CI->db->trans_begin();

        $sql_importados = "  SELECT  dia.hoa_id, SUM(hoaed_importado) as registros_importados 
                             FROM planillas.hojaasistencia_emp_dia dia 
                             WHERE dia.hoaed_estado = 1 AND dia.hoa_id = ? 
                             GROUP BY dia.hoa_id  ";

        list($rs) = $this->_CI->db->query($sql_importados, array( $hoja_id ))->result_array();   

        if( ($rs['registros_importados']*1) > 0 )
        {
            return false;
        }


        $sql = " UPDATE  planillas.hojaasistencia_emp_dia 
                 SET hoaed_estado = 0 
                 WHERE  hoae_id  IN ( SELECT hoae_id FROM planillas.hojaasistencia_emp WHERE hoa_id = ? )";

         $this->_CI->db->query($sql, array($hoja_id));         
 
         $sql = " UPDATE planillas.hojaasistencia_emp  SET hoae_estado = 0 WHERE hoa_id = ?  ";
         $this->_CI->db->query($sql, array($hoja_id));       

         $sql = " UPDATE  planillas.hojaasistencia  SET hoa_estado = 0 WHERE hoa_id = ?   ";
         $this->_CI->db->query($sql, array($hoja_id));       

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


    public function get_planilla_vinculada($hoja_id){


        $sql =" SELECT pla_id FROM planillas.planillas WHERE  hoja_asociada = ? AND pla_estado = 1 ";
        $reg = $this->_CI->db->query($sql,array($hoja_id))->result_array();

        return $reg[0]['pla_id'];
    }


    public function info_trabajador_dia($indiv_id, $dia )
    {

    /*    $sql = "  SELECT hoae.hoae_id, hoaed.* 
                  FROM planillas.hojaasistencia_emp_dia hoaed
                  LEFT JOIN planillas.hojaasistencia_emp hoae ON hoaed.hoae_id = hoae.hoae_id
                  LEFT JOIN planillas.hojaasistencia hoa ON hoae.hoa_id = hoa.hoa_id

                  WHERE hoaed.indiv_id = ? AND  hoaed.hoaed_fecha = ? AND  hoaed.hoaed_estado = 1 LIMIT 1 ";

    */

         $verif_hora1_e =  " (CASE WHEN hoae_hora1_e < hor.hor_hora1_e THEN
                                hor.hor_hora1_e
                              ELSE 
                                hoae_hora1_e
                              END )  ";


         $verif_hora1_s =  " (CASE WHEN hoae_hora1_s > hor.hor_hora1_s THEN
                                hor.hor_hora1_s
                           ELSE 
                                hoae_hora1_s
                           END )  ";


         $sql_calculo_horas_trabajadas = "COALESCE( EXTRACT( 'hour' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                         (COALESCE( EXTRACT( 'minutes' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";
        
        
        $horas_trabajadas_doble_horario = ' hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e ';
        $tardanzas_doble_horario        = ' hoae_hora1_e - hor_hora1_e  + hoae_hora2_e - hor_hora2_e  ';



        $sql ="  SELECT
                       (  substring( hoa.anio_eje from 3 for 2) || hoa.hoa_id ||  '-' ||hoa.plati_id )  as hoa_codigo , 
                       hoae.hoae_id,
                       dia.*, 
                       hoa.hoa_id, 
                       (m.sec_func || '-' || t.tarea_nro ) as meta_codigo,
                       m.nombre as meta_nombre,  
                       (usur_indiv.indiv_appaterno || ' ' || usur_indiv.indiv_apmaterno || ' ' || usur_indiv.indiv_nombres ) as responsable_hoja,
                       hoaed_fecreg as fecha_registro,
                       hatd_nombre as estado_nombre,
                       platica.platica_nombre,
                       ht.hatd_nombre,
                       ht.tipoescalafon_view,
                       htp.htp_registrar_marcacion_horas,
                       htp.*,
                       hor.*,

                       ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id ) as planilla_importado,

                      (CASE WHEN hor.hor_numero_horarios = 2 THEN  EXTRACT( 'hour' FROM (".$horas_trabajadas_doble_horario.") ) || '_' ||  EXTRACT( 'minutes' FROM (".$horas_trabajadas_doble_horario.")  ) ELSE  EXTRACT( 'hour' FROM (hoae_hora1_s - hoae_hora1_e ) ) || '_' ||  EXTRACT( 'minutes' FROM (hoae_hora1_s - hoae_hora1_e ) )  END ) as asistencia,
                       
                      (CASE WHEN hor.hor_numero_horarios = 2 THEN  EXTRACT( 'hour' FROM (".$tardanzas_doble_horario.") ) || '_' ||  EXTRACT( 'minutes' FROM (".$tardanzas_doble_horario.") ) ELSE  EXTRACT( 'hour' FROM ( hoae_hora1_e - hor_hora1_e  ) ) || '_' ||  EXTRACT( 'minutes' FROM ( hoae_hora1_e - hor_hora1_e  ) )  END ) as tardanzas,

                       ( CASE WHEN ( ".$sql_calculo_horas_trabajadas." ) is null OR ( hoae_hora1_e is null OR hoae_hora1_s is null ) THEN  
                           0  
                       ELSE  
                           (".$sql_calculo_horas_trabajadas." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                       END   )  as horas_contabilizadas,

                       indiv.*,

                       plaasis.plaasis_fecreg,

                       COALESCE(  permisos.minutos  , '0')  as permisos


                FROM  planillas.hojaasistencia_emp_dia dia 
                LEFT JOIN public.individuo indiv ON dia.indiv_id = indiv.indiv_id 
                LEFT JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                LEFT JOIN planillas.hojaasistencia_horarios hor On dia.hor_id = hor.hor_id 
                LEFT JOIN planillas.hojaasistencia_emp hoae ON dia.hoae_id = hoae.hoae_id
                LEFT JOIN planillas.hojaasistencia hoa ON hoae.hoa_id = hoa.hoa_id 
                LEFT JOIN planillas.planilla_asistencia_importacion plaasis ON dia.plaasis_id = plaasis.plaasis_id
                LEFT JOIN planillas.planillas pla ON dia.pla_id = pla.pla_id 
                LEFT JOIN planillas.planilla_tipo_categoria platica ON dia.platica_id = platica.platica_id 
                LEFT JOIN planillas.hojadiario_tipo ht ON dia.hatd_id = ht.hatd_id 
                LEFT JOIN planillas.hojadiario_tipo_plati htp ON persla.plati_id = htp.plati_id AND ht.hatd_id = htp.hatd_id AND htp_estado = 1
                LEFT JOIN sag.tarea t ON hoa.tarea_id = t.tarea_id 
                LEFT JOIN pip.meta m ON t.sec_func = m.sec_func AND m.ano_eje = t.ano_eje 
                LEFT JOIN public.usuario usur ON hoa.user_id = usur.usur_id 
                LEFT JOIN public.individuo usur_indiv ON usur.indiv_id = usur_indiv.indiv_id 
                LEFT JOIN ( 
                
                      SELECT pp.pers_id as indiv_id, pepe_fechadesde as fecha,  SUM(( EXTRACT( 'hour' FROM (pepe_horafin - pepe_horaini ) ) * 60 ) + EXTRACT( 'minutes' FROM (pepe_horafin - pepe_horaini ) ) ) as minutos
                      FROM rh.persona_permiso pp 
                      INNER JOIN rh.persona_permiso_movimiento ppm ON pp.pepe_id = ppm.pepe_id AND ppm.pepem_estado = 1 AND ppm.ppest_id >= 3 
                      WHERE pp.pepe_estado = 1 AND pp.pers_id = ? AND pp.pepe_fechadesde  = ? 
                      GROUP BY indiv_id, pepe_fechadesde 

                 ) as permisos  ON  dia.indiv_id = permisos.indiv_id AND dia.hoaed_fecha = permisos.fecha
 
                WHERE hoaed_estado = 1 AND dia.indiv_id = ? AND dia.hoaed_fecha =  ? 

                LIMIT 1  ";

        list($rs) =  $this->_CI->db->query($sql, array(  $indiv_id, $dia, $indiv_id, $dia ) )->result_array();

        return $rs;
    }



    public function info_trabajador_dia_modulo_asistencia($indiv_id, $dia )
    {

    /*    $sql = "  SELECT hoae.hoae_id, hoaed.* 
                  FROM planillas.hojaasistencia_emp_dia hoaed
                  LEFT JOIN planillas.hojaasistencia_emp hoae ON hoaed.hoae_id = hoae.hoae_id
                  LEFT JOIN planillas.hojaasistencia hoa ON hoae.hoa_id = hoa.hoa_id

                  WHERE hoaed.indiv_id = ? AND  hoaed.hoaed_fecha = ? AND  hoaed.hoaed_estado = 1 LIMIT 1 ";

    */

         $verif_hora1_e =  " (CASE WHEN hoae_hora1_e < hor.hor_hora1_e THEN
                                hor.hor_hora1_e
                              ELSE 
                                hoae_hora1_e
                              END )  ";


         $verif_hora1_s =  " (CASE WHEN hoae_hora1_s > hor.hor_hora1_s THEN
                                hor.hor_hora1_s
                           ELSE 
                                hoae_hora1_s
                           END )  ";


         $sql_calculo_horas_trabajadas = "COALESCE( EXTRACT( 'hour' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                         (COALESCE( EXTRACT( 'minutes' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";
        
        
        $horas_trabajadas_doble_horario = ' hoae_hora1_s - hoae_hora1_e + hoae_hora2_s - hoae_hora2_e ';
        $tardanzas_doble_horario        = ' hoae_hora1_e - hor_hora1_e  + hoae_hora2_e - hor_hora2_e  ';

        // Al importar desde el biometrico, cada dia registrado debe tener horario puesto 
        // Obtener Tardanzas a partir del horario 


        $sql ="  SELECT
                       (  substring( hoa.anio_eje from 3 for 2) || hoa.hoa_id ||  '-' ||hoa.plati_id )  as hoa_codigo , 
                       hoae.hoae_id,
                       dia.hatd_id as estado_dia_id,
                       dia.*, 
                       hoa.hoa_id, 
                       (m.sec_func || '-' || t.tarea_nro ) as meta_codigo,
                       m.nombre as meta_nombre,  
                       (usur_indiv.indiv_appaterno || ' ' || usur_indiv.indiv_apmaterno || ' ' || usur_indiv.indiv_nombres ) as responsable_hoja,
                       hoaed_fecreg as fecha_registro,
                       hatd_nombre as estado_nombre,
                       platica.platica_nombre,
                       ht.hatd_nombre,
                       ht.tipoescalafon_view,
                       htp.htp_registrar_marcacion_horas,
                       htp.*,
                       hor.*,

                       ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id ) as planilla_importado,

                      (CASE WHEN hor.hor_numero_horarios = 2 THEN  

                            EXTRACT( 'hour' FROM (".$horas_trabajadas_doble_horario.") ) || '_' ||  EXTRACT( 'minutes' FROM (".$horas_trabajadas_doble_horario.")  ) 

                       ELSE 
                            EXTRACT( 'hour' FROM (hoae_hora1_s - hoae_hora1_e ) ) || '_' ||  EXTRACT( 'minutes' FROM (hoae_hora1_s - hoae_hora1_e ) )  

                       END ) as asistencia,
                       
                      (CASE WHEN hor.hor_numero_horarios = 2 THEN  

                            EXTRACT( 'hour' FROM (".$tardanzas_doble_horario.") ) || '_' ||  EXTRACT( 'minutes' FROM (".$tardanzas_doble_horario.") ) 
                       ELSE 
                            EXTRACT( 'hour' FROM ( hoae_hora1_e - hor_ini_tardanza  ) ) || '_' ||  EXTRACT( 'minutes' FROM (hoae_hora1_e - hor_ini_tardanza) ) 

                       END ) as tardanzas,


                       ( CASE WHEN ( ".$sql_calculo_horas_trabajadas." ) is null OR ( hoae_hora1_e is null OR hoae_hora1_s is null ) THEN  
                           0  
                       ELSE  
                           (".$sql_calculo_horas_trabajadas." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                       END   )  as horas_contabilizadas,
                       
                       indiv.indiv_appaterno,
                       indiv.indiv_apmaterno,
                       indiv.indiv_nombres,
                       indiv.indiv_dni,
                       indiv.indiv_id,
                       indiv.indiv_key,
                       plaasis.plaasis_fecreg,
                       COALESCE(permisos.minutos,'0') as permisos,
                       COALESCE(descuentos_permisos.minutos_a_descontar,0) as minutos_a_descontar
  

                FROM  planillas.hojaasistencia_emp_dia dia 
                LEFT JOIN public.individuo indiv ON dia.indiv_id = indiv.indiv_id 
                LEFT JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                LEFT JOIN planillas.hojaasistencia_horarios hor On dia.hor_id = hor.hor_id 
                LEFT JOIN planillas.hojaasistencia_emp hoae ON dia.hoae_id = hoae.hoae_id
                LEFT JOIN planillas.hojaasistencia hoa ON hoae.hoa_id = hoa.hoa_id 
                LEFT JOIN planillas.planilla_asistencia_importacion plaasis ON dia.plaasis_id = plaasis.plaasis_id
                LEFT JOIN planillas.planillas pla ON dia.pla_id = pla.pla_id 
                LEFT JOIN planillas.planilla_tipo_categoria platica ON dia.platica_id = platica.platica_id 
                LEFT JOIN planillas.hojadiario_tipo ht ON dia.hatd_id = ht.hatd_id 
                LEFT JOIN planillas.hojadiario_tipo_plati htp ON persla.plati_id = htp.plati_id AND ht.hatd_id = htp.hatd_id AND htp_estado = 1
                LEFT JOIN sag.tarea t ON hoa.tarea_id = t.tarea_id 
                LEFT JOIN pip.meta m ON t.sec_func = m.sec_func AND m.ano_eje = t.ano_eje 
                LEFT JOIN public.usuario usur ON hoa.user_id = usur.usur_id 
                LEFT JOIN public.individuo usur_indiv ON usur.indiv_id = usur_indiv.indiv_id 
                LEFT JOIN ( 
                
                      SELECT pp.pers_id as indiv_id, pepe_fechadesde as fecha,  SUM(( EXTRACT( 'hour' FROM (pepe_horafin - pepe_horaini ) ) * 60 ) + EXTRACT( 'minutes' FROM (pepe_horafin - pepe_horaini ) ) ) as minutos
                      FROM rh.persona_permiso pp 
                      WHERE pp.pepe_estado = 1 AND pp.pers_id = ? AND pp.pepe_fechadesde  = ? 
                      GROUP BY indiv_id, pepe_fechadesde 

                 ) as permisos  ON  dia.indiv_id = permisos.indiv_id AND dia.hoaed_fecha = permisos.fecha
 
               LEFT JOIN (
                                                          
                     SELECT  
                            datos_permisos.indiv_id,
                            datos_permisos.dia,  
                            
                            SUM( COALESCE( (CASE WHEN permot_congoce = 0 THEN 

                                ( (Extract('hours' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( 'minutes' FROM (permiso_hora_regreso - permiso_hora_inicio) ) ) 

                             /* Cuando la licencia es con goce pero ha superado el limite de minutos, se descuenta el numero de minutos permitidos  */  
                             WHEN  ( (Extract('hours' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( 'minutes' FROM (permiso_hora_regreso - permiso_hora_inicio) ) ) > permot_limiteminutos THEN

                         ( ( (Extract('hours' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( 'minutes' FROM (permiso_hora_regreso - permiso_hora_inicio) ) )  - permot_limiteminutos )

                             /* CUando la licencia es con goce y no ha superado el limite de minutos */
                             ELSE 

                                ( (Extract('hours' FROM (permiso_hora_regreso - permiso_hora_inicio) )::numeric * 60)  +  EXTRACT( 'minutes' FROM (permiso_hora_regreso - permiso_hora_inicio) ) )  
                      
                             END), 0)  )as minutos_a_descontar

                     FROM (

                           SELECT pp.pers_id as indiv_id, 
                                  pepe_fechadesde as dia,
                                  mot.permot_id,
                                  mot.permot_congoce,
                                  mot.permot_horainicio,
                                  mot.permot_horafin,
                                  mot.permot_limiteminutos,
                                    (CASE WHEN hor_dia.hor_id is not null THEN 

                                     CASE WHEN pp.pepe_horaini < hor_dia.hor_hora1_e THEN 
                                         hor_dia.hor_hora1_e
                                     ELSE 
                                         pp.pepe_horaini  
                                     END 
                                                             
                                                        ELSE  
                                     CASE WHEN pp.pepe_horaini < hor_plati.hor_hora1_e THEN 
                                         hor_plati.hor_hora1_e
                                     ELSE 
                                         pp.pepe_horaini  
                                     END 

                                                        END) as permiso_hora_inicio,
                                                        
                                                        (CASE WHEN hor_dia.hor_id is not null THEN 

                                     CASE WHEN pp.pepe_horafin > hor_dia.hor_hora1_s THEN 
                                        hor_dia.hor_hora1_s
                                     ELSE 
                                         pp.pepe_horafin  
                                     END 
                                                             
                                                        ELSE  
                                     CASE WHEN pp.pepe_horafin > hor_plati.hor_hora1_s THEN 
                                         hor_plati.hor_hora1_s
                                     ELSE 
                                         pp.pepe_horafin  
                                     END 

                                  END) as permiso_hora_regreso
                                                     
                            FROM rh.persona_permiso pp 
                            INNER JOIN rh.permiso_motivo mot ON pp.permot_id = mot.permot_id  
                            INNER JOIN rh.persona_situlaboral persla ON pp.pers_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1
                            LEFT JOIN planillas.hojaasistencia_emp_dia dia ON pp.pers_id = dia.indiv_id  AND pp.pepe_fechadesde = dia.hoaed_fecha AND  dia.hoaed_estado = 1  
                            LEFT JOIN planillas.hojaasistencia_horarios hor_dia ON dia.hor_id = hor_dia.hor_id 
                            LEFT JOIN planillas.planillatipo_dia_horario ptdh ON ptdh.plati_id = persla.plati_id AND EXTRACT('dow' from pp.pepe_fechadesde) = ptdh.dia_id 
                            LEFT JOIN planillas.hojaasistencia_horarios hor_plati ON ptdh.hor_id = hor_plati.hor_id 
     
                            WHERE pepe_estado = 1  AND ( mot.permot_congoce = 0 OR mot.permot_limiteminutos > 0 )  AND pp.pepe_fechadesde  = '".$dia."'  

                      ) as datos_permisos
                      GROUP BY datos_permisos.indiv_id, datos_permisos.dia 
                      ORDER BY datos_permisos.indiv_id, datos_permisos.dia 

               ) as descuentos_permisos ON dia.indiv_id = descuentos_permisos.indiv_id AND dia.hoaed_fecha = descuentos_permisos.dia 



    
                WHERE hoaed_estado = 1 AND dia.indiv_id = ? AND dia.hoaed_fecha =  ? 

                LIMIT 1  ";

        list($rs) =  $this->_CI->db->query($sql, array(  $indiv_id, $dia, $indiv_id, $dia ) )->result_array();

        return $rs;
    }


    public function registrar_evento_dia( $params = array() )
    { 

        $this->_CI->load->library(array('App/trabajadordia'));

        $fecha_ini =  strtotime($params['fechaini']);
        $fecha_fin =  strtotime($params['fechafin']);
         
        $dia = date('j', $fecha_fin);
        $mes = date('n', $fecha_fin);
        $ano = date('Y', $fecha_fin);
        $mk_limite  =  mktime(0,0,0,$mes,$dia,$ano);  
        
        $dia = date('j', $fecha_ini);
        $mes = date('n', $fecha_ini);
        $ano = date('Y', $fecha_ini);
        $mk_inicio  =  mktime(0,0,0,$mes,$dia,$ano);  

        $mk_current = $mk_inicio;
 
        while($mk_current <= $mk_limite )
        { 
            
              $n_fecha    =  date("Y-m-d",mktime(0,0,0,$mes,$dia,$ano)); 
              $params['dia'] = $n_fecha;
 
              $configuracion_dia_por_defecto = $this->_CI->trabajadordia->getDefaultConfig(array('indiv_id' => $params['indiv_id'], 'fecha' => $n_fecha ));
              

              $sql = " SELECT * 
                       FROM planillas.hojaasistencia_emp_dia 
                       WHERE indiv_id = ?  AND hoaed_fecha = ? AND hoaed_estado = 1 LIMIT 1 ";

              list($rs) = $this->_CI->db->query($sql ,array( $params['indiv_id'], $params['dia'] ))->result_array();
 

              if( sizeof($rs) > 0 )
              {
                  if( $rs['registro_id'] == '0' || $rs['registro_id'] == '' )
                  {
                       $dia_id = $rs['hoaed_id'];

                       $sql = " UPDATE planillas.hojaasistencia_emp_dia 
                                SET registro_id = ?, tiporegistro_id = ?, hatd_id = ?, hoaed_laborable = ?, hor_id = ?    
                                WHERE hoaed_id = ? 
                              "; 

                       $this->_CI->db->query($sql, array( $params['registro'], $params['tipo'], $params['tipo'],  $configuracion_dia_por_defecto['platide_laborable'], $configuracion_dia_por_defecto ['hor_id'], $dia_id ));
                  }

              }
              else
              {
                  $sql = " INSERT INTO planillas.hojaasistencia_emp_dia(hoaed_fecha, indiv_id, registro_id, hatd_id, tiporegistro_id, hoaed_laborable, hor_id ) 
                           VALUES( ?, ?, ?, ?, ?, ?, ? ) ";
                  
                  $this->_CI->db->query($sql, array($params['dia'],$params['indiv_id'], $params['registro'],$params['tipo'], $params['tipo'], $configuracion_dia_por_defecto['platide_laborable'], $configuracion_dia_por_defecto ['hor_id'] ));

                  $id = $this->_CI->db->insert_id();

                  $kp = $id.'ASISDIA';

                  $sql = " UPDATE planillas.hojaasistencia_emp_dia SET  hoaed_key = md5(?) WHERE hoaed_id = ? ";

                  $this->_CI->db->query($sql, array($kp, $id));
 
              }

            $dia+=1;
            $mk_current  =  mktime(0,0,0,$mes,$dia,$ano);
      }


      return true;

    } 


    public function eliminar_evento_dia( $params = array() )
    {

        // Al eleminar el evento del día buscamos restaurar al estado y con la config x defecto

        $sql = " UPDATE planillas.hojaasistencia_emp_dia dia
                 SET registro_id = 0, 
                     tiporegistro_id = 0, 
                     hatd_id = ?,
                     hoaed_laborable = ide.ide_laborable,
                     hor_id = idh.hor_id 

                 FROM planillas.individuo_dia_horario idh, 
                      planillas.individuo_dia_estado ide    
                 WHERE  dia.indiv_id = ? AND dia.tiporegistro_id = ? AND dia.registro_id = ? AND dia.hoaed_importado = 0 AND dia.hoa_id != 0 AND dia.hoae_id != 0
                       AND dia.indiv_id = idh.indiv_id  AND EXTRACT('dow' FROM(hoaed_fecha)) = idh.dia_id 
                       AND dia.indiv_id = ide.indiv_id  AND EXTRACT('dow' FROM(hoaed_fecha)) = ide.dia_id     
               ";

       $rs = $this->_CI->db->query($sql, array(ASISDET_FALTA, $params['indiv_id'], $params['tiporegistro_id'], $params['registro_id'] ) );

       $sql = " DELETE FROM planillas.hojaasistencia_emp_dia dia 
                WHERE  dia.indiv_id = ? AND 
                       dia.tiporegistro_id = ? AND 
                       dia.registro_id = ? AND 
                       dia.hoaed_importado = 0 AND 
                       dia.hoa_id = 0 AND dia.hoae_id = 0 ";

       $rs = $this->_CI->db->query($sql, array($params['indiv_id'], $params['tiporegistro_id'], $params['registro_id'] ) );                

       return ($rs) ?  true : false;
    }


    public function eliminar_incidencia_asistencia( $params = array() )
    {
  
       $sql = "  UPDATE planillas.hojaasistencia_emp_dia dia
                 SET registro_id = 0, 
                     tiporegistro_id = 0, 
                     hatd_id = ? 

                 WHERE dia.hoaed_importado = 0 AND  
                       dia.tiporegistro_id = ? AND 
                       dia.registro_id = ? 
             ";             
        
        $rs = $this->_CI->db->query($sql, array(ASISDET_NOCONSIDERADO, $params['tiporegistro_id'], $params['registro_id'] ) );                

        return ($rs) ?  true : false;
    }

    public function set_minutos_tardanzas($hoja_id)
    { 

        $sql = "UPDATE  planillas.hojaasistencia_emp_dia hed 
                SET hoaed_tardanzas =  (CASE WHEN ( hed.hoae_numero_horarios = 2 AND EXTRACT( 'hour' FROM (hoae_hora1_e - hor_hora1_e   ) ) > 0 ) THEN  

                                              EXTRACT( 'hour' FROM (hoae_hora1_e - hor_hora1_e  + hoae_hora2_e - hor_hora2_e  ) ) * 60 + EXTRACT( 'minutes' FROM (  hoae_hora1_e - hor_hora1_e  + hoae_hora2_e - hor_hora2_e ) ) 
    
                                        WHEN ( hed.hoae_numero_horarios = 1 AND EXTRACT( 'hour' FROM (hoae_hora1_e - hor_hora1_e ) ) > 0 ) THEN

                                              EXTRACT( 'hour' FROM ( hoae_hora1_e - hor_hora1_e  ) ) * 60 +  EXTRACT( 'minutes' FROM ( hoae_hora1_e - hor_hora1_e  ) )    
                                        ELSE  
                                              0

                                        END ) 

                FROM planillas.hojaasistencia_emp he, 
                     planillas.individuo_horario  inho,
                     planillas.hojaasistencia_horarios hor 

                WHERE 
                       he.hoa_id =  ? AND 
                       ( he.indiv_id = inho.indiv_id AND inho.indho_estado = 1 )
                       AND  ( inho.hor_id = hor.hor_id  ) 


                 ";


          $this->_CI->db->query($sql , array($hoja_id) );
              
    } 



    public function actualizar_registro_escalafon($params = array())
    {


          if($params['tipo'] )
          {
            
          } 


    }

    public function get_plati_config($plati_id = '0')
    {

        $sql = "SELECT * FROM planillas.hojaasistencia_plati_config 
                WHERE hoplac_estado = 1 AND plati_id = ? 
                LIMIT 1 ";

        $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();
        
        return $rs[0];
    }

    public function get_info_hojas($hojas = array())
    {
 
        $in_hojas = implode(',', $hojas);

        $sql =" SELECT  hojas.*, 
                       (  substring( hojas.anio_eje from 3 for 2) || hojas.hoa_id ||  '-' ||hojas.plati_id )  as hoa_codigo , 
                        plati.plati_nombre as tipo_planilla, 
                        est.hoae_nombre as estado, 
                        hojas.hoa_estado as estado_id, 
						CASE WHEN hoa_anio::integer < 2021 
                            THEN ( tarea.sec_func || ' - ' || tarea.tarea_nro || ' ' || tarea.tarea_nombre )
                            ELSE ( tarea.sec_func || ' - ' || tarea.tarea_nombre )	
						END as proyecto

               FROM planillas.hojaasistencia hojas 
               LEFT JOIN planillas.planilla_tipo plati ON hojas.plati_id =  plati.plati_id 
               LEFT JOIN planillas.hoa_estados est ON hojas.hoa_estado = est.hoae_id
               LEFT JOIN sag.tarea ON hojas.tarea_id = tarea.tarea_id 
               WHERE hojas.hoa_id IN(".$in_hojas.")  
               ORDER BY hoa_fechaini desc, hojas.tarea_id
            ";


         return $this->_CI->db->query($sql, array())->result_array();
      
    }

    public function get_rango_de_fechas_hojas($hojas = array())
    { 
      $in_hojas = implode(',', $hojas);

      $sql ="  SELECT MIN(hoa_fechaini) as fecha_ini, MAX(hoa_fechafin) as fecha_fin 
              FROM planillas.hojaasistencia hojas   
               WHERE hojas.hoa_id IN (".$in_hojas.")"; 
                


       list($rs) = $this->_CI->db->query($sql, array())->result_array();
      
       return $rs;
    }   


    public function get_asistencias_importar($params = array())
    {

         // Tabla resumen importacion 

         /*
            @Nota : Parte de esta consulta SQL se repite en planillaasistencia.importar
         */

          $this->_CI->load->library(array('App/hojaasistencia', 
                                          'App/planillaempleadovariable', 
                                          'App/planillaempleado', 
                                          'App/concepto_metodos'));
  
          $in_hojas = implode(',', $params['hojas']);
 
          $plati_id  = $params['plati_id'];

          $fecha_ini = $params['fecha_inicio'];
          $fecha_fin = $params['fecha_fin'];
          
          $sql_tipos = " SELECT hatd_label FROM planillas.hojadiario_tipo ht
                         INNER JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = ?
                         WHERE hatd_estado = 1 AND  htp.vari_id != 0  
                         ORDER BY ht.hatd_orden asc ";

          $rs = $this->_CI->db->query($sql_tipos, array($plati_id) )->result_array();

          $sql_tipos_header = '';
          
          foreach($rs as $reg)
          {
              $sql_tipos_header.= ', "dato_'.$reg['hatd_label'].'" numeric(10,2)';
          }


          $verif_hora1_e =  " (CASE WHEN hoae_hora1_e < hor.hor_hora1_e THEN
                                 hor.hor_hora1_e
                               ELSE 
                                 hoae_hora1_e
                               END )  ";


          $verif_hora1_s =  " (CASE WHEN hoae_hora1_s > hor.hor_hora1_s THEN
                                 hor.hor_hora1_s
                            ELSE 
                                 hoae_hora1_s
                            END )  ";


          $sql_calculo_horas_trabajadas = "COALESCE( EXTRACT( ''hour'' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) + 
                                          (COALESCE( EXTRACT( ''minutes'' FROM (".$verif_hora1_s." - ".$verif_hora1_e." ) ) , 0) / 60 ) ";


          $sql_table = " SELECT * FROM crosstab( '    

                                   SELECT (hoae.indiv_id || ''_'' || hoae.platica_id ) as indice, hoae.indiv_id, hoae.platica_id, hoae.hoagru_id, ht.hatd_label, 

                                          SUM( CASE WHEN htp.htp_importar_horas = 1 THEN 

                                                      ( CASE WHEN ( ".$sql_calculo_horas_trabajadas." ) is null  OR ( hoae_hora1_e is null OR hoae_hora1_s is null ) THEN  
                                                          0  
                                                      ELSE  
                                                          (".$sql_calculo_horas_trabajadas." - (CASE WHEN  hoae_hora1_s >= hor_descontar_despuesde AND hoae_hora1_s is not NULL THEN hor_descontar_horas ELSE 0 END ) )
                                                      END   )  
                                                
                                                WHEN htp.htp_importar_horas = 2 THEN 
                                                
                                                         (  COALESCE( EXTRACT( ''hour'' FROM ( hor.hor_hora1_s - hor.hor_hora1_e ) ) , 0) + 
                                                            (COALESCE( EXTRACT( ''minutes'' FROM ( hor.hor_hora1_s - hor.hor_hora1_e ) ) , 0) / 60 )  - hor_descontar_horas
                                                          )
                                                
                                                WHEN htp.htp_importar_horas = 3 THEN 

                                                          (  COALESCE( EXTRACT( ''hour'' FROM ( hor.hor_hora1_s2 - hor.hor_hora1_e2 ) ) , 0) + 
                                                             (COALESCE( EXTRACT( ''minutes'' FROM ( hor.hor_hora1_s2 - hor.hor_hora1_e2 ) ) , 0) / 60 )  - hor_descontar_horas
                                                           )

                                                ELSE
                                                       1 

                                                END ) as total
              
                                    FROM planillas.hojaasistencia hoa  
                                    INNER JOIN  planillas.hojaasistencia_emp hoae ON hoa.hoa_id = hoae.hoa_id AND hoae.hoae_estado = 1 
                                    INNER JOIN  planillas.hojaasistencia_emp_dia hoaed ON hoae.hoae_id = hoaed.hoae_id AND hoaed.hoaed_estado = 1 AND hoaed_laborable = 1 AND hoaed_importado = 0
                                    INNER JOIN  planillas.hojaasistencia_horarios hor ON hoaed.hor_id = hor.hor_id
                                    INNER JOIN  planillas.hojadiario_tipo_plati htp ON hoaed.hatd_id = htp.hatd_id AND htp.plati_id = hoa.plati_id AND htp_estado = 1 AND htp.vari_id != 0
                                    INNER JOIN  planillas.hojadiario_tipo ht ON htp.hatd_id = ht.hatd_id   
                                    WHERE hoa.hoa_id IN(".$in_hojas.")  AND  ( hoaed.hoaed_fecha between ''".$fecha_ini."'' AND ''".$fecha_fin."'' ) 
                                    GROUP BY hoae.indiv_id, hoae.platica_id, hoae.hoagru_id, ht.hatd_label, ht.hatd_orden    
                                    ORDER BY hoae.indiv_id, hoae.platica_id, hoae.hoagru_id, ht.hatd_label, ht.hatd_orden asc ', 

                                    ' SELECT hatd_label FROM planillas.hojadiario_tipo ht
                                      INNER JOIN planillas.hojadiario_tipo_plati htp ON ht.hatd_id = htp.hatd_id AND  htp.htp_estado = 1 AND htp.plati_id = $plati_id 
                                      WHERE hatd_estado = 1 AND  htp.vari_id != 0  ORDER BY ht.hatd_orden asc  ' )
                                    AS( indice text,
                                        indiv_id int,
                                        platica_id int,
                                        hoagru_id int
                                        ".$sql_tipos_header."
                                      )

                               "; 

          $sql ="  SELECT indiv.indiv_appaterno, indiv_apmaterno, indiv_nombres, indiv_dni, platica_nombre, indiv.indiv_key, 
                         tabla.* 
                   FROM (".$sql_table.") as tabla
                   LEFT JOIN public.individuo indiv ON tabla.indiv_id = indiv.indiv_id 
                   LEFT JOIN planillas.planilla_tipo_categoria platica On tabla.platica_id = platica.platica_id   
                   LEFT JOIN planillas.empleado_presupuestal empre ON tabla.indiv_id = empre.indiv_id  AND empre.empre_estado = 1 AND empre.ano_eje = ?

                   WHERE indiv.indiv_estado = 1 
               ";

               $values = array($params['anio']);

               
              if($params['dni'] != '')
              {
                 $sql.=" AND indiv.indiv_dni = ? ";
                 $values[] =  $params['dni'];
              }
              else
              { 
                  if($params['categoria'] != '' && $params['categoria'] != '0')
                  {
                     $sql.=" AND tabla.platica_id = ? ";
                     $values[] =  $params['categoria'];
                  }

                  if($params['grupo'] != ''  && $params['grupo'] != '0' )
                  {
                     $sql.=" AND tabla.hoagru_id = ? ";
                     $values[] =  $params['grupo'];
                  } 

                  if($params['tarea_trabajador'] != '' && $params['tarea_trabajador'] != '0')
                  {
                     $sql.=" AND empre.tarea_id = ? ";
                     $values[] =  $params['tarea_trabajador'];
                  } 

                  if($params['fuente_trabajador'] != '' && $params['fuente_trabajador'] != '0' )
                  {
                     $sql.=" AND empre.fuente_id = ? AND empre.tipo_recurso = ? ";
                     $values[] =  $params['fuente_trabajador'];
                     $values[] =  $params['tr_trabajador'];
                  }
              }

          $sql.="      

                   ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres, platica_nombre 
                ";

         $rs =  $this->_CI->db->query($sql, $values )->result_array();


         return $rs; 
 

    } 


    public function get_trabajadores( $params = array())
    {

        $sql = " SELECT indiv.*,
                        data.*, 
                        plati.plati_nombre,
                        platica.platica_nombre 
                 FROM ( 

                     SELECT  hoae.indiv_id , hoae.platica_id 
                     FROM planillas.hojaasistencia hoa 
                     INNER JOIN planillas.hojaasistencia_emp hoae ON hoa.hoa_id = hoae.hoa_id AND hoae.hoae_estado = 1
                     INNER JOIN planillas.hojaasistencia_emp_dia dia ON hoae.hoae_id = dia.hoae_id AND dia.hatd_id != 0 AND dia.hoaed_estado = 1 
                     WHERE hoa.hoa_id = ? 
                     GROUP BY hoae.indiv_id, hoae.platica_id 
                     ORDER BY hoae.indiv_id, hoae.platica_id 

                 ) as data 
                 INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                 INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_ultimo = 1 AND persla_estado = 1 
                 INNER JOIN planillas.planilla_tipo plati On persla.plati_id = plati.plati_id 
                 LEFT JOIN planillas.planilla_tipo_categoria platica ON data.platica_id = platica.platica_id 

                 ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, platica_nombre
               "; 
              
        if($params['return_sql'] === true )
        {
            return $sql;
        }
        else
        {
           return  $this->_CI->db->query($sql, array($params['hoja']) )->result_array();
        }


    } 

    public function getDiasSinHoraSalida( $params = array() ){
  
         
        $sql = " SELECT dia.indiv_id, dia.hoaed_fecha, 
                       ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres )  as trabajador_nombre,
                        indiv.indiv_dni,
                        plati.plati_nombre,
                        dia.hoae_hora1_e, 
                        dia.hoae_hora1_s, 
                        pp.*,
                        permot.permot_nombre   

                 FROM planillas.hojaasistencia_emp_dia dia 
                 INNER JOIN rh.persona_situlaboral persla ON dia.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id IN (".$params['plati_id'].")
                 INNER JOIN public.individuo indiv ON dia.indiv_id = indiv.indiv_id 
                 INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                 LEFT JOIN rh.persona_permiso pp ON dia.indiv_id = pp.pers_id AND dia.hoaed_fecha = pp.pepe_fechadesde AND pp.pepe_estado = 1  
                 LEFT JOIN rh.permiso_motivo permot ON pp.permot_id = permot.permot_id 
                 WHERE hoaed_estado = 1 AND dia.hatd_id = ? AND dia.hoae_hora1_s is null AND hoaed_fecha between ? AND ?                  
                 
                 ORDER BY hoaed_fecha, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
              ";

        $rs = $this->_CI->db->query($sql, array(ASISDET_ASISTENCIA, $params['fecha_inicio'], $params['fecha_fin']))->result_array();

        return $rs;
    }

    public function actualizar_hora_salida_dia($params = array()){

        $sql=" UPDATE planillas.hojaasistencia_emp_dia SET hoae_hora1_s = ? WHERE indiv_id = ? AND hoaed_fecha = ? ";

        $ok = $this->_CI->db->query($sql, array($params['hora_salida'], $params['indiv_id'], $params['dia']));

        return ($ok ? true : false);
    }


    public function actualizar_falta_dia($params = array()){

        $sql=" UPDATE planillas.hojaasistencia_emp_dia 
               SET hatd_id = ? 
               WHERE indiv_id = ? AND hoaed_fecha = ? ";

        $ok = $this->_CI->db->query($sql, array( ASISDET_FALTA, $params['indiv_id'], $params['dia']));

        return ($ok ? true : false);
    }


}