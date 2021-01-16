<div class="window_container">

  <div id="dvViewName" class="dv_view_name">
	       
	    <table class="_tablepadding2" border="0">
 		   <tr> 
	           <td> 
	                <?PHP 
	                          $this->resources->getImage('application_edit.png',array('width' => '22', 'height' => '22'));
	                      ?>
	           </td>

	          <td>
	               <span class="sp12b">  
	               		Configurar parámetros de exportación a SUNAT <?PHP  echo ($modo == 'trabajador') ? ' ( TRABAJADOR ) ' : ' ( TIPO DE PLANILLA )'  ?> 
	           		</span>
	           </td>
	       </tr>
	   </table>
  </div>


  <div class="dv_busqueda_personalizada"> 

  	<?PHP 
  		if($modo == 'tipoplanilla')
  		{ 
  	?>
	  	 <table class="_tablepadding4"> 
	  	 	 <tr>
	  	 	 	<td> 
	  	 	 	    <span class="sp12b"> Tipo de planilla </span>
	  	 	 	</td>
	  	 	 	<td> 
	  	 	 		<span class="sp12b"> : </span>
	  	 	 	</td>
	  	 	 	<td>
	  	 	 		<span class="sp12b"> <?PHP echo $view_info['plati_nombre']; ?> </span>		
	  	 	 	</td>
	  	 	 </tr>
	  	 </table>
 <?PHP 
  	}
  	else
  	{
?>

		<table class="_tablepadding4"> 
			 <tr>
			 	<td> 
			 	    <span class="sp12b"> Trabajador </span>
			 	</td>
			 	<td> 
			 		<span class="sp12b"> : </span>
			 	</td>
			 	<td>
			 		<span class="sp12b"> <?PHP echo $view_info['indiv_appaterno'].' '.$view_info['indiv_apmaterno'].' '.$view_info['indiv_nombres'].' (DNI:'.$view_info['indiv_dni'].') '; ?> </span>		
			 	</td>
			 </tr>
		</table>
	

<?PHP 
  	}
 ?>
  </div>


 <form dojoType="dijit.form.Form" id="formsunat_parametros"> 

 	<input type="hidden" name="modoformulario" value="<?PHP echo $modo; ?>" />

 <div class="dvFormBorder"> 

	<table class="_tablepadding4" width="100%">


 		<tr class="row_form">
 		 	 <td width="150"> 
 		 	 	<span class="sp12b"> Tipo de trabajador </span>	
 		 	 </td>
 		 	 <td width="5">
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>
 		 	 <td>
                <select data-dojo-type="dijit.form.FilteringSelect" 
                        data-dojo-props='name:"tipotrabajador", 
                        				 disabled:false, 
                        				 autoComplete:false, 
                        				 highlightMatch: "all",  
                                         <?PHP  echo ($modo == 'trabajador') ? ' disabled:true, ' : ''  ?> 
                                         queryExpr:"*${0}*", 
                                         invalidMessage: "Tipo de trabajador no registrado" '

                        style="margin-left:6px; font-size:11px; width: 320px;">
                   
                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
	                 foreach($catalogos['tipotrabajador'] as $reg)
	                 {
	                      echo "<option value='".trim($reg['codigo'])."' "; 

	                      if( trim($reg['codigo']) == trim($parametros_sunat['tipotrabajador']) )
	                      {
	                      	  echo ' selected="selected" '; 
	                      }

	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
	                 }
		            
		            ?>
               </select>

               <?PHP  
               		if($modo == 'trabajador') echo ' <input type="hidden" name="tipotrabajador" value="'.trim($parametros_sunat["tipotrabajador"]).'" />';
                ?> 
 		 	 </td>

 		</tr>	

 		<tr class="row_form">
 		 	 <td> 
 		 	 	<span class="sp12b"> Regimen laboral </span>	
 		 	 </td>
 		 	 <td>
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>

 		 	 <td>
               <select data-dojo-type="dijit.form.FilteringSelect" 
                                     data-dojo-props='name:"regimenlaboral", 
                                     				 disabled:false, 
                                     				 autoComplete:false, 
                                     				 highlightMatch: "all",  
                                     				 <?PHP  echo ($modo == 'trabajador') ? ' disabled:true, ' : ''  ?> 
                                                      queryExpr:"*${0}*", 
                                                      invalidMessage: "Regimen laboral no registrado" ' 
                                     style="margin-left:6px; font-size:11px; width: 320px;">
                   
                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
	         	    foreach($catalogos['regimenlaboral'] as $reg)
	                 {
                     	  echo "<option value='".trim($reg['codigo'])."' "; 

 	                      if( trim($reg['codigo']) == trim($parametros_sunat['regimenlaboral']) )
 	                      {
 	                      	  echo ' selected="selected" '; 
 	                      }

 	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
	                 }
		            
		            ?>
               </select>
               <?PHP  
               		if($modo == 'trabajador') echo ' <input type="hidden" name="regimenlaboral" value="'.trim($parametros_sunat["regimenlaboral"]).'" />';
                ?> 
 		 	 </td>
 		</tr>	
 		<tr class="row_form">
 		 	 <td> 
 		 	 	<span class="sp12b"> Categoria Ocupacional</span>	
 		 	 </td>
 		 	 <td>
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>

 		 	 <td>
               <select data-dojo-type="dijit.form.FilteringSelect" 
                                                  data-dojo-props='name:"categoriaocupacional", 
                                                  				 disabled:false, 
                                                  				 autoComplete:false, 
                                                  				 highlightMatch: "all",  
                                                  				 <?PHP  echo ($modo == 'trabajador') ? ' disabled:true, ' : ''  ?> 
                                                                 queryExpr:"*${0}*", 
                                                                 invalidMessage: "Regimen laboral no registrado" ' 
                                                  style="margin-left:6px; font-size:11px; width: 320px;">
                   
                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
	                 foreach($catalogos['categoria_ocupacional'] as $reg)
	                 {
                      	  echo "<option value='".trim($reg['codigo'])."' "; 

  	                      if( trim($reg['codigo']) == trim($parametros_sunat['catagoriaocupacional']) )
  	                      {
  	                      	  echo ' selected="selected" '; 
  	                      }

  	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
	                 }
		            
		            ?>
               </select>
               <?PHP  
               		if($modo == 'trabajador') echo ' <input type="hidden" name="categoriaocupacional" value="'.trim($parametros_sunat["catagoriaocupacional"]).'" />';
                ?> 
 		 	 </td>

 		</tr>
 		<tr class="row_form">
 		 	 <td> 
 		 	 	<span class="sp12b">Nivel Educativo</span>	
 		 	 </td>
 		 	 <td>
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>

 		 	 <td>
          		   <select data-dojo-type="dijit.form.FilteringSelect" 
                                               data-dojo-props='name:"niveleducativo", 
                                               				 disabled:false, 
                                               				 autoComplete:false, 
                                               				 highlightMatch: "all",  
                                                              queryExpr:"*${0}*", 
                                                              invalidMessage: "Nivel educativo no registrado" ' 
                                               style="margin-left:6px; font-size:11px; width: 320px;">


                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
		               foreach($catalogos['niveleducativo'] as $reg)
		               {
	                    	  echo "<option value='".trim($reg['codigo'])."' "; 

		                      if( trim($reg['codigo']) == trim($parametros_sunat['niveleducativo']) )
		                      {
		                      	  echo ' selected="selected" '; 
		                      }

		                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
		               }

		            ?>
               </select>
 		 	 </td>

 		</tr>		
 		<tr class="row_form">
 		 	 <td> 
 		 	 	<span class="sp12b">Ocupación</span>	
 		 	 </td>
 		 	 <td>
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>

 		 	 <td>
                	<select data-dojo-type="dijit.form.FilteringSelect" 
                	                                   data-dojo-props='name:"ocupacion", 
                	                                   				 disabled:false, 
                	                                   				 autoComplete:false, 
                	                                   				 highlightMatch: "all",  
                	                                                  queryExpr:"*${0}*", 
                	                                                  invalidMessage: "Ocupación no registrada" ' 
                	                                   style="margin-left:6px; font-size:11px; width: 320px;">
                   
                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
	                foreach($catalogos['ocupaciones'] as $reg)
	                {
                      	  echo "<option value='".trim($reg['codigo'])."' "; 

  	                      if( trim($reg['codigo']) == trim($parametros_sunat['ocupacion']) )
  	                      {
  	                      	  echo ' selected="selected" '; 
  	                      }

  	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
	                }
		            
		            ?>
               </select>
 		 	 </td>

 		</tr>
 		<tr class="row_form">
 		 	 <td> 
 		 	 	<span class="sp12b">Tipo de Contrato</span>	
 		 	 </td>
 		 	 <td>
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>

 		 	 <td>
                  <select data-dojo-type="dijit.form.FilteringSelect" 
                                                     data-dojo-props='name:"tipocontrato", 
                                                     				 disabled:false, 
                                                     				 autoComplete:false, 
                                                     				 highlightMatch: "all",  
                                                                    queryExpr:"*${0}*", 
                                                                    invalidMessage: "Tipo de contrato no registrado" ' 
                                                     style="margin-left:6px; font-size:11px; width: 320px;">
                   
                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
	                foreach($catalogos['tipo_contrato'] as $reg)
   	                {
                     	  echo "<option value='".trim($reg['codigo'])."' "; 

 	                      if( trim($reg['codigo']) == trim($parametros_sunat['tipocontrato']) )
 	                      {
 	                      	  echo ' selected="selected" '; 
 	                      }

 	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
   	                }
		            
		            ?>
               </select>
 		 	 </td>

 		</tr>

 		<tr class="row_form">
 		 	 <td> 
 		 	 	<span class="sp12b">Tipo de Pago y Periocidad</span>	
 		 	 </td>
 		 	 <td>
 		 	 	<span class="sp12b">
 		 	 		:
 		 	 	</span>	
 		 	 </td>

 		 	 <td>
                <select data-dojo-type="dijit.form.FilteringSelect" 
		                 data-dojo-props='name:"tipopago", 
		                 				 disabled:false, 
		                 				 autoComplete:false, 
		                 				 highlightMatch: "all",  
		                                queryExpr:"*${0}*", 
		                                invalidMessage: "Tipo de pago no registrado" ' 
		                 style="margin-left:6px; font-size:11px; width: 100px;">
                   
                    <option value="0"> No Especificado </option>
                  	<?PHP
	                
		               foreach($catalogos['tipopago'] as $reg)
		               {
		                     echo "<option value='".trim($reg['codigo'])."' "; 

	   	                      if( trim($reg['codigo']) == trim($parametros_sunat['tipopago']) )
	   	                      {
	   	                      	  echo ' selected="selected" '; 
	   	                      }

	   	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
		               }

		            ?>
               </select>


               <select data-dojo-type="dijit.form.FilteringSelect" 
                                                data-dojo-props='name:"periocidadpago", 
                                                				 disabled:false, 
                                                				 autoComplete:false, 
                                                				 highlightMatch: "all",  
                                                               queryExpr:"*${0}*", 
                                                               invalidMessage: "Opcion no valida" ' 
                                                style="margin-left:6px; font-size:11px; width: 100px;">
                  
                   <option value="0"> No Especificado </option>
                 	<?PHP
	                
	                 foreach($catalogos['periocidad_remuneracion'] as $reg)
	                 {
	                      echo "<option value='".trim($reg['codigo'])."' "; 

	                      if( trim($reg['codigo']) == trim($parametros_sunat['periocidadpago']) )
	                      {
	                      	  echo ' selected="selected" '; 
	                      }

	                      echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
		             }
		            
		            ?>
              </select>

              <span class="sp12b">Rem. básica : </span>	

              <input type="text" data-dojo-type="dijit.form.TextBox"
              					data-dojo-props="name:'rembasica' "
              				    class="formelement-80-11" style="margin:0px 0px 0px 6px;" value="<?PHP echo trim($parametros_sunat['rembasica']); ?>" />


 		 	 </td>

 		</tr>
 

		<tr class="row_form">
		 	 <td> 
		 	 	<span class="sp12b"> Establecimiento </span>	
		 	 </td>
		 	 <td>
		 	 	<span class="sp12b">
		 	 		:
		 	 	</span>	
		 	 </td>

		 	 <td> 	
		 	 		<input type="text"  data-dojo-type="dijit.form.TextBox"
		 	 							data-dojo-props="name:'establecimientoruc', 
		 	 											 readonly:true "
		 	 						    class="formelement-80-11" 
		 	 						    style="margin:0px 0px 0px 6px;" 
		 	 						    value="<?PHP echo RUC_INSTITUCION; ?>" />


                	
    	               <select data-dojo-type="dijit.form.FilteringSelect" 
    	                                                data-dojo-props='name:"establecimiento", 
    	                                                				 disabled:false, 
    	                                                				 autoComplete:false, 
    	                                                				 highlightMatch: "all",  
    	                                                               queryExpr:"*${0}*", 
    	                                                               invalidMessage: "Opcion no valida" ' 
    	                                                style="margin-left:6px; font-size:11px; width: 180px;">
    	                  
    	              
    	                 	<?PHP
    		                
    		                 foreach($catalogos['establecimientos'] as $reg)
    		                 {
    		                     echo "<option value='".trim($reg['codigo'])."' "; 

    		                     if( trim($reg['codigo']) == trim($parametros_sunat['establecimiento']) )
    		                     {
    		                     	  echo ' selected="selected" '; 
    		                     }

    		                     echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
    			             }
    			            
    			            ?>
    	              </select>
  			 </td>
		</tr>

		<tr class="row_form">
		 	 <td> 
		 	 	<span class="sp12b"> Jornada Laboral </span>	
		 	 </td>
		 	 <td>
		 	 	<span class="sp12b">
		 	 		:
		 	 	</span>	
		 	 </td>

		 	 <td> 
                 <select  data-dojo-type="dijit.form.MultiSelect"  data-dojo-props="name:'jornadalaboral[]'" multiple="true" class="formelement-150-11"   size="3"  > 
                     	<option value="jormax"  <?PHP if(trim($parametros_sunat['jornadamaxima']) == '1')  echo ' selected="selected" '; ?> > Jornada de trabajo máxima </option>
                     	<option value="joratip" <?PHP if(trim($parametros_sunat['jornadaatipica']) == '1')  echo ' selected="selected" '; ?> > Jornada átipica o acumulativa </option>
                     	<option value="jorhornoc" <?PHP if(trim($parametros_sunat['jornadanocturno']) == '1')  echo ' selected="selected" '; ?> > Trabajo en horario nocturno </option>
                 </select> 
  			 </td>
		</tr>

		<tr class="row_form">
		 	 <td> 
		 	 	<span class="sp12b"> Situación especial </span>	
		 	 </td>
		 	 <td>
		 	 	<span class="sp12b">
		 	 		:
		 	 	</span>	
		 	 </td>

		 	 <td> 
                	<select data-dojo-type="dijit.form.Select" data-dojo-props="name:'situacionespecial'" style="width:200px; font-size:11px;"> 
	                     	<option value="0" <?PHP if(trim($parametros_sunat['situacionespecial']) == '0')  echo ' selected="selected" '; ?> > Ninguna </option>
                			<option value="1" <?PHP if(trim($parametros_sunat['situacionespecial']) == '1')  echo ' selected="selected" '; ?> > Trabajador de dirección </option>
	                     	<option value="2" <?PHP if(trim($parametros_sunat['situacionespecial']) == '2')  echo ' selected="selected" '; ?> > Trabajador de confianza </option>
                	</select>
  			 </td>
		</tr>

 		<tr class="row_form">
 		 	 <td> 
 		 	 		<span class="sp12b"> Sindicalizado </span>	
 		 	     	
              </td>
              <td>
              	<span class="sp12b">
              		:
              	</span>	
              </td>

              <td>	
		 	     	<select data-dojo-type="dijit.form.Select" data-dojo-props="name:'sindicalizado'" style="width:50px; font-size:11px;"> 
                			<option value="0" <?PHP if(trim($parametros_sunat['sindicalizado']) == '0')  echo ' selected="selected" '; ?>  > No </option>
                			<option value="1" <?PHP if(trim($parametros_sunat['sindicalizado']) == '1')  echo ' selected="selected" '; ?> > Si </option>
                	</select>
                 
                 	<span class="sp12b"> Discapacitado : </span>	
                 	<select data-dojo-type="dijit.form.Select" data-dojo-props="name:'discapacitado'" style="width:50px; font-size:11px;"> 
                 			<option value="0" <?PHP if(trim($parametros_sunat['discapacitado']) == '0')  echo ' selected="selected" '; ?> > No </option>
                 			<option value="1" <?PHP if(trim($parametros_sunat['discapacitado']) == '1')  echo ' selected="selected" '; ?> > Si </option>
                 	</select>

                 	<span class="sp12b"> Aporta SCTR : </span>	
                 	<select data-dojo-type="dijit.form.Select" data-dojo-props="name:'sctr'" style="width:50px; font-size:11px;"> 
                 			<option value="0" <?PHP if(trim($parametros_sunat['sctr']) == '0')  echo ' selected="selected" '; ?> > No </option>
                 			<option value="1" <?PHP if(trim($parametros_sunat['sctr']) == '1')  echo ' selected="selected" '; ?> > Si </option>
                 	</select>

 		 	 </td>

 		</tr>		

		 

		<tr class="row_form">
		 	 <td> 
		 	 	<span class="sp12b"> Regimen de Salud </span>	
		 	 </td>
		 	 <td>
		 	 	<span class="sp12b">
		 	 		:
		 	 	</span>	
		 	 </td>

		 	 <td> 
	            
	         <select data-dojo-type="dijit.form.FilteringSelect" 
	                                            data-dojo-props='name:"regimensalud", 
	                                            				 disabled:false, 
	                                            				 autoComplete:false, 
	                                            				 highlightMatch: "all",  
	                                                           queryExpr:"*${0}*", 
	                                                           invalidMessage: "Tipo de contrato no registrado" ' 
	                                            style="margin-left:6px; font-size:11px; width: 320px;">
	                <option value="0"> No Especificado </option>

	            			<?PHP
	            				                
			                 foreach($catalogos['regimendesalud'] as $reg)
			                 {
			                     echo "<option value='".trim($reg['codigo'])."' "; 

			                     if( trim($reg['codigo']) == trim($parametros_sunat['regimensalud']) )
			                     {
			                     	  echo ' selected="selected" '; 
			                     }

			                     echo " >".trim($reg['codigo'])." - ".trim($reg['descripcion'])."</option>";
				             }
				            
				            ?>
	            	</select>
				 </td>
		</tr>
 		 
 		 <?PHP 
 		 	if($modo == 'tipoplanilla')
 		 	{

 		 ?>

 		 <tr class="dv_busqueda_personalizada"> 

 		 	  <td> 
		 	 	<span class="sp12b"> Aplicar a </span>	
		 	 </td>
		 	 <td>
		 	 	<span class="sp12b">
		 	 		:
		 	 	</span>	
		 	 </td>

		 	 <td> 
 		 	 	 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'modo_aplicar'" style="width:280px; font-size:11px;"> 
 		 	 	 		<option value="1"> Todos los trabajadores de este regimen</option>
 		 	 	 		<option value="2"> Excluir trabajadores con datos personalizados </option>
 		 	 	 </select>

 		 	 </td>
 		 </tr>

 		 <?PHP 
 		  	}
 		  	else
 		  	{
 		  ?>
	  			<tr class="row_form">
	  			 	 <td> 
	  			 	 	<span class="sp12b"> RUC </span>	
	  			 	 </td>
	  			 	 <td>
	  			 	 	<span class="sp12b">
	  			 	 		:
	  			 	 	</span>	
	  			 	 </td>

	  			 	 <td> 
 						<input type="text"  data-dojo-type="dijit.form.TextBox"
 											data-dojo-props="name:'ruc' "
 										    class="formelement-80-11" style="margin:0px 0px 0px 6px;" 
 										    value="<?PHP echo trim($parametros_sunat['ruc']); ?>" />

	  			     </td>
	  			</tr>

	  			 

 		  <?PHP 		
 		  	}
 		 ?>

 		 <tr> 

 		 	 <td colspan="3" align="center">

 		 	 		 <?PHP  $key = ($modo == 'trabajador') ? 'indiv_key' : 'plati_key';  ?> 
 		 	  		
 		 	  		<input name="view" type="hidden" class="hdkeyview" value="<?PHP echo trim($view_info[$key]); ?>" /> 

 		 	  		<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
 		 	  		   <?PHP 
 		 	  		      $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
 		 	  		   ?>
 		 	  		     <script type="dojo/method" event="onClick" args="evt">
 		 	  		     		

 		 	  		     		if(confirm('Realmente desea guardar y aplicar los cambios ?'))
 		 	  		     		{
 									var codigo_e = dojo.query('.hdkeyview',this.parentNode)[0].value;
	 		 	  		     		var form = dojo.formToObject('formsunat_parametros');
	 		 	  		     		var data = form;

	 		 	  		     		data.view = codigo_e;

	 		 	  		     		if(Tipoplanilla._M.actualizar_parametros_sunat.process(data)) 
	 		 	  		     		{
	 		 	  		     				Tipoplanilla._V.configuracion_sunat.close();
	 		 	  		     		}
	 							}

 		 	  		     </script>
 		 	  		     <label class="sp11">
 		 	  		     		 Guardar y Aplicar
 		 	  		     </label>
 		 	  		</button>

 		 	 </td>
 		 </tr>


	</table>

			<input type="hidden" name="situacion" value="1" />
	</div>

</form>


<!-- 	<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	   <?PHP 
	      $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
	   ?>
	     <script type="dojo/method" event="onClick" args="evt">
	     		
	     	 
	     </script>
	     <label class="sp11">
	     		 Guardar
	     </label>
	</button>

 -->






</div>