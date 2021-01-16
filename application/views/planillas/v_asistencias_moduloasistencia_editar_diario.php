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


	 	// if($diario_info['hoaed_id'] == '')
	 	// {
	 	//     echo '		 <span class="sp11b">  No existe registro de asistencia para este día  </span> 

	 	//     		</div>
	 	//     		</div>
	 	//     	';

	 	//     die();
	 	// }

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
 
	 		     	 if( trim($diario_info['biom_id']) != '0' &&  trim($diario_info['biom_id']) != '')
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
	 			
	 			<!-- <span class="sp12"> <?PHP echo $diario_info['hatd_nombre']; ?> </span> -->
				
	 		    <input type="hidden" id="hdregistrodiario_estado" value="<?PHP echo $estado_actual_del_dia; ?>" />

	 		    <select id="selregistrodiario_estado" 
	 		    		data-dojo-type="dijit.form.FilteringSelect"  
	 		    		data-dojo-props=' name:"estado",
	 		    		                  autoComplete:false, 
	 		    		                  highlightMatch: "all",  
	 		    		                  queryExpr:"*${0}*", 
	 		    		                  invalidMessage: "Estado no registrado"   '  
	 		    		class="formelement-150-11">
	 		    	  
	 		    	  <option value="<?PHP echo ASISDET_NOCONSIDERADO; ?>"> DIA NO CONSIDERADO </option>
	 		    	  <option value="<?PHP echo ASISDET_DESCANSOSEMANAL; ?>"> DESCANSO SEMANAL </option>
	 		    	  <option value="<?PHP echo ASISDET_ASISTENCIA; ?>"> ASISTENCIA </option>
	 		    	  <option value="<?PHP echo ASISDET_FALTA; ?>"> FALTA	    </option> 

	 		    </select>		
  
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
   							 	<td><span class="sp11b"> Ingreso </span></td>
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

 
	 	<tr id="trregistrodiario_horario1" class="row_form">
	 		<td><span class="sp11b">Mañana Ingreso</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 	
	 			   
	 			  <input id="fec_editardiario_hora1"  data-dojo-type="dijit.form.TimeTextBox"
	 			   							 		           data-dojo-props='type:"text", name:"hora1", value:"T07:45:00",
	 			   							 		           title:"",
	 			   							 		           constraints:{formatLength:"short"},
	 			   							 		           required:true,
	 			   							 		           invalidMessage:"" ' 
	 			   							 		           style="width:60px; font-size:11px;" 

	 			   							 		           onChange="dijit.byId('fec_editardiario_hora2').constraints.min = this.get('value'); "

	 			   							 		           />
	 		</td>

	 		<td><span class="sp11b"> Salida</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 	
	 			 

		 		<input id="fec_editardiario_hora2"  data-dojo-type="dijit.form.TimeTextBox"
		 		           data-dojo-props='type:"text", name:"hora2", value:"T07:45:00",
		 		           title:"",
		 		           constraints:{formatLength:"short"},
		 		           required:true,
		 		           invalidMessage:"" ' style="width:60px; font-size:11px;"/>

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

	 		<td><span class="sp11b">M.Permisos</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td>  

	 			
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
   
	 	<tr class="row_form">
	 		<td><span class="sp11b">Observacion</span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
 				<!-- <span class="sp12"> 
 					 <?PHP 
 					 	echo (trim($diario_info['hoaed_obs']) != '' ? trim($diario_info['hoaed_obs']) : '------------'); 
 					 ?>
 				</span>
 -->
 				<input type="text" data-dojo-type="dijit.form.Textarea" data-dojo-props="name:'obs'" class="formelement-200-11"  value="<?PHP echo trim($diario_info['hoaed_obs']); ?>" />	
	 		
	 		</td>
	 	</tr>


	 	<tr>
	 	 	<td colspan="7" align="center"> 

 	 		    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	              
	              <?PHP 
	                 $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
	              ?>

	                <label class="lbl11">Actualizar</label>
                    <script type="dojo/method" event="onClick" args="evt">
                        	Asistencias.Ui.btn_registrar_detalle_dia_modulo_asistencia(this,evt);                             
                    </script>
               
               </button>

	 	 	</td>
	 	</tr>
  
	 </table>
 
 </div>

</div>