  
<div class="window_container">
 
  
    <div>

        <table class="_tablepadding4">

            <tr>
                <td> 
                     <span class="sp12b"> Planilla:  </span>
                </td>

                <td> 
                     <span class="sp12b"> <?PHP echo $planilla_info['pla_codigo']; ?>  </span>
                </td>
 
                <?PHP 

                  if(CONECCION_AFECTACION_PRESUPUESTAL)
                  { 
                ?>

                      <td> 
                           <span class="sp12b"> Descontar dinero desde  </span>
                      </td>

                      <td>
                          <span class="sp12b"> : </span>
                      </td>

                      <td>
                          <span class="sp12">  

                          <?PHP 
                                if($planilla_info['pla_afectadinero_modo'] == PLATI_AFECTARDINERO_MODO_SALDO )
                                {

                                    echo " Saldo presupuestal";
                                }
                                else if( $planilla_info['pla_afectadinero_modo'] == PLATI_AFECTARDINERO_MODO_PREAFECTACION )
                                {
                                    echo " Preafectación ";
                                }

                            ?> 

                          </span>
                      </td>

                <?PHP
                  }
                ?>
            </tr>

        </table>
       

    </div>

     <div class="dv_busqueda_personalizada_pa2"> 

         <form id="form_table_afectacion">
             <table class="_tablepadding2"> 
                 <tr> 
                      <td> <span class="sp11b"> Tarea presupuestal </span> </td>
                      <td>  <span class="sp11b"> : </span></td>
                      <td>
                          <select data-dojo-type="dijit.form.FilteringSelect" data-dojo-props=' name:"tarea",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La tarea no es valida"   ' class="formelement-200-11">
                               <option value="0" selected="true"> Ver todas </option>
                              <?PHP 
                                 foreach($afectados['tareas'] as $tarea){
                                  ?>  
                                     <option value="<?PHP echo $tarea['tarea_id'] ?>"> <?PHP echo $tarea['tarea'] ?> </option>
                                  <?PHP  
                                 }
                              ?>
                              
                           </select>
                      </td>
                      
                      <td> <span class="sp11b"> Clasificador </span> </td>
                      <td>  <span class="sp11b"> : </span></td>
                      <td>
                          <select data-dojo-type="dijit.form.FilteringSelect" data-dojo-props=' name:"partida",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "El Clasificador no es valido"   ' class="formelement-200-11">
                              <option value="0" selected="true"> Ver todos </option>

                                <?PHP 
                                 foreach($afectados['partidas'] as $partida){
                                  ?>  
                                     <option value="<?PHP echo $partida['id_clasificador'].'-'.$partida['ano_eje'] ?>"> <?PHP echo $partida['partida'] ?> </option>
                                  <?PHP  
                                 }
                              ?>
                           </select>
                      </td>
                      <td> 
                           <button dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                <?PHP  $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                        ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                      Planillas.Ui.btn_filtrar_afectacion(this,evt);
                                </script>
                                <label class="sp11">
                                               Filtrar
                                </label>
                         </button>
                      </td>
                 </tr>
             </table>
       </form>

        <div class="dv_message_t2"> 
             
        </div>

     </div> 
 

 <input type="hidden" id="hdafectacion_modo" value="<?PHP echo CONECCION_AFECTACION_PRESUPUESTAL ? '1' : '0'; ?>" />
 <div id="dvtable_afectacion_planilla"></div>
 
 

 <div style="margin: 8px 0px 0px 8px;"> 

       <form id="form_afectacion_datos"> 

           <input type="hidden" name="planilla" value="<?PHP echo $view; ?>" />

       </form>

       <button id="btnap_afectarpresupuestal"  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
            <?PHP 
               $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
            ?>
            <script type="dojo/method" event="onClick" args="evt">
  
                 var data = dojo.formToObject('form_afectacion_datos');

                 if( Planillas.Ui.Grids.planillas_afectacion != null)
                 {
                    Planillas.Ui.Grids.planillas_afectacion.refresh();
                 }          

                 if(confirm('Realmente desea realizar la afectacion presupuestal ? '))
                 {

                    if(Planillas._M.afectacion_presupuestal.process(data))
                    { 
                        Planillas._V.ver_afectacion_presupuestal.close();
                        
                        var data = {}
                        data.planilla = dojo.byId('hdviewplanilla_id').value;
                        Planillas._V.registrar_siaf.load(data);
                        Planillas.fn_load_planilla({'codigo' : Planillas._M.afectacion_presupuestal.data.key});
                    }
                    else
                    {
                        if( Planillas.Ui.Grids.planillas_afectacion != null)
                        {
                           Planillas.Ui.Grids.planillas_afectacion.refresh();
                        }          
                    }
                 }
                
            </script>
            <label class="sp11">
                <?PHP echo (CONECCION_AFECTACION_PRESUPUESTAL) ? 'Afectar presupuestalmente ' : ' Confirmar afectacion presupuestal'; ?>
                    
            </label>
        </button>

        <?PHP 

            if(CONECCION_AFECTACION_PRESUPUESTAL)
            { 
         ?>

            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                 <?PHP 
                    $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                 ?>
                 <script type="dojo/method" event="onClick" args="evt">
            
                      
                          
                      Impresiones.Ui.btn_resumenpresupuestal_planilla_click(this,evt);
                     
                 </script>
                 <label class="sp11">
                       Imprimir cuadro resumen
                 </label>
             </button>  

         <?PHP 
            }
         ?>


         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
              <?PHP 
                 $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
              ?>
              <script type="dojo/method" event="onClick" args="evt">
         
                   var data = dojo.formToObject('form_afectacion_datos');
         
                  
              </script>
              <label class="sp11">
                     Ver trabajadores
              </label>
          </button>
 </div>
 
 
 
 
</div>