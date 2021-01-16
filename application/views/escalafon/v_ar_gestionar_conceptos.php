 
    
<div id="dvViewName" class="dv_view_name">
     Gestion rápida de conceptos
</div>
 


<div style="margin: 8px 0px 8px 0px;">
	
	<span class="sp12b"> Trabajador: </span>
	<span class="sp12"> <?PHP echo $persona['indiv_appaterno'].' '.$persona['indiv_apmaterno'].' '.$persona['indiv_nombres']. ' ('.$persona['tipo_empleado'].')'; ?> </span>
</div>

<div>


	<div style="width:400px; height: 250px; border:1px solid #333; overflow: auto; ">

		<table class="_tablepadding4">

			<tr class="tr_header_celeste">
	 			<td width="320"> Concepto </td>
	 			<td width="10"> </td>
	 			<td width="50"> Add </td>
			</tr>

		<?PHP 
			foreach($conceptos as $conc){

		?> 
				<tr class="tr_row_celeste">
					<td > 
					   <span class"sp12">	
						<?PHP echo $conc['conc_nombre']; ?> 
						</span>	
					</td>	
					<td  align="center">  <span class"sp12"> : </span> </td>
					<td  align="center"> 
						<input type="hidden" class="trabajador" value="<?PHP echo $persona['indiv_key']; ?>" />
						<input type="hidden" class="concepto" value="<?PHP echo $conc['conc_key']; ?>" />
						<input type="hidden" class="rela" value="<?PHP echo $conc['empcon_key']; ?>" />
					    <input type="checkbox" 
					    	   data-dojo-type="dijit.form.CheckBox"  
					    	   class="ch_gestion_concepto"

					    	   <?PHP if($conc['empcon_id'] != '') echo ' checked'; ?>
					    	      />
					</td>	
				</tr>


		<?PHP

			}
		?>		
		 
		</table>

 	</div>

	<div align="center" style="margin:5px 0px 0px 0px;">
	  <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	                    <?PHP 
	                        $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
	                     ?>
	                     
	                     <label class="sp12">
                                  Cerrar
                         </label>
	                     <script type="dojo/method" event="onClick" args="evt">
	                          

	                          Persona._V.gestion_rapida_deconceptos.close(); 
	                     </script>
	   </button>
	</div>

</div>