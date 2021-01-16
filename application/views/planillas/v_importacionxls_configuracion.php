
<form id="frm_importxls_configuracion" data-dojo-type="dijit.form.Form"> 

	
 	
	
	<div class="dv_celeste_padding4">

			<table class="_tablepadding4">
				 
				<tr>
				 	 <td width="40"> 
						 <span class="sp12b"> 
			 					
								Importar
			 
						 </span>
				 	 </td>	
				 	 <td>
				 	 	<span class="sp12b"> 
				 	 		:
				 	 	</span>
				 	 </td>	
				 	 <td>
				 	 	 
						<select id="selimpxls_modo" data-dojo-type="dijit.form.Select" data-dojo-props="name: 'modo_importacion'" style="width: 350px; font-size:12px;"  >
							 <?php 
							 	foreach ($modos as $modo)
							 	{
							 ?> 	  	
								 	<option value="<?PHP echo $modo['xim_id']; ?>" > <?PHP echo $modo['xim_nombre']; ?> </option>
							 <?PHP  
							 	}
							 ?>
							 
						</select>
		 
				 	 </td>
				</tr>

			</table>
	</div>

	
	<div id="dvimpxls_configuracion" style="margin:10px 0px 0px 0px;">
	

		<table class="_tablepadding4"> 

			<tr>
				
				<td width="150">
					 <span class="sp12b">
						Considerar 
					 </span> 
				</td>

				<td width="10">
					 <span class="sp12b">
					  	: 
					 </span> 
				</td>
			
				<td>
		 			
					 <select id="selimpxls_by" data-dojo-type="dijit.form.Select" data-dojo-props="name: 'by'" style="width: 200px; font-size:12px;" >
						 <option value="planilla"> Planilla </option>
						 <option value="mes" > Mes 	   </option>
					 </select>

				</td>
			
			</tr>


			<tr>
				
				<td>
					 <span class="sp12b">
						 Restringir a solo
					 </span> 
				</td>

				<td>
					 <span class="sp12b">
					  	: 
					 </span> 
				</td>
			
				<td>
		 			
					 <select name="tiposplanilla[]" multiple="multiple" style="width: 200px; font-size:12px; height:100px;" >
						 
						  <?php 
						  	foreach ($platis as $plati)
						  	{
						  ?> 	  	
						 	 	<option value="<?PHP echo $plati['plati_id']; ?>" > <?PHP echo $plati['plati_nombre']; ?> </option>
						  <?PHP  
						  	}
						  ?>

					 </select>

				</td>
			
			</tr>


			<tr>
				
				<td>
					 <span class="sp12b">
						 Respecto al trabajador y la planilla
					 </span> 
				</td>

				<td>
					 <span class="sp12b">
					  	: 
					 </span> 
				</td>
			
				<td>
		 			
					 <select id="selimpxls_vinculacion"  data-dojo-type="dijit.form.Select" data-dojo-props="name: 'vincular'" style="width: 350px; font-size:12px;"  >
						 <option value="no_vincular"> Que anticipadamente ya este vinculado a la planilla </option>
						 <option value="vincular"> En caso no este vinculado, vincular el trabajador a la planilla  </option>
						 <!-- <option value="no_vincular_solounaplanilla"> Debe figurar en una sola planilla elaborada </option> -->
					 </select>

				</td>
			</tr>
				 

		</table>

	</div>




</form>


<div align="center" style="margin: 10px 0px 0px 0px;">

				 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				   <?PHP 
				      $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
				   ?>
				     <script type="dojo/method" event="onClick" args="evt">
	 						
	 						importacionxls.Ui.btn_importacion_click(this,evt);

				     </script>
				     <label class="sp12">
				           Realizar importación
				     </label>
				</button>

</div>