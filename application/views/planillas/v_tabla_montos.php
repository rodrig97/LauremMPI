<div class="window_container">

 

  <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true' style="width:500x; height: 350px;">
       
       <div  data-dojo-type="dijit.layout.ContentPane" data-dojo-props=' splitter: true, region:"top", style:"width: 530px; height:40px; padding: 5px 5px 5px 5px;"' >
        
           <table class="_tableppading4">

                 <tr>
                    <td width="80">
                        <span class="sp12b">  Tabla </span>
                    </td>   
                    <td width="10">
                        <span class="sp12b">  : </span>
                    </td>
                    <td>
                        
                      <select id="seltablemontos_tables"  data-dojo-type="dijit.form.Select" data-dojo-props='name: "tables", disabled:false' style="margin-left:6px; font-size:12px; width: 230px;">
                          
                           <?PHP 
                              foreach($tables as $table)
                              {
                            ?>
                                 
                                  <option value="<?PHP echo trim($table['vtd_id']); ?>"> <?PHP echo trim($table['vtd_nombre']); ?> </option>

                            <?PHP     

                              }
                           ?>

                      </select>

                    </td>
                 </tr>

           </table>

       </div>
      
      
       <div id="dvtablamontos_view"  data-dojo-type="dijit.layout.ContentPane" data-dojo-props=' region:"center", style:"padding:0px 0px 0px 0px;"' >
           
   
       </div>
      
  </div>
   
</div>