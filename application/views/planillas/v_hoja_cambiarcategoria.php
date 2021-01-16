<div class="window_container">


    <form dojoType="dijit.form.Form" id="form_asistencias_cambiarcategoria"> 
    
        <input type="hidden" value="<?PHP echo $hoja; ?>" name="hoja" /> 
        <input type="hidden" value="<?PHP echo $detalle; ?>" name="detalle" /> 
        <input type="hidden" value="<?PHP echo $actual; ?>" name="actual" /> 


        <div id="dvViewName" class="dv_view_name">
          
              <table class="_tablepadding2" border="0">
                    <tr> 
                        <td> 
                             <?PHP 
                                       $this->resources->getImage('refresh.png',array('width' => '22', 'height' => '22'));
                                   ?>
                        </td>

                       <td>
                            Cambiar categoria 
                        </td>
                    </tr>
              </table>
        </div>

        <div>
 
             <table class="_tablepadding4"> 

                <tr> 
                    <td width="100"> <span class="sp12b"> Trabajador </span> </td>
                    <td width="6"> <span class="sp12b"> : </span>   </td>
                    <td>  
                        <span class="sp12"> 
                        <?PHP 
                             echo $detalle_info['indiv_appaterno'].' '.$detalle_info['indiv_apmaterno'].' '.$detalle_info['indiv_nombres'].' - (DNI: '.$detalle_info['indiv_dni'].')';   
                        ?>
                         </span>
                    </td>
                </tr>

                <tr> 
                    <td> <span class="sp12b"> Categoria Actual </span> </td>
                    <td> <span class="sp12b"> : </span>   </td>
                    <td>  <span class="sp12"> 
                        <?PHP 
                            if( trim($detalle_info['categoria']) == '')
                            {
                                echo '-------';
                            }
                            else
                            {
                                echo trim($detalle_info['categoria']);
                            }
                        ?> </span>
                    </td>
                </tr>

                 <tr> 
                    <td> <span class="sp12b"> Cambiar a </span> </td>
                    <td> <span class="sp12b"> : </span>   </td>
                    <td>  
                        <select id="calasis_categoria" data-dojo-type="dijit.form.Select"  data-dojo-props=' name:"categoria" '  class="formelement-150-11" style="width:150px; font-size:11px;">
                                
                                <option value="0"> No especificar </option>
                                <?PHP 
                                  foreach($categorias as $reg)
                                  {
                                     
                                      echo  " <option value='".$reg['platica_key']."'>".$reg['platica_nombre']."</option> ";   
                                  }
                                ?>
                        </select> 
                    </td>
                </tr>

                 <tr> 
                    <td colspan="3" align="center"> 


                         <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                           <?PHP 
                              $this->resources->getImage('accept.png',array('width' => '14', 'height' => '14'));
                           ?>
                             <script type="dojo/method" event="onClick" args="evt">
                                    
                                    if(confirm('Realmente desea actualizar la categoria del trabajador ? '))
                                    {
                                        
                                        Asistencias.Ui.btn_cambiarCategoria_click(this,evt);
                                    }

                             </script>
                             <label class="sp11">
                                   Actualizar categoria
                             </label>
                        </button>

                     </td>
                </tr>
             </table>

        </div>

    </form>
</div>
