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
	             Registro de licencias  
	          </td>
	      </tr>
	  </table>
	</div>

	<form id="form_licencias_registradas" data-dojo-type="dijit.form.Form"> 
 		   
 		 <input id="hdLicenciasRegistradas_fechadesde" type="hidden" value="<?PHP echo $fechadesde; ?>">
		 <input id="hdLicenciasRegistradas_fechahasta" type="hidden" value="<?PHP echo $fechahasta; ?>">
		 
		 <div class="dv_busqueda_personalizada">
		 
		     <table class="_tablepadding2" border="0" >
		         <tr>    
		             <td width="60"> 
		                 <span class="sp11b">  Periodo </span>
		             </td> 
		             <td width="5"> 
		                 <span class="sp11b">  </span>
		             </td> 
		             <td width="300"> 
 
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
		             </td> 
		             
		             <td> 
		                 <span class="sp11b"> Tipo </span>
		             </td> 
		             <td> 
		                 <span class="sp11b"> : </span>
		             </td> 
		             <td colspan="4">         
		                 
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
		            <td> 
		               <span class="sp11b"> Trabajador </span>
		            </td> 
		            <td> 
		               <span class="sp11b"> : </span>
		            </td> 
		            <td colspan="2"> 

 		            	   <input type="hidden" id="HdLicenciasRegistroTipoTrabajador" value="<?PHP echo $plati_id; ?>">

		                   <select id="sellicenciatrabajador"  
		                           data-dojo-type="dijit.form.FilteringSelect" 
		                           class="formelement-200-11" 
		                           data-dojo-props='name:"trabajador", 
		                                            disabled:false, 
		                                            autoComplete:false, 
		                                            highlightMatch: "all",  
		                                            queryExpr:"${0}", 
		                                            invalidMessage: "La Persona no esta registrada" ' 
		                           style="width:300px;"  >
		                   </select>

		            </td> 

		            <td></td>
 					<td colspan="4">

 					       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
 					         <?PHP 
 					            $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
 					         ?>
 					           <script type="dojo/method" event="onClick" args="evt">

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

	<div id="tablalicencias_registradas"></div>

	<div style="margin: 5px 0px 0px 5px">
		
	
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

	       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	         <?PHP 
	            $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
	         ?>
	           <script type="dojo/method" event="onClick" args="evt">
					 

	                var data =  dojo.formToObject('form_explorar_asistencias'); 
	               
	                EstadoDia._V.nuevo_registro.load(data);

                                                
	           
		           </script>
	           <label class="sp11">
	                Registrar Nuevo  
	           </label>
	      </button>

	</div>

</div>