
<div id="dvViewName" class="dv_view_name">
     Modificar tipo de estado del día.
</div>
 

<div class="dv_busqueda_personalizada" style="margin:3px;">
 	
 	<table class="_tablepadding4">
 		<tr>
 			<td> 
 				<span class="sp11b"> 
 					 Tipo 	
 				</span>	
 			</td>
 			<td>
 				<span class="sp11b"> : </span>
 			</td>
 			<td> 
 				<span class="sp11"> <?PHP echo trim($info['hatd_nombre']); ?> </span>
 			</td>	
 		</tr>
 	</table>
</div>


<form id="formestadodeldia_actualizar" dojoType="dijit.form.Form">    

<input type="hidden" name="view" value="<?PHP echo trim($info['hatd_key']); ?>" />

<table class="_tablepadding4" width="400">	

	<tr class="row_form">
		<td width="100"> 
			 <span class="sp11b"> 
			 	 Nombre 	
			 </span>
		</td>	
		<td width="10" align="center">  
			 <span class="sp11b"> 
			 	 :  	
			 </span>
		</td>	
		<td> 
			 <input type="text" data-dojo-type="dijit.form.TextBox" name="nombre" value="<?PHP echo trim($info['hatd_nombre']); ?>" class="formelement-150-11" />
		</td>	
	</tr>

	<tr class="row_form">
		<td> 
			 <span class="sp11b"> 
			 	 Nombre Corto	
			 </span>
		</td>	
		<td> 
			 <span class="sp11b"> 
			 	 :  	
			 </span>
		</td>	
		<td> 
			 <input type="text" data-dojo-type="dijit.form.TextBox" name="nombre_corto" value="<?PHP echo trim($info['hatd_nombrecorto']); ?>" class="formelement-150-11" />
		</td>	
	</tr>

	<tr class="row_form">
		<td> 
			 <span class="sp11b"> 
			 	 Alias en Hoja
			 </span>
		</td>	
		<td> 
			 <span class="sp11b"> 
			 	 :  	
			 </span>
		</td>	
		<td> 
			 <input type="text" data-dojo-type="dijit.form.TextBox" name="label" value="<?PHP echo trim($info['hatd_label']); ?>" class="formelement-150-11" />
		</td>	
	</tr>


	<tr class="row_form">
		<td> 
			 <span class="sp11b"> 
			 	 Color
			 </span>
		</td>	
		<td> 
			 <span class="sp11b"> 
			 	 :  	
			 </span>
		</td>	
		<td> 
 
		 
			 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'color'" style="font-size:11px; width:145px;">
			 	<option value="#FFFFFF" <?PHP if(trim($info['hatd_color']) == '#FFFFFF') echo " selected='selected' "; ?> > BLANCO </option>
			 	<option value="#80FF80" <?PHP if(trim($info['hatd_color']) == '#80FF80') echo " selected='selected' "; ?> > VERDE </option>
			 	<option value="#FC2525" <?PHP if(trim($info['hatd_color']) == '#FC2525') echo " selected='selected' "; ?>> ROJO </option>
			 	<option value="#DDDDDD" <?PHP if(trim($info['hatd_color']) == '#DDDDDD') echo " selected='selected' "; ?>> PLOMO </option>
			 	<option value="#92c7ff" <?PHP if(trim($info['hatd_color']) == '#92c7ff') echo " selected='selected' "; ?>> AZUL </option>
			 </select>
		</td>	
	</tr>


	<tr class="row_form">
		<td> 
			 <span class="sp11b"> 
			 	 Orden
			 </span>
		</td>	
		<td> 
			 <span class="sp11b"> 
			 	 :  	
			 </span>
		</td>	
		<td> 
			
			<select data-dojo-type="dijit.form.Select" data-dojo-props="name:'orden'" style="font-size:11px; width:145px;">
				 <?PHP 
 
				 	for($i = 1; $i <= $maxtipos ; $i++)
				 	{ 
				 	
				 		echo " <option value='".$i."' ";

				 		if( trim($i) == $info['hatd_orden'] ) echo " selected='selected' ";

				 		echo " >".$i."</option> ";	
				 	}

				 ?>
			</select>
		</td>	
	</tr>


	<tr class="row_form">
		<td> 
			 <span class="sp11b"> 
			 	Desde escalafon
			 </span>
		</td>	
		<td> 
			 <span class="sp11b"> 
			 	 :  	
			 </span>
		</td>	
		<td> 
			  <?PHP 

			   echo ($info['hatd_escalafon'] == '1') ? 'Si' : 'No';
			   
			  ?>
		</td>	
	</tr>

	<tr>
		<td colspan="3" align="center">

				<button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
				       <?PHP 
				          $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
				       ?>
				     <label class="lbl10"> Actualizar </label>
				     <script type="dojo/method" event="onClick" args="evt">
 							
				     	    var data = dojo.formToObject('formestadodeldia_actualizar');

				     	    if(Asistencias._M.actualizar_tipoestadodia.process(data))
				     	    {
				     	    	Asistencias.Ui.Grids.estados_del_dia.refresh();
				     	    	Asistencias._V.view_estado_dia.reload();
				     	    }

				     </script>
				</button>

				<button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
				       <?PHP 
				          $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
				       ?>
				     <label class="lbl10"> Deshabilitar </label>
				     <script type="dojo/method" event="onClick" args="evt">
				
				     </script>
				</button>
		</td>

	</tr>

</table>

</form>