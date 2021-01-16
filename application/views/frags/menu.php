 
<?PHP
 
   $MENU_CLASES = array(
                           'mnu_baritem' => 'menu_option',
                           'option' => 'menu_option',
                            
                        );

?>
                
 
                   
<div id="system_mainmenu" data-dojo-type="dijit.MenuBar">
    <?PHP
 
        $menu = $this->system->getMenu(0,SYSTEM_MODULE_ID, $usuario['usur_id']);

        foreach($menu as $menuparent){
                $sbmenu = $this->system->getMenu($menuparent['sysmnu_id'],SYSTEM_MODULE_ID, $usuario['usur_id']); // RECUPERANDO LAS OPCIONES DEL MENU
             ?>
             <div id="<?PHP echo trim($menuparent['sysmnu_domid']); ?>" class="<?PHP echo $MENU_CLASES['mnu_baritem']?>" data-dojo-type="<?PHP echo ($menuparent['sysmnu_haschild']=='1' && sizeof($sbmenu)> 0 ) ? 'dijit.PopupMenuBarItem' : 'dijit.MenuBarItem' ?>" >
                   <span><?PHP echo trim($menuparent['sysmnu_nombre'])?></span>
                    <?PHP
                        if($menuparent['sysmnu_haschild']=='1'){ // IMRPRIMIENO EL MENU 
                             
                             
                              if(sizeof($sbmenu)>0){ // SI EL MENU TIENE OPCIONES DISPONIBLES PARA EL USUARIO
                             ?>
                                   <div id="<?PHP echo trim($menuparent['sysmnu_domid']); ?>_mnuopts" data-dojo-type="dijit.Menu" > <!-- MENU 1 -->
                                         
                                       <?PHP foreach($sbmenu as $sm_opt){  // IMPRIMIENDO LAS OPCIONES DEL MENU
                                                    
                                                     $sbmenu_mnu_sb = $this->system->getMenu($sm_opt['sysmnu_id'],SYSTEM_MODULE_ID, $usuario['usur_id']); 
                                            ?> 
                                                       
							<div id="<?PHP echo trim($sm_opt['sysmnu_domid']);?>" class="<?PHP echo trim($MENU_CLASES['option']); ?>" data-dojo-type="<?PHP echo ($sm_opt['sysmnu_haschild']=='1' && sizeof($sbmenu_mnu_sb)>0 ) ? 'dijit.PopupMenuItem' : 'dijit.MenuItem' ?>" > <!-- SI EL MENU TIENE CHIL ENTONS ES SUBMENU-->
                                                               
                                                                <span> <?PHP echo trim($sm_opt['sysmnu_nombre']);?> </span> <!-- OPCION DEL MENU -->
                                                                
                                                                <?PHP 
                                                                       if($sm_opt['sysmnu_haschild']=='1'){ // si la opcion del menu tiene childs
                                                                            
                                                                           
                                                                          if(sizeof($sbmenu_mnu_sb)>0){ //  ?>
                                                                            
                                                                             <div id="<?PHP echo trim($sm_opt['sysmnu_domid']);?>_mnuopts" data-dojo-type="dijit.Menu" > 
                                                                            
                                                                                 <?PHP     
                                                                                     foreach($sbmenu_mnu_sb as $sbmenu_mnu_sb_opt ){
                                                                                   ?>

                                                                                                <div id="<?PHP echo trim($sbmenu_mnu_sb_opt['sysmnu_domid']);?>"  class="<?PHP echo trim($MENU_CLASES['option']); ?>"  data-dojo-type="dijit.MenuItem"> 
                                                                                                      <span> 
                                                                                                            <?PHP echo trim($sbmenu_mnu_sb_opt['sysmnu_nombre'])?> <!-- SUB MENU -->
                                                                                                      </span>
                                                                                                </div>

                                                                                   <?PHP   
                                                                                   }
                                                                                   ?>
                                                                               </div>
                                                                              
                                                                               <?PHP
                                                                           }
                                                                       }
                                                                ?>
                                                        </div>
                                                        
                                       <?PHP } ?>                 
							 
				   </div>  
                             <?PHP    
                             }
                       }
                    ?> 
             </div>
    <?PHP
        }

    ?>
</div>
                    
                   