<?PHP 

 	$meses = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$proyectados = array();
 	$rentas_neta_anual = array();
 	$rentas_neta_global = array();
 	$impuesto_ala_renta = array();
 	$impuesto_anual = array();

 	$retenciones_anteriores = array();

 	$impuesto_a_pagaar = array();

 	$factor  = array(  '01' =>  array(12, array()),
 	                   '02' =>  array(12, array()),
 	                   '03' =>  array(12, array()),
 	                   '04' =>  array(9,  array('01', '02', '03')),
 	                   '05' =>  array(8,  array('01', '02', '03', '04')),
 	                   '06' =>  array(8,  array('01', '02', '03', '04')),
 	                   '07' =>  array(8,  array('01', '02', '03', '04')),
 	                   '08' =>  array(5,  array('01', '02', '03', '04', '05', '06', '07')),
 	                   '09' =>  array(4,  array('01', '02', '03', '04', '05', '06', '07','08')),
 	                   '10' =>  array(4,  array('01', '02', '03', '04', '05', '06', '07','08')),
 	                   '11' =>  array(4,  array('01', '02', '03', '04', '05', '06', '07','08')),
 	                   '12' =>  array(1,  array('01', '02', '03', '04'))
 	                ); 
?>


<table id="tableresumenquintacategoria" class="_tablepadding4" border="1">
 
	<tr class='tr_header_celeste'>
		<td> 
			<span class="sp12b"> Concepto  </span>
		</td>
		<td>
			<span class="sp12b"> Enero </span>
		</td>
		<td>
			<span class="sp12b"> Febrero </span>
		</td>
		<td>
			<span class="sp12b"> Marzo </span>
		</td>
		<td>
			<span class="sp12b"> Abril </span>
		</td>	
		<td>
			<span class="sp12b"> Mayo </span>
		</td>	
		<td>
			<span class="sp12b"> Junio </span>
		</td>	
		<td>
			<span class="sp12b"> Julio </span>
		</td>	
		<td>
			<span class="sp12b"> Agosto </span>
		</td>	
		<td>
			<span class="sp12b"> Septiembre </span>
		</td>	
		<td>
			<span class="sp12b"> Octubre </span>
		</td>	
		<td>
			<span class="sp12b"> Noviembre </span>
		</td>	
		<td>
			<span class="sp12b"> Diciembre </span>
		</td>	
	</tr>	

	<tr>
		<td> 
			<span class="sp12b"> Remuneración (Base Gratif y Proyectada)  </span>
		</td>

		<?PHP 
		 
			foreach ($meses as $mes)
			{
	 	?>

				<td>
					<span class="sp12"> 
				    <?PHP
		 				$valor = $data[$mes][1];
		 				echo number_format($valor,2); 
		 			?> 
		 			</span>
				</td>

		<?PHP 
			}
		?>  	
	</tr>

	<tr>
		<td> 
			<span class="sp12b"> Remuneración Pagada  </span>
		</td>

		<?PHP 
			foreach ($meses as $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 				$valor = $data[$mes][2];
	 				echo number_format($valor,2); 
	 			?> 
	 			</span>
			</td>
		<?PHP 
			}
		?>  	
	</tr>

	<tr style="background-color : #D2FFD2">
		<td> 
			<span class="sp12b"> N° Meses Restantes  </span>
		</td>
	
		<?PHP 

			$meses_restantes = array(11,10,9,8,7,6,4,3,2,1);

			foreach ($meses_restantes as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP 
			      	echo $mes;
	 			?> 
	 			</span>
			</td>
		<?PHP 
			}
		?>  	
		
	</tr>

	<tr>
		<td> 
			<span class="sp12b"> Remuneración Proyectada  </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 				$valor = $data[$mes][1];
	 			    $proyectado = $meses_restantes[$k] * $valor;
	 				
	 				$proyectados[$k] = $proyectado;

	 				echo number_format($proyectado,2); 
	 			?> 
	 			</span>
			</td>
		<?PHP 
			}
		?>  	

	</tr>
    <!--     $data_registros = array(
                                'REMUNERACION_PROYECTADA'   => array('1', $proyectar ),
                                'REMUNERACION_BASE'         => array('2', $base_mes),
                                'GRATI1'                    => array('3', $grati1),
                                'GRATI2'                    => array('4', $grati2),
                                'REMUNERACION_ANTERIOR'     => array('5', $remuneraciones_anteriores),
                                'RETENCIONES_ANTERIORES'    => array('6', $retenciones_anteriores),
                                'OTROS_INGRESOS'            => array('7', $otros_ingresos_mes),
                                'DESCUENTO_APLICADO'        => array('8', $impuesto_calculado)
                              );
 -->
	<tr>
		<td> 
			<span class="sp12b"> Gratificación Ordinaria (Julio)  </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 				$valor = $data[$mes][3];
	 		 		echo number_format($valor,2); 
	 			?> 
	 			</span>
			</td>
		<?PHP 
			}
		?>  	

	</tr>

	<tr>
		<td> 
			<span class="sp12b">   Gratificación Ordinaria (Diciembre)   </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			    $valor = $data[$mes][4];
	 				echo number_format($valor,2); 
	 			?> 
	 			</span>
			</td>
		<?PHP 
			}
		?>  	

	</tr>


	<tr>
		<td> 
			<span class="sp12b"> Remuneraciones Anteriores   </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			    $valor = $data[$mes][5];
	 				echo number_format($valor,2); 
	 			?> 
	 			</span>
			</td>
		<?PHP 
			}
		?>  	

	</tr>

	<tr>
		<td> 
			<span class="sp12b"> Renta neta anual  </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 			    $valor = $data[$mes][2] + $proyectados[$k] + $data[$mes][3] + $data[$mes][4] + $data[$mes][5];

	 			    $rentas_neta_anual[$k] = $valor;

	 				echo number_format($valor,2); 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>

	<tr style="background-color : #D2FFD2">
		<td> 
			<span class="sp12b"> Maximo 7 UITS </span>
		</td>
	
		<?PHP 
			$max_uit = 30800; // $max_uit = 26600;
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 			    
	 				echo number_format($max_uit,2); 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>


	<tr>
		<td> 
			<span class="sp12b">Renta neta global  </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 			   $rentas_neta_global[$k] = 	$rentas_neta_anual[$k] - $max_uit;

	 			   echo number_format($rentas_neta_global[$k],2); 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>


	<tr style="background-color : #D2FFD2">
		<td> 
			<span class="sp12b"> Hasta 27 UITs   </span>
		</td>
	
		<?PHP 
			$max_uit = 15;
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 			    
	 				echo number_format($max_uit,2).'%'; 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>

	<tr>
		<td> 
			<span class="sp12b"> Impuesto a la renta </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 			   $impuesto_ala_renta[$k] = 	$rentas_neta_global[$k] * 0.15;

	 			   echo number_format($impuesto_ala_renta[$k],2); 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>

	<tr>
		<td> 
			<span class="sp12b"> Impuesto Anual </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 	 
	 			   echo number_format($impuesto_ala_renta[$k],2); 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>


	<tr>
		<td> 
			<span class="sp12b"> Impuesto a pagar </span>
		</td>
	
		<?PHP 
			foreach ($meses as $k => $mes)
			{
		?>
			<td>
				<span class="sp12"> 
			    <?PHP
	 			     
	 	 
	 			   echo number_format($impuesto_ala_renta[$k],2); 
	 			?> 
	 			</span> 
			</td>
		<?PHP 
			}
		?>  	

	</tr>



</table>

