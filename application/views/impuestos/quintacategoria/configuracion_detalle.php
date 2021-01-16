


<div class="dv_busqueda_personalizada">  
 
        <table class="_tablepadding2">
             <tr>
                <td width="110"> 
                    <span class="sp11b"> Tipo de trabajador: </span>
                </td>
                <td style="min-width:100px;">
                    <span class="sp12">  <?PHP echo $plati_info['plati_nombre']; ?> </span> 
                </td>
                
             </tr>
        </table>    

</div>

<div>

	<span class="sp12b" style="text-decoration: underline; color:#990000;"> Módulo de quinta categoria </span> <br/> <br/>
	 
	<?PHP 
		if(  is_array($configuracion_tipo_trabajador) && sizeof($configuracion_tipo_trabajador) > 0){
	?>

			<table class="_tablepadding4">
				<tr>
					<td>
						<span class="sp12b"> Considerar la remuneración </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
						<span class="sp12">
						 	<?PHP echo ($configuracion_tipo_trabajador['calculo_base_promedio_remuneraciones'] == '1') ? 'En base al promedio del año' : 'Remuneración fijada desde datos del trabajador'; ?>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<span class="sp12b"> Solo calcular a partir de alcanzar el tope de 7Uit </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
					 	<span class="sp12">
							<?PHP echo ($configuracion_tipo_trabajador['calculo_al_alcanzar_topeminimo'] == '1') ? 'Si' : 'No'; ?>
					 	</span>
					</td>
				</tr>

				<tr>
					<td>
						<span class="sp12b"> Proyección de la gratificación </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
						<span class="sp12">
						<?PHP

						    if($configuracion_tipo_trabajador['qcco_tipo_gratificacion'] == '0'){
								echo 'No se proyecta la gratificación';
							}
							else if($configuracion_tipo_trabajador['qcco_tipo_gratificacion'] == '1'){
								echo 'Se considera la remuneración base ';
							}
							else if($configuracion_tipo_trabajador['qcco_tipo_gratificacion'] == '2'){
								echo 'Se considera el aguinaldo de S./ '.$configuracion_tipo_trabajador['qcco_aguinaldo_diciembre'];
							}
					 	?>
					 	</span>
					</td>
				</tr>

				<tr>
					<td>
						<span class="sp12b"> Variable de cálculo </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
					 	<span class="sp12">
							<?PHP echo ($configuracion_tipo_trabajador['variable_id'] != '' ) ? 'Ok ('.$configuracion_tipo_trabajador['variable_importar'].')' : '--- Aqui tenemos un problema'; ?>
					 	</span>
					</td>
				</tr>

				<tr>
					<td>
						<span class="sp12b"> Concepto de cálculo </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
					 	<span class="sp12">
							<?PHP echo ($configuracion_tipo_trabajador['concepto_id'] != '' ) ? 'Ok ('.$configuracion_tipo_trabajador['concepto_importar'].')' : '--- Aqui tenemos un problema'; ?>
					 	</span>
					</td>
				</tr>

 
			</table>


	<?PHP 
		}else{

			echo '<span class="sp12b"> El módulo de quinta categoria para este tipo de trabajador no esta activo </span>';
		}
	?>

</div>


<div style="margin-top: 10px;">

	<span class="sp12b" style="text-decoration: underline; color:#990000;"> Módulo de Cuarta categoria </span> <br/> <br/>
	 
	<?PHP 
		if(  is_array($configuracion_tipo_trabajador_cuarta) && sizeof($configuracion_tipo_trabajador_cuarta) > 0){
	?>

			<table class="_tablepadding4">
				  
				<tr>
					<td>
						<span class="sp12b"> Porcentaje de descuento </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
						<span class="sp12">
						    <?PHP  echo ($configuracion_tipo_trabajador_cuarta['cuartaconf_porcentaje']).'%'; ?>
					 	</span>
					</td>
				</tr>

				<tr>
					<td>
						<span class="sp12b"> Variable de cálculo </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
					 	<span class="sp12">
							<?PHP echo ($configuracion_tipo_trabajador_cuarta['variable_id'] != '' ) ? 'Ok ('.$configuracion_tipo_trabajador_cuarta['variable_importar'].')' : '--- Aqui tenemos un problema'; ?>
					 	</span>
					</td>
				</tr>

				<tr>
					<td>
						<span class="sp12b"> Concepto de cálculo </span>
					</td>
					<td>
						<span class="sp12b">: </span>
					</td>
					<td>
					 	<span class="sp12">
							<?PHP echo ($configuracion_tipo_trabajador_cuarta['concepto_id'] != '' ) ? 'Ok ('.$configuracion_tipo_trabajador_cuarta['concepto_importar'].')' : '--- Aqui tenemos un problema'; ?>
					 	</span>
					</td>
				</tr>

 
			</table>


	<?PHP 
		}else{

			echo '<span class="sp12b"> El módulo de cuarta categoria para este tipo de trabajador no esta activo </span>';
		}
	?>

</div>

<!-- 
<div>
	

	<span> Proyección </span>
	
	<div>
		Considerar monto de contrato 
			
	</div>
		
	<div>
		Parametros fijos del trabajador
	</div>

</div>

<div>
	
	<span>
		Remuneración imponible mensual
	</span>
	
	<div>
		Conceptos 
	</div>

</div> 


  <div  dojoType="dijit.layout.TabContainer" 
        attachParent="true" tabPosition="top" tabStrip="true" 
        data-dojo-props=' region:"center" ' style="width:100%; min-height:320px; height:70%; max-height:450px;"> 
 
        <div data-dojo-type="dijit.layout.ContentPane" 
        	  title="<span class='titletabname'>  Cálculo </span>"   data-dojo-props='' >
			
			<?PHP 
			if(MODULO_QUINTA_CONFIGURACION_COMPLETA){ 
			?>	
			<div style="float:left; display: block; width: 50%;">
			    
			    <div>
			    	<span class="sp12b"> 1.- Proyeccion: </span> 
			    </div>
			   
			    <span class="sp11"> Variables del trabajador que se consideran como Remuneración mensual </span> 

			    <div>
					<form data-dojo-type="dijit.form.Form" id="form_quinta_parametro_nuevo" >
						
						 
						 <input type="hidden" value="<?PHP echo $plati_id; ?>" name="tipoplanilla" />
						 <input type="hidden" value="<?PHP echo TIPOCALCULO_QUINTA; ?>" name="tipocalculo" />

				    	 <span class="sp12"> Variable: </span>
				    	 <select class="seloperando"
				    	         data-dojo-type="dijit.form.FilteringSelect" 
				    	         data-dojo-props='style : "width:150px; font-size:11px;",
				    	                          autoComplete: false,
				    	                          highlightMatch: "all",  
				    	                          name:"parametro",
				    	                          queryExpr:"*${0}*" ' >
				  		 <?PHP 
				    	     
				    	   foreach($variables as $vari){

				    	         echo "<option value='".$vari['vari_id']."' > ".$vari['vari_nombre']."</option> ";
				    	   }

				    	  ?> 

				    	 </select>
				    	 

				    	  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				    	    <?PHP 
				    	       $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
				    	    ?>
				    	      <script type="dojo/method" event="onClick" args="evt">
				    	     
				    	          var data = dojo.formToObject('form_quinta_parametro_nuevo');
 								

 								  if (data.parametro != '') {

					    	          if (Calculos._M.registrar_parametro.process(data)){

					    	          	  QuintaCategoria.Ui.Grids.parametros_proyeccion.refresh();
					    	          }
 								  }

				    	      </script>
				    	      <label class="sp11">
				    	 
				    	      </label>
				    	 </button>

			    	 </form>
			    </div>

		    	<div id="dvquinta_parametros_proyeccion">
		    	  	
		    	</div>
				
				<div style="margin:5px 0px 0px 5px;">
					
				 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				   <?PHP 
				      $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
				   ?>
				     <script type="dojo/method" event="onClick" args="evt">

				         var codigo = '';      
				                 
				         for(var i in QuintaCategoria.Ui.Grids.parametros_proyeccion.selection){
				               codigo = i;
				         }
				         
				         if (codigo != '') {   
				             
				             if (Calculos._M.quitar_parametro.process({'codigo' : codigo})) {

				             	 QuintaCategoria.Ui.Grids.parametros_proyeccion.refresh();
				             }
				         }
				         else{
				             alert('Debe seleccionar un registro');
				         }
				     </script>
				     <label class="sp11">
							Quitar
				     </label>
				</button>
				</div>

			</div>
			
			<?PHP } ?>

			<div style="float:left; display: block; width: 50%;">
			    
			    <div>
			    	<?PHP 
			    		$indice = (MODULO_QUINTA_CONFIGURACION_COMPLETA) ? '2' : '1';
			    	?>
			    	<span class="sp12b"> <?PHP echo $indice; ?>.- Remuneración mensual imponible: </span> 
			    </div>
			    

			    <span class="sp11"> También Remuneración considerada para cálculo del promedio </span> 

			   
			    <div>
					<form data-dojo-type="dijit.form.Form" id="form_quinta_concepto_nuevo" >
						
						 
						 <input type="hidden" value="<?PHP echo $plati_id; ?>" name="tipoplanilla" />
						 <input type="hidden" value="<?PHP echo TIPOCALCULO_QUINTA; ?>" name="tipocalculo" />

				    	 <span class="sp12"> Concepto: </span>
				    	 <select class="seloperando"
				    	         data-dojo-type="dijit.form.FilteringSelect" 
				    	         data-dojo-props='style : "width:150px; font-size:11px;",
				    	                          autoComplete: false,
				    	                          highlightMatch: "all",  
				    	                          name:"concepto",
				    	                          queryExpr:"*${0}*" ' >
				  		 <?PHP 
				    	     
				    	   foreach($conceptos as $conc){

				    	         echo "<option value='".$conc['conc_id']."' > ".$conc['conc_nombre']."</option> ";
				    	   }

				    	  ?> 

				    	 </select>
				    	 

				    	  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				    	    <?PHP 
				    	       $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
				    	    ?>
				    	      <script type="dojo/method" event="onClick" args="evt">
				    	     
				    	          var data = dojo.formToObject('form_quinta_concepto_nuevo');
 								

 								  if (data.concepto != '') {

					    	          if (Calculos._M.registrar_concepto.process(data)){

					    	          	  QuintaCategoria.Ui.Grids.conceptos_imponibles.refresh();
					    	          }
 								  }
 								  
				    	      </script>
				    	      <label class="sp11">
				    	 
				    	      </label>
				    	 </button>

			    	 </form>
			    </div>

		    	<div id="dvquinta_conceptos_imponibles">
		    	  	
		    	</div>
				
				<div style="margin: 5px 0px 0px 5px;">
					
				 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
				   <?PHP 
				      $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
				   ?>
				     <script type="dojo/method" event="onClick" args="evt">

				         var codigo = '';      
				                 
				         for(var i in QuintaCategoria.Ui.Grids.conceptos_imponibles.selection){
				               codigo = i;
				         }
				         
				         if (codigo != '') {   
				             
				             if (Calculos._M.quitar_concepto.process({'codigo' : codigo})) {

				             	 QuintaCategoria.Ui.Grids.conceptos_imponibles.refresh();
				             }
				         }
				         else{
				             alert('Debe seleccionar un registro');
				         }
				     </script>
				     <label class="sp11">
							Quitar
				     </label>
				</button>
				</div>

			</div>
             
        </div>
		<!-- 
		<?PHP 
		if(MODULO_QUINTA_CONFIGURACION_COMPLETA){ 
		?>	
		<div data-dojo-type="dijit.layout.ContentPane" 
			  title="<span class='titletabname'>  Cálculo por promedio </span>"   data-dojo-props='' >
		     
		</div>
		<?PHP 
		}
		?>
  </div>  -->

