 
<table class="_tablepadding4" style="font-size: 12px;">

	<tr class="tr_header_celeste">
		
		<td width="10">
			 # 
		</td>

	<?PHP 

		$header= array_keys($table[0]);
 
		foreach($header as $k => $v )
		{
		 
			$label = '';
 
			if(strpos($v, 'ver_' ) !== FALSE  )
			{
				list($x,  $label ) = explode('_', trim(strtoupper($v)) );  
			}

			if(strpos($v, 'DATO_' ) !== FALSE)
			{

				list($x,$id,$label) =  explode('_', trim(strtoupper($v)) );  
			}
 

		   	if($label != '')
		   	{

		   		 echo ' <td>'.$label.'</td>'; 
			 
		 	}
			 
		}
	?>

	</tr>



<?PHP 
			  
	$c = 0;

	foreach ($table as $reg)
	{
		 $c++;

		 echo '
		 		<tr class="tr_row_celeste"> 
		 		<td>'.$c.'</td>';


		 $vari_id = 0;

		 foreach($reg as $key => $v )
		 {
		  
		 	$label = '';

		 
		 	if(strpos($v, 'ver_' ) !== FALSE  )
		 	{	
		 	 
		 		list($x, $id, $label ) = explode('_', trim(strtoupper($v)) );  

		 		$vari_id = $id;
		 		?>

		 			<td align="center" width="120">
		 				<?PHP echo trim($label); ?>	
		 			</td>


		 		<?PHP 

		 	}

		 	if(strpos($key, 'DATO_' ) !== FALSE)
		 	{

		 		list($x,$id,$label) =  explode('_', trim(strtoupper($key)) );  

		 		?> 

		 		<td class="datos"> 

		 		 	<div class="dvlabel_afp"> <?PHP echo $v; ?> </div>	
		 		 	<div class="textbox_afp" style="display: none;"> 

		 		 		<input type="text" class="formelement-50-12"  data-dojo-props="" value="<?PHP echo trim($v); ?>" data-dojo-type="dijit.form.TextBox" /> 

		 		 	</div>
		 		
		 			<input type="hidden" class="last_data" value="<?PHP echo $v; ?>"  />
					<input  type="hidden" class="keys" value="<?PHP echo $vari_id."_".$id;  ?>" />

	 			</td>

		 		<?PHP 
		 	}
		 
 
		 	 
		 }

?>

		  <td align="center">
 					
 			 <div class="dv_btncontent_afp">
 			  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
			      <?PHP 
			         $this->resources->getImage('edit.png',array('width' => '12', 'height' => '12'));
			      ?>
			        <script type="dojo/method" event="onClick" args="evt">
 					
			        	 tabla_variables_montos.btn_editar_reglon(this, evt);

			        </script>
			        <label class="sp12">
			             
			        </label>
			   	  </div>
			   </button>

			   <div class="dv_btncontent_afp" style="display: none;"  >
			    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
			      <?PHP 
			         $this->resources->getImage('cancel.png',array('width' => '12', 'height' => '12'));
			      ?>
			        <script type="dojo/method" event="onClick" args="evt">
 
			            tabla_variables_montos.btn_cancel_reglon(this, evt);
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
  						
  						  tabla_variables_montos.btn_save_reglon(this, evt);
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