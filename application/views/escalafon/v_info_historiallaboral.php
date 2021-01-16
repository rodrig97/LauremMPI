 
        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"<span class=\"titletabname \"> Historico </span>"'>
               

               <div style="margin:10px 0px 10px 0px;">   


                     <input type="hidden" class="hdkeyindiv" value="<?PHP echo $view; ?>" /> 

                     <?PHP 

                         if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
                         {   

                     ?>
                        
                           <button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
                                  <?PHP 
                                     $this->resources->getImage('note.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                <label class="lbl10">Registrar nuevo</label>
                                <script type="dojo/method" event="onClick" args="evt">

                                        var empkey = dojo.query('.hdkeyindiv', this.domNode.parentNode)[0].value; 
                                        Persona._V.nueva_situlaboral.load({'empkey' : empkey});
                                </script>
                           </button>
                
                      <?PHP 
                         }
                      ?>

               </div> 

            
               <div id="dvhistorial_data"> </div>    
              
               <div id="dvhistorial_table"></div>
              
               <div class="table_toolbar" align="right"> 



                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
                          <?PHP 
                             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Ver</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                    Persona.Ui.btn_tblhistolaboral_ver_click(this,evt);
                            </script>
                   </button>

                   <?PHP 

                       if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
                       {   

                   ?>
  
                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
                          <?PHP 
                             $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Modificar</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                    Persona.Ui.btn_tblhistolaboral_editar_click(this,evt);
                            </script>
                   </button> 


                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP  $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                          ?>
                            <label class="lbl10"> Cesar </label>
                            <script type="dojo/method" event="onClick" args="evt">
                                   Persona.Ui.btn_tblinfoper_retirar_view_click(this,evt);
                            </script>
                   </button>

                   <?PHP 
                      }
                   ?> 


                   <?PHP 

                       if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_DEL') )
                       {   

                   ?>

                   <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <label class="lbl10">Eliminar registro</label>
                              <script type="dojo/method" event="onClick" args="evt">
                                   Persona.Ui.btn_tblsilab_del_clic(this,evt);
                            </script>
                   </button>

                   <?PHP 
                      }
                   ?> 


               </div>
            
            
        </div>

 