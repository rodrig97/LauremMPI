

<div class="subtitle_red"> 

	REPORTE DE BANCOS

</div>


<div class="dv_busqueda_personalizada">

	<div style="margin: 0px 0px 3px 0px;">
		<span class="sp11b"> Parametros del reporte </span>
	</div>

	<table class="_tablepadding2" border="0">

			<tr> 
				<td width="30"> 
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

				 <td> 
				 	 <span class="sp11b"> 
					 	 Banco 
					 </span> 		  
				 </td>
				 <td align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td> 
					 <span class="sp11">
					 	<?PHP 
					 		 echo $reporte_info['banco'];
					 	?>
					 </span>
				</td> 
				<td width="82">  
					 <span class="sp11b"> 
					 	  Régimen	
					 </span> 		    		
				</td>
				<td width="10" align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td width="160" width="60"> 
					 <span class="sp11">
					 		<?PHP 

					 		 	if( trim($reporte_info['plati']) != '')
					 		 	{
					 		 		 echo $reporte_info['plati'];			
					 		 	}
					 		 	else
					 		 	{
					 		 		echo '---------';
					 		 	}	
					 		 	
					 		 ?>
					 </span>
				</td> 	 	
			</tr> 


			<tr>
				<td width="30">  
					 <span class="sp11b"> 
					 	   Planillas seleccionadas
					 </span> 		    		
				</td>
				<td width="10" align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td colspan="10" width="160" width="60"> 
 

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

				</td> 	 	
	  
			</tr>

	</table>


</div>



<div id="dv_table_reportes_preview" class="dv_form_1">
 	 <table id="table_reportes_preview" class="table_reporter"> 
 		<tr class="tr_header_celeste">
 			<td width="30">		
 				#	
 			</td>

 			<td width="80">
 				Planilla
 			</td>	


 			<td width="100">
 				Apellido Paterno	
 			</td>	

 			<td width="100">
 				Apellido Materno	
 			</td>	

 			<td width="100">
 				Nombres	
 			</td>	
 			<td width="70">
 				DNI	
 			</td>	
 			<td width="160">
 				 Banco
 			</td>
 			<td width="120">
 				 Cuenta Bancaria
 			</td>
 			<td width="60">
 				Monto S./
 			</td> 
 		</tr>	

	<?PHP 
	
		$c = 0;
		$total = 0;

 		foreach($reporte as $reg)
 		{
 			$c++;
 	?>
 			
 			<tr class="row_form"> 
 			 	<td align="center"><?PHP echo $c; ?> </td>
 			  	<td><?PHP echo $reg['pla_codigo']; ?></td>
 			 	<td><?PHP echo $reg['indiv_appaterno']; ?></td>
 			 	<td><?PHP echo $reg['indiv_apmaterno']; ?></td>
 			 	<td><?PHP echo $reg['indiv_nombres']; ?></td>
 			 	<td><?PHP echo $reg['indiv_dni']; ?></td>
 			 	<td><?PHP echo $reg['ebanco_nombre']; ?></td>
 			 	<td><?PHP echo $reg['pecd_cuentabancaria']; ?> </td>
 			 	<td><?PHP echo sprintf("%01.2f", $reg['deposito']); ?> </td>

 			</tr>

 	<?PHP
 	    	 $total+= $reg['deposito'];
 	
 		}


 		if(sizeof($reporte) > 0)
 		{  
 		
 	?>	 
 
 		 <tr class="row_form"> 
 	 	 	 <td colspan="8"> 
 	  	 	
 		 	 </td>
 		 	 <td> 
 		 	 		 <?PHP  echo $total; ?>
 		 	 </td>
 		 </tr>
 
   <?PHP 

 		}
 		else
 		{

    ?>
	   	 <tr> 
 	 	 	 <td colspan="9">  

 	 	 	 	
 	  	 		<div class="sp11b"> No se enontraron registros </div>
 		 	 </td>
 		 </tr>
  
    <?PHP
 	   }
    ?>

 	 </table>

</div>



<div style="margin: 4px 0px 0px 5px;">
	<table>
		 <tr>
		 	<td> 	

		 		<?PHP 
		 			if(sizeof($reporte) > 0)
		 			{

		 		?>

			 		<input type="hidden" class="modoreporte" value="BANCO" />	
		 			<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	                      <?PHP 
	                         $this->resources->getImage('note_book.png',array('width' => '14', 'height' => '14'));
	                      ?>
	                    <script type="dojo/method" event="onClick" args="evt">
	                    		
	                            Exporter.Ui.btn_generar(this,evt);     

	                    </script>
	                    <label class="sp11">
	                          Generar Archivo
	                    </label>
	                </button>

                <?PHP 
                	}
                ?> 
		 	</td> 
		 </tr>
	</table>
<?PHP // echo var_dump(explode('.',sprintf("%01.2f", '54554.329')));  ?>

</div>