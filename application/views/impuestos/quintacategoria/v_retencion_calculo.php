<div class="window_container">

  	<div class="dv_busqueda_personalizada_pa2">
 	 	
 		<div>
 			
 		<span class="sp12">
 			Trabajador: 
 		</span>

 		<span class="sp12b">
 	 		<?PHP echo $indiv_info['indiv_appaterno'].' '.$indiv_info['indiv_apmaterno'].' '.$indiv_info['indiv_nombres'].' (DNI: '.$indiv_info['indiv_dni'].')';  ?>
 		</span>

 		</div>

 		<div>
 			<span class="sp11">
 				
 			<?PHP echo $qr_info['tipla_nombre'].' Codigo: <b>'.$qr_info['codigo'].'</b> mes: <b>'.$qr_info['mes_nombre'].'</b>'; ?>
 			</span>
 		</div>	
 	</div>

	 <table class="_tablepadding4" border="0">
	 	<tr>
	 		<td width="200">
	 			<span class="sp11"> Remuneración en el proceso </span>
	 		</td>
	 		<td width="5">
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td width="70" align="right">
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_trabajador_remuneraccion'],2); ?>
	 			</span>
	 		</td>
	 		<td width="70"></td>
	 	</tr>
		<tr>
			<td width="200">
				<span class="sp11"> Proyección de la remuneración </span>
			</td>
			<td width="5">
				<span class="sp11"> : </span>		
			</td>
			<td width="70" align="right">
				<span class="sp11">
					<?PHP echo number_format($qr_info['qcr_proyeccion_remuneraciones'],2); ?>
				</span>
			</td>
	 		<td></td>
		</tr>

	 	<tr>
	 		<td>
	 			<span class="sp11"> Ingresos anteriores </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right">
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_ingresos_anteriores'],2); ?>
	 			</span>
	 		</td>
	 		<td align="left">
	 			<?PHP 
	 				if( ($qr_info['qcr_constancia_ingresos']*1) > 0){ 
	 			?>
	 			<span class="sp11">
	 				+ <?PHP echo number_format($qr_info['qcr_constancia_ingresos'],2); ?> (Const)
	 			</span>
	 			
	 			<?PHP 
	 				}
	 			?>
	 		</td> 
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Ingresos en el mes </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_ingresos_delmes'],2); ?>
	 			</span> 
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Gratifiaciones proyectadas </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_gratificacion_monto_proyectado'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Proyección de ingresos anual </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_ingresos_proyectados'],2); ?>
	 			</span>
	 		</td> 
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> - 7 UITS </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_montominimo_desc'],2); ?>
	 			</span>
	 		</td> 
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Remuneración neta anual ( Aplicamos tasas escalonadas)</span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_remuneracion_neta_anual_proyectada'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> = Impuesto anual </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_impuesto_anual'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> - Retenciones anteriores 
	 				<?PHP echo ' ('.$qr_info['qcr_periodo_retenciones'].')'; ?>  
	 			</span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_retenciones_anteriores'],2); ?>

	 			</span>
	 		</td> 
	 		<td align="left">
	 			<?PHP 
	 				if( ($qr_info['qcr_constancia_descuento']*1) > 0){ 
	 			?>
	 			<span class="sp11">
	 				+ <?PHP echo number_format($qr_info['qcr_constancia_descuento'],2); ?>
	 			</span>
	 			
	 			<?PHP 
	 				}
	 			?>
	 		</td> 
	 	</tr>

	 	<tr>
	 		<td>
	 			<span class="sp11"> / Cociente del mes </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_calculo_factor'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Impuesto del mes </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_impuesto_delmes'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Retención ya efectuada en el mes </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11">
	 				<?PHP echo number_format($qr_info['qcr_retencion_delmes_acum'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 	<tr>
	 		<td>
	 			<span class="sp11"> Retención a efectuar </span>
	 		</td>
	 		<td>
	 			<span class="sp11"> : </span>		
	 		</td>
	 		<td align="right" >
	 			<span class="sp11b">
	 				<?PHP echo number_format($qr_info['qcr_retencion'],2); ?>
	 			</span>
	 		</td>
	 		<td></td>
	 	</tr>
	 </table>
</div>	