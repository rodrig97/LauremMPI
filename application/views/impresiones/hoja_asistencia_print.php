<?php

$this->pdf->initialize('L','mm','A4');
$this->pdf->AddPage();
$this->pdf->SetTopMargin(0);
 
$pointer_v 		= 17;
$alto_fila 		= 4;
$bordes 		= 'TRLB';

$tamanio_letra_header = 7;
$tamanio_letra_celda  = 5;

$ancho_hoja = 275; 
$alto_fila_header = 4;


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


if(function_exists('validar_fecha_postgres') === FALSE )
{
	function validar_fecha_postgres($fecha)
	{
	    list($anio, $mes, $dia) = explode('-', $fecha);

	    if( strlen($anio) == 4 && strlen($mes) == 2 && strlen($dia) == 2 )
	    {
	         return true;
	    }
	    else
	    {
	         return false;
	    }
	}
}

/* 

0 : numeracion
1 : Nombre 
2:  Categoria 
3 :   

*/ 

 
$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
 
$_DIAS = array(   '1' => 'L',
				  '2' => 'M',
				  '3' => 'M',
				  '4' => 'J',
				  '5' => 'V',
				  '6' => 'S',
				  '7' => 'D'  );

$_DIAS_L = array( '1' => 'Lunes',
				  '2' => 'Martes',
				  '3' => 'Miercoles',
				  '4' => 'Jueves',
				  '5' => 'Viernes',
				  '6' => 'Sabado',
				  '7' => 'Domingo'  );


$_MESES = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
 

$this->pdf->setXY(0,$pointer_v);
$this->pdf->ln();
  

if(sizeof($calendario) == 0)
{
    $this->pdf->ln();
 	$this->pdf->Cell(200,$alto_fila,' La hoja de asistencia no tiene trabajadores asignados ','',0,'L');
}


$counter_trabajadores = 0;

$mes_act = 0;
$cc_hm = 0; /* contador de dias del mes, para el colspan del header mes*/

$trabajador_fila = '';
$nc_resumen = 0;

$this->pdf->SetFont('Arial','', 5);


$totales_por_dia = array();

foreach($calendario as $ind =>  $reg)
{ 

		if($this->pdf->getY() >= 185 || $ind == 0  )
		{

			if($ind > 0 )
			{
				$this->pdf->AddPage();
			}

			$this->pdf->SetFont('Arial','B',($tamanio_letra_header +1 ));
			$this->pdf->Cell(280,$alto_fila,' REGISTRO DE ASISTENCIA - '.strtoupper(trim($hoja_info['tipo_planilla'])),'',0,'C');
			 
			$this->pdf->ln();
			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(16, $alto_fila_header, 'Codigo','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(13, $alto_fila_header,  ((trim($hoja_info['hoa_codigo']) == '' ) ? '------'  : trim($hoja_info['hoa_codigo']) ),'',0,'L');
			 
			$this->pdf->SetFont('Arial','B',$tamanio_letra_header); 
			$this->pdf->cell(10, $alto_fila_header, 'Desde','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(14, $alto_fila_header,  _get_date_pg(trim($hoja_info['hoa_fechaini'])) ,'',0,'L');

			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(10, $alto_fila_header, 'Hasta','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(14, $alto_fila_header,  _get_date_pg(trim($hoja_info['hoa_fechafin'])) ,'',0,'L');
			
			$this->pdf->ln();
			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(16, $alto_fila_header, 'Proyecto','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(13, $alto_fila_header,  ((trim($hoja_info['meta_nombre']) == '' ) ? '------'  : trim($hoja_info['tarea_codigo']).' - '.utf8_decode(trim($hoja_info['meta_nombre'])) ),'',0,'L');
			

			$this->pdf->ln(); 
			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(16, $alto_fila_header, utf8_decode('Descripción'),'',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(50, $alto_fila_header,  ((trim($hoja_info['hoa_descripcion']) == '' ) ? '------'  : trim($hoja_info['hoa_descripcion']) ),'',0,'L');
			 
			 
 
	        $this->pdf->SetFillColor(80, 192, 192);
		    $fill = true;
	
			/* HEADER DEL CALENDARIO */ 

			$this->pdf->SetFont('Arial','',$tamanio_letra_celda);
	 	 	$header = array_keys($reg);

	 	 	$this->pdf->ln();
	 	 	$this->pdf->ln();
	 	 	$this->pdf->Cell($sizes[0],($alto_fila * 3),' # ',$bordes,0,'C', $fill);
	 	 	$this->pdf->Cell($sizes[1],($alto_fila * 3),'Apellidos y nombres ',$bordes,0,'C', $fill);
 			
 			if($hoja_info['hoa_tienecategorias'] == '1')
 			{
 				$this->pdf->Cell($sizes[2], ($alto_fila * 3), 'Categoria', $bordes, 0, 'C', $fill);
 			}

 		

 			if($ind == 0)
 			{

				foreach($header as $k => $field)
				{
			 		    
			 		   if(validar_fecha_postgres($field) )
			 		   { 
	  
							$dia = strtotime($field);	
							$mc  = date('n',$dia);

			 		        if($mc != $mes_act && $mes_act != '' )
			 		        {
			 		        	$this->pdf->Cell( ( $cc_hm * $ancho_celda_calendario ), $alto_fila, $_MESES[$mes_act], $bordes, 0, 'C', $fill);
			 		         	$mes_act = $mc;
			 		        	$cc_hm = 0;
			 		        }
			 		        else
			 		        {
			 		        	$mes_act = $mc;
			 		        }	

			 		        $cc_hm++;
	 
			 		   }  
 
			 		   list($tipo, $label) = explode('_', $field);

			 		   if( $tipo == 'dato')
			 		   {
			 		   		$nc_resumen++;
			 		   		 
			 		   }
 
				}
			}

			$this->pdf->Cell( ( $cc_hm * $ancho_celda_calendario ), $alto_fila, $_MESES[$mes_act], $bordes, 0, 'C', $fill);

			if( $nc_resumen > 0)
			{
				$this->pdf->Cell( ( $nc_resumen * $ancho_celda_calendario ),  $alto_fila, 'RESUMEN', 'RLT', 0, 'C', $fill);
			}
 			
 			$this->pdf->ln();

 			// Espacio en blanco del margen izquierdo sumando las 2/3 columnas impresas arriba altofila*3
 			
 			if($hoja_info['hoa_tienecategorias'] == '1')
 			{

 				$this->pdf->Cell( ($sizes[0] + $sizes[1] + $sizes[2]), $alto_fila,  '','',0,'C');
 			}
 			else
 			{
 				$this->pdf->Cell( ($sizes[0] + $sizes[1]), $alto_fila,  '','',0,'C');	
 			}	


		    /*  FILA, INICIAL DE LOS DIAS */
 	 	 	foreach($header as $k => $field)
			{
		 	    
	 		   if(validar_fecha_postgres($field) )  
	 		   { 
			        $dia = strtotime($field);
	 		        $this->pdf->Cell($ancho_celda_calendario, $alto_fila, $_DIAS[date('N',$dia)], $bordes, 0, 'C', $fill);
	 		   } 
 
			}
 			
 			if( $nc_resumen > 0)
 			{
 				$this->pdf->Cell( ( $nc_resumen * $ancho_celda_calendario ),  $alto_fila, '', 'RLB', 0, 'C', $fill);
 			}

			$this->pdf->ln();
			// Espacio en blanco del margen izquierdo sumando las 2/3 columnas impresas arriba altofila*3
			if($hoja_info['hoa_tienecategorias'] == '1')
 			{

 				$this->pdf->Cell( ($sizes[0]+ $sizes[1]+ $sizes[2]),$alto_fila,  '','',0,'C');
 			}
 			else
 			{
 				$this->pdf->Cell( ($sizes[0]+ $sizes[1]),$alto_fila,  '','',0,'C');	
 			}	


		    /* FILA, NUMERO DEL DIA*/
		 	foreach($header as $k => $field)
			{
			  
			   if(validar_fecha_postgres($field) )
			   { 
			  	    $dia = strtotime($field);
	 		        $this->pdf->Cell($ancho_celda_calendario, $alto_fila, date('d',$dia), $bordes, 0, 'C', $fill);
	 		   } 


	 		   list($tipo, $label) = explode('_', $field);

	 		   if( $tipo == 'dato')
	 		   {

	 		   		$this->pdf->Cell($ancho_celda_calendario, $alto_fila, $label, $bordes, 0, 'C', $fill);

	 		   }

			}

			$this->pdf->ln();
			

		} // Terminamos de imprimir la cabezera del reporte


		// Recorremos campo por campo 

		$counter_trabajadores++; 
		
		foreach($reg as $k => $field)
		{
		     
		     $total_celda = 0;

			if($k=='indiv_key')
			{	
				 $this->pdf->Cell($sizes[0], $alto_fila, $counter_trabajadores,$bordes,0,'C');
	 	 		 $this->pdf->Cell($sizes[1], $alto_fila, utf8_decode(trim($reg['trabajador'])),$bordes,0,'L');

	 	 		 if($hoja_info['hoa_tienecategorias'] == '1')
	 	 		 {
	 	 		 	$this->pdf->Cell($sizes[2], ($alto_fila), utf8_decode(trim($reg['categoria'])), $bordes, 0, 'C', false);
	 	 		 }
			 
			}	
			else if( validar_fecha_postgres($k)  )
			{ 
			 	$fecha_corta = _get_date_pg($k);   

			 	$ft =  strtotime($k);
			    $anio = date('Y', $ft );
			    $mes = $_MESES[date('n', $ft)];
			    $dia = date('d', $ft);
			    $dia_sem = date('N', $ft);
			    $dial = $_DIAS_L[date('N', $ft)];
	 			$fecha_larga =  $dial.', '.$dia.' de '.$mes.' del '.$anio;
	 
				$data      = explode('_',$field);
	 
				$tipo      = trim($data[0]);
				$obs 	   = trim($data[1]);
				
				$tardanzas_horas = trim($data[2]);
				$tardanzas_minutos = trim($data[3]);

			 
				$permisos  = trim($data[4]);
		        $sin_contrato  = trim($data[5]); 
		        $laborable  = trim($data[6]);
		        $biometrico_id  = trim($data[7]);
		        $horas_trabajadas = trim($data[8]);
		        $min_trabajadas = trim($data[9]);
		        $horas_contabilizadas = trim($data[10]);

		        list($horas, $minutos, $segundos) = explode(':', $data[11]);
		   		$marcacion1 = $horas.':'.$minutos;    
		   	    list($horas, $minutos, $segundos) = explode(':', $data[12]);
		   		$marcacion2 = $horas.':'.$minutos;    
		   	    list($horas, $minutos, $segundos) = explode(':', $data[13]);
		   		$marcacion3 = $horas.':'.$minutos;  
		   	    list($horas, $minutos, $segundos) = explode(':', $data[14]);
		   		$marcacion4 = $horas.':'.$minutos;      
	       	   
				$dia_importado = trim($data[15]);
	 			$pla_id = trim($data[16]);
				$pla_codigo = trim($data[17]);
				
			 

				if( trim($field) == '' )
				{
					$tipo = ASISDET_NOCONSIDERADO;
				} 
	 

			    $label = ($tipo == ASISDET_NOCONSIDERADO ) ? '' : $rs_estados_dia[$tipo]['hatd_label'];
	  
		 	    if($tipo == ASISDET_NOCONSIDERADO && $obs != '' ) $label = 'Obs'; 
					
		 	    if($tipo == ASISDET_ASISTENCIA  )
		 	    {

		 	        if( $tardanzas_horas !='0' || $tardanzas_minutos != '0' )
		 	        {
					 	 if( ($tardanzas_horas*1) > 0 || ($tardanzas_minutos * 1) > 0 )
					 	 {
						 	 $minutos_tardanzas = $tardanzas_minutos*1;
			 	         	 $emin = ($tardanzas_horas > 0 ) ? ($tardanzas_horas*60) : 0;
			 	        	 $minutos_tardanzas+=$emin;
					 	 }
				    }	

		 	    	if($permisos!='0')
		 	        {
		 	         
		 	        }	
		 	    	 
		 	    } 
 

				 $obs = ($obs != '') ? $obs : '-------';

 		 		 $bg_color = $rs_estados_dia[$tipo]['hatd_color'];

     
 		 		 if( $tipo != ASISDET_INDEFINIDO && ($laborable == '0') )
 		 		 {
 					 $bg_color = ASISDET_COLORCELESTE_DIANOLABORABLE;
				 }
	   
						 
			   	  if( $params['modo_ver'] == MODOVERCALENDARIO_XDEFECTO	)
			   	  { 
			   	  	   //echo $label;  
			   	  }
			   	  else if (  $params['modo_ver'] == MODOVERCALENDARIO_HORASASISTENCIA )
			   	  {
						
					  if($tipo == ASISDET_ASISTENCIA)
					  {	
					  	  if($config['diario_tipo_horatrabajadas'] == '1')
					  	  {
				 	 	 	 $label = $horas_trabajadas.'h '.$min_trabajadas.'m';
			   	  	  	  }
			   	  	  	  else
			   	  	  	  {
			   	  	  	  	  $label = $horas_contabilizadas.'h ';

			   	  	  	  	  $total_celda = ($horas_contabilizadas * 1);
			   	  	  	  }
			   	  	  }
			   	  	  else
			   	  	  {
			   	  	  	  $label = $label;  
			   	  	  }
			   	  }	
			   	  else if (  $params['modo_ver'] == MODOVERCALENDARIO_TARDANZAS )
			 	  {
					  if($tipo == ASISDET_ASISTENCIA)
				  	  {

	  	 				 	if( ($tardanzas_horas*1) > 0 || ($tardanzas_minutos * 1) > 0 )
	  	 				 	{
	  		 				 	$emin = ($tardanzas_horas > 0 ) ? ($tardanzas_horas*60) : 0;
	  		 				 	$tardanzas_minutos+=$emin;
	  		 			 	} 
	  		 			 	else
	  		 			 	{
	  		 			 		$tardanzas_minutos = '0';
	  		 			 	}
	  	 				 	
	  	 				 	$label =  $tardanzas_minutos.' min ';

			   	  	  }
			   	  	  else
			   	  	  {
			   	  	  	  $label = $label;  
			   	  	  }	 
			   	  }	
			 	  else if( $params['modo_ver'] == MODOVERCALENDARIO_MARCACION1 )
			   	  {	 
			   	  	   $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion1 : $label);
			   	  }	
			 	  else if( $params['modo_ver'] == MODOVERCALENDARIO_MARCACION2 )
			   	  {
			   	   	   $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion2 : $label);
			   	  }
				  else if( $params['modo_ver'] == MODOVERCALENDARIO_MARCACION3 )
				  {
				   	   $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion3 : $label);
				  }
				  else if( $params['modo_ver'] == MODOVERCALENDARIO_MARCACION4 )
				  {
			   	   	   $label = ($tipo == ASISDET_ASISTENCIA ? $marcacion4 : $label);
			   	  }		
			   	  else
			   	  {
			   	  	    $label = $label;  
			   	  } 


	        	  if($biometrico_id != '' && $biometrico_id !='0' && $params['modo_ver'] != MODOVERCALENDARIO_HORASASISTENCIA)
	 	     	  {
	 	     	  		//echo '.';
	 	     	  } 
			 	 
			 	 $label = (trim($label) == '') ? '---' : $label;   	 

			  	 $this->pdf->Cell($ancho_celda_calendario,$alto_fila, $label ,$bordes,0,'C');
	 
		 		 $col++;

		 		 if($totales_por_dia[$k] == null)
		 		 {
		 		 	 $totales_por_dia[$k] = 0;
		 		 }

		 		 $totales_por_dia[$k] = $totales_por_dia[$k] + $total_celda; 
				 		 
			}
			else
			{

				 list($tipo, $label) = explode('_', $k);

				 if( $tipo == 'dato')
				 {

				  	$this->pdf->Cell($ancho_celda_calendario, $alto_fila,  $field, $bordes,  0, 'C', false);

				  	if($totales_por_dia[$label] == null)
				  	{
				  		 $totales_por_dia[$label] = 0;
				  	}

				  	$totales_por_dia[$label] = $totales_por_dia[$label] + $field; 


				 }
			}


 	 	}

 	 	
        $this->pdf->ln();
 
}

 

$this->pdf->Cell(($sizes[0]+$sizes[1]+$sizes[2]), $alto_fila, 'TOTALES:',$bordes,0,'R'); 

foreach ($totales_por_dia as $key => $total_columna)
{
	$this->pdf->Cell($ancho_celda_calendario,$alto_fila, $total_columna ,$bordes,0,'C');
}

$this->pdf->ln();


// SI EL RESUMEN OCUPA MUCHO ESPACIO ES NECESARIO IMPRIMIRLO DEBAJO EN OTRA HOJA

if($reporte_unico === FALSE)
{ 

	$this->pdf->AddPage();
	 
	$alto_fila 		= 5;
	$bordes 		= 'TRLB';
	 
	$size_cell = 9;

	$sizes = array(8, 60, 22, 12);

	$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
  
	$counter_trabajadores = 0;

	foreach ($resumen  as $ind =>  $reg)
	{

		if($this->pdf->getY() >= 185 || $ind == 0  )
		{

			if($ind > 0 )
			{

				$this->pdf->AddPage();
			}

			$this->pdf->SetFont('Arial','B',($tamanio_letra_header +1 ));
			$this->pdf->Cell(280,$alto_fila,' RESUMEN ','',0,'C');
			 
			$this->pdf->ln();
  
			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(10, $alto_fila_header, 'Codigo','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(13, $alto_fila_header,  ((trim($hoja_info['hoa_codigo']) == '' ) ? '------'  : trim($hoja_info['hoa_codigo']) ),'',0,'L');
			 
			$this->pdf->SetFont('Arial','B',$tamanio_letra_header); 
			$this->pdf->cell(10, $alto_fila_header, 'Desde','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(14, $alto_fila_header,  _get_date_pg(trim($hoja_info['hoa_fechaini'])) ,'',0,'L');

			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(10, $alto_fila_header, 'Hasta','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(14, $alto_fila_header,  _get_date_pg(trim($hoja_info['hoa_fechafin'])) ,'',0,'L');

			$this->pdf->ln();

			$this->pdf->SetFont('Arial','B',$tamanio_letra_header);
			$this->pdf->cell(14, $alto_fila_header, 'Descripcion','',0,'L');
			$this->pdf->cell(2, $alto_fila_header, ':','',0,'L');
			$this->pdf->SetFont('Arial','',$tamanio_letra_header);
			$this->pdf->cell(50, $alto_fila_header,  ((trim($hoja_info['hoa_descripcion']) == '' ) ? '------'  : trim($hoja_info['hoa_descripcion']) ),'',0,'L');
			 
			 

	        $this->pdf->SetFillColor(80, 192, 192);
		    $fill = true;

			/* HEADER DEL CALENDARIO */ 
			$this->pdf->SetFont('Arial','',$tamanio_letra_celda);
	 	 	$header = array_keys($reg);

	 	 	$this->pdf->ln();

	 	 	$this->pdf->Cell($sizes[0],($alto_fila),' # ',$bordes,0,'C', $fill);
	 	 	$this->pdf->Cell($sizes[1],($alto_fila),'Nombres y Apellidos',$bordes,0,'C', $fill);
	 	 	$this->pdf->Cell($sizes[3],($alto_fila),'DNI',$bordes,0,'C', $fill);
				
			if($hoja_info['hoa_tienecategorias'] == '1')
			{
				$this->pdf->Cell($sizes[2], ($alto_fila), 'Categoria', $bordes, 0, 'C', $fill);
			}
	 	
			foreach($header as $field)
			{
				list($tipo, $label) = explode('_', $field );

				if($tipo == 'dato')
				{
					
					$this->pdf->Cell($size_cell, $alto_fila,  $label, $bordes,  0, 'C', $fill);
				}

			}
			$this->pdf->ln();
			 
		}
 
			 
		$counter_trabajadores++; 
		
		$this->pdf->SetFont('Arial','',$tamanio_letra_celda);
		
		foreach($reg as $k => $field)
		{
		     
			if($k=='indiv_key')
			{	
				 
				 $this->pdf->Cell($sizes[0], $alto_fila, $counter_trabajadores,$bordes,0,'C', false);
	 	 		 $this->pdf->Cell($sizes[1], $alto_fila, trim($reg['trabajador']),$bordes,0,'L', false);
	 	 		 $this->pdf->Cell($sizes[3], $alto_fila, trim($reg['indiv_dni']),$bordes,0,'C', false);

	 	 		 if($hoja_info['hoa_tienecategorias'] == '1')
	 	 		 {
	 	 		 	$this->pdf->Cell($sizes[2], ($alto_fila), trim($reg['platica_nombre']), $bordes, 0, 'C', false);
	 	 		 }
			 
			}	
	 
			list($tipo, $label) = explode('_', $k );

			if($tipo == 'dato')
			{
				
				$this->pdf->Cell($size_cell, $alto_fila,  $field, $bordes,  0, 'C', false);
			}
			 
			 
		}

	    $this->pdf->ln();
	 

	}           

}

$fecha = date('d/m/Y').' '.date('h:i:s A');
$this->pdf->Cell(150,$alto_fila,  $fecha. ' Autorizacion: '.$usuario['persona_key'].' por: '.$usuario['user_nombre'] , '',0,'L');

$this->pdf->Output();

  