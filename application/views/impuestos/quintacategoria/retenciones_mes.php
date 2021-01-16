<div class="window_container">
	
	<!-- <div id="dvViewName" class="dv_view_name">
	      
	   <table class="_tablepadding2" border="0">

	      <tr> 
	          <td> 
	               <?PHP 
	                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
	                     ?>
	          </td>

	         <td>
	              Registro de retenciones del mes
	          </td>
	      </tr>
	  </table>
	</div> -->

	<div data-dojo-type="dijit.form.Form" id="form_quinta_detallemes">
 
		<input type="hidden" name="anio" value="<?PHP echo $anio; ?>">
		<input type="hidden" name="mes" value="<?PHP echo $mes_id;  ?>">
		<input type="hidden" name="trabajador" value="<?PHP echo $indiv_id; ?>">
		
	</div>
	 
 	<div class="dv_busqueda_personalizada_pa2">
	 	
			
		<span class="sp12">
			Trabajador: 
		</span>

		<span class="sp12b">
	 		<?PHP echo $indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'].' '.$indiv_info['indiv_nombres'].' (DNI: '.$indiv_info['indiv_dni'].')';  ?>
		</span>

	</div>

	<div id="dvretenciones_table"></div>

	<div style="margin:8px 0px 0px 8px;">
		

		 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		   <?PHP 
		      $this->resources->getImage('note.png',array('width' => '14', 'height' => '14'));
		   ?>
		     <script type="dojo/method" event="onClick" args="evt">
 
		     var codigo = '';      
		             
		     for(var i in QuintaCategoria.Ui.Grids.detalle_retenciones_mes.selection){
		           codigo = i;
		     }
		     
		     if(codigo != '')      
		     {    
		     	 var data = dojo.formToObject('form_quinta_detallemes');
		     	 data.view = codigo;

		         QuintaCategoria._V.detalle_retencion.load(data);
		     }
		     else{
		         alert('Debe seleccionar un registro');
		     }
 
		         
		     </script>
		     <label class="sp11">
		          Ver calculo
		     </label>
		</button>

	</div>

</div>