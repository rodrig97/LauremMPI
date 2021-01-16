<html>

<head>

	<title>  Impresion de hoja de asistencia</title>

	<style> 
			#table_asistencia_calendario, #table_asistencia_calendario_resumen{
			  font-size: 11px;

			}

			#table_asistencia_calendario td, #table_asistencia_calendario_resumen td{
			   padding: 2px 2px 2px 2px;
			   border: 1px solid #99ccff;
			    

			}

			#table_asistencia_calendario .td_fecha, #table_asistencia_calendario_resumen .td_fecha{
			   padding: 0px 0px 0px 0px  !important;
			   border: 1px solid #99ccff !important;
			   text-align: center;
			   width: 20px;
			   cursor: pointer;
			}

			#table_asistencia_calendario .td_numeracion, #table_asistencia_calendario_resumen .td_numeracion{
			    width:35px;
			    text-align: center;
			 } 

			 #table_asistencia_calendario .td_nombre_trabajador, #table_asistencia_calendario_resumen .td_nombre_trabajador{
			    width:100px; 
			 } 
			 
			  
			 #table_asistencia_calendario  tr:Hover, #table_asistencia_calendario_resumen tr:Hover{
			      background-color:#FFFED9 !important;
			  } 
			 

			 #table_asistencia_calendario .tr_header td, #table_asistencia_calendario_resumen .tr_header td{
			  font-weight: bold; 
			  text-align:center;
			    border: 1px solid #99ccff; 
			    background-color: #deeeff; /*#dff4ff */
			 
			 }


			 #dvcontainer_calendar{

			    width:100%;
			    height: 100%;
			    overflow:  auto;  
			 }


			/* TIPOS DE CASILLAS */

			  #table_asistencia_calendario .dv_nocontar, #table_asistencia_calendario_resumen .dv_nocontar{
			      background-color:#DDDDDD ;
			      /*
			      display: block;
			      width: 100%;
			      height: 100%;*/
			      padding: 2px 2px 2px 2px;

			  }


	</style>

</head>

<body> 



	<div>
	             <table>
	                   <tr>
	                      <td width="35"> <span class="sp12b"> Codigo    </span> </td>
	                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
	                      <td width="70"> <span class="sp12"> <?PHP echo  (trim($hoja_info['hoa_codigo']) == '' ) ? '------'  : trim($hoja_info['hoa_codigo']);  ?>   </span> </td>
	                       <td colspan="9"> </td> 

	                   </tr>   
	                  <tr>
	                      <td width="35"> <span class="sp12b"> Desde    </span> </td>
	                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
	                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechaini'])); ?>   </span> </td>

	                      <td width="35"> <span class="sp12b"> Hasta    </span> </td>
	                      <td width="10" align="center">  <span class="sp12b"> :       </span> </td>
	                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechafin'])); ?>    </span> </td>

	                      <td width="94"> <span class="sp12b"> Tipo Trabajador    </span> </td>
	                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
	                      <td width="180"> <span class="sp12">  <?PHP echo trim($hoja_info['tipo_planilla']); ?>    </span> </td>

	                     
	                  
	                      <td width="75"> <span class="sp12b"> Descripcion </span> </td>
	                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
	                      <td width="250"> <span class="sp12">  <?PHP echo  (trim($hoja_info['hoa_descripcion']) == '' ) ? '-------------'  : trim($hoja_info['hoa_descripcion']); ?>    </span> </td>
	  
	                  </tr> 
	                
	             </table>

	</div>



	<?PHP
	 
	if(sizeof($calendario) == 0){


		  echo ' <div class="dv_busqueda_personalizada" style="font-size:11px"> La hoja de asistencia no tiene trabajadores asignados</div> ';

	}


	$_DIAS = array(
				  '1' => 'L',
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
	$w_table =  ( ($n_fields - 2) * 35 ) + 340;

	 

	?>



	<table id="table_asistencia_calendario" border="1" width="<?PHP echo $w_table; ?>" > 


	<?PHP

	$counter_trabajadores = 0;

	$mes_act = 0;
	$cc_hm = 0; /* contador de dias del mes, para el colspan del header mes*/


	$trabajador_fila = '';

	foreach($calendario as $ind =>  $reg)
	{

		if($ind==0){

			/* HEADER DEL CALENDARIO */ 
	 	 	$header = array_keys($reg);


	 	 		/* FILA, NOMBRE DEL MES */
				echo "<tr class='tr_header'>
						<td rowspan='3' class='td_numeracion' > # </td>
						<td rowspan='3' width='300'> Nombres y Apellidos </td>
					 ";

				foreach($header as $k => $field){
				 	
				  
			 		   if($k > 2){ 
	 		
							$dia = strtotime($field);	
							$mc  = date('n',$dia);

			 		        if($mc != $mes_act && $k>3 )
			 		        {

			 		        	
			 		        	echo " <td colspan='".$cc_hm."'>".$_MESES[$mes_act]."</td>";
			 		        	$mes_act = $mc;
			 		        	$cc_hm=0;
			 		        }else{
			 		        	$mes_act = $mc;
			 		        }	
			 		        
			 		        $cc_hm++;

			 		      

			 		   }  

				}
				echo " <td colspan='".$cc_hm."'>".$_MESES[$mes_act]."</td>";

			    echo "</tr>";

			    /*  FILA, INICIAL DE LOS DIAS */
	 	 	 	echo "<tr  class='tr_header'>";

				foreach($header as $k => $field){
				 	
				 	 
			 		   if($k > 2){ 
	 		
			 		        $dia = strtotime($field);
			 		        echo "<td>".$_DIAS[date('N',$dia)]."</td>"; 

			 		   } 

				}

			    echo "</tr>";


			    /* FILA, NUMERO DEL DIA*/
			    echo "<tr  class='tr_header'>";

				foreach($header as $k => $field){
				 	
				 	 
			 		   if($k > 2){ 
	 		
			 		        $dia = strtotime($field);
			 		        echo "<td>".date('d',$dia)."</td>"; 

			 		   } 

				}

			    echo "</tr>";

		}




		/* CUERPO DE LA TABLA */
	 	 
	 	echo "<tr class='tr_detalle'>";
	 	 /* impresion de las celdas de cada registro */

	 	$counter_trabajadores++; 
	   
	    $col = 1;
		foreach($reg as $k => $field){

			 if($k=='indiv_key'){		 	


					$trabajador_fila = trim($field);

			 	echo "<td class='td_numeracion'>".$counter_trabajadores."</td> 
	 		 		  <td class='td_nombre_trabajador'> 
	 		 		  	    <input type='hidden' value='".$trabajador_fila."'  class='spdetcal_indkey' /> ";

	 		  if($hoja_info['estado_id'] == HOJAASIS_ESTADO_ELABORAR )	 echo " <span class='spdetcal_delete'>[X]</span>  ";
	 
	 	        echo     $reg['trabajador']."</td>  ";
			 } 
			 else if($k>2){	

			 	$fecha_corta = _get_date_pg($k);   
			 	$ft =  strtotime($k);
			    $anio = date('Y', $ft );
			    $mes = $_MESES[date('n', $ft)];
			    $dia = date('d', $ft);
			    $dia_sem = date('N', $ft);
			    $dial = $_DIAS_L[date('N', $ft)];
	 			$fecha_larga =  $dial.', '.$dia.' de '.$mes.' del '.$anio;

	 			//$mensaje_tt = "  ".$fecha_larga." ";
	 
					$data      = explode('_',$field);
					$tipo      = trim($data[0]);
					$obs 	   = trim($data[1]);
					$tardanzas = trim($data[2]);
					$permisos  = trim($data[3]);
	 /*
				  	$_colores = array(

							ASISDET_ASISTENCIA       => '#FFFFFF',
							ASISDET_NOCONSIDERADO    => '#DDDDDD',
							ASISDET_DESCANSOMEDICO   => '#FF8B53', 
							ASISDET_COMISIONSERV     => '#80FF80',
							ASISDET_LICENCIASIND     => '#80FF80',
							ASISDET_VACACIONES       => '#FF8B53',
							ASISDET_FALTA            => '#FC2525',
							ASISDET_FALTAJUSTIFICADA => '#FC2525',
							ASISDET_LICENCIAGOCE     => '#FF8B53',
							ASISDET_LICENCIASINGOCE  => '#FC2525'
	 
				  	);  
	 			 	
	 			 	$_labels  = array( 
							ASISDET_ASISTENCIA       => 'A',
							ASISDET_NOCONSIDERADO    => '',
							ASISDET_DESCANSOMEDICO   => 'Des.Med', 
							ASISDET_COMISIONSERV     => 'Com.Serv',
							ASISDET_LICENCIASIND     => 'Lic.Sind',
							ASISDET_VACACIONES       => 'Vac.',
							ASISDET_FALTA            => 'F',
							ASISDET_FALTAJUSTIFICADA => 'F.Jus',
							ASISDET_LICENCIAGOCE     => 'Lic.Goce',
							ASISDET_LICENCIASINGOCE  => 'Lic.Sn.Goc'
	 
				  	);  */

	  			   $label = ($tipo == ASISDET_ASISTENCIA || $tipo == ASISDET_NOCONSIDERADO ) ? '' : $rs_estados_dias[$tipo]['hatd_label'];


	  			   $label_tt = '';
			 	    if($tipo == ASISDET_NOCONSIDERADO && $obs != '' ) $label = 'Obs'; 
	 				
			 	    if($tipo == ASISDET_ASISTENCIA  ){

			 	    	if($permisos!='0')
			 	        {
			 	        	$label.= 'P:'.$permisos;
			 	        	$label_tt.='  Permisos: '.$permisos;
			 	        }	

			 	        if($tardanzas!='0')
			 	        {
						   $label.= ' T:'.$tardanzas;
						   	$label_tt.=' | Tardanzas:'.$tardanzas;		 	        	
			 	        }	

			 	    	 
			 	    } 



					$obs = ($obs != '') ? $obs : '-------';

				   $mensaje_tt = "
			 					<table style=\"font-size:12px;\" class=\"_tablepadding4\"> 
			 						<tr> 
			 							 <td colspan=\"3\">  <b>".$fecha_larga." (".$fecha_corta.")</b>   </td>
			 						</tr> 
			 						<tr>
			 							  <td width=\"60\"> 
			 							  	  <b> Trabajador</b>
			 							  </td> 
			 							  <td width=\"5\"> <b>:</b> </td>
			 							  <td> 
			 							  	  ".$reg['trabajador']."
			 							  </td> 
			 						</tr>
			 						<tr>
			 							  <td> 
			 							  	  <b> Estado </b>
			 							  </td> 
			 							    <td> <b>:</b> </td>
			 							  <td> 
			 							  	  ".$rs_estados_dias[$tipo]['hatd_nombre']."
			 							  </td> 
			 						</tr>";
	  			
			 				if($label_tt != ''){

			 				  $mensaje_tt.=" <tr> 
			 														<td> </td> 
			 														<td> </td> 
			 														<td>".$label_tt."</td>  
			 												  </tr>";		

			 				}									  	

			 		 $mensaje_tt .= "		
	 								<tr>
			 							  <td> 
			 							  	 <b> Observacion </b> 
			 							  </td> 
			 							    <td> <b>:</b> </td>
			 							  <td> 
			 							  	  ".$obs."
			 							  </td> 
			 						</tr> 
			 					  
			 				  </table>";

	 
	 		 		 $bg_color = $rs_estados_dias[$tipo]['hatd_color'];

	 		 		 if( $dia_sem == 6 ||  $dia_sem == 7){

	 		 		 	 $bg_color = '#deeeff';

	 		 		 }

	 				 echo "<td  width='60' class='td_fecha' style='background-color:".$bg_color."' > 

	 				 	     <input type='hidden' class='colposition' value='".$col."' />
	 				 	     <input type='hidden' class='estadodia' value='' />
	 				 	     <input type='hidden' class='tdhojafecha' value='".$k."' /> 
	 				 	     <input type='hidden' class='tdhojatrabajador' value='".$trabajador_fila."' /> 
	 				 	     <input type='hidden' class='ttmensaje' value='".$mensaje_tt."' /> 
	 				 	     <div class='sp10'>
	 				 	     	  ".$label." 
	 				 	     </div> 
	 				  	  </td>"; // .$field.

	 		 
	 
		 		 $col++;
	 		 }
	 		  
		}

	    echo "</tr>";

	}

	?>

	</table>


</body>

</html>