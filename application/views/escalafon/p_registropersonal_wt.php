<!-- 
    Interfaz programar actividades 
-->
  
<div id="dvViewName" class="dv_view_name">
     Programar acciones y actividades de tareas presupuestales
</div>
  

<div id="dv_ppaa_contenedor" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
                   
                    <div dojoType="dojox.layout.ExpandoPane" 
				splitter="true" 
				duration="125" 
				region="left" 
                                title="<span class='titletabname'> Ver Actividades segun </span>" 
				previewOnDblClick="false"
				id="dv_ppaa_panelizq" 
				maxWidth="275" 
                                style="width: 275px;">
                        
                        
                        <div id="dv_ppaa_tabizq" dojoType="dijit.layout.TabContainer" attachParent="true" tabPosition="bottom" tabStrip="true">
                                        <div id="dv_ppaa_tabizq_1" dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Tareas</span>">

                                                 <!-- Arbol de tareas, objetivos -->
                                                    <div data-dojo-id="store_tree_reltareaobj" data-dojo-type="dojo.data.ItemFileReadStore" data-dojo-props='url:"<?PHP echo base_url();?>tareas/get_tree_objetivostarea?t=1"'></div>
                                                    <div data-dojo-id="model_tree_reltareaobj" data-dojo-type="dijit.tree.ForestStoreModel" data-dojo-props='store:store_tree_reltareaobj, query:{tipo:"tarea"},
                                                            rootId:"tareasRoot", rootLabel:"Tareas Presupuestales", childrenAttrs:["children"]'></div>
                                                            
                                                    <div class="dv_type1"> 
                                                        <table>
                                                            <tr>
                                                                 <td>
                                                                       <label class="sp_tt2">Tarea: </label>
                                                                 </td>
                                                                <td>
                                                                      <select id="selproac_tareasall"   data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea no existe"   ' style="width: 250px; font-size:12px;">
                                                                          <option value="0" selected="selected"> </option> 
                                                                        <?PHP 
                                                                            foreach($tareas as $tarea){
                                                                        ?>
                                                                            <option  value="<?PHP echo trim($tarea['tarea_key']);  ?>" > <?PHP echo trim($tarea['tarea_codigo']).'.- '.trim($tarea['tarea_nombre']);  ?> </option>

                                                                       <?PHP } ?>
                                                                            
                                                                     </select>  
                                                                 </td>
                                                                 <td>
                                                                        <button id="btnproac_viewtareaact" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                                      <?PHP 
                                                                                         $this->resources->getImage('search_add.png',array('width' => '14', 'height' => '14'));
                                                                                      ?>
                                                                                <script type="dojo/method" event="onClick" args="evt">
                                                                                      var v = dijit.byId('selproac_tareasall').get('value');
                                                                                      //alert(isNaN(v));
                                                                                      if( v != false &&  isNaN(v) == true && v!= '0' ){
                                                                                            Actividades.get_actividades(v,3);
                                                                                      }
                                                                                       else{
                                                                                             alert('Verifique la tarea seleccionada');
                                                                                       }
                                                                                </script>
                                                                               
                                                                        </button>
                                                                          
                                                                 </td>
                                                            </tr>
                                                        </table>
                                                        <div style="margin-top: 4px;">
                                                            <a href="http://sys7.mpi.gob.pe/sisplae_addoins/reporte_actividades.php" target="_blank" style="font-size:10px; color:#333333;"> Ver reporte de actividades</a>
                                                        </div>
                                                    </div>
                                                    <div id="tree_reltareaobj" data-dojo-type="dijit.Tree" data-dojo-props='model:model_tree_reltareaobj, openOnClick:true, persist:false' class="tree_fs12">
                                                            <script type="dojo/method" data-dojo-event="onClick" data-dojo-args="item">
                                                                   /*  console.log("Execute of node " + continentStore.getLabel(item)
                                                                            +", population=" + continentStore.getValue(item, "population"));
                                                                                var arbol = dijit.byId('mytree');
                                                                   */
                                                                  var obj = store_tree_reltareaobj.getValue(item,"key"); 
                                                                  Actividades.objetivo_current = obj;
                                                                  Actividades.get_actividades(obj,2);  
                                                                
                                                                  //alert(Acciones);
                                                               //   console.log('key: ' + store_tree_reltareaobj.getValue(item,"key"));
                                                                   
                                                            </script>
                                                            <script type="dojo/method" data-dojo-event="onOpen" data-dojo-args="item">
                                                                  console.log("Open of node " + store_tree_reltareaobj.getValue(item,'tipo'));
                                                                  
                                                                  var obj = store_tree_reltareaobj.getValue(item,"key"); 
                                                                  var tipo =store_tree_reltareaobj.getValue(item,'tipo');
                                                                
                                                                  if(tipo == 'tarea'){
                                                                      
                                                                       Actividades.objetivo_current = obj;
                                                                       Actividades.get_actividades(obj,3);  
                                                                  }
                                                              
                                                            </script>
                                                            <script type="dojo/method" data-dojo-event="onClose" data-dojo-args="item">
                                                                    //console.log("Close of node " + continentStore.getLabel(item)||"root");
                                                            </script>
                                                             
                                                             <script type="dojo/method" data-dojo-event="onLoad" data-dojo-args="">
                                                                   
                                                            </script>
                                                             <script type="dojo/method" data-dojo-event="_onNodeMouseEnter" data-dojo-args="node, evt">
                                                                       
                                                                     dijit.showTooltip(node.item.tooltip,node.domNode.id,['after']);
                                                            </script>
                                                            <script type="dojo/method" data-dojo-event="_onNodeMouseLeave" data-dojo-args="node, evt">
                                                                    
                                                                   dijit.hideTooltip(node.domNode.id); 
                                                            </script>
                                                            
                                                             
                                                    </div>
 
					</div>
                                   	<div id="dv_ppaa_tabizq_2" dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Objetivos</span>">
						<div class="searchBar">
							<p>
								<span style="float: left;">Search:</span>
								<input id="searchBox" name="searchBox" style="float: left;">

								<span id="runSearchIcon" style="border: none; floast: left; padding: 3px;">
									<img src="../../presentation/resources/icons/next.png" style="height:12px; width:12px;">
								</span>
							</p>
						</div>
						
					</div>
					<div id="dv_ppaa_tabizq_3" dojoType="dijit.layout.AccordionContainer" title="<span class='titletabname'>Areas</span>" style="width:275px;" attachParent="true">
						<div dojoType="dijit.layout.ContentPane" title="Dojo DojoDojoDojoDojo Dojo Dojo Dojo Dojo Dojo Dojo Dojo Dojo Dojo Dojo Dojo">
							<ul id="dojoList"></ul>

						</div>
						<div dojoType="dijit.layout.ContentPane" title="Dijit">
							<ul id="dijitList"></ul>
						</div>
						<div dojoType="dijit.layout.ContentPane" title="DojoX">
							<ul id="dojoxList"></ul>
						</div>
					</div>
					
				</div>
                         
                    </div>
                    
                     
                 <div  id="dv_ppaa_paneltop_sel"   dojoType="dojox.layout.ExpandoPane" 
				splitter="true" 
				duration="125" 
				region="top" 
                                title="<span style='font-size:12px;'>Tarea presupuestal</span>" 
				previewOnDblClick="true"
				
			         data-dojo-props='id:"dv_ppaa_paneltop_sel", region:"top", style:"height: 90px;"'>
                     <div  style="background-color: #FFFFFF; border:1px solid #cccccc; height: 60px; " ></div>
                    </div> 
                        
                   <div   dojoType="dijit.layout.TabContainer" attachParent="true" tabPosition="bottom" tabStrip="true" region="center" >
                            <div id="dv_ppaa_panelcenter"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='id:"dv_ppaa_panelcenter"'  title="<span class='titletabname'>Programacion </span>">



                            </div>
                           <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='' title="<span class='titletabname'>Consolidado de saldos </span>">

                               


                                <table id="tbl_progra_fisica" class="_tablepadding4" width="1200" cellpadding="0" cellspacing="0" >
                                    <tr class="trHead2" >
                                        <td colspan="16" align="center" style="border:  1px solid #c2d1de;"> <span> Acciones Generales y su programaci&oacute;n financiera</span> </td>
                                    </tr>
                                     <tr class="trHead2">
                                        <td width="200" rowspan="2" align="center" style=" border:  1px solid #c2d1de;">Obj. Especifico</td>
                                        <td width="200" rowspan="2" align="center" style="border:  1px solid #c2d1de;">Acciones Generales</td>
                                        <td width="90" rowspan="2" align="center" style="border:  1px solid #c2d1de; ">U.Medida</td>
                                        <td colspan="3" align="center" style="border:  1px solid #c2d1de;">I Trimestre</td>
                                        <td colspan="3" align="center" style="border:  1px solid #c2d1de;">II Trimestre</td>
                                        <td colspan="3" align="center" style="border:  1px solid #c2d1de;">III Trimestre</td>
                                        <td colspan="3" align="center" style="border:  1px solid #c2d1de;">IV Trimestre</td>
                                        <td rowspan="2" align="center" style="border:  1px solid #c2d1de;">Total</td>

                                    </tr>
                                    <tr class="trHead2">
                                        <td align="center" style="border:  1px solid #c2d1de;">ENE</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">FEB</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">MAR</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">ABR</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">MAY</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">JUN</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">JUL</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">AGO</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">SEP</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">OCT</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">NOV</td>
                                        <td align="center" style="border: 1px solid #c2d1de;">DIC</td>

                                    </tr>


                                    <tr>
                                        <td align="center" class="celda1">

                                        </td>
                                        <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 
                                        <td class="celda1"> </td> 
                                         <td class="celda1"> </td> 

                                    </tr>


                                    <tr style="font-size:12px;">
                                        <td align="center" style=" border-bottom: 1px solid #c2d1de; border-left: 1px solid #c2d1de; font-size:12px;">
                                            <div>  Impulsar el plan de modernizacion municipal  </div>

                                        </td>

                                        <td style="border-bottom: 1px solid #c2d1de; border-left: 1px solid #c2d1de;"> 

                                            <div>
                                                <b>#1 Actividad (A.Rutinaria): </b> Ejecucion de trabajos en areas usuarias (Rutinaria)
                                            </div>

                                        </td>
                                        <td align="center" style="border-bottom: 1px solid #c2d1de; border-left: 1px solid #c2d1de;  border-right: 1px solid #c2d1de;">
                                             TRABAJO

                                        </td>

                                       <td class="celda1" width="30" align="center"> 0.00 </td>
                                       <td class="celda1" width="30" align="center"> 0.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>
                                       <td class="celda1" width="30" align="center"> 1000.00 </td>

                                       <td class="celda1" width="30" align="center"> 10000.00 </td>
                                    </tr>
                                    <tr style="font-size:12px;">
                                         <td> </td>
                                        <td  colspan="16" style=" border: 1px solid #c2d1de; font-weight: bold; color:#990000">
                                             Total: 10000.00
                                        </td>
  
                                    </tr>




                                    <!-- FINAL DE LA ACTIVIDAD-->


                                </table>
 
 
                               

                            </div>
                  </div>
    </div>