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
                Actualizar la afectación presupuestal
              </td>
          </tr>
      </table>

    </div>

    
    <div style="margin:10px;"> 


        <form id="form_update_afectacion_detalle" data-dojo-type="dijit.form.Form">
          
              <input type="hidden" name="individuo" value="<?PHP echo $info['empleado_key']; ?>" />
              <input type="hidden" name="planilla" value="<?PHP echo $info['pla_key']; ?>" />
       
              <table class="_tablepadding4">
              
                <tr class="row_form">
                  <td width="130">
                      <span class="sp12b"> Trabajador </span>   
                  </td>
                  <td width="8">
                      <span class="sp12b"> : </span>  
                  </td>
                  <td width="450">
                        <span class="sp12"> <?PHP echo $info['empleado'].'  ( DNI: '.$info['empleado_dni'].' )'; ?> </span>
                  </td>
                </tr>

                <tr class="row_form">
                  <td>
                      <span class="sp12b"> Afectación actual </span>   
                  </td>
                  <td>
                      <span class="sp12b"> : </span>  
                  </td>
                  <td>
                       <span class="sp12b"> Tarea: </span>  <span class="sp12"> <?PHP echo $info['tarea'] ?> </span>

                       <span class="sp12b"> Fuente: </span>  <span class="sp12"> <?PHP echo $info['fuente'] ?> </span>
           
                  </td>
                </tr>

                <tr class="row_form">
                   <td> 
                       <span class="sp12b"> Tarea presupuestal </span>
                   </td>
                   <td>
                       <span class="sp12b"> : </span>
                   </td>
                   <td>
                        <select id="selnpla_tarea"  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:6px; font-size:11px; width: 220px;">
                            <option value="0"> No Especificar </option>
                               <?PHP
                               foreach($tareas as $tarea){
                                    echo "<option value='".trim($tarea['cod_tarea'])."'>(".trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
                               }
                             ?>
                       </select>
                   </td>
                </tr>

                <tr class="row_form" > 
                    <td width="40"> 
                        <span class="sp12b"> Fuente Financiamiento </span>
                    </td>
                    <td>
                         <span class="sp12b"> : </span>
                    </td>
                    
                    <td>
                        <select id="sel_crpla_selfuente"   data-dojo-type="dijit.form.Select" data-dojo-props='name:"fuente_financiamiento", disabled:false' style="margin-left:6px; font-size:11px; width: 220px;">
                              
                         </select>
                    </td>
                </tr> 

                <tr>
                    <td colspan="3" align="center">

                           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                             <?PHP 
                                $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                             ?>
                               <script type="dojo/method" event="onClick" args="evt">
                  
                                   var datos = dojo.formToObject('form_update_afectacion_detalle');

                                   if(datos.fuente_financiamiento == '' || datos.tarea == '0' || datos.tarea == '' || datos.tarea == null)
                                   {  
                                        alert('Verifique la nueva afectacion presupuestal');
                                   } 
                                   else
                                   {

                                       if(confirm('Realmente desea actualizar la afectación presupuestal del trabajador'))
                                       {

                                            if(Planillas._M.actualizar_afectacion_detalle.process(datos))
                                            {
                                                 Planillas.Ui.Grids.planillas_detalle.refresh();
                                                 Planillas._M.get_detalleplanilla_variables.reload();
                                                 Planillas._V.actualizacion_afectacion_detalle.close();
                                            }
 
                                       }

                                   }  
                                  
                               </script>
                               <label class="sp12">
                                     Actualizar afectación
                               </label>
                          </button>
                          

                    </td>
                </tr>


              </table>
        
        </form>
    
  </div>

</div>