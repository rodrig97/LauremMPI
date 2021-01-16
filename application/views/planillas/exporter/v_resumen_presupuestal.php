
<div class="subtitle_red"> 

 	<?PHP echo $reporte_info['reporte_nombre']; ?>	

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
				 
			</tr> 
 
	</table>
	
	<div>
	    <span class="sp12b"> Planillas Seleccionadas:  </span>
	    <select  dojoType="dijit.form.FilteringSelect" 
	   					 		   data-dojo-props='autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La planilla no fue seleccionada" '    
	   					 		   style="margin-left:6px; font-size:12px; width: 120px;"> 

	   					      <?PHP
	   					       	
	   					       	 if( sizeof($reporte_info['planillas_codigos']) == 0)
	   					       	 {
	   					       	 	  echo "<option value = '0' > No se seleccionaron planillas </option>";
	   					       	 }
	   					       	 else
	   					       	 {
	   					       	 	  $c = 1;
	   						          foreach($reporte_info['planillas_codigos'] as $planilla)
	   						          {
	   						             echo " <option value='".$planilla."'> N°".$c.".- ".$planilla."</option> ";
	   						           
	   						             $c++;
	   						          }
	   					         }
	   					      ?>
	   					 </select>
	</div>
	
    
</div>

 
<div id="dv_table_reportes_preview"> 

	<table class="_tablepadding4" border="1" id="table_reportes_preview"> 

		<?PHP 

		    $headers = array_keys($estructura);
		    
		    echo "<tr class='tr_header_celeste' >";
		    
		    foreach($headers as $h)
		    {
		    	echo " <td>".$h."</td>";
		    }
		    
		    echo "</tr>";

		    $total = 0;

		    foreach($reporte as $reg)
		    {
		        echo "<tr class='tr_row_celeste'>";

		        $clasificador = trim($reg['clasificador_nombre']);

		        if(strlen($clasificador) > 50 )
		        {

		           $reg['clasificador_nombre'] = '..'.utf8_decode(substr($clasificador,25,25)); 
		           if(strlen($reg['clasificador_nombre'])>50) $reg['clasificador_nombre'].=".."; 

		        } 
		        else
		        {

		           $reg['clasificador_nombre']  = utf8_decode(substr($clasificador,0,25));
		           if(strlen($reg['clasificador_nombre'])>25) $reg['clasificador_nombre'].=".."; 

		        }


		        $meta = trim($reg['meta_nombre']);

		        if(strlen($meta) > 50 )
		        {

		           $reg['meta_nombre'] = '..'.utf8_decode(substr($meta,25,25)); 
		           if(strlen($reg['meta_nombre'])>50) $reg['meta_nombre'].=".."; 

		        } 
		        else
		        {

		           $reg['meta_nombre']  = utf8_decode(substr($meta,0,25));
		           if(strlen($reg['meta_nombre'])>25) $reg['meta_nombre'].=".."; 

		        }

		        foreach ($estructura as $v)
	 			{	

	 				$valor =  ($v=='total') ? sprintf("%01.2f",$reg[$v]) : $reg[$v];

		            echo "<td> ".$valor."</td>";
		        }	

		        echo "</tr>";

		        $total+= $reg['total'];

		    }
		?>
		
		<tr class="tr_row_celeste">
			<td colspan="<?PHP echo (sizeof($estructura) - 3 ); ?>">
				
			</td>
			<td>
				<?PHP echo $total; ?>
			</td>
			<td colspan="2"> </td>
		</tr>


	</table>
</div>

<form id="form_resumen_siaf" method="post" target="_blank" action="impresiones/resumen_siaf">

	<input type="hidden" name="planillas" value="<?PHP echo trim($planillas_keys_txt); ?>" />

	<input type="hidden" name="reporte" value="<?PHP echo trim($reporte_id); ?>" /> 

</form>

<div style="margin-top:5px;">  

  <button dojoType="dijit.form.Button" class="dojobtnfs_12" > 
      <?PHP 
         $this->resources->getImage('page_search.png',array('width' => '14', 'height' => '14'));
      ?>
	    <script type="dojo/method" event="onClick" args="evt">
 			 
 			 /*
	    	  var datos = dojo.formToObject('form_resumen_siaf');

	    	  datos.mode = 'resumen_presupuestal_siaf';

	    	  Impresiones._V.preview.load(datos); */

	    	  dojo.byId('form_resumen_siaf').submit();

	    </script>
	    <label class="sp12">
	         Visualizar en PDF  
	    </label>
  </button>

</div>