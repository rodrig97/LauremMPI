<div id="dvViewName" class="dv_view_name">
  
          <table class="_tablepadding2" border="0">

                  <tr> 
                      <td> 
                           <?PHP 
                                     $this->resources->getImage('window_search.png',array('width' => '22', 'height' => '22'));
                                 ?>
                      </td>

                    <td>
                    Registro de Variables y Conceptos remunerativos
                      </td>
                  </tr>
              </table>
</div>
 
<div id="viewplanilla_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
 <!-- 
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="top" 
            data-dojo-props='region:"top", style:"height: 90px;"'>
                 
      
          
      </div>
    -->
     
     <div   dojoType="dijit.layout.TabContainer" attachParent="true" tabPosition="top" tabStrip="true" data-dojo-props=' region:"center" '>
            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables de calculo</span>">
                    
                
                <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' class="bordercontainer_interno" style="width: 980px;">
                    
                    <div  dojoType="dijit.layout.ContentPane" data-dojo-props=' region:"left", splitter:true  ' style="width:700px;" >
                        <!--splitter:true, minSize:150, maxSize:250 -->
                        
                        <div class="dv_busqueda_personalizada">
                            
                            <form id="frm_searchvariable_main" data-dojo-type="dijit.form.Form"  >
                            
                             <table class="_tablepadding2" border="0"> 
                                 <tr> 
                                     <td width="40">
                                         <span class="sp11b"> Nombre </span>
                                     </td>
                                     <td width="10">
                                            <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="160">  
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name: 'nombre'"  class="formelement-150-11"/> 
                                     </td>
                          
                                     <td width="90">
                                         <span class="sp11b"> Tipo Pla. </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="120">  
                                         
                                          <select dojoType="dijit.form.Select" class="formelement-150-11" data-dojo-props="name: 'tipoplanilla'" style="width:180px;"  >
                                              <option value="0"> No especificar</option>
                                                <?PHP 
                                                foreach($tipos_planilla as $tipo){
                                                    ?>
                                                   
                                                    <option value="<?PHP echo trim($tipo['plati_key']); ?>">  <?PHP echo trim($tipo['plati_nombre']); ?>  </option> 
                                             <?PHP 

                                                }
                                             ?> 
                                          </select> 
                                         
                                     </td>

                                       <td width="30">
                                         <span class="sp11b"> Personalizable </span> 
                                       </td>
                                       <td width="10">
                                           <span class="sp11b"> : </span>  
                                       </td>
                                       <td width="120">  
                                           
                                            <select name="personalizable" dojoType="dijit.form.Select" class="formelement-80-11" data-dojo-props="name: 'personalizable'" style="width:90px;" >
                                                <option value="0"> No Especificar </option>
                                                <option value="1"> No</option>
                                                <option value="2"> Si</option>
                                                 
                                            </select> 
                                           
                                       </td>

                                   </tr>

                              <tr>
                                      <td width="40">
                                         <span class="sp11b"> Grupo </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="120">  
                                         
                                          <select dojoType="dijit.form.FilteringSelect" class="formelement-150-11" data-dojo-props="name: 'grupo'" >
                                              <option value="0"> No especificar</option>
                                              <?PHP
                                                 foreach($grupos as $el){
                                                   echo " <option value='".$el['gvc_id']."'>".trim($el['gvc_nombre'])."</option> ";
                                                 }
                                              ?>
                                          </select> 
                                         
                                     </td> 

                                     <td width="30">
                                       <span class="sp11b"> Computable 5ta (Grati) </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td>  
                                         
                                          <select name="afecto_cuarta_quinta" 
                                                  dojoType="dijit.form.Select" 
                                                  class="formelement-80-11" 
                                                  data-dojo-props="name: 'afecto_cuarta_quinta'" style="width:90px;" >

                                              <option value="2"> ---  </option>
                                              <option value="0"> No</option>
                                              <option value="1"> Si</option>
                                               
                                          </select> 
                                         
                                     </td>

                                      <td colspan="4">
   
                                           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                              <?PHP 
                                                 $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                              ?>
                                                <script type="dojo/method" event="onClick" args="evt">
                                                      Variables.Ui.btn_filtrartabla_main(this,evt);

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
                        
                        
                        <div id="dvtable_variablesview">
 
                        </div>
                        
                        <div class="dv_sub_toolbar" align="right">
                            
                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                              <?PHP 
                                                 $this->resources->getImage('window_add.png',array('width' => '14', 'height' => '14'));
                                              ?>
                                                <script type="dojo/method" event="onClick" args="evt">
                                                     Variables._V.nueva_variable.load({}); 

                                                </script>
                                                <label class="lbl11">
                                                        Nueva variable
                                                </label>
                                           </button>
                        </div>
                        
                        
                    </div>
                    
                    <div id="dv_variable_panelizq"  dojoType="dijit.layout.ContentPane" data-dojo-props=' region:"center" '>
                        
                    </div>
                    
                </div>
                
                  
                 
            </div>
          
            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Conceptos remunerativos</span>" style="width:275px;" attachParent="true">
                  
                
                
                 <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' class="bordercontainer_interno" style="width: 980px;">
                    
                       <div  dojoType="dijit.layout.ContentPane" data-dojo-props=' region:"left", splitter:true  ' style="width:800px;" >
                        <!--splitter:true, minSize:150, maxSize:250 -->
                        
                        <div class="dv_busqueda_personalizada">
                            
                           <form id="frm_searchconceptos_main" data-dojo-type="dijit.form.Form">   
                        
                               <table class="_tablepadding2" border="0"> 
                                 <tr> 
                                     <td width="40">
                                         <span class="sp11b"> Nombre </span>
                                     </td>
                                     <td width="10">
                                            <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="160">  
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name: 'nombre'"  class="formelement-150-11"/> 
                                     </td>
                          
                                     <td width="90">
                                         <span class="sp11b"> Tipo Pla. </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="120">  
                                         
                                          <select dojoType="dijit.form.Select" class="formelement-150-11" data-dojo-props="name: 'tipoplanilla'" style="width:180px;" >
                                              <option value="0"> No especificar</option>
                                                <?PHP 
                                                foreach($tipos_planilla as $tipo){
                                                    ?>
                                                   
                                                    <option value="<?PHP echo trim($tipo['plati_key']); ?>">  <?PHP echo trim($tipo['plati_nombre']); ?>  </option> 
                                             <?PHP 

                                                }
                                             ?> 
                                          </select> 
                                         
                                     </td>

                                       <td width="30">
                                         <span class="sp11b"> Tipo </span> 
                                       </td>
                                       <td width="10">
                                           <span class="sp11b"> : </span>  
                                       </td>
                                       <td width="120">  
                                           
                                             <select dojoType="dijit.form.Select"  data-dojo-props="name: 'tipo'" class="formelement-100-11" style="width:100px;" /> 
                                                 <option value="0" selected="true">No especificar</option>
                                                 <option value="1" >Ingreso</option>
                                                 <option value="2" >Descuento</option>
                                                 <option value="3" >Aportacion</option>
                                                 <option value="99"> No Afecto</option>
                                           </select>  
                                           
                                       </td>

                                   </tr>

                              <tr>
                                    <td width="40">
                                         <span class="sp11b"> Grupo </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="120">  
                                         
                                          <select dojoType="dijit.form.FilteringSelect" class="formelement-150-11" data-dojo-props="name: 'grupo'" >
                                              <option value="0"> No especificar</option>
                                              <?PHP
                                                 foreach($grupos as $el){
                                                   echo " <option value='".$el['gvc_id']."'>".trim($el['gvc_nombre'])."</option> ";
                                                 }
                                              ?>
                                          </select> 
                                         
                                     </td>

                                     <td width="40">
                                         <span class="sp11b"> Clasificador </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="120">  
                                         
                                           <select  dojoType="dijit.form.FilteringSelect" data-dojo-props='name: "partida", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Partida Presupuestal no es valida" '   class="formelement-250-11"> 
                                                  <option value="0" selected="true"> NO ESPECIFICAR </option>
                                                  <option value="99"> SIN CLASIFIADOR ESPECIFICADO </option>
                                                  <?PHP
                                                      foreach($clasificadores as $partida){
                                                          echo "<option value='".$partida['id_clasificador']."'> ".$partida['codigo']." ".$partida['descripcion']."</option>";
                                                      }
                                                  ?>
                                             </select> 
                                         
                                     </td>
      
                                     <td width="30">
                                       <span class="sp11b"> Predeterminado </span> 
                                     </td>
                                     <td width="10">
                                         <span class="sp11b"> : </span>  
                                     </td>
                                     <td width="120">  
                                         
                                         <select dojoType="dijit.form.Select"  data-dojo-props="name: 'predeterminado'" class="formelement-100-11" style="width:100px;"  /> 
                                               <option value="99" selected="true">No especificar</option>
                                               <option value="0" selected="true">NO </option>
                                               <option value="1" selected="true">SI</option>
                                              
                                         </select>  
                                         
                                     </td>


                                  
                                  </tr> 
                                  
                                  <tr>
                                         <td> 
                                            <span class="sp12b"> SUNAT </span> 
                                        </td>
                                        <td width="10"> 
                                             <span class="sp12b"> : </span> 
                                        </td> 
                                        <td>  
                                            <select name="sunat"  dojoType="dijit.form.FilteringSelect"  data-dojo-props='name:"sunat", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Concepto SUNAT no registrado" '  class="formelement-200-11" style="width:150px;" > 
                                                <option value="0" selected="true"> NO ESPECIFICAR </option>  
                                                <option value="-1"> SIN CASILLA ESPECIFICADA </option>
                                               <?PHP 
                                                  foreach($conceptosunat as $cosu){
                                                           echo "<option value='".$cosu['cosu_id']."'>".trim($cosu['cosu_codigo']).' - '.trim($cosu['cosu_descripcion'])."</option>";
                                                  }
                                               ?>
                                            </select>  
                                        </td> 


                                        <td colspan="3">
                                             <span class="sp11b"> Afecto a 4ta: </span> 
                                        
                                             <select name="afecto_cuarta" 
                                                     dojoType="dijit.form.Select" 
                                                     class="formelement-80-11" 
                                                     data-dojo-props="name: 'afecto_cuarta'" style="width:50px;" >

                                                 <option value="2"> ---  </option>
                                                 <option value="0"> No</option>
                                                 <option value="1"> Si</option>
                                                  
                                             </select> 


                                            <!--  <span class="sp11b"> Afecto a 5ta: </span> 
                                             
                                             <select name="afecto_quinta" 
                                                     dojoType="dijit.form.Select" 
                                                     class="formelement-80-11" 
                                                     data-dojo-props="name: 'afecto_quinta'" style="width:50px;" >

                                                 <option value="2"> ---  </option>
                                                 <option value="0"> No</option>
                                                 <option value="1"> Si</option>
                                                  
                                             </select>  -->
                                            
                                             <span class="sp11b"> 5Ta: </span> 
                                                                                       
                                             <select name="afecto_quinta" 
                                                     dojoType="dijit.form.Select" 
                                                     class="formelement-80-11" 
                                                     data-dojo-props="name: 'afecto_quinta'" style="width:150px;" >

                                                 <option value="5"> ---  </option>
                                                 <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_PROYECTABLE; ?>"> Remuneración proyectable </option>
                                                 <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_NOPROYECTABLE; ?>"> Otros ingresos no proye. </option>
                                                 <option value="<?PHP echo QUINTA_TIPO_CONCEPTO_AMBOS; ?>"> Ambos </option>
                                                 <option value="0"> Ninguno </option>
                                                  
                                             </select> 
                                        </td>


                                        <td>
                                        
                                             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                <?PHP 
                                                   $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                                ?>
                                                  <script type="dojo/method" event="onClick" args="evt">
                                                        Conceptos.Ui.btn_filtrartabla_main(this,evt);

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
                        
                        
                        <div id="dvtable_conceptosview">
 
                        </div>
                        
                        <div class="dv_sub_toolbar" align="right">
                            
                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                  <?PHP 
                                                     $this->resources->getImage('window_add.png',array('width' => '14', 'height' => '14'));
                                                  ?>
                                                    <script type="dojo/method" event="onClick" args="evt">
                                                            //Conceptos.Ui.btn_preview_concepto(this,evt);

                                                            Conceptos._V.nuevo_concepto.load({});
                                                    </script>
                                                    <label class="sp11">
                                                            Nuevo Concepto
                                                    </label>
                                             </button>
                            
                        </div>
                        
                        
                    </div>
                    
                    <div id="dv_concepto_panelizq"   dojoType="dijit.layout.ContentPane" data-dojo-props=' region:"center" '>
                        
                    </div>
                  
                        
                 </div>
                    <!-- 
                    <div  dojoType="dijit.layout.ContentPane" data-dojo-props=' region:"center" '>
                        
                    </div>
                    -->
                </div>
                
                
                
                
            </div>
         
     </div>
    
    
                   <!-- 
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center",  '>
            
             
            <div id="dvdetalle_planilla"> 
                
                
            </div>
            
             
          
        </div>
    
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"right",style:"width: 400px;"'>
            
            
        </div> -->
</div>