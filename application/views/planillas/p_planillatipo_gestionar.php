<div id="dvViewName" class="dv_view_name">
 
  <table class="_tablepadding2" border="0">

      <tr> 
          <td> 
               <?PHP 
                         $this->resources->getImage('application2.png',array('width' => '22', 'height' => '22'));
                     ?>
          </td>

        <td>
             Gestionar tipos de planilla
          </td>
      </tr>
  </table>
 
</div>

<div id="dvgtp_gestionar_main" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>
 

        <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left", style:" width: 600px;"'>
        	    


        	   <div id="table_tipoplanilla_tipos" style="margin:20px 0px 0px 0px;">

        	   </div>

    	       <div style="margin:10px 5px 5px 5px;">
    	   		 
	    	   		<button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
	    	   		   <?PHP 
	    	   		      $this->resources->getImage('application.png',array('width' => '14', 'height' => '14'));
	    	   		   ?>
	    	   		     <script type="dojo/method" event="onClick" args="evt">
	    	          
	    	   		     </script>
	    	   		     <label class="sp11">
	    	   		     		Registrar nuevo Régimen
	    	   		     </label>
	    	   		</button>


              <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                     
                     <?PHP  
                        $this->resources->getImage('calendar.png',array('width' => '14', 'height' => '14'));
                     ?>
                       <label class="lbl10"> Calendario/Horario de trabajo </label>
                       
                       <script type="dojo/method" event="onClick" args="evt">
                               
                             var codigo = '';      
                                     
                             for(var i in Tipoplanilla.Ui.Grids.main.selection){
                                   codigo = i;
                             }
                             
                             if(codigo != '')      
                             {   
                                  Asistencias._V.horario_trabajador.load({'modo' : 'regimen', 'view' : codigo });
                             }
                             else
                             {
                                 alert('Debe seleccionar un registro');
                             }

                       </script>
              </button>

    	   
<!--                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP 
                     $this->resources->getImage('application.png',array('width' => '14', 'height' => '14'));
                  ?>
                    <script type="dojo/method" event="onClick" args="evt">
                    
                    </script>
                    <label class="sp11">
                        Conceptos del Sistema
                    </label>
               </button>
 -->
               
    	       </div>

 
        </div>

        <div id="dvgtp_view"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center" '>

        </div> 

</div>