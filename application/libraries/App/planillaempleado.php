<?php

class planillaempleado extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'plaemp_id',
                                    'code'  => 'plaemp_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'plaemp_estado'
                            );
    
    protected $_SCHEMA      = 'planillas';
    protected $_TABLE       = 'planilla_empleados';
    protected $_PREF_TABLE  = 'PLANILLAEMPMPI'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }
    
    
    public function get($id, $is_key = false)
    {
         
        $sql = " SELECT  (ind.indiv_nombres || ' ' || ind.indiv_appaterno || ' ' ||ind.indiv_apmaterno) as empleado,
                        ind.indiv_dni as empleado_dni,
                        cate.platica_nombre as categoria_nombre,     
                        p_e.*, 
                        ind.indiv_key as empleado_key,

                        persla.persla_fechaini, persla_fechacese, 

                        (CASE WHEN p_e.peaf_id != 0 THEN     
                       
                           pensi.pentip_id

                         ELSE 

                           0

                         END   ) as tipo_pension, 

                        afp.afp_nombre,
                        mo.afm_nombre as modo_afp,
                        p_e.platica_id as categoria,
                        (tarea.sec_func || '-'|| tarea.tarea_nro) as tarea,
                        (tarea.sec_func || '-'|| tarea.tarea_nro) as tarea_codigo,
                        tarea.tarea_nombre,
                        (p_e.fuente_id  || '-' || p_e.tipo_recurso) as fuente,

                        pla.pla_id,
                        pla.pla_key,
                        pla.plati_id,
                        plati.plati_planilladetalle_ocupacion as config_ocupacion,
                        pcd.*,
                        eb.ebanco_nombre, 

                        (CASE WHEN p_e.pecd_id != pcd2.pecd_id   THEN

                            0
                        ELSE

                            1

                        END) as cuenta_actualizada,
 
                        pensi.peaf_jubilado as jubilado,


                        ocu.ocu_nombre as ocupacion_nombre,

                        firma.firma_imagen,
                        firma.indiv_id as firma_indiv,
                        ind.indiv_suspension_cuarta,
                        ind.indiv_suspension_fecha,
                        COALESCE( EXTRACT( 'YEAR' FROM(ind.indiv_suspension_fecha) )::text,'') as suspension_anio  

                        FROM planillas.planilla_empleados p_e 
                        LEFT JOIN planillas.planillas pla on p_e.pla_id = pla.pla_id
                        LEFT JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                        LEFT JOIN  public.individuo ind ON p_e.indiv_id = ind.indiv_id 
                        LEFT JOIN  rh.persona_situlaboral persla ON p_e.persla_id = persla.persla_id 
                        LEFT JOIN  planillas.planilla_tipo_categoria cate ON p_e.platica_id = cate.platica_id 
                        LEFT JOIN  rh.persona_pension pensi ON p_e.peaf_id = pensi.peaf_id   
                        LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id      
                        LEFT JOIN  rh.afp_modo mo ON mo.afm_id = pensi.afm_id 
                        LEFT JOIN  rh.persona_cuenta_deposito pcd ON p_e.pecd_id = pcd.pecd_id  
                        LEFT JOIN  rh.persona_cuenta_deposito pcd2 ON   p_e.indiv_id = pcd2.pers_id AND pcd2.pecd_estado = 1
                        LEFT JOIN  public.entidades_bancarias eb ON pcd.ebanco_id = eb.ebanco_id
                        LEFT JOIN  sag.tarea ON p_e.tarea_id = tarea.tarea_id 
                        LEFT JOIN planillas.ocupacion ocu ON p_e.ocu_id = ocu.ocu_id 
                        LEFT JOIN planillas.firmas firma ON p_e.firma_id = firma.firma_id 
                        WHERE ";
        
        $sql.= (!$is_key)?  " p_e.plaemp_id = ?  " : " p_e.plaemp_key = ?  ";
        
        $sql.="       LIMIT 1    ";
        
        //echo $sql." ".$id;
        $rs = $this->_CI->db->query($sql, array($id))->result_array();
        return $rs[0];
    }
    


    public function get_list($planilla_id, $by_indiv_id = false, $params = array())
    { 
 
        $sql = " SELECT pla.pla_id,  ";
      
        $query =  array();

        if($by_indiv_id)
        {
            $sql.="  plaemp.indiv_id as detalle_id, 
                     indiv.indiv_key as detalle_key, ";
        }    
        else
        {
            $sql.="  plaemp.plaemp_id as detalle_id, 
                     plaemp.plaemp_key as detalle_key,
                     cate.platica_nombre as categoria_nombre, ";
        }

        $sql.="      plaemp.indiv_id,   
                     indiv.indiv_appaterno, 
                     indiv.indiv_apmaterno,
                     indiv.indiv_nombres as nombres, 
                     ( indiv_appaterno ||' '||  indiv_apmaterno ) as apellidos,   
                     indiv.indiv_dni as dni, 
                     plati_nombre as tipo_empleado, 
                     ocu.ocu_nombre as ocupacion_nombre,
                     (tarea.sec_func || '-'|| tarea.tarea_nro) as tarea,
                     tarea.tarea_nombre,
                     (plaemp.fuente_id || '-' || plaemp.tipo_recurso) as fuente,
                     firma.firma_imagen,
                     firma.indiv_id as firma_indiv,
                     historial.persla_quinta_gratificacionproyeccion,
                     indiv.indiv_suspension_cuarta,
                     indiv.indiv_suspension_fecha,
                     COALESCE( EXTRACT( 'YEAR' FROM(indiv.indiv_suspension_fecha) )::text,'') as suspension_anio  
                        

                FROM planillas.planilla_empleados plaemp  
                LEFT JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id AND plaec.plaec_estado = 1
                LEFT JOIN planillas.firmas firma ON plaemp.firma_id = firma.firma_id 
                LEFT JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id AND indiv_estado = 1
                LEFT JOIN planillas.ocupacion ocu ON plaemp.ocu_id = ocu.ocu_id 
                LEFT JOIN rh.persona_cuenta_deposito pecd ON plaemp.pecd_id = pecd.pecd_id 
                LEFT JOIN rh.persona_pension pepe ON plaemp.peaf_id = pepe.peaf_id 
                LEFT JOIN rh.persona_situlaboral historial  ON plaemp.persla_id = historial.persla_id 
                LEFT JOIN sag.tarea ON plaemp.tarea_id = tarea.tarea_id
                LEFT JOIN planillas.planilla_tipo tip ON historial.plati_id = tip.plati_id
                LEFT JOIN planillas.planilla_tipo_categoria cate ON plaemp.platica_id = cate.platica_id  
                INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id  
        
               
                "; 


              if(is_array($planilla_id))
              {
                
                $in_planillas =   implode(',',$planilla_id);
            
      
                $sql.= " AND pla.pla_id IN ( ".$in_planillas." ) ";

              }
              else
              {
                  $sql.=" AND pla.pla_id = ? ";
                  $query[]= $planilla_id;

              }
              
                
      $sql.="  
                WHERE plaemp.plaemp_estado = 1

      
               ";
        
       


        if( trim($params['dni']) != '')
        {
            
            $sql.=" AND indiv_dni = ?  ";
            $query[] = trim($params['dni']);

        }
        else
        {

            if( trim($params['paterno']) != '')
            {
               $sql.= " AND indiv_appaterno = ? ";
               $query[] = strtoupper(trim($params['paterno']));   
            }

            if( trim($params['materno']) != '')
            {
               $sql.= " AND indiv_apmaterno = ? ";
               $query[] = strtoupper(trim($params['materno']));   
            }

            if( trim($params['nombres']) != '')
            {
               $sql.= " AND indiv_nombres = ? ";
               $query[] = strtoupper(trim($params['nombres']));   
            }


            if( trim($params['dependencia']) != '0' && trim($params['dependencia']) != '')
            {
               $sql.= " AND historial.depe_id = ? ";
               $query[] = trim($params['dependencia']);   
            }

            if( trim($params['cargo']) != '0' && trim($params['cargo']) != '')
            {
               $sql.= " AND historial.cargo_id = ? ";
               $query[] = trim($params['cargo']);   
            }


            if($params['tienecuenta'] != '0' && $params['tienecuenta'] != '')
            {

                if($params['tienecuenta'] == '1')
                {
                    $sql.=" AND pecd.pecd_id is not NULL ";
                
                    if($params['banco'] != '0')
                    {
                        $sql.=" AND pecd.ebanco_id = ? ";
                        $query[] = $params['banco'];
                    }

                }  
                else if($params['tienecuenta'] == '2')
                {
                     $sql.=" AND pecd.pecd_id is NULL ";   
                }
                 
            }


            if($params['tipopension'] != '0' && $params['tipopension'] != '')
            {

                if($params['tipopension'] ==  PENSION_AFP )
                {
                    $sql.=" AND pepe.pentip_id = ? ";
                    $query[] = PENSION_AFP;
                
                    if($params['afp'] != '0')
                    {
                        $sql.=" AND pepe.afp_id = ? ";
                        $query[] = $params['afp'];
                    }

                    if($params['modoafp'] != '0')
                    {
                        $sql.=" AND pepe.afm_id = ? ";
                        $query[] = $params['modoafp'];
                    }

                }  
                else if($params['tipopension'] == PENSION_SNP )
                {
                     $sql.=" AND pepe.pentip_id = ? ";
                     $query[] = PENSION_SNP;   
                }
                 
            }


            if($params['tarea'] != '0' && $params['tarea'] != '')
            {

               if($params['tarea']=='no')
               {
                  $sql.=" AND (plaemp.tarea_id = 0 OR plaemp.tarea_id is null) ";  
               }
               else if($params['tarea']=='si')
               {
                  $sql.=" AND ( plaemp.tarea_id is not null AND  plaemp.tarea_id != 0  ) ";  
               }
               else
               {
                
                   $sql.=" AND plaemp.tarea_id = ? ";
                   $query[] = $params['tarea'];  
                     
               }

            }


            if($params['fuente'] != '0' && $params['fuente'] != '')
            {

               if($params['fuente'] == 'no')
               {
                   $sql.=" AND (plaemp.fuente_id = '' OR plaemp.fuente_id is null ) ";
               }
               else if($params['fuente'] == 'si')
               {
                   $sql.=" AND (plaemp.fuente_id != '' AND plaemp.fuente_id is not null ) ";
               }
               else
               {
                   $sql.=" AND plaemp.fuente_id = ? ";
                   $query[] = $params['fuente'];  

               }
            }

            
            if($params['tiporecurso'] != '')
            {
               if($params['tiporecurso'] == 'no')
               {
                   $sql.=" AND (plaemp.tipo_recurso = '' OR plaemp.tipo_recurso is null ) ";
               }
               else if($params['tiporecurso'] == 'si')
               {
                   $sql.=" AND (plaemp.tipo_recurso != '' AND plaemp.tipo_recurso is not null ) ";
               }
               else
               { 
                   $sql.=" AND plaemp.tipo_recurso = ? ";
                   $query[] = $params['tiporecurso'];  
               }     
            }
      

            if($params['concepto'] != '0' && $params['concepto'] != '')
            {

               $sql.=" AND (plaec.conc_id = ? AND plaec.conc_id is not NULL ) ";
               $query[] = $params['concepto'];  
                 
            }

            if($params['grupo'] != '0' && $params['grupo'] != '')
            {

               $sql.=" AND ( historial.gremp_id = ? ) ";
               $query[] = $params['grupo'];  
                 
            }

        }

        $sql.=" GROUP BY pla.pla_id, ";


        if($by_indiv_id)
        {
            $sql.="  plaemp.indiv_id, indiv.indiv_key,";
        }    
        else
        {
            $sql.="  plaemp.plaemp_id, cate.platica_nombre, plaemp.plaemp_key,  ";
        }



        $sql.="      firma_imagen,firma_indiv, plaemp.indiv_id, indiv_appaterno, indiv_apmaterno, 
                     indiv_nombres, indiv_dni,  plati_nombre, ocupacion_nombre,  tarea.sec_func, 
                     tarea.tarea_nro,tarea_nombre, plaemp.fuente_id, plaemp.tipo_recurso, firma_imagen, firma_indiv,
                     historial.persla_quinta_gratificacionproyeccion,
                     indiv.indiv_suspension_cuarta,
                     indiv.indiv_suspension_fecha 

                ORDER BY "; 

        if($params['orderbyplanilla'] === TRUE )
        {

            $sql.=" pla.pla_id,";
        }    

        $sql.=" indiv_appaterno, indiv_apmaterno, indiv_nombres  ";
        
        if($by_indiv_id)
        {
            $sql.="  ,plaemp.indiv_id ";
        }    
        else
        {
            $sql.="  ,plaemp.plaemp_id  ";
        }

        $rs = $this->_CI->db->query($sql, $query )->result_array();
        
        return $rs;

    }
    

    public function has_empleado($planilla_id, $empleado_id, $tipo = 0, $planilla_tiene_categorias)
    {

        if($planilla_tiene_categorias && $tipo == 0)
        {

            $sql = " SELECT *   
                     FROM  planillas.planilla_empleados 
                     WHERE plaemp_estado = 1 AND 
                           pla_id = ? AND indiv_id = ? LIMIT 1  ";   

        }
        else
        {

            $sql = " SELECT *   
                     FROM  planillas.planilla_empleados 
                     WHERE plaemp_estado = 1 AND 
                           pla_id = ? AND indiv_id = ? AND platica_id = ? 
                    
                     LIMIT 1 ";    
 
        }


        list($rs)  = $this->_CI->db->query($sql, array($planilla_id, $empleado_id, $tipo ) )->result_array();
        
        return  ( ( sizeof($rs) == 0 ) ? FALSE :  $rs );     
       
    }
    
    

    public function registrar( $planilla_id, $empleado_id , $tipo = 0, $return_id = false, $params = array() )
    {
           
         $this->_CI->db->trans_begin();
      

         $this->_CI->load->library(array('App/planilla',
                                         'App/variable',
                                         'App/concepto',
                                         'App/variable_metodos',
                                         'App/empleadoafectacion',
                                         'App/empleadovariable',
                                         'App/empleadoconcepto',
                                         'App/planillaempleadovariable', 
                                         'App/planillaempleadoconcepto',
                                         'App/persona', 
                                         'App/firma',
                                         'Catalogos/afp'));
         
          
          $plani_info    =  $this->_CI->planilla->get($planilla_id);
          $plati_id    = $plani_info['plati_id'];

          $ano_eje = $plani_info['ano_eje'];
            
          /* SOPORTE PARA TABLA DE DATOS */

          $static_data = array(
                            'y_values' => array()
                         ); 

          $static_data['y_values']['platica_id'] = $tipo; 
          
          
          $indiv_info  = $this->_CI->persona->get_some_info( $empleado_id, 'id');
            
          
          $values = array(
                             'pla_id'    => $planilla_id,
                             'indiv_id'  => $empleado_id,
                             'persla_id' => $indiv_info['persla_id'], 
                             'peaf_id'   => $indiv_info['peaf_id'],
                             'pecd_id'   => $indiv_info['pecd_id'], 
                             'emocu_id'  => $indiv_info['emocu_id'],
                             'ocu_id'    => $indiv_info['ocu_id']
                         );

         $edad_trabajador = ($indiv_info['edad'] == '' || is_numeric($indiv_info['edad']) == false) ? 0 : $indiv_info['edad'];
 

         if(trim($params['ocupacion_label']) != '')
         {
            $values['plaemp_ocupacion_label'] = trim($params['ocupacion_label']);
         }




         if($tipo != 0 && $tipo != '0' )
         {
            $values['platica_id'] = $tipo; 

         } 


         $planilla_tiene_categorias = ($plani_info['pla_tiene_categoria'] == '1') ? true : false;
 
         $registro_empleado =  $this->has_empleado($planilla_id, $empleado_id, $tipo, $planilla_tiene_categorias );
 
         if( $registro_empleado === FALSE )
         {
             
             $empre_id = '0';
             $w_a      = false;

             /* 
               Verificamos en caso la planilla tenga categorias si el trabajador ya figura en esta. 
               Tenemos que recuperar los datos de su afectacion presupuestal
             */ 
         
             if($planilla_tiene_categorias)
             {

                $sql = " SELECT empre_id, tarea_id, fuente_id, tipo_recurso, clasificador_id 
                         FROM planillas.planilla_empleados plaemp
                         WHERE plaemp.pla_id = ? AND plaemp.indiv_id = ? 
                         GROUP BY empre_id, tarea_id, fuente_id, tipo_recurso, clasificador_id
                       ";

                $rs = $this->_CI->db->query($sql, array($planilla_id, $empleado_id))->result_array();

                if(sizeof($rs) == 1)
                { 
                    $tarea_id        = $rs[0]['tarea_id'];
                    $fuente_id       = $rs[0]['fuente_id'];
                    $recurso_id      = $rs[0]['tipo_recurso'];
                    $clasificador_id = $rs[0]['clasificador_id'];
                    $empre_id        = $rs[0]['empre_id'];

                    $w_a = true;
                }
          
             }   
            
             /* 
               Sino se especifico la afectación presupuestal entonces esta se obtiene de la planilla o del trabajador, segun corresponda.
             */  
  
             if($w_a == false)
             { 
 
                 if($plani_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA) // Datos de afectacion especificados en la planilla
                 {
                     $tarea_id        = ($plani_info['tarea_id']  != '') ? $plani_info['tarea_id'] : '0'; 
                     $fuente_id       = ($plani_info['fuente_id'] != '') ? $plani_info['fuente_id'] : '0';
                     $recurso_id      = ($plani_info['tipo_recurso'] != '') ? $plani_info['tipo_recurso'] : '';

                     if($plani_info['pla_especificar_clasificador'] == '1')
                     { 
                    
                        $clasificador_id = ($plani_info['clasificador_id'] != '') ? $plani_info['clasificador_id'] : '';
                     }
                     else
                     {
                        $clasificador_id = '';
                     }
 
                 }
                 else if($plani_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA_X_DETALLE )
                 {
                     $afectacion_info = $this->_CI->empleadoafectacion->get($empleado_id, $ano_eje );
                     
                     $tarea_id        = $afectacion_info['tarea_id'];
                     $fuente_id       = $afectacion_info['fuente_id'];
                     $recurso_id      = $afectacion_info['tipo_recurso'];
                     $clasificador_id = '';
                     $empre_id        = $afectacion_info['empre_id'];
                 }
             }    


             if(trim($params['fuente_especifica']) != '') // ESTO VIENE DESDE EL MIGRADO
             {  

                list($fuente_id,  $recurso_id) = explode('-',$params['fuente_especifica'] );
                $values['plaemp_afectacion_actualizada'] = '1';
             }

             $values['empre_id']        = $empre_id;
             $values['tarea_id']        = $tarea_id;
             $values['fuente_id']       = $fuente_id;
             $values['tipo_recurso']    = $recurso_id;
             $values['clasificador_id'] = $clasificador_id;


             $firma_info = $this->_CI->firma->get_actual();
 
             $values['firma_id'] = ( trim($firma_info['firma_id']) != '' ?  trim($firma_info['firma_id']) : '1');
             
             /*
                  Guardamos datos en la tabla planilla.planilla_empleados
             */ 
             list($plaemp_id, $plaemp_key) = parent::registrar($values,true );

             /* 
                 Cuando en una planilla de regimen o tipo de planillas con categorias se añade un trabajador 
                 por defecto  no se especifica la categoria del trabajador, esta se especifica desde el panel de datos (variables y conceptos)
                 por lo tanto si $tiene_categorias y $tipo = 0 significa q solo se relaciono el trabajador con la planilla. PERO aun no
                 le corresponden conceptos por que no se le ha asignado una categoria 

             */ 

             if($planilla_tiene_categorias && $tipo == 0)
             {
 

                if ($this->_CI->db->trans_status() === FALSE)
                {
                   $this->_CI->db->trans_rollback();
                   $ok = false;    
                }
                else
                {
                   $this->_CI->db->trans_commit();
                   $ok = true;
                   
                } 

                if($return_id)
                {
                   if($ok == false) return array(false,false);
                   
                   return  array($plaemp_id, $plaemp_key);
                }        
                else
                {
                   return $ok;
                }

             }   
            

            /*
               Traemos los datos de los conceptos estaticos del sistema.
               en este caso, solo nos interesan aquellos que tengan que ver con el sistema de pensiones.
            */

            $sql = "  SELECT * FROM  planillas.conceptos_sistema WHERE plati_id = ? LIMIT 1";
            list($conceptos_sistema) = $this->_CI->db->query($sql, array($plati_id))->result_array();

            $conceptos_afps = array( $conceptos_sistema['conc_aportobli'], $conceptos_sistema['conc_comvar'], $conceptos_sistema['conc_seguros'] );

            if($plati_id == TIPOPLANILLA_CONSCIVIL )
            {
                $conceptos_afps[] = $conceptos_sistema['conc_aportobli_cc'];
            }

            $conceptos_onp = array( $conceptos_sistema['conc_onp'] ); 
           
           // $afp_tiene_jubilacion = false; 



            $sql ="  SELECT  
                                concs.conc_id,
                                concs.conc_nombre,
                                concs.conc_nombrecorto,
                                concs.plati_id,
                                concs.conc_tipo,
                                concs.conc_afecto,
                                concs.conc_ecuacion_id,
                                concs.gvc_id,
                                concs.conc_displayprint,
                                COALESCE(concs.conc_max_x_mes,0) as conc_max_x_mes,

                                cpc.id_clasificador as partida_id, 
                                cpc.copc_id as afectacion_id, 
                                gr.gvc_nombre as grupo,

                                empcon.empcon_id, 
                                empcon.indiv_id, 
                                ecb.indiv_id_b


                        FROM planillas.conceptos concs 
                        LEFT JOIN planillas.empleado_concepto empcon ON empcon.conc_id = concs.conc_id AND empcon.empcon_estado = 1 AND empcon.indiv_id = ? 
                        LEFT JOIN planillas.conceptos_presu_cont cpc ON concs.conc_id = cpc.conc_id AND copc_estado = 1
                        LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = concs.gvc_id 
                        LEFT JOIN planillas.empleado_concepto_beneficiario ecb ON ecb.empcon_id = empcon.empcon_id AND ecb_estado = 1

                        WHERE concs.conc_estado = 1 AND concs.plati_id = ? AND (  concs.conc_esxdefecto = 1 OR empcon.empcon_id is not null  OR concs.conc_afecto = 0 )  
                        
                        ORDER BY conc_id   ";


             $conceptos =  $this->_CI->db->query($sql, array($empleado_id, $plati_id) )->result_array();


             foreach($conceptos as $conc)
             {  
 
              /*   if($conc['conc_id'] == $conceptos_sistema['conc_aportobli'] )
                 {

                      $afp_tiene_jubilacion = true;
                 }*/

                 $values = array( 
                                     'plaemp_id'          => $plaemp_id, 
                                     'conc_id'            => $conc['conc_id'], 
                                     'plaec_procedencia'  => ( $conc['empcon_id'] != '' ? PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR : PROCENDENCIA_CONCEPTO_DEL_TIPOPLANILLA ),
                                     'plaec_displayprint' => $conc['conc_displayprint'],
                                     'copc_id'            => ($conc['afectacion_id'] != '') ? $conc['afectacion_id'] : '0',
                                     'tarea_id'           => $tarea_id,
                                     'fuente_id'          => $fuente_id,
                                     'tipo_recurso'       => $recurso_id,
                                     'clasificador_id'    => (($clasificador_id != '') ?  $clasificador_id : trim($conc['partida_id']) ),
                                     'indiv_id_b'         => ($conc['indiv_id_b'] != '') ? $conc['indiv_id_b'] : '0',
                                     'conc_afecto'        => $conc['conc_afecto'],
                                     'conc_ecuacion_id'   => $conc['conc_ecuacion_id'],
                                     'conc_tipo'          => $conc['conc_tipo'],
                                     'gvc_id'             => $conc['gvc_id'],
                                     'conc_max_x_mes'     => $conc['conc_max_x_mes']
                                 );

 
              
                $this->_CI->planillaempleadoconcepto->registro_directo($values , $empleado_id, 0, $static_data);
                 
             }  


             /* 

                 AGREGAR VARIABLES DEL TIPO DE PLANILLA (DIAS EN EL PERIODO Y DOMINGOS EN EL PERIODO )

             */ 

                 $sql = " SELECT 
                                plava.*, 
                                vari.vari_displayprint 
                          
                          FROM planillas.planilla_variable plava
                          LEFT JOIN planillas.variables vari ON plava.vari_id = vari.vari_id
                          WHERE plava.plava_estado = 1 AND plava.pla_id = ?  ";
                
                 $variables_planilla = $this->_CI->db->query($sql, array($planilla_id) )->result_array();

                 foreach($variables_planilla as $vari)
                 {
                     
                      $data = array( 'plaemp_id'          =>  $plaemp_id, 
                                     'vari_id'            =>  $vari['vari_id'],
                                     'plaev_valor'        =>  $vari['plava_value'],
                                     'plaev_procedencia'  =>  PROCENDENCIA_VARIABLE_VALOR_XDEFECTO,
                                     'plaev_displayprint' =>  $vari['vari_displayprint']);

                    
                      $this->_CI->planillaempleadovariable->registro_directo($data, false, $static_data);

                 }

            /* 
                FIN ADD
            */

             $sql = "  SELECT 
                            fv.variable, 
                            vari.vari_displayprint, 
                            COALESCE(empvar.empvar_id,0) as empvar_id, 

                            (CASE WHEN empvar.empvar_id is not null THEN

                                  empvar.empvar_value 
                                        
                             ELSE
                                   vari.vari_valordefecto                     
                            
                             END) as  valor,

                            vari.vtd_id,
                            vari.vari_valor_metodo,
                            vtd.y_value_key
                            

                        FROM (

                                SELECT  coops_operando1 as variable
                                FROM planillas.planilla_empleado_concepto plaec  
                                LEFT JOIN planillas.conceptos_ops ops ON ops.conc_id = plaec.conc_id AND ops.coops_ecuacion_id = plaec.conc_ecuacion_id AND coops_operando1_t = 1      
                                WHERE plaec.plaemp_id = ? 

                                UNION

                                SELECT  coops_operando2 as variable
                                FROM planillas.planilla_empleado_concepto plaec  
                                LEFT JOIN planillas.conceptos_ops ops ON ops.conc_id = plaec.conc_id AND ops.coops_ecuacion_id = plaec.conc_ecuacion_id AND coops_operando2_t = 1      
                                WHERE plaec.plaemp_id = ? 
                                 
                                ORDER BY variable 

                        ) as fv
                        INNER JOIN planillas.variables vari ON fv.variable = vari.vari_id AND vari.vari_estado = 1  
                        LEFT JOIN planillas.variables_tabla_datos vtd ON vari.vtd_id = vtd.vtd_id
                        LEFT JOIN planillas.empleado_variable empvar ON vari.vari_id = empvar.vari_id  AND empvar.empvar_estado = 1 AND empvar.indiv_id = ?

                        WHERE vari.vari_procesadaxplanilla = 0 

                        ORDER BY fv.variable 
                    ";


            $variables_del_concepto = $this->_CI->db->query($sql, array($plaemp_id,$plaemp_id,$empleado_id))->result_array();      


            foreach($variables_del_concepto as $vari)
            { 

                $variable_valor = $vari['valor'];
                
                if( trim($vari['vari_valor_metodo']) != '')
                {

                    $v_params_q = array(
                                         'indiv_id' => $empleado_id
                                         );

                    $variable_valor =  call_user_func_array( array($this->_CI->variable_metodos, trim($vari['vari_valor_metodo']) ), 
                                                                       array($v_params_q) );

                    if($variable_valor === FALSE) $variable_valor = 0;
                }

                $data = array( 'plaemp_id'          =>  $plaemp_id, 
                               'vari_id'            =>  $vari['variable'],
                               'plaev_valor'        =>  $variable_valor,
                               'plaev_procedencia'  =>  ( $conc['empvar_id'] != '0' ? PROCENDENCIA_VARIABLE_VALOR_PERSONALIZADO : PROCENDENCIA_VARIABLE_VALOR_XDEFECTO ),
                               'plaev_displayprint' =>  $vari['vari_displayprint']);


                $static_data['vtd_id']      = $vari['vtd_id'];
                $static_data['y_value_key'] = $vari['y_value_key'];
 
            
                $this->_CI->planillaempleadovariable->registro_directo($data, false, $static_data);
            }   


       
             if( ADD_CONCS_RELACIONADOS_NO_VINCULADOS_TRABAJADOR == '1')
             { 
    

             }
          


              /*  CAMBIOS ESTATICOS DE SISTEMA DE PENSIONES */ 
              list($tipo_pension, $data_pension)  =  $this->_CI->persona->get_tipo_pension($empleado_id); // GET INFO PENSION
              
              $static_data = array('y_values' => array());

              if($tipo_pension == '' || trim($tipo_pension) == '0'){

                  $this->_CI->db->trans_rollback();

                  return false;

              }


              if($tipo_pension == PENSION_AFP && $data_pension['jubilado'] == '0' )
              { 
                   

                  $sql = " DELETE FROM planillas.planilla_empleado_concepto plaec
                           WHERE plaec.plaemp_id = ? AND plaec.conc_id IN (?)
                         ";  

                  $this->_CI->db->query($sql, array($plaemp_id, $conceptos_sistema['conc_onp']));      
              
                  $p =  ' ?,?,? ';

                  if($plati_id == TIPOPLANILLA_CONSCIVIL)
                  {
                     $p.=',?';
                  }

                  $sql =" SELECT conc_id, conc_ecuacion_id FROM planillas.conceptos WHERE conc_id IN (".$p.") ";
                  $rs = $this->_CI->db->query($sql, $conceptos_afps )->result_array();
                  
                  $concs_pension_ecuacion_id = array();

                  foreach ($rs as $reg)
                  {
                      $concs_pension_ecuacion_id[$reg['conc_id']] = $reg['conc_ecuacion_id']; 
                  }

                   $values_conc_pension = array( 
                                                   'plaemp_id'          => $plaemp_id, 
                                                   'plaec_procedencia'  => PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR,
                                                   'plaec_displayprint' => OPCIONIMPRESION_MOSTRAR,
                                                   'copc_id'            => 0,
                                                   'tarea_id'           => $tarea_id,
                                                   'fuente_id'          => $fuente_id,
                                                   'tipo_recurso'       => $recurso_id,
                                                   'clasificador_id'    => '',
                                                   'indiv_id_b'         => 0,
                                                   'conc_afecto'        => 1,
                                                   'conc_tipo'          => TIPOCONCEPTO_DESCUENTO,
                                                   'gvc_id'             => GRUPOVC_AFP,
                                                   'conc_max_x_mes'     => 0
                                               );
 
                 
                   // Jubilacion
                   $values_conc_pension['conc_id'] = $conceptos_sistema['conc_aportobli']; // JUbilacion
                   $values_conc_pension['conc_ecuacion_id'] = $concs_pension_ecuacion_id[$conceptos_sistema['conc_aportobli']];

                   if($values_conc_pension['conc_id'] != '' && $values_conc_pension['conc_id'] != '0')
                   {  
                      //var_dump($values_conc_pension);
                       $this->_CI->planillaempleadoconcepto->registro_directo($values_conc_pension , $empleado_id, 0, $static_data);
                   }
                  // Comision variable
                   $values_conc_pension['conc_id'] = $conceptos_sistema['conc_comvar'];
                   $values_conc_pension['conc_ecuacion_id'] = $concs_pension_ecuacion_id[$conceptos_sistema['conc_comvar']];

                   if($values_conc_pension['conc_id'] != '' && $values_conc_pension['conc_id'] != '0')
                   {
                       $this->_CI->planillaempleadoconcepto->registro_directo($values_conc_pension , $empleado_id, 0, $static_data);
                   }

 
                    if( (AFP_QUITARINVALIDEZ_AUTOMATICO == true && $edad_trabajador < AFP_EDADINVALIDEZ_LIMITE) || (AFP_QUITARINVALIDEZ_AUTOMATICO == FALSE && $data_pension['invalidez'] == '1' )  )
                    {

                      // INVALIDEZ
                       $values_conc_pension['conc_id'] = $conceptos_sistema['conc_seguros'];
                       $values_conc_pension['conc_ecuacion_id'] = $concs_pension_ecuacion_id[$conceptos_sistema['conc_seguros']];
                      $values_conc_pension['conc_tipo'] = TIPOCONCEPTO_DESCUENTO;
                       
                       if($values_conc_pension['conc_id'] != '' && $values_conc_pension['conc_id'] != '0')
                       {
                         $this->_CI->planillaempleadoconcepto->registro_directo($values_conc_pension , $empleado_id, 0, $static_data);
                       }
                     
                   }



                   if($plati_id == TIPOPLANILLA_CONSCIVIL )
                   { 
                       // APORT OBLIGATORIO CS
                        $values_conc_pension['clasificador_id'] = $clasificador_id;
                        $values_conc_pension['conc_tipo'] = TIPOCONCEPTO_APORTACION;
                        $values_conc_pension['conc_id'] = $conceptos_sistema['conc_aportobli_cc'];
                        $values_conc_pension['conc_ecuacion_id'] = $concs_pension_ecuacion_id[$conceptos_sistema['conc_aportobli_cc']];

                        if($values_conc_pension['conc_id'] != '' && $values_conc_pension['conc_id'] != '0')
                        {
                             $this->_CI->planillaempleadoconcepto->registrar($values_conc_pension , $empleado_id, 0, $static_data);
                        }
                   }

 

                   


                   $sql = "SELECT * FROM  planillas.afps_vars_tipoplanilla WHERE plati_id = ? AND avt_estado = 1 LIMIT 1 ";
                   $v_rs =  $this->_CI->db->query($sql, array($plati_id))->result_array();
                   $vars_xplati  =  $v_rs[0];
              
                   $valores_afp = $this->_CI->afp->get_porcentajes($data_pension['afp']); // Valores para la afp especificada
                   $valores_afp['comision'] = ($data_pension['afm_id'] == AFP_FLUJO) ? $valores_afp['comision'] : $valores_afp['saldo'] ;  

                   $tasa_jubilacion = ($plati_id != TIPOPLANILLA_CONSCIVIL ) ? $valores_afp['jubilacion'] : $valores_afp['jubilacion_cc'];
                   /* 
                   $this->_CI->planillaempleadovariable->set_valor($plaemp_id, $vars_xplati['vars_aportobli'] , $tasa_jubilacion   );
                   $this->_CI->planillaempleadovariable->set_valor($plaemp_id, $vars_xplati['vars_seguros'] , $valores_afp['invalides']   );
                   $this->_CI->planillaempleadovariable->set_valor($plaemp_id, $vars_xplati['vars_comvar'] , $valores_afp['comision']   );

                  */


                  $data_variables = array( 'plaemp_id'          =>  $plaemp_id,  
                                           'plaev_procedencia'  =>  PROCENDENCIA_VARIABLE_VALOR_XDEFECTO,
                                           'plaev_displayprint' =>  OPCIONIMPRESION_MOSTRAR);
              
                  $data_variables['vari_id'] = $vars_xplati['vars_aportobli'];
                  $data_variables['plaev_valor'] = $tasa_jubilacion;
                     
                  $this->_CI->planillaempleadovariable->registrar($data_variables, false, $static_data);

                  $data_variables['vari_id'] = $vars_xplati['vars_seguros'];
                  $data_variables['plaev_valor'] = $valores_afp['invalides'];
                     
                  $this->_CI->planillaempleadovariable->registrar($data_variables, false, $static_data);

                  $data_variables['vari_id'] = $vars_xplati['vars_comvar'];
                  $data_variables['plaev_valor'] = $valores_afp['comision'];
                     
                  $this->_CI->planillaempleadovariable->registrar($data_variables, false, $static_data);


            } 
            else if($tipo_pension == PENSION_SNP  && $data_pension['jubilado'] == '0' )
            {
                   
                 $delete_conceptos_params =  array($plaemp_id, $conceptos_sistema['conc_aportobli'], $conceptos_sistema['conc_comvar'], $conceptos_sistema['conc_seguros']);   

                 $p =  ' ?,?,? ';

                 if($plati_id == TIPOPLANILLA_CONSCIVIL)
                 {
                    $delete_conceptos_params[] = $conceptos_sistema['conc_aportobli_cc'];
                    $p.=',?';
                 }
   
                  $sql = " DELETE FROM planillas.planilla_empleado_concepto plaec
                           WHERE plaec.plaemp_id = ? AND plaec.conc_id IN (".$p.")  ";  

                  $this->_CI->db->query($sql, $delete_conceptos_params );      
                  
                  $sql =" SELECT conc_id, conc_ecuacion_id FROM planillas.conceptos WHERE conc_id IN ( ? ) LIMIT 1";
                  $rs = $this->_CI->db->query($sql, array($conceptos_sistema['conc_onp']) )->result_array();
                  
                  $concs_pension_ecuacion_id = array();

                  foreach ($rs as $reg)
                  {
                      $concs_pension_ecuacion_id[$reg['conc_id']] = $reg['conc_ecuacion_id']; 
                  }


                  $values_conc_pension = array(   'plaemp_id'          => $plaemp_id,  
                                                  'plaec_procedencia'  => PROCENDENCIA_CONCEPTO_DEL_TRABAJADOR,
                                                  'plaec_displayprint' => OPCIONIMPRESION_MOSTRAR,
                                                  'copc_id'            => 0,
                                                  'tarea_id'           => $tarea_id,
                                                  'fuente_id'          => $fuente_id,
                                                  'tipo_recurso'       => $recurso_id,
                                                  'clasificador_id'    => '',
                                                  'indiv_id_b'         => 0,
                                                  'conc_afecto'        => 1,
                                                  'conc_tipo'          => TIPOCONCEPTO_DESCUENTO,
                                                  'gvc_id'             => GRUPOVC_ONP,
                                                  'conc_max_x_mes'     => 0
                                              );


                  $values_conc_pension['conc_id'] = $conceptos_sistema['conc_onp'];
                  $values_conc_pension['conc_ecuacion_id'] = $concs_pension_ecuacion_id[$conceptos_sistema['conc_onp']];

                   
                  if($values_conc_pension['conc_id'] != '' && $values_conc_pension['conc_id'] != '0')
                  {
                    $this->_CI->planillaempleadoconcepto->registrar($values_conc_pension , $empleado_id, 0, $static_data);
                  }
             

             }
             else  
             {
                 // JUBILADO O NO TIENE PENSION
                   
                  $delete_conceptos_params =  array($plaemp_id, $conceptos_sistema['conc_onp'], $conceptos_sistema['conc_aportobli'], $conceptos_sistema['conc_comvar'], $conceptos_sistema['conc_seguros']);   

                  $p =  ' ?,?,?,? ';

                  if($plati_id == TIPOPLANILLA_CONSCIVIL)
                  {
                     $delete_conceptos_params[] = $conceptos_sistema['conc_aportobli_cc'];
                     $p.=',?';
                  }
                  
                  $sql = " DELETE FROM planillas.planilla_empleado_concepto plaec
                           WHERE plaec.plaemp_id = ? AND plaec.conc_id IN (".$p.")  ";  

                  //  var_dump($delete_conceptos_params);
                     

                  $this->_CI->db->query($sql,  $delete_conceptos_params );     

             }
              
         /*    if ($this->_CI->db->trans_status() === FALSE)
             {
                $this->_CI->db->trans_rollback();
                $ok = false;
             }
             else
             {
                $this->_CI->db->trans_commit();
                $ok = true;
             }   */

         }
         else
         {
            
          
            $plaemp_id  = $registro_empleado['plaemp_id'];
            $plaemp_key = $registro_empleado['plaemp_key']; 


            if(trim($params['fuente_especifica']) != '') // ESTO VIENE DESDE EL MIGRADO
            {  
               list($fuente_id,  $recurso_id) = explode('-',$params['fuente_especifica'] );
 
               $sql = " UPDATE planillas.planilla_empleados plaemp 
                         SET fuente_id = ?, tipo_recurso = ?,  plaemp_afectacion_actualizada = 1
                         WHERE plaemp_id = ? 
                       ";

               $this->_CI->db->query($sql, array($fuente_id, $recurso_id, $plaemp_id));

            }



         }
         
             
          
         if ($this->_CI->db->trans_status() === FALSE)  
         {
            $this->_CI->db->trans_rollback();

            return ($return_id) ? array(false,false) : false;

         }
         else
         {
            $this->_CI->db->trans_commit();
            
            return ($return_id) ?  array($plaemp_id, $plaemp_key) : true;
         }   

/*

        if($return_id)
        {
           if($ok == FALSE) return array(false,false);
           
           return  array($plaemp_id, $plaemp_key);
        }        
        else
        {
 
            return $ok;
        }*/

     
           
    }
    
     
 
    
    public function get_planillaempleado_variables($plaemp_id, $personalizable = true, $for_impresion = false)
    {
        
         $p = array($plaemp_id);
        
         $sql = " SELECT pev.*,
                         vars.vari_nombre as variable,
                         vars.vari_nombrecorto as nombre_corto,
                         vars.vari_descripcion,
                         vars.vari_personalizable,
                         
                         ( CASE WHEN gvc.gvc_nombre is null THEN 
                             '------- '
                                ELSE  
                                     gvc.gvc_nombre
                                END   ) as grupo_nombre,


                         uni.vau_nombre as unidad,
                         uni.vau_abrev as unidad_abrev     

                         FROM  planillas.planilla_empleado_variable pev 
                         LEFT JOIN planillas.variables vars ON pev.vari_id = vars.vari_id
                         LEFT JOIN planillas.grupos_vc gvc ON  gvc.gvc_id =  vars.gvc_id
                         LEFT JOIN planillas.variable_unidad uni ON vars.vau_id = uni.vau_id
                  WHERE pev.plaev_estado = 1 AND pev.plaemp_id = ? ";
 
         
         if($personalizable)
         {
             $sql.=" AND ( vars.vari_personalizable = ".VARIABLE_PERSONALIZABLE_PLANILLA."  OR vars.vari_personalizable =  ".VARIABLE_PERSONALIZABLE_GESTIONDATOS."  OR  vars.vari_personalizable =  ".VARIABLE_PERSONALIZABLE_AMBOS." )";
         }
         else
         {
              $sql.=" AND ( vars.vari_personalizable = ".VARIABLE_PERSONALIZABLE_NO." )";
         }


          if($for_impresion) $sql.=" AND ( plaev_displayprint = ".OPCIONIMPRESION_MOSTRAR." OR ( plaev_displayprint = ".OPCIONIMPRESION_MAYORACERO." AND plaev_valor > 0 )  ) ";


         $sql.="  ORDER BY vari_personalizable desc, vari_orden asc, vars.gvc_id, vars.vari_nombre ";
        
         return $this->_CI->db->query($sql, $p )->result_array();
         
    }

    
    public function get_planillaempleado_conceptos($plaemp_id, $tipo = 0, $solo_marcados = 0, $for_impresion = false)
    {
      
         $p = array($plaemp_id);
        
         $sql = " SELECT pec.*,
                         concs.conc_nombre as concepto,
                         concs.conc_nombrecorto as nombre_corto,
                         concs.conc_descripcion,
                         concs.conc_obligatorio,

                       ( CASE WHEN gr.gvc_nombre is null THEN 
                             '------- '
                        ELSE  
                             gr.gvc_nombre
                        END   ) as grupo_nombre,

                        ( CASE WHEN qconf.qcco_id is null THEN 
                              '0'
                         ELSE  
                              '1'
                         END   ) as concepto_quinta,
 

                         (  ind.indiv_appaterno ||' '|| ind.indiv_apmaterno || ' ' ||  ind.indiv_nombres || ' ('|| ind.indiv_dni || ')' ) as beneficiario 
 

                         FROM planillas.planilla_empleado_concepto pec 
                         LEFT JOIN planillas.conceptos concs ON pec.conc_id = concs.conc_id
                         LEFT JOIN planillas.grupos_vc gr ON gr.gvc_id = concs.gvc_id  
                         LEFT JOIN public.individuo ind ON pec.indiv_id_b != null AND pec.indiv_id_b = ind.indiv_id 
                         LEFT JOIN planillas.quintacategoria_configuracion qconf ON pec.conc_id = qconf.conc_grati AND qcco_estado = 1
                  WHERE pec.plaec_estado = 1 AND pec.plaemp_id = ? ";
         
       if($solo_marcados==1) $sql.="  AND pec.plaec_marcado = 1 ";

         if($tipo != 0)
         {
             $sql.=" AND pec.conc_tipo = ? ";
             $p[] = $tipo;
         }
         
         if($tipo ==  0)
         {
             $sql.=" AND pec.conc_tipo = 0 AND pec.conc_afecto = 0 ";
            
         }


       if($for_impresion){

            $sql.=" AND  pec.conc_afecto = 1  ";    
            $sql.=" AND ( plaec_displayprint = ".OPCIONIMPRESION_MOSTRAR." OR ( plaec_displayprint = ".OPCIONIMPRESION_MAYORACERO." AND plaec_value > 0 )  ) ";
 
       } 
      
       $sql.=" ORDER BY concs.gvc_id, concs.conc_tipo, concs.conc_nombre   ";

         return $this->_CI->db->query($sql, $p )->result_array();
        
    }


    public function quitar_empleado($detalle_id = '0', $all_by_indiv = false, $pla_id = 0){

         $this->_CI->db->trans_begin();

         if($all_by_indiv == false )
         { 
                  $sql = "DELETE FROM planillas.planilla_empleado_concepto WHERE  plaemp_id = ? ";
                  $this->_CI->db->query($sql,array($detalle_id));
                  $sql = "DELETE FROM planillas.planilla_empleado_variable  WHERE plaemp_id = ? ";
                  $this->_CI->db->query($sql,array($detalle_id));
                  $sql = "DELETE FROM planillas.planilla_empleados WHERE plaemp_id = ?  ";
                  $this->_CI->db->query($sql,array($detalle_id));

                  $sql = "UPDATE planillas.hojaasistencia_emp_dia SET pla_id = 0, plaemp_id = 0, hoaed_importado = 0, plaasis_id = 0 WHERE plaemp_id = ? ";
                  $this->_CI->db->query($sql, array($detalle_id));
         }         
         else
         {

                 $sql = "DELETE FROM planillas.planilla_empleado_concepto WHERE  plaemp_id  IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE indiv_id = ? AND pla_id = ? ) ";
                 $this->_CI->db->query($sql,array($detalle_id, $pla_id));
                 $sql = "DELETE FROM planillas.planilla_empleado_variable  WHERE plaemp_id  IN (SELECT plaemp_id FROM planillas.planilla_empleados WHERE indiv_id = ? AND pla_id = ? ) ";
                 $this->_CI->db->query($sql,array($detalle_id, $pla_id));
                 $sql = "DELETE FROM planillas.planilla_empleados WHERE indiv_id = ? AND pla_id = ?  ";
                 $this->_CI->db->query($sql,array($detalle_id, $pla_id));

                 $sql = "UPDATE planillas.hojaasistencia_emp_dia SET pla_id = 0, plaemp_id = 0, hoaed_importado = 0, plaasis_id = 0 WHERE indiv_id = ? AND pla_id = ? ";
                 $this->_CI->db->query($sql,array($detalle_id, $pla_id));
 
         }


        $ok =false;
        
        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
        }
        else
        {
            $this->_CI->db->trans_commit();
            $ok = true;
        }    
           
        
        return $ok;

    }
    


    public function add_conceptos_no_vinculados()
    {
 

        // AGREGANDO LOS CONCEPTOS NECESARIOS QUE NO TIENE EL TRABAJADOR
        $sql = "SELECT *,cpc.copc_id as afectacion_id, cpc.id_clasificador as partida_id

                FROM ( 
                   SELECT (rs_union.conc_id) 
                           FROM ( 
                               
                                       (
                                          
                                           SELECT coops_operando1 as conc_id 
                                           FROM planillas.planilla_empleado_concepto pec  
                                           LEFT JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id
                                           LEFT JOIN planillas.conceptos_ops co ON co.conc_id = conc.conc_id AND co.coops_estado = 1 AND co.coops_operando1_t = 2 
                                           WHERE pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.plaemp_id = ?
                                           ORDER BY conc_id
                                            
                                       )
                                        UNION (

                                           SELECT coops_operando2 as conc_id  
                                           FROM planillas.planilla_empleado_concepto pec  
                                           LEFT JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id
                                           LEFT JOIN planillas.conceptos_ops co ON co.conc_id = conc.conc_id AND co.coops_estado = 1 AND co.coops_operando2_t = 2 
                                           WHERE  pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.plaemp_id = ?
                                           ORDER BY conc_id    
                                       ) 

                           ) as rs_union    

                   WHERE rs_union.conc_id NOT IN (SELECT conc_id 
                                                  FROM planillas.planilla_empleado_concepto pec2 
                                                  WHERE pec2.plaemp_id = ? )
               ) as data 
               LEFT JOIN  planillas.conceptos_presu_cont cpc ON data.conc_id = cpc.conc_id AND copc_estado = 1        

        ";

        $conceptos =  $this->_CI->db->query($sql, array($plaemp_id,$plaemp_id,$plaemp_id))->result_array();


        foreach($conceptos as $conc){
            
            $values = array(
                            'plaemp_id' => $plaemp_id, 
                            'conc_id' => $conc['conc_id'],
                            'plaec_value' => '0',
                            'plaec_procedencia' => PROCENDENCIA_CONCEPTO_POR_ESTAR_RELACIONADO,
                            'plaec_displayprint' => $conc['conc_displayprint'],
                            'copc_id' =>  ($conc['afectacion_id'] != '') ? $conc['afectacion_id'] : '0',
                            'tarea_id' => $tarea_id,
                            'fuente_id' => $fuente_id,
                            'tipo_recurso' => $recurso_id,
                            'clasificador_id'    =>  ( ($clasificador_id != '') ?  $clasificador_id : trim($conc['partida_id']) ),

                            );
             
           $this->_CI->planillaempleadoconcepto->registrar($values, $empleado_id, '2', $static_data);
            
        }

    }


    public function get_afectacion_by_individ($pla_id, $indiv_id)
    {

        $sql = " SELECT empre_id, tarea_id, fuente_id, tipo_recurso 
                 FROM planillas.planilla_empleados plaemp
                 WHERE plaemp.pla_id = ? AND plaemp.indiv_id = ? 
                 GROUP BY empre_id, tarea_id, fuente_id, tipo_recurso
               ";

        $rs = $this->_CI->db->query($sql, array($pla_id, $indiv_id))->result_array();

        return $rs[0];
    }

    /*
            retorna un recordset con los registros de detalle que le pertenecen al trabajador
     */
    public function get_categorias_planilla_trabajador( $planilla_id, $indiv_id )
    {


        $sql =" SELECT 
                       plaemp_id,plaemp_key, 
                       indiv_id, 
                       pe.platica_id, 
                       ocus.platica_nombre as tipo_nombre 

                    FROM planillas.planilla_empleados pe
                    INNER JOIN planillas.planilla_tipo_categoria ocus ON pe.platica_id = ocus.platica_id AND ocus.platica_estado = 1 
                    WHERE pe.plaemp_estado =  1 AND  pe.pla_id = ? AND  indiv_id = ?  

                    ORDER BY platica_id
               ";

      $rs = $this->_CI->db->query($sql,array( $planilla_id, $indiv_id))->result_array();

      return $rs;

    }  


    public function get_first_plaempKey($indiv_id, $planilla_id)
    {

        $sql =" SELECT plaemp_key 
                FROM planillas.planilla_empleados pe 
                WHERE pe.pla_id = ? AND pe.indiv_id = ?  AND pe.plaemp_estado =  1  
                ORDER BY plaemp_id 
                LIMIT 1 ";

        $rs = $this->_CI->db->query($sql,array($planilla_id, $indiv_id))->result_array();

        return $rs[0]['plaemp_key'];

    }

    public function registro_boletas($indiv_id, $params = array() )
    {


            $sql = " SELECT

                         pla.pla_anio,
                         pla.pla_mes,
                         pla.pla_key,
                         pla.pla_fecini,
                         pla.pla_fecfin,
                         ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                         data.plaemp_key,
                         indiv.indiv_dni,
                         ( indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                         plati.plati_nombre,
                         platica.platica_nombre as categoria,
                         ocu.ocu_nombre as ocupacion,
                         data.plaemp_ocupacion_label as ocupacion_label,
                         data.ingresos,
                         data.descuentos,
                         (data.ingresos - data.descuentos) as neto,
                         data.aportacion

                         FROM ( 
 
                             SELECT  
                                    pla.pla_id,
                                    pla.plati_id,
                                    plaemp.plaemp_id,
                                    plaemp.plaemp_key,
                                    plaemp.indiv_id,
                                    plaemp.platica_id,
                                    plaemp.ocu_id,
                                    plaemp.plaemp_ocupacion_label, 

                                    plaec.tarea_id,
                                    plaec.fuente_id,
                                    plaec.tipo_recurso,
                                  
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

                              FROM planillas.planilla_empleados plaemp 
                              INNER JOIN planillas.planillas pla On plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1
                              INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id = ".ESTADO_PLANILLA_CERRADA."   
                              INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   
        

                              WHERE plaemp.plaemp_estado = 1  AND plaemp.indiv_id = ?
  
                              GROUP BY  pla.pla_id,
                                        pla.plati_id,
                                        plaemp.plaemp_id,
                                        plaemp.plaemp_key,
                                        plaemp.indiv_id,
                                        plaemp.platica_id,
                                        plaemp.ocu_id,
                                        plaemp.plaemp_ocupacion_label,                                      
                                        plaec.tarea_id,
                                        plaec.fuente_id,
                                        plaec.tipo_recurso
                              
                              ORDER BY  pla.pla_id, plaemp.plaemp_id, plaemp.indiv_id,   plaemp.platica_id
                      
                ) as data 
                INNER JOIN planillas.planillas pla ON data.pla_id = pla.pla_id  
                LEFT JOIN planillas.planilla_tipo plati ON data.plati_id = plati.plati_id 
                INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                LEFT JOIN  planillas.planilla_tipo_categoria platica ON data.platica_id = platica.platica_id 
                LEFT JOIN planillas.ocupacion ocu ON data.ocu_id = ocu.ocu_id 
                LEFT JOIN sag.tarea ON data.tarea_id = tarea.tarea_id 

                ORDER BY pla.pla_id desc, pla_anio, pla_mes, indiv_appaterno, indiv_apmaterno, indiv_nombres
            ";

 
            $rs = $this->_CI->db->query($sql, array($indiv_id) )->result_array();

            return $rs;
    }

    public function get_registro_individuo()
    {

        $sql = " ";

    }
    

    public function actualizar_ocupacion($params = array())
    {
       
       // plaemp_ocupacion_label


        $values = array();

        if($params['modo'] == '1')
        {
            $field = 'ocu_id';
        }
        else
        {
            $field = 'plaemp_ocupacion_label';
        }

        $values[] = $params['valor'];

        $sql = " UPDATE planillas.planilla_empleados SET ".$field." = ? WHERE plaemp_id = ?  ";
        
        $values[] = $params['id'];

        $ok = $this->_CI->db->query($sql, $values);
          
        return ($ok) ? true : false;
    }


}