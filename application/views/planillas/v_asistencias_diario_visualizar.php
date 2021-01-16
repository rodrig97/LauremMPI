<div class="window_container">
	
<input type="hidden" id="hddiariofechamin" value="<?PHP  echo $fecha;   ?>"/>
<input type="hidden" id="hddiariofechamax" value="<?PHP  echo $hoja_info['hoa_fechafin'];   ?>"/>
  

<div id="form_asis_registrodiario" data-dojo-type="dijit.form.Form">  

 <!--      <span id="cut_tooltip" data-dojo-type="dijit.Tooltip" data-dojo-props='connectId:["tblsts"]'>tooltip for cut</span>
  -->
 	 <input type="hidden" name="trabajador" value="<?PHP echo $trabajador_key;  ?>" /> 
	 <input type="hidden" name="dia" value="<?PHP  echo $fecha;   ?>" />
	 <input type="hidden" name="hoja" value="<?PHP echo $hoja;   ?>" />  

	 <input type="hidden" id="hddiario_time1" value="<?PHP echo trim($diario_info['hoae_hora1_e']);   ?>" /> 
	 <input type="hidden" id="hddiario_time2" value="<?PHP echo trim($diario_info['hoae_hora1_s']);   ?>" /> 
	 <input type="hidden" id="hddiario_time3" value="<?PHP echo trim($diario_info['hoae_hora2_e']);   ?>" /> 
	 <input type="hidden" id="hddiario_time4" value="<?PHP echo trim($diario_info['hoae_hora2_s']);   ?>" /> 

	 <div class="dv_busqueda_personalizada_pa2">
 
	 	   <table>
	 	   		<tr> 
	 	   			<td colspan="3"> <span class="sp11b"> <?PHP echo $fecha_larga.' ('.$fecha_corta.')' ?> </span> 
	 	   				<!-- 	<?PHP 
	 	   						 if($sin_contrato)
	 	   						 {
	 	   						 	echo ' <span class="sp11b" style="color:#990000"> [ El contrato actual no es vigente en esta fecha ] </span> ';
	 	   						 }
	 	   					?> -->
	 	   			</td>
	 	   		</tr>
	 	   		<tr> 
	 	   			<td colspan="3"> <span class="sp11b">  <?PHP echo $trabajador ?> </span> </td>
	 	   		</tr>	
   			 	<?PHP

   		 		if($hoja_info['hoa_tienecategorias']  == '1')
   		 		{

   			 	?>

   			 	<tr height="30">
   			 		<td width="60"><span class="sp11b"> Categoria </span></td>
   			 		<td width="5"><span class="sp11b"> :  </span></td>
   			 		<td> 
   			 			 
   			 		   	<span class="sp12b"> <?PHP echo $diario_info['platica_nombre']; ?> </span>

   			 		</td>
   			 	</tr>
   			 
   			 	<?PHP 
   		 		}
   			 	?>	
	 	   </table>
	 </div>


	 <?PHP 
	 	if($mostrar_full_info == '1' && $diario_info['hoaed_id'] != '' && $diario_info['hoa_id'] != '' )
	 	{ 
	 ?>

 	  	  <div class="dv_celeste_mensaje"> 
 	  	  	 <table class="_tablepadding4">
 	  	  	 		<tr>
 	  	  	 			<td> 
 	  	  	 				<span class="sp11b">  
 	  	  	 					 Hoja de asistencia <?PHP  echo $diario_info['hoa_codigo']; ?> de la meta <?PHP  echo $diario_info['meta_codigo']; ?>, creada por <?PHP echo $diario_info['responsable_hoja']; ?>
 	  	  	 				</span>
 	  	  	 			</td>	
 	  	  	 			 
 	  	  	 		</tr>
 	  	  	 </table>
 	  	  </div>

 	 <?PHP 
 	 	}
 	 ?>

	 <?PHP 


	 	if($diario_info['hoaed_id'] == '')
	 	{
	 	    echo '		 <span class="sp11b">  No existe registro de asistencia para este día  </span> 

	 	    		</div>
	 	    		</div>
	 	    	';

	 	    die();
	 	}

 		if($diario_info['hoaed_importado'] == '1')
	    {
 	  ?>		

 	  	  <div class="dv_green_mensaje"> 
 	  	  	 <table class="_tablepadding4">
 	  	  	 		<tr>
 	  	  	 			<td> 
 	  	  	 				<span class="sp11b">  
 	  	  	 					 Importado a la planilla  <?PHP  echo $diario_info['planilla_importado']; ?> el día <?PHP echo $fecha_importacion; ?>
 	  	  	 				</span>
 	  	  	 			</td>	
 	  	  	 			 
 	  	  	 		</tr>
 	  	  	 </table>
 	  	  </div>

 	  <?php 
	    }
	 ?>

	 <table class="_tablepadding4" style="width:100%" border="0">


	 	<tr class="row_form">
	 		<td width="90"><span class="sp11b"> Info del día </span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
	 			   
	 		     <?PHP 
	 		     	 $td = ($diario_info['hoaed_laborable'] == '1') ? ' Día Laborable ' :  ' No Laborable';
	 		    	
	 		     	 echo '<span class="sp12">'.$td.'</span>';


	 		     	 if($diario_info['biom_id'] != '0')
	 		     	 {

	 		     	 	 echo ' <div>  <span class="sp11b"> Registro importado desde dispositivo biometrico. </span> </div> ';
	 		     	 }
	 		      
	 		     ?>
 
	 		</td> 
		 
	 	</tr>
 

	 	<tr class="row_form">
	 		<td width="90"><span class="sp11b"> Estado del dia</span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
	 			  <span class="sp12"> <?PHP echo $diario_info['hatd_nombre']; ?> </span>

	 			  
	 			  	 		    <span class="sp11b" id="spHorario" style="cursor:pointer;"> [H] </span>

	 			  	 		    <span data-dojo-type="dijit.Tooltip" data-dojo-props='connectId:["spHorario"], position:["above"] '> 

	 			  	 		    		<table class="_tablepadding2">
	 			   							<tr class="row_form">
	 			   								<td width="60"> 
	 			   									<span class="sp11b"> Horario </span>
	 			   								</td>
	 			   								<td width="5"> 
	 			   									<span class="sp11b"> : </span>
	 			   								</td>

	 			   								<td colspan="4"> 
	 			   									 <span class="sp11"> <?PHP echo $diario_info['hor_alias']; ?> </span> 
	 			   								</td>	

	 			   							</tr>

	 			   							 <tr class="row_form">
	 			   							 	<td><span class="sp11b"> Ingreso</span></td>
	 			   							 	<td><span class="sp11b">:</span></td>
	 			   							 	<td width="70">  
	 			   							 		<span class="sp11"> 
	 			   							 		<?PHP 
	 			   							 			 echo (trim($diario_info['hor_hora1_e']) != '') ?  trim($diario_info['hor_hora1_e']) : '-----';
	 			   							 		?>	 
	 			   							 		</span>
	 			   							 	</td>
	 			   							 	<td width="60"> <span class="sp11b"> Salida</span></td>
	 			   							 	<td width="5"> <span class="sp11b">:</span></td>
	 			   							    <td width="70">  
	 			   							 		<span class="sp11"> 
	 			   							 	 	<?PHP 
	 			   							 	 		 echo (trim($diario_info['hor_hora1_s']) != '') ?  trim($diario_info['hor_hora1_s']) : '-----';
	 			   							 	 	?>	 
	 			   							 		</span>
	 			   							 	</td>
	 			   							 </tr>

	 			   							 	<?PHP 
	 			   							 
	 			   							 	 if(trim($diario_info['hor_numero_horarios']) == '2')
	 			   							 	 {

	 			   							 	?>
	 			   							 	
	 			   							  	<tr  class="row_form">
	 			   							 		<td><span class="sp11b">Tarde Ingreso</span></td>
	 			   							 		<td><span class="sp11b">:</span></td>
	 			   							 		<td> 
	 			   							 			<span class="sp11"> 
	 			   							 			 <?PHP 
	 			   							 			 	 echo (trim($diario_info['hor_hora2_e']) != '') ?  trim($diario_info['hor_hora2_e']) : '-----';
	 			   							 			 ?>
	 			   							 			 </span>
	 			   							 		</td>

	 			   							 		<td><span class="sp11b"> Salida</span></td>
	 			   							 		<td><span class="sp11b">:</span></td>
	 			   							 		<td> 
	 			   							 			<span class="sp11"> 
	 			   							 			 <?PHP 
	 			   							 			 	 echo (trim($diario_info['hor_hora2_s']) != '') ?  trim($diario_info['hor_hora2_s']) : '-----';
	 			   							 			 ?>
	 			   							 			</span>
	 			   							 		</td>
	 			   							 	</tr>
	 			   							 
	 			   							 	<?PHP 
	 			   							 	  }
	 			   							 	?>

	 			  	 		    		</table>

	 			  	 		    </span>


	 		</td>
	 	</tr>


<?PHP
 
	 	if($diario_info['htp_registrar_marcacion_horas'] == '1')
	 	{   

?>
 	

	 	<tr id="trregistrodiario_horario1" class="row_form">
	 		<td><span class="sp11b">Mañana Ingreso</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 	
	 			  <?PHP  

	 			  	if(trim($diario_info['hoae_hora1_e']) != '')
	 			  	{

	 			  		list($horas, $minutos, $segundos) = explode(':', $diario_info['hoae_hora1_e'] );
	 			  		$marcacion = $horas.':'.$minutos.' horas';    	

	 			  		echo  '<span class="sp12">'.$marcacion.'</span>';
	 			  
	 			  	}
	 			  	else
	 			  	{
	 			  		echo  '<span class="sp12"> ------ </span>';
	 			  	}
	 			  ?>
	 		</td>

	 		<td><span class="sp11b"> Salida</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 	
	 			<?PHP
	 				if(trim($diario_info['hoae_hora1_s']) != '')
	 				{  
	 					list($horas, $minutos, $segundos) = explode(':', $diario_info['hoae_hora1_s'] );
	 					$marcacion = $horas.':'.$minutos.' horas';    	

	 					echo  '<span class="sp12">'.$marcacion.'</span>';
	 				}
	 				else
	 				{
	 					echo  '<span class="sp12"> ------ </span>';
	 				}
	 			?>
	 		 
	 		</td>
	 	</tr>


	 	<?PHP 

	 		if($diario_info['hor_numero_horarios'] == '2')
	 		{

	 	?>
	 	
			  	<tr id="trregistrodiario_horario2" class="row_form">
			 		<td><span class="sp11b">Tarde Ingreso</span></td>
			 		<td><span class="sp11b">:</span></td>
			 		<td> 
			 			 <?PHP 

			 			 	if(trim($diario_info['hoae_hora2_e']) != '')
			 			 	{  

			 			 		list($horas, $minutos, $segundos) = explode(':', $diario_info['hoae_hora2_e'] );
			 			 		$marcacion = $horas.':'.$minutos.' horas';    	

			 			 		echo  '<span class="sp12">'.$marcacion.'</span>';

			 			 	}
			 			 	else
			 			 	{
			 			 		echo  '<span class="sp12"> ------ </span>';
			 			 	}

			 			 ?>
			 		</td>

			 		<td><span class="sp11b"> Salida</span></td>
			 		<td><span class="sp11b">:</span></td>
			 		<td>  
			 			<?PHP

			 				if(trim($diario_info['hoae_hora2_s']) != '')
			 				{

			 					list($horas, $minutos, $segundos) = explode(':', $diario_info['hoae_hora2_s'] );
			 					$marcacion = $horas.':'.$minutos.' horas';    	

			 					echo  '<span class="sp12">'.$marcacion.'</span>';

			 				}
			 				else
			 				{
			 					echo  '<span class="sp12"> ------ </span>';
			 				}
			 			?>
			 		</td>
			 	</tr>

	 	<?PHP 
	 	    }
	 	?>

	 	<?PHP 

	 		if($config['diario_tipo_horatrabajadas'] == '1')
	 		{
	   ?>
	   		
	   			<tr id="trregistrodiario_htra" class="row_form">
	   				<td><span class="sp11b"> Horas Trabajadas </span></td>
	   				<td><span class="sp11b">:</span></td>
	   				<td colspan="4"> 
	   					   <span class="sp12"> 
	   						<?PHP 

	   						 	list($horas, $minutos) = explode( '_', $diario_info['asistencia']); 
	   							
	   							if($horas != '' || $minutos != '')
	   							{
	   								$horas = (trim($horas) == '') ? '0' : $horas;
	   								$minutos = (trim($minutos) == '') ? '0' : $minutos;
	   								
	   								echo  $horas.' horas '.$minutos.' minutos ';
	   							}
	   							else
	   							{
	   								echo ' ------ ';
	   							} 
	   				 
	   						?> 	
	   						</span> 

	   				</td> 
	   			</tr>


	   <?PHP			 
	 		}
	 		else
	 		{
	    ?>
	    			<tr id="trregistrodiario_htra" class="row_form">
	    				<td><span class="sp11b"> Horas Contabilizadas </span></td>
	    				<td><span class="sp11b">:</span></td>
	    				<td colspan="4"> 
	    					   <span class="sp12"> 
	    						<?PHP 
 
	    							echo " ".$diario_info['horas_contabilizadas']." horas ";
	    						?> 	
	    						</span> 

	    				</td> 
	    			</tr>
	    <?PHP 
	 		}
	 	?>	
	 	 
	 	<tr id="trregistrodiario_tar" class="row_form">
	 		<td><span class="sp11b">M.Tardanza</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 	 
	 		    <span class="sp12"> 
 				 <?PHP 

 				 	list($horas, $minutos) = explode( '_', $diario_info['tardanzas']); 
 
 				 	if($minutos === '')
 				 	{
 				 		$minutos = 0;
 				 	}

 				 	if( ($horas*1) > 0 || ($minutos * 1) > 0 )
 				 	{
	 				 	$emin = ($horas > 0 ) ? ($horas*60) : 0;
	 				 	$minutos+=$emin;
	 			 	} 
	 			 	else
	 			 	{
	 			 		$minutos = '0';
	 			 	}
 				 	echo  $minutos.' minutos ';
 				 ?> 	
 				</span>
	 		</td>
 			

 			<?PHP 

 				if(ASISTENCIAS_MODULO_PERMISOS === TRUE)
 				{ 
 			
 			?>

	 		<td><span class="sp11b"></span></td>
	 		<td><span class="sp11b"> </span></td>
	 		<td>  

	 			<!-- 
	 				<span class="sp11"> 
	 			 	 	 <?PHP 
	 			 	 	 
	 			 	 	  	$permisos = $diario_info['permisos']; 
	 			 	 	 	
	 			 	 	 	if($permisos > 0)
	 			 	 	 	{

	 			 	 	 	}

	 			 	 	 	echo  $permisos.' min. ';

	 			 	 	 ?>


	 			 	</span>
	 			
	 			 	<span class="sp11b" id="spverpermisosdia" style="cursor:pointer;"> [V] </span>
 -->

	 			 

	 		</td>
	 		<?PHP 
	 			}
	 			else
	 			{
	 		?>
	 		
	 			<td colspan="3"> </td>
	 		<?PHP			
	 			}
	 		?>

	 	</tr>

<?PHP 
	}
?>

	 	<tr class="row_form">
	 		<td><span class="sp11b">Observacion</span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
 				<span class="sp12"> 
 					 <?PHP 
 					 	echo (trim($diario_info['hoaed_obs']) != '' ? trim($diario_info['hoaed_obs']) : '------------'); 
 					 ?>
 				</span>
	 		</td>
	 	</tr>
  
	 </table>
 
 </div>

</div>