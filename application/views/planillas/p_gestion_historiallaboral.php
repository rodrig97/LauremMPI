<div id="dvViewName" class="dv_view_name">
    
     <table class="_tablepadding2" border="0">
         <tr> 
              <td> 
                   <?PHP 
                             $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                         ?>
              </td>
              <td>
                    Gestión de Historial Laboral
              </td>
         </tr>
      </table>
</div>



<div id="viewcontratos_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
  

      <div  dojoType="dijit.layout.ContentPane" data-dojo-props=' region:"center" ' >
               
         
         <div   dojoType="dijit.layout.TabContainer" attachParent="true" tabPosition="top" tabStrip="true" data-dojo-props=' region:"center" '>

                <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Explorar contratos </span>">
                      

                        <div class="dv_busqueda_personalizada">

                         

                            <table class="_tablepadding2" width="100%" border="0">
                               <tr>
                                    <td colspan="3">
                                            <div data-dojo-type="dijit.form.DropDownButton" >
                                                    
                                                        <span class="sp12b">
                                                             <?PHP 
                                                        $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                                    ?>
                                                            
                                                             Parámetros de Búsqueda
                                                        
                                                        </span>
                                                       <div id="tooltipDlg" data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el registro de trabajadores"'>
                                                          <form id="form_registrocontratos_consulta"  data-dojo-type="dijit.form.Form">   
                                                           <div class="dv_formu_find_tt" style="width:800px;">
                                                             
                                                                       <table class="_tablepadding2" style="width:100%" border="0">
                                                                             <tr height="30" class="row_form" >
                                                                                     <td colspan="7"> <span class="sp12b"> Más parámetros de busqueda</span></td>
                                                                             </tr>

                                                                             <tr height="30" class="row_form" >
                                                                                     <td width="110"> <span class="sp11b">Régimen </span></td>
                                                                                     <td width="5"> <span class="sp11b"> : </span> </td>
                                                                                     <td> 
                                                                                          <select id="selcertificados_regimen" data-dojo-type="dijit.form.Select" 
                                                                                                  data-dojo-props=' name:"regimen" ' 
                                                                                                  style="width:200px; font-size:11px;">
                                                                                                 
                                                                                                 <option value="0" selected="selected"> No Especificar </option>
                                                                                                <?PHP 
                                                                                                   foreach($tipo_empleados as $tipoemp){
                                                                                                       echo '<option value="'.trim($tipoemp['id']).'">'.trim($tipoemp['label']).'</option>';
                                                                                                   }
                                                                                                ?>
                                                                                         </select>
                                                                                     </td>

                                                                                      <td>
                                                                                           <span class="sp11b"> Grupo </span>
                                                                                      </td>
                                                                                      
                                                                                      <td> <span class="sp11b"> : </span> </td>

                                                                                     <td colspan="4">
                                                                                         <select data-dojo-type="dijit.form.FilteringSelect" 
                                                                                                  data-dojo-props=' name:"grupo",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "El grupo no existe"   ' 
                                                                                                  style="width: 200px; font-size:11px;">

                                                                                                 <option value="0" selected="selected"> No Especificar </option>
                                                                                             
                                                                                         </select>                                                                                         
                                                                                      </td> 
                                                                                </tr>
                                                                            <tr height="30"  class="row_form"> 
                                                                                 <td> <span class="sp11b">Dependencia</span></td>
                                                                                 <td> <span class="sp11b"> : </span> </td>
                                                                                  <td> 
                                                                                     <select data-dojo-type="dijit.form.FilteringSelect" 
                                                                                             data-dojo-props=' name:"dependencia",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   '
                                                                                             style="width: 300px; font-size:11px;">
                                                                                        
                                                                                             <option value="0" selected="selected"> No Especificar </option>
                                                                                          <?PHP 
                                                                                               foreach($dependencias as $depe){
                                                                                                      
                                                                                           ?>
                                                                                               <option  value="<?PHP echo trim($depe['area_id']);  ?>"> <?PHP echo trim($depe['area_nombre']); ?> </option>

                                                                                          <?PHP } ?>
                                                                                     </select> 

                                                                                 </td>
                                                                           
                                                                                 <td> <span class="sp11b">Cargo</span></td>
                                                                                 <td> <span class="sp11b"> : </span> </td>
                                                                                  <td> 
                                                                                      <select data-dojo-type="dijit.form.Select" 
                                                                                              data-dojo-props=' name:"cargo" ' 
                                                                                              style="width: 150px; font-size:11px;">
                                                                                      
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
                                                                                 <td> <span class="sp11b">DNI</span></td>
                                                                                 <td> <span class="sp11b"> : </span> </td>
                                                                                  <td colspan="4"> 
                                                                                     <input type="text" dojoType="dijit.form.TextBox" 
                                                                                            data-dojo-props="name:'dni'" class="formelement-80-11" />
                                                                                    
                                                                                 </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                            
                                                                                <td>
                                                                                    <span class="sp11b"> 
                                                                                        Estado del registro 
                                                                                    </span>

                                                                                </td>

                                                                                <td> <span class="sp11b"> : </span> </td>

                                                                                <td colspan="4">

                                                                                    <select id="selcaontratos_tipobusqueda_tipo"  
                                                                                            data-dojo-type="dijit.form.Select" 
                                                                                            data-dojo-props="name:'estado_tipo'"
                                                                                            style="width:80px; font-size:11px;" >
                                                                                         
                                                                                         <option  value="1"> Activo </option>
                                                                                         <option  value="2"> Cesado </option>
                                                                                         <option  value="3"> Ambos </option>
                                                                                    </select>

                                                                               
                                                                                    <select id="selcaontratos_tipobusqueda_modo"  data-dojo-type="dijit.form.Select" 
                                                                                                    data-dojo-props="name:'estado_modo'" style="width:150px; font-size:11px;" >
                                                                                         <option  value="1"> Con periodo vencido </option>
                                                                                         <option  value="2"> Que empieze </option>
                                                                                         <option  value="3"> Que termine </option>  
                                                                                         <option  value="0"> No especificar</option>  
                                                                                    </select>
                                                                            
                                                                                   <div id="dv_contratos_bs1" style="display:inline;">  <!-- ITEM 1 -->

                                                                                        <div id="dv_contratos_bs2" style="display:inline;">
                                                                            
                                                                                            <select id="selcontratos_fecha_sel1" 
                                                                                                    data-dojo-type="dijit.form.Select" 
                                                                                                    data-dojo-props="name:'estado_modo_fechaini_sel'" style="width:100px; font-size:11px;">
                                                                                                
                                                                                                 <option value="1"> Antes del   :</option>
                                                                                                 <option value="2"> Despues del :</option>
                                                                                                 <option value="3"> Entre el    :</option>
                                                                                                
                                                                                            </select>
                                                                            
                                                                                            <div id="estado_cal_fecha1" 
                                                                                                data-dojo-type="dijit.form.DateTextBox"
                                                                                                data-dojo-props='type:"text", name:"estado_modo_fechaini_fecha1", value:"",
                                                                                                                  constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                                                                 lang:"es",
                                                                                                                 required:true,
                                                                                                                 promptMessage:"mm/dd/yyyy",
                                                                                                                 invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' 
                                                                                                 class="formelement-80-11"
                                                                                                                 
                                                                                                 onChange="dijit.byId('estado_cal_fecha2').constraints.min = this.get('value'); dijit.byId('estado_cal_fecha2').set('value', this.get('value') );  "
                                                                                                             >
                                                                                               </div> 
                                                                                            
                                                                                            <div id="dv_contratos_bs3" style="display:inline;">

                                                                                                <span class="sp11b"> hasta el: </span>
                                                                                                
                                                                                                <div id="estado_cal_fecha2"  data-dojo-type="dijit.form.DateTextBox"
                                                                                                                 data-dojo-props='type:"text", name:"estado_modo_fechaini_fecha2", value:"",
                                                                                                                  constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                                                                 lang:"es",
                                                                                                                 required:true,
                                                                                                                 promptMessage:"mm/dd/yyyy",
                                                                                                                 invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
                                                                                                             </div> 

                                                                                            </div>
                                                                                            
                                                                                            <div id="dv_contratos_bs4" style="display:inline;">
                                                                            
                                                                                                <select id="selcontratos_yqterminen" data-dojo-type="dijit.form.Select" 
                                                                                                        data-dojo-props="name:'estado_yqterminen'" style="width:100px; font-size:11px;">
                                                                                                    
                                                                                                     <option value="1"> Y que termine</option>
                                                                                                     <option value="0"> ---------- </option>
                                                                            
                                                                                                </select>

                                                                                            </div>
                                                                                               
                                                                                        </div>

                                                                                        <div id="dv_contratos_bs5" style="display:inline;">
                                                                            

                                                                                            <select id="selcontratos_fecha_sel2" data-dojo-type="dijit.form.Select" 
                                                                                                    data-dojo-props="name:'estado_modo_fechahasta_sel'" style="width:100px; font-size:11px;">
                                                                                             
                                                                                                 <option value="1"> Antes del   :</option>
                                                                                                 <option value="2"> Despues del :</option>
                                                                                                 <option value="3"> Entre el    :</option>
                                                                                                 <option value="4"> Indefinidamente </option>
                                                                                                
                                                                                            </select>
                                                                            
                                                                                           <div id="dv_contratos_bs6" style="display:inline;">         
                                                                            
                                                                                                <div id="estado_cal_fecha3" data-dojo-type="dijit.form.DateTextBox"
                                                                                                             data-dojo-props='type:"text", name:"estado_modo_fechahasta_fecha1", value:"",
                                                                                                              constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                                                             lang:"es",
                                                                                                             required:true,
                                                                                                             promptMessage:"mm/dd/yyyy",
                                                                                                             invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                                                                                             
                                                                                                               onChange="dijit.byId('estado_cal_fecha4').constraints.min = this.get('value'); dijit.byId('estado_cal_fecha4').set('value', this.get('value') );  "
                                                                                                             >
                                                                                                         </div> 
                                                                                            
                                                                                                <div id="dv_contratos_bs7" style="display:inline;">

                                                                                                    <span class="sp11b"> hasta el: </span>
                                                                                                    
                                                                                                    <div id="estado_cal_fecha4"  data-dojo-type="dijit.form.DateTextBox"
                                                                                                                     data-dojo-props='type:"text", name:"estado_modo_fechahasta_fecha2", value:"",
                                                                                                                      constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                                                                                                     lang:"es",
                                                                                                                     required:true,
                                                                                                                     promptMessage:"mm/dd/yyyy",
                                                                                                                     invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
                                                                                                                 </div> 
                                                                                                </div>

                                                                                            </div>            
                                                                                        </div>


                                                                                    </div> <!--  FIN ITEM 1 -->


                                                                                </td>
                                                                            
                                                                            </tr>


                                                                            <tr>
                                                                                 <td>
                                                                                      <span class="sp11b"> Monto de contrato </span>
                                                                                 </td>
                                                                                 
                                                                                 <td> <span class="sp11b"> : </span> </td>

                                                                                <td>
                                                                            
                                                                                      <select  id="selmontocontrato_tipo" data-dojo-type="dijit.form.Select" 
                                                                                               data-dojo-props="name:'sel_montocontrato_comparar'" 
                                                                                               style="width:100px; font-size:11px;">
                                                                                           
                                                                                           <option value="0"> No especificar </option>
                                                                                           <option value="1"> Mayor que :</option>
                                                                                           <option value="2"> Menor que :</option> 
                                                                                           <option value="3"> Entre :</option> 
                                                                                      </select>
                                                                                      
                                                                                      <div id="dv_montocontrato_c1" style="display: inline;">

                                                                                          <input name="montocontrato1" 
                                                                                                 data-dojo-type="dijit.form.TextBox"
                                                                                                 data-dojo-props="name:'montocontrato1'" 
                                                                                                 style="font-size:11px; width:70px;" />

                                                                                      </div>

                                                                                      
                                                                                      <div id="dv_montocontrato_c2" style="display: none;">

                                                                                          <span class="sp11b"> y </span>
                                                                                          
                                                                                          <input name="montocontrato2" 
                                                                                                 data-dojo-type="dijit.form.TextBox"
                                                                                                 data-dojo-props="name:'montocontrato2'" 
                                                                                                 style="font-size:11px; width:70px;" />

                                                                                      </div>

                                                                            
                                                                                 </td> 
                                                         
                                                                                 <td width="70">
                                                                                      <span class="sp11b"> Sobre sus ingresos (S./) </span>
                                                                                 </td>
                                                                                 
                                                                                  <td> <span class="sp11b"> : </span> </td>

                                                                                  <td>
                                                                                      
                                                                                      <select  id="selcontrato_rem_modo" data-dojo-type="dijit.form.Select" 
                                                                                               data-dojo-props="name:'considerar_remuneracion'" 
                                                                                               style="width:130px; font-size:11px;">
                                                                                          
                                                                                           <option value="0"> No especificar </option>
                                                                                           <option value="1"> Sin ingresos desde </option>
                                                                                           <option value="2"> Con ingresos en  </option>
                                                                                       
                                                                                      </select>
                                                                                     
                                                                                      <select  id="selmontocontrato_ultimomes" 
                                                                                               data-dojo-type="dijit.form.Select" 
                                                                                               data-dojo-props="name:'mes_remuneracion'" 
                                                                                               style="width:100px; font-size:11px;">
                                                                                               
                                                                                                <option value="01">Enero</option> 
                                                                                                <option value="02">Febrero</option> 
                                                                                                <option value="03">Marzo</option> 
                                                                                                <option value="04">Abril</option> 
                                                                                                <option value="05">Mayo</option> 
                                                                                                <option value="06">Junio</option> 
                                                                                                <option value="07">Julio</option> 
                                                                                                <option value="08">Agosto</option> 
                                                                                                <option value="09">Septiembre</option> 
                                                                                                <option value="10">Octubre</option> 
                                                                                                <option value="11">Noviembre</option> 
                                                                                                <option value="12">Diciembre</option> 
                                                                                      </select>
                                                                                  
                                                                                 </td> 
                                                                            </tr>

                                                                       

                                                                            <tr>
                                                                                 <td>
                                                                                      <span class="sp11b"> Considerar </span>
                                                                                 </td>
                                                                                 
                                                                                 <td> <span class="sp11b"> : </span> </td>

                                                                                 <td colspan="4">
                                                                            
                                                                                      <select  id="selmontocontrato_considerar" 
                                                                                               data-dojo-type="dijit.form.Select" 
                                                                                               data-dojo-props="name:'considerar_registros'" 
                                                                                               style="width:250px; font-size:11px;">

                                                                                           <option value="1"> Solo el último registro del trabajador </option>
                                                                                           <option value="2"> Incluir tambien los registros historicos </option>
                                                                                      </select>
                                                                                      
                                                                                
                                                                                 </td> 
                                                                            </tr>
                                                                            
                                                                         
                                                                            <tr>
                                                                                <td colspan="6" align="center">

                                                                                      <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                                              <?PHP 
                                                                                                 $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                                                                              ?>
                                                                                                 <label class="lbl10">Realizar Busqueda</label>
                                                                                                  <script type="dojo/method" event="onClick" args="evt">
                                                                                                          
                                                                                                        var ok = dijit.byId('form_registrocontratos_consulta').validate();

                                                                                                        if( ok === true )
                                                                                                        {

                                                                                                           Planillas.Ui.Grids.contratos.refresh();
                                                                                                        }

                                                                                                </script> 
                                                                                       </button>
                                                                                </td> 
                                                                            </tr>
                                                                           

                                                                       </table>
                                                               
                                                             </div>


                                                       </form>
                                                       </div>

                                               </div> 

                                    </td>
                               </tr>    
                         

                         
                       

                            </table>  

                        </div>


                        <div>

                              <div id="table_contratos_view">  
                                   <!-- 
                                         #
                                         Trabajador 
                                         DNI
                                         Tipo
                                         Area
                                         Cargo
                                         Fecha de Inicio
                                         Fecha de termino
                                         Monto Contrato 
                                         Dias faltantes
                                         Vigente
                                         Cesado 
                                    --> 
                              </div>
    
                        </div>

                        <div style="margin:5px 5px 5px 5px;">   

                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                   <?PHP 
                                      $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                   ?>
                                     <script type="dojo/method" event="onClick" args="evt">
                                           var codigo_e = '';   
                                           var selection =  Planillas.Ui.Grids.contratos.selection;   
                                           
                                           for(var i in selection)
                                           {  
                                               if(selection[i] === true)
                                               {
                                                 codigo_e = i;
                                                 break;
                                               }
                                           
                                           }

                                           if(codigo_e != '')      
                                           {

                                              Persona._V.view_situacion_laboral.load({'codigo' : codigo_e});    
                                            }


                                     </script>
                                      <label class="sp11">
                                           Visualizar registro 
                                     </label>
                                </button>

                                
                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                   <?PHP 
                                      $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                   ?>
                                     <script type="dojo/method" event="onClick" args="evt">
                                            
                                          Historiallaboral.Ui.btn_view_cesar_masivo(this,evt);

                                     </script>
                                      <label class="sp11">
                                           Cesar Seleccionados
                                     </label>
                                </button>

                                 <button id="btncertificados_elaborar"  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="disabled: true"  > 
                                   <?PHP 
                                      $this->resources->getImage('note.png',array('width' => '14', 'height' => '14'));
                                   ?>
                                     <script type="dojo/method" event="onClick" args="evt">
                                              
                                       

                                            Historiallaboral.Ui.btn_elaborar_certificados(this,evt);
                                              
                                     </script>
                                      <label class="sp11">
                                           Elaborar certificados de trabajo 
                                     </label>
                                </button>


                                <div>

                                    <table class="_tablepadding2">
                                        <tr>
                                             <td> <span class="sp11" style="color:grey; "> Contrato activo  </span> </td>
                                             <td> <span class="sp11" style="color:#990000; "> Contrato activo vencido </span> </td>
                                             <td> <span class="sp11" style="color:#336699;" > Contrato cesado </span> </td>
                                        </tr>

                                    </table> 
                                </div>
                        </div>  


                </div>
              <!-- 
                <div dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Certificados de trabajo</span>" style="" >
                     
                </div> -->
          </div>
      

      </div>

 </div>
    
    
 
 <!--
     <div   dojoType="dijit.layout.TabContainer" attachParent="true" tabPosition="top" tabStrip="true" data-dojo-props=' region:"center" '>
            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Variables de calculo</span>">
                  
            </div>
          
            <div dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Conceptos remunerativos</span>" style="width:275px;" attachParent="true">
                 
            </div>
      </div>
 
 -->