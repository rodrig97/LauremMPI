  <div class="window_container">
   <div class="dv_upload_data">
                    
                     <form id="formadjfiles_newviatico"  action="<?PHP echo base_url();?>archivosescalafon/subir/subir_para_importacion" method="post" enctype="multipart/form-data" target="frame_uploader_files">
                         
                          <input name="data" class="data" type="hidden" value="<?PHP echo $data; ?>" />
                          
                          <table class="_tablepadding4">
                               <tr>
                                   <td>  
                                        <span class="sp12b">Archivo </span> 
                                      
                                   </td>
                                   <td> 
                                       <span class="sp12b"> : </span> 
                                   </td>
                                    <td> 
                                          <input name="adjunto" type="file"  />
                                    </td>
                                 
                               </tr>
                               <tr>
                                   <td> <span class="sp12b">Descripcion  </span>  
                                       
                                   </td>
                                   <td> 
                                       <span class="sp12b"> : </span> 
                                   </td>
                                   <td>
                                        <div data-dojo-type="dijit.form.TextArea" data-dojo-props="name:'descripcion'" class="formelement-200-12"></div>
                                   </td>
                               </tr>
                             
                           </table>

                      </form>
                    
                      <iframe name="frame_uploader_files" id="frame_uploader_files" style="display:none;"></iframe>
                     
                </div>

                <div id="dv_files_buttonsubir" align="center"> 
  

                        <button class="btnadjfile_submit" dojoType="dijit.form.Button" class="dojobtnfs_12"  > 
                                          <?PHP 
                                             $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                          ?>

                                <script type="dojo/method" event="onClick" args="evt">
                                   Persona.Ui.btn_subirfile_click(this,evt);
                                </script>
                                <label class="lbl11">Guardar Archivo</label> 
                        </button>


                </div>

                <div id="dv_files_subir" class="dv_upload_cargando" style="display:none;">
                     <div data-dojo-type="dijit.ProgressBar" class="progresarbar_files" data-dojo-props='indeterminate: true, label:"Cargando"'></div>
                </div>


   </div>