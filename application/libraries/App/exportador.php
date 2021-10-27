<?php

class exportador extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => '',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_SCHEMA = '';
    protected $_TABLE = '';
    protected $_PREF_TABLE= ''; 
     
    public function __construct()
    {
          
        parent::__construct();
          
    }


    public function getReportes($tipo = 0)
    {
 
        $sql =" SELECT  * FROM planillas.reportes 
                WHERE rep_estado = 1 AND reptip_id = ? 
                ORDER BY rep_id ";

        $rs =   $this->_CI->db->query($sql , array($tipo))->result_array(); 
     
        return $rs;
    }
     

    
    public function sin_cuenta_bancaria($params)
    { 
 
        $ESTADO_AFECTO_PLANILLA = ESTADOPLANILLA_MINIMO_BANCOS;
 
        if(sizeof($params['planillas']) > 0 )
        {
             $in_planillas = implode(',', $params['planillas']);
        }
        else
        {
            return array();
        }
         
        $sql = "  SELECT  ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                          indiv.indiv_appaterno, 
                          indiv.indiv_apmaterno, 
                          indiv.indiv_nombres, 
                          indiv.indiv_dni,
                          datos.*,
                          (tarea.sec_func || '-' || tarea.tarea_nro) as tarea_codigo,

                          (datos.fuente_id || '-' || datos.tipo_recurso ) as fuente_codigo,

                          (datos.ingresos - datos.descuentos) as deposito   

                  FROM (  
        
                      SELECT 
                          pla.pla_id,
                          plaemp.indiv_id,

                          plaemp.tarea_id,
                          plaemp.fuente_id,
                          plaemp.tipo_recurso,


                          SUM(CASE WHEN conc_tipo = ".TIPOCONCEPTO_INGRESO." THEN 
                                    plaec_value
                              ELSE 
                                    0
                              END) as ingresos,
        
                          SUM(CASE WHEN conc_tipo = ".TIPOCONCEPTO_DESCUENTO."  THEN 
                                    plaec_value
                              ELSE 
                                    0
                              END) as descuentos

                     FROM planillas.planilla_empleado_concepto plaec 
                     INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 AND pecd_id = 0
                     INNER JOIN planillas.planillas pla ON pla.pla_id = plaemp.pla_id AND pla.pla_estado = 1 ";

                     if( sizeof($params['planillas']) > 0 ) $sql.= " AND pla.pla_id IN (".$in_planillas.") ";
        
        $sql.=" 
                     INNER JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1 AND  movs.plaes_id =  ".$ESTADO_AFECTO_PLANILLA." 
        
                     WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.conc_afecto = 1

                     GROUP BY pla.pla_id,plaemp.indiv_id, plaemp.tarea_id, plaemp.fuente_id,  plaemp.tipo_recurso

                 ) as datos  
                 
                 INNER JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id 
                 INNER JOIN planillas.planillas pla ON datos.pla_id = pla.pla_id 
                 LEFT JOIN sag.tarea ON datos.tarea_id = tarea.tarea_id

                 ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
        
               ";

        $rs = $this->_CI->db->query($sql, array($params['banco'] ))->result_array();

        return $rs;

    }



    public function bancos($params = array())
    {

        $ESTADO_AFECTO_PLANILLA =  ESTADOPLANILLA_MINIMO_BANCOS;


        if(sizeof($params['planillas']) > 0 )
        {
             $in_planillas = implode(',', $params['planillas']);
        }
        else
        {
            return array();
        }
 

        $sql = "  SELECT    
                         ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                          indiv.indiv_appaterno, 
                          indiv.indiv_apmaterno, 
                          indiv.indiv_nombres, 
                          indiv.indiv_dni,
                          datos.*,
                          (datos.ingresos - datos.descuentos) as deposito ,

                          ( tarea.sec_func || ' - ' || tarea.tarea_nro )  as tarea_codigo,

                          ( datos.fuente_id || ' ' || datos.tipo_recurso ) as fuente_codigo


                  FROM (  
 
                      SELECT 
                          
                          pla.pla_id,
                          plaemp.indiv_id,
                          plaemp.tipo_recurso,
                          plaemp.fuente_id, 
                          plaemp.tarea_id,
                          eb.ebanco_nombre, 
                          pcd.pecd_cuentabancaria, 

                          SUM(CASE WHEN conc_tipo = ".TIPOCONCEPTO_INGRESO." THEN 
                                    plaec_value
                              ELSE 
                                    0
                              END) as ingresos,
 
                          SUM(CASE WHEN conc_tipo = ".TIPOCONCEPTO_DESCUENTO."  THEN 
                                    plaec_value
                              ELSE 
                                    0
                              END) as descuentos

                     FROM planillas.planilla_empleado_concepto plaec 
                     INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                     INNER JOIN rh.persona_cuenta_deposito pcd ON plaemp.pecd_id = pcd.pecd_id AND plaemp.indiv_id = pcd.pers_id AND pcd.ebanco_id = ? 
                     INNER JOIN public.entidades_bancarias eb ON pcd.ebanco_id = eb.ebanco_id
                     INNER JOIN planillas.planillas pla ON pla.pla_id = plaemp.pla_id AND pla.pla_estado = 1 ";

                     if( sizeof($params['planillas']) > 0 ) $sql.= " AND pla.pla_id IN (".$in_planillas.") ";
 
        $sql.=" 
                     INNER JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1 AND  movs.plaes_id =  ".$ESTADO_AFECTO_PLANILLA." 
 
                     WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.conc_afecto = 1

                     GROUP BY pla.pla_id, plaemp.indiv_id, plaemp.tipo_recurso, plaemp.fuente_id, plaemp.tarea_id, eb.ebanco_nombre, pcd.pecd_cuentabancaria

                     ORDER BY  pla.pla_id, plaemp.indiv_id, plaemp.tipo_recurso, plaemp.fuente_id, plaemp.tarea_id, eb.ebanco_nombre 

                 ) as datos  
                 
                 INNER JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id 
                 INNER JOIN planillas.planillas pla ON datos.pla_id = pla.pla_id 
                 LEFT JOIN sag.tarea ON datos.tarea_id = tarea.tarea_id 

                 ORDER BY pla_codigo, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
 
               ";

        $rs = $this->_CI->db->query($sql, array($params['banco']))->result_array();

        return $rs;
    }


    /* PAGOS A BENEFICIARIOS JUDICIALES */
    public function beneficiarios_judiciales($params)
    {
 
       $ESTADO_AFECTO_PLANILLA = ESTADO_PLANILLA_CERRADA;

       if(sizeof($params['planillas']) > 0 )
       {
            $in_planillas = implode(',', $params['planillas']);
       }
       else
       {
           return array();
       }
       

       $query = array();

       $sql = "    SELECT
                       datos.fuente_id, datos.tipo_recurso,  
                       (datos.fuente_id || '-' || datos.tipo_recurso) as fuente, plasiaf.siaf,

                       pla.pla_id,

                        ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as planilla, 

                        (pla.pla_fecini || ' - ' ||  pla.pla_fecfin ) as periodo,

                       (indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador, 
                       indiv.indiv_dni as trabajador_dni,
                       concs.conc_nombre as concepto, 
                       (bene.indiv_appaterno || ' ' || bene.indiv_apmaterno || ' ' || bene.indiv_nombres ) as beneficiario, 
                       bene.indiv_dni as beneficiario_dni,
                       datos.total,
                       banco.ebanco_nombre as banco,
                       cuenta.pecd_cuentabancaria as cuenta,
                       (tarea.sec_func || '-' ||tarea.tarea_nro) as tarea_codigo,
                       tarea.tarea_nombre
                       
                    FROM ( 

                          SELECT plaec.fuente_id, plaec.tipo_recurso, plaemp.tarea_id, pla.pla_id, plaemp.indiv_id,  plaec.conc_id, ecb.indiv_id_b, SUM(plaec.plaec_value) as total

                             FROM planillas.planilla_empleado_concepto plaec
                             INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 
                             INNER JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id AND indiv.indiv_estado = 1 
                             INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 ";

                      if( sizeof($params['planillas']) > 0 ) $sql.= " AND pla.pla_id IN (".$in_planillas.") ";

          $sql.=" 
                             INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado =1 AND plamo.plaes_id = ".$ESTADO_AFECTO_PLANILLA."
                             INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.gvc_id = ".GRUPOVC_RETENCIONJUDICIAL."
                             LEFT JOIN planillas.empleado_concepto empcon ON empcon.conc_id = concs.conc_id AND plaemp.indiv_id = empcon.indiv_id  AND empcon_estado = 1
                             LEFT JOIN planillas.empleado_concepto_beneficiario ecb ON empcon.empcon_id = ecb.empcon_id AND ecb_estado = 1
                              
                          WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND  plaec.plaec_value > 0 AND plaec.conc_afecto = 1

                          GROUP BY  plaec.fuente_id, plaec.tipo_recurso, plaemp.tarea_id, pla.pla_id, plaemp.indiv_id, plaec.conc_id, ecb.indiv_id_b  
                           
                          ORDER BY plaec.fuente_id, plaec.tipo_recurso, plaemp.tarea_id

                    ) as datos
                    LEFT JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id
                    LEFT JOIN public.individuo bene ON  datos.indiv_id_b = bene.indiv_id 
                    LEFT JOIN rh.persona_cuenta_deposito cuenta  ON cuenta.pers_id = bene.indiv_id AND cuenta.pecd_estado = 1  
                    LEFT JOIN public.entidades_bancarias banco ON cuenta.ebanco_id = banco.ebanco_id 
                    LEFT JOIN planillas.conceptos concs ON datos.conc_id = concs.conc_id  
                    LEFT JOIN planillas.planillas pla ON datos.pla_id = pla.pla_id
                    LEFT JOIN sag.tarea ON datos.tarea_id = tarea.tarea_id
					LEFT JOIN planillas.planilla_siaf plasiaf ON datos.pla_id=plasiaf.pla_id and datos.fuente_id=plasiaf.fuente_id and datos.tipo_recurso=plasiaf.tipo_recurso

                    WHERE true = true 

                    ";

                    if($params['banco']!='' && $params['banco'] != '0')
                    {
                         $sql.=" AND cuenta.ebanco_id = ".$params['banco'];
                    }

         $sql.=" 
                    ORDER BY datos.fuente_id, datos.tipo_recurso, trabajador, conc_nombre,  beneficiario
 

              ";      

        $query[] = $params['anio'];
        $query[] = $params['mes'];

       $rs = $this->_CI->db->query($sql, $query)->result_array();
 
       return $rs;
 

    }



    public function afp($params = array())
    {

          $ESTADO_AFECTO_PLANILLA = ESTADOPLANILLA_MINIMO_AFP;
  
          $sql = " SELECT * 
                   FROM planillas.concepto_enlace 
                   WHERE gvc_id IN (?,?) AND coen_estado = 1  
                   ORDER BY coen_orden ";

          $rs = $this->_CI->db->query($sql, array(GRUPOVC_AFP, GRUPOVC_AFP_APORTE) )->result_array();

          $conc_nombres_header='';   

          foreach($rs as $k => $con)
          {

            if($k>0)  $conc_nombres_header.=',';
            $conc_nombres_header.=' "'.trim($con['coen_nombre']).'" text'; 

          }
   
         $afp  = trim($params['afp']);
         $anio = trim($params['anio']);
         $mes  = trim($params['mes']);
  

         if(sizeof($params['planillas']) > 0 )
         {
              $in_planillas = implode(',', $params['planillas']);
         }
         else
         {

             return array();
         }
      
                                                 
         $sql = "  SELECT indiv.indiv_dni, 
                          indiv.indiv_appaterno, 
                          indiv.indiv_apmaterno, 
                          indiv.indiv_nombres, 
                          plati.plati_nombre as regimen, 
                          peaf_codigo, 
                          afp.afp_id, 
                          afp.afp_nombre, 
                          indiv.indiv_dni,  

                         ( CASE WHEN date_part('month', persla_fechacese) = ".$mes." AND date_part('year', persla_fechacese) = ".$anio." AND persla_fechacese is not null THEN 

                                2
                           WHEN  date_part('month', persla_fechaini) = ".$mes." AND date_part('year', persla_fechaini) = ".$anio." THEN

                                1   
                           ELSE 
                                null 
                         
                            END ) as tipo,

                         (  CASE WHEN date_part('month', persla_fechacese) = ".$mes." AND date_part('year', persla_fechacese) = ".$anio."  AND persla_fechacese is not null THEN 

                                persla_fechacese

                            WHEN  date_part('month', persla_fechaini) = ".$mes." AND date_part('year', persla_fechaini) = ".$anio."  THEN

                                persla_fechaini 

                            ELSE 
                            
                                 null 
                         
                         END ) as fecha,     

                         ( CASE WHEN data.afp_c = '1' THEN

                                'C'

                           ELSE 

                                'N'
                           END ) as cs,

                          data.* 
              
                FROM ( 
                       SELECT *  FROM  crosstab( '
                       
                                        SELECT   ( plaemp.indiv_id || ''_'' || plati.plati_afp_c ) as indice,
                                                 plaemp.indiv_id, 
                                                 plati.plati_afp_c,
                                                 concs.coen_id, 
                                                 SUM(pec.plaec_value) as monto  

                                        FROM  planillas.planilla_empleado_concepto pec
                                        INNER JOIN (

                                           SELECT concs.conc_id,concs.coen_id 
                                           FROM planillas.conceptos concs 
                                           WHERE concs.gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") AND concs.conc_estado = 1 
                                           ORDER BY concs.plati_id, conc_afecto asc, concs.conc_nombre 

                                        ) as concs ON concs.conc_id = pec.conc_id 
                                        LEFT JOIN planillas.concepto_enlace coen ON coen.coen_id = concs.coen_id 
                                        INNER JOIN planillas.planilla_empleados plaemp ON pec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 
                                        LEFT JOIN rh.persona_situlaboral perlsa ON plaemp.persla_id = perlsa.persla_id
                                        INNER JOIN rh.persona_pension peaf ON plaemp.peaf_id = peaf.peaf_id AND peaf.pentip_id = ".PENSION_AFP." ";

                                       

                                 $sql.=" INNER JOIN planillas.planilla_tipo plati ON perlsa.plati_id = plati.plati_id  ";

                                        if( trim($params['tipogasto']) != '')  $sql.=" AND plati.plati_afp_tipo = \'".trim($params['tipogasto'])."\'";
                                        


                                 $sql.=" INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = ''".$anio."''  AND pla_mes = ''".$mes."'' ";

                                        if( sizeof($params['planillas']) > 0 ) $sql.= " AND pla.pla_id IN (".$in_planillas.") ";

  
                                 $sql.=" INNER JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1 AND  movs.plaes_id = ".ESTADOPLANILLA_MINIMO_AFP."  
                                         WHERE  pec.plaec_estado = 1 AND pec.plaec_marcado = 1 
                                         GROUP BY plaemp.indiv_id, plati.plati_afp_c, concs.coen_id, coen.coen_orden                       
                                         ORDER BY plaemp.indiv_id, plati.plati_afp_c, coen.coen_orden    
                           ', 
                           ' SELECT coen_id FROM planillas.concepto_enlace coen 
                             WHERE gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") AND coen_estado = 1 ORDER BY coen_orden ' 
                           )AS(
                             indice text,
                             indiv_id int,
                             afp_c text,

                             ".$conc_nombres_header."  

                           )
                     ) as data 
                     INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                     INNER JOIN rh.persona_situlaboral persla ON data.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                     INNER JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id
                     INNER JOIN rh.persona_pension peaf ON peaf.pers_id = data.indiv_id AND peaf.peaf_estado = 1 AND peaf.pentip_id = ".PENSION_AFP."  AND peaf_jubilado = 0
                     INNER JOIN rh.afp ON peaf.afp_id = afp.afp_id 

                      ";

                     if($afp != '' && $afp != '0')   $sql.=" WHERE peaf.afp_id = ".$afp;
                     

                $sql.="  

                     ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres
                 ";

                

         $rs = $this->_CI->db->query($sql, $query)->result_array();

           
        return $rs;

           
    }
 
    public function afp_($params)
    {   
  
         $ESTADO_AFECTO_PLANILLA = ESTADOPLANILLA_MINIMO_AFP;
 
         $sql = " SELECT * FROM planillas.concepto_enlace 
                  WHERE gvc_id = ? AND coen_estado = 1  
                  ORDER BY coen_orden ";

         $rs = $this->_CI->db->query($sql, array(GRUPOVC_AFP) )->result_array();

         $conc_nombres_header='';   

         foreach($rs as $k => $con)
         {

           if($k>0)  $conc_nombres_header.=',';
           $conc_nombres_header.=' "'.trim($con['coen_nombre']).'" text'; 

         }
  
        $afp  = trim($params['afp']);
        $anio = trim($params['anio']);
        $mes  = trim($params['mes']);
 

        if(sizeof($params['planillas']) > 0 )
        {
             $in_planillas = implode(',', $params['planillas']);
        }
        else
        {

            return array();
        }
 


        $sql = "  SELECT indiv.indiv_dni, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, afp.afp_nombre, indiv.indiv_dni, data.* 
                     FROM ( 

                       SELECT *  FROM  crosstab( '
                      
                                      SELECT ( plaemp.indiv_id || ''-'' || plati.plati_id ) as indice, 
                                              plaemp.indiv_id, 
                                              peaf.afp_id, 
                                              peaf.peaf_codigo,  
                                              plati.plati_id, 
                                              plati.plati_nombre,

                                             ( CASE WHEN date_part(\'month\', persla_fechacese) = ".$mes." AND date_part(\'year\', persla_fechacese) = ".$anio." AND persla_fechacese is not null THEN 

                                                    2
                                               WHEN  date_part(\'month\', persla_fechaini) = ".$mes." AND date_part(\'year\', persla_fechaini) = ".$anio." THEN

                                                    1   
                                               ELSE 
                                                    null 
                                             
                                                END ) as tipo_ic,

                                             (  CASE WHEN date_part(\'month\', persla_fechacese) = ".$mes." AND date_part(\'year\', persla_fechacese) = ".$anio."  AND persla_fechacese is not null THEN 

                                                    persla_fechacese

                                                WHEN  date_part(\'month\', persla_fechaini) = ".$mes." AND date_part(\'year\', persla_fechaini) = ".$anio."  THEN

                                                    persla_fechaini 

                                                ELSE 
                                                
                                                     null 
                                             
                                             END ) as fecha_ic,     

                                             ( CASE WHEN plati.plati_afp_c = 1 THEN
    
                                                    \'c\'

                                               ELSE 
    
                                                    null
                                               END ) as cs,

                       
                                               concs.coen_id, SUM(pec.plaec_value) as monto  

                                                     FROM  planillas.planilla_empleado_concepto pec
                                                     INNER JOIN (

                                                        SELECT concs.conc_id,concs.coen_id 
                                                        FROM planillas.conceptos concs 
                                                        WHERE concs.gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") AND concs.conc_estado = 1 
                                                        ORDER BY concs.plati_id, conc_afecto asc, concs.conc_nombre 

                                                     ) as concs ON concs.conc_id = pec.conc_id 
                                                     LEFT JOIN planillas.concepto_enlace coen ON coen.coen_id = concs.coen_id 
                                                     INNER JOIN planillas.planilla_empleados plaemp ON pec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 
                                                     LEFT JOIN rh.persona_situlaboral perlsa ON plaemp.persla_id = perlsa.persla_id
                                                     INNER JOIN rh.persona_pension peaf ON plaemp.peaf_id = peaf.peaf_id AND peaf.pentip_id = ".PENSION_AFP." ";


                                                        if($afp != '' && $afp != '0')
                                                        {
                                                                $sql.=" AND peaf.afp_id = ".$afp;
                                                        }


                                    $sql.=" 
                                                     INNER JOIN planillas.planilla_tipo plati ON perlsa.plati_id = plati.plati_id
                                                      ";


                                                       if( trim($params['tipogasto']) != '')
                                                       {
                                                           $sql.=" AND plati.plati_afp_tipo = \'".trim($params['tipogasto'])."\'";
                                                       }


                                     $sql.="                   

                                                     INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 AND pla_anio = \'".$anio."\'  AND pla_mes = \'".$mes."\' ";

                                                        

                                                        if( sizeof($params['planillas']) > 0 ) $sql.= " AND pla.pla_id IN (".$in_planillas.") ";




 
                                    $sql.="         
                                                     INNER JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1 AND  movs.plaes_id = ".$ESTADO_AFECTO_PLANILLA."  
                                                     INNER JOIN planillas.planilla_tipo plati On pla.plati_id = plati.plati_id 
                                                WHERE  pec.plaec_estado = 1 AND pec.plaec_marcado = 1 
                                                GROUP BY plaemp.indiv_id, peaf.afp_id, peaf.peaf_codigo, plati.plati_id, plati.plati_nombre,  tipo_ic, fecha_ic, cs, concs.coen_id, coen.coen_orden                       
                                                ORDER BY plaemp.indiv_id, plati.plati_id, coen.coen_orden
                                                ',

                                                ' SELECT coen_id FROM planillas.concepto_enlace coen WHERE gvc_id IN (".GRUPOVC_AFP.",".GRUPOVC_AFP_APORTE.") AND coen_estado = 1 ORDER BY coen_orden ' 
                                                 ) 
                                                AS(
                                                  indice text,
                                                  indiv_id int,
                                                  afp_id int,       
                                                  peaf_codigo text,  
                                                  plati_id int,    
                                                  regimen text, 
                                                  tipo text,
                                                  fecha text,
                                                  cs text, 
                                                  ".$conc_nombres_header."  
                     
                                                )
                    ) as data 
                    INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id 
                    INNER JOIN rh.afp ON afp.afp_id = data.afp_id 

                    ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres
                ";


      //   echo $sql;
        $rs = $this->_CI->db->query($sql, $query)->result_array();

    
        return $rs;

    }

 



    public function  pdt600($params)
    {

        /* AGRUPAR LOS CONCEPTOS POR CONCEPTO DE SUNAT (snt) */

        $ESTADO_AFECTO_PLANILLA = ESTADOPLANILLA_MINIMO_SUNAT;  
 
        $plati_restric = '';

        if($params['tipoplanilla'] == '')
        {
         
            $plati_restric = " NOT IN (".TIPOPLANILLA_REGIDORES.",".TIPOPLANILLA_PENSIONSITAS.")";

        }
        else if($params['tipoplanilla'] == TIPOPLANILLA_PENSIONSITAS )
        {
           
            $plati_restric = " IN (".TIPOPLANILLA_PENSIONSITAS.")";
        }

    
        $sql = "   SELECT  
                      ind.indiv_id,
                      ind.indiv_dni as dni,
                      ( ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres  ) as trabajador,
                      montos_sunat.*,
                      cosus.cosu_codigo,
                      cosus.cosu_descripcion
         
                    FROM (

                          SELECT  plaem.indiv_id, conc.cosu_id, sum(plaec_value) as monto 
                                    
                          FROM planillas.planilla_empleado_concepto pec 
                          INNER JOIN planillas.planilla_empleados plaem ON pec.plaemp_id = plaem.plaemp_id AND plaem.plaemp_estado = 1
                          INNER JOIN planillas.planillas pla ON plaem.pla_id = pla.pla_id AND pla.plati_id ".$plati_restric." AND pla_mes = ? 
                          INNER JOIN planillas.planilla_movimiento movs ON pla.pla_id = movs.pla_id AND plamo_estado = 1 AND  movs.plaes_id =  ".$ESTADO_AFECTO_PLANILLA."   
                          INNER JOIN planillas.conceptos conc ON pec.conc_id = conc.conc_id AND conc.cosu_id is not null AND conc.cosu_id != 0

                          WHERE  pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.plaec_value > 0
                          GROUP BY  plaem.indiv_id, conc.cosu_id

                          ORDER BY plaem.indiv_id, conc.cosu_id
                     
                    ) as  montos_sunat
                     INNER JOIN planillas.conceptos_sunat cosus ON montos_sunat.cosu_id = cosus.cosu_id AND cosu_incluir_enarchivo = 1
                     LEFT JOIN public.individuo ind ON montos_sunat.indiv_id = ind.indiv_id  
                 

                  ORDER BY ind.indiv_dni, montos_sunat.cosu_id 
               ";



        $rs= $this->_CI->db->query($sql, array($params['mes']))->result_array();       

        return $rs;
     }



     public function grupo_descuento($params = array() )
     {

        $in_mes = '';
        $in_planillas = '';
        $in_grupos = '';    
 
        if(sizeof($params['planillas']) > 0 )
        {
             $in_planillas = implode(',', $params['planillas']);
        }
 
        if(sizeof($params['grupos']) > 0 )
        {
             $in_grupos = implode(',', $params['grupos']);
        }

        $anio = $params['anio'];
  
        $sql = " SELECT 

                        ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                        indiv.indiv_appaterno,
                        indiv.indiv_apmaterno,
                        indiv.indiv_nombres,
                        indiv.indiv_dni,

                        ( bene.indiv_appaterno || ' ' || bene.indiv_apmaterno || ' ' || bene.indiv_nombres || '(' || bene.indiv_dni ||')' ) as beneficiario,
                        bene.indiv_dni as beneficiario_dni,
                        grupo.gvc_nombre as grupo,

                        ff.nombre as fuente, 
                        tr.nombre as tipo_recurso,

                        ( datos.fuente_id || ' - ' || datos.tipo_recurso ) as fuente_codigo,

                        ( tarea.sec_func || ' - '|| tarea.tarea_nro ) as tarea_codigo,

                        datos.*,
                        planillas.planilla_siaf.siaf

                 FROM (  

                      SELECT plaemp.indiv_id, 
                             pla.ano_eje,
                             pla.pla_mes, 
                             pla.pla_id,
                             plaec.gvc_id, 
                             plaec.tarea_id, 
                             plaec.fuente_id, 
                             plaec.tipo_recurso, 
                             plaec.indiv_id_b, 
                             SUM(plaec_value) as total  
                      
                      FROM planillas.planilla_empleado_concepto plaec
                      
                      INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 
                      INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 
                      INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 ";

       
                      $sql.="  AND pla.pla_anio = '".$anio."' ";
         
 
                 if(sizeof($params['planillas']) > 0 )
                 {
                      $sql.="  AND pla.pla_id IN (".$in_planillas.") ";
                 }     



        $sql.= "    WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0";
              
                     if(sizeof($params['grupos']) > 0 )
                     {
                        $sql.= " AND concs.gvc_id IN (".$in_grupos.")  ";
                     }   


        $sql.="       GROUP BY pla.ano_eje, pla.pla_mes, pla.pla_id, plaemp.indiv_id, plaec.gvc_id, plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.indiv_id_b  
                      ORDER BY pla.ano_eje, pla.pla_mes, pla.pla_id, plaemp.indiv_id, plaec.gvc_id, plaec.tarea_id, plaec.fuente_id, plaec.tipo_recurso, plaec.indiv_id_b

                 ) as datos
                 LEFT JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id 
                 LEFT JOIN public.individuo bene ON datos.indiv_id_b = bene.indiv_id 
                 LEFT JOIN planillas.planillas pla ON datos.pla_id = pla.pla_id 
                 LEFT JOIN planillas.grupos_vc grupo ON datos.gvc_id = grupo.gvc_id 
                 LEFT JOIN sag.tarea ON datos.tarea_id = tarea.tarea_id 
                 LEFT JOIN pip.fuente_financ ff ON datos.ano_eje = ff.ano_eje AND datos.fuente_id = ff.fuente_financ 
                 LEFT JOIN pip.tipo_recurso tr ON datos.ano_eje = tr.ano_eje AND ff.fuente_financ = tr.fuente_financ AND datos.tipo_recurso = tr.tipo_recurso    
                 LEFT JOIN planillas.planilla_siaf ON planillas.planilla_siaf.pla_id = pla.pla_id AND planillas.planilla_siaf.fuente_id = datos.fuente_id AND planillas.planilla_siaf.tipo_recurso = datos.tipo_recurso

                 ORDER BY datos.fuente_id, datos.tipo_recurso, pla_codigo, indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
               
               ";

        $rs = $this->_CI->db->query($sql, array())->result_array();


        return $rs;
 
     }
 
     public function find_planillas( $params = array() )
     {

         $sql = "  SELECT  ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes || pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as planilla_codigo, 
                           pla.* 
                   FROM planillas.planillas pla 
                   INNER JOIN planillas.planilla_movimiento plamov ON pla.pla_id = plamov.pla_id  AND plamov.plamo_estado =  1 AND plamov.plaes_id = ".ESTADO_PLANILLA_CERRADA." 
 
                   WHERE pla.pla_estado =  1 ";

                   $values =array();

                if( trim($params['anio']) != '')
                {

                    $sql.=" AND pla_anio = ? ";
                    $values[] = trim($params['anio']);
                }    

                if( trim($params['mes']) != '')
                {

                    $sql.=" AND pla_mes = ? ";
                    $values[] = trim($params['mes']);
                }
 
                if(sizeof($params['plati']) > 0 )
                {   
                    $in_plati = implode(',', $params['plati']);
                    $sql.=" AND pla.plati_id IN (".$in_plati.") ";
                }

        $rs = $this->_CI->db->query($sql, $values)->result_array();

        return $rs;        

     }
   
     public function tabla_descuento($grupo_id)
     {  


     }

     public function pdt601($params){

        
        $params['tipoplanilla'] = TIPOPLANILLA_PENSIONSITAS;

        return  $this->pdt600($params);
 
     }


     public function presu_ff_pla_clasi_meta($datos)
     {

        $this->_CI->load->library(array('App/planilla'));

        $params['planillas'] = $datos['planillas'];
       
        $rs = $this->_CI->planilla->get_resumen_presupuestal($params, false, true, false );

        return $rs;
     }  


     public function presu_ff_pla_clasi_meta_tarea($datos)
     {
        
        $this->_CI->load->library(array('App/planilla'));
        $params['planillas'] = $datos['planillas'];
       
        $rs = $this->_CI->planilla->get_resumen_presupuestal($params, false, true, true );

        return $rs;
     }
 
     public function presu_ff_clasi_meta($datos)
     {
        
        $this->_CI->load->library(array('App/planilla'));
        $params['planillas'] = $datos['planillas'];
       
        $rs = $this->_CI->planilla->get_resumen_presupuestal($params, false, false, false );

        return $rs;
     }


     public function presu_ff_clasi_meta_tarea($datos)
     {
        
        $this->_CI->load->library(array('App/planilla'));
        $params['planillas'] = $datos['planillas'];
       
        $rs = $this->_CI->planilla->get_resumen_presupuestal($params, false, false, true );

        return $rs;
     }


     public function planillas_detalladas($params)
     {


        $this->_CI->load->library(array('App/planilla'));


        if( sizeof($params['mes']) == 0 || $params['plati'] == '' || $params['plati'] == FALSE || $params['anio'] == '' || $params['anio'] == FALSE )
        {

           return array(); 
        }


        $pla_anio = $params['anio'];

        $meses1 =  $params['mes'];
        $meses2 =  $params['mes'];
        
        foreach ( $meses1 as $m => $v)
        {
            
             $meses1[$m] = "'".$v."'";
        } 

        $in_mes = implode(',', $meses1);


        foreach( $meses2 as $m => $v) 
        {
            
            $meses2[$m] = "''".$v."''";
        } 

        $in_mes_slash = implode(',', $meses2);


        
        $planilla_tipo = trim($params['plati']);
   

        $PLANILLA_ESTADO_MINIMO = ESTADO_PLANILLA_CERRADA;




        /* Obtenemos los conceptos que se utilizan en las planillas para luego mostrarlos en el header */

        $sql = " SELECT concs.conc_id, 
                        concs.conc_nombre 
                 FROM(
                       SELECT distinct(plaec.conc_id ) as conc_id
                       FROM planillas.planilla_empleado_concepto plaec  
                       INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_estado = 1  AND plaec.plaemp_id = plaemp.plaemp_id 
                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = '".$pla_anio."' AND pla.pla_mes IN (".$in_mes.") AND pla.plati_id  IN (".$planilla_tipo.")
                       INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
                       WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 ";

                       if(trim($params['indiv_id']) != '')
                       {
                           $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                       }


         $sql.=" 
                   ) as rs_d
                INNER JOIN planillas.conceptos concs ON rs_d.conc_id = concs.conc_id 
                ORDER BY    concs.conc_tipo asc, concs.gvc_id asc, concs.conc_orden asc, concs.conc_id 

                ";
        
        $rs = $this->_CI->db->query($sql, array())->result_array();
   
        $conc_nombres_header='';   

         foreach($rs as $k => $con)
         {

           if($k>0)  $conc_nombres_header.=',';
           $conc_nombres_header.=' "DATO_'.trim($con['conc_nombre']).'" text'; 

         }


         $hay_datos = true;

         if(sizeof($rs) == 0)
         {

           $hay_datos = false;
         }


         /* Obtenemos las variables que van a ir en el planillon */ 
 
         $sql = " SELECT vari_id, vari_nombrecorto 
                  FROM planillas.variables 
                  WHERE vari_planillon = 1 AND vari_estado = 1 AND plati_id = ".$planilla_tipo."
                  ORDER BY vari_id  ";

         $rs = $this->_CI->db->query($sql, array($planilla_tipo) )->result_array();
            
            
        $vari_id_s = array();
        $vari_id_header = array();
        $vari_keys = array();
        $in_variables_planillon = '';
        $in_variables_header = '';

 
        $con_variables = true;

        if(sizeof($rs) == 0)
        {
            $con_variables = false;
        }

        foreach($rs as $reg)
        {
            $vari_id_s[] = $reg['vari_id'];
            $vari_id_header[] = '"DATO_'.$reg['vari_nombrecorto'].'" double precision ';
            $vari_keys[] = $reg['vari_nombrecorto'];
        }

        $in_variables_planillon = implode(',', $vari_id_s );
        $in_variables_header = implode(',' , $vari_id_header );



        $agrupar_por_mes = false;

        if($params['agrupar_por_mes'] === true)
        {
            $agrupar_por_mes = true;
        }

        $sql="";

        if($agrupar_por_mes === FALSE)
        {

            $sql.="  SELECT  

                      ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as planilla_codigo,
                      pla.pla_mes as mes,
                      plati.plati_nombre as regimen,         
                      ind.indiv_appaterno as paterno, 
                      ind.indiv_apmaterno as materno, 
                      ind.indiv_nombres as nombres, 
                      ind.indiv_dni as dni,  
                      ind.indiv_sexo as sexo, 
                      ind.indiv_fechanac as fecha_nacimiento,  
                      persla.persla_fechaini as fecha_inicio_contrato, 
                      persla.persla_fechafin as fecha_fin_contrato, 
                      persla.persla_fechacese as fecha_cese,
                      ind.indiv_essalud as essalud_codigo, 
                     
                      ( CASE WHEN  pensi.pentip_id = 1 THEN 

                            'ONP'

                        WHEN pensi.pentip_id = 2 THEN

                            'AFP'

                        ELSE '--'

                        END

                      ) as tipo_pension,


                      afp.afp_nombre as afp, 
                      pensi.peaf_codigo as afp_codigo,
                      
                      ( CASE WHEN   pensi.afm_id = ".AFP_FLUJO." THEN 

                            'FLUJO'

                        WHEN  pensi.afm_id = ".AFP_SALDO." THEN

                            'SALDO'

                        ELSE '--'

                        END

                      ) as  modo_afp,

                      platica.platica_nombre as categoria,
                      ocu.ocu_nombre as ocupacion "; 


                    if($con_variables)
                    {    

                        $sql.= " ,varis.* ";
     
                    }
                      
           $sql.= " , data_remuneraciones.*, 
                      totales.*,  
                      (totales.DATO_ingresos - totales.DATO_descuentos ) as DATO_neto,
                      (tarea.sec_func || '-' || tarea.tarea_nro )  as  tarea_codigo, 
                      (plaemp.fuente_id || '-' || plaemp.tipo_recurso ) as fuente, 
                      plasi.siaf 
                  ";
 
       }
       else 
       {

            $sql.="  SELECT   
 
                      data_remuneraciones.pla_mes as mes,
                    
                      ind.indiv_appaterno as paterno, 
                      ind.indiv_apmaterno as materno, 
                      ind.indiv_nombres as nombres, 
                      ind.indiv_dni as dni,  
                      ind.indiv_sexo as sexo, 
                      ind.indiv_fechanac as fecha_nacimiento,  
                      ind.indiv_essalud as essalud_codigo 
                     
                      "; 


                    if($con_variables)
                    {    

                        $sql.= " ,varis.* ";
       
                    }
                      
           $sql.= " , data_remuneraciones.*, 
                      totales.*,  
                      (totales.DATO_ingresos - totales.DATO_descuentos ) as DATO_neto 
                  ";
       
       }



        $sql.=" 
              FROM ( 
                 
                  SELECT * FROM crosstab('   

                      SELECT ";

                      if($agrupar_por_mes === false)
                      { 

                         $sql.=" 
                                plaemp.plaemp_id, 
                                plaemp.indiv_id, 
                                plaemp.persla_id, 
                                plaemp.platica_id, 
                                pla.pla_id, 
                                pla.plati_id,  
                                plaec.conc_id, 
                                plaec_value 
                              ";
                      }
                      else
                      {

                         $sql.="
                                (pla.pla_mes || ''_'' || plaemp.indiv_id) as indice,
                                pla.pla_mes,  
                                plaemp.indiv_id, 
                                plaec.conc_id, 
                                SUM(plaec_value) 
                              ";
                      }


                        $sql.="  
                            
                           FROM planillas.planilla_empleado_concepto plaec  
                           INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 
                           INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_estado = 1  AND plaec.plaemp_id = plaemp.plaemp_id 
                           INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ''".$pla_anio."'' AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                           INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
         
                           WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 
                        ";


                      if(trim($params['indiv_id']) != '')
                      {
                          $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                      }


                      if($agrupar_por_mes === false)
                      { 


                        $sql.="  

                            ORDER BY pla.pla_id, pla.plati_id, plaemp.plaemp_id, plaemp.indiv_id, 
                                       concs.conc_tipo asc, concs.gvc_id asc, concs.conc_orden asc, concs.conc_id  

                            ";
                      
                      }
                      else
                      {

                          $sql.="  
                              GROUP BY   pla.pla_mes, plaemp.indiv_id, plaec.conc_id                         

                              ORDER BY   pla.pla_mes, plaemp.indiv_id, plaec.conc_id

                              ";
                      }

                        $sql.=" 
                               ',' SELECT concs.conc_id FROM(
               
                                         SELECT distinct(plaec.conc_id ) as conc_id
                                         FROM planillas.planilla_empleado_concepto plaec  
                                         INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_estado = 1  AND plaec.plaemp_id = plaemp.plaemp_id 
                                         INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ''".$pla_anio."''  AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                                         INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
                           
                                         WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 ";



                                         if(trim($params['indiv_id']) != '')
                                         {
                                             $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                                         }

                      $sql.=" 
                                     ) as rs_d
                                  INNER JOIN planillas.conceptos concs ON rs_d.conc_id = concs.conc_id 
                                  ORDER BY concs.conc_tipo asc, concs.gvc_id asc, concs.conc_orden asc, concs.conc_id  ' 

                                 )AS(
                          ";
 
                                    if($agrupar_por_mes === false)
                                    { 

                                      $sql.=" 
                                               plaemp_id int, 
                                               indiv_id int,  
                                               persla_id int,  
                                               platica_id int,           
                                               pla_id int, 
                                               plati_id int, 
                                               ".$conc_nombres_header;
                                    }
                                    else
                                    {

                                       $sql.="indice text,
                                              pla_mes text,  
                                              indiv_id int, 
                                            ".$conc_nombres_header;
                                    }

                          $sql.=" 
                                   )

                    )  as data_remuneraciones

                    ";


                    if($con_variables)
                    { 
                        
                        if($agrupar_por_mes === false)
                        { 


                        $sql.="    
                                  LEFT JOIN (


                                    SELECT * FROM crosstab('            
                                
                                       SELECT  plaev.plaemp_id, 
                                               plaev.vari_id, 
                                               SUM(plaev_valor) as valor
                                        
                                        FROM planillas.planilla_empleado_variable plaev
                                        WHERE  plaev.plaev_estado = 1 AND 
                                               plaev.plaemp_id IN (SELECT plaemp_id   
                                                                   FROM planillas.planilla_empleados plaemp 
                                                                   INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ''".$pla_anio."'' AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                                                                   INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
                                                                   
                                                                   WHERE  plaemp.plaemp_estado = 1 
                                                                   ";


                                                                   if(trim($params['indiv_id']) != '')
                                                                   {
                                                                       $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                                                                   }


                        $sql.=" 
                                                                   )  
                                                
                                                AND plaev.vari_id IN (".$in_variables_planillon.")

                                        GROUP BY plaev.plaemp_id, plaev.vari_id
                                        ORDER BY plaev.plaemp_id, plaev.vari_id

                                      ', 
                                      ' SELECT vari_id 
                                        FROM planillas.variables 
                                        WHERE vari_planillon = 1 AND vari_estado = 1 AND  plati_id = ".$planilla_tipo."
                                        ORDER BY vari_id   ' ) 
                                      as ( plaemp_id  int,
                                          ".$in_variables_header."  

                                         )   

                                  ) as varis ON varis.plaemp_id = data_remuneraciones.plaemp_id   

                          ";

                      }
                      else
                      {

                          $sql.="    
                                    LEFT JOIN (


                                      SELECT * FROM crosstab('            
                                  
                                         SELECT  plaemp.indiv_id, 
                                                 pla.pla_mes,
                                                 plaev.vari_id, 
                                                 SUM(plaev_valor) as valor
                                          
                                          FROM planillas.planilla_empleado_variable plaev 
                                          INNER JOIN planillas.planilla_empleados plaemp ON plaev.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ''".$pla_anio."'' AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                                          INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 

                                          WHERE  plaev.plaev_estado = 1 AND  plaev.vari_id IN (".$in_variables_planillon.") ";


                                          if(trim($params['indiv_id']) != '')
                                          {
                                              $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                                          }

                          $sql.="               

                                          GROUP BY  plaemp.indiv_id, pla.pla_mes, plaev.vari_id
                                          ORDER BY  plaemp.indiv_id, pla.pla_mes, plaev.vari_id

                                        ', 
                                        ' SELECT vari_id 
                                          FROM planillas.variables 
                                          WHERE vari_planillon = 1 AND vari_estado = 1 AND  plati_id = ".$planilla_tipo."
                                          ORDER BY vari_id   ' ) 
                                        as ( 
                                              indiv_id int,
                                              pla_mes text,
                                            ".$in_variables_header."  

                                           )   

                                    ) as varis ON data_remuneraciones.indiv_id = varis.indiv_id AND data_remuneraciones.pla_mes = varis.pla_mes   

                            ";

                      }

                  }
  


                $sql.= " LEFT JOIN (

                            SELECT 

                                ";

                        if($agrupar_por_mes === false)
                        { 
                            $sql.=" plaemp.plaemp_id, ";
                        } 
                        else
                        {
                            $sql.=" pla.pla_mes, plaemp.indiv_id, ";
                        }

                               
                    $sql.="                                                    
                                  SUM((CASE WHEN plaec.conc_tipo = 1 THEN     

                                                plaec.plaec_value 
                                            ELSE 
                                                                0 
                                          END )) as DATO_ingresos,

                                  SUM((CASE WHEN plaec.conc_tipo = 2 THEN     

                                           plaec.plaec_value 

                                       ELSE 
                                           0 
                                       END )) as DATO_descuentos,


                                  SUM((CASE WHEN plaec.conc_tipo = 3 THEN    

                                            plaec.plaec_value 
                                      ELSE 
                                           0 
                                      END )) as DATO_aportacion

                            FROM planillas.planillas pla
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id  AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   
                            INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id >= ".$PLANILLA_ESTADO_MINIMO."    
                           
                            WHERE pla.pla_anio = '".$pla_anio."' AND pla.pla_mes IN (".$in_mes.") AND pla.plati_id IN (".$planilla_tipo.")
                            
                            ";


                            if(trim($params['indiv_id']) != '')
                            {
                                $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                            }

              if($agrupar_por_mes === false)
              { 
                  $sql.=" 
                            GROUP BY plaemp.plaemp_id
                            ORDER BY plaemp.plaemp_id 
                     
                     ) as totales ON data_remuneraciones.plaemp_id = totales.plaemp_id
                       
                     LEFT JOIN  public.individuo ind ON data_remuneraciones.indiv_id = ind.indiv_id
                     LEFT JOIN  rh.persona_situlaboral persla ON data_remuneraciones.persla_id = persla.persla_id   
                     LEFT JOIN  planillas.planilla_tipo_categoria platica ON data_remuneraciones.platica_id = platica.platica_id
                     LEFT JOIN  rh.persona_pension pensi ON pensi.pers_id = data_remuneraciones.indiv_id AND pensi.peaf_estado = 1 
                     LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id  
                     LEFT JOIN planillas.planillas pla ON data_remuneraciones.pla_id = pla.pla_id 
                     LEFT JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                     LEFT JOIN planillas.planilla_empleados plaemp ON data_remuneraciones.plaemp_id = plaemp.plaemp_id 
                     LEFT JOIN planillas.planilla_siaf plasi ON pla.pla_id = plasi.pla_id AND plaemp.fuente_id = plasi.fuente_id AND plaemp.tipo_recurso = plasi.tipo_recurso AND plasi.plasiaf_estado = 1
                     LEFT JOIN  planillas.ocupacion ocu ON plaemp.ocu_id = ocu.ocu_id 
                     LEFT JOIN sag.tarea ON plaemp.tarea_id = tarea.tarea_id 


                    ORDER BY  mes, planilla_codigo,   ind.indiv_appaterno, ind.indiv_apmaterno, ind.indiv_nombres 


                    ";
              } 
              else
              {
                 
                  $sql.=" 
                            GROUP BY pla.pla_mes, plaemp.indiv_id
                            ORDER BY pla.pla_mes, plaemp.indiv_id

                     ) as totales ON data_remuneraciones.indiv_id = totales.indiv_id AND data_remuneraciones.pla_mes = totales.pla_mes
 
                     LEFT JOIN  public.individuo ind ON data_remuneraciones.indiv_id = ind.indiv_id
   
                    ORDER BY   ind.indiv_appaterno, ind.indiv_apmaterno, ind.indiv_nombres, ind.indiv_dni,  mes

                    ";
              }

                           
                           
                            $sql.=" 

                     
                  "; 
    


              if($hay_datos)
              { 
                  $rs =  $this->_CI->db->query($sql, array() )->result_array();
              }
              else
              {

                 $rs = array();
              }

              return $rs;
            

     }



     public function planillas_detalladas_($params)
     {


        $this->_CI->load->library(array('App/planilla'));


        if( sizeof($params['mes']) == 0 || $params['plati'] == '' || $params['plati'] == FALSE || $params['anio'] == '' || $params['anio'] == FALSE )
        {

           return array(); 
        }


        $pla_anio = $params['anio'];

        $meses1 =  $params['mes'];
        $meses2 =  $params['mes'];
        
        foreach ( $meses1 as $m => $v)
        {
            
             $meses1[$m] = "'".$v."'";
        } 

        $in_mes = implode(',', $meses1);


        foreach( $meses2 as $m => $v) 
        {
            
            $meses2[$m] = "\'".$v."\'";
        } 

        $in_mes_slash = implode(',', $meses2);



        $planilla_tipo = $params['plati'];
     
        
        $PLANILLA_ESTADO_MINIMO = ESTADO_PLANILLA_CERRADA;

        /* Obtenemos los conceptos que se utilizan en las planillas para luego mostrarlos en el header */

        $sql = " SELECT concs.conc_id, 
                        concs.conc_nombre 
                 FROM(
                       SELECT distinct(plaec.conc_id ) as conc_id
                       FROM planillas.planilla_empleado_concepto plaec  
                       INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_estado = 1  AND plaec.plaemp_id = plaemp.plaemp_id 
                       INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = '".$pla_anio."' AND pla.pla_mes IN (".$in_mes.") AND pla.plati_id  IN (".$planilla_tipo.")
                       INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
                       WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 
                   ) as rs_d
                INNER JOIN planillas.conceptos concs ON rs_d.conc_id = concs.conc_id 
                ORDER BY    concs.conc_tipo asc, concs.gvc_id asc, concs.conc_orden asc, concs.conc_id 

                ";
        
        $rs = $this->_CI->db->query($sql, array())->result_array();
     
        $conc_nombres_header='';   

         foreach($rs as $k => $con)
         {

           if($k>0)  $conc_nombres_header.=',';
           $conc_nombres_header.=' "DATO_'.trim($con['conc_nombre']).'" text'; 

         }


         $hay_datos = true;

         if(sizeof($rs) == 0)
         {

           $hay_datos = false;
         }


         /* Obtenemos las variables que van a ir en el planillon */ 

         $sql = " SELECT vari_id, vari_nombrecorto 
                  FROM planillas.variables 
                  WHERE vari_planillon = 1 AND vari_estado = 1 AND plati_id = ?
                  ORDER BY vari_id  ";

         $rs = $this->_CI->db->query($sql ,array($planilla_tipo))->result_array();
            
            
        $vari_id_s = array();
        $vari_id_header = array();
        $vari_keys = array();
        $in_variables_planillon = '';
        $in_variables_header = '';

        $has_variables = true;

        if(sizeof($rs) == 0 )
        {
            $has_variables = false;
        } 

        $con_variables = true;

        if(sizeof($rs) == 0)
        {
            $con_variables = false;
        }

        foreach($rs as $reg)
        {
            $vari_id_s[] = $reg['vari_id'];
            $vari_id_header[] = '"DATO_'.$reg['vari_nombrecorto'].'" double precision ';
            $vari_keys[] = $reg['vari_nombrecorto'];
        }

        $in_variables_planillon = implode(',', $vari_id_s );
        $in_variables_header = implode(',' , $vari_id_header );



        $agrupar_por_mes = true;


        $sql ="  SELECT

                  ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as planilla_codigo,
                  pla.pla_mes as mes,
                  plati.plati_nombre as regimen,         
                  ind.indiv_appaterno as paterno, 
                  ind.indiv_apmaterno as materno, 
                  ind.indiv_nombres as nombres, 
                  ind.indiv_dni as dni,  
                  ind.indiv_sexo as sexo, 
                  ind.indiv_fechanac as fecha_nacimiento,  
                  persla.persla_fechaini as fecha_inicio_contrato, 
                  persla.persla_fechafin as fecha_fin_contrato, 
                  persla.persla_fechacese as fecha_cese,
                  ind.indiv_essalud as essalud_codigo, 
                 
                  ( CASE WHEN  pensi.pentip_id = 1 THEN 

                        'ONP'

                    WHEN pensi.pentip_id = 2 THEN

                        'AFP'

                    ELSE '--'

                    END

                  ) as tipo_pension,


                  afp.afp_nombre as afp, 
                  pensi.peaf_codigo as afp_codigo,
                  
                  ( CASE WHEN   pensi.afm_id = ".AFP_FLUJO." THEN 

                        'FLUJO'

                    WHEN  pensi.afm_id = ".AFP_SALDO." THEN

                        'SALDO'

                    ELSE '--'

                    END

                  ) as  modo_afp,

                  platica.platica_nombre as categoria,
                  ocu.ocu_nombre as ocupacion "; 


                if($con_variables)
                {    

                    $sql.= " ,varis.* ";
     
                }
                  
       $sql.= " , data_remuneraciones.*, 
                  totales.*,  
                  (totales.DATO_ingresos - totales.DATO_descuentos ) as DATO_neto,
                  (tarea.sec_func || '-' || tarea.tarea_nro )  as  tarea_codigo, 
                  (plaemp.fuente_id || '-' || plaemp.tipo_recurso ) as fuente, 
                  plasi.siaf 

              FROM ( 
                 
                  SELECT * FROM crosstab('   

                      SELECT plaemp.plaemp_id, 
                             plaemp.indiv_id, 
                             plaemp.persla_id, 
                             plaemp.platica_id, 
                             pla.pla_id, 
                             pla.plati_id,  
                             plaec.conc_id, 
                             plaec_value
                          
                           FROM planillas.planilla_empleado_concepto plaec  
                           INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 
                           INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_estado = 1  AND plaec.plaemp_id = plaemp.plaemp_id 
                           INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = \'".$pla_anio."\' AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                           INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
         
                           WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 
         
                            ORDER BY pla.pla_id, pla.plati_id, plaemp.plaemp_id, plaemp.indiv_id, 
                                     concs.conc_tipo asc, concs.gvc_id asc, concs.conc_orden asc, concs.conc_id 
                          
                        ',' SELECT concs.conc_id FROM(
               
                               SELECT distinct(plaec.conc_id ) as conc_id
                               FROM planillas.planilla_empleado_concepto plaec  
                               INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_estado = 1  AND plaec.plaemp_id = plaemp.plaemp_id 
                               INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = \'".$pla_anio."\'  AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                               INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
                 
                               WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 
                           ) as rs_d
                        INNER JOIN planillas.conceptos concs ON rs_d.conc_id = concs.conc_id 
                        ORDER BY concs.conc_tipo asc, concs.gvc_id asc, concs.conc_orden asc, concs.conc_id  ' )AS(
            
                                              plaemp_id int, 
                                              indiv_id int,  
                                              persla_id int,  
                                              platica_id int,           
                                              pla_id int, 
                                              plati_id int, 
                                              ".$conc_nombres_header."

                           )

                    )  as data_remuneraciones

                    ";


                    if($con_variables)
                    { 
                    
                    $sql.="    
                              LEFT JOIN (


                                SELECT * FROM crosstab('            
                            
                                   SELECT  plaev.plaemp_id, 
                                           plaev.vari_id, 
                                           SUM(plaev_valor) as valor
                                    
                                    FROM planillas.planilla_empleado_variable plaev
                                    WHERE  plaev.plaev_estado = 1 AND 
                                           plaev.plaemp_id IN (SELECT plaemp_id   
                                                               FROM planillas.planilla_empleados plaemp 
                                                               INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = \'".$pla_anio."\' AND pla.pla_mes IN (".$in_mes_slash.") AND pla.plati_id = ".$planilla_tipo."
                                                               INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id >= ".$PLANILLA_ESTADO_MINIMO." 
                                                               
                                                               WHERE  plaemp.plaemp_estado = 1 )  
                                            
                                            AND plaev.vari_id IN (".$in_variables_planillon.")

                                    GROUP BY plaev.plaemp_id, plaev.vari_id
                                    ORDER BY plaev.plaemp_id, plaev.vari_id

                                  ', 
                                  ' SELECT vari_id 
                                    FROM planillas.variables 
                                    WHERE vari_planillon = 1 AND vari_estado = 1 AND  plati_id = ".$planilla_tipo."
                                    ORDER BY vari_id   ' ) 
                                  as ( plaemp_id  int,
                                      ".$in_variables_header."  

                                     )   

                              ) as varis ON varis.plaemp_id = data_remuneraciones.plaemp_id   

                      ";


                  }
     
                $sql.= " LEFT JOIN (

                            SELECT 
                                  plaemp.plaemp_id,
                                                                                   
                                  SUM((CASE WHEN plaec.conc_tipo = 1 THEN     

                                                plaec.plaec_value 
                                            ELSE 
                                                                0 
                                          END )) as DATO_ingresos,

                                  SUM((CASE WHEN plaec.conc_tipo = 2 THEN     

                                           plaec.plaec_value 

                                       ELSE 
                                           0 
                                       END )) as DATO_descuentos,


                                  SUM((CASE WHEN plaec.conc_tipo = 3 THEN    

                                            plaec.plaec_value 
                                      ELSE 
                                           0 
                                      END )) as DATO_aportacion

                            FROM planillas.planillas pla
                            INNER JOIN planillas.planilla_empleados plaemp ON plaemp.pla_id = pla.pla_id  AND plaemp.plaemp_estado = 1
                            INNER JOIN planillas.planilla_empleado_concepto plaec ON plaec.plaemp_id = plaemp.plaemp_id  AND  plaec_marcado = 1 AND plaec.plaec_estado = 1  AND conc_afecto = 1   
                            INNER JOIN planillas.planilla_movimiento movs ON movs.pla_id = pla.pla_id  AND plamo_estado = 1 AND movs.plaes_id >= ".$PLANILLA_ESTADO_MINIMO."    
                           
                            WHERE pla.pla_anio = '".$pla_anio."' AND pla.pla_mes IN (".$in_mes.") AND pla.plati_id IN (".$planilla_tipo.")
                            GROUP BY plaemp.plaemp_id
                            ORDER BY plaemp.plaemp_id
                           


                      ) as totales ON data_remuneraciones.plaemp_id = totales.plaemp_id

                        
                      LEFT JOIN  public.individuo ind ON data_remuneraciones.indiv_id = ind.indiv_id
                      LEFT JOIN  rh.persona_situlaboral persla ON data_remuneraciones.persla_id = persla.persla_id   
                      LEFT JOIN  planillas.planilla_tipo_categoria platica ON data_remuneraciones.platica_id = platica.platica_id
                      LEFT JOIN  rh.persona_pension pensi ON pensi.pers_id = data_remuneraciones.indiv_id AND pensi.peaf_estado = 1 
                      LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id  
                      LEFT JOIN planillas.planillas pla ON data_remuneraciones.pla_id = pla.pla_id 
                      LEFT JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                      LEFT JOIN planillas.planilla_empleados plaemp ON data_remuneraciones.plaemp_id = plaemp.plaemp_id 
                      LEFT JOIN planillas.planilla_siaf plasi ON pla.pla_id = plasi.pla_id AND plaemp.fuente_id = plasi.fuente_id AND plaemp.tipo_recurso = plasi.tipo_recurso AND plasi.plasiaf_estado = 1
                      LEFT JOIN  planillas.ocupacion ocu ON plaemp.ocu_id = ocu.ocu_id 
                      LEFT JOIN sag.tarea ON plaemp.tarea_id = tarea.tarea_id 


                     ORDER BY  mes, planilla_codigo,   ind.indiv_appaterno, ind.indiv_apmaterno, ind.indiv_nombres 
                  ";
     


              if($hay_datos)
              { 
                  $rs =  $this->_CI->db->query($sql, array() )->result_array();
              }
              else
              {

                 $rs = array();
              }


              return $rs;
            

     }


     public function get_trabajadores_info($params)
     {    

        //  (select count(pefa_id) FROM rh.persona_familia fami WHERE paren_id = 1 AND pefa_estado = 1 AND pers.indiv_id = fami.pers_id   ) as n_hijos,
        

         
          $estado_solo_activos =($params['estado_trabajador'] == '1') ? true : false;

          $plati_id = array();
          $in_plati = '';

          if( sizeof($params['plati']) > 0 && trim($params['indiv_id']) == ''){
             $in_plati = ' AND historial.plati_id IN ('.implode(',', $params['plati']).')';
             $in_vari = ' AND vari.plati_id IN ('.implode(',', $params['plati']).')';
          } 

          $sql = " SELECT   (pers.indiv_id) as individuo_id,
                            pers.indiv_appaterno as paterno,
                            pers.indiv_apmaterno as materno,
                            pers.indiv_nombres as nombres,
                            pers.indiv_dni as dni,
                            pers.indiv_sexo as sexo_id,
                            pers.indiv_fechanac as fecha_nacimiento, 
                            pers.indiv_direccion1 as direccion1,
                            pers.indiv_direccion2 as direccion2,
                            pers.indiv_telefono as telefono,
                            pers.indiv_celular as celular,
                            pers.indiv_email as email,
                            pers.indiv_ruc as ruc,
                            pers.indiv_observ as obs,
                            pers.indiv_essalud as essalud,
                            ecivil.estciv_nombre as estado_civil,
                            
                            ( CASE WHEN  pensi.pentip_id = 1 THEN 

                                  'ONP'

                              WHEN pensi.pentip_id = 2 THEN

                                  'AFP'

                              ELSE '--'

                              END

                            ) as tipo_pension,


                            afp.afp_nombre as afp, 
                            pensi.peaf_codigo as afp_codigo,
            
                            ( CASE WHEN   pensi.afm_id = ".AFP_FLUJO." THEN 

                                  'FLUJO'

                              WHEN  pensi.afm_id = ".AFP_SALDO." THEN

                                  'SALDO'

                              ELSE '--'

                              END

                            ) as  modo_afp,
 
                            pensi.peaf_jubilado as jubilado,
                            pensi.peaf_invalidez as invalidez,
                            banco.ebanco_nombre as banco,
                            cuenta.pecd_cuentabancaria,  
                            ge.gremp_nombre as grupo_empleado,
                            plati.plati_nombre as regimen_actual, 
                            ocu.ocu_nombre as ocupacion_nombre,
                            historial.plati_id,
                            historial.persla_fechaini as fecha_inicio_contrato, 
                            historial.persla_fechafin as fecha_fin_contrato,
                            historial.persla_fechacese as fecha_cese, 
                            historial.persla_carnet_presento,
                            historial.persla_carnet_fechainicio,
                            historial.persla_carnet_fechafin,
                            historial.persla_carnet_numero,

                            ( CASE WHEN conc_sindicato.empcon_id is not NULL THEN

                                    'Si'
                              ELSE 
                                    'No'
                              END ) as sindicalizado,
                            
                            (CASE WHEN historial.persla_vigente = 1 THEN
                                  'ACTIVO'
                            ELSE 
                                  'INACTIVO'      
                            END) as estado_trabajador            
                

             FROM public.individuo pers 
             INNER JOIN rh.persona_situlaboral historial  ON pers.indiv_id = historial.pers_id AND historial.persla_ultimo = 1  AND persla_estado = 1 ".$in_plati." ".($estado_solo_activos && trim($params['indiv_id']) == '' ? 'AND persla_vigente = 1' : '')." 
             LEFT JOIN  rh.persona_pension pensi ON pensi.pers_id = pers.indiv_id AND pensi.peaf_estado = 1 
             LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id   
             LEFT JOIN  rh.persona_cuenta_deposito cuenta  ON cuenta.pers_id = pers.indiv_id AND cuenta.pecd_estado = 1  
             LEFT JOIN  public.entidades_bancarias as banco ON cuenta.ebanco_id = banco.ebanco_id 
             LEFT JOIN  rh.estadocivil ecivil ON pers.indiv_estadocivil = ecivil.estciv_id
             LEFT JOIN  planillas.planilla_tipo plati ON historial.plati_id = plati.plati_id 
             LEFT JOIN  planillas.grupo_empleado ge ON historial.gremp_id = ge.gremp_id 
             LEFT JOIN  planillas.ocupacion ocu ON historial.ocu_id = ocu.ocu_id  
             LEFT JOIN  planillas.conceptos_sistema cosi ON plati.plati_id = cosi.plati_id 
             LEFT JOIN  planillas.empleado_concepto conc_sindicato ON cosi.conc_sindicato = conc_sindicato.conc_id AND conc_sindicato.indiv_id = pers.indiv_id AND empcon_estado = 1
            
             WHERE pers.indiv_estado = 1 
             ";

             if(trim($params['indiv_id']) != '')
             {
                 $sql.=" AND pers.indiv_id = ".trim($params['indiv_id']);
             }

        $sql.="ORDER BY pers.indiv_appaterno, pers.indiv_apmaterno, pers.indiv_nombres   ";


        $sql_header_varibales = " SELECT x.plati_id, x.vari_id, x.vari_nombre FROM (
                                       SELECT distinct(vari.plati_id) as plati_id, vari.vari_id as vari_id, vari.vari_nombre
                                       FROM planillas.empleado_variable empvar 
                                       INNER JOIN rh.persona_situlaboral historial ON empvar.indiv_id = historial.pers_id AND historial.persla_ultimo = 1  AND persla_estado = 1 ".$in_plati." ".($estado_solo_activos  && trim($params['indiv_id']) == ''  ? 'AND persla_vigente = 1' : '')." 
                                       INNER JOIN planillas.variables vari ON empvar.vari_id = vari.vari_id AND historial.plati_id = vari.plati_id AND vari_estado = 1 
                                       WHERE empvar_estado = 1 AND empvar_value > 0 ".$in_vari." 
                                       ORDER BY vari.plati_id,vari.vari_id 
                                  ) as x
                                 ";

        $rs = $this->_CI->db->query($sql_header_varibales, array())->result_array();         

        $vari_nombres_header='';   

        foreach($rs as $k => $var)
        {

          if($k>0)  $vari_nombres_header.=',';
          $vari_nombres_header.=' "V_'.trim($var['plati_id']).'-'.trim($var['vari_nombre']).'" text'; 

        }         


        $sql_table_variables = " SELECT * FROM  crosstab('   
                                     SELECT (empvar.indiv_id ||''_'' || historial.plati_id ) as indice, 
                                             empvar.indiv_id, 
                                             historial.plati_id,
                                             empvar.vari_id, 
                                             empvar.empvar_value 
                                     FROM planillas.empleado_variable empvar
                                     INNER JOIN rh.persona_situlaboral historial ON empvar.indiv_id = historial.pers_id AND historial.persla_ultimo = 1  AND persla_estado = 1 ".$in_plati." ".($estado_solo_activos && trim($params['indiv_id']) == '' ? 'AND persla_vigente = 1' : '')." 
                                     INNER JOIN planillas.variables vari ON empvar.vari_id = vari.vari_id AND vari_estado = 1
                                     WHERE empvar.empvar_estado = 1 AND empvar.empvar_value > 0 ".$in_vari." 
                                     ORDER BY vari.plati_id, empvar.indiv_id, vari.vari_id 

                                 ',' SELECT x.vari_id 
                                     FROM (
                                       SELECT distinct(vari.plati_id), vari.vari_id as vari_id 
                                       FROM planillas.empleado_variable empvar 
                                       INNER JOIN rh.persona_situlaboral historial ON empvar.indiv_id = historial.pers_id AND historial.persla_ultimo = 1  AND persla_estado = 1 ".$in_plati." ".($estado_solo_activos && trim($params['indiv_id']) == '' ? 'AND persla_vigente = 1' : '')." 
                                       INNER JOIN planillas.variables vari ON empvar.vari_id = vari.vari_id AND historial.plati_id = vari.plati_id AND vari_estado = 1 
                                       WHERE empvar_estado = 1 AND empvar_value > 0 ".$in_vari." 
                                       ORDER BY vari.plati_id,vari.vari_id 
                                     ) as x'
                                   ) AS(
                                      indice text,
                                      indiv_id int, 
                                      plati_id int, 
                                    ".$vari_nombres_header." 
                                    )  
                                ";


        if($params['incluir_params'] == '1'){

            $sql_completo =" SELECT * FROM (".$sql.") as trabajadores 
                             LEFT JOIN (".$sql_table_variables.") as variables ON trabajadores.individuo_id = variables.indiv_id  AND trabajadores.plati_id = variables.plati_id  
                             ORDER BY trabajadores.paterno, trabajadores.materno, trabajadores.nombres  
                          ";
          
        }
        else{

            $sql_completo = $sql; 
        }

        return $this->_CI->db->query($sql_completo, array())->result_array();
 
     }


     public function consolidado_sunat_por_planilla($params)
     {
 
         $sql = " SELECT gvc_nombre
                  FROM planillas.grupos_vc grupo  
                  WHERE gvc_sunat = 1 AND gvc_estado = 1 
                  ORDER BY gvc_id ";
 
         $rs = $this->_CI->db->query($sql, array())->result_array();         

         $conc_nombres_header='';   

         foreach($rs as $k => $con)
         {

           if($k>0)  $conc_nombres_header.=',';
           $conc_nombres_header.=' "DATO_'.trim($con['gvc_nombre']).'" text'; 

         }         
  
          $estado_reporte = ESTADO_PLANILLA_CERRADA;

          $mes = $params['mes'];
          $anio = trim($params['anio']);
 

          $sql = "    SELECT

                      plati.plati_nombre,

                       ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,

                      datos.* 

                      FROM (
                       
                        SELECT *  FROM  crosstab( '  SELECT (pla.pla_id ||''_'' || plaec.fuente_id ||''_'' || plaec.tipo_recurso ) as indice,  
                                                     pla.pla_id, 
                                                     plaec.fuente_id, 
                                                     plaec.tipo_recurso, 
                                                     grupo.gvc_id, 
                                                     SUM(plaec_value) as monto  

                                                   FROM planillas.planilla_empleado_concepto plaec 
                                                            INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.conc_estado = 1
                                                            INNER JOIN planillas.grupos_vc grupo ON concs.gvc_id = grupo.gvc_id AND   gvc_estado = 1  AND gvc_sunat = 1 
                                                            INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                                                            INNER JOIN planillas.planillas pla On plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ''".$anio."'' AND pla.pla_mes = ''".$mes."''
                                                            INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".$estado_reporte."
                                                   WHERE plaec_estado = 1 AND plaec_marcado = 1  AND plaec_value > 0 AND concs.conc_afecto = 1

                                                   GROUP BY pla.pla_id, plaec.fuente_id, plaec.tipo_recurso, grupo.gvc_id
                                                ORDER BY pla.pla_id, plaec.fuente_id, plaec.tipo_recurso, grupo.gvc_id 
                       
                                            ',' SELECT gvc_id FROM planillas.grupos_vc grupo WHERE gvc_sunat = 1 AND gvc_estado = 1  ORDER BY gvc_id  ' 
                                             ) 
                                            AS(
                                              indice text,
                                              pla_id int,
                                              fuente_id text,       
                                              tipo_recurso text, 
                                                ".$conc_nombres_header."  

                           ) 

                  ) as datos 
                  INNER JOIN planillas.planillas pla ON  datos.pla_id = pla.pla_id  
                  INNER JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                  ORDER BY pla.plati_id, pla_codigo, datos.fuente_id, datos.tipo_recurso   ";

 


          $rs = $this->_CI->db->query($sql, array())->result_array();
  
          return $rs;

     }  



     public function consolidado_sunat_por_trabajador($params)
     {
 
         $sql = " SELECT gvc_nombre
                  FROM planillas.grupos_vc grupo  
                  WHERE gvc_sunat = 1 AND gvc_estado = 1 
                  ORDER BY gvc_id ";


         $rs = $this->_CI->db->query($sql, array())->result_array();         

         $conc_nombres_header='';   

         foreach($rs as $k => $con)
         {

           if($k>0)  $conc_nombres_header.=',';
           $conc_nombres_header.=' "DATO_'.trim($con['gvc_nombre']).'" text'; 

         }         
  
          $estado_reporte = ESTADO_PLANILLA_CERRADA;

          $mes = trim($params['mes']);

          $anio = trim($params['anio']);
 

          $sql = "    SELECT

                      indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, indiv.indiv_dni,  

                      plati.plati_nombre as regimen, asistencia.dias as \"DATO_asistencia\",
                      datos.* 

                      FROM (
                       
                        SELECT *  FROM  crosstab( '
                                                   SELECT  
                                                           ( pla.plati_id || ''-'' || plaemp.indiv_id   ) as indice,
                                                           pla.plati_id,
                                                           plaemp.indiv_id,   
                                                           grupo.gvc_id, 
                                                           SUM(plaec.plaec_value) as monto  

                                                   FROM planillas.planilla_empleado_concepto plaec 
                                                            INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.conc_estado = 1
                                                            INNER JOIN planillas.grupos_vc grupo ON concs.gvc_id = grupo.gvc_id AND gvc_sunat = 1 
                                                            INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                                                            INNER JOIN planillas.planillas pla On plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1  AND pla.pla_anio = ''".$anio."'' AND pla.pla_mes = ''".$mes."''
                                                            INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".$estado_reporte."
                                                   WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND concs.conc_afecto = 1 AND plaec.plaec_value > 0

                                                   GROUP BY plaemp.indiv_id, pla.plati_id, grupo.gvc_id
                                                   ORDER BY plaemp.indiv_id, pla.plati_id, grupo.gvc_id
                       
                                            ',' SELECT gvc_id FROM planillas.grupos_vc grupo WHERE gvc_sunat = 1 AND gvc_estado = 1  ORDER BY gvc_id  ' 
                                             ) 
                                            AS(
                                              indice text,
                                              plati_id int,
                                              indiv_id int,  
                                             ".$conc_nombres_header."  

                           ) 

                  ) as datos 
                  LEFT JOIN ( 

                      SELECT indiv_id, pla.plati_id, ROUND(SUM(plaec_asis.plaec_value)) as dias
                      FROM planillas.planilla_empleado_concepto plaec_asis
                      INNER JOIN planillas.planilla_empleados plaemp ON plaec_asis.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1
                      INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1  AND pla.pla_anio ='".$anio."' AND pla.pla_mes ='".$mes."'
                      INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".$estado_reporte."
                      INNER JOIN planillas.conceptos_sistema cosi ON plaec_asis.conc_id = cosi.conc_asistencia 

                      WHERE plaec_asis.plaec_estado  = 1 

                      GROUP BY plaemp.indiv_id, pla.plati_id 
                      ORDER BY plaemp.indiv_id, pla.plati_id

                  ) as asistencia ON datos.indiv_id = asistencia.indiv_id AND datos.plati_id = asistencia.plati_id 

                  INNER JOIN public.individuo indiv ON datos.indiv_id = indiv.indiv_id   
                  LEFT JOIN planillas.planilla_tipo plati ON datos.plati_id = plati.plati_id  
                  ORDER BY  indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres  ";

                  



          $rs = $this->_CI->db->query($sql, array())->result_array();
  
          return $rs;

     }  
     

     
     

     public function reporte_por_conceptos($params)
     {

       $query = array();

       $sql = " SELECT  ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                         data.siaf, indiv_appaterno, indiv_apmaterno, indiv_nombres, indiv_dni, data.total   

                    FROM (  

                         SELECT   plasi.siaf, pla.pla_id, plaemp.indiv_id, SUM(plaec_value) as total 

                                 FROM planillas.planilla_empleado_concepto plaec 
                                 INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.conc_estado = 1
                                 INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1 
                                 INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 
                                 INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".ESTADO_PLANILLA_CERRADA."
                                 LEFT JOIN planillas.planilla_siaf plasi ON pla.pla_id = plasi.pla_id AND plasi.fuente_id = plaec.fuente_id AND plasi.tipo_recurso = plaec.tipo_recurso  AND plasi.plasiaf_estado = 1
                      
                         WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 AND plaec.conc_afecto = 1
                      ";

                     $solo_codigos = false; 

			               if(trim($params['anio']) != '')
                     {
                         $sql.=" AND pla.pla_anio = ? ";
                         $query[] = $params['anio']; 
                     } 

                     if( trim($params['concepto']) != '')
                     {

                       $sql.=" AND plaec.conc_id = ? ";

                       $query[] = $params['concepto'];
                       
                     }

                     if( trim($params['grupo']) != '')
                     {

                       $sql.=" AND concs.gvc_id = ? ";

                       $query[] = $params['grupo'];
                        
                     }


                     if(trim($params['siaf']) != '')
                     {

                        $sql.=" AND plasi.siaf = ? ";
                        $query[] = $params['siaf'];
                        $solo_codigos = true;

                     }


                     if(trim($params['planilla']) != '')
                     {

                        $sql.=" AND pla.pla_id = ? ";
                        $query[] = $params['planilla'];
                        $solo_codigos = true;

                     }


                     if($solo_codigos == FALSE)
                     {


                         if(trim($params['mes']) != '')
                         {

                            $sql.=" AND pla.pla_mes = ? ";
                            $query[] = $params['mes']; 
                         } 

                         if(trim($params['planillatipo']) != '')
                         {

                            $sql.=" AND pla.plati_id = ? ";
                            $query[] = $params['planillatipo']; 
                         }
                     }


               $sql.=" GROUP BY plasi.siaf, pla.pla_id, plaemp.indiv_id  


                ) as data 
                LEFT JOIN public.individuo indiv ON data.indiv_id =  indiv.indiv_id 
                LEFT JOIN planillas.planillas pla ON data.pla_id = pla.pla_id 

                ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres,  pla_codigo   

      
              ";




         $rs = $this->_CI->db->query($sql , $query)->result_array();

         return $rs;

     }


     public function consolidado_por_mes( $params = array() )
     {

         // var_dump($params);  

          $anio = $params['anio'];

          if($params['plati_id'] != '' && $params['plati_id'] != '0' && is_numeric($params['plati_id']) ){

              $plati_id = $params['plati_id'];
          } 
          else
          {
              $plati_id = '';
          }


          if($params['tarea_id'] != '' && $params['tarea_id'] != '0' && is_numeric($params['tarea_id']) ){

              $tarea_id = $params['tarea_id'];
          } 
          else
          {
              $tarea_id = '';
          }


          if($params['modo'] == 'neto')
          {


          }
          else
          {

            if($params['conc_id'] != '' && $params['conc_id'] != '0' && is_numeric($params['conc_id']) ){

                $conc_id = $params['conc_id'];
            } 
            else
            {
                $conc_id = '';
            //    if($params['grupo'] == '') return false;
            }
          }


          //(CASE WHEN conc_tipo = 2 THEN (plaec_value * -1) ELSE plaec_value END)    

          if($params['modo'] == 'concepto' || $params['modo'] == 'grupo' )
          {
            $sql_modo = ' SUM(plaec_value) as total  '; 
          }
          else if($params['modo'] == 'bruto')
          {
               $sql_modo = ' SUM( (CASE WHEN conc.conc_tipo = 1 THEN  plaec_value  ELSE 0 END) ) as total  ';    
          }
          else if($params['modo'] == 'neto')
          {
               $sql_modo = ' SUM( (CASE WHEN conc.conc_tipo = 2 THEN (plaec_value * -1) ELSE (CASE WHEN conc.conc_tipo = 1 THEN plaec_value ELSE 0 END )  END) ) as total  ';    
          }
          else if($params['modo'] == 'costo')
          {
               $sql_modo = ' SUM((CASE WHEN conc.conc_tipo IN (1,3) THEN  plaec_value  ELSE 0 END)) as total  ';    
          }
          else
          {
            $sql_modo = ' SUM(plaec_value) as total  '; 
          }

          $sql_core = "   SELECT  plaemp.indiv_id, 
                                  pla.pla_mes, 
                                  ".$sql_modo."
                         
                          FROM planillas.planilla_empleados plaemp 
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 ";

                          if($anio != '')
                          {
                              $sql_core.=" AND pla.pla_anio = ''".$anio."''";
                          }
                          else
                          {
                              $sql_core.=" AND pla.pla_anio = 2014 ";
                          }

                          if($plati_id != '')
                          {
                             $sql_core.=" AND plati_id = ".$plati_id;
                          }

                     
           $sql_core.= "  INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = ".ESTADO_PLANILLA_CERRADA."
                          INNER JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND plaec.plaec_estado = 1 AND plaec_marcado = 1  AND plaec.conc_afecto = 1 
                          INNER JOIN planillas.conceptos conc ON plaec.conc_id = conc.conc_id   
                        ";

                        if($tarea_id != '')
                        {
                           $sql_core.=" AND plaec.tarea_id = ".$tarea_id;
                        }

                        if($params['grupo'] != '' || $conc_id != '' )
                        {
                           if($params['grupo'] != '' )
                           {
                               $sql_core.=" AND  conc.gvc_id IN (".$params['grupo'].")";
                           }
                           else
                           {
                               $sql_core.=" AND plaec.conc_id = ".$conc_id;
                           }  

                        } 

                        

           $sql_core.="                  
                          WHERE plaec.plaec_value > 0 
                          GROUP BY plaemp.indiv_id, pla.pla_mes 
                          ORDER BY plaemp.indiv_id, pla.pla_mes 

                       ";


          $sql = "   SELECT  (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador, 
                               plati.plati_nombre as regimen_actual,
                              ( ind.indiv_dni) as dni, 
                              data.*

                    FROM (

                         SELECT * FROM crosstab(' 

                             ".$sql_core."

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

                 ) as data
                 LEFT JOIN \"public\".individuo ind ON data.indiv_id = ind.indiv_id  
                 LEFT JOIN rh.persona_situlaboral persla ON ind.indiv_id = persla.pers_id AND persla_estado = 1 AND persla.persla_ultimo = 1 
                 LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                 ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

             ";


            return  $this->_CI->db->query($sql, array())->result_array();
     }


     public function remuneraciones($params)
     {

          $sql = " SELECT indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres, indiv_dni, plati.plati_nombre as regimen, 
                          data.total as remuneracion, ( tarea.sec_func || '-' || tarea.tarea_nro )as tarea,  (empre.fuente_id || ' - ' || empre.tipo_recurso ) as  fuente

                  FROM (  

                    SELECT  empvar.indiv_id, SUM(empvar_value) as total
                    FROM planillas.empleado_variable empvar
                    INNER JOIN planillas.variables vari ON empvar.vari_id = vari.vari_id AND vari_estado = 1 AND vari_remuneracion = 1  
 
                    GROUP BY empvar.indiv_id
                  
                  ) as data
                  INNER JOIN public.individuo indiv ON data.indiv_id = indiv.indiv_id
                  INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1 AND persla_vigente = 1 
                  LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                  LEFT JOIN planillas.empleado_presupuestal empre ON indiv.indiv_id = empre.indiv_id AND empre_estado = 1 
                  LEFT JOIN sag.tarea ON empre.tarea_id = tarea.tarea_id 

                  ORDER BY indiv.indiv_appaterno, indiv.indiv_apmaterno, indiv.indiv_nombres 
                ";

         $rs = $this->_CI->db->query($sql, array())->result_array();

         return $rs;
     }

     public function ingresos_mensuales()
     {

         $sql = "   SELECT  (ind.indiv_appaterno || ' ' || ind.indiv_apmaterno || ' ' || ind.indiv_nombres) as trabajador, 
                              plati.plati_nombre,
                             ('_'|| ind.indiv_dni) as dni, 
                             asistencia.*

                   FROM (
                        SELECT * FROM crosstab(' 

                               SELECT  plaemp.indiv_id, pla.pla_mes, SUM(plaec_value) as total 
                               FROM planillas.planilla_empleados plaemp 
                               INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1 
                               INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1  AND plamo.plaes_id = 2
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
                LEFT JOIN planillas.planilla_tipo plati ON persla.plati_id = plati.plati_id 
                ORDER BY indiv_appaterno, indiv_apmaterno, indiv_nombres

            ";
     


         $rs = $this->_CI->db->query($sql, array())->result_array();
         
         return $rs; 
     }


     public function exportar_planilla_concepto_vertical($params = array())
     {

         if( sizeof($params['plati']) > 0 && sizeof($params['mes']) > 0 )
         {

            $plati_in = implode(',', $params['plati']);
            $mes_in = implode("','", $params['mes']); 

            $anio = $params['anio'];
           
         } 
         else
         {
             return array();
         }

         $PLANILLA_ESTADO_MINIMO = ESTADO_PLANILLA_CERRADA;

         $sql ="SELECT  
                     pla_codigo, plati_nombre, tipo, 
 
                  concepto,  fuente, SUM(datos.monto) as \"DATO_monto\" 

                 FROM (   

                SELECT 
                                
                            ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                             plati.plati_nombre,
                             indiv.indiv_dni,
                             (indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                               
                               
                             (CASE WHEN plaec.conc_tipo = 1 THEN
                                 
                                'INGRESO'
                              WHEN  plaec.conc_tipo = 2 THEN

                                'DESCUENTO'

                              ELSE

                                'APORTACION'

                              END ) as tipo,
                              

                             (CASE WHEN  refc.conc_nombre is not null THEN
                              
                                  refc.conc_nombre
                              ELSE  

                                   concs.conc_nombre

                              END ) as concepto,
                              

                             plaec.plaec_value as monto, 
                              
                             ( tarea.sec_func || '-' || tarea.tarea_nro ) as tarea_codigo,
                              
                              ( plaec.fuente_id || '-' || plaec.tipo_recurso  ) as fuente,
                              
                             eb.ebanco_nombre as banco,
                               pecd_cuentabancaria as  nro_cuenta,
                              afp.afp_nombre as afp,
                              peaf_codigo
                           
                            
                          FROM planillas.planilla_empleado_concepto plaec
                          LEFT JOIN planillas.conceptos concs ON concs.conc_id = plaec.conc_id 
                          LEFT JOIN planillas.concepto_agrupadoresumen concre ON concs.gvc_id = concre.gvc_id 
                           LEFT JOIN planillas.conceptos refc ON concre.conc_id_ref =refc.conc_id 

                          INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaec.plaemp_id AND plaemp.plaemp_estado = 1
                          INNER JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id 
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_anio = '".$anio."' AND pla.pla_mes IN ('".$mes_in."')  AND  pla.plati_id IN (".$plati_in.")
                          INNER JOIN planillas.planilla_movimiento plamo  ON pla.pla_id = plamo.pla_id AND plamo.plaes_id = ".$PLANILLA_ESTADO_MINIMO." AND plamo.plamo_estado = 1
                          INNER JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                          LEFT JOIN sag.tarea On tarea.tarea_id = plaec.tarea_id 
                          LEFT JOIN rh.persona_cuenta_deposito cuenta ON cuenta.pecd_id = plaemp.pecd_id 
                          LEFT JOIN public.entidades_bancarias eb ON cuenta.ebanco_id = eb.ebanco_id  
                          LEFT JOIN  rh.persona_pension peaf ON plaemp.indiv_id = peaf.pers_id AND peaf.peaf_estado = 1 
                          LEFT JOIN rh.afp ON peaf.afp_id = afp.afp_id 

                          WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 AND plaec.conc_afecto = 1 

                          ORDER BY pla.plati_id, pla.pla_id, pla_codigo, indiv.indiv_id, pla.pla_id, plaemp.plaemp_id, plaec.plaec_id 

                ) as datos

                GROUP BY pla_codigo, plati_nombre, tipo, concepto,  fuente 

                ORDER BY plati_nombre, pla_codigo, tipo, concepto ,   fuente  ";

     
          return $this->_CI->db->query($sql, array())->result_array();
     }



     public function exportar_individuo_concepto_vertical($params = array())
     {

         if( sizeof($params['plati']) > 0 && sizeof($params['mes']) > 0 )
         {

            $plati_in = implode(',', $params['plati']);
            $mes_in = implode("','", $params['mes']); 

            $anio = $params['anio'];
           
         } 
         else
         {
             return array();
         }
 
         $PLANILLA_ESTADO_MINIMO = ESTADO_PLANILLA_CERRADA;

         $sql ="   SELECT 
                        
                    ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                     plati.plati_nombre,
                     indiv.indiv_dni,
                     (indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                      
                     concs.conc_nombre as concepto,
                     
                     (CASE WHEN plaec.conc_tipo = 1 THEN
                         
                        'INGRESO'
                      WHEN  plaec.conc_tipo = 2 THEN

                        'DESCUENTO'

                      ELSE

                        'APORTACION'

                      END ) as tipo,

                     plaec.plaec_value as \"DATO_monto\",
                      
                     ( tarea.sec_func || '-' || tarea.tarea_nro ) as tarea_codigo,
                      
                      ( plaec.fuente_id || '-' || plaec.tipo_recurso ) as fuente,
                    
                     eb.ebanco_nombre as banco,
                       pecd_cuentabancaria as  nro_cuenta,
                      afp.afp_nombre as afp,
                      peaf_codigo,

                    
                    (bene.indiv_appaterno || ' ' || bene.indiv_apmaterno || ' ' || bene.indiv_nombres ) as beneficiario
                    
                  FROM planillas.planilla_empleado_concepto plaec
                  INNER JOIN planillas.planilla_empleados plaemp ON plaemp.plaemp_id = plaec.plaemp_id AND plaemp.plaemp_estado = 1 ";


                  if(trim($params['indiv_id']) != '')
                  {
                      $sql.=" AND plaemp.indiv_id = ".trim($params['indiv_id']);
                  }

      $sql.=" 
                  INNER JOIN public.individuo indiv ON plaemp.indiv_id = indiv.indiv_id 
                  INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_anio = '".$anio."' AND pla.pla_mes IN ('".$mes_in."') AND pla.plati_id IN(".$plati_in.") 
                  INNER JOIN planillas.planilla_movimiento plamo  ON pla.pla_id = plamo.pla_id AND plamo.plaes_id = ".$PLANILLA_ESTADO_MINIMO." AND plamo.plamo_estado = 1
                  INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id 

                  LEFT JOIN planillas.empleado_concepto empcon ON empcon.conc_id = concs.conc_id AND plaemp.indiv_id = empcon.indiv_id  AND empcon_estado = 1
                  LEFT JOIN planillas.empleado_concepto_beneficiario ecb ON empcon.empcon_id = ecb.empcon_id AND ecb_estado = 1
                  LEFT JOIN public.individuo bene ON  ecb.indiv_id_b = bene.indiv_id 

                  INNER JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                  LEFT JOIN sag.tarea ON tarea.tarea_id = plaec.tarea_id 
                  LEFT JOIN rh.persona_cuenta_deposito cuenta ON cuenta.pecd_id = plaemp.pecd_id 
                  LEFT JOIN public.entidades_bancarias eb ON cuenta.ebanco_id = eb.ebanco_id  
                  LEFT JOIN  rh.persona_pension peaf ON plaemp.indiv_id = peaf.pers_id AND peaf.peaf_estado = 1 
                  LEFT JOIN rh.afp ON peaf.afp_id = afp.afp_id 

                  WHERE plaec.plaec_estado = 1 AND plaec.plaec_marcado = 1 AND plaec.plaec_value > 0 AND plaec.conc_afecto = 1  

                  ORDER BY pla.plati_id, pla.pla_id, pla_codigo, indiv.indiv_id, pla.pla_id, plaemp.plaemp_id, concs.conc_tipo, concs.conc_nombre,  plaec.plaec_id 
               ";

     
          return $this->_CI->db->query($sql, array())->result_array();
     }



     public function consolidado_descuentos_porplanilla($params)
     {
       
           $sql = " SELECT gvc_nombre
                    FROM planillas.grupos_vc grupo  
                    WHERE  gvc_estado = 1 
                    ORDER BY gvc_id ";
       
           $rs = $this->_CI->db->query($sql, array())->result_array();         

           $conc_nombres_header='';   

           foreach($rs as $k => $con)
           {

             if($k>0)  $conc_nombres_header.=',';
             $conc_nombres_header.=' "DATO_'.trim($con['gvc_nombre']).'" text'; 

           }         
       
            $estado_reporte = ESTADO_PLANILLA_CERRADA;

 

            $anio = trim($params['anio']);
            $mes = $params['mes'];
            
            $in_mes_s = implode("\',\'", $mes);

            $in_mes = implode("','", $mes);
 

            $sql = "    SELECT 

                        plati.plati_nombre,

                         ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,

                         (clasi.tipo_transaccion ||'.'|| clasi.generica || '.' || clasi.subgenerica || '.' || clasi.subgenerica_det || '.' || clasi.especifica || '.' || clasi.especifica_det || ' - ' || clasi.descripcion ) as clasificador,

                        datos.*,

                        datos_ingreso_neto.ingresos as \"DATO_TOTALINGRESO\",
                        datos_ingreso_neto.descuentos as \"DATO_TOTALDESCUENTO\",
                        datos_ingreso_neto.aportacion as \"DATO_TOTALAPORTACION\",
                        ( datos_ingreso_neto.ingresos - datos_ingreso_neto.descuentos ) as \"DATO_NETO\",
                        (datos_ingreso_neto.ingresos + datos_ingreso_neto.aportacion) as \"DATO_COSTOTOTAL\"

                        FROM (
                         
                          SELECT *  FROM  crosstab( '  SELECT (pla.pla_id ||''_'' || plaec.fuente_id ||''_'' || plaec.tipo_recurso || ''_'' || COALESCE(plasi.siaf,'''') ) as indice, 

                                                               pla.pla_id, 
                                                               plaec.fuente_id, 
                                                               plaec.tipo_recurso, 
                                                               plasi.siaf,
                                                               grupo.gvc_id, 
                                                               SUM(plaec_value) as monto

                                                     FROM planillas.planilla_empleado_concepto plaec 
                                                     INNER JOIN planillas.conceptos concs ON plaec.conc_id = concs.conc_id AND concs.conc_estado = 1
                                                     INNER JOIN planillas.grupos_vc grupo ON concs.gvc_id = grupo.gvc_id AND   gvc_estado = 1  
                                                     INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp.plaemp_estado = 1
                                                     INNER JOIN planillas.planillas pla On plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1 AND pla.pla_anio = ''".$anio."'' AND pla.pla_mes IN(''".$in_mes_s."'')
                                                     INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".$estado_reporte."
                                                     LEFT JOIN planillas.planilla_siaf plasi ON pla.pla_id = plasi.pla_id  AND plaec.fuente_id = plasi.fuente_id AND plaec.tipo_recurso = plasi.tipo_recurso AND plasiaf_estado = 1 AND pla.ano_eje = plasi.ano_eje
                                                     WHERE plaec_estado = 1 AND plaec_marcado = 1  AND plaec_value > 0 AND concs.conc_afecto = 1

                                                     GROUP BY pla.pla_id, plaec.fuente_id, plaec.tipo_recurso, plasi.siaf, grupo.gvc_id
                                                     ORDER BY pla.pla_id, plaec.fuente_id, plaec.tipo_recurso, plasi.siaf, grupo.gvc_id 
                         
                                              ',' SELECT gvc_id FROM planillas.grupos_vc grupo WHERE gvc_estado = 1 ORDER BY gvc_id  ' 
                                               ) 
                                              AS(
                                                indice text,
                                                pla_id int,
                                                fuente_id text,       
                                                tipo_recurso text, 
                                                siaf text,
                                                  ".$conc_nombres_header."  

                             ) 

                       ) as datos 
                        

                       LEFT JOIN (
                              
                              SELECT 

                                  pla.pla_id, 
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

                              FROM planillas.planilla_empleado_concepto plaec 
                              INNER JOIN planillas.planilla_empleados plaemp ON plaec.plaemp_id = plaemp.plaemp_id AND plaemp_estado = 1 
                              INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla_estado = 1  AND pla.pla_anio = '".$anio."'  AND pla.pla_mes IN ('".$in_mes."')
                              INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".$estado_reporte." 
                              WHERE plaec_estado = 1 AND plaec_marcado = 1  AND plaec_value > 0 AND plaec.conc_afecto = 1
                              GROUP BY pla.pla_id, plaec.fuente_id, plaec.tipo_recurso
                              ORDER BY pla.pla_id, plaec.fuente_id, plaec.tipo_recurso


                       ) as datos_ingreso_neto ON datos.pla_id = datos_ingreso_neto.pla_id AND datos.fuente_id = datos_ingreso_neto.fuente_id AND datos.tipo_recurso = datos_ingreso_neto.tipo_recurso
    
                    INNER JOIN planillas.planillas pla ON  datos.pla_id = pla.pla_id  
                    LEFT JOIN pip.especifica_det clasi ON clasi.id_clasificador = pla.clasificador_id AND pla.ano_eje = clasi.ano_eje 
                    INNER JOIN planillas.planilla_tipo plati ON pla.plati_id = plati.plati_id 
                    ORDER BY pla.plati_id, pla_codigo, datos.fuente_id, datos.tipo_recurso   

                    ";

       


            $rs = $this->_CI->db->query($sql, array())->result_array();
       
            return $rs;

    } 


}