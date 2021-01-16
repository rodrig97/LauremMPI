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
                  Elaborar certificados de trabajo
              </td>
          </tr>
      </table>
    
    </div>

    <div> 
 

        <form dojoType="dijit.form.Form" id="formcontratos_elaborar"> 

          <input type="hidden" name="views" value="<?PHP echo $views; ?>" />
 
          <div style="margin:5px;"> 

              <table class="_tablepadding4">
                  <tr>

                      <td> 
                          <span class="sp11b"> Fecha del documento </span>
                      </td>
                      <td> 
                          <div id="calcertificado_fechadoc"  data-dojo-type="dijit.form.DateTextBox"
                                   data-dojo-props='type:"text", name:"fechadocumento", value:"",
                                    constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                   lang:"es",
                                   required:true,
                                   promptMessage:"mm/dd/yyyy",
                                   invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                  onChange=""   >
                          </div> 
                      </td>
                      <td> 
                          <span class="sp11b"> N° de inicio del certificado </span>
                      </td>
                      <td> 
                          <span class="sp11b"> : </span>
                      </td>
                      <td> 
                         <input type="text" data-dojo-type="dijit.form.TextBox" name="nrocertificado" style="font-size:11px; width:60px;" />
                         
                         <span class="sp11b"> - 2014 <?PHP echo DOCUMENTO_POSTFIJO; ?> </span>

                      </td>
                     

                  </tr> 

              </table>
          </div>

        </form>


        <div id="table_certificados_list">

        </div>

        <div style="margin:4px;"> 

              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                <?PHP 
                   $this->resources->getImage('page_edit.png',array('width' => '14', 'height' => '14'));
                ?>
                  <script type="dojo/method" event="onClick" args="evt">
                      
                      var data  = dojo.formToObject('formcontratos_elaborar');
                     
                      data.mode = 'certificados_trabajo';

                       Impresiones._V.preview.load(data);

                  </script>
                   <label class="sp11">
                        Generar certificado  
                  </label>
             </button>

             
              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                <?PHP 
                   $this->resources->getImage('attachment.png',array('width' => '14', 'height' => '14'));
                ?>
                  <script type="dojo/method" event="onClick" args="evt">
                       
                      var data  = dojo.formToObject('formcontratos_elaborar');

                      Exporter._M.data_certificados.send(data);
                      
                  </script>
                   <label class="sp11">
                        Descargar en excel
                  </label>
             </button>

        </div>

    </div>

</div>