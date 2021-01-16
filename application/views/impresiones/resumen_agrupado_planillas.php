<?php
 

$conceptos_header          =  array();
 
$sql = " SELECT * FROM planillas.planilla_siaf 
         WHERE siaf = ? AND plasiaf_estado = 1 AND ano_eje = ? ";
$rs = $this->db->query($sql, array($siaf, $anio ))->result_array();

$planillas = array();
foreach($rs as $reg)
{
  $planillas[] = $reg['pla_id'];
}


if(sizeof($planillas) == 0)
{
    die('No se encontraron planillas asociadas al registro SIAF especificado.');
}
 
$in_planillas = implode(',', $planillas);

$planilla_info = $this->planilla->get($planillas[0]);  
$plati_nombre = trim(strtoupper($planilla_info['tipo']));


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
                   INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.conc_afecto = 1 AND pec.plaec_marcado = 1 AND pec.plaec_value > 0 AND pec.conc_tipo = ?
                   INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id  
                   LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       

                   WHERE pe.pla_id IN (".$in_planillas.") AND pe.plaemp_estado = 1
          
          ) as rs_d 

      ) rs_x

      LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 
      ORDER BY nombre   ";


$conceptos_header['ingresos']       = $this->db->query($sql, array(TIPOCONCEPTO_INGRESO ))->result_array();
$conceptos_header['descuentos']     = $this->db->query($sql, array(TIPOCONCEPTO_DESCUENTO ))->result_array();
$conceptos_header['aportaciones']   = $this->db->query($sql, array(TIPOCONCEPTO_APORTACION ))->result_array();

 
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
             INNER JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1  AND pec.plaec_value > 0 AND pec.conc_afecto = 1
             INNER JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
             LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                      
            WHERE pe.pla_id IN (".$in_planillas.")  AND pe.plaemp_estado = 1
          ) as rs_d 

      ) rs_x

      LEFT JOIN planillas.conceptos conc ON rs_x.conc_id = conc.conc_id 

   
      ORDER BY conc_id

";
                       

$conceptos_planilla = $this->db->query($sql, array($planilla_id))->result_array(); 

$sql_result = '';
foreach($conceptos_planilla as $k => $reg)
{

    if($k>0)  $sql_result.=',';
    $sql_result.=' "'.$reg['conc_id'].'" text'; 

}


$sql_main = " SELECT  

                    ( substring( pla.pla_anio from 3 for 2)  || pla.pla_mes ||  pla.pla_codigo || pla.pla_tipo || pla.plati_id )  as pla_codigo,
                    main.*  

              FROM  (     

                SELECT * FROM crosstab('

                                 SELECT   t_rs.pla_id, t_rs.conc_id, SUM(t_rs.monto) as monto  

                                   FROM (

                                     SElECT 
                                        pemp.pla_id,

                                         ( CASE WHEN ggvc.gvcg_id is null THEN
                                              pec.conc_id 
                                        ELSE
                                             ggvc.conc_id_ref     
                                        END
                                         ) as conc_id,

                                         pec.plaec_value as monto

                                     FROM  planillas.planilla_empleados pemp  
                                     LEFT JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pemp.plaemp_id  AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND  pec.conc_afecto = 1 AND pec.plaec_value > 0
                                     LEFT JOIN planillas.conceptos concs ON pec.conc_id = concs.conc_id
                                     LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON concs.gvc_id = ggvc.gvc_id AND gvcg_estado = 1
                                     WHERE  pemp.plaemp_estado = 1 AND pemp.pla_id IN (".$in_planillas.")

                                   ) t_rs

                                  GROUP BY  t_rs.pla_id, t_rs.conc_id
                                  ORDER BY  t_rs.pla_id, t_rs.conc_id
                   ','

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
                       LEFT JOIN planillas.planilla_empleado_concepto pec ON pec.plaemp_id = pe.plaemp_id AND pec.plaec_estado = 1 AND pec.plaec_marcado = 1 AND pec.conc_afecto = 1 AND pec.plaec_value > 0
                       LEFT JOIN planillas.conceptos con ON pec.conc_id = con.conc_id 
                       LEFT JOIN planillas.concepto_agrupadoresumen  ggvc ON con.gvc_id = ggvc.gvc_id  AND gvcg_estado = 1       
                                          
                       WHERE pe.pla_id IN (".$in_planillas.") AND plaemp_estado = 1

                       ORDER BY conc_id
                     
                       ) as rs_d 


      
                 ')
                as (
                    pla_id int,
                   ".$sql_result."
                    
                )

            ) as main 
  

            LEFT JOIN  planillas.planillas pla ON main.pla_id = pla.pla_id  

            ORDER BY  pla.pla_id



";


$resumen = $this->db->query($sql_main, array())->result_array(); 


$totales = array( 
                  'ingresos' => array(),
                  'descuentos' => array(),
                  'aportaciones' => array(),
                  'afps' => array()

                  );

$this->pdf->initialize('L','mm','A3');
 
$this->pdf->paginador = true;

$this->pdf->AliasNbPages();
$this->pdf->AddPage();
$this->pdf->SetTopMargin(0);

$alto_linea = 4;

$this->pdf->ln();
$this->pdf->SetFont('Arial','B',11);

 

$this->pdf->Cell(400,6,'RESUMEN POR REGISTRO SIAF','',0,'C');
$this->pdf->ln();
$this->pdf->Cell(400,6, $plati_nombre, '',0,'C');
$this->pdf->ln();
$this->pdf->ln();
$this->pdf->SetFont('Arial','B',9);
$this->pdf->Cell(30,6, '','',0,'L');
$this->pdf->Cell(300,6, 'REGISTRO SIAF: '.$siaf,'',0,'L');
  
  
$ws = array(10,80,35);


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
  

 
$ancho_celda = 310/$tceldas;
$font_size   = 6;
$alto_linea  = 7;

 
$this->pdf->SetFont('Arial','',$font_size);

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

$this->pdf->Cell( $ws[0],$alto_linea,' N' ,'TRL',0,'C',$fill);
$this->pdf->Cell( $ws[1],$alto_linea,' PLANILLA ', 'TRL',0,'C',$fill);
 
 
  
foreach($conceptos_header['ingresos'] as $c)
{
 	 $this->pdf->Cell($ancho_celda,$alto_linea, ( trim($c['nombre']) != '' ? substr($c['nombre'],0,6) : '-------' ), $bordes,0,'C',$fill);
}


if($wdif == 2)
{
 	for($j=1; $j<=$dif; $j++)
    {
 		 $this->pdf->Cell($ancho_celda,$alto_linea, '------' ,$bordes,0,'C',$fill);
 	}		
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
             $this->pdf->Cell($ancho_celda,$alto_linea, ( trim($c['nombre']) != '' ? substr($c['nombre'],0,6) : '-------' ),$bordes,0,'C',$fill);
          
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
            //PARA COMPLETAR EL ESPACIO EN BLANCO EN CASO QUEDE VACIO
            $this->pdf->setXY( $x,  $y );   
            $this->pdf->Cell($ancho_celda, $alto_linea,  '',$bordes,0,'C',$fill);
            $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   
       
            $x = $x + $ancho_celda;
            $y = $y - $alto_linea;  
        }
  

        $this->pdf->setXY( $x,  $y );   
        $this->pdf->Cell($ancho_celda, ($alto_linea * 2),  ' T.APORT',$bordes,0,'C',$fill); // .APORT


}
else
{

    $pos_xini_aport = $this->pdf->getX(); 
    $pos_yini_aport = $this->pdf->getY();
    $x = $this->pdf->getX();    
    $y = $this->pdf->getY();

}
 

/* REGLON DE ABAJO */
 
$this->pdf->setXY( $pos_xini,  $pos_yini + $alto_linea );

$this->pdf->Cell($ws[0],$alto_linea,'','BRL',0,'C',$fill);
$this->pdf->Cell($ws[1],$alto_linea,'','BRL',0,'C',$fill);
 


foreach($conceptos_header['descuentos'] as $c)
{
 	 $this->pdf->Cell($ancho_celda,$alto_linea, ( trim($c['nombre']) != '' ? substr($c['nombre'],0,6) : '-------' ),$bordes,0,'C',$fill);
}


if($wdif == 1)
{
 	for($j=1; $j<=$dif; $j++)
    {
 		 $this->pdf->Cell($ancho_celda,$alto_linea, '------' ,$bordes,0,'C',$fill);
 	}		
}

$this->pdf->Cell($ancho_celda,$alto_linea, 'TOT.DESC',$bordes,0,'C',$fill);
$this->pdf->Cell($ancho_celda,$alto_linea, 'A PAGAR','BRL',0,'C',$fill);
 

$this->pdf->ln();
  
/*DETALLE DE LA PLANILLA*/
$i = 0;
 
$total_sin_cuenta = 0;
$total_con_cuenta = 0;

$cantidad_sin_cuenta = 0;
$cantidad_con_cuenta = 0;

foreach($resumen as $reg)
{

    $i++;
    $total_ingresos = 0;
    $total_descuentos = 0;
    $total_aportacion = 0;
  
    $font_size   = 7;
    $alto_linea  = 7;
     
    $this->pdf->SetFont('Arial','',$font_size);  
 
     if($i>1) $this->pdf->ln();
 
     $planilla_info = $this->planilla->get($reg['pla_id']);

     $this->pdf->Cell($ws[0],$alto_linea, '('.$planilla_info['meta'].')','TRL',0,'C',false);
 	 $this->pdf->Cell($ws[1],$alto_linea, $planilla_info['pla_codigo'].' - '.substr(utf8_decode($planilla_info['tarea_nombre']), 0, 35) ,'TRL',0,'L',false); // ." ".$this->pdf->getY()
    
  
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

     $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_ingresos ,2) ,$bordes,0,'R',false); // TOTAL INGRESOS 
 

     /* SEGUNDO REGLON */
     $this->pdf->ln();
     $this->pdf->SetFont('Arial','',($font_size-1));  
     $this->pdf->Cell($ws[0],$alto_linea, $planilla_info['num_emps'],'BRL',0,'C',false);

  
     $info_abonos = $this->planilla->get_resumen_neto_por_abono($planilla_info['pla_id']);


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


     $sub_info_detalle = ' Con cuenta : '. $valores['cuenta'][0].'     '.$valores['cuenta'][1].' Sin cuenta: '.$valores['sincuenta'][0].'     '.$valores['sincuenta'][1].'     '.$planilla_info['clasificador_d'];

     $this->pdf->Cell($ws[1], $alto_linea, $sub_info_detalle,'BRL',0,'L',false);
 

     $this->pdf->SetFont('Arial','',$font_size);  

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
 
        if(sizeof($conceptos_header['aportaciones']) > 0)
        { 

               $this->pdf->setXY( $x,  $y ); 
               $this->pdf->Cell($ancho_celda,($alto_linea *2), $total_aportacion,$bordes,0,'C',false);
                       $x = $x + ($ancho_celda);

        }
 
        $encima = true;
  

        $this->pdf->setXY( $x,  $y );  
        $this->pdf->SetFont('Arial','', $font_size);
       
        $this->pdf->setXY($lx, (  $ly - $alto_linea) ); 
 

 }

 

/*  

RESUMEN 

*/ 
$this->pdf->SetFont('Arial','B', $font_size);
$this->pdf->ln();
  
$cantidad_sin_cuenta = sprintf("%12s", $cantidad_sin_cuenta);
$this->pdf->Cell($ws[0],$alto_linea, '','',0,'R',false);
$this->pdf->Cell(40,$alto_linea, '  Sin cuenta : '.$cantidad_sin_cuenta.'       S./'.$total_sin_cuenta,'',0,'R',false);

$this->pdf->Cell(40,$alto_linea, 'TOTAL','',0,'R',false);

$this->pdf->SetFont('Arial','', $font_size);

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

 $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_ingresos ,2) ,$bordes,0,'R',false); // TOTAL INGRESOS 



/* SEGUNDO REGLON */
$this->pdf->SetFont('Arial','B', $font_size);
$this->pdf->ln();
 

$cantidad_con_cuenta = sprintf("%12s", $cantidad_con_cuenta);
$this->pdf->Cell($ws[0],$alto_linea,'','',0,'R',false);
$this->pdf->Cell(40,$alto_linea, '  Con cuenta : '.$cantidad_con_cuenta.'       S./'.$total_con_cuenta,'',0,'R',false);

$this->pdf->Cell(40,$alto_linea, 'PLANILLAS','',0,'R',false); 
$this->pdf->SetFont('Arial','', $font_size);

$total_descuentos = 0;

 foreach($conceptos_header['descuentos'] as $c){

       $total_descuentos+=$totales['descuentos'][$c['conc_id']];
       $this->pdf->Cell($ancho_celda,$alto_linea,  number_format( $totales['descuentos'][$c['conc_id']] ,2) ,$bordes,0,'R',false);     
 }

     if($wdif == 1){

        for($j=1; $j<=$dif; $j++){
             $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
        }       
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

foreach($conceptos_header['aportaciones'] as $c){


       $total_aportacion +=$totales['aportaciones'][$c['conc_id']];
         $this->pdf->setXY( $x,  $y );   
        $this->pdf->Cell($ancho_celda,$alto_linea,  number_format( $totales['aportaciones'][$c['conc_id']] ,2) ,$bordes,0,'R',false);   

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
 



$this->pdf->Output();