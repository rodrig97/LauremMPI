
<div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 100%" >
 
    <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"top", splitter: true '  style="height: 55px; padding: 0px 0px 0px 0px;">
        
          <div class="dv_busqueda_personalizada_pa2" style="margin: 0px 0px 0px 0px;">
        <div>
            <table class="_tablepadding2">
                <tr> 
                     <td><span class="sp11b"> Empleado </span></td>
                     <td><span class="sp11b"> : </span></td>
                     <td width="300"><span class="sp12"> <?PHP echo trim($trabajador_info['indiv_appaterno']).' '.trim($trabajador_info['indiv_apmaterno']).' '.trim($trabajador_info['indiv_nombres']).' ('.$estado_trabajo.') '; ?>   </span></td>
                     <td> 
                         <input class="hdptrabajador_empkey" type="hidden" value="<?PHP echo trim($pers_key); ?>" />       
           
                        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                              $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                           ?>
                            <script type="dojo/method" event="onClick" args="evt">
                                 Trabajadores.Ui.btn_showaddconcepto_click(this,evt);
                                
                            </script>
                            <label class="sp11">
                                  Agregar Concepto
                            </label>
                        </button>
                     </td> 
                </tr> 
            </table> 
        </div>
      
        
        </div>
    </div>
    <!-- 
      <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
               
              <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Ingresos</span>">  -->
     <div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"left" ' style="width:50%; padding: 1px 1px 1px 1px;">
         
          <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables  </span>">
             
             
               <table class="_tablepadding2_c" width="100%">
                  <tr class="tr_header_celeste">
                      <td width="20" align="center"> # </td>
                      <td width="20"> </td>
                      <td width="20"> </td>
                      <td width="170"> 

                            Variable  
                      </td>
                      <td width="100"> Grupo  </td>
                      <td width="10">   </td>
                      <td width="60"> Valor </td>
                      <td width="20"> </td> 
                  <!--    <td width="20"> </td> -->
                  </tr>
                   <?PHP    
                        $c = 1;
                       foreach($variables as $vari)
                       {
                   ?>
                          <tr class="row_form"> 
                                <td align="center" >  
                                    <?PHP 
                                       // var_dump($vari);
                                    ?>
                                    <span class="sp11"> <?PHP echo $c; $c++; ?> </span> 
                                </td>    
                                <td> 
                                    
                                </td>    
                                <td> 
                                     
                                </td>                     
                                <td> 

                                    <span class="spVariable_personalizar" <?PHP if($vari['empvar_id'] != '') echo ' style="text-decoration:underline;"' ?>  > <?PHP echo trim($vari['vari_nombre']); ?> </span> 

                                </td> 
                                <td align="center"> <span class="sp11"> <?PHP echo (trim($vari['grupo']) != '') ?  trim($vari['grupo']) : '--------'; ?> </span> </td> 
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 
                                     

                                   <?PHP if(trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_GESTIONDATOS || trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_AMBOS ){   ?>

                                       <input type="hidden" class="hdpladet_varvd" value="<?PHP echo trim($vari['valor']) ; ?>"  />
                                       
                                       
                                       <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props=""  value="<?PHP echo  trim($vari['valor']) ; ?>"
                                              class="txtpladet_vari"  class="formelement-50-11" style="text-align: center;"   />
                                
                                     <?PHP }else{
                                        ?>
                                              <span class="sp11b"> <?PHP echo number_format(trim($vari['valor']),2); ?> </span>
                                        <?PHP 
                                     } ?>          


                                </td>
                                <td align="center">
                                     <input class="hdvari_key" type="hidden" value="<?php echo $vari['vari_key']; ?>"/>  
                                     <input class="hdtra_key" type="hidden" value="<?php echo $pers_key; ?>"/>   
                                        
                                 <?PHP if(trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_GESTIONDATOS  || trim($vari['vari_personalizable']) == VARIABLE_PERSONALIZABLE_AMBOS ){   ?>
                                     <button  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="disabled:true" >
                                       <?PHP 
                                          $this->resources->getImage('save.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                              Trabajadores.Ui.btn_save_valorvariable(this,evt);
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
                  if(sizeof($variables) == 0 ) echo " <div class='dv_new_resumen'> No hay variables vinculadas </div> ";
                ?>
 
         
         </div>

         <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Datos Presupuestales </span>">                      
              
               
               <table class="_tablepadding2_c" width="100%">
                  <tr class="row_form"> 
                       <td width="100"> <span class="sp11b"> Año de Ejecución </span>  </td>
                       <td width="10"> : </td>
                       <td> 
                           

                           <select id="selgd_anio"  data-dojo-type="dijit.form.Select" 
                                   data-dojo-props='name:"anio" ' class="formelement-80-11" style="width:80px;">
                               
                               <option value="<?PHP echo $anio_ejecucion; ?>"> <?PHP echo $anio_ejecucion; ?> </option>
                                
                          </select>

                       </td>
                  </tr> 
                   <tr class="row_form"> 
                        <td width="100"> <span class="sp11b"> Tarea Presupuestal </span>  </td>
                        <td width="10"> : </td>
                        <td> 
                            

                            <select id="selgd_tarea"  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", readOnly:true, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' class="formelement-180-11">
                                <option value="0"> No Especificar </option>
                                   <?PHP
                                   foreach($tareas as $tarea){
                                        echo "<option value='".trim($tarea['cod_tarea'])."' ";

                                        if($afectacion['tarea_id'] == trim($tarea['cod_tarea']) ){
                                           echo " selected='true' ";
                                        }  

                                        echo ">(".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
                                   }
                                 ?>
                           </select>

                        </td>
                   </tr> 
                    <tr class="row_form"> 
                        <td> <span class="sp11b"> Fuente Financiamiento </span>  </td>
                        <td> : </td>
                        <td> 
                            <select id="selgd_selfuente"   data-dojo-type="dijit.form.Select" data-dojo-props='name:"fuente_financiamiento", readOnly:true, disabled:false' class="formelement-180-11" style="width:200px;">
                                  
                                <?PHP 
                                    if($afectacion['empre_id'] == '')
                                    {

                                ?>
                                      <option value="0"> No Especificar  </option>

                             <?PHP 
                                    }
                                    else
                                    {

                                       echo "  <option value='".$afectacion['fuente_id'].'-'.$afectacion['tipo_recurso']."'>".$afectacion['fuente']."</option> "; 

                                    } 
                              ?>
 
                            </select>
                        </td>
                   </tr> 
                   <tr> 

                         <td colspan="3" align="center"> 
                             
                             <input class="hdtra_key" type="hidden" value="<?php echo $pers_key; ?>"/>  

                             <button  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="" >
                                    
                                        <?PHP 
                                          $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                            
                                              dijit.byId('selgd_tarea').set('readOnly', false);
                                              dijit.byId('selgd_selfuente').set('readOnly', false);
                                              dijit.byId('btngdt_saveafectacion').set('disabled',false);


                                        </script>
                                        <label class="sp11">
                                              Modificar
                                        </label>
                             </button>
                             
                             <button id="btngdt_saveafectacion"  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="disabled: true" >
                                    
                                        <?PHP 
                                          $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                              Trabajadores.Ui.btn_actualizar_presupuestal_click(this,evt);
                                        </script>
                                        <label class="sp11">
                                            Guardar Cambios
                                        </label>
                             </button> 

<!-- 
                             <button id="btngdt_verpresupuesto"  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="disabled: false" >
                                    
                                        <?PHP 
                                          $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                              Trabajadores.Ui.btn_ver_presupuesto_click(this,evt);
                                        </script>
                                        <label class="sp11">
                                             Ver Presupuesto
                                        </label>
                             </button> -->
                              <!-- 
                               <button  dojoType="dijit.form.Button" class="btnpladet_savevar" data-dojo-props="disabled: true" >
                                    
                                        <?PHP 
                                          $this->resources->getImage('cancel.png',array('width' => '14', 'height' => '14'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                            
                                        </script>
                                        <label class="sp11">
                                              Cancelar
                                        </label>
                             </button> -->

                         </td>
                   </tr>
              </table> 

         </div>
         
        
     </div>
    
    
     <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="width:50%; padding: 0px 0px 0px 0px;">
               
              <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Conceptos Ingresos</span>">
                          
                    
                        <table class="_tablepadding2_c" width="100%">
                          <tr class="tr_header_celeste">
                              <td width="40" align="center"> # </td>
                              <td width="170"> Concepto remunerativo </td>
                              <td width="100"> Grupo </td>
                              <td width="10">   </td>
                              <td width="60"> Quitar </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($conceptos['ingresos'] as $conc){
                           ?>
                            <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td>  <span class="sp11"> <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                <td align="center"> <span class="sp11"> <?PHP echo (trim($conc['grupo']) != '') ? trim($conc['grupo']) : '--------'; ?> </span> </td>
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 

                                    <input type="hidden" value="<?PHP echo trim($conc['empcon_key']); ?> " class="hdgdt_conckey" />
                        
                                    <?PHP 

                                       if($conc['conc_obligatorio'] == '0' )
                                       { 
                                    ?>

                                       <button  dojoType="dijit.form.Button" class="btngdt_quitarconc" data-dojo-props="disabled:false" >
                                         <?PHP 
                                            $this->resources->getImage('remove.png',array('width' => '10', 'height' => '10'));
                                         ?>
                                          <script type="dojo/method" event="onClick" args="evt">
                                                
                                                if(confirm('Realmente desea desvincular el concepto del trabajador ? '))
                                                {

                                                   Trabajadores.Ui.btn_desvincularconcepto_click(this,evt);
      
                                                }

                                          </script>
                                          <label class="sp11">

                                          </label>
                                      </button>

                                    <?PHP 
                                      } 
                                      else
                                      {
                                         echo ' --- ';
                                      }
                                    ?>

                                </td> 

                            </tr> 
                            <?PHP 
                               }
                            ?>
                      </table> 
                  
                   <?PHP 
                       if(sizeof($conceptos['ingresos']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene  INGRESOS vinculados  </div> ";
                     ?>
              </div>
               <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Descuentos</span>">
                  

                      <table class="_tablepadding2_c" width="100%">
                          <tr class="tr_header_celeste">
                              <td width="40" align="center"> # </td>
                               <td width="170"> Concepto remunerativo </td>
                              <td width="100"> Grupo </td>
                              <td width="10">   </td>
                              <td width="60"> Quitar </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($conceptos['descuentos'] as $conc){
                           ?>
                               <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td>  
                                      <input type="hidden" class="hdempconkey"  value="<?PHP echo $conc['empcon_key']; ?>" />
                                     
                                    <?PHP 

                                          if($conc['gvc_id'] == GRUPOVC_RETENCIONJUDICIAL && $conc['ecb_id'] == '' ){

                                               
                                                 $this->resources->getImage('user_add.png',array('width' => '18', 'height' => '18', 'class'=>'td_conceptoempleado_retencion'));
                                               
                                          }  
                                     
                                          if($conc['ecb_id'] != ''){ 

                                               $this->resources->getImage('user_search.png',array('width' => '18', 'height' => '18', 'id'=>'ben'.$conc['ecb_id'],  'class'=>'cursor_pointer' ));
                                         

                                          ?>

                                               <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId:'<?PHP echo "ben".$conc['ecb_id'];?>',position:['above']">
                                                                                          
                                                         <div class="dv_subtitlered">
                                                               Beneficiario Judicial 
                                                         </div>

                                                             <table class="_tablepadding2">

                                                                 <tr>
                                                                    <td> <span class="sp11b"> Beneficiario </span> </td>
                                                                    <td> <span class="sp11b"> : </span> </td>
                                                                    <td> <span class="sp11">   <?PHP echo trim($conc['beneficiario']); ?> </span> </td>
                                                                 </tr>

                                                                  <tr>
                                                                    <td> <span class="sp11b"> Observacion </span> </td>
                                                                    <td> <span class="sp11b"> : </span> </td>
                                                                    <td> <span class="sp11">   <?PHP echo (trim($conc['ecb_descripcion']) != '')  ? trim($conc['ecb_descripcion']) : '-------'; ?> </span> </td>
                                                                 </tr>

                                                                      <tr>
                                                                    <td> <span class="sp11b"> Registrado el </span> </td>
                                                                    <td> <span class="sp11b"> : </span> </td>
                                                                    <td> <span class="sp11">   <?PHP echo trim($conc['ecb_fecreg']); ?> </span> </td>
                                                                 </tr>

                                                            </table>

                                                        </div>


                                      <?PHP     
                                          } // FIN DE BENEFICIARIO JUDICIAL INFO
                                      ?>  

                                       <span class="sp11"> <?PHP echo trim($conc['concepto']); ?> </span> 

                                    

                                </td> 
                                <td align="center"> <span class="sp11"> <?PHP echo (trim($conc['grupo']) != '') ? trim($conc['grupo']) : '--------'; ?> </span> </td>
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 

                                    <?PHP 

                                       if($conc['conc_obligatorio'] == '0' )
                                       { 
                                    ?>


                                     <input type="hidden" value="<?PHP echo trim($conc['empcon_key']); ?> " class="hdgdt_conckey" />
                      
                                     <button  dojoType="dijit.form.Button" class="btngdt_quitarconc" data-dojo-props="disabled:false" >
                                       <?PHP 
                                          $this->resources->getImage('remove.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">

                                              if(confirm('Realmente desea desvincular el concepto del trabajador ? '))
                                              {
                                               
                                                  Trabajadores.Ui.btn_desvincularconcepto_click(this,evt);
                                              }

                                        </script>
                                        <label class="sp11">
                                                    
                                        </label>
                                    </button>


                                   
                                    <?PHP 
                                      } 
                                      else
                                      {
                                         echo ' --- ';
                                      }
                                    ?>
                                </td> 

                            </tr> 
                            <?PHP 
                               }
                            ?>
                      </table> 
                   
                    <?PHP 
                       if(sizeof($conceptos['descuentos']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene DESCUENTOS vinculados </div> ";
                     ?>

              </div>
                <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Aportaciones</span>">
                            

                   <table class="_tablepadding2_c" width="100%">
                          <tr class="tr_header_celeste">
                              <td width="40" align="center"> # </td>
                              <td  width="170"> Concepto remunerativo </td>
                              <td width="100"> Grupo </td>
                              <td width="10">   </td>
                              <td width="60"> Quitar </td>

                          </tr>
                           <?PHP    
                                $c = 1;
                               foreach($conceptos['aportaciones'] as $conc){
                           ?>
                                <tr class="row_form"> 
                                <td align="center">  <span class="sp11"> <?PHP echo $c; $c++; ?> </span> </td>                         
                                <td>  <span class="sp11"> <?PHP echo trim($conc['concepto']); ?> </span> </td> 
                                <td align="center"> <span class="sp11"> <?PHP echo (trim($conc['grupo']) != '') ? trim($conc['grupo']) : '--------'; ?> </span> </td>
                                <td align="center"> <span class="sp11">:</span></td> 
                                <td align="center"> 

                                  <?PHP 

                                     if($conc['conc_obligatorio'] == '0' )
                                     { 
                                  ?>

                                     <input type="hidden" value="<?PHP echo trim($conc['empcon_key']); ?> " class="hdgdt_conckey" />
                      
                                     <button  dojoType="dijit.form.Button" class="btngdt_quitarconc" data-dojo-props="disabled:false" >
                                       <?PHP 
                                          $this->resources->getImage('remove.png',array('width' => '10', 'height' => '10'));
                                       ?>
                                        <script type="dojo/method" event="onClick" args="evt">

                                            if(confirm('Realmente desea desvincular el concepto del trabajador ? '))
                                            {
                                                  
                                                  Trabajadores.Ui.btn_desvincularconcepto_click(this,evt);
                                            }
                                        </script>
                                        <label class="sp11">

                                        </label>
                                    </button>

                                    
                                    <?PHP 
                                      } 
                                      else
                                      {
                                         echo ' --- ';
                                      }
                                    ?>


                                </td> 

                            </tr> 


                      <?PHP 
                               }
                            ?>
                      </table>
                    
                    <?PHP 
                       if(sizeof($conceptos['aportaciones']) == 0 ) echo " <div class='dv_new_resumen'> El trabajador no tiene APORTACIONES vinculadas </div> ";
                     ?>
 
              </div>
     </div>
    
    <!--
      <div  dojoType="dijit.layout.ContentPane"   data-dojo-props='region:"bottom", splitter: true '  style="height:25px; padding: 0px 0px 0px 0px;">
          
            Resumen del detalle
          
      </div> -->
</div>