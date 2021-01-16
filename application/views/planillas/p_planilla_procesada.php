<div id="dvViewName" class="dv_view_name">
    Elaboracion de Planilla de Remuneraciones
</div>

<input type="hidden" id="hdviewplanilla_id" value="<?PHP echo trim($plani_info['pla_key']);?>" />
 
<input type="hidden" id="hdviewplanilla_tienecategorias" value="<?PHP echo trim($plani_info['pla_tiene_categoria']); ?>" />

<div id="viewplanilla_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="top" 
            data-dojo-props='region:"top", style:"height: 105px;"'>
          
    
         <div>
             
             <table class="_tablepadding2">
               <tr class="row_form"> 
                    
                     <td width="105"> 
                        <span class="sp12b">Régimen</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td width="150">  
                        <input id="hdidplanilla_actual" type="hidden" value="<?PHP echo trim($plani_info['pla_key']); ?>" />
                        <span class="sp12">
                        <?PHP 
                         echo  trim($plani_info['tipo']);
                        ?>
                        </span>
                    </td>
                
                    <td width="120"> 
                        <span class="sp12b">Codigo de la Planilla</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td width="180">  
                        
                        <span class="sp12">
                        <?PHP 
                            echo  trim($plani_info['pla_codigo'])."<span class='sp11b'> (Estado: ".trim($plani_info['estado']).")</span>";
                        ?>
                        </span>
                    </td>
             
                    <td width="60"> 
                        <span class="sp12b"> Mes - Año</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td  colspan="2">  
                        
                        <span class="sp12">
                        <?PHP 
                            echo $plani_info['pla_mes']."/".$plani_info['pla_anio'];
                        ?>
                      

                        <?PHP 
                           if($plani_info['pla_fecini'] != '' && $plani_info['pla_fecfin'] != '')
                           {
                               echo 'Del '._get_date_pg($plani_info['pla_fecini']).' hasta el'._get_date_pg($plani_info['pla_fecfin']);
                           }
                        ?>
                        </span>


                        <span class="sp12b">
                            Periodo:
                        </span>
                        <span class="sp12">

                        <?PHP 
                            echo trim($plani_info['pla_semana']) != '' ? trim($plani_info['pla_semana']) : '---';
                        ?>

                        </span>
     
                    </td>
 
               </tr>
               <tr class="row_form"> 
                    <td> 
                        <span class="sp12b">Afectación</span>
                    </td>
                    <td> 
                        <span class="sp12b">:</span>
                    </td>
                    <td colspan="7">  
                        
                           <?PHP 
                            

                              if( $plani_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA )
                              { 

                                  if($plani_info['tarea_id'] != '')
                                  {

                                      echo '<span class="sp11b"> Tarea: </span> ';
                                      echo '<span class="sp11"> '.(trim($plani_info['tarea_codigo']).' '.substr(trim($plani_info['tarea_nombre']),0,50).'..' ).'</span>';
                                  }

 
                                  if($plani_info['fuente_id'] != '' && $plani_info['tipo_recurso'] != '' )
                                  {
                                      echo '<span class="sp11b"> Fuente F: </span> ';
                                      echo '<span class="sp11"> '.$plani_info['fuente_id'].' - '.$plani_info['tipo_recurso'].' ('.$plani_info['fuente_abrev'].') </span>';
          
                                  } 

                                  if($plani_info['clasificador_id'] != '' )
                                  {
                                      echo '<span class="sp11b"> Clasificador </span> ';
                                      echo '<span class="sp11"> '.(substr(trim($plani_info['clasificador']),0,25).'..' ).'</span>';
                                  
                                  } 

                              }
                              else
                              {

                                  echo ' <span class="sp12"> Especificada por cada trabajador de la planilla </span>';

                              }
                            ?>
                      
                    </td> 

                    <td width="150">  

                        <span class="sp12b"> SIAF: </span>


                        <span class="sp11"> 


                          <?PHP  
                             foreach ($siafNros as $siaf)
                             {
                                 
                                 echo ' '.$siaf['siaf'];

                             }

                             if(sizeof($siafNros) == 0) echo '----';
                          ?>

                        </span>

                    </td>
               </tr>
               <tr class="row_form"> 
                    <td> 
                        <span class="sp12b">Descripcion/Obs</span>
                    </td>
                    <td> <span class="sp12b">:</span>  </td>
                    <td colspan="8">
                        <span class="sp12">
                         <?PHP 
                            echo  (trim($plani_info['pla_descripcion']) != '') ? trim($plani_info['pla_descripcion']): ' -------';
                        ?></span>
                    </td>
               </tr>
               
          </table>
         </div>
         
         
              <div class="dv_busqueda_personalizada_pa2" style="width:99%; max-width: 1200px; margin:5px 0px 0px 0px;">
                 
              <table class="_tablepadding2">
                  <tr> 
                      
                        <td> 
                              
                                 <div  data-dojo-type="dijit.form.DropDownButton" >

                                         <label class="sp12">
                                                <?PHP 
                                                  $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                               ?>

                                                  Impresiones 
                                          </label>
                                          <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Especificar fuente de importacion de datos"'>

                                                <table class="_tablepadding2">
                                                     <tr> 
                                                          <td> 
                                                               
                                                                    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                      <?PHP 
                                                                         $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                                                      ?>
                                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                           
                                                                            Impresiones.Ui.btn_masivoboletas_click(this,evt);
                                                                        </script>
                                                                        <label class="sp11">
                                                                               Imprimir Boletas de Pago
                                                                        </label>
                                                                   </button>
                                                          </td>  
                                                    </tr>
                                                    <tr>         
                                                           <td> 
                                                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                      <?PHP 
                                                                         $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                                                      ?>
                                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                           
                                                                            Impresiones.Ui.btn_resumenplanilla_click(this,evt);
                                                                        </script>
                                                                        <label class="sp11">
                                                                               Imprimir resumen de planilla
                                                                        </label>
                                                                   </button>
                                                          </td>    
                                                      </tr>
                                                      <tr>       
                                                           <td> 
                                                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                      <?PHP 
                                                                         $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                                                      ?>
                                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                           
                                                                            Impresiones.Ui.btn_resumendetallado_click(this,evt);
                                                                        </script>
                                                                        <label class="sp11">
                                                                               Imprimir Planilla de remuneraciones
                                                                        </label>
                                                                   </button>
                                                          </td>           
                                                     </tr>

                                                      <tr>       
                                                           <td> 
                                                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                      <?PHP 
                                                                         $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                                                      ?>
                                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                           
                                                                            Impresiones.Ui.btn_resumencontable_click(this,evt);
                                                                        </script>
                                                                        <label class="sp11">
                                                                               Imprimir resumen contable
                                                                        </label>
                                                                   </button>
                                                          </td>           
                                                     </tr>
                                                </table>


                                          </div>
                                    </div> 
                       </td>

                        
                       <!--
                       <td> 
                              
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                            Planillas.Ui.btn_ver_resumen(this,evt);
                                    </script>
                                    <label class="sp11">
                                           Ver Resumen de planilla
                                    </label>
                               </button>
                       </td>
                      
                       
                      <td> 
                              
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                            Planillas.Ui.btn_anularplanilla_click(this,evt);
                                        
                                    </script>
                                    <label class="sp11">
                                          Eliminar Planilla  
                                    </label>
                               </button>
                       </td> -->

                        <td>
                            
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('window_edit.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">

                                          var pla_key = dojo.byId('hdviewplanilla_id').value;
                                          Planillas._V.resumen_descansos_medicos.load({'planilla' : pla_key});
                                    </script>
                                    <label class="sp11">
                                         Descansos médicos
                                    </label>
                               </button>
                       </td>
                       

                      <?PHP 

                          if($plani_info['estado_id'] == ESTADOPLANILLA_PROCESADA  ) 
                          {      


                      ?>


                           <td> 
                                  
                                    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                      <?PHP 
                                         $this->resources->getImage('cancel.png',array('width' => '14', 'height' => '14'));
                                      ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                                Planillas.Ui.btn_cancelarproceso_click(this,evt);
                                            
                                        </script>
                                        <label class="sp11">
                                              Cancelar Proceso  
                                        </label>
                                   </button>
                           </td>
                            
                          
                            <td>
                                
                                    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                      <?PHP 
                                         $this->resources->getImage('credit_cart.png',array('width' => '14', 'height' => '14'));
                                      ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                                Planillas.Ui.btn_afectacion_presupuestal(this,evt);
                                            
                                        </script>
                                        <label class="sp11">
                                             Afectacion Presupuestal
                                        </label>
                                   </button>
                           </td>
                          
                      <?PHP 
                            }
                            else
                            {

                              ?> 

                              <td>
                                    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                      <?PHP 
                                         $this->resources->getImage('credit_cart.png',array('width' => '14', 'height' => '14'));
                                      ?>
                                        <script type="dojo/method" event="onClick" args="evt">
                                                    
                                              Planillas.Ui.btn_registrarsiaf_click(this,evt);

                                        </script>
                                        <label class="sp11">
                                              Actualizar SIAF 
                                        </label>
                                   </button>
                              </td>



                                   <td>
                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                   <?PHP 
                                      $this->resources->getImage('cancel.png',array('width' => '14', 'height' => '14'));
                                   ?>
                                     <script type="dojo/method" event="onClick" args="evt">
                                              
                                          if(confirm('Realmente desea anular el proceso, esto revertira la afectación Presupuestal ?'))
                                          { 

                                                  Planillas._V.anulacion_planilla.load({});

                                          }

                                     </script>
                                     <label class="sp11">
                                           Anular Proceso 
                                     </label>
                                </button>
                               </td>
                         <?PHP      
                            }
                       ?>

                       <?PHP 
                       if( sizeof($importaciones_asistencia) > 0 )
                       {  

                       ?> 
                       
                       <td> 

                            <button id="btnelapla_hoja" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                              <?PHP 
                                 $this->resources->getImage('attachment.png',array('width' => '14', 'height' => '14'));
                              ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                        
                                     var data = {}

                                     data.planilla = dojo.byId('hdviewplanilla_id').value;

                                     Planillas._V.asistencias_importadas.load(data);

                                </script>

                                <label class="sp11">
                                      Ver importaciones
                                </label>
                           </button>
                           <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId: 'btnelapla_hoja', position:['above']">
                                  Visualizar registro de asistencias importadas a la planilla
                           </div>
                       </td>
                       
                       <?PHP } ?>
                      
                       <?PHP 

                         if($problemas_pension == '1'){
                       ?>
                       
                         <td>
                            <span class="sp11" id="spListaSinSNP" style="color:#990000;">
                                La planilla tiene trabajadores con observaciones por Desc. ONP - AFP
                            </span> 

                           <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId: 'spListaSinSNP', position:['above']" style="width: 300px;">
                                <?PHP echo $problemas_pension_mensaje; ?>
                            </div> 
                         </td>
                      
                       <?PHP    
                         }
                       ?>
                  </tr>
                
              </table>

           
         </div>
          
      </div>
    
    <div   dojoType="dijit.layout.TabContainer" attachParent="true" tabPosition="bottom" tabStrip="true" data-dojo-props=' region:"center" ' style="padding: 0px 0px 0px 0px;">
         
        <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Detalle por trabajador </span>" style="padding: 0px 0px 0px 0px;">
            
            
            <div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 100%; height: 100%;">

                 <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", splitter:true ' style ="width: 620px; padding: 0px 0px 0px 0px;"   >


                    <input type="hidden" id="hdplanillaprocesada" value="1" />

                    <div id="dvdetalle_planilla_procesada"> 


                    </div> 


               <!--      <div style="border:1px solid #336699; height:200px; width:90%; overflow:auto">

                        Probando mensajes de validacion
                    </div> -->

  
 

                </div>

                <div id="dv_vipla_detalle"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >


                </div>
            </div>
             
            
        </div>
 <!--        <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Resumen por Conceptos</span>">
             
 
                <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width:100%; height: 100%; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
 
                     <div  dojoType="dijit.layout.ContentPane" 
                            splitter="true" 
                             region="left" 
                            data-dojo-props='region:"left", style:"width: 500px;"' style=" padding:0px 0px 0px 0px; ">


                            <div id="ac" data-dojo-type="dijit.layout.AccordionContainer" data-dojo-props='closable: true, style:"width: 400px; height: 1200px;" '>

                                    <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:" <span class=\"sp11\">Conceptos - Ingresos </span>" '  >
                          
                                        <div id="dvtablepla_resumen_ingresos"></div>

                                    </div>
                                    <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"sp11\">Conceptos - Descuentos</span>"' >
                                          <div id="dvtablepla_resumen_descuentos"></div>  
                                    </div>
                                    <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"sp11\">Conceptos - Aportaciones</span>"'   >
                                          <div id="dvtablepla_resumen_aportaciones"></div>   
                                    </div>

                             </div>


                     </div>

                     <div  dojoType="dijit.layout.ContentPane" 
                            splitter="true" 
                             region="center" 
                            data-dojo-props='region:"center" ' style=" padding:0px 0px 0px 0px; ">

                            
                         <div id="dvtable_recon_trabajadores"> 
                             
                         </div>
                         
                     </div>

                </div>
  
            
        </div> -->
        
        <!--
         <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Resumen por Clasificador de gasto</span>">
             
 
                <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width:100%; height: 100%; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
 
                     <div  dojoType="dijit.layout.ContentPane" 
                            splitter="true" 
                             region="left" 
                            data-dojo-props='region:"left", style:"width: 500px;"' style=" padding:0px 0px 0px 0px; ">

                             <div id="dvtablepla_resumen_xclasificador"></div>

                     </div>

                     <div  dojoType="dijit.layout.ContentPane" 
                            splitter="true" 
                             region="center" 
                            data-dojo-props='region:"center" ' style=" padding:0px 0px 0px 0px; ">

                            
                             <div id="dvtablepla_resumen_xclasificador_det"></div>
                     </div>

                </div>
  
            
        </div> -->
        
        
    </div>
         
    
       
    <!--
    
        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"bottom"' style="padding:0px 0px 0px 0px"   >
               
                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Procesar Planilla</label>
                            <script type="dojo/method" event="onClick" args="evt">
                                   
                            </script>
               </button>
                
           
        </div>
    -->
 
    
</div>