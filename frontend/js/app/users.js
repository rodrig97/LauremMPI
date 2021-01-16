 
 
 var Users = {
      
     _Models : {


              actualizar_llave : new Laugo.Model({

                 connect  : 'usuarios/actualizar_llave',

                 message_function : function()
                 {
                  
                 }

              }),


              registrar_usuario : new Laugo.Model({

                 connect  : 'usuarios/registrar_nuevo' 

              }),
     
              rq_get_new_view : new Request({

                    url:  'users/view_nuevo',

                    method: 'post',

                    type : 'text',

                    onRequest: function(){
                        app.loader_show();

                    },

                    onSuccess: function(responseText){
                        app.loader_hide();

                         Users.Ui.wd_new_user = new dijit.Dialog({
                                        title: "Registro de un nuevo usuario",
                                        content:  responseText, 
                                        style: "width: 500px; height: 450px; background-color:#FFFFFF;",
                                        onCancel : function(){

                                                dijit.registry.destroy_childrens(  Users.Ui.wd_new_user.domNode );
                                             
                                        }
                                    });


                         Users.Ui.wd_new_user.show();
                    },

                    onFailure: function(){
                         alert('Error on acciones.get_acciones [FAILURE]');
                         app.loader_hide();
                    }

               })
 
     },

     _V : {


            permisos_usuario : new Request({

                  url:  'usuarios/detalle_usuario',

                  method: 'post',

                  type : 'text',

                  onRequest: function(){
                      app.loader_show();

                  },

                  onSuccess: function(responseText){
                      app.loader_hide();
                      

                      dijit.byId('dv_geusu_panelcenter').set('content', responseText);


                      dojo.forEach(dojo.query('.dijit_checkbox_id'), function(hd_id,ind){
                            
                            var node_checkbox = dijit.byId(hd_id.value);
                           
                            dojo.connect(node_checkbox, 'onChange', function(evt)
                            {
                                 
                              
                                 var  li = node_checkbox.domNode.parentNode;
                                 var  est = node_checkbox.get('value');

                                 var key = node_checkbox.view;

                                 var tipo = node_checkbox.tipo;

                                   
                                 if( est !== false )
                                 {
                                     var desactivado = false;
                                     var check  = true;
                                     var estado = 1;
                                 }
                                 else
                                 {
                                     var desactivado = true;
                                     var check  = false;
                                     var estado = 0;
                                 }

                                 var us = dojo.byId('hddetalleusuario').value;


                                 var data = { 
                                               'view' : key,
                                               'estado' : estado,
                                               'us'     : us,
                                               'tipo'   : tipo
                                            }

                                if(Users._Models.actualizar_llave.process(data))
                                {

                                     dojo.forEach(dojo.query('.dijit_checkbox_id', li), function(hdh_id, ind2){

                                         if( hdh_id != hd_id && tipo == 'menu' )
                                         {  
                                           
                                           var node_checkbox_h = dijit.byId(hdh_id.value);
                                           node_checkbox_h.set('disabled', desactivado);
                                           /*  node_checkbox_h.set('checked', check);*/
                                         }   
                                     });

                                }
                                

                                 


/*
                                  var li = node_checkbox.domNode.parentNode.parentNode,
                                      est =  node_checkbox.get('value'),
                                      spconcepto = dojo.query('.sppladet_nombreconc',row)[0],
                                      conc_k = dojo.query('.hdpladet_conck',row)[0].value,
                                      ns = (est) ?  '1' : '0';*/

 
                                   
                                 /* 
                                 node_checkbox.set('disabled', true);
                            
                                 if( Conceptos.update_detalleplanilla({'conc_k' : conc_k, 'ns' : ns }))
                                 { 
                                
                                      if(est)
                                      {
                                            dojo.setStyle(spconcepto,'textDecoration', 'none');
                                            dojo.setStyle(spconcepto,'color', '#000000'); 
                                      }
                                      else
                                      {
                                           dojo.setStyle(spconcepto,'textDecoration', 'line-through');
                                           dojo.setStyle(spconcepto,'color', '#990000'); 
                                      }

                                 }
                                 else{
                                   
                                 }
                             
                                 node_checkbox.set('disabled', false);    */
                                 
                                 
                            });  
                            
                      
                      });



                  },

                  onFailure: function(){
                       alert('Error on acciones.get_acciones [FAILURE]');
                       app.loader_hide();
                  }

             }),

 
            nuevo_usuario : new Laugo.View.Window({
                 
                connect : 'usuarios/nuevo',
              
                style : {
              
                     width :  '500px',
                     height:  '500px',
                     'background-color'  : '#FFFFFF'
                },
                
                title: ' Registrar nuevo usuario ',
                
                onLoad: function(){
                      

                 
                },
                
                onClose: function(){
                    
                //    alert('ventana cerrada');
                     return true;
                }
                
            }) 
        
             

     }, 
     
     Ui: {

         Grids : {},
         
         wd_new_user : null,

         btn_nuevo_usuario : function(btn, evt)
         {

              var dni = prompt('Ingrese el DNI del nuevo usuario');

              if(dni)
              {
                
                Users._V.nuevo_usuario.load({'view' : dni });
              
              }

         },

         btn_registrar_usuario : function(btn,evt)
         {

              var data = dojo.formToObject('form_usuario_nuevo');


              if( data.modo == '1' && ( data.fechanac == null || data.fechanac == '' || dojo.trim(data.dni) == '' || dojo.trim(data.paterno) == '' || dojo.trim(data.materno) == '' || dojo.trim(data.nombre) == '' ) )
              {

                 alert('El nombre debe estar completo, ademas la fecha de nacimiento es obligatoria ');

              }
              else{

                if( data.modo != '3'  && (dojo.trim(data.usuario) == '' || dojo.trim(data.usuario).length < 6 ) ) 
                {
                   alert('El usuario debe tener al menos 6 caracteres');
                }
                else
                {

                   if(  data.modo != '3'  && ( dojo.trim(data.psw1) == '' ||  dojo.trim(data.psw2) == '' || dojo.trim(data.psw1).length < 8 )  )
                   {
                         alert('La contraseña debe tener al menos 8 caracteres');
                   }
                   else
                   {

                        if( data.modo != '3' && ( dojo.trim(data.psw1) != dojo.trim(data.psw2) ) )
                        {
                            alert('Las contraseñas no coinciden');
                        }
                        else
                        {

                            if(Users._Models.registrar_usuario.process(data))
                            {


                                Users._V.nuevo_usuario.close();

                                Users.Ui.Grids.gestionar_usuarios.refresh();
                                

                            }

                        }
                   }
                }

               
                
              }
         }
     },
     
     
     get_view_new: function(){
         this._Models.rq_get_new_view.send({ajax: 1});
     }
      
 }