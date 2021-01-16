<!-- Ventana Especificar DNI --> 
  
<div id="dvescala_especidni" data-dojo-type="dijit.Dialog" title="<span style='font-size:12px;'> Especificar DNI</span>" style="width: 250px; height: 220px;" >
    <div>   
        <table class="_tablepadding4">
             <tr>
                  <td> <span class="sp12b">DNI </span></td>
                  <td>:</td>
                  <td> <input id="txtdni_especificar" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:8" class="formelement-100-12"  /> </td>
                  <td>
                      <button id="btndni_buscar" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                              <?PHP 
                                 $this->resources->getImage('search_add.png',array('width' => '14', 'height' => '14'));
                              ?>
                            <script type="dojo/method" event="onClick" args="evt">
                                 Persona.Ui.btn_buscardni_click(this,evt);
                            </script>
                      </button>
                  </td>
             </tr>
        </table>
     </div>
     
     <div id="dvinfopers_container">
        
     </div>
</div>
  

<!-- -->