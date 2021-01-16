<div id="dvViewName" class="dv_view_name">
    
     <table class="_tablepadding2" border="0">
         <tr> 
              <td> 
                   <?PHP 
                             $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                         ?>
              </td>
              <td>
                   Elaboracion de Planilla de Remuneraciones
              </td>
         </tr>
      </table>
</div>

<input type="hidden" id="hdviewplanilla_id" value="<?PHP echo trim($plani_info['pla_key']);?>" />
<input type="hidden" id="hdviewplanilla_tipo_id" value="<?PHP echo trim($plani_info['tipo_key']);?>" />
<input type="hidden" id="hdviewplanilla_tienecategorias" value="<?PHP echo trim($plani_info['pla_tiene_categoria']); ?>" />


<div id="viewplanilla_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="top" 
            data-dojo-props='region:"top", style:"height: 105px;"'>
          
    
         <div>
               
          <table class="_tablepadding2">
               <tr class="row_form"> 
                    
                     <td width="105"> 
                        <span class="sp12b">Régimen</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td width="150">  
                        <input id="hdidplanilla_actual" type="hidden" value="<?PHP echo trim($plani_info['pla_key']); ?>" />
                        <span class="sp12">
                        <?PHP 
                     //   var_dump($plani_info);
                         echo  trim($plani_info['tipo']);
                        ?>
                        </span>
                    </td>
                
                    <td width="120"> 
                        <span class="sp12b">Codigo de la Planilla</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td width="180">  
                        
                        <span class="sp12">
                        <?PHP 
                     //   var_dump($plani_info); 201301001r
                            echo  trim($plani_info['pla_codigo'])."<span class='sp11b'> (Estado: ".trim($plani_info['estado']).")</span>";
                        ?>
                        </span>
                    </td>
             
                    <td width="60"> 
                        <span class="sp12b"> Mes - Año</span>
                    </td>
                    <td width="10"> 
                        <span class="sp12b">:</span>
                    </td>
                    <td >  
                        
                        <span class="sp12">
                        <?PHP 
                            echo $plani_info['pla_mes']."/".$plani_info['pla_anio'];
                        ?>
                      

                        <?PHP 
                           if($plani_info['pla_fecini'] != '' && $plani_info['pla_fecfin'] != '')
                           {
                               echo 'Del '._get_date_pg($plani_info['pla_fecini']).' hasta el'._get_date_pg($plani_info['pla_fecfin']);
                           }
                        ?>
                        </span>

                        <span class="sp12b">
                            Periodo:
                        </span>
                        <span class="sp12">

                        <?PHP 
                            echo trim($plani_info['pla_semana']) != '' ? trim($plani_info['pla_semana']) : '---';
                        ?>

                        </span>
     
                    </td>
               </tr>
               <tr class="row_form"> 
                    <td> 
                        <span class="sp12b">Afectación</span>
                    </td>
                    <td> 
                        <span class="sp12b">:</span>
                    </td>
                    <td colspan="7" id="tdElaborarPlanilla_afectacion" style="cursor: pointer;">  
                        
                           <?PHP 
                            

                              if( $plani_info['pla_afectacion_presu'] == PLANILLA_AFECTACION_ESPECIFICADA )
                              { 

                                  if($plani_info['tarea_id'] != '')
                                  {

                                      echo '<span class="sp11b"> Tarea: </span> ';
                                      echo '<span class="sp11"> '.(trim($plani_info['tarea_codigo']).' '.substr(trim($plani_info['tarea_nombre']),0,50).'..' ).'</span>';
                                  }

 
                                  if($plani_info['fuente_id'] != '' && $plani_info['tipo_recurso'] != '' )
                                  {
                                      echo '<span class="sp11b"> Fuente F: </span> ';
                                      echo '<span class="sp11"> '.$plani_info['fuente_id'].' - '.$plani_info['tipo_recurso'].' ('.$plani_info['fuente_abrev'].') </span>';
          
                                  } 

                                  if($plani_info['clasificador_id'] != '' )
                                  {
                                      echo '<span class="sp11b"> Clasificador </span> ';
                                      echo '<span class="sp11"> '.(substr(trim($plani_info['clasificador']),0,25).'..' ).'</span>';
                                  
                                  } 

                              }
                              else
                              {

                                  echo ' <span class="sp12"> Especificada por cada trabajador de la planilla </span>';

                              }
                            ?>
                      
                    </td> 
               </tr>
               <tr class="row_form"> 
                    <td> 
                        <span class="sp12b">Descripcion/Obs</span>
                    </td>
                    <td> <span class="sp12b">:</span>  </td>
                    <td colspan="7" id="tdElaborarPlanilla_descripcion">
                        <span class="sp12">
                         <?PHP 
                            echo  (trim($plani_info['pla_descripcion']) != '') ? trim($plani_info['pla_descripcion']): ' -------';
                        ?>

                        </span>
                    </td>
               </tr>
               
          </table>
         </div>
         
         <div class="dv_busqueda_personalizada_pa2" style="width:<?PHP echo ( sizeof($importaciones_asistencia) > 0 ) ? '1200px;' : '1100px;' ?> margin:5px 0px 0px 0px;">
              <table class="_tablepadding2">
                  <tr> 
                  
                        <td> 
                              
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                        Planillas.Ui.btn_buscartrabajador_click(this,evt);
                                     
                                    </script>
                                     <label class="sp11">
                                          Buscar y agregar trabajadores
                                    </label>
                               </button>
                       </td>
                       <td> 
                              
                        <div  data-dojo-type="dijit.form.DropDownButton" >

                            <label class="sp11">    
                                  <?PHP 
                                    $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                 ?>

                                    Importar datos
                            </label>
                            <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Especificar fuente de importacion de datos"'>

                                  <table class="_tablepadding2">
                                       <tr> 
                                            <td> 
                                                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                      <?PHP 
                                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                                      ?>
                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                Planillas.Ui.btn_importacion_op(this,evt);
                                                        </script>
                                                        
                                                        <span class="sp11"> Trabajadores desde otra Planilla </span>
                                                        
                                                   </button>
                                            </td> 


                                         <!--    <?PHP if(trim($plani_info['plati_id']) == TIPOPLANILLA_CONSCIVIL){  ?>

                                             <td> 
                                                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                      <?PHP 
                                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                                      ?>
                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                console.log('From Tareo');
                                                                Planillas.Ui.btn_importacion_tareo(this,evt);
                                                        </script>

                                                        <span class="sp11">  Desde Tareo de Cons. Civil</span>
                                                         
                                                   </button>
                                            </td>     
                                            <?PHP } ?>     -->


                                           
                                             <td> 
                                                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                      <?PHP 
                                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                                      ?>
                                                        <script type="dojo/method" event="onClick" args="evt">
                                                               
                                                                  Planillas.Ui.btn_importacion_hoja(this,evt);
                                                        </script>
                                                        
                                                        <span class="sp11"> Desde Hoja de Asistencia </span>
                                                   </button>
                                            </td>    

                                           
                                       </tr>
                                  </table>


                            </div>
                      </div> 
                       
                       </td>
                       
                       <?PHP 
                       if( sizeof($importaciones_asistencia) > 0 )
                       {  

                       ?> 
                       
                       <td> 

                            <button id="btnelapla_hoja" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                              <?PHP 
                                 $this->resources->getImage('attachment.png',array('width' => '14', 'height' => '14'));
                              ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                        
                                     var data = {}

                                     data.planilla = dojo.byId('hdviewplanilla_id').value;

                                     Planillas._V.asistencias_importadas.load(data);

                                </script>

                                <label class="sp11">
                                      Ver importaciones
                                </label>
                           </button>
                           <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId: 'btnelapla_hoja', position:['above']">
                                  Visualizar registro de asistencias importadas a la planilla
                           </div>
                       </td>
 
                       <?PHP } ?>


                       <td>
                              <div  data-dojo-type="dijit.form.DropDownButton" >

                                  <label class="sp11">    
                                        <?PHP 
                                          $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                       ?>

                                          Eliminar
                                  </label>
                                  <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Especificar fuente de importacion de datos"'>

                                        <table class="_tablepadding2">
                                             <tr>   

                                                <td> 
                                                     <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                            <?PHP 
                                                               $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                                            ?>
                                                             
                                                              <span class="sp11"> Quitar todos los trabajadores  </span>
                                                              <script type="dojo/method" event="onClick" args="evt">
                                                                      Planillas.Ui.btn_quitartodos_detalle_click(this,evt);
                                                              </script>
                                                     </button>
                                                </td>
                            
                                                <td>
                                                     <button id="btn_plae_quitardetalle"  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                            <?PHP 
                                                               $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                                            ?> 

                                                              <span class="sp11"> Eliminar solo variables y conceptos </span>
                                                              <script type="dojo/method" event="onClick" args="evt">
                                                                      Planillas.Ui.btn_eliminar_all_conceptos(this,evt);
                                                              </script>
                            
                                                     </button>
                                                 </td> 
                                                   <td> 
                                                        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                            <?PHP 
                                                               $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                                            ?>
                                                              <script type="dojo/method" event="onClick" args="evt">
                                                                      Planillas.Ui.btn_eliminarplanilla_click(this,evt);
                                                                  
                                                              </script>
                                                          
                                                              <span class="sp11"> Eliminar Planilla </span>
                                                         </button>
                                                  </td>    

                                                        
                                             </tr>
                                        </table>


                                  </div>
                            </div> 

                       </td>


                         <?PHP 
                             if(MODULO_QUINTA_CATEGORIA && $plati_calcula_quinta == '1'){ 
                         ?>
                           <td width="150">
                             
                             <input id="hd_planilla_calcularquinta" type="checkbox" data-dojo-props="name:'calcular_quinta'" data-dojo-type="dijit.form.CheckBox" value="1" checked="checked" /> 

                             <span class="sp11">
                                 Calcular renta de Quinta
                             </span>
                             
                           </td>
                               
                       <?PHP 
                           }
                       ?>

                         <?PHP 
                             if(MODULO_CUARTA_CATEGORIA && $plati_calcula_cuarta == '1'){ 
                         ?>
                           <td width="150">
                             
                             <input id="hd_planilla_calcularcuarta" type="checkbox" data-dojo-props="name:'calcular_cuarta'" data-dojo-type="dijit.form.CheckBox" value="1" checked="checked" readOnly="true" /> 

                             <span class="sp11">
                                 Calcular renta de Cuarta
                             </span>
                             
                           </td>
                               
                       <?PHP 
                           }
                       ?>

                       <td> 
                              
                            <!--   <span class="sp11">  Verificar monto de pensiones: </span>  
                              <input type="checked" data-dojo-type="dijit.form.CheckBox" id="chkComprobarPensiones"  value='1' checked="checked" />
 -->
                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                <?PHP 
                                   $this->resources->getImage('process.png',array('width' => '14', 'height' => '14'));
                                ?>
                                  <script type="dojo/method" event="onClick" args="evt">
                                          Planillas.Ui.btn_procesarplanilla_click(this,evt);
                                      
                                  </script>
                                  <label class="sp11">
                                       Procesar Planilla
                                  </label>
                             </button>
                       </td>
                       
                       <td> 
                              <?PHP 
                                if(DEV_MODE){ 
                              ?>
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt"> 
                                        var data = {}
                                        data.codigo = dojo.byId('hdviewplanilla_id').value;
                                        Planillas.fn_load_planilla(data);
                                        
                                    </script>
                                    <label class="sp11">
                                          
                                    </label>
                               </button>
                               <?PHP 
                                  }
                               ?> 
                       </td>
                       
                    
                         <td> 
                            <button id="btn_plae_modificarvalor" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                            Conceptos.Ui.btn_modificar_detalle_all(this,evt);
                                    </script>
                                    <label class="sp11">
                                          Modificar variables y conceptos
                                    </label>
                               </button>
                                 <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId: 'btn_plae_modificarvalor', position:['above']">
                                   Con este boton usted puede modificar el valor de una variable <br/>
                                     o un concepto para todos los trabajadores de la planilla 
                               </div> 
                        </td> 

                  </tr>
                
              </table>
         </div>
         
      </div>
                   
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", splitter:true ' style ="width: 620px;"  >
            
            
            <input type="hidden" id="hdplanillaprocesada" value="0" />  

            


          <div style="margin:0px 0px 5px 0px;">
            <div data-dojo-type="dijit.form.DropDownButton" >
                      
                         <span class="sp12b">
                              <?PHP 
                         $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                             
                               Filtrar trabajadores
                         
                         </span>
                        <div  data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el detalle de la planilla"'>

                            <div class="dv_formu_find_tt">

                                  <form id="form_detalleplanilla_busqueda"  data-dojo-type="dijit.form.Form">   
                                        <table class="_tablepadding2" style="width:100%">
                                              <tr height="30" class="row_form" >
                                                      <td colspan="7"> <span class="sp12b">Parametros de busqueda</span></td>
                                              </tr>
 
                                             <tr height="30"  class="row_form"> 
                                                  <td> <span class="sp12b">Dependencia</span></td>
                                                  <td>:</td>
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
                                                  <td width="10">:</td>
                                                  <td width="105"> 
                                                      <input id="petxt_appaterno" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-100-11" />

                                                  </td>
                                         
                                                  <td width="100"> <span class="sp12b">Apellido Materno </span></td>
                                                  <td width="10">:</td>
                                                  <td width="105"> 
                                                      <input id="petxt_apmaterno" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'materno'" class="formelement-100-11" />

                                                  </td>
                                          
                                                  <td width="60"> <span class="sp12b">Nombres </span></td>
                                                  <td width="10">:</td>
                                                  <td width="105"> 
                                                      <input id="petxt_nombres" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'nombres'" class="formelement-100-11" />

                                                  </td>
                                             </tr>
                                               <tr height="30"  class="row_form"> 
                                                  <td> <span class="sp12b">DNI</span></td>
                                                  <td>:</td>
                                                   <td colspan="7"> 
                                                      <input id="petxt_dni" type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-80-11" />
                                                      <label class="lbl_submensaje"> (*) Al especificar este campo no se consideraran los demas en la busqueda</label>
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
                                                                
                                                              <script type="dojo/method" event="onClick" args="evt">
                                                                         
                                                                    var sel_ops = ['pesel_tienecuenta', 'pesel_banco', 'pesel_tipopension', 'pesel_afp', 'pesel_modoafp', 'pesel_tarea', 'pesel_fuente', 'pesel_concepto', 'pesel_grupo'];

                                                                    for(x in sel_ops)
                                                                    {
                                                                       dijit.byId(sel_ops[x]).set('value', '0');
                                                                    }

                                                                    Planillas.Ui.Grids.planillas_detalle.refresh();

                                                              </script>

                                                              <label class="lbl10">Realizar Busqueda</label>
                                                       
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

                                  <form id="form_detalleplanilla_busqueda_op"  data-dojo-type="dijit.form.Form">   
                                        
                                      <table class="_tablepadding4" width="100%">
                           
                                              <tr class="row_form">
                                                  <td> <span class="sp12b">Cuenta Bancaria</span></td>
                                                  <td>:</td>
                                                  <td> 
                                                      
                                                      <select id="pesel_tienecuenta" name="tienecuenta"   data-dojo-type="dijit.form.Select" data-dojo-props='name:"tienecuenta"'  style="width:100px; font-size:11px;" >
                                                           
                                                          <option value="0"> No especificar</option>
                                                          <option value="1">Si</option>
                                                          <option value="2">No</option>
                                                       </select>
                                                  </td>
                                                 
                                                  <td colspan="2"> 

                                                      <span class="sp12b">Banco: </span>     
                
                                                      <select id="pesel_banco" name="banco"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"banco"'  style="width:150px; font-size:11px;" >
                                                                    
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
                                                      <select id="pesel_tipopension" name="tipopension" data-dojo-type="dijit.form.Select" data-dojo-props='name:"tipopension"' style="width:100px; font-size:11px;" >
                                                             <option value="0"> No especificar</option>
                                                             <option value="1" > ONP</option>
                                                             <option value="2" > AFP</option>
                                                      </select> 

                                                  </td>
                                                
                                                  <td>

                                                       <span class="sp12b">Afp: </span>    
                                                       <select  id="pesel_afp" name="afp"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"afp"' class="formelement-50-12" style="width:120px; font-size:11px;" >
                                                           
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
                                                       <select id="pesel_modoafp" name="modoafp"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"modoafp"' style="width:100px; font-size:11px;" >
                                                             <option value="0"> No especificar</option>
                                                             <option value="1" >FLUJO</option>
                                                             <option value="2" > SALDO</option>
                                                        </select> 
                                                  </td>
 
                                             </tr>

                                             <tr class="row_form">
                                                  <td> <span class="sp12b"> Tarea Presupuestal</span></td>
                                                  <td>:</td>
                                                  <td colspan="3"> 
                                                     <select id="pesel_tarea" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:6px; font-size:11px; width: 180px;">
                                                          <option value="0"> No Especificar </option>
                                                          <option value="no"> SIN TAREA PRESUPUESTAL </option>
                                                          <option value="si"> CON TAREA PRESUPUESTAL </option>
                                                             <?PHP
                                                             foreach($tareas as $tarea){
                                                                  echo "<option value='".trim($tarea['cod_tarea'])."'>(".trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
                                                             }
                                                           ?>
                                                     </select>
                                                      
                                                  </td>
                                                  
 
                                             </tr>

                                              <tr class="row_form">
                                                  <td> <span class="sp12b"> Fuente de F.</span></td>
                                                  <td>:</td>
                                                  <td colspan="3"> 
                                                      <select id="pesel_fuente" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"fuente", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La fuente de financiamiento no esta registrada" ' style="margin-left:6px; font-size:11px; width: 180px;">
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
                                                  <td> <span class="sp12b"> Que tengan el concepto </span> </td>
                                                  <td>:</td>
                                                  <td colspan="3">   
                                                       
                                                          
                                                       <select id="pesel_concepto" data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"concepto", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "El concepto no esta registrado" ' style="margin-left:6px; font-size:11px; width: 180px;">
                                                          <option value="0"> No Especificar </option>

                                                             <?PHP
                                                             foreach($conceptos as $conc){
                                                                  echo "<option value='".trim($conc['conc_id'])."'>".trim($conc['conc_nombre'])." </option>";
                                                             }
                                                           ?>
                                                       </select>
                                                     
                                                  </td>
                                                 
                                             </tr>


                                              <tr class="row_form">
                                                  <td> <span class="sp12b"> Del Grupo </span></td>
                                                  <td>:</td>
                                                  <td colspan="3"> 
                                                     <select id="pesel_grupo"  name="grupo"  dojoType="dijit.form.FilteringSelect" data-dojo-props='name:"grupo", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Grupo no registrado" '  style="margin-left:6px; font-size:11px; width: 180px;" > 
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
                                              
                                                  Planillas.Ui.Grids.planillas_detalle.refresh();

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

              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                     <?PHP 
                        $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                     ?>
                       <label class="lbl10"> Ver todos </label>
                       <script type="dojo/method" event="onClick" args="evt">


                            var ops = ['pesel_dependencia', 'pesel_cargo', 'pesel_tienecuenta', 'pesel_banco', 'pesel_tipopension', 'pesel_afp', 'pesel_modoafp', 'pesel_tarea', 'pesel_fuente', 'pesel_concepto', 'pesel_grupo'];

                            for(x in ops)
                            {
                               dijit.byId(ops[x]).set('value', '0');
                            }

                            ops = ['petxt_appaterno', 'petxt_apmaterno', 'petxt_nombres', 'petxt_dni' ];

                            for(x in ops)
                            {
                               dijit.byId(ops[x]).set('value', '');
                            }    
                            
                            Planillas.Ui.Grids.planillas_detalle.refresh();  

                       </script>
              </button>

          </div>
 

            <div id="dvdetalle_planilla"></div> 
            
            <div  style="margin: 5px 0px 5px 2px; " align="left"> 
                
                
                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Visualizar detalle</label>
                            <script type="dojo/method" event="onClick" args="evt">
                                
                            </script>
                   </button>

                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                               $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                            ?>
                             <label class="lbl10">Actualizar afectación</label>
                            <script type="dojo/method" event="onClick" args="evt">
                                    Planillas.Ui.btn_update_afectacion_detalle_click(this,evt);
                            </script>
                   </button> 


                  <!--  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                               $this->resources->getImage('attachment.png',array('width' => '14', 'height' => '14'));
                            ?>
                             <label class="lbl10"> Ver asistencia </label>
                            <script type="dojo/method" event="onClick" args="evt">  

                                 alert('Lola');



                            </script>
                   </button>  
 -->
                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Quitar trabajador</label>
                            <script type="dojo/method" event="onClick" args="evt">
                                    Planillas.Ui.btn_quitar_detalle_click(this,evt);
                            </script>
                   </button>
        

                  


                   
                   <div data-dojo-type="dijit.Tooltip" data-dojo-props="connectId: 'btn_plae_quitardetalle', position:['above']">
                        Con este boton usted eliminara todos los conceptos y variables <br/> de todos los trabajdores de la planilla
                   </div>  

             </div>
             
        </div>
    
        <div id="dv_vipla_detalle"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            
           
        </div>
    <!--
    
        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"bottom"' style="padding:0px 0px 0px 0px"   >
               
                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Procesar Planilla</label>
                            <script type="dojo/method" event="onClick" args="evt">
                                   
                            </script>
               </button>
                
           
        </div>
    -->
 
    
</div>