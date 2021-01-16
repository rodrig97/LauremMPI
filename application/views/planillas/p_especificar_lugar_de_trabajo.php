<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('user_search_b.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
             Especificar lugar de trabajo 
          </td>
      </tr>
  </table>
 
</div>

<div id="escalaregistro_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
  
        <div id="dv_ppaa_panelcenter"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center", style:"height: 400px;" '>
             
            <div class="dvmb10"> 
            
             <div data-dojo-type="dijit.form.DropDownButton" >
                      
                         <span class="sp12b">
                              <?PHP 
                         $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                             
                              Parámetros de Búsqueda
                         
                         </span>
                        <div id="tooltipDlg" data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el registro de trabajadores"'>

                            <div class="dv_formu_find_tt">
                                  <form id="form_registro_consulta"  data-dojo-type="dijit.form.Form">   
                                        <table class="_tablepadding2" style="width:100%">
                                              <tr height="30" class="row_form" >
                                                      <td colspan="7"> <span class="sp12b">Parametros de busqueda</span></td>
                                              </tr>

                                              
                                             <tr id="trexplorarasis_regimen">
                                                  <td width="120"> <span class="sp11b"> Tipo de trabajador   </span> </td>
                                                  <td width="10"> <span class="sp11b">  :  </span> </td>
                                                  <td width="350"> 
                                                        <select   data-dojo-type="dijit.form.Select" data-dojo-props='name:"situlaboral", disabled:false' style=" font-size:12px; width: 200px;">
                                                              <?PHP
                                                                foreach($tipos as $tipo){
                                                                     echo "<option value='".trim($tipo['plati_id'])."'>".trim($tipo['plati_nombre'])."</option>";
                                                                }
                                                              ?>
                                                        </select>
                                                  </td>
                                             </tr>

                                             <tr id="trexplorarasis_regimen">
                                                  <td width="120"> <span class="sp11b"> Lugar de trabajo   </span> </td>
                                                  <td width="10"> <span class="sp11b">  :  </span> </td>
                                                  <td width="350"> 
                                                        <select   data-dojo-type="dijit.form.Select" data-dojo-props='name:"lugar_de_trabajo", disabled:false' style=" font-size:12px; width: 200px;">
                                                               <option value="0"> No especificar </option>
                                                               <option value="<?PHP echo LUGAR_DE_TRABAJO_PALACIO; ?>"> Palacio Municipal </option>
                                                               <option value="<?PHP echo LUGAR_DE_TRABAJO_PERIFERICO; ?>"> Periféricos </option>
                                                        </select>
                                                  </td>
                                             </tr>
                                            
                                            <!--   <tr height="30"  class="row_form"> 
                                                  <td width="100"> <span class="sp12b">Apellido Paterno</span></td>
                                                  <td width="10">:</td>
                                                  <td width="105"> 
                                                      <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-100-11" />

                                                  </td>
                                         
                                                  <td width="100"> <span class="sp12b">Apellido Materno </span></td>
                                                  <td width="10">:</td>
                                                  <td width="105"> 
                                                      <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'materno'" class="formelement-100-11" />

                                                  </td>
                                          
                                                  <td width="60"> <span class="sp12b">Nombres </span></td>
                                                  <td width="10">:</td>
                                                  <td width="105"> 
                                                      <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'nombres'" class="formelement-100-11" />

                                                  </td>
                                             </tr> -->
                                             <tr height="30"  class="row_form"> 
                                                 <td> <span class="sp12b">DNI</span></td>
                                                 <td>:</td>
                                                 <td> 
                                                      <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-80-11" />
                                                      <label class="lbl_submensaje"> (*) Al especificar este campo no se consideraran los demas en la busqueda</label>
                                                  </td>
                                             </tr>
                                             <tr height="30"  class="row_form">  
                                               <td> <span class="sp12b">Activos</span></td>
                                                <td>
                                                    : 
                                                </td>
                                                <td> 
                                                    
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"vigente" ' style="width: 50px; font-size:12px;">
                                                                   <option value="1" selected="selected">  SI </option>
                                                                   <option value="0">  NO </option>
                                                                   <option value="2">  No especificar </option>
                                                                     
                                                       </select> 
                                                </td>
                                             </tr>
                                             <tr height="30">
                                                 <td> </td>
                                                 <td> </td>
                                                  <td> 

                                                      <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                              <?PHP 
                                                                 $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                                              ?>
                                                                 <label class="lbl10">Realizar Busqueda</label>
                                                                  <script type="dojo/method" event="onClick" args="evt">
                                                                         Planillas.Ui.Grids.especificar_lugar_trabajo.refresh();
                                                                </script>
                                                       </button>

                                                </td>
                                             </tr>

                                        </table>
                                  </form>
                              </div>


                        </div>
                </div> 
            </div>
             
             <div id="dvapersonalregistrado_data"> </div>    
             <div id="dvespecificarlugar_de_trabajo"></div>
             
               <div class="table_toolbar" align="left"> 
                   
                   <span class="sp11"> Actualizar a: </span>
                   
                   <select id="selLugarDetrabajoActualizar"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"lugar_de_trabajo", disabled:false' 
                   style=" font-size:11px; width:140px;">
                          <option value="<?PHP echo LUGAR_DE_TRABAJO_PALACIO; ?>"> Palacio Municipal </option>
                          <option value="<?PHP echo LUGAR_DE_TRABAJO_PERIFERICO; ?>"> Periféricos </option>
                   </select>

                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10"> Actualizar </label>
                              <script type="dojo/method" event="onClick" args="evt">
                                  
                                   var lugar = dijit.byId('selLugarDetrabajoActualizar').get('value');
 
                                   var codigo_e = '';   
                                   var selection =  Planillas.Ui.Grids.especificar_lugar_trabajo.selection;   
                                   
                                   var ce = 0;
                                   for(var i in selection)
                                   { 

                                       if(selection[i] === true)
                                       {
                                         codigo_e +='_'+ i;
                                         ce++;
                                       }
                                         
                                   }


                                   if(codigo_e != '')      
                                   {
                                       if(confirm('Realmente desea actualizar el lugar de trabajo de  '+ ce +' trabajadores ?')){
                                         
                                           if(Asistencias._M.actualizar_lugar_trabajo.process({'lugar_de_trabajo' : lugar, 'trabajadores' : codigo_e}))
                                           {
                                                Planillas.Ui.Grids.especificar_lugar_trabajo.refresh();                      
                                           }
                                       }
                                   }
                                   else
                                   { 
                                       alert('Debe seleccionar un registro');
                                   } 
                                   
                                

                            </script>
                   </button>

                 <!--   <button  dojoType="dijit.form.Button" class="dojobtnfs_12"  disabled="true"> 
                          <?PHP 
                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Eliminar</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                  //  Persona.Ui.btn_registrarcomserv_click(this,evt);
                            </script>
                   </button> -->

               </div>
            
            
        </div>
</div>