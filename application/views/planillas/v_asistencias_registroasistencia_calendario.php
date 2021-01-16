<?PHP
 

if(function_exists('validar_fecha_postgres') == FALSE)
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


if(sizeof($calendario) == 0)
{
 
	  echo ' <div class="dv_busqueda_personalizada" style="font-size:11px"> 

	  				La hoja de asistencia no tiene trabajadores asignados 
  	  		</div>  ';

}
 
$_DIAS = array(
			  '1' => 'L',
			  '2' => 'M',
			  '3' => 'M',
			  '4' => 'J',
			  '5' => 'V',
			  '6' => 'S',
			  '7' => 'D'  );

$_DIAS_L = array(
			  '1' => 'Lunes',
			  '2' => 'Martes',
			  '3' => 'Miercoles',
			  '4' => 'Jueves',
			  '5' => 'Viernes',
			  '6' => 'Sabado',
			  '7' => 'Domingo'  );


$_MESES = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');


$n_fields = sizeof($calendario[0] );
$w_table =  ( ($n_fields - 2) * 35 ) + 340;

 

if(sizeof($calendario) > 0  && is_array($calendario))
{ 


?>

<div class="dvcontainer_calendar"> 

<input id="hdcalendariotipo" type="hidden" value="2" />
 
<table id="table_asistencia_calendario" border="1" width="<?PHP echo $w_table; ?>" > 


<?PHP
 

$counter_trabajadores = $contador_inicial;

$mes_act = 0;
$cc_hm = 0; /* contador de dias del mes, para el colspan del header mes*/


$trabajador_fila = '';

foreach($calendario as $ind =>  $reg)
{

	if($ind==0)
	{

		/* HEADER DEL CALENDARIO */ 
 	 	$header = array_keys($reg);


 	 		/* FILA, NOMBRE DEL MES */
			echo "<tr  class='tr_header'>
					<td rowspan='3' class='td_numeracion' > # </td>
					<td rowspan='3' width='300'> Nombres y Apellidos </td>
				 ";

			foreach($header as $k => $field)
			{
			   
			   list($anio, $mes, $dia) = explode('-', $field);
			    
	 		   if($k > 4 && validar_fecha_postgres($field) )
	 		   { 
					$dia = strtotime($field);	
					$mc  = date('n',$dia);

	 		        if($mc != $mes_act && $cc_hm > 0 )
	 		        {
		
	 		        	echo " <td colspan='".$cc_hm."'>".$_MESES[$mes_act]."</td>";
	 		        	$mes_act = $mc;
	 		        	$cc_hm=0;
	
	 		        }
	 		        else
	 		        {
	 		        	$mes_act = $mc;
	 		        }	
	 		        
	 		        $cc_hm++;

	 		   }  

			}

			echo " <td colspan='".$cc_hm."'>".$_MESES[$mes_act]."</td>";

		    echo "</tr>";

		    /*  FILA, INICIAL DE LOS DIAS */
 	 	 	echo "<tr  class='tr_header'>";

			foreach($header as $k => $field)
			{
			  	 
		 		   list($anio, $mes, $dia) = explode('-', $field);
		 		  			    
		 		   if($k > 4 &&  validar_fecha_postgres($field) )
		 		   {
		 		   	    $dia = strtotime($field);
		 		        echo "<td>".$_DIAS[date('N',$dia)]."</td>"; 

		 		   } 

			}

		    echo "</tr>";


		    /* FILA, NUMERO DEL DIA*/
		    echo "<tr  class='tr_header'>";

			foreach($header as $k => $field)
			{
			  	  
			  	   list($anio, $mes, $dia) = explode('-', $field);
			  	   		 		  			    
   		 		   if($k > 4 && validar_fecha_postgres($field) )
   		 		   {
 		
		 		        $dia = strtotime($field);
		 		        echo "<td>".date('d',$dia)."</td>"; 

		 		   } 

			}

		    echo "</tr>";

	}




	/* CUERPO DE LA TABLA */
 	 
 	echo "<tr class='tr_detalle'>";
 	 /* impresion de las celdas de cada registro */

 	$counter_trabajadores++; 
   
    $col = 1;
    $cf = 1;

	foreach($reg as $k => $field)
	{
 
		 if($k=='indiv_key')
		 {		 	
 
				$trabajador_fila = trim($reg['indice']);

		 		echo "<td class='td_numeracion'>".$counter_trabajadores."</td> 
 		 			  <td class='td_nombre_trabajador'> 

 		 		  	  <input type='hidden' value='".$trabajador_fila."'  class='spdetcal_indkey' /> 
 		 		  	  <input type='hidden' value='".trim($reg['indiv_key'])."'  class='spdetcal_individuo' />  
 		 		  	  <input type='hidden' value='".trim($reg['platica_id'])."'  class='spdetcal_categoria' />  
 		 		  	  
 		 		  	  <span class='spnombretrabajador'>".$reg['trabajador']." </span> 

 	        			 <div style='margin:2px 0px 0px 2px'>  <span class='sp11b'>  DNI: </span> <span class='sp11'> ".$reg['dni']." </span>";
 						
 							echo "  <span class='sp11b'> Tipo : </span> <span class='sp11'>".($reg['regimen_actual_abrev'] == '' ? '---' : $reg['regimen_actual_abrev'])." </span>";
 							
				        	if($hoja_info['hoa_tienecategorias'] == '1')
				        	{
					        	echo "  <span class='sp11b'> Categoria : ".($reg['categoria'] == '' ? '---' : $reg['categoria'])." </span>";
				        	}
				        	 

  	         	echo " </div> 


  	         		 ";

 	        	echo " </td>  ";
	
		 } 

	 	 		    
		 if( validar_fecha_postgres($k) )
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
	        
	        $marcaciones_ok = true;
	        
	        if($data[11] == '00:00:00'){

	        	$marcacion1 = '---';
	        	if($tipo == ASISDET_ASISTENCIA) $marcaciones_ok = false;

	        }else{

	   			$marcacion1 = $horas.':'.$minutos;    
	        }

	   	    list($horas, $minutos, $segundos) = explode(':', $data[12]);

   	        $marcaciones_ok = true;
   	         
   	        if($data[12] == '00:00:00'){

   	         	$marcacion2 = '---';
   	         	if($tipo == ASISDET_ASISTENCIA) $marcaciones_ok = false;

   	        }else{
 
	   			$marcacion2 = $horas.':'.$minutos;    
   	        }

	   	    list($horas, $minutos, $segundos) = explode(':', $data[13]);
	   		$marcacion3 = $horas.':'.$minutos;  
	   	    list($horas, $minutos, $segundos) = explode(':', $data[14]);
	   		$marcacion4 = $horas.':'.$minutos;      
       	   
			$dia_importado = trim($data[15]);
 			$pla_id = trim($data[16]);
			$pla_codigo = trim($data[17]);

			$minutos_a_descontar = trim($data[20]);
 			
		 

			if( trim($field) == '' )
			{
				$tipo = ASISDET_NOCONSIDERADO;
			} 
 

		    $label = ($tipo == ASISDET_NOCONSIDERADO ) ? '' : $rs_estados_dia[$tipo]['hatd_label'];
 
		    $mensaje_tooltip = '';
 	    
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

		 	        	 $mensaje_tooltip.=' | Tardanzas:'.$minutos_tardanzas.' min';		 	        	

					   	 // $label.= ' T:'.$minutos_tardanzas;

				 	 }
				 	
	 	        }	

	 	    	if($permisos!='0')
	 	        {
	 	        	// $label.= 'P:'.$permisos;
	 	        	$mensaje_tooltip.='  Permisos: '.$permisos.' min';
	 	        }	
	 	    	 
	 	    } 



				$obs = ($obs != '') ? $obs : '-------';

			   $tooltip = " <table style=\"font-size:12px;\" class=\"_tablepadding4\"> 
		 						<tr> 
		 							 <td colspan=\"3\">  <span class=\"sp11b\">".$fecha_larga." (".$fecha_corta.")</span>   </td>
		 						</tr> 
		 						<tr>
		 							  <td width=\"60\"> 
		 							  	 <span class=\"sp11b\"> Trabajador</span>
		 							  </td> 
		 							  <td width=\"5\"><span class=\"sp11b\">:</span> </td>
		 							  <td> 
		 							  	  ".$reg['trabajador']." 
		 							  </td> 
		 						</tr>
		 						<tr>
		 							  <td> 
		 							  	 <span class=\"sp11b\"> Estado </span>
		 							  </td> 
		 							    <td><span class=\"sp11b\">:</span> </td>
		 							  <td> 
		 							  	  ".$rs_estados_dia[$tipo]['hatd_nombre']."
		 							  </td> 
		 						</tr>   
		 				 	";

		 			

  			
		 				if($mensaje_tooltip != '')
		 				{

		 				  $tooltip.=" <tr> 
		 														<td> </td> 
		 														<td> </td> 
		 														<td>".$mensaje_tooltip."</td>  
		 												  </tr>";		

		 				}									  	

		 		 $tooltip.= "		
 								<tr>
		 							  <td> 
		 							  	<span class=\"sp11b\"> Observacion </span> 
		 							  </td> 
		 							    <td><span class=\"sp11b\">:</span> </td>
		 							  <td> 
		 							  	  ".$obs."
		 							  </td> 
		 						</tr> 
		 					";

 				 if($dia_importado == '1')
 				 {
			 			$tooltip.=  " <tr>
			 							  <td colspan=3> 
			 							  	 <span class=\"sp11b\"> Dia importado en la planilla : </span> ".$pla_codigo."
			 							  </td> 
			 						</tr>   ";
 				 }

		 		$tooltip.=" </table>";

 
 		 		 $bg_color = $rs_estados_dia[$tipo]['hatd_color'];

     
 		 		 if( $tipo != ASISDET_INDEFINIDO && ($laborable == '0') )
 		 		 {
 					 $bg_color = ASISDET_COLORCELESTE_DIANOLABORABLE;
				 }

 				 echo "<td class='td_fecha' style='background-color:".$bg_color."; ' > 

 				 	     <input type='hidden' class='colposition' value='".$col."' />
 				 	     <input type='hidden' class='estadodia' value='' />
 				 	     <input type='hidden' class='tdhojafecha' value='".$k."' /> 
 				 	     <input type='hidden' class='tdhojatrabajador' value='".$trabajador_fila."' /> 
 				 	     <input type='hidden' class='ttmensaje' value='".$tooltip."' /> 
 					 ";

 					 if($dia_importado == '1')
 					 {
 					 	
 					 	echo " <div style='background-color: #336699;  height:1px;   top:0px; position:float; display:float; margin:0px 0px 0px 0px;'> </div> ";
 					 }

 	      		echo     " 
 				 	     <div class='sp10' ";

 				 	     if($sin_contrato == '1')
 				 	     {
 				 	     	 echo " style='color:#990000;'   ";
 				 	     }

 				 	     if($marcaciones_ok == false){

 				 	     	 echo " style='color:#f0730f;font-weight:bold;'  ";  
 				 	     }

 				 echo     " > ";
 				 	     	   
 							 
 						   	  if( $params['modo_ver'] == MODOVERCALENDARIO_XDEFECTO	)
 						   	  { 
 						   	  	   echo $label;  
 						   	  }
 						   	  else if (  $params['modo_ver'] == MODOVERCALENDARIO_HORASASISTENCIA )
 						   	  {
 									
 								  if($tipo == ASISDET_ASISTENCIA)
 								  {	
 								  	  if($config['diario_tipo_horatrabajadas'] == '1')
 								  	  {
								 	 	 echo $horas_trabajadas.'h '.$min_trabajadas.'m';
 						   	  	  	  }
 						   	  	  	  else
 						   	  	  	  {	
 						   	  	  	  	  $horas_contabilizadas = round($horas_contabilizadas,1);
 						   	  	  	  	  echo $horas_contabilizadas.'h ';
 						   	  	  	  }
 						   	  	  }
 						   	  	  else
 						   	  	  {
 						   	  	  	  echo $label;  
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
				  		 			 		$tardanzas_minutos = '';
				  		 			 	}

				  	 				 	echo  $tardanzas_minutos;
 
 						   	  	  }
 						   	  	  else
 						   	  	  {
 						   	  	  	  echo $label;  
 						   	  	  }	 
   						   	  }	
						   	  else if (  $params['modo_ver'] == MODOVERCALENDARIO_PERMISOS )
							  {
								  if($tipo == ASISDET_ASISTENCIA)
								  {
				  	 				 	 
				  	 				 echo $minutos_a_descontar;

						   	  	  }
						   	  	  else
						   	  	  {
						   	  	  	  echo $label;  
						   	  	  }	 
						   	  }	
   						 	  else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION1 )
   						   	  {	 

   						   	  	  echo ($tipo == ASISDET_ASISTENCIA ? $marcacion1 : $label);

 						   	  }	
   						 	  else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION2 )
   						   	  {
   						   	   	  echo ($tipo == ASISDET_ASISTENCIA ? $marcacion2 : $label);
 						   	  }
    						  else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION3 )
    						  {
    						   	   echo ($tipo == ASISDET_ASISTENCIA ? $marcacion3 : $label);
  						   	  }
    						  else if (  $params['modo_ver'] == MODOVERCALENDARIO_MARCACION4 )
    						  {
    						   	   echo ($tipo == ASISDET_ASISTENCIA ? $marcacion4 : $label);
  						   	  }		
 						   	  else
 						   	  {

 						   	  	    echo $label;  
 						   	  }


 				        	  if($biometrico_id != '' && $biometrico_id !='0' && $params['modo_ver'] != MODOVERCALENDARIO_HORASASISTENCIA)
 				 	     	  {
 				 	     	  		//echo '.';
 				 	     	  } 
 				 	     	  // echo '*' Si hay datos incompletos del biometrico

 				echo 	  "
 				 	     </div> 
 				  	  </td>";  

 		 
 
	 		 $col++;
 		 }
 		  
		$cf++;
	}

    echo "</tr>";

}

?>

</table>
</div>

<?PHP 

	}
?>