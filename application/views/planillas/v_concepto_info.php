<div id="dv_concepto_info_detalle"> 
    
     <input type="hidden" value="<?PHP echo trim($concepto_info['conc_key']); ?>" class="hdconcview_id"/>
    
    
<div id="dvViewName" class="dv_view_name">
    Informacion del Concepto
</div>
 

 

  <?PHP 
                     if($concepto_info['conc_estado'] == '0')
                     {

                        echo "<span class='sp12b'> :::::::::: Este Concepto ha sido eliminado :::::::::: </span>";

                     }
 ?>    
 

<div class="dv_busqueda_personalizada">
        

    
     <table>
         <tr>
           <td width="80"> 
            <span class="sp12b">Concepto </span>
            </td> 
             <td width="10"> 
                <span class="sp12b">: </span>
            </td> 
            <td width="220"> 
                <span class="sp12"> <?PHP echo trim($concepto_info['conc_nombre']); ?> </span>
            </td> 

               <?PHP 
                  if($concepto_info['conc_estado'] == '1'){
            ?>

             <td width="90"> 
                
                 
                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                  <?PHP 
                     $this->resources->getImage('edit.png',array('width' => '14', 'height' => '14'));
                  ?>
                    <script type="dojo/method" event="onClick" args="evt">
                            Conceptos.Ui.btn_getview_concepto_editar(this,evt);
                    </script>
                    <label class="lbl11">
                            Modificar
                    </label>
                 </button>
             </td>
               <td> 
                 

                  <input type="hidden" value="<?PHP echo trim($concepto_info['conc_key']); ?>" class="hdconcview_id"/>
   
                   
                 <button  dojoType="dijit.form.Button" class="dojobtnfs_12" data-dojo-props="disabled: false" > 
                  <?PHP 
                     $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                  ?>
                    <script type="dojo/method" event="onClick" args="evt">

                           Conceptos.Ui.btn_eliminar_click(this,evt);

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

<table class="_tablepadding2_c" >
     
     <tr class="row_form">
        <td width="150"> 
            <span class="sp12b">Aplicable a </span>
        </td> 
         <td width="10"> 
            <span class="sp12b">: </span>
        </td> 
        <td width="400"> 
            <span class="sp12"> <?PHP echo trim($concepto_info['tipo_planilla']); ?> </span>
        </td> 
    </tr>
    

     <tr class="row_form">
        <td> 
            <span class="sp12b">Afecto al trabajador </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($concepto_info['afecto']); ?> </span>
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
            <span class="sp12"> <?PHP echo trim($concepto_info['conc_nombre']); ?> </span>
        </td> 
    </tr>
     <tr class="row_form">
        <td> 
            <span class="sp12b">Nombre para Boleta </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
               <span class="sp12"> <?PHP echo (trim($concepto_info['conc_nombrecorto']) != '' ? trim($concepto_info['conc_nombrecorto']) : '--------');  ?> </span>
        </td> 
    </tr>

     <tr class="row_form">
        <td> 
            <span class="sp12b">Nombre para Planilla </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
               <span class="sp12"> <?PHP echo (trim($concepto_info['conc_planillon_nombre']) != '' ? trim($concepto_info['conc_planillon_nombre']) : '--------');  ?> </span>
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
            <span class="sp12"> <?PHP echo (trim($concepto_info['conc_descripcion']) != '' ? trim($concepto_info['conc_descripcion']) : '--------'); ?> </span>
        </td> 
    </tr>
   <tr class="row_form">
        <td> 
            <span class="sp12b"> Tipo </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo ( trim($concepto_info['conc_afecto']) == '0' ? 'No Afecto' :  trim($concepto_info['concepto_tipo']));  ?> </span>
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
            <span class="sp12"> <?PHP echo trim($concepto_info['grupo_nombre']); ?> </span>
        </td> 
    </tr>
    
    <tr class="row_form">
        <td> 
            <span class="sp12b">Predeterminado </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo trim($concepto_info['predeterminado']); ?> </span>
        </td> 
    </tr>

    <tr class="row_form">
        <td> 
            <span class="sp12b"> # de veces como máximo al mes </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo (trim($concepto_info['conc_max_x_mes'])=='0') ? ' No tiene ' : trim($concepto_info['conc_max_x_mes']); ?> </span>
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
            <span class="sp12"> <?PHP echo trim($concepto_info['impresion']); ?> </span>
        </td> 
    </tr>

      <!--  <tr class="row_form">
        <td> 
            <span class="sp12b"> Posición en la impresión </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
               <span class="sp12"> <?PHP echo trim($concepto_info['conc_orden']); ?> </span>
        </td> 
    </tr> -->

   

     <tr class="row_form">
        <td> 
            <span class="sp12b"> Partida Presupuestal</span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo (trim($concepto_info['clasificador']) != '' ) ?  trim($concepto_info['clasificador']).' - '.trim($concepto_info['clasificador_nombre'])  : ' No especificada.'  ?> </span>
        </td> 
    </tr>

     <tr class="row_form">
        <td> 
            <span class="sp12b"> Casilla SUNAT - PDT </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12">

                    <?PHP echo (trim($concepto_info['cosu_codigo']) != '' ) ?  trim($concepto_info['cosu_codigo']).' - '.trim($concepto_info['cosu_descripcion'])  : ' No especificada.'  ?>
            </span>
        </td> 
    </tr>

     <tr class="row_form">
        <td> 
            <span class="sp12b"> Con restricción de montos </span>
        </td> 
         <td> 
            <span class="sp12b">: </span>
        </td> 
        <td> 
            <span class="sp12"> <?PHP echo $concepto_info['restriccion'];?> </span>
        </td> 
    </tr>
     


</table>

<div style="margin: 4px 2px 2px 2px; border: 1px solid #ebe9e9;" > 
    <div style="margin: 0px 0px 0px 0px; padding: 2px 2px 2px 2px;"> 
        <span class="sp12b"> Fórmula de cálculo:  </span>
    </div>
    <div style="padding: 3px 3px 3px 3px;"> 
       <?PHP  echo $ecuacion; ?>     
    </div>

</div> 

<div style="margin: 4px 2px 2px 2px; border: 1px solid #ebe9e9;" > 
    
    <div style="margin: 0px 0px 0px 0px; padding: 2px 2px 2px 2px;"> 
        <span class="sp12b"> Conceptos en los que se utiliza:  </span>
    </div>

    <div style="padding: 3px 3px 3px 3px;"> 
         <?PHP 
              if(sizeof($operando_conceptos)==0) echo ' <span style="font-size:11px; color:#333; " >-- Ninguno -- </span>';

               foreach($operando_conceptos as $k => $cop){
                    echo  '<label style="font-size:11px; color:#333; "  > ';
                     if($k!=0) echo ',';
                     echo   $cop['conc_nombre'].' </label> ';
               }
          ?> 
    </div>

</div> 

 
    
</div>