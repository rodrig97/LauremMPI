<div id="dvViewName" class="dv_view_name">
   

     <table class="_tablepadding2" border="0">
         <tr> 
            <td> 
                <?PHP 
                    $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                 ?>
            </td>
            <td>
                 Importar trabajadores desde archivo excel
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

                     <input type="hidden" name="importar_trabajadores" value="1" />

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