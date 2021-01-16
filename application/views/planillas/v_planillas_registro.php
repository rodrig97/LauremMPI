<div class="window_container" style="height: 100%">

    <div id="dvViewName" class="dv_view_name">
       
            <table class="_tablepadding2" border="0">

                  <tr> 
                      <td> 
                           <?PHP 
                                     $this->resources->getImage('notebook_search.png',array('width' => '22', 'height' => '22'));
                                 ?>
                      </td>

                    <td>
                   Registro de planillas
                      </td>
                  </tr>
              </table>
    </div>

    <div class="dv_containerinto_window">
    
            <div> 
                    <div  data-dojo-type="dijit.form.DropDownButton" >

                        <span class="sp12b">
                              <?PHP 
                                $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                             ?>

                                 Busqueda Personalizada
                        </span>
                        <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el registro de trabajadores"'>

                            <div class="dv_formu_find_tt">
                                  <form id="form_registroplanilla_consulta"  data-dojo-type="dijit.form.Form">   
                                        <table class="_tablepadding4" style="width:100%">
                                              <tr class="row_form" >
                                                      <td colspan="3"> <span class="sp12b">Parametros de busqueda</span></td>
                                              </tr>
                                               <tr class="row_form" >
                                                   <td> <span class="sp12b">Codigo</span></td>
                                                   <td> <span class="sp12b">: </span></td>
                                                   <td>  
                                                       <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:40, name:'codigo'" class="formelement-100-11"/>
                                                   </td>
                                              </tr>
                                               <tr class="row_form" >
                                                   <td> <span class="sp12b">Siaf</span></td>
                                                   <td> <span class="sp12b">: </span></td>
                                                   <td>  
                                                       <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:40, name:'siaf'" class="formelement-100-11"/>
                                                   </td>
                                              </tr>
                                              <tr  class="row_form"> 
                                                 <td width="165">
                                                     <span class="sp12b">
                                                     Año 
                                                     </span>
                                                 </td>
                                                 <td width="5" width="10">
                                                     <span class="sp12b">: </span>
                                                 </td>
                                                      
                                                 <td width="330">

                                                 <?PHP 

                                                     if($modo == REGISTROPLANILLAS_DECLARARSUNAT )
                                                     {

                                                        echo "<span class='sp11b'>".$params['anio']."</span> 

                                                              <input type='hidden' value='".$params['anio']."' name='anio' />
                                                            ";
 
                                                     }
                                                     else
                                                     {
 
                                                   ?>

                                                      <select id="selplani_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:0px; font-size:11px; width: 80px;">
                                                      <!--    <option value="2012">2012</option>  -->

                                                              

                                                              <?PHP 
                                                                 foreach ($anios as $anio)
                                                                 {
                                                                   # code...
                                                                   echo '<option value="'.$anio['ano_eje'].'" ';

                                                                   if($anio['ano_eje']== date('Y'))
                                                                   {
                                                                      echo ' selected="selected" ';
                                                                   }

                                                                   echo ' >'.$anio['ano_eje'].'</option>';
                                                                 }
                                                              ?>
                                                     </select>

                                                     <?PHP 
                                                        }
                                                     ?>
                                                 </td>
                                             </tr>
                                             <tr class="row_form" >
                                                   <td> <span class="sp12b">Mes</span></td>
                                                   <td> <span class="sp12b">: </span></td>
                                                    <td>  
                                                      <?PHP 

                                                          if($modo == REGISTROPLANILLAS_DECLARARSUNAT )
                                                          {

                                                             echo "<span class='sp11b'>".$params['mes_label']."</span> 

                                                                   <input type='hidden' value='".$params['mes']."' name='mes' />
                                                                 ";
                                                      
                                                          }
                                                          else
                                                          {
                                                      
                                                        ?>
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'mes'"  class="formelement-100-11" style="width:120px;">
                                                           <option value="0" selected> No Especificar </option>
                                                           <option value="01"> Enero</option>
                                                           <option value="02"> Febrero</option>
                                                           <option value="03"> Marzo</option>
                                                           <option value="04"> Abril</option>
                                                           <option value="05"> Mayo</option>
                                                           <option value="06"> Junio</option>
                                                           <option value="07"> Julio</option>
                                                           <option value="08"> Agosto</option>
                                                           <option value="09"> Septiembre</option>
                                                           <option value="10"> Octubre</option>
                                                           <option value="11"> Noviembre</option>
                                                           <option value="12"> Diciembre</option>
                                                          
                                                       </select>

                                                       <?PHP 
                                                          }
                                                       ?>
                                                   </td>
                                              </tr>

                                              <tr class="row_form" >
                                                    <td> <span class="sp12b">Periodo</span></td>
                                                    <td> <span class="sp12b">: </span></td>
                                                     <td>  
                                                        <input type="text" dojoType="dijit.form.TextBox" 
                                                          data-dojo-props='name:"semana" ' class="formelement-50-11"  />
                                                        
                                                     </td>
                                               </tr>

                                              <tr class="row_form" >
                                                  
                                                   <td width="130"> <span class="sp12b">Régimen</span></td>
                                                   <td width="10"> <span class="sp12b">: </span></td>
                                                   <td width="300">  
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'tipoplanilla'" class="formelement-150-11" style="width:180px;">
                                                         
                                                           <option value="0" selected> No Especificar </option>
                                                             <?PHP
                                                               foreach($tipos as $tipo){
                                                                    echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                                               }
                                                             ?>
                                                       </select>
                                                   </td>
                                              </tr>
                                              <tr class="row_form" >
                                                   <td> <span class="sp12b">Estado</span></td>
                                                   <td> <span class="sp12b">: </span></td>
                                                   <td>  
                                                      <?PHP 

                                                          if($modo == REGISTROPLANILLAS_DECLARARSUNAT )
                                                          {

                                                             echo "<span class='sp11b'>".$params['estadoplanilla_label']."</span> 

                                                                   <input type='hidden' value='".$params['estadoplanilla']."' name='estado' />
                                                                 ";
                                                      
                                                          }
                                                          else
                                                          {
                                                      
                                                        ?>



                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'estado'"  class="formelement-80-11"  style="width:120px;"> 
                                                              
                                                               <option value="0" selected> No Especificar </option>
                                                              <?PHP 
                                                                 foreach($estados as $est){
                                                              ?> 
                                                                <option value="<?PHP echo trim($est['plaes_id']); ?>"  <?PHP if($est['plaes_id'] == ESTADOPLANILLA_ELABORADA) echo ' selected="selected" '; ?>  > <?PHP echo trim($est['plaes_nombre']); ?></option>
                                                              <?PHP } ?>
                                                       </select> 

                                                       <?PHP 
                                                          }
                                                       ?>
                                                   </td>
                                              </tr>
                                              <tr class="row_form" >
                                                   <td> <span class="sp12b">Tarea Presupuestal</span></td>
                                                   <td> <span class="sp12b">: </span></td>
                                                   <td>  
                                                         <select  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:0px; font-size:11px; width: 180px;">
                                                            <option value="0" selected> No Especificar </option>
                                                               <?PHP
                                                               foreach($tareas as $tarea)
                                                               {
                                                                    echo "<option value='".trim($tarea['cod_tarea'])."'>(".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
                                                               }
                                                             ?>
                                                       </select>
                                                   </td>
                                              </tr>
                                              <tr class="row_form" >
                                                   <td> <span class="sp12b">Descripcion</span></td>
                                                   <td> <span class="sp12b">: </span></td>
                                                   <td>  
                                                       <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:40, name:'descripcion'" class="formelement-250-11"/>
                                                   </td>
                                              </tr>
                                              
                                              <tr>
                                                  <td colspan="3" align="center"> 
                                                         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                              <?PHP 
                                                                 $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                                              ?>
                                                                <script type="dojo/method" event="onClick" args="evt">
                                                                        Planillas.Ui.btn_filtrar_registroplanilla(this,evt);
                                                                </script>
                                                                <label class="sp11">
                                                                    Realizar Busqueda
                                                                </label>
                                                        </button>
                                                  </td> 
                                              </tr> 
                                                
                                        </table>
                                  </form>
                              </div>
                        </div>
                    </div> 

            </div>
            <br/>
            <div id="dvplaregistro_table" >
            </div>

             <div class="table_toolbar" align="left">

                <?PHP 

                if($modo == REGISTROPLANILLAS_DECLARARSUNAT )
                {

                ?>

                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                         <?PHP 
                            $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                         ?>
                            <label class="lbl10">Seleccionar</label>
                             <script type="dojo/method" event="onClick" args="evt">
                                    
                                    Impuestos.Ui.btn_sunat_seleccionarplanilla(this,evt);
                                 
                           </script>
                  </button>

                <?PHP 

                }
                else
                {
                  ?> 
 
                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Visualizar</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                   
                                   Planillas.Ui.btn_visualizarplanilla_click(this,evt);
                                  
                            </script>
                   </button>

                <?PHP
                }
                ?>
             </div>

    </div>
    
</div>