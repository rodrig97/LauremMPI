

var Impuestos = {
        
      Cache: {
             
 
      }, 
        
      _M: {

         sunat_seleccionar_planillas : new Laugo.Model({
            connect  : 'impuestos/sunat_planillas/seleccionar'
         }),

         sunat_deseleccionar_planillas : new Laugo.Model({
            connect  : 'impuestos/sunat_planillas/deseleccionar'
         })

      },

      _V: {

            view_resumen_quinta : new Request({
                
                type :  'html',
                
                method: 'post',
                
                url : 'impuestos/view_resumen_quinta',
                
                onRequest : function(){
                     app.loader_show(); 
                },
                
                onSuccess  : function(responseHTML){
                    
                     app.loader_hide(); 
 
                     dijit.byId('impuestoquinta_result').set('content', responseHTML);
                  

                     var myST = new superTable("tableresumenquintacategoria", {
                                    cssSkin : "sDefault",
                                    headerRows : 1,
                                    fixedCols : 1,
                                    colWidths : [],
                                    onStart : function () {
                                   
                                    },
                                    onFinish : function () {
                                      
                                    }
                                  });

                      
                },
                
                onFailure : function(){
                    
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
                        
                        
                      require(["dgrid/List", "dgrid/OnDemandGrid","dgrid/Selection", "dgrid/Selector", "dgrid/editor", "dgrid/Keyboard", "dgrid/extensions/Pagination", "dojo/_base/declare", "dojo/store/JsonRest", "dojo/store/Observable", "dojo/store/Cache", "dojo/store/Memory", "dojo/domReady!"], 
                                  function(List, Grid, Selection, Selector, editor, Keyboard, Pagination, declare, JsonRest, Observable, Cache, Memory){


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



                                                              Planillas.Ui.Grids.planillas_registro  = new  window.escalafon_grid({
                                                                          store: store_planillas,
                                                                          loadingMessage : 'Cargando',
                                                                          selectionMode: "none",
                                                                          columns: colums,
                                                                          allowSelectAll: true


                                                              }, "dvplaregistro_table");
 
                                    }



                      });


                       
                 
                },
                
                onClose: function(){
                    
                //    alert('ventana cerrada');
                     return true;
                }
                
            }),


            view_resumen_trabajador_pdt : new Laugo.View.Window({
                 
                connect : 'impuestos/view_resumen_trabajador_pdt',
                
                style : {
                     width :  '850px',
                     height:  '500px',
                     'background-color'  : '#FFFFFF'
                },
                
                title: ' Resumen PDT- SUNAT ',
                
                onLoad: function(){
                        
                }

              }),



            view_pdtpensionable : new Laugo.View.Window({
                 
                connect : 'impuestos/view_pdtpensionable',
                
                style : {
                     width :  '850px',
                     height:  '500px',
                     'background-color'  : '#FFFFFF'
                },
                
                title: ' Resumen PDT  ',
                
                onLoad: function(){
                        


                        dojo.connect( dijit.byId('selpdtpensionable_regimen'), 'onChange', function(evt){
                              

                               var data = { 'view' : dijit.byId('selpdtpensionable_regimen').get('value') }

                               Impuestos._V.view_pdtpensionable_regimen.send(data);

                            
                        }); 


                        dijit.byId('selpdtpensionable_regimen').onChange();

                }

              }),


              view_pdtpensionable_regimen : new Request({
                  
                  type :  'html',
                  
                  method: 'post',
                  
                  url : 'impuestos/view_pdtpensionable_regimen',
                  
                  onRequest : function(){
                       app.loader_show(); 
                  },
                  
                  onSuccess  : function(responseHTML){
                      
                       app.loader_hide(); 
              
                       dijit.byId('dvpdtpensionable_regimen_container').set('content', responseHTML);
                      

                        
                  },
                  
                  onFailure : function(){
                      
                  } 
                  
              })
            

      },

      Ui : {

            btn_sunat_seleccionarplanilla : function(btn,evt){


                var planillas_k = '';   
                var selection =  Planillas.Ui.Grids.planillas_registro.selection;   
                
                for(var i in selection)
                { 

                    if(selection[i] === true)
                    {
                      planillas_k +='_'+ i;
                    }
                      
                }

                if(planillas_k != '')      
                {
                    if(Impuestos._M.sunat_seleccionar_planillas.process({'planillas' : planillas_k}))
                    {
                        Planillas.Ui.Grids.sunat_planillas_seleccionadas.refresh();     
                        Planillas.Ui.Grids.planillas_registro.refresh();                     
                    } 
                }
                else
                { 
                    alert('Debe seleccionar un registro');
                }

           },


            btn_sunat_deseleccionarplanilla : function(btn,evt){


                var planillas_k = '';   
                var selection = Planillas.Ui.Grids.sunat_planillas_seleccionadas.selection;   
                
                for(var i in selection)
                { 

                    if(selection[i] === true)
                    {
                      planillas_k +='_'+ i;
                    }
                      
                }

                if(planillas_k != '')      
                {
                    if(Impuestos._M.sunat_deseleccionar_planillas.process({'planillas' : planillas_k}))
                    {
                       Planillas.Ui.Grids.sunat_planillas_seleccionadas.refresh();              
                    } 
                }
                else
                { 
                    alert('Debe seleccionar un registro');
                }

           }


      }
}