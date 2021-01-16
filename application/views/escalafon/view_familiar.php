
<div id="dvViewName" class="dv_view_name">
     
      <table class="_tablepadding2" border="0">

        <tr> 
            <td> 
                 <?PHP 
                           $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                       ?>
            </td>

          <td>
                    Detalle Familiar del trabajador
            </td>
        </tr>
    </table>

</div>

<div class="dv_form_bceleste" style="margin:10px 0px 0px 0px;"> 

    <table class="_tablepadding4" width="100%">
            
            <tr height="30" class="row_static_form">
                <td width="110">
                     <span class="sp12b">Trabajador</span>
                </td>
                 <td width="20">
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP echo trim($info['indiv_nombres']).' '.trim($info['indiv_appaterno']).' '.trim($info['indiv_apmaterno']); ?></span>
                </td>
            </tr>
             <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">Parentesco</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo trim($info['paren_nombre']); ?>  </span>
                </td>
            </tr>
             <tr height="30" class="row_static_form">
                <td>
                    <span class="sp12b"> Familiar: </span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP echo trim($info['pefa_nombres']).' '.trim($info['pefa_apellpaterno']).' '.trim($info['pefa_apellmaterno']); ?></span>
                </td>
            </tr>
              <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">DNI</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo trim($info['pefa_dni']); ?>  </span>
                </td>
            </tr>

            
            <?PHP 
             if( $info['paren_id'] == FAMILIAR_HIJO )
             { 
            ?>

             <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">Estudiante</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo trim($info['estudiante']); ?>  </span>
                </td>
            </tr>
    
                
            <?PHP 
                }
            ?>


              <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">Sexo</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo (trim($info['pefa_sexo'])=='1') ? 'HOMBRE' : 'MUJER'; ?>  </span>
                </td>
            </tr>
             <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">Fecha de Nacimiento</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo _get_date_pg(trim($info['pefa_fechanace'])); ?>  </span>
                </td>
            </tr>
            <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">Ocupacion</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo  (trim($info['ocupacion']) != '') ? trim($info['ocupacion']) : ' --------- ';  ?>  </span>
                </td>
            </tr>
             <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b">Observacion</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                        <span class="sp12"> <?PHP  echo (trim($info['pefa_observacion']) != '') ? trim($info['pefa_observacion']) : ' --------- '; ?>  </span>
                </td>
            </tr>
          
           
            
        </table>
</div>
<?PHP
// var_dump($info);
?>