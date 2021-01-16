

 <div id="dvViewName" class="dv_view_name">
      
      <table class="_tablepadding2" border="0">
          <tr> 
              <td> 
                   <?PHP 
                             $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                         ?>
              </td>

             <td>
                   Detalle de la solicitud de permiso 
              </td>
          </tr>
      </table>
</div>

 
<div style="padding: 5px 5px 5px 5px;"> 

    <form dojoType="dijit.form.Form" id="form_permiso_solicitud">  

        <input type="hidden" value="<?PHP echo trim($solicitud['pepe_key']); ?>" name="view" />
          

        <table class="_tablepadding4" width="100%"> 

               <tr class="row_form">
                 <td width="150">
                     <span class="sp11b"> Estado </span>
                 </td>  
                 <td>
                     <span class="sp11b"> : </span>
                 </td>  
                 <td>   
                      <span class="sp12"> 
                           <?PHP 
                                  echo trim($solicitud['estado']);
                           ?>
                      </span>
     
                 </td>  
             </tr>

             <tr class="row_form">
                 <td width="150">
                     <span class="sp11b"> Fecha </span>
                 </td>  
                 <td>
                     <span class="sp11b"> : </span>
                 </td>  
                 <td>   
                      <span class="sp12"> 
                           <?PHP 
                                  echo get_fecha_larga($solicitud['pepe_fechadesde']);
                           ?>
                      </span>
     
                 </td>  
             </tr>

             <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Solicita </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                      <span class="sp12"> 
                           <?PHP 
                                  echo trim($solicitud['trabajador']);
                           ?>
                      </span> 
                </td>  
            </tr>  
            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Jefe Inmediato (Autoriza) </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                       <span class="sp12"> 
                            <?PHP 
                                   echo trim($solicitud['autoriza']);
                            ?>
                       </span>
                       
                </td>  
            </tr>  

            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Documento de referencia </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                     <span class="sp12"> 
                          <?PHP 
                                 echo (trim($solicitud['pepe_documento_ref']) != '') ? strtoupper(trim($solicitud['pepe_documento_ref'])) : '-------';
                          ?>
                     </span>

                </td>  
            </tr>  

            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Hora de Salida </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                      
                      <span class="sp12"> 
                           <?PHP 
                                  echo strtoupper(trim($solicitud['pepe_horaini']));
                           ?>
                      </span>
                </td>  
            </tr>  


            <?PHP 
             
               if( $solicitud['estado_id'] == ASIDET_PERMISOESTADO_APROBADO )
               { 
            ?>

                  <tr class="row_form"> 
                      <td>
                          <span class="sp11b"> Hora de Retorno </span>
                      </td>  
                      <td>
                          <span class="sp11b"> : </span>
                      </td>  
                      <td>  

                          <input  id="selsolicitudper_horaretorno" 
                                  data-dojo-type="dijit.form.TimeTextBox"
                                  data-dojo-props='type:"text", 
                                                   name:"horaretorno", 
                                                    title:"",
                                                   constraints:{formatLength:"short"},
                                                   required:true,
                                                   invalidMessage:"" ' 
                                   style="width:90px; font-size:11px;"
                                   onChange=""   />
                             
                      </td>  
                  </tr>  

            <?PHP 
               }
            ?>

            <?PHP 
             
               if( $solicitud['estado_id'] == ASIDET_PERMISOESTADO_RETORNO )
               { 
            ?>

                  <tr class="row_form" > 
                      <td>
                          <span class="sp11b"> Hora de Retorno </span>
                      </td>  
                      <td>
                          <span class="sp11b"> : </span>
                      </td>  
                      <td>  
                           <span class="sp12"> 
                            <?PHP 
                                 echo $solicitud['pepe_horafin'];
                            ?>
                            </span>
                      </td>  
                  </tr>  

            <?PHP 
               }
            ?>


            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Motivo </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td> 
                     
                     <span class="sp12"> 
                          <?PHP 
                                 echo strtoupper(trim($solicitud['motivo']));
                          ?>
                     </span>
                </td>  
            </tr>  
             
            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Lugar de destino </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                       
                       <span class="sp12"> 
                            <?PHP 
                                   echo strtoupper(trim($solicitud['destino']));
                            ?>
                       </span> 

                </td>  
            </tr>  

            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Nota adiccional </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                    
                    <span class="sp12"> 
                         <?PHP 
                                echo (trim($solicitud['pepe_nota']) != '') ? strtoupper(trim($solicitud['pepe_nota'])) : '-------';
                         ?>
                    </span>

                </td>  
            </tr>  

            <?PHP 
             
               if( $solicitud['estado_id'] != ASIDET_PERMISOESTADO_RETORNO )
               { 
            ?>


            <tr class="row_form"> 
                <td>
                    <span class="sp11b"> Observación </span>
                </td>  
                <td>
                    <span class="sp11b"> : </span>
                </td>  
                <td>
                    
 
                    <textarea data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'observacion'" style="font-size:11px; width:250px; "><?PHP echo trim($solicitud['estado_obs']); ?></textarea>

                </td>  
            </tr>  

            <?PHP 
               }
            ?>

          
        </table> 

    </form>


    <div style="margin:7px 0px 0px 0px;"> 


           <?PHP 
            
              if( $solicitud['estado_id'] == ASIDET_PERMISOESTADO_AUTORIZADO )
              { 
           ?> 

            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
              <?PHP 
                 $this->resources->getImage('accept.png',array('width' => '14', 'height' => '14'));
              ?>
                <script type="dojo/method" event="onClick" args="evt">

                      var data  = dojo.formToObject('form_permiso_solicitud'), mensaje = ' <ul> ', err = false;
                    
                      if(Permisos._M.aprobar.process(data))
                      {
                           Planillas.Ui.Grids.permisos_panel.refresh();
                           Permisos._V.ver_detalle_panel.reload();
                      }  



                </script>
                <label class="sp11">
                     Aprobar  
                </label>
           </button>
           
           <?PHP 
              }
           ?>

           <?PHP 
            
              if( $solicitud['estado_id'] == ASIDET_PERMISOESTADO_APROBADO )
              { 
           ?>


            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
              <?PHP 
                 $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
              ?>
                <script type="dojo/method" event="onClick" args="evt">

                      var data  = dojo.formToObject('form_permiso_solicitud'), mensaje = ' <ul> ', err = false;
                    
                      if(Permisos._M.registrar_retorno.process(data))
                      {
                           Planillas.Ui.Grids.permisos_panel.refresh();
                           Permisos._V.ver_detalle_panel.reload();
                      }  



                </script>
                <label class="sp11">
                     Registrar retorno  
                </label>
           </button>


           <?PHP 
              }
           ?>


     
    </div>

</div>
