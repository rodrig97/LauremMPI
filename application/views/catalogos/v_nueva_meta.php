 <div class="window_container">

      <div id="dvViewName" class="dv_view_name">
          
            <table class="_tablepadding2" border="0">

              <tr> 
                  <td> 
                       <?PHP 
                                 $this->resources->getImage('add.png',array('width' => '22', 'height' => '22'));
                             ?>
                  </td>

                 <td>
                   
                  <?PHP echo ($option == 'modificar' ? 'Modificar Meta Presupuestal' : 'Registrar Nueva Meta Presupuestal' );  ?>
                  </td>
              </tr>
            </table>
      </div> 


      <div>


           <form dojoType="dijit.form.Form" id="form_meta_nueva"> 
                  
                   <input type="hidden" name="view" value="<?PHP echo ($option == 'modificar' ? $tarea_info['tarea_key'] : '' )  ?>" />

                   <input type="hidden" name="option" value="<?PHP echo ($option == 'modificar' ? '2' : '1' )  ?>" />

                   <table class="_tablepadding4" width="100%" >
                      <tr  class="row_form"> 
                         <td width="60">
                             <span class="sp12b">
                             Año
                             </span>
                         </td>
                         <td width="5" width="10">
                             :
                         </td>
                              
                         <td width="330">

                                <?PHP 

                                   if($option != 'modificar')
                                   {

                                ?>
                                  <select   data-dojo-type="dijit.form.Select" 
                                           data-dojo-props='name: "anio", disabled:false' 
                                           style=" font-size:11px; width: 100px;">
                                 
                                           <?PHP 
                                              foreach ($anios as $anio)
                                              {
                                                # code...
                                                echo '<option value="'.$anio['ano_eje'].'" ';

                                                if($option == 'modificar')
                                                {
                                                     if($anio['ano_eje'] ==  $tarea_info['ano_eje']) echo ' selected="selected" ';
                                                }

                                                echo ' >'.$anio['ano_eje'].'</option>';
                                              }
                                           ?>
                                  </select>

                              <?PHP
                                  }
                                  else
                                  {
                              ?>

                                    <input type="hidden" name="anio" value="<?PHP echo $tarea_info['ano_eje'];  ?>" />

                                    <span class="sp12b"> <?PHP echo $tarea_info['ano_eje']; ?> </span>


                              <?PHP 
                                  }
                               ?>
                         </td>
                     </tr>
                    
                    <tr  class="row_form"> 
                        <td><span class="sp12b">Código</span></td>
                        <td>:</td>
                        <td> 
                            <input type="text" dojoType="dijit.form.TextBox" data-dojo-props='name:"codigo" '
                                   class="formelement-100-11" style="margin:0px 0px 0px 0px;" value="<?PHP echo ($option == 'modificar' ? $tarea_info['sec_func'] : '' )  ?>" />
                        </td>
                    </tr>
                    
                    
                      <tr  class="row_form"> 
                          <td><span class="sp12b">Nombre</span></td>
                          <td>:</td>
                          <td> 
                              <input type="text" dojoType="dijit.form.Textarea" data-dojo-props='name:"nombre" '
                                     class="formelement-300-11" style="margin:0px 0px 0px 0px;" value="<?PHP echo ($option == 'modificar' ? $tarea_info['tarea_nombre'] : '' )  ?>" />
                          </td>
                      </tr>
                 <!--     

                      <tr  class="row_form"> 
                          <td><span class="sp12b">Residente</span></td>
                          <td>:</td>
                          <td> 
                              
                          </td>
                      </tr> -->

                 </table>
               
           </form>
       



      </div>



      <div align="center" style="margin: 20px 0px 0px 0px;">
             

          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
              <?PHP 
                 $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
              ?>
                <script type="dojo/method" event="onClick" args="evt">

                      var data = dojo.formToObject('form_meta_nueva');  
                      
                      if( dojo.trim(data.nombre)  == '' || dojo.trim(data.codigo) == '')
                      {
                          alert('Los campos codigo y nombre son obligatorios');
                      }         
                      else
                      {   

                        if(confirm('Realmente desea registrar la Meta Presupuestal ?'))
                        { 

                          if(data.option=='1')
                          {
                            if(Catalogos._M.registrar_meta.process(data)) 
                            {
                                Catalogos._V.nueva_meta.close(); 
                                Catalogos.Ui.Grids.gestionar_metas.refresh();
                            }
                          }
                          else
                          {

                            if(Catalogos._M.actualizar_meta.process(data)) 
                            {
                                Catalogos._V.nueva_meta.close(); 
                                Catalogos.Ui.Grids.gestionar_metas.refresh();
                            }
                          }

                        }
                        

                      }


                    
                </script>
                <label class="sp11">
                     Registrar
                </label>
         </button>
 
      </div>


</diV>