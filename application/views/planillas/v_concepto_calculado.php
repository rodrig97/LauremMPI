
<table class="_tablepadding4">
	<tr>
		<td> 
			<span class="sp12b">   Trabajador  </span>
		</td> 	
		<td>
			<span class="sp12b"> : </span>
		</td>
		<td>
			 <span class="sp12"> <?PHP echo $info['trabajador']; ?> </span>
		</td>
	</tr>
	<tr>
		<td> 
			<span class="sp12b">  Concepto </span>
		</td> 	
		<td>
			<span class="sp12b">  : </span>
		</td>
		<td>
			<span class="sp12"> <?PHP echo $info['concepto']; ?></span>
		</td>
	</tr>

</table>


<div style=" width: 580px; height: 170px; overflow: auto; border: 1px solid #e0f0ff; padding: 2px 0px 0px 2px; margin: 8px 0px 0px 0px">
	<?PHP 
 
	?>
	<table class="_tablepadding4">

		<tr class="tr_header_celeste" >
			<?PHP 
			   foreach($formula as $v){
	 				
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


		<tr class="tr_row_celeste">
			<?PHP 
			   foreach($calculado as $v){
	 			
	 				if(is_numeric($v)) $v = round($v,2);		
	 				echo "<td align='center'> $v </td>";
			   }
			?>

			<td align="center"> = </td>
			<td align="center">  <?PHP echo round($info['total'],2); ?> </td>
			 
			<?PHP 
				if($info['total_modificado'] != $info['total']){

					echo "<td align='center'> ".round($info['total_modificado'],2)."</td>";
				}
			?> 	

		</tr>

	</table>

</div>

 
<?PHP 

	if(sizeof($movimientos) > 0 ){ 
?>
 
	<div>
 		<table class="_tablepadding4">
 			<tr class="tr_header_celeste" >
 				<td width="20"> Op </td>
 				<td width="60"> Monto </td>
 			</tr>
 			<?PHP
 			 	foreach($movimientos as $mov){
 			 	
 			 	 
 			 	 if($mov['plaecm_add'] != '0') $valor = $mov['plaecm_add'];
 			 	 if($mov['plaecm_res'] != '0') $valor = $mov['plaecm_res'];
 			 	 if($mov['plaecm_prestado'] != '0') $valor = $mov['plaecm_prestado'];
 			 	 if($mov['plaecm_pagado'] != '0') $valor = $mov['plaecm_pagado'];  
 			 	 if($mov['plaecm_excedente'] != '0') $valor = $mov['plaecm_excedente'];  

 			 	 if( $mov['plaecm_add'] != '0'  || $mov['plaecm_prestado'] != '0' ) $op = '+';
 			 	 if( $mov['plaecm_res'] != '0'  || $mov['plaecm_pagado'] != '0' || $mov['plaecm_excedente'] != '0'  ) $op = '-';

 			 ?>

	 			<tr class="tr_row_celeste">
	 				<td align="center"> <?PHP echo $op; ?> </td>
	 				<td align="center"> <?PHP echo round($valor,2); ?> </td>
	 			</tr>


 			 <?PHP 
 			 	}
 			?>

 		</table>

	</div>


<?PHP 
	}
?>