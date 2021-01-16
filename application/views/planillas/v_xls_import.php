

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
						<span class="sp12b"> Planillas </span>
					</td>
					<td>
						<span class="sp12b">:</span>	
					</td>
					<td>
						<span class="sp12"><?PHP echo sizeof($planillas); ?> encontradas. </span>
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
			
				<div align="center" style="margin:10px 0px 0px 0px;">
		  
	       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
		      <?PHP 
		         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
		      ?>
		        <script type="dojo/method" event="onClick" args="evt">

		        		importacionxls.Ui.btn_importar_click(this,evt);
		               
		        </script>
		        <label class="sp12">
		       		 Importar Archivo

		        </label>
		   </button>		

	</div>



		</div>



		<input name="view"  type="hidden" value="<?PHP echo $view; ?>" />
		 
		<table class="_tablepadding4" border="1"> 

			<tr class="tr_header_celeste">
				<td>
					COLUMNA 	
				</td>
				<td>

				</td>

				<td>
					 VARIABLE
				</td>

			</tr>

			<?PHP 
			  for($i=1; $i<=NUMERO_VARIABLES_IMPORTADOR_EXCEL; $i++)
			  { 
			?>

			<tr class="tr_row_celeste">

				<td width="100"> 
					<select class="cs_xls_tareo_columna" 
							data-dojo-type="dijit.form.Select"  
							data-dojo-props="name:'columna[]'" 
							style="width:120px; font-size:12px;">
					

						  <option value="0" selected="selected"> -----  </option>		

						<?PHP
							foreach ($columnas_variables as $col => $pos)
							{
								

							  	echo "<option value='".$pos."'> ".$col." </option> ";
							}

						 ?>
					</select>			
				</td>

				<td width="80">
		 			<span class="sp12b"> Cargar a </span>
				</td>

				<td width="200">	

					<select class="cs_xls_tareo_vari" 
							data-dojo-type="dijit.form.FilteringSelect"  
							 data-dojo-props='name:"variable[]", disabled:false, autoComplete:false, 
							 				  highlightMatch: "all",  queryExpr:"*${0}*", 
							 				  invalidMessage: "Variable no registrada" '  
							style="width:200px; fotn-size:11px;">
						
						<option value="0"> ----- </option>
						<?PHP
							foreach ($variables_destino as $vari) {
							  	
							  	echo "<option value='".$vari['vari_key']."'> ".$vari['vari_nombre']." </option> ";
							}

						 ?>
					</select>
 
				</td>	

			</tr>

			<?PHP

				}
			?>		 

		</table>


	</form>


	
</div>