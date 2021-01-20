 
<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline" ' style="width:980px; height: 430px; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
    
     <div  data-dojo-type="dijit.layout.ContentPane" 
           data-dojo-props='region:"left", style:"width: 360px;"' 
           style="padding:4px  4px 40px 4px;">
 
         <div style="padding: 0px 0px 4px 4px; color:#990000; "> 
            <span class="sp12b"> Registro de Hojas de Asistencia</span>
         </div>   

         <div  data-dojo-type="dijit.form.DropDownButton" style="margin:5px 5px 10px 5px;" >

             <span class="sp12b">
                   <?PHP 
                     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                  ?>

                      Busqueda Personalizada
             </span>
             <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda"'>

                 <div class="dv_formu_find_tt">
                       <form id="form_importarasistencia_hojas"  data-dojo-type="dijit.form.Form">   
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
                                       
                                        <td width="130"> <span class="sp12b">Tipo de trabajador</span></td>
                                        <td width="10"> <span class="sp12b">: </span></td>
                                        <td width="300">  
                                            <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'tipoplanilla'" class="formelement-150-11" style="width:200px;">
                                              
                                                <option value="<?PHP echo $planilla_info['plati_id']; ?>"  selected="selected" ><?PHP echo $planilla_info['tipo']; ?></option>
                                                
                                            </select>
                                        </td>
                                   </tr>


                                    <tr  class="row_form"> 
                                       <td width="165">
                                           <span class="sp12b">
                                           Año 
                                           </span>
                                       </td>
                                       <td width="5" width="10">
                                            <span class="sp12b"> : </span>
                                       </td>
                                            
                                       <td width="330">
                                            <select  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:0px; font-size:11px; width: 60px;">
                                             <?PHP 
                                                echo '<option value="'.$anio.'" >'.$anio.'</option>';
                                            
                                            ?>
                                           </select>
                                       </td>
                                   </tr>

                                  <tr class="row_form" >
                                        <td> <span class="sp12b">Mes </span></td>
                                        <td> <span class="sp12b">: </span></td>
                                         <td>  
                                             <select data-dojo-type="dijit.form.Select" data-dojo-props="name: 'mes'"  class="formelement-100-11" style="width:100px;">
                                                <option value="0"  selected="selected"  > No Especificar </option>
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

                                             
                                        </td>
                                   </tr>
                                    

                                   <tr  class="row_form"  id="tr_crpla_seltarea_row" > 
                                       <td>
                                           <span class="sp12b">
                                            Proyecto
                                           </span>
                                       </td>
                                      <td> <span class="sp12b">: </span></td>
                                            
                                       <td>
                                          
                                            <select  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="font-size:11px; width: 250px;">
                                                <option value="0"> No Especificar </option>
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

                                
                                   <tr class="row_form" >
                                        <td> <span class="sp12b">Descripcion</span></td>
                                        <td> <span class="sp12b">: </span></td>
                                        <td>  
                                            <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:40, name:'descripcion'" class="formelement-250-11"/>
                                        </td>
                                   </tr>


                                    <tr  class="row_form"> 
                                       <td width="165">
                                           <span class="sp12b">
                                             Mostrar Hojas
                                           </span>
                                       </td>
                                       <td width="5" width="10">
                                            <span class="sp12b"> : </span>
                                       </td>
                                            
                                       <td width="330">
                                            <select  data-dojo-type="dijit.form.Select" data-dojo-props='name: "mostrar", disabled:false' 
                                                     style="margin-left:0px; font-size:11px; width: 150px;">
                                                <option value="1" selected="selected"> Pendientes de importar </option>
                                                <option value="2"> Totalmente importadas </option>
                                                <option value="0"> Ver todas </option>
                                           </select>
                                       </td>
                                   </tr>
                                   
                                   <tr>
                                       <td colspan="3" align="center"> 
                                              <button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" > 
                                                   <?PHP 
                                                      $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                                   ?>
                                                     <script type="dojo/method" event="onClick" args="evt">
                                                               Asistencias.Ui.Grids.prewview_hojas_importacion.refresh();
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

        
         <textarea id="asistencias_tabla_estructura" style="display:none;"> <?PHP  echo json_encode($estruct); ?> </textarea>

         <div id="dv_importacion_hojaspreview" >

         </div>
          
          
          <div class="table_toolbar" align="left">
   
               <button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" > 
                      <?PHP 
                         $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                      ?>
                        <label class="lbl10">Seleccionar</label>
                        <script type="dojo/method" event="onClick" args="evt">
                              
                             /* var id_hoja_asis = event.rows[0].id;
                              Asistencias.Cache.view_hoja_preview_importacion = id_hoja_asis;
                              Asistencias.Cache.view_hoja_preview_importacion_pla =  dojo.byId('hdviewplanilla_id').value;*/
                            

                              var selection =  Asistencias.Ui.Grids.prewview_hojas_importacion.selection;   
                              var id_hoja_asis = '';

                              for(var i in selection)
                              { 

                                  if(selection[i] === true)
                                  {
                                    id_hoja_asis +='_'+ i;
                                  }
                                    
                              }
 
                              if(id_hoja_asis != '')      
                              {
                                   Asistencias.get_importacion_config(id_hoja_asis, dojo.byId('hdviewplanilla_id').value);
                              }
                              else
                              { 
                                  alert('Debe seleccionar un registro');
                              }

                        </script>
               </button>

               <button  data-dojo-type="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="disabled: false" > 
                             <?PHP 
                                $this->resources->getImage('window_search.png',array('width' => '14', 'height' => '14'));
                             ?>
                               <script type="dojo/method" event="onClick" args="evt">
                              /*       Asistencias.view_hoja(Asistencias.Cache.view_hoja_preview_importacion, dojo.byId('hdviewplanilla_id').value , 2);
                              */  
                                       var data = { from : 'importacion', view : ''}
                                       var selection =   Asistencias.Ui.Grids.prewview_hojas_importacion.selection;   

                                       for(var i in selection)
                                       { 
                                           if(selection[i] === true)
                                           { 
                                               data.view = i;
                                               break;
                                           }
                                             
                                       }
                                       
                                       if(data.view != '')      
                                       {
                                            Asistencias._V.visualizar_hoja.load(data);  
                                       }
                                       else
                                       { 
                                           alert('Debe seleccionar un registro');
                                       }

                                      


                               </script>

                               <label class="sp11">
                                     Visualizar Hoja 
                               </label>
               </button>
        
               <button   data-dojo-type="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="disabled: false"  > 
                      <?PHP 
                         $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                      ?>
                         <span class="sp11"> Devolver hoja </span>
                        <script type="dojo/method" event="onClick" args="evt">
                                    Planillas.Ui.btn_devolver_fop_hoja(this,evt);
                        </script>
               </button>

          </div>
         
     </div>
    
     <div id="dv_importarasis_preview"  data-dojo-type="dijit.layout.ContentPane" 
          data-dojo-props='region:"center", style:"width:1000px;"' 
          style=" padding:0px 0px 0px 0px; overflow: hidden; scroll:none; ">
               

            <div data-dojo-type="dijit.layout.BorderContainer" 
                 data-dojo-props='design:"headline" ' 
                 style="width:100%; height: 100%; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
                  
                 <div id="dv_importacion_asistencias_config_panel" data-dojo-type="dijit.layout.ContentPane" 
                      data-dojo-props='region:"top", style:"height: 50px;"' 
                      style="padding:4px  4px 40px 4px;">

                       

                 </div>

                 <div  id="dv_asistencias_importacion_detalle"
                        data-dojo-type="dijit.layout.ContentPane" 
                       data-dojo-props='region:"center" ' 
                       style="padding:4px 4px 4px 4px;">

                       
                        <div id="table_asistencias_resumenimportacion"  class="grid"></div>
                        
                        <div id="dv_asistencias_import_counter" style="padding: 3px 10px 3px 3px; "  align="right"> 
                            <span class="sp11b" id="spcounter_importacion"> 0 </span> <span class="sp11b"> registros encontrados para importar </span> 
                        </div>

                </div>

               <div  data-dojo-type="dijit.layout.ContentPane" 
                      splitter="true"  
                      data-dojo-props='region:"bottom", style:"height:40px;"' 
                      style=" padding:0px 0px 0px 0px; overflow: hidden; ">
               

                       <div style=" padding:3px 8px 3px 3px;" align="right">
               
                             <!--  <button id="btniha_visualizar" data-dojo-type="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="disabled: true" > 
                                            <?PHP 
                                               $this->resources->getImage('window_search.png',array('width' => '14', 'height' => '14'));
                                            ?>
                                              <script type="dojo/method" event="onClick" args="evt">
                                                    Asistencias.view_hoja(Asistencias.Cache.view_hoja_preview_importacion, dojo.byId('hdviewplanilla_id').value , 2);
                                               
                                              </script>

                                              <label class="sp11">
                                                    Visualizar Asistencia   
                                              </label>
                              </button>
                             -->
                        

                             <button id="btniha_importar"  data-dojo-type="dijit.form.Button" class="dojobtnfs_12"  data-dojo-props="disabled: true" > 
                                    <?PHP 
                                       $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                    ?>
                                      <span class="sp11">Importar </span>
                                      
                                      <script type="dojo/method" event="onClick" args="evt">
                                            Planillas.Ui.btn_importar_hojaasistencia(this,evt);
                                      </script>
                             </button>
                         </div>

               </div>


            </div>





     </div>
  
   
    
             
</div>
 