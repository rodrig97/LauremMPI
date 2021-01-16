<!--
<div id="toolbar1" data-dojo-type="dijit.Toolbar"
			><div data-dojo-type="ToolbarSectionStart" data-dojo-props='label:"Buttons"'></div
			><div id="toolbar1.cut" data-dojo-type="dijit.form.Button" data-dojo-props='iconClass:"dijitEditorIcon dijitEditorIconCut", showLabel:false'>Cut</div
			><div id="toolbar1.copy" data-dojo-type="dijit.form.Button" data-dojo-props='iconClass:"dijitEditorIcon dijitEditorIconCopy", showLabel:true'>Copy</div

</div>
</div>
-->


 
<div class="dv_bgceleste">
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
</div>

<div id="wdnuevo_viewcontainer" class="dv-450auto">
        <form id="form_info_personal"  data-dojo-type="dijit.form.Form">   
        <table class="_tablepadding4">
         

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
                      <select  id="fip_selsexo" name="sexo" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12" >
                         <option value="0" selected="selected"> -------- </option>
                         <option value="1">Masculino</option>
                         <option value="2">Femenino</option>
                     </select> 
                  </td>
             </tr>
              <tr  class="row_form">
                          <td> <span class="sp12b">Lugar de Origen</span></td>
                          <td>:</td>
                          <td colspan="4"> 
                               <select id="fip_departamento" name="departamento" data-dojo-type="dijit.form.Select" data-dojo-props='required:true ' style="width: 130px; font-size:10px;">
                                    <option value="0" selected="selected"> ------ </option>
                                     <?PHP 
                                        foreach($departamentos as $departamento){
                                            echo '<option value="'.trim($departamento['departamento']).'">'.trim($departamento['nombre']).'</option>';
                                        }
                                     ?>
                              </select>

                                <select id="fip_provincia"  name="provincia" data-dojo-type="dijit.form.Select" data-dojo-props='required:true ' style="width: 130px; font-size:10px;">

                              </select>

                                <select id="fip_distrito"  name="distrito" data-dojo-type="dijit.form.Select" data-dojo-props='required:true ' style="width: 130px; font-size:10px;">

                              </select>

                          </td>
                   </tr>
          
             <tr class="row_form">
                  <td> <span class="sp12b">Fecha de Nacimiento</span></td>
                  <td>:</td>
                  <td> 
                     <!-- <input name="fechanac" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"   />
                     -->
                     
                     <div   id="fip_fechanac" data-dojo-type="dijit.form.DateTextBox"
                  					data-dojo-props='type:"text", name:"fechanac", value:"",
                  				         constraints:{datePattern:"dd/MM/yyyy", strict:true},
                  					lang:"es",
                  					required:true,
                  					promptMessage:"mm/dd/yyyy",
                  					invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."'>
	             </div>
                </td>
             </tr>
             <tr class="row_form">
                  <td> <span class="sp12b">Dni</span></td>
                  <td>:</td>
                  <td> 
                      <input  id="fip_txtdni" name="dni" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:8, readOnly: true" class="formelement-100-12" value="<?PHP echo $dni; ?>"  />
                  </td>
             </tr>
            
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
                          <select id="fip_txtlibtip" name="tipolibreta" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-80-12">
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
                  <td> <span class="sp12b">ESSALUD</span></td>
                  <td>:</td>
                  <td> 
                      <select id="fip_hasessalud"  name="hasessalud"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                          <option value="1">Si</option>
                            <option value="0" selected="selected">No</option>
                      </select>
                         <div class="containerItems" style="display:none">

                          <span class="sp12b">Codigo: </span>
                         <input id="fip_essaludcod" name="essaludcod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"  />

                         </div>
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
                            <select id="fip_banco" name="banco"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12">
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
             <tr class="row_form">
                  <td> <span class="sp12b">Sistema de Pensiones</span></td>
                  <td>:</td>
                  <td> 
                      <select id="fip_haspension" name="haspension" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                          <option value="1">Si</option>
                          <option value="0" selected="selected">No</option>
                      </select>
                       <div class="containerItems" style="display:none"> 
                          <select id="fip_tipopension" name="tipopension"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12">
                             <option value="1" selected="selected">ONP</option>
                             <option value="2">AFP</option>
                          </select> 
                         <span class="sp12b"># </span>
                          <input id="fip_codpension" name="codpension" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"  />

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
                           </div>
                      </div>

                  </td>
             </tr>
            
        </table>
            <br/><br/><br/>
        </form> 
       
</div>
