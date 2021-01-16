<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('calendar.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
              Configuración del módulo de gestión de asistencia
          </td>
      </tr>
      
  </table>
 
</div>

 

<!-- 
<div id="dvasistencias_asistencia_config" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
 -->
  <div id="dvasistencias_asistencia_config"  
        dojoType="dijit.layout.TabContainer" 
        attachParent="true" tabPosition="top" tabStrip="true" 
        data-dojo-props=' region:"center" '>

    <div data-dojo-type="dijit.layout.ContentPane" title="<span class='titletabname'>  Estados del día</span>"  data-dojo-props='region:"center", style:"" '>
              

          <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:100%; height:100$">
 
              <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 570px;"'>
                   
                  <div id="table_estadodeldia"> </div>

                  <div style="margin:10px 0px 0px 10px;">

                      <button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
                             <?PHP 
                                $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                             ?>
                           <label class="lbl10"> Registrar nuevo </label>
                           <script type="dojo/method" event="onClick" args="evt">
                                 Asistencias._V.nuevo_estadodia.load({});
                           </script>
                      </button>
                  </div>

              </div>
              <div id="dvestadodia_view" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 600px;"'>
          
              </div>

          </div>     


    </div>  

    <div data-dojo-type="dijit.layout.ContentPane" title="<span class='titletabname'>  Horarios </span>"  data-dojo-props='region:"center", style:"" '>
          

          <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:100%; height:100$">
          
              <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 570px;"'>
                   
                  <div id="table_horarios"> </div>

                  <div style="margin:10px 0px 0px 10px;">

                      <button  dojoType="dijit.form.Button" class="dojobtnfs_12"   > 
                             <?PHP 
                                $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                             ?>
                           <label class="lbl10"> Registrar nuevo </label>
                           <script type="dojo/method" event="onClick" args="evt">
                                  Asistencias._V.nuevo_horario.load({});
                           </script>
                      </button>
                  </div>

              </div>
              <div id="dvhorario_view" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 600px;"'>
          
              </div>

          </div>     



    </div>

    <div data-dojo-type="dijit.layout.ContentPane" title="<span class='titletabname'>Por tipo de planilla</span>"  data-dojo-props='region:"left", style:" width: 400px;"'>
 
          <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:100%; height:100$">
          
              <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 400px;"'>
                    
                    <div style="margin:2px 2px 2px 2px;">
                         <span class="sp11b"> Régimenes activos </span>
                    </div>

                    <div id="table_tipoplanilla_asistencia" style="margin:20px 0px 0px 0px;">

                    </div>

                  
              </div>
              <div id="dvplanillatipo_config_panel" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 800px;"'>
                 
              </div>

          </div>     


    </div>

</div>