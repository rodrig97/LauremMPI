<div id="dvViewName" class="dv_view_name">
    
     <table class="_tablepadding2" border="0">
         <tr> 
              <td> 
                   <?PHP 
                             $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                         ?>
              </td>
              <td>
                   Explorar registro de asistencia obreros
              </td>
         </tr>
      </table>
</div>
 

<div id="asistencias_explorar_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="padding:0px;">
  
    <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"top" ' style ="height:30px;"   >  
   
             <table class="_tablepadding2">

                <tr>  
                      <td>

                          <div data-dojo-type="dijit.form.DropDownButton" >
                                  
                                     <span class="sp12b">
                                          <?PHP 
                                               $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                           ?>
                                         
                                          Parámetros de Búsqueda
                                     
                                     </span>
                                    
                                     <div  data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda"'>
                          
                                              
                                           <form id="form_explorar_asistencias"  data-dojo-type="dijit.form.Form" action="exportar/asistencia_permisos_excel" method="post" target="_blank">   
                                                
                                                <input type="hidden" name="tipo_registro_asistencia" value="1">
                                                <table class="_tablepadding4">
                                                    <tr id="trexplorarasis_tipobusqueda">
                                                         <td width="120"> <span class="sp11b"> Filtrar </span> </td>
                                                         <td width="10"> <span class="sp11b">  :  </span> </td>
                                                         <td width="350"> 
                                                               <select id="selexplorarasis_tipobusqueda" data-dojo-type="dijit.form.Select" data-dojo-props='name:"tipobusqueda", disabled:false'
                                                                       style=" font-size:12px; width: 200px;">
                                                                      
                                                                      <option value="1"> Por Tipo de trabajador </option> 
                                                                      <option value="2"> Por Proyecto </option>
                                                                      <option value="3"> Por Area de Trabajo </option>
                                                                      <option value="4"> Por Hoja de Asistencia </option>
                                                                      <option value="5"> Por Trabajador </option>   
                                                               </select>
                                                         </td>
                                                    </tr>

                                                    <tr id="trexplorarasis_regimen">
                                                         <td width="120"> <span class="sp11b"> Tipo de trabajador   </span> </td>
                                                         <td width="10"> <span class="sp11b">  :  </span> </td>
                                                         <td width="350"> 
                                                               <select   data-dojo-type="dijit.form.Select" data-dojo-props='name:"planillatipo", disabled:false' style=" font-size:12px; width: 200px;">
                                                                   
                                                                     <?PHP
                                                                       foreach($tipos as $tipo){
                                                                            echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                                                       }
                                                                     ?>
                                                               </select>
                                                         </td>
                                                    </tr>
                                                    <tr id="trexplorarasis_proyecto"  style="display:none;">
                                                         <td> <span class="sp11b"> Meta/Proyecto   </span> </td>
                                                         <td> <span class="sp11b">  :  </span> </td>
                                                         <td> 
                                                              <select  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style=" font-size:11px; width: 300px;">
                                                                   
                                                                      <?PHP
                                                                      foreach($tareas as $tarea){
																		if (trim($tarea['ano_eje']) >= 2021) {
																			echo "<option value='".trim($tarea['tarea_id'])."'> (".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).') '.trim($tarea['tarea_nombre'])."</option>";
																		} else {
																			echo "<option value='".trim($tarea['tarea_id'])."'> (".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea_nro']).') '.trim($tarea['tarea_nombre'])."</option>";
																		}
                                                                      }
                                                                    ?>
                                                              </select>
                                                         </td>
                                                    </tr>
                                                     <tr id="trexplorarasis_area" style="display:none;">
                                                         <td> <span class="sp11b"> Area de Trabajo   </span> </td>
                                                         <td> <span class="sp11b">  :  </span> </td>
                                                         <td> 
                                                               <select name="dependencia"   data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   ' style="width: 300px; font-size:11px;">
                                                                     
                                                                     <?PHP 
                                                                         foreach($dependencias as $depe){
                                                                     ?>
                                                                         <option  value="<?PHP echo trim($depe['area_id']);  ?>"> <?PHP echo trim($depe['area_nombre']); ?> </option>

                                                                    <?PHP } ?>
                                                               </select> 
                                                         </td>
                                                    </tr>
                                                     <tr id="trexplorarasis_hoja" style="display:none;">
                                                         <td> <span class="sp11b"> Hoja de asistencia </span> </td>
                                                         <td> <span class="sp11b">  :  </span> </td>
                                                         <td> 
                                                              
                                                              <input type="text" data-dojo-type="dijit.form.TextBox" value="" name="codigohoja" class="formelement-80-11" /> 
                                                         </td>
                                                    </tr>
                                                     <tr id="trexplorarasis_trabajador" style="display:none;">
                                                         <td> <span class="sp11b">  Trabajador   </span> </td>
                                                         <td> <span class="sp11b">  :  </span> </td>
                                                         <td> 
                                                              <input type="text" data-dojo-type="dijit.form.TextBox" value="" name="dni" class="formelement-80-11" />  
                                                         </td>
                                                    </tr>

                                                    <tr id="trexplorarasis_mostraractivos"  >
                                                         <td width="120"> <span class="sp11b"> Mostrar   </span> </td>
                                                         <td width="10"> <span class="sp11b">  :  </span> </td>
                                                         <td width="350"> 
                                                               <select   data-dojo-type="dijit.form.Select" 
                                                                         data-dojo-props='name:"mostraractivos", disabled:false' 
                                                                         style=" font-size:12px; width: 300px;">
                                                                      
                                                                      <option value="2" select="selected">  Solo trabajadores con registro de asistencia </option>
                                                                      <option value="1"> Solo trabajadores Activos </option>
                                                                      <option value="0"> Todos los trabajadores (Activos e Inactivos) </option>
                                                               </select>
                                                         </td>
                                                    </tr>
                                                   
                                                    <tr> 
                                                       <td><span class="sp12b"> Periodo</span></td>
                                                       <td> <span class="sp11b">  :  </span> </td>
                                                       <td> 
                                                           <span class="sp12b">Del: </span>
                                                           <div id="cal_expasis_desde" data-dojo-type="dijit.form.DateTextBox"
                                                                            data-dojo-props='type:"text", name:"fechadesde", value:"",
                                                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                            lang:"es",
                                                                            required:true,
                                                                            promptMessage:"mm/dd/yyyy",
                                                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                                                            
                                                                              onChange="dijit.byId('cal_expasis_hasta').constraints.min = this.get('value');
                                                                                        var m = dojo.date.add(this.get('value'), 'day', 29);
                                                                                        dijit.byId('cal_expasis_hasta').set('value', m); ">
                                                                        </div> 
                                                           
                                                           <span class="sp12b"> Hasta: </span>
                                                           
                                                           <div id="cal_expasis_hasta"  data-dojo-type="dijit.form.DateTextBox"
                                                                            data-dojo-props='type:"text", name:"fechahasta", value:"",
                                                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                            lang:"es",
                                                                            required:true,
                                                                            promptMessage:"mm/dd/yyyy",
                                                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
                                                                        </div> 
                                                       </td>
                                                   </tr>
                                                     
                                                    <tr> 
                                                        <td colspan="3" align="center">


                                                          
                                                        </td>
                                                    </tr>
                                                </table>

                                                <input type="hidden" value="1" name="pagina" id="hdasistencias_explorar_paginador" />

                                            </form> 
                          
                                    </div>
                            </div> 


                      </td>

                    <!--    <td>   
                            <span class="sp11b"> Ordenar por: </span>
                            <select id="sel_asis_ordenarpor"  
                                    data-dojo-type="dijit.form.Select" 
                                    data-dojo-props='name:"orden", disabled:false'
                                    style="font-size:11px; width:200px;">
                                  
                                   <option value="1"> Nombre del trabajador, categoria  </option>
                                   <option value="2"> Categoria, nombre del trabajador  </option>
                            </select>
                      </td> -->

                       <td>   
                            <span class="sp11b"> Visualizar: </span>
                            <select id="sel_asis_visualizar"  
                                    data-dojo-type="dijit.form.Select" 
                                    data-dojo-props='name:"ver_modo", disabled:false'
                                    style="font-size:11px; width:140px;">
                                  
                                   <option value="1"> Estado del día </option>
                                   <option value="2"> Horas trabajadas </option>
                                   <option value="3"> Tardanzas </option> 
                              
                                   <option value="4"> Hora ingreso 1 </option>
                                   <option value="5"> Hora Salida 1  </option>
                                   <?PHP
                                     if($config['maximo_marcaciones'] > 2)
                                     {
                                   ?>
                                       <option value="6"> Hora ingreso 2 </option>
                                       <option value="7"> Hora Salida 2  </option>
                                  <?PHP    
                                     }
                                   ?>
                            </select>
                      </td>

                      <td>

                             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                     <?PHP 
                                        $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                     ?>
                                        <label class="lbl10">Realizar Busqueda</label>
                                         <script type="dojo/method" event="onClick" args="evt">
                                                
                                                dojo.byId('hdasistencias_explorar_paginador').value = '1';

                                                var data =  dojo.formToObject('form_explorar_asistencias'); 

                                                data.ver_modo = dijit.byId('sel_asis_visualizar').get('value');
                                                Asistencias.get_registro_asistencia(data);
                                       </script>
                              </button> 

                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                    <?PHP 
                                       $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                    ?>
                                      <label class="lbl10"> Generar reporte en excel </label>
                                      <script type="dojo/method" event="onClick" args="evt">
                                                
                                               var data =  dojo.formToObject('form_explorar_asistencias'); 
                                               data.ver_modo = dijit.byId('sel_asis_visualizar').get('value');

                                               Asistencias._M.exportar_asistencia_excel.send(data);

                                               
                                      </script>
                             </button> 
                            

 

                      </td>
                </tr>
             </table> 

 

    </div>
    
    <div  id="dvpanel_explorar_asistencias" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center",  style:"padding:0px 0px 0px 0px;"  ' style =""  >
 
    </div>




</div>