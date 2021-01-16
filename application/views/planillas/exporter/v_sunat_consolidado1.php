<div class="subtitle_red"> 

	CONSOLIDADO SUNAT

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
				<!-- 
				<td width="82">  
					 <span class="sp11b"> 
					 	  Régimen	
					 </span> 		    		
				</td>
				<td width="10" align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td width="160" width="60"> 
					 <span class="sp11">
					 		 <?PHP 
					 		 	 echo $reporte_info['tipoplanilla'];
					 		 ?>
					 </span>
				</td> 	  -->	
			</tr> 

			 
	</table>


</div>


<!--
<div class="dv_form_1">
 	 <table class="table_reporter"> 
 		<tr class="tr_header_celeste">
 			<td width="30">		
 				#	
 			</td>
 			<td width="65">
 				DNI	
 			</td>	
 			<td width="250">
 				Trabajador
 			</td>
 			<td width="180">
 				Concepto
 			</td>
 			<td width="60">
 				Monto S./
 			</td> 
 			<td width="60">
 				Monto S./
 			</td> 
 		</tr>	

 	<?PHP 
 	    $c = 0;
 		$total = 0;
 		foreach($reporte as $reg){
 				$c++;
 			?>
 			<tr class="row_form"> 
 			 	<td align="center"><?PHP echo $c; ?> </td>
 			 	<td><?PHP echo $reg['dni']; ?></td>
 			 	<td><?PHP echo $reg['trabajador']; ?></td>
 			 	<td><?PHP echo $reg['cosu_codigo'].' - '.$reg['cosu_descripcion']; ?></td>
 			 	<td align="right"><?PHP echo number_format($reg['monto'],2); ?> </td>
 			 	<td align="right"><?PHP echo number_format($reg['monto'],2); ?> </td>

 			</tr>

 			<?PHP
 			 	 $total+= $reg['monto'];
 		}


 		if(sizeof($reporte) > 0){  
 	?>	 
 		 <tr class="row_form"> 
 	 	 	 <td colspan="4"> 
 	  	 	
 		 	 </td>
 		 	 <td align="right"> 
 		 	 		 <?PHP  echo number_format($total,2); ?>
 		 	 </td>
 		 	  <td align="right"> 
 		 	 		 <?PHP  echo number_format($total,2); ?>
 		 	 </td>
 		 </tr>
 		 <?PHP 

 		   }
 		   else{

 		   	  ?>
 		   	  	 <tr> 
		 	 	 	 <td colspan="5"> 
		 	  	 		<div class="sp11b"> No se enontraron registros </div>
		 		 	 </td>
		 		 	 
		 		 </tr>
 		   <?PHP
 		   }
 		 ?>

 	 </table>

</div>

-->


<div style="margin: 4px 0px 0px 5px;">
	<table>
		 <tr>
		 	<td> 	

		 		<?PHP 
		 			if(sizeof($reporte) > 0)
		 			{

		 		?>

			 		<input type="hidden" class="modoreporte" value="SUNAT" />
 
		 			<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	                      <?PHP 
	                         $this->resources->getImage('note_book.png',array('width' => '14', 'height' => '14'));
	                      ?>
	                    <script type="dojo/method" event="onClick" args="evt">
	                    		
	                            Exporter.Ui.btn_generar(this,evt);     

	                    </script>
	                    <label class="sp11">
	                          Generar Archivo
	                    </label>
	                </button>

                <?PHP 
                	}
                ?> 
		 	</td> 
		 </tr>
	</table> 

</div>