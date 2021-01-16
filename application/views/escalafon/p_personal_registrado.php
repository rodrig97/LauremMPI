<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('user_search_b.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
            Registro de Trabajadores de la Institucion
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

                                              <tr height="30" class="row_form" >
                                                      <td width="110"> <span class="sp12b">Régimen </span></td>
                                                      <td width="20">:</td>
                                                      <td colspan="7"> 
                                                           <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"situlaboral" ' class="formelement-35-12" style="width:200px; font-size:12px;">
                                                                  <option value="0" selected="selected"> No Especificar </option>
                                                                 <?PHP 
                                                                    foreach($tipo_empleados as $tipoemp){
                                                                        echo '<option value="'.trim($tipoemp['id']).'">'.trim($tipoemp['label']).'</option>';
                                                                    }
                                                                 ?>
                                                          </select>
                                                      </td>
                                                 </tr>
                                             <tr height="30"  class="row_form"> 
                                                  <td> <span class="sp12b">Dependencia</span></td>
                                                  <td>:</td>
                                                   <td colspan="7"> 
                                                      <select data-dojo-type="dijit.form.FilteringSelect" data-dojo-props=' name:"dependencia",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   ' style="width: 300px; font-size:12px;">
                                                              <option value="0" selected="selected"> No Especificar </option>
                                                           <?PHP 
                                                                foreach($dependencias as $depe){
                                                                       
                                                            ?>
                                                                <option  value="<?PHP echo trim($depe['area_id']);  ?>"> <?PHP echo trim($depe['area_nombre']); ?> </option>

                                                           <?PHP } ?>
                                                      </select> 

                                                  </td>
                                             </tr>
                                              <tr height="30"  class="row_form">  
                                                          <td> <span class="sp12b">Cargo</span></td>
                                                          <td>:</td>
                                                           <td colspan="7"> 
                                                               <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"cargo" ' style="width: 150px; font-size:12px;">
                                                                            <option value="0" selected="selected"> No Especificar </option>
                                                                           <?PHP 
                                                                                foreach($cargos as $cargo){
                                                                            ?>
                                                                                <option  value="<?PHP echo trim($cargo['cargo_id']);  ?>"  > <?PHP echo $cargo['cargo_nombre']; ?> </option>

                                                                           <?PHP } ?>
                                                               </select> 
                                                          </td>
                                              </tr>
                                              <tr height="30"  class="row_form"> 
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
                                             </tr>
                                               <tr height="30"  class="row_form"> 
                                                  <td> <span class="sp12b">DNI</span></td>
                                                  <td>:</td>
                                                   <td colspan="7"> 
                                                      <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-80-11" />
                                                      <label class="lbl_submensaje"> (*) Al especificar este campo no se consideraran los demas en la busqueda</label>
                                                  </td>
                                             </tr>
                                             <tr height="30"  class="row_form">  
                                               <td> <span class="sp12b">Activos</span></td>
                                                <td>
                                                    : 
                                                </td>
                                                <td colspan="7"> 
                                                    
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
                                                  <td colspan="7"> 

                                                      <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                              <?PHP 
                                                                 $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                                              ?>
                                                                 <label class="lbl10">Realizar Busqueda</label>
                                                                  <script type="dojo/method" event="onClick" args="evt">
                                                                          Persona.Ui.btn_regtrabaj_filtrar_click(this,evt);
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
            
 
    <!-- 
            <div class="dv_busqueda_personalizada_pa2"> 

                 <table class="_tablepadding2"> 

                    <tr> 
                        <td width="110"> <span class="sp12b">Situacion laboral </span></td>
                        <td width="20">:</td>
                        <td> 
                            <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"situlaboral" ' class="formelement-35-12">
                                   <option value="0" selected="selected"> No Especificar </option>
                                   <?PHP 
                                        foreach($tipo_empleados as $tipoemp){
                                            echo '<option value="'.trim($tipoemp['id']).'">'.trim($tipoemp['label']).'</option>';
                                        }
                                 ?>
                            </select>
                        </td>
                        
                        <td width="25"> <span class="sp12b">DNI</span></td>
                        <td width="20">:</td>
                        <td> 
                             <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-60-11" />
                        </td>
                        
                        <td width="60"> <span class="sp12b">Ap.Paterno</span></td>
                        <td width="10">:</td>
                        <td width="105"> 
                             <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-80-11" />

                        </td>
                        <td width="100"> <span class="sp12b">Apellido Materno</span></td>
                        <td width="10">:</td>
                        <td width="105"> 
                             <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-80-11" />

                        </td>
                          <td width="100"> <span class="sp12b">Nombres</span></td>
                        <td width="10">:</td>
                        <td width="105"> 
                             <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-80-11" />

                        </td>

                    </tr>


                 </table> 

            </div>  
-->

            
               <div id="dvapersonalregistrado_data"> </div>    
               <div id="dvapersonalregistrado_table"></div>
               <div class="table_toolbar" align="left"> 

                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Visualizar Información</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                   Persona.Ui.btn_tblinfoper_click(this,evt);
                            </script>
                   </button>

                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10"> Ver pagos</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                  
                                   var codigo = '';      
                                           
                                   for(var i in Persona.Ui.Grids.trabajadores.selection){
                                         codigo = i;
                                   }

                                   
                                   if(codigo != '')      
                                   {    
                                    
                                      Planillas._V.boletas_individuales.load({'trabajador_key' : codigo});
                                   }
                                   else{
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