var Historiallaboral = {


	_M : {
 
 		cese_masivo : new Laugo.Model({
		   connect  : 'historiallaboral/cesar_masivo'
		})

	},

	_V : {


		 cese_masivo : new Laugo.View.Window({
		    
		     connect : 'historiallaboral/cese_masivo',
		     
		     style : {
		          width : '840px',
		          height : '500px',
		          'background-color' : '#FFFFFF'
		     },
		     
		     title : 'Cesar contratos',
		     
		     onLoad : function(){

		     		var Table = new superTable("tablecese_trabajadores", {
		     		                 cssSkin : "sDefault",
		     		                 headerRows : 1,
		     		                 fixedCols  : 0,
		     		                 colWidths  : [],
		     		                 onStart : function () {
		     		                
		     		                 },
		     		                 onFinish : function () {
		     		                   
		     		                 }
		     		
		     		            });

 
		     		if(dijit.byId('calcese_fecha') != null )
		     		{

			     		var strDate = dojo.byId('calcese_fechaminima').value;
			     		var dateParts = strDate.split("-");
			     		var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
			     			
			     		var fecha = $_currentDate();

		     		     dijit.byId('calcese_fecha').set('value', fecha);
		     		     dijit.byId('calcese_fecha').constraints.min = date;

		     		}
 
		     		

		     }

		  }),

		elaborar_certificado : new Laugo.View.Window({
		   
		    connect : 'historiallaboral/elaborar_certificado',
		    
		    style : {
		         width : '800px',
		         height : '500px',
		         'background-color' : '#FFFFFF'
		    },
		    
		    title : 'Elaborar Certificados',
		    
		    onLoad : function(){


		    		// calcertificado_fechadoc 

		     		var fecha = $_currentDate();
		     		 dijit.byId('calcertificado_fechadoc').set('value', fecha);

		    		require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination","dgrid/extensions/ColumnHider", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
		    		                 function(List, Grid, Selection, editor, Keyboard, Pagination, ColumnHider, declare, JsonRest, Observable, Cache, Memory){


		    		                  if(dojo.byId('table_certificados_list') != null )
		    		                  { 

		    		                      if( window.grid_columnhider === null ||  window.grid_columnhider === undefined)  window.grid_columnhider = (declare([Grid, Selection, ColumnHider, Keyboard]));


	                                             var store = Observable(Cache(JsonRest({
	                                                     target:  app.getUrl() + "historiallaboral/certificados_list", 
	                                                     idProperty: "id",
	                                                     sortParam: 'oby',
	                                                     query: function(query, options){

	                                                     		 var data = dojo.formToObject('formcontratos_elaborar');
 
	                                                     		 for(d in data)
	                                                     		 {
	                                                     		     query[d] = data[d];
	                                                     		 } 
 
	                                                             return JsonRest.prototype.query.call(this, query, options);
	                                                     }
	                                             }), Memory()));

	                                             var columns = { 
	                                                     col1: {label: '#', sortable: true},
	                                                     col2: {label: 'Trabajador', sortable: false},
	                                                     col11: {label: 'Dni', sortable: false},
	                                                     col3: {label: 'Tipo', sortable: false},
	                                                     col4: {label: 'Area', sortable: false},
	                                                     col5: {label: 'Proyecto', sortable: false},
	                                                     col6: {label: 'Categoria', sortable: false},
	                                                     col7: {label: 'Ocupación', sortable: false},
	                                                     col8: {label: 'Desde', sortable: false}, 
	                                                     col9: {label: 'Hasta', sortable: false}, 
	                                                     col10: {label: 'Dias Lab.', sortable: false} 
	                                             };
	
	                                             Planillas.Ui.Grids.planillas_preview_importacion  = new  window.grid_columnhider({

	                                                     store: store,
	                                                     loadingMessage : 'Cargando',
	                                                     getBeforePut: false,
	                                                     columns: columns 


	                                             }, "table_certificados_list");

		    		                            
		    		                   }
		    		                    

		    		     });   
		    		

		              
		    }
		   
		})

	},

	Ui : {
 		
 		btn_view_cesar_masivo : function(btn,evt){

 			var codigo_e = '';   
 			var selection =  Planillas.Ui.Grids.contratos.selection;   
 			
 			for(var i in selection)
 			{  
 			    if(selection[i] === true)
 			    {
 			      codigo_e +='_'+ i;
 			    }
 
 			}

 			if(codigo_e != '')      
 			{
 			     Historiallaboral._V.cese_masivo.load({'view' : codigo_e});
 			}
 			else
 			{ 
 			    alert('Debe seleccionar un registro');
 			}
 		

 		},

 		btn_cesar_masivo  : function(btn,evt){
 			 
 			 var data = dojo.formToObject('form_info_cesar');
 		 	

 			 if(data.fechacese == null || data.fechacese == undefined )
 			 {
 			 	alert('Por favor verifique la fecha de cese');

 			 	return false;
 			 }

 			 if(confirm('¿Realemnte desea cesar estos registros ? '))
 			 {
	 			 if(Historiallaboral._M.cese_masivo.process(data))
	 			 {
	 			 	  Historiallaboral._V.cese_masivo.close();
	 			 	  Planillas.Ui.Grids.contratos.refresh();
	 			 }
 			 	
 			 }

 		},

		btn_elaborar_certificados : function(btn,evt){

			var codigo_e = '';   
			var selection =  Planillas.Ui.Grids.contratos.selection;   
			
			for(var i in selection)
			{  
			    if(selection[i] === true)
			    {
			      codigo_e +='_'+ i;
			    }
			      
			}

			if(codigo_e != '')      
			{
			     Historiallaboral._V.elaborar_certificado.load({'view' : codigo_e});
			}
			else
			{ 
			    alert('Debe seleccionar un registro');
			}
 

		},


		btn_view_generar_certificados : function(btn,evt){
 
		}

	},

}