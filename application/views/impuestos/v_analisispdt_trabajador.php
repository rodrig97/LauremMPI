
<div class="window_container">

	<div data-dojo-type="dijit.layout.BorderContainer" 
		 data-dojo-props='design:"headline", liveSplitters:true' style="width:840px; height: 425px;">


		 <div  data-dojo-type="dijit.layout.ContentPane" 
		 		data-dojo-props=' splitter: true, region:"top", style:"height: 80px; padding: 0px 0px 0px 0px;"' >

				 <div id="dvViewName" class="dv_view_name">
				    

				      <table class="_tablepadding2" border="0">
				          <tr> 
				             <td> 
				                 <?PHP 
				                  $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
				                  ?>
				             </td>
				             <td>
				                  Análisis de PDT - Trabajador
				             </td>
				          </tr>
				     
				      </table>
				 </div>

		</div>


		 <div  data-dojo-type="dijit.layout.ContentPane" 
		 		data-dojo-props=' splitter: true, region:"center", style:"width: 420px; padding: 0px 0px 0px 0px;"' >
		   
					<?PHP 

						$cosu_anterior = $reg[0]['cosu_codigo'];
						$nr = 1;
						$rowspan = array('rs' => '0' );

						foreach ($data as $reg) 
						{
						   
						   $c = $reg['cosu_codigo'];			

						   if($c == $cosu_anterior)
						   {
						   	  $nr++;
						   }
						   else
						   {
						   	  $rowspan[$cosu_anterior] = $nr; 
						   	  $nr = 1;
						   	  $cosu_anterior = $c;
						   }

						}
					?>	

					<div>

						<table class="_tablepadding4" >
							<tr class="tr_header_celeste">
							 	<td> Código SUNAT </td>
							 	<td> : </td>
							 	<td> Monto </td>
							 	<td> Concepto </td>		
							</tr>

							<?PHP 

								 $cosu_anterior = '';

								 $conc_tipo = '';

								 $labels_tipos = array('1' => 'Ingresos', '2' => 'Descuentos', '3' => 'Aportaciones');

								 foreach($data as $reg)
								 {
							?>
									 <?PHP 

									   if($conc_tipo != $reg['conc_tipo'])
									   {

									 	  $conc_tipo = $reg['conc_tipo'];
									  ?>	 

									  	  <tr>
									  	  		<td colspan="4" align="center"> <span class="sp11b"> <?PHP echo $labels_tipos[$conc_tipo]; ?> </span>  </td>
									  	  
									  	  </tr>
									 	


									 <?PHP  	
									   }		
									 ?>	


				   				 <tr class="tr_row_celeste">
 
				   				 	<?PHP 


							 		 if($cosu_anterior != $reg['cosu_codigo'])
								 	 { 
				   				 	
				   				 	?>
				   				 	 	<td rowspan="<?PHP echo $rowspan[$reg['cosu_codigo']]; ?>"> 

				   				 	 		<span class="sp11">	<?PHP echo $reg['cosu_codigo'].' '.$reg['cosu_descripcion']; ?>  </span>

				   				 	 	</td>
				   				 	
				   				 	<?PHP 	
				   				 		 $cosu_anterior = $reg['cosu_codigo'];

				   				 	  }
				   				 	?>
				   				 	 <td> <span class="sp11"> : </span> </td>
				   				 	 <td> <span class="sp11"> <?PHP echo $reg['monto']; ?> </span> </td>
				   				 	 <td  <?PHP  if( in_array($reg['conc_id'], $conceptos_pensionable) ) echo 'style="background-color:#80D6FF;' ?> > 	
				   				 	 	<span class="sp11"> <?PHP echo $reg['conc_nombre']; ?> </span>

				   				 	 </td>	
				   				 </tr>

							<?PHP 
								 }
							?>

						</table>

					</div>

		 </div> <!-- FIN PANEL CENTER -->


		 <div  data-dojo-type="dijit.layout.ContentPane" 
		 		data-dojo-props=' splitter: true, region:"right", style:"width: 425px; padding: 3px 3px 3px 3px;"' >
		  	 
		  	 	
		  	  	<table class="_tablepadding4">

		  	  		<tr>
		  	  			<?PHP 
		  	  			   foreach($ecuacion_pensionable as $v){
		  	  	 				
		  	  	 				echo "<td width='60' align='center'> $v </td>";
		  	  			   }
		  	  			?>
		  	  			<td width='15' align="center"> = </td>
		  	  			<td width='60' align="center"> TOTAL </td>
		  	  			 
		  	  			<?PHP 
		  	  				if($info['total_modificado'] != $info['total']){

		  	  					echo " <td width='60' align='center'> Total Modificado </td> ";
		  	  				}
		  	  			?> 	


		  	  		</tr>

		  	   </table> 



		  </div>


 	</div> <!-- FIN BORDER->
</div>