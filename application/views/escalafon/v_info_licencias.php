<div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >

<?PHP
  
   if( $this->user->has_key('TRABAJADOR_LICENCIAS_EDITAR') )
   { 

?>
                        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Registrar Nuevo </span>"'>
                                
                            <div class="dv_form_toolbarcontainer">

                                
                                   <input class="hduserkey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" />    
                                   <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Registrar licencia</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                        Persona.Ui.btn_registerlicencia_click(this,evt);     
                                            </script>
                                   </button>
                                
                            </div>
                                
                            <div class="dv_form_container">
                            
                              <form id="form_info_licencias"  data-dojo-type="dijit.form.Form">   

                                   <table class="_tablepadding4" width="100%">
                                           
                                             <tr id="trli_doc" height="40"   class="row_form">
                                                    <td width="120"> <span class="sp12b">Doc. Referencia </span></td>
                                                  <td width="10">:</td>
                                                  <td width="210">
                                                      <input name="documento" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100, trim:true  " class="formelement-200-12"  />
                                                  </td>
                                           
                                                  <td width="50"> <span class="sp12b">Autoriza </span></td>
                                                  <td width="10">:</td>
                                                  <td>
                                                      <input name="autoriza" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100, trim:true   " class="formelement-200-12"  />
                                                  </td>
                                             </tr>
                                             <tr id="trli_infosisgedo">
                                                 <td colspan="6"> 

                                                       <div class="info_sisgedo">
                                                         
                                                       </div>

                                                 </td>

                                             </tr>
<!--
                                             <tr height="40"   class="row_form">
                                                  <td> <span class="sp12b">Fecha del documento </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                         <div id="callicefecdoc"  data-dojo-type="dijit.form.DateTextBox"
                                                                    data-dojo-props='type:"text", name:"fechadoc", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
                                                         </div>

                                                  </td>
                                             </tr> -->
                                            <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b">Tipo</span></td>
                                                  <td>:</td>
                                                  <td colspan="4">
                                                        <select  name="tipo" data-dojo-type="dijit.form.Select" data-dojo-props='name: "tipo"' 
                                                                 class="formelement-100-12" style="width:150px;" >
                                                             
                                                            <?PHP 

                                                              foreach ($tipo_licencias as $reg)
                                                              {
                                                            
                                                               echo " <option value='".$reg['tipolic_id']."'> ".$reg['tipolic_nombre']." </option> ";
                                                              }
                                                            ?>
                                                        </select> 
                                                  </td>
                                             </tr>
                                        <!--     <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Motivo </span></td>
                                                  <td>:</td>
                                                  <td colspan="4"> 
                                             
                                                       <select id="sellicencia_motivo"  name="motivo" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-100-12" style="width:150px;" >
                                                             <option value="0" selected="selected"> -------- </option>
                                                             <option value="1">Salud del empleado</option>
                                                             <option value="2">Otro motivo</option>
                                                        </select> 
                                                      <input id="txtlicenciamotivo_des" name="motivo_des" type="text" data-dojo-type="dijit.form.TextArea" data-dojo-props="maxlength:100" class="formelement-250-12" style="display:none;" />
                                                  </td>
                                             </tr> -->
                                              <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b">Intervalo de tiempo</span></td>
                                                  <td>:</td>
                                                  <td colspan="4">

                                                       <span class="sp12b" style="margin:12px 8px 0x 8px;">Desde: </span>
                                                         <input id="callicedesde" type="text" data-dojo-type="dijit.form.DateTextBox"
                                                         class="formelement-100-12"    
                                                                    data-dojo-props='type:"text", name:"fechaini", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
                                                                   
                                                                onChange="dijit.byId('callicehasta').constraints.min = this.get('value'); dijit.byId('callicehasta').set('value', this.get('value') );"     
                                                             />
                                                      
                                                         <span class="sp12b" style="margin:12px 8px 0x 8px;">Hasta: </span>
                                                         <input id="callicehasta" type="text"  data-dojo-type="dijit.form.DateTextBox"
                                                         class="formelement-100-12"    
                                                                    data-dojo-props='type:"text", name:"fechafin", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 
                                                                  
                                                                onChange=""   
                                                                />
                                                  </td>
                                             </tr>


                                             <tr id="trhis_obs" height="35" class="row_form" > 
                                                  <td> <span class="sp12b">Descripcion/Obs</span></td>
                                                  <td>:</td>
                                                  <td colspan="4"> 
                                                      <div data-dojo-props="name:'descripcion'" data-dojo-type="dijit.form.TextArea" class="formelement-350-11"></div>
                                                  </td>
                                             </tr> 

                                  </table>

                              </form>
                            </div>
                           
                        </div>

<?PHP
}
?>
                        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Historico </span>"'>
                              
                              <div class="dv_busqueda_personalizada">
                                   
                                   <form data-dojo-type="dijit.form.Form" id="formLicenciasSearch" method="post" action="exportar/reporte_de_licencias" target="_blank">


                                     <input name="trabajador" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']);?>" />
                                     
                                     <input type="hidden" name="tipo" value="lic_" />
                                     <input type="hidden" name="tipoPeriodo" value="anio">
                                     <input type="hidden" name="filtrar_por" value="trabajador" />

                                    <table class="tablepadding4">
                                         <tr>
                                            <td width="35">
                                                <span class="sp11b"> Año </span>
                                            </td>
                                            <td width="10" align="center"> 
                                                <span class="sp11b"> : </span>
                                            </td>
                                            <td>                   
                                                 <select name="anio"  
                                                     data-dojo-type="dijit.form.Select" 
                                                     data-dojo-props="name:'anio' "
                                                     onChange=" Persona.Ui.Grids.licencias.refresh(); "
                                                     style="font-size: 11px; width: 80px;">
                                                      <option value="0"> ---- </option>
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
                                    </table>
                                    </form>
                                </div>

                               <div id="dvlicencia_data"> </div>    
                               <div id="dvlicencia_table"></div>
                               <div class="table_toolbar" align="right"> 
                                   
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_tbllice_ver_click(this,evt);
                                            </script>
                                   </button>
                                   
                                   <?PHP
                                     
                                      if( $this->user->has_key('TRABAJADOR_LICENCIAS_DEL') )
                                      { 

                                   ?>

                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Eliminar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_tbllice_del_clic(this,evt);
                                            </script>
                                   </button>
                                   

                                   <?PHP
                                       }
                                   ?>
                                    
                                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                         <?PHP 
                                            $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                         ?>
                                            <label class="lbl10">Exportar Excel</label>
                                             <script type="dojo/method" event="onClick" args="evt"> 
                                               document.getElementById('formLicenciasSearch').submit();
                                           </script>
                                  </button>

                               </div>
                            
                            
                        </div>
                   </div>