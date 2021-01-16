 
<div class="window_container">

    <input type="hidden" id="hdplanilladetalle_plakey" value="<?PHP echo trim($plani_info['pla_key']); ?>" />
    <div class="dv_busqueda_personalizada">
         <table class="_tablepadding2">
             <tr>
                 <td width="50"> <span class="sp11b"> Planilla </span> </td>
                 <td width="10"> <span class="sp11b"> : </span> </td>
                 <td width="80"> <span class="sp11">  <?PHP echo trim($plani_info['pla_codigo']);    ?> </span>  </td>
                 <td width="90"> <span class="sp11b"> Régimen </span> </td>
                 <td width="10"> <span class="sp11b"> : </span> </td>
                 <td width="400"> <span class="sp11"> <?PHP echo  trim($plani_info['tipo']);   ?> con </span> <span id="spcantemppla" class="sp11b" ><?PHP echo (trim($plani_info['num_emps']) == '') ? '0' : trim($plani_info['num_emps']);  ?></span> <span class="sp11">  trabajadores agregados </span> </td>
             </tr>
         </table>    
    </div>
    
 <div class="dvmb10"> 

    <div data-dojo-type="dijit.form.DropDownButton" >

             <span class="sp12b">
                  <?PHP 
                       $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                   ?>

                    Parametros de Busqueda

             </span>
            <div id="tooltipDlg" data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el registro de trabajadores"'>

                <div class="dv_formu_find_tt">
                      <form id="form_busqueda_trabajadores"  data-dojo-type="dijit.form.Form">   


                            <?PHP 
                                if($plani_info['pla_fecfin'] != '')
                                {

                            ?>
                            <input type="hidden" name="fechalimite" value="<?PHP echo $plani_info['pla_fecfin']; ?>" /> 

                            <?PHP 
                                }
                            ?>

                            <table class="_tablepadding2" style="width:100%">
                                  <tr height="30" class="row_form" >
                                          <td colspan="7"> <span class="sp12b">Parametros de busqueda</span></td>
                                  </tr>

                                  <tr height="30" class="row_form" >
                                          <td width="110"> <span class="sp12b"> Régimen </span></td>
                                          <td width="20">:</td>
                                          <td colspan="7"> 
                                               <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"situlaboral" ' class="formelement-35-12" style="width:180px;">
                                                      
                                                     <?PHP 
                                                       foreach($tipo_empleados as $tipoemp){
                                                            if(trim($tipoemp['id']) == $plani_info['plati_id']) echo '<option value="'.trim($tipoemp['id']).'" selected >'.trim($tipoemp['label']).'</option>';
                                                        }
                                                     ?>
                                              </select>
                                          </td>
                                     </tr>
                                 <tr height="30"  class="row_form"> 
                                      <td> <span class="sp12b">Dependencia</span></td>
                                      <td>:</td>
                                       <td colspan="7"> 
                                          <select data-dojo-type="dijit.form.FilteringSelect" data-dojo-props=' name:"dependencia",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   ' style="width: 300px; font-size:11px;">
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
                                                   <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"cargo" ' style="width: 150px; font-size:12px;" style="width:180px;">
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
                                   <td> <span class="sp12b">Vigente</span></td>
                                    <td>
                                        : 
                                    </td>
                                    <td colspan="7"> 
                                          <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"vigente" ' style="width: 100px; font-size:11px;">
                                                                   <option value="1" selected="selected">  Si </option>
                                                                   <option value="0">  No </option>
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

                                                           var sel_ops = ['adsel_tienecuenta', 'adsel_banco', 'adsel_tipopension', 'adsel_afp', 
                                                                                          'adsel_modoafp', 'adsel_tarea', 'adsel_fuente', 'adsel_grupo' ];

                                                                            for(x in sel_ops)
                                                                            {
                                                                               dijit.byId(sel_ops[x]).set('value', '0');
                                                                            }

                                                              Planillas.Ui.btn_filtrardetalle_click(this,evt);
                                                    </script>
                                           </button>

                                    </td>
                                 </tr>

                            </table>
                      </form>
                  </div>
 
            </div>
    </div>

    
               <div data-dojo-type="dijit.form.DropDownButton" >
                      
                         <span class="sp12b">
                              <?PHP 
                         $this->resources->getImage('search_add.png',array('width' => '14', 'height' => '14'));
                            ?>
                             
                                 Otros parámetros 
                         
                         </span>
                        <div  data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el detalle de la planilla"'>

                            <div class="dv_formu_find_tt">

                                  <form id="form_busqueda_trabajadores_ops"  data-dojo-type="dijit.form.Form">   
                                        
                                      <table class="_tablepadding4" width="100%">
                           
                                              <tr class="row_form">
                                                  <td> <span class="sp12b">Cuenta Bancaria</span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      
                                                      <select id="adsel_tienecuenta" name="tienecuenta"   data-dojo-type="dijit.form.Select" data-dojo-props='name:"tienecuenta"'  style="width:100px; font-size:11px;" >
                                                           
                                                          <option value="0"> No especificar</option>
                                                          <option value="1">Si</option>
                                                          <option value="2">No</option>
                                                       </select>
                                                  </td>
                                                 
                                                  <td colspan="2"> 

                                                      <span class="sp12b">Banco: </span>     
                
                                                      <select id="adsel_banco" name="banco"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"banco"'  style="width:150px; font-size:11px;" >
                                                                    
                                                               <option value="0" selected="selected"> No especificar </option>
                                                               <?PHP
                                                                       
                                                                    foreach($bancos as $banco)
                                                                    {
                                                                        
                                                                        echo '<option value="'.trim($banco['ebanco_id']).'" ';
                                                                        
                                                                        echo ' >'.trim($banco['ebanco_nombre']).'</option>';
                                                                       
                                                                    }
                                                                     
                                                               ?>
                                                                      
                                                        </select>

                                                  </td>
 
                                             </tr>
                                             <tr class="row_form">
                                                  <td> <span class="sp12b">Sistema de Pensiones</span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      <select id="adsel_tipopension" name="tipopension" data-dojo-type="dijit.form.Select" data-dojo-props='name:"tipopension"' style="width:100px; font-size:11px;" >
                                                             <option value="0"> No especificar</option>
                                                             <option value="1" > ONP</option>
                                                             <option value="2" > AFP</option>
                                                      </select> 

                                                  </td>
                                                
                                                  <td>

                                                       <span class="sp12b">Afp: </span>    
                                                       <select  id="adsel_afp" name="afp"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"afp"' class="formelement-50-12" style="width:120px; font-size:11px;" >
                                                           
                                                            <option value="0" selected="selected" >No especificar </option>             
                                                           <?PHP
                                                        
                                                             foreach($afps as $afp){

                                                                  echo '<option value="'.$afp['id'].'" ';

                                                                  echo ' >'.trim($afp['label']).'</option>';

                                                              }


                                                               ?>
                                                           
                                                                     
                                                       </select> 
                                                  </td>
                                                  <td>

                                                       <span class="sp12b">Modo: </span>   
                                                       <select id="adsel_modoafp" name="modoafp"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"modoafp"' style="width:100px; font-size:11px;" >
                                                             <option value="0"> No especificar</option>
                                                             <option value="1" >FLUJO</option>
                                                             <option value="2" > SALDO</option>
                                                        </select> 
                                                  </td>
 
                                             </tr>

                                             <tr class="row_form">
                                                  <td> <span class="sp12b">
												  <?PHP echo (trim($this->usuario['anio_ejecucion']) >= 2021) ? 'Meta Presupuestal' : 'Tarea Presupuestal'; ?>
												  </span></td>
                                                  <td>:</td>
                                                  <td colspan="3"> 
                                                     <select id="adsel_tarea" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:6px; font-size:11px; width: 180px;">
                                                          <option value="0"> No Especificar </option>
                                                          <option value="no"> SIN TAREA PRESUPUESTAL </option>
                                                          <option value="si"> CON TAREA PRESUPUESTAL </option>
                                                             <?PHP
                                                             foreach($tareas as $tarea){
																if (trim($tarea['ano_eje']) >= 2021) {
																   echo "<option value='".trim($tarea['cod_tarea'])."'>(".trim($tarea['sec_func']).') '.trim($tarea['nombre'])."</option>";
																} else {
																   echo "<option value='".trim($tarea['cod_tarea'])."'>(".trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
																}
                                                             }
                                                           ?>
                                                     </select>
                                                      
                                                  </td>
                                                  
 
                                             </tr>

                                              <tr class="row_form">
                                                  <td> <span class="sp12b"> Fuente de F.</span></td>
                                                  <td>:</td>
                                                  <td colspan="3"> 
                                                      <select id="adsel_fuente" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"fuente", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La fuente de financiamiento no esta registrada" ' style="margin-left:6px; font-size:11px; width: 180px;">
                                                          <option value="0"> No Especificar </option>
                                                          <option value="no"> SIN FUENTE DE FINANCIAMIENTO </option>
                                                          <option value="si"> CON FUENTE DE FINANCIAMIENTO </option>
                                                             <?PHP
                                                             foreach($fuentes as $fuente){
                                                                  echo "<option value='".trim($fuente['codigo'])."'>".trim($fuente['codigo'])." ".trim($fuente['nombre'])."</option>";
                                                             }
                                                           ?>
                                                     </select>
                                                      
                                                  </td>
                                                 
                                             </tr>


                                              <tr class="row_form">
                                                  <td> <span class="sp12b"> Del Grupo </span></td>
                                                  <td>:</td>
                                                  <td colspan="3"> 
                                                     <select id="adsel_grupo"  name="grupo"  dojoType="dijit.form.FilteringSelect" data-dojo-props='name:"grupo", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Grupo no registrado" '  style="margin-left:6px; font-size:11px; width: 180px;" > 
                                                        <option value="0" selected="true"> No Especificar</option>  
                                                        <?PHP 
                                                          foreach($grupos as $g)
                                                          {
                                                             
                                                               echo '<option value="'.trim($g['gremp_id']).'" > '.trim($g['gremp_nombre']).' </option>';
                                                          }
                                                        ?>
                                                     </select>  
                                                      
                                                  </td>
                                                 
                                             </tr>

                                           

                                      </table>

                                      
                                      <div style="margin: 10px 0px 0px 10px;"> 

                                         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                           <?PHP 
                                              $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                           ?>
                                            <script type="dojo/method" event="onClick" args="evt">
                                              
                                                 Planillas.Ui.Grids.buscar_trabajador.refresh();

                                            </script>
                                            <label class="sp11">
                                                            Realizar Busqueda                          
                                            </label>
                                         </button>

                                      </div>
                                  </form>
                              </div>


                        </div>
            </div>

</div>    
    
<div id="dv_planillaadddetalle_table"></div>

<div>
   <button  dojoType="dijit.form.Button" class="dojobtnfs_12"  > 
          <?PHP 
             $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
          ?>
             <label class="lbl10">Agregar a Planilla</label>
              <script type="dojo/method" event="onClick" args="evt">
                  //  Persona.Ui.btn_registrarcomserv_click(this,evt);
                  Planillas.Ui.btn_addempleado_planilla_click(this,evt);
            </script>
   </button>
    
</div>
    
</div>