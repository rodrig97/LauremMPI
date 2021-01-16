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
	              Permisos registrados
	          </td>
	      </tr>
	  </table>
	</div>

	<form id="form_permisos_registrados" data-dojo-type="dijit.form.Form"> 
 		   
 		 <input id="hdPermisosRegistrados_fechadesde" type="hidden" value="<?PHP echo $fechadesde; ?>">
		 <input id="hdPermisosRegistrados_fechahasta" type="hidden" value="<?PHP echo $fechahasta; ?>">
		 
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

		                 <div id="calPermisosRegistrados_desde"  
		                 	  data-dojo-type="dijit.form.DateTextBox"
		                      data-dojo-props='type:"text", name:"fecha_desde", value:"",
					                           constraints:{datePattern:"dd/MM/yyyy", strict:true},
						                       lang:"es",
						                       required:true,
						                       promptMessage:"mm/dd/yyyy",
						                       invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 
						      class="formelement-80-11"
		                      onChange="dijit.byId('calPermisosRegistrados_hasta').constraints.min = dijit.byId('calPermisosRegistrados_desde').get('value'); "></div> 

		                 <span class="sp11b">  Hasta: </span>

		                 <div id="calPermisosRegistrados_hasta"  
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
		                 <span class="sp11b"> Motivo </span>
		             </td> 
		             <td> 
		                 <span class="sp11b"> : </span>
		             </td> 
		             <td>         
		                 <select data-dojo-type="dijit.form.Select" 
		                 		 data-dojo-props="name:'motivo'" 
		                 		 class="formelement-100-11" 
		                 		 style="width:120px;" > 

		                         <option value="0"> No especificar </option>
		                         <?PHP 
		                             foreach ($motivos as $motivo)
		                             {
		                                 echo " <option value=".$motivo['permot_id'].">  ".$motivo['permot_nombre']." </option> ";
		                             }
		                         ?>
		                 </select>
		             </td> 

		             <td> 
		                 <span class="sp11b"> Retorno </span>
		             </td> 
		             <td> 
		                 <span class="sp11b"> : </span>
		             </td> 
		             <td>         
		                 <select data-dojo-type="dijit.form.Select" 
		                 		 data-dojo-props="name:'retorno'" 
		                 		 class="formelement-100-11" 
		                 		 style="width:120px;" > 

		                         <option value="1"> Todos </option> 
		                         <option value="0"> Sin retorno </option> 

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

 		            	   <input type="hidden" id="HdPermisosRegistroTipoTrabajador" value="<?PHP echo $plati_id; ?>">
 
		                   <select id="selspermisoaprobacion_solicita"  
		                           data-dojo-type="dijit.form.FilteringSelect" 
		                           class="formelement-200-11" 
		                           data-dojo-props='name:"solicita", 
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

 					                 Permisos.Ui.Grids.permisos_registrados.refresh();
 					               
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

	<div id="tablapermisos_registrados"></div>

	<div style="margin: 5px 0px 0px 5px">
		
	
	       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	         <?PHP 
	            $this->resources->getImage('application.png',array('width' => '14', 'height' => '14'));
	         ?>
	           <script type="dojo/method" event="onClick" args="evt">
					  
					var codigo = '';      
					        
					for(var i in Permisos.Ui.Grids.permisos_registrados.selection){
					      codigo = i;
					}
 
					if(codigo != '')      
					{    
					     Permisos._V.visualizar_permiso.load({'view' : codigo});
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
					         
					 for(var i in Permisos.Ui.Grids.permisos_registrados.selection){
					       codigo = i;
					 }
					 
					 if(codigo != '')      
					 {    
					 	  
					 	  if (confirm('Realmente desea eliminar la papeleta?')) {
					 	  
						      if ( Permisos._M.eliminar.process({'view' : codigo}) ) {
						      		Permisos.Ui.Grids.permisos_registrados.refresh();
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
					
					Permisos._V.nuevo_permiso.load(data); 
	           
		           </script>
	           <label class="sp11">
	                Registrar Nuevo  
	           </label>
	      </button>

	</div>

</div>