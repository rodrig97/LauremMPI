<div class="window_container">
	
	<div class="dv_busqueda_personalizada">
		
		<table class="_tablepadding4">
			<tr>
				<td>
					<span class="sp11"> Trabajador </span>
				</td>
				<td>
					<span class="sp11">:</span>
				</td>
				<td>
					<span class="sp11b"> <?PHP echo $indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'].' '.$indiv_info['indiv_nombres']; ?></span>
				</td>
			</tr>
		</table>
	</div>
 
	<form data-dojo-type="dijit.form.Form" id="formDescansosMedicosTrabajador">
		<input type="hidden" name="empleado" value="<?PHP echo $indiv_info['indiv_key']; ?>" />
		<input type="hidden" name="anio" value="<?PHP echo $anio; ?>" />
	</form>
	
	<div style="margin-top: 5px;">
		
		<div id="dvdescansosmedicos_anio"> </div>
	</div>
	
	<div style="margin-top: 5px;">

		<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		       <?PHP 
		          $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
		       ?>
		          <label class="lbl10">Ver</label>
		           <script type="dojo/method" event="onClick" args="evt"> 

		           				 var codigo = '';      
		           	                    
		           	            for(var i in Planillas.Ui.Grids.resumen_trabajador_descansos_medicos.selection){
		           	                  codigo = i;
		           	            }

		           	            console.log(codigo);
		           	         
		           	            if(codigo != '')      
		           	            {
		           	                Persona._V.view_descanso.load({'codigo' : codigo});    
		           	            }
		           	            else{
		           	                alert('Debe seleccionar un registro');
		           	            }
		         </script>
		</button>
		
	</div>
</div>