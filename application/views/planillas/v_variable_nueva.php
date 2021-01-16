<div class="window_container">
  
    
    <div class="dv_subtitle3">
                 Registrar una nueva variable
    </div>
    
   <form dojoType="dijit.form.Form" id="form_nueva_variable">      

    <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width:720px; height:350px;">

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
                                  <input name="nombre" dojoType="dijit.form.TextBox" class="formelement-150-11" data-dojo-props="maxlength:40 "   /> 
                             </td> 
                          </tr>
                            <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Nombre Corto (Boleta de Pago) </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                  <input name="nombrecorto" dojoType="dijit.form.TextBox" class="formelement-150-11" data-dojo-props="maxlength:20 "  /> 
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
                                 <div dojoType="dijit.form.Textarea"  data-dojo-props="name: 'descripcion', maxlength: 200"  class="formelement-150-11" ></div>
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

                                 <select id="selgc_tipoplanilla" dojoType="dijit.form.MultiSelect"  multiple="true" class="formelement-150-11"   size="<?PHP echo sizeof($tipos_planilla); ?>"  > 
                                     <?PHP 
                                        foreach($tipos_planilla as $tipo){
                                            ?>

                                            <option value="<?PHP echo trim($tipo['plati_key']); ?>">  <?PHP echo trim($tipo['plati_nombre']); ?>  </option> 
                                     <?PHP 

                                        }
                                     ?> 
                                 </select> 
                             </td> 
                          </tr>


                     </table>        


        </div>
        <div dojoType="dijit.layout.ContentPane"  data-dojo-props=' region:"right" ' attachParent="true" style="width: 400px;  " class="panelpegadito" >
 
            <input type="hidden" name="tipo" value="1" />
              <table class="_tablepadding4" >
                        

                        <!-- 
                         <tr class="row_form">
                              <td width="260"> 
                                 <span class="sp12b"> Tipo  </span> 
                             </td>
                             <td width="10"> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <select name="tipovariable"  dojoType="dijit.form.Select" data-dojo-props="name: 'tipovariable'" class="formelement-150-11" > 
                                     <option value="1" selected="true"> Variable de Calculo </option>
                                     <option value="2"> Variable de Proceso </option>
                                 </select>  
                             </td> 
                          </tr>

                          <tr class="row_form">
                              <td> 
                                 <span class="sp12b"> Tipo de valor </span> 
                             </td>
                             <td width="10"> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <select name="tipo"  dojoType="dijit.form.Select" data-dojo-props="name: 'tipo'" class="formelement-150-11" > 
                                     <option value="1" selected="true"> Solo Numeros</option>
                                     <option value="2"> Lista de opciones</option>

                                 </select>  
                             </td> 
                          </tr>
                        -->

                          <tr class="row_form">
                              <td width="100"> 
                                 <span class="sp12b"> Grupo </span> 
                             </td>
                             <td width="10"> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <select id="selvariablegrupo" name="grupo"  dojoType="dijit.form.FilteringSelect" data-dojo-props="name: 'grupo'" class="formelement-150-11" > 
                                     <option value="0" selected="true"></option>  
                                    <?PHP 
                                       foreach($grupos as $g){
                                                echo "<option value='".$g['gvc_id']."'>".trim($g['gvc_nombre'])."</option>";
                                       }
                                    ?>
                                 </select>  
                             </td> 
                          </tr>
      
                           <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Valor por defecto </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                  <input name="pordefecto" dojoType="dijit.form.TextBox" class="formelement-50-11" value="0.00"  /> 
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
                                         <option value="<?PHP echo $opt['vau_id'] ?>" > <?PHP echo $opt['vau_nombre'] ?></option>
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
                                         <option value="<?PHP echo $opt['vape_id'] ?>" > <?PHP echo $opt['vape_nombre'] ?></option>
                                     <?PHP
                                       }  
                                     ?>
                                 </select>
                                 
                             </td> 
                          </tr>

                         <!--    

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Por Planilla </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                 <input name="porplanilla" type="checkbox" data-dojo-props="name:'porplanilla'" dojoType="dijit.form.CheckBox" value="1"/> 
                             </td> 
                          </tr>
                          
                          
                          
                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Referente al  </span> 
                             </td>
                             <td> 
                                 <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                  <select dojoType="dijit.form.Select" name="ownner"  data-dojo-props="name: 'ownner'" data-dojo-props="name:'ownner'" class="formelement-100-11">
                                     <option value="2" selected="true"> Sistema</option>
                                     <option value="1">  Usuario</option>
                                 </select>
                             </td> 
                          </tr> -->
                          
                            <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Mostrar en impresion </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td>
                                <!--  <input name="displayprint" type="checkbox" dojoType="dijit.form.CheckBox" value="1"/>  -->
                                   <select dojoType="dijit.form.Select"   data-dojo-props="name: 'displayprint'" class="formelement-180-11" style="width:180px;" />   
                                     <option value="1" >Siempre                  </option>
                                     <option value="2" >Solo cuando es mayor a cero</option>
                                     <option value="3" selected="true" > Nunca </option>
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
                                 <input name="enplanillon" type="checkbox" data-dojo-props="name:'enplanillon'" dojoType="dijit.form.CheckBox" value="1"/> 
                             </td> 
                          </tr>

                          <tr class="row_form">
                             <td> 
                                 <span class="sp12b"> Alias en Planilla </span> 
                             </td>
                             <td> 
                                  <span class="sp12b"> : </span> 
                             </td> 
                             <td> 
                                  <input name="nombreplanillon" dojoType="dijit.form.TextBox" class="formelement-50-11" data-dojo-props="maxlength:2 "  /> 
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
                                  <input name="orden" dojoType="dijit.form.TextBox" class="formelement-50-11" data-dojo-props="maxlength:5 "  /> 
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
                                    <input name="afecto_a_cuarta_quinta" type="checkbox" data-dojo-props="name: 'afecto_a_cuarta_quinta'"  dojoType="dijit.form.CheckBox" value="1"/> 
                                   
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
                                 <input name="cuentaremuneracion" type="checkbox" data-dojo-props="name:'cuentaremuneracion'" dojoType="dijit.form.CheckBox" value="1" <?PHP if($variable_info['vari_planillon'] == '1') echo ' checked ' ?>/> 
                             </td> 
                          </tr>

                      </table>



        </div>

    </div>

    
   <div align="center" style="margin: 6px 0px 0px 0px;">

          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
            <?PHP 
               $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
            ?>
              <script type="dojo/method" event="onClick" args="evt">
                   Variables.Ui.btn_registrar_click(this,evt); 

              </script>
              <label class="lbl11">
                      Registrar variable
              </label>
         </button>
   </div>
    
</form> 
 
    
</div>