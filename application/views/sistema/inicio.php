<?php

$this->load->view('frags/top.php');
 
$piso=1;

?>

<body class="<?PHP echo UI_FRONTEND; ?>">
 
<?PHP
 
    $this->load->view('frags/global_windows');
    $this->ui->global_loader();
?>
  
<div id="page">

    <div id="header">
     <div id="header_top" style="background-color: #000000">
            <div id="dv_user_des"> 
                   <b> Usuario: </b>  <?PHP  echo ( trim($usuario['user_nombre']) == '') ? 'NO IDENTIFICADO' : trim($usuario['user_nombre'])  ; ?> <b> Año de ejecución: </b> <?PHP  echo $usuario['anio_ejecucion']; ?>  | <a id="aCloseSesion" href="<?PHP echo base_url().'users/cerrar'?>">Cerrar Sesion</a>
            </div>
        </div>
        
        <div id="header_inf">
            
            <div class="subcolumns">
        	    <div class="c50l">
                        <div id="header_logo">

                            <?PHP $this->resources->getImage('institucion/logo_aplicacion.png', array('width' => '180', 'height' => '30' ));?>
                        </div>
                        <div id="module_name" style="margin-left: 50px;">
                             Recursos Humanos Suite. Powered by Ideartic Labs v2.0.0
                        </div>
                    </div>

                    <div class="c50l">

                    </div> 
            </div>
 
        </div>
         
    </div>
    
    <div id="bodi">
        
        <?PHP
 
            $this->load->view('frags/menu');


//            var_dump($this->user['user_id']);

  
        
        ?> 


        <?PHP 

        if( $this->user->has_key('GESTION_RAPIDA_DE_CONCEPTOS')  ){
        ?>

             <input id="hdpermiso_gestionrapida" type="hidden" value="1" />

        <?PHP 
        }
      
        ?>    

        <input type="hidden" id="hdPageId" value="" />       
        <div id="bodi_content">
 
             
        </div>   
    </div> <!-- FIN BODI-->
    <div id="footer">
        <?php 
            $this->load->view('frags/footer.php');
        ?>
    </div>
</div>


<?php 

$this->load->view('frags/bottom.php');

?>

