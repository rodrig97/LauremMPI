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
                      Gestionar Metas Presupuestales
                  </td>
              </tr>
            </table>
      </div> 

           
      <div>
          <form data-dojo-type="dijit.form.Form" id="formtarea_filtro"> 
              
            <table class="_tablepadding4">
                <tr>
                   <td>
                        <span class="sp12b">  Año:  </span>
                    </td>     
                   <td> 


                       <select id="selplani_anio"  
                               data-dojo-type="dijit.form.Select" 
                               data-dojo-props='name: "anio", disabled:false' 
                               style="margin-left:6px; font-size:11px; width: 80px;">
                     
                               <?PHP 
                                  foreach ($anios as $anio)
                                  {
                                    # code...
                                    echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
                                  }
                               ?>
                      </select> 
 

                   </td>
                   <td>
                        <span class="sp12b">  Codigo/Nombre:  </span>
                    </td>     
                   <td>
                        <input type="text" data-dojo-type="dijit.form.TextBox" name="codigo_nombre" style="width:180px; font-size:11px; " />
                   </td>

                   <td>

                        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                           <?PHP 
                              $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                           ?>
                             <script type="dojo/method" event="onClick" args="evt">
                                  Catalogos.Ui.Grids.gestionar_metas.refresh();
                             </script>
                             <label class="sp11">
                                  Buscar
                             </label>
                        </button>

                   </td>     

                   <td>

                        <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                           <?PHP 
                              $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                           ?>
                             <script type="dojo/method" event="onClick" args="evt">
                                

                                  Catalogos._V.nueva_meta.load({});
                                  
                             </script>
                             <label class="sp11">
                                  Nueva
                             </label>
                        </button>

                   </td>     
                </tr>
            </table>

          </form>
      </div> 


      <div id="dv_gestionar_metas">

      </div>  

      <div style="margin: 6px 0px 0px 6px;">

          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
             <?PHP 
                $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
             ?>
               <script type="dojo/method" event="onClick" args="evt">

                      var data = { 'option' : 'modificar'} 
                          data.view = '';
                       
                      var grid = Catalogos.Ui.Grids.gestionar_metas;
                      
                      if(grid != null )
                      {   
                          data.view = '';
                          for(var i in grid.selection){data.view = i;}
                      }
                      else
                      {
                           Console.log('No existe el objeto GRID');
                      }

                      if(data.view != '')      
                      { 
                           Catalogos._V.nueva_meta.load(data);
                      }
                      else
                      {
                           alert('Debe seleccionar un registro');
                      }

                      

               </script>  
               <label class="sp11">
                    Modificar
               </label>
          </button>

       <!--    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
             <?PHP 
                $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
             ?>
               <script type="dojo/method" event="onClick" args="evt">
                    
                    var data = { 'option' : 'delete'} 
                        data.view = '';
                     
                    var grid = Catalogos.Ui.Grids.gestionar_metas;
                    
                    if(grid != null )
                    {   
                        data.view = '';
                        for(var i in grid.selection){data.view = i;}
                    }
                    else
                    {
                         Console.log('No existe el objeto GRID');
                    }

                    if(data.view != '')      
                    { 
                         if('Realmente desea eliminar esta Meta ?')
                         {

                           if(Catalogos._M.eliminar_meta.process(data))
                           {
                              Catalogos.Ui.Grids.gestionar_metas.refresh();
                           }
                         }


                    }
                    else
                    {
                         alert('Debe seleccionar un registro');
                    }

               </script>
               <label class="sp11">
                    Eliminar
               </label>
          </button> -->

      </div>
</div>