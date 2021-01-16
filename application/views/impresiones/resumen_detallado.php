<?php

$this->pdf->initialize('L', 'mm', TAMANIO_PLANILLA_IMPRESA );
$this->pdf->paginador = false;

$this->pdf->AliasNbPages();
$this->pdf->SetTopMargin(0);


foreach($planillas as $planilla_id)
{       


        if(TAMANIO_PLANILLA_IMPRESA == 'A3')
        {
            $ancho_total = 400;
            $ws = array(11,60,35); // Ancho de, Numerador, Nombre Y Firma 
            // Los datos tienen un ancho  igual al de la celda + 4
            $numero_minimo_de_celdas = 12;
            $tamanio_fuente_cabecera = 11;
            $tamanio_letra_info_cabecera = 7;
            $tamanio_letra_celdas_cabecera = 6;
            $tamanio_fuente_celda = 7;
            $tamanio_fuente_info_registro = 7;
            $altodelinea_celda = 7;    

            $cantidad_letras_header = 10;
        }
 

        $planilla_info             = $this->planilla->get($planilla_id); 
        $conceptos_header          =  array();
        $plati_id                  = $planilla_info['plati_id'];


        $sql = " SELECT * FROM ( 

                     SELECT plaemp.plaemp_id, count(plaec.plaec_id) as nc
                     FROM   planillas.planilla_empleados plaemp 
                     LEFT JOIN planillas.planilla_empleado_concepto plaec ON plaemp.plaemp_id = plaec.plaemp_id  AND  plaec.plaec_value > 0  AND plaec.plaec_estado = 1 AND plaec.conc_afecto = 1 AND plaec.plaec_marcado = 1 
                     WHERE plaemp.pla_id = ? 
                     GROUP BY plaemp.plaemp_id 
                     ORDER BY plaemp.plaemp_id

                 ) as data 
                 WHERE data.nc = 0  
                 ";

        $rs =  $this->db->query($sql, array($planilla_id))->result_array();

        if(sizeof($rs) > 0 )
        {

           $this->pdf->AddPage();
           
           $this->pdf->ln();
           $this->pdf->Cell( 100, 6, utf8_decode('Atención, el total de ingresos y aportaciones son CERO para uno o más trabajadores de la planilla. No es posible imprimir la planilla: '.$planilla_info['pla_codigo'].'. '), '', 0, 'L');
           $this->pdf->ln();
 
 
           continue;
        }


        $sql =" SELECT rs_x.conc_id,  
                       conc.conc_planillon_nombre as nombre 

                FROM 
                (

                  SELECT  distinct(rs_d.conc_id) FROM
                  (
                    SELECT 

                       ( CASE WHEN ggvc.gvcg_id is null THEN
                                  pec.conc_id 
                            ELSE
                                  ggvc.conc_id_ref     
                            END
                                )  as conc_id 

                       FROM planillas.planilla_empleados pe 
                       INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.conc_afecto = 1 AND pec.plaec_marcado = 1 AND plaec_value > 0 AND pec.conc_tipo = ?
                       INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id  
                       LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                       
                       WHERE pe.pla_id = ? AND pe.plaemp_estado = 1 
                  
                  ) as rs_d 

              ) rs_x

              LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 

         
              ORDER BY conc.conc_orden asc, conc_id   ";


        $conceptos_header['ingresos'] = $this->db->query($sql, array(TIPOCONCEPTO_INGRESO, $planilla_id))->result_array();

        $conceptos_header['descuentos'] = $this->db->query($sql, array(TIPOCONCEPTO_DESCUENTO, $planilla_id))->result_array();

        $conceptos_header['aportaciones'] = $this->db->query($sql, array(TIPOCONCEPTO_APORTACION, $planilla_id))->result_array();

        $conceptos_header['info_vars'] = array( array('codigo' => 'afp',
                                                      'nombre' => 'PENSION')    );

        if(PLANILLA_CUENTABANCARIA)
        {
          $conceptos_header['info_vars'][] =  array('codigo' => 'cuenta_bancaria',
                                                    'nombre' => 'CUENTA'); 
          
        }
        else
        {

           $conceptos_header['info_vars'][] =  array('codigo' => 'afp_codigo',
                                                     'nombre' => 'CODIGO AFP'); 
          
        }


        $sql =" SELECT rs_x.conc_id,  conc.conc_planillon_nombre as nombre FROM 
                (

                  SELECT  distinct(rs_d.conc_id) FROM
                  (
                    SELECT 
                     ( CASE WHEN ggvc.gvcg_id is null THEN
                                pec.conc_id 
                          ELSE
                               ggvc.conc_id_ref     
                          END
                              )  as conc_id 

                     FROM planillas.planilla_empleados pe 
                     INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1  AND plaec_value > 0  AND pec.conc_afecto = 1
                     INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                     LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                              
                       WHERE pe.pla_id = ?  AND pe.plaemp_estado = 1
                  ) as rs_d 

              ) rs_x

              LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 

           
              ORDER BY conc_orden asc, conc_id

        ";
                               

        $conceptos_planilla = $this->db->query($sql, array($planilla_id))->result_array(); 

        $sql_header = '';
        foreach($conceptos_planilla as $k => $reg)
        {
             $sql_header.=' ,"'.$reg['conc_id'].'" text'; 

        }



        /* Obtenemos las variables que van a ir en el planillon */ 

        $sql = " SELECT vari_id, vari_planillon_nombre 
                 FROM planillas.variables 
                 WHERE vari_planillon = 1 AND vari_estado = 1 AND plati_id = ?
                 ORDER BY vari_orden asc  ";

        $rs = $this->db->query($sql ,array($plati_id))->result_array();
          
        $vari_id_s = array();
        $vari_id_header = array();
        $variables = array();
        $in_variables_planillon = '';
        $in_variables_header = '';

        $has_variables = true;

        if(sizeof($rs) == 0 )
        {

            $has_variables = false;

        }

        foreach($rs as $reg)
        {
            $vari_id_s[] = $reg['vari_id'];
            $vari_id_header[] = '"'.$reg['vari_planillon_nombre'].'" double precision ';
            $variables[] = $reg['vari_planillon_nombre'];
        }

        $in_variables_planillon = implode(',', $vari_id_s );
        $in_variables_header = implode(',' , $vari_id_header );


 
        $sql_main = "  SELECT (ind.indiv_appaterno ||' '|| ind.indiv_apmaterno ||' '|| ind.indiv_nombres ) as nombre_trabajador, 
                               platica_nombre as ocupacion,
                            ind.indiv_dni,
                            afp_nombre as afp, pensi.peaf_codigo as afp_codigo,  pecd_cuentabancaria as cuenta_bancaria,
                            main.*  
                    ";

        if(sizeof($vari_id_s) > 0 )
        {

            $sql_main.=" 
                                ,varis.*
                        ";

        }

        $sql_main.=" 
                    FROM  (     

                        SELECT * FROM crosstab('

                                          SELECT   t_rs.plaemp_id, t_rs.platica_id, t_rs.indiv_id, t_rs.ocupacion_label, t_rs.pecd_id, t_rs.peaf_id, t_rs.conc_id, SUM(t_rs.monto) as monto  

                                           FROM (

                                             SElECT 
                                                pemp.plaemp_id, 
                                                pemp.platica_id, 
                                                pemp.indiv_id,  
                                                pemp.pecd_id,
                                                pemp.peaf_id,

                                               ( CASE WHEN ocu.ocu_nombre is null THEN
                                                     pemp.plaemp_ocupacion_label
                                                ELSE
                                                     ocu.ocu_nombre    
                                                END
                                                 ) as ocupacion_label,


                                               ( CASE WHEN ggvc.gvcg_id is null THEN
                                                    pec.conc_id 
                                              ELSE
                                                   ggvc.conc_id_ref     
                                              END
                                               ) as conc_id,

                                                conc_orden, 
                                                pec.plaec_value as monto
                                             
                                             FROM  planillas.planilla_empleados pemp  
                                             LEFT JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pemp.plaemp_id  AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.plaec_value > 0
                                             LEFT JOIN planillas.conceptos concs ON pec.conc_id = concs.conc_id
                                             LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON concs.gvc_id = ggvc.gvc_id AND gvcg_estado = 1
                                             LEFT JOIN planillas.ocupacion ocu ON pemp.ocu_id = ocu.ocu_id 
                                             WHERE  pemp.plaemp_estado = 1 AND pemp.pla_id = ".$planilla_id." 

                                           ) t_rs

                                          GROUP BY  plaemp_id, t_rs.platica_id, indiv_id, ocupacion_label,  t_rs.pecd_id, t_rs.peaf_id, conc_id, conc_orden

                                          order by  plaemp_id, t_rs.platica_id, ocupacion_label, conc_orden asc, conc_id

                         

                        ',' 
                              SELECT rs_d.conc_id FROM
                                                                            (
                                                                              SELECT 
                                          distinct(   
                                                 (CASE WHEN ggvc.gvcg_id is null THEN
                                                  pec.conc_id 
                                            ELSE
                                                 ggvc.conc_id_ref     
                                            END) 
                                                                                    ) as conc_id

                                                                               FROM planillas.planilla_empleados pe 
                                                                               LEFT JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND plaec_value > 0 
                                                                               LEFT JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                                                                               LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                                                                                        
                                                                                 WHERE pe.pla_id =   ".$planilla_id." AND plaemp_estado = 1
                              
                                                                              ) as rs_d 
                                                                            LEFT JOIN planillas.conceptos con2 ON rs_d.conc_id = con2.conc_id 

                                       
                                      ORDER BY  con2.conc_orden asc, rs_d.conc_id
                             


              
                         ')
                        as (
                            plaemp_id int,
                            platica_id int,
                            indiv_id int,
                            ocupacion_label text,
                            pecd_id int,
                            peaf_id int
                           ".$sql_header."
                            
                        )

                    ) as main 

        ";


          if(sizeof($vari_id_s) > 0 )
          {


              $sql_main.=" 


                          LEFT JOIN (


                                SELECT * FROM crosstab('            SELECT plaev.plaemp_id, 
                                                                           vari.vari_orden, 
                                                                           plaev.vari_id,   
                                                                           SUM(plaev_valor) as valor
                                                                    
                                                                    FROM  planillas.planilla_empleado_variable plaev
                                                                    LEFT JOIN planillas.variables vari ON plaev.vari_id = vari.vari_id       
                                                                    WHERE  plaev.plaev_estado = 1 AND 
                                                                           plaev.plaemp_id IN (SELECT plaemp_id   
                                                                                               FROM planillas.planilla_empleados plaemp 
                                                                                               WHERE plaemp.pla_id = ".$planilla_id."  AND plaemp.plaemp_estado = 1 )  
                                                                            
                                                                            AND plaev.vari_id IN (".$in_variables_planillon.")

                                                                    GROUP BY plaev.plaemp_id, plaev.vari_id, vari.vari_orden
                                                                    ORDER BY plaev.plaemp_id, vari.vari_orden asc 

                                                            ', 
                                                            ' SELECT vari_id 
                                                              FROM planillas.variables 
                                                              WHERE vari_planillon = 1 AND vari_estado = 1 AND  plati_id = ".$plati_id."
                                                              ORDER BY vari_orden asc    ' ) 
                                                            as ( plaemp_id  int,
                                                                 vari_orden int,

                                                                ".$in_variables_header."  

                                                               )   

                          ) as varis ON varis.plaemp_id = main.plaemp_id 

                ";

          }


          $sql_main.="  

                    LEFT JOIN  public.individuo ind ON main.indiv_id = ind.indiv_id
                    LEFT JOIN  planillas.planilla_tipo_categoria ocu ON main.platica_id = ocu.platica_id
                    LEFT JOIN  rh.persona_pension pensi ON main.peaf_id = pensi.peaf_id  
                    LEFT JOIN  rh.afp ON afp.afp_id = pensi.afp_id   
                    LEFT JOIN  rh.persona_cuenta_deposito pcd ON main.pecd_id = pcd.pecd_id 

              ORDER BY ind.indiv_appaterno, ind.indiv_apmaterno, ind.indiv_nombres

        ";
         

         $resumen = $this->db->query($sql_main, array())->result_array(); 
         

         

        $totales = array( 
                          'ingresos' => array(),
                          'descuentos' => array(),
                          'aportaciones' => array(),
                          'afps' => array()
                          );

    
         
        $tipo_planilla_label = (trim($planilla_info['pla_tipo']) == 'r' ? 'PLANILLA UNICA DE REMUNERACIONES' : 'PLANILLA UNICA DE VACACIONES' );


        if(trim($planilla_info['plati_id']) == '12' ){

           $tipo_planilla_label = 'PLANILLA DE INCENTIVOS ECONOMICOS';
        }
         

        /*  CALCULAMOS LA DIFERENCIA ENTRE LA CANTIDAD DE CELDAS DE INGRESOS Y DESCUENTOS */
        $dif  = 0;
        $wdif = 0; 
         
        $dif = (sizeof($conceptos_header['ingresos']) >  sizeof($conceptos_header['descuentos']) ) ? (sizeof($conceptos_header['ingresos']) -  sizeof($conceptos_header['descuentos']) )  :  (sizeof($conceptos_header['descuentos']) -  sizeof($conceptos_header['ingresos']) );
        $wdif = (sizeof($conceptos_header['ingresos']) >  sizeof($conceptos_header['descuentos']) ) ? 1 : 2;



        /* 
            CALCULANDO TAMAÑO DE CELDAS Y LETRA
        */
         
        $s1 = (sizeof($conceptos_header['ingresos']) >  sizeof($conceptos_header['descuentos']) )  ?  sizeof($conceptos_header['ingresos']) : sizeof($conceptos_header['descuentos']);
        $tceldas = $s1 + 3;
        $s2 = (sizeof($conceptos_header['aportaciones']) % 2 == 0 ) ? (sizeof($conceptos_header['aportaciones']) / 2) : ( (sizeof($conceptos_header['aportaciones']) + 1) / 2);
        $tceldas += $s2;
        $s3 = (sizeof($conceptos_header['info_vars']) % 2 == 0 ) ? (sizeof($conceptos_header['info_vars']) / 2) : ( (sizeof($conceptos_header['info_vars']) + 1) / 2);
        $tceldas += $s3;


        if(sizeof($variables) > 0 )
        {

            $ancho_variables = (sizeof($variables) * 5) + 3;

        }
        else
        {
             $ancho_variables = 0;
        }

        $completar_celdas = ($tceldas < $numero_minimo_de_celdas) ?  ($numero_minimo_de_celdas - $tceldas)  : 0;
         

        $tf = 0;
        foreach($ws as $a)
        {
            $tf+=$a;
        }
          
        $ancho_celda = ($ancho_total - $tf - $ancho_variables)/($tceldas + $completar_celdas);
         
        if($ancho_celda >= 15)
        {
            $add_maxletras =  round( ($ancho_celda - 15 ) ) - 1 ;
            $cantidad_letras_header+=$add_maxletras; 
            $tamanio_fuente_celda++;

            if(round($ancho_celda - 15 ) > 8 )  $tamanio_fuente_celda++;
        }
         
          
        /*DETALLE DE LA PLANILLA*/
        $i = 0;

        foreach($resumen as $k => $reg)
        {

        	 $i++;

             if($this->pdf->getY() >= 246 || $i == 1 || ( ($k+1) == sizeof($resumen) && $this->pdf->getY() >= 230 ) )
             {
                    $this->pdf->AddPage();
           
                    $alto_linea = 4;
                    $this->pdf->ln();
                    $this->pdf->SetFont('Arial','B', $tamanio_fuente_cabecera);
         
                    $this->pdf->Cell($ancho_total,6, $tipo_planilla_label,'',0,'C');
                    $this->pdf->ln();
                    $this->pdf->Cell($ancho_total,6, trim(strtoupper($planilla_info['tipo'])),'',0,'C');
                      
                    $this->pdf->ln();


                    $this->pdf->SetFont('Arial','B', $tamanio_letra_info_cabecera);
                    $this->pdf->Cell(33,$alto_linea,'PLANILLA CODIGO','',0,'L');
                    $this->pdf->Cell(5,$alto_linea,':','',0,'C');
                    $this->pdf->SetFont('Arial','', $tamanio_letra_info_cabecera);
                    $this->pdf->Cell(30,$alto_linea,trim($planilla_info['pla_codigo']),'',0,'L');


                    $meses_label = array('01' => 'ENERO',
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
                    $this->pdf->ln();
                    $this->pdf->SetFont('Arial','B',$tamanio_letra_info_cabecera);
                    $this->pdf->Cell(33,$alto_linea,'PERIODO ','',0,'L');
                    $this->pdf->Cell(5,$alto_linea,':','',0,'C');
                    $this->pdf->SetFont('Arial','',$tamanio_letra_info_cabecera);
                    $this->pdf->Cell(30,$alto_linea,  $meses_label[trim($planilla_info['pla_mes'])].' - '.$planilla_info['pla_anio']  ,'',0,'L');


                    if(trim($planilla_info['pla_fecini']) != '')
                    {  // FECHAS 

                        $this->pdf->SetFont('Arial','B',$tamanio_letra_info_cabecera);
                        $this->pdf->Cell(13,$alto_linea,'SEMANA','',0,'L');
                        $this->pdf->Cell(2,$alto_linea,':','',0,'C');
                        $this->pdf->SetFont('Arial','',$tamanio_letra_info_cabecera);
                        $this->pdf->Cell(10,$alto_linea,'DEL','',0,'C');
                        $this->pdf->SetFont('Arial','',$tamanio_letra_info_cabecera);
                         
                        $this->pdf->Cell(15,$alto_linea,trim($planilla_info['pla_fecini']),'',0,'L');
                        //$this->pdf->SetFont('Arial','B',$tamanio_letra_info_cabecera);
                        $this->pdf->Cell(10,$alto_linea,'AL','',0,'C');
                        $this->pdf->SetFont('Arial','',$tamanio_letra_info_cabecera);
                        $this->pdf->SetFont('Arial','',$tamanio_letra_info_cabecera);
                        $this->pdf->Cell(15,$alto_linea,trim($planilla_info['pla_fecfin']),'',0,'L');
                     
                    }

                    $this->pdf->ln();
                    $this->pdf->SetFont('Arial','B',$tamanio_letra_info_cabecera);
                    $this->pdf->Cell(33,$alto_linea,'TAREA PRESUPUESTAL','',0,'L');
                    $this->pdf->Cell(5,$alto_linea,':','',0,'C');
                    $this->pdf->SetFont('Arial','',$tamanio_letra_info_cabecera);


                    if(trim($planilla_info['pla_afectacion_presu']) == PLANILLA_AFECTACION_ESPECIFICADA)
                    { 
                        $this->pdf->Cell(30,$alto_linea,trim($planilla_info['tarea_codigo']).'-'.utf8_decode(trim($planilla_info['tarea_nombre'])),'',0,'L');
                    }
                    else
                    {
                        $this->pdf->Cell(30,$alto_linea, '-----------------------------------------------------------------------  ','',0,'L');
                    }
                     
         
                    $tamanio_letra_celdas_cabecera   = 6;
                    $alto_linea  = 7;

                     
                    $this->pdf->SetFont('Arial','',$tamanio_letra_celdas_cabecera);

                    $this->pdf->ln();
                     

                    $pos_xini_aport = 0;
                    $pos_yini_aport = 0;

                    $bordes = 'TLRB';
                    /* ENCABEZADO */

                    /* REGLON DE ARRIBA */
                    $this->pdf->ln();

                    $this->pdf->SetFillColor(246, 246, 246);
                    $fill = true;


                    $pos_xini  =  $this->pdf->getX(); 
                    $pos_yini  = $this->pdf->getY();

                    $this->pdf->Cell( $ws[0],$alto_linea,'#','TRL',0,'C',$fill);
                    $this->pdf->Cell( $ws[1],$alto_linea,'APELLIDOS Y NOMBRES', 'TRL',0,'C',$fill);
                        
                    $variables_header = '';

                    if(sizeof($variables) > 0 )
                    {
                     
                        foreach ($variables as $vari)
                        {
                            $variables_header.= ' '.sprintf('%-5s',$vari);
                        }
                     
                        $this->pdf->Cell($ancho_variables,$alto_linea,  $variables_header, 'TRL', 0,'C',$fill);
                      
                    } 
                      
                    foreach($conceptos_header['ingresos'] as $c)
                    {
                         $this->pdf->Cell($ancho_celda,$alto_linea, utf8_decode(substr($c['nombre'],0, $cantidad_letras_header )),$bordes,0,'C',$fill);
                    }


                    if($wdif == 2)
                    {
                            for($j=1; $j<=$dif; $j++)
                            {
                                 $this->pdf->Cell($ancho_celda,$alto_linea, '--------------' ,$bordes,0,'C',$fill);
                            }       
                    }

                    for($j=1; $j<=$completar_celdas; $j++)
                    {
                         $this->pdf->Cell($ancho_celda,$alto_linea, '--------------' ,$bordes,0,'C',$fill);
                    }       

                    $this->pdf->Cell($ancho_celda,$alto_linea, 'TOT.ING',$bordes,0,'C',$fill);
                    $this->pdf->Cell($ancho_celda,$alto_linea, 'NETO','TRL',0,'C',$fill);
                     

                    if(sizeof($conceptos_header['aportaciones']) > 0)
                    { 

                            $pos_xini_aport = $this->pdf->getX(); 
                            $pos_yini_aport = $this->pdf->getY();
                            $x = $this->pdf->getX();    
                            $y = $this->pdf->getY();

                            $encima = true;

                            foreach($conceptos_header['aportaciones'] as $c)
                            {
                     
                                 //Se imprime en el punto X
                                 $this->pdf->setXY( $x,  $y );   
                                 $this->pdf->Cell($ancho_celda,$alto_linea, utf8_decode(substr($c['nombre'],0,6)),$bordes,0,'C',$fill);
                              
                                 //Definimos la siguiente posicion X
                                 if($encima){
                                    $x = $x;
                                    $y = $y + $alto_linea;
                                 }
                                 else{
                                    $x = $x + $ancho_celda;
                                    $y = $y - $alto_linea;
                                 }

                                 $encima = !$encima;

                            }
                     
                            if($encima===false)
                            { 
                                //paRA COMPLETAR EL ESPACIO EN BLANCO EN CASO QUEDE VACIO
                                $this->pdf->setXY( $x,  $y );   
                                $this->pdf->Cell($ancho_celda, $alto_linea,  '--------------',$bordes,0,'C',$fill);
                                $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   
                           
                                $x = $x + $ancho_celda;
                                $y = $y - $alto_linea;  
                            }

                            /*
                                $x = $x + $ancho_celda;
                                $y = $y - $alto_linea;  

                            if(sizeof($conceptos_header) % 2 > 0 )
                            {

                                  $this->pdf->setXY( $x,  $y );   
                                  $this->pdf->Cell($ancho_celda,$alto_linea,  round($x,2).'.'.round($y,2),$bordes,0,'C',$fill);
                                  $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   
                     
                            }          */


                            $this->pdf->setXY( $x,  $y );   
                            $this->pdf->Cell($ancho_celda, ($alto_linea * 2),  ' T.APORT',$bordes,0,'C',$fill); // .APORT
                     
                    }
                    else
                    {   

                       $this->pdf->Cell($ancho_celda, ($alto_linea * 2),  ' T.APORT',$bordes,0,'C',$fill); // .APORT
                       $pos_xini_aport = ($this->pdf->getX() - $ancho_celda); 
                       $pos_yini_aport = $this->pdf->getY();
                       $x = $this->pdf->getX();    
                       $y = $this->pdf->getY();
                         
                    }

                    $encima = true;  // 192 192 192
                     

                    if(sizeof($conceptos_header['aportaciones']) > 0) $x = $x + $ancho_celda;
                    /* VARIABLES */ 
                    foreach($conceptos_header['info_vars'] as $c)
                    {
                     
                         $this->pdf->setXY( $x,  $y );   
                         $this->pdf->Cell(($ancho_celda + 4),$alto_linea, substr($c['nombre'],0, $cantidad_letras_header ),$bordes,0,'C',$fill);

                         if($encima)
                         {
                            $x = $x;
                            $y = $y + $alto_linea;
                         }
                         else{
                            $x = $x + ($ancho_celda + 4);
                            $y = $y - $alto_linea;
                         }

                         $encima = !$encima;

                    }

                    if($encima===false)
                    {

                        $this->pdf->setXY( $x,  $y );   
                        $this->pdf->Cell(($ancho_celda + 4),$alto_linea,  '',$bordes,0,'C',$fill); // 
                        $this->pdf->setXY( $x + ($ancho_celda + 4) ,  $y - $alto_linea );   
                    }
                     
                     $this->pdf->setXY( $x,  $y );   

                     

                    /* FIRMA */ 
                    $this->pdf->Cell($ws[2], ($alto_linea * 2), 'FIRMA', $bordes,0,'C',$fill);
                    //$this->pdf->ln();


                    /* REGLON DE ABAJO */
                     
                    $this->pdf->setXY( $pos_xini,  $pos_yini + $alto_linea );

                    $this->pdf->Cell($ws[0],$alto_linea,'DNI','BRL',0,'C',$fill);
                    $this->pdf->Cell($ws[1],$alto_linea,'','BRL',0,'C',$fill);

                    if(sizeof($variables) > 0 )
                    {
                        $this->pdf->Cell($ancho_variables,$alto_linea,  '', 'BRL', 0,'C',$fill);
                    }


                    foreach($conceptos_header['descuentos'] as $c)
                    {
                        $this->pdf->Cell($ancho_celda,$alto_linea, utf8_decode(substr($c['nombre'],0,6)),$bordes,0,'C',$fill);
                    }


                    if($wdif == 1)
                    {

                            for($j=1; $j<=$dif; $j++){
                                 $this->pdf->Cell($ancho_celda,$alto_linea, '--------------' ,$bordes,0,'C',$fill);
                            }       
                    }

                    for($j=1; $j<=$completar_celdas; $j++)
                    {
                         $this->pdf->Cell($ancho_celda,$alto_linea, '--------------' ,$bordes,0,'C',$fill);
                    } 

                    $this->pdf->Cell($ancho_celda,$alto_linea, 'TOT.DESC',$bordes,0,'C',$fill);
                    $this->pdf->Cell($ancho_celda,$alto_linea, 'A PAGAR','BRL',0,'C',$fill);
                     


             }
            
             $total_ingresos = 0;
             $total_descuentos = 0;
             $total_aportacion = 0;
            

             // IMPRESION DE TRABAJADORES DE LA PLANILLA
            if($i==1)
            { 
                 $this->pdf->ln();
            }
            
            $this->pdf->SetFont('Arial','',$tamanio_fuente_info_registro);  
            $alto_linea  = $altodelinea_celda;
         
            if($i>1) $this->pdf->ln();

             $this->pdf->Cell($ws[0],$alto_linea, $i,'TRL',0,'C',false);
         	 $this->pdf->Cell($ws[1],$alto_linea, utf8_decode($reg['nombre_trabajador']) ,'TRL',0,'L',false); // ." ".$this->pdf->getY()
            

             if(sizeof($variables) > 0 )
             {
              
                 foreach ($variables as $vari)
                 {

                     $v = ($reg[$vari] == '' ? '0' : $reg[$vari] );
                     $variables_trabajador.= ' '.sprintf('%-5s', $v );
                 }
         
                 $this->pdf->Cell($ancho_variables,$alto_linea,  $variables_trabajador, 'TRL', 0,'C', false);

             }
             $variables_trabajador = '';

         
              
             $this->pdf->SetFont('Arial','',$tamanio_fuente_celda);  
         
             foreach($conceptos_header['ingresos'] as $c)
             {

             	 $ing = ($reg[$c['conc_id']] == '' ) ? 0 : $reg[$c['conc_id']];
             	 $total_ingresos+=$ing;

                 $totales['ingresos'][$c['conc_id']] += $ing; 

             	 $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($reg[$c['conc_id']],2) ,$bordes,0,'R',false);
                      
             }


             if($wdif == 2)
             {

             	for($j=1; $j<=$dif; $j++)
                {
             		 $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
             	}

             }

             for($j=1; $j<=$completar_celdas; $j++)
             {
                  $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C', false);
             }       


             $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_ingresos ,2) ,$bordes,0,'R',false); // TOTAL INGRESOS 
         

             /* SEGUNDO REGLON */
             $this->pdf->ln();
             $this->pdf->SetFont('Arial','',($tamanio_fuente_info_registro - 1));  
             $this->pdf->Cell($ws[0],$alto_linea,$reg['indiv_dni'],'BRL',0,'C',false);
             

             if( trim($reg['ocupacion_label']) != '' )
             {

                $sub_info_detalle = trim($reg['ocupacion_label']); 
             }
             else
             {
                $sub_info_detalle = trim($reg['ocupacion']);       
             }

             if($sub_info_detalle=='') $sub_info_detalle = '------------------------';


             $this->pdf->Cell($ws[1], $alto_linea, $sub_info_detalle,'BRL',0,'L',false);

             if(sizeof($variables) > 0 )
             {
                 $this->pdf->Cell($ancho_variables,$alto_linea,  '', 'BRL', 0,'C', false);
             }

             $this->pdf->SetFont('Arial','',$tamanio_fuente_celda);  

             foreach($conceptos_header['descuentos'] as $c)
             {

             	 $des = ($reg[$c['conc_id']] == '' ) ?  0 : $reg[$c['conc_id']];
             	 $total_descuentos+=$des;
                 $totales['descuentos'][$c['conc_id']] += $des; 

             	 $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($reg[$c['conc_id']],2) ,$bordes,0,'R',false);
                       
             }

             if($wdif == 1)
             {

             	for($j=1; $j<=$dif; $j++)
                {
             		 $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
             	}		
             }

             for($j=1; $j<=$completar_celdas; $j++)
             {
                  $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C', false);
             }       



            $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_descuentos,2) ,$bordes,0,'R',false); // TOTAL DESCUENTOS 


            /* NETO A PAGAR */
            $neto = $total_ingresos - $total_descuentos;
            $x = $this->pdf->getX();	
            $y = $this->pdf->getY();
            $this->pdf->setXY( $x,  $y - $alto_linea );
            $this->pdf->Cell($ancho_celda, ($alto_linea * 2), number_format($neto,2) ,$bordes,0,'R',false);

            $lx =  $x;
            $ly = $y;


            $this->pdf->setX($pos_xini_aport );
            $x = $this->pdf->getX();    
            $y = $this->pdf->getY();

            $encima = true;
            foreach($conceptos_header['aportaciones'] as $c)
            {


                 $this->pdf->setXY( $x,  $y );   
                 $apor = ($reg[$c['conc_id']] == '' ) ?  0 : $reg[$c['conc_id']];

                 $totales['aportaciones'][$c['conc_id']] += $apor; 

                 $total_aportacion+= $apor;
                 $this->pdf->Cell($ancho_celda,$alto_linea, number_format($reg[$c['conc_id']],2) ,$bordes,0,'R',false);


                 if($encima){
                    $x = $x;
                    $y = $y + $alto_linea;
                 }
                 else{
                    $x = $x + $ancho_celda;
                    $y = $y - $alto_linea;
                 }

                 $encima = !$encima;

            }


            if( !$encima){
         
                    $this->pdf->setXY( $x,  $y );   
                    $this->pdf->Cell($ancho_celda,$alto_linea,  '',$bordes,0,'C',false);
                    $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   

                    $x = $x + $ancho_celda;
                    $y = $y - $alto_linea;
              }



         

           $this->pdf->setXY( $x,  $y ); 
           $this->pdf->Cell($ancho_celda,($alto_linea *2), number_format($total_aportacion,2),$bordes,0,'C',false);
           $x = $x + ($ancho_celda);
         



                $encima = true;
                 

             //   $y = $y - $alto_linea;
                $this->pdf->SetFont('Arial','', $tamanio_fuente_info_registro );
                
                foreach($conceptos_header['info_vars'] as $c)
                {

                     $this->pdf->setXY( $x,  $y );   
                     $this->pdf->Cell(($ancho_celda +4 ),$alto_linea, substr($reg[$c['codigo']],0,15),$bordes,0,'C',false);

                     if($encima)
                     {
                        $x = $x;
                        $y = $y + $alto_linea;
                     }
                     else
                     {
                        $x = $x + ($ancho_celda +4 );
                        $y = $y - $alto_linea;
                     }

                     $encima = !$encima;

                }

                 if($encima === FALSE)
                 {
                    $this->pdf->setXY( $x,  $y );   
                    $this->pdf->Cell(($ancho_celda +4 ),$alto_linea,  '',$bordes,0,'C',false);
                    $this->pdf->setXY( $x + ($ancho_celda +4 ) ,  $y - $alto_linea );   
                 }

                 $this->pdf->setXY( $x,  $y );  
                 $this->pdf->SetFont('Arial','', $tamanio_fuente_info_registro);
              

                 $this->pdf->Cell($ws[2], ($alto_linea * 2 ), ' ', $bordes,0,'L',false); // Firma: ____________________ 
                 $this->pdf->setXY($lx, (  $ly - $alto_linea) ); 
         

         }

         

        /*  

        RESUMEN 

        */ 
        $this->pdf->SetFont('Arial','B', $tamanio_fuente_info_registro);
        $this->pdf->ln();
        $this->pdf->Cell($ws[0],$alto_linea, '', '',0,'C',false);

        if(sizeof($variables) > 0 )
        {

            $this->pdf->Cell($ancho_variables,$alto_linea, '', '',0,'C',false);
        }

        $this->pdf->Cell($ws[1],$alto_linea, 'TOTAL','',0,'R',false);

        $this->pdf->SetFont('Arial','', $tamanio_fuente_celda);

        $total_ingresos = 0;
          
        foreach($conceptos_header['ingresos'] as $c){
            
                 $total_ingresos+=$totales['ingresos'][$c['conc_id']];
                $this->pdf->Cell($ancho_celda,$alto_linea,  number_format( $totales['ingresos'][$c['conc_id']] ,2) ,$bordes,0,'R',false);

        }

         if($wdif == 2){

                for($j=1; $j<=$dif; $j++){
                     $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
                }       
         }

         for($j=1; $j<=$completar_celdas; $j++)
         {
              $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
         }       


         $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_ingresos ,2) ,$bordes,0,'R',false); // TOTAL INGRESOS 



        /* SEGUNDO REGLON */
        $this->pdf->SetFont('Arial','B', $tamanio_fuente_info_registro);
        $this->pdf->ln();
        $this->pdf->Cell($ws[0],$alto_linea,'','',0,'C',false);

        if(sizeof($variables) > 0 )
        {
            $this->pdf->Cell($ancho_variables,$alto_linea, '', '',0,'C',false);
        }

        $this->pdf->Cell($ws[1], $alto_linea, 'PLANILLAS','',0,'R',false);
        $this->pdf->SetFont('Arial','', $tamanio_fuente_celda);

        $total_descuentos = 0;

         foreach($conceptos_header['descuentos'] as $c)
         {

           $total_descuentos+=$totales['descuentos'][$c['conc_id']];
           $this->pdf->Cell($ancho_celda,$alto_linea,  number_format( $totales['descuentos'][$c['conc_id']] ,2) ,$bordes,0,'R',false);     
         }

         if($wdif == 1){

            for($j=1; $j<=$dif; $j++){
                 $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
            }       
         }

         for($j=1; $j<=$completar_celdas; $j++)
         {
              $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
         }       



        $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_descuentos,2) ,$bordes,0,'R',false); // TOTAL DESCUENTOS 


            /* NETO A PAGAR */
         $neto = $total_ingresos - $total_descuentos;
         $x = $this->pdf->getX();    
         $y = $this->pdf->getY();
         $this->pdf->setXY( $x,  $y - $alto_linea );
         $this->pdf->Cell($ancho_celda, ($alto_linea * 2), number_format($neto,2) ,$bordes,0,'R',false);

         $lx =  $x;
         $ly  = $y;


        $this->pdf->setX($pos_xini_aport );
        $x = $this->pdf->getX();    
        $y = $this->pdf->getY();

        $encima = true;
        $total_aportacion = 0;

        foreach($conceptos_header['aportaciones'] as $c)
        {
         
             $total_aportacion +=$totales['aportaciones'][$c['conc_id']];
             $this->pdf->setXY( $x,  $y );   
             $this->pdf->Cell($ancho_celda,$alto_linea,  number_format( $totales['aportaciones'][$c['conc_id']] ,2) ,$bordes,0,'R',false);   

             if($encima)
             {
                $x = $x;
                $y = $y + $alto_linea;
             }
             else
             {
                $x = $x + $ancho_celda;
                $y = $y - $alto_linea;
             }

             $encima = !$encima;

        }


        if(!$encima)
        {
            $this->pdf->setXY( $x,  $y );   
            $this->pdf->Cell($ancho_celda,$alto_linea,  '',$bordes,0,'C',false);
            $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   
            $x = $x + $ancho_celda;
            $y = $y - $alto_linea;
        }


        if(sizeof($conceptos_header['aportaciones']) > 0)
        {                 
             $this->pdf->setXY( $x,  $y ); 
             $this->pdf->Cell($ancho_celda,($alto_linea *2), $total_aportacion,$bordes,0,'C',false);
        }

        $this->pdf->ln();
 

       $info_abonos = $this->planilla->get_resumen_neto_por_abono($planilla_id);


       $valores = array();


       foreach ($info_abonos as $regabono)
       {
       
            if($regabono['estado'] == '1')
            {

                $valores['cuenta'] = array($regabono['cantidad'], $regabono['total'] );

            } 

            if($regabono['estado'] == '0')
            {
               $valores['sincuenta'] = array($regabono['cantidad'], $regabono['total'] );
            }      
       }


       if( !is_array($valores['cuenta']) )
       {

           $valores['cuenta'] = array( 0, '0.00'); 
       }


       if( !is_array($valores['sincuenta']) )
       {

           $valores['sincuenta'] = array( 0, '0.00'); 
       }   


       $total_sin_cuenta+= ($valores['sincuenta'][1] == '') ? 0 : $valores['sincuenta'][1];
       $total_con_cuenta+= ($valores['cuenta'][1] == '') ? 0 : $valores['cuenta'][1];


       $cantidad_sin_cuenta+= ($valores['sincuenta'][0] == '') ? 0 : $valores['sincuenta'][0];
       $cantidad_con_cuenta+= ($valores['cuenta'][0] == '') ? 0 : $valores['cuenta'][0];


       $info_abonos_label = ' Con cuenta : '. $valores['cuenta'][0].'     '.$valores['cuenta'][1];
  
       $this->pdf->Cell( 100 , ($alto_linea -1),  $info_abonos_label, '',0,'L',false);
       $this->pdf->ln();

       $info_abonos_label = ' Sin cuenta: '.$valores['sincuenta'][0].'     '.$valores['sincuenta'][1];
       
       $this->pdf->Cell( 100 , ($alto_linea -1),  $info_abonos_label, '',0,'L',false);
       $this->pdf->ln(); 


       $this->pdf->ln();



         $this->pdf->Cell( 60 ,($alto_linea ), '', '',0,'C',false);

         $this->pdf->Cell( 29 ,($alto_linea ), 'VoBo Presupuesto','T',0,'C',false);

         $this->pdf->Cell( 29 ,($alto_linea ), '', '',0,'C',false);

          $this->pdf->Cell( 29 ,($alto_linea ), 'VoBo Contabilidad','T',0,'C',false);

         $this->pdf->Cell( 29 ,($alto_linea ), '', '',0,'C',false);

          $this->pdf->Cell( 29 ,($alto_linea ), 'VoBo Administracion','T',0,'C',false);



         $this->pdf->Cell( 29 ,($alto_linea ), '', '',0,'C',false);

          $this->pdf->Cell( 29 ,($alto_linea ), 'VoBo Tesoreria','T',0,'C',false);



         $this->pdf->Cell( 29 ,($alto_linea ), '', '',0,'C',false);

          $this->pdf->Cell( 29 ,($alto_linea ), 'VoBo RR.HH','T',0,'C',false);


}


$this->pdf->Output();