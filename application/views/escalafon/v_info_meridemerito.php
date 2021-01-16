 
                    <div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >

                        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Registrar Nuevo </span>"'>
                              
                              <form id="form_info_meritodemerito"  data-dojo-type="dijit.form.Form">   

                                   <table class="_tablepadding4">
                                             <tr>
                                                  <td> <span class="sp12">Documento que autoriza </span></td>
                                                  <td>:</td>
                                                  <td>
                                                      <input name="documento" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:200" class="formelement-250-12"  />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td> <span class="sp12">Fecha del documento </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                         <div data-dojo-type="dijit.form.DateTextBox"
                                                                    data-dojo-props='type:"text", name:"fechadoc", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
                                                         </div>

                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td> <span class="sp12"> Ubicacion </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="ubicacion" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-250-12"  />
                                                  </td>
                                             </tr>
                                              <tr>
                                                  <td> <span class="sp12"> Motivo </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="motivo" type="text" data-dojo-type="dijit.form.TextArea" data-dojo-props="maxlength:20" class="formelement-250-12"  />
                                                  </td>
                                             </tr>

                                  </table>

                              </form>
                        </div>
                        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Historico </span>"'>
                             
                        </div>
                   </div>
                 