 
	<div dojoType="dijit.layout.TabContainer" splitter="true" 
		data-dojo-props='region:"left", tabPosition:"bottom" '
	 	 style="width:99%;">


	    <div  dojoType="dijit.layout.ContentPane" data-dojo-props='style:"padding:0px 0px 0px 0px; "  ' title="<span class='titletabname'> Hoja de asistencia </span>" >                      
	            

	    	<div  data-dojo-type="dijit.layout.BorderContainer" 
	    		  data-dojo-props='design:"headline", liveSplitters:true, style:"padding:0px 0px 0px 0px;" ' style="width:100%; height:100%; padding:1px 1px 1px 1px;">

	            <div id="dv_registroasis_calendario" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center" ' >
	              
	                  
	           <?PHP  

   			        $this->load->view('planillas/v_asistencias_registroasistencia_calendario', array('calendario' => $calendario,  
			   		                                                                                 'rs_estados_dia' => $rs_estados_dia,
			   		                                                                                 'params' => $params ));
	            ?> 

	      	  </div>

	            <div id="dv_registroasis_paginador"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"bottom" ' style="height:20px;"  >
	              		 
	              		<?PHP 
	              		 	$total_registros = $total_registros[0]['total'];
	              		?>

	              		<div style="float:left;">
	              			<span class="sp11b"> Se encontraron un total de <?PHP echo $total_registros; ?> registros. Paginas: </span>
	              		</div>

						<div style="float:left;"> 

							<ul class="paginador"> 
	               
		            		<?PHP 
		            		 
	 
		            		 $total_paginas = floor(($total_registros / $params['limit']));

		            		 $t = $total_registros % $params['limit'];

		            		 if($t > 0)
		            		 {
		            		 	$total_paginas++;
		            		 }

		            		 $ultima_pagina = $total_paginas;

		            		/* if($total_paginas > 10)
		            		 {
	 						 	$total_counter = $paginaactual + 10;
		            		 }
	 						  

	 						 if($total_counter >= $total_paginas )
	 						 {
	 						 	$total_counter = $total_paginas - 1;
	 						 }*/

	 					//	 echo " <li class='pagina'> <input type='hidden' value='1' class='paginapointer' /> 1 </li>";

		            		 for ($i=1; $i<= $ultima_pagina ; $i++)
		            		 { 
		            		 
		            		 	 echo " <li class='pagina' > <input type='hidden' value='".$i."' class='paginapointer' /> "; 

		            		 	 if($i == $paginaactual)
		            		 	 {
		            		 	 	echo " <span style='color: #990000; font-size:12px; font-weight:bold;'> $i </span>";
		            		 	 }	
		            		 	 else
		            		 	 {
		            		 	 	echo $i;
		            		 	 }

		            		 	 echo "</li>";
		            		 
		            		 }

		            	//	 echo " <li  class='pagina'>".$ultima_pagina."</li>";
	 

		            		?>

		            		</ul>	    

		            		<?PHP if($total_paginas == 0) echo ' <span class="sp11b"> [Ninguna] </span>'; ?>
	            		</div>
 


	            </div>

	        </div>

	    </div>


	    <!-- 
	    <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Resumen </span>">                      

	    	  <textarea id="estructura_hojaresumen" style="display:none;"> <?PHP  echo json_encode($estructura_tabla_resumen); ?></textarea>

	    	  <div class="dv_busqueda_personalizada_pa2" style="width:800px;">

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
 			-->
	</div>
 