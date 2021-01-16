             

		  	   

             <div class="div_subseccion_pdt">

                  <div class="div_subtitle_pdt"> 
		  	   	    <span class="sp12b"> INGRESOS IMPONIBLES ACTUALMENTE </span>  <span class="sp11"> (Conceptos sumados en el pensionable)</span>
                  </div>   

 				  <table class="_tablepadding2">
 				    <tr class="tr_header_celeste">
 				  		<td width="220"> 
 				  		    <span class="sp11b"> Concepto  </span>	
 				  		 </td>

 				  		 <td width="10"> 
 				  		    <span class="sp11b" align="center"> :  </span>
 				  		 </td>

 				  		 <td width="500">
 				  		    <span class="sp11b"> SUNAT PDT  </span>		
 				  		</td>
 				  	</tr>	  

 				  	<?PHP 

                         if(sizeof($tabla_conceptos_pensionable) >  0)
                         { 
     				  		 foreach ($tabla_conceptos_pensionable as $reg)
     				  		 {
 				    ?>
     				    		 <tr class="tr_row_celeste">
     				    			<td> 
     				    			    <span class="sp11"> <?PHP echo $reg['conc_nombre']; ?>  </span>	
     				    			 </td>
     				    			 <td align="center">
     				    			    <span class="sp11"> :  </span>
     				    			 </td>
     				    			 <td <?PHP if(trim($reg['cosu_codigo']) == '' ||  ($reg['cosu_codigo']) === null ) echo 'style="background-color:#FF9B9B;' ?>>   
     				    			      <span class="sp11"> <?PHP  echo $reg['cosu_codigo'].' - '.$reg['cosu_descripcion']; ?> </span>		
     				    			 </td>
     				     		</tr>	  
 				    <?PHP 
 				  		   }
                         }
                         else{

                             echo ' <td colspan="3"> No se encontraron conceptos imponibles. </td> ';
                         }

 				  	?>

 				  </table>	

		  	   </div> 


                <div class="div_subseccion_pdt">

                         <div class="div_subtitle_pdt"> 

   		  	   		           <span class="sp12b"> INGRESOS NO IMPONIBLES CON CASILLA SUNAT </span>
                        </div>


    				    <table class="_tablepadding2">
    				  	 
                           <tr class="tr_header_celeste">
                                <td width="220"> 
                                    <span class="sp11b"> Concepto  </span>  
                                 </td>

                                 <td width="10"> 
                                    <span class="sp11b" align="center"> :  </span>
                                 </td>

                                 <td width="500">
                                    <span class="sp11b"> SUNAT PDT  </span>     
                                 </td>
                            </tr>     

    				  	<?PHP 
                            if(sizeof($tabla_conceptos_pensionable) >  0)
                            { 
        				  		 foreach ($tabla_conceptos_sincasilla as $reg)
        				  		 {
    				    ?>

        				    	  <tr class="tr_row_celeste">
                                    <td> 
                                        <span class="sp11"> <?PHP echo $reg['conc_nombre']; ?>  </span> 
                                     </td>
                                     <td align="center">
                                        <span class="sp11"> :  </span>
                                     </td>
                                     <td width="500"> 
                                        <span class="sp11"> <?PHP echo $reg['cosu_codigo'].' - '.$reg['cosu_descripcion']; ?> </span>       
                                     </td>
                                 </tr>    

    				    <?PHP 
    				  		    }
                            }
                            else{

                                echo ' <td colspan="3"> No se encontraron conceptos. </td> ';
                            }
    				  	?>

    				  </table>	

   		  	   </div>


              <div class="div_subseccion_pdt" style="overflow:auto;">
 
                       
                    <div class="div_subtitle_pdt" style="width:100%;"> 

                           <span class="sp12b">Fórmula del Pensionable </span>
                   </div>


                       <table class="_tablepadding4">

                              <tr> 
                                  <?PHP 
                                    if(sizeof($ecuacion_pensionable) >  0)
                                    { 

                                         foreach($ecuacion_pensionable as $v){
                                              
                                              echo "<td width='60' align='center'> $v </td>";
                                         }
                                  ?>
                                      <td width='15' align="center"> = </td>
                                      <td width='60' align="center"> TOTAL </td>
                                   
                                  <?PHP 
                                          if($info['total_modificado'] != $info['total']){

                                              echo " <td width='60' align='center'> Total Modificado </td> ";
                                          }

                                    }
                                    else
                                    {
                                          echo ' <td colspan="1"> No especificado. </td> ';
                                    }
                                  ?>  


                              </tr>

                       </table> 

               </div>


                
                <div class="div_subseccion_pdt" style="overflow:auto;">
                
                         
                      <div class="div_subtitle_pdt" style="width:100%;"> 

                             <span class="sp12b">Fórmula de ESSALUD </span>
                     </div>


                         <table class="_tablepadding4">

                                <tr>
                                    <?PHP 
                                      if(sizeof($ecuacion_essalud) >  0)
                                      { 

                                           foreach($ecuacion_essalud as $v){
                                                
                                                echo "<td width='60' align='center'> $v </td>";
                                           }
                                    ?>
                                            <td width='15' align="center"> = </td>
                                            <td width='60' align="center"> TOTAL </td>
                                     
                                    <?PHP 
                                            if($info['total_modificado'] != $info['total']){

                                                echo " <td width='60' align='center'> Total Modificado </td> ";
                                            }
                                      }
                                      else
                                      {
                                            echo ' <td colspan="1"> No especificado. </td> ';
                                      }
                                    ?>  


                                </tr>

                         </table> 

                 </div>

               <!-- -->
                <div class="div_subseccion_pdt" style="overflow:auto;">
                
                         
                      <div class="div_subtitle_pdt" style="width:100%;"> 

                             <span class="sp12b">Fórmula de ONP </span>
                     </div>


                         <table class="_tablepadding4">

                                <tr>
                                    <?PHP 

                                     if(sizeof($ecuacion_onp) >  0)
                                     { 
                                           foreach($ecuacion_onp as $v){
                                                
                                                echo "<td width='60' align='center'> $v </td>";
                                           }
                                    ?>
                                        <td width='15' align="center"> = </td>
                                        <td width='60' align="center"> TOTAL </td>
                                     
                                    <?PHP 
                                        if($info['total_modificado'] != $info['total']){

                                            echo " <td width='60' align='center'> Total Modificado </td> ";
                                        }
                                    }
                                    else
                                    {
                                          echo ' <td colspan="1"> No especificado. </td> ';
                                    }

                                    ?>  


                                </tr>

                         </table> 

                 </div>

                
                <div class="div_subseccion_pdt" style="overflow:auto;">
                
                         
                      <div class="div_subtitle_pdt" style="width:100%;"> 

                             <span class="sp12b">Fórmula de SCTR </span>
                     </div>


                         <table class="_tablepadding4">

                                <tr>
                                    <?PHP 
                                      if(sizeof($ecuacion_sctr) >  0)
                                      { 

                                       foreach($ecuacion_sctr as $v){
                                            
                                            echo "<td width='60' align='center'> $v </td>";
                                       }
                                    ?>
                                        <td width='15' align="center"> = </td>
                                        <td width='60' align="center"> TOTAL </td>
                                         
                                    <?PHP 
                                        if($info['total_modificado'] != $info['total']){

                                            echo " <td width='60' align='center'> Total Modificado </td> ";
                                        }
                                     }
                                     else
                                     {
                                           echo ' <td colspan="1"> No especificado. </td> ';
                                     }
                                    ?>  


                                </tr>

                         </table> 

                 </div>