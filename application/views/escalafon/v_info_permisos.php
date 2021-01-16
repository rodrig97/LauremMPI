  <div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >


      <?PHP 
                  
                   if( $this->user->has_key('TRABAJADOR_PERMISOS_EDITAR') )
                   { 

      ?>

                        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Registrar Nuevo </span>"'>
                           <div class="dv_form_toolbarcontainer">
                                
                                  <input class="hduserkey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" />    
                                   <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Registrar Permiso</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                         Persona.Ui.btn_permiso_click(this,evt);     
                                            </script>
                                   </button>
                            </div>
                                
                            <div class="dv_form_container">
                              <form id="form_info_permisos"  data-dojo-type="dijit.form.Form">   

                                   <table class="_tablepadding4" width="100%">
                                             <tr id="trperm_doc" height="40"   class="row_form">
                                                  <td width="120"> <span class="sp12b">Doc. Referencia </span></td>
                                                  <td width="10">:</td>
                                                  <td width="210">
                                                      <input name="documento" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100,  trim:true " class="formelement-200-12"  />
                                                  </td>
                                           
                                                  <td width="50"> <span class="sp12b">Autoriza </span></td>
                                                  <td width="10">:</td>
                                                  <td>
                                                      <input name="autoriza" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100, trim:true   " class="formelement-200-12"  />
                                                  </td>
                                             </tr>
                                             <tr id="trperm_infosisgedo">
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

                                                         <div id="calPermfecdoc"  data-dojo-type="dijit.form.DateTextBox"
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
                                                  <td> <span class="sp12b">Fecha </span></td>
                                                  <td>:</td>
                                                  <td colspan="4"> 
                                             
                                                         <input id="calPermdesde" type="text" data-dojo-type="dijit.form.DateTextBox"
                                                              class="formelement-100-12" 
                                                                    data-dojo-props='type:"text", name:"fechaini", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
                                                                   
                                                                onChange="dijit.byId('callPermhasta').constraints.min = this.get('value'); dijit.byId('callPermhasta').set('value', this.get('value') );"     
                                                             />
                                                         
                                                  

                                                       
                                                  </td>
                                             </tr>


                                             <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Hora de Salida</span></td>
                                                  <td>:</td>
                                                  <td colspan="4"> 
 
                                                    
                                                     <input  data-dojo-type="dijit.form.TimeTextBox"
                                                                data-dojo-props='type:"text", name:"horaini", value:"T07:45:00",
                                                                title:"",
                                                                constraints:{formatLength:"short"},
                                                                required:true,
                                                                invalidMessage:"" ' style="font-size:12px"/>

                                                       
                                                  </td>
                                             </tr>
                                             <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b">Hora de Regreso</span></td>
                                                  <td>:</td>
                                                  
                                                  <td colspan="4">
                                                         
                                                          <input  data-dojo-type="dijit.form.TimeTextBox"
                                                                    data-dojo-props='type:"text", name:"horafin", value:"T07:45:00",
                                                                    title:"title: Time using local conventions",
                                                                    constraints:{formatLength:"short"},
                                                                    required:true,
                                                                    invalidMessage:"" '  style="font-size:12px" />
                                                       
                                                  </td>
                                             </tr>
                                             
                                              <tr>
                                                  <td> <span class="sp12b"> Motivo </span></td>
                                                  <td>:</td>
                                                  
                                                  <td colspan="4">

                                                       <input name="motivo" type="text" data-dojo-type="dijit.form.TextArea" data-dojo-props="trim:true, maxlength:20" class="formelement-350-11"  />
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
                             
                            
                              <div id="dvpermiso_data"> </div>    
                               <div id="dvpermiso_table"></div>
                               <div class="table_toolbar" align="right"> 
                                   
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_tblpermiso_ver_click(this,evt);
                                            </script>
                                   </button>

                                   <?PHP 
                                               
                                      if( $this->user->has_key('TRABAJADOR_PERMISOS_DEL') )
                                      { 
                                   ?>


                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Eliminar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_tblperm_del_clic(this,evt);
                                            </script>
                                   </button>
                                    

                                   <?PHP 
                                      }
                                   ?>

                               </div>
                            
                            
                            
                        </div>
                   </div>