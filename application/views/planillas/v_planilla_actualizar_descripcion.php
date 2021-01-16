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
                Actualizar descripcion de la planilla
              </td>
          </tr>
      </table>

    </div>

    
    <div style="margin:10px;"> 


        <form id="form_update_planilla_descripcion" data-dojo-type="dijit.form.Form">
           
              <input type="hidden" name="planilla" value="<?PHP echo $info['pla_key']; ?>" />
       
              <table class="_tablepadding4">
              
                <tr class="row_form">
                  <td width="130">
                      <span class="sp12b"> Descripción   </span>   
                  </td>
                  <td width="8">
                      <span class="sp12b"> : </span>  
                  </td>
                  <td width="450">
                       <div dojoType="dijit.form.Textarea" data-dojo-props='name:"descripcion" ' class="formelement-200-11" style="margin:0px 0px 0px 6px;"><?PHP echo trim($plani_info['pla_descripcion']); ?></div> 
                  </td>
                </tr>
 

                <tr>
                    <td colspan="3" align="center">

                           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                             <?PHP 
                                $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                             ?>
                               <script type="dojo/method" event="onClick" args="evt">
                  
                                    Planillas.Ui.btn_actualizardescripcion_planilla(this,evt);
                                  
                               </script>
                               <label class="sp12">
                                     Actualizar descripción
                               </label>
                          </button>
                          

                    </td>
                </tr>


              </table>
        
        </form>
    
  </div>

</div>