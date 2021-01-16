<div class="window_container" >
<!--
<div class="dv_subtitle3">
    Editar informacion del concepto Remunerativo
</div>
-->
        <form id="formNuevoConcepto" dojoType="dijit.form.Form">    
            
          <div class="dv_celeste_padding4">
            
              <table>
                 <tr>
                   <td width="130"> 
                       <span class="sp12b"> Nombre del concepto </span> 
                   </td>
                   <td width="20"> 
                        <span class="sp12b"> : </span> 
                   </td> 

                   <td width="260"> 
                       <?PHP echo trim($concepto_info['conc_nombre']).' ('.$concepto_info['tipo_planilla'].')'; ?>
                   </td> 
                    
                  <td width="120"> 
                      <span class="sp12b"> Afecto al trabajador </span> 
                  </td>
                  <td width="10"> 
                      <span class="sp12b"> : </span> 
                  </td> 
                  <td>
                     <!--
                      <input name="afecto" type="checkbox" data-dojo-props="name: 'afecto'"  dojoType="dijit.form.CheckBox" checked="checked" value="1"/> 
                     -->
                      <select id="selconcepto_afecto" data-dojo-type="dijit.form.Select" data-dojo-props="name:'afecto'" style="width:60px; font-size:11px;">
                           <option value="1" <?PHP if($concepto_info['conc_afecto'] == '1') echo ' selected="selected"'; ?>  > Si </option>
                           <option value="0" <?PHP if($concepto_info['conc_afecto'] == '0') echo ' selected="selected"'; ?> > No </option>
                      </select> 

                  </td> 

                   
                 </tr>
             </table> 

          </div>




        <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width:890px; height:420px;">
                <div dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"center" ' attachParent="true" style="width: 580px; " class="panelpegadito" >
                       
                      <table class="_tablepadding4" width="420">
                             <tr class="row_form">
                               <td width="200"> 
                                   <span class="sp12b"> Nombre del concepto </span> 
                               </td>
                               <td width="10"> 
                                    <span class="sp12b"> : </span> 
                               </td> 
                             <td> 
                                 <input id="text_field_concepto" name="nombreconcepto" dojoType="dijit.form.TextBox" maxlength="100" value="<?PHP echo trim($concepto_info['conc_nombre']); ?>" class="formelement-200-11"  /> 
                             </td> 
                          </tr>
                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Nombre corto (Boleta) </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 

                             <td> 
                                 <input name="nombrecorto" dojoType="dijit.form.TextBox" maxlength="50" value="<?PHP echo trim($concepto_info['conc_nombrecorto']); ?>" class="formelement-150-11"  /> 
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Alias en la Planilla </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>   
                                  <input name="nombreplanillon" dojoType="dijit.form.TextBox" class="formelement-150-11" data-dojo-props="maxlength:20, name:'nombreplanillon' " value="<?PHP echo trim($concepto_info['conc_planillon_nombre']); ?>" /> 
                                   
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Régimen</span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                  <input id="sel_newconc_tipopla" type="hidden" value="<?PHP echo trim($concepto_info['plati_key']); ?>" name="tipoplanilla" />
                                  <span class="sp12b"> <?PHP echo $concepto_info['tipo_planilla']; ?> </span>
                             </td> 
                          </tr>

                           <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Descripcion </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 

                             <td> 


                                  <input dojoType="dijit.form.Textarea"   value="<?PHP echo trim($concepto_info['conc_descripcion']); ?>"    data-dojo-props="name: 'descripcion', maxlength: 200" style="width: 200px; font-size: 11px;" />
                             </td> 
                          </tr>


                           <tr class="row_form" id="tr_concepto_nuevo_tipo" <?PHP if($concepto_info['conc_afecto'] == '0') echo ' style="display:none; "' ?> >
                             <td> 
                                 <span class="sp12b"> Tipo </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <select dojoType="dijit.form.Select"  data-dojo-props="name: 'tipo'" class="formelement-100-11"  style="width:120px;"  /> 
                                     <option value="1" <?PHP if($concepto_info['conc_tipo'] == '1') echo ' selected="true"' ?> >Ingreso</option>
                                     <option value="2" <?PHP if($concepto_info['conc_tipo'] == '2') echo ' selected="true"' ?> >Descuento</option>
                                     <option value="3" <?PHP if($concepto_info['conc_tipo'] == '3') echo ' selected="true"' ?> >Aportacion</option>
                                   </select>  
                             </td> 
                          </tr>

                          <tr class="row_form">
                                      <td> 
                                         <span class="sp12b"> Grupo </span> 
                                     </td>
                                     <td width="10"> 
                                          <span class="sp12b"> : </span> 
                                     </td> 
                                     <td>
                                       <?PHP // var_dump(($concepto_info['gvc_id'] == $g['gvc_id'] )); var_dump($grupos); echo 'Concepto: '.$concepto_info['gvc_id']; ?>
                                         <select id="selconceptogrupo" name="grupo"  dojoType="dijit.form.FilteringSelect"  data-dojo-props='name:"grupo", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Grupo no registrado" '   class="formelement-150-11" > 
                                             <option value="0" ></option>  
                                            <?PHP 
                                               foreach($grupos as $g){
                                                        echo "<option value='".$g['gvc_id']."' "; 
                                                        if( $g['gvc_id'] == $concepto_info['gvc_id'] ) echo  ' selected="true" ';  

                                                        echo " >".trim($g['gvc_nombre'])."</option>";
                                               }
                                            ?>
                                         </select>  
                                     </td> 
                          </tr>

                          <tr class="row_form" id="tr_concepto_nuevo_sunat" <?PHP if($concepto_info['conc_afecto'] == '0') echo ' style="display:none; "' ?>  >
                                      <td> 
                                         <span class="sp12b"> Concepto Sunat </span> 
                                     </td>
                                     <td width="10"> 
                                          <span class="sp12b"> : </span> 
                                     </td> 
                                     <td> 
                                     
                                         <select id="selconcepto_sunat"  name="conceptosunat"  dojoType="dijit.form.FilteringSelect"  data-dojo-props='name:"conceptosunat", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Concepto SUNAT no registrado" '  class="formelement-150-11" > 
                                             <option value="0"> NO ESPECIFICAR </option>  
                                            <?PHP 
                                               foreach($conceptosunat as $cosu)
                                               {
                                                  echo "<option value='".$cosu['cosu_id']."' ";

                                                  if($concepto_info['cosu_id'] == $cosu['cosu_id'])
                                                  {
                                                     echo ' selected="true"';
                                                  }

                                                  echo " >".trim($cosu['cosu_codigo']).' - '.trim($cosu['cosu_descripcion'])."</option>";
                                               }
                                            ?>
                                         </select>  
                                     </td> 
                          </tr>

                          <tr class="row_form">
                                    <td> 
                                        <span class="sp12b"> Afecto a cuarta </span> 
                                    </td>
                                    <td> 
                                        <span class="sp12b"> : </span> 
                                    </td> 
                                    <td>
                                        <input name="afecto_a_cuarta" type="checkbox" data-dojo-props="name: 'afecto_a_cuarta'"  dojoType="dijit.form.CheckBox" value="1"  <?PHP if($concepto_info['conc_afecto_cuarta'] == '1') echo 'checked="true"' ?> /> 
                                 
                                    </td> 
                          </tr>
<!-- 
                          <tr class="row_form">
                                    <td> 
                                        <span class="sp12b"> Afecto a Quinta </span> 
                                    </td>
                                    <td> 
                                        <span class="sp12b"> : </span> 
                                    </td> 
                                    <td>
                                        <input name="afecto_a_quinta" type="checkbox" data-dojo-props="name: 'afecto_a_quinta'"  dojoType="dijit.form.CheckBox" value="1" <?PHP if($concepto_info['conc_afecto_quinta'] == '1') echo 'checked="true"' ?> /> 
                                         
                                    </td> 
                          </tr> -->
                            

                          <tr class="row_form">
                                    <td> 
                                        <span class="sp12b"> Quinta categoría </span> 
                                    </td>
                                    <td> 
                                        <span class="sp12b"> : </span> 
                                    </td> 
                                    <td>
                                         
                                        <select data-dojo-type = "dijit.form.Select"   
                                                data-dojo-props="name: 'afecto_a_quinta'" 
                                                class="formelement-200-11" 
                                                style="width:220px;"  />   
                                            
                                            <option value="0" <?PHP if($concepto_info['conc_afecto_quinta'] == '0') echo ' selected="true"' ?> > No se considera </option> 
                                            <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_PROYECTABLE ?>" <?PHP if($concepto_info['conc_afecto_quinta'] == QUINTA_TIPO_CONCEPTO_PROYECTABLE) echo ' selected="true"' ?> > Remuneración y monto proyectable </option>
                                            <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_NOPROYECTABLE; ?>" <?PHP if($concepto_info['conc_afecto_quinta'] == QUINTA_TIPO_CONCEPTO_NOPROYECTABLE) echo ' selected="true"' ?> > Otro ingreso del mes NO proyectable </option>
                                         
                                         </select>  

                                    </td> 
                          </tr>

                           <tr class="row_form">
                                     <td> 
                                         <span class="sp12b"> Predeterminado </span> 
                                     </td>
                                     <td> 
                                         <span class="sp12b"> : </span> 
                                     </td> 
                                     <td>
                                         <input name="predeterminado" type="checkbox" dojoType="dijit.form.CheckBox" value="1"  <?PHP if($concepto_info['conc_esxdefecto'] == '1') echo 'checked="true"' ?> /> 
                                         <span class="sp10"> 
                                                Se incluye por defecto en todas las planillas para todos los trabajadores.
                                         </span>

                                     </td> 
                           </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Acceso directo </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <input name="acceso_directo" type="checkbox" data-dojo-props="name: 'acceso_directo'"  dojoType="dijit.form.CheckBox" value="1" <?PHP if($concepto_info['conc_accesodirecto'] == '1') echo 'checked="true"' ?>  /> 
                                 <span class="sp10"> 
                                      Que aparezca como una casilla para marcar desde la gestión rapida de datos del trabajador.
                                 </span>

                             </td> 
                          </tr>

 

                           <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Número máximo de veces permitido en un mes </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                 <input name="cantmaxmes" dojoType="dijit.form.TextBox" maxlength="50" class="formelement-50-11"  value="<?PHP echo trim($concepto_info['conc_max_x_mes']); ?>"  /> 

                                 <span class="sp10">Si es cero, no se restrige la cantidad de veces por mes  </span>
                             </td> 
                          </tr>
 

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Mostrar en impresion </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                  <select dojoType="dijit.form.Select"   data-dojo-props="name: 'mostrarimpresion'" class="formelement-200-11" style="width:180px;"  />   
                                     <option value="1" <?PHP if($concepto_info['conc_displayprint'] == '1') echo ' selected="true"' ?>>Siempre                  </option>
                                     <option value="2" <?PHP if($concepto_info['conc_displayprint'] == '2') echo ' selected="true"' ?> >Solo cuando es mayor a cero</option>
                                     <option value="3" <?PHP if($concepto_info['conc_displayprint'] == '3') echo ' selected="true"' ?> > Nunca </option>
                                   </select>  
                             </td> 
                           </tr>

                           <tr class="row_form">
                              <td> 
                                  <span class="sp12b"> Posición en la impresión </span> 
                              </td>
                              <td> 
                                   <span class="sp12b"> : </span> 
                              </td> 
                              <td> 
                                   <input name="posicionimpresion" dojoType="dijit.form.TextBox" class="formelement-50-11" data-dojo-props="maxlength:6, name:'posicionimpresion' "  value="<?PHP echo trim($concepto_info['conc_orden']); ?>"  /> 
                                    <span class="sp10"> Orden de visualizacion del concepto en la planilla y boletas.</span>
                              </td> 
                           </tr>
                            

                           <tr class="row_form">
                                     <td> 
                                         <span class="sp12b"> Restringir monto mínimo y máximo </span> 
                                     </td>
                                     <td> 
                                         <span class="sp12b"> : </span> 
                                     </td> 
                                     <td>
                                       
                                        <input type="hidden" class="hdconckey" value="<?PHP echo trim($concepto_info['conc_key']); ?>"/> 
                                          
                                          <?PHP 

                                             if($concepto_info['cmm_id'] != '')
                                             {
                                                echo ' <span class="sp11b"> Con restricción </span> ';
                                             }
                                          ?>  


                                          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                <?PHP     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));    ?>
                                                  <script type="dojo/method" event="onClick" args="evt">
                                                        Conceptos.Ui.btn_config_restringir_minmax(this,evt);
                                                  </script>
                                                  <label class="sp11">
                                                          Configuración
                                                  </label>
                                           </button>

                                     </td> 
                           </tr>
                     


                     </table> 
                    

                </div>
                <div   dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"right", splitter: true ' attachParent="true" style="width:410px;" class="panelpegadito" >
                     <div class="dv_subtitle4" align="center"> 
                            Afectacion Contable y Presupuestal
                     </div>

                     <table class="_tablepadding4"> 
                         <!--   <tr> 
                               <td colspan="3" align="center"> <span class="sp12b"> Afectacion Contable </span> </td>
                           </tr> 
                           <tr>  
                               
                               <td width="180"> <span class="sp12b"> Debe </span> </td> 
                               <td width="10"> : </td>
                               <td> 
                                   <select dojoType="dijit.form.FilteringSelect" data-dojo-props="name: 'cuendadebe', disabled: true "   class="formelement-200-11"> 
                                       
                                   </select> 
                               </td> 
                               
                           </tr> 
                            <tr>  
                               
                               <td> <span class="sp12b"> Haber </span> </td> 
                               <td> : </td>
                               <td> 
                                   <select dojoType="dijit.form.FilteringSelect"  data-dojo-props="name: 'cuendahaber', disabled: true"  class="formelement-200-11"> 
                                       
                                   </select> 
                               </td> 
                               
                           </tr>  -->
                            <tr> 
                               <td colspan="3" align="center"> <span class="sp12b"> Afectacion Presupuestal </span> </td>
                           </tr> 
                          
                           
                           <tr>  
                               
                               <td>   <span class="sp12b"> Partida Presupuestal </span> </td> 
                               <td> : </td>
                               <td> 
                                   <?PHP // echo $concepto_info['id_clasificador']; ?>
                                   <select id="selconcepto_clasificador"  dojoType="dijit.form.FilteringSelect" data-dojo-props='name: "partida", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Partida Presupuestal no es valida" '   class="formelement-250-11"> 
                                        
                                        <option value="0" <?PHP if($concepto_info['id_clasificador']=='' || $concepto_info['id_clasificador']== '0' ) echo " selected "; ?>> NO ESPECIFICAR </option>
                                        <?PHP
                                            foreach($partidas_presupuestales as $partida){

                                                echo "<option value='".$partida['ano_eje']."-".$partida['id_clasificador']."'";

                                                if( $partida['id_clasificador'] != '' && ( $partida['id_clasificador'] == $concepto_info['id_clasificador'] ) ) echo " selected = 'true' "; 
                                                
                                                echo " > ".$partida['codigo']." ".$partida['descripcion']."</option>";
                                            }
                                        ?>
                                   </select> 
                               </td> 
                                
                           </tr> 


                           <tr> 
                               <td colspan="3" align="center"> <span class="sp12b"> Afectacion Contable </span> </td>
                           </tr> 

                           <tr>  
                               
                               <td width="180"> <span class="sp12b"> Debe </span> </td> 
                               <td width="10"> : </td>
                               <td> 
                                   <select data-dojo-type="dijit.form.FilteringSelect" 
                                           data-dojo-props='name: "cuentadebe", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La cuenta contable es incorrecta"  '   
                                           class="formelement-200-11"> 

                                        <option value="0"> NO ESPECIFICAR </option>
                                        <?PHP
                                            foreach($cuentasContables as $cuenta){
                                                echo "<option value='".$cuenta['ccont_id']."' "; 

                                                if( $cuenta['ccont_id'] != '' && ( trim($cuenta['ccont_id']) == trim($concepto_info['cuentadebe_id']) ) ) echo " selected = 'true' "; 
                                                
                                                echo " > ".$cuenta['ccont_codigo']." - ".$cuenta['ccont_nombre']."</option>";
                                            }
                                        ?>
                                   </select> 
                               </td> 
                               
                           </tr> 
                            <tr>  
                               
                               <td> <span class="sp12b"> Haber </span> </td> 
                               <td> : </td>
                               <td> 
                                   <select data-dojo-type="dijit.form.FilteringSelect"   
                                           data-dojo-props='name: "cuentahaber", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La cuenta contable es incorrecta"  '   
                                           class="formelement-200-11"> 
                                         
                                         <option value="0"> NO ESPECIFICAR </option>
                                         <?PHP
                                             foreach($cuentasContables as $cuenta){
                                                 echo "<option value='".$cuenta['ccont_id']."' ";

                                                 if( $cuenta['ccont_id'] != '' && ( $cuenta['ccont_id'] == $concepto_info['cuentahaber_id'] ) ) echo " selected = 'true' "; 

                                                 echo " > ".$cuenta['ccont_codigo']." - ".$cuenta['ccont_nombre']."</option>";
                                             }
                                         ?>
                                   </select> 
                               </td> 
                               
                           </tr> 
                           
                           <tr> 
                              <td colspan="3"> 
                                   <div class="dv_subtitle4">Conceptos en los que se utiliza. </div>
                                   <div class="dv_cr_1"> 
                                        <?PHP 
                                            if(sizeof($operando_conceptos)==0) echo ' <span style="font-size:11px; color:#333; " >-- Ninguno -- </span>';
                                            foreach($operando_conceptos as $k => $cop){
                                                 echo  '<label> ';
                                                     if($k!=0) echo ',';
                                                 echo   $cop['conc_nombre'].' </label> ';
                                            }
                                        ?> 
                                   </div>
                               </td>
                           </tr>
                           
                       </table> 
                      
                    
                </div> 
                <div   dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"bottom", splitter: true ' attachParent="true" style="height: 170px;"   class="panelpegadito" >
         
                    <div> 
                        
                         <div class="dv_subtitle4">    

                            <span> Calculo del concepto </span>
                          
                             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                              <?PHP     $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));    ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                         Variables._V.nueva_variable.load({}); 
                                </script>
                                <label class="sp11">
                                        Crear nueva variable
                                </label>
                             </button>

                            <!--
                             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                              <?PHP     $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));    ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                         Conceptos._V.condiciones.load({}); 
                                </script>
                                <label class="sp11">
                                        Condiciones
                                </label>
                             </button>
                             -->

                         </div>

                         <div style="margin: 0px 0px 0px 10px "> <?PHP echo $ecuacion; ?> </div>
                       
                    </div>
                    
                        <div id="dv_operator_factory"> 
                            <div id="dv_operation_base" class="dv_label_operation_fconcepto"> 
                               Concepto =   
                            </div> 
                            
                            <?PHP
                            
                            $c = 0;  $nc =true;
                      
                            $i = 0;
 
                            foreach($ecuacion_encode as $kl => $linea){   
                                 
                             
                            ?> 
                         <div class="concepto_line"  >   
                        
                                <div class="line_parte" id="oparte-<?PHP echo ($kl+1); ?>" > 

                                    <div class="operator_comp"> 
                                        <input type="hidden" class="hdtipocomponente" value="abre_parentesis" />
                                        <input type="hidden" class="hdestadoparentesis" value="<?PHP echo ($linea[0]=='1') ? '1' : '0';    ?>" />
                                        <label  class="<?PHP echo ($linea[0]=='1') ? 'operator_parentesis_select' : 'operator_parentesis';    ?>">(</label>
                                    </div>

                                    <div class="operator_comp">
                                        <input type="hidden" value="<?PHP echo $linea[1]; ?>" class="hdvalor_select" />
                                        <input type="hidden" class="hdtipocomponente" value="operando" />
                                        <select class="seloperando" data-dojo-type="dijit.form.FilteringSelect" 
                                             data-dojo-props='  /*value: "id",
                                                                label : "nombre",  
                                                                store: Conceptos._M.store_conceptos_variables,  */
                                                                style : "width:150px; font-size:11px;",
                                                                autoComplete: false,
                                                                highlightMatch: "all",  
                                                                queryExpr:"*${0}*"

                                                                ' >
                                                                <?PHP 
                                                                $set =false;
                                                                  foreach($conceptos_y_variables as $cv){

                                                                     echo "<option value='".$cv['codigo']."' "; 
                                                                     if( trim($linea[1]) == trim($cv['codigo']) ){
                                                                          echo " selected='true'";
                                                                          $set = true;
                                                                     }
                                                                     echo " > ".$cv['nombre']."</option> ";


                                                                  }
                                                                  if(!$set) echo '<option value="" selected="true">'.$linea[1].'</option> ';
                                                                ?> 

                                                              </select>
                                    </div>

                                     <div class="operator_comp"> 
                                        <input type="hidden" class="hdtipocomponente" value="cierre_parentesis" />
                                        <input type="hidden" class="hdestadoparentesis" value="<?PHP echo ($linea[2]=='1') ? '1' : '0';    ?>" />
                                        <label class="<?PHP echo ($linea[2]=='1') ? 'operator_parentesis_select' : 'operator_parentesis';    ?>">)</label>
                                    </div>

                                      <div class="operator_comp"> 
                                        <input type="hidden" class="hdtipocomponente" value="operador" />
                                        <select data-dojo-type="dijit.form.Select" class="seloperador" id="seloperador-<?PHP echo ($kl+1); ?>"> 
                                            <option value="¿"></option>
                                            <option value="+" <?PHP echo ($linea[3] == '+') ? ' selected="true"' : ''; ?> > + </option>
                                            <option value="-" <?PHP echo ($linea[3] == '-') ? ' selected="true"' : ''; ?> > - </option>
                                            <option value="x" <?PHP echo ($linea[3] == 'x') ? ' selected="true"' : ''; ?> > X </option>
                                            <option value="/" <?PHP echo ($linea[3] == '/') ? ' selected="true"' : ''; ?> > / </option>
                                        </select>
                                       <input type="hidden" class="hdidselectoperador" value="seloperador-<?PHP echo ($kl+1); ?>" />
                                        <input type="hidden" class="hdparteid" value="oparte-<?PHP echo ($kl+1); ?>" />
                                    </div>


                                </div>
                     
                           </div>   
                            <?PHP 
                                $c++;
                                
                            }
                            /*
                                 if($c==2 ){
                                     $c = 1;
                                     echo '</div>';
                                 }   $nc = true;
                                 $c++;
                            } */
                           //  if(!$nc) echo '</div>'; 
                            ?>
                            
                            <label id="delete_last_operation" class="abre_parentesis"> X </label>    
                        </div>
                     
                </div> 
        </div>
            
        <div align="center">
              <input type="hidden" value="<?PHP echo trim($concepto_info['conc_key']); ?>" class="hdobjectkey"  />  
             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));    ?>
                    <script type="dojo/method" event="onClick" args="evt">
                         Conceptos.Ui.btn_actualizar_concepto(this,evt);
                    </script>
                    <label class="sp11">
                            Actualizar Concepto Remunerativo
                    </label>
             </button>
        </div>
    
    </form>
</div>