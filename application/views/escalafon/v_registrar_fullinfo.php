<div  class="window_container"><!-- para efectos de la elimancion del DOm -->
<input id="hdPersInfoKey" type="hidden" value="<?PHP echo trim($pers_info['indiv_key']);?>" />
<!-- <div id="dvViewName" class="dv_view_name">
    Informacion del Trabajador  
    
</div> -->

<div class="dv_yellow_resltador"> 
    <table class="_tablepadding4">
        <tr>
            <td> <span class="sp12b"> Trabajador / Empleado</span></td>
            <td><span class="sp12b">:</span></td>
            <td> 
              <?PHP 
                
                  if(trim($pers_info['tipo_empleado']) == '' )
                  {
                     $pers_info['tipo_empleado'] = ', No posee regristro laboral '; 
                  }
                  else
                  {
                    $pers_info['tipo_empleado'] =  $pers_info['tipo_empleado'].' ('.$estado_trabajo.')';
                  }

                    echo $pers_info['indiv_nombres'].' '.$pers_info['indiv_appaterno'].' '.$pers_info['indiv_apmaterno'].' '.' DNI: '.$pers_info['indiv_dni'].' - '.$pers_info['tipo_empleado'];
               ?>
                
              
            </td>
        </tr>
 
    </table>

</div>

<div id="dv_fullinfo_default_data"> 
    
     <input class="departamento" type="hidden" value="<?PHP echo trim($pers_info['departamento']); ?>" />
     <input class="provincia"    type="hidden" value="<?PHP echo trim($pers_info['provincia']); ?>" />
     <input class="distrito"     type="hidden" value="<?PHP echo trim($pers_info['distrito']); ?>" />
</div>
    <!-- 
     <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    
                                <script type="dojo/method" event="onClick" args="evt">
                                        Persona._V.gestion_rapida_deconceptos.load({'empkey': <?PHP echo "'".$pers_info['indiv_key']."'"; ?>});
                                </script>
      </button>
  -->
<div id="wd_newpersona_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>

    <div id="wd_newpersona_tabs"  
          dojoType="dijit.layout.TabContainer" 
          attachParent="true" tabPosition="top" tabStrip="true" 
          data-dojo-props=' region:"center" '>
            
            <div id="wd_newpersona_tab_tab1" dojoType="dijit.layout.ContentPane" 
                 title="<span class='titletabname'>Informacion personal</span>">
                <?PHP
                   $this->load->view('escalafon/v_info_personal');
                 ?>
                  
            </div>


         

            <div id="wd_newpersona_tab_tab2"  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Historial laboral</span>" style="width:275px;" attachParent="true">
                  <?PHP 


                     if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_VER') )
                     { 

                  ?>

                 <?PHP
                   $this->load->view('escalafon/v_info_historiallaboral', array('view' =>  $pers_info['indiv_key'] ) );
                 ?>
                  
                 <?PHP 
                     }
                     else
                     {

                          echo PERMISO_RESTRINGICO_MENSAJE;

                     }
                 ?>

            </div>
          

        

              <?PHP if($pers_info['persla_id'] != ''){  ?>
          

            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Vacaciones</span>" style="width:275px;" attachParent="true">
                
                 <?PHP 
                 

                    if( $this->user->has_key('TRABAJADOR_VACACIONES_VER') )
                    { 

                       $this->load->view('escalafon/v_info_vacaciones');
                    
                    }
                    else
                    {

                       echo PERMISO_RESTRINGICO_MENSAJE;

                    }
                 ?>  
                
            </div>
            
           
            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Comision de servicio</span>" style="width:275px;" attachParent="true">
                 <?PHP 
                 

                   if( $this->user->has_key('TRABAJADOR_COMISIONSERVICIO_VER') )
                   { 

               
                          $this->load->view('escalafon/v_info_comisionservicio');
                 
                   }
                   else
                   {

                        echo PERMISO_RESTRINGICO_MENSAJE;

                   }
                 ?>
                
            </div>

            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Descansos Medicos</span>" style="width:275px;" attachParent="true">
                
                 <?PHP 
                 

                    if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_VER') )
                    { 

                       $this->load->view('escalafon/v_info_descansomedico', array('tipos_descanso_medico' => $tipos_descanso_medico ));
                    
                    }
                    else
                    {

                       echo PERMISO_RESTRINGICO_MENSAJE;

                    }
                 ?>  
                
            </div>


            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Licencias</span>" style="width:275px;" attachParent="true">
                
                 <?PHP 
                 

                    if( $this->user->has_key('TRABAJADOR_LICENCIAS_VER') )
                    { 

                       $this->load->view('escalafon/v_info_licencias');
                    
                    }
                    else
                    {

                       echo PERMISO_RESTRINGICO_MENSAJE;

                    }
                 ?>  
                
            </div>
        <!-- 
             <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Meritos/Demeritos</span>" style="width:275px;" attachParent="true">
                  <?PHP
                   $this->load->view('escalafon/v_info_meridemerito');
                 ?>  
                 
            </div> -->
       

            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Formacion Academica</span>" style="width:275px;" attachParent="true">
                 

                 <?PHP

                    if( $this->user->has_key('TRABAJADOR_ACADEMICO_VER') )
                    {  

                      $this->load->view('escalafon/v_info_formacademica');

                   }
                   else
                   {

                        echo PERMISO_RESTRINGICO_MENSAJE;

                   }
                 ?>    
                
            </div>
            <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'>Informacion Familiar</span>" style="width:275px;" attachParent="true">
                

                 <?PHP

                    if( $this->user->has_key('TRABAJADOR_FAMILIAR_VER') )
                    {  

                           $this->load->view('escalafon/v_info_infofamiliar');

                   }
                   else
                   {

                        echo PERMISO_RESTRINGICO_MENSAJE;

                   }
                 ?> 
            </div> 

            <?PHP 
              }
            ?>

     </div>
 
</div>
</div>