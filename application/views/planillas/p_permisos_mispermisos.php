<div id="dvViewName" class="dv_view_name">
   

     <table class="_tablepadding2" border="0">
         <tr> 
            <td> 
                <?PHP 
                    $this->resources->getImage('note_accept.png',array('width' => '22', 'height' => '22'));
                 ?>
            </td>
            <td>
                 Mis Permisos
            </td>
         </tr>
    
     </table>
</div>
 
<div id="mispermisos_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true'>
 
                   
        <div   data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"left" ' style ="width: 620px;"  >
             
            <div id="tablapermisos_panel"></div>


        </div>
    
        <div  id="permisoli_detalle" data-dojo-type="dijit.layout.ContentPane" data-dojo-props='region:"center"' style="padding:0px 0px 0px 0px"   >
            
           
        </div> 
    
</div>