<div class="window_container">

    <div id="dvViewName" class="dv_view_name">
	      
	    <table class="_tablepadding2" border="0">

	      <tr> 
	          <td> 
	               <?PHP 
	                         $this->resources->getImage('chart_up.png',array('width' => '22', 'height' => '22'));
	                     ?>
	          </td>

	         <td>
	               Actualizar la afectación Presupuestal 
	          </td>
	      </tr>
	  </table>
	</div>


	<div class="dv_busqueda_personalizada">

		<div>
			<span class="sp12b"> 
				 Afectación actual : 
			</span>

		</div>

		<div> 
		<?PHP 
		 

		   if( $plani_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA )
		   { 

		       if($plani_info['tarea_id'] != '')
		       {

		           echo '<span class="sp11b"> Tarea: </span> ';
		           echo '<span class="sp11"> '.(trim($plani_info['tarea_codigo']).' '.substr(trim($plani_info['tarea_nombre']),0,50).'..' ).'</span>';
		       }

		
		       if($plani_info['fuente_id'] != '' && $plani_info['tipo_recurso'] != '' )
		       {
		           echo '<span class="sp11b"> Fuente F: </span> ';
		           echo '<span class="sp11"> '.$plani_info['fuente_id'].' - '.$plani_info['tipo_recurso'].' ('.$plani_info['fuente_abrev'].') </span>';
		
		       } 

		       if($plani_info['clasificador_id'] != '' )
		       {
		           echo '<span class="sp11b"> Clasificador </span> ';
		           echo '<span class="sp11"> '.(substr(trim($plani_info['clasificador']),0,25).'..' ).'</span>';
		       
		       } 

		   }
		   else
		   {

		       echo ' <span class="sp12"> Especificada por cada trabajador de la planilla </span>';

		   }
		 ?>

		</div>


	</div>


		<form  data-dojo-type="dijit.form.Form" id="form_modificarafectacion_planilla">

		<div> 
			 <table class="_tablepadding4"  width="100%" height="100%">

					<tr class="row_form"> 
					    <td width="40"> 
					        <span class="sp12b"> Afectación Presupuestal </span>
					    </td>
					    <td>
					        :
					    </td>
					    
					    <td>
					        
					        <select id="sel_crpla_seltarea" name="afectacion_especificada" data-dojo-type="dijit.form.Select" data-dojo-props='name:"afectacion_especificada", disabled:false' style="margin-left:6px; font-size:12px; width: 120px;">
					            <option value="2" selected="true"> Usar la establecida en cada Trabajador y Concepto </option>
					            <option value="1"> Especificar la afectación </option>
					         </select>
					    </td>
					</tr> 
					
					
					
					<tr  class="row_form"  id="tr_crpla_seltarea_row" > 
					    <td>
					        <span class="sp12b">
					         Tarea
					        </span>
					    </td>
					    <td>
					        :
					    </td>
					         
					    <td>
					         <!-- <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10"  class="formelement-50-11" style="margin-left:6px;"  />
					         -->
					         <select id="selnpla_tarea"  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:6px; font-size:11px; width: 250px;">
					             <option value="0"> No Especificar </option>
					                <?PHP
					                foreach($tareas as $tarea){
					                     echo "<option value='".trim($tarea['tarea_id'])."'>(".trim($tarea['sec_func']).'-'.trim($tarea['tarea_nro']).') '.trim($tarea['tarea_nombre'])."</option>";
					                }
					              ?>
					        </select>
					    </td>
					</tr>
					
					<tr class="row_form" id="tr_crpla_selfuente_row" > 
					    <td> 
					        <span class="sp12b"> Fuente Financiamiento </span>
					    </td>
					    <td>
					        :
					    </td>
					    
					    <td>
					        <select id="sel_crpla_selfuente"   data-dojo-type="dijit.form.Select" data-dojo-props='name:"fuente_financiamiento", disabled:false' style="margin-left:6px; font-size:11px; width: 250px;">
					              
					         </select>
					    </td>
					</tr> 

					<tr class="row_form" id="tr_crpla_especificar_clasi_row" > 
					    <td><span class="sp12b"> Especificar clasificador </span></td>
					    <td>:</td>
					    <td> 
					        
					       <select id="selnpla_especificar_clasi" data-dojo-type="dijit.form.Select" 
					               data-dojo-props=' name:"especificar_clasificador", disabled:false' 
					               style="margin-left:6px; font-size:12px; width: 80px;">
					            <option value="1"  selected="selected" > Si </option> 
					           <option value="0" >No </option> 
					         
					       </select>      



					    </td>
					</tr>
					

					<tr class="row_form" id="tr_crpla_selclasi_row"  > 
					    <td> 
					        <span class="sp12b"> Clasificador </span>
					    </td>
					    <td>
					        :
					    </td>
					    
					    <td>
					     <?PHP 
					        if(CONECCION_AFECTACION_PRESUPUESTAL)
					        { 
					     ?>  

					        <select id="sel_crpla_selclasificador"   data-dojo-type="dijit.form.Select" data-dojo-props='name:"clasificador", disabled:false' 
					                style="margin-left:6px; font-size:11px; width: 250px;">
					        </select>

					        <?PHP 
					          }
					          else
					          {
					                                       ?>

					        <select id="sel_crpla_selclasificador"  dojoType="dijit.form.FilteringSelect" data-dojo-props='name: "clasificador", autoComplete:false, highlightMatch: "all",  queryExpr:"${0}*", invalidMessage: "La Partida Presupuestal no es valida" '    style="margin-left:6px; font-size:11px; width: 250px;"> 
					             
					             <option value="0" <?PHP if($concepto_info['id_clasificador']=='' || $concepto_info['id_clasificador']== '0' ) echo " selected "; ?>> NO ESPECIFICAR </option>
					             <?PHP
					                 foreach($partidas_presupuestales as $partida){
					                     echo "<option value='".$partida['id_clasificador']."'";
					                     if( $partida['id_clasificador'] != '' && ( $partida['id_clasificador'] == $concepto_info['id_clasificador'] ) ) echo " selected = 'true' "; 
					                     echo " > ".$partida['codigo']." ".$partida['descripcion']."</option>";
					                 }
					             ?>
					        </select> 

					        <?PHP 
					           }
					        ?>
					    </td>
					</tr> 

					<tr>

						<td colspan="3" align="center">

								 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
								   <?PHP 
								      $this->resources->getImage('accept.png',array('width' => '14', 'height' => '14'));
								   ?>
								     <script type="dojo/method" event="onClick" args="evt">
								         Planillas.Ui.btn_actualizarafectacion_planilla(this,evt);
								      
								     </script>
								      <label class="sp11">
								           Actualizar
								     </label>
								</button>

						</td>


					</tr>

		</table>
	</div>

	</form>
</div>