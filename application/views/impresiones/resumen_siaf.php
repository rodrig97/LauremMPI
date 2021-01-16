<?PHP 
 
$this->pdf->initialize('p','mm','A4');
 
$this->pdf->AliasNbPages();

$this->pdf->AddPage();
 

$this->pdf->ln();
$this->pdf->SetFont('Arial','B',8);
$this->pdf->Cell(190,4,' RESUMEN DE AFECTACION PRESUPUESTAL ','',0,'C');
 
$this->pdf->ln();
$this->pdf->ln();

 
$this->pdf->SetFont('Arial','B',6);  
 
$alto_fila= 4; 

foreach ($estructura as $key => $v)
{
  	$this->pdf->Cell($v[1], $alto_fila, $key, 'TBLR', 0 , 'C'); 
}	
$this->pdf->ln();
 


$this->pdf->SetFont('Arial','',6); 

$total = 0;
 

foreach($reporte as $reg)
{ 

	    $clasificador = trim($reg['clasificador_nombre']);

	    if(strlen($clasificador) > 50 )
	    {

	       $reg['clasificador_nombre'] = '..'.utf8_decode(substr($clasificador,25,25)); 
	       if(strlen($reg['clasificador_nombre'])>50) $reg['clasificador_nombre'].=".."; 

	    } 
	    else
	    {

	       $reg['clasificador_nombre']  = substr($clasificador,0,25);
	       if(strlen($reg['clasificador_nombre'])>25) $reg['clasificador_nombre'].=".."; 

	    }


	    $meta = trim($reg['meta_nombre']);

	    if(strlen($meta) > 50 )
	    {

	       $reg['meta_nombre'] = '..'.utf8_decode(substr($meta,25,25)); 
	       if(strlen($reg['meta_nombre'])>50) $reg['meta_nombre'].=".."; 

	    } 
	    else
	    {

	       $reg['meta_nombre']  = substr($meta,0,25);
	       if(strlen($reg['meta_nombre'])>25) $reg['meta_nombre'].=".."; 

	    }
 	
	    $reg['total'] = number_format($reg['total'],2);

	    foreach ($estructura as $v)
	    {
	        $this->pdf->Cell($v[1], $alto_fila,  $reg[$v[0]] 	  		   , 'TBLR', 0 , $v[2]); 
	    }	
	    $this->pdf->ln();
	   
	    $total+= $reg['total'];
  
}

$this->pdf->ln();


$this->pdf->SetFont('Arial','B',7); 

$this->pdf->Cell( 20, $alto_fila,  '', '', 0 , 'L'); 
 
$this->pdf->Cell( 20, $alto_fila,  ('TOTAL: S./'.$total) , '', 0 , 'L'); 
 

$this->pdf->Output();

