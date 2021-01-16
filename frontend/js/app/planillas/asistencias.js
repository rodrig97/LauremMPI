 
var Asistencias = {
	
	Cache : {
      
      test_ancho_dinamico : '1000px',
      view_hoja_preview_importacion : null,
      ultima_celda_seleccionada : null,
      ultima_celda_actualizada : null
	},

  hoja_ready :  function(){

         if(dojo.byId('table_asistencia_calendario') != null)
         { 
          
             var fr = dojo.query('#table_asistencia_calendario tr')[0];
             var w_f = [40,300];

             var tipo_calendario = dojo.byId('hdcalendariotipo').value;
           
            /* for(var i=2; i<= dojo.query('td',fr).length; i++  )
             {
                 w_f[i] = '35';
             }*/

             var hoja_celda_onclick = function(tdfecha){

                  var data = {}

                  if(tipo_calendario == '1')
                  {
                    data.hoja       = dojo.byId('hdviewasistencia_id').value;
                  }
                  
                  data.fecha      = dojo.query('.tdhojafecha',tdfecha)[0].value;
                  data.trabajador = dojo.query('.tdhojatrabajador',tdfecha)[0].value;
                   
                  Asistencias.Cache.ultima_celda_seleccionada = tdfecha; 
                  
                  if(tipo_calendario == '1')
                  {
                    Asistencias._V.detalle_dia.load(data);
                  }
                  else
                  {
                    Asistencias._V.dia_info.load(data);
                  }

             }



             var hojaasistencia = new superTable("table_asistencia_calendario", {
                            cssSkin : "sDefault",
                            headerRows : 3,
                            fixedCols : 2,
                            colWidths : w_f,
                            onStart : function () {
                           
                            },
                            onFinish : function () {
                              
                            }
                          });
                 

              var rows =  dojo.query("#table_asistencia_calendario .tr_detalle"), 
                  nombres_trabajadores =  dojo.query('.td_nombre_trabajador'), 
                  r = ( nombres_trabajadores.length / 2);
        

              dojo.forEach( rows, function(row,ind){
        
                   var tdsfecha = dojo.query('.td_fecha', row);

                   dojo.forEach( tdsfecha , function(tdfecha, k){


                        dojo.connect(tdfecha,'onclick', function(){
                               
                             hoja_celda_onclick(tdfecha);

                        });


                         dojo.connect(tdfecha,'onmouseenter', function(){
                              
                              try{  
                                
                                if((ind-r) > 0)
                                {
                                  dojo.setStyle(nombres_trabajadores[(ind-r)],'backgroundColor','#FFFFCE');       
                                }
                                dijit.showTooltip(  dojo.query('.ttmensaje',tdfecha)[0].value ,tdfecha,['above']);
                                 
                              }catch(e){
                                   
                              }
                          
                         }); 
        

                         dojo.connect(tdfecha,'onmouseout', function(){
                              
                               // COLUMNAS

                               try{ 

                                  if((ind-r) > 0)
                                  {
                                    dojo.setStyle(nombres_trabajadores[(ind-r)],'backgroundColor','');
                                  }

                                  dijit.hideTooltip(tdfecha); 

                               }catch(e){
                                    
                               }
                               
        
                         }); 



                   });

              });
              
              if( dojo.byId('hdviewasistencia_estado_id') != null && tipo_calendario == '1' )
              {
 
                  if(dojo.byId('hdviewasistencia_estado_id').value ==  app._consts.hojaestado_elaborar)
                  {     
                       var deletes =  dojo.query("#table_asistencia_calendario .spdetcal_delete");
                         
                        dojo.forEach(deletes, function(del,ind){

                               dojo.connect(del,'onclick', function(evt){

                                    var data = {}
                                    data.detalle = dojo.query('.spdetcal_indkey', del.parentNode )[0].value;
                                    
                                    data.hoja =  dojo.byId('hdviewasistencia_id').value;
                                    if(confirm('Realmente desea quitar al trabajador de la hoja actual?')){
                                         if(Asistencias._M.quitar_empleado.process(data)){

                                        
                                                  Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value);        
                                          }
                                    } 

                               });

                        });

                        var cambiarcategoria =  dojo.query("#table_asistencia_calendario .spdetcal_cambiarcategoria");
                          
                         dojo.forEach(cambiarcategoria, function(del,ind){

                                dojo.connect(del,'onclick', function(evt){

                                     var data = {}
                                     data.detalle = dojo.query('.spdetcal_indkey', del.parentNode )[0].value;
                                     data.categoria = dojo.query('.spdetcal_categoria', del.parentNode )[0].value;
                                     data.hoja =  dojo.byId('hdviewasistencia_id').value;
                                     
                            
                                     Asistencias._V.cambiar_categoria_detalle.load(data);

                                });

                         });




                          var ae = dojo.byId('hdaccesoescalafon').value;
    
                          if(ae == '1')
                          {
  
                              var trabajadores =  dojo.query("#table_asistencia_calendario .spnombretrabajador");
       
                               dojo.forEach(trabajadores, function(del,ind){

                                    dojo.connect(del,'onclick', function(evt){
                                         var data = {}
                                         data.detalle = dojo.query('.spdetcal_individuo', del.parentNode )[0].value;
                                         data.hoja =  dojo.byId('hdviewasistencia_id').value;
                                          
                                         Persona._V.full_info_persona.load({'empkey' : data.detalle, 'from' : 'asistencia'});
                                    });

                               });

                          }    

                  }   
              }

              if(Asistencias.Cache.ultima_celda_actualizada != null) dojo.setStyle( Asistencias.Cache.ultima_celda_actualizada,'fontWeight','bold');       
              
        }


        if(dojo.byId('asistencias_table_hojaresumen') != null )
        {


            require(["dgrid/List", "dgrid/OnDemandGrid", "dgrid/ColumnSet","dgrid/Selection", "dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dgrid/extensions/ColumnHider","dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                             function(List, Grid, ColumnSet, Selection,  Selector,editor, Keyboard, Pagination, ColumnHider, declare, JsonRest, Observable, Cache, Memory){



                                var ComplexGrid = declare([Grid, ColumnSet, Selection, Keyboard]);

                                var estructura_txt = dojo.byId('estructura_hojaresumen').value;
                                var struct = dojo.json.parse(estructura_txt);
                                
                                var contador_registros_importacion = 0;
                                  
                           
                                var store = Observable(Cache(JsonRest({
                                          target:  app.getUrl() + "asistencias/get_table_hoja_resumen/", 
                                          idProperty: "id",
                                          sortParam: 'oby',
                                          query: function(query, options){
                                                
                                                  if(dojo.byId('form_asistencia_calendario_config') != null )
                                                  {

                                                      var data = dojo.formToObject('form_asistencia_calendario_config');
                                                      
                                                      for(x in data)
                                                      {
                                                        query[x] = data[x];
                                                      } 

                                                      var data = dojo.formToObject('form_asistencia_hojaresumen_filtro');
                                                      
                                                      for(x in data)
                                                      {
                                                        query[x] = data[x];
                                                      }
                                                } 
        
                                                return JsonRest.prototype.query.call(this, query, options);
                                        }
                                }), Memory()));
                                
                                 Planillas.Ui.Grids.asistencias_hojaresumen_tabla = new ComplexGrid({

                                        store: store,
                                        loadingMessage : 'Cargando',
                                        getBeforePut: false, 
                                        columnSets: struct 


                                }, "asistencias_table_hojaresumen");
                                 
                                

                                Planillas.Ui.Grids.asistencias_hojaresumen_tabla.refresh(); 

                     });
 
        }


  },
	
	_M : {	

	  registrar_hoja: new Laugo.Model({
              connect: 'asistencias/registrar_hoja'
    }),

		eliminar_hoja : new Laugo.Model({

         connect: 'asistencias/eliminar_hoja'

     }),

		agregar_empleado:  new Laugo.Model({

         connect : 'asistencias/agregar_empleado'
    }),

    quitar_empleado : new Laugo.Model({

          connect :  'asistencias/quitar_empleado'

    }),

    importar_trabajadores : new Laugo.Model({

               connect :  'asistencias/importar_trabajadores'

    }),

		registrar_detalle_dia : new Laugo.Model({

        connect : 'asistencias/registrar_detalle_dia'
    }),

    actualizar_detalle_dia_modulo_asistencia : new Laugo.Model({

        connect : 'asistencias/actualizar_detalle_dia_modulo_asistencia'

    }),

    finalizar_registro : new Laugo.Model({

         connect : 'asistencias/finalizar_registro'

    }),

    
    devolver_hoja_asistencia : new Laugo.Model({

               connect :  'asistencias/devolver'

    }),


    actualizar_categoria : new Laugo.Model({

               connect :  'asistencias/actualizar_categoria'

    }),


    registrar_tipoestadodia : new Laugo.Model({

               connect :  'asistencias/registrar_tipoestadodia'

    }),


    actualizar_tipoestadodia : new Laugo.Model({

               connect :  'asistencias/actualizar_tipoestadodia'

    }),


    registrar_horario : new Laugo.Model({

               connect :  'asistencias/registrar_horario'

    }),


    registrar_horario_pordefecto : new Laugo.Model({

               connect :  'asistencias/registrar_horario_pordefecto'

    }),

    actualizar_lugar_trabajo : new Laugo.Model({

               connect :  'asistencias/actualizar_lugar_trabajo'

    }),


    get_calendario : new Request({
              
              type :  'text',
              
              method: 'post',
              
              url : 'asistencias/get_calendario',
              
              onRequest : function(){
 
                  dijit.byId('dv_hoja_calendario').set('content', '<div class="dv_cargando">Cargando..</div>' );
                 //   dijit.byId('dv_hoja_calendario container_calendar').set('content','<div class="dv_cargando">Cargando..</div>');
              },
              
              onSuccess  : function(responseText){
 

                  dijit.byId('dv_hoja_calendario').set('content', responseText);
              
                  Asistencias.hoja_ready();

              },
              
              onFailure : function(){
                  
              } 
              
          }),




     get_registro_asistencia : new Request({
               
               type :  'text',
               
               method: 'post',
               
               url : 'asistencias/get_registro_asistencia',
               
               onRequest : function(){
                   
                   app.loader_show(); 
                   dijit.byId('dvpanel_explorar_asistencias').set('content', '<div class="dv_cargando">Cargando..</div>' );
                  //   dijit.byId('dvpanel_explorar_asistencias container_calendar').set('content','<div class="dv_cargando">Cargando..</div>');
               },
               
               onSuccess  : function(responseText){
                   
                   app.loader_hide(); 

                   dijit.byId('dvpanel_explorar_asistencias').set('content', responseText);

                 
                   dojo.forEach( dojo.query('.pagina') , function(elli, k){

                        
                         dojo.connect(elli,'onclick', function(){
                             
                              try{   

                                  var pagina = dojo.query('.paginapointer', elli)[0].value;

                                  dojo.byId('hdasistencias_explorar_paginador').value= pagina;

                                  var data =  dojo.formToObject('form_explorar_asistencias');
                                  Asistencias.get_registro_asistencia(data);
                                 
                              }catch(e){
                                  console.log(e);
                              }
                          
                          
                         });

                   }); 


                   Asistencias.hoja_ready();
                    

               },
               
               onFailure : function(){

                app.loader_hide();
                   
               } 
               
           }),


       get_registro_asistencia_calendario : new Request({
                 
                 type :  'text',
                 
                 method: 'post',
                 
                 url : 'asistencias/get_registro_asistencia_calendario',
                 
                 onRequest : function(){
       
                     dijit.byId('dv_registroasis_calendario').set('content', '<div class="dv_cargando">Cargando..</div>' );
           
                 },
                 
                 onSuccess  : function(responseText){

                   
        
                     dijit.byId('dv_registroasis_calendario').set('content', responseText);
                 
                     Asistencias.hoja_ready();

                 },
                 
                 onFailure : function(){
                     
                 } 
                 
             }),


       get_importacion_config : new Request({
              
              type :  'text',
              
              method: 'post',
              
              url : 'asistencias/get_importacion_config',
              
              onRequest : function(){ 
                    
                    dijit.byId('dv_importacion_asistencias_config_panel').set('content', '<div class="dv_cargando">Cargando..</div>' );
                
                     
              },
              
              onSuccess  : function(responseText){
                    
                

                   dijit.byId('dv_importacion_asistencias_config_panel').set('content', responseText );
 
                  
                  if(dijit.byId('cal_asistencia_importar_desde') != null )
                  {
                       var strDate = dojo.byId('hdasistencia_importar_desde').value;
                       var dateParts = strDate.split("-");
                       var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);

                       dijit.byId('cal_asistencia_importar_desde').constraints.min = date;
                       dijit.byId('cal_asistencia_importar_desde').set('value', date);
                  
                       strDate = dojo.byId('hdasistencia_importar_hasta').value;
                       dateParts = strDate.split("-");
                       date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);

                       dijit.byId('cal_asistencia_importar_hasta').set('value', date);
                       dijit.byId('cal_asistencia_importar_hasta').constraints.max = date;
                       dijit.byId('cal_asistencia_importar_desde').constraints.max = date;
                   }


                   if( Planillas.Ui.Grids.planillas_preview_importacion != null)
                   {

                      Planillas.Ui.Grids.planillas_preview_importacion.refresh();
                   }

              },
              
              onFailure : function(){
                  
              } 
              
          }),
 
          
          planilla_tipo_configurar_actualizar : new Laugo.Model({

                     connect :  'asistencias/planilla_tipo_configurar_actualizar'

          }),


          estadodia_plati_config_actualizar : new Laugo.Model({

                     connect :  'asistencias/estadodia_plati_config_actualizar'

          }), 



          revertir_importacion : new Laugo.Model({

                     connect :  'asistencias/revertir_importacion'

          }),


          actualizar_hora_salida_dia : new Laugo.Model({

                     connect :  'asistencias/actualizar_hora_salida_dia'

          }),

          actualizar_falta_dia : new Laugo.Model({

                     connect :  'asistencias/actualizar_falta_dia'

          }),


          exportar_asistencia_excel : new Request({
                  
                        type :  'json',                     
                        method: 'post',
                        url : 'asistencias/exportar_asistencia_excel',

                        timeout : 600000,
                        
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

	_V:{


    detalle_dia : new Laugo.View.Window({
    
        connect :  'asistencias/editar_diario',

        style : {
                   width : '380px',
                   height : '400px',
                   'background-color' : '#FFFFFF'
         },

        title : ' Detalle del dia',

        onLoad: function(){


                if(dijit.byId('selregistrodiario_estado') != null){  

                     var estados_con_marcaciones = dojo.json.parse(dojo.byId('hd_estados_dia_con_marcaciones').value);
                      
                     dojo.connect( dijit.byId('selregistrodiario_estado'), 'onChange', function(evt){
                             
                             var v = dijit.byId('selregistrodiario_estado').get('value'), 
                                con_marcaciones = false;

                             for(e in estados_con_marcaciones)
                             {  
                                if(v == estados_con_marcaciones[e])
                                {
                                    var con_marcaciones = true;
                                    break;
                                }
                             }

                            if(con_marcaciones)
                            {
                                
                                dojo.setStyle(dojo.byId('trregistrodiario_htra'), 'display', 'table-row' ); 
                                dojo.setStyle(dojo.byId('trregistrodiario_tar'), 'display', 'table-row' ); 
                                dojo.setStyle(dojo.byId('trregistrodiario_horario1'), 'display', 'table-row' );
                                
                                if(dojo.byId('trregistrodiario_horario2') != null)
                                {
                                  dojo.setStyle(dojo.byId('trregistrodiario_horario2'), 'display', 'table-row' );
                                }

                                if(dojo.byId('trregistrodiario_aplicarhasta') != null)
                                {                                
                                  dojo.setStyle(dojo.byId('trregistrodiario_aplicarhasta'), 'display', 'none' );
                                }
 
                            }
                            else{

                                
                                dojo.setStyle(dojo.byId('trregistrodiario_htra'), 'display', 'none' ); 
                                dojo.setStyle(dojo.byId('trregistrodiario_tar'), 'display', 'none' ); 
                                dojo.setStyle(dojo.byId('trregistrodiario_horario1'), 'display', 'none' );

                                if(dojo.byId('trregistrodiario_horario2') != null)
                                {

                                  dojo.setStyle(dojo.byId('trregistrodiario_horario2'), 'display', 'none' );
                                }

                                if(dojo.byId('trregistrodiario_aplicarhasta') != null)
                                { 
                                   dojo.setStyle(dojo.byId('trregistrodiario_aplicarhasta'), 'display', 'table-row' );
                                }
                            } 
                            
                        //    Planillas.Ui.Grids.planillas_preview.refresh();
                          
                      });
                      
                      var vc = document.getElementById('hdregistrodiario_estado').value;

                      dijit.byId('selregistrodiario_estado').set('value',vc);

                      dijit.byId('selregistrodiario_estado').onChange();

               } 

               
              if( dojo.byId('hddiariofechamin') != null)
              {

                    var strDate = dojo.byId('hddiariofechamin').value;
                    var dateParts = strDate.split("-");
                    var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                  
                    if(dijit.byId('cal_regdia_hasta') != null )
                    {
                         dijit.byId('cal_regdia_hasta').set('value', date);
                         dijit.byId('cal_regdia_hasta').constraints.min = date;
               
                         strDate = dojo.byId('hddiariofechamax').value;
                         dateParts = strDate.split("-");
                         date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                         dijit.byId('cal_regdia_hasta').constraints.max = date;
                     }
              }
              

              if( dojo.byId('hddiario_desdeescalafon') != null)
              { 

                  var  x = dojo.byId('hddiario_desdeescalafon').value;
            
                  if(x != '')
                  {
                    
                      Asistencias._V.cargar_desde_escalafon(x, 'detalle_dia');
                    
                  }

              }   
 

              if( dojo.byId('hddiario_time1') != null && dijit.byId('fec_hora1') != null )
              { 

                 var hora =  dojo.byId('hddiario_time1').value;
                 dijit.byId('fec_hora1').set('value', 'T'+hora);
 
              }
 

              if( dojo.byId('hddiario_time2') != null && dijit.byId('fec_hora2') != null )
              { 

                 var hora =  dojo.byId('hddiario_time2').value;
                 dijit.byId('fec_hora2').set('value', 'T'+hora);
              
              }

              if( dojo.byId('hddiario_time3') != null && dijit.byId('fec_hora3') != null )
              { 

                 var hora =  dojo.byId('hddiario_time3').value;
                 dijit.byId('fec_hora3').set('value', 'T'+hora);
              
              }

              if( dojo.byId('hddiario_time4') != null && dijit.byId('fec_hora4') != null )
              { 

                 var hora =  dojo.byId('hddiario_time4').value;
                 dijit.byId('fec_hora4').set('value', 'T'+hora);
              
              }
 
              
              if( dojo.byId('spverpermisosdia') != null )
              { 
                    dojo.connect( dojo.byId('spverpermisosdia') ,'onclick', function(){
                               
                          var data = dojo.formToObject('form_asis_registrodiario');
                          Permisos._V.detalle_del_dia.load(data);      

                    }); 
              }

        } 


    }),
 

     permisos : new Laugo.View.Window({
     
         connect :  'asistencias/permisos',

         style : {
                    width : '420px',
                    height : '300px',
                    'background-color' : '#FFFFFF'
          },

         title : ' Registrar permiso ',

         onLoad: function(){ 

         } 


     }),


    registro_permisos : new Laugo.View.Window({
    
        connect :  'asistencias/registrar_permiso',

        style : {
                   width : '380px',
                   height : '400px',
                   'background-color' : '#FFFFFF'
         },

        title : ' Registrar permiso ',

        onLoad: function(){

 
              if( dojo.byId('hddiariofechamin') != null)
              {

                    var strDate = dojo.byId('hddiariofechamin').value;
                    var dateParts = strDate.split("-");
                    var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                  
                    if(dijit.byId('cal_regdia_hasta') != null )
                    {
                         dijit.byId('cal_regdia_hasta').set('value', date);
                         dijit.byId('cal_regdia_hasta').constraints.min = date;
               
                         strDate = dojo.byId('hddiariofechamax').value;
                         dateParts = strDate.split("-");
                         date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                         dijit.byId('cal_regdia_hasta').constraints.max = date;
                     }
              }
              

              if( dojo.byId('hddiario_desdeescalafon') != null)
              { 

                  var  x = dojo.byId('hddiario_desdeescalafon').value;
            
                  if(x != '')
                  {
                    
                      Asistencias._V.cargar_desde_escalafon(x, 'detalle_dia');
                    
                  }

              }   
    

              if( dojo.byId('hddiario_time1') != null && dijit.byId('fec_hora1') != null )
              { 

                 var hora =  dojo.byId('hddiario_time1').value;
                 dijit.byId('fec_hora1').set('value', 'T'+hora);
    
              }
    

              if( dojo.byId('hddiario_time2') != null && dijit.byId('fec_hora2') != null )
              { 

                 var hora =  dojo.byId('hddiario_time2').value;
                 dijit.byId('fec_hora2').set('value', 'T'+hora);
              
              }

              if( dojo.byId('hddiario_time3') != null && dijit.byId('fec_hora3') != null )
              { 

                 var hora =  dojo.byId('hddiario_time3').value;
                 dijit.byId('fec_hora3').set('value', 'T'+hora);
              
              }

              if( dojo.byId('hddiario_time4') != null && dijit.byId('fec_hora4') != null )
              { 

                 var hora =  dojo.byId('hddiario_time4').value;
                 dijit.byId('fec_hora4').set('value', 'T'+hora);
              
              }
    
              
        } 


    }),


    dia_info : new Laugo.View.Window({

          connect :  'asistencias/visualizar_detalle_dia',

          style : {
                     width : '380px',
                     height : '400px',
                     'background-color' : '#FFFFFF'
           },

          title : ' Detalle del dia',

          onLoad: function(){

  
                if ( dojo.byId('hddiario_desdeescalafon') != null) { 

                    var  x = dojo.byId('hddiario_desdeescalafon').value;
                
                    if (x != '') {
                      
                        Asistencias._V.cargar_desde_escalafon(x, 'dia_info');
                      
                    }

                }   
 

                if ( dojo.byId('spverpermisosdia') != null ) { 

                      dojo.connect( dojo.byId('spverpermisosdia') ,'onclick', function(){
                                 
                            var data = dojo.formToObject('form_asis_registrodiario');
                            Permisos._V.detalle_del_dia.load(data);      

                      }); 
                } 



                if (dijit.byId('selregistrodiario_estado') != null) {  

                      
                        dojo.connect( dijit.byId('selregistrodiario_estado'), 'onChange', function(evt){
                                
                                var valorEstadoDiaActual = dijit.byId('selregistrodiario_estado').get('value');

                                var diaConMarcacionDeHoras = false; 

                                if (valorEstadoDiaActual === '1' ) {
                                    diaConMarcacionDeHoras = true;
                                } 
                                   
                                if (diaConMarcacionDeHoras) {
                                   
                                   dojo.setStyle(dojo.byId('trregistrodiario_htra'), 'display', 'table-row' ); 
                                   dojo.setStyle(dojo.byId('trregistrodiario_tar'), 'display', 'table-row' ); 
                                   dojo.setStyle(dojo.byId('trregistrodiario_horario1'), 'display', 'table-row' );
                                   
                                   if (dojo.byId('trregistrodiario_horario2') != null) {
                                     dojo.setStyle(dojo.byId('trregistrodiario_horario2'), 'display', 'table-row' );
                                   }

                                   if (dojo.byId('trregistrodiario_aplicarhasta') != null) {                                
                                     dojo.setStyle(dojo.byId('trregistrodiario_aplicarhasta'), 'display', 'none' );
                                   }
                  
                                } else {
 
                                   dojo.setStyle(dojo.byId('trregistrodiario_htra'), 'display', 'none' ); 
                                   dojo.setStyle(dojo.byId('trregistrodiario_tar'), 'display', 'none' ); 
                                   dojo.setStyle(dojo.byId('trregistrodiario_horario1'), 'display', 'none' );

                                   if(dojo.byId('trregistrodiario_horario2') != null)
                                   {

                                     dojo.setStyle(dojo.byId('trregistrodiario_horario2'), 'display', 'none' );
                                   }

                                   if(dojo.byId('trregistrodiario_aplicarhasta') != null)
                                   { 
                                      dojo.setStyle(dojo.byId('trregistrodiario_aplicarhasta'), 'display', 'table-row' );
                                   }
                                } 
                         });
                           
                       //    Planillas.Ui.Grids.planillas_preview.refresh();
                         
                  }



                  if( dojo.byId('hddiario_time1') != null && dijit.byId('fec_editardiario_hora1') != null )
                  { 

                     var hora =  dojo.byId('hddiario_time1').value;
                     dijit.byId('fec_editardiario_hora1').set('value', 'T'+hora);
                  
                  }
                  

                  if( dojo.byId('hddiario_time2') != null && dijit.byId('fec_editardiario_hora2') != null )
                  { 

                     var hora =  dojo.byId('hddiario_time2').value;
                     dijit.byId('fec_editardiario_hora2').set('value', 'T'+hora);
                  
                  }

                  if( dojo.byId('hddiario_time3') != null && dijit.byId('fec_hora3') != null )
                  { 

                     var hora =  dojo.byId('hddiario_time3').value;
                     dijit.byId('fec_hora3').set('value', 'T'+hora);
                  
                  }

                  if( dojo.byId('hddiario_time4') != null && dijit.byId('fec_hora4') != null )
                  { 

                     var hora =  dojo.byId('hddiario_time4').value;
                     dijit.byId('fec_hora4').set('value', 'T'+hora);
                  
                  }
                 
                 var vc = document.getElementById('hdregistrodiario_estado').value;

                 dijit.byId('selregistrodiario_estado').set('value',vc);

                 dijit.byId('selregistrodiario_estado').onChange();

          } // Fin metodo onload

    }), 


    importacion_trabajadores : new Laugo.View.Window({

          connect :  'asistencias/importacion_trabajadores',

          style : {
                     width : '850px',
                     height : '500px',
                     'background-color' : '#FFFFFF'
           },

          title : ' Importar relación de trabajadores ',

          onLoad: function(){

               
               require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination","dgrid/extensions/ColumnHider", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                  function(List, Grid, Selection, editor, Keyboard, Pagination, ColumnHider, declare, JsonRest, Observable, Cache, Memory){

                                  dijit.byId('calasis_addetini').set('value',new Date());


                                  var strDate = dojo.byId('hddetallefechamin').value;
                                  var dateParts = strDate.split("-");
                                  var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                                  
                                   dijit.byId('calasis_addetini').set('value', date);
                                   dijit.byId('calasis_addetini').constraints.min = date;
                                  
                                   strDate = dojo.byId('hddetallefechamax').value;
                                   dateParts = strDate.split("-");
                                   date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                                   dijit.byId('calasis_addetini').constraints.max = date;
                                   dijit.byId('calasis_addetfin').constraints.max = date;


                                   dijit.byId('calasis_addetfin').set('value', date);

                                   if(dojo.byId('dvasisregistroimportacion_table') != null ){ 

                                    
                                       if( window.grid_columnhider === null ||  window.grid_columnhider === undefined)  window.grid_columnhider = (declare([Grid, Selection, ColumnHider, Keyboard]));


                                                             var store = Observable(Cache(JsonRest({
                                                                      target:  app.getUrl() + "asistencias/provide", 
                                                                      idProperty: "id",
                                                                      sortParam: 'oby',
                                                                      query: function(query, options){

                                                                               
                                                                              var  data =  dojo.formToObject('form_registroasistencias');
               
                                                                              for(d in data){
                                                                                  query[d] = data[d];
                                                                              }
               

                                                                              return JsonRest.prototype.query.call(this, query, options);
                                                                      }
                                                              }), Memory()));

                                                              var colums = { // you can declare columns as an object hash (key translates to field)
                                                                     // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                      col1: {label: '#', sortable: true},
                                                                      col2: {label: 'Codigo', sortable: false},
                                                                      col7: {label: 'Desde', sortable: false},
                                                                      col8: {label: 'Hasta', sortable: false},
                                                                      col5: {label: 'Proyecto/Meta', sortable: false},
                                                                      col6: {label: 'Descripcion', sortable: false},
                                                                      col9: {label: 'C.Tra', sortable: false},
                                                                      col10: {label: 'Responsable', sortable: false} 

                                                              };
               
                                                              Asistencias.Ui.Grids.asistencias_registro_importacion  = new  window.grid_columnhider({
                                                                      loadingMessage : 'Cargando',
                                                                      store: store,
                                                                      getBeforePut: false,
                                                                      selectionMode: "single",
                                                                      columns: colums 


                                                              }, "dvasisregistroimportacion_table");
  
                                    }


                                    if(dojo.byId('dvasisimportacion_trabajadores') != null ){ 

                                     
                                        if( window.grid_columnhider === null ||  window.grid_columnhider === undefined)  window.grid_columnhider = (declare([Grid, Selection, ColumnHider, Keyboard]));




                                                              var store = Observable(Cache(JsonRest({
                                                                       target:  app.getUrl() + "asistencias/trabajadores_hoja", 
                                                                       idProperty: "id",
                                                                       sortParam: 'oby',
                                                                       query: function(query, options){
                                                                              

                                                                              dijit.byId('btn_importar_fromhoja').set('disabled', true);

                                                                               var  data =  dojo.formToObject('dvasisimportacion_trabajadores_data');
                                    
                                                                               for(d in data){
                                                                                   query[d] = data[d];
                                                                               } 
                                    

                                                                               return JsonRest.prototype.query.call(this, query, options);
                                                                       }
                                                               }), Memory()));

                                                               var colums = { 
                                                                       col1: {label: '#', sortable: true},
                                                                       col2: {label: 'Trabajador', sortable: false},
                                                                       col3: {label: 'DNI', sortable: false},
                                                                       col4: {label: 'Categoria.', sortable: false,

                                                                                               renderCell: function(object, value, node, options)
                                                                                                                   {
                                                                                                                          
                                                                                                                      dijit.byId('btn_importar_fromhoja').set('disabled', false);
                                                                                                                   } 
                                                                             }
                                                               };
                                    
                                                               Asistencias.Ui.Grids.asistencias_registro_importacion_trabajadores  = new  window.grid_columnhider({
                                                                       loadingMessage : 'Cargando',
                                                                       store: store,
                                                                       getBeforePut: false,
                                                                       columns: colums 


                                                               }, "dvasisimportacion_trabajadores");

                                                   if( Asistencias.Ui.Grids.asistencias_registro_importacion_trabajadores != null){
                                                  
                                                        Asistencias.Ui.Grids.asistencias_registro_importacion_trabajadores.refresh();
                                       

                                                   }          
                                     }


 
                      });   


          }

    }),

		/*
		  Hoja -> hoja de asistencia 
		*/ 
 		crear_nueva_hoja : new Laugo.View.Window({
             
              connect : 'asistencias/nueva_hoja',
              
              style : {
                   width : '800px',
                   height : '400px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Registrar nueva hoja de asistencia',
              
              onLoad : function(){

 
         
                      
                      dojo.connect( dijit.byId('sel_crasis_tipoplanilla'), 'onChange', function(evt){
                             
                            if( dijit.byId('sel_crasis_tipoplanilla').get('value') == app._consts.sitlab_construccion){
                               
                                dijit.byId('sel_crpla_seltarea').set('value', app._consts.enplanilla_especificar_tarea );
                            }
                            
                        //    Planillas.Ui.Grids.planillas_preview.refresh();
                          
                      });
                       
                       var calendars = ['cal_crasis_desde','cal_crasis_hasta'];
                       var fecha = $_currentDate();
                      dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});
                      
                      
                                           
                     // dijit.byId('sel_crpla_seltarea').onChange();
                     
                     
                     require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                        function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                         if(dojo.byId('table_asistencias_preview') != null ){ 

                                             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                    var store_asistencias_preview = Observable(Cache(JsonRest({
                                                                            target:  app.getUrl() + "asistencias/provide", 
                                                                            idProperty: "id",
                                                                            sortParam: 'oby',
                                                                            query: function(query, options){

                                                                                    
                                                                                    query.tipo = dijit.byId('sel_crasis_tipoplanilla').get('value');
                                                                                    //console.log('Tipo a buscar: '+ query.tipo);
                                                                                    return JsonRest.prototype.query.call(this, query, options);
                                                                            }
                                                                    }), Memory()));

                                                                    var colums = { // you can declare columns as an object hash (key translates to field)
                                                                           // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                            col1: {label:'#', sortable: false},
                                                                            col2: {label: 'Codigo', sortable: true},
                                                                            col3: {label: 'Estado', sortable: true},
                                                                            col4: {label: 'Tipo Trabajador', sortable: true},
                                                                            col5: {label: 'Descripcion', sortable: true},
                                                                            col6: {label: 'Desde', sortable: true},
                                                                            col7: {label: 'Hasta', sortable: true},
                                                                            col8: {label: 'C.Tra', sortable: true} 

                                                                    };
 
                                                                    Asistencias.Ui.Grids.asistencias_preview  = new  window.escalafon_grid({
                                                                            loadingMessage : 'Cargando',
                                                                            store: store_asistencias_preview,
                                                                            getBeforePut: false,
                                                                            columns: colums 


                                                                    }, "table_asistencias_preview");

                                                          if( Asistencias.Ui.Grids.asistencias_preview != null){
                                                   
                                                             Asistencias.Ui.Grids.asistencias_preview.refresh();
                                                              // Persona.Ui.Grids.trabajadores.store.query({});
                                                              // Persona.Ui.Grids.trabajadores.cleanup();

                                                        }          
                                          }



                            });   
  
                       
                  dijit.byId('cal_crasis_desde').set('value',new Date());      
              }
             
          }),


 	  registro_de_hojas : new Laugo.View.Window({
             
              connect : 'asistencias/registro_de_hojas',
              
              style : {
                  width :  '850px',
                   height:  '500px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Registro de hojas de asistencias',
              
              onLoad : function(){

  
                     
                     require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination","dgrid/extensions/ColumnHider", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                        function(List, Grid, Selection, editor, Keyboard, Pagination, ColumnHider, declare, JsonRest, Observable, Cache, Memory){


                                         if(dojo.byId('dvasisregistro_table') != null ){ 

                                          
                                             if( window.grid_columnhider === null ||  window.grid_columnhider === undefined)  window.grid_columnhider = (declare([Grid, Selection, ColumnHider, Keyboard]));


                                                                   var store = Observable(Cache(JsonRest({
                                                                            target:  app.getUrl() + "asistencias/provide", 
                                                                            idProperty: "id",
                                                                            sortParam: 'oby',
                                                                            query: function(query, options){

                                                                                     
                                                                                    var  data =  dojo.formToObject('form_registroasistencias');
  
                                                                                    for(d in data){
                                                                                        query[d] = data[d];
                                                                                    }
  

                                                                                    return JsonRest.prototype.query.call(this, query, options);
                                                                            }
                                                                    }), Memory()));

                                                                    var colums = { // you can declare columns as an object hash (key translates to field)
                                                                           // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                            col1: {label: '#', sortable: true},
                                                                            col2: {label: 'Codigo', sortable: false},
                                                                            col3: {label: 'Estado', sortable: false},
                                                                            col4: {label: 'Tipo Trabajador', sortable: false},
                                                                            col5: {label: 'Proyecto/Meta', sortable: false},
                                                                            col6: {label: 'Descripcion', sortable: false},
                                                                            col7: {label: 'Desde', sortable: false},
                                                                            col8: {label: 'Hasta', sortable: false},
                                                                            col9: {label: 'C.Tra', sortable: false},
                                                                            col10: {label: 'Responsable', sortable: false} 

                                                                    };
 
                                                                    Asistencias.Ui.Grids.asistencias_registro  = new  window.grid_columnhider({
                                                                            loadingMessage : 'Cargando',
                                                                            store: store,
                                                                            getBeforePut: false,
                                                                            columns: colums 


                                                                    }, "dvasisregistro_table");

                                                          if( Asistencias.Ui.Grids.asistencias_registro != null){
                                                        //Asistencias.Ui.Grids.comisiones.store.view_Asistencias('6');
                                                             Asistencias.Ui.Grids.asistencias_registro.refresh();
                                                              // Persona.Ui.Grids.trabajadores.store.query({});
                                                              // Persona.Ui.Grids.trabajadores.cleanup();

                                                        }          
                                          }



                            });   

                        
              }
             
          }),
    

     view_hoja : new Laugo.View.Window({
             
              connect : 'asistencias/hoja',
              
              style : {
                  width :  '900px',
                   height:  '500px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Hoja de Asistencia',
              
              onLoad : function(){

                  //console.log('CARGADO');
    
                   var fr = dojo.query('#table_asistencia_calendario tr')[0];
                   
                   var w_f = [40,300];
                   for(var i=2; i<= dojo.query('td',fr).length; i++  )
                   {
                       w_f[i] = '35';
                   }


                   if(dojo.byId('table_asistencia_calendario') != null )
                   {

 
                       var myST = new superTable("table_asistencia_calendario", {
                                      cssSkin : "sDefault",
                                      headerRows : 3,
                                      fixedCols : 2,
                                      colWidths : w_f,
                                      onStart : function () {
                                     
                                      },
                                      onFinish : function () {
                                        
                                      }
                                    });
                           

                        var rows =  dojo.query("#table_asistencia_calendario .tr_detalle"), 
                            nombres_trabajadores =  dojo.query('.td_nombre_trabajador'), 
                            r = ( nombres_trabajadores.length / 2);
     
                        dojo.forEach( rows, function(row,ind){
     
                             var tdsfecha = dojo.query('.td_fecha', row);

                             dojo.forEach( tdsfecha , function(tdfecha, k){
      

                                   dojo.connect(tdfecha,'onmouseenter', function(){
                                       
                                        try{  

                                            if((ind-r) > 0)
                                            {
                                              dojo.setStyle(nombres_trabajadores[(ind-r)],'backgroundColor','#FFFFCE');    
                                            }

                                            dijit.showTooltip(  dojo.query('.ttmensaje',tdfecha)[0].value ,tdfecha,['above']);
                                           
                                        }catch(e){
                                            console.log(e);
                                        }
                                    
                                    
                                   }); 
     

                                   dojo.connect(tdfecha,'onmouseout', function(){
                                        
                                         // COLUMNAS
                                         try{  


                                            if((ind-r) > 0)
                                            {
                                              dojo.setStyle(nombres_trabajadores[(ind-r)],'backgroundColor','');
                                            }
                                           
                                            dijit.hideTooltip(tdfecha); 
                                            
                                         }catch(e){
                                             console.log(e);
                                         }
                                       
      
                                   }); 



                             });

                        });


                  }
        
                    
                  if(dojo.byId('table_asistencia_calendario_resumen_c') != null )
                  {
 
                      (function(){

                          //console.log(dojo.query('#table_asistencia_calendario_resumen_c'));
                           var fr = dojo.query('#table_asistencia_calendario_resumen_c tr')[0];
                            
                           var w_f = [40,300];
                           //console.log('total: '+ dojo.query('td',fr).length );
                           for(var i=2; i< dojo.query('td',fr).length; i++  )
                           {
                               w_f[i] = '35';
                           }
                           
                           //console.log(w_f);
                           var myST_rs = new superTable("table_asistencia_calendario_resumen_c", {
                                          cssSkin : "sDefault",
                                          headerRows : 1,
                                          fixedCols : 2,
                                          colWidths : w_f,
                                          onStart : function () {
                                         
                                          },
                                          onFinish : function () {
                                            
                                          }
                                        });

                           //console.log(myST_rs);


                      })();
                }

              }
             
    }),


 		buscar_trabajador: new Laugo.View.Window({
              
              connect : 'asistencias/buscar_trabajador',
               
              style : {
                   width :  '940px',
                   height:  '500px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Agregar Trabajador a la hoja de asistencia ',
              
              onLoad: function(){
 

                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                    function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                     if(dojo.byId('dv_asistencia_adddetalle_table') != null ){ 

                                         if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                var store_trabajadores = JsonRest({
                                                                        target:app.getUrl() + "escalafon/get_trabajadores", 
                                                                        idProperty: "id",
                                                                        sortParam: 'oby',
                                                                        query: function(query, options){
 
                                                                                    
                                                                                var data = dojo.formToObject('form_agregardetalle_busqueda');

                                                                                for(d in data){
                                                                                    query[d] = data[d];
                                                                                }
                                                                                return JsonRest.prototype.query.call(this, query, options);
                                                                        }
                                                                });

                                                                var colums = { // you can declare columns as an object hash (key translates to field)
                                                                           // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox), 
                                                                           // col0: Selector({disabled: function(object){ return object.col1 == "note"; }}),
                                                                            col0: Selector({}),
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
 
                                                                Asistencias.Ui.Grids.buscar_trabajador  = new  (declare([Grid, Selection,Keyboard]))({

                                                                        store: store_trabajadores,
                                                                        loadingMessage : 'Cargando',
                                                                        allowSelectAll: true,
                                                                        getBeforePut: true,
                                                                        columns: colums,
                                                                        pagingLinks: false,
                                                                        pagingTextBox: true,
                                                                        firstLastArrows: true,
                                                                        rowsPerPage : 25


                                                                }, "dv_asistencia_adddetalle_table");

                                                      if( Asistencias.Ui.Grids.buscar_trabajador != null){
                                                  
                                                    }          
                                      }
 
                        });
                  
    
                  //dijit.byId('calasis_addetini').set('value',new Date());


                  var strDate = dojo.byId('hddetallefechamin').value;
                  var dateParts = strDate.split("-");
                  var date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
         
                   dijit.byId('calasis_addetini').set('value', date);
                   dijit.byId('calasis_addetini').constraints.min = date;
         
                   strDate = dojo.byId('hddetallefechamax').value;
                   dateParts = strDate.split("-");
                   date = new Date(dateParts[0], (dateParts[1] -1 ) , dateParts[2]);
                   dijit.byId('calasis_addetini').constraints.max = date;
                   dijit.byId('calasis_addetfin').constraints.max = date;


                   dijit.byId('calasis_addetfin').set('value', date);

              }
                
              
    }),


    cambiar_categoria_detalle: new Laugo.View.Window({
                 
         connect : 'asistencias/cambiar_categoria_detalle',
          
         style : {
              width :  '400px',
              height:  '250px',
              'background-color'  : '#FFFFFF'
         },
         
         title: ' Cambiar la categoria del trabajador',
         
         onLoad: function(){

         }
                 
     }),


    horario_trabajador : new Laugo.View.Window({
                 
         connect : 'asistencias/horario_trabajador',
          
         style : {
              width :  '800px',
              height:  '420px',
              'background-color'  : '#FFFFFF'
         },
         
         title: ' Actualizar horario de trabajo ',
         
         onLoad: function(){

         }
                 
     }),


    horario_nuevo : new Laugo.View.Window({
                 
         connect : 'asistencias/horario_nuevo',
          
         style : {
              width :  '800px',
              height:  '420px',
              'background-color'  : '#FFFFFF'
         },
         
         title: ' Registrar un nuevo horario ',
         
         onLoad: function(){

         } 
                 
     }),

     visualizar_hoja : new Laugo.View.Window({
                  
          connect : 'asistencias/ui_view',
           
          style : {
               width :  '1100px',
               height:  '600px',
               'background-color'  : '#FFFFFF'
          },
          
          title: ' Hoja de asistencia ',
          
          onLoad: function(){

                Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value );
          }
                  
      }),

     cargar_desde_escalafon : function(x, ventana){

          var rs =  x.split("|");
          var tipo = rs[1];
          var codigo = rs[0];

          if(tipo == 3  )
          {
             view = 'view_descanso';
          }
          else if(tipo == 4 )
          {
             view = 'view_comision';

          }
          else if(tipo == 5 )
          {
             view = 'view_licencia';

          }
          else if(tipo == 6)
          {
            view = 'view_vacaciones';
          } 

          Asistencias._V[ventana].close();

          Persona._V[view].load({'codigo' : codigo, 'oid' : '1'});
 

     },


      view_estado_dia : new Request({
               
           type :  'text',
           
           method: 'post',
           
           url : 'asistencias/estado_dia_view',
           
           onRequest : function(){
 
               dijit.byId('dvestadodia_view').set('content', '<div class="dv_cargando">Cargando..</div>' );
           },
           
           onSuccess  : function(responseText){
  
               dijit.byId('dvestadodia_view').set('content', responseText);
            
           },
           
           onFailure : function(){
               
           } 
           
       }),



      nuevo_estadodia : new Laugo.View.Window({
                   
           connect : 'asistencias/nuevo_estadodia',
            
           style : {
                width :  '400px',
                height:  '320px',
                'background-color'  : '#FFFFFF'
           },
           
           title: ' Registrar un nuevo estado del día ',
           
           onLoad: function(){

           } 
                   
       }), 
 

      nuevo_horario : new Laugo.View.Window({
                   
           connect : 'asistencias/nuevo_horario',
            
           style : {
                width :  '470px',
                height:  '390px',
                'background-color'  : '#FFFFFF'
           },
           
           title: ' Registrar un nuevo horario',
           
           onLoad: function(){




                  dojo.connect( dijit.byId('sel_tipohorario'), 'onChange', function(evt){
                          

                              var v = dijit.byId('sel_tipohorario').get('value');

                              if(v == '1')
                              { 
                                   dojo.setStyle(dojo.byId('tr_segundohorario'), 'display', 'none' ); 
                                   dojo.setStyle(dojo.byId('tr_refrigerio'), 'display', 'table-row' ); 
                              }
                              else
                              {
                                  dojo.setStyle(dojo.byId('tr_segundohorario'), 'display', 'table-row' ); 
                                  dojo.setStyle(dojo.byId('tr_refrigerio'), 'display', 'none' ); 
                              }

                           });

                 dijit.byId('sel_tipohorario').onChange();
 

                 dojo.connect( dijit.byId('sel_horariodosdias'), 'onChange', function(evt){
                          
                             var v = dijit.byId('sel_horariodosdias').get('value');

                             if(v == '1')
                             { 
                                  dojo.setStyle(dojo.byId('spanDiaAnterior'), 'display', 'inline' ); 
                              
                             }
                             else
                             {
                                 dojo.setStyle(dojo.byId('spanDiaAnterior'), 'display', 'none' );  
                             }

                             dijit.byId('fec_hora1').onChange(); 
                        //     dijit.byId('fec_hora2').onChange();

                 });

                 dijit.byId('sel_horariodosdias').onChange();



                 dojo.connect( dijit.byId('selhor_tardanza'), 'onChange', function(evt){
                          
                             var v = dijit.byId('selhor_tardanza').get('value');

                             if(v == '1')
                             { 
                                  dojo.setStyle(dojo.byId('dvhor_tardanza'), 'display', 'inline' ); 
                     
                             }
                             else
                             {
                                 dojo.setStyle(dojo.byId('dvhor_tardanza'), 'display', 'none' );  
                             }

                 });

                 dijit.byId('selhor_tardanza').onChange();


                 dojo.connect( dijit.byId('selhor_faltaxtar'), 'onChange', function(evt){
                          
                             var v = dijit.byId('selhor_faltaxtar').get('value');

                             if(v == '1')
                             { 
                                  dojo.setStyle(dojo.byId('dvhor_faltaxtar'), 'display', 'inline' ); 
                     
                             }
                             else
                             {
                                 dojo.setStyle(dojo.byId('dvhor_faltaxtar'), 'display', 'none' );  
                             }

                 });

                 dijit.byId('selhor_faltaxtar').onChange();



                 dojo.connect( dijit.byId('fec_hora1'), 'onChange', function(evt){
                            

                             var v = dijit.byId('sel_horariodosdias').get('value');

                             dijit.byId('fec_hora_ft').constraints.min = this.get('value');
                             dijit.byId('fec_hora_tar').constraints.min = this.get('value');
                             dijit.byId('fec_hora_ft').set('value', this.get('value'));
                             dijit.byId('fec_hora_tar').set('value', this.get('value'));    
 
                             if(v == '0')
                             { 
                                 dijit.byId('fec_hora2').constraints.min = this.get('value'); 
                                 dijit.byId('fec_hora2').set('value', this.get('value')); 
                                 dijit.byId('fec_refri').constraints.min = this.get('value');
                                 dijit.byId('fec_refri').set('value', this.get('value'));
                             }
                             else
                             {
                                 dijit.byId('fec_hora2').constraints.min = null;
                                 dijit.byId('fec_hora2').set('value', 'T00:00'); 
                             
                                 dijit.byId('fec_refri').constraints.min = null;
                                 dijit.byId('fec_refri').set('value', 'T00:00'); 
                             }

                 });
                
                 dijit.byId('fec_hora1').onChange();

/*
 
                 dojo.connect( dijit.byId('fec_hora2'), 'onChange', function(evt){
                            

                             var v = dijit.byId('sel_horariodosdias').get('value');

                             if(v == '0')
                             { 
                                
                                dijit.byId('fec_hora3').constraints.min = this.get('value'); 
                                dijit.byId('fec_hora3').set('value', this.get('value'));   
                                dijit.byId('fec_hora_ft').constraints.max = this.get('value');
                                dijit.byId('fec_refri').constraints.max = this.get('value');
                                dijit.byId('fec_hora_tar').constraints.max = this.get('value');
                                
                             }
                             else
                             {
                                dijit.byId('fec_hora3').constraints.min = this.get('value'); 
                                dijit.byId('fec_hora3').set('value', this.get('value'));   

                                console.log('Deberia estar aqui.. ');
                                dijit.byId('fec_hora_tar').constraints.max =  null;
                                dijit.byId('fec_hora_ft').constraints.max =null;
                                dijit.byId('fec_refri').constraints.max = this.get('value');
                             }

                 });
                 
                 dijit.byId('fec_hora2').onChange(); */

           } 
                   
       }), 


      view_horario : new Request({
               
           type :  'text',
           
           method: 'post',
           
           url : 'asistencias/horario_view',
           
           onRequest : function(){
 
               dijit.byId('dvhorario_view').set('content', '<div class="dv_cargando">Cargando..</div>' );
           },
           
           onSuccess  : function(responseText){
  
               dijit.byId('dvhorario_view').set('content', responseText);
            
           },
           
           onFailure : function(){
               
           } 
           
       }),


      view_planillatipo_config : new Request({
               
           type :  'text',
           
           method: 'post',
           
           url : 'asistencias/planilla_tipo_configurar',
           
           onRequest : function(){
      
               dijit.byId('dvplanillatipo_config_panel').set('content', '<div class="dv_cargando">Cargando..</div>' );
           },
           
           onSuccess  : function(responseText){
      
               dijit.byId('dvplanillatipo_config_panel').set('content', responseText);


 
               require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                       function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){



                     if(dojo.byId('dvtable_estadodia_plati') != null )
                     { 

                         if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                var store = Observable(Cache(JsonRest({
                                        target: app.getUrl() +"asistencias/get_estados_del_dia", 
                                        idProperty: "id",
                                        sortParam: 'oby',
                                        query: function(query, options)
                                        {
                                            var data = dojo.formToObject('form_estadodia_plati');  
                                            for(x in data)
                                            {
                                              query[x] = data[x];
                                            } 

                                            return JsonRest.prototype.query.call(this, query, options);
                                        }

                                }), Memory()));


                                var columnas = {  
                                         
                                        col1: {label: '#', sortable: true},
                                        col2: {label: 'Nombre ', sortable: false}, 
                                        col3: {label: 'Alias', sortable: false},
                                        plati_activado : {label: 'Activo', sortable: false}
                                   
                                };
                     

                                Asistencias.Ui.Grids.estados_del_dia_plati = new  window.escalafon_grid({
                                   
                                            store: store,  
                                            loadingMessage : 'Cargando',
                                            getBeforePut: false, 
                                            columns: columnas,
                                            pagingLinks: false,
                                            pagingTextBox: true,
                                            firstLastArrows: true 

                                }, "dvtable_estadodia_plati");


                                if( Asistencias.Ui.Grids.estados_del_dia_plati != null)
                                {
                                    Asistencias.Ui.Grids.estados_del_dia_plati.refresh();
                                }          
                           

/*
                               var memoryStore = new Memory({});
                               var  restStore = new JsonRest({

                                        target:"asistencias/data/diaestados/", 
                                        idProperty: "value",
                                        sortParam: 'oby',
                                        query: function(query, options){
                                            

                                            var data = dojo.formToObject('form_estadodia_plati');

                                            for(x in data)
                                            {
                                              query[x] = data[x];
                                            } 

                                            
                                            return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                        }

                                  }); 
                                Asistencias._M.dia_estados_cache =  new  Cache(restStore, memoryStore);
                                Asistencias._M.dia_estados_cache.query({fortipo : '71c30216a4d1a1d0c23b7158a709bc23'});
                                       

                                var dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves','Viernes', 'Sabado', 'Domingo']; 

                                for(dia in dias)
                                {
                                   dijit.byId('sel_diaestado_dia'+dias[dia]).set('store', Asistencias._M.dia_estados_cache );
                                  
                                }



                                var memoryStore = new Memory({});
                                var  restStore = new JsonRest({

                                         target:"asistencias/data/horarios/", 
                                         idProperty: "value",
                                         sortParam: 'oby',
                                         query: function(query, options){
                                             

                                             var data = dojo.formToObject('form_estadodia_plati');

                                             for(x in data)
                                             {
                                               query[x] = data[x];
                                             } 

                                             
                                             return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                         }

                                   }); 
                                 Asistencias._M.dia_horarios_cache =  new  Cache(restStore, memoryStore);
                                 Asistencias._M.dia_horarios_cache.query({fortipo : '71c30216a4d1a1d0c23b7158a709bc23'});
                                        

                                 var dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves','Viernes', 'Sabado', 'Domingo']; 

                                 for(dia in dias)
                                 {
                                    dijit.byId('sel_horario_dia'+dias[dia]).set('store', Asistencias._M.dia_horarios_cache );
                                   
                                 } 
                                 dijit.byId('sel_horario_diaLunes').set('value', '21312');*/
                   }
 

                });

  
                if( dijit.byId('sel_estadodiaplati_visualizar') != null)
                {

                    dojo.connect( dijit.byId('sel_estadodiaplati_visualizar'), 'onChange', function(evt){
                         
                          if( Asistencias.Ui.Grids.estados_del_dia_plati != null)
                          {
                              Asistencias.Ui.Grids.estados_del_dia_plati.refresh();
                          }     
                            
                    });

                    dijit.byId('sel_estadodiaplati_visualizar').onChange(); 
                }

            
           },
           
           onFailure : function(){
               
           } 
           
       }),


       view_planillatipo_config_estado : new Request({
                
            type :  'text',
            
            method: 'post',
            
            url : 'asistencias/planilla_tipo_configurar_estadodia',
            
            onRequest : function(){
       
                dijit.byId('dvplanillatipo_diaestado_config').set('content', '<div class="dv_cargando">Cargando..</div>' );
            },
            
            onSuccess  : function(responseText){
       
                dijit.byId('dvplanillatipo_diaestado_config').set('content', responseText);


                dojo.connect( dijit.byId('sel_estadoconfig_importar'), 'onChange', function(evt){
                     
                      var v = dijit.byId('sel_estadoconfig_importar').get('value'); 

                      if(v == '1')
                      {
                          dojo.setStyle(  dojo.byId('tr_estadoconfig_importar1'), 'display', 'table-row');
                          dojo.setStyle(  dojo.byId('tr_estadoconfig_importar2'), 'display', 'table-row');
                          dojo.setStyle(  dojo.byId('tr_estadoconfig_importar3'), 'display', 'table-row'); 
                      }
                      else
                      {
                          dojo.setStyle(  dojo.byId('tr_estadoconfig_importar1'), 'display', 'none');
                          dojo.setStyle(  dojo.byId('tr_estadoconfig_importar2'), 'display', 'none');
                          dojo.setStyle(  dojo.byId('tr_estadoconfig_importar3'), 'display', 'none'); 
                      }
                       
                    
                });

                dijit.byId('sel_estadoconfig_importar').onChange(); 

               
             
            },
            
            onFailure : function(){
                
            } 
            
        }),
 


       planillatipo_horario_modificar : new Laugo.View.Window({
       
           connect :  'asistencias/planillatipo_horario_modificar',

           style : {
                      width : '700px',
                      height : '380px',
                      'background-color' : '#FFFFFF'
            },

           title : ' Modificar el horario por defecto ',

           onLoad: function(){ 

           } 


       }),


       configurar_reporte_asistencia : new Laugo.View.Window({
       
           connect :  'asistencias/configurar_reporte_asistencia',

           style : {
                      width : '420px',
                      height : '300px',
                      'background-color' : '#FFFFFF'
            },

           title : ' Registrar permiso ',

           onLoad: function(){ 

           } 


       }),
 

       actualizacion_de_fechas_sincierre : new Laugo.View.Window({
       
           connect :  'asistencias/actualizacion_de_fechas_sincierre',

           style : {
                      width : '1000px',
                      height : '550px',
                      'background-color' : '#FFFFFF'
            },

           title : ' Actualización de fechas ',

           onLoad: function(){ 

           } 


       }) 


	},

	Ui : {

		 Grids : {


        asistencias_preview : null,
        asistencias_registro : null,
        buscar_trabajador : null,
        tipoplanilla_config : null,
        prewview_hojas_importacion : null,

		 },

     btn_registrarhoja_click : function(btn,evt){


          if(confirm('Realmente desea registrar esta hoja de asistencia?')){

                var data = dojo.formToObject('formnuevahoja');

                if(Asistencias._M.registrar_hoja.process(data)){


                    Asistencias.fn_load_hojaasistencia({'view' : Asistencias._M.registrar_hoja.data.key });

                    Asistencias._V.crear_nueva_hoja.close();
                }

          }
             

     },


     btn_visualizarasistencia_click : function(btn,evt){

          var data = {} 
                
            data.view = '';
                
                var grid = Asistencias.Ui.Grids.asistencias_registro;
                
                if(grid != null ){  
                     data.view = '';
                     for(var i in grid.selection){data.view = i;}
 
                }else{

                     Console.log('No existe el objeto GRID');
                }

                if(data.view != '')      
                { 
                          
                       Asistencias.fn_load_hojaasistencia(data);    
                 
                }
                else{
                        alert('Debe seleccionar un registro');
                }

     },

      btn_buscartrabajador_click : function(){
              
                
                var id = dojo.byId('hdviewasistencia_id').value;
                
                Asistencias._V.buscar_trabajador.load({'view' : id});
            
     
     },


     btn_addempleado_hoja_click : function(){


             var asistencia_key = dojo.byId('hdad_asistencia_key').value,
                 fecha_inicio =  dojo.date.locale.format(dijit.byId('calasis_addetini').get('value'), {datePattern: "yyyy-MM-dd",  selector: "date",
      formatLength: "short"}),
                 fecha_fin =  dojo.date.locale.format(dijit.byId('calasis_addetfin').get('value'), {datePattern: "yyyy-MM-dd",  selector: "date",
      formatLength: "short"}),
                 emp_grupo = '', data = {};
      
  
           
               data  = {
                        'p_c' : asistencia_key, 
                        'fechainicio' : fecha_inicio,
                        'fechafin' : fecha_fin}

             
             if(dijit.byId('calasis_categoria') != null)
             {

                 data.categoria =  dijit.byId('calasis_categoria').get('value');

             }


             if( dijit.byId('selasis_addet_grupo') != null )
             {

                if(dijit.byId('selasis_addet_grupo').get('value') == '' && dijit.byId('selasis_addet_grupo').get('value') != '0' ){
   
                      data.nuevo_grupo = '1';
                      data.grupo = dijit.byId('selasis_addet_grupo').get('value') ;
                      data.grupo_label = dijit.byId('selasis_addet_grupo').get('displayedValue') ;


                 }
                 else{
                      data.grupo = dijit.byId('selasis_addet_grupo').get('value');
                      data.grupo_label = '';
                 }  
              
             }



                if(asistencia_key != '')
                { 
                    var codigo_e = '';      
                   
                    var selection =  Asistencias.Ui.Grids.buscar_trabajador.selection;   
                    
                    for(var i in selection)
                    { 

                        if(selection[i] === true)
                        {
                          codigo_e +='_'+ i;
                        }
                          
                    }


                    if(codigo_e != '')      
                    {

                        data.e_c = codigo_e;

                        if(Asistencias._M.agregar_empleado.process(data))
                        {
                            // Actualizar calendario
                            console.log('Actualizar calendario');

                            Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value);         
                        }
                    }
                    else{ 
                        alert('Debe seleccionar un registro');
                    }
                }
                else{
                    console.log('Falta especificar el key de la planilla');
                }

     },

     btn_registrar_detalle_dia : function(btn,evt){

         var data = dojo.formToObject('form_asis_registrodiario');

         var ok = true; 

         if( data.estado ==  app._consts.dia_asistencia || data.estado == app._consts.falta_tardanza ) 
         {

           //  ok = dijit.byId('form_asis_registrodiario').validate();
          
         }   

          
         if(ok)
         {
           
           if(Asistencias._M.registrar_detalle_dia.process(data)  )
           {
              
                if(dojo.byId('hdviewasistencia_id') != null)
                {
                     Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value); 
                     Asistencias._V.detalle_dia.close();
                    
                     Asistencias.Cache.ultima_celda_actualizada =  Asistencias.Cache.ultima_celda_seleccionada; 
                }
                else{

                   Asistencias._M.get_registro_asistencia.reload();
                }                
                                             
           }
         
         }



     },


     btn_registrar_detalle_dia_modulo_asistencia : function(btn,evt){

         var data = dojo.formToObject('form_asis_registrodiario');

         var ok = true; 

         if( data.estado ==  app._consts.dia_asistencia || data.estado == app._consts.falta_tardanza ) 
         {

           //  ok = dijit.byId('form_asis_registrodiario').validate();
          
         }   

          
         if(ok)
         {
           
           if(Asistencias._M.actualizar_detalle_dia_modulo_asistencia.process(data)  )
           {
              
                if(dojo.byId('hdviewasistencia_id') != null)
                {
                     Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value); 
                     Asistencias._V.detalle_dia.close();
                    
                     Asistencias.Cache.ultima_celda_actualizada =  Asistencias.Cache.ultima_celda_seleccionada; 
                }
                else{

                   Asistencias._M.get_registro_asistencia.reload();
                }                
                                             
           }
         
         }



     },
     


     btn_finalizarregistro_hoja : function(btn,evt){
         
          var data = {}
              data.hoja_key = dojo.byId('hdviewasistencia_id').value;

          if(confirm('Realmente desea finalizar el registro de la hoja de asistencia? ')){ 
             
              if(Asistencias._M.finalizar_registro.process(data)){
 
                  Asistencias.fn_load_hojaasistencia({'view' :  Asistencias._M.finalizar_registro.data.hoja_key  });
                
              }

          } 

     },

     btn_eliminar_hoja : function(btn,evt){

           var data = {}
              data.hoja_key = dojo.byId('hdviewasistencia_id').value;

          if(confirm('Realmente desea eliminar esta hoja de asistencia? ')){ 
             
              if(Asistencias._M.eliminar_hoja.process(data)){
  
                    app.view_load('',{'view' : app.getUrl() + 'planillas/white_view' ,
                                                      'data' : {},
                                                      'fn' : function(){ }} );

                    Asistencias._V.registro_de_hojas.load({});
                
              }

          }       
     },


     btn_filtrardetalle_click : function(btn,evt){
              
                Asistencias.Ui.Grids.buscar_trabajador.refresh();
   
     },
  

     btn_filtrar_registro   : function(btn,evt){


           Asistencias.Ui.Grids.asistencias_registro.refresh();


     },

     btn_cambiarCategoria_click : function(){
              
          var data = dojo.formToObject('form_asistencias_cambiarcategoria');

          if(Asistencias._M.actualizar_categoria.process(data))
          {

             Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value);

             Asistencias._V.cambiar_categoria_detalle.close();
          }
                
     },


     mnuregistro_asistencia_click : function(btn,evt){

          Asistencias._V.registro_de_hojas.load({});
     }

	},

   fn_load_hojaasistencia : function(data){
          
               app.view_load('',{'view' : app.getUrl() + 'asistencias/ui_view',
                                          'data' : data,
                                          'fn' : function(){ 


                                                var dims = app.get_dims_body_app();   
                                                dijit.byId('viewasistencia_container').resize({w: dims.w ,h: (dims.h-45), l: 0, t:0});


                                                Asistencias.get_calendario(dojo.byId('hdviewasistencia_id').value);

                                           }  

                            });

    },
 

    get_calendario : function(hoja_id, data){


      if(dojo.byId('form_asistencia_calendario_config') != null )
      {
          var data = dojo.formToObject('form_asistencia_calendario_config');
       
      }
                                    
      if(data == null || data == undefined)
      {
          var data = {

                'hoja' : hoja_id
          }
      }
      else
      {
          data.hoja = hoja_id;
      }

       Asistencias._M.get_calendario.send(data);

    },




    get_registro_asistencia : function(data)
    {
  
       Asistencias._M.get_registro_asistencia.send(data);

    },

    get_importacion_config : function(hoja_id, planilla_k){

        Asistencias._M.get_importacion_config.send({hoja : hoja_id, planilla : planilla_k});

    },


    view_hoja : function( hoja_id, pla_id, modo){ 

         pla_id = (pla_id === undefined || pla_id === null || pla_id === false) ? 0 : pla_id;
         modo   = (modo === undefined || modo === null || modo === false) ? 0 : modo;  

        Asistencias._V.view_hoja.load({hoja : hoja_id, planilla : pla_id, view : modo});

    },
  

    view_impresion : function(hoja){

        window.open('impresiones/hoja_asistencia/'+hoja,'_blank');
    }




}

  