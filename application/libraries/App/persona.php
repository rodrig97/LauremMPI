<?php

class persona extends Table{
     
    protected $_FIELDS=array(   
                                    'id'          => 'indiv_id',
                                    'code'        => 'indiv_key',
                                    'name'        => 'indiv_nombres',
                                    'descripcion' => '',
                                    'state'       => 'indiv_estado'
                            );
    
    protected $_SCHEMA     = 'public';
    protected $_TABLE      = 'individuo';
    protected $_PREF_TABLE = 'PERSMPI'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }


     public function get_by_dni($dni)
     {
          $query = array($dni); 
          
          $sql   = " SELECT * FROM  public.individuo indiv WHERE indiv_dni = ? LIMIT 1";
          $rs    = $this->_CI->db->query($sql, $query)->result_array();

          return $rs[0];
     }


     public function get_list($params)
     {
      
        $query = array();

        $sql = " SELECT indiv.*,
                       (  indiv.indiv_appaterno ||' '|| indiv.indiv_apmaterno || ' ' ||  indiv.indiv_nombres ) as persona_nombre 
                       FROM public.individuo indiv 
                       INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla_estado = 1 AND persla_ultimo = 1                       
                       WHERE indiv_estado = 1 ";


        if($params['nombre'] != '' )
        {
            $sql.=" AND (  indiv.indiv_appaterno ||' '|| indiv.indiv_apmaterno || ' ' ||  indiv.indiv_nombres ) like ? ";
            $query[] =  trim($params['nombre']).'%';
        }               

        if( trim($params['plati_id']) != '' && trim($params['plati_id']) != '0'){

            $sql.=" AND persla.plati_id IN (".trim($params['plati_id']).") ";
        }

        if( $params['orden'] != '' ) // && $params['nombre'] != '' 
        { 
            $sql.=" ORDER BY ? ";
            $query[] = $params['orden'];
        } else {
            $sql.= " ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres  ";
        }

        if($params['limit'] != '' ) // && $params['nombre'] != '' 
        { 
         
           $sql.=' LIMIT  ? '; 
           $query[] = $params['limit'];
       
         }
 
         return $this->_CI->db->query($sql, $query)->result_array();
     }
    
    
    public function get_some_info($param, $search_by = 1)
    {
         
        if($search_by == 1 )
        {
           $param_q = ' ind.indiv_dni';
        }
        else if($search_by=='id')
        {
           $param_q = ' ind.indiv_id';
        } 
        
        
        $sql = "SELECT  ind.*,  
                        ( ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as nombre_completo, 
                        COALESCE(date_part('YEAR', age(now(), ind.indiv_fechanac) ),0) as edad,  
                        plati.plati_nombre as tipo_planilla,
                        situ.*,

                        COALESCE(pensi.peaf_id,0)  as peaf_id, 
                        COALESCE(cuenta.pecd_id,0) as pecd_id,   
                        0 as emocu_id,
                        COALESCE(situ.ocu_id,0)  as ocu_id
                        

                FROM public.individuo ind
                LEFT JOIN rh.persona_situlaboral situ ON ind.indiv_id = situ.pers_id AND situ.persla_estado = 1 AND situ.persla_ultimo = 1
                LEFT JOIN planillas.planilla_tipo plati ON situ.plati_id = plati.plati_id
                LEFT JOIN rh.persona_pension pensi ON pensi.pers_id = ind.indiv_id AND pensi.peaf_estado = 1 
                LEFT JOIN rh.persona_cuenta_deposito cuenta  ON cuenta.pers_id = ind.indiv_id AND cuenta.pecd_estado = 1  
                
                WHERE ".$param_q." = ?

                ORDER BY ind.indiv_id 
                LIMIT 1 ";
       
        $rs= $this->_CI->db->query($sql,array($param))->result_array();
        return $rs[0];
        
        
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
        
        $rs= $this->_CI->db->query($sql,array($pers_id))->result_array();
        return $rs[0];
        
    } 
     


    public function get_situacionlaboral( $id = 0, $tipo)
    {

        $sql = " SELECT indiv_dni, persla.* FROM rh.persona_situlaboral persla 
                 INNER JOIN public.individuo indiv ON persla.pers_id = indiv.indiv_id
                 WHERE ";

                 if($tipo == 'dni')
                 {
                    $sql.=" indiv.indiv_dni = ? ";
                 }
                 else
                 {
                    $sql.=" persla.pers_id = ? ";
                 }

       $sql.=" AND persla.persla_ultimo = 1 AND persla.persla_estado = 1 LIMIT 1 ";

      list($rs) = $this->_CI->db->query($sql, array($id))->result_array();

      return $rs;

    } 

   
   public function get_trabajadores($params = array(), $count =  false)
   {
        
        $vars_ejec = array();
       
        $sql = ' SELECT   ';

        if($count)
        {

          $sql.=' count(*) as total ';
        }
        else
        {

          $sql.='    pers.*, 
                     COALESCE(date_part(\'YEAR\', age(now(), pers.indiv_fechanac) ),0) as edad, 
                     historial.persla_vigente as vigente,
                     cargos.cargo_nombre as cargo_nombre, 
                     plati.plati_tipoempleado as tipo_trabajador, 
                     depes.area_nombre as depe_nombre, 
                     historial.persla_id,
                     historial.persla_key,
                     historial.meta_nombre,
                     historial.plati_id,
                     historial.cargo_id,

                     ( CASE WHEN persla_fechafin is not null THEN 

                          persla_fechafin::text
                     ELSE

                           \'\'
                     END )  as termino_de_contrato  

                     , up.ultimo_pago

               ';
 

              if($params['fecha_limite'] != '')
              {
   
                  $sql.= ' 
                           ,( CASE WHEN persla_fechafin is not null  AND  persla_fechafin <  \''.$params['fecha_limite'].'\'  THEN 
                               
                                1
                                                       
                           ELSE

                                0

                           END )  as contrato_vencido  ';

               }
        }

        $sql.='             
                 FROM public.individuo pers 
                 LEFT JOIN rh.persona_situlaboral historial  ON pers.indiv_id = historial.pers_id AND historial.persla_ultimo = 1 
                 LEFT JOIN planillas.planilla_tipo plati ON  historial.plati_id = plati.plati_id
                 INNER JOIN rh.persona_pension pepe ON pers.indiv_id = pepe.pers_id AND pepe.peaf_estado = 1
                 LEFT JOIN rh.persona_cuenta_deposito pecd ON pers.indiv_id = pecd.pers_id AND pecd.pecd_estado = 1
                 LEFT JOIN planillas.empleado_presupuestal empre ON empre.indiv_id = pers.indiv_id  AND empre.empre_estado = 1 AND empre.ano_eje = ?
                 LEFT JOIN public.cargo cargos ON cargos.cargo_id = historial.cargo_id  
                 LEFT JOIN public.area depes ON historial.depe_id = depes.area_id

                 LEFT JOIN (
                    SELECT pe.indiv_id, max(pla_fecreg) as ultimo_pago 
                    FROM planillas.planilla_empleados pe
                        INNER JOIN planillas.planillas p ON pe.pla_id = p.pla_id
                        INNER JOIN public.individuo i on pe.indiv_id = i.indiv_id
                    WHERE p.pla_tipo = \'r\' AND p.pla_estado = 1
                    GROUP BY 1
                ) AS up ON up.indiv_id = pers.indiv_id

              WHERE ( persla_estado = 1 OR persla_estado is null ) 
               ';


          
         $vars_ejec[] = $params['anio_eje'];
            
     
        
        if($params['dni'] != '' )
        {
             
            $sql.=" AND pers.indiv_dni = ? ";
            $vars_ejec[] = $params['dni'];
        
            if($params['vigente_estricto']===true)
            {
                if($params['vigente'] != '2' )
                { 

                      if($params['vigente'] == '1' )
                      {

                          $sql.= " AND ( historial.persla_vigente = 1 OR historial.persla_vigente = 2 ) ";

                      }
                      else
                      { 

                          $sql.= " AND  ( historial.persla_vigente = 0 OR historial.persla_vigente = 2 ) ";
                           
                      }
                }
            }

        }
        else{
        
        
                if($params['depe_id'] != '' && $params['depe_id'] != '0'){
                    $sql.=" AND historial.depe_id = ? ";
                    $vars_ejec[] = $params['depe_id'];
                }

                 if($params['situ_lab'] != '' && $params['situ_lab'] != '0'){
                    $sql.=" AND historial.plati_id = ? ";
                    $vars_ejec[] = $params['situ_lab'];
                }

                if($params['cargo'] != '' && $params['cargo'] != '0'){
                    $sql.=" AND historial.cargo_id = ? ";
                    $vars_ejec[] = $params['cargo'];
                }
                
                if($params['materno'] != '' ){
                    $sql.=" AND pers.indiv_apmaterno = ? ";
                    $vars_ejec[] = $params['materno'];
                }
                
                if($params['paterno'] != '' ){
                    $sql.=" AND pers.indiv_appaterno = ? ";
                    $vars_ejec[] = $params['paterno'];
                }
                

                if($params['paterno'] != '' ){
                    $sql.=" AND pers.indiv_appaterno = ? ";
                    $vars_ejec[] = $params['paterno'];
                }
                

                if($params['lugar_de_trabajo'] != '' ){
                   $sql.=" AND pers.indiv_lugar_de_trabajo = ? ";
                    $vars_ejec[] = $params['lugar_de_trabajo'];
                }

                if($params['vigente'] != '2' )
                { 

                      if($params['vigente'] == '1' )
                      {

                          $sql.= " AND ( historial.persla_vigente = 1 OR historial.persla_vigente = 2 ) ";

                      }
                      else
                      { 

                          $sql.= " AND  ( historial.persla_vigente = 0 OR historial.persla_vigente = 2 ) ";
                           
                      }
                }


                if($params['grupo'] != '0' && $params['grupo'] != '')
                {

                    $sql.=" AND historial.gremp_id = ? ";
                    $vars_ejec[] = $params['grupo'];
                }

 
                if($params['tienecuenta'] != '0')
                {

                    if($params['tienecuenta'] == '1')
                    {
                        $sql.=" AND pecd.pecd_id is not NULL ";
                    
                        if($params['banco'] != '0')
                        {
                            $sql.=" AND pecd.ebanco_id = ? ";
                            $vars_ejec[] = $params['banco'];
                        }

                    }  
                    else if($params['tienecuenta'] == '2')
                    {
                         $sql.=" AND pecd.pecd_id is NULL ";   
                    }
                     
                }


                if($params['tipopension'] != '0')
                {

                    if($params['tipopension'] ==  PENSION_AFP )
                    {
                        $sql.=" AND pepe.pentip_id = ? ";
                        $vars_ejec[] = PENSION_AFP;
                    
                        if($params['afp'] != '0')
                        {
                            $sql.=" AND pepe.afp_id = ? ";
                            $vars_ejec[] = $params['afp'];
                        }

                        if($params['modoafp'] != '0')
                        {
                            $sql.=" AND pepe.afm_id = ? ";
                            $vars_ejec[] = $params['modoafp'];
                        }

                    }  
                    else if($params['tipopension'] == PENSION_SNP )
                    {
                         $sql.=" AND pepe.pentip_id = ? ";
                         $vars_ejec[] = PENSION_SNP;   
                    }
                     
                }


                if($params['tarea'] != '0')
                {

                   if($params['tarea']=='no')
                   {
                      $sql.=" AND (empre.tarea_id = 0 OR empre.tarea_id is null) ";  
                   }
                   else if($params['tarea']=='si')
                   {
                      $sql.=" AND ( empre.tarea_id is not null AND  empre.tarea_id != 0  ) ";  
                   }
                   else
                   {
                    
                       $sql.=" AND empre.tarea_id = ? ";
                       $vars_ejec[] = $params['tarea'];  
                         
                   }

                }


                if($params['fuente'] != '')
                {

                   if($params['fuente'] == 'no')
                   {
                       $sql.=" AND (empre.fuente_id = '' OR empre.fuente_id is null ) ";
                   }
                   else if($params['fuente'] == 'si')
                   {
                       $sql.=" AND (empre.fuente_id != '' AND empre.fuente_id is not null ) ";
                   }
                   else
                   {
                       $sql.=" AND empre.fuente_id = ? ";
                       $vars_ejec[] = $params['fuente'];  

                   }
                }

                
                if($params['tiporecurso'] != '')
                {
                   if($params['tiporecurso'] == 'no')
                   {
                       $sql.=" AND (empre.tipo_recurso = '' OR empre.tipo_recurso is null ) ";
                   }
                   else if($params['tiporecurso'] == 'si')
                   {
                       $sql.=" AND (empre.tipo_recurso != '' AND empre.tipo_recurso is not null ) ";
                   }
                   else
                   { 
                       $sql.=" AND empre.tipo_recurso = ? ";
                       $vars_ejec[] = $params['tiporecurso'];  
                   }     
                }


        }
        
        
        if($count == FALSE)
        {
        
          $sql.= "  ORDER BY indiv_appaterno, indiv_apmaterno,indiv_nombres ";  

          if($params['limit']>0) $sql.= "  LIMIT ".$params['limit'];
            
          if($params['offset'] > 0 && $params['limit'] > 0 ){
              
             $sql.= " OFFSET ".$params['offset']; 
             
          }
        
        }
 
        $rs = $this->_CI->db->query($sql,$vars_ejec)->result_array();
       
        return $rs;
       
   }
   
   
   public function get_count_trabajadores($params)
   {  
        $count = true; 

        unset($params['limit']);
        unset($params['offset']);

        $rs = $this->get_trabajadores($params, $count);  
        return $rs[0]['total'];
       
   }
   

   
   public function add_pension($indiv_id, $data )
   {

       $this->_CI->load->library(array('App/concepto', 'App/empleadoconcepto'));
       
       $this->_CI->db->trans_begin();
      
      /*
         QUITANDO LOS CONCEPTOS DE LA PLANILLA
      */
       $this->remove_pension($indiv_id);
         
       $data['pers_id'] =  $indiv_id;
       
        $r   = $this->_CI->db->insert( 'rh.persona_pension',$data); 
        $id  = $this->_CI->db->insert_id();
        $md5 = md5('PERSPENSION'.$id);
        $sql = " UPDATE rh.persona_pension SET peaf_key = ? WHERE peaf_id = ? ";
        $this->_CI->db->query($sql, array($md5,$id));

        /* ACTUALIZAMOS EL PEAF EN LAS PLANILLAS EN ESTADO ELABORAR */ 
        $sql = "   UPDATE planillas.planilla_empleados
                   SET peaf_id = ? 
                   WHERE indiv_id = ? AND 
                         plaemp_id IN ( 

                               SELECT plaemp.plaemp_id 
                               FROM planillas.planillas pla 
                               INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id AND plamo_estado = 1 AND plaes_id = ".ESTADOPLANILLA_ELABORADA." 
                               INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id AND plaemp.plaemp_estado = 1 AND plaemp.indiv_id = ?
                               WHERE pla.pla_estado = 1
                           )

               ";

        $this->_CI->db->query($sql, array($id, $indiv_id, $indiv_id ));


       if(CONFIG_SUPPORT_AFP_CONCEPTOS == 1 && $data['peaf_jubilado'] == '0')
       { 

           if( $data['pentip_id'] == PENSION_AFP )
           {
              $grupo     = array( GRUPOVC_AFP, GRUPOVC_AFP_APORTE ); 
           }
           else
           {
              $grupo     = GRUPOVC_ONP; 
           }
   
           $this->add_conceptos_pension_planillas($indiv_id, $grupo);
  
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
   

   public function remove_pension($indiv_id, $delete = false)
   {
   
        if(CONFIG_SUPPORT_AFP_CONCEPTOS == 1 )
        { 
            $this->remove_conceptos_pension_planillas($indiv_id);
        } 

        if($delete)
        {
           
           $sql = " DELETE FROM rh.persona_pension WHERE pers_id = ? AND peaf_estado = 1";
           $this->_CI->db->query($sql, array($indiv_id));

        }   
        else
        {

            $this->_CI->db->where( 'pers_id', $indiv_id)
                          ->update( 'rh.persona_pension', array('peaf_estado' => '0')); 
        }

 
       
   }


   public function remove_conceptos_pension_planillas($indiv_id)
   {
 
      $this->_CI->load->library(array('App/empleadoconcepto','App/planillaempleadoconcepto'));
   
      $sql = " SELECT conc_id FROM planillas.conceptos conc  
               WHERE conc.conc_estado = 1 AND  gvc_id in (?,?,?)  AND conc.conc_afecto = 1 ";
      
      $conceptos_pension_del = $this->_CI->db->query($sql, array(GRUPOVC_AFP, GRUPOVC_AFP_APORTE, GRUPOVC_ONP))->result_array();
     
      $sql = " SELECT plaemp_id 
               FROM planillas.planilla_empleados plaemp
               INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1
               INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id AND  mov.plamo_estado = 1 AND  mov.plaes_id = ".ESTADOPLANILLA_ELABORADA."
               WHERE plaemp.indiv_id = ?   ";


      $conceptos_en_planillas = $this->_CI->db->query($sql, array($indiv_id))->result_array();          
 

      foreach($conceptos_pension_del as $concepto)
      {  
   
          $this->_CI->empleadoconcepto->desvincular_concepto($indiv_id, $concepto['conc_id'], true); 

          foreach($conceptos_en_planillas as $reg)
          {
             $this->_CI->planillaempleadoconcepto->desvincular( $concepto['conc_id'], $reg['plaemp_id'] );
          }

      }      
       
   }

   public function add_conceptos_pension_planillas( $indiv_id, $grupo)
   {
 
           $this->_CI->load->library(array('App/concepto', 'App/variable', 'Catalogos/afp'));
 
           $sql = " SELECT plaemp_id, pla.plati_id,  plaemp.tarea_id, plaemp.fuente_id, plaemp.tipo_recurso, 
                           pla.pla_afectacion_presu, pla.clasificador_id, COALESCE(date_part('YEAR', age(now(), indiv.indiv_fechanac) ),0) as edad  
                    FROM planillas.planilla_empleados plaemp
                    LEFT JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id 
                    LEFT JOIN rh.persona_situlaboral persla ON plaemp.persla_id = persla.persla_id 
                    INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1
                    INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id  AND  mov.plamo_estado = 1 AND mov.plaes_id = ".ESTADOPLANILLA_ELABORADA."
                    WHERE plaemp.indiv_id = ?   ";


           $planilla_empleados = $this->_CI->db->query($sql, array($indiv_id))->result_array(); 

          
          list($tipo_pension, $data_pension)  =  $this->_CI->persona->get_tipo_pension($indiv_id); // GET INFO PENSION


           foreach($planilla_empleados as $reg)
           {  

             $id_clasificador = '';

             if( $reg['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA )
             {
                $id_clasificador = $reg['clasificador_id'];
             }
             else if(  $reg['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE )
             {
                $id_clasificador = $concepto['id_clasificador'];
             }
             

             $conceptos_pension = $this->_CI->concepto->get_list(array('tipoplanilla' => $reg['plati_id'],
                                                                       'grupo'        => $grupo    ));

             foreach($conceptos_pension as $concepto)
             {  
                // $this->_CI->empleadoconcepto->registrar($indiv_id, $concepto['conc_id']); 


                $this->_CI->planillaempleadoconcepto->registrar( array('conc_id' => $concepto['conc_id'],
                                                                        'plaemp_id'  => $reg['plaemp_id'],
                                                                        'tarea_id' => $reg['tarea_id'],
                                                                        'fuente_id' => trim($reg['fuente_id']),
                                                                        'tipo_recurso' => trim($reg['tipo_recurso']),
                                                                        'clasificador_id' =>  $id_clasificador,
                                                                        'plaec_displayprint' => $concepto['conc_displayprint']
                
                                                                         ), 
                                                                         $indiv_id, PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR );


                $edad_trabajador = $reg['edad']*1;


                if(  (AFP_QUITARINVALIDEZ_AUTOMATICO == TRUE &&  $edad_trabajador > AFP_EDADINVALIDEZ_LIMITE )  || (AFP_QUITARINVALIDEZ_AUTOMATICO == FALSE && $data_pension['invalidez'] == '0' )     )
                {
                    $sql = "  SELECT * FROM  planillas.conceptos_sistema WHERE plati_id = ? LIMIT 1";
                    list($conceptos_sistema) = $this->_CI->db->query($sql, array($reg['plati_id']))->result_array();

                    $this->_CI->planillaempleadoconcepto->desvincular( $conceptos_sistema['conc_seguros'] , $reg['plaemp_id'] );
                }


             }

           
           }

 
 
           if($grupo == GRUPOVC_AFP || in_array(GRUPOVC_AFP, $grupo) )
           {

                

               $sql = "SELECT * FROM  planillas.afps_vars_tipoplanilla WHERE plati_id = ? AND avt_estado = 1 LIMIT 1 ";
               $v_rs =  $this->_CI->db->query($sql, array($reg['plati_id']))->result_array();
               $vars_xplati  =  $v_rs[0];



               if($tipo_pension == PENSION_AFP )
               {
                 
                    $valores_afp = $this->_CI->afp->get_porcentajes($data_pension['afp']); // Valores para la afp especificada
                    $valores_afp['comision'] = ($data_pension['afm_id'] == AFP_FLUJO) ? $valores_afp['comision'] : $valores_afp['saldo'] ; 


                    $var_aporte = $this->_CI->variable->get($vars_xplati['vars_aportobli']);
                    $var_seguro = $this->_CI->variable->get($vars_xplati['vars_seguros']);
                    $var_comision = $this->_CI->variable->get($vars_xplati['vars_comvar']);
 
 
                    foreach($planilla_empleados as $reg)
                    {  

                
               /*
                      $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $vars_xplati['vars_aportobli'] , $valores_afp['jubilacion']   );
                      $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $vars_xplati['vars_seguros'] , $valores_afp['invalides']   );
                      $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $vars_xplati['vars_comvar'] , $valores_afp['comision']   );*/
                      
                    
                       $tasa_jubilacion = ($plati_id != TIPOPLANILLA_CONSCIVIL ) ? $valores_afp['jubilacion'] : $valores_afp['jubilacion_cc'];

                       $this->_CI->planillaempleadovariable->registrar( array('plaemp_id' => $reg['plaemp_id'], 
                                                                              'vari_id' => $vars_xplati['vars_aportobli'],
                                                                              'plaev_valor' => $tasa_jubilacion,
                                                                              'plaev_displayprint' => $var_aporte['vari_displayprint'],
                                                                              'plaev_procedencia' =>  PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO  ));

                       $this->_CI->planillaempleadovariable->registrar( array('plaemp_id' => $reg['plaemp_id'], 
                                                                              'vari_id' => $vars_xplati['vars_seguros'],
                                                                              'plaev_valor' => $valores_afp['invalides'],
                                                                              'plaev_displayprint' => $var_seguro['vari_displayprint'],
                                                                              'plaev_procedencia' =>  PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO  ));


                       $this->_CI->planillaempleadovariable->registrar( array('plaemp_id' => $reg['plaemp_id'], 
                                                                              'vari_id' => $vars_xplati['vars_comvar'],
                                                                              'plaev_valor' => $valores_afp['comision'],
                                                                              'plaev_displayprint' => $var_comision['vari_displayprint'],
                                                                              'plaev_procedencia' =>  PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO  ));


                    }
               } 

           }

   }

 

   public function get_tipo_pension( $indiv_id )
   {


      $data = array();

      $sql  = " SELECT * FROM rh.persona_pension
                WHERE pers_id = ? AND peaf_estado = 1";

      $rs  = $this->_CI->db->query($sql,array($indiv_id))->result_array();
      $reg = $rs[0];

      if($reg['pentip_id'] == PENSION_SNP )
      {    
      
            $data = array(  'codigo' => trim($reg['peaf_codigo']),
                            'jubilado' => trim($reg['peaf_jubilado']),
                            'invalidez' => trim($reg['peaf_invalidez'])  );
      
      }  
      else if($reg['pentip_id'] == PENSION_AFP )
      {

            $data = array(
                            'codigo' => trim($reg['peaf_codigo']),
                            'afp'    => trim($reg['afp_id']),
                            'afm_id' => trim($reg['afm_id']),
                            'jubilado' => trim($reg['peaf_jubilado']),
                            'invalidez' => trim($reg['peaf_invalidez'])
                         );
      
      }
      else
      {
      
            $reg['pentip_id'] = 0;
      
      }
 
      return array( $reg['pentip_id'] , $data );      
 
   }

/*
   
   public function add_essalud($id_pers,$data)
   {
    
        $this->_CI->db->trans_begin();
            
             $this->remove_essalud($id_pers);
               
             $data['pers_id'] =   $id_pers;
             $r               =   $this->_CI->db->insert( 'rh.persona_essalud',$data); 
             $id              =   $this->_CI->db->insert_id();
             $md5             =   md5('PERSALUD'.$id);
       
             $sql = " UPDATE rh.persona_essalud SET persa_key = ? WHERE persa_id = ? ";
             $this->_CI->db->query($sql, array($md5,$id));


        if ($this->_CI->db->trans_status() === FALSE)
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
   
   public function remove_essalud($id_pers){
       
       $this->_CI->db->where( 'pers_id', $id_pers)
                     ->update( 'rh.persona_essalud', array('persa_estado' => '0')); 
   }*/
   
   
   public function add_cuentadeposito($id_pers,$data)
   {
        
       $this->_CI->db->trans_begin();
       
  
       $this->remove_cuenta($id_pers);
         
       $data['pers_id'] =  $id_pers;
       $r=$this->_CI->db->insert( 'rh.persona_cuenta_deposito',$data); 

       $id = $this->_CI->db->insert_id();
       $md5 = md5('PERSCUENTA'.$id);

       $sql= " UPDATE rh.persona_cuenta_deposito SET pecd_key = ? WHERE pecd_id = ? ";
       $this->_CI->db->query($sql, array($md5,$id));
        

       
        if ($this->_CI->db->trans_status() === FALSE) {
            $this->_CI->db->trans_rollback();
            return false;
        } else  {
            $this->_CI->db->trans_commit();
            return true;
        } 
       
   }
   
    public function remove_cuenta($id_pers)
    { 

         $this->_CI->db->trans_begin();
 
          $this->_CI->db->where( 'pers_id', $id_pers)
                          ->update( 'rh.persona_cuenta_deposito', array('pecd_estado' => '0')); 

 


        if ($this->_CI->db->trans_status() === FALSE) {
            $this->_CI->db->trans_rollback();
            return false;
        } else  {
            $this->_CI->db->trans_commit();
            return true;
        } 
       
   }
    
   
   public function registrar_comision($id_pers,$data = array()){
       
       // $r=$this->_CI->db->insert($this->_TABLE_WS,$data);
         
      
       
   }
   
   public function actualizar_grupo($indiv_id, $grupo_id)
   {

      $sql = " UPDATE rh.persona_situlaboral SET gremp_id = ?  
               WHERE pers_id = ? AND persla_ultimo = 1 AND persla_estado = 1 ";
     
      $ok = $this->_CI->db->query($sql, array($grupo_id, $indiv_id));
   
      return ($ok) ? true : false;
   }  


   public function es_beneficiario_judicial($indiv_id_b)
   { 

      $sql = " SELECT ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador, 
                      ( indiv_b.indiv_appaterno || ' ' || indiv_b.indiv_apmaterno || ' ' || indiv_b.indiv_nombres ) as beneficiario, 
                       empcon.conc_id,
                       concs.conc_nombre 

                FROM planillas.empleado_concepto_beneficiario empben 
                INNER JOIN planillas.empleado_concepto empcon ON empben.empcon_id = empcon.empcon_id AND empcon.empcon_estado = 1
                LEFT JOIN public.individuo indiv ON empcon.indiv_id = indiv.indiv_id 
                LEFT JOIN public.individuo indiv_b ON empcon.indiv_id = empben.indiv_id_b 
                LEFT JOIN planillas.conceptos concs ON empcon.conc_id = concs.conc_id 
                WHERE empben.ecb_estado = 1 AND empben.indiv_id_b = ? ";


      $rs = $this->_CI->db->query($sql, array($indiv_id_b))->result_array();

      return $rs;
 
   }

   public function actualizar_ocupacion($params = array())
   {



    /*    $sql = " SELECT plati_id FROM rh.persona_situlaboral persla WHERE pers_id = ? AND persla_estado = 1 AND persla_ultimo = 1 LIMIT 1 ";
        list($rs) = $this->_CI->db->query($sql, array($params['indiv_id']) )->result_array();

        $plati_id = $rs['plati_id'];*/
  
        $sql = " UPDATE rh.persona_situlaboral 
                 SET ocu_id = ? 
                 WHERE pers_id = ? AND persla_estado = 1 AND persla_ultimo = 1 ";

        $this->_CI->db->query($sql, array( $params['ocupacion'], $params['indiv_id']));
/*

        $sql = " UPDATE planillas.empleado_ocupacion SET emocu_estado = 0 WHERE indiv_id = ? ";
        $this->_CI->db->query($sql, array($params['indiv_id']) ); 

        $sql = "INSERT INTO planillas.empleado_ocupacion(indiv_id, ocu_id, plati_id ) VALUES(?,?,?) ";
        $this->_CI->db->query($sql, array($params['indiv_id'], $params['ocupacion'],   $plati_id));
*/  
        $sql = " UPDATE planillas.planilla_empleados plaemp 
                 SET ocu_id = ? 
                 FROM  planillas.planillas pla,
                       planillas.planilla_movimiento plamo

                 WHERE  plaemp.pla_id = pla.pla_id AND 
                        pla.pla_estado = 1 AND 
                        plamo.pla_id = pla.pla_id AND 
                        plamo.plamo_estado = 1 AND 
                        plaemp.indiv_id = ? AND 
                        plamo.plaes_id = ? ";

         $this->_CI->db->query($sql, array( $params['ocupacion'],$params['indiv_id'] , ESTADOPLANILLA_ELABORADA ) );

   }



   public function verificar_dia_escalafon($indiv_id, $fecha_id)
   {
        $sql =" 
                SELECT peco_fechadesde as inicio, peco_fechahasta as fin, 'Comisio de servicio '::text as tipo 
                FROM  rh.persona_comision 
                WHERE peco_estado = 1 AND pers_id = 

                
                      SELECT perdm_fechaini as inicio, perdm_fechafin as fin, 'Descanso Medico'::text as tipo 
                FROM  rh.persona_descansomedico 
                WHERE perdm_estado = 1 AND pers_id = 
             
                SELECT peft_fecha as inicio, peft_fecha as fin, 'Faltas'::text as tipo 
                FROM  rh.persona_faltas_tardanzas
                WHERE perdm_estado = 1 AND peft_tipo AND  pers_id = 

                SELECT peft_fecha as inicio, peft_fecha as fin, 'Faltas'::text as tipo 
                FROM  rh.persona_faltas_tardanzas
                WHERE perdm_estado = 1 AND peft_tipo AND  pers_id = 
           ";
   }

   public function comparar_meses_trabajadores()
   {

       $sql = "   SELECT indiv.indiv_appaterno as Paterno, 
                         indiv.indiv_apmaterno as Materno, 
                         indiv.indiv_nombres as Nombres, 
                         '_'||indiv.indiv_dni as DNI,  

                       sept.plati_id as septiembre_regimen,
                       sept.asistencia as septiembre_asistencia,
                       sept.monto as septiembre_asistencia,

                       oct.plati_id as octubre_regimen,
                       oct.asistencia as octubre_asistencia,
                       oct.monto as octubre_asistencia,

                       nov.plati_id as noviembre_regimen,
                       nov.asistencia as noviembre_asistencia,
                       nov.monto as noviembre_asistencia      

                 FROM (


                   SELECT distinct(pe.indiv_id) as indiv_id 
                   FROM planillas.planilla_empleado_concepto pec 
                   INNER JOIN planillas.planilla_empleados         pe   ON pec.plaemp_id = pe.plaemp_id AND pe.plaemp_estado = 1 
                   INNER JOIN planillas.planillas                  p    ON pe.pla_id = p.pla_id AND p.pla_estado = 1 
                   INNER JOIN planillas.planilla_movimiento        pm   ON p.pla_id  = pm.pla_id AND plamo_estado = 1 AND pm.plaes_id >= 2 
                   
                ) as tra 
                LEFT JOIN (
                 
                   SELECT  p.pla_mes, 
                           pe.indiv_id, 
                           MAX(pt.plati_nombre) as plati_id,
                           
                           SUM(pec.plaec_value) as monto, 
                           MAX(pec2.plaec_value ) as asistencia

                   FROM planillas.planilla_empleado_concepto pec 
                   INNER JOIN planillas.planilla_empleados         pe   ON pec.plaemp_id = pe.plaemp_id AND pe.plaemp_estado = 1 
                   INNER JOIN planillas.planillas                  p    ON pe.pla_id = p.pla_id AND p.pla_estado = 1 AND pla_mes = '09'
                   INNER JOIN planillas.planilla_movimiento        pm   ON p.pla_id  = pm.pla_id AND plamo_estado = 1 AND pm.plaes_id >= 2 
                   INNER JOIN planillas.planilla_tipo              pt   ON p.plati_id = pt.plati_id
                   LEFT  JOIN planillas.conceptos_sistema          cs   ON pt.plati_id = cs.plati_id
                   LEFT  JOIN planillas.planilla_empleado_concepto pec2 ON pe.plaemp_id = pec2.plaemp_id and cs.conc_asistencia = pec2.conc_id
                   INNER JOIN public.mes       me ON p.pla_mes    = me.mes_eje
                   INNER JOIN public.individuo i  ON pe.indiv_id  = i.indiv_id
                   INNER JOIN sag.tarea t  ON pec.tarea_id = t.tarea_id 
                   WHERE pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.conc_tipo IN (1) 
                                  
                   GROUP BY p.pla_mes, pe.indiv_id  
                   ORDER BY p.pla_mes, pe.indiv_id  

                ) as sept ON tra.indiv_id = sept.indiv_id 

                LEFT JOIN (
                   
                   SELECT  p.pla_mes, 
                           pe.indiv_id, 
                           MAX(pt.plati_nombre) as plati_id,
                           
                           SUM(pec.plaec_value) as monto, 
                           MAX(pec2.plaec_value ) as asistencia

                   FROM planillas.planilla_empleado_concepto pec 
                   INNER JOIN planillas.planilla_empleados         pe   ON pec.plaemp_id = pe.plaemp_id AND pe.plaemp_estado = 1 
                   INNER JOIN planillas.planillas                  p    ON pe.pla_id = p.pla_id AND p.pla_estado = 1 AND pla_mes = '10'
                   INNER JOIN planillas.planilla_movimiento        pm   ON p.pla_id  = pm.pla_id AND plamo_estado = 1 AND pm.plaes_id >= 2 
                   INNER JOIN planillas.planilla_tipo              pt   ON p.plati_id = pt.plati_id
                   LEFT  JOIN planillas.conceptos_sistema          cs   ON pt.plati_id = cs.plati_id
                   LEFT  JOIN planillas.planilla_empleado_concepto pec2 ON pe.plaemp_id = pec2.plaemp_id and cs.conc_asistencia = pec2.conc_id
                   INNER JOIN public.mes       me ON p.pla_mes    = me.mes_eje
                   INNER JOIN public.individuo i  ON pe.indiv_id  = i.indiv_id
                   INNER JOIN sag.tarea t  ON pec.tarea_id = t.tarea_id 
                   WHERE pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.conc_tipo IN (1) 
                                  
                   GROUP BY p.pla_mes, pe.indiv_id  
                   ORDER BY p.pla_mes, pe.indiv_id  


                ) as oct ON tra.indiv_id = oct.indiv_id

                LEFT JOIN (
                   SELECT  p.pla_mes, 
                           pe.indiv_id, 
                           MAX(pt.plati_nombre) as plati_id,
                           
                           SUM(pec.plaec_value) as monto, 
                           MAX(pec2.plaec_value ) as asistencia

                   FROM planillas.planilla_empleado_concepto pec 
                   INNER JOIN planillas.planilla_empleados         pe   ON pec.plaemp_id = pe.plaemp_id AND pe.plaemp_estado = 1 
                   INNER JOIN planillas.planillas                  p    ON pe.pla_id = p.pla_id AND p.pla_estado = 1 AND pla_mes = '11'
                   INNER JOIN planillas.planilla_movimiento        pm   ON p.pla_id  = pm.pla_id AND plamo_estado = 1 AND pm.plaes_id >= 2 
                   INNER JOIN planillas.planilla_tipo              pt   ON p.plati_id = pt.plati_id
                   LEFT  JOIN planillas.conceptos_sistema          cs   ON pt.plati_id = cs.plati_id
                   LEFT  JOIN planillas.planilla_empleado_concepto pec2 ON pe.plaemp_id = pec2.plaemp_id and cs.conc_asistencia = pec2.conc_id
                   INNER JOIN public.mes       me ON p.pla_mes    = me.mes_eje
                   INNER JOIN public.individuo i  ON pe.indiv_id  = i.indiv_id
                   INNER JOIN sag.tarea t  ON pec.tarea_id = t.tarea_id 
                   WHERE pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.conc_tipo IN (1) 
                                  
                   GROUP BY p.pla_mes, pe.indiv_id  
                   ORDER BY p.pla_mes, pe.indiv_id  

                   ) as nov ON tra.indiv_id = nov.indiv_id

                   INNER JOIN public.individuo indiv ON tra.indiv_id = indiv.indiv_id 

                   ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
                   
              ";

   }  


   public function  asistencia_por_mes()
   {

      $sql = "      SELECT  (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador, plati.plati_nombre, ('_'|| ind.indiv_dni) as dni, asistencia.* FROM (

            SELECT * FROM crosstab(' 

               SELECT  plaemp.indiv_id, pla.plati_id, pla.pla_mes,   SUM(plaec_value) as total 
               FROM planillas.planilla_empleados plaemp 
               INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla.plati_id IN (4,5)
               INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = 2
               INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 0 
               INNER JOIN planillas.conceptos_sistema cosi ON  pla.plati_id = cosi.plati_id AND  plaec.conc_id = conc_asistencia   
               
               WHERE plaec.plaec_value > 0 

               GROUP BY plaemp.indiv_id, pla.plati_id, pla.pla_mes 
               
               ORDER BY plaemp.indiv_id, pla.pla_mes 

            ', 'SELECT mes_eje FROM public.mes WHERE mes_eje IN (''09'',''10'',''11''  )' )as ct(
              
               \"indiv_id\" numeric,
               \"plati_id\" numeric,
               \"sept\" numeric,
               \"oct\" numeric,
               \"nov\" numeric  
              
            )

            ) as asistencia
            LEFT JOIN \"public\".individuo ind ON asistencia.indiv_id = ind.indiv_id 
            LEFT JOIN planillas.planilla_tipo plati ON asistencia.plati_id = plati.plati_id 
            ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

        ";
  }


  public function asistencia_meses($params = array() )
  {

 

       $sql = " SELECT ";


        if($params['modo'] == '')
        {

           $sql.=" (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador,  
                        ('_'|| ind.indiv_dni) as dni, asistencia.* ";
        }
        else
        {
            $sql.=" asistencia.*, persla.persla_fechaini, persla.persla_fechafin ";
        }
 
      $sql.="    FROM (

                     SELECT * FROM crosstab(' 

                        SELECT  plaemp.indiv_id, pla.pla_mes, ROUND(SUM(plaec_value)) as total 
                        FROM planillas.planilla_empleados plaemp 
                        INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 
                        INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT."
                        INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_tipo = 1 AND plaec.conc_afecto = 1 
                         
                        WHERE plaec.plaec_value > 0 
                        GROUP BY plaemp.indiv_id, pla.pla_mes 
                        ORDER BY plaemp.indiv_id, pla.pla_mes 

                     ', 'SELECT mes_eje FROM public.mes WHERE  mes_id > 0 ' )as ct(
                       
                        \"indiv_id\" numeric, 
                        \"enero\" numeric,
                        \"febrero\" numeric,
                        \"marzo\" numeric,
                        \"abril\" numeric,
                        \"mayo\" numeric,
                        \"junio\" numeric,
                        \"julio\" numeric,
                        \"agosto\" numeric,
                        \"septiembre\" numeric,
                        \"octubre\" numeric, 
                        \"noviembre\" numeric,
                        \"diciembre\" numeric  
                       
                     )

             ) as asistencia
             LEFT JOIN \"public\".individuo ind ON asistencia.indiv_id = ind.indiv_id  
             LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_estado = 1 AND persla.persla_ultimo = 1
             ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

         ";

      $_MESES = array( 
                          '01' => 'ENERO',
                          '02' => 'FEBRERO',
                          '03' => 'MARZO',
                          '04' => 'ABRIL',
                          '05' => 'MAYO',
                          '06' => 'JUNIO',
                          '07' => 'JULIO',
                          '08' => 'AGOSTO',
                          '09' => 'SEPTIEMBRE',
                          '10' => 'OCTUBRE',
                          '11' => 'NOVIEMBRE',
                          '12' => 'DICIEMBRE'
                      );
 

      $meses= array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre','noviembre', 'diciembre');
 

      if( $params['modo'] == 'altas' )
      {

          $sql_ = $sql;
 
          $sql = " SELECT data.indiv_id FROM (".$sql_.") as data 

                   WHERE (data.noviembre = 0 OR data.noviembre is null) AND data.diciembre > 0 AND data.persla_fechaini > '2013-12-01'
                ";

      }


      if( $params['modo'] == 'bajas' )
      {

          $sql_ = $sql;
      
          $sql = " SELECT data.indiv_id FROM (".$sql_.") as data 

                   WHERE data.noviembre > 0 AND (data.diciembre = 0 OR  data.diciembre is null)
                ";

      }

  
         $rs = $this->_CI->db->query($sql, array())->result_array();
        
 
      return $rs;   

  }


  public function historico_ingresos_mes($params = array() )
  {
       

      $meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

      $sql_meseje ="   SELECT  * FROM 
                   ( VALUES (''".$params['anio_anterior']."_12'') ";
      
      foreach ($meses as $mes)
      {
          
          $sql_meseje.= ",(''".$params['anio']."_".$mes."'')";

      }

      $sql_meseje.=" ) as mes_eje"; 


      $sql.=" SELECT

                  (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador,  
                   ind.indiv_dni,
                   plati.plati_nombre,
                   persla.*,
                   ingresos.*

               FROM (

                     SELECT * FROM crosstab(' 

                        SELECT  plaemp.indiv_id, (pla.pla_anio || ''_'' || pla.pla_mes) as mes, COALESCE(SUM(plaec_value),0) as total 
                        FROM planillas.planilla_empleados plaemp 
                        INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1
                        INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ?
                        INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_tipo = 1 AND plaec.conc_afecto = 1 
                         
                        WHERE plaec.plaec_value > 0  AND ( ( pla.pla_anio = '?' AND (pla.pla_mes != '?' OR pla.sunat_seleccionada = 1 ) ) OR ( pla.pla_anio = '?'  AND pla.pla_mes = ''12'') )
                        GROUP BY plaemp.indiv_id, pla.pla_anio, pla.pla_mes 
                        ORDER BY plaemp.indiv_id, pla.pla_anio, pla.pla_mes 

                     ', '".$sql_meseje."' )as ct(
                       
                        \"indiv_id\" numeric, 
                        \"".$params['anio_anterior']."_12\" double precision,
                        \"".$params['anio']."_01\" double precision,
                        \"".$params['anio']."_02\" double precision,
                        \"".$params['anio']."_03\" double precision,
                        \"".$params['anio']."_04\" double precision,
                        \"".$params['anio']."_05\" double precision,
                        \"".$params['anio']."_06\" double precision,
                        \"".$params['anio']."_07\" double precision,
                        \"".$params['anio']."_08\" double precision,
                        \"".$params['anio']."_09\" double precision,
                        \"".$params['anio']."_10\" double precision, 
                        \"".$params['anio']."_11\" double precision,
                        \"".$params['anio']."_12\" double precision  
                       
                     )

             ) as ingresos
             LEFT JOIN \"public\".individuo ind ON ingresos.indiv_id = ind.indiv_id  
             LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_estado = 1 AND persla.persla_ultimo = 1
             LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
             ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

         ";

         $values = array( ESTADOPLANILLA_MINIMO_SUNAT, $params['anio'], $params['mes1'],  $params['anio_anterior']  );

 /*     $_MESES = array( 
                          '01' => 'ENERO',
                          '02' => 'FEBRERO',
                          '03' => 'MARZO',
                          '04' => 'ABRIL',
                          '05' => 'MAYO',
                          '06' => 'JUNIO',
                          '07' => 'JULIO',
                          '08' => 'AGOSTO',
                          '09' => 'SEPTIEMBRE',
                          '10' => 'OCTUBRE',
                          '11' => 'NOVIEMBRE',
                          '12' => 'DICIEMBRE'
                      );
      

      $meses= array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre','noviembre', 'diciembre');
      */

      $get_fields = ' * ';

      if($params['mes1'] != '01')
      {
          if( $params['modo'] == 'altas' )
          {

              $sql_ = $sql;
             
              $sql = " SELECT ".$get_fields." FROM (".$sql_.") as data 

                       WHERE (data.\"".$params['anio']."_".$params['mes2']."\" = 0 OR data.\"".$params['anio']."_".$params['mes2']."\" is null) AND data.\"".$params['mes1']."\" > 0  
                    ";

          }


          if( $params['modo'] == 'bajas' )
          {

              $sql_ = $sql;
          
              $sql = " SELECT ".$get_fields." FROM (".$sql_.") as data 

                       WHERE (data.\"".$params['mes1']."\" = 0 OR data.\"".$params['mes1']."\" is null) AND data.\"".$params['anio']."_".$params['mes2']."\" > 0  
                    ";

          }  
        
      }
      else
      {

          if( $params['modo'] == 'altas' )
          {

              $sql_ = $sql;
             
              $sql = " SELECT ".$get_fields." FROM (".$sql_.") as data 

                       WHERE (data.\"".$params['anio_anterior']."_".$params['mes2']."\" = 0 OR data.\"".$params['anio_anterior']."_".$params['mes2']."\" is null) AND data.\"".$params['anio']."_".$params['mes1']."\" > 0  
                    ";

          }


          if( $params['modo'] == 'bajas' )
          {

              $sql_ = $sql;
          
              $sql = " SELECT ".$get_fields." FROM (".$sql_.") as data 

                       WHERE (data.\"".$params['anio']."_".$params['mes1']."\" = 0 OR data.\"".$params['anio']."_".$params['mes1']."\" is null) AND data.\"".$params['anio_anterior']."_".$params['mes2']."\" > 0  
                    ";

          }  
      }

 
         $rs = $this->_CI->db->query($sql, $values )->result_array();
        
      
      return $rs;   


  }
 

  public function trabajadores_varios_regimenes_x_mes($params = array())
  { 

      $sql = "  SELECT distinct(plaemp.indiv_id) as indiv_id
                FROM planillas.planilla_empleados plaemp 
                INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ?  AND pla_mes = ?  
                INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADOPLANILLA_MINIMO_SUNAT." 
                INNER JOIN rh.persona_situlaboral persla ON plaemp.persla_id = persla.persla_id 
                INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id AND plaec.plaec_estado = 1 AND plaec_marcado = 1 AND plaec.conc_afecto = 1
                INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.cosu_id != 0


             ";

  }

  public function actualizar_lugar_de_trabajo($nuevo_lugar_de_trabajo, $ids){
      
      $ids_key = implode("','", $ids);
      $sql = " UPDATE public.individuo SET indiv_lugar_de_trabajo = ? WHERE indiv_key IN ('".$ids_key."') ";

      $ok = $this->_CI->db->query($sql, array($nuevo_lugar_de_trabajo));

      return ($ok ? true : false);
  }

}