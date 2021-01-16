<?PHP

  $height_top = ($tiene_categorias == FALSE) ? '57px' : '89px';
?>

<div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"left" ' style="width:50%; padding: 1px 1px 1px 1px;">
    <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Conceptos remunerativos</span>">                      
              


        <div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 100%" >
         
            <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"top", splitter: true '  style="height: <?PHP echo $height_top; ?>; padding: 0px 0px 0px 0px;">
                
                <div>
                    <table class="_tablepadding2">
                        <tr> 
                             <td><span class="sp11b"> Empleado </span></td>
                             <td><span class="sp11b"> : </span></td>
                             <td> 

                                <span class="sp12"> <?PHP echo trim($trabajador_nombre); ?>   </span>
                            </td>
                         </tr> 
                    </table> 
                </div>

                 <div style="margin: 2px 0px 0px 0px;">               
                    <input class="hdpladet_empkey" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />       
                 

                    <?PHP 
                          if($tiene_categorias)
                          {  
                    ?>
                         <div>
                               <table>
                                 <tr>
                                   <td>

                                        <div class="clearfix" style="margin:0px 0px 0px 10px;">
                                            <ul class="ul_csdet"> 
                                           <?PHP 

                                             foreach($trabajador_categorias as $reg)
                                             {

                                                  echo "<li ";

                                                  if($reg['plaemp_key'] == $detalle['plaemp_key']) echo ' style="background-color: #deeeff" ';

                                                  echo "> 
                                                           <input type='hidden' class='hddetpla' value='".$reg['plaemp_key']."' /> 
                                                           <input type='hidden' class='hddetkey' value='".$reg['plaemp_key']."' /> ".$reg['tipo_nombre']."

                                                       </li>";

                                             } 
                                            
                                           ?>
                                           </ul>
                                         </div>

                                   </td>  

                                   <?PHP if( trim($detalle['plaemp_key']) != '' && trim($detalle['platica_id']) != '0' ){   ?> 
                                    <td style="padding: 0px 0px 0px 5px;">     
                                         <input class="hdpladet_indkey" type="hidden" value="<?PHP echo trim($detalle['empleado_key']); ?>" />
                                         <input class="hdpladet_empkey" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />   
                                         <span id="spdet_quitartipocs"> [QUITAR] </span>
                                   </td>
                                   <?PHP } ?>


                                   <?PHP if(sizeof($categorias) > 0  ){   ?>
                                 
                                    <td style="padding: 0px 0px 0px 5px;">     
                                      <span class="sp11b">Tipo: </span>
                                        <select id="seldet_tipotrabajador" data-dojo-type="dijit.form.Select" class="formelement-80-11">
                                            
                                             <?PHP
                                                 foreach($categorias as $reg)
                                                 {  
                                           
                                                     echo   '<option value="'.$reg['platica_id'].'" ';
                                                     if($reg['platica_id'] == $detalle['platica_id'])   echo  ' selected="selected" ';                                      
                                                     echo' > '.$reg['platica_nombre'].' </option> ';
                                            
                                                  }
                                             ?> 
                                            
                                        </select>
                                      </td>
                                      <td>  
                                          <input class="hdpladet_empkey" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />   
                                          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                <?PHP 
                                                  $this->resources->getImage('accept_s.png',array('width' => '14', 'height' => '14'));
                                               ?>
                                                <script type="dojo/method" event="onClick" args="evt">
                                                       Planillas.Ui.btn_set_tipotrabajador(this,evt);
                                                </script>
                                                <label class="sp11">
                                                      Añadir
                                                </label>
                                            </button>

                                      </td>

                                      <?PHP } ?>

                                 </tr>
                               </table>
                       </div>
                        <div style="margin: 4px 0px 0px 0px; ">
                                <?PHP if( trim($detalle['plaemp_key']) != '' && trim($detalle['platica_id']) != '0' ){   ?> 
                              <table class="_tablepadding2">
                                 <tr>
                                    
                                    <td>
                                          <input class="hdpladet_empkey" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />
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
                               <?PHP } ?>
                        </div>

                    <?PHP 
                          }
                          else
                          {
                    ?>
               

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
                    <input type="hidden" value="1" id="hddetalleplanillatipo" />

             </div>
            
             
         <?PHP 

               if( $tiene_categorias == FALSE || ( $tiene_categorias && $detalle['platica_id'] != '0'  ) )
               {  
        ?>

           
               <div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"left" ' style="width:50%; padding: 1px 1px 1px 1px;">
                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables </span>">                      
                     
                    
                      <table id="tbpladet_varstra" class="_tablepadding2_c" width="100%">
                          
                          <tr class="tr_header_celeste">
                              <td width="20" align="center"> # </td>
                              <td width="170"> Variable  </td>
                              <td width="10">   </td>
                              <td width="60"> Valor </td>
                              <td width="20"> </td> 
                              <td width="30"> </td> 
                          </tr>
                         
                           <?PHP    
                         
                               $c = 1;
                          
                               foreach($variables['variables'] as $vari)
                               {
                           ?>
                                    <tr class="row_form"> 
                                        <td align="center" >  
                                            <input class="hdplaev_key" type="hidden" value="<?php echo $vari['plaev_key']; ?>"/>   
                                            <span class="sp11"> <?PHP echo $c; $c++; ?> </span> 
                                        </td>                         
                                        <td> <span class="sp11"> <?PHP echo trim($vari['variable']); ?> </span> </td> 
                                        <td align="center"> <span class="sp11">:</span></td> 
                                        <td align="center"> 
                                            
                                        <?PHP 
                                              if( trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_PLANILLA || trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_AMBOS )
                                              {   
                                        ?>

                                                 <input type="hidden" class="hdpladet_varvd" value="<?PHP echo trim($vari['plaev_valor']); ?>"  />
                                                  
                                                 <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="" value="<?PHP echo trim($vari['plaev_valor']); ?>"
                                                        class="txtpladet_vari"  class="formelement-50-11" />
                                        
                                         <?PHP 
                                               }
                                               else
                                               {
                                         ?>
                                              
                                                  <span class="sp11b"> <?PHP echo number_format(trim($vari['plaev_valor']),2); ?> </span>
                                         
                                         <?PHP 
                                               }  
                                         ?>

                                        </td>
                                        
                                        <td align="center"> <span class="sp11"><?PHP echo trim($vari['unidad_abrev']); ?></span></td> 

                                        <td align="center">
                                                

                                         <?PHP 
                                              if( trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_PLANILLA || trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_AMBOS )
                                              {  
                                         ?>
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
                                         
                                    </tr> 
                            <?PHP 
                           
                               }

                            ?>
                      </table> 

                      <?PHP 
                          if(sizeof($variables['variables']) == 0 ) echo " <div class='dv_new_resumen'> No hay variables relacionadas </div> ";
                      ?>
                </div>
                 
                 <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Datos  </span>">
                     
                     
                       <table class="_tablepadding2_c" width="100%">

                          <tr class="tr_header_celeste">
                              <td width="20" align="center"> # </td>
                              <td width="170"> Variable  </td>
                              <td width="50"> Grupo   </td>
                              <td width="10">   </td>
                              <td width="60"> Valor </td>
                              <td width="30"> </td> 
                          
                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($variables['datos'] as $vari)
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

                                               <span class="sp11b"> <?PHP echo number_format(trim($vari['plaev_valor']),2); ?> </span>
         
                                        </td>
                                        <td align="center">
                                              
                                              <span class="sp11"><?PHP echo trim($vari['unidad_abrev']); ?></span>
                                        </td>
                                           
                                    </tr> 
                            <?PHP 
                               }
                            ?>
                      </table>
                      
                       <?PHP 
                          if(sizeof($variables['datos']) == 0 ) echo " <div class='dv_new_resumen'> No hay DATOS relacionados </div> ";
                        ?>
         
                 
                 </div>
             </div>
            
            
             <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
                       
                      <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Ingresos</span>">
                                  
                            
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

         
                                        if($conc['conc_afecto'] == '0'){

                                            $conc['concepto'] =   '[NO AF.] '.$conc['concepto'];
                                        }
         

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

                                          if($conc['conc_afecto'] == '0'){

                                            $conc['concepto'] =   '[NO AF.] '.$conc['concepto'];
                                         }
                                   ?>
                                       <tr class="row_form"> 
                                        <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                        <td> <span class="sppladet_nombreconc"   <?PHP if(trim($conc['plaec_marcado'])=='0') echo ' style="color:#990000; text-decoration: line-through"'; ?> >

                                        <?PHP
                                          if($conc['indiv_id_b'] != '0'){ 

                                                       $this->resources->getImage('user_search.png',array('width' => '18', 'height' => '18', 'id'=>'bend'.$conc['indiv_id_b'],  'class'=>'cursor_pointer' ));
                                                 

                                                  ?>

                                                       <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId:'<?PHP echo "bend".$conc['indiv_id_b'];?>',position:['above']">
                                                                                                  
                                                                 <div class="dv_subtitlered">
                                                                       Beneficiario Judicial 
                                                                 </div>

                                                                       <table class="_tablepadding2">

                                                                             <tr>
                                                                                 <td> <span class="sp11b"> Beneficiario </span> </td>
                                                                                 <td> <span class="sp11b"> : </span> </td>
                                                                                 <td> <span class="sp11">   <?PHP echo trim($conc['beneficiario']); ?> </span> </td>
                                                                             </tr>
                                                                                                   
                                                                       </table>

                                                                </div>


                                              <?PHP     
                                                  } // FIN DE BENEFICIARIO JUDICIAL INFO
                                              ?>  




                                         <?PHP echo trim($conc['concepto']); ?> </span>

                                         </td> 
                                         <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                        <td align="center"> <span class="sp11">:</span></td> 
                                        <td align="center"> 
                                             <input type="hidden" value="<?PHP echo trim($conc['plaec_key']); ?> " class="hdpladet_conck" />
                                            
                                             <?PHP 
                                               if($conc['conc_afecto'] == '1'){ 
                                            ?>
                                             <input type="checkBox" data-dojo-type="dijit.form.CheckBox"  <?PHP if(trim($conc['plaec_marcado'])=='1') echo 'checked'; ?>  />
                                           <?PHP 
                                              }else{

                                                 echo 'Si';
                                              }
                                           ?>

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

                                          if($conc['conc_afecto'] == '0'){

                                            $conc['concepto'] =   '[NO AF.] '.$conc['concepto'];
                                          }
                                   ?>
                                     <tr class="row_form"> 
                                        <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                        <td> <span class="sppladet_nombreconc"   <?PHP if(trim($conc['plaec_marcado'])=='0') echo ' style="color:#990000; text-decoration: line-through"'; ?> > <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                        <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                        <td align="center"> <span class="sp11">:</span></td> 
                                        <td align="center"> 
                                             <input type="hidden" value="<?PHP echo trim($conc['plaec_key']); ?> " class="hdpladet_conck" />
                                            
                                           <?PHP 
                                               if($conc['conc_afecto'] == '1'){ 
                                            ?>
                                             <input type="checkBox" data-dojo-type="dijit.form.CheckBox"  <?PHP if(trim($conc['plaec_marcado'])=='1') echo 'checked'; ?>  />
                                           <?PHP 
                                              }else{

                                                 echo 'Si';
                                              }
                                           ?>
                                        </td> 

                                    </tr>                             <?PHP 
                                       }
                                    ?>
                              </table>
                            
                            <?PHP 
                               if(sizeof($conceptos['aportaciones']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene APORTACIONES vinculadas en esta planilla </div> ";
                             ?>
         
                      </div>


                     <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>No Afectos</span>">
                                  
                            
                                <table class="_tablepadding2_c" width="100%">
                                  <tr class="tr_header_celeste">
                                      <td width="40" align="center"> # </td>
                                      <td> Concepto  </td>
                                      <td width="50"> Grupo   </td>
                                     <td width="10">   </td>
                                      <td width="60"> Calcular </td>

                                  </tr>
                                   <?PHP    
                                        $c = 1;
                                       foreach($conceptos['noafectos'] as $conc)
                                       {
          
                                   ?>
                                    <tr class="row_form"> 
                                        <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                        <td> <span class="sppladet_nombreconc"   <?PHP if(trim($conc['plaec_marcado'])=='0') echo ' style="color:#990000; text-decoration: line-through"'; ?> > <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                        <td align="center"> <span class="sp11"><?PHP echo trim($conc['grupo_nombre']); ?></span></td> 
                                        <td align="center"> <span class="sp11">:</span></td> 
                                        <td align="center"> 
                                              <span class="sp11"> Si </span>
                                        </td> 

                                    </tr> 
                                    <?PHP 
                                       }
                                    ?>
                              </table> 
                          
                           <?PHP 
                               if(sizeof($conceptos['noafectos']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene Conceptos NO AFECTOS vinculados en esta planilla </div> ";
                             ?>
                      </div>
             </div>
            

             <?PHP 
                 }
                 else
                 {  
              ?>
              

                <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
                       
                </div>
              
              <?PHP 
                 }
              ?>
        </div>


    </div>


    <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Otros datos </span>">  
          <div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 100%" >
                 
                    <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"top", splitter: true '  style="height: 30px; padding: 0px 0px 0px 0px;">
                        
                        <div class="dv_busqueda_personalizada_pa2" style="margin: 0px 0px 0px 0px;">
                            <table class="_tablepadding2">
                                <tr> 
                                   
                                     <td width="30"><span class="sp11b"> Empleado </span></td>
                                     <td width="10"><span class="sp11b"> : </span></td>
                                     <td>

                                          <span class="sp12"> <?PHP echo trim($trabajador_nombre); ?>   </span>

                                          

                                     </td>
                                     
                                </tr> 
                            </table> 
                 
                        </div>
 
                     </div>


                     <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"center", splitter: true '  style=" padding: 5px 5px 5px 5px;">
              
                            <table class="_tablepadding4">
                                 <tr class="row_form">
                                       <td width="150"> 
                                          <span class="sp12b"> Ocupación </span> 
                                      </td>
                                      <td width="10"> 
                                           <span class="sp12b"> : </span> 
                                      </td> 
                                      <td width="350">
                                          
                                          <?PHP 
                                              if($detalle['config_ocupacion'] == '1')
                                              {

                                          ?>
                                          <div> 
                                              <input class="key" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />      
                                              <input type="hidden" id="hdplanilladetalle_ocupacion_id" value="<?PHP echo trim($detalle['ocu_id']); ?>" />
                                              
                                              <select id="selplanilladetalle_ocupacion"  
                                                      name="ocupacion"  
                                                      dojoType="dijit.form.FilteringSelect" 
                                                      data-dojo-props='name:"ocupacion", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Ocupacion no registrada" ' class="formelement-200-12" > 
                                                  
                                                  <option value="0" selected="true"></option>  
                                                  <?PHP 
                                                    foreach($ocupaciones as $ocu)
                                                    {
                                                       
                                                         echo '<option value="'.trim($ocu['ocu_id']).'" > '.trim($ocu['ocu_nombre']).' </option>';
                                                    }
                                                  ?>
                                              </select>  

                                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                      <?PHP 
                                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                                      ?>
                                                       <script type="dojo/method" event="onClick" args="evt">
                                                             var p = this.domNode.parentNode;
                                                             var data = { 
                                                                         'detalle' : dojo.query('.key', p)[0].value, 
                                                                         'ocupacion'  : dijit.byId('selplanilladetalle_ocupacion').get('value'),
                                                                         'ocupacion_label' : dijit.byId('selplanilladetalle_ocupacion').get('displayedValue'),
                                                                         'modo'   : '1'

                                                                        }

                                                             Planillas._M.actualizar_ocupacion_boleta.process(data);
                                                        </script> 
                                               </button>

                                          </div>

                                          <?PHP 
                                            }
                                            else if($detalle['config_ocupacion'] == '2')
                                            {

                                          ?>

                                          <div>
                                             <input class="key" type="hidden" value="<?PHP echo trim($detalle['plaemp_key']); ?>" />      
                                             <input id="txtplanilladetalle_labelboleta"
                                                    type="text" 
                                                    data-dojo-type="dijit.form.TextBox" 
                                                    data-dojo-props="name:'ocupacion_txt'" 
                                                    class="formelement-200-12" 
                                                    value="<?PHP echo trim($detalle['plaemp_ocupacion_label']); ?>" />


                                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                      <?PHP 
                                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                                      ?>
                                                    
                                                          <script type="dojo/method" event="onClick" args="evt">
                                                               
                                                                var p = this.domNode.parentNode;
                                                                var data = { 
                                                                            'detalle' : dojo.query('.key', p)[0].value, 
                                                                            'ocupacion_label'  : dijit.byId('txtplanilladetalle_labelboleta').get('value'),
                                                                            'modo'   : '2'

                                                                           }

                                                                Planillas._M.actualizar_ocupacion_boleta.process(data);
                                                        </script> 
                                               </button>

                                          </div>

                                          <?PHP 

                                            }
                                            else
                                            {
                                          ?>

                                              <div> 
                                                  <?PHP echo (trim($detalle['ocupacion_nombre']) != '' ? $detalle['ocupacion_nombre'] : '--------' );  ?>
                                              </div>

                                          <?PHP 
                                            }
                                          ?>
                                      </td>   

                                      <td> </td>
                                </tr>
                             <tr class="row_form">
                                 <td> <span class="sp12b"> Cuenta Bancaria </span> </td>  
                                 <td> <span class="sp12b">  : </span> </td> 
                                 <td> 

                                     <?PHP 
                                         if($detalle['pecd_id'] != '' && $detalle['pecd_id'] != '0')
                                         {  

                                              echo  ' <span class="sp12"> '.$detalle['pecd_cuentabancaria'].' ('.$detalle['ebanco_nombre'].') </span> ';

                                              if($detalle['cuenta_actualizada'] == '0')
                                              {
                                               ?>


                                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                    <?PHP 
                                                      $this->resources->getImage('accept.png',array('width' => '14', 'height' => '14'));
                                                   ?>
                                                    <script type="dojo/method" event="onClick" args="evt">
                                                         
                                                    </script>
                                                    <label class="sp11">
                                                           Actualizar
                                                    </label>
                                                </button>


                                               <?PHP
                                              }

                                         }
                                         else
                                         {
                                             echo ' Sin cuenta Bancaria ';
                                         }


                                     ?>
       

                                 </td>

                                 <td> </td>
                              </tr>
                               <tr class="row_form">
                                   <td> <span class="sp12b"> Sistema de Pensiones </span> </td>  
                                   <td> <span class="sp12b">  :  </span> </td> 

                                   <td> 

                                        <?PHP

                                            echo trim($detalle['afp_nombre']) == '' ? 'ONP' : trim($detalle['afp_nombre']) ; 
                                        ?>

                                   </td>

                                   <td> </td>
                                </tr>
                                  
                                <?PHP 
                                    if(MODULO_CUARTA_CATEGORIA &&  $plati_cuarta == '1'){ 
                                ?>
                                <tr class="row_form">
                                    <td> <span class="sp12b"> Suspensión de cuarta </span> </td>  
                                    <td> <span class="sp12b">  :  </span> </td> 

                                    <td> 
                                        <span class="sp12">
                                         <?PHP

                                            
                                            echo trim($detalle['indiv_suspension_cuarta']) == '1' ? 'Si' : 'No presento' ; 
                                            
                                            if(trim($detalle['indiv_suspension_cuarta']) == '1'){
                                               echo ', fecha del documento: '._get_date_pg($detalle['indiv_suspension_fecha']); 
                                            }
                                           
                                         ?>
                                        </span>

                                    </td>

                                    <td> </td>
                                 </tr>
                                 <?PHP 
                                  }
                                 ?>

    
                            </table>

                     </div>
                    
                   
                </div> <!-- FIN BORDER-->
                



          </div>

      </div>
