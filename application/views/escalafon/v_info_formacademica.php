  <div  data-dojo-type="dijit.layout.AccordionContainer"  data-dojo-props='region:"left", splitter:true '    >

      <?PHP 
      
       if( $this->user->has_key('TRABAJADOR_ACADEMICO_EDITAR') )
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
                                                  Persona.Ui.btn_registeracademico_click(this,evt);     
                                        </script>
                                 </button>
                                
                            </div>
                                
                            <div class="dv_form_container">
                              <form id="form_info_academica"  data-dojo-type="dijit.form.Form">   

                                   <table id="table_academico_data" class="_tablepadding4" width="100%">
                                             <tr height="35"  class="row_form">
                                                  <td width="140"> <span class="sp12b">Tipo de estudio</span></td>
                                                  <td width="20">:</td>
                                                  <td>
                                                      <select id="selAcademicoTiEst" name="tipo" data-dojo-type="dijit.form.Select" style="font-size:12px;">
                                                             <option value="0" selected="selected"> ------ </option>
                                                             <?PHP 
                                                                foreach($tipoestudio as $tipo){
                                                                    echo '<option value="'.$tipo['id'].'">'.trim($tipo['label']).'</option>';
                                                                }
                                                             ?>
                                                      </select>
                                                  </td>
                                             </tr>
                                              <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Nombre </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="nombre" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100, name:'nombre'" class="formelement-300-12"  />
                                                  </td>
                                             </tr>
                                             
                                            <tr height="35"  class="row_form">
                                                <td> <span class="sp12b">Centro de estudios</span></td>
                                                <td>:</td>
                                                <td>
                                                    <select  id="selAcademico_centroestudios"  name="centroestudios" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"centroestudios", autoComplete:false, trim:true, highlightMatch: "all",  queryExpr:"${0}", invalidMessage: ""   ' style="width: 300px; font-size:12px;">
                                                        <!--  <option value="0" selected="selected"> ------ </option>
                                                            
                                                                foreach($centroestudio as $centro){
                                                                    echo '<option value="'.$centro['id'].'">'.trim($centro['label']).'</option>';
                                                                }
                                                            ?>-->
                                                    </select>
                                                </td>
                                            </tr>
                                              <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b">Carrera Profesional</span></td>
                                                  <td>:</td>
                                                  <td>
                                                      <select id="selAcademico_carrera" name="carrera" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, trim:true, highlightMatch: "all",  queryExpr:"${0}", invalidMessage: ""   ' style="width: 300px; font-size:12px;">
                                                            <!-- <option value="0" selected="selected"> ------ </option>
                                                             
                                                                foreach($especialidades as $especi){
                                                                    echo '<option value="'.$especi['id'].'">'.trim($especi['label']).'</option>';
                                                                }
                                                              -->
                                                      </select>
                                                  </td>
                                             </tr>
                                             <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b">Especialidad</span></td>
                                                  <td>:</td>
                                                  <td>
                                                      <select id="selAcademico_especialidad"  name="especialidad" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, trim:true, highlightMatch: "all",  queryExpr:"${0}", invalidMessage: ""   ' style="width: 300px; font-size:12px;">
                                                            <!-- <option value="0" selected="selected"> ------ </option>
                                                              
                                                                foreach($especialidades as $especi){
                                                                    echo '<option value="'.$especi['id'].'">'.trim($especi['label']).'</option>';
                                                                }
                                                             ?>-->
                                                      </select>
                                                  </td>
                                             </tr>
                                         
                                            <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Ubicacion </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="ubicacion" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props=" trim:true,maxlength:50" class="formelement-250-12"  />
                                                  </td>
                                             </tr> <!--
                                                   <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Ubicacion </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                 
                                                       <select    data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"ciudad", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Ciudad no esta registrada" ' style="margin-left:0px; font-size:11px; width: 180px;">
                                                                <option value="0"> No Especificar </option>
                                                                   <?PHP
                                                                   foreach($ciudades as $ciudad){
                                                                        echo "<option value='".trim($ciudad['distrito_id'])."-".trim($ciudad['provincia_id'])."-".trim($ciudad['departamento_id'])."'>  ".trim($ciudad['distrito'])." - ".trim($ciudad['provincia'])." - ".trim($ciudad['departamento'])."   </option>";
                                                                   }
                                                                 ?>
                                                           </select>
                                                     
                                                  </td>
                                             </tr> -->
                                             <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Modalidad </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      <select name="modalidad" data-dojo-type="dijit.form.Select" style="font-size:12px;">
                                                             <option value="0" selected="selected"> ------ </option>
                                                             <option value="1" > Presencial </option>
                                                             <option value="2" > Semi Presencial </option>
                                                             <option value="3" > A Distancia </option>
                                                      </select> 
                                                  </td>
                                             </tr>
                                             <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Situacion </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      <select name="situacion" data-dojo-type="dijit.form.Select">
                                                          <option value="0" selected="selected"> ------ </option>
                                                          <option value="1">Actualmente</option>
                                                          <option value="2">Culminado</option> 
                                                          <option value="3">No culminado </option>
                                                      </select>
                                                  </td>
                                             </tr>
                                             <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Estado del titulo/Certificado </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      <select name="titulo" data-dojo-type="dijit.form.Select">
                                                          <option value="0" selected="selected"> ------ </option> 
                                                          <option value="1">Sin Titulo</option> 
                                                          <option value="2">Titulo en tramite </option>
                                                          <option value="3">Con Titulo</option>
                                                      </select>
                                                  </td>
                                             </tr>
                                              <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Año de estudios </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      <input name="anioestudio" data-dojo-type="dijit.form.NumberSpinner"
                                                            data-dojo-props='onChange:function(val){},
                                                                             value:0, 
                                                                             constraints:{min:0,max:7,places:0}  ' style="width: 50px;"/>
                                                      
                                                  </td>
                                             </tr>
                                              <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Horas Academicas </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="horasacademicas" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props=" trim:true, maxlength:7" class="formelement-50-12" value="0"  />
                                                  </td>
                                             </tr>
                                             
                                              <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b">Desde</span></td>
                                                  <td>:</td>
                                                  <td> 

                                                         <div id="calacadesde" data-dojo-type="dijit.form.DateTextBox"
                                                                     class="formelement-100-11" 
                                                                    data-dojo-props='type:"text", name:"fechaini", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'
                                                                     onChange="dijit.byId('calacahasta').constraints.min = this.get('value'); dijit.byId('calacahasta').set('value', this.get('value') );"  
                                                                    >
                                                         </div>
                                                         <span>Hasta: </span>
                                                         
                                                      <div id="calacahasta" data-dojo-type="dijit.form.DateTextBox"
                                                                    class="formelement-100-11" 
                                                                    data-dojo-props='type:"text", name:"fechafin", value:"",
                                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                    lang:"es",
                                                                    required:true,
                                                                    promptMessage:"mm/dd/yyyy",
                                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
                                                         </div>  
                                                  </td>
                                             </tr>
                                             
                                              <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Fecha de Publicacion </span></td>
                                                  <td>:</td>
                                                  <td> 

                                                       <input name="fechapubli" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props=" trim:true, maxlength:12" class="formelement-50-12"  />
                                                  </td>
                                             </tr>
                                             
                                                <tr height="35"  class="row_form">
                                                  <td> <span class="sp12b"> Obs/Des </span></td>
                                                  <td>:</td>
                                                  <td> 
                                                       <div  name="descripcion"  dojoType="dijit.form.TextArea" style="width: 300px;"></div>
                                                        <!-- <input name="descripcion" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:12" class="formelement-80-12"  />
                                                        -->
                                                       </td>
                                             </tr>
                                             
                                  </table>

                              </form>
             

            </div>
                        </div>
                  
            <?PHP 
                }
            ?>

                        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Informacion Registrada </span>"'>
                             
                                
                               <div id="dvacademico_data"> </div>    
                               <div id="dvacademico_table"></div>
                               <div class="table_toolbar" align="right"> 

                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Ver</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                      Persona.Ui.btn_tblestudio_ver_click(this,evt);
                                            </script>
                                   </button>

                                   <?PHP 
                                   
                                    if( $this->user->has_key('TRABAJADOR_ACADEMICO_DEL') )
                                    { 

                                    ?>


                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                          <?PHP 
                                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                          ?>
                                             <label class="lbl10">Eliminar</label>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                    Persona.Ui.btn_tblestudio_del_clic(this,evt);
                                            </script>
                                   </button>

                                   <?PHP 

                                     }
                                   ?>

                               </div>
                            
                            
                            
                        </div>
</div>