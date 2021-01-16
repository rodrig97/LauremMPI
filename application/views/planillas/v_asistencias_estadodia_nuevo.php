
<div id="dvViewName" class="dv_view_name">
     Registrar un nuevo estado del día.
</div>
 
 
<form id="formestadodeldia_nuevo" dojoType="dijit.form.Form">    
 

<table class="_tablepadding4" width="370">	

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
			 <input type="text" data-dojo-type="dijit.form.TextBox" name="nombre" value="" class="formelement-150-11" />
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
			 <input type="text" data-dojo-type="dijit.form.TextBox" name="nombre_corto" value="" class="formelement-150-11" />
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
			 <input type="text" data-dojo-type="dijit.form.TextBox" name="label" value="" class="formelement-150-11" />
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
			 	<option value="#FFFFFF"   > BLANCO </option>
			 	<option value="#80FF80"   > VERDE </option>
			 	<option value="#FC2525"  > ROJO </option>
			 	<option value="#DDDDDD"  > PLOMO </option>
			 	<option value="#336699"  > AZUL </option>
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
				 	
				 		echo " <option value='".$i."'   >".$i."</option> ";	
				 	}

				 ?>
			</select>
		</td>	
	</tr>

 
	<tr>
		<td colspan="3" align="center">

				<button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
				       <?PHP 
				          $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
				       ?>
				     <label class="lbl10"> Registrar </label>
				     <script type="dojo/method" event="onClick" args="evt">
 							
				     	    var data = dojo.formToObject('formestadodeldia_nuevo');

				     	    if(Asistencias._M.registrar_tipoestadodia.process(data))
				     	    {
				     	    	Asistencias.Ui.Grids.estados_del_dia.refresh();
				     	    	Asistencias._V.nuevo_estadodia.close();
				     	    }

				     </script>
				</button>
 
		</td>

	</tr>

</table>

</form>