 <div  data-dojo-type="dijit.form.DropDownButton" style="margin:5px 5px 10px 5px;" >

      <span class="sp12b">
            <?PHP 
              $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
           ?>

             Hojas seleccionadas
      </span>
       

      <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda"'>

          <table class="_tablepadding4">
            <tr class="tr_header_celeste">  
              <td> Codigo </td> 
              <td> Proyecto </td> 
              <td> Desde </td>  
              <td> Hasta </td>  
              <td> Descripcion </td>   
            </tr>

              <?PHP 
                 foreach ($hojas_info as $hoja)
                 {
              ?> 
                    <tr class="tr_row_celeste" style="font-size:11px;"> 
                      <td width="60" align="center">  <?PHP echo $hoja['hoa_codigo']; ?> </td>  
                      <td width="200"> <?PHP echo $hoja['proyecto']; ?> </td> 
                      <td width="60"  align="center" > <?PHP echo _get_date_pg($hoja['hoa_fechaini']); ?> </td> 
                      <td width="60"  align="center" > <?PHP echo _get_date_pg($hoja['hoa_fechafin']); ?> </td> 
                      <td width="200"> <?PHP echo trim($hoja['hoa_descripcion']); ?> </td>   
                    </tr>

               <?PHP 
                 }
              ?>
              </table>

      </div>

</div>

<span class="sp11b"> <?PHP echo sizeof($hojas_info); ?> hojas seleccionadas del tipo <?PHP echo $hojas_info[0]['tipo_planilla']; ?>  </span>


<div style="display:inline; float:left;">

    <form data-dojo-type="dijit.form.Form" id="form_importacion_asistencia_config" style="display:inline;"> 
           
        <table class="_tablepadding2">
            <tr>
                <td width="30"> 
                   <span class="sp11b"> Desde  </span>  
                </td>
                <td width="5">
                   <span class="sp11b"> :  </span>  
                </td>
                <td width="60"> 
                       
                   <input type="hidden" name="hojas" value="<?PHP echo $views; ?>" />
                   <input type="hidden" name="planilla" value="<?PHP echo $planilla; ?>" />
                        
                   <input id="hdasistencia_importar_desde" type="hidden" value="<?PHP echo $rango_fechas['fecha_ini'] ?>" /> 

                   <div id="cal_asistencia_importar_desde" data-dojo-type="dijit.form.DateTextBox"
                                    data-dojo-props='type:"text", name:"fechadesde", value:"",
                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                    lang:"es",
                                    required:true,
                                    promptMessage:"mm/dd/yyyy",
                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                    
                                      onChange="dijit.byId('cal_asistencia_importar_hasta').constraints.min = this.get('value');   "
                                    >
                                </div>  
                </td>
                <td width="30">
                   <span class="sp11b"> Hasta  </span>  
                </td>
                <td width="5">
                   <span class="sp11b"> :  </span>  
                </td>
                <td width="60"> 

                   <input id="hdasistencia_importar_hasta" type="hidden" value="<?PHP echo $rango_fechas['fecha_fin'] ?>" /> 

                   <div id="cal_asistencia_importar_hasta"  data-dojo-type="dijit.form.DateTextBox"
                                    data-dojo-props='type:"text", name:"fechahasta", value:"",
                                     constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                    lang:"es",
                                    required:true,
                                    promptMessage:"mm/dd/yyyy",
                                    invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
                                </div>  
                </td> 
            

              </tr> 

            </table>
    </form>

</div>
 
<div  style="display:inline; float:left;">
       <div  data-dojo-type="dijit.form.DropDownButton" >

            <span class="sp12b">
                  <?PHP 
                    $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                 ?>

                   Filtro por trabajador
            </span>
              
            <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda" ' >
                


                <form data-dojo-type="dijit.form.Form" id="form_importacion_asistencia_filtro_trabajador"> 

                <div style="padding: 10px 10px 10px 10px;"> 

                    <table class="_tablepadding4" width="300">
                      <tr class="tr_row">
                          <td width="60"> <span class="sp11b"> DNI  </span> </td>
                          <td width="5"> <span class="sp11b"> : </span> </td>
                          <td width="235">  
                               <input type="text" data-dojo-type="dijit.form.TextBox" name="dni" value=""  class="formelement-80-11"  />
                          </td>
                      </tr>
                      <tr class="tr_row">
                          <td> <span class="sp11b"> Categoria  </span> </td>
                          <td> <span class="sp11b"> : </span> </td>
                          <td>  
                                <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'categoria'"  style="font-size:11px; width:180px;">
                                      
                                      <option value="0"> No Especificar </option>
                                     <?PHP
                                         foreach($categorias as $reg)
                                         {  
                                     
                                              echo "<option value='".trim($reg['platica_id'])."'> ".trim($reg['platica_nombre'])."  </option>";
                                     
                                         }
                                     ?> 
                                    
                                </select>
                          </td>
                      </tr>
                      <?PHP 

                       if($config['grupo_trabajadores'] == '1')
                       {

                      ?>
                      <tr class="tr_row">
                          <td> <span class="sp11b"> Grupo  </span> </td>
                          <td> <span class="sp11b"> : </span> </td>
                          <td>  
                                 <select data-dojo-type="dijit.form.FilteringSelect" 
                                         data-dojo-props='name:"grupo", 
                                                          autoComplete:false, 
                                                          highlightMatch: "all",  
                                                          queryExpr:"*${0}*", 
                                                          invalidMessage: "El grupo no esta registrado" ' 
                                         class="formelement-180-11">

                                     <option value="0"> No Especificar </option>
                                     <?PHP
                                        foreach($grupos as $grupo)
                                        {
                                             echo "<option value='".trim($grupo['hoagru_id'])."'>  ".trim($grupo['hoagru_nombre'])."</option>";
                                        }
                                     ?>
                                </select>
                          </td>
                      </tr>
                      <?PHP 
                         }
                      ?>

                      <?PHP 

                       if($config['importacion_buscar_por_ap'] == '1')
                       {

                      ?>
                      <tr class="tr_row">
                          <td colspan="3"> <span class="sp11b"> Según afectación presupuestal  </span> </td>
                      </tr>
                      <tr class="tr_row">
                          <td> <span class="sp11b"> Tarea  </span> </td>
                          <td> <span class="sp11b"> : </span> </td>
                          <td>  

                                 <select data-dojo-type="dijit.form.FilteringSelect" 
                                         data-dojo-props='name:"tarea", 
                                                          autoComplete:false, 
                                                          highlightMatch: "all",  
                                                          queryExpr:"*${0}*", 
                                                          invalidMessage: "La Tarea Presupuestal no esta registrada" ' 
                                         class="formelement-180-11">

                                     <option value="0"> No Especificar </option>
                                     <?PHP
                                        foreach($tareas as $tarea)
                                        {
                                             echo "<option value='".trim($tarea['tarea_id'])."'> (".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
                                        }
                                     ?>
                                </select>
                          </td>
                      </tr>
                      <tr class="tr_row">
                          <td> <span class="sp11b"> Fuente F.  </span> </td>
                          <td> <span class="sp11b"> : </span> </td>
                          <td>  
                              <select data-dojo-type="dijit.form.FilteringSelect" 
                                      data-dojo-props='name:"fuente", 
                                                       autoComplete:false, 
                                                       highlightMatch: "all",  
                                                       queryExpr:"*${0}*", 
                                                       invalidMessage: "La Fuente de Financiamiento no esta registrada" ' 
                                      class="formelement-180-11">

                                  <option value="0"> No Especificar </option>
                                  <?PHP
                                     foreach($fuentes as $fuente)
                                     {
                                          echo "<option value='".trim($fuente['codigo'])."'>  ".trim($fuente['codigo'])." ".substr(trim($fuente['nombre']), 0, 40)."</option>";
                                     }
                                  ?>
                             </select>

                          </td>
                      </tr>

                      <?PHP 
                        }
                      ?>  
                    </table>

                </div>

                </form>

            </div>

      </div>


      <button dojoType="dijit.form.Button" class="dojobtnfs_12"  > 
         <?PHP 
           $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
         ?>
          <script type="dojo/method" event="onClick" args="evt"> 
      
                Planillas.Ui.Grids.planillas_preview_importacion.refresh();
      
          </script>

          <label class="sp11">
                Buscar  
          </label>
      </button>

</div>

 

 
 
    
</div>