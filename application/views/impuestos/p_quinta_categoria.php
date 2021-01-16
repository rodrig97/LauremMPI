<div id="dvViewName" class="dv_view_name">
   

     <table class="_tablepadding2" border="0">
         <tr> 
            <td> 
                <?PHP 
                 $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                 ?>
            </td>
            <td>
                 Quinta Categoria
            </td>
         </tr>
    
     </table>
</div>
 
<div id="impuestoquinta_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
    
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left" ' style ="width: 620px;"  >


              <div style="margin:5px 5px 5px 5px">

                  <div data-dojo-type="dijit.form.DropDownButton" >
                      
                         <span class="sp12b">
                              <?PHP 
                         $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                             
                            Parámetros de Búsqueda de trabajadores
                         </span>
                        <div  data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el detalle de la planilla"'>

                            <div class="dv_formu_find_tt">

                                  <form id="form_detalleplanilla_busqueda"  data-dojo-type="dijit.form.Form">   
                                    
                                     <table class="_tablepadding2" style="width:100%" border="0">
                                             <tr height="30" class="row_form" >
                                                  <td width="110"> <span class="sp12b"> Año </span></td>
                                                  <td width="20"> <span class="sp12b"> :</span> </td>
                                                  <td colspan="7"> 
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:0px; font-size:11px; width: 80px;">
                                                    
                                                               <?PHP 
                                                                  foreach ($anios as $anio)
                                                                  {
                                                                    # code...
                                                                     echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
                                                                  }
                                                               ?>
                                                       </select>
                                                  </td>
                                             </tr>

                                             <tr height="30" class="row_form" >
                                                  <td width="110"> <span class="sp12b"> Régimen </span></td>
                                                  <td width="20"> <span class="sp12b"> :</span> </td>
                                                  <td colspan="7"> 
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"situlaboral" ' class="formelement-35-12" style="width:180px;">
                                                              
                                                              <option value="0"> No especificar </option> 
                                                             <?PHP 

                                                               foreach($tipo_empleados as $tipoemp){
                                                                    echo '<option value="'.trim($tipoemp['id']).'" >'.trim($tipoemp['label']).'</option>';
                                                                }
                                                             ?>
                                                      </select>
                                                  </td>
                                             </tr>
                                              <tr height="30"  class="row_form"> 
                                                   <td> <span class="sp12b">Area</span></td>
                                                   <td> <span class="sp12b"> :</span> </td>
                                                    <td colspan="7"> 

                                                       <select id="pesel_dependencia" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props=' name:"dependencia", autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   ' style="width: 300px; font-size:12px;">
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
                                                   <td> : </td>
                                                   <td colspan="7"> 
                                                      <select id="pesel_cargo" data-dojo-type="dijit.form.Select" data-dojo-props=' name:"cargo" ' style="width: 150px; font-size:12px;">
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
                                                   <td width="10"> <span class="sp12b"> :</span> </td>
                                                   <td width="105"> 
                                                       <input id="petxt_appaterno" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-100-11" />

                                                   </td>
                                              
                                                   <td width="100"> <span class="sp12b">Apellido Materno </span></td>
                                                   <td width="10"> <span class="sp12b"> :</span> </td>
                                                   <td width="105"> 
                                                       <input id="petxt_apmaterno" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'materno'" class="formelement-100-11" />

                                                   </td>
                                              
                                                   <td width="60"> <span class="sp12b">Nombres </span></td>
                                                   <td width="10"> <span class="sp12b"> :</span> </td>
                                                   <td > 
                                                       <input id="petxt_nombres" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'nombres'" class="formelement-100-11" />

                                                   </td>
                                              </tr>
                                                <tr height="30"  class="row_form"> 
                                                   <td> <span class="sp12b">DNI</span></td>
                                                   <td> <span class="sp12b"> :</span> </td>
                                                    <td colspan="7"> 
                                                       <input id="petxt_dni" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-80-11" />
                                                       <label class="lbl_submensaje"> (*) Al especificar este campo no se consideraran los demas en la busqueda</label>
                                                   </td>
                                              </tr>

                                              <tr> 
                                                   <td> 
                                                       <span class="sp12b"> Monto pagado S./ </span>

                                                   </td>
                                                   <td> 
                                                       <span class="sp12b"> : </span>
                                                   </td>
                                                     <td colspan="7">   
                                                          
                                                   </td>
                                              </tr>

                                              <tr> 
                                                   <td> 
                                                       <span class="sp12b"> Filtro extra </span>

                                                   </td>
                                                   <td> 
                                                       <span class="sp12b"> : </span>
                                                   </td>
                                                   <td colspan="7"> 
                                                       <select data-dojo-type="dijit.form.Select" style="font-size:11px; width:250px;"> 
                                                           <option value="0"> Ninguno </option>
                                                           <option value="1"> Con descuento de quinta categoria </option>
                                                           <option value="2"> Con Monto mínimo (7UITS) alcanzado </option>
                                                           <option value="3"> Con descuento pendiente </option>
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
                                                                  <label class="lbl10"> Buscar</label>
                                                                   <script type="dojo/method" event="onClick" args="evt">
 
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
 

            <div id="impuestoquinta_filtrotrabajadores"> 
            </div> 

            <div  style="margin: 5px 5px 5px 5px">

                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                     <?PHP 
                        $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                      <label class="lbl10"> Visualizar</label>
                        <script type="dojo/method" event="onClick" args="evt">
             
                       </script>
                  </button>
                


                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                     <?PHP 
                        $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                      <label class="lbl10"> Generar Excel </label>
                        <script type="dojo/method" event="onClick" args="evt">
                 
                       </script>
                  </button>



             </div> 


        </div>
    
        <div id="impuestoquinta_result"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            

           
        </div>
 
 
    
</div>  