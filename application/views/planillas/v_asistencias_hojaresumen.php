 <?PHP
 

$n_fields = sizeof($resumen[0] );
$w_table =  ( ($n_fields - 1) * 35 ) + 340;
 
?>

<div class="dvcontainer_calendar">
	 
	<table id="table_asistencia_hoja_resumen" border="1" width="<?PHP echo $w_table; ?>" > 


	<?PHP

	$counter_trabajadores = 0;
	 
	$trabajador_fila = '';

	foreach($resumen as $ind =>  $reg)
	{

		if($ind==0)
		{
			/* HEADER DEL RESUMEN */ 

	 	 	echo "<tr class='tr_header'>
					<td   class='td_numeracion' > #</td>
					<td   width='300'> Trabajador </td>
				 ";
		     
	 	 	$header = array_keys($reg); 
			foreach($header as $k => $field)
			{
		 
				list($pref, $label) = explode('_', $field);

		 		   if($pref == 'dato')
		 		   {
		 		   	 	echo " <td width='50'>".$label."</td>";		
		 		   }  
		   
			}
			 
		    echo "</tr>"; 

		}

	 	$counter_trabajadores++; 

		/* CUERPO DE LA TABLA */
	 	$indiv_id = $reg['indiv_id'];
	 
	 	$class = 'existe_preview'; 	// ($existe == '1') ? 'existe_preview' : 'no_existe_preview';

	 	$nombre_trabajador = " <span class='".$class."'> ".$reg['trabajador']." (".$reg['dni'].") </span>";


	 	echo "<tr class='tr_detalle'>

	 		 		<td align='center'> <input type='hidden' value='".$indiv_id."' class='hdindiv_id' /> ".$counter_trabajadores." </td>
	 		 		<td> ".$nombre_trabajador." </td> 		

	 		 ";

	   	 /* impresion de las celdas de cada registro */
 
	    
		foreach($reg as $k => $field)
		{
 		  
	  	   list($pref, $label) = explode('_', $k);

   		   if($pref == 'dato')
   		   {
   		   	 	echo " <td class='td_fecha'>".$field."</td>";		
   		   }  	  
		}

	    echo "</tr>";

	}

	?>

	</table>
	 
 </div>