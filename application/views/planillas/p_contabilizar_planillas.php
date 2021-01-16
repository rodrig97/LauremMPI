<div id="dvViewName" class="dv_view_name">
   

    <table class="_tablepadding2" border="0">

        <tr> 
            <td> 
                 <?PHP 
                           $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                       ?>
            </td>

          <td>
               Contabilizar Planillas
            </td>
        </tr>
    </table>
</div>
 

<div id="planilla_reporter_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 <!--
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="top" 
            data-dojo-props='region:"top", style:"height: 105px;"'>
          
    
     
         
      </div>
     -->              


        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center", splitter:true ' style ="width: 620px;"  >
              


              <div class="dv_busqueda_personalizada_pa2"> 

                    <div  id ="form_reportes_planilla_filtro" data-dojo-type="dijit.form.Form">

                             <input type="hidden" value="<?PHP echo ESTADOPLANILLA_MINIMO_REPORTEADOR ?>" name="estado" />

                             <table class="_tablepadding2"> 
                                  <tr>
                                      <td width="50"><span class="sp11b">Planilla</span></td> 
                                      <td width="5"> <span class="sp11b">:</span> </td>
                                      <td width="100" > 
                                          <div style="margin-left:5px">
                                           <input type="input" name="codigo" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'codigo'" class="formelement-80-11" value="" />
                                          </div>
                                      </td>
                                      <td width="40"><span class="sp11b">Siaf :</span></td> 
                                      <td> 
                                          <div style="margin-left:5px">
                                           <input type="input" name="siaf" data-dojo-type="dijit.form.TextBox" data-dojo-props="name: 'siaf'" class="formelement-80-11" value="" />
                                          </div>
                                      </td>
                                      <td> </td>
                                      <td colspan="2"> 
                                       
                                       </td>
                                  </tr>

                                   <tr> 
                                       <td width="50"><span class="sp11b">Año :</span></td> 
                                       <td width="5"> <span class="sp11b">:</span> </td>
                                       <td width="60">  

                                              <select id="selplanireport_anio"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' style="margin-left:6px; font-size:11px; width: 80px;">
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
                                       <td width="40"><span class="sp11b">Mes:</span></td>
                                       <td width="92">   
                                             <select id="selplanireport_mes" data-dojo-type="dijit.form.Select" data-dojo-props='  name:"mes", disabled:false' style="margin-left:6px; width:85px; " class="formelement-80-11">
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
                                       <td width="73"><span class="sp11b">Régimen:</span></td> 
                                       <td width="60"> 
                                             <select id="selplanireport_plati"  data-dojo-type="dijit.form.Select" data-dojo-props='name:"tipoplanilla", disabled:false' style="margin-left:6px; width:180px; " class="formelement-100-11">
                                                    <option value="0" selected="true"> NO ESPECIFICAR </option>
                                                   <?PHP
                                                     foreach($tipos as $tipo){
                                                          echo "<option value='".trim($tipo['plati_key'])."'>".trim($tipo['plati_nombre'])."</option>";
                                                     }
                                                   ?>
                                             </select>
                                       </td> 
                                       <td> 
                                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                  <?PHP 
                                                     $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                                  ?>
                                                    <script type="dojo/method" event="onClick" args="evt">

                                                            Planillas.Ui.Grids.planillas_reporte_filtro.refresh();
                                                        
                                                    </script>
                                                    <label class="sp11">
                                                          Filtrar
                                                    </label>
                                               </button>
              
                                       </td> 
                                   </tr>
                             </table>
                         </div>

 
              </div> 


              <div> 
                 <div id="dv_reportes_filtroplanilla">

                 </div>
              </div>
            

            

              <div   dojoType="dijit.layout.TabContainer" attachParent="true"
                      tabPosition="top" tabStrip="true" data-dojo-props='' 
                      style=" height: 300px; margin:8px 0px 0px 0px; ">
 
 
  

                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Documentos </span>" style="padding: 8px 4px 4px 4px;">
 
                         <div data-dojo-type="dijit.form.Form" id="form_reporte_IMPRESIONES"> 
                            
                            <form id="frmexportar_impresiones" action="impresiones/imprimir" method="post" target="_blank"> 

                              <input id="hdfrmexportar_planillas" type="hidden" value="" name="planillas" />

                              <input id="hdimprimirsiaf" type="hidden" value="" name="siaf" />
                              <input id="hdimprimiranio" type="hidden" value="" name="anio" />

                              <table class="_tablepadding2">

                                    <tr> 
                                        <td>
                                             <span class="sp11b">  Documento  </span>
                                        </td>

                                        <td> 
                                             <span class="sp11b">  : </span>
                                        </td>

                                        <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"documento"'  class="formelement-180-11" style="width:180px;">
                                                  
                                                  <option value="9"> Resumen contable </option> 
                                                  <option value="2"> Resumen de Planilla </option>
                                                  <option value="3"> Planilla de Remuneraciones </option> 
                                                  <option value="4"> Por número de SIAF </option> 
                                                  
                                           </select>   
                                        </td>

                                        <td>
                                          

                                             <input type="hidden" class="modoreporte" value="IMPRESIONES" />
                                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="" > 
                                               <?PHP 
                                                  $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                               ?>
                                                 <script type="dojo/method" event="onClick" args="evt">
 

                                                           var planillas = '';
                                                           var selection = Planillas.Ui.Grids.planillas_reporte_filtro.selection;
                                                           
                                                           var data = dojo.formToObject('form_reportes_planilla_filtro');

                                                           dojo.byId('hdimprimirsiaf').value = data.siaf;
                                                           dojo.byId('hdimprimiranio').value = data.anio;

                                                           for(var i in selection )
                                                           {
                                                               if(selection[i] === true)
                                                               { 
                                                                 planillas +='_'+ i;
                                                             }
                                                           }

                                                         dojo.byId('hdfrmexportar_planillas').value =  planillas;
                                                         dojo.byId('frmexportar_impresiones').submit();

                                                 </script>
                                                 <label class="sp11">
                                                         Generar 
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
     
    
</div>