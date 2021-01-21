

var Planillas = {
        
      Cache: {
             
             view_afectacion            : null,
             view_resumen_conc_pla      :  0,
             view_resumen_conc_concepto : 0,
             view_importacion_planilla_select : 0,
             detalleplanilla_detalle_id : null,
             detalleplanilla_plakey     : null,
             planilla_cargada : null,
             tareo_seleccionado : null,

             ver_planilla_desde_boleta : null
      }, 
        
      _M: {


          registrar: new Laugo.Model({ connect: 'planillas/registrar'}),
           
          agregar_empleado : new Laugo.Model({
             connect  : 'planillas/agregar_empleado'
          }),
          

          quitar_empleado : new Laugo.Model({
             connect  : 'planillas/quitar_empleado/especifico'
          }),

          quitar_todos_empleado : new Laugo.Model({
             connect : 'planillas/quitar_empleado/todos'
          }),

          quitar_detalle_conceptos : new Laugo.Model({

              connect : 'planillas/quitar_detalle_conceptos'
          }),


          actualizar_afectacion_detalle: new Laugo.Model({

              connect : 'planillas/actualizar_afectacion_detalle'
          }),


          set_categoria_trabajador : new Laugo.Model({
             connect  : 'planillas/set_categoria_trabajador',

             message_function : function(){

             }
          }),

          afectacion_presupuestal : new Laugo.Model({

              connect : 'planillas/afectacion_presupuestal'

          }),


          actualizar_afectacionplanilla : new Laugo.Model({

              connect : 'planillas/actualizar_afectacionplanilla'

          }),


          actualizar_descripcionplanilla : new Laugo.Model({

              connect : 'planillas/actualizar_descripcionplanilla'

          }),

          actualizar_ocupacion_boleta : new Laugo.Model({

              connect : 'planillas/actualizar_ocupacion_boleta'

          }),


          get_detalleplanilla_variables : new Request({
              
              type :  'text',
              
              method: 'post',
              
              url : 'planillas/get_planillaempleado_detalle',
              
              onRequest : function(){
                    dijit.byId('dv_vipla_detalle').set('content','<div class="dv_cargando">Cargando..</div>');
              },
              
              onSuccess  : function(responseText)
              {
              
                  dijit.byId('dv_vipla_detalle').set('content',responseText);

                  var node_quitar =  dojo.byId('spdet_quitartipocs');
 
 
                  if(node_quitar!= null){
                  
                       dojo.connect( node_quitar, 'onclick', function(e){


                            if(confirm('Realmente desea eliminar el detalle del trabajador')){ 

                                 var codigo_e = dojo.query('.hdpladet_empkey',node_quitar.parentNode)[0].value;
                                 var indiv_k = dojo.query('.hdpladet_indkey',node_quitar.parentNode)[0].value;

                                 if(Planillas._M.quitar_empleado.process({'detalle' : codigo_e}))
                                 {

                                     Planillas.Ui.Grids.planillas_detalle.refresh();
                                     Planillas._M.get_detalleplanilla_variables.send({'codigo' : indiv_k,'pla_key' : dojo.byId('hdviewplanilla_id').value});   
   
                                 }

                            }

                       });

                   }

                   dojo.forEach(dojo.query('.ul_csdet li'), function(li, ind)
                   {

                        dojo.connect(li, 'onclick', function(evt){

                               
                               Planillas._M.get_detalleplanilla_variables.send({'codigo' : dojo.query('.hddetkey',li)[0].value, 'pla_key' : dojo.byId('hdviewplanilla_id').value});   

                        });

                   });

                 
                   if( dojo.byId('hddetalleplanillatipo') != null && dojo.byId('hddetalleplanillatipo').value == '1')
                   {
                       
                       dojo.forEach(dojo.query('.txtpladet_vari'), function(box,ind){
                             
                             var btn_save = dojo.query('.btnpladet_savevar', box.parentNode.parentNode)[0];
        
                             dojo.connect(box, 'onkeydown', function(evt)
                             {
                                 
                                  // console.log(evt.keyCode);
                                   if( (evt.keyCode >= 37 && evt.keyCode <= 40) || evt.keyCode  == 46 || evt.keyCode == 190 || evt.keyCode == 110 || (evt.keyCode>=48 && evt.keyCode <= 57) || (evt.keyCode>=96 && evt.keyCode <= 105) || evt.keyCode == 8 || evt.keyCode == 9){

                                   }
                                    else{
                                        evt.preventDefault();
                                    }
                              
                             });


                             dojo.connect(box, 'onkeyup', function(evt)
                             {
                                 
                                  var hd_valor_ini =   dojo.query('.hdpladet_varvd', box.parentNode)[0].value;
                                 
                                  if(parseFloat(dijit.byNode(box).get('value'))  != parseFloat(hd_valor_ini))
                                  {
                                       dijit.byNode(btn_save).set('disabled', false);
                                  }
                                  else
                                  {
                                        dijit.byNode(btn_save).set('disabled', true);
                                  }
                             
                             });


                             dojo.connect(box, 'onkeypress', function(evt)
                             {
                                 
                                    
                                    if(evt.charOrCode == dojo.keys.ENTER)
                                    {
                                      
                                       dijit.byNode(btn_save).onClick();
                                    }
                                    else if(evt.charOrCode == dojo.keys.ESCAPE)
                                    {
 
                                         var hd_valor_ini =   dojo.query('.hdpladet_varvd', box.parentNode)[0].value;
                                         this.value = hd_valor_ini;
                                         dijit.byNode(this).set('value',hd_valor_ini);
                                    }
                             
                             });



                           
                       });
                       
                        
                       dojo.forEach(dojo.query('.dijit_checkbox_id'), function(hd_id,ind){
                             
                             var node_checkbox = dijit.byId(hd_id.value);
                            
                             dojo.connect(node_checkbox, 'onChange', function(evt)
                             {
                                  
                                   var row = node_checkbox.domNode.parentNode.parentNode,
                                       est =  node_checkbox.get('value'),
                                       spconcepto = dojo.query('.sppladet_nombreconc',row)[0],
                                       conc_k = dojo.query('.hdpladet_conck',row)[0].value,
                                       ns = (est) ?  '1' : '0';
                                    
                              
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
                                  
                                  node_checkbox.set('disabled', false);
                                  
                                  
                             });  
                             
         
                       });
                       

                   }


                        dojo.forEach( dojo.query('.td_placonc_vercalculo'), function(cell, ind){
                             
                             dojo.connect(cell , 'onclick', function(evt){
                                   
                                   var key = dojo.query('.hd_placonc_data', cell)[0].value;
                                   
                                   var quinta_categoria = '0'; 
                                   
                                   if(dojo.query('.hd_placonc_quinta', cell)[0] != null && dojo.query('.hd_placonc_quinta', cell)[0] != undefined){
 

                                      if (dojo.query('.hd_placonc_quinta', cell)[0] != null) {

                                          quinta_categoria = dojo.query('.hd_placonc_quinta', cell)[0].value;
                                      
                                      }

                                      var detalle_id = '0';

                                      if(dojo.query('.hd_placonc_detalle_id', cell) != null){

                                         detalle_id = dojo.query('.hd_placonc_detalle_id', cell)[0].value;
                                       
                                      }

                                   }
 
                                   var planilla = dojo.byId('hdviewplanilla_id').value;

                                   if (quinta_categoria == '1') {

                                      QuintaCategoria._V.detalle_retencion_desdeplanilla.load({'planilla' : planilla, 'detalle' : detalle_id});

                                   } else {
                                       Conceptos._V.view_calculo.load({'key' : key});
                                   }


                             });

                        });

                    if(dojo.byId('hdplanilladetalle_ocupacion_id') != null)
                    {
                         var ocu_id =  dojo.byId('hdplanilladetalle_ocupacion_id').value;
                         dijit.byId('selplanilladetalle_ocupacion').set('value', ocu_id );
                    }
                   
              },
              
              onFailure : function(){
                  
              } 
              
          }),


          get_resumen_planilla : new Request({
              
              type :  'json',
              
              method: 'post',
              
              url : 'planillas/get_resumen_procesada',
              
              onRequest : function(){
                   app.loader_show(); 
              },
              
              onSuccess  : function(responseJSON){
                  
                   app.loader_hide(); 
                     

                    
              },
              
              onFailure : function(){
                  
              } 
              
          }),

 
          
          get_ff_tarea : new Request({
              
              type :  'json',
              
              method: 'post',
              
              url : 'tareas/get_fuentes',
              
              onRequest : function(){
                   app.loader_show(); 
              },
              
              onSuccess  : function(responseJSON){
                  
                    app.loader_hide(); 
                //     dojo.forEach( dijit.byId('sel_crpla_selfuente').getOptions(), function(opt,it){

                //         dijit.byId('sel_crpla_selfuente').removeOption(opt);
                //    });

                //    dojo.forEach( responseJSON, function(newOption, it){


                //         dijit.byId('sel_crpla_selfuente').addOption(  {label:  newOption.fuente_nombre, value: newOption.fuente_codigo, disabled : false} );

                //    });

                    dijit.byId('sel_crpla_selfuente').set('value', '');
                    dijit.byId('sel_crpla_selfuente').store.data = [];
                    dijit.byId('sel_crpla_selfuente').store.put(  {name:  'No especificar', id: '0'} );
                    dojo.forEach( responseJSON, function(newOption, it){
                            dijit.byId('sel_crpla_selfuente').store.put(  {name:  newOption.fuente_nombre, id: newOption.fuente_codigo} );
                    });
                    dijit.byId('sel_crpla_selfuente').set('value','0');
                                                    
                //    dijit.byId('sel_crpla_selfuente').addOption();
                    
              },
              
              onFailure : function(){
                  
              } 
              
          }),



          get_clasificador_tarea : new Request({
              
              type :  'json',
              
              method: 'post',
              
              url : 'tareas/get_clasificador',
              
              onRequest : function(){
                   app.loader_show(); 
              },
              
              onSuccess : function(responseJSON){
                  
                   app.loader_hide(); 
                  
                   if( dijit.byId('sel_crpla_selclasificador').getOptions != undefined)
                   { 

                       dojo.forEach( dijit.byId('sel_crpla_selclasificador').getOptions(), function(opt,it){

                            dijit.byId('sel_crpla_selclasificador').removeOption(opt);
                       });


                       dojo.forEach( responseJSON, function(newOption, it){
     
                            dijit.byId('sel_crpla_selclasificador').addOption(  {label:  newOption.clasificador_label, value: newOption.clasificador_id, disabled : false} );

                       });
                     
                    }                               
                //    dijit.byId('sel_crpla_selfuente').addOption();
                    
              },
              
              onFailure : function(){
                  
              } 
              
          }),
 


          get_detalle_otraplanilla : new Request({
              
              type :  'html',
              
              method: 'post',
              
              url : 'planillas/detalle_otraplanilla',
              
              onRequest : function(){
                  app.loader_show(); 

                  dijit.byId('btn_importar_fromotraplanilla').set('disabled', true);
 
            //      dijit.byId('btn_importar_from_tareo').set('disabled', true);
              },
              
              onSuccess  : function(responseText){
                  app.loader_hide(); 
                  
                  dijit.byId('dv_importacion_otradetalle').set('content', responseText);

                 // dijit.byId('btn_importar_from_tareo').set('disabled', false);

                   dijit.byId('btn_importar_fromotraplanilla').set('disabled', false);

                   require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                                  function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                                 if(dojo.byId('dv_importacion_otraempleados') != null ){ 

                                                       if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                          var store_planillas_preview = Observable(Cache(JsonRest({
                                                                                      target: app.getUrl() +"planillas/provide/detalle", 
                                                                                      idProperty: "id",
                                                                                      sortParam: 'oby',
                                                                                      query: function(query, options){

                                                                                            
                                                                                              query.view = Planillas.Cache.view_importacion_planilla_select;
                                                                                            //  console.log('Tipo a buscar: '+ query.planilla);  
                                                                                              return JsonRest.prototype.query.call(this, query, options);
                                                                                      }
                                                                           }), Memory()));

                                                                            var colums = { // you can declare columns as an object hash (key translates to field)
                                                                                     // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                                      col1: {label: '#', sortable: true},
                                                                                      col2: {label: 'DNI', sortable: false},
                                                                                      col3: {label: 'Ap. Paterno', sortable: false},
                                                                                      col4: {label: 'Ap. Materno', sortable: false},
                                                                                      col5: {label: 'Nombres', sortable: false} 
                                                                                    
                                                                          };
           
                                                                              Planillas.Ui.Grids.empleados_preview_importacion  = new  window.escalafon_grid({
                                                                                      loadingMessage : 'Cargando',
                                                                                      store: store_planillas_preview,
                                                                                      getBeforePut: false,
                                                                                      columns: colums 


                                                                              }, "dv_importacion_otraempleados");

                                                                    if( Planillas.Ui.Grids.empleados_preview_importacion != null){
                                                                  //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                                     //  Planillas.Ui.Grids.empleados_preview_importacion.refresh();
                                                                        // Persona.Ui.Grids.trabajadores.store.query({});
                                                                        // Persona.Ui.Grids.trabajadores.cleanup();

                                                                  }          
                                                    }


                                       

                   });
              },
              
              onFailure : function(){
                  
              } 
              
          }),
          
          procesar_planilla : new Laugo.Model({
              
              connect : 'planillas/procesar_planilla'
              
          }),



          cancelar_proceso : new Laugo.Model({ 

              connect :  'planillas/cancelar_proceso'

          }),


          anular_proceso : new Laugo.Model({ 

              connect :  'planillas/anular_proceso'

          }),



          validar : new Request({
              
              type :  'json',
              
              method: 'post',
              
              url : 'planillas/validar',
              
              onRequest : function(){
                   app.loader_show(); 
              },
              
              onSuccess  : function(responseJSON)
              {
                   app.loader_hide(); 
                     
              },
              
              onFailure : function(){
                  
              } 
              
          }),

          eliminar_planilla : new Laugo.Model({

              connect :  'planillas/eliminar'

          }),

          revertir_planilla : new Laugo.Model({

               connect:  'planillas/revertir'

          }),
 
          import_trabajadores_from_pla : new Laugo.Model({

               connect :  'planillas/importar/otra_planilla'

          }),

          import_trabajadores_from_hoja : new Laugo.Model({

               connect :  'planillas/importar/hoja_asistencia'

          }),


          import_trabajadores_from_tareo : new Laugo.Model({

               connect :  'planillas/importar/tareo'

          }),


          desvincular_hoja : new Laugo.Model({

              connect : 'planillas/desvincular_hoja'
          }),


          guardar_siaf : new Laugo.Model({
             
              connect : 'planillas/actualizar_siaf',
              
              message_function: function(mensaje, data){
                  
              }
            
          }),

           
      },
      
      _V : {
           
          view_nueva_planilla : new Laugo.View.Window({
               
              connect : 'planillas/ui_registrar_new',
              
              style : {
                   width :  '900px',
                   height:  '550px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Registrar nueva planilla de remuneraciones ',
              
              onLoad: function(){
                     
                     
                      
                      dojo.connect( dijit.byId('sel_crpla_seltarea'), 'onChange', function(evt){
                           
                            var v = dijit.byId('sel_crpla_seltarea').get('value');
                            
                            if( v==app._consts.enplanilla_especificar_tarea ){
                                
                                dojo.setStyle(  dojo.byId('tr_crpla_seltarea_row'), 'display', 'table-row');
                                dojo.setStyle(  dojo.byId('tr_crpla_selfuente_row'), 'display', 'table-row');
                                dojo.setStyle(  dojo.byId('tr_crpla_especificar_clasi_row'), 'display', 'table-row');

                                dijit.byId('selnpla_especificar_clasi').onChange();
                      
                               
                            }
                            else{
                                 dojo.setStyle(  dojo.byId('tr_crpla_seltarea_row'),  'display', 'none');
                                 dojo.setStyle(  dojo.byId('tr_crpla_selfuente_row'), 'display', 'none');
                                 dojo.setStyle(  dojo.byId('tr_crpla_especificar_clasi_row'),  'display', 'none');
                                 dojo.setStyle(  dojo.byId('tr_crpla_selclasi_row'), 'display', 'none');
                               
                            }
                          
                      });

                      /* tr_crpla_selclasi_row */
                                           
                      dijit.byId('sel_crpla_seltarea').onChange();

                      dojo.connect( dijit.byId('selnpla_especificar_clasi'), 'onChange', function(evt){
                           
                            var v = dijit.byId('selnpla_especificar_clasi').get('value');
                            
                            if( v == '1' )
                            {
                               
                                dojo.setStyle(  dojo.byId('tr_crpla_selclasi_row'), 'display', 'table-row');
                                 
                            }
                            else
                            {
                                 dojo.setStyle(  dojo.byId('tr_crpla_selclasi_row'), 'display', 'none');
                            }
                          
                      });


                      
                      dojo.connect( dijit.byId('sel_crpla_tipoplanilla'), 'onChange', function(evt){
                             
                            if( dijit.byId('sel_crpla_tipoplanilla').get('value') == app._consts.sitlab_construccion){
                               
                                dijit.byId('sel_crpla_seltarea').set('value', app._consts.enplanilla_especificar_tarea );
                            }
                            
                            Planillas.Ui.Grids.planillas_preview.refresh();
                          
                      });
                      
                     
                      dojo.connect( dijit.byId('chk_crpla_conintervalo'), 'onChange', function(evt){
                             
                             if(dijit.byId('chk_crpla_conintervalo').get('value')){
                                   dojo.setStyle(  dojo.byId('tr_crpla_intervalo_row'), 'display', 'table-row');
                             }
                             else{
                                   dojo.setStyle(  dojo.byId('tr_crpla_intervalo_row'), 'display', 'none');
                             }
                            
                          
                      });
                      
                  
                     dojo.connect( dijit.byId('selnpla_tarea'), 'onChange', function(evt){
                           
                            var codigo = dijit.byId('selnpla_tarea').get('value');
                        
                            Planillas._M.get_ff_tarea.send({'view' : codigo});

                            Planillas._M.get_clasificador_tarea.send({'view' : codigo});
                          
                      });
                      
                      
                       var calendars = ['cal_crpla_desde','cal_crpla_hasta'];
                       var fecha = $_currentDate();
                      dojo.forEach(calendars, function(cal,ind){dijit.byId(cal).set('value',  fecha   );});
                      
                       
                     
                     require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                        function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                         if(dojo.byId('table_planillas_preview') != null ){ 

                                             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                    var store_planillas_preview = Observable(Cache(JsonRest({
                                                                            target:  app.getUrl() + "planillas/registro_planillas/preview", 
                                                                            idProperty: "id",
                                                                            sortParam: 'oby',
                                                                            method: 'post',
                                                                            query: function(query, options){
  
                                                                                    query.tipo = dijit.byId('sel_crpla_tipoplanilla').get('value');
                                                                                    
                                                                                    return JsonRest.prototype.query.call(this, query, options);
                                                                            }
                                                                    }), Memory()));

                                                                    var colums = {  

                                                                            col1: {label:'#', sortable: true},
                                                                            col6: {label: 'Año', sortable: false},
                                                                            col7: {label: 'Mes', sortable: false},
                                                                            col2: {label: 'Codigo', sortable: false},
                                                                            col4: {label: 'Centro de Costo', sortable: false},
                                                                            col3: {label: 'Estado', sortable: false},
                                                                            col5: {label: 'Des/Obs', sortable: false},
                                                                            col8: {label: 'Tipo de Planilla', sortable: false}, 
                                                                            col9: {label: 'Num. Emps', sortable: false} 

                                                                    };
 
                                                                    Planillas.Ui.Grids.planillas_preview  = new  window.escalafon_grid({
                                                                            loadingMessage : 'Cargando',
                                                                            store: store_planillas_preview,
                                                                            getBeforePut: false,
                                                                            columns: colums 


                                                                    }, "table_planillas_preview");

                                                          if( Persona.Ui.Grids.planillas_preview != null){
                                                        //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                             Persona.Ui.Grids.planillas_preview.refresh();
                                                              // Persona.Ui.Grids.trabajadores.store.query({});
                                                              // Persona.Ui.Grids.trabajadores.cleanup();

                                                        }          
                                          }



                            });   


                    dijit.byId('cal_crpla_desde').set('value',new Date());    
                    
                    
                    var f_actual = new Date();

                    var f_meses = {      '1' : '01',
                                         '2' : '02',
                                         '3' : '03',
                                         '4' : '04',
                                         '5' : '05',
                                         '6' : '06',
                                         '7' : '07',
                                         '8' : '08',
                                         '9' : '09',
                                         '10' : '10',
                                         '11' : '11',
                                         '12' : '12' 
                                    }

                    var mes = f_meses[(f_actual.getMonth() + 1)];                

                    dijit.byId('selplani_mes').set('value', mes );                  
                                     
               
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
              
          }),
          
          
          registro_de_planillas : new Laugo.View.Window({
               
              connect : 'planillas/ui_registro_planillas',
              
              style : {
                   width :  '850px',
                   height:  '500px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Registro de planillas ',
              
              onLoad: function(){
                      
                      
                    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                 if(dojo.byId('dvplaregistro_table') != null ){ 

                                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                            var store_planillas = Observable(Cache(JsonRest({
                                                                    target: app.getUrl() +"planillas/registro_planillas/main", 
                                                                    idProperty: "id",
                                                                    sortParam: 'oby',
                                                                    query: function(query, options){
                                                                         
                                                                           var data  = dojo.formToObject('form_registroplanilla_consulta');
                                                                           
                                                                            for(d in data){
                                                                                 query[d] = data[d];
                                                                            }
                                                                                    
                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                    }
                                                            }), Memory()));

                                                              var colums = { // you can declare columns as an object hash (key translates to field)
                                                                       // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
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



                                                            Planillas.Ui.Grids.planillas_registro  = new  window.escalafon_grid({
                                                                    loadingMessage : 'Cargando',
                                                                    store: store_planillas,
                                                                    getBeforePut: false,
                                                                    columns: colums,
                                                                    showFooter :true


                                                            }, "dvplaregistro_table");

                                                if( Planillas.Ui.Grids.planillas_registro != null)
                                                {
                                              
                                                     Planillas.Ui.Grids.planillas_registro.refresh();
                                                     
                                                }          
                                  }



                    });


                     
               
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
              
          }),
          
          
          ver_afectacion_presupuestal : new Laugo.View.Window({
             
              connect : 'planillas/ver_afectacion',
              
              style : {
                   width : '830px',
                   height : '500px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Afectacion presupuestal de la planilla',
              
              onLoad : function(){
                  
                  
                     
                    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                 if(dojo.byId('dvtable_afectacion_planilla') != null )
                                 { 

                                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                            var store_afectacion = Observable(Cache(JsonRest({
                                                                    target: app.getUrl() +"planillas/get_afectacion", 
                                                                    idProperty: "id",
                                                                    sortParam: 'oby',
                                                                    query: function(query, options){
                                                                            
                                                                             query.codigo = Planillas.Cache.view_afectacion;

                                                                              var data = dojo.formToObject('form_table_afectacion');

                                                                              for(x in data ){
                                                                                query[x] = data[x];
                                                                              }

                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                    }
                                                            }), Memory()));
                                                             

                                                            if( dojo.byId('hdafectacion_modo').value=='1')
                                                            { 

                                                                    var colums = { 

                                                                              col1: {label:'#', sortable: true},
                                                                              col2: {label: 'Tarea Presupuestal', sortable: false},
                                                                              col3: {label: 'Clasificador', sortable: false},
                                                                              col4: {label: 'Fuente', sortable: false},
                                                                              col5: {label: 'Planilla S./', sortable: false,
                                                                                                      
                                                                                               renderCell: function(object, value, node, options)
                                                                                                                   {
                                                                                                                          
                                                                                                                         dojo.attr(node, 'innerHTML', value);

                                                                                                                   } 
                                                                                    },
                                                                                                                               
                                                                              col6: {label: 'Disponible S./', sortable: false,
                                                                                                      
                                                                                               renderCell: function(object, value, node, options)
                                                                                                                   {
                                                                                                                          if(parseFloat(object.col5) > parseFloat(value) )
                                                                                                                          { 
                                                                                                                            dojo.setStyle(node, 'color', '#990000');
                                                                                                                            dijit.byId('btnap_afectarpresupuestal').set('disabled', true);
                                                                                                                          }
                                                                                                                          dojo.attr(node, 'innerHTML', value);
                                                                                                                   }
                                                                                    },
                                                                                                               
                                                                                                               
                                                                              col7: {label: 'Saldo. S./', sortable: false ,
                                                                                                      
                                                                                               renderCell: function(object, value, node, options)
                                                                                                                   {
                                                                                                                  
                                                                                                                          if(parseFloat(object.col5) > parseFloat(object.col6) )
                                                                                                                          { 
                                                                                                                            dojo.setStyle(node, 'color', '#990000');
                                                                                                                          }
                                                                                                                          dojo.attr(node, 'innerHTML', value);
                                                                                                                   }
                                                                                    } 
                                                                                                               
                                                                          
                                                                             
                                                                   };
 
                                                            }
                                                            else
                                                            {

                                                                   var colums = { 

                                                                             col1: {label:'#', sortable: true},
                                                                             col2: {label: 'Tarea Presupuestal', sortable: false},
                                                                             col3: {label: 'Clasificador', sortable: false},
                                                                             col4: {label: 'Fuente', sortable: false},
                                                                             col5: {label: 'Planilla S./', sortable: false,
                                                                                                     
                                                                                              renderCell: function(object, value, node, options)
                                                                                                                  {
                                                                                                                         
                                                                                                                        dojo.attr(node, 'innerHTML', value);

                                                                                                                  } 
                                                                                  }   
                                                                  };

                                                            }
  
                                                            Planillas.Ui.Grids.planillas_afectacion  = new  window.escalafon_grid({

                                                                    loadingMessage : 'Cargando',
                                                                    store: store_afectacion,
                                                                    getBeforePut: false,
                                                                    columns: colums,
                                                                    showFooter :true,
                                                                    
                                                                    get: function(x)
                                                                    {
                                                                         
                                                                    } 


                                                            }, "dvtable_afectacion_planilla");

                                                  if( Planillas.Ui.Grids.planillas_afectacion != null){
                                                //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                     Planillas.Ui.Grids.planillas_afectacion.refresh();
                                                      // Persona.Ui.Grids.trabajadores.store.query({});
                                                      // Persona.Ui.Grids.trabajadores.cleanup();

                                                }          
                                  }



                    });
                  
              }
             
          }), 

          
          /*  */ 
          modificar_afectacion_planilla : new Laugo.View.Window({
             
              connect : 'planillas/modificar_afectacion',
              
              style : {
                   width : '500px',
                   height : '380px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Modificar la afectación de la planilla ',
              
              onLoad : function()
              { 


                     dojo.connect( dijit.byId('sel_crpla_seltarea'), 'onChange', function(evt){
                          
                           var v = dijit.byId('sel_crpla_seltarea').get('value');
                           
                           if( v==app._consts.enplanilla_especificar_tarea ){
                               
                               dojo.setStyle(  dojo.byId('tr_crpla_seltarea_row'), 'display', 'table-row');
                               dojo.setStyle(  dojo.byId('tr_crpla_selfuente_row'), 'display', 'table-row');
                               dojo.setStyle(  dojo.byId('tr_crpla_especificar_clasi_row'), 'display', 'table-row');

                               dijit.byId('selnpla_especificar_clasi').onChange();
                     
                              
                           }
                           else{
                                dojo.setStyle(  dojo.byId('tr_crpla_seltarea_row'),  'display', 'none');
                                dojo.setStyle(  dojo.byId('tr_crpla_selfuente_row'), 'display', 'none');
                                dojo.setStyle(  dojo.byId('tr_crpla_especificar_clasi_row'),  'display', 'none');
                                dojo.setStyle(  dojo.byId('tr_crpla_selclasi_row'), 'display', 'none');
                              
                           }
                         
                     });
 
                     dijit.byId('sel_crpla_seltarea').onChange();

                     dojo.connect( dijit.byId('selnpla_especificar_clasi'), 'onChange', function(evt){
                          
                           var v = dijit.byId('selnpla_especificar_clasi').get('value');
                           
                           if( v == '1' )
                           {
                              
                               dojo.setStyle(  dojo.byId('tr_crpla_selclasi_row'), 'display', 'table-row');
                                
                           }
                           else
                           {
                                dojo.setStyle(  dojo.byId('tr_crpla_selclasi_row'), 'display', 'none');
                           }
                         
                     });

 
                    
                    dojo.connect( dijit.byId('selnpla_tarea'), 'onChange', function(evt){
                          
                           var codigo = dijit.byId('selnpla_tarea').get('value');
                       
                           Planillas._M.get_ff_tarea.send({'view' : codigo});

                           Planillas._M.get_clasificador_tarea.send({'view' : codigo});
                         
                     });
                     
           
              },

              onClose : function()
              {

                  var data = {}
                  data.codigo = dojo.byId('hdviewplanilla_id').value;
                  Planillas.fn_load_planilla(data);
                  return true;
              }
             
          }),

          
         
          /*  */ 
          ver_resumen_conceptos : new Laugo.View.Window({
             
              connect : 'planillas/ver_resumen/conceptos',
              
              style : {
                   width : '800px',
                   height : '500px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Resumen de la planilla',
              
              onLoad : function(){


                        
              }
             
          }),


          /*  */ 
          modificacion_descripcion : new Laugo.View.Window({
             
              connect : 'planillas/modificar_descripcion',
              
              style : {
                   width : '370px',
                   height : '250px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Actualizar descripcion de la planilla',
              
              onLoad : function(){


                        
              },

              onClose: function()
              {
                  var data = {}
                  data.codigo = dojo.byId('hdviewplanilla_id').value;
                  Planillas.fn_load_planilla(data);
                  return true;
              }
             
          }),

          ver_reporte_conceptos : new Laugo.View.Window({
             
              connect : 'planillas/reporte_por_conceptos',
              
              style : {
                   width : '420px',
                   height : '480px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Reporte de Concepto por trabajador ',
              
              onLoad : function(){



                      try{  
                          
                            require(["dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory","dojo/data/ItemFileReadStore", "dojo/domReady!"], 
                                        function( declare, JsonRest, Observable, Cache, Memory, ItemFileReadStore){
                                      
                                     
                                   var memoryStore = new Memory({});
                                   var  restStore = new JsonRest({

                                            target:"conceptos/provide/reportexconcepto/", 
                                            idProperty: "value",
                                            sortParam: 'oby',
                                            query: function(query, options){
                                                

                                                var data = dojo.formToObject('form_planilla_reporteconcepto');

                                                for(x in data ){
                                                  query[x] = data[x];
                                                }

                                                return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                            }

                                      }); 
                                    Conceptos._M.store_conceptos_reporte =  new  Cache(restStore, memoryStore);
                                   
                                 
                            });
    

                            dijit.byId('selreporteconcepto_conceptos').set('store', Conceptos._M.store_conceptos_reporte );

                            dojo.connect(dijit.byId('sel_crpla_tipoplanilla'), 'onChange', function(e){
                             
                                  dijit.byId('selreporteconcepto_conceptos').set('value','');
                                  Conceptos._M.store_conceptos_reporte.query({});
                            });


                            dojo.connect(dijit.byId('sel_crpla_tipoconcepto'), 'onChange', function(e){
                                  

                                  dijit.byId('selreporteconcepto_conceptos').set('value','');
                                  Conceptos._M.store_conceptos_reporte.query({});

                            });


                            
                       

                      }
                      catch(error){
                           
                            
                      }

                        
              }
             
          }), 

          

          ver_reporte_concepto_mes : new Laugo.View.Window({
             
              connect : 'planillas/reporte_por_concepto_mes',
              
              style : {
                   width : '650px',
                   height : '340px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Reporte Mensualizado por trabajador ',
              
              onLoad : function(){

                    try{  
                        
                          require(["dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory","dojo/data/ItemFileReadStore", "dojo/domReady!"], 
                                      function( declare, JsonRest, Observable, Cache, Memory, ItemFileReadStore){
                                    
                                   
                                 var memoryStore = new Memory({});
                                 var  restStore = new JsonRest({

                                          target:"conceptos/provide/reportexconcepto/", 
                                          idProperty: "value",
                                          sortParam: 'oby',
                                          query: function(query, options){
                                              

                                              var data = dojo.formToObject('form_planilla_reporteconcepto_mes');

                                              for(x in data ){
                                                query[x] = data[x];
                                              }

                                              return dojo.store.JsonRest.prototype.query.call(this, query, options);
                                          }

                                    }); 
                                  Conceptos._M.store_conceptos_reporte =  new  Cache(restStore, memoryStore);
                                 
                               
                          });
                    

                          dijit.byId('selreporte_conceptos').set('store', Conceptos._M.store_conceptos_reporte );

                          dojo.connect(dijit.byId('selreporte_regimen'), 'onChange', function(e){
                           
                                dijit.byId('selreporte_conceptos').set('value','');
                                Conceptos._M.store_conceptos_reporte.query({});
                          });


                          dojo.connect(dijit.byId('selreporte_tipoconcepto'), 'onChange', function(e){
                                

                                dijit.byId('selreporte_conceptos').set('value','');
                                Conceptos._M.store_conceptos_reporte.query({});

                          });


                          dojo.connect(dijit.byId('selreporte_regimen'), 'onChange', function(e){
                                
                                var t = dijit.byId('selreporte_regimen').get('value');

                               /* if(t == '0')
                                {
                                    dijit.byId('selreporte_modo').get('concepto').set('disabled', true);
                                    
                                }
                                else
                                {
                                }

                                dijit.byId('selreporte_modo').onChange();*/

                          });


                          dijit.byId('selreporte_regimen').onChange();

                          dojo.connect(dijit.byId('selreporte_modo'), 'onChange', function(e){
                                
                                var t = dijit.byId('selreporte_modo').get('value');

                                if(t == 'concepto')
                                {
                                     
                                  dojo.setStyle(  dojo.byId('rowreporte_concepto'), 'display', 'table-row');
                                     dojo.setStyle(  dojo.byId('rowreporte_grupo'), 'display', 'none');
                                }
                                else if(t == 'grupo')
                                {
                                     
                                  dojo.setStyle(  dojo.byId('rowreporte_concepto'), 'display', 'none');
                                  dojo.setStyle(  dojo.byId('rowreporte_grupo'), 'display', 'table-row');
                                }
                                else
                                {
                                  dojo.setStyle(  dojo.byId('rowreporte_concepto'), 'display', 'none');
                                  dojo.setStyle(  dojo.byId('rowreporte_grupo'), 'display', 'none');
                                }

                               

                          });
 
                          dijit.byId('selreporte_modo').onChange();
                     

                    }
                    catch(error){
                         
                          
                    }

 
                        
              }
             
          }),

  /*  */ 
          registrar_siaf : new Laugo.View.Window({
             
              connect : 'planillas/registrar_siaf',
              
              style : {
                   width : '400px',
                   height : '300px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Registrar número siaf',
              
              onLoad : function(){
 

                   dojo.forEach(dojo.query('.txtsiaf_siaf'), function(box,ind){
                         
                         var btn_save = dojo.query('.btnsiaf_savesiaf', box.parentNode.parentNode)[0];
                   
                         dojo.connect(box, 'onkeydown', function(evt)
                         {  
 
                               if( (evt.keyCode >= 37 && evt.keyCode <= 40) || evt.keyCode  == 46 ||  evt.keyCode == 110 || (evt.keyCode>=48 && evt.keyCode <= 57) || (evt.keyCode>=96 && evt.keyCode <= 105) || evt.keyCode == 8 || evt.keyCode == 9){

                               }
                               else{
                                    evt.preventDefault();
                               }
                          
                         });
 
                         dojo.connect(box, 'onkeyup', function(evt)
                         {
                             
                              var hd_valor_ini =   dojo.query('.hdsiaf_siaf', box.parentNode)[0].value;
                             
                              if(parseFloat(dijit.byNode(box).get('value'))  != parseFloat(hd_valor_ini))
                              {
                                   dijit.byNode(btn_save).set('disabled', false);
                              }
                              else
                              {
                                    dijit.byNode(btn_save).set('disabled', true);
                              }
                         
                         });


                         dojo.connect(box, 'onkeypress', function(evt)
                         {
                              
                                if(evt.charOrCode == dojo.keys.ENTER)
                                {
                                   dijit.byNode(btn_save).onClick();
                                }
                                else if(evt.charOrCode == dojo.keys.ESCAPE)
                                {
                                     var hd_valor_ini =   dojo.query('.hdsiaf_siaf', box.parentNode)[0].value;
                                     this.value = hd_valor_ini;
                                     dijit.byNode(this).set('value',hd_valor_ini);

                                    evt.stopPropagation(); 

                                }
                         
                         });



                       
                   });     
              },


              onClose: function(){
                  
                  if(dojo.byId('hdviewplanilla_id') != null)
                  {
                    var data = {}
                    data.codigo = dojo.byId('hdviewplanilla_id').value;
                    Planillas.fn_load_planilla(data);
                    return true;
                  }
             
              }
             
          }),
 
          actualizacion_afectacion_detalle : new Laugo.View.Window({
             
              connect : 'planillas/actualizacion_afectacion_detalle',
              
              style : {
                   width : '500px',
                   height : '300px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Actualizar afectación presupuestal del trabajador ',
              
              onLoad : function(){


                    dojo.connect( dijit.byId('selnpla_tarea'), 'onChange', function(evt)
                    {
                          
                           var codigo = dijit.byId('selnpla_tarea').get('value');

                           Planillas._M.get_ff_tarea.send({'view' : codigo});

                         //  Planillas._M.get_clasificador_tarea.send({'view' : codigo});
                         
                     });

                        
              }
             
          }),
          
          
          buscar_trabajador: new Laugo.View.Window({
              
              connect : 'planillas/buscar_trabajador',
               
              style : {

                   width :  '940px',
                   height:  '500px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Agregar Trabajador a Planilla de Remuneracion ',
              
              onLoad: function(){
 

                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                    function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                     if(dojo.byId('dv_planillaadddetalle_table') != null ){ 

                                         if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                var store_trabajadores = JsonRest({
                                                                        target:app.getUrl() + "escalafon/get_trabajadores", 
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
                                                                            col0: Selector({}),
                                                                            col1: {label: '#', sortable: true, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                        dojo.attr(node, 'innerHTML', '[' + value+']');
                                                                                                      }
                                                                                                      else
                                                                                                      {
                                                                                                         dojo.attr(node, 'innerHTML', value ); 
                                                                                                      }
                                                                                                     
                                                                                               }

                                                                                  },
                                                                            col2: {label: 'Ap. Paterno', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},
                                                                            col3: {label: 'Ap. Materno', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},

                                                                            col4: {label: 'Nombres', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},

                                                                            col5: {label: 'DNI', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},
                                                                            col6: {label: 'Tipo de trabajador', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},

                                                                            col7: {label: 'Area/Proyecto', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},
                                                                            col8: {label: 'Cargo', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }},

                                                                            col9: {label: 'Activo', sortable: false, 

                                                                                   renderCell: function(object, value, node, options){
                                                                                                      if(parseInt(object.activo) == 0 )
                                                                                                      { 
                                                                                                        dojo.setStyle(node, 'color', '#990000');
                                                                                                   
                                                                                                      }
                                                                                                      dojo.attr(node, 'innerHTML',  value);
                                                                                               }} 
                                                                          
                                                                };

 

                                                               /*  var columxxs = { 

                                                                           col1: {label:'#', sortable: true},
                                                                           col2: {label: 'Tarea Presupuestal', sortable: false},
                                                                           col3: {label: 'Clasificador', sortable: false},
                                                                           col4: {label: 'Fuente', sortable: false},
                                                                           col5: {label: 'Planilla S./', sortable: false,
                                                                                                   
                                                                                            renderCell: function(object, value, node, options)
                                                                                                                {
                                                                                                                       
                                                                                                                      dojo.attr(node, 'innerHTML', value);

                                                                                                                } 
                                                                                 },
                                                                                                                            
                                                                           col6: {label: 'Disponible S./', sortable: false,
                                                                                                   
                                                                                            
                                                                                 },
                                                                                                            
                                                                                                            
                                                                           col7: {label: 'Saldo. S./', sortable: false ,
                                                                                                   
                                                                                            renderCell: function(object, value, node, options)
                                                                                                                {
                                                                                                               
                                                                                                                       if(parseFloat(object.col5) > parseFloat(value) )
                                                                                                                       { 
                                                                                                                         dojo.setStyle(node, 'color', '#990000');
                                                                                                                       }
                                                                                                                       dojo.attr(node, 'innerHTML', value);
                                                                                                                }
                                                                                 } 
                                                                                                            
                                                                       
                                                                          
                                                                };*/
 
                                                                Planillas.Ui.Grids.buscar_trabajador  = new  (declare([Grid, Selection,Keyboard]))({

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


                                                                }, "dv_planillaadddetalle_table");

                                                      if( Planillas.Ui.Grids.buscar_trabajador != null){
                                                  
                                                    }          
                                      }
 
                        });
                  
                  
              }
                
              
          }),


          boletas_individuales : new Laugo.View.Window({
               
              connect : 'planillas/boletas_individuales',
              
              style : {
                   width :  '880px',
                   height:  '500px',
                   'background-color'  : '#FFFFFF'
              },
              
              title: ' Registro de boletas de pago por trabajador ',
              
              onLoad: function(){
                  

                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                             function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                 if(dojo.byId('dv_planilla_boletasindividuales') != null ){ 

                                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                            var store_boletas = Observable(Cache(JsonRest({
                                                                    target: app.getUrl() +"planillas/registro_boletas", 
                                                                    idProperty: "id",
                                                                    sortParam: 'oby',
                                                                    query: function(query, options){
                                                                         
                                                                           var data  = dojo.formToObject('form_boletaindividual');
                                                                           
                                                                            for(d in data){
                                                                                 query[d] = data[d];
                                                                            }
                                                                                    
                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                    }
                                                            }), Memory()));

                                                              var colums = { 
                                                                        
                                                                        col0: Selector({}),
                                                                        col1: {label: '#', sortable: true},                                                                          
                                                                        col2: {label: 'DNI', sortable: true},
                                                                        col3: {label: 'Año', sortable: false},
                                                                        col4: {label: 'Mes', sortable: false},
                                                                        col5: {label: 'Planilla', sortable: false},
                                                                        col6: {label: 'Periodo', sortable: false},
                                                                        col7: {label: 'Regimen', sortable: false},
                                                                        col8: {label: 'Ocupación', sortable: false},
                                                                        col9: {label: 'Ingresos', sortable: false},
                                                                        col10: {label: 'Descuento', sortable: false},
                                                                        col11: {label: 'Neto', sortable: false},
                                                                        col12: {label: 'Aportacion', sortable: false}
                                                                      
                                                                };



                                                        Planillas.Ui.Grids.boletas_individuales  = new  (declare([Grid, Selection,Keyboard]))({

                                                                      store: store_boletas,
                                                                      loadingMessage : 'Cargando',
                                                                      allowSelectAll: true,
                                                                      getBeforePut: true,
                                                                      selectionMode: "none",
                                                                      columns: colums,
                                                                      pagingLinks: false,
                                                                      pagingTextBox: true,
                                                                      firstLastArrows: true,
                                                                      rowsPerPage : 100


                                                              }, "dv_planilla_boletasindividuales");

                                                if( Planillas.Ui.Grids.boletas_individuales != null)
                                                {
                                              
                                                     Planillas.Ui.Grids.boletas_individuales.refresh();
                                                     
                                                }          
                                  }



                    });


                     
               
              },
              
              onClose: function(){
                  
              //    alert('ventana cerrada');
                   return true;
              }
              
          }),


          /*  */ 
          anulacion_planilla : new Laugo.View.Window({
             
              connect : 'planillas/anulacion',
              
              style : {
                   width : '600px',
                   height : '250px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Anular proceso',
              
              onLoad : function(){


                        
              }
             
          }),

          asistencias_importadas : new Laugo.View.Window({
             
              connect : 'planillas/asistencias_importadas',
              
              style : {
                   width : '600px',
                   height : '250px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Registro de importaciones',
              
              onLoad : function(){


                        require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                             function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                 if(dojo.byId('table_planilla_importaciones') != null ){ 

                                     if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                            var store_importaciones = Observable(Cache(JsonRest({
                                                                    target: app.getUrl() +"planillas/registro_importaciones", 
                                                                    idProperty: "id",
                                                                    sortParam: 'oby',
                                                                    query: function(query, options){
                                                                         
                                                                          query.planilla =  dojo.byId('hdviewplanilla_id').value;
                                                                               
                                                                        return JsonRest.prototype.query.call(this, query, options);
                                                                    }
                                                            }), Memory()));

                                                              var colums = { 
                                                                         
                                                                        col1: {label: '#', sortable: true},                                                                          
                                                                        col2: {label: 'Fecha', sortable: true},
                                                                        col3: {label: 'Descripcion', sortable: false},
                                                                        col4: {label: 'Realizada por', sortable: false} 
                                                                      
                                                                };



                                                        Planillas.Ui.Grids.planilla_importaciones  = new  (declare([Grid, Selection,Keyboard]))({

                                                                      store: store_importaciones,
                                                                      loadingMessage : 'Cargando', 
                                                                      columns: colums 
                                                                   

                                                              }, "table_planilla_importaciones");

                                                if( Planillas.Ui.Grids.planilla_importaciones != null)
                                                {
                                              
                                                     Planillas.Ui.Grids.planilla_importaciones.refresh();
                                                     
                                                }          
                                  }



                    });

                        
              },

              onClose : function(){
                
              }
             
          }),
          


          detalle_hoja_importada : new Laugo.View.Window({
             
              connect : 'asistencias/get_registro_asistencia',
              
              style : {
                   width : '800px',
                   height : '600px',
                   'background-color' : '#FFFFFF'
              },
              
              title : 'Detalle de la importación',
              
              onLoad : function(){

                   dijit.byId('dvpanel_explorar_asistencias').set('content', responseText);

       /*            
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

                   }); */


                   Asistencias.hoja_ready();
                        
              },

              onClose : function(){
                
              }
             
          }),
          

          Importacion: {


               otra_planilla : new Laugo.View.Window({
                                  
                        connect : 'planillas/importacion/otra_planilla',
                         
                        style : {
                             width :  '940px',
                             height:  '440px',
                             'background-color'  : '#FFFFFF'
                        },
                        
                        title: ' Importar trabajadores a la planilla actual',
                        
                        onLoad: function(){
                            
                            
                            
                                 require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                                  function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


                                                   if(dojo.byId('dv_importacion_plapreview') != null ){ 

                                                       if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                              var store_planillas_preview = Observable(Cache(JsonRest({
                                                                                      target:  app.getUrl() + "planillas/registro_planillas/preview", 
                                                                                      idProperty: "id",
                                                                                      sortParam: 'oby',
                                                                                      query: function(query, options){

                                                                                              
                                                                                              query.tipo = dojo.byId('hdviewplanilla_tipo_id').value;
                                                                                              query.just = 'procesadas';
                                                                                              console.log('Tipo a buscar: '+ query.tipo);
                                                                                              return JsonRest.prototype.query.call(this, query, options);
                                                                                      }
                                                                              }), Memory()));

                                                                              var colums = { // you can declare columns as an object hash (key translates to field)
                                                                                     // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                                                      col1: {label:'#', sortable: true},
                                                                                      col2: {label: 'Codigo', sortable: false},
                                                                                      col3: {label: 'Estado', sortable: false},
                                                                                      col4: {label: 'Centro de Costo', sortable: false},
                                                                                      col5: {label: 'Des/Obs', sortable: false},
                                                                                      col6: {label: 'Año', sortable: false},
                                                                                      col7: {label: 'Mes', sortable: false},
                                                                                      col8: {label: ' Tipo de Planilla ', sortable: false}, 
                                                                                      col9: {label: 'Num. Emps', sortable: false} 

                                                                              };
           
                                                                              Planillas.Ui.Grids.planillas_preview_importacion  = new  window.escalafon_grid({

                                                                                      store: store_planillas_preview,
                                                                                      loadingMessage : 'Cargando',
                                                                                      getBeforePut: false,
                                                                                      columns: colums 


                                                                              }, "dv_importacion_plapreview");

                                                                    if( Planillas.Ui.Grids.planillas_preview_importacion != null){
                                                                  //Persona.Ui.Grids.comisiones.store.view_persona('6');
                                                                       Planillas.Ui.Grids.planillas_preview_importacion.refresh();
                                                                        // Persona.Ui.Grids.trabajadores.store.query({});
                                                                        // Persona.Ui.Grids.trabajadores.cleanup();

                                                                  }          
                                                    }
                                                    
                                                    
                                                    
                                                 



                                      });   
                               
                            
                            
           
                        }
                    }),



                  hojaasistencia: new Laugo.View.Window({


                        connect : 'planillas/importacion/hojaasistencia',
                         
                        style : {
                             width :  '1000px',
                             height:  '500px',
                             'background-color'  : '#FFFFFF'
                        },
                        
                        title: ' Importar desde registro de asistencia',
                        
                        onLoad: function(){


                                  require(["dgrid/List", "dgrid/OnDemandGrid", "dgrid/ColumnSet","dgrid/Selection", "dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dgrid/extensions/ColumnHider","dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                                   function(List, Grid, ColumnSet, Selection,  Selector,editor, Keyboard, Pagination, ColumnHider, declare, JsonRest, Observable, Cache, Memory){

                                     
                                            if(dojo.byId('dv_importacion_hojaspreview') != null )
                                            { 
                                                       
                                                   if( window.grid_columnhider === null ||  window.grid_columnhider === undefined)  window.grid_columnhider = (declare([Grid, Selection, ColumnHider, Keyboard]));

 
                                                      var store_asistencias_preview = Observable(Cache(JsonRest({

                                                              target:  app.getUrl() + "asistencias/provide/importacion", 
                                                              idProperty: "id",
                                                              sortParam: 'oby',
                                                              query: function(query, options){

                                                                      var data  = dojo.formToObject('form_importarasistencia_hojas');
                                                                      
                                                                       for(d in data){
                                                                            query[d] = data[d];
                                                                       } 

                                                                      return JsonRest.prototype.query.call(this, query, options);
                                                              }

                                                      }), Memory()));

                                                      var colums = { 

                                                              col0: Selector({ unhidable : true}),
                                                              col1: {label:'#', sortable: true},
                                                              col2: {label: 'Codigo', sortable: false},
                                                              info_import: {label: 'Importado %', sortable: false},
                                                              col6: {label: 'Meta', sortable: false},
                                                              col3: {label: 'Desde', sortable: false},
                                                              col4: {label: 'Hasta', sortable: false},
                                                              col5: {label: 'C.Tra', sortable: false} 

                                                      };

                                                      Asistencias.Ui.Grids.prewview_hojas_importacion  = new  window.grid_columnhider({

                                                              store: store_asistencias_preview,
                                                              loadingMessage : 'Cargando',
                                                              getBeforePut: false,
                                                              allowSelectAll: true,
                                                              columns: colums 


                                                      }, "dv_importacion_hojaspreview");

   
                                            }


                                            if( dojo.byId('table_asistencias_resumenimportacion') != null )
                                            {   

                                                  var ComplexGrid = declare([Grid, ColumnSet, Selection, Keyboard]);

                                                  var estructura_txt = dojo.byId('asistencias_tabla_estructura').value;
                                                  var struct = dojo.json.parse(estructura_txt);
                                                
                                                  var contador_registros_importacion = 0;
                                                  
                                                  struct[0][0][0] = Selector({field:'col0'});
                                                 
                                                    
                                                  struct[0][0][1].renderCell = function(object, value, node, options){

                                                                                   if(contador_registros_importacion == 0)
                                                                                   {
                                                                                      dijit.byId('btniha_importar').set('disabled', false);
                                                                                      
                                                                                      if(dijit.byId('btniha_visualizar') !=null )
                                                                                      {

                                                                                          dijit.byId('btniha_visualizar').set('disabled', false);
                                                                                        
                                                                                      }
                                                                                      
                                                                                      if(dijit.byId('btniha_devolver') != null)
                                                                                      {
                                                                                          dijit.byId('btniha_devolver').set('disabled', false );
                                                                                      }
                                                                                   }

                                                                                   contador_registros_importacion++;
  
                                                                                   dojo.byId('spcounter_importacion').innerHTML = contador_registros_importacion;

                                                                                   dojo.attr(node, 'innerHTML', value);
                                                                               }
  

                                                  var store = Observable(Cache(JsonRest({
                                                            target:  app.getUrl() + "asistencias/get_table_resumen_importacion/", 
                                                            idProperty: "id",
                                                            sortParam: 'oby',
                                                            query: function(query, options){
                                                                      
                                                                      contador_registros_importacion = 0;
                                                                      dojo.byId('spcounter_importacion').innerHTML = contador_registros_importacion;


                                                                      dijit.byId('btniha_importar').set('disabled', true);
                                                                      
                                                                      if(dijit.byId('btniha_visualizar') !=null )
                                                                      {

                                                                          dijit.byId('btniha_visualizar').set('disabled', true);
                                                                        
                                                                      }

                                                                      if(dijit.byId('btniha_devolver') != null)
                                                                      {
                                                                          dijit.byId('btniha_devolver').set('disabled', true );
                                                                      }

                                                                    if(dojo.byId('form_importacion_asistencia_config') != null)
                                                                    {
                                                                        var  data = dojo.formToObject('form_importacion_asistencia_config');
       
                                                                        for(x in data)
                                                                        {
                                                                          query[x] = data[x];
                                                                        } 

                                                                        var filtro_trabajador = dojo.formToObject('form_importacion_asistencia_filtro_trabajador');
   
                                                                        for(x in filtro_trabajador)
                                                                        {
                                                                          query[x] = filtro_trabajador[x];
                                                                        } 

                                                                        query.return = '1';
                                                                    } 
                                                                    else
                                                                    {
                                                                        query.return = '0';
                                                                    }
                                                                    

                                                                  return JsonRest.prototype.query.call(this, query, options);
                                                          }
                                                  }), Memory()));
 
                                                   Planillas.Ui.Grids.planillas_preview_importacion  = new ComplexGrid({

                                                          store: store,
                                                          loadingMessage : 'Cargando',
                                                          getBeforePut: false,
                                                          allowSelectAll: true,
                                                          columnSets: struct 


                                                  }, "table_asistencias_resumenimportacion");
                                                   
                                            }

                              });   


                        }

                })


          },
 
 
          resumen_descansos_medicos : new Laugo.View.Window({
             
              connect : 'planillas/resumen_descuentos_medicos',
              
              style : {
                   width : '800px',
                   height : '380px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Descansos médicos ',
              
              onLoad : function(){ 

                    
                    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                     function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){
 

                                      if(dojo.byId('dvDescansosMedicosPlanilla') != null ){ 

                                          if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                     var store = Observable(Cache(JsonRest({
                                                             target:  app.getUrl() + "planillas/get_descansos_medicos_anio", 
                                                             idProperty: "id",
                                                             sortParam: 'oby',
                                                             query: function(query, options){
 
                                                                     // query.tipo = dojo.byId('hdviewplanilla_tipo_id').value;
                                                                     // query.just = 'procesadas';
                                                                     // console.log('Tipo a buscar: '+ query.tipo);
                                                                     query.planilla = dojo.byId('hdviewplanilla_id').value;
                                                                     return JsonRest.prototype.query.call(this, query, options);
                                                             }
                                                     }), Memory()));

                                                     var colums = { // you can declare columns as an object hash (key translates to field)
                                                            // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                             num: {label:'#', sortable: true},
                                                             trabajador: {label: 'Trabajador', sortable: false},
                                                             dni: {label: 'DNI', sortable: false},
                                                             anio: {label: 'Anio', sortable: false},
                                                             dias_dm: {label: 'Dias en escalafon', sortable: false},
                                                             planilla_dm: {label: 'En esta planilla (d/h)', sortable: false},
                                                             horas_anio_dm: {label: 'Horas pagadas en el anio', sortable: false},
                                                             dias_anio_dm: {label: 'Dias pagados en el anio', sortable: false}      

                                                     };
                                              
                                                     console.log(colums);

                                                     Planillas.Ui.Grids.resumen_planilla_descansos_medicos  = new window.escalafon_grid({

                                                             store: store,
                                                             loadingMessage : 'Cargando',
                                                             getBeforePut: false,
                                                             columns: colums 


                                                     }, "dvDescansosMedicosPlanilla");

                                                    
                                                     if ( Planillas.Ui.Grids.resumen_planilla_descansos_medicos != null) {

                                                          Planillas.Ui.Grids.resumen_planilla_descansos_medicos.refresh();
 
                                                     }          
                                       }
                                        

                         });   

              }
             
          }),



          resumen_descansos_medicos_trabjador : new Laugo.View.Window({
             
              connect : 'descansosmedicos/descansos_medicos_trabajador',
              
              style : {
                   width : '500px',
                   height : '300px',
                   'background-color' : '#FFFFFF'
              },
              
              title : ' Descansos médicos del trabajador',
              
              onLoad : function(){ 

                    
                    require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                     function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){

                                       
                                      if(dojo.byId('dvdescansosmedicos_anio') != null ){ 

                                          if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                     var store = Observable(Cache(JsonRest({
                                                             target:  app.getUrl() + "escalafon/get_descansosmedicos", 
                                                             idProperty: "id",
                                                             sortParam: 'oby',
                                                             query: function(query, options){
          
                                                                     // query.tipo = dojo.byId('hdviewplanilla_tipo_id').value;
                                                                     // query.just = 'procesadas';
                                                                     // console.log('Tipo a buscar: '+ query.tipo);
                                                                    //   query.planilla = dojo.byId('hdviewplanilla_id').value;
                                                                    var datos = dojo.formToObject('formDescansosMedicosTrabajador');

                                                                    for(x in datos){
                                                                        query[x] = datos[x];
                                                                    }

                                                                     return JsonRest.prototype.query.call(this, query, options);
                                                             }
                                                     }), Memory()));

                                                     var colums = { // you can declare columns as an object hash (key translates to field)
                                                            // col1: editor({label: '#', field: 'date'}, dijit.form.DateTextBox),
                                                             num: {label:'#', sortable: true},
                                                             documento: {label: 'Documento', sortable: false},
                                                             desde: {label: 'Desde', sortable: false},
                                                             hasta: {label: 'Hasta', sortable: false}, 
                                                             dias: {label: 'Dias', sortable: false} 

                                                     };
                                               
                                                     Planillas.Ui.Grids.resumen_trabajador_descansos_medicos  = new window.escalafon_grid({

                                                             store: store,
                                                             loadingMessage : 'Cargando',
                                                             getBeforePut: false,
                                                             columns: colums 


                                                     }, "dvdescansosmedicos_anio");

                                                    
                                                     if ( Planillas.Ui.Grids.resumen_trabajador_descansos_medicos != null) {

                                                          Planillas.Ui.Grids.resumen_trabajador_descansos_medicos.refresh();
          
                                                     }          
                                       }
                                        

                         });   

              }
             
          })  
  

      }, 
       
      
      Ui: {
           
          Grids :  {
              
              buscar_trabajador              : null,  
              planillas_detalle              : null,
              planillas_registro             : null,
              planillas_preview              : null,
              resumen_ingresos               : null,
              resumen_descuentos             : null,
              resumen_aportaciones           : null, 
              resumen_conceptos_xclasificador : null,
              planillas_afectacion           : null,
              resumen_conceptos_trabajadores : null,
              planillas_preview_importacion  : null, 
              empleados_preview_importacion  : null,
              trabajadores_gestionardata     : null,
              planillas_reporte_filtro       : null,
              tareos                         : null,
              tareos_detalle                 : null,


              on_select : function(event){
                
                 if(event.grid.id == 'dvdetalle_planilla' || event.grid.id == 'dvdetalle_planilla_procesada' ){
                     
                     var row_id = event.rows[0].id; // verifiacion_key
                     var pla_key = dojo.byId('hdviewplanilla_id').value;
                     Planillas.Cache.detalleplanilla_detalle_id = row_id;
                     Planillas.Cache.detalleplanilla_plakey = pla_key;
                  //   Verificaciones._M.get_preview_ficha.send({'ficha' : row_id}); 
                     Planillas._M.get_detalleplanilla_variables.send({'codigo' : row_id, 'pla_key' : pla_key});  
                 } 
                 else if(event.grid.id == 'dvtable_variablesview'){
                     
                     var row_id = event.rows[0].id; // verifiacion_key
                     console.log('variable: '+row_id);
                     Variables.Ui.get_view(row_id);
                 }
                  else if(event.grid.id == 'dvtable_conceptosview'){
                     
                     var row_id = event.rows[0].id; // verifiacion_key
                     console.log('concepto: '+row_id);
                     Conceptos.Ui.get_view(row_id);
                 }
                 else if(event.grid.id == 'dvtablepla_resumen_ingresos'){
                     
                     var id_conc = event.rows[0].id; // verifiacion_key
                     var id_pla  = dojo.byId('hdviewplanilla_id').value;
                     Planillas.Cache.view_resumen_conc_pla = id_pla;
                     Planillas.Cache.view_resumen_conc_concepto = id_conc;
                     Planillas.Ui.Grids.resumen_conceptos_trabajadores.refresh();
                  
                 } 
                 else if(event.grid.id == 'dvtablepla_resumen_descuentos'){
                     
                     var id_conc = event.rows[0].id; // verifiacion_key
                      var id_pla  = dojo.byId('hdviewplanilla_id').value;
                     Planillas.Cache.view_resumen_conc_pla = id_pla;
                     Planillas.Cache.view_resumen_conc_concepto = id_conc;
                     Planillas.Ui.Grids.resumen_conceptos_trabajadores.refresh();
                 }
                  else if(event.grid.id == 'dvtablepla_resumen_aportaciones'){
                     
                     var id_conc = event.rows[0].id; // verifiacion_key
                     var id_pla  = dojo.byId('hdviewplanilla_id').value;
                     Planillas.Cache.view_resumen_conc_pla = id_pla;
                     Planillas.Cache.view_resumen_conc_concepto = id_conc;
                     Planillas.Ui.Grids.resumen_conceptos_trabajadores.refresh();
                 }
                 else if(event.grid.id == 'dv_importacion_plapreview'){
                     
                     var id_pla = event.rows[0].id; // verifiacion_key
                     Planillas.Cache.view_importacion_planilla_select   = id_pla;
                     console.log('Planilla: '+id_pla);
                     //Planillas.Ui.Grids.empleados_preview_importacion.refresh();

                     Planillas._M.get_detalle_otraplanilla.send({'planilla' :  id_pla});
                 }
                 else if (event.grid.id == 'dvtgd_trabajadores'){

                      var id_trabajador = event.rows[0].id;
                      Trabajadores.Cache.trabajador_gestion_datos = id_trabajador;
                       
                      if(dijit.byId('selgd_modo').get('value') == '1')
                      {

                           Trabajadores._V.gestionar_datos_rapida.send({'trabajador' : id_trabajador});   
                      }
                      else
                      {
                           Trabajadores._V.gestionar_datos.send({'trabajador' : id_trabajador}); 
                      }
                    /*
                                     else if (event.grid.id == 'dv_importacion_hojaspreview')
                                     {

                                          var id_hoja_asis = event.rows[0].id;
                                          Asistencias.Cache.view_hoja_preview_importacion = id_hoja_asis;
                                          Asistencias.Cache.view_hoja_preview_importacion_pla =  dojo.byId('hdviewplanilla_id').value;
                                          Asistencias.get_preview_hoja(id_hoja_asis, dojo.byId('hdviewplanilla_id').value);
                                     }*/
  
                 }
                 else if (event.grid.id == 'dv_importacion_tareo'){

                      var id_tareo = event.rows[0].id;
                      Planillas.Cache.tareo_seleccionado = id_tareo;
                      
                      Planillas._M.get_detalle_tareo.send({tareo: id_tareo});

                     // Planillas.Ui.Grids.tareos_detalle.refresh();
                      console.log(id_tareo);
                 }
                 else if (event.grid.id == 'table_tipoplanilla_tipos'){

                      var view = event.rows[0].id;

                      Tipoplanilla._V.view.send({'view' : view});
                      
                      console.log(view);

                 }
                 else if (event.grid.id == 'dv_geusu_usuarios'){

                      var view = event.rows[0].id;

                      //Tipoplanilla._V.view.send({'view' : view});

                      Users._V.permisos_usuario.send({'view' : view});
                      
                      console.log(view);

                 }
                 else if(event.grid.id == 'dv_planilla_boletasindividuales')
                 {
                      var view = event.rows[0].data.planilla;
 
                      Planillas.Cache.ver_planilla_desde_boleta =view;
                      
                 }
                 else if(event.grid.id == 'impuestoquinta_filtrotrabajadores')
                 {
                      var view = event.rows[0].id;
                      var data = {}
                
                      data.view = view;
                      Impuestos._V.view_resumen_quinta.send(data);
                 }
                 else if(event.grid.id == 'dvasisregistroimportacion_table')
                 {
                      var view = event.rows[0].id;
                      var data = {}
                
                      data.view = view;

                      dojo.byId('hdasisimportacion_trabajadores_view').value = view; 

                       var reg = event.grid.store.get(view);
                      console.log(event.grid.store.get(view));  

                      dojo.byId('spasisimportacion_codigo').innerHTML = reg.col2;
                      dojo.byId('spasisimportacion_proyecto').innerHTML = reg.col5;
                      dojo.byId('spasisimportacion_periodo').innerHTML = 'Del :'+ reg.col7+' hasta el: '+reg.col8;

                     if( Asistencias.Ui.Grids.asistencias_registro_importacion_trabajadores != null ) Asistencias.Ui.Grids.asistencias_registro_importacion_trabajadores.refresh();
                     
                     console.log(data);
                 }
                 else if(event.grid.id == 'table_estadodeldia')
                 {
                      var view = event.rows[0].id;
                      var data = {}
                 
                      data.view = view;
                         
                      Asistencias._V.view_estado_dia.send(data);

                 }
                 else if(event.grid.id == 'table_horarios')
                 {
                      var view = event.rows[0].id;
                      var data = {}
                 
                      data.view = view;
                         
                      Asistencias._V.view_horario.send(data);

                 }
                 else if(event.grid.id == 'table_tipoplanilla_asistencia')
                 {
                      var view = event.rows[0].id;
                      var data = {}
                 
                      data.view = view;
                         
                      Asistencias._V.view_planillatipo_config.send(data);

                 } 
                 else if(event.grid.id == 'dvtable_estadodia_plati')
                 {
                      var view = event.rows[0].id;
                      var query = {}
                      var data = dojo.formToObject('form_estadodia_plati'); 

                      for(d in data){
                          query[d] = data[d];
                      }
                 
                      query.estado = view;

                         
                      Asistencias._V.view_planillatipo_config_estado.send(query);

                 } 
                 else if(event.grid.id == 'tablapermisos_panel')
                 {
                      var view = event.rows[0].id;
                      var query = {}
                       
                      Permisos._V.ver_detalle_panel.send({'view' : view});
 
                 } 
                 else if(event.grid.id == 'table_tipoplanilla_quintacategoria')
                 {
                      var view = event.rows[0].id;
                      var data = {}
                 
                      data.view = view;
                         
                      QuintaCategoria._V.view_planillatipo_config.send(data);

                 } 
                 else
                 {
                      console.log('Ocurrio algo inesperado');
                 }

                
               } 
              
          },
           
          btn_registrarplanilla_click : function(btn,evt){
                
              var data  = dojo.formToObject('formnuevaplanilla');
              
          
              err = false; 
              if(data.tipo_select_tarea == '1' && ( data.tarea == '' || data.tarea == '0' ||  data.fuente_financiamiento == '' || data.fuente_financiamiento == '0'   ) )
               {

                  err= true;
                   alert('Verifique los datos Presupuestales de la planilla');
               }

               if(data.conintervalo && ( data.fechahasta == '' || data.fechadesde == '' ) ){

                   err = true;
                   alert('Verifique el intervalo de fechas');
               } 

               if(!err){ 
                  if(confirm('Realmente desea reagistrar la planilla ?')){ 
                  
                      if(Planillas._M.registrar.process(data))
                      {
                         Planillas._V.view_nueva_planilla.close();
                         // Planillas._V.registro_de_planillas.load({});
                         var data = {}
                         
                         data.codigo = Planillas._M.registrar.data.key;
                         
                         Planillas.fn_load_planilla(data);
                      }
                  }
                } 
                
          }, 
           
          
          btn_filtrar_registroplanilla : function(btn,evt){
            
           
               
             Planillas.Ui.Grids.planillas_registro.refresh();
            
          },
          
         
          btn_visualizarplanilla_click : function(btn,evt){
               
                
                var data = {} 
                
                    data.codigo = '';
                
                var grid = Planillas.Ui.Grids.planillas_registro;
                
                if(grid != null ){  
                     data.codigo = '';
                     for(var i in grid.selection){data.codigo = i;}
 
                }else{

                     Console.log('No existe el objeto GRID');
                }

                if(data.codigo != '')      
                { 
                          
                       Planillas.fn_load_planilla(data);    
                       Planillas._V.registro_de_planillas.close();
                 
                }
                else{
                        alert('Debe seleccionar un registro');
                }

          },
          
          btn_buscartrabajador_click : function(){
              
                
                var id = dojo.byId('hdidplanilla_actual').value;
                
                Planillas._V.buscar_trabajador.load({'view' : id});
            
          },
          
          
          btn_filtrardetalle_click : function(btn,evt){
              
                Planillas.Ui.Grids.buscar_trabajador.refresh();
                
            
          },
          
          btn_procesarplanilla_click : function(btn,evt){
            
               var codigo = dojo.byId('hdviewplanilla_id').value; 

              // var verificar_pensiones = dijit.byId('chkComprobarPensiones').get('value');
 
               var calcular_quinta = '0';
               
               if(dojo.byId('hd_planilla_calcularquinta') != null){
                   calcular_quinta = (dijit.byId('hd_planilla_calcularquinta').get('value') != false) ? '1' : '0'; 
               }


               var calcular_cuarta = '0';
               
               if(dojo.byId('hd_planilla_calcularcuarta') != null){
                   calcular_cuarta = (dijit.byId('hd_planilla_calcularcuarta').get('value') != false) ? '1' : '0'; 
               }
 
               if(confirm('Realmente desea procesar la planilla ? '))
               {
                    if(Planillas._M.procesar_planilla.process({'codigo' : codigo, 'calcular_quinta' : calcular_quinta, 'calcular_cuarta' : calcular_cuarta }))
                    { 
               
                        if(Planillas._M.procesar_planilla.data.negativos == '1')
                        {
                            app.alert('ATENCION. La planilla tiene netos NEGATIVOS');
                        }

                        // if(verificar_pensiones === '1'){

                        //   if(Planillas._M.procesar_planilla.data.problemas_pension == '1')
                        //   {
                        //       app.alert(Planillas._M.procesar_planilla.data.problemas_pension_mensaje);
                        //   }
                          
                        // }

                        Planillas.fn_load_planilla({'codigo' : Planillas._M.procesar_planilla.data.key});
                    }
               }
               
            
          },
          
          btn_cancelarproceso_click : function(btn,evt){

                 var key = dojo.byId('hdviewplanilla_id').value;
                
                 if(confirm('¿Realmente desea cancelar el proceso de la planilla?')){ 

                   if(Planillas._M.cancelar_proceso.process({'key' : key})){

                         Planillas.fn_load_planilla({'codigo' : Planillas._M.cancelar_proceso.data.key});
                   }

                 }

               

          },

          
          btn_anularproceso_click : function(btn,evt){

                 var datos = dojo.formToObject('form_planilla_anular');
                 datos.key = dojo.byId('hdviewplanilla_id').value;
                   
                 if(confirm('Realmente desea anular el proceso ?'))
                 {

                     if(Planillas._M.anular_proceso.process(datos)) 
                     {
                        
                         Planillas._V.anulacion_planilla.close();

                         Planillas.fn_load_planilla({'codigo' : Planillas._M.anular_proceso.data.key});
                     }
                  
                 }
                  
          },


          btn_eliminarplanilla_click : function(btn,evt){


              var key = dojo.byId('hdviewplanilla_id').value;
                
                 if(confirm('¿Realmente desea eliminar la planilla?')){ 

                   if(Planillas._M.eliminar_planilla.process({'key' : key})){

                      
                        app.view_load('',{'view' : app.getUrl() + 'planillas/white_view' ,
                                                      'data' : {},
                                                      'fn' : function(){ }} );

                              Planillas._V.registro_de_planillas.load({});
                        // Planillas.fn_load_planilla({'codigo' : Planillas._M.cancelar_proceso.data.key});
                   }

                 }

          },

          btn_afectacion_presupuestal : function(btn,evt){
            
                var codigo = dojo.byId('hdviewplanilla_id').value; 
                Planillas.Cache.view_afectacion = codigo;
                Planillas._V.ver_afectacion_presupuestal.load({'codigo' : codigo});
            
          },
          
          
          btn_ver_resumen : function(btn,evt){
              
                var codigo = dojo.byId('hdviewplanilla_id').value; 
                Planillas._V.ver_resumen_conceptos.load({'codigo' : codigo});
                
          },
          
            
          btn_addempleado_planilla_click : function(btn,evt)
          {
                
                var planilla_key = dojo.byId('hdplanilladetalle_plakey').value;
                
                if(planilla_key != '')
                { 
                    var codigo_e = '';   
                    var selection =  Planillas.Ui.Grids.buscar_trabajador.selection;   
                    
                    for(var i in selection)
                    { 

                        if(selection[i] === true)
                        {
                          codigo_e +='_'+ i;
                        }
                          
                    }

                    if(codigo_e != '')      
                    {
                        if(Planillas._M.agregar_empleado.process({'p_c' : planilla_key, 'e_c' : codigo_e}))
                        {
                             Planillas.Ui.Grids.planillas_detalle.refresh();                      
                        }
                    }
                    else
                    { 
                        alert('Debe seleccionar un registro');
                    }

                }
                else
                {
                    console.log('Falta especificar el key de la planilla');
                }

          },  
         
         
          btn_importacion_op: function(btn,evt){
                 
                 var planilla = dojo.byId('hdviewplanilla_id').value;
                 
                 Planillas._V.Importacion.otra_planilla.load({'planilla' : planilla});
                
          },


          btn_importacion_tareo: function(btn,evt){

                
               Planillas._V.Importacion.tareos.load({});

          },
          

        btn_importacion_hoja: function(btn,evt){

              var planilla = dojo.byId('hdviewplanilla_id').value;
             
               Planillas._V.Importacion.hojaasistencia.load({'planilla' : planilla});

          },

          btn_importar_fop_click : function(btn,evt){

               console.log('Proceder con importacion');
               var data = {}
               data.source =  Planillas.Cache.view_importacion_planilla_select;
               data.target = dojo.byId('hdviewplanilla_id').value;
 
               if(Planillas._M.import_trabajadores_from_pla.process(data)){

                      dijit.byId('dv_vipla_detalle').set('content','');
                      Planillas.Ui.Grids.planillas_detalle.refresh();
               }


          },


         btn_importar_tareo_click : function(btn,evt){

               console.log('Proceder con importacion desde tareo');
               var data = {}
              
               data.tareo =  Planillas.Cache.tareo_seleccionado;
               data.planilla = dojo.byId('hdviewplanilla_id').value; 

               console.log(Planillas.Ui.Grids.tareos.selection);
 
                 if(Planillas._M.import_trabajadores_from_tareo.process(data)){
                         dijit.byId('dv_vipla_detalle').set('content','');
                        Planillas.Ui.Grids.planillas_detalle.refresh();
                 }
 

          },

          btn_importar_hojaasistencia : function(btn,evt){

               var values = {}
               var data = dojo.formToObject('form_importacion_asistencia_config');
               
               for(x in data)
               {
                 values[x] = data[x];
               } 

               var filtro_trabajador = dojo.formToObject('form_importacion_asistencia_filtro_trabajador');
               
               for(x in filtro_trabajador)
               {
                 values[x] = filtro_trabajador[x];
               } 
 
               var codigo_e = '';   
               var selection =   Planillas.Ui.Grids.planillas_preview_importacion.selection;   
                  
                for(var i in selection)
                { 
                    if(selection[i] === true)
                    {
                      codigo_e +='_'+ i;
                    }
                      
                }

                values['trabajadores'] = codigo_e; 
 
                if(codigo_e != '')      
                { 
                    if(confirm('Realmente desea realizar la importacion ? '))
                    { 
                       
                         if(Planillas._M.import_trabajadores_from_hoja.process(values))
                         { 
                              Planillas._V.Importacion.hojaasistencia.close();
                              Planillas.fn_load_planilla(Planillas.Cache.planilla_cargada);
                         } 
                    }
                }
                else
                {
                   alert('Debe seleccionar un registro como minimo');
                }

          },


          btn_devolver_fop_hoja : function(btn,evt){

               var data = {}
               data.hoja = dojo.byId('hdhojaasistencia_viewid').value;
               
               if(confirm('Realmente desea devolver la hoja de asistencia ?')){ 
                 if(Asistencias._M.devolver_hoja_asistencia.process(data)){
                     Planillas._V.Importacion.hojaasistencia.refresh();
                 }
               }


          },

          btn_desvincular_hoja : function(btn,evt){

               // desvincular hoja de asistencia de planilla

               if(confirm('Realmente desea desvincular la hoja de asistencia de la  planilla')){

                   var data = {}
                   data.planilla = dojo.byId('hdviewplanilla_id').value;
                   if(Planillas._M.desvincular_hoja.process(data)){

                        Asistencias._V.view_hoja.close();
                        Planillas.fn_load_planilla(Planillas.Cache.planilla_cargada);
                   }

               }

          },
 
          btn_quitar_detalle_click : function(btn,evt){

             

              var selection = Planillas.Ui.Grids.planillas_detalle.selection;
              
              var codigo_e = '',nr= 0;      
                   
              for(var i in selection)
              { 

                  if(nr==0) codigo_e +=i;
                  nr++;
              }

              if(codigo_e != '')      
              { 
                if(confirm('Realmente desea eliminar el detalle de la Planilla')){ 
                   if(Planillas._M.quitar_empleado.process({'detalle' : codigo_e, 'pla' : dojo.byId('hdviewplanilla_id').value }))
                   {
                      Planillas.Ui.Grids.planillas_detalle.refresh();
                      dijit.byId('dv_vipla_detalle').set('content','');
                   }
                }          
              }
              else{ 
                 alert('Debe seleccionar un registro');
               }
             // Planillas._M.quitar_empleado({'detalle'});

          },

          btn_update_afectacion_detalle_click: function(btn,evt)
          {

              var selection        = Planillas.Ui.Grids.planillas_detalle.selection;
              
              var codigo_e         = '',nr= 0;      
              
              var tiene_categorias = dojo.byId('hdviewplanilla_tienecategorias').value;

              var planilla =  dojo.byId('hdviewplanilla_id').value;  

              for(var i in selection)
              { 

                  if(nr==0) codigo_e +=i;
                  nr++;
              }

              if(codigo_e != '')      
              { 
                  // tiene_categorias
 
                  Planillas._V.actualizacion_afectacion_detalle.load({'view' : codigo_e,
                                                           'categorias' : tiene_categorias,
                                                           'planilla' : planilla });

              }
              else{ 
                 alert('Debe seleccionar un registro');
               }


          },

           btn_quitartodos_detalle_click : function(btn,evt){

             
               if(confirm('Realmente desea quitar todos los trabajadores de la planilla ?')){ 
                   if(Planillas._M.quitar_todos_empleado.process({'pla' : dojo.byId('hdviewplanilla_id').value }))
                   {
                      Planillas.Ui.Grids.planillas_detalle.refresh();
                      dijit.byId('dv_vipla_detalle').set('content','');
                   }
                }          
            
          },


          btn_eliminar_all_conceptos : function(btn,evt){

              if(confirm('Realmente desea quitar el detalle de conceptos y variables de todos los trabajadores')){

                  if( Planillas._M.quitar_detalle_conceptos.process({'pla' : dojo.byId('hdviewplanilla_id').value})  )
                  {
                      dijit.byId('dv_vipla_detalle').set('content','');
                  }

              }

          },
 

          /* btn_deldet_from */

          btn_filtrar_afectacion  : function(btn,evt){

              Planillas.Ui.Grids.planillas_afectacion.refresh();


          },


          btn_set_tipotrabajador : function(btn,evt){

             var data = {}
                data.detalle =  dojo.query('.hdpladet_empkey' , btn.domNode.parentNode)[0].value;
                data.tipo    =  dijit.byId('seldet_tipotrabajador').get('value');

              if(Planillas._M.set_categoria_trabajador.process(data)){

                  Planillas.Ui.Grids.planillas_detalle.refresh();
                  
                  var row_id =  Planillas._M.set_categoria_trabajador.data.key; // verifiacion_key
                  var pla_key = dojo.byId('hdviewplanilla_id').value;
                 
                  Planillas.Cache.detalleplanilla_detalle_id = row_id;
                  Planillas.Cache.detalleplanilla_plakey = pla_key;
              
                  Planillas._M.get_detalleplanilla_variables.send({'codigo' : row_id, 'pla_key' : pla_key});  


              }

          },



          btn_registrarsiaf_click : function(btn,evt){

               var data = {}
               data.planilla = dojo.byId('hdviewplanilla_id').value;
        

               Planillas._V.registrar_siaf.load(data);


          },

          btn_save_siaf_click : function(btn,evt){
            
                 var data = data || {};
                
                 var tr_row=  btn.domNode.parentNode.parentNode;
                 
                 var reg_id = dojo.query('.hdsiafreg',tr_row)[0].value,
                     fuente = dojo.query('.hdsiaffuente',tr_row)[0].value,
                     valor = dijit.byNode(dojo.query('.txtsiaf_siaf',tr_row)[0]).get('value'),
                     keys =  dojo.query('.hdkeys',tr_row)[0].value,
                     view = '',
                     modo =  dojo.query('.hdmodo',tr_row)[0].value;

                  if( dojo.byId('hdviewplanilla_id') != null )
                  {
                     view =  dojo.byId('hdviewplanilla_id').value;
                  }
                

                 if(valor!='' && valor != undefined && valor != NaN){ 

                     dijit.byNode(dojo.query('.txtsiaf_siaf', tr_row )[0]).set('disabled',true);

                     if(Planillas._M.guardar_siaf.process({'reg' : reg_id, 
                                                           'siaf' : valor, 
                                                           'ff' : fuente, 
                                                           'view' : view, 
                                                           'keys' : keys, 
                                                           'modo' : modo }))
                     {
                         
                         dojo.query('.hdsiaf_siaf', tr_row )[0].value= valor;
                         btn.set('disabled',true);
                         
                     }
                     
                     dijit.byNode(dojo.query('.txtsiaf_siaf', tr_row )[0]).set('disabled',false);

                }
                 
                // alert(var_k+ " "+ valor);
             
          },


          btn_actualizarafectacion_planilla : function(){
              

               if(confirm('Realmente desea actualizar la afectacion_presupuestal de la planilla ? '))
               { 
                    var data = dojo.formToObject('form_modificarafectacion_planilla');

                    data.view = dojo.byId('hdviewplanilla_id').value;
                    
                    if(Planillas._M.actualizar_afectacionplanilla.process(data)) 
                    {

                       Planillas._V.modificar_afectacion_planilla.close();

                    }
               }            
          },

          btn_actualizardescripcion_planilla : function(){
              

               if(confirm('Realmente desea actualizar la descripcion de la planilla ? '))
               { 
                    var data = dojo.formToObject('form_update_planilla_descripcion');

                    data.view = dojo.byId('hdviewplanilla_id').value;
                    
                    if(Planillas._M.actualizar_descripcionplanilla.process(data)) 
                    {

                       Planillas._V.modificacion_descripcion.close();

                    }
               }            
          }, 


          mnunew_planilla_click : function(){
                
              Planillas._V.view_nueva_planilla.load({});
              
          },
          
          
          mnuregistro_planilla_click : function(){
              
                  Planillas._V.registro_de_planillas.load({});
              
          } 


           
           /*
          this._$V.view_nueva_planilla.load({ d : '111111'},
                 
                 
                 function(){
                     
                     
                 } 
                
            ); */
          
      },
      
      fn_load_planilla : function(data){  
 
              //console.log(arguments.callee.caller);

                         Planillas.Cache.planilla_cargada = data;
          
                          app.view_load('',{'view' : app.getUrl() + 'planillas/ui_view' ,
                                            'data' : data,
                                            'fn' : function(){
 

                                                       var dims = app.get_dims_body_app(),
                                                           desc_altura = (dims.h > 700) ? (0) : 0;
                                                        
                                                        dijit.byId('viewplanilla_container').resize({w: dims.w ,h: (dims.h - 50 -desc_altura), l: 0, t:0});


                                                        var planilla_procesada = dojo.byId('hdplanillaprocesada').value;  
                                                  

                                                         require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection","dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                                                 function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){

 
                                                                    if( dojo.byId('dvdetalle_planilla') != null )
                                                                    { 

                                                                          dojo.connect(dojo.byId('tdElaborarPlanilla_afectacion'), 'onclick', function(){
 
                                                                                var planilla = dojo.byId('hdviewplanilla_id').value;  
                                                                                Planillas._V.modificar_afectacion_planilla.load({'view' : planilla});

                                                                          });

                                                                          dojo.connect(dojo.byId('tdElaborarPlanilla_descripcion'), 'onclick', function(){
                                                                          
                                                                                var planilla = dojo.byId('hdviewplanilla_id').value;  
                                                                                Planillas._V.modificacion_descripcion.load({'view' : planilla});

                                                                          });

                                                                            


                                                                             if( window.escalafon_grid === null ||  window.escalafon_grid === undefined)  window.escalafon_grid = (declare([Grid, Selection,Keyboard]));


                                                                                          var store_planillas = Observable(Cache(JsonRest({
                                                                                                  target: app.getUrl() +"planillas/provide/detalle", 
                                                                                                  idProperty: "id",
                                                                                                  sortParam: 'oby',
                                                                                                  query: function(query, options){

                                                                                                          var data = {}

                                                                                                          query.view = dojo.byId('hdviewplanilla_id').value;

                                                                                                          if(dojo.byId('form_detalleplanilla_busqueda') != null)
                                                                                                          {

                                                                                                             data = dojo.formToObject('form_detalleplanilla_busqueda');

                                                                                                              for(x in data)
                                                                                                              {
                                                                                                                query[x] = data[x];
                                                                                                              }


                                                                                                             data = dojo.formToObject('form_detalleplanilla_busqueda_op');

                                                                                                              for(x in data)
                                                                                                              {
                                                                                                                query[x] = data[x];
                                                                                                              }

                                                                                                          }

                                                                                                          return JsonRest.prototype.query.call(this, query, options);
                                                                                                  }
                                                                                          }), Memory()));

                                                                                            var colums = {  
                                                                                                      col1: {label:'#'},
                                                                                                      col2: {label: 'DNI', sortable: false},
                                                                                                      col3: {label: 'Ap. Paterno', sortable: false},
                                                                                                      col4: {label: 'Ap. Materno', sortable: false},
                                                                                                      col5: {label: 'Nombres', sortable: false},
                                                                                                      col6: {label: 'Tipo', sortable: false},
                                                                                                      col7: {label: 'Meta-Tarea', sortable: false},
                                                                                                      col8: {label: 'Fuente', sortable: false}
                                                                                               
                                                                                              };



                                                                                          Planillas.Ui.Grids.planillas_detalle  = new  window.escalafon_grid({

                                                                                                  store: store_planillas,
                                                                                                  loadingMessage : 'Cargando',
                                                                                                  getBeforePut: false,
                                                                                                  columns: colums 


                                                                                          }, "dvdetalle_planilla");

                                                                                if( Planillas.Ui.Grids.planillas_detalle != null){
                                                                               
                                                                                   Planillas.Ui.Grids.planillas_detalle.refresh();
                                                                                    
                                                                                 }   

                                                                          }
                                                                          else if(  dojo.byId('dvdetalle_planilla_procesada') != null )
                                                                          {


                                                                                       var store_planillas = Observable(Cache(JsonRest({
                                                                                                        target: app.getUrl() +"planillas/provide/detalle_procesada", 
                                                                                                        idProperty: "id",
                                                                                                        sortParam: 'oby',
                                                                                                        query: function(query, options){

                                                                                                                var data = {}

                                                                                                                query.view = dojo.byId('hdviewplanilla_id').value;

                                                                                                                if(dojo.byId('form_detalleplanilla_busqueda') != null)
                                                                                                                {

                                                                                                                   data = dojo.formToObject('form_detalleplanilla_busqueda');

                                                                                                                    for(x in data)
                                                                                                                    {
                                                                                                                      query[x] = data[x];
                                                                                                                    }

                                                                                                                    
                                                                                                                   data = dojo.formToObject('form_detalleplanilla_busqueda_op');

                                                                                                                    for(x in data)
                                                                                                                    {
                                                                                                                      query[x] = data[x];
                                                                                                                    }

                                                                                                                }

                                                                                                                return JsonRest.prototype.query.call(this, query, options);
                                                                                                        }
                                                                                                }), Memory()));

                                                                                                
                                                                                                if(dojo.byId('hdviewplanilla_tienecategorias').value=='1')
                                                                                                {       

                                                                                                      var colums = { 

                                                                                                         
                                                                                                              col1: {label: '#', sortable : true},
                                                                                                              col2: {label: 'DNI', sortable: false},
                                                                                                              col3: {label: 'Nombre Completo', sortable: false},
                                                                                                              col4: {label: 'Categoria', sortable: false},
                                                                                                              col5: {label: 'Ing.', sortable: false,

                                                                                                                                      renderCell: function(object, value, node, options)
                                                                                                                                                          {
                                                                                                                                                                 if(parseFloat(object.col5) < parseFloat(object.col6) )
                                                                                                                                                                 { 
                                                                                                                                                                   dojo.setStyle(node, 'color', '#990000');
                                                                                                                                                                 }

                                                                                                                                                                 dojo.attr(node, 'innerHTML', value);
                                                                                                                                                          }
 
                                                                                                              },
                                                                                                              col6: {label: 'Desc.', sortable: false,
                                                                                                
                                                                                                                                       renderCell: function(object, value, node, options)
                                                                                                                                                           {
                                                                                                                                                                  if(parseFloat(object.col5) < parseFloat(value) )
                                                                                                                                                                  { 
                                                                                                                                                                    dojo.setStyle(node, 'color', '#990000');
                                                                                                                                                                  }

                                                                                                                                                                  dojo.attr(node, 'innerHTML', value);
                                                                                                                                                           }


                                                                                                              },

                                                                                                              col7: {label: 'Neto.', sortable: false,
                                                                                                
                                                                                                                                       renderCell: function(object, value, node, options)
                                                                                                                                                           {
                                                                                                                                                                  if( parseFloat(value) < 0 )
                                                                                                                                                                  { 
                                                                                                                                                                    dojo.setStyle(node, 'color', '#990000');
                                                                                                                                                                  }

                                                                                                                                                                  dojo.attr(node, 'innerHTML', value);
                                                                                                                                                           }


                                                                                                              },
                                                                                                              col8: {label: 'Aport.', sortable: false},
                                                                                                              col9: {label: 'Gasto', sortable: false},
                                                                                                              col10: {label: 'Meta-Tarea', sortable: false},
                                                                                                              col11: {label: 'Fuente', sortable: false}
   
                                                                                                      };
                                                                                                 }
                                                                                                 else
                                                                                                 {  

                                                                                                      console.log('AQUi STOY');
                                                                                                     var colums = { 

                                                                                                         
                                                                                                               col1: {label: '#', sortable : true},
                                                                                                               col2: {label: 'DNI', sortable: false},
                                                                                                               col3: {label: 'Nombre Completo', sortable: false},
                                                                                                               col5: {label: 'Ing.', sortable: false,

                                                                                                                                      renderCell: function(object, value, node, options)
                                                                                                                                                          {
                                                                                                                                                                 if(parseFloat(object.col5) < parseFloat(object.col6) )
                                                                                                                                                                 { 
                                                                                                                                                                   dojo.setStyle(node, 'color', '#990000');
                                                                                                                                                                 }

                                                                                                                                                                 dojo.attr(node, 'innerHTML', value);
                                                                                                                                                          }

                                                                                                               },
                                                                                                               col6: {label: 'Desc.', sortable: false,
                                                                                                
                                                                                                                                       renderCell: function(object, value, node, options)
                                                                                                                                                           {
                                                                                                                                                                  if(parseFloat(object.col5) < parseFloat(value) )
                                                                                                                                                                  { 
                                                                                                                                                                    dojo.setStyle(node, 'color', '#990000');
                                                                                                                                                                  }

                                                                                                                                                                  dojo.attr(node, 'innerHTML', value);
                                                                                                                                                           }


                                                                                                               },
                                                                                                               col7: {label: 'Neto.', sortable: false,
                                                                                                               
                                                                                                                                        renderCell: function(object, value, node, options)
                                                                                                                                                            {
                                                                                                                                                                   if( parseFloat(value) < 0 )
                                                                                                                                                                   { 
                                                                                                                                                                     dojo.setStyle(node, 'color', '#990000');
                                                                                                                                                                   }

                                                                                                                                                                   dojo.attr(node, 'innerHTML', value);
                                                                                                                                                            }


                                                                                                               },
                                                                                                               col8: {label: 'Aport.', sortable: false},
                                                                                                               col9: {label: 'Gasto', sortable: false},
                                                                                                               col10: {label: 'Meta-Tarea', sortable: false},
                                                                                                               col11: {label: 'Fuente', sortable: false}
                                                                                                     
                                                                                                       };
                                                                                                 }
                                                                                                      
                       
                                                                                       Planillas.Ui.Grids.planillas_detalle = new  (declare([Grid, Selection,Keyboard]))({

                                                                                              store: store_planillas,
                                                                                              loadingMessage : 'Cargando',
                                                                                              allowSelectAll: true,
                                                                                              getBeforePut: true,
                                                                                              columns: colums,
                                                                                              pagingLinks: false,
                                                                                              pagingTextBox: true,
                                                                                              firstLastArrows: true,
                                                                                              rowsPerPage : 25


                                                                                      }, "dvdetalle_planilla_procesada");

                                                                                      if( Planillas.Ui.Grids.planillas_detalle != null){
                                                                                     
                                                                                         Planillas.Ui.Grids.planillas_detalle.refresh();
                                                                                          
                                                                                       }   
                                                                          }       
                                                                      


                                                        });






                                                 require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                                                    function(List, Grid, Selection, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){

                                                               if(dojo.byId('dvtablepla_resumen_ingresos') != null){    
                                                                   
                                                                       
                                                                      (function(){
                                                                          
                                                                            var store = JsonRest({
                                                                                target:app.getUrl() + "planillas/get_resumen_conceptos/ingresos", 
                                                                                idProperty: "id",
                                                                                sortParam: 'oby',
                                                                                query: function(query, options){
                                                                                         
                                                                                        query.view = dojo.byId('hdviewplanilla_id').value;
                                                                                        return JsonRest.prototype.query.call(this, query, options);
                                                                                }
                                                                            });

                                                                            var colums = {  
                                                                                        col1: {label: '#', sortable: false},
                                                                                        col2: {label: 'Concepto', sortable: true},
                                                                                        col3: {label: 'Clasificador', sortable: true},
                                                                                        col4: {label: 'Monto', sortable: true} 

                                                                            };

                                                                            Planillas.Ui.Grids.resumen_ingresos  = new  (declare([Grid, Selection,Keyboard]))({

                                                                                    store: store,
                                                                                    loadingMessage : 'Cargando',
                                                                                    getBeforePut: true,
                                                                                    columns: colums 


                                                                            }, "dvtablepla_resumen_ingresos");
                                                                            
                                                                            if(Planillas.Ui.Grids.resumen_ingresos != null) Planillas.Ui.Grids.resumen_ingresos.refresh();
                                                                          
                                                                      })();
                                                                      
                                                                      
                                                                       

                                                               }        
                                                               
                                                               
                                                               if(dojo.byId('dvtablepla_resumen_descuentos') != null){    

                                                                    
                                                                        (function(){

                                                                           var store = JsonRest({
                                                                                    target:app.getUrl() + "planillas/get_resumen_conceptos/descuentos", 
                                                                                    idProperty: "id",
                                                                                    sortParam: 'oby',
                                                                                    query: function(query, options){

                                                                                            query.view = dojo.byId('hdviewplanilla_id').value;
                                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                                    }
                                                                            });

                                                                            var colums = {  
                                                                                        col1: {label: '#', sortable: false},
                                                                                        col2: {label: 'Concepto', sortable: true},
                                                                                        col3: {label: 'Clasificador', sortable: true},
                                                                                        col4: {label: 'Monto', sortable: true} 

                                                                            };

                                                                            Planillas.Ui.Grids.resumen_descuentos  = new  (declare([Grid, Selection,Keyboard]))({

                                                                                    store: store,
                                                                                    loadingMessage : 'Cargando',
                                                                                    getBeforePut: true,
                                                                                    columns: colums 


                                                                            }, "dvtablepla_resumen_descuentos");

                                                                            if(Planillas.Ui.Grids.resumen_descuentos != null) Planillas.Ui.Grids.resumen_descuentos.refresh();

                                                                        
                                                                    })();
                                                                   
                                                                       

                                                               }        
                                                               
                                                               
                                                               
                                                               if(dojo.byId('dvtablepla_resumen_aportaciones') != null){    
                                                                    
                                                                        
                                                                        (function(){
                                                                            
                                                                          var store = JsonRest({
                                                                                target:app.getUrl() + "planillas/get_resumen_conceptos/aportaciones", 
                                                                                idProperty: "id",
                                                                                sortParam: 'oby',
                                                                                query: function(query, options){

                                                                                        query.view = dojo.byId('hdviewplanilla_id').value;
                                                                                        return JsonRest.prototype.query.call(this, query, options);
                                                                                }
                                                                            });

                                                                            var colums = {  
                                                                                        col1: {label: '#', sortable: false},
                                                                                        col2: {label: 'Concepto', sortable: true},
                                                                                        col3: {label: 'Clasificador', sortable: true},
                                                                                        col4: {label: 'Monto', sortable: true} 

                                                                            };

                                                                            Planillas.Ui.Grids.resumen_aportaciones  = new  (declare([Grid, Selection,Keyboard]))({

                                                                                    store: store,
                                                                                    loadingMessage : 'Cargando',
                                                                                    getBeforePut: true,
                                                                                    columns: colums 


                                                                            }, "dvtablepla_resumen_aportaciones");
                                                                            
                                                                             if(Planillas.Ui.Grids.resumen_aportaciones != null) Planillas.Ui.Grids.resumen_aportaciones.refresh();

                                                                        })();
                                                                        
                                                                       

                                                               }
                                                               
                                                               
                                                               if(dojo.byId('dvtable_recon_trabajadores') != null){
                                                                   
                                                                   
                                                                       (function(){
                                                                            
                                                                              var store = JsonRest({
                                                                                    target:app.getUrl() + "planillas/get_x_concepto_trabajadores", 
                                                                                    idProperty: "id",
                                                                                    sortParam: 'oby',
                                                                                    query: function(query, options){

                                                                                            query.view = dojo.byId('hdviewplanilla_id').value;
                                                                                            query.planilla = Planillas.Cache.view_resumen_conc_pla;
                                                                                            query.concepto = Planillas.Cache.view_resumen_conc_concepto;
                                                                                            
                                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                                    }
                                                                                });

                                                                                var colums = {  
                                                                                            col1: {label: '#', sortable: false},
                                                                                            col2: {label: 'Concepto', sortable: true},
                                                                                            col3: {label: 'DNI', sortable: true}, 
                                                                                            col4: {label: 'Ap. Paterno', sortable: true},
                                                                                            col5: {label: 'Ap. Materno', sortable: true},
                                                                                            col6: {label: 'Nombres', sortable: true},
                                                                                            col7: {label: 'Monto', sortable: true} 

                                                                                };

                                                                                Planillas.Ui.Grids.resumen_conceptos_trabajadores  = new  (declare([Grid, Selection,Keyboard]))({

                                                                                        store: store,
                                                                                        loadingMessage : 'Cargando',
                                                                                        getBeforePut: true,
                                                                                        columns: colums 


                                                                                }, "dvtable_recon_trabajadores");

                                                                             //    if(Planillas.Ui.Grids.resumen_aportaciones != null) Planillas.Ui.Grids.resumen_aportaciones.refresh();

                                                                        })();
                                                                   
                                                                   
                                                               }





                                                                 if(dojo.byId('dvtablepla_resumen_xclasificador') != null){
                                                                   
                                                                   
                                                                       (function(){
                                                                            
                                                                              var store = JsonRest({
                                                                                    target:app.getUrl() + "planillas/get_x_concepto_trabajadores", 
                                                                                    idProperty: "id",
                                                                                    sortParam: 'oby',
                                                                                    query: function(query, options){

                                                                                           
                                                                                            return JsonRest.prototype.query.call(this, query, options);
                                                                                    }
                                                                                });

                                                                                var colums = {  
                                                                                            col1: {label: '#', sortable: true},
                                                                                            col2: {label: 'Clasificador', sortable: false},
                                                                                            col3: {label: 'Monto', sortable: false} 

                                                                                };

                                                                                Planillas.Ui.Grids.resumen_conceptos_xclasificador  = new  (declare([Grid, Selection,Keyboard]))({

                                                                                        store: store,
                                                                                        loadingMessage : 'Cargando',
                                                                                        getBeforePut: true,
                                                                                        columns: colums 


                                                                                }, "dvtablepla_resumen_xclasificador");

                                                                             //    if(Planillas.Ui.Grids.resumen_aportaciones != null) Planillas.Ui.Grids.resumen_aportaciones.refresh();

                                                                        })();
                                                                   
                                                                   
                                                               }
                                                               
                                                               
                                                      });








                                          }
                         });
                 
          
      } 



  
}
 
