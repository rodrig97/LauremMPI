
<?PHP 

   if($modo == 'importacion')
   {

?>
<div class="window_container"> 

<?PHP 
  } 
?>

<input type="hidden" id="hdviewasistencia_id" value="<?PHP echo trim($hoja_info['hoa_key']);?>" />
<input type="hidden" id="hdviewasistencia_tipo_id" value="<?PHP echo trim($hoja_info['plati_id']);?>" />
<input type="hidden" id="hdviewasistencia_estado_id" value="<?PHP echo trim($hoja_info['estado_id']);?>" />

<div id="viewasistencia_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
      
        <?PHP 

             $height =   '170px;';
           

          ?>
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
            region="top" 
            data-dojo-props='region:"top", style:" height: <?PHP echo $height; ?> "'>
  

         

                <div id="dvViewName" class="dv_view_name">
                    
                    <table  border="0">
                        <tr> 
                          <td> 
                              <?PHP 
                                  $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                              ?>
                          </td>
                          <td>
                             Elaborar hoja de asistencia
                         </td>
                        </tr>
                    </table>
       
                </div>

         

             <table class="_tablepadding4" border="0">
                   <tr>
                      <td width="35"> <span class="sp12b"> Codigo    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70"> <span class="sp12"> <?PHP echo  (trim($hoja_info['hoa_codigo']) == '' ) ? '------'  : trim($hoja_info['hoa_codigo']);  ?>   </span> </td>
                      <td colspan="3"> </td> 
                      <td width="94"> <span class="sp12b">Creada el</span> </td>
                      <td width="10" align="center">  <span class="sp12b"> :       </span> </td>
                      <td width="180" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechareg'])) . ' ' . substr($hoja_info['hoa_fechareg'], 11, 8); ?>    </span> </td>
                      <td colspan="3"> </td> 

                   </tr>   

                   <tr>
                      <td width="35"> <span class="sp12b"> Proyecto    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" colspan="7"> <span class="sp12"> <?PHP echo  (trim($hoja_info['proyecto']) == '' ) ? '------'  : trim($hoja_info['proyecto']);  ?>   </span> </td>
                      <td width="35"> <span class="sp12b"> Residente    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" > <span class="sp12"> <?PHP echo  (trim($hoja_info['residente']) == '' ) ? '------'  : trim($hoja_info['residente']);  ?>   </span> </td>
                        
                   </tr>   
 
                  <tr>
                      <td width="35"> <span class="sp12b"> Desde    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechaini'])); ?>   </span> </td>

                      <td width="35"> <span class="sp12b"> Hasta    </span> </td>
                      <td width="10" align="center">  <span class="sp12b"> :       </span> </td>
                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechafin'])); ?>    </span> </td>

                      <td width="94"> <span class="sp12b"> Tipo Trabajador    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="180"> <span class="sp12">  <?PHP echo trim($hoja_info['tipo_planilla']); ?>    </span> </td>

                     
                  
                      <td width="75"> <span class="sp12b"> Descripcion </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="250"> <span class="sp12">  <?PHP echo  (trim($hoja_info['hoa_descripcion']) == '' ) ? '-------------'  : trim($hoja_info['hoa_descripcion']); ?>    </span> </td>
  
                  </tr> 
                
             </table>

 

           <div class="dv_busqueda_personalizada_pa2" style="width:90%; margin:5px 0px 0px 0px;">


              <form dojoType="dijit.form.Form" id="form_asistencia_calendario_config"> 


                <input type="hidden" name="hoja" value="<?PHP echo trim($hoja_info['hoa_key']);?>" /> 

              <table class="_tablepadding2">
                  <tr> 
                     <!--   <td width="120">
                             <span class="sp12b"> Añadir Trabajador</span>
                       </td> 
                       <td width="10">:</td>
                        
                       <td> 
                               <div dojoType="dijit.form.TextBox" name="codigotrabajador" class="formelement-80-11"></div> 
                       </td>
                       <td> 
                              
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                       


                                    </script>
                                    <label class="sp12">
                                          
                                    </label>
                               </button>
                       </td> -->

                       <?PHP 
                           if( $this->user->has_key('ASISTENCIAS_ACCESOCOMPLETO_SOLOVER') == FALSE || SUPERACTIVO === TRUE)
                           {
                       ?> 

                        <td> 
                              
                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                        Asistencias.Ui.btn_buscartrabajador_click(this,evt);
                                     
                                    </script>
                                    <label class="sp11">
                                            Asignar trabajadores
                                    </label>
                               </button>
                       </td>

                       <?PHP 
                          }
                       ?> 
                    <!--    <td> 
                              
                        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                      <?PHP 
                                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                                      ?>
                                                        <script type="dojo/method" event="onClick" args="evt">
                                                                Planillas.Ui.btn_importacion_op(this,evt);
                                                        </script>
                                                        <label class="sp11">
                                                                  Trabajadores desde otra Hoja.
                                                        </label>
                                                   </button>
                           
                       </td> -->
                       
                     
                         <td> 
             
                                 <div  data-dojo-type="dijit.form.DropDownButton" >

                                     <label class="sp11">    
                                           <?PHP 
                                             $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                          ?>

                                            Otras opciones
                                     </label>
                                     <div data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Seleccione la operacion que desea realizar"'>

                                            <table class="_tablepadding2">
                                                 <tr> 
                                                    <td> 
                                                        
                                                        <?PHP  
                                                           if($this->user->has_key('ASISTENCIAS_ACCESOCOMPLETO_SOLOVER') == FALSE || SUPERACTIVO === TRUE)
                                                           {  

                                                           
                                                        ?>

                                                          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                              <?PHP 
                                                                 $this->resources->getImage('attachment.png',array('width' => '14', 'height' => '14'));
                                                              ?>
                                                                <script type="dojo/method" event="onClick" args="evt"> 

                                                                       var hoja = dojo.byId('hdviewasistencia_id').value;
                                                                      
                                                                       Asistencias._V.importacion_trabajadores.load({'hoja' : hoja});

                                                                </script>
                                                                <label class="sp11">
                                                                      Importar trabajadores  
                                                                </label>
                                                           </button>

                                                        <?PHP 
                                                          }
                                                        ?>
                                                    </td> 


                                                   
                                                          <td> 
                                                              
                                                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                  <?PHP 
                                                                     $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                                                  ?>
                                                                    <script type="dojo/method" event="onClick" args="evt">
                                                                           
                                                                          var data = {'view' : dojo.byId('hdviewasistencia_id').value, 'mode' : 'hoja_asistencia' }

                                                                          if(dojo.byId('form_asistencia_calendario_config') != null )
                                                                          {
                                                                              var config = dojo.formToObject('form_asistencia_calendario_config');
                                                                              
                                                                              for(x in config)
                                                                              {
                                                                                data[x] = config[x];
                                                                              } 

                                                                          }

                                                                          Impresiones._V.preview.load(data);

                                                                    </script>
                                                                    <label class="sp11">
                                                                         Imprimir hoja 
                                                                    </label>
                                                               </button>

                                                          </td> 

    <!-- 
                                                         <td> 
                                                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                   <?PHP 
                                                                      $this->resources->getImage('accept.png',array('width' => '14', 'height' => '14'));
                                                                   ?>
                                                                     <script type="dojo/method" event="onClick" args="evt">
                                                                             Asistencias.Ui.btn_finalizarregistro_hoja(this,evt);
                                                                         
                                                                     </script>
                                                                     <label class="sp11">
                                                                          Terminar registro 
                                                                     </label>
                                                                </button>
                                                          </td>      -->
                                                          
                                                              <?PHP 
                                                                  if( $this->user->has_key('ASISTENCIAS_ACCESOCOMPLETO_SOLOVER') == FALSE || SUPERACTIVO === TRUE)
                                                                  {
                                                              ?> 

                                                         
                                                             

                                                               <td> 
                                                                     <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                                       <?PHP 
                                                                          $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                                                       ?>
                                                                         <script type="dojo/method" event="onClick" args="evt">
                                                                                 Asistencias.Ui.btn_eliminar_hoja(this,evt);
                                                                             
                                                                         </script>
                                                                         <label class="sp11">
                                                                               Eliminar Hoja  
                                                                         </label>
                                                                    </button>
                                                              </td>    

                                                          <?PHP 
                                                                }

                                                         
                                                          ?>

                                                     
                                                 </tr>
                                            </table>
                                            


                                            

                                            


                                     </div>
                               </div> 
                        </td>


                         <td>   
                              <span class="sp11b"> Ordenar por: </span>
                              <select id="sel_asis_ordenarpor"  
                                      data-dojo-type="dijit.form.Select" 
                                      data-dojo-props='name:"orden", disabled:false'
                                      style="font-size:11px; width:200px;">
                                    
                                     <option value="1"> Nombre del trabajador, categoria  </option>
                                     <option value="2"> Categoria, nombre del trabajador  </option>
                              </select>
                        </td>

                         <td>   
                              <span class="sp11b"> Visualizar: </span>
                              <select id="sel_asis_visualizar"  
                                      data-dojo-type="dijit.form.Select" 
                                      data-dojo-props='name:"ver_modo", disabled:false'
                                      style="font-size:11px; width:140px;">
                                    
                                     <option value="2"> Horas trabajadas </option>
                                     <option value="1"> Estado del día </option>
                                     <option value="3"> Tardanzas </option>
                                     <option value="8"> Permisos </option>
                                     <option value="4"> Hora ingreso 1 </option>
                                     <option value="5"> Hora Salida 1  </option>
                                     <?PHP
                                       if($config['maximo_marcaciones'] > 2)
                                       {
                                     ?>
                                         <option value="6"> Hora ingreso 2 </option>
                                         <option value="7"> Hora Salida 2  </option>
                                    <?PHP    
                                       }
                                     ?>
                              </select>

                              <span class="sp10">  </span>
                        </td>


                        <td>

                            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">  

                                         
                                             Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value );

                                    </script>
                                    <label class="sp12">
                                            <!--   Trabajadores desde otra Hoja. -->
                                    </label>
                               </button>

                        </td>
                  
                  </tr>
                
              </table>

            </form>
         </div>    
  

 
    </div>


     <div id="dv_hoja_calendario"  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="center" 
            data-dojo-props='region:"center" '>
          
            

        <div id="dvcontainer_calendar"> 
        

        </div>    

    </div>


 </div>
             


 
<?PHP 

   if($modo == 'importacion')
   {

?>
      </div>

<?PHP 
  } 
?>
