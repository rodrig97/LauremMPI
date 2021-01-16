
<div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 100%" >
 
    <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"top", splitter: true '  style="height: 40px; padding: 0px 0px 0px 0px;">
        
        <div class="dv_busqueda_personalizada_pa2" style="margin: 0px 0px 0px 0px;">
            <table class="_tablepadding2">
                <tr> 
                   
                     <td width="30"><span class="sp11b"> Empleado </span></td>
                     <td width="10"><span class="sp11b"> : </span></td>
                     <td width="340"><span class="sp12"> <?PHP echo trim($trabajador_nombre); ?>   </span></td>
                      <td> 
                           <input type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" id="plaempdetpro_id" /> 
             
                        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                              $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                           ?>
                            <script type="dojo/method" event="onClick" args="evt">
                                 Impresiones.Ui.btn_previewboleta_click(this,evt);
                            </script>
                            <label class="sp11">
                                  Imprimir Boleta de Pago
                            </label>
                        </button>
                    </td>
                  
                </tr> 
            </table> 
        </div>
         <div style="margin: 0px 0px 0px 0px;">               
            
         
           <!-- 
            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                <?PHP 
                  $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
               ?>
                <script type="dojo/method" event="onClick" args="evt">
                </script>
                <label class="sp11">
                      Agregar Concepto
                </label>
            </button> -->
         </div>
        
     </div>
    
    <!-- 
      <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
               
              <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Ingresos</span>">  -->
     <div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"left" ' style="width:50%; padding: 1px 1px 1px 1px;">
           <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables del Trabajador</span>">                      
              <!-- <div class="dv_red_subtitle"> 
                   Variables de Calculo
               </div>
              -->
                <input type="hidden" value="2" id="hddetalleplanillatipo" />
              <table class="_tablepadding2_c" width="100%">
                  <tr class="tr_header_celeste">
                      <td width="40" align="center"> # </td>
                       <td align="center"> Variable  </td>
                      <td width="50"> Grupo   </td>
                       <td width="10">   </td>
                      <td width="60"> Valor </td>
                      
                  </tr>
                   <?PHP    
                        $c = 1;
                     //   var_dump($variables);
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
                                       <span class="sp11"> 
                                     <?PHP echo sprintf('%.2f',trim($vari['plaev_valor'])); ?> </span>
                                        
                                </td>
                               

                            </tr> 
                    <?PHP 
                       }
                    ?>
              </table> 
                    <?PHP 
                  if(sizeof($variables['trabajador']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene variables de PROCESO en esta planilla </div> ";
                ?>
        </div>
         
         <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables del sistema </span>">
             
             
               <table class="_tablepadding2_c" width="100%">
                  <tr class="tr_header_celeste">
                      <td width="40" align="center"> # </td>
                      <td align="center"> Variable  </td>
                      <td width="50"> Grupo   </td>
                         <td width="10">   </td>
                      <td align="center" width="60"> Valor </td>
                
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
                                        <span class="sp11"> 
                                     <?PHP echo sprintf('%.2f',trim($vari['plaev_valor'])); ?> </span>
                                </td>
                                 
                            </tr> 
                    <?PHP 
                       }
                    ?>
              </table>
              
               <?PHP 
                  if(sizeof($variables['sistema']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene variables de PROCESO en esta planilla </div> ";
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
                              <td width="60"> Monto S./ </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                                $total_ingresos = 0;
                               foreach($conceptos['ingresos'] as $conc){
                                  $total_ingresos+= $conc['plaec_value'];
                                   ?>
                            <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td> <span class="sp11"> <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                      <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                    <span class="sp11"> 
                                     <?PHP echo sprintf('%.2f',trim($conc['plaec_value'])); ?> </span>
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
                              <td width="60"> Monto S./ </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                                $total_descuentos = 0;
                               foreach($conceptos['descuentos'] as $conc){
                                      $total_descuentos+= $conc['plaec_value'];
                           ?>
                            <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td> <span class="sp11"> <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center">    <span class="sp11"> 
                                     <?PHP echo sprintf('%.2f',trim($conc['plaec_value'])); ?> </span>
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
                              <td width="60"> Monto S./ </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                                $total_aportaciones = 0;
                               foreach($conceptos['aportaciones'] as $conc){
                                    $total_aportaciones+= $conc['plaec_value'];
                           ?>
                            <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td> <span class="sp11"> <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                              <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                       <span class="sp11"> 
                                      <?PHP echo sprintf('%.2f',trim($conc['plaec_value'])); ?> </span>
                                </td> 

                            </tr> 
                            <?PHP 
                               }
                            ?>
                      </table>
                    
                    <?PHP 
                       if(sizeof($conceptos['aportaciones']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene APORTACIONES vinculadas en esta planilla </div> ";
                     ?>
 
              </div>
         
     </div>
    
            <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"bottom", splitter: true '  style="height:25px; padding: 0px 0px 0px 0px;">
          
                     <table> 
                          <tr> 
                               <td width="90"> <span class="sp11b"> Total Ingresos </span> </td>
                               <td width="10"> <span class="sp11b"> : </span> </td>
                               <td width="60"> <span class="sp11"> S./ <?PHP echo sprintf('%.2f',trim($total_ingresos)); ?> </span> </td>
                               
                               <td width="105"> <span class="sp11b"> Total Descuentos </span> </td>
                               <td width="10"> <span class="sp11b"> : </span> </td>
                               <td width="60"> <span class="sp11"> S./ <?PHP echo sprintf('%.2f',trim($total_descuentos)); ?> </span> </td>
                              
                               <td width="90">  <span class="sp11b"> Total a pagar </span> </td>
                               <td width="10"> <span class="sp11b"> : </span> </td>
                               <td width="60"> <span class="sp11b">S./ <?PHP   $total_pagar = $total_ingresos - $total_descuentos;
                                                                 echo sprintf('%.2f',trim($total_pagar)); ?> </span> </td>
                          
                          
                               <td width="105"> <span class="sp11b"> Total Aportaciones </span> </td>
                               <td width="10">  <span class="sp11b"> : </span> </td>
                               <td width="60">  <span class="sp11"> S./ <?PHP echo sprintf('%.2f',trim($total_aportaciones)); ?>  </span> </td>
                          
                              
                          </tr> 
                      
                          
                     </table> 
              </div>
</div>