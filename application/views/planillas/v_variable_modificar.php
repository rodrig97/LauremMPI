<div class="window_container">
  
   
        
<div class="dv_celeste_padding4">
    <b>Variable: </b>
    <?PHP  echo trim($variable_info['vari_nombre']); ?>
</div>
    
    
   <form dojoType="dijit.form.Form" id="form_nueva_variable">      

    <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'  style="width:720px; height:350px;">

        <div dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"center" ' attachParent="true" style="width: 320px;  " class="panelpegadito" >
                
                     <table class="_tablepadding4" width="100%" border="0">
                          <tr class="row_form">
                             <td width="180"> 
                                 <span class="sp12b"> Nombre de la Variable </span> 
                             </td>
                             <td width="10"> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                  <input name="nombre" dojoType="dijit.form.TextBox" value="<?PHP echo trim($variable_info['vari_nombre']); ?>" class="formelement-150-11"  /> 
                             </td> 
                          </tr>
                            <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Nombre Corto (alias) </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                  <input name="nombrecorto" dojoType="dijit.form.TextBox" value="<?PHP echo trim($variable_info['vari_nombrecorto']); ?>" class="formelement-150-11"  /> 
                             </td> 
                          </tr>
                            <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Descripcion </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                 <div dojoType="dijit.form.Textarea" value="<?PHP echo trim($variable_info['vari_descripcion']); ?>"  data-dojo-props="name: 'descripcion', maxlength: 200"  class="formelement-150-11" ></div>
                             </td> 
                          </tr>

                         <tr class="row_form">
                              <td> 
                                 <span class="sp12b"> Grupo </span> 
                             </td>
                             <td width="10"> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <select id="selvariablegrupo" name="grupo"  dojoType="dijit.form.FilteringSelect" data-dojo-props="name: 'grupo'" class="formelement-150-11" > 
                                     <option value="0"></option>  
                                    <?PHP 
                                     foreach($grupos as $g){
                                                echo "<option value='".$g['gvc_id']."' "; 
                                                if( $g['gvc_id'] == $variable_info['gvc_id'] ) echo  ' selected="true" ';  

                                                echo " >".trim($g['gvc_nombre'])."</option>";
                                       }
                                    ?>
                                 </select>  
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Aplicable a </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 

                             <td> 
                                    <span class="sp12b">  <?PHP echo trim($variable_info['tipo_planilla']); ?> </span>
                                 
                             </td> 
                          </tr>
                             <tr> 
                              <td colspan="3"> 
                                   <div class="dv_subtitle4">Conceptos relacionados </div>
                                   <div class="dv_cr_1"> 
                                        <?PHP 
                                            if(sizeof($operando_conceptos)==0) echo ' <span style="font-size:11px; color:#333; " >-- Ninguno -- </span>';
                                            foreach($operando_conceptos as $k => $cop){
                                                 echo  '<label> ';
                                                     if($k!=0) echo ',';
                                                 echo   $cop['conc_nombre'].' </label> ';
                                            }
                                        ?> 
                                   </div>
                               </td>
                           </tr>


                     </table>        


        </div>
        <div dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"right" ' attachParent="true" style="width: 400px;   " class="panelpegadito" >
 
            <input type="hidden" name="tipo" value="1" /> 

              <table class="_tablepadding4" style="width: 100%">
                      
                           <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Valor por defecto </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                  <input name="pordefecto" data-dojo-props="name: 'pordefecto'" dojoType="dijit.form.TextBox" value="<?PHP echo trim($variable_info['vari_valordefecto']); ?>" class="formelement-50-11"   /> 
                             </td> 
                          </tr>
 
                         <tr class="row_form">
                              <td width="100"> 
                                 <span class="sp12b"> Unidad </span> 
                             </td>
                             <td width="10"> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <select  name="unidadmedida"  dojoType="dijit.form.FilteringSelect" data-dojo-props="name: 'unidad'" class="formelement-150-11" > 
                                     <?PHP 
                                       foreach($unidades as $opt)
                                       {
                                      ?>  
                                         <option value="<?PHP echo $opt['vau_id'] ?>"
                                          
                                          <?PHP 
                                                if($opt['vau_id'] == $variable_info['vau_id']) echo " selected='selected' ";
                                          ?>

                                          > <?PHP echo $opt['vau_nombre'] ?></option>
                                     <?PHP
                                       }  
                                     ?>
                                 </select>  
                             </td> 
                          </tr>

                    

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Personalizable </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>

                              <!--    <input name="personalizable" type="checkbox" data-dojo-props="name:'personalizable'" dojoType="dijit.form.CheckBox" value="1"/> 
                               -->   
                                 <select data-dojo-type="dijit.form.Select" style="font-size:11px; width:200px;" data-dojo-props="name:'personalizable'" > 
                                     
                                     <?PHP 
                                       foreach($personalizables as $opt)
                                       {
                                      ?>  
                                         <option value="<?PHP echo $opt['vape_id'] ?>" 
                                          
                                          <?PHP 
                                                if($opt['vape_id'] == $variable_info['vari_personalizable']) echo " selected='selected' ";
                                          ?>

                                           > <?PHP echo $opt['vape_nombre'] ?></option>
                                     <?PHP
                                       }  
                                     ?>
                                 </select>
                                 
                             </td> 
                          </tr>
                          
                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Mostrar en impresion </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                <!--  <input name="displayprint" type="checkbox" dojoType="dijit.form.CheckBox" value="1"/>  -->
                                   <select dojoType="dijit.form.Select"   data-dojo-props="name: 'displayprint'" class="formelement-180-11" style="width:180px;"   />   
                                      <option value="1" <?PHP if($variable_info['vari_displayprint'] == '1') echo ' selected="true"' ?>>Siempre                  </option>
                                      <option value="2" <?PHP if($variable_info['vari_displayprint'] == '2') echo ' selected="true"' ?> >Solo cuando es mayor a cero</option>
                                      <option value="3" <?PHP if($variable_info['vari_displayprint'] == '3') echo ' selected="true"' ?> > Nunca </option>
                                   </select> 
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Se muestra en la planilla de remuneraciones  </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <input name="enplanillon" type="checkbox" data-dojo-props="name:'enplanillon'" dojoType="dijit.form.CheckBox" value="1" <?PHP if($variable_info['vari_planillon'] == '1') echo ' checked ' ?>/> 
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Alias en Planillón </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                  <input name="nombreplanillon" dojoType="dijit.form.TextBox" class="formelement-50-11" data-dojo-props="maxlength:2, name:'nombreplanillon' "  value="<?PHP echo trim($variable_info['vari_planillon_nombre']); ?>"  /> 
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> N° de Orden </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                  <input name="orden" dojoType="dijit.form.TextBox" class="formelement-50-11" data-dojo-props="maxlength:5 "  value="<?PHP echo (trim($variable_info['vari_orden']) != '1000') ?  trim($variable_info['vari_orden']) :  '0'; ?>"  /> 
                             </td> 
                          </tr>
                          

                          <tr class="row_form">
                                    <td> 
                                     <span class="sp12b"> Quinta (Rem computable grati) </span> 
                                    </td>
                                    <td> 
                                        <span class="sp12b"> : </span> 
                                    </td> 
                                    <td>
                                        <input name="afecto_a_cuarta_quinta" type="checkbox" data-dojo-props="name: 'afecto_a_cuarta_quinta'"  dojoType="dijit.form.CheckBox" value="1"  <?PHP if($variable_info['vari_conc_afecto_cuarta_quinta'] == '1') echo 'checked="true"' ?> /> 
                                       
                                    </td> 
                          </tr>
                          

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Cuenta como remuneración  </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <input name="cuentaremuneracion" type="checkbox" data-dojo-props="name:'cuentaremuneracion'" dojoType="dijit.form.CheckBox" value="1" <?PHP if($variable_info['vari_remuneracion'] == '1') echo ' checked ' ?>/> 
                             </td> 
                          </tr>

                          <?PHP 
                              if(sizeof($tabla_datos)> 0)
                              { 
                          ?>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Valor desde la tabla de datos </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                <!--  <input name="displayprint" type="checkbox" dojoType="dijit.form.CheckBox" value="1"/>  -->
                                   <select dojoType="dijit.form.Select"   
                                           data-dojo-props="name: 'tabladatos'" 
                                           class="formelement-180-11" style="width:180px;"   />   
                                      
                                       <option value="0"> ----  </option>     

                                       <?PHP 
                                           foreach ($tabla_datos as $tabla )
                                          {
                                         ?>  
                                              <option value="<?PHP echo $tabla['vtd_id'];?>" <?PHP if($variable_info['vtd_id'] == $tabla['vtd_id']) echo ' selected="true"'  ?>  >  <?PHP echo $tabla['vtd_nombre'];?> </option>
                                         <?PHP 
                                           }
                                       ?>
                                   </select> 
                             </td> 
                          </tr>
                          
                          <?PHP 
                              } 
                              else
                              {
                                ?>

                                    <input type="hidden" name="tabladatos" value="0" />

                                     
                                <?PHP 
                              }
                          ?>

                      </table>



        </div>

    </div>

    
   <div align="center" style="margin: 6px 0px 0px 0px;">
        <input type="hidden" value="<?PHP echo trim($variable_info['vari_key']); ?>" class="hdobjectkey"  />  
        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
            <?PHP 
               $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
            ?>
              <script type="dojo/method" event="onClick" args="evt">
                   Variables.Ui.btn_actualizar_click(this,evt); 

              </script>
              <label class="lbl11">
                      Actualizar variable
              </label>
         </button>
   </div>
    
</form> 
 
    
</div>