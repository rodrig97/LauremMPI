 
 
<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' 
style="width:920px; height: 400px; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
    
     <div  dojoType="dijit.layout.ContentPane" 
        data-dojo-props='region:"left", style:"width: 460px;"' style="padding:0px 0px 0px 0px;">
 
         <div style="padding: 0px 0px 4px 4px;"> 
            <span class="sp12b"> Registro de planillas</span>
         </div> 
       
         <div id="dv_importacion_plapreview" ></div>
         
     </div>
    
     <div id="dv_importacion_otradetalle"  dojoType="dijit.layout.ContentPane" 
              
            data-dojo-props='region:"center", style:"width: 460px;"' style=" padding:0px 0px 0px 0px; overflow: hidden; scroll:none; ">
           
        
 
     </div>
  
     <div  dojoType="dijit.layout.ContentPane" 
              
            data-dojo-props='region:"bottom", style:"height:40px;"' style=" padding:0px 0px 0px 0px; overflow: hidden; ">
  
             <div style=" padding:3px 8px 3px 3px;" align="right">
                   <button id="btn_importar_fromotraplanilla" data-dojo-props="disabled:true"  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                          <?PHP 
                             $this->resources->getImage('refresh.png',array('width' => '14', 'height' => '14'));
                          ?>
                             <span class="sp12">Importar relacion de trabajadores</span>
                            <script type="dojo/method" event="onClick" args="evt">
                                    Planillas.Ui.btn_importar_fop_click(this,evt);
                            </script>
                   </button>
               </div>

     </div>
    
             
</div>

