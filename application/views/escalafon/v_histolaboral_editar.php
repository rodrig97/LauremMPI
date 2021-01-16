

<div id="dvViewName" class="dv_view_name">
      
        <table class="_tablepadding2" border="0">

          <tr> 
              <td> 
                   <?PHP 
                             $this->resources->getImage('edit.png',array('width' => '22', 'height' => '22'));
                         ?>
              </td>

            <td>
                Modificar registro de historial laboral.
              </td>
          </tr>
      </table>
</div>
  

 <form id="form_info_historial" name="form_info_historial"  data-dojo-type="dijit.form.Form">   
                      
        <input type="hidden" name="view" value="<?PHP echo trim($datos_registro['persla_key']); ?>" />  

        <table class="_tablepadding4" width="100%" border="0">
               <tr height="35" class="row_form" >
                                  <td width="130"> <span class="sp12b">Régimen </span></td>
                                  <td width="20">:</td>
                                  <td> 
                                       <input id="hdhledit_tipo" type="hidden" value="<?PHP echo $datos_registro['plati_id']; ?>" />

                                       <select id="selhis_laboral" name="situlaboral"  data-dojo-type="dijit.form.Select" data-dojo-props='readOnly:true' class="formelement-35-12"  style="width:200px;" >
                                             <option value="0" selected="selected"> ------ </option>
                                             <?PHP 
                                                foreach($tipo_empleados as $tipoemp){
                                                    echo '<option value="'.trim($tipoemp['id']).'">'.trim($tipoemp['label']).'</option>';
                                                }
                                             ?>
                                      </select>
                                  </td>
                             </tr>

                             <tr id="trhis_periodo"  height="35" class="row_form">
                                 <td> <span class="sp12b"> Periodo de trabajo </span></td>
                                  <td>:</td>
                                  <td> 
                                       <label for="actual" class="sp12b"> Vigente</label>
                                       
                                        <input id="hdhledit_vigente" type="hidden" value="<?PHP echo $datos_registro['persla_vigente']; ?>" /> 
                                        
                                        <input id="chhis_actual" name="vigente" type="checkbox" value="1" data-dojo-type="dijit.form.CheckBox" data-dojo-props=""  />

                                         <span class="sp12b" style="margin:12px 8px 0x 8px;">Desde: </span>
                                        
                                         <input id="hdhledit_fechaini" type="hidden" value="<?PHP echo _get_date_pg($datos_registro['persla_fechaini']); ?>" /> 
                                         
                                         <input id="calhisdesde" type="text" class="formelement-100-11" data-dojo-type="dijit.form.DateTextBox"
                                                    data-dojo-props='type:"text", name:"fechaini", value:"01/01/1980",
                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                    lang:"es",
                                                    required:true,
                                                    promptMessage:"mm/dd/yyyy",
                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

                                                onChange=" "     
                                             />

                                        <!--
                                         <div id="dvhis_calhasta" style="display:inline;">
                                                    
                                             <span class="sp12b" style="margin:12px 8px 0x 8px;">Hasta: </span>
                                             
                                             <input id="hdhledit_fechafin" type="hidden" value="<?PHP echo ( trim($datos_registro['persla_fechafin']) != '' ) ? _get_date_pg($datos_registro['persla_fechafin']) : ''; ?>" />  
                                             <input id="calhishasta" type="text" class="formelement-100-11" data-dojo-type="dijit.form.DateTextBox"
                                                        data-dojo-props='type:"text", name:"fechafin", value:"01/01/1980",
                                                         constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                        lang:"es",
                                                        required:true,
                                                        promptMessage:"mm/dd/yyyy",
                                                        invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 

                                                    onChange=""   
                                                    />
                                         </div> -->

 
                                  </td>
                             </tr>

                       

                             <tr id="trhis_terminocontrato"  height="35" class="row_form">
                                  <td> <span class="sp12b"> Termino del contrato </span></td>
                                  <td>:</td>
                                  <td>  

                                        <div id="dvtermino_contrato" style="display:none;">
                                        
                                           <label for="terminocontrato" class="sp12b"> Indefinido</label>
                                           <input id="chhis_termino" name="terminoindefinido" type="checkbox" value="1" data-dojo-type="dijit.form.CheckBox"   />
                                          
                                        </div>

                                         <div id="dvhis_termino" style="display:inline;">
                                            
                                             <span class="sp12b" style="margin:12px 8px 0x 8px;">Hasta: </span>
                                             
                                             <input id="hdhledit_fechatermino" type="hidden" value="<?PHP echo ( trim($datos_registro['persla_fechafin']) != '' ) ? _get_date_pg($datos_registro['persla_fechafin']) : ''; ?>" />     
                                             
                                             <input id="calhistermino" type="text" class="formelement-100-11" data-dojo-type="dijit.form.DateTextBox"
                                                        data-dojo-props='type:"text", name:"fechatermino", value:"01/01/1980",
                                                         constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                        lang:"es",
                                                        required:true,
                                                        promptMessage:"mm/dd/yyyy",
                                                        invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

                                                    onChange=""     
                                                 />
                                         </div>
                                      

                                  </td>

                             </tr>

                             <tr id="tr_carnet_cc" height="35" class="row_form" style="display: none;">
                                 <td> <span class="sp12b"> Carnet de construcción civil </span></td>
                                  <td>:</td>
                                  <td> 
                                       <label for="actual" class="sp12b"> Presento </label>
                                      
                                       <input id="hdhledit_presento_carnet" type="hidden" value="<?PHP echo $datos_registro['persla_carnet_presento']; ?>" /> 
                                       
                                       <input id="chcarnet_presento" 
                                              name="carnet_presento" 
                                              type="checkbox" 
                                              value="1" 
                                              data-dojo-type="dijit.form.CheckBox" data-dojo-props=""  />
                                      
                                      <div id="dv_carnetPresento" style="display:none;">
                                         
                                         <div>
                                           
                                         <span class="sp12b" style="margin:12px 8px 0x 8px;"> Vigencia: </span>
                                       
                                         <input id="hdhledit_carnetfecha_inicio" type="hidden" value="<?PHP echo _get_date_pg($datos_registro['persla_carnet_fechainicio']); ?>" /> 
                                       
                                         <input id="calCarnetDesde" 
                                                type="text" class="formelement-100-11" 
                                                data-dojo-type="dijit.form.DateTextBox"
                                                    data-dojo-props='type:"text", name:"fechacarnet_desde", value:"01/01/1980",
                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                    lang:"es",
                                                    required:true,
                                                    promptMessage:"mm/dd/yyyy",
                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

                                                onChange="dijit.byId('calCarnetHasta').constraints.min = this.get('value');  "     
                                             />
                                         
                                         
                                         <input id="hdhledit_carnetfecha_fin" type="hidden" value="<?PHP echo _get_date_pg($datos_registro['persla_carnet_fechafin']); ?>" />

                                         <input id="calCarnetHasta" 
                                                type="text" class="formelement-100-11" data-dojo-type="dijit.form.DateTextBox"
                                                    data-dojo-props='type:"text", name:"fechacarnet_hasta", value:"01/01/1980",
                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                    lang:"es",
                                                    required:true,
                                                    promptMessage:"mm/dd/yyyy",
                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
                             
                                             />


                                         <span class="sp12b" style="margin:12px 8px 0x 8px;"> Número: </span>
                                         <input type="text" data-dojo-type="dijit.form.TextBox" style="font-size:11px; width: 70px; " data-dojo-props='type:"text", name:"fechacarnet_numero"' value="<?PHP echo $datos_registro['persla_carnet_numero']; ?>" />
                                         
                                         </div>
                                         
  

                                      </div>
                             
                                  </td>
                             </tr>


                            
                            <tr id="trhis_monto" height="35"  class="row_form">
                                  <td> <span class="sp12b"> Monto Contrato S./</span></td>
                                  <td>:</td>
                                  <td> 
                                      <input name="montocontrato" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:5" class="formelement-50-12" value="<?PHP echo $datos_registro['persla_montocontrato']; ?>" />
                                  </td>
                             </tr> 

                             <tr id="trhis_plaza" height="35"  class="row_form" style="display:none;">
                                  <td> <span class="sp12b">Plaza </span></td>
                                  <td>:</td>
                                  <td> 
                                      <input id="hdhledit_plaza" type="hidden" value="<?PHP echo ( trim($datos_registro['persla_plaza']) != '' ? trim($datos_registro['persla_plaza']) : '.'  ); ?>" />
                                      <input id="txthis_plaza"  type="text" name="plaza" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:50" class="formelement-150-12"  />
                                  </td>
                             </tr>
                             <tr id="trhis_cat" height="35"  class="row_form"  style="display:none;">
                                  <td> <span class="sp12b">Categor&iacute;a</span></td>
                                  <td>:</td>
                                  <td> 
                                       <select name="categoria" data-dojo-type="dijit.form.Select" data-dojo-props='' style="width: 150px; font-size:12px;">
                                          <option value="0" selected="selected"> -------- </option>
                                          <?PHP 
                                               foreach($categorias as $categoria){
                                          ?>
                                                   <option  value="<?PHP echo trim($categoria['catemp_key']);  ?>"> <?PHP echo $categoria['catemp_nombre']; ?> </option>

                                          <?PHP } ?>
                                       </select> 
                                  </td>
                             </tr>
                             <tr id="trhis_doc" height="35"  class="row_form">
                                  <td> <span class="sp12b">Doc. Referencia </span></td>
                                  <td>:</td>
                                  <td> 
                                      
                                      <input id="hdhledit_doc" type="hidden" value="<?PHP echo ( trim($datos_registro['persla_doc']) != '' ? trim($datos_registro['persla_doc']) : '.'  ); ?>" />
                                      <input id="txthis_documento"  name="documento" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-100-12"  />

                                      <span class="sp12b">Autoriza : </span> 
                                        
                                       <input id="hdhledit_aut" type="hidden" value="<?PHP echo ( trim($datos_registro['persla_docaut']) != '' ? trim($datos_registro['persla_docaut']) : '.'  ); ?>" /> 
                                       <input id="txthis_aut"  name="autoriza" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-200-12" /> 
                                  </td>
                             </tr>

                             <tr id="trhis_infosisgedo">
                                 <td colspan="3"> 

                                       <div class="info_sisgedo">
                                         
                                       </div>

                                 </td>

                             </tr>

                             <tr> 
                                 <td> 

                                 </td>

                             </tr> 


                              <tr id="trhis_depe" height="35"  class="row_form"  style="display:none;">
                                  <td> <span class="sp12b">Dependencia</span></td>
                                  <td>:</td>
                                  <td> 

                                      <input id="hdhledit_depe" type="hidden" value="<?PHP echo $datos_registro['depe_id']; ?>" />
                                      <select id="selhis_depe" name="dependencia" id="selhis_depe" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   '  style="width: 330px; font-size:12px;">
                                            <option value="0"> NO ESPECIFICADO </option>
                                            <?PHP 
                                                foreach($dependencias as $depe){
                                            ?>
                                                <option  value="<?PHP echo trim($depe['area_id']);  ?>"> <?PHP echo trim($depe['area_nombre']); ?> </option>

                                           <?PHP } ?>
                                      </select> 

                                  </td>
                             </tr>
                            <!--   <tr id="trhis_proy" height="35"  class="row_form"  style="display:none;">
                                  <td> <label for="hasproact" class="sp12b"> Actividad/Proyecto</label>

                                  </td>
                                  <td> : </td>
                                  <td> 

                                      <input name="hasproyect" id="chhis_proyecto" name="hasproact" type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="" />
                                      <div id="dvhis_proyecto" style="display:none;">
                                              <label for="hasproact" class="sp12b"> Especifique el proyecto: </label>
                                              <select id="selhis_proyecto" name="proyecto" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "El Proyecto no existe"   '  style="width: 330px; font-size:12px;">
                                                     <option value="0" selected="selected"> -------- </option>
                                                    <?PHP 
                                                        foreach($metas as $meta){
                                                    ?>
                                                        <option  value="<?PHP echo trim($meta['ano_eje'].'-'.$meta['sec_ejec'].'-'.$meta['sec_func']);  ?>"> <?PHP echo '(Año: '.trim($meta['ano_eje']).') '.trim($meta['nombre']); ?> </option>

                                                   <?PHP } ?>
                                              </select> 
                                      </div>
                                  </td>
                             </tr> -->

                              <tr id="trhis_cargo" height="35"  class="row_form"  style="display:none;">
                                  <td> <span class="sp12b">Cargo/Ocupación</span></td>
                                  <td>:</td>
                                  <td> 

                                       <input id="hdhledit_cargo" type="hidden" value="<?PHP echo $datos_registro['ocu_id']; ?>" />
                                    
                                       <select id="selhis_cargo"  
                                               name="ocupacion"  
                                               data-dojo-type="dijit.form.FilteringSelect" 
                                               data-dojo-props='name:"ocupacion", 
                                                               disabled:false, 
                                                               autoComplete:false, 
                                                               highlightMatch: "all",  
                                                               queryExpr:"*${0}*", 
                                                               invalidMessage: "Ocupacion no registrada" ' 
                                                               class="formelement-200-12" > 
                                             
                                              <option value="0" selected="true"></option>  
                                             <?PHP 
                                               foreach($ocupaciones as $ocu)
                                               {
                                                  
                                                    echo '<option value="'.trim($ocu['ocu_id']).'" > '.trim($ocu['ocu_nombre']).' </option>';
                                               }
                                             ?>
                                       </select>  
                                  </td>
                             </tr>


                             <tr id="trhis_cargo" height="35"  class="row_form"  style="display:none;">
                                  <td> <span class="sp12b">Cat.Remunerativa</span></td>
                                  <td>:</td>
                                  <td> 
                                       <select name="catrem" id="selhis_catrem" data-dojo-type="dijit.form.Select" data-dojo-props='' style="width: 150px; font-size:12px;">
                                                     <option value="0" selected="selected"> -------- </option>
                                                   <?PHP 
                                                        foreach($categoriasrem as $cate){
                                                    ?>
                                                        <option  value="<?PHP echo trim($cate['catre_id']);  ?>"  > <?PHP echo $cate['catre_nombre']; ?> </option>

                                                   <?PHP } ?>
                                       </select> 
                                  </td>
                             </tr>


                             <tr id="trhis_obs" height="35" class="row_form" style="display:none;"> 
                                  <td> <span class="sp12b">Descripcion/Obs</span></td>
                                  <td>:</td>
                                  <td> 

                                      <input id="hdhledit_descripcion" type="hidden" value="<?PHP echo $datos_registro['persla_descripcion']; ?>" />
                                      <div id="dvhis_descripcion"  data-dojo-props="name:'descripcion'" data-dojo-type="dijit.form.TextArea" class="formelement-350-11"></div>
                                  </td>
                             </tr> 
 
                         </table>

                              <div class="dv_form_toolbarcontainer" align="center">
                                  
                                <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                    <?PHP 
                                       $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                    ?>
                                       <label class="lbl11">Actualizar datos</label>
                                       <script type="dojo/method" event="onClick" args="evt">
                                                 Persona.Ui.btn_historia_actualizar_click(this,evt);  

                                       </script>
                                </button>
                              
                          </div>


                       </form>
        