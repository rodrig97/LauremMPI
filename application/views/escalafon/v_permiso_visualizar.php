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
                    Papeleta de salida
                </td>
            </tr>
      </table>

  </div>
     

  <form dojoType="dijit.form.Form" id="form_permiso_nuevasolicitud"> 
      
        <table class="_tablepadding4" width="100%"> 

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
                                   echo get_fecha_larga($info_permiso['pepe_fechadesde']);
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
                                   echo trim($info_permiso['trabajador']);
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
                                    echo trim($info_permiso['autoriza']);
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
                                  echo (trim($info_permiso['pepe_documento_ref']) != '') ? strtoupper(trim($info_permiso['pepe_documento_ref'])) : '-------';
                           ?>
                      </span>

                 </td>  
             </tr>  

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
                                  echo (trim($info_permiso['motivo']) != '') ? strtoupper(trim($info_permiso['motivo'])) : '-------';
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
                                   echo ( trim($info_permiso['pepe_horaini']) != '' ? $info_permiso['pepe_horaini'] : '-----' );
                            ?>
                       </span>
                 </td>  
             </tr>  

             <tr class="row_form"> 
                 <td>
                     <span class="sp11b"> Hora de Retorno </span>
                 </td>  
                 <td>
                     <span class="sp11b"> : </span>
                 </td>  
                 <td>
                       
                       <span class="sp12"> 
                            <?PHP 
                                   echo ( trim($info_permiso['pepe_horafin']) != '' ? $info_permiso['pepe_horafin'] : '-----' );
                            ?>
                       </span>
                 </td>  
             </tr>  

             <tr class="row_form"> 
                 <td>
                     <span class="sp11b"> Observación </span>
                 </td>  
                 <td>
                     <span class="sp11b"> : </span>
                 </td>  
                 <td>
                      <span class="sp12"> 
                           <?PHP 
                                  echo (trim($info_permiso['pepe_nota']) != '') ? strtoupper(trim($info_permiso['pepe_nota'])) : '-----';
                           ?>
                      </span>

                 </td>  
             </tr>  



        </table> 
      
  </form> 

 
    
</div>