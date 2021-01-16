 <div class="window_container"> 
    
    <div>
         <table class="_tablepadding2" border="0">
              <tr> 
                  <td> 
                       <?PHP 
                                 $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                             ?>
                  </td>

                 <td>
                    <span style="color:#990000; font-weight:bold;"> Registrar nueva papeleta de salida </span>
                  </td>
              </tr>
          </table>
    </div>


     <table class="_tablepadding4" width="100%">
         <tr id="trperm_doc" height="40"   class="row_form">
              <td width="100"> <span class="sp12b">Doc. Referencia </span></td>
              <td width="10">:</td>
              <td width="210">
                  <input name="documento" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100,  trim:true " class="formelement-200-12"  />
              </td>
       
             <!--  <td width="50"> <span class="sp12b">Autoriza </span></td>
              <td width="10">:</td>
              <td>
                  <input name="autoriza" type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="maxlength:100, trim:true   " class="formelement-200-12"  />
              </td> -->
         </tr>
        <!--  <tr id="trperm_infosisgedo">
             <td colspan="6"> 

                   <div class="info_sisgedo">
                     
                   </div>

             </td>

         </tr> -->
         
         <tr height="35"   class="row_form">
              <td> <span class="sp12b">Hora de Salida:</span></td>
              <td>:</td>
              <td colspan="4"> 
     

                 <input  data-dojo-type="dijit.form.TimeTextBox"
                            data-dojo-props='type:"text", name:"horaini", value:"T07:45:00",
                            title:"",
                            constraints:{formatLength:"short"},
                            required:true,
                            invalidMessage:"" ' style="font-size:11px;"/>

                   
              </td>
         </tr>
         <tr height="35"   class="row_form">
              <td> <span class="sp12b">Hora de regreso</span></td>
              <td>:</td>
              
              <td colspan="4"> 

                      <input  data-dojo-type="dijit.form.TimeTextBox"
                                data-dojo-props='type:"text", name:"horafin", value:"T07:45:00",
                                title:"title: Time using local conventions",
                                constraints:{formatLength:"short"},
                                required:true,
                                invalidMessage:"" '  style="font-size:11px;" />
                   
              </td>
         </tr>
         
          <tr>
              <td> <span class="sp12b"> Motivo </span></td>
              <td>:</td>
              
              <td colspan="4">

                   <input name="motivo" type="text" data-dojo-type="dijit.form.TextArea" 
                       data-dojo-props="trim:true, maxlength:20" 
                       class="formelement-200-11"  />
              </td>
         </tr>


         <tr>
            <td colspan="3" align="center"> 
                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  
                  <?PHP 
                     $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                  ?>

                    <label class="lbl11">Registrar</label>
                      <script type="dojo/method" event="onClick" args="evt">
                              alert('Papeleta registrada correctamente');                           
                      </script>
                 
                 </button>
            </td>
         </tr>
    </table>

</div>