
<form id="frm_exportxls_configuracion" data-dojo-type="dijit.form.Form"> 
 

		<div class="dv_celeste_padding4">

				<table class="_tablepadding4">
					 
					<tr>
					 	 <td width="150"> 
							 <span class="sp12b"> 
				 					
									Datos y Estructura 
				 
							 </span>
					 	 </td>	
					 	 <td>
					 	 	<span class="sp12b"> 
					 	 		:
					 	 	</span>
					 	 </td>	
					 	 <td>
					 	 	 
							<select id="selexportacion_excel" data-dojo-type="dijit.form.Select" 
									data-dojo-props="name: 'exportacion_data'" style="width: 380px; font-size:12px;"  >
								 	 
								 	<option value="1"> Información de trabajadores  </option>
								 	<option value="2"> Registro de remuneraciones  </option>
								 	<option value="6"> Registro de remuneraciones agrupado por mes  </option>
								 	<option value="3"> Registro de remuneraciones (Trabajador concepto en vertical) </option>
								 	<option value="4"> Registro de remuneraciones (Por planilla agrupado en vertical) </option>
								 	<option value="5"> Registro de descuentos por planilla </option>

							</select>
			 
					 	 </td>
					</tr> 
				</table>
		  </div>


	
			<div id="dvimpxls_configuracion" style="margin: 10px 0px 0px 5px; border:1px solid #99ccff; padding:5px 5px 5px 5px; ">
					

						<table class="_tablepadding4"> 

							<tr> 
								<td width="30">
		                           <span class="sp12b">
		                           Año
		                           </span>
		                       </td>
		                       <td width="5" width="10">
		                           :
		                       </td>
		                            
		                       <td width="80">
		                          
		                            <select id="selplani_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:6px; font-size:11px; width: 50px;">
		                            <!--    <option value="2012">2012</option>  -->

		                                    <?PHP 
		                                       foreach ($anios as $anio)
		                                       {
		                                         # code...
		                                          echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
		                                       }
		                                    ?>
		                           </select>
		                       </td>


								<td width="30">
									 <span class="sp12b">
										 Meses
									 </span> 
								</td>

								<td width="10">
									 <span class="sp12b">
									  	: 
									 </span> 
								</td>
							
								<td width="110">
						 			
									 <select name="mes[]" multiple="multiple" style="width: 100px; font-size:12px; height:170px;" >
										 
									   	    <option value="01">Enero</option> 
			                                <option value="02">Febrero</option> 
			                                <option value="03">Marzo</option> 
			                                <option value="04">Abril</option> 
			                                <option value="05">Mayo</option> 
			                                <option value="06">Junio</option> 
			                                <option value="07">Julio</option> 
			                                <option value="08">Agosto</option> 
			                                <option value="09">Septiembre</option> 
			                                <option value="10">Octubre</option> 
			                                <option value="11">Noviembre</option> 
			                                <option value="12">Diciembre</option> 

									 </select>

								</td>
								

								<td width="40">
									 <span class="sp12b">
										 Regimenes
									 </span> 
								</td>

								<td width="10">
									 <span class="sp12b">
									  	: 
									 </span> 
								</td>
							
								<td>
						 			
								    <select name="plati[]" multiple="multiple" style="width: 200px; font-size:12px; height:170px;" >
				 							 
		 						   	   <?PHP 
  										 	foreach ($tipo_planillas as $plati)
  										 	{
  										 ?> 	

  										 	 <option value="<?PHP echo $plati['plati_id']; ?>"> <?PHP echo $plati['plati_nombre']; ?> </option>

  										 <?PHP 
  										 	}
  										 ?>

			 						 </select>

								</td>

							</tr>

						 
 

						</table>

			</div>
 			

 			 <div id="dvimpxls_individuo" style="margin: 10px 0px 0px 5px; border:1px solid #99ccff; padding:5px 5px 5px 5px; ">
 					
 					<table class="_tablepadding2">
 						<tr>
 							<td width="120">
 								<span class="sp11b">
 									DNI	del trabajador
 								</span>
 							</td>
 							<td width="10">
 								<span class="sp11b">
 									:
 								</span>
 							</td>
 							<td>
 								 <input type="text" name="dni" data-dojo-type="dijit.form.TextBox" value="" class="formelement-100-11"  />
 							</td>
 						</tr>


 					</table>			 
 		
 			</div>	
			
			<div id="dvimpxls_individuo_extra" style="margin: 10px 0px 0px 5px; border:1px solid #99ccff; padding:5px 5px 5px 5px; ">
				
					<table class="_tablepadding2">
 						<tr>
 							<td width="200">
 								<span class="sp11b">
 									Incluir parametros remunerativos 
 								</span>
 							</td>
 							<td width="10">
 								<span class="sp11b">
 									:
 								</span>
 							</td>
 							<td>
 								 <select data-dojo-type="dijit.form.Select" 
 								 		 data-dojo-props="name:'incluir_params'"
 								 		 style="width:60px; font-size: 11px;" 
 								 		 name="incluir_params">
									<<option value="1"> Si </option>
									<<option value="0"> No </option>
 								 </select>
 							</td>
 						</tr>
 						<tr>
 							<td>
 								<span class="sp11b">
 									Estado 
 								</span>
 							</td>
 							<td width="10">
 								<span class="sp11b">
 									:
 								</span>
 							</td>
 							<td>
 								 <select data-dojo-type="dijit.form.Select" 
 								 		 data-dojo-props="name:'estado_trabajador'"
 								 		 style="width:190px; font-size: 11px;" 
 								 		 name="estado_trabajador">
									<<option value="2"> Todos </option>
									<<option value="1"> Solo activos </option>
 								 </select>
 							</td>
 						</tr>
 						<tr> 
							<td>
								 <span class="sp12b">
									 Regimenes
								 </span> 
							</td>

							<td width="10">
								 <span class="sp12b">
								  	: 
								 </span> 
							</td>
						
							<td>
					 			
							    <select name="plati_info[]" data-dojo-props="name:'plati_info[]'"   multiple="multiple" style="width: 200px; font-size:12px; height:170px;" >
			 							 
	 						   	   <?PHP 
										 	foreach ($tipo_planillas as $plati)
										 	{
										 ?> 	

										 	 <option value="<?PHP echo $plati['plati_id']; ?>"> <?PHP echo $plati['plati_nombre']; ?> </option>

										 <?PHP 
										 	}
										 ?>

		 						 </select>

							</td>

						</tr>
					</table>
			</div>
</form>


<div align="center" style="margin: 15px 0px 0px 0px;">

		 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		   <?PHP 
		      $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
		   ?>
		     <script type="dojo/method" event="onClick" args="evt">
						
						Exporter.Ui.btn_exportarexcel(this,evt);

		     </script>
		     <label class="sp12">
		            Exportar datos
		     </label>
		</button>

</div>