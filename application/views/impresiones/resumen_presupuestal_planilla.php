<?php

$this->pdf->initialize('p', 'mm','A4');
$this->pdf->paginador = true;

$this->pdf->AliasNbPages();
$this->pdf->SetTopMargin(0);

$this->pdf->AddPage();


$this->pdf->SetFont('Arial','B', 6); 

$alto_linea = 6;
$ancho_hoja = 190;

$this->pdf->Cell( $ancho_hoja, $alto_linea, utf8_decode('RESUMEN DE LA AFECTACIÓN PRESUPUESTAL DE LA PLANILLA'),'',0,'C');

$this->pdf->ln();
$this->pdf->ln();


$this->pdf->SetFont('Arial','B', 5); 

$alto_linea = 5;
$this->pdf->Cell(24, $alto_linea,'AFECTANCION DESDE', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea, ($planilla_info['pla_afectadinero_modo'] == '1' ? 'PREAFECTACION' : 'SALDO PRESUPUESTAL') , '',0,'L');
$this->pdf->ln();

$this->pdf->Cell(24, $alto_linea,'PLANILLA CODIGO', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea,trim($planilla_info['pla_codigo']).'    - '.trim($planilla_info['mes']), '',0,'L');
$this->pdf->ln();

$this->pdf->Cell(24, $alto_linea,'TIPO', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea,trim($planilla_info['tipo']), '',0,'L');
$this->pdf->ln();

$this->pdf->Cell(24, $alto_linea,'DESCRIPCION', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea, trim($planilla_info['pla_descripcion'])  , '',0,'L');
$this->pdf->ln();

$this->pdf->Cell(24, $alto_linea,'NRO DE TRABAJADORES', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea, trim($planilla_info['num_emps'])  , '',0,'L');
$this->pdf->ln();

$this->pdf->Cell(24, $alto_linea,'ESTADO ACTUAL', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea, trim($planilla_info['estado'])  , '',0,'L');
$this->pdf->ln();
 
$this->pdf->Cell(24, $alto_linea,'REPORTE GENERADO', '',0,'L');
$this->pdf->Cell(5, $alto_linea,':', '',0,'C'); 
$this->pdf->Cell(30, $alto_linea, 'El '.(date('d').'/'.date('m').'/'.date('Y')).' a las '.(date('G').':'.date('i')).' horas'  , '',0,'L');
$this->pdf->ln();
 

$this->pdf->ln();
$this->pdf->ln();

$c = 1;
$this->pdf->SetFillColor(246, 246, 246);
$fill = true;

 
$alto_linea = 6;
$this->pdf->Cell( 8, $alto_linea, '#','TLBR',0,'C', $fill);

$this->pdf->Cell( 70, $alto_linea, ' TAREA PRESUPUESTAL' ,'TLBR',0,'C', $fill);

$this->pdf->Cell( 40, $alto_linea, 'CLASIFICADOR','TLBR',0,'C', $fill);

$this->pdf->Cell( 15, $alto_linea, 'FUENTE.F' ,'TLBR',0,'C', $fill);

$this->pdf->Cell( 16, $alto_linea, ' PLANILLA S./' ,'TLBR',0,'C', $fill);

$this->pdf->Cell( 16, $alto_linea, ' DISPONIBLE S./' ,'TLBR',0,'C', $fill);

$this->pdf->Cell( 16, $alto_linea, ' SALDO S./' ,'TLBR',0,'C', $fill);

$ws = array( 8, 70, 40, 15, 16, 16, 16);


$this->pdf->ln();


/* 
if($plani_info['tarea_id'] != '')
                                  {

                                      echo '<span class="sp11b"> Tarea: </span> ';
                                      echo '<span class="sp11"> '.(trim($plani_info['tarea_codigo']).' '.substr(trim($plani_info['tarea_nombre']),0,50).'..' ).'</span>';
                                  }

 
                                  if($plani_info['fuente_id'] != '' && $plani_info['tipo_recurso'] != '' )
                                  {
                                      echo '<span class="sp11b"> Fuente F: </span> ';
                                      echo '<span class="sp11"> '.$plani_info['fuente_id'].' - '.$plani_info['tipo_recurso'].' ('.$plani_info['fuente_abrev'].') </span>';
          
                                  } 

                                  if($plani_info['clasificador_id'] != '' )
                                  {
                                      echo '<span class="sp11b"> Clasificador </span> ';
                                      echo '<span class="sp11"> '.(substr(trim($plani_info['clasificador']),0,25).'..' ).'</span>';
                                  
                                  } 
*/


$saldo_suficientes = true;
$total = 0;

foreach ($tabla_afectacion as $reg)
{
 	
 	$this->pdf->SetFont('Arial','', 5); 
	$this->pdf->Cell( $ws[0], $alto_linea, $c,'TLBR',0,'C');

	$this->pdf->Cell( $ws[1], $alto_linea, (trim($reg['tarea_codigo']).' '.substr(trim($reg['tarea_nombre']),0,55).'..' ) ,'TLBR',0,'L');

	$this->pdf->Cell( $ws[2], $alto_linea, (substr(trim($reg['partida']),0,30).'..' ) ,'TLBR',0,'L');

	$this->pdf->Cell( $ws[3], $alto_linea, ($reg['fuente_codigo']) ,'TLBR',0,'C');

	$planilla 	= sprintf("%.2f",trim($reg['total']));
	$disponible = sprintf("%.2f",trim($reg['disponible']));
	$diferencia =   $reg['disponible'] - $reg['total'];

	$fill_x = false;
	if($diferencia < 0 )
	{
		$saldo_suficientes = false;
		$fill_x = true;
	}

	$this->pdf->SetFont('Arial','', 6); 	

	$this->pdf->Cell( $ws[4], $alto_linea, $planilla ,'TLBR',0,'C');

	$this->pdf->Cell( $ws[5], $alto_linea, $disponible ,'TLBR',0,'C');

	$this->pdf->Cell( $ws[6], $alto_linea, ($diferencia) ,'TLBR',0,'C', $fill_x);

	$this->pdf->ln();

	$total+= $planilla;

	$c++;
}
	 
	$this->pdf->Cell( (133), $alto_linea, 'COSTO TOTAL DE LA PLANILLA S./    ' ,'',0,'R');
	$this->pdf->Cell( $ws[4], $alto_linea, sprintf("%.2f", $total ) ,'TLBR',0,'C');
 

 $this->pdf->ln();

 $this->pdf->ln();



if($saldo_suficientes == false)
{

	$this->pdf->Cell( 181, $alto_linea, ' No existen fondos suficientes para afectar la planilla. ' ,'TLBR',0,'C', $fill);
}



$this->pdf->Output();