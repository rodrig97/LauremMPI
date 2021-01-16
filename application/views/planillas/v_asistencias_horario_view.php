<div class="window_container">

  	<div id="dvViewName" class="dv_view_name">
	      
	    <table class="_tablepadding2" border="0">

	      <tr> 
	          <td> 
	               <?PHP 
	                    $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
	               ?>
	          </td>

	         <td>
	              Horario de trabajo
	          </td>
	      </tr>
	    </table>
	</div> 

	<div class="dvFormBorder">
	
	<table class="_tablepadding4" border="0" width="100%">	

			<tr class="row_form">
				 <td width="150"><span class="sp11b"> Alias </span></td>
				 <td width="8"><span class="sp11b">:</span></td>
				 <td colspan="4">  

				 	<span class="sp11"> 
				 <?PHP 
				 	 echo (trim($info['hor_alias']) != '') ?  trim($info['hor_alias']) : '-----';
				 ?>	 
				 	 </span>
				 </td>
			</tr>

			<tr class="row_form">
				 <td><span class="sp11b">Horario corrido</span></td>
				 <td><span class="sp11b">:</span></td>
				 <td> 
				 		<span class="sp11"> 
							 	<?PHP 
							 		 echo (trim($info['hor_numero_horarios']) == '1') ? 'Si' : 'No';
							 	?>	 
				 		 </span>
				 </td>

			 	<td><span class="sp11b"> Horario entre dos días </span></td>
			 	<td><span class="sp11b">:</span></td>
			 	<td> 
			 
			 		 	<span class="sp11"> 
			 		  	<?PHP 
			 		  		 echo ( trim($info['hor_entredosdias']) == '1' ) ?  'Si' : 'No';
			 		  	?>	 
			 		 	</span>
			     </td> 

			</tr>
 			
 			<tr class="row_form">
 				<td>
 					<span class="sp11b"> 
 							Ingreso    
 							<?PHP 
 								 echo ( trim($info['hor_entredosdias']) == '1' ) ?  '(Día anterior)' : '';
 							?>	
 					</span>
 				</td>
 				<td><span class="sp11b">:</span></td>
 				<td width="70"> 
 					<span class="sp11"> 
 					<?PHP 
 						 echo (trim($info['hor_hora1_e']) != '') ?  trim($info['hor_hora1_e']) : '-----';
 					?>	 
 					</span>
 				</td>
 				<td  width="70"><span class="sp11b"> Salida</span></td>
 				<td  width="8"><span class="sp11b">:</span></td>
 				<td  width="70" > 
 					<span class="sp11"> 
 				 	<?PHP 
 				 		 echo (trim($info['hor_hora1_s']) != '') ?  trim($info['hor_hora1_s']) : '-----';
 				 	?>	 
 					</span>
 				</td>
 			</tr>

 			 

 			<tr class="row_form">

				<td><span class="sp11b"> Tardanza </span></td>
				<td><span class="sp11b">:</span></td>
				<td> 
					 	<span class="sp11"> 
					  	<?PHP 
					  		 echo (trim($info['hor_ini_tardanza']) != '') ?  trim($info['hor_ini_tardanza']) : '-----';
					  	?>	 
					 	</span>
			    </td>
 

  				<td><span class="sp11b"> Falta por tardanza </span></td>
  				<td><span class="sp11b">:</span></td>
  				<td> 
 
  					 	<span class="sp11"> 
  					  	<?PHP 
  					  		 echo ( trim($info['hor_faltaportardanza']) == '1' && trim($info['hor_hora1_max_ft']) != '') ?  trim($info['hor_hora1_max_ft']) : '-----';
  					  	?>	 
  					 	</span>
  			    </td>
 			</tr>
 		 
 			<?PHP 
 		
 			 if(trim($info['hor_numero_horarios']) == '2')
 			 {

 			?>
 			
 		 	<tr  class="row_form">
 				<td><span class="sp11b">Tarde Ingreso</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					<span class="sp11"> 
 					 <?PHP 
 					 	 echo (trim($info['hor_hora2_e']) != '') ?  trim($info['hor_hora2_e']) : '-----';
 					 ?>
 					 </span>
 				</td>

 				<td><span class="sp11b"> Salida</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					<span class="sp11"> 
 					 <?PHP 
 					 	 echo (trim($info['hor_hora2_s']) != '') ?  trim($info['hor_hora2_s']) : '-----';
 					 ?>
 					</span>
 				</td>
 			</tr>
 	
 			<?PHP 
 			  }
 			  else
 			  { 
 			?>

	 			<?PHP 
	 				 if(trim($info['hor_descontar_horas']) != '0')
	 				 { 
	 			?>

	 			<tr class="row_form">
	 				 <td><span class="sp11b"> Descontar por refrigerio ( Calculo de horas efectivamente trabajadas) </span></td>
	 				 <td><span class="sp11b">:</span></td>
	 				 <td colspan="4"> 
	 				 	 
	 				 	 <span class="sp11">  
	 				 	 <?PHP 
	 				 	 	 echo (trim($info['hor_descontar_horas']) != '') ?  trim($info['hor_descontar_horas']) : '-----';
	 				 	 ?>
	   		 		 	 </span>
	 				 	 
	 				 	 <span class="sp11b">  horas  </span>
	 				 	 
	 				 	 <span class="sp11b">  solo si la hora de salida es despues de:  </span>
	 					
	 					 <span class="sp11"> 
	 					<?PHP 
	 						 echo (trim($info['hor_descontar_despuesde']) != '') ?  trim($info['hor_descontar_despuesde']) : '-----';
	 					?>
	 					 </span>

	 				 </td>
	 			</tr>

 			<?PHP 
 					}
 				}
 			?>

 			<tr class="row_form">
 				<td colspan="6"> 
 					<span class="sp11"> 
 						 Horario alternativo. (útil si por licencias se considera un horario de trabajo diferente)
 					</span>
 				</td>
 			</tr>

 			<tr class="row_form">
 				<td><span class="sp11b"> Ingreso</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					 <span class="sp11"> 
 					 <?PHP 
 					 	 echo (trim($info['hor_hora1_e2']) != '') ?  trim($info['hor_hora1_e2']) : '-----';
 					 ?>
 					</span>
 				</td>

 				<td><span class="sp11b"> Salida</span></td>
 				<td><span class="sp11b">:</span></td>
 				<td> 
 					<span class="sp11"> 
 					 <?PHP 
 					 	 echo (trim($info['hor_hora1_s2']) != '') ?  trim($info['hor_hora1_s2']) : '-----';
 					 ?>
 					</span>
 				</td>
 			</tr>

 

	</table>	

	</div>


</div>