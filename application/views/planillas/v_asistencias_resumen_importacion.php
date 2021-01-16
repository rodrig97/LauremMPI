
 <?PHP

//	var_dump($calendario);

$_DIAS = array(   '1' => 'L',
				  '2' => 'M',
				  '3' => 'M',
				  '4' => 'J',
				  '5' => 'V',
				  '6' => 'S',
				  '7' => 'D'  );

$_DIAS_L = array(

				  '1' => 'Lunes',
				  '2' => 'Martes',
				  '3' => 'Miercoles',
				  '4' => 'Jueves',
				  '5' => 'Viernes',
				  '6' => 'Sabado',
				  '7' => 'Domingo'  );


$_MESES = array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');


$n_fields = sizeof($calendario[0] );
$w_table =  ( ($n_fields - 4) * 35 ) + 340;
 
?>
<form data-dojo-type="dijit.form.Form" id="frmimportarasistencia_view">
	<input id="hdhojaasistencia_viewid" name="hoja_k" type="hidden" value="<?PHP echo $hoja_info['hoa_key'];  ?>" />
</form>


<div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId:'spleyenda',position:['below']">
    
	 <table class="_tablepadding2_c" style="font-size: 11px;"> 
	<?PHP 
		 $c = 0;
		foreach($rs_estados_dias as $dia){
			
			 $c++;
			 echo " 
			 		 <tr class='row_form'> 
			 		 	 <td width='20' align='center'>".$c."</td>
			 		 	 <td width='20'> <b> ".$dia['hatd_label']." </b></td>
			 		 	 <td width='5'> : </td>
			 		 	 <td width='150'>".$dia['hatd_nombre']."</td>
			 		 </tr>
			 	  "; 
		}

	?>

	 </table>
 
</div>


<div class="dv_busqueda_personalizada_pa2">

	<table class="_tablepadding2">
		<tr>
			<td width="30">	
				<span id="spleyenda">  
			 		 		[L]
			 	</span>
			   <span class="sp11b"> Desde  </span>	
			</td>
			<td width="5">
				 <span class="sp11b"> :  </span>	
			</td>
			<td width="60">
				 <span class="sp11"><?PHP echo $hoja_info['hoa_fechaini'] ?>   </span>	
			</td>
			<td width="30">
			   <span class="sp11b"> Hasta  </span>	
			</td>
			<td width="5">
				 <span class="sp11b"> :  </span>	
			</td>
		    <td width="60">
				 <span class="sp11"><?PHP echo $hoja_info['hoa_fechafin'] ?>   </span>	
			</td>

			<td width="30">
			   <span class="sp11b"> Tipo  </span>	
			</td>
			<td width="5">
				 <span class="sp11b"> :  </span>	
			</td>
			<td>
				 <span class="sp11"><?PHP echo $hoja_info['tipo_planilla'] ?>   </span>	
			</td>
		</tr>	

		<tr>
			<td> 
  		     

				 <span class="sp11b"> Descripcion  </span>	
			</td>
			<td>
				 <span class="sp11b"> :  </span>	
			</td>
			<td colspan="7">
				  <span class="sp11"><?PHP echo (trim($hoja_info['hoa_descripcion']) != '') ? trim($hoja_info['hoa_descripcion']) : '------'; ?>   </span>	
				
			</td>
		</tr>
 	</table>
 



</div>

<div  style="height:280px;" >
 	
<?PHP 

if(sizeof($calendario) == 0)
{
	 echo ' No hay trabajadores ';
}
else
{

?>
  
	<table id="table_asistencia_calendario_resumen" border="1" width="<?PHP echo $w_table; ?>" > 


	<?PHP

	$counter_trabajadores = 0;

	$mes_act = 0;
	$cc_hm = 0; /* contador de dias del mes, para el colspan del header mes*/


	$trabajador_fila = '';

	foreach($calendario as $ind =>  $reg)
	{

		if($ind==0)
		{

			/* HEADER DEL CALENDARIO */ 
	 	 	$header = array_keys($reg);
 
	 	 		/* FILA, NOMBRE DEL MES */
				echo "<tr class='tr_header'>
						<td   class='td_numeracion' > # </td>
						<td> <input type='checkbox' name='importar[]' value='".$reg['indiv_key']."' class='testxx' /> </td>
						<td   width='300'> Trabajador </td>
						<td   width='60'> H.T</td>
					 ";

				foreach($header as $k => $field)
				{
					   if($k > 6)
			 		   { 
	 		 				echo " <td>".$field."</td>";		
			 		   }  
				}
				 
			    echo "</tr>";
	 

		}
	 	$counter_trabajadores++; 

		/* CUERPO DE LA TABLA */
	 	$indiv_id = $reg['indiv_id'];
	 	$existe  = $reg['existe'];

	 	$class = ($existe == '1') ? 'existe_preview' : 'no_existe_preview';

	 	$nombre_trabajador = " <span class='".$class."'> ".$reg['trabajador']." (".$reg['dni'].") </span>";

	 	$categoria = $reg['platica_nombre'];

	 	echo "<tr class='tr_detalle'>

	 		 		<td align='center'> ".$counter_trabajadores." </td>
	 		 		<td>  <input type='checkbox' name='importar[]' value='".$reg['indiv_key']."' class='testxx' />  </td>
	 		 		<td> 
	 		 			<div>".$nombre_trabajador." </div>
	 		 			<div> ".$categoria." </div>

	 		 		</td> 		
	 		 		<td width='60' align='center'>	 
	 		 			".$reg['horas_trabajadas']."
	 		 		</td>
	 		 ";

	    unset($reg['trabajador'], $reg['dni'],$reg['indiv_id'], $reg['platica_id'], $reg['platica_nombre'], $reg['existe'], $reg['horas_trabajadas']  );		 

	 	 /* impresion de las celdas de cada registro */
 
	    $col = 1;
		foreach($reg as $k => $field){

			echo " <td align='center'  width='60' > ".$field."</td> ";

	 		  
		}

	    echo "</tr>";

	}

	?>

	</table> 

<?PHP 

}	

?>

</div>