

<div class="subtitle_red"> 

	REPORTE DE AFP

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

					 		     echo ($reporte_info['plati'] == '' ? '------' : $reporte_info['plati'] );
					 		 ?>
					 </span>
				</td> 	 	
			</tr> 

			<tr> 
				 <td> 
				 	 <span class="sp11b"> 
					 	 AFP 
					 </span> 		  
				 </td>
				 <td   align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td colspan="4"> 
					 <span class="sp11">
					 		 <?PHP 
					 		 	 echo ($reporte_info['afp_nombre'] == '' ? '------' : $reporte_info['afp_nombre']);
					 		 ?>
					 </span>
				</td> 	
				<td> 
				 	 <span class="sp11b"> 
					 	 Tipo Gasto 
					 </span> 		  
				 </td>
				 <td   align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td > 
					 <span class="sp11">
					 		 <?PHP  
					 		 	  echo ($reporte_info['tipogasto'] == '' ? '------' : $reporte_info['tipogasto']);
					 		 ?>
					 </span>
				</td> 	  
			</tr>

			<tr>
				<td> 
				 	 <span class="sp11b"> 
					 	 Planillas Seleccionadas
					 </span> 		  
				</td>
				<td   align="left">
					 <span class="sp11b">
					 		:
					 </span>
				</td> 	
				<td colspan="7"> 
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



<div class="dv_form_1">
 	 <table id="table_reportes_preview" class="table_reporter"> 

 			<tr class='tr_header_celeste'>  
 				<td width="10"> 
 					#	
 				</td>	
 				 
 				<td width="60">
 					Apellido Paterno
 				</td>
 				
 				<td width="60">
 					Apellido Materno
 				</td>

 				<td width="90">
 					Nombre	
 				</td>

 				<td width="80">
 					DNI
 				</td>

 				<td width="110">
 					Tipo de Trabajador
 				</td>
 
 				<td width="110">
 					AFP
 				</td>

 				<td width="100">
 					Codigo
 				</td>
 
 				<td width="20">
 					I/C
 				</td>

 				<td width="50">
 					Fecha I/C
 				</td>

 				<td width="50">
 					Tipo CS
 				</td>
 
 				<td width="60">
 					Pensionable
 				</td>
 
 				<td width="60">
 	 				Comision
 				</td>
 
 				<td width="60">
 					Jubilacion
 				</td>
 
 				<td width="60">
 					Invalidez
 				</td>

 				<td width="60">
 					Aporte C.S
 				</td>
 
 			</tr>


 			<?PHP 

 				if(sizeof($reporte) > 0 )
 			 	{


	 				$c = 0;
	 				foreach ($reporte as $reg)
	 				{
	  	
	 					$c++;


	 					$fecha = (trim($reg['fecha']) != '' ) ? _get_date_pg(trim($reg['fecha'])) : '';
	 					
	 					echo " 
	 							<tr class='row_form'>
	 								<td> ".$c." </td>
	 								<td> ".trim($reg['indiv_appaterno'])." </td>
	 								<td> ".trim($reg['indiv_apmaterno'])." </td>
	 								<td> ".trim($reg['indiv_nombres'])." </td>
	 								<td> ".trim($reg['indiv_dni'])." </td>
	 								<td> ".trim($reg['regimen'])." </td>
	 								<td> ".trim($reg['afp_nombre'])." </td>
	 								<td> ".trim($reg['peaf_codigo'])." </td>
	 								<td> ".trim($reg['tipo'])." </td>
	 								<td> ".$fecha." </td>
	 								<td> ".trim($reg['cs'])." </td>
	 								<td> ".trim($reg['PENSIONABLE'])." </td>
	 								<td> ".trim($reg['AFP - COMISION'])." </td>
	 								<td> ".trim($reg['AFP - JUBILACION'])." </td>
	 								<td> ".trim($reg['AFP - SEGURO'])." </td>
	 								<td> ".trim($reg['AFP APORTE CONS.CIV'])." </td>

	 							</tr>

	 						 ";

	  				}
	  			}
	  			else
	  			{

	  				 	echo  ' <tr> 
							 	 	 	 <td colspan="16">  

							 	 	 	 	
							 	  	 		<div class="sp11b"> No se enontraron registros </div>
							 		 	 </td>
							 		 </tr>';
	  			}
 						
 			?>


 	 </table>

</div>



<div style="margin: 4px 0px 0px 5px;">


	<form data-dojo-type="dijit.form.Form" id="frm_exportar_datos_afp" >

		<?PHP 
			foreach($datos_post as $key => $dato)
			{

				echo " <input type='hidden' name='".$key."' value='".$dato."' />";			

			}
		?>

	</form>

	<table>
		 <tr>
		 	<td> 	

		 		<?PHP 
		 		
		 		if(sizeof($reporte) > 0)
		 		{

		 		?>
				 		<input type="hidden" class="modoreporte" value="AFP" />	
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


</div>