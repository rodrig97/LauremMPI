<div class="window_container">
	
	
	<div id="dvViewName" class="dv_view_name" style="font-size: 12px;">
	    
	      <table class="_tablepadding2" border="0">
	          <tr> 
	              <td> 
	                   <?PHP 
	                             $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
	                         ?>
	              </td>

	             <td>
	                  Registrar Licencia
	              </td>
	          </tr>
	    </table>
	</div>
	  


	<div class="dvFormBorder">
	
	<div data-dojo-type="dijit.form.Form" id="form_registro_dia">

	<table class="_tablepadding4" width="99%">
		
		<tr class="row_form" >
			<td width="120">
				<span class="sp11b">
					Trabajador 
				</span>
			</td>
			<td width="5">
				<span class="sp11">
					: 
				</span>
			</td>
			<td>
				<input type="hidden" id="HdLicenciaNuevoTipoTrabajador" value="<?PHP echo $plati_id; ?>">

				<select id="selregistrodiario_persona"  name="trabajador"
				       data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
				       data-dojo-props='name:"trabajador", 
				                        disabled:false, 
				                        autoComplete:false, 
				                        highlightMatch: "all",  
				                        queryExpr:"${0}", 
				                        invalidMessage: "La Persona no esta registrada" ' style="width:300px;"  >
				</select>

			</td>
		</tr>
		
		<tr class="row_form" >
			<td>
				<span class="sp11b">
					Tipo
				</span>
			</td>
			<td>
				<span class="sp11">
					: 
				</span>
			</td>
			<td>
				<select name="estado_dia" 
						id="sel_estadodia_tipoestado" 
					    data-dojo-type="dijit.form.FilteringSelect" 
					    data-dojo-props="name:'estado_dia'" 
					    class="formelement-200-11" >

					<option value="vac_1">   Vacaciones </option>
					<option value="desm_1">  Descansos Médicos </option>
					<option value="comc_1">  Comisión de Servicios </option>
					<!-- <option value="falta_1"> Falta  </option> -->
						
					<?PHP 
						foreach ($tipo_licencias as $reg ) {
							echo '	<option value="lic_'.$reg['tipolic_id'].'">'.$reg['tipolic_nombre'].'</option> ';
						}
					?><!-- 

					<option value="lic_cita_medica">  Citas Médicas </option>
					<option value="lic_perm_particular">  Permiso Particular </option>
					<option value="lic_perm_onomastico">  Permiso por onomástico </option>
					<option value="lic_sindical">  Licencia Sindical </option>
					<option value="lic_citacion_judicial">  Citación Judicial </option>
					<option value="lic_capacitacion">  Licencia por capacitación </option>
					<option value="lic_paternidad">  Licencia por Maternidad/Paternidad </option>
					<option value="lic_fallecimiento">  Licencia por fallecimiento </option>
					<option value="lic_suspension">  Suspensión Temporal </option>
					<option value="lic_goce_haber">  Licencia con goce de Haber </option>
					<option value="lic_sin_goce_haber">  Licencia sin goce de Haber </option> -->
				</select>
			</td>
		</tr>
		
		<tr class="row_form" >
			<td>
				  <span class="sp11b">
				  		Desde 
				  </span>
			</td>
			<td>
				<span class="sp11">
					: 
				</span>
			</td>
			<td> 
				  <div id="calEstadoDia_desde" 
				  	   data-dojo-type="dijit.form.DateTextBox"
				  	   style="font-size: 11px; width: 90px;"
				       data-dojo-props='type:"text", 
				                        name:"fecha_desde", value:"",
				                        constraints:{datePattern:"dd/MM/yyyy", strict:true},
							            lang:"es",
							            required:true,
							            promptMessage:"mm/dd/yyyy",
							            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
					   
					   onChange="dijit.byId('calEstadoDia_hasta').constraints.min = this.get('value'); 
					   			 dijit.byId('calEstadoDia_hasta').set('value', this.get('value') );" />    
							         	>
				  </div>
 
				  <span class="sp11b">
				  		Hasta: 
				  </span>
 				  
			      <div id="calEstadoDia_hasta" 
			      	   data-dojo-type="dijit.form.DateTextBox"
			    	   style="font-size: 11px; width: 90px;"
			           data-dojo-props='type:"text", 
			                          	name:"fecha_hasta", value:"",
				                        constraints:{datePattern:"dd/MM/yyyy", strict:true},
					  			        lang:"es",
					  			        required:true,
					  			        promptMessage:"mm/dd/yyyy",
					  			        invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
			      </div>


			</td>
		</tr>
	 

		<tr class="row_form" >
		     <td> <span class="sp11"> Doc. Referencia </span> </td>
		     <td> <span class="sp11"> : </span></td>
		     <td>
		         <input name="documento" 
		         		type="text" 
		         		data-dojo-type="dijit.form.TextBox" 
		         		data-dojo-props="maxlength:100, trim:true, name:'documento' " 
		         		class="formelement-180-11"  />
		     </td>
		</tr>
		
		<tr class="row_form" > 
		     <td> <span class="sp11"> Autoriza </span></td>
		     <td>:</td>
		     <td>
		         <input name="autoriza" 
		         		type="text" 
		         		data-dojo-type="dijit.form.TextBox" 
		         		data-dojo-props="maxlength:100, trim:true, name:'autoriza'   " 
		         		class="formelement-180-11"  />
		     </td>
		</tr> 
<!-- 
		<tr class="row_form"> 
		     <td> <span class="sp11"> Motivo </span></td>
		     <td>:</td>
		     <td> 
		        <div data-dojo-props="name:'motivo'" data-dojo-type="dijit.form.TextArea" data-dojo-props="maxlength:100,   required:true, trim:true,  missingMessage:'Especifique el Motivo de la comision de servicio' " class="formelement-350-11"></div> 
		     </td>
		</tr>
		 -->
		<tr class="row_form" id="tr_estadodia_tiposeguro"> 
		     <td> <span class="sp11"> Tipo seguro (DM) </span></td>
		     <td>:</td>
		     <td> 
		     	 <select  name="tipo_descansomedico"
		     	 		  data-dojo-type="dijit.form.Select" 
		     	 		  data-dojo-props='name:"tipo_descansomedico"' 
		     	          class="formelement-100-12" 
		     	          style="width:180px; font-size: 11px;" >
		     	    
		     	     <?PHP 
		     	        foreach ($tipos_descanso_medico as $tipo)
		     	        {
		     	        
		     	            echo " <option value='".$tipo['tdm_id']."'>".$tipo['tdm_nombre']."</option> ";
		     	        }
		     	     ?>
		     	 </select> 
		     </td>
		</tr>

		<tr class="row_form" id="tr_estadodia_destino">
	         <td> <span class="sp11"> Destino </span></td>
	         <td>:</td>
	         <td> 
		        
	              <select  data-dojo-type="dijit.form.FilteringSelect" 
	              		   name="destino"
	              		   data-dojo-props='name:"destino", 
	              		   					disabled:false, 
	              		   					autoComplete:false, 
	              		   					highlightMatch: "all",  
	              		   					queryExpr:"${0}*", 
	              		   					invalidMessage: "La Ciudad no esta registrada" ' 
	              		   					style="margin-left:0px; font-size:11px; width: 180px;">

	                     <option value="0"> No Especificar </option>

	                     <?PHP
	                        foreach($ciudades as $ciudad)
	                        {
	                           
	                             echo "<option value='".trim($ciudad['distrito_id'])."-".trim($ciudad['provincia_id'])."-".trim($ciudad['departamento_id'])."'>  ".trim($ciudad['distrito'])." - ".trim($ciudad['provincia'])." - ".trim($ciudad['departamento'])."   </option>";
	                         }
	                      ?>
	              </select> 
	      	  </td>
		</tr>
		

		<tr class="row_form" >
		     <td> <span class="sp11"> Obs/Descripción </span></td>
		     <td>:</td>
		     <td>
		         <input name="observacion" type="text" 
		         		data-dojo-type="dijit.form.TextArea" 
		         		data-dojo-props="maxlength:100, trim:true, name:'observacion' " 
		         		style="font-size: 11px; width: 300px;" />
		     </td>
		</tr> 


		<tr height="40">
		  	<td colspan="3" align="center">
		  		 
		  		
		  		 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		  		   <?PHP 
		  		      $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
		  		   ?>
		  		     <script type="dojo/method" event="onClick" args="evt">

		  		            var data  = dojo.formToObject('form_registro_dia'), mensaje = ' <ul> ', err = false;
		  					
		  		            var errores = [];

		  		            if ( data.trabajador == undefined || data.trabajador == null || data.trabajador == '' || data.trabajador == '0' ) {

		  		            	errores.push('Debe especificar un trabajador');
		  		            }

		  		            
		  		            if( data.estado_dia == undefined || data.estado_dia == null || data.estado_dia == '' || data.estado_dia == '0' ){
		  		            
		  		            	errores.push('Verifique el tipo de licencia especificada ');	
		  		            }

		  		            if(data.fecha_desde == undefined || data.fecha_desde == null || data.fecha_desde == '' || 
		  		               data.fecha_hasta == undefined || data.fecha_hasta == null || data.fecha_hasta == '' ){

		  		            	errores.push('Verifique el periodo registrado');
		  		            }

		  		            if(errores.length == 0){ 

			  		            if(confirm('Realmente quiere proceder con el registro? ')){ 

				  					if(EstadoDia._M.registrar_dia_estado_trabajador.process(data)){
				  						
				  						dijit.byId('form_registro_dia').reset();

				  						var fecha = $_currentDate(); 
				  						
				  						dijit.byId('sel_estadodia_tipoestado').set('value', data.estado_dia );
				  						
				  						dijit.byId('calEstadoDia_desde').set('value',  fecha   );
				  						dijit.byId('calEstadoDia_hasta').set('value',  fecha   );


				  					}

			  					}

		  					} else {

		  						var myDialog = new dijit.Dialog({
		  						               title: "Atenci&oacute;n",
		  						               content:  '<div style="padding: 4px 4px 4px 4px;"> <ul> <li> ' + ( errores.join('</li> <li> ') ) + ' </li> </ul> </div>',
		  						               style: "width: 350px"
		  						           });

		  						myDialog.show();
		  					}

		  		           // if(data.solicita == '' || data.solicita == undefined || data.solicita == null)
		  		           // {  
		  		           //      mensaje+='<li> La persona que solicita no esta registrada </li> ';
		  		           //      err = true;
		  		           // } 
		  		           // if(data.autoriza == '' || data.autoriza == undefined || data.autoriza == null)
		  		           // { 
		  		           //      mensaje+='<li> La persona que autoriza no esta registrada </li> ';
		  		           //      err = true;
		  		           // } 
		  		           // if( dijit.byId('selsolicitudper_hora') != null && (data.horasalida == '' || data.horasalida == undefined || data.horasalida == null) )
		  		           // { 
		  		           //      mensaje+='<li> La hora de salida no es valida </li> ';
		  		           //      err = true;
		  		           // } 
		  		           // if(data.motivo == '' || data.motivo == undefined || data.motivo == null)
		  		           // { 
		  		           //      mensaje+='<li> Por favor verifique el motivo del permiso</li> ';
		  		           //      err = true;
		  		           // }
		  		           

		  		           // if(err === false) 
		  		           // {
		  		           //     if(data.destino == '')
		  		           //     {
		  		           //          data.destino_label = dijit.byId('selsolicitudper_destino').get('displayedValue');
		  		           //     }

		  		           //     if(Permisos._M.registrar_solicitud.process(data))
		  		           //     {

		  		           //           Permisos._V.nueva_solicitud.close();
		  		           //           dojo.byId('mnupermisos_missolicitudes').click();
		  		           //     }
		  		           // }                  
		  		           // else
		  		           // {
		  		           //      app.alert(mensaje);
		  		           // }

		  		     </script>
		  		     <label class="sp11">
		  		          Registrar  
		  		     </label>
		  		</button>
		  	</td>
		</tr>

	</table>
 
	</div>

	</div>
	 
</div>