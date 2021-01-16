<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('calendar.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
              Explorar por fechas
          </td>
      </tr>
  </table>
 
</div>

<div id="dvexplorarfechas_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
 

        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:"width: 350px;" '>
        	  

        	   <form data-dojo-type="dijit.form.Form" id="form_filtrarxfechas">

        	   		<input type="hidden" name="explorador" value="1" />


					<div class="dv_busqueda_personalizada"> 	
 		        			 
 									
 						   <span class="sp12b">Visualizar : </span>
		        			 

			        		<select id="dvexplorarfechas_tipoview" 
			        			 	data-dojo-type="dijit.form.Select" 
			        			 	data-dojo-props="name:'tipoview'" class="formelement-100-12" style="width:200px;">
			        		 		
			        		 		<option value="5"> Descansos médicos </option>  
			        		 		<option value="2"> Licencias </option> 
			        		 		<option value="3"> Permisos </option> 
			        		 		<option value="1"> Comisiones de Servicio </option> 
			        		 		<option value="6"> Faltas  </option>  
			        		 		<option value="7"> Tardanzas</option>  
			        		 	<!--	<option value="5"> Contratos </option> -->
			        		</select>	
 
		        	</div>	
 
		        	<table class="_tablepadding4" border="0" width="100%">

		        		<tr>
		        			 <td colspan="6" align="center" > 
		        			  	<span class="sp12b"> 	Parámetros de busqueda	 </span>
		        			 </td>
		        		</tr>

		        		 <tr id="trxf_destino"  class="row_form">
                              <td width="200"> 
                              		<span class="sp12b"> Destino </span>
                              </td>
                              <td width="5"> 
                              		<span class="sp12b"> :</span>
                              </td>
                              <td colspan="4"> 
                             
                              	   <select data-dojo-type="dijit.form.FilteringSelect" 
                              	   			data-dojo-props='name:"ciudad", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"${0}*", invalidMessage: "La Ciudad no esta registrada" ' 
                              	   			style="margin-left:0px; font-size:11px; width: 200px;">
                                       
                                        <option value="0"> No Especificar </option>
                                           <?PHP
                                           foreach($ciudades as $ciudad){
    	                                            echo "<option value='".trim($ciudad['distrito_id'])."-".trim($ciudad['provincia_id'])."-".trim($ciudad['departamento_id'])."'>  ".trim($ciudad['distrito'])." - ".trim($ciudad['provincia'])." - ".trim($ciudad['departamento'])."   </option>";
                                           }
                                         ?>
                                   </select>
                             
                              </td>
                        </tr>

		        		 <tr id="trxf_tipolic" class="row_form" style="display: none;">
                              <td width="200">  <span class="sp12b"> Tipo  </span></td>
                              <td width="5">   <span class="sp12b"> :</span></td>
                              <td colspan="4"> 
								    <select name="tipolic" 
								   			data-dojo-type="dijit.form.Select" 
								   			data-dojo-props='name:"tipolic"' class="formelement-100-12" style="width:200px; font-size:11px;" >
                                         
                                         <option value="0" selected="selected">  No especificar </option>
                                         
                                         <?PHP 

                                         	foreach ($tipo_licencias as $reg)
                                         	{
                                         
  												 echo " <option value='".$reg['tipolic_id']."'> ".$reg['tipolic_nombre']." </option> ";
                                         	}
                                         ?>

                                    </select> 
							 
                              </td>
                        </tr> 
                        
		        		 <tr id="trxf_tipoft"  class="row_form" style="display: none; ">
                              <td width="200">  <span class="sp12b"> Tipo  </span></td>
                              <td width="5"> <span class="sp12b"> :</span></td>
                              <td colspan="4"> 
                                 <select  name="tipoft" 
                                 		  data-dojo-type="dijit.form.Select" 
                                 		  class="formelement-80-12" data-dojo-props='name:"tipoft"' style="width:100px; font-size:11px;" > 
                                      
                                       <option value="1">Tardanza</option>
                                       <option value="2">Falta</option>
                                   </select>
                              </td>
                        </tr>


		        		<tr id="trxf_tipofecha" class="row_form">
		        			 <td width="200">  
		        			 	 <span class="sp12b"> Fecha  </span>
		        			 </td>
		        			 <td width="5"> 
		        			 	 <span class="sp12b"> 	: </span>
		        			 </td>
 								
 							  <td colspan="4">
   							  	   <select  id="selxf_porfecha"    data-dojo-type="dijit.form.Select" data-dojo-props='name:"busquedaporfecha"' 
   							  	   			class="formelement-100-12" style="width:200px; font-size:11px;" >
		                                 <option value="1" selected="selected">  Del documento </option>
		                                 <option value="2" selected="selected">  De registro en el sistema </option>
		                           
		                           </select> 
 							  </td>
		        		</tr>


		        		 <tr id="trxf_rangofechas" class="row_form">

		        		    <td width="200"> 
		        				  <span class="sp12b"  >Desde  </span>
		        			</td>
		        			<td width="5"> 
		        				  <span class="sp12b"> : </span>
		        			</td>
		        			<td width="90"> 
		        				  <input id="calhisdesde" type="text" class="formelement-100-11" style="font-size:11px; width:80px;" data-dojo-type="dijit.form.DateTextBox"
					                     data-dojo-props='type:"text", name:"desde", value:"01/01/1980",
					                       				  constraints:{datePattern:"dd/MM/yyyy", strict:true},
					                                      lang:"es",
					                                      required:true,
					                                      promptMessage:"mm/dd/yyyy",
					                                      invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

					 					   onChange="dijit.byId('calhishasta').constraints.min = this.get('value'); 
					 					   			 dijit.byId('calhishasta').set('value', this.get('value') );" />    
					                      
		        			</td>
		        		
		        		 	<td width="50">
		        		 		 <span class="sp12b" >Hasta: </span> 
		        		 	</td>
		        		 	<td width="5">
		        		 		  <span class="sp12b" > :</span>
		        		 	</td>
		        		 	<td width="90"> 
		        		 		 
		        		 		<input id="calhishasta" type="text" class="formelement-100-11" style="font-size:11px; width:80px;" data-dojo-type="dijit.form.DateTextBox"
		                           		data-dojo-props='type:"text", name:"hasta", value:"01/01/1980",
		                                       constraints:{datePattern:"dd/MM/yyyy", strict:true},
		                                       lang:"es",
		                                       required:true,
		                                       promptMessage:"mm/dd/yyyy",
		                                       invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 

		                          onChange=""   />

		        		 	</td>
		        		 </tr>

		         		 <tr id="trxf_pordni" class="row_form">
		         			 <td width="200"> 
		         			 	 <span class="sp12b"> Trabajador  </span>
		         			 </td>
		         			 <td width="5"> 
		         			 	 <span class="sp12b"> 	: </span>
		         			 </td>
		 						
		 					  <td colspan="4">
		 					  	    <input type="text" data-dojo-type="dijit.form.TextBox" 
		 					  	    		data-dojo-props="name:'dni'" style="font-size:11px; width:70px;" />
		 					  		<span class="sp11b"> (DNI) </span>
		 					  </td>
		         		 </tr>

		        		 <tr id="trxf_poracumulado" class="row_form">
		        			 <td width="200">  
		        			 	 <span class="sp12b"> Agrupar por  </span>
		        			 </td>
		        			 <td width="5"> 
		        			 	 <span class="sp12b"> 	: </span>
		        			 </td>
								
							  <td colspan="4">
							  	   <select id="selxf_agruparpor"  name="agruparpor" 
							  	   			data-dojo-type="dijit.form.Select" data-dojo-props='name:"agruparpor"' 
							  	   			class="formelement-100-12" style="width:130px; font-size:11px;" >
		                                 <option value="0" selected="selected"> No agrupar </option>
		                                 <option value="1"> Trabajador / Año  </option>
		                                 <option value="2"> Trabajador / Mes </option>
		                           </select> 
							  </td>
		        		 </tr>

		         		 <tr id="trxf_acumulado" class="row_form">
		         			 <td width="200"> 
		         			 	 <span class="sp12b"> Total acumulado </span>
		         			 </td>
		         			 <td width="5"> 
		         			 	 <span class="sp12b"> 	: </span>
		         			 </td>
		 						
		 					  <td colspan="4">
		 					  	    <select   data-dojo-type="dijit.form.Select" data-dojo-props='name:"poracumulado"' 
		 					  	    		  class="formelement-100-11" style="width:100px;  font-size:11px;" >
		                                  <option value="1" selected="selected"> Mayor o igual a </option>
		                                  <option value="2"> Menor o igual a </option>
		                            </select> 

		                            <input type="text" data-dojo-type="dijit.form.TextBox" 
		                            		 data-dojo-props="name:'valoracumulado'" style="font-size:11px; width:60px;" value="0" />
		 					  </td>
		         		 </tr>

		        	</table>	

	        	</form>

	        	<div style="margin:6px 0 0 6px " align="center"> 
	        		   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	                          <?PHP 
	                             $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
	                          ?>
	                             <label class="lbl11">Realizar Busqueda </label>
	                              <script type="dojo/method" event="onClick" args="evt">
	                                   	Persona.Ui.btn_explorarxfechas_click(this,evt);
	                            </script>
	                   </button>
	        	</div>

        </div>

        <div id="dvexpfec_panelcenter"   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center" '>


        		<div class="dv_busqueda_personalizada">
        		  	
        		  	<div> 
        		 		<span class="sp12b" id="spn_explorarfechas_titulo"> <?PHP echo $header['titulo'] ?></span>
        		  	</div>		
        		 
        		</div>

        		<input type="hidden" id="hdexplorarxfechas_tipo" value="<?PHP echo $tipo; ?>" />
        		 
        		<div id="dv_explorarxfechas" class="<?PHP echo $class_table; ?>"></div>
 


        		<div align="right" style="margin:8px 15px 0 0;"> 
        			
<!-- 
        			 <button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" data-dojo-props='disabled: true ' > 
        			        <?PHP 
        			           $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
        			          ?>
        			       <label class="lbl10">Exportar</label>
        			       <script type="dojo/method" event="onClick" args="evt">
        			             
        			       </script>
        			 </button> -->

        		     <button id="btn_explorarfechas_verdetalle"  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" data-dojo-props='disabled: true '> 
        		            <?PHP 
        		               $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
        		              ?>
        		           <label class="lbl10">Ver Detalle</label>
        		           <script type="dojo/method" event="onClick" args="evt">
        		                              Persona.Ui.btn_exfverdetalle_click(this,evt);   
        		           </script>
        		     </button>

        		</div>



        </div>
<!--
        <div id="dvexpfec_panelderecha"   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"right",  style:"width: 400px;" '>

        </div> -->
</div>