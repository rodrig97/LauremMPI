<?php


$this->pdf->initialize('p','mm','A4');
$this->pdf->paginador = true;
$this->pdf->AliasNbPages();


foreach($planillas as $planilla_id)
{
 
		$planilla_info             = $this->planilla->get($planilla_id); 
		$afectacion_fuentes        = $this->planilla->get_afectacion_fuentes($planilla_id);
 
		foreach($afectacion_fuentes as $detalle)
		{ 

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

				$afectacion = array( 

									'fuente'      => ( $detalle['fuente_id'] != '' ? $detalle['fuente_id'] : '0'),
									'tiporecurso' => ( $detalle['tipo_recurso'] != '' ? $detalle['tipo_recurso'] : '0')

							  );

				$conceptos                 =  array();
				$conceptos['ingresos']     = $this->planilla->get_resumen_conceptos($planilla_info['pla_id'],TIPOCONCEPTO_INGRESO	 , $afectacion);
		  		$conceptos['descuentos']   = $this->planilla->get_resumen_conceptos($planilla_info['pla_id'],TIPOCONCEPTO_DESCUENTO	 , $afectacion);
				$conceptos['aportaciones'] = $this->planilla->get_resumen_conceptos($planilla_info['pla_id'],TIPOCONCEPTO_APORTACION , $afectacion);

		        $tabla_afps                = $this->planilla->tabla_afps($planilla_info['pla_id'], $afectacion);

		        $tabla_afectacion 		   = $this->planilla->get_resumen_presupuestal(array( 
		        																				'planillas' => array($planilla_info['pla_id']),
		        																				'fuente'	=>  $afectacion['fuente'],
		        																				'tiporecurso' => $afectacion['tiporecurso']

		        																			));


		        /* EMPIEZA LA GENERACION DEL PDF*/

				$this->pdf->AddPage();

				$this->pdf->SetTopMargin(0);

				$alto_fila = 3;
				$bordes = '';
				$tamanio_letra = 7;
				 

				$columnas = array(0,10);
				$coords  = array(0,10,75,140);
				  
				$this->pdf->ln();



				$tipo_planilla_label = (trim($planilla_info['pla_tipo']) == 'r' ? '' : ' DE VACACIONES' );

				$this->pdf->SetFont('Arial','B',9);
				$this->pdf->Cell(190,4,'RESUMEN DE PLANILLA'.$tipo_planilla_label,'',0,'C');
				$this->pdf->ln();
				$this->pdf->Cell(190,4, trim(strtoupper($planilla_info['tipo'])),'',0,'C');

				$this->pdf->ln();
				

		 
				$this->pdf->SetFont('Arial','B',9);
				$this->pdf->Cell(168,$alto_fila,'SIAF',$bordes,0,'R');
				$this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C'); 
				$this->pdf->Cell(15,$alto_fila,trim($detalle['siaf']),$bordes,0,'L');
				$this->pdf->ln();	


				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$alto_fila = 4;  
 

				$this->pdf->Cell(33,$alto_fila,'PLANILLA CODIGO',$bordes,0,'L');
				$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$this->pdf->Cell(30,$alto_fila,trim($planilla_info['pla_codigo']).'    - '.trim($planilla_info['mes']),$bordes,0,'L');

				$this->pdf->ln();

				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->Cell(33,$alto_fila,'FUENTE',$bordes,0,'L');
				$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$this->pdf->Cell(30,$alto_fila,trim($afectecion_label),$bordes,0,'L');

				$this->pdf->ln();


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


				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->Cell(33,$alto_fila,'PERIODO ',$bordes,0,'L');
				$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$this->pdf->Cell(30,$alto_fila,  $meses_label[trim($planilla_info['pla_mes'])].' - '.$planilla_info['pla_anio']  ,$bordes,0,'L');


				if(trim($planilla_info['pla_fecini']) != ''){  // FECHAS 

					$this->pdf->SetFont('Arial','B',$tamanio_letra);
					$this->pdf->Cell(13,$alto_fila,'SEMANA',$bordes,0,'L');
					$this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
					$this->pdf->SetFont('Arial','',$tamanio_letra);
					$this->pdf->Cell(10,$alto_fila,'DEL',$bordes,0,'C');
					$this->pdf->SetFont('Arial','',$tamanio_letra);
					 
					$this->pdf->Cell(15,$alto_fila,trim($planilla_info['pla_fecini']),$bordes,0,'L');
					//$this->pdf->SetFont('Arial','B',$tamanio_letra);
					$this->pdf->Cell(10,$alto_fila,'AL',$bordes,0,'C');
					$this->pdf->SetFont('Arial','',$tamanio_letra);
					$this->pdf->SetFont('Arial','',$tamanio_letra);
					$this->pdf->Cell(15,$alto_fila,trim($planilla_info['pla_fecfin']),$bordes,0,'L');


				}

				
		 
				if(trim($planilla_info['pla_afectacion_presu']) ==  PLANILLA_AFECTACION_ESPECIFICADA )
				{ 

					$this->pdf->ln();
					$this->pdf->SetFont('Arial','B',$tamanio_letra);
					$this->pdf->Cell(33,$alto_fila,'TAREA PRESUPUESTAL',$bordes,0,'L');
					$this->pdf->Cell(5,$alto_fila,':',$bordes,0,'C');
					$this->pdf->SetFont('Arial','',$tamanio_letra);

					$tarea_nombre = substr(utf8_decode(trim($planilla_info['tarea_nombre'])), 0,100);

					$this->pdf->Cell(30,$alto_fila, trim($planilla_info['tarea_codigo']).'-'.$tarea_nombre,$bordes,0,'L');
 	
				} 


				$this->pdf->ln();
				$this->pdf->ln();
				$this->pdf->Cell(193,1,'','T',0,'C');

				$fila_ini_ytop = 50;


				$fila_ini = 50;

				$this->pdf->line(73, $fila_ini ,73, $fila_ini + 120);


				$this->pdf->setXY($coords[1],$fila_ini);

				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->Cell(62,$alto_fila,'INGRESOS',$bordes,0,'C');
				$fila_ini+=($alto_fila + 5);


				$this->pdf->SetFont('Arial','',$tamanio_letra);

				$total_ingresos = 0;
				foreach( $conceptos['ingresos'] as $k => $concepto){
				        
				        $this->pdf->setXY($coords[1],$fila_ini);
				        $this->pdf->Cell(45,$alto_fila,$concepto['nombre_corto'],$bordes,0,'L');
				        $this->pdf->Cell( 2,$alto_fila,':',$bordes,0,'C');
				        $this->pdf->Cell(15,$alto_fila,sprintf('%.2f',trim($concepto['monto'])),$bordes,0,'R');
				        $fila_ini+=$alto_fila;
				        $total_ingresos+=$concepto['monto'];
				}

				$fila_ini+=$alto_fila;
				$this->pdf->setXY($coords[1],$fila_ini);
				$this->pdf->Cell(62,$alto_fila,'--------------------------------------------------------------------', '',0,'C');  


				$this->pdf->setXY($coords[1], ( 165) );
				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->Cell(45,$alto_fila,'TOTAL INGRESOS S./',$bordes,0,'R');
				$this->pdf->Cell( 2,$alto_fila,':',$bordes,0,'C');
				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$this->pdf->Cell(15,$alto_fila,sprintf('%.2f',trim($total_ingresos)),$bordes,0,'R');


				 


				$fila_ini = 50;
				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->setXY($coords[2],$fila_ini);
				$this->pdf->Cell(62,$alto_fila,'DESCUENTOS',$bordes,0,'C');
				$fila_ini+=($alto_fila + 5);

				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$total_descuentos = 0;

				foreach( $conceptos['descuentos'] as $k => $concepto){
				        
				        $this->pdf->setXY($coords[2],$fila_ini);
				        $this->pdf->Cell(45,$alto_fila,$concepto['nombre_corto'],$bordes,0,'L');
				        $this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
				        $this->pdf->Cell(15,$alto_fila,sprintf('%.2f', trim($concepto['monto']) ),$bordes,0,'R'); //trim($concepto['monto'])
				        $fila_ini+=$alto_fila;
				        $total_descuentos+=$concepto['monto'];
				}

				$fila_ini+=$alto_fila;
				$this->pdf->setXY($coords[2],$fila_ini);
				$this->pdf->Cell(62,$alto_fila,'--------------------------------------------------------------------', '',0,'C');
				  
				$this->pdf->setXY($coords[2], ( 165) );
				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->Cell(45,$alto_fila,'TOTAL DESCUENTOS S./',$bordes,0,'R');
				$this->pdf->Cell( 2,$alto_fila,':',$bordes,0,'C');
				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$this->pdf->Cell(15,$alto_fila,sprintf('%.2f',trim($total_descuentos)),$bordes,0,'R');







				$fila_ini = 50;
				$this->pdf->line(138, $fila_ini ,138, $fila_ini + 120);

				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->setXY($coords[3],$fila_ini);
				$this->pdf->Cell(62,$alto_fila,'APORTACIONES',$bordes,0,'C');
				$fila_ini+=($alto_fila + 5);



				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$total_aportaciones = 0;

				foreach( $conceptos['aportaciones'] as $k => $concepto){
				        
				        $this->pdf->setXY($coords[3],$fila_ini);
				        $this->pdf->Cell(45,$alto_fila,$concepto['nombre_corto'],$bordes,0,'L');
				        $this->pdf->Cell(2,$alto_fila,':',$bordes,0,'C');
				        $this->pdf->Cell(15,$alto_fila,sprintf('%.2f',trim($concepto['monto'])),$bordes,0,'R');
				        $fila_ini+=$alto_fila;
				        $total_aportaciones+=$concepto['monto'];
				}

				$fila_ini+=$alto_fila;
				$this->pdf->setXY($coords[3],$fila_ini);
				$this->pdf->Cell(62,$alto_fila,'--------------------------------------------------------------------', '',0,'C');


				$this->pdf->setXY($coords[3], ( 165) );
				$this->pdf->SetFont('Arial','B',$tamanio_letra);
				$this->pdf->Cell(45,$alto_fila,'TOTAL APORTACIONES S./',$bordes,0,'R');
				$this->pdf->Cell( 2,$alto_fila,':',$bordes,0,'C');
				$this->pdf->SetFont('Arial','',$tamanio_letra);
				$this->pdf->Cell(15,$alto_fila,sprintf('%.2f',trim($total_aportaciones)),$bordes,0,'R');

			 	
			 	$this->pdf->setXY( ($coords[1] ), ( 170) );
				$this->pdf->Cell( 193,1,'','T',0,'C');

				 
				$this->pdf->setXY( ($coords[3] - 4), ( 175) );
				$this->pdf->SetFont('Arial','B',($tamanio_letra + 2));
				$this->pdf->Cell(45,$alto_fila,'TOTAL A PAGAR  S./',$bordes,0,'R');
				$this->pdf->Cell( 2,$alto_fila,':',$bordes,0,'C');

				$total_a_pagar = $total_ingresos - $total_descuentos;
				$this->pdf->Cell(19,$alto_fila,sprintf('%.2f',trim($total_a_pagar)),$bordes,0,'R');

				$this->pdf->SetFont('Arial','',$tamanio_letra);


				$this->pdf->ln();
				$this->pdf->ln();
		  	    $this->pdf->ln();
		 		 

		  	    $this->pdf->SetFont('Arial','B', 6); 
		        $alto_fila = 4;
		  	
		        if(sizeof($tabla_afps) > 0 )
		        {


				        $header  = array_keys($tabla_afps[0]);

				        $this->pdf->Cell(30,$alto_fila, 'AFP', 'TBLR' ,0,'C'); 

				        for($i =2; $i<sizeof($header); $i++)
				        {
				            
						    $this->pdf->Cell(20,$alto_fila, $header[$i], 'TBLR' ,0,'C'); 
					 
				        }

				        $this->pdf->Cell(20, $alto_fila, 'TOTAL', 'TBLR' ,0,'C'); 

				        $this->pdf->ln();

				        $totales = array();


				        $this->pdf->SetFont('Arial','', 6); 
				        foreach($tabla_afps as $row)
				        {
				 			
				 			$this->pdf->SetFont('Arial','', 6); 
				 		    $total = 0;
				 			foreach($row as $k => $v)
					        {
					          	

					        	if($k == 'afp_nombre' && $k)
					        	{
				 					 $this->pdf->Cell(30, $alto_fila,  $v, 'TBLR' ,0,'L'); 
					        	}

					        	if($k != 'afp_id' & $k != 'afp_nombre')
					        	{ 	
					        		$v = ($v=='') ? '0.00' : $v;

					        		if($v != '') $total+=$v;

							  		$this->pdf->Cell( 20, $alto_fila, sprintf('%.2f', $v) , 'TBLR' ,0,'C'); 

							  		if($totales[$k] == '') $totales[$k] = 0;
							  		$totales[$k]+= $v;

							    }

					        }
					        
					        if($totales['total']=='') $totales['total'] = 0;
					        $totales['total']+=$total;
					        
					        $this->pdf->SetFont('Arial','B', 6); 
					        $this->pdf->Cell(20, $alto_fila, sprintf('%.2f',$total), 'TBLR' ,0,'C'); 

					        $this->pdf->ln();

				        }

				         $this->pdf->SetFont('Arial','B', 6); 
				         $this->pdf->Cell(30, $alto_fila,  'TOTAL: ', 'TBLR' ,0,'L'); 


				         foreach($totales as $totalv)
				         {
				         	 $this->pdf->Cell(20, $alto_fila, sprintf('%.2f',$totalv ), 'TBLR' ,0,'C'); 
				         }



				        $this->pdf->ln();
				  	    $this->pdf->ln();
				}
		 		
		  	    $this->pdf->SetFont('Arial','B', 6);
		 		$this->pdf->Cell(15, $alto_fila, 'FUENTE', 'TBLR' ,0,'C'); 
		 		$this->pdf->Cell(18, $alto_fila, 'PLANILLA', 'TBLR' ,0,'C'); 
		 		$this->pdf->Cell(15, $alto_fila,  'PARTIDA' , 'TBLR' ,0,'C'); 
		 		$this->pdf->Cell(15, $alto_fila, 'META', 'TBLR' ,0,'C'); 
		 		$this->pdf->Cell(15, $alto_fila,  'MONTO', 'TBLR' ,0,'C'); 
		 		$this->pdf->Cell(43, $alto_fila,  'PARTIDA' , 'TBLR' ,0,'C'); 
		 		$this->pdf->Cell(43, $alto_fila, 'META', 'TBLR' ,0,'C'); 
		 		$this->pdf->SetFont('Arial','',6);

		 	    $this->pdf->ln();

		 	    $total = 0;

		 		foreach($tabla_afectacion as $reg)
		 		{

		 			$fuente = $reg['fuente_id'].'-'.$reg['tipo_recurso'];

		 			$clasificador = trim($reg['clasificador_nombre']);

		 			if(strlen($clasificador) > 50 )
		 			{

		 			   $clasificador = '..'.utf8_decode(substr($clasificador,25,25)); 
		 			   if(strlen($reg['clasificador_nombre'])>50) $clasificador.=".."; 

		 			} 
		 			else
		 			{

		 			   $clasificador  = substr($clasificador,0,25);
		 			   if(strlen($reg['clasificador_nombre'])>25) $clasificador.=".."; 

		 			}


		 			$meta = trim($reg['meta_nombre']);

		 			if(strlen($meta) > 50 )
		 			{

		 			   $meta = '..'.utf8_decode(substr($meta,25,25)); 
		 			   if(strlen($reg['meta_nombre'])>50) $meta.=".."; 

		 			} 
		 			else
		 			{

		 			   $meta  = substr($meta,0,25);
		 			   if(strlen($reg['meta_nombre'])>25) $meta.=".."; 

		 			}

		  			$this->pdf->Cell(15, $alto_fila, $fuente  				  		   , 'TBLR' ,0,'C'); 
		 	 		$this->pdf->Cell(18, $alto_fila, trim($reg['pla_codigo']) 		   , 'TBLR' ,0,'C'); 
		 	 		$this->pdf->Cell(15, $alto_fila, trim($reg['clasificador_codigo']) , 'TBLR' ,0,'C'); 
		 	 		$this->pdf->Cell(15, $alto_fila, trim($reg['sec_func'])   		   , 'TBLR' ,0,'C'); 
		 	 		$this->pdf->Cell(15, $alto_fila, sprintf('%.2f',$reg['total'] )     , 'TBLR' ,0,'C'); 
		 	 		$this->pdf->Cell(43, $alto_fila, $clasificador 					   , 'TBLR' ,0,'L'); 
		 	 		$this->pdf->Cell(43, $alto_fila, $meta 					  		   , 'TBLR' ,0,'L'); 

			 		if($reg['total'] != '' && is_numeric($reg['total']) ) $total+=$reg['total'];

		 		    $this->pdf->ln();
		 		}	

		 		  
		 		if(sizeof($tabla_afectacion) == 0)
		 		{
					 
			 	    $this->pdf->Cell(164, $alto_fila, utf8_decode(' - No existe información presupuestal que pueda mostrarse, probablemente no hay ninguna tarea presupuestal asignada a la planilla'), '' ,0,'C'); 
				 	  		 			
		 		}
		 		else
		 		{	
		 		    $this->pdf->SetFont('Arial','B', 6);	
			 		$this->pdf->Cell(48, $alto_fila, '' 							   , '' ,0,'C'); 
			 		$this->pdf->Cell(15, $alto_fila, 'TOTAL: '				   		   , 'TBLR' ,0,'C'); 
			 		$this->pdf->Cell(15, $alto_fila, sprintf('%.2f',$total ) 	       , 'TBLR' ,0,'C');  

		  		    $this->pdf->ln();	

		  		    $this->pdf->SetFont('Arial','', 6);		
		 		}


		  
				 


		}

}				  


$this->pdf->Output();

?>
