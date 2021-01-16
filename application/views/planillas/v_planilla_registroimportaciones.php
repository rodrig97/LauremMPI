 
<div class="window_container">

  

	<div id="table_planilla_importaciones"> </div>

	<div style="margin: 5px 5px 5px 5px; "> 
<!-- 
			<button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="disabled: false" > 
	              <?PHP 
	                 $this->resources->getImage('window_search.png',array('width' => '14', 'height' => '14'));
	              ?>
	                <script type="dojo/method" event="onClick" args="evt">
	                    /*  Asistencias.view_hoja(Asistencias.Cache.view_hoja_preview_importacion, dojo.byId('hdviewplanilla_id').value , 2);*/

	                 	  var selection =  Planillas.Ui.Grids.planilla_importaciones.selection;   
	                 	  var codigo_e = '';   
	                 	  
	                 	  for(var i in selection)
	                 	  { 
	                 	      if(selection[i] === true)
	                 	      {
	                 	        codigo_e +=i; 
	                 	        break;
	                 	      }
	                 	  }

	                 	  if(codigo_e != '')      
	                 	  {
	                 	       console.log(codigo_e);
	                 	  	  	var data = {}

	                 	  	  	data.detalle_importacion = '1';
	                 	  	  	data.importacion = codigo_e;

	                 	  	    Planillas._V.detalle_hoja_importada.load(data);

	                 	  }
	                 	  else
	                 	  { 
	                 	      alert('Debe seleccionar un registro');
	                 	  }


	                </script>

	                <label class="sp11">
	                      Visualizar Asistencia   
	                </label>
			</button> -->
			
			<?PHP 

				if($planilla_estado == ESTADOPLANILLA_ELABORADA ){ 
			?>

			<button data-dojo-type="dijit.form.Button" class="dojobtnfs_12"  data-dojo-props="" > 
			       <?PHP 
			          $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
			       ?>
			         <span class="sp11">Revertir importación </span>
			         
			         <script type="dojo/method" event="onClick" args="evt"> 

			         	 var selection =  Planillas.Ui.Grids.planilla_importaciones.selection;   
			         	 var codigo_e = '';   
			         	 
			         	 for(var i in selection)
			         	 { 
			         	     if(selection[i] === true)
			         	     {
			         	       codigo_e +=i; 
			         	       break;
			         	     }
			         	 }

			         	 if(codigo_e != '')      
			         	 {
			         	    
			         	 	  	var data = {}
 
			         	 	  	data.view = codigo_e;

	     	 	             	 if(confirm('Realmente desea revertir la importacion, al realizar esta operacion el registro del tareo podra ser modificado e importado nuevamente'))
	     	 	             	 {
	     	 	    	             if(Asistencias._M.revertir_importacion.process(data))
	     	 	    	             {
	     	 	    	             	  Planillas.Ui.Grids.planilla_importaciones.refresh();
	     	 	    	             }
	     	 	    	         	 	
	     	 	             	 }

			         	 }
			         	 else
			         	 { 
			         	     alert('Debe seleccionar un registro');
			         	 }

			         	

			         </script>
			</button>

			<?PHP 
				}
			?>

	</div>

</div>