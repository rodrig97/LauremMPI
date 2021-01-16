<div id="dvViewName" class="dv_view_name">
   

     <table class="_tablepadding2" border="0">
         <tr> 
            <td> 
                <?PHP 
                    $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                 ?>
            </td>
            <td>
                 Aprobacion de solicitudes de permiso
            </td>
         </tr>
    
     </table>
</div>
 
<div id="permisoaprobacion_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
                   
        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left" ' style ="width: 620px;"  >
           

            <div class="dv_busqueda_personalizada">

              <form data-dojo-type="dijit.form.Form" data-dojo-props="" id="formaprobacionpanel">

                <table class="_tablepadding2">
                    <tr>    
                        <td width="60"> 
                            <span class="sp11b"> Dia </span>
                        </td> 
                        <td width="5"> 
                            <span class="sp11b"> : </span>
                        </td> 
                        <td width="110"> 
                            <div id="calaprobacion_dia"  data-dojo-type="dijit.form.DateTextBox"
                                  data-dojo-props='type:"text", name:"fecha", value:"",
                                   constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                 lang:"es",
                                 required:true,
                                 promptMessage:"mm/dd/yyyy",
                                 invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                 
                                   onChange="" > </div> 
                        </td> 
                        
                        <td> 
                            <span class="sp11b"> Estado </span>
                        </td> 
                        <td> 
                            <span class="sp11b"> : </span>
                        </td> 
                        <td>         
                            <select data-dojo-type="dijit.form.Select" data-dojo-props="name:'estado'" class="formelement-100-11" style="width:120px;" > 
                                    <?PHP 
                                        foreach ($estados as $estado)
                                        {
                                            echo " <option value=".$estado['ppest_id'].">  ".$estado['ppest_nombre2']." </option> ";
                                        }
                                    ?>
                            </select>
                        </td> 
                    
                    </tr>

                    <tr>    

                        <td> 
                            <span class="sp11b"> Código </span>
                        </td> 
                        <td> 
                            <span class="sp11b"> : </span>
                        </td> 
                        <td>         
                             <input type="text" data-dojo-type="dijit.form.TextBox" data-dojo-props="name:'codigo'" class="formelement-80-11"  />
                        </td> 


                       <td> 
                          <span class="sp11b"> Trabajador </span>
                       </td> 
                       <td> 
                          <span class="sp11b"> : </span>
                       </td> 
                       <td colspan="4"> 
                              <select id="selspermisoaprobacion_solicita"  
                                     data-dojo-type="dijit.form.FilteringSelect" class="formelement-200-11" 
                                     data-dojo-props='name:"solicita", 
                                                      disabled:false, 
                                                      autoComplete:false, 
                                                      highlightMatch: "all",  
                                                      queryExpr:"${0}", 
                                                      invalidMessage: "La Persona no esta registrada" ' style="width:300px;"  >
                              </select>

                       </td> 

                       <td>

                              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                <?PHP 
                                   $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                                ?>
                                  <script type="dojo/method" event="onClick" args="evt">

                                           Planillas.Ui.Grids.permisos_panel.refresh();
                                      
                                  </script>
                                  <label class="sp11">
                                       Filtrar  
                                  </label>
                             </button>
                       </td>    

                    </tr> 
                </table>    

              </form>

            </div>


            <div id="tablapermisos_panel"></div>


        </div>
    
        <div  id="permisoli_detalle" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            
           
        </div> 
    
</div>