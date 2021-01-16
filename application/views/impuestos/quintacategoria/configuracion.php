<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('calendar.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
              Configuración del calculo de Cuarta y Quinta categoria
          </td>
      </tr>
      
  </table>
 
</div>

 
 
<div id="dvquintacategoria_configuracion"  
    dojoType="dijit.layout.TabContainer" 
    attachParent="true" tabPosition="top" tabStrip="true" 
    data-dojo-props=' region:"center" '>


	<div data-dojo-type="dijit.layout.ContentPane" 
	     title="<span class='titletabname'>Por tipo de trabajador</span>"  
	     data-dojo-props='region:"left", style:" width: 350px;"'>

	      <div data-dojo-type="dijit.layout.BorderContainer" 
	           data-dojo-props='design:"sidebar", liveSplitters:true' style="width:100%; height:100px">
	      
	          <div data-dojo-type="dijit.layout.ContentPane" 
	                data-dojo-props='region:"left", style:" width: 340px;"'>
	                
	                <div style="margin:2px 2px 2px 2px;">
	                     <span class="sp11b"> Tipos de trabajador </span>
	                </div>

	                <div id="table_tipoplanilla_quintacategoria" style="margin:20px 0px 0px 0px;">

	                </div>

	              
	          </div>
	          <div id="dvplanillatipo_config_panel" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 800px;"'>
	             
	          </div>

	      </div>      

	</div>

</div>