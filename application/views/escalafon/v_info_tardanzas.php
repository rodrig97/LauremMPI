<div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >

  <?PHP 
            
 if( $this->user->has_key('TRABAJADOR_FALTASTAR_EDITAR') )
 { 

?>

                        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Registrar Nuevo </span>"'>
                           <div class="dv_form_toolbarcontainer">
                                 <input class="hduserkey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" />    

                                  

                                   <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Registrar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                        Persona.Ui.btn_registrartardanza(this,evt);     
                                            </script>
                                   </button>
 
                            </div>
                                
                            <div class="dv_form_container">
                              <form id="form_info_tardanzas"  data-dojo-type="dijit.form.Form">   

                                   <table class="_tablepadding4" width="100%">
                                  
                                          
                                           <tr height="35"   class="row_form">
                                                  <td > <span class="sp12b">Día</span></td>
                                                  <td>:</td>
                                                  <td>
                                                      <div id="caltard_dia" class="formelement-100-12"    data-dojo-type="dijit.form.DateTextBox" 
                                                                    data-dojo-props='type:"text", name:"fecha", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
                                                       </div>
                                                  </td>
                                             </tr>
                                           <tr id="trfaltar_time" height="35"   class="row_form">
                                                  <td> <span class="sp12b">Tiempo de tardanza</span></td>
                                                  <td>:</td>
                                                  <td> 
                                                   
                                                    <span class="sp12b" style="margin:12px 8px 0x 8px;">Minutos: </span>
                                                    <input name="minutos" data-dojo-type="dijit.form.NumberSpinner"
                                                            data-dojo-props='onChange:function(val){  },
                                                            value:0, 
                                                            constraints:{min:0,max:60,places:0}
                                                            ' style="width: 50px;"/>
                                                    <span class="sp12b" style="margin:12px 8px 0x 8px;">Segundos: </span>
                                                     <input name="segundos" data-dojo-type="dijit.form.NumberSpinner"
                                                            data-dojo-props='onChange:function(val){  },
                                                            value:0, 
                                                            constraints:{min:0,max:60,places:0}
                                                            ' style="width: 50px;"/>
                                                    
                                                    
                                                  </td>
                                             </tr>
                                               
                                              <tr>
                                                  <td> <span class="sp12b"> Justificación/obs </span></td>
                                                  <td>:</td>
                                                  
                                                  <td>

                                                       <input name="justificacion" type="text" data-dojo-type="dijit.form.TextArea" data-dojo-props="trim:true, maxlength:20" class="formelement-350-11"  />
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
                             
                            
                               <div id="dvfaltardanza_data"> </div>    
                               <div id="dvtardanza_table"></div>
                               <div class="table_toolbar" align="right"> 
                                   
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                     Persona.Ui.btn_tbltardanza_ver_click(this,evt);
                                            </script>
                                   </button>
                                   
                                   <?PHP 
                                               
                                    if( $this->user->has_key('TRABAJADOR_FALTASTAR_DEL') )
                                    { 

                                   ?>

                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Eliminar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                     Persona.Ui.btn_tbltardanza_del_clic(this,evt);
                                            </script>
                                   </button>
                                   
                                   <?PHP 

                                    }
                                   ?>

                               </div>
                            
                            
                            
                        </div>
                   </div>