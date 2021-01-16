<table class="_tablepadding4">

  <tr>
  	 <td width="35">
  	     <span class="sp11b"> Tarea </span> 
     </td>
  	 <td width="5"> 
  	 	   <span class="sp11b"> : </span>
  	 </td>
  	 <td colspan="4">
         <span class="sp11"> 
    	 		 <?PHP 
             echo trim($info['tarea_codigo']).'-'.trim($info['tarea_nombre']);
           ?>
         </span>
  	 </td>
  </tr>

  <tr>
     <td>
         <span class="sp11b"> Desde </span> 
     </td>
     <td> 
         <span class="sp11b"> : </span>
     </td>
     <td width="60">
         <span class="sp11"> 
           <?PHP 
             echo trim($info['fecha_inicio']);
           ?>
         </span>
     </td>
     <td width="40">
           <span class="sp11b"> Hasta </span> 
       </td>
       <td width="10"> 
           <span class="sp11b"> : </span>
       </td>
       <td>
           <span class="sp11"> 
             <?PHP 
               echo trim($info['fecha_termino']);
             ?>
           </span>
       </td>
   
  </tr>


</table>



<div id="table_trabajadores_tareo" style="margin:10px 0px 0px 6px;"> </div>


 