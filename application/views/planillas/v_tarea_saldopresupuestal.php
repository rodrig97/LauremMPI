
<div class="dv_busqueda_personalizada"> 
	<div style="margin:3px 3px 3px 3px">
		<span class="sp12b"> 
			 Saldo presupuestal de la generica 2.1. para la Tarea Presupuestal por Fuente de Financiamiento
		</span>
	</div>

	<div style="margin:3px 3px 3px 3px">

		<span class="sp12b"> 
			Tarea: 
		</span>
		
		<span class="sp12">
				<?PHP 

						echo $tarea_info['sec_func'].'-'.$tarea_info['tarea_nro'].' '.$tarea_info['tarea_nombre']
				?>
		</span>
	</div>

</div>



<div> 


	<table class="_tablepadding4">

		<tr class="tr_header_celeste">
			 <td width="60"> Año </td>
		 	<td width"150">  Tarea </td>
		 	<td width"300">  Tarea </td>
		 	<td width="100">   Fuente </td>
		 	<td width="100"> Clasificador</td>
		 	<td width="100"> Saldo </td>	

		</tr>

	<?PHP 

		
		foreach ($saldos as $reg)
		{
		
	?>

		 <tr class="tr_row_celeste">
		 	<td align="center"> <?PHP echo $reg['ano_eje'] ?></td>
		 	<td align="center"> <?PHP echo $reg['sec_func'].'-'.$reg['tarea_nro'] ?></td>
		    <td> <?PHP echo $reg['tarea_nombre'] ?></td>

		 	<td align="center"> <?PHP echo $reg['fuente_financ'].' '.$reg['tipo_recurso'] ?></td>
		 	<td align="center"> <?PHP echo $reg['tipo_transaccion'].'.'.$reg['generica'].'.*' ?></td>
		 	<td align="center"> S./ <?PHP echo $reg['saldo'] ?></td>			

		 </tr>


	<?PHP 
		}
	?>

	</table>

	<?PHP 

		if(sizeof($saldos) == 0)
		{
			echo  "<tr>
						 <td colspan='6' > La tarea presupuestal no tiene la generica 2.1 asignada </td>

					</tr> ";
		}

	?>

</div>