<?php

$this->load->view('frags/top.php');
  
?>

<body class="claro">
    <?PHP
         $this->ui->global_loader();
    ?>
    <div id="ui_login_window_c">
        <div id="ui_login_namesys"> 
             <?PHP 
                $this->resources->getImage('institucion/logo_aplicacion.png');
             ?>
        </div>
        <div id="ui_login_window">

            
            <div id="ui_wlogin_body">
                  
                 <table class="_tablepadding4">
                     <tr> 
                         <td colspan="3" class="login_title"> Inicio de Sesion </td>
                     </tr>
                     <tr height="40">
                          <td width="80"><b> Año de ejecución</b></td>
                          <td>:</td>
                          <td> 
                               <select id="sellogin_anio"  data-dojo-type="dijit.form.Select" 
                                        data-dojo-props='name: "anio", disabled:false'
                                        style="margin-left:0px; font-size:12px; width: 50px;">
                                    
                                    <?PHP 
                                       foreach ($anios as $anio)
                                       {
                                         # code...
                                          echo '<option value="'.$anio['ano_eje'].'" >'.$anio['ano_eje'].'</option>';
                                       }
                                    ?>
                               </select>
                          </td> 
                     </tr>
                      <tr height="40">
                           <td width="80"><b> Usuario </b></td>
                           <td>:</td>
                           <td> 
                               <!--  <input id="login_txtuser" type="text" class="txtInput_1" maxlength="30" size="22"  /> -->
                               <input id="login_txtuser" data-dojo-type="dijit.form.TextBox"  style="width:180px; " maxlength="30"   />
                            </td>
                      </tr>
                      <tr height="40">
                           <td><b>Contrase&ntilde;a </b></td>
                           <td>:</td>
                           <td> 
                               <input id="login_txtpass" type='password' data-dojo-type="dijit.form.TextBox"  style="width:180px; " maxlength="30"  />
                           </td>
                      </tr>

                      <tr height="40">
                           <td><b>Verificaci&oacute;n</b></td>
                           <td>:</td>
                           <td> 
                               <div> <span  id="login_spcuestion" class="sp_small1"> Cuanto es 2 + 2 ? </span> </div>
                               <!-- <input id="login_txtcomp" type="text" class="txtInput_1" size="10" maxlength="5"/> -->
                               <input id="login_txtcomp" data-dojo-type="dijit.form.TextBox"  style="width:80px; " maxlength="5"   />
                           </td>
                      </tr>
                      <tr height="40">
                           <td colspan="3" class="login_title"> 
                                
                                  <button id="login_btnLogin" data-dojo-type="dijit.form.Button"  data-dojo-props='' style="font-weight: normal; font-size:12px;">
                                     Iniciar sesion
                                  </button>
                           </td>
                      </tr>
                    
                 </table>

            </div>

            
        </div>
    </div>

<?php 

$this->load->view('frags/bottom.php');

?>

