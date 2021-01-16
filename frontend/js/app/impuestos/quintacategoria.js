
var QuintaCategoria = {

	_M : {

		registrar_constancia_retencion : new Laugo.Model({
		   connect  : 'quintacategoriarouter/registrar_constancia_retencion'
		}),

		eliminar_constancia_retencion : new Laugo.Model({
		   connect  : 'quintacategoriarouter/eliminar_constancia_retencion'
		})

	},

	_V : {
 
		view_planillatipo_config : new Request({
		         
		     type :  'text',
		     
		     method: 'post',
		     
		     url : 'quintacategoriarouter/configuracion_detalle',
		     
		     onRequest : function(){
		
		         dijit.byId('dvplanillatipo_config_panel').set('content', '<div class="dv_cargando">Cargando..</div>' );
		     },
		     
		     onSuccess  : function(responseText){
		
		         dijit.byId('dvplanillatipo_config_panel').set('content', responseText);


		
		       //   require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
		       //                           function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){



		       //         if(dojo.byId('dvquinta_parametros_proyeccion') != null )
		       //         { 

		       //             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


		       //                    var store = Observable(Cache(JsonRest({
		       //                            target: app.getUrl() +"calculosrouter/get_parametros/", 
		       //                            idProperty: "id",
		       //                            sortParam: 'oby',
		       //                            query: function(query, options)
		       //                            {
		       //                                var data = dojo.formToObject('form_quinta_parametro_nuevo');  
		       //                                for(x in data)
		       //                                {
		       //                                  query[x] = data[x];
		       //                                } 

		       //                                return JsonRest.prototype.query.call(this, query, options);
		       //                            }

		       //                    }), Memory()));


		       //                    var columnas = {  
		                                   
		       //                            col1: {label: '#', sortable: true},
		       //                            col2: {label: 'Variable', sortable: false}                              
		       //                    };
		               

		       //                    QuintaCategoria.Ui.Grids.parametros_proyeccion = new  window.escalafon_grid({
		                             
		       //                                store: store,  
		       //                                loadingMessage : 'Cargando',
		       //                                getBeforePut: false, 
		       //                                columns: columnas,
		       //                                pagingLinks: false,
		       //                                pagingTextBox: true,
		       //                                firstLastArrows: true 

		       //                    }, "dvquinta_parametros_proyeccion");


		       //                    if( QuintaCategoria.Ui.Grids.parametros_proyeccion != null)
		       //                    {
		       //                        QuintaCategoria.Ui.Grids.parametros_proyeccion.refresh();
		       //                    }          
		
		       //         }
 					   


 					   // if(dojo.byId('dvquinta_conceptos_imponibles') != null )
 					   // { 

 					   //     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


 					   //            var store = Observable(Cache(JsonRest({
 					   //                    target: app.getUrl() +"calculosrouter/get_conceptos/", 
 					   //                    idProperty: "id",
 					   //                    sortParam: 'oby',
 					   //                    query: function(query, options)
 					   //                    {
 					   //                        var data = dojo.formToObject('form_quinta_concepto_nuevo');  
 					   //                        for(x in data)
 					   //                        {
 					   //                          query[x] = data[x];
 					   //                        } 

 					   //                        return JsonRest.prototype.query.call(this, query, options);
 					   //                    }

 					   //            }), Memory()));


 					   //            var columnas = {  
 					                       
 					   //                    col1: {label: '#', sortable: true},
 					   //                    col2: {label: 'Concepto ', sortable: false}                              
 					   //            };
 					   

 					   //            QuintaCategoria.Ui.Grids.conceptos_imponibles = new  window.escalafon_grid({
 					                 
 					   //                        store: store,  
 					   //                        loadingMessage : 'Cargando',
 					   //                        getBeforePut: false, 
 					   //                        columns: columnas,
 					   //                        pagingLinks: false,
 					   //                        pagingTextBox: true,
 					   //                        firstLastArrows: true 

 					   //            }, "dvquinta_conceptos_imponibles");


 					   //            if( QuintaCategoria.Ui.Grids.conceptos_imponibles != null)
 					   //            {
 					   //                QuintaCategoria.Ui.Grids.conceptos_imponibles.refresh();
 					   //            }          
 					   
 					   // }
 					   
		

		       //    });

		
		       //    if( dijit.byId('sel_estadodiaplati_visualizar') != null)
		       //    {

		       //        dojo.connect( dijit.byId('sel_estadodiaplati_visualizar'), 'onChange', function(evt){
		                   
		       //              if( Asistencias.Ui.Grids.estados_del_dia_plati != null)
		       //              {
		       //                  Asistencias.Ui.Grids.estados_del_dia_plati.refresh();
		       //              }     
		                      
		       //        });

		       //        dijit.byId('sel_estadodiaplati_visualizar').onChange(); 
		       //    }

		      
		     },
		     
		     onFailure : function(){
		         
		     } 
		     
		 }),

		

		ver_trabajadores_retencion : new Request({
		     
		    method: 'post',
		    
		    url : 'quintacategoriarouter/trabajadores',
		    
		    onRequest : function(){
		         app.loader_show(); 
		    },
		    
		    onSuccess  : function(responseText){
		        
		         app.loader_hide(); 
		         
		         dijit.byId('dvQuintaCategoria_panel').set('content', responseText); 

		         
		         document.getElementById("table_quinta_trabajadores").addEventListener("click",function(e) {
		           // e.target was the clicked element 

		           if (e.target && (e.target.matches("td.mes_detalle") || e.target.parentNode.matches("td.mes_detalle")) ) {
		            
		            	var nodo = ( e.target.parentNode.matches("td.mes_detalle") ) ? e.target.parentNode : e.target;

		            	var indiv_id = nodo.querySelector('.hd_indiv_id').value;
		            	var mes_id = nodo.querySelector('.hd_mes_id').value;   
		            	var anio = nodo.querySelector('.hd_anio').value;   

		            	QuintaCategoria._V.detalle_mes_quinta.load({'trabajador' : indiv_id, 'mes' : mes_id, 'anio' : anio });

		           }


		         });

		         // Retenciones del mes - Documento / Planilla - Grati ETC, 
		    },
		    
		    onFailure : function(){
		         app.loader_hide(); 
		    } 
		    
		}),
 

		
		detalle_mes_quinta : new Laugo.View.Window({
		     
		    connect : 'quintacategoriarouter/detalle_mes_retenciones',
		    
		    style : {
		         width :  '450px',
		         height:  '350px',
		         'background-color'  : '#FFFFFF'
		    },
		    
		    title: ' Registro de retenciones del mes ',
		    
		    onLoad: function(){
		             
		          require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
		                      function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
 

                       if(dojo.byId('dvretenciones_table') != null ){ 

                           if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                  var store = Observable(Cache(JsonRest({
                                          target: app.getUrl() +"quintacategoriarouter/get_retenciones_mes", 
                                          idProperty: "id",
                                          sortParam: 'oby',
                                          query: function(query, options){
                                               
                                                 var data  = dojo.formToObject('form_quinta_detallemes');
                                                 
                                                  for(d in data){
                                                       query[d] = data[d];
                                                  }
                                                          
                                                  return JsonRest.prototype.query.call(this, query, options);
                                          }
                                  }), Memory()));

                                  var colums = { // you can declare columns as an object hash (key translates to field)
                                         // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                          col1: {label:'#', sortable: true},
                                          col2: {label: 'Documento', sortable: true},
                                          col3: {label: 'Codigo', sortable: false},
                                          col4: {label: 'Retencion S./', sortable: false} 
                                        
                                  }; 

                                  QuintaCategoria.Ui.Grids.detalle_retenciones_mes  = new  window.escalafon_grid({
                                          loadingMessage : 'Cargando',
                                          store: store,
                                          getBeforePut: false,
                                          columns: colums,
                                          showFooter :true


                                  }, "dvretenciones_table");

                                  if( QuintaCategoria.Ui.Grids.detalle_retenciones_mes != null) {
                                
                                       QuintaCategoria.Ui.Grids.detalle_retenciones_mes.refresh();
                                       
                                  }          

                        } 

		          });


		           
		     
		    },
		    
		    onClose: function(){
		        
		    //    alert('ventana cerrada');
		         return true;
		    }
		    
		}),


		detalle_retencion : new Laugo.View.Window({
		     
		    connect : 'quintacategoriarouter/detalle_calculo_retencion',
		    
		    style : {
		         width :  '500px',
		         height:  '520px',
		         'background-color'  : '#FFFFFF'
		    },
		    
		    title: ' Detalle del calculo de la retención ',
		    
		    onLoad: function(){
		              
		           
		     
		    },
		    
		    onClose: function(){
		        
		    //    alert('ventana cerrada');
		         return true;
		    }
		    
		}),

 
		detalle_retencion_desdeplanilla : new Laugo.View.Window({
		     
		    connect : 'quintacategoriarouter/detalle_calculo_retencion_planilla',
		    
		    style : {
		         width :  '500px',
		         height:  '440px',
		         'background-color'  : '#FFFFFF'
		    },
		    
		    title: ' Detalle del calculo de la retención ',
		    
		    onLoad: function(){
		              
		           
		     
		    },
		    
		    onClose: function(){
		        
		    //    alert('ventana cerrada');
		         return true;
		    }
		    
		}),


		retenciones_anteriores : new Laugo.View.Window({
		     
		    connect : 'quintacategoriarouter/constancias_de_retencion',
		    
		    style : {
		         width :  '780px',
		         height:  '440px',
		         'background-color'  : '#FFFFFF'
		    },
		    
		    title: ' Constancias de retenciones ',
		    
		    onLoad: function(){
		               
		            
		            require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
		                               function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


		                                if(dojo.byId('dvRetencionesAnteriores') != null ){ 

		                                    	if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


		                                        var store = Observable(Cache(JsonRest({
		                                               target:  app.getUrl() + "quintacategoriarouter/getRetencionesConstancias", 
		                                               idProperty: "id",
		                                               sortParam: 'oby',
		                                               query: function(query, options){

		                                               		var data = dojo.formToObject('formRetencionesAnteriores');

		                                               		for (x in data) {
		                                               			query[x] = data[x];
		                                               		}
		                                                        
		                                                    return JsonRest.prototype.query.call(this, query, options);
		                                               		 
		                                               }
		                                        }), Memory()));


		                                        var colums = {  

		                                               num: {label:'#', sortable: true},
		                                               anio: {label: 'Año', sortable: false},
		                                               trabajador: {label: 'Trabajador', sortable: false},
		                                               descripcion: {label: 'Descripción', sortable: false},
		                                               ingresos: {label: 'Ingresos', sortable: false},
		                                               descuentos: {label: 'Desc.5Ta', sortable: false} 

		                                        };

		                                        QuintaCategoria.Ui.Grids.constancias_retencion  = new  window.escalafon_grid({
		                                               loadingMessage : 'Cargando',
		                                               store: store,
		                                               getBeforePut: false,
		                                               columns: colums 


		                                        }, "dvRetencionesAnteriores");


                                                if ( QuintaCategoria.Ui.Grids.constancias_retencion != null) {
                                                
                                                    QuintaCategoria.Ui.Grids.constancias_retencion.refresh();
                                                 
                                                }          
		                                
		                                }


		                                var memoryStore = new Memory({});
		                                var restStore = new JsonRest({

		                                         target:"escalafon/provide/individuos/todos", 
		                                         idProperty: "value",
		                                         sortParam: 'oby',
		                                         query: function(query, options){
  
  		                                             return dojo.store.JsonRest.prototype.query.call(this, query, options);
		                                         }

		                                 });  

		                                 Persona.Stores.individuos = new Cache(restStore, memoryStore);
		                                 Persona.Stores.individuos.query({});
		                                
		                                 dijit.byId('selTrabajadorRetencionesAnteriores').set('store',Persona.Stores.individuos); 
 
		            });   
		            

		           
		     
		    },
		    
		    onClose: function(){
		        
		    //    alert('ventana cerrada');
		         return true;
		    }
		    
		}),


		nueva_retencion_anterior : new Laugo.View.Window({
		     
		    connect : 'quintacategoriarouter/nueva_retencion_constancia_anterior',
		    
		    style : {
		         width :  '440px',
		         height:  '340px',
		         'background-color'  : '#FFFFFF'
		    },
		    
		    title: ' Nueva Constancia de Retencion ',
		    
		    onLoad: function(){
		           


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
		           
		                      dijit.byId('selTrabajadorRetencionAnteriorNueva').set('store',Persona.Stores.individuos); 

		           });
		           
		     
		    },
		    
		    onClose: function(){
		        
		    //    alert('ventana cerrada');
		         return true;
		    }
		    
		})


	},

	Ui : {
		Grids : {},

		btn_cargar_trabajadores : function(){

			var data = dojo.formToObject('form_quinta_panel');
			console.log('ola');
			QuintaCategoria._V.ver_trabajadores_retencion.send(data);
		}
	}
}