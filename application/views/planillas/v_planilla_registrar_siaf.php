
<div class="window_container">



	<?PHP 

		if(sizeof($planillas) > 0 )
		{  
 
   ?>	

  		 <div class="dv_busqueda_personalizada" style="font-size:11px;"> 

  		 	<span class="sp11b"> Planillas : </span>

  		 	<?PHP 
				 foreach ($planillas as $pla)
				 {
				 	 
				 	 echo $pla['pla_codigo'].' ';

				 }
			?>  	 
		
		</div>	 

	<?PHP
	    }
	?>


	<div> 
			<table class="_tablepadding4">
			
				<tr class="tr_header_celeste">
					<td width="150"> 
						 Fuente
					</td>
					<td width="10"> 
						 :
					</td>	
					<td width="100"> 
						 N° Siaf
					</td>	

					<td>

					</td>	
				</tr>

			<?PHP 

				 foreach ($fuentes as $reg)
				 {
			?>
		 
				<tr class="tr_row_celeste">
					<td align="center"> 
						 <span class="sp11"> <?PHP echo $reg['fuente_id'].' - '.$reg['tipo_recurso'].' '.$reg['fuente_abrev']; ?> </span>
					</td>
					<td align="center"> 
						:
					</td>
					<td>
						<input type="hidden" class="hdkeys" value="<?PHP echo $keys; ?>"/>
						<input type="hidden" class="hdmodo" value="<?PHP echo $modo; ?>"/>
						<input type="hidden" class="hdsiaffuente" value="<?PHP echo $reg['fuente_id'].'_'.$reg['tipo_recurso']; ?>"  />		
		 			    <input type="hidden" class="hdsiafreg" value="<?PHP echo trim($reg['plasiaf_id']); ?>"  />
    					<input type="hidden" class="hdsiaf_siaf" value="<?PHP echo trim($reg['siaf']); ?>"  />
    					 
    					<input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="" value="<?PHP echo trim($reg['siaf']); ?>"
    					       class="txtsiaf_siaf"  style="width:80px; font-size:12px"/>	

					</td>
		 	
					 <td>
					 	
					 		 <button  dojoType="dijit.form.Button" class="btnsiaf_savesiaf" data-dojo-props="disabled:true" >
					 		      
					 		    <?PHP 
					 		      $this->resources->getImage('save.png',array('width' => '10', 'height' => '10'));
					 		    ?>
					 		    <script type="dojo/method" event="onClick" args="evt">
					 		       Planillas.Ui.btn_save_siaf_click(this,evt);
					 		    </script>
					 		    <label class="sp11">

					 		    </label>
					 		</button>

					 </td>		

				</tr>
		  
			<?PHP 
				 }
			?> 
			</table>

	</div>


	<div align="center" style="margin:10px 0px 0px 0px;">

		<!--  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		   <?PHP 
		      $this->resources->getImage('credit_cart.png',array('width' => '14', 'height' => '14'));
		   ?>
		     <script type="dojo/method" event="onClick" args="evt">
		           
		     </script>
		     <label class="sp11">
		          Planilla comprometida (Siaf)
		     </label>
		</button> -->

	</div>

</div>