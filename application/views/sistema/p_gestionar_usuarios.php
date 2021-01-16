<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">
      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('user_search_b.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
             Gestionar Usuarios y Permisos
          </td>
      </tr>
  </table>
 
</div>

<div id="gestionusuarios_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
  
        <div id="dv_geusu_panelleft"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left"' style="width:540px;">
              
                 
            <div>

                  <table class="_tablepadding4">
                      <tr>
                         <td>
                              <span class="sp12b">  DNI:  </span>
                          </td>     
                         <td>
                              <input type="text" data-dojo-type="dijit.form.TextBox" style="width:100px; font-size:12px; " />
                         </td>
                         <td>
                              <span class="sp12b">  Nombre:  </span>
                          </td>     
                         <td>
                              <input type="text" data-dojo-type="dijit.form.TextBox" style="width:100px; font-size:12px; " />
                         </td>

                         <td>

                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                 <?PHP 
                                    $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                   <script type="dojo/method" event="onClick" args="evt">
                                   
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
                                      

                                        Users.Ui.btn_nuevo_usuario(this,evt);

                                   </script>
                                   <label class="sp11">
                                        Nuevo
                                   </label>
                              </button>

                         </td>     
                      </tr>
                  </table>

            </div> 


            <div id="dv_geusu_usuarios">

            </div>  

            <div style="margin: 6px 0px 0px 6px;">

                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                   <?PHP 
                      $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                   ?>
                     <script type="dojo/method" event="onClick" args="evt">
                     
                     </script>
                     <label class="sp11">
                          Modificar
                     </label>
                </button>

                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                   <?PHP 
                      $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                   ?>
                     <script type="dojo/method" event="onClick" args="evt">
                     
                     </script>
                     <label class="sp11">
                          Desactivar
                     </label>
                </button>

            </div>


        </div>

        <div id="dv_geusu_panelcenter"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center" '>
              
                 
        </div>
            
</div>