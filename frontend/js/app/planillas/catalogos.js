var Catalogos = {

	    Cache : {


	    },			

		_M : {

 			  
 			 registrar_meta : new Laugo.Model({
 			    connect  : 'catalogos/registrar_meta'
 			 }),					
  		
 			 actualizar_meta : new Laugo.Model({
 			    connect  : 'catalogos/actualizar_meta'
 			 }),		

 			 eliminar_meta : new Laugo.Model({
 			    connect  : 'catalogos/eliminar_meta'
 			 }),	

      		  exportar_data : new Request({
                    
      			              type :  'json',				              
      			              method: 'post',
      			              url : 'exportar/exportar_data',

      			              timeout : 100000,
      			              
      			              onRequest : function()
      			              {
      			                  app.loader_show(); 
      			              },
      			              
      			              onSuccess  : function(data)
      			              {
      			                 
      			              	 app.loader_hide(); 

      			              	 if(data.result == '1')
      			              	 {   	
      
      				                 var codigo = data.file;
      				                 window.open('docsmpi/exportar/'+codigo);
      				             }
      				             else
      				             {
      				             	 app.alert( (data.mensaje || 'Algo no salio bien durante la generación del archivo de exportación'));
      				             }	
      			                    
      			              },
      			              
      			              onFailure : function()
      			              {
      			              	 app.loader_hide();     
      			              } 
                    
                })  
		
		},

		_V : {

  
		       Metas : new Laugo.View.Window({
		            
		             connect : 'catalogos/gestionar_metas',
		              
		              style : {
		                   width :  '700px',
		                   height:  '420px',
		                   'background-color'  : '#FFFFFF'
		              },
		              
		              title: ' Gestionar Metas Presupuestales',
		              
		              onLoad: function(){
		                 	
 
		              			require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
		              			                        function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


		              			     if(dojo.byId('dv_gestionar_metas') != null )
		              			     { 

		              			         if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


		              			                var store = Observable(Cache(JsonRest({
		              			                        target: app.getUrl() +"catalogos/get_metas", 
		              			                        idProperty: "id",
		              			                        sortParam: 'oby',
		              			                        query: function(query, options)
		              			                        {
	              			                                var data = {}  

	              			                                data = dojo.formToObject('formtarea_filtro');

	              			                                for(d in data){
	              			                                     query[d] = data[d];
	              			                                }

	              			                                return JsonRest.prototype.query.call(this, query, options);
		              			                        }
		              			                }), Memory()));


		              			                var columnas = {  
		              			                         
		              			                        col1: {label: '#', sortable: true},
		              			                        col2: {label: 'Año', sortable: false},
		              			                        col3: {label: 'Codigo', sortable: false},
		              			                        col4: {label: 'Nombre', sortable: false} 
		              			                };
		              			

		              			                Catalogos.Ui.Grids.gestionar_metas = new  window.escalafon_grid({
		              			                   
		              			                            store: store,  
		              			                            loadingMessage : 'Cargando',
		              			                            selectionMode: 'single',
		              			                            getBeforePut: false, 
		              			                            columns: columnas,
		              			                            pagingLinks: false,
		              			                            pagingTextBox: true,
		              			                            firstLastArrows: true 

		              			                }, "dv_gestionar_metas");


		              			                if( Catalogos.Ui.Grids.gestionar_metas  != null)
		              			                {
		              			                   Catalogos.Ui.Grids.gestionar_metas.refresh();
		              			                }          
		              			      }



		              			});

		              },
		              
		              onClose: function(){
		                  
		              //    alert('ventana cerrada');
		                   return true;
		              }
		        }),



    	       nueva_meta : new Laugo.View.Window({
    	            
    	             connect : 'catalogos/nueva_meta',
    	              
    	              style : {
    	                   width :  '460px',
    	                   height:  '260px',
    	                   'background-color'  : '#FFFFFF'
    	              },
    	              
    	              title: ' Meta Presupuestal ',
    	              
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
			  
			 metas : {

				  btn_registrar : function(btn,evt)
				  {


				  		var data  = dojo.formToObject('frm_exportxls_configuracion');
	 					
	 					if(confirm('Realmente desea registrar la Meta Presupuestal ? '))
	 					{
	 						Exporter._M.exportar_data.send(data);
	 					}
	 					
				  }	
 			 	
			 }
 
		}


}