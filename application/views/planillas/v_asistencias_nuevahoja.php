<div class="window_container">
  
<div id="dvViewName" class="dv_view_name">
      
    <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
     Crear nueva hoja de asistencia
          </td>
      </tr>
  </table>
</div>



<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:800px; height: 250px;">
     
     <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props=' splitter: true, region:"left", style:"width: 420px; padding: 0px 0px 0px 0px;"' >
     
         <form dojoType="dijit.form.Form" id="formnuevahoja"> 
             
                 <table class="_tablepadding4" width="100%">
                    <tr  class="row_form"> 
                       <td width="100">
                           <span class="sp12b">
                           Año
                           </span>
                       </td>
                       <td width="5">
                           :
                       </td>
                            
                       <td>
                           
                           <select id="selasisni_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:6px; font-size:12px; width: 80px;">
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
                       </td>
                   </tr>
                   <tr class="row_form"> 
                       <td> 
                           <span class="sp12b"> Tipo de Trabajador</span>
                       </td>
                       <td>
                           :
                       </td>
                       
                       <td>
                           <select id="sel_crasis_tipoplanilla"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"planillatipo", disabled:false' style="margin-left:6px; font-size:12px; width: 200px;">
                                 <?PHP
                                   foreach($tipos as $tipo)
                                   {
                                        
                                        if($tipo['asistencia'] == '1')
                                        { 
                                           echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                        }
                                   }
                                 ?>
                           </select>
                       </td>
                   </tr> 
                    <!--
                   <tr class="row_form"> 
                       <td width="40"> 
                           <span class="sp12b"> Tarea Presupuestal </span>
                       </td>
                       <td>
                           :
                       </td>
                       
                       <td>
                           
                           <select id="sel_crpla_seltarea" name="tipo_select_tarea" data-dojo-type="dijit.form.Select" data-dojo-props='name:"tipo_select_tarea", disabled:false' style="margin-left:6px; font-size:12px; width: 120px;">
                               <option value="2" selected="true"> Usar la vinculada a cada empleado </option>
                               <option value="1"> Especificar una Tarea Presupuestal </option>
                            </select>
                       </td>
                   </tr> 
                  
                 
                   
                   <tr  class="row_form"  id="tr_crpla_seltarea_row" > 
                       <td>
                           <span class="sp12b">
                            Tarea
                           </span>
                       </td>
                       <td>
                           :
                       </td>
                            
                       <td>
                            <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10"  class="formelement-50-11" style="margin-left:6px;"  />
                            <select id="selnpla_tarea"  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:0px; font-size:11px; width: 180px;">
                                <option value="0"> No Especificar </option>
                                   <?PHP
                                   foreach($tareas as $tarea){
                                        echo "<option value='".trim($tarea['cod_tarea'])."'>(".trim($tarea['sec_func']).'-'.trim($tarea['tarea']).') '.trim($tarea['nombre'])."</option>";
                                   }
                                 ?>
                           </select>
                       </td>
                   </tr>
                   -->


                   
                   <tr  class="row_form"  id="tr_crpla_seltarea_row" > 
                       <td>
                           <span class="sp12b">
                            Proyecto
                           </span>
                       </td>
                       <td>
                           :
                       </td>
                            
                       <td>
                            <!-- <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10"  class="formelement-50-11" style="margin-left:6px;"  />
                            -->
                            <select  data-dojo-type="dijit.form.FilteringSelect" data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' style="margin-left:6px; font-size:11px; width: 250px;">
                                <option value="0"> No Especificar </option>
                                   <?PHP
                                   foreach($tareas as $tarea){
                                        echo "<option value='".trim($tarea['tarea_id'])."'> (".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea_nro']).') '.trim($tarea['tarea_nombre'])."</option>";
                                   }
                                 ?>
                           </select>
                       </td>
                   </tr>

                   
                    <tr  class="row_form" id="tr_crpla_intervalo_row"> 
                       <td><span class="sp12b">Intervalo de fechas</span></td>
                       <td>:</td>
                       <td> 
                           <span class="sp12b">Del: </span>
                           <div id="cal_crasis_desde" data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechadesde", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                            
                                              onChange="dijit.byId('cal_crasis_hasta').constraints.min = this.get('value'); dijit.byId('cal_crasis_hasta').set('value', this.get('value') );  "
                                            >
                                        </div> 
                           
                           <span class="sp12b"> Hasta: </span>
                           
                           <div id="cal_crasis_hasta"  data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechahasta", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
                                        </div> 
                       </td>
                   </tr>
                   <tr  class="row_form"> 
                       <td><span class="sp12b">Descripcion</span></td>
                       <td>:</td>
                       <td> 
                           <div dojoType="dijit.form.Textarea" data-dojo-props='name:"descripcion" ' class="formelement-200-11" style="margin:0px 0px 0px 6px;"></div> 
                       </td>
                   </tr>
                  
               </table>
             
         </form>
     
     </div>
    
    
     <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='  region:"center", style:"width: 360px; padding:0px 0px 0px 0px;"' >
         
         <div id="table_asistencias_preview">
         </div>
         
     </div>
    
</div>


<div align="center" style="margin: 20px 0px 0px 0px;">
     
    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
      <?PHP 
         $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
      ?>
        <script type="dojo/method" event="onClick" args="evt">

              Asistencias.Ui.btn_registrarhoja_click(this,evt);
            
        </script>
        <label class="sp12">
             Guardar y completar personal
        </label>
   </button>
    
</div>
    
</div>