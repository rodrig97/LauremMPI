 
<div data-dojo-type="dijit.form.Form" 
     id="form_asisplati_diaestado_config" style="overflow:auto; "> 
 

<div class="<?PHP echo (is_array($estado_plati_info) === TRUE) ? 'dv_busqueda_personalizada' : 'dv_red_alert'; ?>">  

    <table class="_tablepadding2">
         <tr>
            <td width="90"> 
                <span class="sp11b"> Estado del día: </span>
            </td>
            <td>
                <span class="sp12">  <?PHP echo  $dia_info['hatd_nombre']; ?> </span> 
            </td>
         </tr>
    </table>    

</div>

<table class="_tablepadding4">

    <tr>

        <td colspan="3" align="left"> 
    
              <input type="hidden" name="view" class="key" value="<?PHP echo trim($plati_key); ?>"  />     
              <input type="hidden" name="estado" class="key" value="<?PHP echo trim($estado_key); ?>"  />     

              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP 
                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                  ?>
                   <script type="dojo/method" event="onClick" args="evt">


                         var data = dojo.formToObject('form_asisplati_diaestado_config');
                           
                          if(Asistencias._M.estadodia_plati_config_actualizar.process(data))
                          {
                              Asistencias._V.view_planillatipo_config_estado.reload();

                              Asistencias.Ui.Grids.estados_del_dia_plati.refresh();
                            
                          }

                   </script>
                   <label class="sp11">
                    
                         <?PHP echo (is_array($estado_plati_info) === TRUE) ? ' Actualizar Configuración' : 'Habilitar y guardar Configuración'; ?>          
                   </label>
              </button>


              <?PHP 
                 if(is_array($estado_plati_info) === TRUE)
                 { 
              ?>  
              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP 
                     $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                  ?>
                   <script type="dojo/method" event="onClick" args="evt">

    

                   </script>
                   <label class="sp11">
                                 Desabilitar   
                   </label>
              </button>

              <?PHP 
                  }
              ?>


        </td>
    </tr> 

    <tr class="row_form">
        <td width="80">  
            <span class="sp11"> Registrar con marcación de horas </span>
        </td>

        <td width="5">  
            <span class="sp11"> : </span>
        </td>
        <td width="240">
             <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'con_marcacion_de_horas'" style="width:50px; font-size:11px;">
                 <option value="0" <?PHP echo ($estado_plati_info['htp_registrar_marcacion_horas'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                 <option value="1" <?PHP echo ($estado_plati_info['htp_registrar_marcacion_horas'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
             </select>  
        </td>
    </tr> 
    <tr class="row_form">
        <td>  
            <span class="sp11"> Disponible en la hoja de asistencia </span>
        </td>

        <td>  
            <span class="sp11"> : </span>
        </td>
        <td>
             <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'disponible_en_hoja'" style="width:50px; font-size:11px;">
                 <option value="0" <?PHP echo ($estado_plati_info['htp_edicionenhoja'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                 <option value="1" <?PHP echo ($estado_plati_info['htp_edicionenhoja'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
             </select>  
        </td>
    </tr>
    <tr class="row_form">
        <td>  
            <span class="sp11"> Mostrar en resumen </span>
        </td>

        <td>  
            <span class="sp11"> : </span>
        </td>
        <td>
             <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'mostrarenresumen'" style="width:50px; font-size:11px;">
                 <option value="0" <?PHP echo ($estado_plati_info['htp_mostrarenresumen'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                 <option value="1" <?PHP echo ($estado_plati_info['htp_mostrarenresumen'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
             </select>  
        </td>
    </tr>  
    <tr class="row_form">
        <td>  
            <span class="sp11"> Importar </span>
        </td>

        <td>  
            <span class="sp11"> : </span>
        </td>
        <td>
             <select id="sel_estadoconfig_importar" data-dojo-type="dijit.form.Select" data-dojo-props="name:'importar'" style="width:50px; font-size:11px;">
                 <option value="0" <?PHP echo (trim($estado_plati_info['vari_id']) == '0' || trim($estado_plati_info['vari_id']) == '') ? ' selected="selected" ' : '' ?> > No </option>
                 <option value="1" <?PHP echo (trim($estado_plati_info['vari_id']) != '0') ? ' selected="selected" ' : '' ?> > Si </option>
             </select>  
        </td>
    </tr>  
    <tr id="tr_estadoconfig_importar1"  class="row_form">
        <td>  
            <span class="sp11"> Dato a importar  </span>
        </td>

        <td>  
            <span class="sp11"> : </span>
        </td>
        <td>
             <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'importardato'" style="width:230px; font-size:11px;">
                 <option value="0" <?PHP echo ($estado_plati_info['htp_importar_horas'] == '0') ? ' selected="selected" ' : '' ?> > Numero de registros </option>
                 <option value="1" <?PHP echo ($estado_plati_info['htp_importar_horas'] == '1') ? ' selected="selected" ' : '' ?> > Total de horas efectivamente trabajadas  </option>
                 <option value="2" <?PHP echo ($estado_plati_info['htp_importar_horas'] == '2') ? ' selected="selected" ' : '' ?> > T.de horas segun horario de trabajo </option>
                 <option value="3" <?PHP echo ($estado_plati_info['htp_importar_horas'] == '3') ? ' selected="selected" ' : '' ?> > T.de horas segun H. de T. alternativo  </option>
             </select>  
        </td>
    </tr>  
    <tr id="tr_estadoconfig_importar2"  class="row_form">
        <td>  
            <span class="sp11"> Variable Destino </span>
        </td>

        <td>  
            <span class="sp11"> : </span>
        </td>
        <td>
               <select data-dojo-type="dijit.form.FilteringSelect" 
                       data-dojo-props='name:"variabledestino", disabled:false, autoComplete:false, highlightMatch: "all",  
                                        queryExpr:"*${0}*", invalidMessage: "La variable no esta registrada" ' 
                       style="font-size:11px; width: 200px;">
                    
                        <option value="0"> No especificada </option>

                    <?PHP

                      foreach($variables as $variable)
                      {
                           echo "<option value='".trim($variable['vari_key'])."' ";

                           if(trim($variable['vari_id']) == $estado_plati_info['vari_id'])
                           {
                              echo " selected='selected' ";
                           }

                           echo " > ".trim($variable['vari_nombrecorto'])." </option>";
                      }
                    ?>
              </select>
        </td>
    </tr>  

    <tr id="tr_estadoconfig_importar3"  class="row_form">
        <td>  
            <span class="sp11"> Al importar realizar </span>
        </td>

        <td>  
            <span class="sp11"> : </span>
        </td>
        <td>
              <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'operar'" style="width:100px; font-size:11px;">
                   <option value="0" > -------- </option>
                   <option value="multiplicar" > Multiplicar por </option>
    <!--                <option value="t" > Aplicar tabla de tardanzas </option> -->
              </select> 

              <input type="text" name="multiplo" data-dojo-type="dijit.form.TextBox" style="width:60px; font-size:11px;" value="1" />

        </td>
    </tr>  

    
</table>

</div>