<div class="window_container"> 
	
	
	<div class="dv_busqueda_personalizada">
		
		<div data-dojo-type="dijit.form.Form" id="formRetencionesAnteriores">
			 
			<table class="tablepadding4">
				<tr>
					<td width="40"> 
						<span class="sp11"> Año</span>
					</td>
					<td width="10">
						<span class="sp11"> : </span>
					</td>
					<td width="100">
						 <select data-dojo-type="dijit.form.Select" 
						         data-dojo-props='name: "anio", disabled:false' 
						         style="margin-left:6px; font-size:11px; width: 80px;">
						 
						          <?PHP 

						             foreach ($anios as $anio)
						             { 
						               echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
						             }

						          ?>
						</select>
					</td>
					<td width="80"> 
						<span class="sp11"> Trabajador </span>
					</td>
					<td width="10">
						<span class="sp11"> : </span>
					</td> 
					<td width="320">
						
						<select id="selTrabajadorRetencionesAnteriores"
								data-dojo-type="dijit.form.FilteringSelect" 
								class="formelement-200-11" 
						        data-dojo-props='name:"trabajador", 
						                         disabled:false, 
						                         autoComplete:false, 
						                         highlightMatch: "all",  
						                         queryExpr:"${0}", 
						                         invalidMessage: "La Persona no esta registrada" ' 
						        style="width:300px;">
						</select>

					</td>
					<td>
						 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
						   <?PHP 
						      $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
						   ?>
						     <script type="dojo/method" event="onClick" args="evt">
						       	
						       
						       	 	QuintaCategoria.Ui.Grids.constancias_retencion.refresh();
						       	 	    
						     </script>
						     <label class="sp11">
						           Filtrar
						     </label>
						</button>
					</td>
				</tr>
			</table>

		</div>

	</div>
	
	<div id="dvRetencionesAnteriores">
		
	</div>
	
	<div style="margin:5px 0px 0px 5px;">

		  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		    <?PHP 
		       $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
		    ?>
		      <script type="dojo/method" event="onClick" args="evt">
		        	
		        	var codigo = '';      
		        	        
		        	for(var i in QuintaCategoria.Ui.Grids.constancias_retencion.selection){
		        	      codigo = i;
		        	}
 

		        	if(codigo != '')      
		        	{    
	        	 	   
		        		if(confirm('Realmente desea eliminar la constancia de retencion ?')){

		        	 	   if(QuintaCategoria._M.eliminar_constancia_retencion.process({'view' : codigo }) ){

		        	 	   		QuintaCategoria.Ui.Grids.constancias_retencion.refresh();
		        	 	   }

		        		}

		        	
		        	}
		        	else
		        	{
		        	    alert('Debe seleccionar un registro');
		        	}
		        	      

		      </script>
		      <label class="sp11">
		            Eliminar
		      </label>
		 </button>

		 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		   <?PHP 
		      $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
		   ?>
		     <script type="dojo/method" event="onClick" args="evt">
		            
		            QuintaCategoria._V.nueva_retencion_anterior.load({});

		     </script>
		     <label class="sp11">
		           Registrar nueva
		     </label>
		</button>

	</div>

</div>