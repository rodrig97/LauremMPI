

<table class="_tablepadding2" border="1" id="table_quinta_trabajadores">
	<tr class="tr_header_celeste">
		<td width="30">
			# 
		</td>
		<td width="230">
			Trabajador 
		</td>
	  
		<td width="120">
			DATO 
		</td>

	 	<td width="80">
			Constancia 
		</td>
		<td width="60">
			Enero 
		</td>
		<td width="60">
			Febrero 
		</td>
		<td width="60">
			Marzo 
		</td>
		<td width="60">
			Abril 
		</td>
		<td width="60">
			Mayo 
		</td>
		<td width="60">
			Junio 
		</td>
		<td width="60">
			Julio 
		</td>
		<td width="60">
			Agosto 
		</td>
		<td width="60">
			Septiembre 
		</td>
		<td width="60">
			Octubre 
		</td>
		<td width="60">
			Noviembre 
		</td>
		<td width="60">
			Diciembre 
		</td>
		<td width="60">
			Total MPI
		</td>
		<td width="60">
			Total Año
		</td>
	</tr> 

	<?PHP 

	$ingresos_acumuluados = 0;

	$meses = array('ENERO', 'FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SETIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');


	$mostrar_ingresos_proyectados = false;
	$mostrar_ingresos_mes = true;
	$mostrar_retenciones_mes = true;
	$mostrar_ingresos_acumulados = true;

	$mes_actual = (date('n') * 1) + 1;

	$c = 0;
	foreach ($detalle as $reg) {
		$c++;
		$ingresos_acumuluados = 0;
 	?>
	
	<tr class="tr_row_celeste">
		<td align="center">
			<span class="sp11">
				<?PHP echo $c; ?> 
			</span>  
		</td>
		<td>
			<div>
				<span class="sp11">
					<?PHP 
					$nombres = explode(' ', $reg['indiv_nombres']);
					echo $reg['indiv_appaterno'].' '.$reg['indiv_apmaterno'].' '.$nombres[0]; ?> 
				</span>
			</div>
			<div style="margin-top: 3px;">
				<span class="sp11">DNI: </span>
				<span class="sp11b"> <?PHP echo $reg['indiv_dni']; ?> </span>
			</div>
			<div style="margin-top: 3px;">
				<span class="sp11">Tipo: </span>
				<span class="sp11"> <?PHP echo $reg['plati_nombre']; ?> </span>
			</div>
		</td>

	 
		<td>
			<?PHP 
				if($mostrar_ingresos_proyectados){ 
			?>

			<div class="quinta_separador">
				Ingresos Proyectados: 
			</div>

			<?PHP 
				}
			?>


			<?PHP 
				if($mostrar_ingresos_mes){ 
			?>

			<div class="quinta_separador">
				Ingresos del mes: 
			</div>
			<?PHP 
				}
			?>

			<?PHP 
				if($mostrar_ingresos_acumulados){ 
			?>
			
			<div class="quinta_separador">
				Ingresos Acum: 
			</div>
			<?PHP 
				}
			?>
			
			
			<?PHP 
				if($mostrar_retenciones_mes){ 
			?>
			
			<div class="quinta_separador">
				Retención del mes
			</div>
			<?PHP 
				}
			?>
		</td> 

		<td>
			<div class="quinta_separador texto_derecha"> 
				 .
			</div>
			<div class="quinta_separador texto_derecha"> 
				  <?PHP echo number_format($reg['constancias_ingresos'],2); ?> 
			</div>
			<div class="quinta_separador texto_derecha"> 
				  <?PHP echo number_format($reg['constancias_descuento'],2); ?> 
			</div>
		</td>

		<?PHP 
			$cm = 0; 
			$retenciones_mes_acumuladas = 0;

			foreach($meses as $key_mes){ 

			$cm++;
		?>
		<td class="mes_detalle">          
			
			<input type="hidden" class="hd_anio" value="<?PHP echo $anio; ?>">
			<input type="hidden" class="hd_indiv_id" value="<?PHP echo $reg['indiv_id']; ?>">
			<input type="hidden" class="hd_mes_id" value="<?PHP echo $cm; ?>">

			<?PHP  
			  	list($total_ingresos_mes, $total_retenciones_mes, $qcr_trabajador_remuneraccion, $qcr_ingresos_proyectados, $qcr_remuneracion_neta_anual_proyectada  ) = explode('_', trim($reg[$key_mes]) );
				
				$ingresos_acumuluados+=$total_ingresos_mes;

				$retenciones_mes_acumuladas+=$total_retenciones_mes;
			?> 

			<?PHP 
				if($mostrar_ingresos_proyectados){ 
			?>
		
			<div class="quinta_separador texto_derecha"> 
				<?PHP echo number_format($qcr_ingresos_proyectados,2); ?>
			</div> 
		
			<?PHP 
				}
			?>
	

			<?PHP 
				if($mostrar_ingresos_mes){ 
			?>
			
			<div class="quinta_separador texto_derecha">
				<?PHP echo number_format($total_ingresos_mes,2); ?>
			</div>
			
			<?PHP 
				}
			?>

			<?PHP 
				if($mostrar_ingresos_acumulados){ 
			?>
			
			<div class="quinta_separador texto_derecha">

				<?PHP 
					
					if($cm <= $mes_actual){
						echo number_format($ingresos_acumuluados,2);
					}else{
						echo number_format(0,2);
					}

				?>
			</div>

			<?PHP 
				}
			?>
			
			
			<?PHP 
				if($mostrar_retenciones_mes){ 
			?>
			
			<div class="quinta_separador texto_derecha">
				<?PHP echo number_format($total_retenciones_mes,2); ?>
			</div>

			<?PHP 
				}
			?>

		</td>
		
		<?PHP 

			}
		?>
		
		
		<td>          
			<?PHP  
			 
			?> 

			<?PHP 
				if($mostrar_ingresos_proyectados){ 
			?>
		
			<div class="quinta_separador texto_derecha"> 
			     --- 
			</div> 
		
			<?PHP 
				}
			?>
		

			<?PHP 
				if($mostrar_ingresos_mes){ 
			?>
			
			<div class="quinta_separador texto_derecha">
				<?PHP echo number_format($ingresos_acumuluados,2); ?>
			</div>
			
			<?PHP 
				}
			?>

			<?PHP 
				if($mostrar_ingresos_acumulados){ 
			?>
			
			<div class="quinta_separador texto_derecha">

				<?PHP 
				   echo number_format($ingresos_acumuluados,2); 
				?>
			</div>

			<?PHP 
				}
			?>
			
			
			<?PHP 
				if($mostrar_retenciones_mes){ 
			?>
			
			<div class="quinta_separador texto_derecha">
				<?PHP echo number_format($retenciones_mes_acumuladas,2); ?>
			</div>

			<?PHP 
				}
			?>

		</td>



		<td>          
			<?PHP  
			 
			?> 

			<?PHP 
				if($mostrar_ingresos_proyectados){ 
			?>
		
			<div class="quinta_separador texto_derecha"> 
			     --- 
			</div> 
		
			<?PHP 
				}
			?>
		

			<?PHP 
			 	
			 	$total_ingresos_full = ($reg['constancias_ingresos']*1) + $ingresos_acumuluados;
				
				if($mostrar_ingresos_mes){ 

			?>
			
			<div class="quinta_separador texto_derecha">
				<?PHP echo number_format($total_ingresos_full,2); ?>
			</div>
			
			<?PHP 
				}
			?>

			<?PHP 
				if($mostrar_ingresos_acumulados){ 
			?>
			
			<div class="quinta_separador texto_derecha">

				<?PHP 
				   echo number_format($total_ingresos_full,2); 
				?>
			</div>

			<?PHP 
				}
			?>
			
			
			<?PHP 
				if($mostrar_retenciones_mes){ 

					$total_descuento_full = ($reg['constancias_descuento']*1) + $retenciones_mes_acumuladas;
			?>
			
			<div class="quinta_separador texto_derecha">
				<?PHP echo number_format($total_descuento_full,2); ?>
			</div>

			<?PHP 
				}
			?>

		</td>


	</tr> 

	<?PHP 
	}
	?>
 
</table>