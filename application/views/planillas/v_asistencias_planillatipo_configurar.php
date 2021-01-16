

<div class="<?PHP echo (is_array($config) === TRUE) ? 'dv_busqueda_personalizada' : 'dv_red_alert'; ?>">  
 
        <table class="_tablepadding2">
             <tr>
                <td width="70"> 
                    <span class="sp11b"> Tipo: </span>
                </td>
                <td>
                    <span class="sp12">  <?PHP echo $plati_info['plati_nombre']; ?> </span> 
                </td>
             </tr>
        </table>    

</div>


  <div  dojoType="dijit.layout.TabContainer" 
        attachParent="true" tabPosition="bottom" tabStrip="true" 
        data-dojo-props=' region:"center" ' style="width:100%; height:350px;"> 

        <div data-dojo-type="dijit.layout.ContentPane" title="<span class='titletabname'> Configuracion general </span>"   data-dojo-props='' >
               
            <div data-dojo-type="dijit.form.Form" 
                 id="form_asisplati_config" style="overflow:auto; "> 

                  <table class="_tablepadding4">

                        <tr>
                            <td colspan="4" align="left"> 
      
                                  <input type="hidden" name="view" class="key" value="<?PHP echo $plati_info['plati_key']; ?>"  />     

                                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                      <?PHP 
                                         $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                                      ?>
                                       <script type="dojo/method" event="onClick" args="evt">


                                             var data = dojo.formToObject('form_asisplati_config');
                                              

                                              if(Asistencias._M.planilla_tipo_configurar_actualizar.process(data))
                                              {
                                                  Asistencias._V.view_planillatipo_config.reload();
                                                
                                              }

                                       </script>
                                       <label class="sp11">
                                        
                                             <?PHP echo (is_array($config) === TRUE) ? ' Actualizar Configuración' : 'Habilitar y guardar Configuración'; ?>          
                                       </label>
                                  </button>


                                  <?PHP 
                                     if(is_array($config) === TRUE)
                                     { 
                                  ?>  
                                  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                      <?PHP 
                                         $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                      ?>
                                       <script type="dojo/method" event="onClick" args="evt">

 

                                       </script>
                                       <label class="sp11">
                                                     Desabilitar   
                                       </label>
                                  </button>

                                  <?PHP 
                                      }
                                  ?>


                            </td>
                        </tr> 

                        <tr class="row_form">
                            <td width="250">  

                                <span class="sp11"> Registro de asistencia   </span> 

                            </td>
                            <td width="10">
                                <span class="sp11"> : </span>
                            </td> 
                            <td width="250">

                                 <select data-dojo-type="dijit.form.Select" 
                                         data-dojo-props="name:'tipo_registro_asistencia'" style="width:50px; font-size:11px;">
                                     <option value="0" <?PHP echo ($config['tipo_registro_asistencia'] == '0') ? ' selected="selected" ' : '' ?>> Ninguno </option>
                                     <option value="<?PHP echo TIPOREGISTRO_ASISTENCIA_TAREO; ?>" <?PHP echo ($config['tipo_registro_asistencia'] == TIPOREGISTRO_ASISTENCIA_TAREO) ? ' selected="selected" ' : '' ?>> Módulo de Tareo de obreros </option>
                                     <option value="<?PHP echo TIPOREGISTRO_ASISTENCIA_MODULO; ?>" <?PHP echo ($config['tipo_registro_asistencia'] == TIPOREGISTRO_ASISTENCIA_MODULO) ? ' selected="selected" ' : '' ?>> Módulo de Asistencia y relojes </option>
                                 </select>  

                            </td>
                            <td> 
                                 
                            </td>
                        </tr>

                        <tr class="row_form">
                            <td width="250">  

                                <span class="sp11"> Registro de asistencia diario  </span> 

                            </td>
                            <td width="10">
                                <span class="sp11"> : </span>
                            </td> 
                            <td width="250">

                                 <select data-dojo-type="dijit.form.Select" 
                                         data-dojo-props="name:'registro_asistencia_diario'" style="width:50px; font-size:11px;">
                                     <option value="0" <?PHP echo ($config['registro_asistencia_diario'] == '0') ? ' selected="selected" ' : '' ?>> No </option>
                                     <option value="1" <?PHP echo ($config['registro_asistencia_diario'] == '1') ? ' selected="selected" ' : '' ?>> Si </option>
                                 </select>  

                            </td>
                            <td> 
                                 
                            </td>
                        </tr>
                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Hora por defecto</span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>

                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'hora_asistencia_pordefecto'" style="width:50px; font-size:11px;">
                                     <option value="0" <?PHP echo ($config['hora_asistencia_pordefecto'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                                     <option value="1" <?PHP echo ($config['hora_asistencia_pordefecto'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
                                 </select>  

                            </td>
                            <td> 
                                  <span class="sp11"> La hora del horario de trabajo aparecera por defecto en el día al momento de vincular al trabajador a la hoja de asistencia. </span>
                            </td>
                        </tr>
                     <!--    <tr class="row_form">
                            <td>  

                                <span class="sp11"> Activar hora limite de registro por marcación </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>

                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'activar_horalimite'" style="width:50px; font-size:11px;">
                                     <option value="0"> No </option>
                                     <option value="1"> Si </option>
                                 </select>  
                                    
                                 <span class="sp11b"> Horas de tolerancia </span>

                                 <input type="text" 
                                        data-dojo-type="dijit.form.TextBox" 
                                        data-dojo-props=" name:'horatolerancia' " 
                                        style="width:50px; font-size:11px;" value="1"  />

                            </td>
                            <td> 
                                
                            </td>
                        </tr>

                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Días de tolerancia para edición del día anterior </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>

                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'activar_diatolerancia'" style="width:50px; font-size:11px;">
                                     <option value="0"> No </option>
                                     <option value="1"> Si </option>
                                 </select>  
                                    
                                 <span class="sp11b"> Dias </span>

                                 <input type="text" 
                                        data-dojo-type="dijit.form.TextBox" 
                                        data-dojo-props=" name:'diatolerancia' " 
                                        style="width:50px; font-size:11px;" value="1"  />

                            </td>
                            <td> 
                                
                            </td>
                        </tr> -->

                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Cierre de hoja de asistencia mediante el botón "Registro finalizado"</span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>

                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'cierre_tareo_manual'" style="width:50px; font-size:11px;">
                                     <option value="0"  <?PHP echo ($config['cierre_tareo_manual'] == '0') ? ' selected="selected" ' : '' ?>  > No </option>
                                     <option value="1"  <?PHP echo ($config['cierre_tareo_manual'] == '1') ? ' selected="selected" ' : '' ?>  > Si </option>
                                 </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr>

                        <tr class="row_form">
                            <td>  

                                <span class="sp11">Ajustar marcaciones al horario </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>
                                   
                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'ajustar_marcaciones_alhorario'" style="width:50px; font-size:11px;">
                                     <option value="0"  <?PHP echo ($config['ajustar_marcaciones_alhorario'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                                     <option value="1"  <?PHP echo ($config['ajustar_marcaciones_alhorario'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
                                 </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr>


                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Considerar para el calculo de horas trabajadas </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>

                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'diario_tipo_horatrabajadas'" style="width:240px; font-size:11px;">
                                     <option value="0"  <?PHP echo ($config['diario_tipo_horatrabajadas'] == '0') ? ' selected="selected" ' : '' ?>  > Limitar al horario de trabajo como máximo </option>
                                     <option value="1"  <?PHP echo ($config['diario_tipo_horatrabajadas'] == '1') ? ' selected="selected" ' : '' ?>  > Sobre las marcaciones registradas  </option>
                                 </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr>
                    <!--     <tr class="row_form">
                            <td>  

                                <span class="sp11"> Máximo de marcaciones </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>

                                 <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'maximo_marcaciones'" style="width:50px; font-size:11px;">
                                     <option value="0"> Horario corrido </option>
                                     <option value="1"> Con refrigerio (Hasta 4 marcaciones) </option>
                                 </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr> -->
                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Grupo de trabajadores </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>
                                   
                                   <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'grupo_trabajadores'" style="width:50px; font-size:11px;">
                                       <option value="0" <?PHP echo ($config['grupo_trabajadores'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                                       <option value="1" <?PHP echo ($config['grupo_trabajadores'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
                                   </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr>
                      
                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Filtrar trabajadores por afectación presupuestal </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>
                                   
                                   <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'importacion_buscar_por_ap'" style="width:50px; font-size:11px;">
                                       <option value="0" <?PHP echo ($config['importacion_buscar_por_ap'] == '0') ? ' selected="selected" ' : '' ?> > No </option>
                                       <option value="1" <?PHP echo ($config['importacion_buscar_por_ap'] == '1') ? ' selected="selected" ' : '' ?> > Si </option>
                                   </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr>
                        <tr class="row_form">
                            <td>  

                                <span class="sp11"> Integración con relojes marcadores </span> 

                            </td>
                            <td>
                                <span class="sp11"> : </span>
                            </td> 
                            <td>
                                   
                                   <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'biometrico_habilitado'" style="width:50px; font-size:11px;">
                                       <option value="0"  <?PHP echo ($config['biometrico_habilitado'] == '0') ? ' selected="selected" ' : '' ?>  > No </option>
                                       <option value="1"  <?PHP echo ($config['biometrico_habilitado'] == '1') ? ' selected="selected" ' : '' ?>  > Si </option>
                                   </select>  

                            </td>
                            <td> 
                                
                            </td>
                        </tr>
                  </table>
            
             </div>
        </div>

        <?PHP 

            if(is_array($config) === TRUE)
            {  

        ?>          

        <div data-dojo-type="dijit.layout.ContentPane" title="<span class='titletabname'> Estados del día/importación </span>"   data-dojo-props='' >


               <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:100%; height:100%">

                    <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left"'>
                         
                        <div style="margin:0px 5px 5px 5px;"> 
                            <span class="sp11b"> Tipos por día </span>
                        </div>
                

                        <form id="form_estadodia_plati" data-dojo-type="dijit.form.Form" >

                             <input type="hidden" name="view" value="<?PHP echo $plati_info['plati_key']; ?>" />
                             <input type="hidden" name="modo" value="tipoplanilla" />

                             <table class="_tablepadding2">
                                <tr>    
                                    <td width="60">
                                        <span class="sp11"> Visualizar</span>
                                    </td>   
                                    <td width="5">
                                        <span class="sp11">: </span>
                                    </td>   
                                    <td>
                                         <select id="sel_estadodiaplati_visualizar" data-dojo-type="dijit.form.Select" data-dojo-props="name:'visualizar'" style="width:100px; font-size:11px;">
                                                <option value="0"> Todos </option>
                                                <option value="1"> Solo los activos </option>
                                         </select>
                                    </td>   
                                </tr>   

                             </table>

                        </form>

                        <div id="dvtable_estadodia_plati"></div>

                    </div>
                    <div id="dvplanillatipo_diaestado_config" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center", style:" width: 50%"'>
   
                    </div>

               </div>


        </div>

        <div data-dojo-type="dijit.layout.ContentPane" title="<span class='titletabname'> Horario por defecto</span>"   data-dojo-props='' >
                
                <div data-dojo-type="dijit.form.Form" 
                     id="form_horariodefecto_modificar" style="overflow:auto; "> 

                  <?PHP 

                    $dias = array(
                            'Lunes' => '1',
                            'Martes' => '2',
                            'Miercoles' => '3',
                            'Jueves' => '4',
                            'Viernes' => '5',
                            'Sabado' => '6',
                            'Domingo' => '7' 
                           );

                  ?>


                  <input type="hidden" name="view" class="key" value="<?PHP echo $plati_info['plati_key']; ?>"  />   

                  <table class="_tablepadding4">

                      <tr class="tr_header_celeste">
                          <td width="100"> 
                            <span class="sp11b"> Día </span>
                          </td>   
                          <td width="10"> 
                             
                          </td>   
                         
                         <td width="80"> 
                           <span class="sp11b"> Laborable </span>
                         </td>   
                          <td width="140"> 
                            <span class="sp11b"> Estado </span>
                          </td>   
                          <td width="200"> 
                            <span class="sp11b"> Horario </span>
                          </td>   
                          
                      </tr>

                    <?PHP 
                       foreach ($horario_defecto as $dia)
                       {
                    ?>
                        <tr class="row_form">
                          <td> 
                                <span class="sp11b"> <?PHP echo $dia['dia']; ?> </span>
                          </td>
                          <td>  <span class="sp11b">:  </span> </td>
                          <td align="center">  
                                <span class="sp11"> <?PHP echo trim($dia['laborable']) == '1' ? 'Si' : 'No'; ?> </span>
                           </td>
                          <td>  

                                <span class="sp11"> <?PHP echo trim($dia['hatd_nombre']) != '' ? $dia['hatd_nombre'] : '------'; ?> </span>
                           </td>
                           <td>
                                 
                                 <span class="sp11"> <?PHP echo trim($dia['hor_alias']) != '' ? $dia['hor_alias'] : '------';  ?> </span>
                            </td>
                        </tr>

                    <?PHP
                       }
                    ?>
 

                      <tr>  
                            <td align="center" colspan="4">

                                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                    <?PHP 
                                       $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                                    ?>
                                     <script type="dojo/method" event="onClick" args="evt"> 

                                             var data = dojo.formToObject('form_horariodefecto_modificar');
 
                                             Asistencias._V.planillatipo_horario_modificar.load(data);

                                     </script>
                                     <label class="sp11">
                                             Modificar             
                                     </label>
                                </button>
                            </td>
                      </tr> 
                 </table>

             </div>
  
        </div>
        
        <?PHP 
        }
        ?>

  </div> 
