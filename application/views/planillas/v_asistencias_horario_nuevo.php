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
	              Crear un nuevo horario de trabajo
	          </td>
	      </tr>
	    </table>
	</div> 
	
 	<div class="dvFormBorder">



 	<form dojoType="dijit.form.Form" id="form_horario_nuevo"> 
	<table class="_tablepadding4" border="0" width="450">	

				<tr class="row_form">
					 <td width="140"><span class="sp11b">Descripción</span></td>
					 <td width="5"><span class="sp11b">:</span></td>
					 <td colspan="4"> 
					 	 <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="name:'descripcion'" style="font-size:11px; width:200px;" />
					 </td>
				</tr>
			
			<tr class="row_form">
				 <td width="140"><span class="sp11b">Horario corrido</span></td>
				 <td width="5"><span class="sp11b">:</span></td>
				 <td> 
				 	<select id="sel_tipohorario" data-dojo-type="dijit.form.Select"  data-dojo-props="name: 'numero_horarios'" style="width:60px; font-size:11px;"> 
				 		<option value="1"> Si </option>
						<option value="2"> No </option>
				 	</select>	
				 </td>
				  <td> <span class="sp11b"> Harario entre 2 días</span></td>
				  <td> <span class="sp11b">:</span></td>
				  <td> 
				  	<select id="sel_horariodosdias" data-dojo-type="dijit.form.Select"  data-dojo-props="name: 'horariodosdias'" style="width:60px; font-size:11px;"> 
				  		<option value="1"> Si </option>
				 		<option value="0" selected="selected"> No </option>
				  	</select>	
				  </td>
			</tr>
 
				
 			
 			<tr class="row_form">
 				<td><span class="sp11b"> Ingreso</span> 
 					<span id="spanDiaAnterior" style="display:none;" class="sp11b"> (Día anterior) </span>
 				</td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					 <input id="fec_hora1"  data-dojo-type="dijit.form.TimeTextBox"
 					            data-dojo-props='type:"text", name:"hora1", value:"T07:45:00",
 					            title:"",
 					            constraints:{formatLength:"short" },
 					            required:true,
 					            invalidMessage:"" ' 
 					            style="width:60px; font-size:11px;" 

 					            onChange=""
 
 					            />
 		 			


 		 		</td>
 				<td><span class="sp11b"> Salida</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 

			 			<input id="fec_hora2"  data-dojo-type="dijit.form.TimeTextBox"
			 			           data-dojo-props='type:"text", name:"hora2", value:"T07:45:00",
			 			           title:"",
			 			           constraints:{formatLength:"short" },
			 			           required:true,
			 			           invalidMessage:"" ' 
			 			           style="width:60px; font-size:11px;" 

			 			           onChange=""   />

 				</td>
 			</tr>

			<tr class="row_form">
 				<td><span class="sp11b"> Tardanza </span></td>
 				<td><span class="sp11b">:</span></td>
 				<td colspan="4"> 

 					 <select id="selhor_tardanza"  data-dojo-type="dijit.form.Select" data-dojo-props="name:'tardanza'" style="font-size:11px; width:40px;">
 					 	<option value="1"> Si </option>
 					 	<option value="0" selected="selected"> No </option>
 					 </select>	

 					 <div style="display:inline;" id="dvhor_tardanza">

	 					 <span class="sp11b"> A partir de: </span>
	 
	 					 <input id="fec_hora_tar"  data-dojo-type="dijit.form.TimeTextBox"
	 					            data-dojo-props='type:"text", name:"hora_tardanza", value:"T07:45:00",
	 					            title:"",
	 					            constraints:{formatLength:"short" },
	 					            required:true,
	 					            invalidMessage:"" ' 
	 					            style="width:60px; font-size:11px;" 

	 					            onChange="dijit.byId('fec_hora_ft').constraints.min = this.get('value'); 
			 			           		   	  dijit.byId('fec_hora_ft').set('value', this.get('value'));   "   />

 					 </div>
 			    </td>
			</tr>

 			<tr class="row_form">
  				<td><span class="sp11b"> Falta por tardanza </span></td>
  				<td><span class="sp11b">:</span></td>
  				<td colspan="4"> 

  					 <select id="selhor_faltaxtar" data-dojo-type="dijit.form.Select" data-dojo-props="name:'faltaportardanza'" style="font-size:11px; width:40px;">
  					 	<option value="1"> Si </option>
  					 	<option value="0" selected="selected" > No </option>
  					 </select>	

  					 <div style="display:inline;" id="dvhor_faltaxtar">
	  					 <span class="sp11b"> A partir de: </span>
	 
	  					 <input id="fec_hora_ft"  data-dojo-type="dijit.form.TimeTextBox"
	  					            data-dojo-props='type:"text", name:"hora_ft", value:"T07:45:00",
	  					            title:"",
	  					            constraints:{formatLength:"short" },
	  					            required:true,
	  					            invalidMessage:"" ' 
	  					            style="width:60px; font-size:11px;" 

	  					            onChange=""   />

	  				 </div>
  			    </td>
 			</tr>
 			
 		 	<tr id="tr_segundohorario" class="row_form">
 				<td><span class="sp11b">Tarde Ingreso</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					 <input id="fec_hora3"  data-dojo-type="dijit.form.TimeTextBox"
 					            data-dojo-props='type:"text", name:"hora3", value:"T07:45:00",
 					            title:"",
 					            constraints:{formatLength:"short"},
 					            required:true,
 					            invalidMessage:"" ' style="width:60px; font-size:11px;"
 					             onChange="dijit.byId('fec_hora4').constraints.min = this.get('value');
 					             		   dijit.byId('fec_hora4').set('value', this.get('value')); "
 					            />
 				</td>

 				<td><span class="sp11b"> Salida</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					
 					 <input id="fec_hora4" data-dojo-type="dijit.form.TimeTextBox"
 					            data-dojo-props='type:"text", name:"hora4", value:"T07:45:00",
 					            title:"",
 					            constraints:{formatLength:"short"},
 					            required:true,
 					            invalidMessage:"" ' style="width:60px; font-size:11px;" 

 					            />

 				</td>
 			</tr>
 

 			<tr id="tr_refrigerio" class="row_form" >
 				 <td><span class="sp11b"> Descontar por refrigerio </span></td>
 				 <td><span class="sp11b">:</span></td>
 				 <td colspan="4"> 
 				 	 <input type="text" data-dojo-type="dijit.form.TextBox" name="horas_descontar" value="0" style="width:40px; font-size:11px;" />
 
 				 	 <span class="sp11b">  horas solo despues de:  </span>

 				 	 <input id="fec_refri" data-dojo-type="dijit.form.TimeTextBox"
 				 	            data-dojo-props='type:"text", name:"hora_ref", value:"T07:45:00",
 				 	            title:"",
 				 	            constraints:{formatLength:"short" },
 				 	            required:true,
 				 	            invalidMessage:"" ' 
 				 	            style="width:60px; font-size:11px;" 

 				 	            onChange=" "

 				 	            />

 				 </td>
 			</tr>

 			<?PHP 

 				if(ASISDET_HORARIO_HORARIOALTERNATIVO === TRUE)
 				{ 
 			?>
 			<tr class="row_form">	
 				<td colspan="6"> 
 					<span class="sp11"> 
 						 Horario alternativo.
 					</span>
 				</td>
 			</tr>

 			<tr class="row_form">
 				<td><span class="sp11b">Mañana Ingreso</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					 <input id="fec_ha_e"  data-dojo-type="dijit.form.TimeTextBox"
 					            data-dojo-props='type:"text", name:"hora_a1", value:"T07:45:00",
 					            title:"",
 					            constraints:{formatLength:"short" },
 					            required:true,
 					            invalidMessage:"" ' 
 					            style="width:60px; font-size:11px;" 

 					            onChange="dijit.byId('fec_ha_s').constraints.min = this.get('value'); 
 					            		  dijit.byId('fec_ha_s').set('value', this.get('value') ); "

 					            />
 				</td>

 				<td><span class="sp11b"> Salida</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 

 					 <input id="fec_ha_s" data-dojo-type="dijit.form.TimeTextBox"
 					            data-dojo-props='type:"text", name:"hora_a2", value:"T07:45:00",
 					            title:"",
 					            constraints:{formatLength:"short"},
 					            required:true,
 					            invalidMessage:"" ' style="width:60px; font-size:11px;"/>

 				</td>
 			</tr>
 
 			<?PHP 

 			 	}
 			?>

 			<tr>
 				<td colspan="6" align="center">

 					 <button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
 					        <?PHP 
 					           $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
 					        ?>
 					      <label class="lbl10"> Registrar horario </label>
 					      <script type="dojo/method" event="onClick" args="evt">
 					            	
 					      	 var data = dojo.formToObject('form_horario_nuevo'); 

 					      	 var ok =  dijit.byId('form_horario_nuevo').validate();

 					      	 if(ok && dojo.trim(data.descripcion) != '')
 					      	 {	

 					      	 	 if(confirm('Realmente desea registrar este horario de trabajo? '))
 					      	 	 {
		 					      	 if(Asistencias._M.registrar_horario.process(data))
		 					      	 {
		 					      	 	 Asistencias._V.nuevo_horario.close();
		 					      	 	 Asistencias.Ui.Grids.horarios.refresh();
		 					      	 }	
  					      	 	 }
 					      	 }
 					      	 else
 					      	 {
 					      	 	alert('Por favor verifique los datos ingresados');
 					      	 }
 					      </script>
 					 </button>
 				</td>	
 			</tr>

	</table>	

	</form>

	</div>
</div>