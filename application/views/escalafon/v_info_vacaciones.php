 <div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >

<?PHP
  
   if( $this->user->has_key('TRABAJADOR_VACACIONES_VER') )
   { 

?>
                        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Registrar Nuevo </span>"'>
                                
                            <div class="dv_form_toolbarcontainer">

                                
                                 <input class="hduserkey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" />    
                                 <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                        <?PHP 
                                           $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                        ?>
                                           <label class="lbl10">Registrar Vacaciones</label>
                                            <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_register_vacaciones_click(this,evt);     
                                          </script>
                                 </button>
                                
                            </div>
                                
                            <div class="dv_form_container">
                            
                              <form id="form_info_vacaciones"  data-dojo-type="dijit.form.Form">   

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
                                      
 <!-- 
                                            <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b">Tipo</span></td>
                                                  <td>:</td>
                                                  <td colspan="4">
                                                        <select  name="tipo" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-100-12" style="width:150px;" >
                                                            <option value="0" selected="selected"> -------- </option>
                                                            <option value="1"> CITT </option>
                                                            <option value="2"> PARTICULAR </option>
                                                        </select> 
                                                  </td>
                                             </tr> -->
                                         
                                              <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b">Intervalo de tiempo</span></td>
                                                  <td>:</td>
                                                  <td colspan="4">

                                                       <span class="sp12b" style="margin:12px 8px 0x 8px;">Desde: </span>
                                                         <input id="calvacadesde" type="text" data-dojo-type="dijit.form.DateTextBox"
                                                         class="formelement-100-12"    
                                                                    data-dojo-props='type:"text", name:"fechaini", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
                                                                   
                                                                onChange="dijit.byId('calvacahasta').constraints.min = this.get('value'); dijit.byId('calvacahasta').set('value', this.get('value') );"     
                                                             />
                                                      
                                                         <span class="sp12b" style="margin:12px 8px 0x 8px;">Hasta: </span>
                                                         <input id="calvacahasta" type="text"  data-dojo-type="dijit.form.DateTextBox"
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
                                                  <td> <span class="sp12b"> Descripción/Obs</span></td>
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
                                   
                                   <form data-dojo-type="dijit.form.Form" id="formVacacionesSearch" method="post" action="exportar/reporte_de_licencias" target="_blank">


                                     <input name="trabajador" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']);?>" />
                                     
                                     <input type="hidden" name="tipo" value="vac_" />
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
                                                     onChange=" Persona.Ui.Grids.vacaciones.refresh(); "
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


                               <div id="dvvacaciones_data"> </div>    
                               <div id="dvvacaciones_table"></div>
                               
                               <div class="table_toolbar" align="right"> 
                                   
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_vacaciones_ver_click(this,evt);
                                            </script>
                                   </button>
                                   
                                   <?PHP
                                     
                                      if( $this->user->has_key('TRABAJADOR_VACACIONES_DEL') )
                                      { 

                                   ?>

                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Eliminar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_vacaciones_del_clic(this,evt);
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
                                                document.getElementById('formVacacionesSearch').submit();
                                            </script>
                                   </button>

                               </div>
                            
                            
                        </div>
                   </div>