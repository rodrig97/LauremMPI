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
	             Resumen de descansos médicos
	          </td>
	      </tr>
	  </table>
	</div>


	<div id="dvDescansosMedicosPlanilla"></div>
	
	<div style="margin-top: 5px;">
				
				<input type="hidden" id="hdDescansoMedicoPlanillaAnio" value="<?PHP echo $anio; ?>" />

		       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		         <?PHP 
		            $this->resources->getImage('application.png',array('width' => '14', 'height' => '14'));
		         ?>
		           <script type="dojo/method" event="onClick" args="evt">
						  
						var codigo = '';      
						        
						for(var i in Planillas.Ui.Grids.resumen_planilla_descansos_medicos.selection){
						      codigo = i;
						}
						
						if(codigo != '')      
						{     
							  var anio = document.getElementById('hdDescansoMedicoPlanillaAnio').value;

							  Planillas._V.resumen_descansos_medicos_trabjador.load({'trabajador' : codigo, 'anio' : anio });
						}
						else
						{
						     alert('Debe seleccionar un registro');
						}
		           
			           </script>
		           <label class="sp11">
		                 Visualizar detalle
		           </label>
		      </button>


	</div>
</div>