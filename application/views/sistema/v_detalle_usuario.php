


<div class="dv_busqueda_personalizada" style="font-size:12px;">

   <?PHP 

     echo "<b> Usuario: </b> ".$info['usur_login']."  <b> Persona: </b> ".$info['indiv_appaterno']." ".$info['indiv_apmaterno']." ".$info['indiv_nombres']."  <b>DNI:</b> ".$info['indiv_dni']."  <b> Nivel: </b> ".( trim($info['syus_categoria']) == '' ? '-----' : $info['syus_categoria'] );

   ?>
 
</div>



<div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"center" ' style="width:500px; height:450px; padding: 1px 1px 1px 1px;">
  
    <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Menu y permisos principales </span>">                      
         

        <input type="hidden" id="hddetalleusuario" value="<?PHP echo $usuario; ?>" /> 


        <ul class="ul_menu_config"> 

         <?PHP 


            foreach ($menu as $key => $item)
            {
         
                $bloqueado = false; 
         ?>
         
               <li> 
               	    
                  <input type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props=""  <?PHP if($item['checked'] == '1')  echo 'checked="true"' ?>  <?PHP if($item['estado'] == '0')  echo 'disabled="true"' ?>   value="<?PHP echo $item['sysmnu_key']; ?>" view="<?PHP echo $item['sysmnu_key']; ?>" tipo="menu" /> 
               			 


               		<?PHP echo trim($item['sysmnu_nombre']); ?> 


               		<?PHP 

                    if($item['checked'] != '1') $bloqueado = true;

               			$sub_menu =  $this->user->config_get_menu( array('parent' => $item['sysmnu_id'], 'usuario' => $usuario_id ) ); 


               			if(sizeof($sub_menu) > 0 )
               			{
        		 
        			echo	"<div>

        						    <ul> 
        					";

        			 
        	       			foreach ($sub_menu as $smnu )
        	       			{

                       
        			 		   echo '   <li> 
               	    						 <input type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props=""  value="'.trim($smnu['sysmnu_key']).'" view="'.trim($smnu['sysmnu_key']).'" tipo="submenu"  
                            ';    

                            if($smnu['checked'] == '1')  echo 'checked="true"';

                            if($smnu['estado'] == '0' || $bloqueado )  echo 'disabled="true"';


                    echo       '  /> 

               	    						 '.trim($smnu['sysmnu_nombre']);


                                 $opciones = $this->user->get_opciones_menu( array('menu' => $smnu['sysmnu_id'], 'usuario' => $usuario_id ) ); 

                                 if(sizeof($opciones) > 0 )
                                 {
                                
                                    echo '<div> 

                                              <ul> 

                                         ';
         
                                                 foreach ($opciones as $opt)
                                                 {
                                                      
                                                      echo ' <li>  <input type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="" value="'.trim($opt['so_key']).'" view="'.trim($opt['so_key']).'" tipo="opt" ';

                                                      if($opt['checked'] == '1')  echo 'checked="true"';

                                                      if($opt['estado'] == '0' || $bloqueado )  echo 'disabled="true"';

                                                      echo ' /> '.trim($opt['so_nombre']).'  </li> ';

                                                 }

                                                 $opciones = array();

                                    echo  ' 
                                              </ul>
                                         </div> '; 


                                 }



                      echo '

                              </li> ';
         	       			}
         
               		echo	"  </ul>

               				</div>";
         		

        				}       			
        	   		?>

                </li>

        <?PHP 
            }
         ?> 

        </ul>

   </div>


   <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Otras configuraciones </span>">   


        <?PHP 

        echo '<div> 

                  <ul> 

             ';
        
                     foreach ($otras_opciones as $opt)
                     {
                          
                          echo ' <li>  <input type="checkbox" data-dojo-type="dijit.form.CheckBox" data-dojo-props="" value="'.trim($opt['so_key']).'" view="'.trim($opt['so_key']).'" tipo="opt" ';

                          if($opt['checked'] == '1')  echo 'checked="true"';
 

                          echo ' /> '.trim($opt['so_nombre']).'  </li> ';

                     }
 

        echo  ' 
                  </ul>
             </div> '; 

        ?>


   </div>

</div>