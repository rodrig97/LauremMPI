<div id="dvViewName" class="dv_view_name">
   

    <table class="_tablepadding2" border="0">

        <tr> 
            <td> 
                 <?PHP 
                           $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                       ?>
            </td>

          <td>
               Generación de Reportes y Archivos de Exportación
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


        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", splitter:true ' style ="width: 620px;"  >
              


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
                                      <td><span class="sp11b">Periodo : </span></td>
                                      <td colspan="2">: 
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props='name:"semana" ' class="formelement-50-11"  />
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


                    <?PHP
                       
                  
                      if( $this->user->has_key('EXPORTAR_SUNAT') )
                      { 

                    ?>
                   
                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Sunat </span>" style="padding: 8px 4px 4px 4px;">
                          
                        <div data-dojo-type="dijit.form.Form"  id="form_reporte_SUNAT"> 

                              <table class="_tablepadding2">
                                  <tr>
                                       <td colspan="3"> 
                                           <span class="sp11b"> Se considerarán todas las planillas del mes especificado. </span>
                                       </td>

                                  </tr>

                                  <tr> 
                                       <td width="90"> <span class="sp11b"> Archivo/Reporte </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td> 
                                            <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"tiporeporte"'  class="formelement-300-11" style="width:350px;">
                                                       
                                                  <?PHP 
                                                       foreach($reportes['SUNAT'] as $reporte)
                                                       {
                                                  ?> 
                                                            <option value="<?PHP echo $reporte['rep_id'] ?>"> <?PHP echo $reporte['rep_nombre'] ?> </option> 
                                                 <?PHP 
                                                       }
                                                  ?> 

                                             </select>
 
                                       </td>
                                  </tr>
                              </table>

                         </div>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 

      
                                <input type="hidden" class="modoreporte" value="SUNAT" />
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                              Exporter.Ui.btn_preview(this,evt);        
                                    </script>
                                    <label class="sp11">
                                            Visualizar
                                    </label>  
                               </button>


                         </div>

                   </div>


                   <?PHP 

                     }
                   ?>


                   <?PHP
                   
                     if( $this->user->has_key('EXPORTAR_BANCOS') )
                     { 

                   ?>

                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Entidades Bancarias </span>" style="padding: 8px 4px 4px 4px;">
                          
                        <div data-dojo-type="dijit.form.Form" id="form_reporte_BANCO"> 

                              <table class="_tablepadding2">
                                  <tr> 
                                       <td width="90"> <span class="sp11b">Banco </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td> 
                                            <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"banco"'  class="formelement-150-11" style="width:150px;">
                                                      
                                                  <?PHP 
                                                       foreach($reportes['BANCOS'] as $reporte)
                                                       {
                                                  ?> 
                                                            <option value="<?PHP echo $reporte['ebanco_id'] ?>"> <?PHP echo $reporte['ebanco_nombre'] ?> </option> 
                                                   <?PHP 
                                                       }
                                                  ?> 

                                             </select>
 
                                       </td>
                                  </tr>
                              </table>

                         </div>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 
 
                               <input type="hidden" class="modoreporte" value="BANCO" />
                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                             Exporter.Ui.btn_preview(this,evt);       
                                    </script>
                                    <label class="sp11">
                                            Visualizar
                                    </label>  
                               </button>


                         </div>


                   </div>

                   <?PHP 

                     }
                   ?>



                   <?PHP
                   
                     if( $this->user->has_key('EXPORTAR_AFP') )
                     { 

                   ?>


                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> AFP </span>" style="padding: 8px 4px 4px 4px;">
                       

                           <div data-dojo-type="dijit.form.Form" id="form_reporte_AFP">  
                              <table class="_tablepadding2">
                                  <tr> 
                                       <td width="30"> <span class="sp11b">AFP </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td width="160"> 
                                            <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"afp" '  class="formelement-150-11" style="width:150px;">
                                                        
                                                      <option value="0"> No especificar </option>

                                                  <?PHP 
                                                       foreach($reportes['AFPS'] as $reporte)
                                                       {
                                                  ?> 
                                                            <option value="<?PHP echo $reporte['afp_id'] ?>"> <?PHP echo $reporte['afp_nombre'] ?> </option> 
                                                  <?PHP 
                                                       }
                                                  ?> 
                                            </select>
 
                                       </td>


                                       <td width="90"> <span class="sp11b"> Tipo de Gasto </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td> 
                                            <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"tipogasto" '  class="formelement-100-11" style="width:100px;">
                                                  
                                                  <option value="0"> No especificar  </option>
                                                  <option value="F"> Funcionamiento  </option>
                                                  <option value="I"> Inversiones     </option>
                                                 
                                            </select>
                                       
                                       </td>


                                  </tr>
                              </table>
                         </div>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 

                                <input type="hidden" class="modoreporte" value="AFP" />
                                
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                          Exporter.Ui.btn_preview(this,evt);      
                                    </script>
                                    <label class="sp11">
                                            Visualizar
                                    </label>  
                               </button>


                         </div>
                      
                  </div>

                  <?PHP 

                    }
                  ?>


                  <?PHP
                  
                    if( $this->user->has_key('EXPORTAR_REPORTE_SIAF') )
                    { 

                  ?>

                  <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> SIAF </span>" style="padding: 8px 4px 4px 4px;">
                       

                          <div data-dojo-type="dijit.form.Form" id="form_reporte_SIAF"> 
                              <table class="_tablepadding2">
                                  <tr> 
                                       <td width="35"> <span class="sp11b">  Reporte </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"tiporeporte"'  class="formelement-350-11" style="width:350px;">
                                                       
                                                  <?PHP 
                                                       foreach($reportes['SIAF'] as $reporte)
                                                       {
                                                  ?> 
                                                            <option value="<?PHP echo $reporte['rep_id'] ?>"> <?PHP echo $reporte['rep_nombre'] ?> </option> 
                                                  <?PHP 
                                                       }
                                                  ?> 

                                             </select>
                                          
                                       </td>
                                  </tr>
                              </table>
                         </div>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 

                                 <input type="hidden" class="modoreporte" value="SIAF" />
                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                             Exporter.Ui.btn_preview(this,evt);
                                    </script>
                                    <label class="sp11">
                                            Visualizar
                                    </label>  
                               </button>


                         </div>
                     
                  </div>

                  <?PHP 

                    }
                  ?>


                  <?PHP
                  
                    if( $this->user->has_key('EXPORTAR_DESCUENTOS') )
                    { 

                  ?>


                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Descuentos </span>" style="padding: 8px 4px 4px 4px;">
                       

                          <div data-dojo-type="dijit.form.Form" id="form_reporte_DESCUENTOS"> 
                              <table class="_tablepadding2">
                                  <tr> 
                                       <td width="90"> <span class="sp11b"> Reporte </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"tiporeporte"'  class="formelement-300-11" style="width:300px;">
                                                 
                                                <option value="0"> Generar resumen de descuentos por trabajador y fuente de financiamiento </option>

                                          </select>
                                          
                                       </td>
                                  </tr>
                              </table>
                         </div>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 

                                <input type="hidden" class="modoreporte" value="DESCUENTOS" />
                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt"> 

                                             dijit.byId('planilla_reporter_view').set('content', ''); 
                                             Exporter.Ui.btn_generar(this,evt);
                                    </script>
                                    <label class="sp11">
                                            Generar
                                    </label>  
                               </button>


                         </div>
                     
                  </div>


                  <?PHP 

                    }
                  ?>


                  <?PHP
                  
                    if( $this->user->has_key('EXPORTAR_IMPRESIONES') )
                    { 

                  ?>


                   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Impresiones </span>" style="padding: 8px 4px 4px 4px;">
 
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
                                                 <?PHP
                                                 
                                                   if( $this->user->has_key('EXPORTAR_IMPRESION_BOLETA') )
                                                   { 

                                                 ?>
                                                    <option value="1"> Boleta de pago </option>  
                                                 
                                                 <?PHP 
                                                    }
                                                 ?>

                                                 <?PHP
                                                 
                                                   if( $this->user->has_key('EXPORTAR_IMPRIMIR_RESUMEN') )
                                                   { 

                                                 ?>
                                                    <option value="2"> Resumen de Planilla </option>
                                                 
                                                 <?PHP 
                                                    }
                                                 ?>


                                                 <?PHP
                                                 
                                                   if( $this->user->has_key('EXPORTAR_IMPRIMIR_PLANILLA') )
                                                   { 

                                                 ?>
                                                        <option value="3"> Planilla de Remuneraciones </option> 
                                                 
                                                 <?PHP 
                                                    }
                                                 ?>

                                                  <option value="4"> Por número de SIAF </option> 
                                                 
                                               
                                           </select>   
                                        </td>
                                    </tr> 

                                    <?PHP
                                    
                                      if( $this->user->has_key('EXPORTAR_IMPRESION_BOLETA') )
                                      { 

                                    ?>

                                    <tr> 
                                        <td>
                                             <span class="sp11b">  Filtro  </span>
                                        </td>

                                        <td> 
                                             <span class="sp11b">  : </span>
                                        </td>

                                        <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"filtro"'  class="formelement-300-11" style="width:300px;">
                                                  <option value="0"> Ninguno </option>  
                                                  <option value="1"> Solo trabjadores sin cuenta bancaria </option>
                                           </select>   
                                        </td>
                                    </tr>

                                    <tr> 
                                        <td>
                                             <span class="sp11b">  Ordenar por </span>
                                        </td>

                                        <td> 
                                             <span class="sp11b">  : </span>
                                        </td>

                                        <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"ordenar"'  class="formelement-300-11" style="width:300px;">
                                                    
                                                  <option value="1"> Planilla, Nombre del Trabajador, Categoria  </option>  
                                                  <option value="2"> Nombre del Trabajador, Categoria  </option>  
                                           </select>   
                                        </td>
                                    </tr>

                                     <tr> 
                                        <td>
                                             <span class="sp11b">  # de trabajadores por hoja </span>
                                        </td>

                                        <td> 
                                             <span class="sp11b">  : </span>
                                        </td>

                                        <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"trabajadoresxhoja"'  class="formelement-50-11" style="width:50px;">
                                                    
                                                  <option value="1"> 1  </option>  
                                                  <option value="2"> 2 </option>  
                                           </select>   
                                        </td>
                                    </tr>

                                    <tr> 
                                        <td>
                                             <span class="sp11b">  # de copias por hoja </span>
                                        </td>

                                        <td>  
                                           <span class="sp11b">  : </span>
                                        </td>

                                        <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"copiasxhoja"'  class="formelement-50-11" style="width:50px;">
                                                    
                                                  <option value="1"> 1  </option>  
                                                  <option value="2"> 2 </option>  
                                           </select>   
                                        </td>
                                    </tr>

                                    <?PHP 
                                       }
                                    ?>


                              </table>
                            
                            </form>

                         </div>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 

                                <input type="hidden" class="modoreporte" value="IMPRESIONES" />
                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                             dijit.byId('planilla_reporter_view').set('content', ''); 

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


                         </div>
                     
                  </div>

                      <?PHP 

                    }
                  ?>



                  <?PHP
                  
                    if( $this->user->has_key('EXPORTAR_OTROS') )
                    { 

                  ?>

                       <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Otros </span>" style="padding: 8px 4px 4px 4px;">
                       

                         <div data-dojo-type="dijit.form.Form" id="form_reporte_OTROS"> 
                              <table class="_tablepadding2">
                                  <tr> 
                                       <td width="90"> <span class="sp11b"> Otros reportes </span> </td>
                                       <td width="10" align="left"> <span class="sp11b"> : </span> </td>

                                       <td> 
                                           <select  data-dojo-type="dijit.form.Select" data-dojo-props='name:"tiporeporte"'  class="formelement-300-11" style="width:300px;">
                                                   
                                               <?PHP 
                                                   foreach($reportes['OTROS'] as $reporte)
                                                   {
                                               ?> 
                                                       <option value="<?PHP echo $reporte['rep_id'] ?>"> <?PHP echo $reporte['rep_nombre'] ?> </option> 
                                               <?PHP 
                                                    }
                                               ?> 

                                          </select>
                                          
                                       </td>
                                  </tr>
                              </table>
                         </div>

 
                         <a href="http://10.0.0.7/sigerhu/exportar/ingresos_mensuales" target="_blank"> Ingresos Mensuales </a>

                         <div align="center" style="margin: 5px 0px 0px 0px;"> 

                                  <input type="hidden" class="modoreporte" value="OTROS" />
                                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                             Exporter.Ui.btn_preview(this,evt);
                                    </script>
                                    <label class="sp11">
                                            Visualizar
                                    </label>  
                               </button>


                         </div>
 
                     
                  </div>

                      <?PHP 

                    }
                  ?>


                   <div  dojoType="dijit.layout.ContentPane" 
                          title="<span class='titletabname'> Operaciones </span>" style="padding: 8px 4px 4px 4px;">
                    
                             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="" > 
                              <?PHP 
                                 $this->resources->getImage('credit_cart.png',array('width' => '14', 'height' => '14'));
                              ?>
                                <script type="dojo/method" event="onClick" args="evt">
                                      var planillas = '';
                                      var selection = Planillas.Ui.Grids.planillas_reporte_filtro.selection;
                                      
                                        
                                      for(var i in selection )
                                      {
                                          if(selection[i] === true)
                                          { 
                                            planillas +='_'+ i;
                                        }
                                      }

                                  

                                      Planillas._V.registrar_siaf.load({'planilla' : planillas, 'modo' : 'multiple'});


                                </script>
                                <label class="sp11">
                                        Actualizar número SIAF
                                </label>  
                           </button>

 
                    </div>


     
              </div>

             
        </div>
    
        <div id="planilla_reporter_view"    data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:5px 5px 5px 5px"   >
            
           
        </div>
   
    
</div>