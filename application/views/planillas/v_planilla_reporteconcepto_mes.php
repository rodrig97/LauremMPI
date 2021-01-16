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
                        Reporte Mensualizado por trabajador.
                 </td>
              </tr>
          </table>
      </div>

      <div>

          <form dojoType="dijit.form.Form" id="form_planilla_reporteconcepto_mes"> 
              
                  <table class="_tablepadding4" >

                      <tr  class="row_form"> 
                         <td width="100">
                             <span class="sp12b">
                             Año
                             </span>
                         </td>
                         <td width="5" width="10">
                              <span class="sp12b"> : </span> 
                         </td>
                              
                         <td width="330">
                          
                                 <select id="selreporte_anio"  
                                 		 data-dojo-type="dijit.form.Select" 
                                 		 data-dojo-props='name: "anio", disabled:false' 
                                 		 style="margin-left:6px; font-size:11px; width: 80px;">
	                            
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
                            <span class="sp12b"> Régimen </span>
                        </td>
                        <td width="10">
                            <span class="sp12b"> : </span> 
                        </td>
                        
                        <td>
                            <select id="selreporte_regimen" 
                            	    data-dojo-type="dijit.form.Select" 
                                    data-dojo-props='name:"planillatipo", disabled:false' 
                                    style="margin-left:6px; font-size:12px; width: 200px;">

                                    <option value="0"> No Especificar </option>
                                  <?PHP
                                    foreach($tipos as $tipo)
                                    {
                                         echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                    }
                                  
                                  ?>
                            </select>
                        </td>
                    </tr> 

                   <tr  class="row_form"   > 
                       <td>
                           <span class="sp12b">
                            Tarea Presupuestal 
                           </span>
                       </td>
                       <td>
                           <span class="sp12b"> : </span> 
                       </td>
                            
                       <td>
                            <!-- <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:10"  class="formelement-50-11" style="margin-left:6px;"  />
                            -->
                            <select id="selreporte_tarea"  
                                    data-dojo-type="dijit.form.FilteringSelect" 
                                    data-dojo-props='name:"tarea", disabled:false, autoComplete:false, highlightMatch: "all",  
                                                     queryExpr:"*${0}*", invalidMessage: "La Tarea Presupuestal no esta registrada" ' 
                                    style="margin-left:6px; font-size:11px; width: 320px;">
                               
                                <option value="0"> No Especificar </option>
                                   <?PHP
                                   foreach($tareas as $tarea){
                                        echo "<option value='".trim($tarea['tarea_id'])."'>(".trim($tarea['ano_eje']).' - '.trim($tarea['sec_func']).'-'.trim($tarea['tarea_nro']).') '.trim($tarea['tarea_nombre'])."</option>";
                                   }
                                 ?>
                           </select>
                       </td>
                   </tr>
                    

 					<tr class="row_form" >
 					   <td> 
 					       <span class="sp12b"> Visualizar </span> 
 					   </td>
 					   <td> 
 					        <span class="sp12b"> : </span> 
 					   </td> 
 					   <td>
 					       <select id="selreporte_modo"  dojoType="dijit.form.Select"  data-dojo-props="name: 'modo'" 
 					              class="formelement-100-11" style="margin-left:6px; font-size:12px; width: 225px;"  /> 
 					          
 					           <option value="concepto" selected="true"> Concepto </option>
 					           <option value="grupo"> Grupo de conceptos </option>
 					           <option value="bruto"> Ingreso Bruto</option>
 					           <option value="neto" > Neto (Ingresos - Descuentos) </option>
 					           <option value="costo"> Costo (Ingresos + Aportaciones)</option>
 					       </select>  
 					   </td> 
 					</tr>
 

                    <tr id="rowreporte_concepto" class="row_form"> 
                        <td> 
                            <span class="sp12b"> Concepto </span>
                        </td>
                        <td>
                            <span class="sp12b"> : </span> 
                        </td>
                        
                        <td>
                           <div> 

                            	<select id="selreporte_tipoconcepto"  dojoType="dijit.form.Select"  data-dojo-props="name: 'tipoconcepto'" 
                            	       class="formelement-100-11" style="margin-left:6px; font-size:12px; width: 95px;"  /> 
                            	   
                            	    <option value="1" selected="true">Ingresos</option>
                            	    <option value="2" >Descuentos </option>
                            	    <option value="3" >Aportaciones </option>
                            	</select>  

                                <select id="selreporte_conceptos" class="seloperando" data-dojo-type="dijit.form.FilteringSelect"     
                                     data-dojo-props='  value: "id",
                                                        label : "nombre",  
                                                        store: Conceptos._M.store_conceptos_reporte, 
                                                        style : "width:200px; font-size:11px;",
                                                        autoComplete: false,
                                                        highlightMatch: "all",  
                                                        queryExpr:"*${0}*",
                                                        name : "concepto"

                                                        ' >
                                  

                                 </select>
                           </div>
                        </td>
                    </tr> 


                   <tr  id="rowreporte_grupo" class="row_form"> 
                       <td> 
                           <span class="sp12b"> Grupo </span>
                       </td>
                       <td>
                           <span class="sp12b"> : </span> 
                       </td>
                        <td>  
                           	
                           	<div style="margin-left:6px;">
	
	                            <select dojoType="dijit.form.FilteringSelect" class="formelement-150-11" data-dojo-props="name: 'grupo'" >
	                                <option value="0"> No especificar</option>
	                                <?PHP
	                                   foreach($grupos as $el){
	                                     echo " <option value='".$el['gvc_id']."'>".trim($el['gvc_nombre'])."</option> ";
	                                   }
	                                ?>
	                            </select> 

                        	</div>
                           
                       </td>
 				   </tr>

               </table>
          </form>
          

      </div>


      <div align="center" style="margin: 10px 0px 0px 0px;">


           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
             <?PHP 
                $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
             ?>
               <script type="dojo/method" event="onClick" args="evt">

                    var data = dojo.formToObject('form_planilla_reporteconcepto_mes');

                    data.generar = 'CONCEPTOS_MES';

                    if( data.modo == 'concepto' && data.concepto == '')
                    {
                        alert('Debe especificar un concepto');  
                    }
                    else
                    {
                        Exporter._M.generar.send(data);
                    }
                   
               </script>
               <label class="sp11">
                    Generar reporte 
               </label>
          </button>

      </div>
 
    
</div>