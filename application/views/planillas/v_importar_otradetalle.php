
<table class="_tablepadding4">
	<tr>	
 		<td>
 			<span class="sp11b"> Tipo </span>
 		</td>	
 		<td>
 			<span class="sp11b"> : </span>	
 		</td>
 		<td colspan="4"> 
 			<span class="sp11"> <?PHP echo $info['tipo']; ?>  </span>
 		</td>
 	</tr>	
 	<tr>	
 		<td>
 			<span class="sp11b"> Planilla </span>
 		</td>	
 		<td>
 			<span class="sp11b"> : </span>	
 		</td>
 		<td> 
 			<span class="sp11"> <?PHP echo $info['pla_codigo']; ?>  </span>
 		</td>
 	 	
 		<td>
 			<span class="sp11b"> Mes - Año </span>
 		</td>	
 		<td>
 			<span class="sp11b"> : </span>	
 		</td>
 		<td> 
 			<span class="sp11"> <?PHP  echo $info['pla_mes']."/".$info['pla_anio'];?>  </span>
 		</td>
 	</tr>	
 	<tr>
 		 <td>
 			<span class="sp11b"> Tarea </span>
 		</td>	
 		<td>
 			<span class="sp11b"> : </span>	
 		</td>
 		<td colspan="4"> 
 			<span class="sp11"> 
 			  <?PHP 
                  echo  ($info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA ) ?   (trim($info['tarea_codigo']).' '.substr(trim($info['tarea_nombre']),0,80).'..' ) : ' Tarea presupuestal especificada por cada trabajador de la planilla' ;
            ?></span>
 		</td>
 	</tr>
</table>


<div id="dv_importacion_otraempleados">


</div>