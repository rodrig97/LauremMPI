<div  class="window_container"  ><!-- para efectos de la elimancion del DOm -->

	<div id="dvViewName" class="dv_view_name">
	    Vincular beneficiario judicial
	</div>	


	<div class="dvFormBorder" style="height: 280px; overflow-y: auto;">
		 <form data-dojo-type="dijit.form.Form" id="frmvbj_vincular">
  			    
  			    <input name="empconkey" type="hidden" value="<?PHP echo trim($key);?>" />
				<table class="_tablepadding4">

					<tr class="row_form">
			 			<td> <span class="sp11b"> Concepto </span></td>	
			 			<td> <span class="sp11b"> : </span></td>	
			 			<td >
			 			 	<span class="sp11"> 
			 			  		 <?PHP echo $concepto_info['conc_nombre']; ?>	
			 			  	</span>    	  	
			 			</td>	
						 
			 		</tr>

			        <tr class="row_form">
			         	<td> <span class="sp11b"> Calculo </span></td>	
			 			<td> <span class="sp11b"> : </span></td>	
			 			<td  > 
			 			    <div  style="font-size:11px;"> 		
			 			  	 <?PHP echo $concepto_info['ecuacion']; ?>

			 			  	</div>
			 			</td>
			 		</tr>

			 		<tr class="row_form">  
			 			<td> <span class="sp11b"> Persona </span></td>	
			 			<td> <span class="sp11b"> : </span></td>	
			 			<td>
			 			 	 <select id="selvb_persona"  data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
														 data-dojo-props='name:"trabajador", disabled:false, autoComplete:false, 
														 highlightMatch: "all",  queryExpr:"${0}", 
														 invalidMessage: "La Persona no esta registrada" ' style="width:250px;"  >
			                  
			                </select>


						    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
						      <?PHP 
						         $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
						      ?>
						        <script type="dojo/method" event="onClick" args="evt">
			 								  Persona.Ui.btn_registrar_beneficiario(this,evt);
						        </script>
						        <label class="sp11">
						               Nueva Persona
						        </label>
						   </button>
			 			</td>	
			 			 

 
			 		</tr>
			 		<tr class="row_form">
			 			<td> <span class="sp11b"> Observacion </span></td>	
			 			<td> <span class="sp11b"> : </span></td>	
			 			<td > 
			 			  	 <div dojoType="dijit.form.Textarea" data-dojo-props='name:"descripcion" ' class="formelement-200-11" style="width:380px;"></div> 
			                      	  
			 			</td>	
						 
			 		</tr>
			 		<tr> 
			 			<td colspan="4" align="center"> 

						     
						    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
						      <?PHP 
						         $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
						      ?>
						        <script type="dojo/method" event="onClick" args="evt">
			  						 Conceptos.Ui.btn_vincularbeneficiario_click(this,evt);		
						        </script>
						        <label class="sp12"> 
						             Registrar   
						        </label>
						   </button>

			 			</td>
			 		</tr>
				</table>

	 	</form>
	</div>


</div>