<?php
 

if(function_exists('_get_date_pg') === FALSE )
{

	function _get_date_pg($time,$type='fecha')
	{
	    
	    $parts=explode(' ',trim($time));
	    $fecha=$parts[0];
	    $hour=$parts[1];
	    $hour=explode('.',trim($hour));
	    $hour=$hour[0];
	    
	    list($anho,$mes,$dia) = explode('-',$fecha);
	    $fecha= $dia.'/'.$mes.'/'.$anho;
	    
	    if($type=='fecha') return $fecha;
	    if($type=='hora')  return  $hour;
	     
	}
}
 

$this->pdf->SetTopMargin(100);
$this->pdf->initialize('p','mm','A4');
$this->pdf->logo = true;
$this->pdf->AliasNbPages();
   

//  EMPIEZA EL CERTIFICADO 

$ancho_hoja = 190;
$tamanio_letra_normal = 9; 
$numero_de_caracteres_ancho_completo = 130;
$numero_de_caracteres_proyecto = 37;
$numero_de_caracteres_categoria = 15;

$sizes = array(80, 30, 30, 30, 12);
 
$c = 0;
$nro_documento = $nro_inicio; 
$indiv_actual = '';
$alto_fila_defecto = 4;

foreach ($detalle_cerficados as $registro_certificado)
{

  	 if( $registro_certificado['indiv_id'] != $indiv_actual )
	 {				

	 		$indiv_actual = $registro_certificado['indiv_id'];
	 		
	 		if($c > 0)
	 		{ 

	 			$this->pdf->ln(); 
	 			$this->pdf->SetFont('Arial','',$tamanio_letra_normal);
	 			$texto = "Se expide el presente a solicitud del interesado; para los fines que estime conveniente."; 

	 			$lines = wordwrap($texto, $numero_de_caracteres_ancho_completo  ,'_');
	 			$lines = explode('_',$lines);

	 			foreach($lines as $k => $line)
	 			{
	 			   $this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, utf8_decode($line), '',0,'L',false); 
	 			   $this->pdf->ln(); 
	 			}	
	 			$this->pdf->ln(); 
	 			$this->pdf->Cell( ($ancho_hoja-10) ,$alto_fila_defecto, INSTITUCION_CIUDAD.', '.utf8_decode($fecha), '',0,'R',false); 

 			}	

	 		$this->pdf->AddPage(); 
	 	
	 		$this->pdf->SetFont('Arial','',$tamanio_letra_normal );
	 		$this->pdf->Cell( ($ancho_hoja-10) ,$alto_fila_defecto, utf8_decode('CT No. ').sprintf("%03s", trim($nro_documento)).'-'.$anio.INSTITUCION_AREARH_DOCUMENTO, '',0,'R',false); 	 
	 		$nro_documento++;

	 		$this->pdf->ln(); 
	 		$this->pdf->ln(); 
	 		
	 		$this->pdf->SetFont('Arial','BU',17);
	 		$this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, 'CERTIFICADO DE TRABAJO ', '',0,'C',false); 	 

	 		$this->pdf->ln(); 
	 		$this->pdf->ln(); 
	 		$this->pdf->ln(); 
		
	 		$this->pdf->SetFont('Arial','', $tamanio_letra_normal);

	 		$texto = INSTITUCION_DESCRIPCION_JEFE_AREA." quien suscribe, certfica que el Sr.(a): "; 

	 		$lines = wordwrap($texto, $numero_de_caracteres_ancho_completo  ,'_');
	 		$lines = explode('_',$lines);

	 		foreach($lines as $k => $line)
	 		{
	 		    $this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, utf8_decode($line), '',0,'L',false); 
	 		    $this->pdf->ln(); 
	 		}


	 		$this->pdf->SetFont('Arial','B',13);
	 		$this->pdf->ln(); 
	 		$texto =  $registro_certificado['indiv_nombres'].' '.$registro_certificado['indiv_appaterno'].' '.$registro_certificado['indiv_apmaterno'];
 			$this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, utf8_decode($texto), '',0,'C',false); 
 			$this->pdf->ln(); 

 			$this->pdf->SetFont('Arial','',$tamanio_letra_normal);
 			$texto = "L.E./D.N.I 	 :	".$registro_certificado['indiv_dni'];
 			$this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, $texto, '',0,'L',false); 	
 			$this->pdf->ln(); 

 			$this->pdf->ln(); 
 			$this->pdf->SetFont('Arial','',$tamanio_letra_normal);
	 		$texto = "Prestó sus servicios bajo el Régimen Laboral de Construcción CIvil, en la Sub Gerencia de proyectos de Inversión, según el siguiente detalle: "; 
 
	 		$lines = wordwrap($texto, $numero_de_caracteres_ancho_completo  ,'_');
	 		$lines = explode('_',$lines);

	 		foreach($lines as $k => $line)
	 		{
	 		    $this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, utf8_decode($line), '',0,'L',false); 
	 		    $this->pdf->ln(); 
	 		}	


	 		$this->pdf->ln(); 
	 		$this->pdf->ln(); 

	 		$this->pdf->SetFillColor(246, 246, 246);
	 		$fill = true;
	 		$this->pdf->Cell(  $sizes[0] ,($alto_fila_defecto*2), 'PROYECTO', 'TRBL',0,'C',$fill);
	 		$this->pdf->Cell(  $sizes[1] ,($alto_fila_defecto*2), 'CATEGORIA', 'TRBL',0,'C',$fill);
	 		$this->pdf->Cell(  ($sizes[2]+$sizes[3]) ,$alto_fila_defecto, utf8_decode('TIEMPO DE DURACIÓN'), 'TRBL',0,'C',$fill);
	 		$this->pdf->Cell(  $sizes[4] ,$alto_fila_defecto, utf8_decode('DÍAS'), 'TRL',0,'C',$fill); 
	 		$this->pdf->ln(); 
	 		$this->pdf->Cell(  ($sizes[0]+$sizes[1]) ,$alto_fila_defecto, '', '',0,'C',false); 
	 		$this->pdf->Cell(  $sizes[2] ,$alto_fila_defecto, 'DEL', 'RLB',0,'C',$fill); 
	 		$this->pdf->Cell(  $sizes[3] ,$alto_fila_defecto, 'AL', 'RLB',0,'C',$fill); 
	 		$this->pdf->Cell(  $sizes[4] ,$alto_fila_defecto, 'LABOR', 'BRL',0,'C',$fill);
	 		$this->pdf->ln();  
	 } 


	
	 // Imprimiendo registro de la tabla

	 $datos = array( $registro_certificado['proyecto'], $registro_certificado['platica_nombre'], 
	 				 $registro_certificado['fecha_ini'], $registro_certificado['fecha_fin'], $registro_certificado['duracion'] );


	 $lines = wordwrap( $datos[0], $numero_de_caracteres_proyecto, '_');
	 $lineas_proyecto = explode('_',$lines);
	 $n_l_p = sizeof($lineas_proyecto);


	 $lines = wordwrap( $datos[1], $numero_de_caracteres_categoria, '_');
	 $lineas_categoria = explode('_',$lines);
	 $n_l_c = sizeof($lineas_categoria);

	 $total_lineas_imprimir = ($n_l_p > $n_l_c) ? $n_l_p : $n_l_c;
 	
 	 $posini_x = $this->pdf->getX(); 
 	 $posini_y = $this->pdf->getY(); 


 	 for($i = 0; $i<$total_lineas_imprimir; $i++)
 	 {	
 	 	$border = ($i== 0) ? 'TRL' : ($i == ($total_lineas_imprimir-1) ? 'BRL' : 'RL' );

 	 	$this->pdf->Cell(  $sizes[0] ,$alto_fila_defecto, $lineas_proyecto[$i] , $border, 0,'L',false);
 		$this->pdf->ln(); 
 	 }	 

 	 $this->pdf->setXY($posini_x, $posini_y); 
 

 	 if(sizeof($lineas_categoria) > 1)
 	 {

		 for($i = 0; $i<$total_lineas_imprimir; $i++)
		 {	

		  	$border = ($i== 0) ? 'TRL' : ($i == ($total_lineas_imprimir-1) ? 'BRL' : 'RL' );
		  	$this->pdf->Cell(  $sizes[0] ,$alto_fila_defecto, '' , '', 0,'L',false);
		  	$this->pdf->Cell(  $sizes[1] ,$alto_fila_defecto, $lineas_categoria[$i] , $border, 0,'C',false);
		 	$this->pdf->ln(); 
		 }
	 }
	 else
	 {	 
	 	$this->pdf->Cell(  $sizes[0] ,$alto_fila_defecto, '' , '', 0,'L',false);
	    $this->pdf->Cell(  $sizes[1] , ( $total_lineas_imprimir * $alto_fila_defecto),  $lineas_categoria[0] , 'TBRL', 0,'C',false);
	 }

 	 $this->pdf->setXY($posini_x, $posini_y); 
 	 $this->pdf->Cell(  ($sizes[0]+$sizes[1]) ,$alto_fila_defecto, '' , '', 0,'L',false);
 	 $this->pdf->Cell(  $sizes[2] , ( $total_lineas_imprimir * $alto_fila_defecto), _get_date_pg($datos[2]) , 'TBRL', 0,'C',false);
 	 
 	 $this->pdf->setXY($posini_x, $posini_y); 
 	 $this->pdf->Cell(  ($sizes[0]+$sizes[1]+$sizes[2]) ,$alto_fila_defecto, '' , '', 0,'L',false);
 	 $this->pdf->Cell(  $sizes[3] , ( $total_lineas_imprimir * $alto_fila_defecto), _get_date_pg($datos[3]) , 'TBRL', 0,'C',false);
 	 
 	 $this->pdf->setXY($posini_x, $posini_y); 
 	 $this->pdf->Cell(  ($sizes[0]+$sizes[1]+$sizes[2]+$sizes[3]) ,$alto_fila_defecto, '' , '', 0,'L',false);
 	 $this->pdf->Cell(  $sizes[4] , ( $total_lineas_imprimir * $alto_fila_defecto), $datos[4] , 'TBRL', 0,'C',false);
	 $this->pdf->ln(); 

	 $c++;
 
}

 	  	  

 $this->pdf->ln(); 
 $this->pdf->SetFont('Arial','',$tamanio_letra_normal);
 $texto = "Se expide el presente a solicitud del interesado; para los fines que estime conveniente."; 

 $lines = wordwrap($texto, $numero_de_caracteres_ancho_completo  ,'_');
 $lines = explode('_',$lines);

 foreach($lines as $k => $line)
 {
    $this->pdf->Cell( $ancho_hoja ,$alto_fila_defecto, utf8_decode($line), '',0,'L',false); 
    $this->pdf->ln(); 
 }	
 $this->pdf->ln(); 
 $this->pdf->Cell( ($ancho_hoja-10) ,$alto_fila_defecto, INSTITUCION_CIUDAD.', '.utf8_decode($fecha), '',0,'R',false); 

$this->pdf->Output();