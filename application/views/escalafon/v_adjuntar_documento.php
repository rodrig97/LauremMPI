   <tr id="trhis_docadj" height="35" class="row_form" style="display:none;"> 
                                  <td> <span class="sp12b">Documento</span></td>
                                  <td>:</td>
                                  <td> 
                                      
                                          <form name="formadjfiles_newviatico"  action="<?PHP echo base_url();?>archivosescalafon/subir/nuevo" method="post" enctype="multipart/form-data" target="frame_uploader_files">
                                                
                                                <input name="tipo_archivos" type="hidden" value="1"/>
                                                <input name="viatico_key" type="hidden" value="<?PHP echo trim($view_key); ?>"/>
                                                <input type="hidden" id="hdadjfiles_id" name="archivo_id" value="0" />
                                                
                                                <input type="file" name="file1" />
                                                <input type="submit" id="btnsubmitadj" />
                                           </form>    

                                       <iframe name="frame_uploader_files" id="frame_uploader_files" style="display:none;"></iframe>   

                                  </td>
                             </tr> 

                         </table>