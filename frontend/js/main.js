

/*
 * Configurando por defecto los modelos
 */ 



var DIALOG_DESTROY = []; 
 
var tip;

var callback_fns = {}


var edit_control ={
    
    data:[],
    
    add: function(node,message){},
    
    remove: function(){
        
    },
    
    observer: function(){
        
    }
     
}

var descuento_altura_frame = 0; 

callback_fns.mnuescala_nuevo = function(){
       
   
       dijit.byId('dv_ppaa_contenedor').resize({w: '1270' ,h: 400, l: 0, t:0},{w: 1000 ,h: 400});
}


callback_fns.mnuescala_registro = function()
{
    
       var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
        
        dijit.byId('escalaregistro_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
         
           
    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                 if(dojo.byId('dvapersonalregistrado_table') != null ){ 
                      
                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                
                     
                                            var store_trabajadores = JsonRest({
                                                    target:"escalafon/get_trabajadores", 
                                                    idProperty: "id",
                                                    sortParam: 'oby',
                                                    query: function(query, options){
  
                                                            var data = dojo.formToObject('form_registro_consulta');
                                                            
                                                            for(d in data){
                                                                query[d] = data[d];
                                                            }
                                                             
                                                            return JsonRest.prototype.query.call(this, query, options);
                                                    }
                                            });

                                              var colums = {  

                                                        col1: {label:'#', sortable: false},
                                                        col2: {label: 'Ap. Paterno', sortable: true},
                                                        col3: {label: 'Ap. Materno', sortable: true},
                                                        col4: {label: 'Nombres', sortable: true},
                                                      
                                                        col5: {label: 'DNI', sortable: false},
                                                        col6: {label: 'Tipo de trabajador', sortable: true},
                                                        col7: {label: 'Area/Proyecto', sortable: true},
                                                        col8: {label: 'Cargo', sortable: true},
                                                        col9: {label: 'Activo', sortable: true}
                                                      
                                                };

                                         
 
                                            Persona.Ui.Grids.trabajadores  = new  (declare([Grid, Selection,Keyboard]))({
                                                    
                                                    store: store_trabajadores,
                                                    loadingMessage : 'Cargando',
                                                    getBeforePut: true,
                                                    columns: colums,
                                                    pagingLinks: false,
                                                    pagingTextBox: true,
                                                    firstLastArrows: true,
                                                    rowsPerPage : 25


                                            }, "dvapersonalregistrado_table");

                                  if( Persona.Ui.Grids.trabajadores != null){
                                //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                   //  Persona.Ui.Grids.trabajadores.refresh();
                                      // Persona.Ui.Grids.trabajadores.store.query({});
                                      // Persona.Ui.Grids.trabajadores.cleanup();
                                   
                                }          
                  }
                             


    });
       
       

}



callback_fns.mnuplanilla_registro = function(){
       
   
    
      

}

callback_fns.mnuplanillas_conceptos_gestionar = function(){
 
 
  try{  
      
        require(["dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory","dojo/data/ItemFileReadStore", "dojo/domReady!"], 
                    function( declare, JsonRest, Observable, Cache, Memory, ItemFileReadStore){
                  
                 
               var memoryStore = new Memory({});
               var  restStore = new JsonRest({

                        target:"planillas/provide/conceptos_y_variables/", 
                        idProperty: "value",
                        sortParam: 'oby',
                        query: function(query, options){
                            
                            if( dijit.byId('sel_newconc_tipopla') != null ){
                                query.fortipo = dijit.byId('sel_newconc_tipopla').get('value');
                            } 
                            else if( dojo.byId('sel_newconc_tipopla') != null ){
                                 query.fortipo = dojo.byId('sel_newconc_tipopla').value;
                            }
                            
                            return dojo.store.JsonRest.prototype.query.call(this, query, options);
                        }

                  }); 
                Conceptos._M.store_conceptos_variables =  new  Cache(restStore, memoryStore);
                Conceptos._M.store_conceptos_variables.query({fortipo : '71c30216a4d1a1d0c23b7158a709bc23'});
            
             
        });
      
     
      Variables.Ui.table_main_ready();
      Conceptos.Ui.table_main_ready();
        
  }
  catch(error){
       
      console.log( (error.message) ? 'Error: '+error.message : 'Error: main.js/mnuplanillas_conceptos_gestionar '+error );  
        
  }
     
      
      
}
  

callback_fns.mnutrabajador_gestiondatos = function(){
      

       Trabajadores.Cache.trabajador_gestion_datos = null;

       dojo.connect( dijit.byId('selgd_modo'), 'onChange', function(evt){

            if(Trabajadores.Cache.trabajador_gestion_datos != null && Trabajadores.Cache.trabajador_gestion_datos != '' && Trabajadores.Cache.trabajador_gestion_datos != undefined)
            {

                if(dijit.byId('selgd_modo').get('value') == '1')
                {

                     Trabajadores._V.gestionar_datos_rapida.send({'trabajador' : Trabajadores.Cache.trabajador_gestion_datos });   
                }
                else
                {
                     Trabajadores._V.gestionar_datos.send({'trabajador' : Trabajadores.Cache.trabajador_gestion_datos }); 
                }
             
           }
           
       });



       require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                 if(dojo.byId('dvtgd_trabajadores') != null ){ 
                      
                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                
                     
                                            var store_trabajadores = JsonRest({
                                                    target:"escalafon/get_trabajadores", 
                                                    idProperty: "id",
                                                    sortParam: 'oby',
                                                    query: function(query, options){
    
                                                            
                                                            var data = dojo.formToObject('form_busqueda_trabajadores');
                                                            
                                                            for(d in data){
                                                                query[d] = data[d];
                                                            }

                                                            data = dojo.formToObject('form_busqueda_trabajadores_ops');
                                                            
                                                            for(d in data){
                                                                query[d] = data[d];
                                                            }
                                                              
                                                            return JsonRest.prototype.query.call(this, query, options);
                                                    }
                                            });

                                              var colums = { // you can declare columns as an object hash (key translates to field)
                                                       // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                        col1: {label:'#', sortable: true},
                                                        col2: {label: 'Ap. Paterno', sortable: false},
                                                        col3: {label: 'Ap. Materno', sortable: false},
                                                        col4: {label: 'Nombres', sortable: false},
                                                      
                                                        col5: {label: 'DNI', sortable: false},
                                                        col6: {label: 'Tipo de trabajador', sortable: false},
                                                        col7: {label: 'Area/Proyecto', sortable: false},
                                                        col8: {label: 'Cargo', sortable: false},
                                                        col9: {label: 'Activo', sortable: false}
                                                   
                                                };

                                         
 
                                            Planillas.Ui.Grids.trabajadores_gestionardata  = new  (declare([Grid, Selection,Keyboard]))({
                                                    
                                                    store: store_trabajadores,
                                                    loadingMessage : 'Cargando',
                                                    getBeforePut: true,
                                                    columns: colums,
                                                    pagingLinks: false,
                                                    pagingTextBox: true,
                                                    firstLastArrows: true,
                                                    rowsPerPage : 25


                                            }, "dvtgd_trabajadores");

                                  if( Persona.Ui.Grids.trabajadores_gestionardata != null)
                                  {
                             
                                  }          
                  }
                             


    });

 
}


 


callback_fns.mnu_planilla_reportes = function(){

       var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
        
        dijit.byId('planilla_reporter_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});

        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                 if(dojo.byId('dv_reportes_filtroplanilla') != null )
                                 { 

                                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                            var store_planillas = Observable(Cache(JsonRest({
                                                                    target: app.getUrl() +"planillas/registro_planillas/reporter", 
                                                                    idProperty: "id",
                                                                    sortParam: 'oby',
                                                                    query: function(query, options){

                                                                           var data  = dojo.formToObject('form_reportes_planilla_filtro');
                                                                           
                                                                            for(d in data){
                                                                                 query[d] = data[d];
                                                                            }

                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                    }
                                                            }), Memory()));

                                                              var colums = {  
                                                                        
                                                                        col0: Selector({label : ''}),
                                                                        col1: {label: '#', sortable: true},
                                                                        col2: {label: 'Codigo', sortable: false},
                                                                        col3: {label: 'Año', sortable: false},
                                                                        col4: {label: 'Mes', sortable: false},
                                                                        col5: {label: 'Tipo de Planilla', sortable: false},
                                                                        col6: {label: 'Des/Obs', sortable: false},
                                                                        col7: {label: 'Centro de Costo', sortable: false},
                                                                        col8: {label: 'Estado', sortable: false}, 
                                                                        col9: {label: 'Num. Emps', sortable: false} 
                                                                      
                                                                };



                                                            Planillas.Ui.Grids.planillas_reporte_filtro  = new  window.escalafon_grid({
                                                                        
                                                                      /*  store: store_planillas,
                                                                        loadingMessage : 'Cargando',
                                                                        getBeforePut: true,
                                                                        columns: colums, 
                                                                        firstLastArrows: true,
                                                                        rowsPerPage : 2 
*/

                                                                        store: store_planillas,
                                                                        loadingMessage : 'Cargando',
                                                                        selectionMode: "none",
                                                                        columns: colums,
                                                                        allowSelectAll: true


                                                            }, "dv_reportes_filtroplanilla");

                                                  if( Planillas.Ui.Grids.planillas_reporte_filtro != null)
                                                  {
                                                  
                                                     Planillas.Ui.Grids.planillas_reporte_filtro.refresh();
                                                  
                                                  }          
                                  }



                    });
  

      dojo.connect( dijit.byId('selplanireport_anio'), 'onChange', function(evt){
                           
               Planillas.Ui.Grids.planillas_reporte_filtro.refresh();          
                          
      });

      dojo.connect( dijit.byId('selplanireport_mes'), 'onChange', function(evt){
                           
               Planillas.Ui.Grids.planillas_reporte_filtro.refresh();          
                          
      });
      
      dojo.connect( dijit.byId('selplanireport_plati'), 'onChange', function(evt){
                           
               Planillas.Ui.Grids.planillas_reporte_filtro.refresh();          
                          
      });

}


callback_fns.actualizacion_de_datos = function(){


    var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
         
    dijit.byId('dvactualizaciondatos_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});

    Persona.get_table_trabajadores();
  

}




callback_fns.explorar_por_fechas = function(){
 
    var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
         
    dijit.byId('dvexplorarfechas_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
  

     dojo.connect( dijit.byId('dvexplorarfechas_tipoview'), 'onChange', function(){

          var v = dijit.byId('dvexplorarfechas_tipoview').get('value');
          
          if(v == '1')
          {
               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'table-row');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'none'); 
               dojo.setStyle( dojo.byId('trxf_tipoft'),  'display', 'none');
          
          }
          else if(v == '2')
          {

               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'none');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'table-row'); 
                   
               dojo.setStyle( dojo.byId('trxf_tipoft'), 'display', 'none');

          }
          else if(v == '3'){

               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'none');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'none'); 
               
               dojo.setStyle( dojo.byId('trxf_tipoft'), 'display', 'none');

          }
          else if(v == '4'){

               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'none');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'none'); 
               
               dojo.setStyle( dojo.byId('trxf_tipoft'), 'display', 'table-row');

          }
          else if(v == '5'){

               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'none');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'none'); 
               dojo.setStyle( dojo.byId('trxf_tipoft'), 'display', 'none');
/*
              dojo.setStyle( dojo.byId('trxf_poracumulado'), 'display', 'none');
              dojo.setStyle( dojo.byId('trxf_acumulado'), 'display', 'none');*/
              

          }
          else if(v == '6'){

               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'none');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'none'); 
               dojo.setStyle( dojo.byId('trxf_tipoft'), 'display', 'none');
 
          }
          else if(v == '7'){

               dojo.setStyle( dojo.byId('trxf_destino'), 'display', 'none');
               dojo.setStyle( dojo.byId('trxf_tipolic'), 'display', 'none'); 
               dojo.setStyle( dojo.byId('trxf_tipoft'), 'display', 'none');
 

          }          
     });
    
     dijit.byId('dvexplorarfechas_tipoview').onChange();



     dojo.connect( dijit.byId('selxf_agruparpor'), 'onChange', function(){

          var v = dijit.byId('selxf_agruparpor').get('value');

          if(v!= 0)
          {
            dojo.setStyle( dojo.byId('trxf_acumulado'), 'display', 'table-row');
          }
          else
          {
             dojo.setStyle( dojo.byId('trxf_acumulado'), 'display', 'none');
          }

     });

     dijit.byId('selxf_agruparpor').onChange();



     dojo.connect( dijit.byId('selxf_porfecha'), 'onChange', function(){

          var v = dijit.byId('selxf_porfecha').get('value');

          if(v!= 0)
          {
            dojo.setStyle( dojo.byId('trxf_rangofechas'), 'display', 'table-row');
          }
          else
          {
             dojo.setStyle( dojo.byId('trxf_rangofechas'), 'display', 'none');
          }

     });

     dijit.byId('selxf_porfecha').onChange();

    

    var calendars = ['calhisdesde','calhishasta' ];
    var fecha = $_currentDate();
    dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});




     require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", 
              "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", 
              "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", 
              "dojo/store/Memory", "dojo/domReady!"], 

           function(List, Grid, Selection, editor, Keyboard, Pagination, 
                  declare, JsonRest, Observable, Cache, Memory){
     
                    
                  if( window.escalafon_grid == null)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));

                  var columns = {}, controller = '', conf = {};

         
                  var store = Observable(Cache(JsonRest({
                                              
                                               target: app.getUrl() + "escalafon/get/",
                                               idProperty: "id",
                                               sortParam : "oby",
                                               
                                               query: function(query, options){
                                                       
                                                      var data = dojo.formToObject('form_filtrarxfechas');

                                                      for( x in data  ){
                                                           query[x] = data[x];
                                                      }   

                                                      return JsonRest.prototype.query.call(this, query, options);
                                                }
                                                               
                                        }), Memory()));


                     Persona.Ui.Grids.explorar_por_fechas = new  window.escalafon_grid({
                                                                                                                 
                                       store: store,
                                       getBeforePut: false,
                                       columns: columns,
                                       loadingMessage : 'Cargando', 
                                                                                                                      
                                                                                                                                         
                                 }, "dv_explorarxfechas");
     });
     



}

 



callback_fns.gestionar_tipo_planilla = function(){
 
    var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
         
    dijit.byId('dvgtp_gestionar_main').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
   

    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


         if(dojo.byId('table_tipoplanilla_tipos') != null )
         { 

             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                    var store = Observable(Cache(JsonRest({
                            target: app.getUrl() +"tipoplanillas/registro", 
                            idProperty: "id",
                            sortParam: 'oby',
                            query: function(query, options)
                            {

                                    var data = {}  
                                    return JsonRest.prototype.query.call(this, query, options);
                            }
                    }), Memory()));


                    var columnas = {  
                             
                            col1: {label: '#', sortable: true},
                            col2: {label: 'Nombre', sortable: false},
                            col3: {label: 'Tipo trabajador', sortable: false},
                            col4: {label: 'Abreviado', sortable: false},
                            col5: {label: 'Con Categorias', sortable: false},
                            col6: {label: 'Activo', sortable: false},
                            col7: {label: '# Trabajadores Activos', sortable: false} 
                    };
 

                    Tipoplanilla.Ui.Grids.main = new  window.escalafon_grid({
                       
                                store: store,  
                                loadingMessage : 'Cargando',
                                getBeforePut: false, 
                                columns: columnas,
                                pagingLinks: false,
                                pagingTextBox: true,
                                firstLastArrows: true 

                    }, "table_tipoplanilla_tipos");


                    if( Tipoplanilla.Ui.Grids.main != null)
                    {
                       Tipoplanilla.Ui.Grids.main.refresh();
                    }          
          }



    });
      

}


callback_fns.gestionar_usuarios = function(){
 

    var dims = app.get_dims_body_app(),
        desc_altura = (dims.h > 700) ? (200) : 0;
         
      dijit.byId('gestionusuarios_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                
 



    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


         if(dojo.byId('dv_geusu_usuarios') != null )
         { 

             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                    var store = Observable(Cache(JsonRest({
                            target: app.getUrl() +"usuarios/get_usuarios", 
                            idProperty: "id",
                            sortParam: 'oby',
                            query: function(query, options)
                            {

                                    var data = {}  
                                    return JsonRest.prototype.query.call(this, query, options);
                            }
                    }), Memory()));


                    var columnas = {  
                             
                            col1: {label: '#', sortable: true},
                            col2: {label: 'Trabajador', sortable: false},
                            col3: {label: 'DNI', sortable: false},
                            col4: {label: 'Usuario', sortable: false},
                            col5: {label: 'Perfil', sortable: false},
                            col6: {label: 'Activo', sortable: false}  
                    };
    

                    Users.Ui.Grids.gestionar_usuarios = new  window.escalafon_grid({
                       
                                store: store,  
                                loadingMessage : 'Cargando',                                                    
                                getBeforePut: false, 
                                columns: columnas,
                                pagingLinks: false,
                                pagingTextBox: true,
                                firstLastArrows: true 

                    }, "dv_geusu_usuarios");


                    if( Users.Ui.Grids.gestionar_usuarios != null)
                    {
                       Users.Ui.Grids.gestionar_usuarios.refresh();
                    }          
          }



    });


}
 
callback_fns.gestionar_historial_laboral = function(){

    
    var dims = app.get_dims_body_app(),
        desc_altura = (dims.h > 700) ? (200) : 0;
         
      dijit.byId('viewcontratos_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0}); 


       
       dojo.connect( dijit.byId('selcaontratos_tipobusqueda_modo'), 'onChange', function(evt){
                            
              console.log(this.get('value')); 

              var tipo = this.get('value');

              switch(tipo)
              {
                 case "1": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs1'), 'display', 'none');
                    dojo.setStyle(dojo.byId('dv_contratos_bs2'), 'display', 'inline')  
                      
                    break;

                 case "2": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs1'), 'display', 'inline');
                    dojo.setStyle(dojo.byId('dv_contratos_bs2'), 'display', 'inline')
                 
                    break;
                 
                 case "3": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs1'), 'display', 'inline');
                    dojo.setStyle(dojo.byId('dv_contratos_bs2'), 'display', 'none');
                  
                    break;

                 case "0": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs1'), 'display', 'none');
                    dojo.setStyle(dojo.byId('dv_contratos_bs2'), 'display', 'inline');  
                    break;

                 default : 
                    break;

              }
       });
       
       dijit.byId('selcaontratos_tipobusqueda_modo').onChange();


       dojo.connect( dijit.byId('selcontratos_fecha_sel1'), 'onChange', function(evt){
                             
              var tipo = this.get('value');

              switch(tipo)
              {
                 case "1": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs3'), 'display', 'none');
                    break;

                 case "2": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs3'), 'display', 'none');
                    break;
                 
                 case "3": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs3'), 'display', 'inline');
                    break;
               
                 default : 
                    break;

              }
       });  

       dijit.byId('selcontratos_fecha_sel1').onChange();


       dojo.connect( dijit.byId('selcontratos_yqterminen'), 'onChange', function(evt){
                             

              var tipo = this.get('value');

              switch(tipo)
              {
                 case "1": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs5'), 'display', 'inline');
                      
                    break;

                 case "0": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs5'), 'display', 'none');
                    break;
              
                 default : 
                    break;

              }
       });

       dijit.byId('selcontratos_yqterminen').onChange();


       dojo.connect( dijit.byId('selcontratos_fecha_sel2'), 'onChange', function(evt){
                            
              console.log(this.get('value')); 

              var tipo = this.get('value');

              switch(tipo)
              {
                 case "1": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs6'), 'display', 'inline');
                    dojo.setStyle(dojo.byId('dv_contratos_bs7'), 'display', 'none');
                      
                    break;

                 case "2": 
                    dojo.setStyle(dojo.byId('dv_contratos_bs6'), 'display', 'inline');
                    dojo.setStyle(dojo.byId('dv_contratos_bs7'), 'display', 'none');
                    break;

                case "3": 
                   dojo.setStyle(dojo.byId('dv_contratos_bs6'), 'display', 'inline');
                   dojo.setStyle(dojo.byId('dv_contratos_bs7'), 'display', 'inline');
                     
                   break;

                case "4": 
                   dojo.setStyle(dojo.byId('dv_contratos_bs6'), 'display', 'none');
                   break;
              
                 default : 
                    break;

              }
       });

       dijit.byId('selcontratos_fecha_sel2').onChange();



       dojo.connect( dijit.byId('selmontocontrato_tipo'), 'onChange', function(evt){
                            
              console.log(this.get('value')); 

              var tipo = this.get('value');

              switch(tipo)
              { 

                case "0": 
                    dojo.setStyle(dojo.byId('dv_montocontrato_c1'), 'display', 'none');
                    dojo.setStyle(dojo.byId('dv_montocontrato_c2'), 'display', 'none');
                     break;
                 case "1": 
                    dojo.setStyle(dojo.byId('dv_montocontrato_c1'), 'display', 'inline');
                    dojo.setStyle(dojo.byId('dv_montocontrato_c2'), 'display', 'none');
                     break;

                 case "2": 
                    dojo.setStyle(dojo.byId('dv_montocontrato_c1'), 'display', 'inline');
                    dojo.setStyle(dojo.byId('dv_montocontrato_c2'), 'display', 'none');
                    break;

                case "3": 
                   dojo.setStyle(dojo.byId('dv_montocontrato_c1'), 'display', 'inline');
                   dojo.setStyle(dojo.byId('dv_montocontrato_c2'), 'display', 'inline');
                   break;
              
                 default : 
                    break;

              }
       });

       dijit.byId('selmontocontrato_tipo').onChange();



       dojo.connect( dijit.byId('selcaontratos_tipobusqueda_tipo'), 'onChange', function(evt){
                            
              console.log(this.get('value')); 

              var tipo = this.get('value');

              switch(tipo)
              {
                 case "1": 
                    dijit.byId('selmontocontrato_considerar').set('value', 1);
                    dijit.byId('selmontocontrato_considerar').set('disabled', true);
                    break;

                 case "2": 
                   
                    dijit.byId('selmontocontrato_considerar').set('disabled', false);
                    break;
                 
                 case "3": 
                     dijit.byId('selmontocontrato_considerar').set('disabled', false);
                    break;
               
                 default : 
                    break;

              }
       });  

       dijit.byId('selcaontratos_tipobusqueda_tipo').onChange();

         


      var calendars = ['estado_cal_fecha1','estado_cal_fecha2', 'estado_cal_fecha3', 'estado_cal_fecha4'];
      var fecha = $_currentDate();
      dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});




    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){



         if(dojo.byId('table_contratos_view') != null )
         { 



           if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


            var store_contratos = JsonRest({
                    target:app.getUrl() + "historiallaboral/get_contratos", 
                    idProperty: "id",
                    sortParam: 'oby',
                    query: function(query, options){

                           
                            var data = dojo.formToObject('form_registrocontratos_consulta');
                             
                            for(d in data){
                                query[d] = data[d];
                            } 

                            if(dijit.byId('selcertificados_regimen').get('value') != '0')
                            {
                                dijit.byId('btncertificados_elaborar').set('disabled', false);
                            }
                            else
                            {
                               dijit.byId('btncertificados_elaborar').set('disabled', true);
                            }

                            return JsonRest.prototype.query.call(this, query, options);
                    }
            });


              var render = function(object, value, node, options, corchetes){

                  if(parseInt(object.contrato_vencido) == 1 )
                  { 
                      dojo.setStyle(node, 'color', '#990000');
                      
                      if(corchetes === true)
                      {
                        dojo.attr(node, 'innerHTML', '[' + value+']');
                      }
                      else
                      {
                        dojo.attr(node, 'innerHTML', value ); 
                        
                      }

                  }
                  else if(parseInt(object.cesado) == 1 )
                  { 
                    dojo.setStyle(node, 'color', '#336699');
                    dojo.attr(node, 'innerHTML', value ); 
                  }
                  else
                  {
                     dojo.attr(node, 'innerHTML', value ); 
                  }

              }

              var colums = {  
                        
                     col0: Selector({}),
                     col1: {label: '#', sortable: true, 

                           renderCell: function(object, value, node, options){
                                               
                                               var corchetes = true;
                                               render(object, value, node, options, corchetes);
                                             
                                       }

                          },

                    col2: {label: 'Trabajador', sortable: false, 

                           renderCell: function(object, value, node, options){
                                              render(object, value, node, options);

                                       }},
                    col3: {label: 'DNI', sortable: false, 

                           renderCell: function(object, value, node, options){
                                            
                                               render(object, value, node, options);
 
                                       }},
 
                   col4: {label: 'Fec.Inicio', sortable: false, 

                          renderCell: function(object, value, node, options){
                                             
                                               render(object, value, node, options);

                                      }},
                   col5: {label: 'Fec.Termino', sortable: false, 

                          renderCell: function(object, value, node, options){
                                            
                                              render(object, value, node, options);

                                      }},

                   col6: {label: 'Contrato S./', sortable: false, 

                          renderCell: function(object, value, node, options){
                                              
                                               render(object, value, node, options);

                                      }},
                     




                    col7: {label: 'Regimen', sortable: false, 

                           renderCell: function(object, value, node, options){

                                          render(object, value, node, options);

                                       }},

                    col8: {label: 'Area', sortable: false, 

                           renderCell: function(object, value, node, options){
                                          


                                       }},
                    col9: {label: 'Cargo', sortable: false, 

                           renderCell: function(object, value, node, options){
                                            render(object, value, node, options);
                                       }}, 

                    col10: {label: 'Ocupacion', sortable: false, 

                           renderCell: function(object, value, node, options){
                                               render(object, value, node, options);
                                       }}, 


                    col11: {label: 'Dias F.', sortable: false, 

                           renderCell: function(object, value, node, options){
                                               render(object, value, node, options);
                                       }} 
                        
              };


 
              Planillas.Ui.Grids.contratos  = new  (declare([Grid, Selection,Keyboard]))({

                      store: store_contratos,
                      loadingMessage : 'Cargando',
                      allowSelectAll: true,
                      getBeforePut: true,
                      selectionMode: "single",
                      columns: colums,
                      pagingLinks: false,
                      pagingTextBox: true,
                      firstLastArrows: true,
                      rowsPerPage : 100


              }, "table_contratos_view");

              if( Planillas.Ui.Grids.contratos != null)
              {
          
              }          
           

         }



    });            


}



callback_fns.importar_trabajadores_panel = function(){

   var dims = app.get_dims_body_app(),
       desc_altura = (dims.h > 700) ? (200) : 0;
        
     dijit.byId('xlsimportacion_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                

}


callback_fns.quinta_categoria = function(){

   var dims = app.get_dims_body_app(),
       desc_altura = (dims.h > 700) ? (200) : 0;
        
     dijit.byId('impuestoquinta_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                
  
      require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                    function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                 if(dojo.byId('impuestoquinta_filtrotrabajadores') != null )
                 { 
                          
                           if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                    
                         
                            var store_trabajadores = JsonRest({
                                  target: app.getUrl() +"impuestos/buscar_trabajadores_quinta", 
                                    idProperty: "id",
                                    sortParam: 'oby',
                                    query: function(query, options){

                                        /*    var data = dojo.formToObject('form_registro_consulta');
                                            
                                            for(d in data){
                                                query[d] = data[d];
                                            }*/
                                             
                                            return JsonRest.prototype.query.call(this, query, options);
                                    }
                            });

                             var colums = { 
                                         
                                        
                                         col1: {label: '#', sortable: true},                                                                          
                                         col2: {label: 'Trabajador', sortable: false},
                                         col3: {label: 'DNI', sortable: false},
                                         col4: {label: 'Tipo', sortable: false},
                                         col5: {label: 'Termino de Contrato', sortable: false},
                                         col6: {label: 'Monto de Contrato S./', sortable: false},
                                         col7: {label: 'Pagos S./', sortable: false},
                                         col8: {label: 'Descuento S./', sortable: false} 
                                        /*  col9: {label: 'Ocupación', sortable: false} 
                                        col9: {label: 'Ingresos', sortable: false},
                                         col10: {label: 'Descuento', sortable: false},
                                         col11: {label: 'Neto', sortable: false},
                                         col12: {label: 'Aportacion', sortable: false}
                                       */
                                 };
                             

                                Planillas.Ui.Grids.quinta_categoria  = new  (declare([Grid, Selection,Keyboard]))({
                                        
                                        store: store_trabajadores,
                                        loadingMessage : 'Cargando',
                                        getBeforePut: true,
                                        columns: colums,
                                        pagingLinks: false,
                                        pagingTextBox: true,
                                        firstLastArrows: true,
                                        rowsPerPage : 50


                                }, "impuestoquinta_filtrotrabajadores");
        
                  }
                    
        });
 
}


callback_fns.afp = function(){

   var dims = app.get_dims_body_app(),
       desc_altura = (dims.h > 700) ? (200) : 0;
        
     dijit.byId('xlsimportacion_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                

}


callback_fns.sunat = function(){
 
  var dims = app.get_dims_body_app(),
      desc_altura = (dims.h > 700) ? (200) : 0;
       
    dijit.byId('impuestosunat_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                
  
     require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/Selector","dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                   function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


          if(dojo.byId('table_sunatbajas') != null )
          { 
                     
                      if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                               
                    
                       var store = JsonRest({
                             target: app.getUrl() +"impuestos/sunat_tregistro/bajas", 
                               idProperty: "id",
                               sortParam: 'oby',
                               query: function(query, options){
                                        
                                       var data  = dojo.formToObject('form_sunat_declarar');
                                       
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
                                   col5: {label: 'Inicio del Contrato', sortable: false},
                                   col6: {label: 'Termino de Contrato', sortable: false},
                                   col7: {label: 'Cesado', sortable: false},
                                   col8: {label: 'Mes del pago anterior', sortable: false},
                                   col9: {label: 'Monto Pagado este mes S./', sortable: false},
                                   col11: {label: 'Observacion', sortable: false} 
                 
                        };
                        

                           Planillas.Ui.Grids.sunat_bajas  = new  (declare([Grid, Selection,Keyboard]))({
                                   
                                   store: store,
                                   loadingMessage : 'Cargando',
                                   getBeforePut: true,
                                   columns: colums,
                                   pagingLinks: false,
                                   pagingTextBox: true,
                                   firstLastArrows: true,
                                   rowsPerPage : 50


                           }, "table_sunatbajas");
   
          }
    
         if(dojo.byId('table_sunataltas') != null )
         { 
                  
                   if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                            
                 
                    var store = JsonRest({
                          target: app.getUrl() +"impuestos/sunat_tregistro/altas", 
                            idProperty: "id",
                            sortParam: 'oby',
                            query: function(query, options){

                                   
                                   var data  = dojo.formToObject('form_sunat_declarar');
                                   
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
                                 col5: {label: 'Inicio del Contrato', sortable: false},
                                 col6: {label: 'Termino de Contrato', sortable: false},
                                 col7: {label: 'Cesado', sortable: false},
                                 col8: {label: 'Mes del pago anterior', sortable: false},
                                 col9: {label: 'Monto Pagado este mes S./', sortable: false},
                                 col11: {label: 'Observacion', sortable: false} 
                            
                     };
                     

                        Planillas.Ui.Grids.sunat_bajas  = new  (declare([Grid, Selection,Keyboard]))({
                                
                                store: store,
                                loadingMessage : 'Cargando',
                                getBeforePut: true,
                                columns: colums,
                                pagingLinks: false,
                                pagingTextBox: true,
                                firstLastArrows: true,
                                rowsPerPage : 50


                        }, "table_sunataltas");

          }


         if(dojo.byId('table_sunat_planillas_seleccionadas') != null )
         { 

             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                    var store_planillas = Observable(Cache(JsonRest({
                                            target: app.getUrl() +"planillas/registro_planillas/seleccionadas_sunat", 
                                            idProperty: "id",
                                            sortParam: 'oby',
                                            query: function(query, options){

                                                    var data  = dojo.formToObject('form_sunat_declarar');
                                                   
                                                    for(d in data){
                                                         query[d] = data[d];
                                                    } 

                                                    data.mostrar_planillas_seleccionadas = '1';

                                                    return JsonRest.prototype.query.call(this, query, options);
                                            }
                                    }), Memory()));

                                      var colums = {  
                                                
                                            col0: Selector({label : ''}),
                                            col1: {label:'#', sortable: true},
                                            col2: {label: 'Codigo', sortable: true},
                                            col3: {label: 'Tipo de Planilla', sortable: false},
                                            col4: {label: 'Des/Obs', sortable: false},
                                            col5: {label: 'Centro de Costo', sortable: false},
                                            col6: {label: 'Año', sortable: false},
                                            col7: {label: 'Mes', sortable: false},
                                            col8: {label: 'Estado', sortable: false}, 
                                            col9: {label: 'Num. Emps', sortable: false} 
                                              
                                        };



                                    Planillas.Ui.Grids.sunat_planillas_seleccionadas  = new  window.escalafon_grid({
                                                
                                              /*  store: store_planillas,
                                                loadingMessage : 'Cargando',
                                                getBeforePut: true,
                                                columns: colums, 
                                                firstLastArrows: true,
                                                rowsPerPage : 2 
*/

                                                store: store_planillas,
                                                loadingMessage : 'Cargando',
                                                selectionMode: "none",
                                                columns: colums,
                                                allowSelectAll: true


                                    }, "table_sunat_planillas_seleccionadas");
    
          }

            
    });

}



callback_fns.asistencias_explorar_obreros = function(){
 
  var dims = app.get_dims_body_app(),
      desc_altura = (dims.h > 700) ? (200) : 0;
       
    dijit.byId('asistencias_explorar_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                
 
    var calendars = ['cal_expasis_desde'];
    var fecha = $_currentDate();
    dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});
 

    dojo.connect( dijit.byId('selexplorarasis_tipobusqueda'), 'onChange', function(evt){

          var tipobusqueda =  dijit.byId('selexplorarasis_tipobusqueda').get('value');
 
          if(tipobusqueda == '1') // Regimen
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'table-row');


          }
          else if(tipobusqueda == '2') // Proyecto
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'none');
          }
          else if(tipobusqueda == '3') // Area
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'table-row');
          }
          else if(tipobusqueda == '4') // hoja
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'none');
          }
          else if(tipobusqueda == '5') // trabajador
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'none');
          }
          else
          { 
             dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'table-row');
             dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
             dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
             dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
             dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'table-row');
          }


          
    });


 
}



callback_fns.asistencias_explorar_empleados = function(){
 
  var dims = app.get_dims_body_app(),
      desc_altura = (dims.h > 700) ? (200) : 0;
       
    dijit.byId('asistencias_explorar_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});                
 
    var calendars = ['cal_expasis_desde'];
    var fecha = $_currentDate();
    dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});
 

    dojo.connect( dijit.byId('selexplorarasis_tipobusqueda'), 'onChange', function(evt){

          var tipobusqueda =  dijit.byId('selexplorarasis_tipobusqueda').get('value');
 
          if(tipobusqueda == '1') // Regimen
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'table-row');


          }
          else if(tipobusqueda == '2') // Proyecto
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'none');
          }
          else if(tipobusqueda == '3') // Area
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'table-row');
          }
          else if(tipobusqueda == '4') // hoja
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'none');
          }
          else if(tipobusqueda == '5') // trabajador
          {
              dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'table-row');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'none');
          }
          else
          { 
             dojo.setStyle( dojo.byId('trexplorarasis_regimen'), 'display', 'table-row');
             dojo.setStyle( dojo.byId('trexplorarasis_proyecto'), 'display', 'none');
             dojo.setStyle( dojo.byId('trexplorarasis_area'), 'display', 'none');
             dojo.setStyle( dojo.byId('trexplorarasis_hoja'), 'display', 'none');
             dojo.setStyle( dojo.byId('trexplorarasis_trabajador'), 'display', 'none');
              dojo.setStyle( dojo.byId('trexplorarasis_mostraractivos'), 'display', 'table-row');
          }


          
    });
  
}



callback_fns.asistencias_configuracion = function(){
 

    var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
         
    dijit.byId('dvasistencias_asistencia_config').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
    


    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


         if(dojo.byId('table_tipoplanilla_asistencia') != null )
         { 

             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                    var store = Observable(Cache(JsonRest({
                            target: app.getUrl() +"tipoplanillas/registro", 
                            idProperty: "id",
                            sortParam: 'oby',
                            query: function(query, options)
                            {

                                    var data = {}  
                                    return JsonRest.prototype.query.call(this, query, options);
                            }
                    }), Memory()));


                    var columnas = {  
                             
                            col1: {label: '#', sortable: true},
                            col2: {label: 'Nombre', sortable: false}, 
                            activo_asistencia: {label: 'Activo para Asistencia', sortable: false}
                       
                    };
    

                    Asistencias.Ui.Grids.tipoplanilla_config = new  window.escalafon_grid({
                       
                                store: store,  
                                loadingMessage : 'Cargando',
                                getBeforePut: false, 
                                columns: columnas,
                                pagingLinks: false,
                                pagingTextBox: true,
                                firstLastArrows: true 

                    }, "table_tipoplanilla_asistencia");


                    if( Asistencias.Ui.Grids.tipoplanilla_config != null)
                    {
                       Asistencias.Ui.Grids.tipoplanilla_config.refresh();
                    }          
          }




          if(dojo.byId('table_estadodeldia') != null )
          { 

              if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                     var store = Observable(Cache(JsonRest({
                             target: app.getUrl() +"asistencias/get_estados_del_dia", 
                             idProperty: "id",
                             sortParam: 'oby',
                             query: function(query, options)
                             {

                                     var data = {}  
                                     return JsonRest.prototype.query.call(this, query, options);
                             }
                     }), Memory()));


                     var columnas = {  
                              
                             col1: {label: '#', sortable: true},
                             col2: {label: 'Nombre ', sortable: false}, 
                             col3: {label: 'Nombre corto', sortable: false}, 
                             col4: {label: 'Alias en hoja ', sortable: false}, 
                             col5: {label: 'Desde escalafón', sortable: false} 
                        
                     };
          

                     Asistencias.Ui.Grids.estados_del_dia = new  window.escalafon_grid({
                        
                                 store: store,  
                                 loadingMessage : 'Cargando',
                                 getBeforePut: false, 
                                 columns: columnas,
                                 pagingLinks: false,
                                 pagingTextBox: true,
                                 firstLastArrows: true 

                     }, "table_estadodeldia");


                     if( Asistencias.Ui.Grids.estados_del_dia != null)
                     {
                         
                     }          
           }


           if(dojo.byId('table_horarios') != null )
           { 

               if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                      var store = Observable(Cache(JsonRest({
                              target: app.getUrl() +"asistencias/get_horarios", 
                              idProperty: "id",
                              sortParam: 'oby',
                              query: function(query, options)
                              {

                                      var data = {}  
                                      return JsonRest.prototype.query.call(this, query, options);
                              }
                      }), Memory()));


                      var columnas = {  
                               
                              col1: {label: '#', sortable: true},
                              col2: {label: 'Descripcion ', sortable: false}, 
                              col3: {label: 'Horario de trabajo', sortable: false} 
                         
                      };
           

                      Asistencias.Ui.Grids.horarios = new  window.escalafon_grid({
                         
                                  store: store,  
                                  loadingMessage : 'Cargando',
                                  getBeforePut: false, 
                                  columns: columnas,
                                  pagingLinks: false,
                                  pagingTextBox: true,
                                  firstLastArrows: true 

                      }, "table_horarios");


                      if( Asistencias.Ui.Grids.horarios != null)
                      {
                          
                      }          
            }



    });
      

} 
 


callback_fns.mis_papeletas_salida = function(){

      var dims = app.get_dims_body_app(),
          desc_altura = (dims.h > 700) ? (200) : 0;
           
        dijit.byId('mispermisos_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0}); 
       

}




callback_fns.permisos_aprobacion = function(){


    var dims = app.get_dims_body_app(),
        desc_altura = (dims.h > 700) ? (200) : 0;
         
      dijit.byId('permisoaprobacion_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0}); 


      var fecha = $_currentDate();

      if( dijit.byId('calaprobacion_dia') != null )
      {
          dijit.byId('calaprobacion_dia').set('value', fecha);  
      }

        
      require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                    function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                 if(dojo.byId('tablapermisos_panel') != null )
                 { 
                          
                           if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                    
                         
                            var store_trabajadores = JsonRest({
                                  target: app.getUrl() +"permisos/get_solicitudes", 
                                    idProperty: "id",
                                    sortParam: 'oby',
                                    query: function(query, options){

                                           var data = dojo.formToObject('formaprobacionpanel');
                                            
                                            for(d in data){
                                                query[d] = data[d];
                                            } 
                                             
                                            return JsonRest.prototype.query.call(this, query, options);
                                    }
                            });

                             var colums = { 
                                         
                                        
                                         col1: {label: '#', sortable: true},                                                                          
                                         estado: {label: 'E', sortable: false},
                                         col2: {label: 'Trabajador', sortable: false},
                                         col3: {label: 'DNI', sortable: false},
                                         col4: {label: 'Fecha', sortable: false},
                                         col5: {label: 'Hora de Salida', sortable: false},
                                         col6: {label: 'Hora de Retorno', sortable: false},
                                         col7: {label: 'Motivo', sortable: false}  
                                       
                                 };
                             

                                Planillas.Ui.Grids.permisos_panel  = new  (declare([Grid, Selection,Keyboard]))({
                                        
                                        store: store_trabajadores,
                                        loadingMessage : 'Cargando',
                                        getBeforePut: true,
                                        columns: colums,
                                        pagingLinks: false,
                                        pagingTextBox: true,
                                        firstLastArrows: true,
                                        rowsPerPage : 50


                                }, "tablapermisos_panel");
        
                  }


                 var memoryStore = new Memory({});
                 var restStore = new JsonRest({

                          target:"escalafon/provide/individuos/todos/no_especificar", 
                          idProperty: "value",
                          sortParam: 'oby',
                          query: function(query, options){
                               
                             
                              return dojo.store.JsonRest.prototype.query.call(this, query, options);
                          }

                    }); 

                  Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                  Persona.Stores.individuos.query({}); 
                  dijit.byId('selspermisoaprobacion_solicita').set('store',Persona.Stores.individuos);
 
 
                    
        });


}
 

callback_fns.charts_panel = function(){


    console.log('Test loader Ini');


}




callback_fns.quintacategoria_configuracion = function(){
 

    var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
         
    dijit.byId('dvquintacategoria_configuracion').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
    


    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                            function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


         if(dojo.byId('table_tipoplanilla_quintacategoria') != null )
         { 

             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                    var store = Observable(Cache(JsonRest({
                            target: app.getUrl() +"tipoplanillas/registro", 
                            idProperty: "id",
                            sortParam: 'oby',
                            query: function(query, options)
                            {

                                    var data = {}  
                                    return JsonRest.prototype.query.call(this, query, options);
                            }
                    }), Memory()));


                    var columnas = {  
                             
                            col1: {label: '#', sortable: true},
                            col2: {label: 'Tipo', sortable: false} 
                       
                    };
    

                    QuintaCategoria.Ui.Grids.tipoplanilla_config = new  window.escalafon_grid({
                       
                                store: store,  
                                loadingMessage : 'Cargando',
                                getBeforePut: false, 
                                columns: columnas,
                                pagingLinks: false,
                                pagingTextBox: true,
                                firstLastArrows: true 

                    }, "table_tipoplanilla_quintacategoria");


                    if( QuintaCategoria.Ui.Grids.tipoplanilla_config != null)
                    {
                       QuintaCategoria.Ui.Grids.tipoplanilla_config.refresh();
                    }          
          }




          if(dojo.byId('table_estadodeldia') != null )
          { 

              if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                     var store = Observable(Cache(JsonRest({
                             target: app.getUrl() +"asistencias/get_estados_del_dia", 
                             idProperty: "id",
                             sortParam: 'oby',
                             query: function(query, options)
                             {

                                     var data = {}  
                                     return JsonRest.prototype.query.call(this, query, options);
                             }
                     }), Memory()));


                     var columnas = {  
                              
                             col1: {label: '#', sortable: true},
                             col2: {label: 'Nombre ', sortable: false}, 
                             col3: {label: 'Nombre corto', sortable: false}, 
                             col4: {label: 'Alias en hoja ', sortable: false}, 
                             col5: {label: 'Desde escalafón', sortable: false} 
                        
                     };
          

                     Asistencias.Ui.Grids.estados_del_dia = new  window.escalafon_grid({
                        
                                 store: store,  
                                 loadingMessage : 'Cargando',
                                 getBeforePut: false, 
                                 columns: columnas,
                                 pagingLinks: false,
                                 pagingTextBox: true,
                                 firstLastArrows: true 

                     }, "table_estadodeldia");


                     if( Asistencias.Ui.Grids.estados_del_dia != null)
                     {
                         
                     }          
           }


           if(dojo.byId('table_horarios') != null )
           { 

               if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                      var store = Observable(Cache(JsonRest({
                              target: app.getUrl() +"asistencias/get_horarios", 
                              idProperty: "id",
                              sortParam: 'oby',
                              query: function(query, options)
                              {

                                      var data = {}  
                                      return JsonRest.prototype.query.call(this, query, options);
                              }
                      }), Memory()));


                      var columnas = {  
                               
                              col1: {label: '#', sortable: true},
                              col2: {label: 'Descripcion ', sortable: false}, 
                              col3: {label: 'Horario de trabajo', sortable: false} 
                         
                      };
           

                      Asistencias.Ui.Grids.horarios = new  window.escalafon_grid({
                         
                                  store: store,  
                                  loadingMessage : 'Cargando',
                                  getBeforePut: false, 
                                  columns: columnas,
                                  pagingLinks: false,
                                  pagingTextBox: true,
                                  firstLastArrows: true 

                      }, "table_horarios");


                      if( Asistencias.Ui.Grids.horarios != null)
                      {
                          
                      }          
            }



    });
      

} 





callback_fns.quinta_registro_retenciones = function(){
 

    var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
         
    dijit.byId('dvregistroquinta_panel').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
    
 
} 

callback_fns.registro_diario = function(){
   
}


callback_fns.registro_licencias = function(){

     var dims = app.get_dims_body_app(),
             desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
           
      dijit.byId('dvRegistroLicencia').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
      

      require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                    function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                 if(dojo.byId('tablalicencias_panel_registradas') != null )
                 { 
                          
                           if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));
                                    
                         
                            var store_trabajadores = JsonRest({
                                    target: app.getUrl() +"estadodiarouter/get_licencias_desdepanel_dia", 
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
                                          
                                         'col1': {label: '#', sortable: true},      
                                         'col2': {label: 'Trabajador', sortable: false},
                                         'col3': {label: 'DNI', sortable: false},
                                         'tipotrabajador': {label: 'Tipo Trabajador', sortable: false},
                                         'col4': {label: 'Tipo', sortable: false},
                                         'col5': {label: 'Desde', sortable: false},
                                         'col6': {label: 'Hasta', sortable: false},
                                         'observacion': {label: 'Detalle/Observacion', sortable: false},
                                         'col7': {label: 'Dias', sortable: false}, 
                                         'fecha_registro': {label: 'Fe.Registro', sortable: false}
                                       
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


                                }, "tablalicencias_panel_registradas");

                                EstadoDia.Ui.Grids.licencias_registradas.refresh();
        
                  }


                 var memoryStore = new Memory({});
                 var restStore = new JsonRest({

                          target:"escalafon/provide/individuos/todos/no_especificar", 
                          idProperty: "value",
                          sortParam: 'oby',
                          query: function(query, options){
                               
                             
                              return dojo.store.JsonRest.prototype.query.call(this, query, options);
                          }

                    }); 

                  Persona.Stores.individuos =  new  Cache(restStore, memoryStore);
                  Persona.Stores.individuos.query({}); 
                  dijit.byId('sellicenciatrabajador').set('store',Persona.Stores.individuos);
      
      
                    
        });



    dojo.connect( dijit.byId('selRLTipoPeriodo'), 'onChange', function(evt){

        var v  = dijit.byId('selRLTipoPeriodo').get('value');

        dojo.setStyle( dojo.byId('divPeriodoAnios'), 'display', 'none'); 
        dojo.setStyle( dojo.byId('divPeriodoIntervaloFechas'), 'display', 'none'); 
                
        if(v == 'anio'){

           dojo.setStyle( dojo.byId('divPeriodoAnios'), 'display', 'inline-block'); 
        
        }
        else
        {
           dojo.setStyle( dojo.byId('divPeriodoIntervaloFechas'), 'display', 'inline-block'); 
        }

    });

    dijit.byId('selRLTipoPeriodo').onChange();


    dojo.connect( dijit.byId('buscarPorTrabajadorTipoTrabajador'), 'onChange', function(evt){

        var v  = dijit.byId('buscarPorTrabajadorTipoTrabajador').get('value');

        dojo.setStyle( dojo.byId('dvbuscarTipoTrabajador'), 'display', 'none'); 
        dojo.setStyle( dojo.byId('dvbuscarTrabajador'), 'display', 'none'); 
                
        if(v == 'tipotrabajador'){

           dojo.setStyle( dojo.byId('dvbuscarTipoTrabajador'), 'display', 'inline-block'); 
        
        }
        else
        {
           dojo.setStyle( dojo.byId('dvbuscarTrabajador'), 'display', 'inline-block'); 
        }

    });

    dijit.byId('buscarPorTrabajadorTipoTrabajador').onChange();



    dojo.connect( dijit.byId('selxf_agruparpor'), 'onChange', function(evt){

        var v  = dijit.byId('selxf_agruparpor').get('value');

        dojo.setStyle( dojo.byId('dvregistrolicenciasTotalAcumulado'), 'display', 'none');  
                
        if(v != '0'){

           dojo.setStyle( dojo.byId('dvregistrolicenciasTotalAcumulado'), 'display', 'inline-block'); 
        
        } 
        
    });

    dijit.byId('selxf_agruparpor').onChange();



 
   var date = new Date();
   
   dijit.byId('calLicenciasRegistradas_desde').set('value', date);
   dijit.byId('calLicenciasRegistradas_hasta').constraints.min = date;
    
   dijit.byId('calLicenciasRegistradas_hasta').set('value', date);

}


 
callback_fns.mnu_contabilidad_inicio = function(){

       var dims = app.get_dims_body_app(),
           desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
        
        dijit.byId('planilla_reporter_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});

        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                 if(dojo.byId('dv_reportes_filtroplanilla') != null )
                                 { 

                                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                            var store_planillas = Observable(Cache(JsonRest({
                                                                    target: app.getUrl() +"planillas/registro_planillas/reporter", 
                                                                    idProperty: "id",
                                                                    sortParam: 'oby',
                                                                    query: function(query, options){

                                                                           var data  = dojo.formToObject('form_reportes_planilla_filtro');
                                                                           
                                                                            for(d in data){
                                                                                 query[d] = data[d];
                                                                            }

                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                    }
                                                            }), Memory()));

                                                              var colums = {  
                                                                        
                                                                        col0: Selector({label : ''}),
                                                                        col1: {label: '#', sortable: true},
                                                                        col2: {label: 'Codigo', sortable: false},
                                                                        col3: {label: 'Año', sortable: false},
                                                                        col4: {label: 'Mes', sortable: false},
                                                                        col5: {label: 'Tipo de Planilla', sortable: false},
                                                                        col6: {label: 'Des/Obs', sortable: false},
                                                                        col7: {label: 'Centro de Costo', sortable: false},
                                                                        col8: {label: 'Estado', sortable: false}, 
                                                                        col9: {label: 'Num. Emps', sortable: false} 
                                                                      
                                                                };



                                                            Planillas.Ui.Grids.planillas_reporte_filtro  = new  window.escalafon_grid({
                                                                        
                                                                      /*  store: store_planillas,
                                                                        loadingMessage : 'Cargando',
                                                                        getBeforePut: true,
                                                                        columns: colums, 
                                                                        firstLastArrows: true,
                                                                        rowsPerPage : 2 
*/

                                                                        store: store_planillas,
                                                                        loadingMessage : 'Cargando',
                                                                        selectionMode: "none",
                                                                        columns: colums,
                                                                        allowSelectAll: true


                                                            }, "dv_reportes_filtroplanilla");

                                                  if( Planillas.Ui.Grids.planillas_reporte_filtro != null)
                                                  {
                                                  
                                                     Planillas.Ui.Grids.planillas_reporte_filtro.refresh();
                                                  
                                                  }          
                                  }



                    });
  

      dojo.connect( dijit.byId('selplanireport_anio'), 'onChange', function(evt){
                           
               Planillas.Ui.Grids.planillas_reporte_filtro.refresh();          
                          
      });

      dojo.connect( dijit.byId('selplanireport_mes'), 'onChange', function(evt){
                           
               Planillas.Ui.Grids.planillas_reporte_filtro.refresh();          
                          
      });
      
      dojo.connect( dijit.byId('selplanireport_plati'), 'onChange', function(evt){
                           
               Planillas.Ui.Grids.planillas_reporte_filtro.refresh();          
                          
      });

}

callback_fns.mnuasistencia_especificarlugar = function(){


   var dims = app.get_dims_body_app(),
       desc_altura = (dims.h > 700) ? (descuento_altura_frame) : 0;
    
   dijit.byId('escalaregistro_container').resize({w: dims.w ,h: (dims.h - 70 -desc_altura), l: 0, t:0});
     
             
   require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
               function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                if(dojo.byId('dvespecificarlugar_de_trabajo') != null ){ 

                    if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                           var store_trabajadores = JsonRest({
                                                   target:app.getUrl() + "escalafon/get_trabajadores", 
                                                   idProperty: "id",
                                                   sortParam: 'oby',
                                                   query: function(query, options){
   
                                                               
                                                           var data = dojo.formToObject('form_registro_consulta');
                                                            
                                                            for(d in data){
                                                                query[d] = data[d];
                                                            }
                                                             
                                                            return JsonRest.prototype.query.call(this, query, options);
                                                   }
                                           });

                                           var colums = { 
                                                      col0: Selector({}),
                                                      num: {label:'#', sortable: true},
                                                      trabajador: {label: 'Trabajador', sortable: false},
                                                      dni: {label: 'DNI', sortable: false},
                                                      tipo_trabajador: {label: 'Tipo de trabajador', sortable: false},
                                                      lugar_de_trabajo: {label: 'Lugar de trabajo', sortable: false} 
                                                     
                                           };

    
   
                                           Planillas.Ui.Grids.especificar_lugar_trabajo  = new  (declare([Grid, Selection,Keyboard]))({

                                                   store: store_trabajadores,
                                                   loadingMessage : 'Cargando',
                                                   allowSelectAll: true,
                                                   getBeforePut: true,
                                                   selectionMode: "none",
                                                   columns: colums,
                                                   pagingLinks: false,
                                                   pagingTextBox: true,
                                                   firstLastArrows: true,
                                                   rowsPerPage : 100


                                           }, "dvespecificarlugar_de_trabajo");

                                 if( Planillas.Ui.Grids.especificar_lugar_trabajo != null){
                             
                               }          
                 }
   
   });
       

}

 
var MENU_MAP = [
                   {dom_id: 'mnuescala_registro', url: app.url + 'escalafon/ui_personal_registrado', data: '', callback: callback_fns.mnuescala_registro},
                   {dom_id: 'mnuvc_gestionar', url: app.url + 'planillas/ui_gestionar_conceptos', data: '', callback: callback_fns.mnuplanillas_conceptos_gestionar},
                   {dom_id: 'mnudt_gestionar', url: app.url + 'trabajadores/gestionar_datos', data: '', callback: callback_fns.mnutrabajador_gestiondatos},
                   {dom_id: 'mnupla_registrohojas', url: app.url + 'asistencias/ver_hojas', data: '', callback:  function(){}},
                   {dom_id: 'mnupla_reportes', url: app.url + 'exportar/exportacion_y_reportes', data: '', callback: callback_fns.mnu_planilla_reportes },
                   {dom_id: 'mnuescalafon_actdata', url: app.url + 'escalafon/actualizacion_de_datos', data: '', callback: callback_fns.actualizacion_de_datos },
                   {dom_id: 'mnuescala_explorarfechas', url: app.url + 'escalafon/explorar_por_fechas', data: '', callback:  callback_fns.explorar_por_fechas  },  
                   {dom_id: 'mnugtp_gestionar', url: app.url + 'tipoplanillas/gestionar', data: '', callback:  callback_fns.gestionar_tipo_planilla  },  
                   {dom_id: 'mnuescala_gestion_sl', url: app.url + 'escalafon/gestion_historial_laboral', data: '', callback:  callback_fns.gestionar_historial_laboral  },  
                   {dom_id: 'mnusistema_usuarios', url: app.url + 'usuarios/gestionar_usuarios', data: '', callback:  callback_fns.gestionar_usuarios  },  
                   {dom_id: 'mnuxls_importar_trabajadores', url: app.url + 'importacionxls/panel_importar_trabajadores', data: '', callback:  callback_fns.importar_trabajadores_panel  },  
                   {dom_id: 'mnuimpuesto_quinta', url: app.url + 'impuestos/quinta_categoria', data: '', callback:  callback_fns.quinta_categoria  },  
                   {dom_id: 'mnuimpuesto_afp', url: app.url + 'impuestos/afp', data: '', callback:  callback_fns.afp  },
                   {dom_id: 'mnuimpuesto_sunat', url: app.url + 'impuestos/sunat', data: '', callback:  callback_fns.sunat  },
                   {dom_id: 'mnuasistencia_explorar', url: app.url + 'asistencias/explorar_obreros', data: '', callback:  callback_fns.asistencias_explorar_obreros  },    
                   {dom_id: 'mnuasistencia_explorar_emple', url: app.url + 'asistencias/explorar_empleados', data: '', callback:  callback_fns.asistencias_explorar_empleados  },    
                   {dom_id: 'mnuasistencia_config', url: app.url + 'asistencias/configuracion', data: '', callback:  callback_fns.asistencias_configuracion  },   
                   {dom_id: 'mnupermisos_missolicitudes', url: app.url + 'permisos/mis_permisos', data: '', callback:  callback_fns.mis_papeletas_salida  },
                   {dom_id: 'mnuquinta_configuracion', url: app.url + 'quintacategoriarouter/configuracion', data: '', callback:  callback_fns.quintacategoria_configuracion  },
                   {dom_id: 'mnuquinta_modulo', url: app.url + 'quintacategoriarouter/registro_retenciones', data: '', callback:  callback_fns.quinta_registro_retenciones  },
                   {dom_id: 'mnupermisos_aprobacion', url: app.url + 'permisos/aprobacion', data: '', callback:  callback_fns.permisos_aprobacion  },
                   {dom_id: 'mnuasistencia_registrodiario', url: app.url + 'asistencias/registro_diario', data: '', callback:  callback_fns.registro_diario  },
                   {dom_id: 'mnuasistencias_reglicencias', url: app.url + 'asistencias/registro_licencias', data: '', callback:  callback_fns.registro_licencias  },
                   {dom_id: 'mnucontabilidad_main', url: app.url + 'contabilidadcontroller/contabilizar_planillas', data: '', callback:  callback_fns.mnu_contabilidad_inicio  },
                   {dom_id: 'mnuasistencia_especificarlugar', url: app.url + 'asistencias/especificar_lugar_trabajo', data: '', callback:  callback_fns.mnuasistencia_especificarlugar  }
                  

              //    mnuescala_explorarfechas 

                //   {dom_id: 'mnuplanilla_registro', url: app.url + 'planillas/ui_registro_planillas', data: '', callback: callback_fns.mnuplanilla_registro} 
                //   {dom_id: 'mnuplanilla_nueva', url: app.url + 'planillas/ui_elaborar', data: '', callback: callback_fns.mnuplanilla_nueva} 
                
                 ];
 
 /* MENU EVENT CLICK */

  
dojo.ready(function(){
    

     app.render_bodi();
     app.ready_view_plattaform();
     
     dojo.forEach(MENU_MAP, function(mnu,ind){
           
            if(dojo.byId(mnu.dom_id) != null){ 
                     dojo.connect( dojo.byId(mnu.dom_id), 'onclick',function(){    
                      app.view_load( dojo.byId(mnu.dom_id), {view: mnu.url, fn: mnu.callback} );  
             
                    }); 
            }
     });
      
     
     if(dojo.byId('mnuescala_nuevo')  != null){ 
     
             dojo.connect( dojo.byId('mnuescala_nuevo'), 'onclick', function(e){

                   dijit.byId('dvescala_especidni').show();
                   Persona.Ui.form_espddni_reset();
                  
                 //  Persona.get_view_nuevo();
                   
             });

     }
     

     
     if(dojo.byId('mnuplanilla_nueva') != null){
         
           dojo.connect( dojo.byId('mnuplanilla_nueva'), 'onclick', Planillas.Ui.mnunew_planilla_click );
 
     }
     
    
     if(dojo.byId('mnuplanilla_registro') != null){
          // alert(Planillas);
           dojo.connect( dojo.byId('mnuplanilla_registro'), 'onclick', Planillas.Ui.mnuregistro_planilla_click );
 
     }
     
     if(dijit.byId('mnupla_nuevahoja') != null){
 
     }

     
     if(dijit.byId('txtdni_especificar') != null){
          
          
          // validar solo numeros 
          
           dojo.connect( dijit.byId('txtdni_especificar'), 'onKeyDown', function(e){
               
                //alert(e.keyCode);
                if(e.keyCode==13)
                {
                   dijit.byId('btndni_buscar').onClick();
                }
                else if( e.keyCode==8  ||   $_keycode_isnumber(e.keyCode)   )
                {
               
                }
                else{
                  
                    e.preventDefault();  
                }
               
                
           });
           
     }
     
         /*    @CAMBIOS3008 */    
     if(dojo.byId('mnasistencia_nuevahoja') != null){

     

             dojo.connect( dojo.byId('mnasistencia_nuevahoja'), 'onclick', function(e){
 
                 Asistencias._V.crear_nueva_hoja.load({}); 
                   
             });

     } 


  
      
      if( dojo.byId('mnu_afps') != null)
      {


            dojo.connect( dojo.byId('mnu_afps'), 'onclick', function(e){
                   
                  Afps._V.gestionar.load({});
                   
             });

      }

      if( dojo.byId('mnu_montoscs') != null)
      {


            dojo.connect( dojo.byId('mnu_montoscs'), 'onclick', function(e){
                  
                  tabla_variables_montos._V.gestionar.load({});
                   
             });

      }


      if( dojo.byId('mnu_vari_ar') != null)
      {


            dojo.connect( dojo.byId('mnu_vari_ar'), 'onclick', function(e){
            
                  Variables._V.acceso_rapido.load({});
                   
             });

      }


      if( dojo.byId('mnuxls_importardatos') != null )
      {

          dojo.connect( dojo.byId('mnuxls_importardatos'), 'onclick', function(){
 
               importacionxls._V.configuracion.load({});

          });

      }

      if( dojo.byId('mnuplanilla_boletaindividuales') != null)
      {


            dojo.connect( dojo.byId('mnuplanilla_boletaindividuales'), 'onclick', function(e){
                   
                   Planillas._V.boletas_individuales.load({});
                   
             });

      }

      
      if( dojo.byId('mnuplanilla_reportedescuento') != null)
      {


            dojo.connect( dojo.byId('mnuplanilla_reportedescuento'), 'onclick', function(e){
                   
                   Planillas._V.ver_reporte_conceptos.load({});
                   
             });

      }


      if( dojo.byId('mnuxls_exportar') != null)
      {
 
         dojo.connect( dojo.byId('mnuxls_exportar'), 'onclick', function(e){
                    
              Exporter._V.exportar_excel.load({});

         });

      }


      if( dojo.byId('mnucata_metas') != null)
      {
      
         dojo.connect( dojo.byId('mnucata_metas'), 'onclick', function(e){
                    
                Catalogos._V.Metas.load({});  

         });

      }



      if( dojo.byId('mnuasis_bioload') != null)
      {
      
         dojo.connect( dojo.byId('mnuasis_bioload'), 'onclick', function(e){
                    
                Biometrico._V.biometricos.load({});  

         });

      }
 

      if( dojo.byId('mnusunat_verificacionpdt') != null)
      {
      
         dojo.connect( dojo.byId('mnusunat_verificacionpdt'), 'onclick', function(e){
                    
                Impuestos._V.view_resumen_trabajador_pdt.load({});

         });

      }


      if( dojo.byId('mnusunat_pdtpensionable') != null)
      {
      
         dojo.connect( dojo.byId('mnusunat_pdtpensionable'), 'onclick', function(e){
                    
                Impuestos._V.view_pdtpensionable.load({});

         });

      }

 
      if( dojo.byId('mnuplanilla_reportemes') != null)
      {
      
         dojo.connect( dojo.byId('mnuplanilla_reportemes'), 'onclick', function(e){
                    
                Planillas._V.ver_reporte_concepto_mes.load({});

         });

      } 


      if( dojo.byId('mnupermisos_nuevo') != null)
      {
      
         dojo.connect( dojo.byId('mnupermisos_nuevo'), 'onclick', function(e){
                    
                Permisos._V.nueva_solicitud.load({});

         });

      }


    /*
    if(dojo.byId('mnupla_reportes') != null){
 
             dojo.connect( dojo.byId('mnupla_reportes'), 'onclick', function(e){
              
                   console.log('AA ');

             });

     } */
      


     if(dojo.byId('mnasistencia_registrohojas') != null){
          // alert(Planillas);
           dojo.connect( dojo.byId('mnasistencia_registrohojas'), 'onclick', Asistencias.Ui.mnuregistro_asistencia_click );

     }


     if(dojo.byId('mnusistema_abouthis') != null){
           
           var acerca_de =  new Laugo.View.Window({
             
              connect : 'about/acerca_de/',
              
              style : {
                   width : '370px',
                   height : '140px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Sobre Laurem ',
              
              onLoad : function(){
 
                        
              }
             
          })

           dojo.connect( dojo.byId('mnusistema_abouthis'), 'onclick', function(){ acerca_de.load({}) });

     }
      


    // app.view_load( null,  {view: app.url + 'estadisticas/panel_inicio' , fn: callback_fns.charts_panel } );  




     dojo.connect( document.body, "dgrid-select", function(event){
        
          Planillas.Ui.Grids.on_select(event);
     }); 
     

/*     dojo.on(document.body, "dgrid-select,dgrid-deselect",
      function(event){
        var msg = (event.type == "dgrid-deselect" ? "de" : "") + "selected";
        console.log(event.grid.id + ": " + msg +
          (event.parentType ? " (via " + event.parentType + "): " : ": "),
          dojo._base.array.map(event.rows, function(row){ return row.id; }).join(", "));

        console.log(event.grid.selection);

        console.log("selection: ", dojo.json.stringify(event.grid.selection, null, "  "));
      }
     );
*/
    
     /*
         Prevenir el backspace automatico y el submit en los formularios el teclar enter
     */
     dojo.connect( document.body, "keydown", function(event){
            
            var doPrevent = false;
            if (event.keyCode === 8 ){

                var d = event.srcElement || event.target;
                if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD')) 
                     || d.tagName.toUpperCase() === 'TEXTAREA') {
                    doPrevent = d.readOnly || d.disabled;
                }
                else {
                    doPrevent = true;
                }
            }

            if (doPrevent) {
                event.preventDefault();
            }
     });

     
});




window.onbeforeunload = function (e) {
  var message = "Esta a punto de salir del sistema",
  e = e || window.event;
  // For IE and Firefox
  if (e) {
    e.returnValue = message;
  }

  // For Safari
  return message;
};