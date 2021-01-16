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
                                                        Persona.Ui.btn_registerfaltar_click(this,evt);     
                                            </script>
                                   </button>
 
                            </div>
                                
                            <div class="dv_form_container">
                              <form id="form_info_faltas"  data-dojo-type="dijit.form.Form">   

                                   <table class="_tablepadding4" width="100%">
                                            
                                          
                                             <tr height="40"   class="row_form">
                                                <td width="170"> <span class="sp12b">Periodo de inasistencia</span></td>
                                                <td>:</td>
                                                <td colspan="4"> 
                                                       <span class="sp12b" style="margin:12px 8px 0x 8px;">Desde: </span>
                                                       <input id="calfalta_desde" type="text"
                                                                  class="formelement-100-12"        
                                                                  data-dojo-type="dijit.form.DateTextBox"
                                                                  data-dojo-props='type:"text", name:"fechaini", value:"",
                                                                   constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                  lang:"es",
                                                                  required:true,
                                                                  promptMessage:"mm/dd/yyyy",
                                                                  invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
                                                                 
                                                              onChange="dijit.byId('calfartar_hasta').constraints.min = this.get('value'); dijit.byId('calfartar_hasta').set('value', this.get('value') );"     
                                                           />
                                                    
                                                       <span class="sp12b" style="margin:12px 8px 0x 8px;">Hasta: </span>
                                                       <input id="calfalta_hasta" type="text"  
                                                                  class="formelement-100-12" 
                                                                  data-dojo-type="dijit.form.DateTextBox"
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

                                             <tr height="35" >
                                                  <td> <span class="sp12b"> Justificada </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                        <select  data-dojo-type="dijit.form.Select" 
                                                             data-dojo-props='name: "justificada", disabled:false' style="font-size:12px; width: 80px;">
                                                              
                                                              <option value="0"> No </option>                                                              
                                                              <option value="1"> Si </option>
                                                       </select>
                                                  </td>
                                             </tr>
                                         
                                              <tr>
                                                  <td> <span class="sp12b"> Justificacion </span></td>
                                                  <td>:</td>
                                                  
                                                  <td>

                                                       <input name="justificacion" type="text" data-dojo-type="dijit.form.TextArea" data-dojo-props="trim:true, maxlength:20" class="formelement-350-11"  />
                                                  </td>
                                             </tr>

                                              <tr>
                                                  <td> <span class="sp12b"> Observación </span></td>
                                                  <td>:</td>
                                                  
                                                  <td>

                                                       <input name="observacion" type="text" data-dojo-type="dijit.form.TextArea" data-dojo-props="trim:true, maxlength:20" class="formelement-350-11"  />
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
                               <div id="dvfaltas_table"></div>
                               <div class="table_toolbar" align="right"> 
                                   
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                     Persona.Ui.btn_tblfaltar_ver_click(this,evt);
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
                                                     Persona.Ui.btn_tblfaltar_del_clic(this,evt);
                                            </script>
                                   </button>
                                   
                                   <?PHP 

                                    }
                                   ?>

                               </div>
                            
                            
                            
                        </div>
                   </div>