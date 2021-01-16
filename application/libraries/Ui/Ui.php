<?php

Class Ui{
    
    private $_CI;
  
    
    public function __construct($base_path=''){
       
        $this->_CI=&get_instance();
        $this->_CI->load->library('resources');
    }
     
    
    public function window_header($params=array()){

        echo    '
                <div id="'.$params['id'].'" class="_window">
                    <div class="_w_header">
                        <table class="tblHeader" cellpadding="0" cellspacing="0">
                           <tr>
                                <td class="td-Title"> '.$params['title'].' </td>
                                <td class="td-Close" id="'.$params['id_close_button'].'" width="50">Cerrar</td>
                           </tr>
                       </table>
                    </div>

                  <div class="_w_body"> ';


    }

    public function window_footer($params=array()){

         echo     ' </div>

                        <div class="_w_footer">
                            '.$params['footer'].'
                        </div>
                    </div>';

    }
    
    public function global_loader(){
        
        
        echo '<div id="ge_cortina"></div>    
              
              <div id="ge_loader">';
                        $this->_CI->resources->getImage('ge/loader_1.gif',array('width'=> '42','height'=>'42'));
                       
        echo  '     <span>Cargando..</span>
              </div> 

              <div id="dv_loader_progressbar"> 

                    <span class="sp12b"> Procesando.. </span>
                     <div id="pbimportarexcel" data-dojo-type="dijit.ProgressBar" data-dojo-props=""></div>
              </div>
              ';
    }
    
    
    public function ui_table($id='',$content_def=''){
         
          echo '<div id="'.$id.'" class="ui_table">
             
                    <div class="ui_table_container">

                        <div class="ui_table_loader">
                            <div class="ul_table_loader_show"> ';
                                 $this->_CI->resources->getImage("loader3.gif");
           echo'                <div class="ul_table_loader_text">Cargando</div>
                            </div>
                            <div class="ui_table_loader_bg"> </div>
                        </div>

                        <div class="ui_table_table">
                            '.$content_def.'
                        </div>
                    </div>

                    <div class="ui_table_footer">

                    </div>
                </div>';
        
    }
    
     
    public function _get_button(){
        
        echo  "<span> Boton  1</span>";
        
    }
     
   
    public function  page_id($id){
        echo '<input type="hidden" id="_hdPageId" value="'.trim($id).'" />    ';
    }

    
    
    
}

?>
