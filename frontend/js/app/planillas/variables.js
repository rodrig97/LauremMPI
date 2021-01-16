/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var Variables = {
    
    Cache: {
      
       id_view_info : null
      
    },
     
     _M : {
          
          
          get_view : new Request({
              
              type : 'text',
              
              method : 'post',
              
              url : 'variables/view',
              
              onRequest: function(){
                  
                  dijit.byId('dv_variable_panelizq').set('content','<div class="sp12b">Cargando</div>');
                  
              },
              
              onSuccess : function (responseText) {
                  dijit.byId('dv_variable_panelizq').set('content',responseText);
                  
              },
              
              onFailure : function (){
                  
              }
             
          }),
          
          registrar : new Laugo.Model({
               
                connect :  'variables/registrar'
               
          }),
          
          actualizar : new Laugo.Model({
               
                connect :  'variables/actualizar'
               
          }),

          eliminar : new Laugo.Model({
               
                connect :  'variables/eliminar'
               
          }),
          
          guardar_detallevariable : new Laugo.Model({
             
              connect : 'variables/guardar_detalleplanilla',
              
              message_function: function(mensaje, data){
                  
              }
            
          }),

          actualizar_valor_forall : new Laugo.Model({

              connect : 'variables/actualizar_detalle_planilla'

          }) 
         
         
     },
     
     _V : {
          
         nueva_variable : new Laugo.View.Window({
            
             connect : 'variables/nueva_variable',
              
              style : {
                   width :  '730px',
                   height:  '480px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Registrar nueva variable ',
              
              onLoad: function(){
                  
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),
        
       
        modificar_variable : new Laugo.View.Window({
            
             connect : 'variables/modificar_variable',
              
              style : {
                   width :  '730px',
                   height:  '480px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Modificar datos de la variable ',
              
              onLoad: function(){
                  
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),
        
        
        add_variable_trabajador : new Laugo.View.Window({
            
             connect : 'detalletrabajador/add_variable_planilla',
              
              style : {
                   width :  '650px',
                   height:  '300px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Gestionar variables de calculo del trabajador ',
              
              onLoad: function(){
                  
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
        }),



        acceso_rapido : new Laugo.View.Window({

              connect : 'variables/acceso_directo',

              style : {
                   width :  '400px',
                   height:  '400px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Gestionar valores de variables (Acceso Directo)',
              
              onLoad: function(){
                  
 
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }


        }) 




     },
     
     
     Ui: {
         
         Grids: {
             
             main : null
             
         },
         
         table_main_ready: function(){
            
              
                app.loader_show();
               
                require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
                      
                             app.loader_hide();             

                             if(dojo.byId('dvtable_variablesview') != null )
                             { 

                                 if(window.escalafon_grid  === null || window.escalafon_grid  === undefined)  window.escalafon_grid  = (declare([Grid, Selection,Keyboard]));


                                                       var store = Observable(Cache(JsonRest({
                                                                target:"variables/provide/main", 
                                                                idProperty: "id",
                                                                sortParam: 'oby',
                                                                query: function(query, options){

                                                                        var data = dojo.formToObject('frm_searchvariable_main');
                                                                        
                                                                        for(x in data) query[x] = data[x];
                                                                        
                                                                        return JsonRest.prototype.query.call(this, query, options);
                                                                }
                                                        }), Memory()));

                                                          var colums = {  

                                                                    col1: {label:'#' , sortable: true},
                                                                    col2: {label: 'Nombre', sortable: false},
                                                                    col3: {label: 'Aplicable a', sortable: false},
                                                                    col4: {label: 'Grupo', sortable: false},
                                                                    col5: {label: 'Perso.', sortable: false},
                                                                    col6: {label: 'Por Defecto', sortable: false}
                                                                  
                                                            };



                                                        Variables.Ui.Grids.main  = new  window.escalafon_grid({
                                                                loadingMessage : 'Cargando',
                                                                store: store,
                                                                getBeforePut: false,
                                                                columns: colums 


                                                        }, "dvtable_variablesview");

                                              if( Variables.Ui.Grids.main != null)
                                              {
                                                 Variables.Ui.Grids.main.refresh();
                                              }          
                              }



                });

             
            
            
         },
         
         btn_getview_variable_editar : function(btn,evt){
           
               var var_k = dojo.query('.hdvariview_id', dojo.byId('dv_variable_info_detalle'))[0].value;
            
               Variables._V.modificar_variable.load({'view' : var_k });
            
           
         },
         
         btn_registrar_click: function(btn,evt){
             
              
              var data = dojo.formToObject('form_nueva_variable');
               
              var a = '';
              
              dojo.forEach(dijit.byId('selgc_tipoplanilla').get('value'), function(p,i){
                   a+=','+p;
              })

              data.aplicable = a; 

              if(data.grupo == '')
              {
                   data.grupo_label = dijit.byId('selvariablegrupo').get('displayedValue');
              }


              if( Variables._M.registrar.process(data))
              {
                  Variables._V.nueva_variable.close();
                  if(Variables.Ui.Grids.main != null) Variables.Ui.Grids.main.refresh();
              }
              
              
         },
         
          btn_actualizar_click: function(btn,evt){
             
              
              
              var data = dojo.formToObject('form_nueva_variable');
              var a = '';
              
              data.view = dojo.query('.hdobjectkey', btn.parentNode)[0].value;
            
              
              if(data.grupo == ''){
                    
                   data.grupo_label = dijit.byId('selvariablegrupo').get('displayedValue');
              }

              if(confirm('Realmente desea guardar los cambios realizados ? '))
              { 
              
                  if( Variables._M.actualizar.process(data))
                  {
                      Variables._V.modificar_variable.close();
                      if(Variables.Ui.Grids.main != null) Variables.Ui.Grids.main.refresh();
                      Variables.Ui.get_view(Variables.Cache.id_view_info);
                  }
              }
              
         },

         btn_eliminar_click : function(btn,evt){

             
             if(confirm('Realmente desea eliminar la variable, esto podria tener efectos sobre los conceptos relacionados')){

                var view = dojo.query('.hdvariview_id', btn.domNode.parentNode)[0].value;

                if(Variables._M.eliminar.process({ 'view' : view })){
                    Variables.Ui.Grids.main.refresh();
                    Variables._M.get_view.reload();
                }
            }
 


         },
         
         
         btn_filtrartabla_main : function(btn,evt){
             
              Variables.Ui.Grids.main.refresh();
             
         },
         
         
         btn_showaddvariable_click : function(btn,evt){
             
            var ep_k =  dojo.query('.hdpladet_empkey' , btn.domNode.parentNode)[0].value;
            Variables._V.add_variable_trabajador.load({'empleado' : ep_k});
             
         },
         
         
         
          
          btn_save_detallevariable_click: function(btn,evt){
            
                 var data = data || {};
                
                 var tr_row=  btn.domNode.parentNode.parentNode;
                 
                 var var_k = dojo.query('.hdplaev_key',tr_row)[0].value,
                      valor = dijit.byNode(dojo.query('.txtpladet_vari',tr_row)[0]).get('value');
                  
                 
        
                 if(valor!='' && valor != undefined && valor != NaN){ 

                     dijit.byNode(dojo.query('.txtpladet_vari', tr_row )[0]).set('disabled',true);
                     if(Variables._M.guardar_detallevariable.process({'var' : var_k, 'v' : valor})){
                         
                         dojo.query('.hdpladet_varvd', tr_row )[0].value= valor;
                         btn.set('disabled',true);
                         
                     }
                     
                     dijit.byNode(dojo.query('.txtpladet_vari', tr_row )[0]).set('disabled',false);

                }
                 
                // alert(var_k+ " "+ valor);
             
          },
         

         btn_actualizar_valor_forall: function(btn,evt){
            
            var planilla = dojo.byId('hdviewplanilla_id').value,
                variable = dijit.byId('addforall_variable').get('value'),
                data = {},
                valor = dijit.byId('addforall_valorvariable').get('value');
 

            if(variable != '' && (valor !== '' && valor !== undefined && valor !== NaN ) ){

               if(confirm('Realemente desea ACTUALIZAR el valor de la variable  para todos los trabajdores? ')){
 
                       data = {

                           'planilla' : planilla,
                           'variable' : variable,
                           'valor'   : valor

                       } 

                       if(Variables._M.actualizar_valor_forall.process(data)){

                            Conceptos._V.modificacion_valores_planilla.close();
                            Planillas.Ui.Grids.planillas_detalle.refresh();
                            dijit.byId('dv_vipla_detalle').set('content','');
                       } 
                   
               }

            }
            else{
                alert('Verifique los valores');
            }

         },
 
         
         get_view : function(variable){
             
             Variables.Cache.id_view_info = variable;
             
             Variables._M.get_view.send({'vari' : variable});
         }
         
     }
    
}