<?php


$this->pdf->initialize('p','mm','A4');
$this->pdf->paginador = true;
$this->pdf->AliasNbPages();
 

$this->pdf->AddPage();

$this->pdf->SetTopMargin(0);

$bordes = '';
$tamanio_letra = 9;  

$this->pdf->ln();

$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell(190,4,'RESUMEN CONTABLE POR SIAF','',0,'C');
$this->pdf->ln();
$this->pdf->Cell(190,4, trim(strtoupper($planilla_info['tipo'])),'',0,'C');

$this->pdf->ln();
$this->pdf->ln();

$alto_fila = 4;
$tamanio_letra = 8;  


$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell(15,$alto_fila,'ANIO:',$bordes,0,'L');
$this->pdf->Cell(15,$alto_fila, $anio,$bordes,0,'C'); 
  
$this->pdf->Cell(15,$alto_fila,'SIAF:',$bordes,0,'L');
$this->pdf->Cell(15,$alto_fila, $siaf,$bordes,0,'C'); 

$this->pdf->ln(); 

$this->pdf->Cell(20,$alto_fila,'PLANILLAS:',$bordes,0,'L'); 
$this->pdf->SetFont('Arial','',$tamanio_letra);
$this->pdf->Cell(30,$alto_fila,trim($planillas_codigos),$bordes,0,'L');


$this->pdf->ln(); 

$this->pdf->ln();
$this->pdf->Cell(193,1,'','T',0,'C');
 
$this->pdf->ln();
$this->pdf->ln(); 
$this->pdf->ln();


$params = array( 
			'siaf' 	 => $siaf,
			'anio' 	 => $anio,
			'modo'	 => 'debe' 
		  ); 

$total_neto =  $this->planilla->get_neto_planilla_siaf($params);

$this->pdf->ln();
// $this->pdf->SetFont('Arial','B',$tamanio_letra);
// $this->pdf->Cell(18,$alto_fila,'NETO S./',$bordes,0,'L');
// $this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
// $this->pdf->SetFont('Arial','',$tamanio_letra);
// $this->pdf->Cell(30,$alto_fila, number_format($total_neto['neto'],2),$bordes,0,'L');
$this->pdf->ln();
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell(18,$alto_fila,'NETO',$bordes,0,'L'); 
$this->pdf->ln();
 
$alto_linea = 5;
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell( 24, $alto_linea, '   ' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 90, $alto_linea, 'CUENTA CONTABLE ','TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, ' DEBE ' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, ' HABER ' ,'TLBR',0,'C', $fill);
$this->pdf->ln();
 
$this->pdf->SetFont('Arial','',$tamanio_letra);
$this->pdf->Cell( 24, $alto_linea, '5101.010102' ,'TLBR',0,'R', $fill);
$this->pdf->Cell( 90, $alto_linea, utf8_decode('Personal Administrativo Nombrado (Régimen Público) ') ,'TLBR',0,'L', $fill);
$this->pdf->Cell( 20, $alto_linea, number_format($total_neto['neto'],2) ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea,  '---','TLBR',0,'R', $fill);
$this->pdf->ln(); 
$this->pdf->Cell( 24, $alto_linea, '2102.01' ,'TLBR',0,'R', $fill);
$this->pdf->Cell( 90, $alto_linea, 'Remuneraciones por Pagar' ,'TLBR',0,'L', $fill);
$this->pdf->Cell( 20, $alto_linea, '---' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, number_format($total_neto['neto'],2) ,'TLBR',0,'R', $fill);
$this->pdf->ln(); 



$fila_ini_ytop = 50;
$fila_ini = 50; 



$params = array( 
				'siaf' 	 => $siaf,
				'anio' 	 => $anio,
				'modo'	 => 'debe',
				'conc_tipo' => TIPOCONCEPTO_DESCUENTO
		  );

$tabla_debe = $this->planilla->get_resumen_contable_siaf($params);


$params = array( 
				'siaf' 	 => $siaf,
				'anio' 	 => $anio,
				'modo'	 => 'haber',
				'conc_tipo' => TIPOCONCEPTO_DESCUENTO
		  );

$tabla_haber = $this->planilla->get_resumen_contable_siaf($params);

 
$alto_fila = 4;  
$this->pdf->ln();
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell(18,$alto_fila,'DESCUENTOS',$bordes,0,'L'); 
$this->pdf->ln();

 

$alto_linea = 5;
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell( 24, $alto_linea, '   ' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 90, $alto_linea, 'CUENTA CONTABLE ','TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, ' DEBE ' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, ' HABER ' ,'TLBR',0,'C', $fill);
$this->pdf->ln();

$alto_linea = 4;

$total_debe = 0;
$total_haber = 0;

$this->pdf->SetFont('Arial','',$tamanio_letra);

foreach ($tabla_debe as $reg) {
    
  $this->pdf->Cell( 24, $alto_linea, trim($reg['ccont_codigo']) ,'TLBR',0,'R', $fill);
  $this->pdf->Cell( 90, $alto_linea, utf8_decode($reg['ccont_nombre']) ,'TLBR',0,'L', $fill);
  $this->pdf->Cell( 20, $alto_linea, number_format($reg['total'],2) ,'TLBR',0,'R', $fill);
  $this->pdf->Cell( 20, $alto_linea, '---' ,'TLBR',0,'C', $fill);
  $this->pdf->ln();
  $total_debe+=($reg['total'] == '' ? 0 : ($reg['total']*1));
}

foreach ($tabla_haber as $reg) { 

  $this->pdf->Cell( 24, $alto_linea, trim($reg['ccont_codigo']) ,'TLBR',0,'R', $fill);
  $this->pdf->Cell( 90, $alto_linea, utf8_decode($reg['ccont_nombre']) ,'TLBR',0,'L', $fill);
  $this->pdf->Cell( 20, $alto_linea, '---' ,'TLBR',0,'C', $fill);
  $this->pdf->Cell( 20, $alto_linea, number_format($reg['total'],2) ,'TLBR',0,'R', $fill);
  $this->pdf->ln();
  $total_haber+=($reg['total'] == '' ? 0 : ($reg['total']*1));

}


$this->pdf->Cell( 24, $alto_linea, '' ,'TLBR',0,'R', $fill);
$this->pdf->Cell( 90, $alto_linea, '' ,'TLBR',0,'L', $fill);
$this->pdf->Cell( 20, $alto_linea, number_format($total_debe,2) ,'TLBR',0,'R', $fill);
$this->pdf->Cell( 20, $alto_linea, number_format($total_haber,2) ,'TLBR',0,'R', $fill);
$this->pdf->ln();

 


$params = array( 
				'siaf' 	 => $siaf,
				'anio' 	 => $anio,
				'modo'	 => 'debe',
				'conc_tipo' => TIPOCONCEPTO_APORTACION
		  );

$tabla_debe = $this->planilla->get_resumen_contable_siaf($params);


$params = array( 
				'siaf' 	 => $siaf,
				'anio' 	 => $anio,
				'modo'	 => 'haber',
				'conc_tipo' => TIPOCONCEPTO_APORTACION
		  );

$tabla_haber = $this->planilla->get_resumen_contable_siaf($params);


$this->pdf->ln();
$this->pdf->ln();
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell(18,$alto_fila,'APORTACIONES',$bordes,0,'L'); 
$this->pdf->ln();

$alto_linea = 5;
$this->pdf->SetFont('Arial','B',$tamanio_letra);
$this->pdf->Cell( 24, $alto_linea, '   ' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 90, $alto_linea, 'CUENTA CONTABLE ','TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, ' DEBE ' ,'TLBR',0,'C', $fill);
$this->pdf->Cell( 20, $alto_linea, ' HABER ' ,'TLBR',0,'C', $fill);
$this->pdf->ln();

$alto_linea = 4;

$total_debe = 0;
$total_haber = 0;

$this->pdf->SetFont('Arial','',$tamanio_letra);

foreach ($tabla_debe as $reg) {
    
  $this->pdf->Cell( 24, $alto_linea, trim($reg['ccont_codigo']) ,'TLBR',0,'R', $fill);
  $this->pdf->Cell( 90, $alto_linea, utf8_decode($reg['ccont_nombre']) ,'TLBR',0,'L', $fill);
  $this->pdf->Cell( 20, $alto_linea, number_format($reg['total'],2) ,'TLBR',0,'R', $fill);
  $this->pdf->Cell( 20, $alto_linea, '---' ,'TLBR',0,'C', $fill);
  $this->pdf->ln();
  $total_debe+=($reg['total'] == '' ? 0 : ($reg['total']*1));
}

foreach ($tabla_haber as $reg) { 

  $this->pdf->Cell( 24, $alto_linea, trim($reg['ccont_codigo']) ,'TLBR',0,'R', $fill);
  $this->pdf->Cell( 90, $alto_linea, utf8_decode($reg['ccont_nombre']) ,'TLBR',0,'L', $fill);
  $this->pdf->Cell( 20, $alto_linea, '---' ,'TLBR',0,'C', $fill);
  $this->pdf->Cell( 20, $alto_linea, number_format($reg['total'],2) ,'TLBR',0,'R', $fill);
  $this->pdf->ln();
  $total_haber+=($reg['total'] == '' ? 0 : ($reg['total']*1));

}


$this->pdf->Cell( 24, $alto_linea, '' ,'TLBR',0,'R', $fill);
$this->pdf->Cell( 90, $alto_linea, '' ,'TLBR',0,'L', $fill);
$this->pdf->Cell( 20, $alto_linea, number_format($total_debe,2) ,'TLBR',0,'R', $fill);
$this->pdf->Cell( 20, $alto_linea, number_format($total_haber,2) ,'TLBR',0,'R', $fill);
$this->pdf->ln();



$this->pdf->Output();
