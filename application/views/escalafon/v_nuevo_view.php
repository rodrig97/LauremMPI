<!--
<div id="toolbar1" data-dojo-type="dijit.Toolbar"
			><div data-dojo-type="ToolbarSectionStart" data-dojo-props='label:"Buttons"'></div
			><div id="toolbar1.cut" data-dojo-type="dijit.form.Button" data-dojo-props='iconClass:"dijitEditorIcon dijitEditorIconCut", showLabel:false'>Cut</div
			><div id="toolbar1.copy" data-dojo-type="dijit.form.Button" data-dojo-props='iconClass:"dijitEditorIcon dijitEditorIconCopy", showLabel:true'>Copy</div

</div>
</div>
-->
 
<div class="window_container">
<div class="dv_bgceleste">
    <?PHP if($tipo_individuo == TIPOINDIVIDUO_TRABAJADOR){  ?>
    <table class="_tablepadding4">
        <tr>  
                <td>
                       <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    
                                <script type="dojo/method" event="onClick" args="evt">
                                        Persona.Ui.btn_registrar_persona(this,evt);     
                                </script>
                        </button>
                  </td>
                  <td> <span class="sp12b">Dni a registrar</span></td>
                  <td>:</td>
                  <td> 
                     <span class="sp12b"> <?PHP echo $dni; ?></span>
                  </td>
                 
        </tr>
    </table>
      <?PHP } ?>

      <?PHP if($tipo_individuo == TIPOINDIVIDUO_BENEFICIARIO)
            {  ?>

           

            <table class="_tablepadding4">
                  <tr>  
                          <td>
                                 <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                            <?PHP 
                                               $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                            ?>
                                              
                                          <script type="dojo/method" event="onClick" args="evt">
                                                  Persona.Ui.btn_registrar_persona(this,evt);     
                                          </script>
                                  </button>
                            </td>
                            <td> 
                                 <span class='sp12b'> Registrar nuevo beneficiario judicial </span>
                            </td>
                           
                  </tr>
              </table>

        <?PHP } ?>
</div>

<div id="wdnuevo_viewcontainer" class="dv-450auto">
        <form id="form_info_personal"  data-dojo-type="dijit.form.Form">   


        <input id="hdnuevoindiv_tipo" name="tipoindividuo" type="hidden" value="<?PHP echo $tipo_individuo ?>"  />
        <table class="_tablepadding4">  

          <?PHP if($tipo_individuo == TIPOINDIVIDUO_TRABAJADOR){  ?>


             <tr class="row_form">
                  <td> <span class="sp12b">Régimen actual</span></td>
                  <td>:</td>
                  <td> 
                      <select id="fip_situlaboral" name="situlaboral"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-150-12" style="width:190px;">
                           <option value="0" selected="selected"> ------ </option>
                                 <?PHP 
                                    foreach($tipo_empleados as $tipoemp){
                                        echo '<option value="'.$tipoemp['id'].'">'.trim($tipoemp['label']).'</option>';
                                    }
                                 ?>
                      </select>
                  </td>
             </tr>
 
            <tr class="row_form">
                  <td width="190"> <span class="sp12b"> Fecha de Inicio del contrato  </span></td>
                  <td>:</td>
                  <td width="500"> 
                
                   <input id="calhisdesde" type="text" class="formelement-100-12" data-dojo-type="dijit.form.DateTextBox"
                                                    data-dojo-props='type:"text", name:"fechaini", value:"",
                                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                    lang:"es",
                                                    required:true,
                                                    promptMessage:"mm/dd/yyyy",
                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

                                                 onChange="dijit.byId('calhistermino').constraints.min = this.get('value'); dijit.byId('calhistermino').set('value', this.get('value') );"    
                                             /> 
                  </td>
             </tr>

              <tr class="row_form">
                  <td width="190"> <span class="sp12b"> Fecha de Termino del contrato  </span></td>
                  <td>:</td>
                  <td width="500"> 
                
                   <input id="calhistermino" type="text" class="formelement-100-12" data-dojo-type="dijit.form.DateTextBox"
                                                    data-dojo-props='type:"text", name:"fechafin", value:"",
                                                     constraints:{datePattern:"dd/MM/yyyy", strict:false},
                                                    lang:"es",
                                                    required:false,
                                                    promptMessage:"mm/dd/yyyy",
                                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'

                                                onChange=" "     
                                             /> 
                  </td>
             </tr>
            
            <tr class="row_form">
                  <td width="190"> <span class="sp12b">Monto de Contrato </span></td>
                  <td>:</td>
                  <td width="500"> 
                       <input  name="montocontrato" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-50-12"  />
                  </td>
             </tr>

  
                <?PHP } ?>

             <tr class="row_form">
                  <td width="190"> <span class="sp12b">Nombres </span></td>
                  <td>:</td>
                  <td width="500"> 
                       <input id="fip_txtnombres" name="nombres" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                  </td>
             </tr>
              <tr class="row_form">
                  <td> <span class="sp12b">Apellido Paterno </span></td>
                  <td>:</td>
                  <td> 
                      <input id="fip_txtpaterno" name="paterno" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                  </td>
             </tr>
              <tr class="row_form">
                  <td> <span class="sp12b">Apellido Materno </span></td>
                  <td>:</td>
                  <td> 
                       <input id="fip_txtmaterno" name="materno" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                  </td>
             </tr>
              <tr class="row_form">
                  <td> <span class="sp12b">Sexo</span></td>
                  <td>:</td>
                  <td> 
                      <select  id="fip_selsexo" name="sexo" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12" style="width:90px; font-size:12px;" >
                         <option value="0" selected="selected"> -------- </option>
                         <option value="1">Masculino</option>
                         <option value="2">Femenino</option>
                     </select> 
                  </td>
             </tr>

           <tr class="row_form">
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

                 <?PHP if($tipo_individuo == TIPOINDIVIDUO_TRABAJADOR){  ?>

              <tr  class="row_form">
                          <td> <span class="sp12b">Lugar de Origen</span></td>
                          <td>:</td>
                          <td colspan="4"> 
                           
                                   <select    data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"ciudad", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"${0}*", invalidMessage: "La Ciudad no esta registrada" ' style="margin-left:0px; font-size:12px; width: 250px;">
                                        <option value="0"> No Especificar </option>
                                        <?PHP
                                           foreach($ciudades as $ciudad)
                                           {
                                              
                                                echo "<option value='".trim($ciudad['distrito_id'])."-".trim($ciudad['provincia_id'])."-".trim($ciudad['departamento_id'])."'>  ".trim($ciudad['distrito'])." - ".trim($ciudad['provincia'])." - ".trim($ciudad['departamento'])."   </option>";
                                            }
                                         ?>
                                 </select>
                                                     

                          </td>
                   </tr>

                   <?PHP } ?>

              <tr class="row_form">
                  <td> <span class="sp12b">Nacionalidad</span></td>
                  <td>:</td>
                  <td> 
                     <select  id="fip_selnacionalidad" name="nacionalidad" data-dojo-type="dijit.form.Select"  class="formelement-80-12">
                         <option value="1">Peruano</option>

                     </select> 
                  </td>
             </tr>
             <tr class="row_form">
                  <td> <span class="sp12b">Fecha de Nacimiento</span></td>
                  <td>:</td>
                  <td> 
                     <!-- <input name="fechanac" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"   />
                     -->
                     
                     <div   id="fip_fechanac" 
                            data-dojo-type="dijit.form.DateTextBox"
                  					data-dojo-props='type:"text", name:"fechanac", value:"",
                  				         constraints:{datePattern:"dd/MM/yyyy", strict:true},
                  					lang:"es",
                  					required:true,
                  					promptMessage:"mm/dd/yyyy",
                  					invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' style="font-size:12px; width:100px;">
	             </div>
                </td>
             </tr>
             <tr class="row_form">
                  <td> <span class="sp12b">Dni</span></td>
                  <td>:</td>
                  <td> 
                      <input  id="fip_txtdni" name="dni" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:8, readOnly: <?PHP echo $dni_editable; ?>" class="formelement-100-12" value="<?PHP echo $dni; ?>"  />
                  </td>
             </tr>

             
             <tr class="row_form">
                  <td> <span class="sp12b">Ruc</span></td>
                  <td>:</td>
                  <td> 
                      <select id="fip_selhasruc" name="hasruc" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12"  style="width: 40px;" >
                         <option value="1">Si</option>
                         <option value="0" selected="selected">No</option>
                      </select>
                      <div class="containerItems" style="display:none">
                        <span class="sp12b">#</span>
                        <input id="fip_txtruc" name="ruc" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:11" class="formelement-100-12"  />
                      </div>
                  </td>
             </tr>

                 <?PHP if($tipo_individuo == TIPOINDIVIDUO_TRABAJADOR){  ?>
 
             <tr class="row_form">
                  <td> <span class="sp12b">Libreta Militar</span></td>
                  <td>:</td>
                  <td> 
                     <select  id="fip_selhaslib"  name="haslibreta"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12" style="width: 40px;">
                         <option value="1">Si</option>
                         <option value="0"  selected="selected">No</option>
                     </select>
                      <div class="containerItems" style="display:none">
                          <span class="sp12b">#</span>
                         <input id="fip_txtlibcod"  name="codlibreta" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"  />
                            <span class="sp12b">De:</span>
                          <select id="fip_txtlibtip" name="tipolibreta" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-80-12" style="width:80px; font-size:12px;">
                             <option value="0" selected="selected"> ------- </option>
                             <option value="1">Ejercito</option>
                             <option value="2">Fap</option>
                             <option value="3">Marina</option>
                          </select> 
                      </div>

                  </td>
             </tr>
             <tr class="row_form">
                  <td> <span class="sp12b">Brevete</span></td>
                  <td>:</td>
                  <td> 
                       <select id="fip_hasbrevete" name="hasbrevete"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12" style="width: 40px;">
                         
                         <option value="1" >Si</option>
                         <option value="0"  selected="selected">No</option>

                       </select>
                       <div class="containerItems" style="display:none">
                             <span class="sp12b">#</span>
                             <input id="fip_codbrevete" name="codbrevete" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"  />
                                <span class="sp12b">Categor&iacute;a:</span>
                              <select id="fip_tipobrevete" name="tipobrevete" data-dojo-type="dijit.form.Select" data-dojo-props=' ' class="formelement-50-12">
                                 <option value="0" selected="selected"> ------ </option>
                                 <?PHP 
                                    foreach($brevetes as $tipobrevet){
                                        echo '<option value="'.$tipobrevet['id'].'">'.trim($tipobrevet['label']).'</option>';
                                    }
                                 ?>
                             </select> 
                       </div>

                  </td>
             </tr>   

          

               <tr class="row_form">
                 <td> <span class="sp12b">Direcci&oacute;n 1 </span></td>
                  <td>:</td>
                  <td> 
                      <input id="fip_direccion1"  name="direccion1" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                  </td>
             </tr>
             <tr class="row_form">
                 <td> <span class="sp12b">Direcci&oacute;n 2</span></td>
                  <td>:</td>
                  <td> 
                      <input id="fip_direccion2"  name="direccion2" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                  </td>
             </tr>
             <tr class="row_form">
                 <td> <span class="sp12b"> Telefono casa </span></td>
                  <td>:</td>
                  <td> 
                      <input  id="fip_fono"  name="fono" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-80-12"  />
                  </td>
             </tr> 

             <?PHP } ?>


                <tr class="row_form">
                 <td> <span class="sp12b"> Telefono celular </span></td>
                  <td>:</td>
                  <td> 
                      <input  id="fip_celular"  name="celular" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-80-12"  />
                  </td>
             </tr>
              <tr class="row_form">
                 <td> <span class="sp12b"> Email </span></td>
                  <td>:</td>
                  <td> 
                      <input id="fip_email"   name="email" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:80" class="formelement-200-12"  />
                  </td>
             </tr>
            
             <tr class="row_form">
                 <td> <span class="sp12b">Essalud </span></td>
                 <td>:</td>
                 <td> 
                      <input id="fip_essalud" name="essalud" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-100-12" style="width:100px;" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
                 </td>
            </tr>

              <tr class="row_form">
                  <td> <span class="sp12b">Cuenta Bancaria</span></td>
                  <td>:</td>
                  <td> 
                      <select id="fip_hascbanco" name="hascbanco"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                          <option value="1">Si</option>
                          <option value="0" selected="selected">No</option>
                       </select>
                      <div class="containerItems" style="display:none">
                        
                                <span class="sp12b"># </span>
                                  <input  id="fip_bancocod" name="bancocod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"  />
                            
                            <div class="dvblock">
                            <span class="sp12b">Entidad bancaria: </span>
                            <select id="fip_banco" name="banco"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12" style="width:140px; font-size:12px;">
                                <option value="0" selected="selected"> ------ </option>
                                    <?PHP
                                foreach($bancos as $banco){
                                    echo '<option value="'.$banco['id'].'">'.trim($banco['label']).'</option>';
                                }
                             ?>
                            </select>
                           </div>
                      </div>
                 </td> 
             </tr>

              <?PHP if($tipo_individuo == TIPOINDIVIDUO_TRABAJADOR){  ?>


             <tr class="row_form">
                  <td> <span class="sp12b">Sistema de Pensiones</span></td>
                  <td>:</td>
                  <td> 
                    <!--   <select id="fip_haspension" name="haspension" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                          <option value="1">Si</option>
                          <option value="0" selected="selected">No</option>
                      </select> -->
                       <div class="containerItems" style="display:inline"> 
                          <select id="fip_tipopension" name="tipopension"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12">
                             <option value="1" selected="selected">ONP</option>
                             <option value="2">AFP</option>
                          </select> 
                         <span class="sp12b"># </span>
                          <input id="fip_codpension" name="codpension" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"  />
                          <span class="sp12b"> Jubilado:  </span>
                          <select id="fip_pesion_jubilado" name="estajubilado"  data-dojo-props="name:'estajubilado'"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12" style="width:60px;">
                              <option value="0" > No </option>
                              <option value="1" > Si </option>
                          </select>   


                           <div class="containerItems" style="display:none"> 
                             <span class="sp12b">Afiliado a: </span>
                              <select id="fip_afp"  name="afp"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12" >
                                     <option value="0"> ------ </option>
                                     <?PHP
                                        foreach($afps as $afp){
                                            echo '<option value="'.$afp['id'].'">'.trim($afp['label']).'</option>';
                                        }
                                     ?>
                              </select>


                              <select id="fip_modoafp" name="modoafp"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12">
                                  <option value="<?PHP echo AFP_FLUJO; ?>" > FLUJO </option>
                                  <option value="<?PHP echo AFP_SALDO; ?>" > SALDO </option>
                              </select> 

                              <?PHP 

                               if(AFP_QUITARINVALIDEZ_AUTOMATICO == FALSE)
                               {

                              ?>
                              
                                <span class="sp12b"> Invalidez:  </span>
                                <select id="fip_pesion_aplicainvalidez" name="aplica_invalidez"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'aplica_invalidez' " class="formelement-50-12" style="width:60px;">
                                    <option value="1" selected="selected" > Si </option>
                                    <option value="0" > No </option>
                                </select>   


                              <?PHP 
                                }
                              ?>



                           </div>
                      </div>

                  </td>
             </tr>

             <?PHP 

                }
             ?>
            
           </table>
 
            <br/><br/><br/>
        </form> 
       
</div>


</div>