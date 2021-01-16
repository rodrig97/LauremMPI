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
                  Cargar Datos 
              </td>
          </tr>
      </table>

    </div>


    <div> 

         <div data-dojo-type="dijit.form.Form" id="form_biometrico_cargardatos"> 

         <table class="_tablepadding4" width="100%">

             <tr>
                 <td>
                     <span class="sp12b"> Biométrico </span> 
                 </td>
                 <td>
                     <span class="sp12b"> : </span> 
                 </td>
                 <td>

                     <select id="sel_biometrico" data-dojo-type="dijit.form.Select" name="biometrico" data-dojo-props="name:'biometrico'" style="width:270px; font-size:12px;" > 
                         <?PHP 

                           foreach($biometricos as $reg)
                           {
                             echo "<option value='".$reg['biom_key']."'> ".$reg['biom_descripcion']." </option> ";
                           }
                         ?>
                     </select>
                 </td>
             </tr>

            <!--   <tr  class="row_form" > 
                 <td><span class="sp12b"> Rango </span></td>
                 <td> </td>
                 <td> 
                     <span class="sp12b">Del: </span>
                     <div id="cal_bio_desde" data-dojo-type="dijit.form.DateTextBox"
                                      data-dojo-props='type:"text", name:"fechadesde", value:"",
                                       constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                      lang:"es",
                                      required:true,
                                      promptMessage:"mm/dd/yyyy",
                                      invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-100-12"
                                      
                                        onChange="dijit.byId('cal_bio_hasta').constraints.min = this.get('value'); dijit.byId('cal_bio_hasta').set('value', this.get('value') );  "
                                      >
                                  </div> 
                     
                     <span class="sp12b"> Al: </span>
                     
                     <div id="cal_bio_hasta"  data-dojo-type="dijit.form.DateTextBox"
                                      data-dojo-props='type:"text", name:"fechahasta", value:"",
                                       constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                      lang:"es",
                                      required:true,
                                      promptMessage:"mm/dd/yyyy",
                                      invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-100-12">
                                  </div> 
                 </td>
             </tr> -->

             <tr>
                 <td colspan="3" align="center"> 


                      <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                        <?PHP 
                           $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                        ?>
                          <script type="dojo/method" event="onClick" args="evt">

                                Biometrico.Ui.btn_cargar_panel_biometrico(this,evt);

                          </script>
                          <label class="sp12"> 

                                Explorar e Importar
                          </label>
                     </button>
                     

                 </td>
             </tr>

         </table>

       </div>
    </div>


</div>