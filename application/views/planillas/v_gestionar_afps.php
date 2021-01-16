
 
<table class="_tablepadding4" style="font-size: 12px;">
	<tr class="tr_header_celeste">
		<td width="20">
			 #
		</td>
		<td width="150"> AFP </td>
		<td width="90">
			% Aporte Obligatorio
		</td>
		<td width="90">
			% Comision Variable (Flujo)
		</td>
		<td width="90">
			% Seguros
		</td>
		<td width="90">
			% Aporte Obligatorio CC
		</td>

		<td width="90">
			Comisión
			por saldo
			(% Remuneración)  
		</td>

		<td width="90">
 			Máximo Asegurable  
		</td>

 


	</tr>

	<?PHP 
		$c= 0;
 		foreach($table as $reg){ 

	?>

	<tr id="afp-<?PHP echo trim($reg['afp_id']); ?>" class="tr_row_celeste">
		 <td align="center" width="30">
		 	<?PHP echo ++$c;   ?>
		 </td>
		 <td align="center" width="">
		 	<?PHP echo $reg['afp_nombre']; ?>	
		 </td>
		 <td align="center" width="70">
		 	<div class="dvlabel_afp"> <?PHP echo $reg['afcv_aportobli']; ?> </div>	
		 	<div class="textbox_afp" style="display: none;"> <input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($reg['afcv_aportobli']); ?>" data-dojo-type="dijit.form.TextBox" /> </div>
			<input type="hidden" class="last_data" value="<?PHP echo $reg['afcv_aportobli']; ?>"  />
		 </td>
		 <td align="center" width="70">
		 	<div class="dvlabel_afp"> <?PHP echo $reg['afcv_comvar']; ?>	</div>
		 	<div class="textbox_afp" style="display: none;"> <input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($reg['afcv_comvar']); ?>" data-dojo-type="dijit.form.TextBox" /> </div>
			<input type="hidden" class="last_data" value="<?PHP echo $reg['afcv_comvar']; ?>"  />
		 </td>
		 <td align="center" width="70">
		  	<div class="dvlabel_afp"> <?PHP echo $reg['afcv_seguros']; ?>	</div>
		 	<div class="textbox_afp" style="display: none;"> <input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($reg['afcv_seguros']); ?>" data-dojo-type="dijit.form.TextBox" /> </div>
			<input type="hidden" class="last_data" value="<?PHP echo $reg['afcv_seguros']; ?>"  />
		 </td>
		  <td align="center" width="70">
		   <div class="dvlabel_afp"> <?PHP echo $reg['afcv_aportobli_cc']; ?>	</div>
		 	<div class="textbox_afp" style="display: none;"> <input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($reg['afcv_aportobli_cc']); ?>" data-dojo-type="dijit.form.TextBox" /> </div>
			<input type="hidden" class="last_data" value="<?PHP echo $reg['afcv_aportobli_cc']; ?>"  />
		 </td>

	     
		 <!-- Nuevos % de afp-->
	     <td align="center" width="70">
	     	<?PHP if($reg['afcv_comisionmixta'] == '1'){ ?>

			   	<div class="dvlabel_afp"> <?PHP echo $reg['afcv_comsaldo']; ?>	</div>
			 	<div class="textbox_afp" style="display: none;"> <input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($reg['afcv_comsaldo']); ?>" data-dojo-type="dijit.form.TextBox" /> </div>
				<input type="hidden" class="last_data" value="<?PHP echo $reg['afcv_comsaldo']; ?>"  />
			 	
		 	<?PHP }else{
		 		 echo '---';
		 	} ?>

		 </td>


	      <td align="center" width="70">
     
 		   	<div class="dvlabel_afp"> <?PHP echo $reg['afcv_max_asegurable']; ?>	</div>
 		 	<div class="textbox_afp" style="display: none;"> <input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($reg['afcv_max_asegurable']); ?>" data-dojo-type="dijit.form.TextBox" /> </div>
 			<input type="hidden" class="last_data" value="<?PHP echo $reg['afcv_max_asegurable']; ?>"  />
	 		 
	 	 </td>
	 


		  <td align="center">
 					
 			 	<div class="dv_btncontent_afp">
 			  		<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
					      <?PHP 
					         $this->resources->getImage('edit.png',array('width' => '12', 'height' => '12'));
					      ?>
					        <script type="dojo/method" event="onClick" args="evt">
		 					
					        	 Afps.btn_editar_reglon(this, evt);

					        </script>
					        <label class="sp12">
					             
					        </label>

			         </button>
			   </div>
			  

			   <div class="dv_btncontent_afp" style="display: none;"  >
					    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
					      <?PHP 
					         $this->resources->getImage('cancel.png',array('width' => '12', 'height' => '12'));
					      ?>
					        <script type="dojo/method" event="onClick" args="evt">
		 
					            Afps.btn_cancel_reglon(this, evt);
					        </script>
					        <label class="sp12">
					             
					        </label>
					   </button>
			 	</div>

			    <div class="dv_btncontent_afp">
					     <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
					      <?PHP 
					         $this->resources->getImage('save.png',array('width' => '12', 'height' => '12'));
					      ?>
					        <script type="dojo/method" event="onClick" args="evt">
		  						
		  						  Afps.btn_save_reglon(this, evt);
					        </script>
					        <label class="sp12">
					             
					        </label>
					   </button>
			 	</div>
		 </td>
		   

	</tr>

	<?PHP 
		}
	?>

</table>