 

<div data-dojo-type="dijit.form.Form" id="form_tipocat_nueva">

  <table class="_tablepadding4" width="400">
     
      <tr>
          <td width="100">
              <span class="sp12b"> Régimen </span>
          </td>
          <td width="10">
               <span class="sp12b"> : </span>
          </td>
          <td> 
              <?PHP 

                  echo trim($info['plati_nombre']);
              ?>
              <input name="view" type="hidden" value="<?PHP echo $info['plati_id']; ?>" />
          </td>
      </tr>

       <tr>
          <td width="100">
              <span class="sp12b"> Nombre </span>
          </td>
          <td width="10">
               <span class="sp12b"> : </span>
          </td>
          <td>
              <input name="nombre" type="text" data-dojo-type="dijit.form.TextBox" class="formelement-150-12" />
            
          </td>
      </tr>

       <tr>
          <td width="100">
              <span class="sp12b"> Nombre Completo </span>
          </td>
          <td width="10">
               <span class="sp12b"> : </span>
          </td>
          <td>
              <input name="nombrecompleto" type="text" data-dojo-type="dijit.form.TextBox" class="formelement-150-12" />
            
          </td>
      </tr>

       <tr>
          <td width="100">
              <span class="sp12b"> Descripción </span>
          </td>
          <td width="10">
               <span class="sp12b"> : </span>
          </td>
          <td>
              <input name="descripcion" type="text" data-dojo-type="dijit.form.TextBox" class="formelement-250-12" />

          </td>
      </tr>

      <tr>
          <td colspan="3" align="center">

                <button  dojoType="dijit.form.Button" class="dojobtnfs_12" > 
                   <?PHP 
                      $this->resources->getImage('save.png',array('width' => '14', 'height' => '14'));
                   ?>
                     <script type="dojo/method" event="onClick" args="evt">
                        
    
                           var datos = dojo.formToObject('form_tipocat_nueva');

                           if(datos.nombre != '')
                           {

                              if(confirm('Realmente desea registrar esta categoria'))
                              {
                                    console.log(datos);

                                    if(Tipoplanilla._M.registrar_categoria.process(datos))
                                    {

                                         Tipoplanilla._V.nueva_categoria.close();
                                    }
                              }

                           }
                           else
                           {
                              alert('El nombre es obligatorio');
                           }



                     </script>
                     <label class="sp11">
                         Registrar
                     </label>
                </button>

          </td> 
      </tr>

  </table>

</div>