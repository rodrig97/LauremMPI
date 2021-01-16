
<div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 100%" >
 
    <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"top", splitter: true '  style="height: 55px; padding: 0px 0px 0px 0px;">
        
        <div>
            <table class="_tablepadding2">
                <tr> 
                     <td><span class="sp11b"> Empleado </span></td>
                     <td><span class="sp11b"> : </span></td>
                     <td><span class="sp12"> <?PHP echo trim($trabajador_nombre); ?>   </span></td>
                  
                </tr> 
            </table> 
        </div>
         <div style="margin: 2px 0px 0px 0px;">               
            <input class="hdpladet_empkey" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />       
         

            <?PHP if($tipo_planilla == TIPOPLANILLA_CONSCIVIL ){  ?>


                 <div>
  

                 </div>


                  <table class="_tablepadding2">
                     <tr>
                        <td>
                             <span class="sp11b">Tipo de trabajo: </span>
                        </td>
                         
                        <td>     
                          <select id="seldet_tipotrabajador" data-dojo-type="dijit.form.Select" class="formelement-80-11">
                               <option value="0"> NO ESPECIFICADO </option> 
                               <option value="1"> OPERARIO </option>
                               <option value="2"> OFICIAL</option>    
                               <option value="3"> PEON </option> 
                          </select>
                        </td>
                        <td>  
                            <input class="hdpladet_empkey" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />   
                            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                    $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                  <script type="dojo/method" event="onClick" args="evt">
                                         Planillas.Ui.btn_set_tipotrabajador(this,evt);
                                  </script>
                                  <label class="sp11">
                                        Registrar
                                  </label>
                              </button>

                        </td>
                        <td>
                 
                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                    $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                  <script type="dojo/method" event="onClick" args="evt">
                                       Conceptos.Ui.btn_showaddconcepto_click(this,evt);
                                      
                                  </script>
                                  <label class="sp11">
                                        Agregar Concepto
                                  </label>
                              </button>

                        </td>

                     </tr>
                  </table>

            <?PHP }else{?>
       

                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                <?PHP 
                                  $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                               ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                     Conceptos.Ui.btn_showaddconcepto_click(this,evt);
                                    
                                </script>
                                <label class="sp11">
                                      Agregar Concepto
                                </label>
                 </button>


             <?PHP } ?>

           

         </div>
        
     </div>
    
       <input type="hidden" value="1" id="hddetalleplanillatipo" />
 <?PHP if( $tipo_planilla != TIPOPLANILLA_CONSCIVIL || ( $tipo_planilla == TIPOPLANILLA_CONSCIVIL && $detalle['platica_id'] != '0'  ) ){  ?>

    <!-- 
      <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
               
              <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Ingresos</span>">  -->
     <div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"left" ' style="width:50%; padding: 1px 1px 1px 1px;">
           <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables del Trabajador </span>">                      
              <!-- <div class="dv_red_subtitle"> 
                   Variables de Calculo
               </div>
              -->
            
              <table id="tbpladet_varstra" class="_tablepadding2_c" width="100%">
                  <tr class="tr_header_celeste">
                      <td width="20" align="center"> # </td>
                      <td width="170"> Variable  </td>
                    
                       <td width="50"> Grupo   </td>
                         <td width="10">   </td>
                      <td width="60"> Valor </td>
                      <td width="20"> </td> 
                  <!--     <td width="20"> </td> -->
                  </tr>
                   <?PHP    
                        $c = 1;
                    //    var_dump($variables['trabajador']);
                       foreach($variables['trabajador'] as $vari)
                       {
                   ?>
                            <tr class="row_form"> 
                                <td align="center" >  
                                    <input class="hdplaev_key" type="hidden" value="<?php echo $vari['plaev_key']; ?>"/>   
                                    <span class="sp11"> <?PHP echo $c; $c++; ?> </span> 
                                </td>                         
                                <td> <span class="sp11"> <?PHP echo trim($vari['variable']); ?> </span> </td> 
                                <td align="center"> <span class="sp11"><?PHP echo trim($vari['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                    
                                    <?PHP if(trim($vari['vari_personalizable']) == '2'){   ?>

                                       <input type="hidden" class="hdpladet_varvd" value="<?PHP echo trim($vari['plaev_valor']); ?>"  />
                                       
                                       
                                       <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props=""  value="<?PHP echo trim($vari['plaev_valor']); ?>"
                                              class="txtpladet_vari"  class="formelement-50-11" style="text-align: center;"   />
                                
                                     <?PHP }else{
                                        ?>
                                              <span class="sp11b"> <?PHP echo number_format(trim($vari['plaev_valor']),2); ?> </span>
                                        <?PHP 
                                     } ?>          
                                </td>
                                <td align="center">
                                        

                                 <?PHP if(trim($vari['vari_personalizable']) == '2'){   ?>
                                     <button  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="disabled:true" >
                                          
                                        <?PHP 
                                          $this->resources->getImage('save.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                           Variables.Ui.btn_save_detallevariable_click(this,evt);
                                        </script>
                                        <label class="sp11">

                                        </label>
                                    </button>
                                  <?PHP } ?>


                                </td>
                                <!--
                                  <td align="center">
                                      
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                        <?PHP 
                                          $this->resources->getImage('remove.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                        </script>
                                        <label class="sp11">

                                        </label>
                                    </button>  
                                </td> -->

                            </tr> 
                    <?PHP 
                    if(sizeof($variables['trabajador']) == 0 ) echo " <div class='dv_new_resumen'> No hay variables del SISTEMA relacionadas </div> ";
                       }
                    ?>
              </table> 
        </div>
         
         <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables del sistema </span>">
             
             
               <table class="_tablepadding2_c" width="100%">
                  <tr class="tr_header_celeste">
                      <td width="20" align="center"> # </td>
                      <td width="170"> Variable  </td>
                       <td width="50"> Grupo   </td>
                         <td width="10">   </td>
                      <td width="60"> Valor </td>
                      <td width="20"> </td> 
                  <!--    <td width="20"> </td> -->
                  </tr>
                   <?PHP    
                        $c = 1;
                       foreach($variables['sistema'] as $vari)
                       {
                   ?>
                          <tr class="row_form"> 
                                <td align="center" >  
                                    <input class="hdplaev_key" type="hidden" value="<?php echo $vari['plaev_key']; ?>"/>   
                                    <span class="sp11"> <?PHP echo $c; $c++; ?> </span> 
                                </td>                         
                                <td> <span class="sp11"> <?PHP echo trim($vari['variable']); ?> </span> </td> 
                                  <td align="center"> <span class="sp11"><?PHP echo trim($vari['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                     

                                   <?PHP if(trim($vari['vari_personalizable']) == '2'){   ?>

                                       <input type="hidden" class="hdpladet_varvd" value="<?PHP echo trim($vari['plaev_valor']); ?>"  />
                                       
                                       
                                       <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props=""  value="<?PHP echo trim($vari['plaev_valor']); ?>"
                                              class="txtpladet_vari"  class="formelement-50-11" style="text-align: center;"   />
                                
                                     <?PHP }else{
                                        ?>
                                              <span class="sp11b"> <?PHP echo number_format(trim($vari['plaev_valor']),2); ?> </span>
                                        <?PHP 
                                     } ?>          


                                </td>
                                <td align="center">
                                        
                                 <?PHP if(trim($vari['vari_personalizable']) == '2'){   ?>
                                     <button  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="disabled:true" >
                                          
                                        <?PHP 
                                          $this->resources->getImage('save.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                           Variables.Ui.btn_save_detallevariable_click(this,evt);
                                        </script>
                                        <label class="sp11">

                                        </label>
                                    </button>
                                  <?PHP } ?>


                                </td>
                                   <!--  <td align="center">
                                     
                                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                        <?PHP 
                                          $this->resources->getImage('remove.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                        </script>
                                        <label class="sp11">

                                        </label>
                                </td>
                                    -->
                            </tr> 
                    <?PHP 
                       }
                    ?>
              </table>
              
               <?PHP 
                  if(sizeof($variables['sistema']) == 0 ) echo " <div class='dv_new_resumen'> No hay variables del SISTEMA relacionadas </div> ";
                ?>
 
         
         </div>
     </div>
    
    
     <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
               
              <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Conceptos Ingresos</span>">
                          
                    
                        <table class="_tablepadding2_c" width="100%">
                          <tr class="tr_header_celeste">
                              <td width="40" align="center"> # </td>
                              <td> Concepto remunerativo </td>
                              <td width="50"> Grupo   </td>
                             <td width="10">   </td>
                              <td width="60"> Calcular </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($conceptos['ingresos'] as $conc){
                           ?>
                            <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td> <span class="sppladet_nombreconc"   <?PHP if(trim($conc['plaec_marcado'])=='0') echo ' style="color:#990000; text-decoration: line-through"'; ?> > <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                   <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                     <input type="hidden" value="<?PHP echo trim($conc['plaec_key']); ?> " class="hdpladet_conck" />
                                     <input type="checkBox" data-dojo-type="dijit.form.CheckBox"  <?PHP if(trim($conc['plaec_marcado'])=='1') echo 'checked'; ?>  />
                                </td> 

                            </tr> 
                            <?PHP 
                               }
                            ?>
                      </table> 
                  
                   <?PHP 
                       if(sizeof($conceptos['ingresos']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene INGRESOS vinculadas en esta planilla </div> ";
                     ?>
              </div>
               <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Descuentos</span>">
                  

                      <table class="_tablepadding2_c" width="100%">
                          <tr class="tr_header_celeste">
                              <td width="40" align="center"> # </td>
                              <td> Concepto remunerativo </td>
                               <td width="50"> Grupo   </td>
                             <td width="10">   </td>
                              <td width="60"> Calcular </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($conceptos['descuentos'] as $conc){
                           ?>
                               <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td> <span class="sppladet_nombreconc"   <?PHP if(trim($conc['plaec_marcado'])=='0') echo ' style="color:#990000; text-decoration: line-through"'; ?> > <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                     <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                     <input type="hidden" value="<?PHP echo trim($conc['plaec_key']); ?> " class="hdpladet_conck" />
                                     <input type="checkBox" data-dojo-type="dijit.form.CheckBox"  <?PHP if(trim($conc['plaec_marcado'])=='1') echo 'checked'; ?>  />
                                </td> 

                            </tr> 
                            <?PHP 
                               }
                            ?>
                      </table> 
                   
                    <?PHP 
                       if(sizeof($conceptos['descuentos']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene DESCUENTOS vinculadas en esta planilla </div> ";
                     ?>

              </div>
                <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Aportaciones</span>">
                            

                   <table class="_tablepadding2_c" width="100%">
                          <tr class="tr_header_celeste">
                              <td width="40" align="center"> # </td>
                              <td> Concepto remunerativo </td>
                               <td width="50"> Grupo   </td>
                             <td width="10">   </td>
                              <td width="60"> Calcular </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($conceptos['aportaciones'] as $conc){
                           ?>
                             <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td> <span class="sppladet_nombreconc"   <?PHP if(trim($conc['plaec_marcado'])=='0') echo ' style="color:#990000; text-decoration: line-through"'; ?> > <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                     <input type="hidden" value="<?PHP echo trim($conc['plaec_key']); ?> " class="hdpladet_conck" />
                                     <input type="checkBox" data-dojo-type="dijit.form.CheckBox"  <?PHP if(trim($conc['plaec_marcado'])=='1') echo 'checked'; ?>  />
                                </td> 

                            </tr>                             <?PHP 
                               }
                            ?>
                      </table>
                    
                    <?PHP 
                       if(sizeof($conceptos['aportaciones']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene APORTACIONES vinculadas en esta planilla </div> ";
                     ?>
 
              </div>
     </div>
    

   <?PHP }else{  ?>
      

        <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
               
        </div>
      <?PHP 
         }
      ?>
</div>