

<div class="window_container"> 

	<div class="dv_celeste_padding2">	


		<div data-dojo-type="dijit.form.Form" id="form_boletaindividual"> 

			<table class="_tablepadding2">
				<tr>
					<td> 
						<span class="sp12b"> 
								DNI 
						</span>
					</td>
					<td> 
						<span class="sp12b">:</span>
					</td>			
					<td>
						<input type="textbox" 
							   data-dojo-type="dijit.form.TextBox"  
							   class="formelement-100-12" 
							   data-dojo-props="name:'dni'"
							   value="<?PHP echo trim($dni); ?>" />
					</td>
  

					<td>

						<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
						  <?PHP 
						     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
						  ?>
						   <script type="dojo/method" event="onClick" args="evt">
						      		if( Planillas.Ui.Grids.boletas_individuales != null)
						      		{

						      		
						      		     Planillas.Ui.Grids.boletas_individuales.refresh();
						      		     
						      		}          

						   </script>
						   <label class="sp11">
						                  Buscar                          
						   </label>
						</button>

					</td>
				</tr>
			</table>

		</div>

	</div>
 
	<div id="dv_planilla_boletasindividuales">

	</div>

	<div style="margin: 5px 0px 0px 5px;">

		<form target="_blank" id="form_boletaspago_view" action="impresiones/boletas_de_pago" method="post" >
 				
 				<input type="hidden" id="hdboletaspago_boletas" name="boletas" value=""/>

 				<span class="sp11"> Número de copias por hoja</span>
				<select name="copias_por_hoja" data-dojo-type="dijit.form.Select" data-dojo-props="name:'copias_por_hoja'" style="font-size: 11px; width: 60px;">
					 <option value="1"> 1 </option>
					 <option value="2"> 2 </option>
				</select>
		</form>

		<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		  <?PHP 
		     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
		  ?>
		   <script type="dojo/method" event="onClick" args="evt">
		     
		         
	        	    var codigo_e = '';   
	        	    var selection =  Planillas.Ui.Grids.boletas_individuales.selection;   
	        	     

	        	    for(var i in selection)
	        	    {  
	        	        if(selection[i] === true)
	        	        {
	        	          codigo_e +="|"+i; 
 
	        	        }
	        	    } 

	        	    if(codigo_e != '')      
	        	    { 	
	        	        dojo.byId('hdboletaspago_boletas').value = codigo_e;
 
	        	        dojo.byId('form_boletaspago_view').submit();
	        	    }
	        	    else
	        	    { 
	        	        alert('Debe seleccionar un registro');
	        	    } 

		   </script>
		   <label class="sp11">
		                 Visualizar Boletas                          
		   </label>
		</button>

		
		<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		  <?PHP 
		     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
		  ?>
		   <script type="dojo/method" event="onClick" args="evt">
		     	
		     	var codigo_e = '';   
		   		var selection =  Planillas.Ui.Grids.boletas_individuales.selection;   
 
		    	for(var i in selection)
		    	{  
		    	    if(selection[i] === true)
		    	    {
		    	      codigo_e +=i; 
		    	
		    	    }
		    	} 

		    	if(Planillas.Cache.ver_planilla_desde_boleta != null && Planillas.Cache.ver_planilla_desde_boleta != '' && codigo_e != '')
		    	{
		    		 Planillas.fn_load_planilla({'codigo' : Planillas.Cache.ver_planilla_desde_boleta  });
		    	}
		    	else
		    	{
		    		  alert('Debe especificar un registro');
		    	}
  			
		   </script>
		   <label class="sp11">
		                  Ir a Planilla                       
		   </label>
		</button>
 
	</div>


</div>