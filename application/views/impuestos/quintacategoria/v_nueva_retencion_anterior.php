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
	              Nueva constancia de retención 
	          </td>
	      	</tr>
	  	</table>

	</div>


	<div id="formRetencionConstanciaNueva" data-dojo-type="dijit.form.Form"> 

	<table class="_tablepadding4" width="100%">
		
		<tr>
			<td width="70">
				<span class="sp11"> Año </span>
			</td>
			<td width="5">
				<span class="sp11">: </span>
			</td>
			<td>
				 <select data-dojo-type="dijit.form.Select" 
				         data-dojo-props='name: "anio", disabled:false' 
				         style="font-size:11px; width: 80px;">
				 
				          <?PHP 

				             foreach ($anios as $anio)
				             { 
				               echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
				             }

				          ?>
				</select>
			</td>
		</tr>	
		<tr>
			<td>
				<span class="sp11"> Trabajador </span>
			</td>
			<td>
				<span class="sp11">: </span>
			</td>
			<td>
				<select id="selTrabajadorRetencionAnteriorNueva"
						data-dojo-type="dijit.form.FilteringSelect" 
						class="formelement-200-11" 
				        data-dojo-props='name:"trabajador", 
				                         disabled:false, 
				                         autoComplete:false, 
				                         highlightMatch: "all",  
				                         queryExpr:"${0}", 
				                         invalidMessage: "La Persona no esta registrada" ' 
				        style="width:250px;">
				</select>

			</td>
		</tr>	

		<tr>
			<td>
				<span class="sp11"> Descripción </span>
			</td>
			<td>
				<span class="sp11">: </span>
			</td>
			<td>
				<input type="text" data-dojo-type="dijit.form.TextBox" name="descripcion" style="width: 250px; font-size: 11px;" />
			</td>
		</tr>	

		<tr>
			<td>
				<span class="sp11"> Ingresos </span>
			</td>
			<td>
				<span class="sp11">: </span>
			</td>
			<td>
				<input type="text" data-dojo-type="dijit.form.NumberTextBox" name="ingreso" value="0" style="width: 60px; font-size: 11px;" />
			</td>
		</tr>	

		<tr>
			<td>
				<span class="sp11"> Descuento </span>
			</td>
			<td>
				<span class="sp11">: </span>
			</td>
			<td>
				<input type="text" data-dojo-type="dijit.form.NumberTextBox" name="descuento" value="0" style="width: 60px; font-size: 11px;" />
			</td>
		</tr>	

		<tr>
			<td align="center" colspan="3">


				 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				   <?PHP 
				      $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
				   ?>
				     <script type="dojo/method" event="onClick" args="evt">
				             
				     		var datos = dojo.formToObject('formRetencionConstanciaNueva');

				     		var trabajadorValidar = datos.trabajador == null || datos.trabajador == '' || datos.trabajador == undefined;

				     		var ingresoValidar = datos.ingreso == null || datos.ingreso == '' || datos.ingreso == undefined;

				     		var descuentoValidar = datos.descuento == null || datos.descuento == '' || datos.descuento == undefined;

				     		if( trabajadorValidar || ingresoValidar || descuentoValidar ){

				     			alert('Verifique los datos ingresados');

				     		}else{

				     			if(confirm('Realmente desea registrar la constancia de retención')){

						     		if(QuintaCategoria._M.registrar_constancia_retencion.process(datos)){

						     			QuintaCategoria._V.nueva_retencion_anterior.close();
		 								 
		 								QuintaCategoria.Ui.Grids.constancias_retencion.refresh();
						     		}
				     				
				     			}

				     		}
				     		

				     </script>
				     <label class="sp11">
				           Registrar constancia
				     </label>
				</button>

			 
			</td>
		</tr>	
	</table>


	</div>

</div>