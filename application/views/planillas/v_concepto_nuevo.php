<div class="window_container">

<div class="dv_subtitle3">
     Registrar un Nuevo Concepto Remunerativo
</div>

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
              <input name="nombreconcepto" dojoType="dijit.form.TextBox" data-dojo-props="name: 'nombreconcepto', conc_nombre:100"  class="formelement-250-12"  /> 
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
                 <option value="1" selected="selected"> Si </option>
                 <option value="0"> No </option>
            </select> 

        </td> 

         
       </tr>
   </table> 
</div>

<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width:890px; height:420px;">
        <div dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"center" ' attachParent="true" style="width: 580px;  " class="panelpegadito" >
               
              <table class="_tablepadding4" width="420">
                  
              
 
                  <tr class="row_form">
                     <td width="200"> 
                         <span class="sp12b"> Nombre corto (Boleta) </span> 
                     </td>
                     <td width="10"> 
                          <span class="sp12b"> : </span> 
                     </td> 

                     <td> 
                         <input name="nombrecorto" dojoType="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-150-11"  /> 
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
 
                          <input name="nombreplanillon" dojoType="dijit.form.TextBox" class="formelement-150-11" data-dojo-props="maxlength:20, name:'nombreplanillon' "  value=""  /> 
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
                          <select id="sel_newconc_tipopla"  dojoType="dijit.form.Select"   data-dojo-props="name: 'tipoplanilla'" class="formelement-150-11" style="width:180px;"  />   
                            <?PHP 
                                 foreach($tipos_planilla as $tipo){
                                      ?>
                                 <option value="<?PHP echo trim($tipo['plati_key']); ?>"><?PHP echo $tipo['plati_nombre']; ?></option>
                          <?PHP 
                                 }
                            ?>
                          </select>  
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


                          <input dojoType="dijit.form.Textarea"   data-dojo-props="name: 'descripcion', maxlength: 200" style="width: 200px; font-size: 11px;" />
                     </td> 
                  </tr>


                  <tr class="row_form" id="tr_concepto_nuevo_tipo">
                     <td> 
                         <span class="sp12b"> Tipo </span> 
                     </td>
                     <td> 
                          <span class="sp12b"> : </span> 
                     </td> 
                     <td>
                         <select dojoType="dijit.form.Select"  data-dojo-props="name: 'tipo'" class="formelement-100-11" style="width:120px;"  /> 
                             <option value="1" selected="true">Ingreso</option>
                             <option value="2" >Descuento</option>
                             <option value="3" >Aportacion</option>
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
                          <select id="selconceptogrupo" name="grupo"  dojoType="dijit.form.FilteringSelect" data-dojo-props='name:"grupo", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Grupo no registrado" ' class="formelement-150-11" > 
                               <option value="0" selected="true"></option>  
                                <?PHP 
                                   foreach($grupos as $g){
                                            echo "<option value='".$g['gvc_id']."'>".trim($g['gvc_nombre'])."</option>";
                                   }
                                ?>
                          </select>  
                      </td> 
                  </tr>


                 <tr class="row_form" id="tr_concepto_nuevo_sunat">
                        <td> 
                           <span class="sp12b"> Concepto Sunat </span> 
                       </td>
                       <td width="10"> 
                            <span class="sp12b"> : </span> 
                       </td> 
                       <td>
                           <select id="selconcepto_sunat" name="conceptosunat"  dojoType="dijit.form.FilteringSelect"  data-dojo-props='name:"conceptosunat", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Concepto SUNAT no registrado" '  class="formelement-150-11" > 
                               <option value="0" selected="true"> NO ESPECIFICAR </option>  
                              <?PHP 
                                 foreach($conceptosunat as $cosu){
                                          echo "<option value='".$cosu['cosu_id']."'>".trim($cosu['cosu_codigo']).' - '.trim($cosu['cosu_descripcion'])."</option>";
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
                                <input name="afecto_a_cuarta" type="checkbox" data-dojo-props="name: 'afecto_a_cuarta'"  dojoType="dijit.form.CheckBox" value="1"/> 
                               
                            </td> 
                  </tr> 

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
                                    
                                    <option value="0"> No se considera </option> 
                                    <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_PROYECTABLE; ?>"> Remuneración y monto proyectable </option>
                                    <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_NOPROYECTABLE; ?>"> Otro ingreso del mes NO proyectable </option>
                                 
                                 </select>  

                            </td> 
                  </tr>
<!-- 

                  <tr class="row_form">
                            <td> 
                                <span class="sp12b"> Quinta Cat. Ingresos no proyect. </span> 
                            </td>
                            <td> 
                                <span class="sp12b"> : </span> 
                            </td> 
                            <td>
                                <input name="afecto_a_quinta" type="checkbox" data-dojo-props="name: 'afecto_a_quinta'"  dojoType="dijit.form.CheckBox" value="1"/> 
                                 
                            </td> 
                  </tr> -->


                   <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Predeterminado </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <input name="predeterminado" type="checkbox" data-dojo-props="name: 'predeterminado'"  dojoType="dijit.form.CheckBox" value="1"/>
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
                                 <input name="acceso_directo" type="checkbox" data-dojo-props="name: 'acceso_directo'"  dojoType="dijit.form.CheckBox" value="1"/> 
                                  <span class="sp10"> 
                                       Que aparezca como una casilla para marcar desde la gestión rapida de datos del trabajador.
                                  </span>
                             </td> 
                   </tr>

                  
<!--
                     <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Obligatorio </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <input name="obligatorio" type="checkbox" data-dojo-props="name: 'obligatorio'" dojoType="dijit.form.CheckBox" value="1"/> 
                             </td> 
                   </tr> -->

                   <tr class="row_form">
                     <td> 
                         <span class="sp12b"> Número máximo de veces permitido en un mes </span> 
                     </td>
                     <td> 
                          <span class="sp12b"> : </span> 
                     </td> 
                     <td> 
                         <input name="cantmaxmes" dojoType="dijit.form.TextBox" maxlength="50" class="formelement-50-11" value="0" /> 

                         <span class="sp10">Si es cero, no se restrige la cantidad de veces por mes  </span>
                     </td> 
                  </tr>

                   

                   <input name="obligatorio" type="hidden"   value="0"/> 

                    <tr class="row_form">
                     <td> 
                         <span class="sp12b"> Mostrar en impresion </span> 
                     </td>
                     <td> 
                          <span class="sp12b"> : </span> 
                     </td> 
                     <td>
                          <select dojoType="dijit.form.Select"   data-dojo-props="name: 'mostrarimpresion'" class="formelement-200-11" style="width:180px;"  />   
                             <option value="1" selected="true">Siempre                  </option>
                             <option value="2" >Solo cuando es mayor a cero</option>
                              <option value="3" > Nunca </option>
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
                          <input name="posicionimpresion" dojoType="dijit.form.TextBox" class="formelement-50-11" data-dojo-props="maxlength:6, name:'posicionimpresion' "  value="0"  /> 
                          <span class="sp10"> Orden de visualizacion del concepto en la planilla y boletas.</span>

                     </td> 
                  </tr>
             


             </table> 
            

        </div>
        <div   dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"right", splitter: true ' attachParent="true" style="width:410px;" class="panelpegadito" >
             <div class="dv_subtitle4"> 
                    Afectacion Contable y Presupuestal
             </div>
        
             <table class="_tablepadding4"> 
               
                    <tr> 
                       <td colspan="3" align="center"> <span class="sp12b"> Afectacion Presupuestal </span> </td>
                   </tr> 
                  
                   
                   <tr>  
                       
                       <td>   <span class="sp12b"> Partida Presupuestal </span> </td> 
                       <td> : </td>
                       <td> 
                           <select id="selconcepto_clasificador" 
                                   dojoType="dijit.form.FilteringSelect"
                                   data-dojo-props='name: "partida", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Partida Presupuestal no es valida" '   class="formelement-180-11"> 
                                <option value="0" selected="true"> NO ESPECIFICAR </option>
                                <?PHP
                                    foreach($partidas_presupuestales as $partida){
                                        echo "<option value='".$partida['ano_eje']."-".$partida['id_clasificador']."'> ".$partida['codigo']." ".$partida['descripcion']."</option>";
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
                                    data-dojo-props='name: "cuentadebe", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Partida Presupuestal no es valida"  '   
                                    class="formelement-200-11"> 

                                 <option value="0" selected="true"> NO ESPECIFICAR </option>
                                 <?PHP
                                     foreach($cuentasContables as $cuenta){
                                         echo "<option value='".$cuenta['ccont_id']."'> ".$cuenta['ccont_codigo']." ".$cuenta['ccont_nombre']."</option>";
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
                                    data-dojo-props='name: "cuentahaber", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Partida Presupuestal no es valida"  '   
                                    class="formelement-200-11"> 
                                  
                                  <option value="0" selected="true"> NO ESPECIFICAR </option>
                                  <?PHP
                                      foreach($cuentasContables as $cuenta){
                                          echo "<option value='".$cuenta['ccont_id']."'> ".$cuenta['ccont_codigo']." ".$cuenta['ccont_nombre']."</option>";
                                      }
                                  ?>
                            </select> 
                        </td> 
                        
                    </tr> 

               </table> 
              
            
        </div> 
        <div   dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"bottom", splitter: true ' attachParent="true" style="height: 170px;"   class="panelpegadito" >
 
                <div class="dv_subtitle4"> 
                  <span> 
                    Calculo del concepto
                  </span>

                    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                      <?PHP     $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));    ?>
                        <script type="dojo/method" event="onClick" args="evt">
                                 Variables._V.nueva_variable.load({}); 
                        </script>
                        <label class="sp11">
                                Crear nueva variable
                        </label>
                     </button>
                </div>

                <div id="dv_operator_factory"> 
                    <div id="dv_operation_base" class="dv_label_operation_fconcepto"> 
                       Concepto =   
                    </div> 


                    <label id="delete_last_operation" class="abre_parentesis"> X </label>    
                </div>
             
        </div> 
</div>
    
<div align="center">
     <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
          <?PHP     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));    ?>
            <script type="dojo/method" event="onClick" args="evt">
                    Conceptos.Ui.btn_guardar_concepto(this,evt);
            </script>
            <label class="sp11">
                    Registrar Concepto Remunerativo
            </label>
     </button>
</div>
    
    </form>
</div>