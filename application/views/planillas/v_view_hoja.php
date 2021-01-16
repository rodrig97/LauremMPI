<div  data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"headline", liveSplitters:true' style="width: 890px; height: 450px; ">
 
 	  <div  dojoType="dijit.layout.ContentPane" 
            splitter="false" 
             region="top" 
            data-dojo-props='region:"top" ' style="height: 40px;">
  
  
             <table>
                   <tr>
                      <td width="35"> <span class="sp12b"> Codigo    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70"> <span class="sp12"> <?PHP echo  (trim($hoja_info['hoa_codigo']) == '' ) ? '------'  : trim($hoja_info['hoa_codigo']);  ?>   </span> </td>
                       <td colspan="9"> </td> 

                   </tr>   
                  <tr>
                      <td width="35"> <span class="sp12b"> Desde    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechaini'])); ?>   </span> </td>

                      <td width="35"> <span class="sp12b"> Hasta    </span> </td>
                      <td width="10" align="center">  <span class="sp12b"> :       </span> </td>
                      <td width="70" align="left"> <span class="sp12">  <?PHP echo _get_date_pg(trim($hoja_info['hoa_fechafin'])); ?>    </span> </td>

                      <td width="94"> <span class="sp12b"> Tipo Trabajador    </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="180"> <span class="sp12">  <?PHP echo trim($hoja_info['tipo_planilla']); ?>    </span> </td>

                     
                  
                      <td width="75"> <span class="sp12b"> Descripcion </span> </td>
                      <td width="10" align="center"> <span class="sp12b"> :       </span> </td>
                      <td width="250"> <span class="sp11">  <?PHP echo  (trim($hoja_info['hoa_descripcion']) == '' ) ? '-------------'  : trim($hoja_info['hoa_descripcion']); ?>    </span> </td>
  
                  </tr> 
                
             </table>
 
 
    </div>


     <div dojoType="dijit.layout.TabContainer" splitter="true"   data-dojo-props='region:"center" ' style="padding: 0px 0px 0px 0px">
           <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Hoja</span>">   

                 <div class="dvcontainer_calendar"> 
            

                 <?PHP 
                     $this->load->view('planillas/v_asistencias_hojacalendario');
                 ?>

               </div>    
           </div>
          <div  dojoType="dijit.layout.ContentPane" title="<span class='titletabname'> Resumen</span>">   

                <div class="dvcontainer_calendar"> 
                 <?PHP 
                 //  $this->load->view('planillas/v_asistencias_hojaresumen');
                 ?>
                 </div>    
           </div>

    </div>
 


  <?PHP if($show_extra){ ?> 
  
	 <div  dojoType="dijit.layout.ContentPane" 
            splitter="false" 
             region="bottom" 
            data-dojo-props='region:"bottom" ' style="height: 26px; padding: 3px 3px 3px 0px;">


            	<div align="right">

            			  <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('remove.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                           Planillas.Ui.btn_desvincular_hoja(this,evt);   
                                    </script>
                                    <label class="sp11">
                                           Desvincular hoja de planilla
                                    </label>
                          </button>

    					           <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                                  <?PHP 
                                     $this->resources->getImage('printer.png',array('width' => '14', 'height' => '14'));
                                  ?>
                                    <script type="dojo/method" event="onClick" args="evt">
                                            Asistencias.view_impresion('<?PHP echo trim($hoja_info['hoa_key']); ?>');
                                    </script>
                                    <label class="sp11">
                                          Imprimir
                                    </label>
                          </button>
            	</div>

     </div>


     <?PHP }  ?>
</div>