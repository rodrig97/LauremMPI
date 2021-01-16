<div class="window_container">
	

	<div id="dvRegistroLicencia" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
	                   
		
		<div dojoType="dijit.layout.ContentPane" 
			 title="<span class='titletabname'> </span>"
			 region="center" >
		

		<div id="dvViewName" class="dv_view_name">
		      
		    <table class="_tablepadding2" border="0">

		      <tr> 
		          <td> 
		               <?PHP 
		                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
		                     ?>
		          </td>

		         <td>
		             Registro de licencias  
		          </td>
		      </tr>
		  </table>
		</div>

		<form id="form_licencias_registradas" method="post" data-dojo-type="dijit.form.Form" action="exportar/reporte_de_licencias" target="_blank" > 
	 		   
	 		 <input id="hdLicenciasRegistradas_fechadesde" type="hidden" value="<?PHP echo $fechadesde; ?>">
			 <input id="hdLicenciasRegistradas_fechahasta" type="hidden" value="<?PHP echo $fechahasta; ?>">
			 
			 <div class="dv_busqueda_personalizada">
			 		
 
			     <table class="_tablepadding2" border="0" >
			       

			       <tr>
			             <td> 
			                 <span class="sp11b"> Tipo de licencia </span>
			             </td> 
			             <td> 
			                 <span class="sp11b"> : </span>
			             </td> 
			             <td>         
			                 
			                  <select name="tipo" 
			                 		id="sel_registrolicencias_tipoestadodia" 
			                 	    data-dojo-type="dijit.form.FilteringSelect" 
			                 	    data-dojo-props="name:'tipo'" 
			                 	    class="formelement-200-11" >

			                 	<option value="0">   No especificar </option>
			                 	<option value="vac_1">   Vacaciones </option>
			                 	<option value="desm_1">  Descansos Médicos </option>
			                 	<option value="comc_1">  Comisión de Servicios </option> 
			                 		
			                 	<?PHP 
			                 		foreach ($tipo_licencias as $reg ) {
			                 			echo '	<option value="lic_'.$reg['tipolic_id'].'">'.$reg['tipolic_nombre'].'</option> ';
			                 		}
			                 	?> 
			                 </select>

			             </td> 
 
			         </tr>

			         <tr>    
			             <td width="100"> 
			                 <span class="sp11b">  Fecha </span>
			             </td> 
			             <td width="5"> 
			                 <span class="sp11b"> :  </span>
			             </td> 
			             <td width="600"> 
	  						 
	  						 <select  id="selRLTipoPeriodo" 
	  						 		 data-dojo-props="name:'tipoPeriodo'"
	  						 		 data-dojo-type="dijit.form.Select" 
	  						 		 style="font-size: 11px; width: 140px;">

	  						 	<option value="anio"> Por Año </option>
	  						 	<option value="rango_dias"> Fecha del documento </option> 
	  						 	<option value="registro_sistema"> Registro en el sistema </option> 
	  						 </select>
						
							<div id="divPeriodoAnios" style="display:inline-block;">
									 
									<select name="anio"  
											data-dojo-type="dijit.form.Select" 
											data-dojo-props="name:'anio' "
											style="font-size: 11px; width: 110px;">
 										
 										<?PHP 
 										   foreach ($anios as $anio)
 										   {
 										     # code...
 										     echo '<option value="'.$anio['ano_eje'].'" ';

 										     if($anio['ano_eje']== date('Y'))
 										     {
 										        echo ' selected="selected" ';
 										     }

 										     echo ' >'.$anio['ano_eje'].'</option>';
 										   }
 										?>

									</select>

							</div>

							<div id="divPeriodoIntervaloFechas" style="display:inline-block;">
								 
				                 <span class="sp11b">  Desde: </span>

				                 <div id="calLicenciasRegistradas_desde"  
				                 	  data-dojo-type="dijit.form.DateTextBox"
				                      data-dojo-props='type:"text", name:"fecha_desde", value:"",
							                           constraints:{datePattern:"dd/MM/yyyy", strict:true},
								                       lang:"es",
								                       required:true,
								                       promptMessage:"mm/dd/yyyy",
								                       invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 
								      class="formelement-80-11"
				                      onChange="dijit.byId('calLicenciasRegistradas_hasta').constraints.min = dijit.byId('calLicenciasRegistradas_desde').get('value'); "></div> 

				                 <span class="sp11b">  Hasta: </span>

				                 <div id="calLicenciasRegistradas_hasta"  
				                 	  data-dojo-type="dijit.form.DateTextBox"
				                      data-dojo-props='type:"text", name:"fecha_hasta", value:"",
				                        			   constraints:{datePattern:"dd/MM/yyyy", strict:true},
								                       lang:"es",
								                       required:true,
								                       promptMessage:"mm/dd/yyyy",
								                       invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 
								      class="formelement-80-11"
				                      onChange=""></div> 

			                 </div>
			             </td> 
			       </tr>
			         <tr> 
			            <td> 
			               <span class="sp11b"> Filtrar por </span>
			            </td> 
			            <td> 
			               <span class="sp11b"> : </span>
			            </td> 
			            <td> 
 
			            		<select id="buscarPorTrabajadorTipoTrabajador" 
			            			 	data-dojo-type="dijit.form.Select" 
			            			 	data-dojo-props="name:'filtrar_por'"
			            			 	style="font-size: 11px; width: 140px;">
			            			<option value="trabajador"> Trabajador especifico </option>
			            			<option value="tipotrabajador"> Tipo de trabajador </option>
			            		</select>
								
								<div id="dvbuscarTipoTrabajador" style="display:inline-block;">
 

									<select data-dojo-type="dijit.form.Select" 
											   data-dojo-props='name:"tipotrabajador", disabled:false'
									        style="margin-left:6px; font-size:11px; width:200px;">
									      
									      <?PHP
									        foreach($tipoTrabajador as $tipo){
									             echo "<option value='".trim($tipo['plati_id'])."'>".trim($tipo['plati_nombre'])."</option>";
									        }
									      ?>
									</select>

									
								</div>
							   
							 	
							 	<div id="dvbuscarTrabajador" style="display:inline-block;">
 
				                   <select id="sellicenciatrabajador"  
				                           data-dojo-type="dijit.form.FilteringSelect" 
				                           class="formelement-200-11" 
				                           data-dojo-props='name:"trabajador", 
				                                            disabled:false, 
				                                            autoComplete:false, 
				                                            highlightMatch: "all",  
				                                            queryExpr:"${0}", 
				                                            invalidMessage: "La Persona no esta registrada" ' 
				                           style="width:280px;"  >
				                   </select>

				                </div>

			            </td> 

			         </tr> 

             		 <tr id="trxf_poracumulado">
             			 <td>  
             			 	 <span class="sp11b"> Agrupar por  </span>
             			 </td>
             			 <td width="5"> 
             			 	 <span class="sp11b"> 	: </span>
             			 </td>
     				     <td>
     					  	    <select id="selxf_agruparpor"  
     					  	   			name="agruparpor" 
     					  	   			data-dojo-type="dijit.form.Select" 
     					  	   			data-dojo-props='name:"agruparpor"' 
     					  	   			class="formelement-100-12" 
     					  	   			style="width:140px; font-size:11px;" >
                                      
                                      <option value="0" selected="selected"> No agrupar </option>
                                      <option value="anio"> Trabajador / Año  </option>  
                                </select> 

                                <div id="dvregistrolicenciasTotalAcumulado" style="display:inline-block;">

                                	<span class="sp11b"> Total acumulado:  </span>

							  	    <select data-dojo-type="dijit.form.Select" 
							  	    		data-dojo-props='name:"poracumulado"' 
							  	    		class="formelement-100-11" 
							  	    		style="width:130px;  font-size:11px;" >

		                                 <option value="1" selected="selected"> Mayor o igual a </option>
		                                 <option value="2"> Menor o igual a </option>
		                            </select> 

		                            <input type="text" 
		                            	   data-dojo-type="dijit.form.TextBox" 
		                           		   data-dojo-props="name:'valoracumulado'" 
		                           		   style="font-size:11px; width:60px;" 
		                           		   value="0" />
                                </div>

     					  </td>
     				</tr> 
              		 <tr> 
	  					<td colspan="3" align="center">

	  					       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	  					         <?PHP 
	  					            $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
	  					         ?>
	  					           <script type="dojo/method" event="onClick" args="evt">

	  					           		var data = dojo.formToObject('form_licencias_registradas');

	  					           		if(data.agruparpor=='anio'){

  					           				EstadoDia.Ui.Grids.licencias_registradas.domNode.style= "width:780px";

  					           				var colums = { 
  					           				             
  					           				            'col1': {label: '#', sortable: true},      
  					           				            'col2': {label: 'Trabajador', sortable: false},
  					           				            'col3': {label: 'DNI', sortable: false},
  					           				            'tipotrabajador': {label: 'Tipo Trabajador', sortable: false}, 
	  					           			            'col4': {label: 'Tipo', sortable: false},
  					           				            'anio': {label: 'Año', sortable: false}, 
  					           				            'col7': {label: 'Dias', sortable: false}  
  					           				          
  					           			    };

  					           			    dojo.setStyle( dojo.byId('dvReistroLicencia_Botonera'), 'display', 'none'); 
	  					           		}
	  					           		else
	  					           		{

	  					           			EstadoDia.Ui.Grids.licencias_registradas.domNode.style= "width:1100px";

	  					           			var colums = { 
	  					           			             
	  					           			            'col1': {label: '#', sortable: true},      
	  					           			            'col2': {label: 'Trabajador', sortable: false},
	  					           			            'col3': {label: 'DNI', sortable: false},
	  					           			            'tipotrabajador': {label: 'Tipo Trabajador', sortable: false},
	  					           			            'col4': {label: 'Tipo', sortable: false},
	  					           			            'col5': {label: 'Desde', sortable: false},
	  					           			            'col6': {label: 'Hasta', sortable: false},
	  					           			            'observacion': {label: 'Detalle/Observacion', sortable: false},
	  					           			            'col7': {label: 'Dias', sortable: false}, 
	  					           			            'fecha_registro': {label: 'Fe.Registro', sortable: false}
	  					           			          
	  					           		    };

  					           			    dojo.setStyle( dojo.byId('dvReistroLicencia_Botonera'), 'display', 'inline-block');

	  					           		}
	  					                
	  					                EstadoDia.Ui.Grids.licencias_registradas.set('columns',colums);

	  					                EstadoDia.Ui.Grids.licencias_registradas.refresh();
	  					               
	  					           </script>
	  					           <label class="sp11">
	  					                Filtrar  
	  					           </label>
	  					      </button>

	  					</td>    
              		  </tr>
			     </table>    
	 

			 </div>
		</form> 

		<div id="tablalicencias_panel_registradas"></div>

		<div style="margin: 5px 0px 0px 5px">
			
			
			<div id="dvReistroLicencia_Botonera" style="display: inline-block;">
				
		       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		         <?PHP 
		            $this->resources->getImage('application.png',array('width' => '14', 'height' => '14'));
		         ?>
		           <script type="dojo/method" event="onClick" args="evt">
						  
						var codigo = '';      
						        
						for(var i in EstadoDia.Ui.Grids.licencias_registradas.selection){
						      codigo = i;
						}
	 				
						if(codigo != '')      
						{     
							 console.log(codigo);
						     var rs =  codigo.split("_");
						     var tipo = rs[0];
						     var codigo = rs[1];

						     if(tipo == 'DM'  )
						     {
						        view = 'view_descanso';
						     }
						     else if(tipo == 'CS' )
						     {
						        view = 'view_comision';

						     }
						     else if(tipo == 'LC' )
						     {
						        view = 'view_licencia';

						     }
						     else if(tipo == 'VC')
						     {
						       view = 'view_vacaciones';
						     }  

						     Persona._V[view].load({'codigo' : codigo, 'oid' : '1'});
						}
						else
						{
						     alert('Debe seleccionar un registro');
						}
		           
			           </script>
		           <label class="sp11">
		                 Visualizar 
		           </label>
		      </button>


		       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		         <?PHP 
		            $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
		         ?>
		           <script type="dojo/method" event="onClick" args="evt">
						  
						 var codigo = '';      
						         
						 for(var i in EstadoDia.Ui.Grids.licencias_registradas.selection){
						       codigo = i;
						 }
						 
						 if(codigo != '')      
						 {    
						 	  
						 	  if (confirm('Realmente desea eliminar este registro?')) {
						 	  
							      if ( EstadoDia._M.eliminar_registro.process({'view' : codigo}) ) {
							      	
							      		EstadoDia.Ui.Grids.licencias_registradas.refresh();
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


			</div>

		       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		         <?PHP 
		            $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
		         ?>
		           <script type="dojo/method" event="onClick" args="evt">
						 

		                var data =  dojo.formToObject('form_licencias_registradas'); 
		               
		                EstadoDia._V.nuevo_registro.load(data);

	                                                
		           
			           </script>
		           <label class="sp11">
		                Registrar Nuevo  
		           </label>
		      </button>


	         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	           <?PHP 
	              $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
	           ?>
	             <script type="dojo/method" event="onClick" args="evt">
	  				 
 

	                  document.getElementById('form_licencias_registradas').submit();     
	             
	  	           </script>
	             <label class="sp11">
	                  Generar reporte   
	             </label>
	        </button>


		</div>

	</div>

</div>