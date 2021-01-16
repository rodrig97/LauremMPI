<div id="dvViewName" class="dv_view_name">
    
    <table class="_tablepadding2" border="0">
         <tr> 
            <td> 
                <?PHP 
                 $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                 ?>
            </td>
            <td>
                 SUNAT
            </td>
         </tr>
    </table>
</div>
 
<div id="impuestosunat_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
        
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"top" ' style ="width: 620px;"  >
 
            <div>  
              <form id="form_sunat_declarar"  data-dojo-type="dijit.form.Form">   

               <table class="_tablepadding4">
                  <tr> 
                      <td width="30"><span class="sp11b">Año :</span></td> 
                      <td width="60">  

                             <select  data-dojo-type="dijit.form.Select" data-dojo-props='name: "anio", disabled:false' 
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
                      <td width="30"><span class="sp11b">Mes:</span></td>
                      <td width="92">   
                            <select data-dojo-type="dijit.form.Select" data-dojo-props='  name:"mes", disabled:false' 
                                    style="margin-left:6px; width:85px; " class="formelement-80-11">
                                 
                                  <option value="01">Enero</option> 
                                  <option value="02">Febrero</option> 
                                  <option value="03">Marzo</option> 
                                  <option value="04">Abril</option> 
                                  <option value="05">Mayo</option> 
                                  <option value="06">Junio</option> 
                                  <option value="07">Julio</option> 
                                  <option value="08">Agosto</option> 
                                  <option value="09">Septiembre</option> 
                                  <option value="10">Octubre</option> 
                                  <option value="11">Noviembre</option> 
                                  <option value="12">Diciembre</option> 
                              </select>
                      </td>  

                      <td>

                          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                            <?PHP 
                               $this->resources->getImage('search.png',array('width' => '14', 'height' => '14'));
                            ?>
                              <script type="dojo/method" event="onClick" args="evt">
                                 
                                  var data = dojo.formToObject('form_sunat_declarar');

                                  data.modo = '1';

                                  Impuestos._V.registro_de_planillas.load(data);
                              </script>
                              <label class="sp11">
                                    Seleccionar planillas
                              </label>
                           </button>

                           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                             <?PHP 
                                $this->resources->getImage('block.png',array('width' => '14', 'height' => '14'));
                             ?>
                               <script type="dojo/method" event="onClick" args="evt">
                           
                               </script>
                               <label class="sp11">
                                    Cerrar el mes
                               </label>
                            </button>

                            <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                              <?PHP 
                                 $this->resources->getImage('accept.png',array('width' => '14', 'height' => '14'));
                              ?>
                                <script type="dojo/method" event="onClick" args="evt">
                            
                                </script>
                                <label class="sp11">
                                    Declaración realizada 
                                </label>
                             </button>
                      </td> 


                    </tr>

               </table>
              </form>
            </div>

        </div>
    
        <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left" ' style ="width: 800px;"  >


         
            
 
<!-- 
            <div id="impuestosunat_filtrotrabajadores"> 
            </div> 

            <div  style="margin: 5px 5px 5px 5px">

                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                     <?PHP 
                        $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                      <label class="lbl10"> Visualizar</label>
                        <script type="dojo/method" event="onClick" args="evt">
             
                       </script>
                  </button>
                


                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                     <?PHP 
                        $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                     ?>
                      <label class="lbl10"> Generar Excel </label>
                        <script type="dojo/method" event="onClick" args="evt">
                 
                       </script>
                  </button>



             </div> 
 -->      

              <div  data-dojo-type="dijit.layout.TabContainer" 
                    attachParent="true" tabPosition="top" tabStrip="true" 
                    data-dojo-props=' region:"center" '>

                      <div  data-dojo-type="dijit.layout.ContentPane" 
                           title="<span class='titletabname'> Planillas Seleccionadas </span>">
                          
                           <div style="margin: 5px 5px 5px 5px;">  
                              <span class="sp12b"> 
                                  Planillas seleccionadas 100/110
                              </span>
                           </div> 

                           <div id="table_sunat_planillas_seleccionadas">
                           </div>


                           <div style="margin:5px 0px 0px 0px;">

                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                 <?PHP 
                                    $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                   <script type="dojo/method" event="onClick" args="evt">
                                      
                                        Impuestos.Ui.btn_sunat_deseleccionarplanilla(this,evt);

                                   </script>
                                   <label class="sp11">
                                         Deseleccionar
                                   </label>
                                </button>
                               
                           </div>


                      </div>
                      
                      <div  data-dojo-type="dijit.layout.ContentPane" 
                           title="<span class='titletabname'>Bajas</span>">
                          
                            
                           <div id="table_sunatbajas">
                           </div>

                           <div style="margin:5px 0px 0px 0px;">

                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                 <?PHP 
                                    $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                   <script type="dojo/method" event="onClick" args="evt">
 
                                   </script>
                                   <label class="sp11">
                                         Generar archivos de bajas (Tra y Per)
                                   </label>
                                </button>
                               
                           </div>

                      </div>
 
                      <div  data-dojo-type="dijit.layout.ContentPane" 
                           title="<span class='titletabname'>Altas</span>">
  
                           <div id="table_sunataltas">
                           </div>

                           <div>

                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                 <?PHP 
                                    $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                   <script type="dojo/method" event="onClick" args="evt">
                           
                                   </script>
                                   <label class="sp11">
                                         Generar archivos de altas 
                                   </label>
                                </button>
                               

                               <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                 <?PHP 
                                    $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                                 ?>
                                   <script type="dojo/method" event="onClick" args="evt">
                               
                                   </script>
                                   <label class="sp11">
                                         Generar archivos de altas 
                                   </label>
                                </button>
                           </div>


                      </div>


                </div>

        </div>
    
        <div id="impuestosunat_result"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            

           
        </div>
 
 
    
</div>  