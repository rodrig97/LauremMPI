<div class="window_container">

       <div id="dvViewName" class="dv_view_name">
            
          <table class="_tablepadding2" border="0">

              <tr> 
                 <td> 
                       <?PHP 
                                 $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                             ?>
                 </td>

                 <td>
                        Reporte de conceptos por trabajador
                 </td>
              </tr>
          </table>
      </div>

      <div>

          <form dojoType="dijit.form.Form" id="form_planilla_reporteconcepto"> 
              
                  <table class="_tablepadding4" >

                      <tr  class="row_form"> 
                         <td width="100">
                             <span class="sp12b">
                             Año
                             </span>
                         </td>
                         <td width="5" width="10">
                              <span class="sp12b"> : </span> 
                         </td>
                              
                         <td width="330">
                          
                              <select id="selplani_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:6px; font-size:11px; width: 80px;">
                              <!--    <option value="2012">2012</option>  -->

                                     <?PHP 
                                        foreach ($anios as $anio)
                                        {
                                          # code...
                                          echo '<option value="'.$anio['ano_eje'].'" ';

                                          if($anio['ano_eje']== date('Y'))
                                          {
                                             echo ' selected="selected" ';
                                          }

                                          echo ' >'.$anio['ano_eje'].'</option>';
                                        }
                                     ?>
                             </select>
                         </td>
                     </tr>
                    
                     <tr  class="row_form"> 
                         <td><span class="sp12b">Mes</span></td>
                         <td>
                                 <span class="sp12b"> : </span> 
                         </td>
                         <td>
                             <select id="selplani_mes" data-dojo-type="dijit.form.Select" data-dojo-props='  name:"mes", disabled:false' style="margin-left:6px; font-size:12px; width: 95px;">
                                 <option value="00"> -------- </option> 
                                 <option value="01">Enero</option> 
                                 <option value="02">Febrero</option> 
                                 <option value="03">Marzo</option> 
                                 <option value="04">Abril</option> 
                                 <option value="05">Mayo</option> 
                                 <option value="06">Junio</option> 
                                 <option value="07">Julio</option> 
                                 <option value="08">Agosto</option> 
                                 <option value="09">Septiembre</option> 
                                 <option value="10">Octubre</option> 
                                 <option value="11">Noviembre</option> 
                                 <option value="12">Diciembre</option> 
                             </select>
                              
                         </td>
                     </tr>

                    <tr class="row_form"> 
                        <td> 
                            <span class="sp12b"> Regimen </span>
                        </td>
                        <td width="10">
                            <span class="sp12b"> : </span> 
                        </td>
                        
                        <td>
                            <select id="sel_crpla_tipoplanilla"  data-dojo-type="dijit.form.Select" 
                                    data-dojo-props='name:"planillatipo", disabled:false' style="margin-left:6px; font-size:12px; width: 200px;">
                                  <?PHP
                                    foreach($tipos as $tipo){
                                         echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                    }
                                  ?>
                            </select>
                        </td>
                    </tr> 
                   
                     <tr class="row_form"> 
                        <td> 
                            <span class="sp12b"> SIAF</span>
                        </td>
                        <td>
                            <span class="sp12b"> : </span> 
                        </td>
                        
                        <td>
                             <input type="input" data-dojo-type="dijit.form.TextBox" class="formelement-100-12" style="margin-left:6px;" data-dojo-props="name: 'siaf'" /> 
                        </td>
                    </tr> 

                    <tr class="row_form"> 
                        <td> 
                            <span class="sp12b"> Planilla</span>
                        </td>
                        <td>
                            <span class="sp12b"> : </span> 
                        </td>
                        
                        <td>
                             <input type="input" data-dojo-type="dijit.form.TextBox" class="formelement-100-12" style="margin-left:6px;"  data-dojo-props="name: 'planilla'" /> 
                        </td>
                    </tr> 

                    <tr class="row_form" id="tr_concepto_nuevo_tipo">
                       <td> 
                           <span class="sp12b"> Tipo </span> 
                       </td>
                       <td> 
                            <span class="sp12b"> : </span> 
                       </td> 
                       <td>
                           <select id="sel_crpla_tipoconcepto"  dojoType="dijit.form.Select"  data-dojo-props="name: 'tipoconcepto'" 
                                  class="formelement-100-11" style="margin-left:6px; font-size:12px; width: 95px;"  /> 
                              
                               <option value="1" selected="true">Ingreso</option>
                               <option value="2" >Descuento</option>
                               <option value="3" >Aportacion</option>
                           </select>  
                       </td> 
                    </tr>

                    <tr class="row_form"> 
                        <td> 
                            <span class="sp12b"> Concepto </span>
                        </td>
                        <td>
                            <span class="sp12b"> : </span> 
                        </td>
                        
                        <td>
                            <input type="hidden" value="<?PHP echo $linea[1]; ?>" class="hdvalor_select" />
                            <input type="hidden" class="hdtipocomponente" value="operando" />

                            <div style="margin-left:6px;"> 
                                <select id="selreporteconcepto_conceptos" class="seloperando" data-dojo-type="dijit.form.FilteringSelect"     
                                     data-dojo-props='  value: "id",
                                                        label : "nombre",  
                                                        store: Conceptos._M.store_conceptos_reporte, 
                                                        style : "width:200px; font-size:11px;",
                                                        autoComplete: false,
                                                        highlightMatch: "all",  
                                                        queryExpr:"*${0}*",
                                                        name : "concepto"

                                                        ' >
                                  

                                 </select>
                           </div>
                        </td>
                    </tr> 
 

               </table>
          </form>
          

      </div>


      <div align="center" style="margin: 10px 0px 0px 0px;">


           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
             <?PHP 
                $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
             ?>
               <script type="dojo/method" event="onClick" args="evt">

                    var data = dojo.formToObject('form_planilla_reporteconcepto');

                    data.generar = 'CONCEPTOS';

                    if(data.concepto != '')
                    {
                        Exporter._M.generar.send(data);
                    }
                    else
                    {
                        alert('Debe especificar un concepto');  
                    }
                   
               </script>
               <label class="sp11">
                    Generar reporte 
               </label>
          </button>

      </div>
 
    
</div>