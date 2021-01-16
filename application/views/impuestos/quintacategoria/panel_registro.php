<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('user_search_b.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
            Registro de Retenciones por trabajador
          </td>
      </tr>
  </table>
 
</div>

<div id="dvregistroquinta_panel" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
  
    <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"top"' style="height:30px;">
        

        <div class="dvmb10"> 
        
         <div data-dojo-type="dijit.form.DropDownButton" >
                  
                     <span class="sp12b">
                          <?PHP 
                     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                 ?>
                         
                          Parámetros de Búsqueda
                     
                     </span>
                    <div id="tooltipDlg" data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el registro de trabajadores"'>

                        <div class="dv_formu_find_tt">
                              <form id="form_quinta_panel"  data-dojo-type="dijit.form.Form">   
                                    <table class="_tablepadding2" style="width:100%">
                                          <tr height="30" class="row_form" >
                                                  <td colspan="7"> <span class="sp12b">Parámetros de busqueda</span></td>
                                          </tr>
                                          <tr height="30" class="row_form" >
                                                  <td width="110"> <span class="sp12b">Año </span></td>
                                                  <td width="20">:</td>
                                                  <td colspan="7"> 
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"anio" ' class="formelement-35-12" style="width:100px; font-size:12px;">
                                                              <option value="2016" selected="selected"> 2016 </option>
                                                              <option value="2015" selected="selected"> 2015 </option>
                                                             
                                                      </select>
                                                  </td>
                                             </tr>

                                          <tr height="30" class="row_form" >
                                                  <td width="110"> <span class="sp12b"> Tipo de Trabajador </span></td>
                                                  <td width="20">:</td>
                                                  <td colspan="7"> 
                                                       <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"situlaboral" ' class="formelement-35-12" style="width:200px; font-size:12px;">
                                                              <option value="0" selected="selected"> No Especificar </option>
                                                             <?PHP 
                                                                foreach($tipo_planillas as $plati){
                                                                    echo '<option value="'.trim($plati['plati_id']).'">'.trim($plati['plati_nombre']).'</option>';
                                                                }
                                                             ?>
                                                      </select>
                                                  </td>
                                             </tr>
                                         
                                           <tr height="30"  class="row_form"> 
                                              <td> <span class="sp12b">DNI</span></td>
                                              <td>:</td>
                                               <td colspan="7"> 
                                                  <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-80-11" />
                                                  <label class="lbl_submensaje"> (*) Al especificar este campo no se consideraran los demas en la busqueda</label>
                                              </td>
                                           </tr>
                                           <tr height="30"  class="row_form">  
                                             <td> <span class="sp12b">Estado</span></td>
                                              <td>
                                                  : 
                                              </td>
                                              <td colspan="4"> 
                                                  
                                                     <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"vigente" ' 
                                                             style="width: 110px; font-size:12px;">
                                                                 <option value="1" selected="selected">  Solo Activos </option>
                                                                 <option value="0">  Cesados </option>
                                                                 <option value="2">  Todos </option>
                                                                   
                                                     </select> 
                                              </td>

                                              <td> <span class="sp12b">Ver SOLO trabajadores con retenciones</span></td>
                                              <td>
                                                   : 
                                              </td>
                                              <td> 
                                                    <input type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="name:'solo_con_retencion'" value="1" checked="checked" />
                                              </td>

                                           </tr>
                                         
                                           <tr height="30">
                                               <td> </td>
                                               <td> </td>
                                                <td colspan="7"> 

                                                    <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                            <?PHP 
                                                               $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                                            ?>
                                                               <label class="lbl10">Realizar Busqueda</label>
                                                                <script type="dojo/method" event="onClick" args="evt">
                                                                        QuintaCategoria.Ui.btn_cargar_trabajadores(this,evt);
                                                              </script>
                                                     </button>

                                              </td>
                                           </tr>

                                    </table>
                              </form>
                          </div>


                    </div>
            </div> 



             <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
               <?PHP 
                  $this->resources->getImage('application.png',array('width' => '14', 'height' => '14'));
               ?>
                 <script type="dojo/method" event="onClick" args="evt">
                        
                        QuintaCategoria._V.retenciones_anteriores.load({});

                 </script>
                 <label class="sp11">
                       Constancias de retención
                 </label>
            </button>
            

        </div>
        


    </div>

    <div id="dvQuintaCategoria_panel" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center" '>
          
    </div>

</div>