<div class="window_container">

  	<div id="dvViewName" class="dv_view_name">
	      
	    <table class="_tablepadding2" border="0">

	      <tr> 
	          <td> 
	               <?PHP 
	                    $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
	               ?>
	          </td>

	         <td>
	              Actualización de días sin hora de salida
	          </td>
	      </tr>
	    </table>
	</div> 

	
	<div class="dv_busqueda_personalizada">
 		<table>
 			<tr>
 				<td width="40">
 					<span class="sp12">Desde </span>
 				</td>
 				<td width="10" align="center">
 					<span class="sp12"> : </span>
 				</td>
 				<td width="70">
 					<span class="sp12b"> <?PHP echo _get_date_pg($datos['fechadesde']); ?> </span>
 				</td>
 		 		<td width="40">
 					<span class="sp12"> Hasta</span>
 				</td>
 				<td width="10" align="center">
 					<span class="sp12"> : </span>
 				</td>
 				<td width="100">
 					<span class="sp12b"> <?PHP echo _get_date_pg($datos['fechahasta']); ?> </span>
 				</td>

 				<?PHP 
 					if(trim($plati_nombre) != ''){ 
 				?>
 		 		<td width="100">
 					<span class="sp12"> Tipo de trabajador</span>
 				</td>
 				<td width="10" align="center">
 					<span class="sp12"> : </span>
 				</td>
 				<td>
 					<span class="sp12b"> <?PHP echo trim($plati_nombre); ?> </span>
 				</td>

 				<?PHP 
 					}
 				?>
 			</tr>
 		</table>
	</div>

	<div class="dvFormBorder" style="margin-top: 5px;">
	  	
	  	<table class="_tablepadding4" border="0">
	  		<tr class="tr_header_celeste">
	  			<td	width="30">
	  				#
	  			</td>
	  			<td width="60">
	  				<span class="sp11"> Día </span>
	  			</td>
	  			<td width="220">
	  				<span class="sp11"> Trabajador </span>
	  			</td>
	  			<td width="50">
	  				<span class="sp11"> H.Entrada </span>
	  			</td>
	  			<td width="60">
	  				<span class="sp11"> H.Salida </span>
	  			</td>

	  			<td width="40">
	  				<span class="sp11"> Papeleta </span>
	  			</td>

				<td width="150">
					<span class="sp11"> Motivo </span>
				</td>	
	  			
	  			<td width="50">
	  				<span class="sp11"> H.Salida </span>
	  			</td>

	  			<td width="50">
	  				<span class="sp11"> H.Regreso </span>
	  			</td>

	  			<td width="100">
	  				Actualizar Hora Salida
	  			</td>

	  			<td width="100">
	  				Marcar el día como falta	
	  			</td>
	  		</tr>	
	<?PHP 

	//	var_dump($rs);

		foreach ($rs as $reg) {
	?>
			<tr class="tr_row_celeste">
				<td>
					
				</td>
				<td align="center">
					<span class="sp11"> <?PHP echo _get_date_pg($reg['hoaed_fecha']); ?> </span>
				</td>
				<td>
					<span class="sp11"> <?PHP echo $reg['trabajador_nombre']; ?> </span>
				</td>
				<td align="center">
					<span class="sp11"> <?PHP echo (trim($reg['hoae_hora1_e']) == '' ? '-----' : $reg['hoae_hora1_e']); ?> </span>
				</td>
				<td align="center">
					<span class="sp11"> <?PHP echo (trim($reg['hoae_hora1_s']) == '' ? '-----' : $reg['hoae_hora1_s']); ?> </span>
 	
				</td>

				<td align="center">
					<span class="sp11"> <?PHP echo ( trim($reg['pepe_id']) != '' ? 'Si' : 'No'); ?> </span>
				</td>
				<td>
					<span class="sp11"> <?PHP echo (trim($reg['permot_nombre']) == '' ? '-----' : $reg['permot_nombre'] ); ?> </span>
				</td>

				<td align="center"> 
					<span class="sp11"> <?PHP echo (trim($reg['pepe_horaini']) == '' ? '-----' : $reg['pepe_horaini'] ); ?> </span>
				</td>

				<td align="center"> 
					<span class="sp11"> <?PHP echo (trim($reg['pepe_horafin']) == '' ? '-----' : $reg['pepe_horafin'] );  ?></span>
				</td>

				<td align="center"> 

					<div>
						<input type="hidden" value="<?PHP echo $reg['indiv_id']; ?>" class="hdTrabajador" />
						<input type="hidden" value="<?PHP echo $reg['hoaed_fecha']; ?>" class="hdFecha" />

						<input     data-dojo-type="dijit.form.TimeTextBox"
						           data-dojo-props='type:"text", name:"hora_actualizar", value:"T15:15",
						           title:"",
						           constraints:{formatLength:"short"},
						           required:true,
						           invalidMessage:"" ' 
						           style="width:60px; font-size:11px;" 
						           class="inputDiaHora"

						           onChange=""  /> 
					</div>
					
					<div style="margin-top: 3px;"> 

						
					 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
					       <?PHP 
					          $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
					       ?>
					          <label class="lbl10"> Actualizar </label>
					           <script type="dojo/method" event="onClick" args="evt">
					                   
					                var nodo = this.domNode.parentNode.parentNode;

					                var valor_hora  = dijit.byNode( dojo.query('.inputDiaHora', nodo)[0] ).get('value');
					                var trabajador  = dojo.query('.hdTrabajador', nodo)[0] .value;
					                var fecha  = dojo.query('.hdFecha', nodo)[0] .value;
 										
	 								if ( valor_hora != '' || valor_hora != null ) {
						                
						                  var datos = {'dia' : fecha, 'trabajador' : trabajador, 'hora' : valor_hora}
	  					
						                  if (Asistencias._M.actualizar_hora_salida_dia.process(datos) ) {

						                  		Asistencias._V.actualizacion_de_fechas_sincierre.refresh();
						                  }


	 							    }else{

	 									alert('Debe ingresar una hora');
	 								}

					                  
					         </script>
					</button> 

					</div>

				</td>

				<td align="center">
					
					<input type="hidden" value="<?PHP echo $reg['indiv_id']; ?>" class="hdTrabajador" />
					<input type="hidden" value="<?PHP echo $reg['hoaed_fecha']; ?>" class="hdFecha" />
 
					 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
					       <?PHP 
					          $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
					       ?>
					          <label class="lbl10"> Día Falta </label>
					           <script type="dojo/method" event="onClick" args="evt">
					                  
					                var nodo = this.domNode.parentNode.parentNode;

					                var trabajador  = dojo.query('.hdTrabajador', nodo)[0] .value;
					                var fecha  = dojo.query('.hdFecha', nodo)[0] .value;
											
	 								if ( confirm('Realmente desea realizar esta operacion?') ) {
						                
						                  var datos = {'dia' : fecha, 'trabajador' : trabajador }
	  					
						                  if (Asistencias._M.actualizar_falta_dia.process(datos) ) {

						                  		Asistencias._V.actualizacion_de_fechas_sincierre.refresh();
						                  }
						                  

	 							    }else{

	 									alert('Debe ingresar una hora');
	 								}

					         </script>
					</button> 


				</td>
			</tr>
	<?PHP 
		}

	?>
		
	  	</table>
	
	<?PHP 


		if(sizeof($rs) == 0){
			echo '<span class="sp11"> No se encontraron días sin hora de salida </span> ';
		}
	?>
	</div>


</div>