 
 
<div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 780px; height: 455px; margin: 0px 0px 0px 0px; padding:0px 0px 0px 0px; ">
    
     <div  dojoType="dijit.layout.ContentPane" 
        splitter="true" 
         region="top" 
        data-dojo-props='region:"top", style:"height: 105px;"' style=" padding:0px 0px 0px 0px; ">



     </div>
    
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="left" 
            data-dojo-props='region:"left", style:"width: 500px;"' style=" padding:0px 0px 0px 0px; ">
           

            <div id="ac" data-dojo-type="dijit.layout.AccordionContainer" data-dojo-props='closable: true, style:"width: 400px; height: 1200px;" '>

                    <div data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"Ingresos"' style=" padding:0px 0px 0px 0px; ">
                         <!--    Here's a closed title pane:
                            <div id="actp3" data-dojo-type="dijit.TitlePane" data-dojo-props='title:"Title Pane #11", style:"width: 300px;", open:false, 
                                            href:"layout/tab1.html", onLoad:function(){ console.log("load of actp3"); actp3Loaded = true; }'>
                            </div>
                            and an open one:
                            <div id="actp4" data-dojo-type="dijit.TitlePane" data-dojo-props='title:"Title Pane #12", style:"width: 300px;", open:true, 
                                            href:"layout/tab2.html", onLoad:function(){ console.log("load of actp4"); actp4Loaded = true; }'>
                            </div> -->
                          <div id="dvtablepla_resumen_ingresos"></div>
                            
                    </div>
                    <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"Descuentos"' style=" padding:0px 0px 0px 0px; " >
                          <div id="dvtablepla_resumen_descuentos"></div>  
                    </div>
                    <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props='title:"Aportaciones"' style=" padding:0px 0px 0px 0px; " >
                          <div id="dvtablepla_resumen_aportaciones"></div>   
                    </div>

             </div>
              
    
     </div>
    
     <div  dojoType="dijit.layout.ContentPane" 
            splitter="true" 
             region="center" 
            data-dojo-props='region:"center" ' style=" padding:0px 0px 0px 0px; ">
          
    
     </div>
             
</div>

