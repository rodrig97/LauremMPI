<div class="window_container">

      <div id="dvViewName" class="dv_view_name">
          
        <table class="_tablepadding2" border="0">

          <tr> 
              <td> 
                   <?PHP 
                             $this->resources->getImage('note.png',array('width' => '22', 'height' => '22'));
                         ?>
              </td>

             <td>
                   Detalle de los permisos del día
              </td>
          </tr>
      </table>
    </div>

    <form id="formpermisosdetalledia" data-dojo-type="dijit.form.Form"> 

         <input type="hidden" value="<?PHP echo $indiv_key; ?>" name="trabajdor" />
         <input type="hidden" value="<?PHP echo $fecha; ?>" name="fecha" />

    </form>

    <div class="dv_busqueda_personalizada" style="margin:5px 0px 0px 0px;">
        <table class="_tablepadding2">
            <tr>
                <td width="110"> 
                     <span class="sp11b">  Trabajador </span>
                </td> 
                <td width="10"> 
                     <span class="sp11b"> : </span> 
                </td> 
                <td> 
                     <span class="sp11"> <?PHP echo $trabajador; ?> </span>   
                </td> 
            </tr> 
            <tr>
                <td> 
                     <span class="sp11b">  Fecha </span>
                </td> 
                <td> 
                     <span class="sp11b"> : </span> 
                </td> 
                <td> 
                     <span class="sp11"> <?PHP echo $fecha_label; ?></span>   
                </td> 
            </tr> 
        </table>  
    </div>

    <div id="tablepermisosdeldia" style="margin:5px 0px 0px 0px;" ></div>

</div>