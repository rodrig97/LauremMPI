<?php


$this->pdf->initialize('p','mm','A4');
$this->pdf->paginador = true;
$this->pdf->AliasNbPages();


foreach($planillas as $planilla_id)
{
        
        $planilla_info             = $this->planilla->get($planilla_id); 
        $afectacion_fuentes        = $this->planilla->get_afectacion_fuentes($planilla_id);


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

		$this->pdf->AddPage();

		$this->pdf->SetTopMargin(0);

		$bordes = '';
		$tamanio_letra = 9;  

		$this->pdf->ln();
		
		$this->pdf->SetFont('Arial','B',$tamanio_letra);
		$this->pdf->Cell(190,4,'RESUMEN CONTABLE POR PLANILLA','',0,'C');
		$this->pdf->ln();
		$this->pdf->Cell(190,4, trim(strtoupper($planilla_info['tipo'])),'',0,'C');

		$this->pdf->ln();
		$this->pdf->ln();
 
		$alto_fila = 4;
		$tamanio_letra = 7;  
		$this->pdf->SetFont('Arial','B',$tamanio_letra);
		$this->pdf->Cell(32,$alto_fila,'PLANILLA CODIGO',$bordes,0,'L');
		$this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
		$this->pdf->SetFont('Arial','',$tamanio_letra);
		$this->pdf->Cell(30,$alto_fila,trim($planilla_info['pla_codigo']),$bordes,0,'L');


		$this->pdf->SetFont('Arial','B',$tamanio_letra);
		$this->pdf->Cell(16,$alto_fila,'PERIODO ',$bordes,0,'L');
		$this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
		$this->pdf->SetFont('Arial','',$tamanio_letra);
		$this->pdf->Cell(30,$alto_fila,  $meses_label[trim($planilla_info['pla_mes'])].' - '.$planilla_info['pla_anio']  ,$bordes,0,'L');

		$this->pdf->ln();

		if(trim($planilla_info['pla_fecini']) != ''){  // FECHAS 

			$this->pdf->SetFont('Arial','B',$tamanio_letra);
			$this->pdf->Cell(32,$alto_fila,'SEMANA',$bordes,0,'L');
			$this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
			$this->pdf->SetFont('Arial','',$tamanio_letra);
			$this->pdf->Cell(7,$alto_fila,'DEL',$bordes,0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra);
			 
			$this->pdf->Cell(15,$alto_fila,trim($planilla_info['pla_fecini']),$bordes,0,'L');
			//$this->pdf->SetFont('Arial','B',$tamanio_letra);
			$this->pdf->Cell(7,$alto_fila,'AL',$bordes,0,'C');
			$this->pdf->SetFont('Arial','',$tamanio_letra);
			$this->pdf->SetFont('Arial','',$tamanio_letra);
			$this->pdf->Cell(15,$alto_fila,trim($planilla_info['pla_fecfin']),$bordes,0,'L');


		}
		
		if(trim($planilla_info['pla_afectacion_presu']) ==  PLANILLA_AFECTACION_ESPECIFICADA )
		{ 

			$this->pdf->ln();
			$this->pdf->SetFont('Arial','B',$tamanio_letra);
            $lcTarea = ( $planilla_info['ano_eje'] < 2021 ) ? 'TAREA' : 'META';
			$this->pdf->Cell(32,$alto_fila,$lcTarea.' PRESUPUESTAL',$bordes,0,'L');
			$this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
			$this->pdf->SetFont('Arial','',$tamanio_letra);
			$tarea_nombre = substr(utf8_decode(trim($planilla_info['tarea_nombre'])), 0,100);

			$this->pdf->Cell(30,$alto_fila, trim($planilla_info['tarea_codigo']).'-'.$tarea_nombre,$bordes,0,'L');
		
		} 


		foreach($afectacion_fuentes as $detalle)
		{  

				$this->pdf->ln();
				$this->pdf->Cell(193,1,'','T',0,'C');

				$afectecion_label = trim($detalle['fuente_id']).' - '.trim($detalle['tipo_recurso']).'  ';
				$afectacion_codigo = $afectecion_label;

				if( trim($detalle['tipo_recurso']) == '0')
				{
					$afectecion_label.= trim($detalle['fuente_nombre']);
				}
				else{
		 			
		 			$afectecion_label.= trim($detalle['fuente_nombre']).' - '.trim($detalle['tipo_recurso_nombre']);		
				}

				//utf8_decode
				if(strlen($afectecion_label) > 50 ){

				   $afectecion_label = '..'.substr($afectecion_label,25,50); 
				   if(strlen($afectecion_label)>50) $afectecion_label.=".."; 
				   $afectecion_label=$afectacion_codigo.' '.$afectecion_label;

				} 
				else{

				   $afectecion_label  = substr($afectecion_label,0,50);
				   if(strlen($afectecion_label)>25) $afectecion_label.=".."; 

				}

				$this->pdf->ln();
				$this->pdf->ln();
		 		$this->pdf->ln();
		 		$this->pdf->ln();


		 		$params = array( 
		 						'pla_id' 	 => $planilla_id,
		 						'fuente'      => ( $detalle['fuente_id'] != '' ? $detalle['fuente_id'] : '0'),
		 						'tiporecurso' => ( $detalle['tipo_recurso'] != '' ? $detalle['tipo_recurso'] : '0'),
		 						'modo'	 => 'debe' 
		 					  );
  				
 				$this->pdf->SetFont('Arial','B',$tamanio_letra);
 				$this->pdf->Cell(18,$alto_fila,'FUENTE',$bordes,0,'L');
 				$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
 				$this->pdf->SetFont('Arial','',$tamanio_letra);
 				$this->pdf->Cell(30,$alto_fila,trim($afectecion_label),$bordes,0,'L');

		 		$this->pdf->ln();
 				$this->pdf->SetFont('Arial','B',$tamanio_letra);
 				$this->pdf->Cell(18,$alto_fila,'SIAF',$bordes,0,'L');
 				$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
 				$this->pdf->SetFont('Arial','',$tamanio_letra);
 				$this->pdf->Cell(30,$alto_fila,(trim($detalle['siaf']) == '' ?  '---' : trim($detalle['siaf'])),$bordes,0,'L');

				$neto_planilla =  $this->planilla->get_neto_planilla($params);

		 		$this->pdf->ln();
 				$this->pdf->SetFont('Arial','B',$tamanio_letra);
 				$this->pdf->Cell(18,$alto_fila,'NETO S./',$bordes,0,'L');
 				$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
 				$this->pdf->SetFont('Arial','',$tamanio_letra);
 				$this->pdf->Cell(30,$alto_fila, number_format($neto_planilla['neto'],2),$bordes,0,'L');

  

				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$alto_fila = 4;  
  

				$fila_ini_ytop = 50;
				$fila_ini = 50; 



				$tabla_debe = $this->planilla->get_resumen_contable($params);


				$params = array( 
								'pla_id' 	 => $planilla_id,
								'fuente'      => ( $detalle['fuente_id'] != '' ? $detalle['fuente_id'] : '0'),
								'tiporecurso' => ( $detalle['tipo_recurso'] != '' ? $detalle['tipo_recurso'] : '0'),
								'modo'	 => 'haber' 
							  );

				$tabla_haber = $this->planilla->get_resumen_contable($params);


 
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


		}

}				  


$this->pdf->Output();

?>
