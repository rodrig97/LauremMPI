
<div id="dv_variable_info_detalle"> 
    
<input type="hidden" value="<?PHP echo trim($vari_info['vari_key']); ?>" class="hdvariview_id"/>
    

<div id="dvViewName" class="dv_view_name" style="margin:8px" >
    Informacion de la variable
</div>
 

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($vari_info);
?>
 
     <?PHP 
                     if($vari_info['vari_estado'] == '0'){

                        echo "<span class='sp12b'> :::::::::: Esta variable ha sido eliminada :::::::::: </span>";

                     }
                ?>    


<div class="dv_busqueda_personalizada" >
    
     <table>
         <tr>
           <td width="45"> 
            <span class="sp12b">Variable </span>
            </td> 
             <td width="10"> 
                <span class="sp12b">: </span>
            </td> 
            <td width="220"> 
                <span class="sp12"> <?PHP echo trim($vari_info['vari_nombre']); ?> </span>


            </td> 

            <?PHP 
                  if($vari_info['vari_estado'] == '1' && $vari_info['vari_protegida'] == '0')
                  {
            ?>

             <td width="50"> 
                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP 
                     $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                  ?>
                    <script type="dojo/method" event="onClick" args="evt">
                         Variables.Ui.btn_getview_variable_editar(this,evt);
                    </script>
                    <label class="lbl11">
                            Modificar
                    </label>
                 </button>
             </td>
               <td> 


                 <input type="hidden" value="<?PHP echo trim($vari_info['vari_key']); ?>" class="hdvariview_id"/>
                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP 
                     $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                  ?>
                    <script type="dojo/method" event="onClick" args="evt">
                            Variables.Ui.btn_eliminar_click(this,evt);
                    </script>
                    <label class="lbl11">
                            Eliminar
                    </label>
                 </button>
             </td>
             
             <?PHP } ?>
         </tr>
     </table>
    
 
</div>


<?PHP 
    
    if($vari_info['vari_protegida']=='1')
    {
        
        echo ' <div class="dv_busqueda_personalizada" >';

        echo  $vari_info['vari_protegida_msm'];

        echo ' </div>';
    }


?>

<table class="_tablepadding2_c" >
     <tr class="row_form">
        <td width="150"> 
            <span class="sp12b">Aplicable a </span>
        </td> 
         <td width="10"> 
            <span class="sp12b">: </span>
        </td> 
        <td width="400"> 
            <span class="sp12"> <?PHP echo trim($vari_info['tipo_planilla']); ?> </span>
        </td> 
    </tr>
    <tr class="row_form">
        <td width="90"> 
            <span class="sp12b">Nombre </span>
        </td> 
         <td width="10"> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($vari_info['vari_nombre']); ?> </span>
        </td> 
    </tr>
     <tr class="row_form">
        <td> 
            <span class="sp12b">Nombre Corto </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
               <span class="sp12"> <?PHP echo trim($vari_info['vari_nombrecorto']); ?> </span>
        </td> 
    </tr>
   <tr class="row_form">
        <td> 
            <span class="sp12b">Descripcion </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo (trim($vari_info['vari_descripcion']) != '') ? trim($vari_info['vari_descripcion']) : ' -------'; ?> </span>
        </td> 
    </tr>
    <tr class="row_form">
        <td> 
            <span class="sp12b"> Grupo </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($vari_info['grupo_nombre']); ?> </span>
        </td> 
    </tr> 
    <!--
   <tr class="row_form">
        <td> 
            <span class="sp12b"> Tipo </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($vari_info['tipo_variable']); ?> </span>
        </td> 
    </tr> -->
    <tr class="row_form">
        <td> 
            <span class="sp12b">Valor por defecto </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo number_format($vari_info['vari_valordefecto'],2).' '.$vari_info['unidad']; ?> </span>
        </td> 
    </tr>
    <!--
    <tr class="row_form">
        <td> 
            <span class="sp12b">Predeterminado </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($vari_info['predeterminado']); ?> </span>
        </td> 
    </tr> -->

     <tr class="row_form">
        <td> 
            <span class="sp12b">Personalizable </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($vari_info['personalizable']); ?> </span>
        </td> 
    </tr>
   <tr class="row_form">
        <td> 
            <span class="sp12b">Mostrar en impresion </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($vari_info['impresion']); ?> </span>
        </td> 
    </tr>

    <tr class="row_form">
        <td> 
            <span class="sp12b"> Mostrar en Planillon  </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo ( trim($vari_info['vari_planillon']) == '1') ? ' Si, mostrar : '.$vari_info['vari_planillon_nombre'] : ' No '; ?> </span>
        </td> 
    </tr>
  
      <tr> 
         <td colspan="3"> 
               <div class="sp12b">Conceptos relacionados </div>
               <div class="dv_cr_1"> 
                    <?PHP 
                        if(sizeof($vari_info['in_conceptos'])==0) echo ' <span style="font-size:11px; color:#333; " >-- Ninguno -- </span>';
                        foreach($vari_info['in_conceptos'] as $k => $cop){
                             echo  '<label> ';
                                 if($k!=0) echo ',';
                             echo   $cop['conc_nombre'].' </label> ';
                        }
                    ?> 
               </div>
           </td>
       </tr>
</table>
     
</div>