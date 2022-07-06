<div class="window_container">
	
<input type="hidden" id="hddiariofechamin" value="<?PHP  echo $fecha;   ?>"/>
<input type="hidden" id="hddiariofechamax" value="<?PHP  echo $hoja_info['hoa_fechafin'];   ?>"/>
 
<textarea  id="hd_estados_dia_con_marcaciones" style="display:none;" ><?PHP echo json_encode($estados_dia_con_marcaciones); ?></textarea>

<?PHP 
 
	if($config['registro_asistencia_diario'] == '1' )
	{
	 
		list($anio, $mes, $dia) = explode( '-', $fecha);
		
		$edicion_permitida = false;

		$dia_tolerancia_edicion = $config['dia_tolerancia_edicion']*1;
		$hora_tolerancia_edicion_anterior = $config['hora_tolerancia_dia_edicion']*1;

		if(  mktime(0, 0, 0, $mes, $dia, $anio) ==  mktime(0, 0, 0, date("m")  , date("d"), date("Y") ) ||   
			 (  mktime(0, 0, 0, $mes, $dia, $anio) ==  mktime(0, 0, 0, date("m")  , date("d") - $dia_tolerancia_edicion, date("Y") )  &&
			 	mktime( date('G'),0,0, 0,0,0 ) <= mktime($hora_tolerancia_edicion_anterior,0,0,0,0,0)   
			  )
		){
			$edicion_permitida = true;			
		} 

	}
	else
	{
		$edicion_permitida = true;

	}

	if($edicion_permitida == false)
	{
		// echo 'UHM NO PUedeS EDITAR ESTO LO SIENTO :( ';
		 	 
	}
	else
	{

	}

?>

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
	 	   					<?PHP 
	 	   						 if($sin_contrato)
	 	   						 {
	 	   						 	echo ' <span class="sp11b" style="color:#990000"> [ El contrato actual no es vigente en esta fecha ] </span> ';
	 	   						 }
	 	   					?>
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
   			 			 
   			 		   <select  data-dojo-type="dijit.form.Select"  data-dojo-props=' name:"categoria" '  class="formelement-150-11" style="width:150px; font-size:11px;">
       	 		                  <?PHP
	 		                        
	 		                        if( trim($config['categoria_noespecificar']) == '1' )
	 		                        {

   			 		                  ?>
   			 		                            <option value="0"> No especificada </option>
   			 		                         
   			 		                 <?PHP 
   			 		                   } 

   			 		                   foreach($categorias as $reg)
   			 		                   {
   			 		                      
   			 		                       echo  " <option value='".$reg['platica_key']."'"; 

   			 		                       if($reg['platica_id'] == $platica_id ) echo " selected='selected'";

   			 		                       echo "  >".$reg['platica_nombre']."</option> ";   
   			 		                   }
   			 		                 ?>
   			 		   </select> 

   			 		</td>
   			 	</tr>
   			 
   			 	<?PHP 
   		 		}
   			 	?>	
	 	   </table>
	 </div>

	 <table class="_tablepadding4" style="width:100%" border="0">


	 	<tr class="row_form">
	 		<td width="90"><span class="sp11b"> Info del día </span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
	 			   
	 		     <?PHP 
	 		     	 $td = ($diario_info['hoaed_laborable'] == '1') ? ' Día Laborable ' :  ' No Laborable';
	 		    	
	 		     	 echo '<span class="sp11">'.$td.'</span>';


	 		     	 if($diario_info['biom_id'] != '0' && $diario_info['biom_id'] != '')
	 		     	 {

	 		     	 	 echo ' <div>  
	 		     	 	 			  <span class="sp11b"> Registro importado desde dispositivo biometrico. </span> 
	 		     	 	 			  <span class="sp11b" id="spMarcaciones" style="cursor:pointer;"> [M] </span>

	 		     	 	 		</div> ';


	 		     	 }
	 		      
	 		     ?>
 
	 		      <span data-dojo-type="dijit.Tooltip" data-dojo-props='connectId:["spMarcaciones"], position:["above"] '> 

	 		      			<span class="sp11b">  Marcaciones registradas: </span>

						
							<?PHP
								   
								  $marcaciones_txt = trim($diario_info['haoed_reloj_import_marcaciones']);
								  if($marcaciones_txt != '' )
								  {
								  	  $mar = explode('|', $marcaciones_txt);
								  	  array_shift($mar);

								  	  echo '<ul class="ul_tooltip_1"> ';

								  	  foreach ($mar as $m)
								  	  {
								  	  
								  	  	   echo ' <li>'.$m.' horas </li> ';	
								  	  }

								  	  echo '</ul>';  
								  }
								  else
								  {
								  	   echo ' <span class="sp11"> No se encontraron marcaciones. </span> ';
								  }
							?>

							<?PHP

							?>

	 		      </span>

	 		</td> 
		 
	 	</tr>


	 

	 	<tr class="row_form">
	 		<td width="90"><span class="sp11b"> Estado del dia</span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
	 			 
	 		    <select id="selregistrodiario_estado" data-dojo-type="dijit.form.FilteringSelect"  data-dojo-props=' name:"estado",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Estado no registrado"   '  class="formelement-150-11">
	 		    	  
	 		    	  <?php
	 		    	  	foreach($estados_dia as $est){

	 		    	  		echo "<option value='".$est['hatd_id']."' ";

	 		    	  		/*if($est['hatd_id'] == $diario_info['hatd_id']){
 									
 								echo " selected='true'";
	 		    	  		}*/

	 		    	  		echo ">  ".$est['hatd_nombre']." </option>";

	 		    	  	}
	 		    	  ?>
	 		    </select>		

	 		    <input type="hidden" id="hdregistrodiario_estado" value="<?PHP echo $estado_actual_del_dia; ?>" />

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
 	

	 	<tr id="trregistrodiario_horario1" class="row_form">
	 		<td><span class="sp11b">Mañana Ingreso</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 
	 			 <input id="fec_hora1"  data-dojo-type="dijit.form.TimeTextBox"
	 			            data-dojo-props='type:"text", name:"hora1", value:"T07:45:00",
	 			            title:"",
	 			            constraints:{formatLength:"short"},
	 			            required:true,
	 			            invalidMessage:"" ' 
	 			            style="width:60px; font-size:11px;" 

	 			            onChange="dijit.byId('fec_hora2').constraints.min = this.get('value'); "

	 			            />
	 		</td>

	 		<td><span class="sp11b"> Salida</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 

	 			 <input id="fec_hora2"  data-dojo-type="dijit.form.TimeTextBox"
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
	 			 <input id="fec_hora3"  data-dojo-type="dijit.form.TimeTextBox"
	 			            data-dojo-props='type:"text", name:"hora3", value:"T07:45:00",
	 			            title:"",
	 			            constraints:{formatLength:"short"},
	 			            required:true,
	 			            invalidMessage:"" ' style="width:60px; font-size:11px;"
	 			             onChange="dijit.byId('fec_hora4').constraints.min = this.get('value'); "
	 			            />
	 		</td>

	 		<td><span class="sp11b"> Salida</span></td>
	 		<td><span class="sp11b">:</span></td>
	 		<td> 
	 			
	 			 <input id="fec_hora4" data-dojo-type="dijit.form.TimeTextBox"
	 			            data-dojo-props='type:"text", name:"hora4", value:"T07:45:00",
	 			            title:"",
	 			            constraints:{formatLength:"short"},
	 			            required:true,
	 			            invalidMessage:"" ' style="width:60px; font-size:11px;" 

	 			            />

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
	 		    <span class="sp11"> 
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
 				 	echo  $minutos.' min. ';
 				 ?> 	
 				</span>
	 		</td>
 			
 			<?PHP 

 				if(ASISTENCIAS_MODULO_PERMISOS === TRUE)
 				{ 
 			
 			?>
	 		<td><span class="sp11b">Permisos</span></td>
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
	 			<input type="text" data-dojo-type="dijit.form.Textarea" data-dojo-props="name:'obs'" class="formelement-200-11"  value="<?PHP echo trim($diario_info['hoaed_obs']); ?>" />	
	 		</td>
	 	</tr>

	 	<?PHP 

	 	if(ASISTENCIAS_DIARIO_APLICARHASTA === TRUE){ 

	 		if( trim($config['registro_asistencia_diario']) != '1'){ 

	 	?>

		 	<tr class="row_form" id="trregistrodiario_aplicarhasta">
		 	    <td><span class="sp11b">Aplicar hasta</span></td>
		 		<td><span class="sp11b"> :  </span></td>
		 		<td colspan="4"> 
	 				  
	 				 <input data-dojo-type="dijit.form.CheckBox" name="chhasta" data-dojo-props="name: 'chhasta'" value="1" onchange=" if(this.get('value')){ dojo.setStyle( dojo.byId('cal_regdia_hasta_c'), 'display', 'inline');  }else{  dojo.setStyle( dojo.byId('cal_regdia_hasta_c'), 'display', 'none');   }   " />	 
	                 
	                 <div id="cal_regdia_hasta_c" style="display: none;">  
	                 <div id="cal_regdia_hasta"  data-dojo-type="dijit.form.DateTextBox"
	                                            data-dojo-props='type:"text", name:"fechahasta", value:"",
	                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
	                                            lang:"es",
	                                            required:true,
	                                            promptMessage:"mm/dd/yyyy",
	                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
	                                        </div> 
	                  </div>                      

		 		</td>

		 	</tr> 

	 	<?PHP 
	 	
	 		}
	 	
	 	}
	 	
	 	?>


        <tr class="row_form">
	 		<td><span class="sp11b">Último cambio</span></td>
	 		<td><span class="sp11b"> :  </span></td>
	 		<td colspan="4"> 
 				<span class="sp12"> 
 					 <?PHP 
 					 	if( $diario_info['hoaed_fechaupdate'] ) {
                            echo _get_date_pg(trim($diario_info['hoaed_fechaupdate'])) . ' ' . substr($diario_info['hoaed_fechaupdate'], 11, 8);
                        } else {
                            echo _get_date_pg(trim($diario_info['hoaed_fecreg'])) . ' ' . substr($diario_info['hoaed_fecreg'], 11, 8);
                        }
 					 ?>
 				</span>
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
                        	Asistencias.Ui.btn_registrar_detalle_dia(this,evt);                             
                    </script>
               
               </button>

	 	 	</td>
	 	</tr>
	 </table>
 
 </div>

</div>