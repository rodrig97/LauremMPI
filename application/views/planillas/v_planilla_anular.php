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
                Anular el proceso de la planilla de remuneraciones
              </td>
          </tr>
      </table>

    </div>

    
    <div style="margin:10px;"> 


        <form id="form_planilla_anular" data-dojo-type="dijit.form.Form">
           
              <input type="hidden" name="planilla" value="<?PHP echo $info['pla_key']; ?>" />
       
              <table class="_tablepadding4">

                <tr class="row_form">
                  <td colspan="3">
                       <?PHP 
                          if(CONECCION_AFECTACION_PRESUPUESTAL){ 
                       ?>
                      
                            Al anular el proceso, el saldo presupuestal gastado sera revertido
                      
                      <?PHP 
                        }
                      ?>
                  </td>
                </tr>
              
                <tr class="row_form">
                  <td width="130">
                      <span class="sp12b"> Motivo de la anulación </span>   
                  </td>
                  <td width="8">
                      <span class="sp12b"> : </span>  
                  </td>
                  <td width="400">
                      

                        <input type="input" data-dojo-type="dijit.form.TextArea" style="width:400px; font-size:11px;" data-dojo-props="name:'motivo' " />


                  </td>
                </tr> 


                <tr class="row_form">
                  <td width="130">
                      <span class="sp12b"> Contraseña </span>   
                  </td>
                  <td width="8">
                      <span class="sp12b"> : </span>  
                  </td>
                  <td width="400">
                      

                        <input type="password" data-dojo-type="dijit.form.TextBox" style="width:150px; font-size:11px;" data-dojo-props="name:'pk' " />


                  </td>
                </tr>
 

                <tr>
                    <td colspan="3" align="center">

                           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                             <?PHP 
                                $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                             ?>
                               <script type="dojo/method" event="onClick" args="evt">
                  
                                   Planillas.Ui.btn_anularproceso_click(this,evt);
                                  
                               </script>
                               <label class="sp12">
                                     Anular proceso
                               </label>
                          </button>
                          

                    </td>
                </tr>


              </table>
        
        </form>
    
  </div>

</div>