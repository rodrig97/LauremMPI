<div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 580px; height: 160px;" >
      


      <div  dojoType="dijit.layout.TabContainer"   data-dojo-props='region:"center" '  style="padding: 0px 0px 0px 0px;">
                   
          <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Variables </span>">
  
            <div> 
               <span class="sp11">
                  Desde aqui usted puede modificar el valor de una variable para todos los trabajadores de la planilla
               </span>
            </div>
 
              <table class="_tablepadding4"> 
                   <tr> 
                       <td width="120"> 
                             <span class="sp12b"> Variable </span>
                       </td>
                       <td width="10"> 
                              <span class="sp12b">  : </span>
                       </td>
                       <td> 
                         <select id="addforall_variable"  data-dojo-type="dijit.form.FilteringSelect" 
                                 data-dojo-props='name:"variable", 
                                                  disabled:false, 
                                                  autoComplete:false, 
                                                  highlightMatch: "all", 
                                                  queryExpr:"*${0}*", 
                                                  invalidMessage: "Concepto no registrado" ' 

                                 style="margin-left:0px; font-size:11px; width: 300px;">
                           <?PHP 
                             foreach($variables as $vari){
                              
                               echo " <option value='".$vari['vari_key']."'>".$vari['vari_nombre']." </option>";

                             }
                           ?>
                         </select>
                       </td>

                   </tr>
                   <tr> 
                       <td> 
                              <span class="sp12b"> Nuevo valor </span>
                       </td>
                       <td> 
                             <span class="sp12b"> : </span>
                       </td>
                       <td> 
                            <input id="addforall_valorvariable" type="text" 
                                   class="formelement-80-11"
                                   data-dojo-type="dijit.form.TextBox" 
                                   data-dojo-props="name:'valor'" 
                                   value="0"/>
                       </td>

                   </tr>
                   <tr> 
                       <td colspan="3" align="center"> 
                              
                         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                               $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                            ?>
                              <script type="dojo/method" event="onClick" args="evt">
                                  Variables.Ui.btn_actualizar_valor_forall(this,evt);
                              </script>
                              <label class="sp12">
                                  Actualizar 
                              </label>
                         </button>

                       </td> 
                   </tr>
               </table>
              

          </div>
          
          <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Conceptos </span>">
           
            

            <div> 
               <span class="sp11">
                  Desde aqui usted puede activar o desactivar  un concepto para todos los trabajdores de la planilla.
               </span>
            </div>

               <table class="_tablepadding4"> 
                   <tr> 
                       <td width="120"> 
                             <span class="sp12b"> Concepto </span>
                       </td>
                       <td width="10"> 
                              <span class="sp12b">  : </span>
                       </td>
                       <td> 
                         <select id="addforall_concepto"  data-dojo-type="dijit.form.FilteringSelect" 
                                 data-dojo-props='name:"concepto", 
                                                  disabled:false, 
                                                  autoComplete:false, 
                                                  highlightMatch: "all", 
                                                  queryExpr:"*${0}*", 
                                                  invalidMessage: "Concepto no registrado" ' 

                                 style="margin-left:0px; font-size:11px; width: 300px;">
                           <?PHP 
                             foreach($conceptos as $conc){
                              
                               echo " <option value='".$conc['conc_key']."'>".$conc['conc_nombre']." </option>";

                             }
                           ?>
                         </select>
                       </td>

                   </tr>
                   <tr> 
                       <td> 
                              <span class="sp12b"> Ajustar a estado </span>
                       </td>
                       <td> 
                             <span class="sp12b"> : </span>
                       </td>
                       <td> 
                            <input id="addforall_estadoconcepto" type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="name:'estado'" value="1" checked="checked"/>
                       </td>

                   </tr>
                   <tr> 
                       <td colspan="3" align="center"> 
                              
                         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                               $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                            ?>
                              <script type="dojo/method" event="onClick" args="evt">
                                    Conceptos.Ui.btn_actualizar_estado_forall(this,evt);
                              </script>
                              <label class="sp12">
                                  Actualizar 
                              </label>
                         </button>

                       </td> 
                   </tr>
               </table>

          </div>

    </div>  

</div>
