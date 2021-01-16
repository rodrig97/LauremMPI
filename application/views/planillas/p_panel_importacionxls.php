<div id="dvViewName" class="dv_view_name">
   

     <table class="_tablepadding2" border="0">
         <tr> 
            <td> 
                <?PHP 
                    $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                 ?>
            </td>
            <td>
                 <?PHP echo $modo_importacion; ?>
            </td>
         </tr>
    
     </table>
</div>
 
<div id="xlsimportacion_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
       <div  dojoType="dijit.layout.ContentPane" 
              region="top" 
              data-dojo-props='region:"top", style:"height: 50px;"'>
 
                <form id="form_config_importacion">
              
                  <?PHP 
                  
                     foreach($config_importacion as $k => $c)
                     {
                  ?>    
                        <input type="hidden" name="<?PHP echo trim($k); ?>" value="<?PHP echo trim($c); ?>"  />
                  <?PHP 

                     }
                  ?>     

               </form>


              <form id="formadjfiles_importacion"  action="<?PHP echo base_url();?>archivosimportacion/guardar_archivo" method="post" enctype="multipart/form-data" target="frame_uploader_files">
                         
                    <input type="hidden" id="hdxls_panel_currentview" name="current_view" value="" />

                    <input type="hidden" name="call" value="parent.importacionxls.on_end_upload_file_xls" />

                    <table class="_tablepadding4">

                      <tr>
                           <td> 
                              <span class="sp12b">
                                   Archivo EXCEL (.XLS)
                              </span>
                           </td>
                           <td>
                                <span class="sp12b">   :    </span>
                           </td>
                           <td> 
                                <input name="adjunto" type="file"  /> 
                           </td>
                          
                         
                           <td>

                              <button class="btnadjfile_submit" dojoType="dijit.form.Button" class="dojobtnfs_12"  > 
                                      
                                      <?PHP    $this->resources->getImage('chart_up.png',array('width' => '14', 'height' => '14')); ?>

                                      <script type="dojo/method" event="onClick" args="evt">
  
                                          if(confirm('Realmente desea importar el archivo? '))
                                          { 
     
                                               dojo.byId('formadjfiles_importacion').submit();
                                          }

                                      </script>
 
                                      <label class="lbl11">Explorar Archivo</label> 
                              </button>
     
                            </td>
      
                            <?PHP 

                               if(  $config_importacion['modo'] == XLS_IMPORTACION_PERSONALZIADA )
                               {

                           ?>

                            <td>
                                
                                <div>
                                   <span class="sp12b"> Configuración </span>
                                </div>
                                
                                <div>

                                    <?PHP 

                                           echo " <span class='sp12b'> Considerar: </span>".$config_importacion['by'];

                                           
                                           if(trim($config_importacion['tipos_permitidos']) != '')
                                           { 
                                               echo " <span class='sp12b'> Regímenes: </span>".$config_importacion['tipos_permitidos'];
                                           }
                                           else
                                           {
                                               echo " <span class='sp12b'> Regímenes: </span> Todos los regímenes"; 
                                           }


                                           if($config_importacion['vincular']=='vincular')
                                           {
                                            
                                               $vincular = 'Si';
                                           }
                                           else
                                           {
                                               $vincular = 'No';
                                           }
  
                                           echo " <span class='sp12b'> Vincular trabajadores: </span>".$vincular;
   
 
                                    ?>

                                </div>
                            </td>

                            <?PHP
                                }
                            ?>
                      </tr>

                  </table>
              
                </form>

                <iframe name="frame_uploader_files" id="frame_uploader_files" style="display:none;"></iframe>
          
       </div>
                   
        <div id="importcs_viewfile"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left" ' style ="width: 620px;"  >
             
        </div>
    
        <div id="importcs_result"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            
           
        </div>
 
 
    
</div>