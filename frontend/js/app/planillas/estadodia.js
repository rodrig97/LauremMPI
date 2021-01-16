

var EstadoDia = {

	_M : {
 
		registrar_dia_estado_trabajador: new Laugo.Model({
		        
		        connect: 'estadodiarouter/registrar_dia_estado_trabajador'
		}),
    
    eliminar_registro: new Laugo.Model({
            
            connect: 'estadodiarouter/eliminar_registro'
    })

	},

	_V : {

		nuevo_registro : new Laugo.View.Window({
		
		    connect :  'estadodiarouter/nuevo_registro',

		    style : {
	               width : '600px',
	               height : '400px',
	               'background-color' : '#FFFFFF'
		     },

		    title : '  ',

		    onLoad: function(){ 


		    		var fecha = $_currentDate(); 
		    		dijit.byId('calEstadoDia_desde').set('value',  fecha   );
		    		dijit.byId('calEstadoDia_hasta').set('value',  fecha   );


		    		dojo.connect( dijit.byId('sel_estadodia_tipoestado'), 'onChange' , function(e){
		    		    	 
 						   dojo.setStyle(dojo.byId('tr_estadodia_destino'), 'display', 'none');
		    		       dojo.setStyle(dojo.byId('tr_estadodia_tiposeguro'), 'display', 'none');

		    		       if(dijit.byId('sel_estadodia_tipoestado').get('value') == 'desm_1' )
		    		       {
		    		            dojo.setStyle(dojo.byId('tr_estadodia_tiposeguro'), 'display', 'table-row');
		    		            
		    		       }
		    		       else if(dijit.byId('sel_estadodia_tipoestado').get('value') == 'comc_1' )
		    		       {
		    		            dojo.setStyle(dojo.byId('tr_estadodia_destino'), 'display', 'table-row');
		    		       } 
		    		    
		    		});


		    		dijit.byId('sel_estadodia_tipoestado').onChange();

 
		    		require([ "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
		    		            function(declare, JsonRest, Observable, Cache, Memory){
		    		  
		    		           var memoryStore = new Memory({});
		    		           var restStore = new JsonRest({

		    		                    target:"escalafon/provide/individuos/todos", 
		    		                    idProperty: "value",
		    		                    sortParam: 'oby',
		    		                    query: function(query, options){

                                    if (dojo.byId('HdLicenciaNuevoTipoTrabajador')!=null) {
                                      
                                      var plati_id = dojo.byId('HdLicenciaNuevoTipoTrabajador').value;
                                      query.plati_id = plati_id;
                                      
                                    }
 		    		                       
		    		                        return dojo.store.JsonRest.prototype.query.call(this, query, options);
		    		                    }

		    		              }); 

		    		            Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
		    		            Persona.Stores.individuos.query({});
		    		
		    		            dijit.byId('selregistrodiario_persona').set('store',Persona.Stores.individuos); 

		    		});
 
		    } 


		}),


        incidencias_registradas : new Laugo.View.Window({
             
            connect : 'estadodiarouter/incidencias_registradas',
            
            style : {
                 width :  '820px',
                 height:  '500px',
                 'background-color'  : '#FFFFFF'
            },
            
            title: 'Licencias e incidencias del día ',
            
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


                                if(dojo.byId('tablalicencias_registradas') != null )
                                { 
                                         
                                          if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                                   
                                        
                                           var store_trabajadores = JsonRest({
                                                   target: app.getUrl() +"estadodiarouter/get_licencias_dia", 
                                                   idProperty: "id",
                                                   sortParam: 'oby',
                                                   query: function(query, options){

                                                          var data = dojo.formToObject('form_licencias_registradas');
                                                           
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
                                                        col4: {label: 'Tipo', sortable: false},
                                                        col5: {label: 'Desde', sortable: false},
                                                        col6: {label: 'Hasta', sortable: false},
                                                        col7: {label: 'Dias', sortable: false}, 
                                                      
                                                };
                                            

                                               EstadoDia.Ui.Grids.licencias_registradas = new  (declare([Grid, Selection,Keyboard]))({
                                                       
                                                       store: store_trabajadores,
                                                       loadingMessage : 'Cargando',
                                                       getBeforePut: true,
                                                       columns: colums,
                                                       pagingLinks: false,
                                                       pagingTextBox: true,
                                                       firstLastArrows: true,
                                                       rowsPerPage : 50


                                               }, "tablalicencias_registradas");

                                               EstadoDia.Ui.Grids.licencias_registradas.refresh();
                       
                                 }


                                var memoryStore = new Memory({});
                                var restStore = new JsonRest({

                                         target:"escalafon/provide/individuos/todos/no_especificar", 
                                         idProperty: "value",
                                         sortParam: 'oby',
                                         query: function(query, options){
                                              
                                             var plati_id = dojo.byId('HdLicenciasRegistroTipoTrabajador').value;

                                             query.plati_id = plati_id;

                                             return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                         }

                                   }); 

                                 Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                                 Persona.Stores.individuos.query({}); 
                                 dijit.byId('sellicenciatrabajador').set('store',Persona.Stores.individuos);
                     
                     
                                   
                       });

                  var strDate = dojo.byId('hdLicenciasRegistradas_fechadesde').value;
                  var dateParts = strDate.split("-");
                  var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                 
                  dijit.byId('calLicenciasRegistradas_desde').set('value', date);
                  dijit.byId('calLicenciasRegistradas_hasta').constraints.min = date;
                  
                  strDate = dojo.byId('hdLicenciasRegistradas_fechahasta').value;
                  dateParts = strDate.split("-");
                  date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]); 
                  
                  dijit.byId('calLicenciasRegistradas_hasta').set('value', date);



            }

        })

	},

	Ui : {

		Grids : {}
	}

}