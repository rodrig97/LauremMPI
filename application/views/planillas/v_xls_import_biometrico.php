

<div style="margin: 8px 8px 8px 8px;">


	<div style="margin:10px 0px 10px 0px;">
 
		<span class="sp12b">
			Parámetros  de la importación	
		</span>
	
	</div>
	 

	<form data-dojo-type="dijit.form.Form" id="frm_xls_biometrico_import">
			

		<div class="dv_busqueda_personalizada">

			<input name="biokey"  type="hidden" value="<?PHP echo trim($bio_key); ?>" />
			<input name="filekey"  type="hidden" value="<?PHP echo trim($file_key); ?>" />
 
			<table class="_tablepadding2">
				
			<!-- 	<tr>
					<td width="100">
						<span class="sp12b"> N° de Registros </span>
					</td>
					<td width="10">
						<span class="sp12b">:</span>	
					</td>
					<td>
						<span class="sp12"> <?PHP echo $numeregistros; ?>  </span>
					</td>
				</tr> 
				<tr>
					<td>
						<span class="sp12b"> Trabajadores </span>
					</td>
					<td>
						<span class="sp12b">:</span>	
					</td>
					<td>
						<span class="sp12"> <?PHP echo sizeof($trabajadores); ?> encontrados. </span>
					</td>

				</tr>  -->
				<tr>
					 <td> 
					     <span class="sp12b">Observación  </span>  
					 </td>
					 <td> 
					 	 <span class="sp12b"> : </span>    
					 </td> 
					 <td>
					     <div data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'obervacion'" class="formelement-200-12"></div>
					 </td>
				</tr>
		
			</table>
			
			


		</div>




		 
 

	</form>


	<div align="center" style="margin:10px 0px 0px 0px;">
		  
	       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		      <?PHP 
		         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
		      ?>
		        <script type="dojo/method" event="onClick" args="evt">

		        		Biometrico.Ui.btn_importar_click(this,evt);
		               
		        </script>
		        <label class="sp12">
		       		 Importar Archivo

		        </label>
		   </button>		

	</div>

</div>