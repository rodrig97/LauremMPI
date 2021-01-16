

var Permisos = {
        
      Cache: {
             
             
      }, 
        
      _M: {


           registrar_solicitud : new Laugo.Model({
                
                 connect :  'permisos/registrar_solicitud'
                
           }),
  
           registrar_papeleta : new Laugo.Model({
                
                 connect :  'permisos/registrar_papeleta'
                
           }),


           registrar : new Laugo.Model({
                
                 connect :  'variables/registrar'
                
           }), 

           aprobar : new Laugo.Model({

                 connect : 'permisos/aprobar'

           }), 

           registrar_retorno : new Laugo.Model({

                 connect : 'permisos/registrar_retorno'

           }),
 
           eliminar : new Laugo.Model({
                
                 connect :  'permisos/eliminar'
                
           })


      },

      _V : {
 
            nueva_solicitud : new Laugo.View.Window({
                 
                connect : 'permisos/nueva_solicitud',
                
                style : {
                     width :  '650px',
                     height:  '380px',
                     'background-color'  : '#FFFFFF'
                },
                
                title: 'Registrar nueva papeleta de salida',
                
                onLoad: function(){
                          
                         // Solicitante  : 
                         // Jefe inmediato que autoriza : 
                         // Documento de referencia
                         // Motivo 
                         // Lugar de destino 
                         // Hora de salida 
                         // Observacion 


                         require([ "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                   function(declare, JsonRest, Observable, Cache, Memory){
                         
                                  var memoryStore = new Memory({});
                                  var restStore = new JsonRest({

                                           target:"escalafon/provide/individuos/todos", 
                                           idProperty: "value",
                                           sortParam: 'oby',
                                           query: function(query, options){
                                                

                                              
                                               return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                           }

                                     }); 

                                   Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                                   Persona.Stores.individuos.query({});
 
                                   dijit.byId('selsolicitudper_autoriza').set('store',Persona.Stores.individuos);

                                   dijit.byId('selsolicitudper_solicita').set('store',Persona.Stores.individuos);

                        });

                }

            }),


            
            nuevo_permiso : new Laugo.View.Window({
                 
                connect : 'permisos/nuevo_permiso',
                
                style : {
                     width :  '650px',
                     height:  '380px',
                     'background-color'  : '#FFFFFF'
                },
                
                title: 'Registrar nueva papeleta de salida',
                
                onLoad: function(){
                          
                         // Solicitante  : 
                         // Jefe inmediato que autoriza : 
                         // Documento de referencia
                         // Motivo 
                         // Lugar de destino 
                         // Hora de salida 
                         // Observacion 


                         require([ "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                   function(declare, JsonRest, Observable, Cache, Memory){
                         
                                  var memoryStore = new Memory({});
                                  var restStore = new JsonRest({

                                           target:"escalafon/provide/individuos/todos", 
                                           idProperty: "value",
                                           sortParam: 'oby',
                                           query: function(query, options){
                                                
                                                if (dojo.byId('HdPermisoNuevoTipoTrabajador')!=null) {
                                                  
                                                  var plati_id = dojo.byId('HdPermisoNuevoTipoTrabajador').value;
                                                  query.plati_id = plati_id;
                                                  
                                                }
                                              
                                               return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                           }

                                     }); 

                                   Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                                   Persona.Stores.individuos.query({});
            
                                   dijit.byId('selsolicitudper_autoriza').set('store',Persona.Stores.individuos);

                                   dijit.byId('selsolicitudper_solicita').set('store',Persona.Stores.individuos);

                       });


                       dijit.byId('dvnuevopermiso_fecha').set('value',new Date());
                }

            }),


            permisos_registrados : new Laugo.View.Window({
                 
                connect : 'permisos/permisos_registrados',
                
                style : {
                     width :  '820px',
                     height:  '500px',
                     'background-color'  : '#FFFFFF'
                },
                
                title: 'Permisos registrados ',
                
                onLoad: function(){
                          
                         // Solicitante  : 
                         // Jefe inmediato que autoriza : 
                         // Documento de referencia
                         // Motivo 
                         // Lugar de destino 
                         // Hora de salida 
                         // Observacion 


                         require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                       function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                    if(dojo.byId('tablapermisos_registrados') != null )
                                    { 
                                             
                                              if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                                       
                                            
                                               var store_trabajadores = JsonRest({
                                                       target: app.getUrl() +"permisos/get_solicitudes", 
                                                       idProperty: "id",
                                                       sortParam: 'oby',
                                                       query: function(query, options){

                                                              var data = dojo.formToObject('form_permisos_registrados');
                                                               
                                                               for(d in data){
                                                                   query[d] = data[d];
                                                               } 
                                                                
                                                               return JsonRest.prototype.query.call(this, query, options);
                                                       }
                                               });

                                                var colums = { 
                                                            
                                                           
                                                            col1: {label: '#', sortable: true},      
                                                            col2: {label: 'Trabajador', sortable: false},
                                                            col3: {label: 'DNI', sortable: false},
                                                            col4: {label: 'Dia', sortable: false},
                                                            col7: {label: 'Motivo', sortable: false},  
                                                            col5: {label: 'Salida', sortable: false},
                                                            col6: {label: 'Retorno', sortable: false}
                                                          
                                                    };
                                                

                                                   Permisos.Ui.Grids.permisos_registrados = new  (declare([Grid, Selection,Keyboard]))({
                                                           
                                                           store: store_trabajadores,
                                                           loadingMessage : 'Cargando',
                                                           getBeforePut: true,
                                                           columns: colums,
                                                           pagingLinks: false,
                                                           pagingTextBox: true,
                                                           firstLastArrows: true,
                                                           rowsPerPage : 50


                                                   }, "tablapermisos_registrados");

                                                   Permisos.Ui.Grids.permisos_registrados.refresh();
                           
                                     }


                                    var memoryStore = new Memory({});
                                    var restStore = new JsonRest({

                                             target:"escalafon/provide/individuos/todos/no_especificar", 
                                             idProperty: "value",
                                             sortParam: 'oby',
                                             query: function(query, options){
                                                  
                                                  
                                                 var plati_id = dojo.byId('HdPermisosRegistroTipoTrabajador').value;

                                                 query.plati_id = plati_id;


                                                 return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                             }

                                       }); 

                                     Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                                     Persona.Stores.individuos.query({}); 
                                     dijit.byId('selspermisoaprobacion_solicita').set('store',Persona.Stores.individuos);
                         
                         
                                       
                           });
 
                      var strDate = dojo.byId('hdPermisosRegistrados_fechadesde').value;
                      var dateParts = strDate.split("-");
                      var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                     
                      dijit.byId('calPermisosRegistrados_desde').set('value', date);
                      dijit.byId('calPermisosRegistrados_hasta').constraints.min = date;
                      
                      strDate = dojo.byId('hdPermisosRegistrados_fechahasta').value;
                      dateParts = strDate.split("-");
                      date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]); 
                      
                      dijit.byId('calPermisosRegistrados_hasta').set('value', date);



                }

            }),
  
           
           visualizar_permiso : new Laugo.View.Window({
                
               connect : 'permisos/visualizar_permiso',
               
               style : {
                    width :  '470px',
                    height:  '320px',
                    'background-color'  : '#FFFFFF'
               },
               
               title: ' Papeleta de salida ',
               
               onLoad: function(){
                         
                          // Solicitante  : 
                          // Jefe inmediato que autoriza : 
                          // Documento de referencia
                          // Motivo 
                          // Lugar de destino 
                          // Hora de salida 
                          // Observacion 


                          require([ "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                    function(declare, JsonRest, Observable, Cache, Memory){
                          
                                   var memoryStore = new Memory({});
                                   var restStore = new JsonRest({

                                            target:"escalafon/provide/individuos/todos", 
                                            idProperty: "value",
                                            sortParam: 'oby',
                                            query: function(query, options){
                                                 

                                               
                                                return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                            }

                                      }); 

                                    Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                                    Persona.Stores.individuos.query({});
             
                                    dijit.byId('selsolicitudper_autoriza').set('store',Persona.Stores.individuos);

                                    dijit.byId('selsolicitudper_solicita').set('store',Persona.Stores.individuos);

                        });


                        dijit.byId('dvnuevopermiso_fecha').set('value',new Date());
               }

           }),

 
           
           ver_detalle_panel : new Request({
               
      
               method: 'post',
               
               url : 'permisos/ver_detalle',
               
               onRequest : function(){
                    app.loader_show(); 
               },
               
               onSuccess  : function(response){
                   
                    app.loader_hide(); 
                    
                    dijit.byId('permisoli_detalle').set('content', response);
                    
                    (function(){
                     
                        var momentoActual = new Date();
                        var hora = momentoActual.getHours();
                        var minuto = momentoActual.getMinutes();
                        var segundo = momentoActual.getSeconds();

                        hora = (hora < 10 ) ? ('0'+ hora) : hora;
                        minuto = (minuto < 10 ) ? ('0'+ minuto) : minuto;
                        segundo = (segundo < 10 ) ? ('0'+ segundo) : segundo;
 
                        var horaImprimible = "T"+hora+":"+minuto+":"+segundo; 
    
                        if(dijit.byId('selsolicitudper_horaretorno') != null )
                        {
                            dijit.byId('selsolicitudper_horaretorno').set('value', horaImprimible);
                        }

                      }());
 
                     
               },
               
               onFailure : function(){
                   
               } 
               
           }),
 

           detalle_del_dia : new Laugo.View.Window({
                
               connect : 'permisos/detalle_del_dia',
               
               style : {
                    width :  '530px',
                    height:  '350px',
                    'background-color'  : '#FFFFFF'
               },
               
               title: ' Detalle del día ',
               
               onLoad: function(){
                     
                        
                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                                       function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                if(dojo.byId('tablepermisosdeldia') != null ){ 

                                      if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                         var store = Observable(Cache(JsonRest({
                                                                     target: app.getUrl() +"permisos/get_permisos_dia", 
                                                                     idProperty: "id",
                                                                     sortParam: 'oby',
                                                                     query: function(query, options){


                                                                             var data = dojo.formToObject('formpermisosdetalledia');

                                                                             for(x in data ){
                                                                               query[x] = data[x];
                                                                             }
                                                                              
                                                                             return JsonRest.prototype.query.call(this, query, options);
                                                                     }
                                                          }), Memory()));

                                                           var colums = { // you can declare columns as an object hash (key translates to field)
                                                                    // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                     col1: {label: '#', sortable: true},
                                                                     col2: {label: 'H.Salida', sortable: false},
                                                                     col3: {label: 'H.Retorno', sortable: false}, 
                                                                     col4: {label: 'Motivo', sortable: false},
                                                                     col5: {label: 'T.Min', sortable: false} 
                                                                   
                                                         };

                                                         Planillas.Ui.Grids.permisos_detalledeldia  = new  window.escalafon_grid({
                                                                 loadingMessage : 'Cargando',
                                                                 store: store,
                                                                 getBeforePut: false,
                                                                 columns: colums 


                                                         }, "tablepermisosdeldia");

                                             
                                   }
      
                        });

               }

           })


      },

      Ui : {

          Grids : {}

      }

}