<div class="window_container">

  <div id="dvViewName" class="dv_view_name">
      
    <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

         <td>
              Registrar Nueva Planilla  
          </td>
      </tr>
  </table>
</div>




<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:880px; height: 425px;">
     
     <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props=' splitter: true, region:"left", style:"width: 530px; padding: 0px 0px 0px 0px;"' >
     
         <form dojoType="dijit.form.Form" id="formnuevaplanilla"> 
             
                 <table class="_tablepadding4" >
                    <tr  class="row_form"> 
                       <td width="165">
                           <span class="sp12b">
                           Año 
                           </span>
                       </td>
                       <td width="5" width="10">
                           :
                       </td>
                            
                       <td width="330">
                            <select id="selplani_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:6px; font-size:12px; width: 80px;">
                            <!--    <option value="2012">2012</option>  -->

                                     <?PHP 
                                        foreach ($anios as $anio)
                                        {
                                          # code...
                                          echo '<option value="'.$anio['ano_eje'].'" ';

                                          if($anio['ano_eje']== date('Y'))
                                          {
                                             echo ' selected="selected" ';
                                          }

                                          echo ' >'.$anio['ano_eje'].'</option>';
                                        }
                                     ?>
                           </select>
                       </td>
                   </tr>
                   <tr class="row_form"> 
                       <td> 
                           <span class="sp12b"> Régimen </span>
                       </td>
                       <td>
                           :
                       </td>
                       
                       <td>
                           <select id="sel_crpla_tipoplanilla"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"planillatipo", disabled:false'
                                  style="margin-left:6px; font-size:12px; width:200px;">
                                 <?PHP
                                   foreach($tipos as $tipo){
                                        echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                   }
                                 ?>
                           </select>
                       </td>
                   </tr> 
                    <tr class="row_form"> 
                       <td> 
                           <span class="sp12b"> Tipo</span>
                       </td>
                       <td>
                           :
                       </td>
                       
                       <td>
                           <select  data-dojo-type="dijit.form.Select" data-dojo-props=' name:"tipo", disabled:false' style="margin-left:6px; font-size:12px; width: 120px;">
                               <option value="r"> Remuneracion </option> 
                               <option value="v"> Vacacional </option>
                           </select>
                       </td>
                   </tr> 

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
						   <?PHP echo (trim($this->usuario['anio_ejecucion']) >= 2021) ? 'Meta Presupuestal' : 'Tarea Presupuestal' ?>
                           </span>
                       </td>
                       <td>
                           :
                       </td>
                            
                       <td>
                            <!-- <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10"  class="formelement-50-11" style="margin-left:6px;"  />
                            -->
                            <select id="selnpla_tarea"  
                                    data-dojo-type="dijit.form.FilteringSelect" 
                                    data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  
                                                     queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' 
                                    style="margin-left:6px; font-size:11px; width: 250px;">
                               
                                <option value="0"> No Especificar </option>
                                   <?PHP
                                   foreach($tareas as $tarea){
										if (trim($tarea['ano_eje']) >= 2021) {
										   echo "<option value='".trim($tarea['tarea_id'])."'>(".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).') '.trim($tarea['tarea_nombre'])."</option>";
										} else {
										   echo "<option value='".trim($tarea['tarea_id'])."'>(".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea_nro']).') '.trim($tarea['tarea_nombre'])."</option>";
										}
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
                           <select id="sel_crpla_selfuente"   data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"fuente_financiamiento", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Fuente de Financiamiento no esta registrada" ' style="margin-left:6px; font-size:11px; width: 250px;">
                                
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
 
                   
                   <tr  class="row_form"> 
                       <td><span class="sp12b">Mes</span></td>
                       <td>:</td>
                       <td>
                           <select id="selplani_mes" data-dojo-type="dijit.form.Select" data-dojo-props='  name:"mes", disabled:false' style="margin-left:6px; font-size:12px; width: 95px;">
                               <option value="01">Enero</option> 
                               <option value="02">Febrero</option> 
                               <option value="03">Marzo</option> 
                               <option value="04">Abril</option> 
                               <option value="05">Mayo</option> 
                               <option value="06">Junio</option> 
                               <option value="07">Julio</option> 
                               <option value="08">Agosto</option> 
                               <option value="09">Septiembre</option> 
                               <option value="10">Octubre</option> 
                               <option value="11">Noviembre</option> 
                               <option value="12">Diciembre</option> 
                           </select>
                           
                           <label class="sp12b">Intervalo de Fechas: </label>
                           
                           <input id="chk_crpla_conintervalo" type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="name:'conintervalo'"  />
                       </td>
                   </tr>
                    <tr  class="row_form" id="tr_crpla_intervalo_row" style="display:none;"> 
                       <td><span class="sp12b">Especificar dias de pago</span></td>
                       <td>:</td>
                       <td> 
                           <span class="sp12b">Del: </span>
                           <div id="cal_crpla_desde" data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechadesde", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                            
                                              onChange="dijit.byId('cal_crpla_hasta').constraints.min = this.get('value'); dijit.byId('cal_crpla_hasta').set('value', this.get('value') );  "
                                            >
                                        </div> 
                           
                           <span class="sp12b"> Hasta: </span>
                           
                           <div id="cal_crpla_hasta"  data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechahasta", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
                                        </div> 
                       </td>
                   </tr>

                   <?PHP if(PLANILLA_SEMANA){  ?>
                    
                     <tr  class="row_form"> 
                         <td><span class="sp12b">Periodo</span></td>
                         <td>:</td>
                         <td> 
                             <div dojoType="dijit.form.TextBox" data-dojo-props='name:"semana" ' class="formelement-50-11" style="margin:0px 0px 0px 6px;"></div> 
                         </td>
                     </tr>

                   <?PHP } ?>

                   <tr  class="row_form"> 
                       <td><span class="sp12b">Descripcion</span></td>
                       <td>:</td>
                       <td> 
                           <div dojoType="dijit.form.Textarea" data-dojo-props='name:"descripcion" ' class="formelement-200-11" style="margin:0px 0px 0px 6px;"></div> 
                       </td>
                   </tr>

                    <tr  class="row_form"> 
                       <td><span class="sp12b">Considerar Categorias</span></td>
                       <td>:</td>
                       <td> 
                           
                          <select data-dojo-type="dijit.form.Select" data-dojo-props='  name:"considerar_categorias", disabled:false' style="margin-left:6px; font-size:12px; width: 80px;">
                              <option value="1" selected="selected"> Si </option> 
                              <option value="0">No </option> 
                              
                          </select>      

                       </td>
                   </tr>
                  
               </table>
             
         </form>
     
     </div>
    
    
     <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='  region:"center", style:"padding:0px 0px 0px 0px;"' >
         
         <div id="table_planillas_preview">
         </div>
         
     </div>
    
</div>


<div align="center" style="margin: 20px 0px 0px 0px;">
      

  <?PHP 
     //if($this->user->has_key('planilla.crear') ){    
  ?>

    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
      <?PHP 
         $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
      ?>
        <script type="dojo/method" event="onClick" args="evt">

              Planillas.Ui.btn_registrarplanilla_click(this,evt);   
            
        </script>
        <label class="sp12">
             Guardar y completar personal
        </label>
   </button>
  
  <?PHP // } ?>

</div>
    
</div>