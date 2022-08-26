


<form >
   <h5>INFORME CARTA</h5>
   <table class="_tablepadding4" width="100%">
       <tr class="row_form">
           <td width="140"> <span class="sp12b">Informe Dirigido a </span></td>
           <td width="20">:</td>
           <td width="500">


               <input id="dirigidoa" class="fieldform" name="dirigidoa" type="text" data-dojo-type="dijit.form.TextBox" value="<?PHP echo trim($pers_info['indiv_nombres']); ?>" data-dojo-props="maxlength:100" class="formelement-250-12" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Trabajo de </span></td>
           <td>:</td>
           <td>

               <input id="area" class="fieldform" name="area" type="text" data-dojo-type="dijit.form.TextBox" value="<?PHP echo trim($pers_info['indiv_appaterno']); ?>" data-dojo-props="maxlength:100" class="formelement-250-12" />
           </td>
       </tr>

       <tr class="row_form">
           <td> <span class="sp12b">Referencia </span></td>
           <td>:</td>
           <td>

               <input id="creferencia" class="fieldform" name="creferencia" type="text" data-dojo-type="dijit.form.TextBox" value="<?PHP echo trim($pers_info['indiv_apmaterno']); ?>" data-dojo-props="maxlength:100" class="formelement-250-12" />
           </td>
       </tr>

       <!--
        tr FECHA
            td
                ACA VA FECHA ACTUAL
            td
        tr
        -->
       


       <!--
                  <tr class="row_form">
                     <td> <span class="sp12b">ESSALUD</span></td>
                     <td>:</td>
                     <td> 
                         <select id="fip_hasessalud"  name="hasessalud"  data-dojo-type="dijit.form.Select" data-dojo-props='' class="formelement-35-12">
                             <option value="1"    <?PHP if (trim($pers_info['persa_id']) != '') echo 'selected="selected"'; ?> >Si</option>
                               <option value="0"  <?PHP if (trim($pers_info['persa_id']) == '') echo 'selected="selected"'; ?>>No</option>
                         </select>
                            <div class="containerItems" style="display:none">

                             <span class="sp12b">Codigo: </span>
                            <input id="fip_essaludcod" name="essaludcod" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:30" class="formelement-100-12"   value="<?PHP if (trim($pers_info['persa_id']) != '') echo trim($pers_info['persa_codigo']); ?>" />

                            </div>
                     </td>
                </tr> 
              -->

       <tr class="row_form">
           <td> <span class="sp12b">Reposicion</span></td>
           <td>:</td>
           <td>
               <input id="reposicion" name="reposicion" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Ingreso por </span></td>
           <td>:</td>
           <td>
               <input id="ingr_por" name="ingr_por" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Acta de Reposicion Judic </span></td>
           <td>:</td>
           <td>
               <input id="acta_repos" name="acta_repos" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Condicion laboral actual </span></td>
           <td>:</td>
           <td>
               <input id="cond_labor" name="cond_labor" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Mediante </span></td>
           <td>:</td>
           <td>
               <input id="mediante1" name="mediante1" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">De Fecha </span></td>
           <td>:</td>
           <td>
               <input id="de_fecha" name="de_fecha" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Cargo en el que se nombro </span></td>
           <td>:</td>
           <td>
               <input id="cargo_nombro" name="cargo_nombro" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Unidad organica se nombro </span></td>
           <td>:</td>
           <td>
               <input id="unidad_org_nomb" name="unidad_org_nomb" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Dependiente </span></td>
           <td>:</td>
           <td>
               <input id="dependiente" name="dependiente" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Regimen Laboral </span></td>
           <td>:</td>
           <td>
               <input id="regimen_lab" name="regimen_lab" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">A partir del </span></td>
           <td>:</td>
           <td>
               <input id="apartirdel" name="apartirdel" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Rotado a </span></td>
           <td>:</td>
           <td>
               <input id="rotadoa" name="rotadoa" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Mediante </span></td>
           <td>:</td>
           <td>
               <input id="mediante2" name="mediante2" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Profesion </span></td>
           <td>:</td>
           <td>
               <input id="profesion" name="profesion" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
       <tr class="row_form">
           <td> <span class="sp12b">Comentario </span></td>
           <td>:</td>
           <td>
               <input id="comentario" name="comentario" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100" class="formelement-250-12" style="width:100px" value="<?PHP echo trim($pers_info['indiv_essalud']); ?>" />
           </td>
       </tr>
   </table>

   <button>
       Guardar
   </button>
</form>
