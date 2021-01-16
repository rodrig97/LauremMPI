<div class="toolbar_form">
    
    <?PHP 

       if( $this->user->has_key('TRABAJADOR_MODIFICAR_INFO_PERSONAL') )
       { 

    ?>

    <input class="hduserkey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']); ?>" />
    
    <button class="btneditar" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
          <?PHP 
             $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
          ?>
             <label class="lbl11">Editar</label>
              <script type="dojo/method" event="onClick" args="evt">
                     
                       Persona.Ui.btn_fipeditar_click(this,evt);
            </script>
    </button>
    
    <button class="btncanceledicion" dojoType="dijit.form.Button" class="dojobtnfs_12" style="display: none;" > 
          <?PHP 
             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
          ?>
             <label class="lbl11">Cancelar edicion</label>
              <script type="dojo/method" event="onClick" args="evt">
                        
                        Persona.Ui.btn_fipcanceledit_click(this,evt);
            </script>
     </button>

    <button class="btnguardar" dojoType="dijit.form.Button" class="dojobtnfs_12"  style="display: none;" > 
          <?PHP 
             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
          ?>
             <label class="lbl11">Guardar cambios</label>
              <script type="dojo/method" event="onClick" args="evt">
                       // Persona.Ui.btn_showinffullper_click(this,evt);  
                       Persona.Ui.btn_fipactualizar_click(this,evt);
                       
            </script>
     </button>
     

     <?PHP 
        }
        else
        {
            echo ' <b> Usted no tiene permiso para realizar modificaciones </b> ';
        }
     ?>

</div>        

<div class="dv-300auto"> 


      <?PHP 
           if( sizeof($beneficiario) > 0 )
             {
      ?> 
     <div class="dv_celeste_padding2" style="margin:4px 0px 0px 0px;">

          <?PHP  
                
                  echo '<ul> ';
                
                  foreach ($beneficiario as $bene)
                  {
                      
                     echo '  <li> <b> Es beneficiario(a) judicial de: '.$bene['trabajador'].' </b> </li>';

                  }

                 echo '</ul>';
        
          ?>
     </div>

     <?PHP 
            } 
          
     ?>

     <form id="form_info_personal"  data-dojo-type="dijit.form.Form">   
                 <table class="_tablepadding4" width="100%">
                      <tr class="row_form">
                          <td width="140"> <span class="sp12b">Nombres </span></td>
                          <td width="20">:</td>
                          <td width="500"> 


                               <input id="fip_txtnombres" class="fieldform" name="nombres" type="text" data-dojo-type="dijit.form.TextBox"  value="<?PHP echo trim($pers_info['indiv_nombres']); ?>" data-dojo-props="maxlength:100" class="formelement-250-12"  />
                          </td>
                     </tr>
                      <tr class="row_form">
                          <td> <span class="sp12b">Apellido Paterno </span></td>
                          <td>:</td>
                          <td> 

                              <input id="fip_txtpaterno" class="fieldform" name="paterno" type="text" data-dojo-type="dijit.form.TextBox"  value="<?PHP echo trim($pers_info['indiv_appaterno']); ?>"  data-dojo-props="maxlength:100" class="formelement-250-12"  />
                          </td>
                     </tr>
                      <tr class="row_form">
                          <td> <span class="sp12b">Apellido Materno </span></td>
                          <td>:</td>
                          <td> 

                               <input id="fip_txtmaterno" class="fieldform" name="materno" type="text" data-dojo-type="dijit.form.TextBox"  value="<?PHP echo trim($pers_info['indiv_apmaterno']); ?>"  data-dojo-props="maxlength:100" class="formelement-250-12"  />
                          </td>
                     </tr>
                      <tr class="row_form">
                          <td> <span class="sp12b">Sexo</span></td>
                          <td>:</td>
                          <td> 
                              <select  id="fip_selsexo" class="fieldform" name="sexo" data-dojo-type="dijit.form.Select"  class="formelement-100-12" style="width:120px;" >
                                 <option value="1" <?PHP if($pers_info['indiv_sexo']=='1') echo 'selected="selected"'; ?>>Masculino</option>
                                 <option value="2" <?PHP if($pers_info['indiv_sexo']=='2') echo 'selected="selected"'; ?>>Femenino</option>
                                 <option value="0" <?PHP if($pers_info['indiv_sexo']=='0' || $pers_info['indiv_sexo']=='' ) echo 'selected="selected"'; ?>> ------- </option>
                             </select> 
                          </td>
                     </tr>

                     <tr class="row_form">
                               <td> <span class="sp12b">Estado Civil</span></td>
                               <td>:</td>
                               <td> 
                                   <select  id="fip_estadocivil"  class="fieldform" name="estadocivil" data-dojo-type="dijit.form.Select"  class="formelement-50-12" style="width:120px; font-size:12px;" >
                                        <option value="0" <?PHP if($pers_info['indiv_estadocivil']=='0') echo 'selected="selected"'; ?> > No especificado</option>
                                        <option value="1" <?PHP if($pers_info['indiv_estadocivil']=='1') echo 'selected="selected"'; ?> >Soltero(a)</option>
                                        <option value="2" <?PHP if($pers_info['indiv_estadocivil']=='2') echo 'selected="selected"'; ?> >Casado(a)</option>
                                        <option value="3" <?PHP if($pers_info['indiv_estadocivil']=='3') echo 'selected="selected"'; ?> >Divorciado(a)</option>
                                        <option value="4" <?PHP if($pers_info['indiv_estadocivil']=='4') echo 'selected="selected"'; ?> >Viudo(a)</option>
                                    </select> 
                                </td>
                           
                        </tr> 

                      <tr height="35"   class="row_form">
                          <td> <span class="sp12b">Lugar de Origen</span></td>
                          <td>:</td>
                          <td colspan="4"> 
                             <!--   <select id="fip_departamento" name="departamento" data-dojo-type="dijit.form.Select" data-dojo-props='required:true ' style="width: 130px; font-size:10px;">
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

                              </select> -->
                                   <select    data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"ciudad", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"${0}*", invalidMessage: "La Ciudad no esta registrada" ' style="margin-left:0px; font-size:12px; width: 250px;">
                                                                <option value="0"> No Especificar </option>
                                                                   <?PHP
                                                                   $lugar = false;
                                                                   foreach($ciudades as $ciudad){

                                                                       if(trim($ciudad['distrito_id'])."-".trim($ciudad['provincia_id'])."-".trim($ciudad['departamento_id'])  == trim($pers_info['distrito'])."-".trim($pers_info['provincia'])."-".trim($pers_info['departamento'])   )
                                                                       {
                                                                          $lugar = true;    
                                                                       }

                                                                        echo "<option value='".trim($ciudad['distrito_id'])."-".trim($ciudad['provincia_id'])."-".trim($ciudad['departamento_id'])."' ";
                                                                        if($lugar) echo ' selected="true" ';
                                                                        echo "   >  ".trim($ciudad['distrito'])." - ".trim($ciudad['provincia'])." - ".trim($ciudad['departamento'])."   </option>";

                                                                        $lugar=false;
                                                                   }
                                                                 ?>
                                                           </select>


                          </td>
                   </tr>
                      
                      <tr class="row_form">
                          <td> <span class="sp12b">Nacionalidad</span></td>
                          <td>:</td>
                          <td> 
                             <select  class="fieldform" id="fip_selnacionalidad" name="nacionalidad" data-dojo-type="dijit.form.Select"  class="formelement-80-12" style="width:100px;">
                                 <option value="1">Peruano(a)</option>

                             </select> 
                          </td>
                     </tr>
                     <tr class="row_form">
                          <td> <span class="sp12b">Fecha de Nacimiento</span></td>
                          <td>:</td>
                          <td> 
                             <!-- <input name="fechanac" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"   />
                             -->

                             <div  class="fieldform" name="fechanac" id="fip_fechanac" data-dojo-type="dijit.form.DateTextBox"
                                                data-dojo-props='type:"text", name:"fechanac", value:"<?PHP echo $pers_info['indiv_fechanac']; ?>",
                                                 constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                lang:"es",
                                                required:true,
                                                promptMessage:"mm/dd/yyyy",
                                                invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' style="width:100px;">
                             </div>


                             <span class="sp12b"> Edad:  </span> <?PHP echo $pers_info['edad']; ?> años
                        </td>
                     </tr>
                     <tr class="row_form">
                          <td> <span class="sp12b">Dni</span></td>
                          <td>:</td>
                          <td> 
                              <input  id="fip_txtdni" name="dni" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:8, readOnly: true" class="formelement-100-12" value="<?PHP echo trim($pers_info['indiv_dni']); ?>"  />
                          </td>
                     </tr>
                     <tr class="row_form">
                          <td> <span class="sp12b">Ruc</span></td>
                          <td>:</td>
                          <td> 
                              <select id="fip_selhasruc" name="hasruc" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12"  style="width: 40px;" >
                                 <option value="1" <?PHP if(trim($pers_info['indiv_ruc'])!='') echo 'selected="selected"'; ?> >Si</option>
                                 <option value="0" <?PHP if(trim($pers_info['indiv_ruc'])=='') echo 'selected="selected"'; ?>>No</option>
                              </select>
                              <div class="containerItems" style="display:none">
                                <span class="sp12b">#</span>
                                <input id="fip_txtruc" name="ruc" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:11" class="formelement-100-12"  value="<?PHP echo trim($pers_info['indiv_ruc']); ?>"   />
                              </div>
                          </td>
                     </tr>
                     <tr class="row_form">
                          <td> <span class="sp12b">Libreta Militar</span></td>
                          <td>:</td>
                          <td> 
                             <select  id="fip_selhaslib"  name="haslibreta"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12" style="width: 40px;">
                                 <option value="1" <?PHP if(trim($pers_info['indiv_libmilitar_cod'])!='') echo 'selected="selected"'; ?> >Si</option>
                                 <option value="0"  <?PHP if(trim($pers_info['indiv_libmilitar_cod'])=='') echo 'selected="selected"'; ?> >No</option>
                             </select>
                              <div class="containerItems" style="display:none">
                                  <span class="sp12b">#</span>
                                 <input id="fip_txtlibcod"  name="codlibreta" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"  value="<?PHP echo trim($pers_info['indiv_libmilitar_cod']); ?>"  />
                                    <span class="sp12b">De:</span>
                                  <select id="fip_txtlibtip" name="tipolibreta" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-80-12">
                                     <option value="0" <?PHP if($pers_info['indiv_libmilitar_tipo']=='') echo 'selected="selected"'; ?>> ------- </option>
                                     <option value="1" <?PHP if($pers_info['indiv_libmilitar_tipo']=='1') echo 'selected="selected"'; ?> >Ejercito</option>
                                     <option value="2" <?PHP if($pers_info['indiv_libmilitar_tipo']=='2') echo 'selected="selected"'; ?>>Fap</option>
                                     <option value="3" <?PHP if($pers_info['indiv_libmilitar_tipo']=='3') echo 'selected="selected"'; ?>>Marina</option>
                                  </select> 
                              </div>

                          </td>
                     </tr>
                     <tr class="row_form">
                          <td> <span class="sp12b">Brevete</span></td>
                          <td>:</td>
                          <td> 
                               <select id="fip_hasbrevete" name="hasbrevete"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12" style="width: 40px;">

                                 <option value="1" <?PHP if(trim($pers_info['indiv_brevete_cod'])!='') echo 'selected="selected"'; ?> >Si</option>
                                 <option value="0" <?PHP if(trim($pers_info['indiv_brevete_cod'])=='') echo 'selected="selected"'; ?>>No</option>

                               </select>
                               <div class="containerItems" style="display:none">
                                     <span class="sp12b">#</span>
                                     <input id="fip_codbrevete" name="codbrevete" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10" class="formelement-100-12"   value="<?PHP echo trim($pers_info['indiv_brevete_cod']); ?>"   />
                                        <span class="sp12b">Categor&iacute;a:</span>
                                      <select id="fip_tipobrevete" name="tipobrevete" data-dojo-type="dijit.form.Select" data-dojo-props=' ' class="formelement-50-12">
                                        <option value="0" <?PHP   if(trim($pers_info['indiv_brevete_tipo'])== '' ) echo ' selected="selected" ';  ?>> ------ </option>
                                         <?PHP 
                                            foreach($brevetes as $tipobrevet){
                                                echo '<option value="'.$tipobrevet['id'].'"';

                                                if(trim($pers_info['indiv_brevete_tipo'])== $tipobrevet['id'] ) echo ' selected="selected" '; 

                                                echo ' >'.trim($tipobrevet['label']).'</option>';
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
                              <input id="fip_direccion1"  name="direccion1" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" value="<?PHP echo trim($pers_info['indiv_direccion1']); ?>"  />
                          </td>
                     </tr>
                     <tr class="row_form">
                         <td> <span class="sp12b">Direcci&oacute;n 2</span></td>
                          <td>:</td>
                          <td> 
                              <input id="fip_direccion2"  name="direccion2" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" value="<?PHP echo trim($pers_info['indiv_direccion2']); ?>" />
                          </td>
                     </tr>
                     <tr class="row_form">
                         <td> <span class="sp12b"> Telefono casa </span></td>
                          <td>:</td>
                          <td> 
                              <input  id="fip_fono"  name="fono" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-80-12" value="<?PHP echo trim($pers_info['indiv_telefono']); ?>"  />
                          </td>
                     </tr>
                        <tr class="row_form">
                         <td> <span class="sp12b"> Telefono celular </span></td>
                          <td>:</td>
                          <td> 
                              <input  id="fip_celular"  name="celular" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:20" class="formelement-80-12" value="<?PHP echo trim($pers_info['indiv_celular']); ?>"  />
                          </td>
                     </tr>
                      <tr class="row_form">
                         <td> <span class="sp12b"> Email </span></td>
                          <td>:</td>
                          <td> 
                              <input id="fip_email"   name="email" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:80" class="formelement-200-12" value="<?PHP echo trim($pers_info['indiv_email']); ?>"   />
                          </td>
                     </tr>

                     <!--
                       <tr class="row_form">
                          <td> <span class="sp12b">ESSALUD</span></td>
                          <td>:</td>
                          <td> 
                              <select id="fip_hasessalud"  name="hasessalud"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                                  <option value="1"    <?PHP if(trim($pers_info['persa_id'])!='') echo 'selected="selected"'; ?> >Si</option>
                                    <option value="0"  <?PHP if(trim($pers_info['persa_id'])=='') echo 'selected="selected"'; ?>>No</option>
                              </select>
                                 <div class="containerItems" style="display:none">

                                  <span class="sp12b">Codigo: </span>
                                 <input id="fip_essaludcod" name="essaludcod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"   value="<?PHP if(trim($pers_info['persa_id'])!='') echo trim($pers_info['persa_codigo']); ?>" />

                                 </div>
                          </td>
                     </tr> 
                   -->
                    <tr class="row_form">
                        <td> <span class="sp12b">Essalud </span></td>
                        <td>:</td>
                        <td> 
                             <input id="fip_essalud" name="essalud" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
                        </td>
                   </tr>
                      <tr class="row_form">
                          <td> <span class="sp12b">Cuenta Bancaria</span></td>
                          <td>:</td>
                          <td> 
                              <select id="fip_hascbanco" name="hascbanco"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                                  <option value="1" <?PHP if(trim($pers_info['pecd_id'])!='') echo 'selected="selected"'; ?> >Si</option>
                                  <option value="0" <?PHP if(trim($pers_info['pecd_id'])=='') echo 'selected="selected"'; ?> >No</option>
                               </select>
                              <div class="containerItems" style="display:none">

                                        <span class="sp12b"># </span>
                                          <input  id="fip_bancocod" name="bancocod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12" value="<?PHP if(trim($pers_info['pecd_id'])!='') echo trim($pers_info['pecd_cuentabancaria']); ?> "  />

                                    <div class="dvblock">
                                    <span class="sp12b">Entidad bancaria: </span>
                                    <select id="fip_banco" name="banco"  data-dojo-type="dijit.form.Select" data-dojo-props="name:'banco'" class="formelement-50-12">
                                      
                                            <?PHP
                                            $sel = false;
                                        foreach($bancos as $banco){
                                            
                                            echo '<option value="'.$banco['id'].'" ';
                                            
                                            if($banco['ebanco_id']== $pers_info['ebanco_id'] ){
                                                echo ' selected="selected" ';
                                                $sel= true;
                                            }
                                            echo ' >'.trim($banco['label']).'</option>';
                                           
                                        }
                                        
                                        
                                     ?>
                                          <option value="0" <?PHP if(!$sel) echo 'selected="selected"'; ?> > ------ </option>
                                    </select>
                                   </div>
                              </div>
                         </td> 
                     </tr>
                     <tr class="row_form">
                          <td> <span class="sp12b">Sistema de Pensiones</span></td>
                          <td>:</td>
                          <td> 
                          <!--     <select id="fip_haspension" name="haspension" data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                                  <option value="1" <?PHP if(trim($pers_info['peaf_id'])!='') echo 'selected="selected"'; ?>>Si</option>
                                  <option value="0" <?PHP if(trim($pers_info['peaf_id'])=='') echo 'selected="selected"'; ?>>No</option>
                              </select> -->
                               <div class="containerItems" style="display:inline">  
                                  <select id="fip_tipopension" name="tipopension"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12">
                                     <option value="0" <?PHP if(trim($pers_info['pentip_id'])=='' || trim($pers_info['pentip_id'])=='0' ) echo 'selected="selected"'; ?> > ... </option>
                                     <option value="1" <?PHP if(trim($pers_info['pentip_id'])=='1') echo 'selected="selected"'; ?> >ONP</option>
                                     <option value="2" <?PHP if(trim($pers_info['pentip_id'])=='2') echo 'selected="selected"'; ?>>AFP</option>
                                  </select> 
                                 <span class="sp12b"># </span>
                                  <input id="fip_codpension" name="codpension" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12" value="<?PHP if(trim($pers_info['peaf_id'])!='') echo trim($pers_info['peaf_codigo']); ?>"  />
                                  <span class="sp12b"> Jubilado:  </span>
                                  <select id="fip_pesion_jubilado" name="estajubilado"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'estajubilado' " class="formelement-50-12" style="width:60px;">
                                      <option value="0"  <?PHP if(trim($pers_info['peaf_jubilado'])=='0') echo 'selected="selected"'; ?> > No </option>
                                      <option value="1"  <?PHP if(trim($pers_info['peaf_jubilado'])=='1') echo 'selected="selected"'; ?> > Si </option>
                                  </select>   

                                   <div class="containerItems" style="display:none"> 
                                     <span class="sp12b">Afiliado a: </span>
                                      <select id="fip_afp"  name="afp"  data-dojo-type="dijit.form.Select" data-dojo-props="name:'afp'" class="formelement-50-12" >
                                             
                                             
                                             <?PHP
                                                    $sel = false;
                                                   foreach($afps as $afp){

                                                        echo '<option value="'.$afp['id'].'" ';

                                                        if($afp['afp_id']== $pers_info['afp_id'] ){
                                                            echo ' selected="selected" ';
                                                            $sel= true;
                                                        }
                                                        echo ' >'.trim($afp['label']).'</option>';

                                                    }


                                             ?>
                                          <option value="0" <?PHP if(!$sel) echo 'selected="selected"'; ?> > ------ </option>
                                             
                                             
                                             
                                      </select>

                                        
                                      <select id="fip_modoafp" name="modoafp"   data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-50-12">
                                         <option value="1" <?PHP if(trim($pers_info['afm_id'])=='1') echo 'selected="selected"'; ?> >FLUJO</option>
                                         <option value="2" <?PHP if(trim($pers_info['afm_id'])=='2') echo 'selected="selected"'; ?>> SALDO</option>
                                      </select> 

                                      <?PHP 

                                       if(AFP_QUITARINVALIDEZ_AUTOMATICO == FALSE)
                                       {

                                      ?>
 
                                        <span class="sp12b">Invalidez:  </span>
                                        <select id="fip_pesion_aplicainvalidez" name="aplica_invalidez"   data-dojo-type="dijit.form.Select" data-dojo-props="name:'aplica_invalidez' " class="formelement-50-12" style="width:60px;">
                                            <option value="1"  <?PHP if(trim($pers_info['peaf_invalidez'])=='1') echo 'selected="selected"'; ?> > Si </option>
                                            <option value="0"  <?PHP if(trim($pers_info['peaf_invalidez'])=='0') echo 'selected="selected"'; ?> > No </option>
                                            
                                        </select>   


                                      <?PHP 
                                        }
                                      ?>


                                   </div>
                              </div>

                          </td>
                     </tr>
              </table>
    </form> 
</div>