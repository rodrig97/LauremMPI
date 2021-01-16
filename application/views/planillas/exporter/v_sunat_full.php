<div class="subtitle_red"> 

	SUNAT PDT

</div>


<div class="dv_busqueda_personalizada">

	<div style="margin: 0px 0px 3px 0px;">
		<span class="sp11b"> Parametros del reporte </span>
	</div>

	<table class="_tablepadding2">

			<tr> 
				<td width="25"> 
					 <span class="sp11b"> 
					 	 Año 
					 </span> 		    		
				</td>
				<td width="10" align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td width="35"> 
					 <span class="sp11">
					 		 <?PHP 
					 		 	 echo $reporte_info['anio'];
					 		 ?>
					 </span>
				</td> 	
				<td width="25"> 
					 <span class="sp11b"> 
					 	  Mes	
					 </span> 		    		
				</td>
				<td width="10" align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td width="80" align="center"> 
					 <span class="sp11">
					 		 <?PHP 
					 		 	 echo $reporte_info['mes'];
					 		 ?>
					 </span>
				</td>
 
			</tr> 

			 
	</table>


</div>

 

<div style="margin: 4px 0px 0px 5px;">
	<table>
		 <tr>
		 	<td> 	

		 		<input type="hidden" class="modoreporte" value="SUNAT" />

	 			<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                      <?PHP 
                         $this->resources->getImage('note_book.png',array('width' => '14', 'height' => '14'));
                      ?>
                    <script type="dojo/method" event="onClick" args="evt">
                    		
                            Exporter.Ui.btn_generar_pdt(this,evt);     

                    </script>
                    <label class="sp11">
                          Generar Archivo
                    </label>
                </button>

		 	</td> 
		 </tr>
	</table> 

</div>