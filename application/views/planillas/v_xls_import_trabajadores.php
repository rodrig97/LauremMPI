

<div style="margin: 8px 8px 8px 8px;">


	<div style="margin:10px 0px 10px 0px;">
 
		<span class="sp12b">
			Parámetros  de la importación	
		</span>
	
	</div>
	 

	<form id="frm_xls_import">
			

		<div class="dv_busqueda_personalizada">

			<table class="_tablepadding2">
				
				<tr>
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
					      <span class="sp12b">Observación  </span>  
					 </td>
					 
					 <td> <span class="sp12b"> : </span>     </td>
					  
					 <td>
					     <div data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'obervacion'" class="formelement-200-12"></div>
					 </td>
				</tr>
		
			</table>
			
			


		</div>



		<input name="view"  type="hidden" value="<?PHP echo $view; ?>" />
		 
	 


	</form>


	<div align="center" style="margin:10px 0px 0px 0px;">
		  
	       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		      <?PHP 
		         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
		      ?>
		        <script type="dojo/method" event="onClick" args="evt">

		        		importacionxls.Ui.btn_importar_trabajadores_click(this,evt);
		               
		        </script>
		        <label class="sp12">
		       		 Importar trabajadores 

		        </label>
		   </button>		

	</div>

</div>