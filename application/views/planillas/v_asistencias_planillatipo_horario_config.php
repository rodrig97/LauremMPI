<div class="window_container">

 
 <div class="dv_busqueda_personalizada">

   <table class="_tablepadding2">
        <tr>
           <td width="40"> 
               <span class="sp11b"> Para: </span>
           </td>
           <td>
               <span class="sp12">  <?PHP echo $plati_info['plati_nombre']; ?> </span> 
           </td>
        </tr>
   </table>    

 </div>

   <div data-dojo-type="dijit.form.Form" 
           id="form_horariodefecto_config" style="overflow:auto; "> 
 

                  <input type="hidden" name="view" class="key" value="<?PHP echo $plati_info['plati_key']; ?>"  />   

                  <table class="_tablepadding4">

                      <tr class="tr_header_celeste">
                          <td width="100"> 
                            <span class="sp11b"> Día </span>
                          </td>   
                          <td width="10"> 
                             
                          </td>   

                          <td width="80"> 
                            <span class="sp11b"> Laborable </span>
                          </td>   
                          <td width="140"> 
                            <span class="sp11b"> Estado </span>
                          </td>   
                          <td width="180"> 
                            <span class="sp11b"> Horario </span>
                          </td>   
                          
                      </tr>

                    <?PHP 
                       foreach ($dias as $dia)
                       {
                    ?>
                        <tr class="row_form">
                          <td> 
                                <span class="sp11b"> <?PHP echo $dia['dia']; ?> </span>
                          </td>
                          <td>  <span class="sp11b">:  </span> </td>
                          <td>    
                                <select  id="sel_dialaborable_dia<?PHP echo $dia['dia']; ?>"  data-dojo-type="dijit.form.Select" data-dojo-props='  name:"laborable[]"' class="formelement-80-11" style="width:70px;" >
                                     <option value="1" <?PHP echo ($dia['laborable'] == '1' ? " selected='selected' " : "") ?>>  Si </option>
                                     <option value="0" <?PHP echo ($dia['laborable'] == '0' ? " selected='selected' " : "") ?>>  No </option>
                                </select>

                          </td>
                          <td> 
                               <select id="sel_diaestado_dia<?PHP echo $dia['dia']; ?>" 
                                       data-dojo-type="dijit.form.FilteringSelect"  
                                       data-dojo-props='  name:"estado[]", 
                                                          disabled:false, 
                                                          autoComplete:false, 
                                                          highlightMatch: "all",  
                                                          queryExpr:"*${0}*", 
                                                          invalidMessage: "Estado no registrado"  '
                                    class="formelement-100-11" style="width:120px;">


                                      <option value=""> ------ </option>
                                    <?PHP

                                      foreach($estados_dias as $estado)
                                      {
                                           echo "<option value='".trim($estado['hatd_key'])."' ";

                                           if(trim($estado['hatd_id']) == $dia['hatd_id'])
                                           {
                                              echo " selected='selected' ";
                                           }

                                           echo " > ".trim($estado['hatd_nombre'])." </option>";
                                      }
                                    ?>

                                </select>   
                           </td>
                           <td>
                                 <select id="sel_horario_dia<?PHP echo $dia['dia']; ?>" 
                                         data-dojo-type = "dijit.form.FilteringSelect"  
                                         data-dojo-props='  name:"horario[]", 
                                                            autoComplete:false, 
                                                            highlightMatch: "all",  
                                                            queryExpr:"*${0}*", 
                                                            invalidMessage: "Horario no registrado"   ' 
                                      class="formelement-150-11" style="width:170px;">
                                       
                                      
                                      <option value=""> ------ </option>
                                        <?PHP

                                          foreach($horarios as $horario)
                                          {
                                               echo "<option value='".trim($horario['hor_key'])."' ";

                                               if(trim($horario['hor_id']) == $dia['hor_id'])
                                               {
                                                  echo " selected='selected' ";
                                               }

                                               echo " > ".trim($horario['hor_alias'])." </option>";
                                          }
                                        ?>

                                  </select>      
                            </td>
                        </tr>

                    <?PHP
                       }
                    ?>
 

                      <tr>  
                            <td align="center" colspan="4">

                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                    <?PHP 
                                       $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                    ?>
                                     <script type="dojo/method" event="onClick" args="evt">

                                              var data = dojo.formToObject('form_horariodefecto_config'),  
                                                  err = false, horarios = [], estados = [];
     
                                              horarios = data['horario[]'];
                                              estados = data['estado[]'];
                                              laborable = data['laborable[]'];

                                              for(x in horarios)
                                              {  
                                                  if( horarios[x] == '')
                                                  {
                                                     err = true;
                                                     break;
                                                  }
                                              }

                                              for(x in estados)
                                              {
                                                  if( estados[x] == '')
                                                  { 
                                                     err = true;
                                                     break;
                                                  }
                                              }   

                                              for(x in laborable)
                                              {
                                                  if( laborable[x] == '')
                                                  { 
                                                     err = true;
                                                     break;
                                                  }
                                              }  

                                              if(err === true)
                                              {
                                                 
                                                 alert('Por favor verifique los horarios especificados.');
                                              }
                                              else
                                              {

                                                  if(confirm('Realmente desea registrar este horario por defecto ? '))
                                                  {
                                                      if(Asistencias._M.registrar_horario_pordefecto.process(data)) 
                                                      { 

                                                            Asistencias._V.planillatipo_horario_modificar.close(); 
                                                            Asistencias._V.view_planillatipo_config.reload();
                                                      }
                                                  }                                                
                                              }



                                              console.log(data);
                                     </script>
                                     <label class="sp11">
                                                   Actualizar             
                                     </label>
                                </button>
                            </td>
                      </tr> 
                 </table>

             </div>

</div>