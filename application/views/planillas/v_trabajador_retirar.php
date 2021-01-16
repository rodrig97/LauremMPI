<div class="window_container">

 <form id="form_info_cesar" name="form_info_cesar"  data-dojo-type="dijit.form.Form">  
	
	<div id="dvViewName" class="dv_view_name">
      
		    <table class="_tablepadding2" border="0">

		      <tr> 
		          <td> 
		               <?PHP 
		                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
		                     ?>
		          </td>

		        <td>
		            Cesar al trabajador  
		          </td>
		      </tr>
		  </table>
	</div>



	<input name="view" type="hidden" value="<?PHP echo trim($info['persla_key']); ?>">

	<table class="_tablepadding4">
		<tr>
			<td widtH="100"><span class="sp12b"> Trabajador </span></td>
			<td width="8"><span class="sp12b"> : </span></td>
			<td><span class="sp12"> 
						<?PHP echo $info['indiv_appaterno'].' '.$info['indiv_apmaterno'].' '.$info['indiv_nombres']; ?>
			    </span></td>
		</tr>
		<tr>
			<td><span class="sp12b"> Régimen </span></td>
			<td><span class="sp12b"> : </span></td>
			<td>
				<span class="sp12"> 

					    <?PHP echo $info['situ_nombre']; ?>
				 </span>
		   </td>
		</tr>
		<tr>
			<td><span class="sp12b"> Periodo  </span></td>
			<td><span class="sp12b"> : </span></td>
			<td>
				<span class="sp12"> 

					    <?PHP 

					    	$fechaend = ($info['persla_fechafin'] != '') ? _get_date_pg($info['persla_fechafin']) : '--------';

					    	echo _get_date_pg($info['persla_fechaini']).'  Hasta  '.$fechaend;

					     ?>
				 </span>
		   </td>
		</tr>
		<tr>
			<td><span class="sp12b">Fecha de cese</span></td>
			<td><span class="sp12b">: </span></td>
			<td> 
				<input id="calcese_fecha" type="text" class="formelement-100-11" data-dojo-type="dijit.form.DateTextBox"
	                                                    data-dojo-props='type:"text", name:"fechacese", value:"01/01/1980",
	                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
	                                                    lang:"es",
	                                                    required:true,
	                                                    promptMessage:"mm/dd/yyyy",
	                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

	                                                onChange=""     
	                                             />

		     </td>
		</tr>
		<tr>
			<td><span class="sp12b">Observación</span></td>
			<td><span class="sp12b">: </span></td>
			<td>
				<textarea data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'observacion'" class="formelement-200-12" ></textarea>

			</td>
		</tr>

	 
	</table>

	<div align="center" style="margin:15px 0px 0px 0px;">
			<button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	                      <?PHP  $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
	                                  ?>
	                       <label class="sp12"> Cesar Trabajador </label>
	                       <script type="dojo/method" event="onClick" args="evt">
	                              Persona.Ui.btn_cesartrabajador_click(this,evt);     
	                       </script>
	        </button>
	</div>

</form>

</div>
