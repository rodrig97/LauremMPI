<div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >


    <?PHP 
   
    if( $this->user->has_key('TRABAJADOR_FAMILIAR_EDITAR') )
    { 

    ?>

                        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Registrar Nuevo </span>"'>
                            <div class="dv_form_toolbarcontainer">
                                    <input class="hduserkey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" />   
                                     <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Guardar datos del familiar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                     Persona.Ui.btn_registerfamilia_click(this,evt);     
                                            </script>
                                     </button>

                            </div>
                                
                            <div class="dv_form_container">
                              

                              <form id="form_info_familiar"  data-dojo-type="dijit.form.Form">   

                                   <table class="_tablepadding4" width="100%">
                                             <tr height="35"   class="row_form">
                                                 <td width="120"> <span class="sp12b">Par&eacute;ntesco </span></td>
                                                 <td width="20">:</td>
                                                 <td>
                                                        <select id="selfami_parentesco"  name="parentesco" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-80-12" style="width:120px; font-size:12px;" >
                                                            <option value="0" selected="selected"> ------ </option>
                                                             <?PHP 
                                                                foreach($parentescos as $paren){
                                                                    echo '<option value="'.$paren['id'].'">'.trim($paren['label']).'</option>';
                                                                }
                                                              ?>
                                                        </select> 
                                                  </td>
                                             </tr>
                                              <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Nombres </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="nombres" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="" class="formelement-200-12"  />
                                                  </td>
                                             </tr>
                                               <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Apellido Paterno </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="paterno" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="" class="formelement-200-12"  />
                                                  </td>
                                             </tr>
                                              <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Apellido Materno </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="materno" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="" class="formelement-200-12"  />
                                                  </td>
                                             </tr>

                                              <tr id="trfami_estudiante" height="35"   class="row_form" style="display:none;">
                                                  <td> <span class="sp12b"> Estudiante </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="estudiante" type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="" value="1"  />
                                                  </td>
                                             </tr>

                                                <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> DNI </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                       <input name="dni" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:8" class="formelement-100-12"  />
                                                  </td>
                                             </tr>
                                            <tr height="35"   class="row_form">
                                                    <td> <span class="sp12b">Sexo</span></td>
                                                    <td>:</td>
                                                    <td> 
                                                          <select class="fieldform" name="sexo" data-dojo-type="dijit.form.Select" 

                                                             class="formelement-50-12" style="width:120px; font-size:12px;" >
                                                             <option value="0" > ------ </option>
                                                             <option value="1" >Masculino</option>
                                                             <option value="2" >Femenino</option>
                                                         </select> 
                                                     </td>
                                                
                                             </tr>
                                              
                                               <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b">Fecha del nacimiento </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                         <div id="calfamiliar_fecnac" data-dojo-type="dijit.form.DateTextBox"  class="formelement-100-11" style="width:120px; font-size:12px;" 
                                                                    data-dojo-props='type:"text", name:"fechanac", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
                                                         </div>

                                                  </td>
                                             </tr>
                                             <tr height="35"   class="row_form">
                                                    <td> <span class="sp12b">Estado Civil</span></td>
                                                    <td>:</td>
                                                    <td> 
                                                        <select class="fieldform" name="estadocivil" data-dojo-type="dijit.form.Select"  class="formelement-50-12" style="width:120px; font-size:12px;" >
                                                             <option value="0" selected="selected" > No especificado</option>
                                                             <option value="1"  >Soltero(a)</option>
                                                             <option value="2" >Casado(a)</option>
                                                             <option value="3" >Divorciado(a)</option>
                                                             <option value="4" >Viudo(a)</option>
                                                         </select> 
                                                     </td>
                                                
                                             </tr> 
                                               <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Ocupación </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                         
                                                      <select id="selfami_ocupacion" name="ocupacion" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, highlightMatch: "all", estrict:false,  queryExpr:"*${0}*" ' style="width: 300px; font-size:12px;">
                                                              
                                                              <option value="0"> No especificado </option>
                                                            <?PHP                                  
                                                                foreach($ocupaciones as $ocupacion)
                                                                {
                                                            ?>
                                                                <option  value="<?PHP echo trim($ocupacion['id']);  ?>"> <?PHP echo trim($ocupacion['label']); ?> </option>

                                                           <?PHP } ?>
                                                      </select> 
                                                  </td>
                                             </tr>
                                               <tr height="35"   class="row_form">
                                                  <td> <span class="sp12b"> Observacion </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="observacion"  data-dojo-type="dijit.form.Textarea" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                                                  </td>
                                             </tr>
                                             
                                    </table>

                              </form>
                              
                            </div>
                        </div>

        <?PHP 
             }
        ?>
                        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Familiares registrados </span>"'>
                              
                               <div id="dvfamiliar_data"> </div>    
                               <div id="dvfamiliar_table"></div>
                               <div class="table_toolbar" align="right"> 


                                

                                  <?PHP 
                                  
                                  if( $this->user->has_key('TRABAJADOR_FAMILIAR_ACT_DES_ESTUDIOS') )
                                  { 

                                  ?>


                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10"> Actualizar estudios </label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                     Persona.Ui.btn_estudiantes_activo_desactivo(this,evt);
                                            </script>
                                   </button>

                                  <?PHP 
                                       }
                                  ?>


                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                     Persona.Ui.btn_tblfamilia_ver_click(this,evt);
                                            </script>
                                   </button>


                                   <?PHP 
                                   
                                   if( $this->user->has_key('TRABAJADOR_FAMILIAR_DEL') )
                                   { 

                                   ?>

                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Eliminar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                    Persona.Ui.btn_tblfamiliar_del_clic(this,evt);
                                            </script>
                                   </button>

                                   <?PHP 
                                        }
                                   ?>
                                   
                               </div>
                            
                            
                            
                        </div>
                   </div>