<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="window_container"> 

     
    <div style="margin: 3px 0px 3px 0px;"> 
         <table class="_tablepadding2"> 
             <tr>
                 <td width="40"> <span class="sp11b"> Trabajador </span> </td>
                 <td> <span class="sp11b"> : </span> </td>
                 <td> <span class="sp11"> <?PHP echo trim($trabajador); ?> </span> </td>
             </tr>
         </table> 
    </div> 
      <div class="dv_busqueda_personalizada_pa2">
                            
       <form id="frm_searchconceptos_adddetalle" data-dojo-type="dijit.form.Form">   

         <input type="hidden" value="<?PHP echo $modo; ?>" name="modo" data-dojo-props="name: 'modo'" />
         <table class="_tablepadding2" border="0"> 
             <tr> 
                 <td width="40">
                     <span class="sp11b"> Nombre </span>
                 </td>
                 <td width="10">
                        <span class="sp11b"> : </span>  
                 </td>
                 <td width="160">  
                      <input type="text" dojoType="dijit.form.TextBox"  data-dojo-props="name: 'nombre'"   class="formelement-150-11"/> 
                 </td>

                 
                  <td width="32">
                     <span class="sp11b"> Tipo </span> 
                 </td>
                 <td width="10">
                     <span class="sp11b"> : </span>  
                 </td>
                 <td width="120">  
 
                           <select dojoType="dijit.form.Select"  data-dojo-props="name: 'tipo'" class="formelement-100-11"  /> 
                              <option value="0" selected="true">No especificar</option>
                             <option value="1" >Ingreso</option>
                             <option value="2" >Descuento</option>
                             <option value="3" >Aportacion</option>
                           </select>  
                 </td>
                 
                 <td width="70">
                     <span class="sp11b"> Aplicable a </span> 
                 </td>
                 <td width="10">
                     <span class="sp11b"> : </span>  
                 </td>
                 <td width="120">   
                      <input type="hidden" value="<?PHP echo trim($planilla_tipo['key']); ?>" name="tipoplanilla" />
                           <span class="sp12b">  <?PHP   echo trim($planilla_tipo['nombre']);
                                ?> </span>
                 </td>
                 <td>
                       <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                          ?>
                            <script type="dojo/method" event="onClick" args="evt">
                                    
                                  <?PHP 

                                    if($modo == 'from_detalle'){ 
            
                                  ?>  
                                        if( Conceptos.Ui.Grids.planilladetalle_buscar != null)
                                        {
                                       
                                           Conceptos.Ui.Grids.planilladetalle_buscar.refresh();
                                                                
                                        }    

                                  <?PHP 
                                     }
                                     elseif($modo=='from_trabajador')
                                     {

                                  ?>  
 
                                         if( Trabajadores.Ui.Grids.add_conceptos != null)
                                         { 
                                              Trabajadores.Ui.Grids.add_conceptos.refresh();
                                               
                                         }          

                                  <?PHP 
                                  
                                     }
                                  ?>

                                 
 
                            </script>
                            <label class="lbl11">
                                    Buscar
                            </label>
                        </button>
                  </td> 
                  
              </tr> 
         </table> 
       </form>
    </div>

    <div id="dv_planillaempleado_addconcepto">

    </div>
    
    <div style="margin:4px 0px 0px 2px;">
        
           <input class="hdaddconc_key" type="hidden" value="<?PHP echo trim($key); ?>" />

           <?PHP if($modo == 'from_detalle'){ 
            
              ?>
                 <label class="sp11">  Para  todos los trabajadores : </label> <input type="checkbox" id="chk_addconc_forall"  data-dojo-type="dijit.form.CheckBox" value="1"  />

                 <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId: 'chk_addconc_forall', position:['above']">
                                       Marque esta casilla si usted desea agregar el concepto a <br/>
                                       todos los trabajadores de la planilla
                                </div>

            <?PHP 
                }
            ?>


           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
              <?PHP 
                 $this->resources->getImage('window_add.png',array('width' => '14', 'height' => '14'));
              ?>
                <script type="dojo/method" event="onClick" args="evt">
  
                    <?PHP if($modo == 'from_detalle'){

                        echo " Conceptos.Ui.btn_addconcepto_detalle_click(this,evt); ";
                        $label_btn = " Seleccionar y añadir concepto ";


                    }elseif($modo=='from_trabajador'){

                        echo " Trabajadores.Ui.btn_addconcepto_click(this,evt); ";
                        $label_btn = " Vincular Concepto al Trabajador ";

                    } ?>
                  </script>
                  <label class="sp11">
                          <?PHP echo $label_btn; ?>
                  </label>
           </button>

          
    </div>

    
</div>