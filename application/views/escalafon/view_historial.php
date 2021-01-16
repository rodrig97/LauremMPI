<div class="window_container"> 
<div id="dvViewName" class="dv_view_name">
   Detalle del registro laboral
</div>

<div class="dv_form_bceleste"> 

<div> 
   <input class="data" type="hidden" value="historial-<?PHP echo trim($info['persla_id']); ?>" />   

     <?PHP 

     if( $this->user->has_key('TRABAJADOR_HISTORIALLABORAL_EDITAR') )
     {   

 ?>
   <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
         <?PHP 
              $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
         ?>
         <label class="lbl10"> Adjuntar documento </label>
        <script type="dojo/method" event="onClick" args="evt">
             Persona.Ui.btn_viewadjuntar_click(this,evt,'view_situacion_laboral');
        </script>
   </button>
 
 <?PHP 
     }
 ?>

</div>

<table class="_tablepadding4" width="100%">
    
    
     <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b"> Vigente </span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo trim($info['vigente']); ?>  </span>
        </td>
    </tr>

    <tr height="30" class="row_static_form">
        <td width="110">
             <span class="sp12b">Trabajador</span>
        </td>
         <td width="20">
             <span class="sp12b">:</span>
        </td>
       <td>
             <span class="sp12"><?PHP echo trim($info['indiv_nombres']).' '.trim($info['indiv_appaterno']).' '.trim($info['indiv_apmaterno']); ?></span>
        </td>
    </tr>
     <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Tipo</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo trim($info['situ_nombre']); ?>  </span>
        </td>
    </tr>
    
     <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Area</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo (trim($info['depe_nombre'])!= '') ? trim($info['depe_nombre']) : '------'; ?>  </span>
        </td>
    </tr>
    
     <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Cargo</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo (trim($info['cargo_nombre']) != '') ? trim($info['cargo_nombre']) : '-------'; ?>  </span>
        </td>
    </tr>
     
     <tr height="30" class="row_static_form">
        <td>
            <span class="sp12b">Proyecto: </span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
             <span class="sp12"> <?PHP echo (trim($info['meta_nombre']) != '') ? trim($info['meta_nombre']) : '------'; ?></span>
        </td>
    </tr>
    
    
      <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Inicio Contrato</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP   echo _get_date_pg(trim($info['persla_fechaini'])); ?>  </span>
        </td>
    </tr>
    
     <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Fin de Contrato</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo $info['fecha_hasta']  ?>  </span>
        </td>
    </tr>


    <?PHP 

        if($info['persla_vigente'] == '0' )
        { 
    ?>    

        <tr height="30" class="row_static_form">
            <td>
                 <span class="sp12b">Fecha de Cese</span>
            </td>
             <td>
                 <span class="sp12b">:</span>
            </td>
           <td>
                    <span class="sp12"> <?PHP  echo $info['fecha_cese']  ?>  </span>
            </td>
        </tr>

        <tr height="30" class="row_static_form">
            <td>
                 <span class="sp12b"> Obs de Cese</span>
            </td>
             <td>
                 <span class="sp12b">:</span>
            </td>
           <td>
                    <span class="sp12"> <?PHP  echo (trim($info['persla_obs_cese']) != '') ? trim($info['persla_obs_cese']) : ' ------- '  ?>  </span>
            </td>
        </tr>


    <?PHP
        }
    ?>


    
     <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Monto Contrato</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo (trim($info['persla_montocontrato']) != '') ? 'S./ '.number_format(trim($info['persla_montocontrato']),2) : '-------'; ?>  </span>
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
                <span class="sp12"> <?PHP  echo (trim($info['pefa_observacion']) != '' ) ? trim($info['pefa_observacion']) : '--------'; ?>  </span>
        </td>
    </tr>


    <tr height="30" class="row_static_form">
        <td>
             <span class="sp12b">Documento</span>
        </td>
         <td>
             <span class="sp12b">:</span>
        </td>
       <td>
                <span class="sp12"> <?PHP  echo (trim($info['documento']) != '' ) ? trim($info['documento']) : '--------'; ?>  </span>
        </td>
    </tr>
 
   
    
</table>

<div style="margin: 5px 0px 0px 0px;">
      
<table class="_tablepadding2"> 

    <tr class="tr_header_celeste">
        <td width="30">#</td>
        <td width="300">Descripcion</td>
        <td width="60" align="center">Ver</td>
        <td width="60" align="center">Borrar</td>
    </tr>

<?PHP  
    $c = 0;
    foreach($documentos as $doc){
  
        $c++;  
        ?> 
         <tr class="row_form2">
             <td align="center">  <span class="sp11">  <?PHP echo $c; ?> </span> </td>
             <td>  <span class="sp11">  <?PHP echo (trim($doc['doc_descripcion']) != '' ) ? trim($doc['doc_descripcion']) : '---------'; ?> </span> </td>
             <td align="center">    <a  class="sp11" href="docsmpi/escalafon/<?PHP echo trim($doc['doc_path']); ?>" target="_blank" > Ver </a>  </td>
             <td align="center">
                  <a href="#" onclick="Persona.quitar_documento('<?PHP echo $doc['doc_key'] ?>','view_situacion_laboral');"> Quitar </a>  
             </td>
         </tr>  
        <?PHP 
 
    }
 ?>
</table>
<?php 
     if(sizeof($documentos) == 0) echo ' <span class="sp11"> No hay documentos adjuntos</span>';
?>

</div>


</div>
 
 </div>