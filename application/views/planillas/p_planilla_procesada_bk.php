<div id="dvViewName" class="dv_view_name">
    Elaboracion de Planilla de Remuneraciones
</div>

<input type="hidden" id="hdviewplanilla_id" value="<?PHP echo trim($plani_info['pla_key']);?>" />

<div id="viewplanilla_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="top" 
            data-dojo-props='region:"top", style:"height: 105px;"'>
          
    
         <div>
             
              
          <table class="_tablepadding2" width="800">
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
                     //   var_dump($plani_info);
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
                     //   var_dump($plani_info);
                          echo  trim($plani_info['pla_anio']).trim($plani_info['pla_mes'])."<span class='sp11b'>(Estado: ".trim($plani_info['estado']).")</span>";
                        ?>
                        </span>
                    </td>
             
                    <td width="60"> 
                        <span class="sp12b"> Mes - Año</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td>  
                        
                        <span class="sp12">
                        <?PHP 
                            echo $plani_info['pla_mes']."/".$plani_info['pla_anio'];
                        ?>
                        </span>
                    </td>
      
               </tr>
               <tr class="row_form"> 
                    <td> 
                        <span class="sp12b">Tarea</span>
                    </td>
                    <td> 
                        <span class="sp12b">:</span>
                    </td>
                    <td colspan="7">  
                        <span class="sp12">
                           <?PHP 
                              echo  ($plani_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA ) ?   (trim($plani_info['tarea_codigo']).' '.trim($plani_info['tarea_nombre']) ) : ' Tarea presupuestal especificada por cada trabajador de la planilla' ;
                        ?>
                        </span>
                    </td> 
               </tr>
               <tr class="row_form"> 
                    <td> 
                        <span class="sp12b">Descripcion/Obs</span>
                    </td>
                    <td> 
                        <span class="sp12b">:</span>
                    </td>
                    <td colspan="7">
                        <span class="sp12">
                         <?PHP 
                            echo  trim($plani_info['pla_descripcion']);
                        ?></span>
                    </td>
               </tr>
               
          </table>
         </div>
         
         
              <div class="dv_busqueda_personalizada_pa2" style="width:700px; margin:5px 0px 0px 0px;">
              <table class="_tablepadding2">
                  <tr> 
                        
                        <td> 
                              
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                       
                                        Impresiones.Ui.btn_previewboleta_click(this,evt);
                                    </script>
                                    <label class="sp11">
                                           Imprimir resumen de planilla
                                    </label>
                               </button>
                       </td>
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
                                            Planillas.Ui.btn_procesarplanilla_click(this,evt);
                                        
                                    </script>
                                    <label class="sp11">
                                          Anular Planilla
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
                                    <label class="sp12">
                                         Afectacion Presupuestal
                                    </label>
                               </button>
                       </td>
                  
                  </tr>
                
              </table>
         </div>
          
      </div>
                   
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", splitter:true ' style ="width: 620px;"  >
            
             
            <div id="dvdetalle_planilla"> 
                
                
            </div> 
            
             
             
        </div>
    
        <div id="dv_vipla_detalle"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            
           
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