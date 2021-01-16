<?php
 
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

$alto_linea = 3;

$this->pdf->ln();
$this->pdf->SetFont('Arial','B',9);
$this->pdf->Cell(400,4,'PLANILLA UNICA DE REMUNERACIONES','',0,'C');
$this->pdf->ln();
$this->pdf->Cell(400,4, trim(strtoupper($planilla_info['tipo'])),'',0,'C');
  
$this->pdf->ln();

$this->pdf->SetFont('Arial','B', 5);
$this->pdf->Cell(33,$alto_linea,'PLANILLA CODIGO','',0,'L');
$this->pdf->Cell(5,$alto_linea,':','',0,'C');
$this->pdf->SetFont('Arial','', 5);
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
$this->pdf->SetFont('Arial','B',5);
$this->pdf->Cell(33,$alto_linea,'PERIODO ','',0,'L');
$this->pdf->Cell(5,$alto_linea,':','',0,'C');
$this->pdf->SetFont('Arial','',5);
$this->pdf->Cell(30,$alto_linea,  $meses_label[trim($planilla_info['pla_mes'])].' - '.$planilla_info['pla_anio']  ,'',0,'L');


if(trim($planilla_info['pla_fecini']) != ''){  // FECHAS 

    $this->pdf->SetFont('Arial','B',5);
    $this->pdf->Cell(13,$alto_linea,'SEMANA','',0,'L');
    $this->pdf->Cell(2,$alto_linea,':','',0,'C');
    $this->pdf->SetFont('Arial','',5);
    $this->pdf->Cell(10,$alto_linea,'DEL','',0,'C');
    $this->pdf->SetFont('Arial','',5);
     
    $this->pdf->Cell(15,$alto_linea,trim($planilla_info['pla_fecini']),'',0,'L');
    //$this->pdf->SetFont('Arial','B',5);
    $this->pdf->Cell(10,$alto_linea,'AL','',0,'C');
    $this->pdf->SetFont('Arial','',5);
    $this->pdf->SetFont('Arial','',5);
    $this->pdf->Cell(15,$alto_linea,trim($planilla_info['pla_fecfin']),'',0,'L');


}

$this->pdf->ln();
$this->pdf->SetFont('Arial','B',5);
$this->pdf->Cell(33,$alto_linea,'TAREA PRESUPUESTAL','',0,'L');
$this->pdf->Cell(5,$alto_linea,':','',0,'C');
$this->pdf->SetFont('Arial','',5);


if(trim($planilla_info['pla_afectacion_presu']) == PLANILLA_AFECTACION_ESPECIFICADA){ 
    $this->pdf->Cell(30,$alto_linea,trim($planilla_info['tarea_codigo']).'-'.utf8_decode(trim($planilla_info['tarea_nombre'])),'',0,'L');
}
else{
    $this->pdf->Cell(30,$alto_linea, '-----------------------------------------------------------------------  ','',0,'L');
}

 




 
$ws = array(10,60,35);


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
 
$ancho_celda = 295/$tceldas;
$font_size   = 6;
$alto_linea  = 5;

 
$this->pdf->SetFont('Arial','',$font_size);

$this->pdf->ln();



$pos_xini_aport = 0;
$pos_yini_aport = 0;

$bordes = 'TLRB';
/* ENCABEZADO */

/* REGLON DE ARRIBA */
$this->pdf->ln();

$this->pdf->SetFillColor(80, 192, 192);
$fill = true;


$pos_xini  =  $this->pdf->getX(); 
$pos_yini  = $this->pdf->getY();

$this->pdf->Cell( $ws[0],$alto_linea,'N','TRL',0,'C',$fill);
$this->pdf->Cell( $ws[1],$alto_linea,'APELLIDOS Y NOMBRES', 'TRL',0,'C',$fill);
 


foreach($conceptos_header['ingresos'] as $c){
 	 $this->pdf->Cell($ancho_celda,$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);

}


if($wdif == 2){

     	for($j=1; $j<=$dif; $j++){
     		 $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',$fill);
     	}		
}

$this->pdf->Cell($ancho_celda,$alto_linea, 'INGRESOS',$bordes,0,'C',$fill);
$this->pdf->Cell($ancho_celda,$alto_linea, 'NETO','TRL',0,'C',$fill);
  


if(sizeof($conceptos_header['aportaciones']) > 0){ 

        $pos_xini_aport = $this->pdf->getX(); 
        $pos_yini_aport = $this->pdf->getY();
        $x = $this->pdf->getX();    
        $y = $this->pdf->getY();

        $encima = true;

        foreach($conceptos_header['aportaciones'] as $c){
 
             //Se imprime en el punto X
             $this->pdf->setXY( $x,  $y );   
             $this->pdf->Cell($ancho_celda,$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);
          
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
 
        if($encima===false){ 
            //paRA COMPLETAR EL ESPACIO EN BLANCO EN CASO QUEDE VACIO
            $this->pdf->setXY( $x,  $y );   
            $this->pdf->Cell($ancho_celda, $alto_linea,  '',$bordes,0,'C',$fill);
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
else{
       $pos_xini_aport = $this->pdf->getX(); 
        $pos_yini_aport = $this->pdf->getY();
        $x = $this->pdf->getX();    
        $y = $this->pdf->getY();


}

$encima = true;
 

if(sizeof($conceptos_header['aportaciones']) > 0) $x = $x + $ancho_celda;
/* VARIABLES */ 
foreach($conceptos_header['info_vars'] as $c){


     $this->pdf->setXY( $x,  $y );   
     $this->pdf->Cell(($ancho_celda + 4),$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);

     if($encima){
        $x = $x;
        $y = $y + $alto_linea;
     }
     else{
        $x = $x + ($ancho_celda + 4);
        $y = $y - $alto_linea;
     }

     $encima = !$encima;

}

 if( !$encima){

        $this->pdf->setXY( $x,  $y );   
        $this->pdf->Cell(($ancho_celda + 4),$alto_linea,  '',$bordes,0,'C',$fill);
        $this->pdf->setXY( $x + ($ancho_celda + 4) ,  $y - $alto_linea );   

 }
 








/* FIRMA */ 
$this->pdf->Cell($ws[2], ($alto_linea * 2), 'FIRMA', $bordes,0,'C',$fill);
//$this->pdf->ln();


/* REGLON DE ABAJO */
 
$this->pdf->setXY( $pos_xini,  $pos_yini + $alto_linea );

$this->pdf->Cell($ws[0],$alto_linea,'DNI','BRL',0,'C',$fill);
$this->pdf->Cell($ws[1],$alto_linea,'','BRL',0,'C',$fill);
foreach($conceptos_header['descuentos'] as $c){
 	 $this->pdf->Cell($ancho_celda,$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);
}


if($wdif == 1){

     	for($j=1; $j<=$dif; $j++){
     		 $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',$fill);
     	}		
}

$this->pdf->Cell($ancho_celda,$alto_linea, 'DESC.',$bordes,0,'C',$fill);
$this->pdf->Cell($ancho_celda,$alto_linea, 'A PAGAR','BRL',0,'C',$fill);
 






  


$this->pdf->ln();
  
/*DETALLE DE LA PLANILLA*/
$i = 0;

foreach($resumen as $reg){

	 $i++;
	 $total_ingresos = 0;
	 $total_descuentos = 0;
     $total_aportacion = 0;

     if($this->pdf->getY() >= 258){

        $this->pdf->AddPage();

        // IMPRESION DEL HEADER @OPTIMIZAR 

                $this->pdf->SetTopMargin(0);

                $alto_linea = 3;

                $this->pdf->ln();
                $this->pdf->SetFont('Arial','B',9);
                $this->pdf->Cell(400,4,'PLANILLA UNICA DE REMUNERACIONES','',0,'C');
                $this->pdf->ln();
                $this->pdf->Cell(400,4, trim(strtoupper($planilla_info['tipo'])),'',0,'C');
                 
                 

                $this->pdf->ln();

                $this->pdf->SetFont('Arial','B', 5);
                $this->pdf->Cell(33,$alto_linea,'PLANILLA CODIGO','',0,'L');
                $this->pdf->Cell(5,$alto_linea,':','',0,'C');
                $this->pdf->SetFont('Arial','', 5);
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
                $this->pdf->SetFont('Arial','B',5);
                $this->pdf->Cell(33,$alto_linea,'PERIODO ','',0,'L');
                $this->pdf->Cell(5,$alto_linea,':','',0,'C');
                $this->pdf->SetFont('Arial','',5);
                $this->pdf->Cell(30,$alto_linea,  $meses_label[trim($planilla_info['pla_mes'])].' - '.$planilla_info['pla_anio']  ,'',0,'L');


                if(trim($planilla_info['pla_fecini']) != ''){  // FECHAS 

                    $this->pdf->SetFont('Arial','B',5);
                    $this->pdf->Cell(13,$alto_linea,'SEMANA','',0,'L');
                    $this->pdf->Cell(2,$alto_linea,':','',0,'C');
                    $this->pdf->SetFont('Arial','',5);
                    $this->pdf->Cell(10,$alto_linea,'DEL','',0,'C');
                    $this->pdf->SetFont('Arial','',5);
                     
                    $this->pdf->Cell(15,$alto_linea,trim($planilla_info['pla_fecini']),'',0,'L');
                    //$this->pdf->SetFont('Arial','B',5);
                    $this->pdf->Cell(10,$alto_linea,'AL','',0,'C');
                    $this->pdf->SetFont('Arial','',5);
                    $this->pdf->SetFont('Arial','',5);
                    $this->pdf->Cell(15,$alto_linea,trim($planilla_info['pla_fecfin']),'',0,'L');


                }

                $this->pdf->ln();
                $this->pdf->SetFont('Arial','B',5);
                $this->pdf->Cell(33,$alto_linea,'TAREA PRESUPUESTAL','',0,'L');
                $this->pdf->Cell(5,$alto_linea,':','',0,'C');
                $this->pdf->SetFont('Arial','',5);


                if(trim($planilla_info['pla_afectacion_presu']) ==PLANILLA_AFECTACION_ESPECIFICADA){ 
                    $this->pdf->Cell(30,$alto_linea,trim($planilla_info['tarea_codigo']).'-'.trim($planilla_info['tarea_nombre']),'',0,'L');
                }
                else{
                    $this->pdf->Cell(30,$alto_linea, '-----------------------------------------------------------------------  ','',0,'L');
                }

                 




                 
                $ws = array(10,60,35);


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
                 
                $ancho_celda = 295/$tceldas;
                $font_size   = 6;
                $alto_linea  = 5;

                 
                $this->pdf->SetFont('Arial','',$font_size);

                $this->pdf->ln();



                $pos_xini_aport = 0;
                $pos_yini_aport = 0;

                $bordes = 'TLRB';
                /* ENCABEZADO */

                /* REGLON DE ARRIBA */
                $this->pdf->ln();

                $this->pdf->SetFillColor(80, 192, 192);
                $fill = true;


                $pos_xini  =  $this->pdf->getX(); 
                $pos_yini  = $this->pdf->getY();

                $this->pdf->Cell( $ws[0],$alto_linea,'N','TRL',0,'C',$fill);
                $this->pdf->Cell( $ws[1],$alto_linea,'APELLIDOS Y NOMBRES', 'TRL',0,'C',$fill);
                 


                foreach($conceptos_header['ingresos'] as $c){
                     $this->pdf->Cell($ancho_celda,$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);

                }


                if($wdif == 2){

                        for($j=1; $j<=$dif; $j++){
                             $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',$fill);
                        }       
                }

                $this->pdf->Cell($ancho_celda,$alto_linea, 'INGRESOS',$bordes,0,'C',$fill);
                $this->pdf->Cell($ancho_celda,$alto_linea, 'NETO','TRL',0,'C',$fill);
                  
                if(sizeof($conceptos_header['aportaciones']) > 0){ 

                        $pos_xini_aport = $this->pdf->getX(); 
                        $pos_yini_aport = $this->pdf->getY();
                        $x = $this->pdf->getX();    
                        $y = $this->pdf->getY();

                        $encima = true;
                        foreach($conceptos_header['aportaciones'] as $c){


                             $this->pdf->setXY( $x,  $y );   
                             $this->pdf->Cell($ancho_celda,$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);

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

                         if( $encima === false){

                                $this->pdf->setXY( $x,  $y );   
                                $this->pdf->Cell($ancho_celda,$alto_linea,  '',$bordes,0,'C',$fill);
                                $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   


                                $x = $x + $ancho_celda;
                                $y = $y - $alto_linea;
                          }


                         
                         $this->pdf->setXY( $x,  $y );   
                         $this->pdf->Cell($ancho_celda, ($alto_linea * 2), 'T.APORT',$bordes,0,'C',$fill);


                }
                 else{
                    $pos_xini_aport = $this->pdf->getX(); 
                    $pos_yini_aport = $this->pdf->getY();
                    $x = $this->pdf->getX();    
                    $y = $this->pdf->getY();

    
                }               


                $encima = true;
                 

                
                if(sizeof($conceptos_header['aportaciones']) > 0) $x = $x + $ancho_celda;

                foreach($conceptos_header['info_vars'] as $c){


                     $this->pdf->setXY( $x,  $y );   
                     $this->pdf->Cell(($ancho_celda + 4),$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);

                     if($encima){
                        $x = $x;
                        $y = $y + $alto_linea;
                     }
                     else{
                        $x = $x + ($ancho_celda + 4);
                        $y = $y - $alto_linea;
                     }

                     $encima = !$encima;

                }

                 if( !$encima){

                        $this->pdf->setXY( $x,  $y );   
                        $this->pdf->Cell(($ancho_celda + 4),$alto_linea,  '',$bordes,0,'C',$fill);
                        $this->pdf->setXY( $x + ($ancho_celda + 4) ,  $y - $alto_linea );   

                 }
                 

 
                /* FIRMA */ 
                $this->pdf->Cell($ws[2], ($alto_linea * 2), 'FIRMA', $bordes,0,'C',$fill);
                //$this->pdf->ln();


                /* REGLON DE ABAJO */
                 
                $this->pdf->setXY( $pos_xini,  $pos_yini + $alto_linea );

                $this->pdf->Cell($ws[0],$alto_linea,'DNI','BRL',0,'C',$fill);
                $this->pdf->Cell($ws[1],$alto_linea,'','BRL',0,'C',$fill);
                foreach($conceptos_header['descuentos'] as $c){
                     $this->pdf->Cell($ancho_celda,$alto_linea, substr($c['nombre'],0,6),$bordes,0,'C',$fill);
                }


                if($wdif == 1){

                        for($j=1; $j<=$dif; $j++){
                             $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',$fill);
                        }       
                }

                $this->pdf->Cell($ancho_celda,$alto_linea, 'DESC.',$bordes,0,'C',$fill);
                $this->pdf->Cell($ancho_celda,$alto_linea, 'A PAGAR','BRL',0,'C',$fill);
                 

                // FIN DE IMPRESION DEL HEADER



     } 



     if($i>1) $this->pdf->ln();
     $this->pdf->Cell($ws[0],$alto_linea, $i,'TRL',0,'C',false);
 	 $this->pdf->Cell($ws[1],$alto_linea,  $reg['nombre_trabajador']." ".$this->pdf->getY(),'TRL',0,'L',false);
  


     foreach($conceptos_header['ingresos'] as $c){

     	 $ing = ($reg[$c['conc_id']] == '' ) ? 0 : $reg[$c['conc_id']];
     	 $total_ingresos+=$ing;

         $totales['ingresos'][$c['conc_id']] += $ing; 

     	 $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($reg[$c['conc_id']],2) ,$bordes,0,'R',false);
              
     }

     if($wdif == 2){

     	for($j=1; $j<=$dif; $j++){
     		 $this->pdf->Cell($ancho_celda,$alto_linea, '' ,$bordes,0,'C',false);
     	}		
     }

     $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($total_ingresos ,2) ,$bordes,0,'R',false); // TOTAL INGRESOS 



     /* SEGUNDO REGLON */
     $this->pdf->ln();
     $this->pdf->Cell($ws[0],$alto_linea,$reg['indiv_dni'],'BRL',0,'C',false);
     $this->pdf->Cell($ws[1], $alto_linea, '','BRL',0,'C',false);

     foreach($conceptos_header['descuentos'] as $c){

     	 $des = ($reg[$c['conc_id']] == '' ) ?  0 : $reg[$c['conc_id']];
     	 $total_descuentos+=$des;
         $totales['descuentos'][$c['conc_id']] += $des; 

     	 $this->pdf->Cell($ancho_celda,$alto_linea,  number_format($reg[$c['conc_id']],2) ,$bordes,0,'R',false);
               
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
    foreach($conceptos_header['aportaciones'] as $c){


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



        if(sizeof($conceptos_header['aportaciones']) > 0){ 

               
                 
               $this->pdf->setXY( $x,  $y ); 
               $this->pdf->Cell($ancho_celda,($alto_linea *2), $total_aportacion,$bordes,0,'C',false);
                       $x = $x + ($ancho_celda);

        }



        $encima = true;
         

     //   $y = $y - $alto_linea;
        $this->pdf->SetFont('Arial','', ($font_size - 1 ) );
        foreach($conceptos_header['info_vars'] as $c){


             $this->pdf->setXY( $x,  $y );   
             $this->pdf->Cell(($ancho_celda +4 ),$alto_linea, substr($reg[$c['codigo']],0,15),$bordes,0,'C',false);

             if($encima){
                $x = $x;
                $y = $y + $alto_linea;
             }
             else{
                $x = $x + ($ancho_celda +4 );
                $y = $y - $alto_linea;
             }

             $encima = !$encima;

        }

         if( !$encima){

                $this->pdf->setXY( $x,  $y );   
                $this->pdf->Cell(($ancho_celda +4 ),$alto_linea,  '',$bordes,0,'C',false);
                $this->pdf->setXY( $x + ($ancho_celda +4 ) ,  $y - $alto_linea );   

         }
        $this->pdf->SetFont('Arial','', $font_size);
      

      $this->pdf->Cell($ws[2], ($alto_linea * 2 ), 'Firma: ____________________ ', $bordes,0,'L',false);
    $this->pdf->setXY($lx, (  $ly - $alto_linea) ); 




 }





/*  

RESUMEN 

*/ 
$this->pdf->SetFont('Arial','B', $font_size);
$this->pdf->ln();
$this->pdf->Cell($ws[0],$alto_linea, '', '',0,'C',false);
$this->pdf->Cell($ws[1],$alto_linea, 'TOTAL','',0,'R',false);
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
 $this->pdf->Cell($ws[0],$alto_linea,'','',0,'C',false);
  $this->pdf->Cell($ws[1], $alto_linea, 'PLANILLAS','',0,'R',false);
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


if( !$encima){

       $this->pdf->setXY( $x,  $y );   
            $this->pdf->Cell($ancho_celda,$alto_linea,  '',$bordes,0,'C',false);
            $this->pdf->setXY( $x + $ancho_celda ,  $y - $alto_linea );   
            $x = $x + $ancho_celda;
            $y = $y - $alto_linea;
      }

  if(sizeof($conceptos_header['aportaciones']) > 0){ 

 
             
     $this->pdf->setXY( $x,  $y ); 
     $this->pdf->Cell($ancho_celda,($alto_linea *2), $total_aportacion,$bordes,0,'C',false);
 }

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

$this->pdf->Output();