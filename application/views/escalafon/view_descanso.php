<div class="window_container">
    
    <div id="dvViewName" class="dv_view_name">
        Detalle del Descanso Médico
    </div>

    <div class="dv_form_bceleste"> 


        <div> 
           <input class="data" type="hidden" value="descanso-<?PHP echo trim($info['perdm_id']); ?>" />   

           <?PHP 
                if( $this->user->has_key('TRABAJADOR_DESCANSOMEDICO_EDITAR') )
                { 
           ?>

                   <button class="btnproac_regacti" dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                         <?PHP 
                              $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
                         ?>
                         <label class="lbl10"> Adjuntar documento </label>
                        <script type="dojo/method" event="onClick" args="evt">
                             Persona.Ui.btn_viewadjuntar_click(this,evt,'view_descanso');
                        </script>
                   </button>

           <?PHP 

              }
           ?>
        </div>


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
                     <span class="sp12b">Documento</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP  echo trim($info['perdm_documento']); ?> </span>


                 <!--     <span class="sp12b">  Autoriza: </span>

                     <span class="sp12"> <?PHP  echo trim($info['perdm_emisor']); ?> </span>
 -->

                </td>
            </tr>
           
            <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b"> Desde</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP  echo _get_date_pg(trim($info['perdm_fechaini'])); ?>   </span>
                </td>
            </tr>
            <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b"> Hasta</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP  echo _get_date_pg(trim($info['perdm_fechafin'])); ?>   </span>
                </td>
            </tr>
            <tr height="30" class="row_static_form">
                <td>
                     <span class="sp12b"> Número de Días</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP  echo $info['dias']; ?>   </span>
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
                     <span class="sp12"> <?PHP  echo  $info['tipo']; ?>   </span>
                </td>
            </tr>
           
            <tr height="30" class="row_static_form">
                 <td>
                      <span class="sp12b">Descripcion / Obs</span>
                </td>
                 <td>
                     <span class="sp12b">:</span>
                </td>
               <td>
                     <span class="sp12"> <?PHP  echo (trim($info['perdm_obs']) != '') ? trim($info['perdm_obs']) : '-------- '; ?>   </span>
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
                              <a href="#" onclick="Persona.quitar_documento('<?PHP echo $doc['doc_key'] ?>','view_descanso');"> Quitar </a>  
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