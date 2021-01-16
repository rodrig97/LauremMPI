

<div id="dvViewName" class="dv_view_name">
    Configuración del monto Mínimo y Máximo
</div>
 

<div style="margin: 12px 0px 0px 0px;">

	<form id="form_ajustar_minmax" data-dojo-type="dijit.form.Form">

			<input type="hidden" value="<?PHP echo $concepto_info['conc_key']; ?>" name="view" />

			<table class="_tablepadding4">

			    <tr>
					<td width="100">
						<span class="sp12b"> 
							Concepto 
						</span>
					</td>

					<td width="10">
						<span class="sp12b"> : </span> 
					</td>

					<td width="250">
			 			
			 		    <?PHP echo trim($concepto_info['conc_nombre']); ?>		

					</td>

				</tr>		
				<tr>
					<td width="100">
						<span class="sp12b"> 
							Ajustar 
						</span>
					</td>

					<td width="10">
						<span class="sp12b"> : </span> 
					</td>

					<td width="250">
			 			
			 			<select data-dojo-type="dijit.form.Select" data-dojo-props="name:'ajustar'" name="ajustar" style="width: 150px; ">
			 				<option value="3" <?PHP echo $concepto_info['cmm_ajuste'] == '3' ?  ' selected="selected" ' : '';  ?>> Mínimo y Máximo </option>

			 				<option value="1" <?PHP echo $concepto_info['cmm_ajuste'] == '1' ?  ' selected="selected" ' : '';  ?> disabled="true"> Monto Minimo </option>
			 				<option value="2" <?PHP echo $concepto_info['cmm_ajuste'] == '2' ?  ' selected="selected" ' : '';  ?> disabled="true"> Monto Máximo </option>
			 				
			 			</select>

					</td>

				</tr>


				<tr>
					<td> 

						<span class="sp12b"> 	 
						   Monto Mínimo		
					   </span> 
					</td>

					<td>
						 <span class="sp12b"> : </span>
					</td>

					<td>
						<input name="static_minimo" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:6" class="formelement-80-12" value="<?PHP echo $concepto_info['cmm_min'] != '' ? $concepto_info['cmm_min'] : '0'; ?>" />  

						<!-- 
						<select data-dojo-type="dijit.form.Select" style="width: 150px; ">
			 				 
			 			</select>

			 			 <input name="restringirmontos" type="checkbox" data-dojo-props="name: 'restringirmontos'"  dojoType="dijit.form.CheckBox" <?PHP if($concepto_info['conc_afecto'] == '1') echo 'checked="true"' ?> value="1"/> 
		                  
		                 <label> Concepto </label>  -->                        
					</td>

				</tr>


				<tr>
					<td> 
					   <span class="sp12b"> 	
					  	 Monto Máximo		 
						</span>
					</td>

					<td>
						<span class="sp12b"> : </span>
					</td>

					<td>
						<input name="static_maximo" type="text" data-dojo-type="dijit.form.TextBox"  data-dojo-props="maxlength:6" class="formelement-80-12"  value="<?PHP echo $concepto_info['cmm_max'] != '' ? $concepto_info['cmm_max'] : '1000000'; ?>"/>  

						<!-- 
						<select data-dojo-type="dijit.form.Select" style="width: 150px; ">
			 				 
			 			</select> -->
					</td>

				</tr>



				<tr>
					<td>
						<span class="sp12b"> 
							Aplicar sobre
						</span>
					</td>

					<td>
						: 
					</td>

					<td>
			 			
			 			<select name="modo_calculo" data-dojo-type="dijit.form.Select" data-dojo-props="name:'modo_calculo'" 
			 					style="width: 250px; ">
			 				<option value="0"  <?PHP echo $concepto_info['cmm_modo'] == '0' ?  ' selected="selected" ' : '';  ?> > Boleta de pago </option>
			 				<option value="1"  <?PHP echo $concepto_info['cmm_modo'] == '1' ?  ' selected="selected" ' : '';  ?> > Al total acumulado del mes, con devolución  </option>
			 				<option value="2"  <?PHP echo $concepto_info['cmm_modo'] == '2' ?  ' selected="selected" ' : '';  ?> > Monto máximo asegurable de AFP </option>	
			 			</select>

			 				
					</td>

				</tr>

				 <tr>
					<td>
						<span class="sp12b"> 
							Observación
						</span>
					</td>

					<td>
						: 
					</td>

					<td>
			 			 <textarea data-dojo-type="dijit.form.TextArea" name="obs" data-dojo-props="name:'obs'" style="width:200px;"><?PHP echo trim($concepto_info['cmm_obs']); ?></textarea>
			 				
					</td>

				</tr>

				<tr>

					<td colspan="3" align="center">


		  						 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		                                                <?PHP     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));    ?>
		                                                  <script type="dojo/method" event="onClick" args="evt">
		                                                       	
		                                                       	var data = dojo.formToObject('form_ajustar_minmax');

		                                                       	if(confirm('Desea registrar la Restricción de montos para el concepto ?')){ 

			                                                  		if(Conceptos._M.ajustar_min_max.process(data)){

			                                                  				Conceptos._V.restringir_minimo_maximo.close();
			                                                  				Conceptos._V.modificar_concepto.refresh();
			                                                  		}

		                                                  		}

		                                                  </script>
		                                                  <label class="sp11">
		                                                          Actualizar
		                                                  </label>
		                        </button>



		                        <?PHP

		                        	if($concepto_info['cmm_id'] != ''){  
		                        ?>

		  						 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		                                                <?PHP     $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));    ?>
		                                                  <script type="dojo/method" event="onClick" args="evt">
		                                                       	
		                                                       	var data = dojo.formToObject('form_ajustar_minmax');

		                                                       	if(confirm('Desea QUITAR la Restricción de montos para el concepto ?')){ 
			                                                  		if(Conceptos._M.quitar_min_max.process(data)){

			                                                  				Conceptos._V.restringir_minimo_maximo.close();
			                                                  				Conceptos._V.modificar_concepto.refresh();
			                                                  		}

			                                                  	}

		                                                  </script>
		                                                  <label class="sp11">
		                                                          Quitar Restricción
		                                                  </label>
		                        </button>

		                        <?PHP 
		                    		}
		                        ?>

					</td>

				</tr>


			</table>
		 
	</form>
</div>