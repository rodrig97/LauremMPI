 
	<div dojoType="dijit.layout.TabContainer" splitter="true" 
		data-dojo-props='region:"left", tabPosition:"bottom" '
	 	 style="width:99%;">


	    <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Hoja de asistencia </span>" >                      
	            
	           <?PHP  

   			        $this->load->view('planillas/v_asistencias_hojacalendario', array('calendario' => $calendario, 
   		                                                                              'hoja_info' => $hoja_info, 
   		                                                                              'rs_estados_dia' => $rs_estados_dia,
   		                                                                              'acceso_escalafon' => $acceso_escalafon,
   		                                                                              'params' => $params ));
	           		       
	            ?> 

	    </div>

	    <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Resumen </span>">                      

	    	  <textarea id="estructura_hojaresumen" style="display:none;"> <?PHP  echo json_encode($estructura_tabla_resumen); ?></textarea>

	    	  <div class="dv_busqueda_personalizada_pa2" style="width:880px;">

	    	  	    <form data-dojo-type="dijit.form.Form" id="form_asistencia_hojaresumen_filtro"> 
			
						<table class="_tablepadding4" width="300">
		 					  <tr class="tr_row">
		 					      <td width="60"> <span class="sp11b"> Trabajador  </span> </td>
		 					      <td width="5"> <span class="sp11b"> : </span> </td>
		 					      <td width="235">  
		 					           <input type="text" data-dojo-type="dijit.form.TextBox" name="trabajador" value=""  class="formelement-200-11"  />
		 					      </td> 

	 					          <td> <span class="sp11b"> Categoria  </span> </td>
	 					          <td> <span class="sp11b"> : </span> </td>
	 					          <td>  
	 					                <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'categoria'"  style="font-size:11px; width:180px;">
	 					                      
	 					                      <option value="0"> No Especificar </option>
	 					                     <?PHP
	 					                         foreach($categorias as $reg)
	 					                         {  
	 					                     
	 					                              echo "<option value='".trim($reg['platica_id'])."'> ".trim($reg['platica_nombre'])."  </option>";
	 					                     
	 					                         }
	 					                     ?> 
	 					                    
	 					                </select>
	 					          </td>
		 					      
		 					      <?PHP 

		 					       if($config['grupo_trabajadores'] == '1')
		 					       {

		 					      ?>
		 					  
	 					          <td> <span class="sp11b"> Grupo  </span> </td>
	 					          <td> <span class="sp11b"> : </span> </td>
	 					          <td>  
	 					                 <select data-dojo-type="dijit.form.FilteringSelect" 
	 					                         data-dojo-props='name:"grupo", 
	 					                                          autoComplete:false, 
	 					                                          highlightMatch: "all",  
	 					                                          queryExpr:"*${0}*", 
	 					                                          invalidMessage: "El grupo no esta registrado" ' 
	 					                         class="formelement-180-11">

	 					                     <option value="0"> No Especificar </option>
	 					                     <?PHP
	 					                        foreach($grupos as $grupo)
	 					                        {
	 					                             echo "<option value='".trim($grupo['hoagru_id'])."'>  ".trim($grupo['hoagru_nombre'])."</option>";
	 					                        }
	 					                     ?>
	 					                </select>
	 					          </td>
		 					    
		 					      <?PHP 
		 					         }
		 					      ?>

		 					      <td>


		 					      		 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		 					      		   <?PHP 
		 					      		      $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
		 					      		   ?>
		 					      		     <script type="dojo/method" event="onClick" args="evt">
		 					      		           Planillas.Ui.Grids.asistencias_hojaresumen_tabla.refresh(); 
		 					      		      
		 					      		     </script>
		 					      		     <label class="sp11">
		 					      		            Filtrar
		 					      		     </label>
		 					      		</button>

		 					      </td>
		 					  </tr>
						</table> 

					</form>
	    	  </div>

	    	  <div id="asistencias_table_hojaresumen" class="grid"> </div>



	    </div>

	</div>
 