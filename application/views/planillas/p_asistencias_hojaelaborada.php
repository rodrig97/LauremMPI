<input type="hidden" id="hdviewasistencia_id" value="<?PHP echo trim($hoja_info['hoa_key']);?>" />

<input type="hidden" id="hdviewasistencia_estado_id" value="<?PHP echo trim($hoja_info['estado_id']);?>" />
<input type="hidden" id="hdviewasistencia_tipo_id" value="<?PHP echo trim($hoja_info['plati_id']);?>" />


<div id="viewasistencia_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="top" 
            data-dojo-props='region:"top", style:"height: 150px;"'>
  

          <div id="dvViewName" class="dv_view_name">
             

               <table  border="0">
                      <tr> 
                           <td> 
                               <?PHP 
                                               $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                                           ?>
                                </td>

                              <td>
                                  Hoja de Asistencia
                                </td>
                            </tr>
               </table>

              
          </div>

                <table class="_tablepadding4" border="0">
                   <tr>
                      <td width="35"> <span class="sp12b"> Codigo    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70"> <span class="sp12"> <?PHP echo  (trim($hoja_info['hoa_codigo']) == '' ) ? '------'  : trim($hoja_info['hoa_codigo']);  ?>   </span> </td>
                       <td colspan="9"> </td> 

                   </tr>   

                   <tr>
                      <td width="35"> <span class="sp12b"> Proyecto    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" colspan="7"> <span class="sp12"> <?PHP echo  (trim($hoja_info['proyecto']) == '' ) ? '------'  : trim($hoja_info['proyecto']);  ?>   </span> </td>
                      <td width="35"> <span class="sp12b"> Residente    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" > <span class="sp12"> <?PHP echo  (trim($hoja_info['residente']) == '' ) ? '------'  : trim($hoja_info['residente']);  ?>   </span> </td>
                        
                   </tr>   
 
                  <tr>
                      <td width="35"> <span class="sp12b"> Desde    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechaini'])); ?>   </span> </td>

                      <td width="35"> <span class="sp12b"> Hasta    </span> </td>
                      <td width="10" align="center">  <span class="sp12b"> :       </span> </td>
                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechafin'])); ?>    </span> </td>

                      <td width="94"> <span class="sp12b"> Tipo Trabajador    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="180"> <span class="sp12">  <?PHP echo trim($hoja_info['tipo_planilla']); ?>    </span> </td>

                     
                  
                      <td width="75"> <span class="sp12b"> Descripcion </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="250"> <span class="sp12">  <?PHP echo  (trim($hoja_info['hoa_descripcion']) == '' ) ? '-------------'  : trim($hoja_info['hoa_descripcion']); ?>    </span> </td>
  
                  </tr> 
                
             </table>
 

            
  

 
    </div>


     <div id="dv_hoja_calendario"  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="center" 
            data-dojo-props='region:"center" '>
          
      
    </div>


 </div>
             