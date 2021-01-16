<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="window_container">

    <?PHP
       if( trim($config['registro_asistencia_diario']) != '1' )
       {
            $fechamin = $hoja_info['hoa_fechaini'];
            $fechamax = $hoja_info['hoa_fechafin'];
       }  
       else
       {
            
            $hoy = date('Y').'-'.date('m').'-'.date('d');
            $fechamin = $hoy;
            $fechamax = $hoy;

            // Si la fecha actual es posterior al ultimo dia entonces solo se pueden agregar al ultimo día

            if( strtotime(date($hoy)) > strtotime($hoja_info['hoa_fechafin']) )
            {
                $fechamin = $hoja_info['hoa_fechafin'];
                $fechamax = $hoja_info['hoa_fechafin']; 
            }

       }

    ?>
    <input type="hidden" id="hddetallefechamin" value="<?PHP  echo $fechamin;   ?>"/>
    <input type="hidden" id="hddetallefechamax" value="<?PHP  echo $fechamax;   ?>"/>



    <input type="hidden" id="hdad_asistencia_key" value="<?PHP echo trim($hoja_info['hoa_key']); ?>" />

    <div class="dv_busqueda_personalizada">
         <table class="_tablepadding2">
             <tr>
                 <td width="50"> <span class="sp12"> Asistencia </span> </td>
                 <td width="10"> <span class="sp12"> : </span> </td>
                 <td width="170"> <span class="sp12b">  Del  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechaini'])).' al '._get_date_pg(trim($hoja_info['hoa_fechafin']));   ?> </span>  </td>
                 <td width="20"> <span class="sp12"> de  </span> </td>
                 <td width="10"> <span class="sp12"> : </span> </td> 
                 <td width="400"> <span class="sp12b"> <?PHP echo  trim($hoja_info['tipo_planilla']);   ?>  </span> con <span id="spcantemppla" class="sp12b" ><?PHP echo (trim($hoja_info['cant_trab']) == '') ? '0' : trim($hoja_info['cant_trab']);  ?></span> <span class="sp12">  trabajadores asignados </span> </td>
             </tr>
         </table>    
    </div>


 <div class="dvmb10"> 

    <div data-dojo-type="dijit.form.DropDownButton" >

             <span class="sp12b">
                  <?PHP 
             $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
         ?>

                    Parametros de Busqueda

             </span>
            <div id="tooltipDlg" data-dojo-type="dijit.TooltipDialog" data-dojo-props='title:"Parametros de busqueda en el registro de trabajadores"'>

                <div class="dv_formu_find_tt">
                      <form id="form_agregardetalle_busqueda"  data-dojo-type="dijit.form.Form">   
                            <input type="hidden" name="from"  value="hojaasistencia"  />
                            <table class="_tablepadding2" style="width:100%">
                                  <tr height="30" class="row_form" >
                                          <td colspan="7"> <span class="sp12b">Parametros de busqueda</span></td>
                                  </tr>

                                  <tr height="30" class="row_form" >
                                          <td width="110"> <span class="sp12b"> Régimen </span></td>
                                          <td width="20">:</td>
                                          <td colspan="7"> 
                                               <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"situlaboral" ' class="formelement-35-12" style="width:220px; font-size:11px;">
                                                      
                                                     <?PHP 
                                                       foreach($tipo_empleados as $tipoemp){
                                                            if(trim($tipoemp['id']) == $hoja_info['plati_id']) echo '<option value="'.trim($tipoemp['id']).'" selected >'.trim($tipoemp['label']).'</option>';
                                                        }
                                                     ?>
                                              </select>
                                          </td>
                                     </tr>
                                 <tr height="30"  class="row_form"> 
                                      <td> <span class="sp12b">Dependencia</span></td>
                                      <td>:</td>
                                       <td colspan="7"> 
                                          <select data-dojo-type="dijit.form.FilteringSelect" data-dojo-props=' name:"dependencia",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "La dependencia no existe"   ' style="width: 300px; font-size:11px;">
                                                  <option value="0" selected="selected"> No Especificar </option>
                                               <?PHP 
                                                    foreach($dependencias as $depe){

                                                ?>
                                                    <option  value="<?PHP echo trim($depe['area_id']);  ?>"> <?PHP echo trim($depe['area_nombre']); ?> </option>

                                               <?PHP } ?>
                                          </select> 

                                      </td>
                                 </tr>
                                  <tr height="30"  class="row_form">  
                                              <td> <span class="sp12b">Cargo</span></td>
                                              <td>:</td>
                                               <td colspan="7"> 
                                                   <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"cargo" ' style="width: 150px; font-size:11px;">
                                                                 <option value="0" selected="selected"> No Especificar </option>
                                                               <?PHP 
                                                                    foreach($cargos as $cargo){
                                                                ?>
                                                                    <option  value="<?PHP echo trim($cargo['cargo_id']);  ?>"  > <?PHP echo $cargo['cargo_nombre']; ?> </option>

                                                               <?PHP } ?>
                                                   </select> 
                                              </td>
                                  </tr>
                                  <tr height="30"  class="row_form"> 
                                      <td width="100"> <span class="sp12b">Apellido Paterno</span></td>
                                      <td width="10">:</td>
                                      <td width="105"> 
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'paterno'" class="formelement-100-11" />

                                      </td>

                                      <td width="100"> <span class="sp12b">Apellido Materno </span></td>
                                      <td width="10">:</td>
                                      <td width="105"> 
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'materno'" class="formelement-100-11" />

                                      </td>

                                      <td width="60"> <span class="sp12b">Nombres </span></td>
                                      <td width="10">:</td>
                                      <td width="105"> 
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'nombres'" class="formelement-100-11" />

                                      </td>
                                 </tr>
                                   <tr height="30"  class="row_form"> 
                                      <td> <span class="sp12b">DNI</span></td>
                                      <td>:</td>
                                       <td colspan="7"> 
                                          <input type="text" dojoType="dijit.form.TextBox" data-dojo-props="name:'dni'" class="formelement-80-11" />
                                          <label class="lbl_submensaje"> (*) Al especificar este campo no se consideraran los demas en la busqueda</label>
                                      </td>
                                 </tr>
                                 <tr height="30"  class="row_form">  
                                   <td> <span class="sp12b">Mostrar</span></td>
                                    <td>
                                        : 
                                    </td>
                                    <td colspan="7"> 
                                     <!--    <input name="vigente" type="checkbox" value="1" data-dojo-type="dijit.form.CheckBox" data-dojo-props=" name:'vigente'"  checked="checked" />
                                      -->
                                         <select data-dojo-type="dijit.form.Select" data-dojo-props=' name:"vigente" ' class="formelement-35-11" style="width:50px;">
                                              <!--   <option value="99"> No especificar </option> -->
                                                <option value="1" selected="selected"> Activos </option>
                                             <!--    <option value="0"> Inactivos </option> -->
                                        </select>
                                    </td>
                                 </tr>
                                 <tr height="30">
                                     <td> </td>
                                     <td> </td>
                                      <td colspan="7"> 

                                          <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                                  <?PHP 
                                                     $this->resources->getImage('search_m.png',array('width' => '14', 'height' => '14'));
                                                  ?>
                                                     <label class="lbl10">Realizar Busqueda</label>
                                                      <script type="dojo/method" event="onClick" args="evt">
                                                              Asistencias.Ui.btn_filtrardetalle_click(this,evt);
                                                    </script>
                                           </button>

                                    </td>
                                 </tr>

                            </table>
                      </form>
                  </div>


            </div>
    </div> 
</div>    
    
<div id="dv_asistencia_adddetalle_table"> 
    
    
</div>

  <div class="dv_busqueda_personalizada_pa2" style="margin-top:8px; padding-left: 10px;">

   <span class="sp12b"> Fecha de inicio de trabajo: </span>
<!--
 <div id="calasis_addetini" data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechainiciotrabajo", value:"<?PHP echo _get_date_pg(trim($hoja_info['hoa_fechaini'])); ?>",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"
                                            
                                              onChange=" "
                                            >
                                        </div> -->


       <div id="calasis_addetini"  data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechainiciotrabajo", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11"

                                            onChange="dijit.byId('calasis_addetfin').constraints.min = this.get('value');  "
                                           
                                            >
       </div> 

       <span class="sp12b"> Hasta: </span>
 
       <div id="calasis_addetfin"  data-dojo-type="dijit.form.DateTextBox"
                                            data-dojo-props='type:"text", name:"fechafintrabajo", value:"",
                                             constraints:{datePattern:"dd/MM/yyyy", strict:true},
                                            lang:"es",
                                            required:true,
                                            promptMessage:"mm/dd/yyyy",
                                            invalidMessage:"Fecha invalida, utilize el formato mm/dd/yyyy."' class="formelement-80-11">
       </div> 

       <?PHP 
         if($hoja_info['hoa_tienecategorias'] == '1')
         { 
       ?>
       <span class="sp12b"> Categoria: </span>
       <select id="calasis_categoria" data-dojo-type="dijit.form.Select"  data-dojo-props=' name:"categoria" '  class="formelement-150-11" style="width:150px; font-size:11px;">
                
          <?PHP
                
                if( trim($config['categoria_noespecificar']) == '1' )
                {

          ?>
                    <option value="0"> No especificar </option>
                 
         <?PHP 
                }
                
               foreach($categorias as $reg)
               {
                   echo  " <option value='".$reg['platica_key']."'>".$reg['platica_nombre']."</option> ";   
               }

         ?>
       </select> 

       <?PHP 

        } 

        ?>
 

       <?PHP
       
       if( trim($config['grupo_trabajadores']) == '1' )
       {

       ?>
         <span class="sp12b"> Grupo: </span>
         <select  id="selasis_addet_grupo"  data-dojo-type="dijit.form.FilteringSelect"  data-dojo-props=' name:"grupoempleado",autoComplete:false, highlightMatch: "all",  queryExpr:"*${0}*", invalidMessage: "Grupo no registrado"   '  class="formelement-150-11">
                 <option value="0"> No Especificar </option> 
                <?PHP 
                   foreach ($grupos as $grupo)
                   {
                      echo  '<option value="'.$grupo['hoagru_key'].'"> '.$grupo['hoagru_nombre'].' </option> ';
                   }
                ?>
         </select> 
      <?PHP 
        }
       ?>
</div>
 
 


<div> 


   <button  dojoType="dijit.form.Button" class="dojobtnfs_12"  > 
          <?PHP 
             $this->resources->getImage('add.png',array('width' => '14', 'height' => '14'));
          ?>
             <label class="lbl11">Agregar a hoja de asistencia</label>
              <script type="dojo/method" event="onClick" args="evt">
                  //  Persona.Ui.btn_registrarcomserv_click(this,evt);
                  Asistencias.Ui.btn_addempleado_hoja_click(this,evt);
            </script>
   </button>
</div>


</div>