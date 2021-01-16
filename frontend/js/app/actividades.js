 
var Actividades = {
    
     last_edit_button : null,
     objetivo_current : '',
     get_actividades_env : 0,
     get_actividades_id  : '',
     
    _Models : {
     
     
         new_actividad: new Request({
            
                url: app.getUrl()+'actividades/registrar',
            
                method : 'post',

                onRequest: function(){
                    app.loader_show();
                },
                
                onSuccess: function(responseJSON){
                     app.loader_hide();
                     
                     Actividades.get_actividades( Actividades.get_actividades_id, Actividades.get_actividades_env  );
                     
                },
                
                onFailure: function(){
                     alert('Error on actividades.new_actividad [FAILURE]');
                     app.loader_hide();
                }
            
         }),
         
         rq_actualizar: new Request({
            
               url:  app.getUrl()+'actividades/actualizar',
               
               method: 'post',
               type: 'json',
               onRequest: function(){
                    app.loader_show();
               },
               
               onSuccess: function(responseJSON){
                   // alert('lalal');
                    app.loader_hide();
                   if(responseJSON.result=='1'){
                        Actividades.get_actividades( Actividades.get_actividades_id, Actividades.get_actividades_env  );
                        alert(responseJSON.mensaje);
                   }
                   else{
                       alert(responseJSON.mensaje);
                   }
               },
            
                onFailure: function(){
                     alert('Error on actividades.rq_actualizar [FAILURE]');
                     app.loader_hide();
                }
            
         }),
         
         
         rq_delete : new Request({
             
               url:  app.getUrl()+'actividades/eliminar',
               
               method: 'post',
               type: 'json',
               onRequest: function(){
                    app.loader_show();
               },
               
               onSuccess: function(responseJSON){
                   // alert('lalal');
                    app.loader_hide();
                   if(responseJSON.result=='1'){
                        Actividades.get_actividades( Actividades.get_actividades_id, Actividades.get_actividades_env  );
                        alert(responseJSON.mensaje);
                   }
                   else{
                       alert(responseJSON.mensaje);
                   }
               },
            
                onFailure: function(){
                     alert('Error on actividades.rq_delete [FAILURE]');
                     app.loader_hide();
                }
         }),
            /*
         *  Modelo de la interfaz programar actividades
         */ 
        get_actividades : new Request({
            
            url: app.getUrl()+'actividades/get_actividades',
            
            method : 'post',
            
            onRequest: function(){
                app.loader_show();
            },
            
            onSuccess: function(responseText){
                
                app.loader_hide(); 
                 
                dijit.registry.destroy_childrens( dojo.byId('dv_ppaa_panelcenter') );   
                dijit.byId('dv_ppaa_panelcenter').set('content',responseText);
                dojo.parser.parse(  dojo.byId('dv_ppaa_panelcenter') );
              
              
               if(Actividades.get_actividades_env=='2' || Actividades.get_actividades_env=='3')
                {
                    var html = dojo.byId('dv_data_tarea_taob').innerHTML;     
                    dijit.byId('dv_ppaa_paneltop_sel').set('content',html); 
                }               
              
               var original_values =[];
                
               var meses = ['','Enero: ','Febrero: ','Marzo: ','Abril: ','Mayo: ','Junio: ','Julio: ','Agosto: ','Septiembre: ','Octubre: ','Noviembre: ', 'Diciembre: ']; 
               var inputs = dojo.query('.input_progra1', dojo.byId('tbl_progra_fisica'));

                     dojo.forEach(inputs,function(el,ind){
                            
                             var t = ind + 1;
                             
                              var im = (t<=12) ? t :  ( ((t%12)== 0) ? 12 : (t%12)   );
                              
                             dojo.connect(el,'onkeyup',function(e){
                                   
                                   var txt = dijit.byNode(el).get('value');
                                   
                                    Actividades.calcular_total_row(el.parentNode.parentNode.parentNode);
                                 if(txt != undefined && txt != null && !isNaN(txt) ){  
                                     dijit.showTooltip( meses[im]  +txt,el,['above']);
                                 }
                             });

                             dojo.connect(el,'onmouseleave',function(e){
                                 
                                dijit.hideTooltip(el); 
                             });
 
                             dojo.connect(el,'onmouseenter',function(e){
                                 var txt = dijit.byNode(el).get('value');
                                  if(txt != undefined && txt != null && !isNaN(txt) ){  
                                      dijit.showTooltip( meses[im]  +txt,el,['above']);
                                  }
                             });

                     });
                     
                  var html_dv_inputs = dojo.query('.dvproac_lblcantfi', dojo.byId('tbl_progra_fisica'));    
                     
                     dojo.forEach(html_dv_inputs,function(el,ind){
                         
                          var t = ind + 1;
                          var im = (t<=12) ? t :  ( ((t%12)== 0) ? 12 : (t%12)   );
                          var txt = el.innerHTML;
                             
                                 dojo.connect(el,'onmouseenter',function(e){
                                    
                                    dijit.showTooltip(  meses[im]  +txt ,el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });
                         
                     });
                      
                     
                  if(dojo.byId('tdproac_ObjGeneral') != null )  dojo.attr('tdproac_ObjGeneral','innerHTML', dojo.byId('hdNombreGeneral').value  );   

                 /*
                  *   TOOLTIPS DE AYUDA 
                  */          
                 
                 if(dojo.byId('hd_show_tooltips') == null || dojo.byId('hd_show_tooltips').value == '1'  ){  
                 
                         var tb_edits = dojo.query('.btnproa_edit', dojo.byId('table_prografi')); 
                         var tb_canceledits = dojo.query('.btnproa_canceledit', dojo.byId('table_prografi'));
                         var tb_saves = dojo.query('.btnproa_save', dojo.byId('table_prografi')); 
                         var tb_deletes = dojo.query('.btnproa_delete', dojo.byId('table_prografi')); 
                         var tb_cambiaract = dojo.query('.btnproa_changeobj', dojo.byId('table_prografi')); 
                         var tb_proacts = dojo.query('.btnproa_disaccs', dojo.byId('table_prografi')); 
                         var tb_probs = dojo.query('.btnproa_disbiser', dojo.byId('table_prografi')); 

                          dojo.forEach(tb_edits,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click para editar la programacion fisica<br/> y nombre de la actividad </span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });

                         dojo.forEach(tb_canceledits,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click para CANCELAR la EDICION de la actividad </span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });

                         dojo.forEach(tb_saves,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click para GUARDAR los cambios realizados en la actividad </span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });
                         
                        dojo.forEach(tb_cambiaract,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click para mover esta actividad a otro objetivo especifico</span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });

                        dojo.forEach(tb_deletes,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click para ELIMINAR la actividad </span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });
                         
                          dojo.forEach(tb_proacts,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click, para programar las acciones <br/> a realizar en la actividad. </span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });
                         
                          dojo.forEach(tb_probs,function(el,ind){

                                 dojo.connect(el,'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Click, para programar la relacion de <br/> bienes o servicios a adquirir o contratar </span>',el,['above']);
                                 });

                                 dojo.connect(el,'onmouseleave',function(e){
                                    dijit.hideTooltip(el); 
                                 });

                         });
                        
                         dojo.connect( dojo.byId('txtproac_actnombre'),'onmouseenter',function(e){

                                    dijit.showTooltip('<span style="font-size:12px;"> Digite aqui el nombre de la nueva actividad </span>', dojo.byId('txtproac_actnombre'),['bellow']);
                         });
                 }
                 
                 
                 
            },
            
            onFailure: function(){
                
            }
            
        }),
         
        get_objetivo: new Request({
            
           url:  app.getUrl()+'actividades/view_objetivo_info',
           method: 'post',
           type: 'text',
           onRequest: function(){
                app.loader_show();
           },

           onSuccess: function(responseText){
               // alert('lalal');
                app.loader_hide(); //wd_obj_view
                Actividades.Ui.wd_obj_view = new dijit.Dialog({
                                title: "Trasladar actividad",
                                content:  responseText, 
                                style: "width: 900px; height: 400px; background-color:#FFFFFF;",
                                onCancel : function(){
                                         
                                        dijit.registry.destroy_childrens(  Actividades.Ui.wd_obj_view.domNode );
                                        //if(Actividades.objetivo_current){}
                                         Actividades.get_actividades( Actividades.get_actividades_id, Actividades.get_actividades_env  );
                                }
                            });
                  
                 //  selproac_objesp 
                Actividades.Ui.wd_obj_view.show();
                
                Objetivos.Ui.ready_objetivos_chainselect('selproac_objgen', 'selproac_objesp');
                
                
                
           },

            onFailure: function(){
                 alert('Error on actividades.get_objetivo [FAILURE]');
                 app.loader_hide();
            }
        }),
        
        
        rq_trasladar_acti : new Request({
             
               url:  app.getUrl()+'actividades/trasladar_objetivo',
               
               method: 'post',
               type: 'json',
               onRequest: function(){
                    app.loader_show();
               },
               
               onSuccess: function(responseJSON){
                   // alert('lalal');
                    app.loader_hide();
                   if(responseJSON.result=='1'){
                       
                        Actividades.Ui.wd_obj_view.onCancel();
                        Actividades.get_actividades( Actividades.get_actividades_id, Actividades.get_actividades_env  );
                        alert(responseJSON.mensaje);
                   }
                   else{
                       alert(responseJSON.mensaje);
                   }
               },
            
                onFailure: function(){
                     alert('Error on actividades.rq_delete [FAILURE]');
                     app.loader_hide();
                }
         })
     
    },
     
    Ui : {
        
        wd_obj_view : null,
        //Guardar cambios en la actividad
        btn_save_click : function(btn,evt){
              
              var row_parent =  btn.domNode.parentNode.parentNode.parentNode;
              
              var acti = dojo.query('.hdproa_acti', btn.domNode.parentNode)[0].value; 
              var actfi = dojo.query('.hdproa_actfi', btn.domNode.parentNode)[0].value; 
              
              var n_nombre  =  dijit.byNode(dojo.query('.txteditnombreact',row_parent)[0]).get('value');
              var n_unmed =    dijit.byNode(dojo.query('.seleditunmed',row_parent)[0]).get('value');
              
              var m_input = dojo.query('.input_progra1',row_parent);
              var tipo = dojo.query('.hdtipoacti',row_parent)[0].value;
              
              var meses = '';
              
              var mensaje = '<ul class="ulerror_t1">', err =false;
              var meses_a = ['','Enero: ','Febrero: ','Marzo: ','Abril: ','Mayo: ','Junio: ','Julio: ','Agosto: ','Septiembre: ','Octubre: ','Noviembre: ', 'Diciembre: ']; 
            
            dojo.forEach(m_input, function(m,ind){
                    var v = dijit.byNode(m).get('value');
                   
                   if(v === undefined ||  v === null || v === ''  || isNaN(v) ){
                       mensaje += '<li> La cantidad de '+ meses_a[(ind+1)]+' no es valida</li>';
                       err= true;
                   }
                    meses +='_'+v;
                    
              });
                
              //alert('acti='+acti+' actfi='+actfi+' nombre='+n_nombre+' unidad='+n_unmed+' meses='+meses+ ' tipo = '+tipo);
               
            
            if(dojo.trim(n_nombre)==''){
                  mensaje += '<li>Por favor complete el nombre de la actividad.</li>';
                  err= true;
            }
          
            if(dojo.trim(n_unmed)==''){
                  mensaje += '<li>La unidad de medida seleccionada no es valida, si no encuentra su unidad de medida comuniquese con la Sg de Informatica.</li>';
                  err= true;
            }
             
            mensaje += '</ul>';
             
            if(err){
                
                  var myDialog = new dijit.Dialog({
                            title: "Atenci&oacute;n",
                            content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                            style: "width: 350px"
                        });
                 myDialog.show();
            } 
            else
            {
                 if(confirm('Realmente desea actualizar la actividad?')){
                  
                      Actividades.actualizar(acti,actfi,n_nombre,n_unmed,meses,tipo);
                  
                 };  
                
                   /* 
                  if(confirm("Realmente desea registrar la actividad?")){
                            /*
                       var inputs_meses = dojo.query('.celda1','trproac_newactividad');  
                       
                       dojo.forEach(inputs_meses, function(el,ind){
                             
                            //  alert(el.innerHTML);
                             var caja =  dojo.query('dijit.form.TextBox',el); 
                            alert(caja); 
                           var w =  dijit.registry.findWidgets(el);
                           
                          alert(w);
                           
                       });
                       
                       
                       for(var i = 1; i<=12; i++)
                       {
                           var mes = dijit.byId('txtproac_nmes'+i).get('value');
                           meses += '_'+mes;
                       }  
                        Actividades.registrar(taob,accion_name, acti_tipo, acti_unmed, meses);     
                  } 
                  */
            }
               
        },
        
        //Editar cambios en la actividad
         btn_edit_click : function(btn,evt){
              /*
              dijit.registry.findWidgets(btn.domNode.parentNode)[0].set('visible','none');
              dojo.forEach(dijit.registry.findWidgets(btn.domNode.parentNode),function(el,ind){
                     el.set('disabled',true);
                   
              }); */
              
              Actividades.cancel_last_edit();
              Actividades.last_edit_button = btn;
              
              var row_parent =  btn.domNode.parentNode.parentNode.parentNode;
              var div_parent =  btn.domNode.parentNode.parentNode; 
               
              dojo.style( dojo.query('.dv_act_nombre',div_parent)[0],'display','none');
              dojo.style( dojo.query('.dv_act_editnombre',div_parent)[0],'display','block');
              
              //alert(dojo.query('.hdunmedselect',row_parent)[0].value);
              
               dijit.byNode(dojo.query('.seleditunmed',row_parent)[0] ).set('value',dojo.query('.hdunmedselect',row_parent)[0].value);
              
               dijit.byNode(dojo.query('.txteditnombreact',div_parent)[0]).set('value', dojo.query('.hdnombre_actividad',div_parent)[0].value  );
                
               //dojo.query('.dv_act_editnombre',div_parent)[0]
               
               dojo.style( dojo.query('.btnproa_edit',div_parent)[0],'display','none');
               dojo.style( dojo.query('.btnproa_canceledit',div_parent)[0],'display','inline');
               dojo.style( dojo.query('.btnproa_save',div_parent)[0],'display','inline');
              
               dojo.style( dojo.query('.dvunmed_sel',row_parent)[0],'display','inline');
               dojo.style( dojo.query('.dvunmed_nombre',row_parent)[0],'display','none');
              
              dojo.forEach( dojo.query('.dvinputprogra', row_parent) , function(el,i){
                    
                    var v = dojo.query('.dvproac_lblcantfi',el.parentNode)[0].innerHTML;
                    v = dojo.trim(v);
                    dijit.byNode(dojo.query('.input_progra1',el)[0]).set('value',v);
                    dojo.style(el,'display','block');
                    
              });
             
              dojo.forEach( dojo.query('.dvproac_lblcantfi', row_parent) , function(el,i){
                    dojo.style(el,'display','none');
              });
              
              /*
              var edits = dojo.query('.input_progra1', btn.domNode.parentNode.parentNode.parentNode);
              alert(edits);
              dojo.forEach(edits, function(e,i){
                    dijit.byNode(e).set('disabled',true);
              });
              
              var edit = dojo.query('.btnproa_edit', btn.domNode.parentNode)[0]; 
              dojo.style(edit,'display','none');
              var meac_k = dojo.query('.hdproa_meacid', btn.domNode.parentNode)[0].value; 
             
              alert('im edit '+ meac_k +' onclick');
              */
        },
        
          btn_canceledit_click : function(btn,evt){
            
              var row_parent =  btn.domNode.parentNode.parentNode.parentNode;
              var div_parent =  btn.domNode.parentNode.parentNode; 
               
              dojo.style( dojo.query('.dv_act_nombre',div_parent)[0],'display','block');
              dojo.style( dojo.query('.dv_act_editnombre',div_parent)[0],'display','none');
              
               dojo.style( dojo.query('.btnproa_edit',div_parent)[0],'display','inline');
               dojo.style( dojo.query('.btnproa_canceledit',div_parent)[0],'display','none');
               dojo.style( dojo.query('.btnproa_save',div_parent)[0],'display','none');
              
              
                dojo.style( dojo.query('.dvunmed_sel',row_parent)[0],'display','none');
               dojo.style( dojo.query('.dvunmed_nombre',row_parent)[0],'display','inline');
              
              dojo.forEach( dojo.query('.dvinputprogra', row_parent) , function(el,i){
                    dojo.style(el,'display','none');
              });
             
              dojo.forEach( dojo.query('.dvproac_lblcantfi', row_parent) , function(el,i){
                    dojo.style(el,'display','block');
              });
              
             
        },
        
         // Eliminar una actividad
          btn_delete_click : function(btn,evt){
              
              Actividades.cancel_last_edit();
              var parent = btn.domNode.parentNode;
              
              var acti_id = dojo.query('.hdproa_acti', parent )[0].value; 
              
              var n_accs= dojo.query('.hdproa_numactis', parent )[0].value;
              var n_bs= dojo.query('.hdproa_numbs', parent )[0].value; 
              
              var mensaje = (parseInt(n_accs)>0 || parseInt(n_bs)>0 ) ? 'La actividad tiene ACCIONES o ITEMS vinculados, realmente desea ELIMINAR la actividad?' : 'Realmente desea eliminar la actividad ?';
              
              if(confirm(mensaje)){  
                  
                    Actividades.eliminar(acti_id);
                
              }
              //alert('im del '+ meac_k +' onclick');
        },
        
        //mostrar la ventana de acciones de las actividades
        btn_displayacciones_click: function(btn,evt){
            
              Actividades.cancel_last_edit();
              var actividad = dojo.query('.hdproa_meacid', btn.domNode.parentNode)[0].value; 
              Acciones.display_acciones(actividad);
              //alert('im acciones '+ meac_k +' onclick');
        },
        
        // mostrar la ventana de bienes/servicios de las actividades
        btn_displaybiser_click: function(btn,evt){
            
               Actividades.cancel_last_edit(); 
              var actividad = dojo.query('.hdproa_meacid', btn.domNode.parentNode)[0].value; 
               Items.display_items(actividad);
             // alert('im biser  '+ meac_k +' onclick');
        },
        
        // registrar una nueva actividad
        btn_regacti_click: function(btn,evt){
            
            Actividades.cancel_last_edit();
             
            var row_parent = btn.domNode.parentNode.parentNode.parentNode;
            
            var taob =  dojo.query('.hdKeyTaOb_current', row_parent)[0].value;
            var accion_name =  dijit.byNode(dojo.query('.txtproac_actnombre', row_parent)[0]).get('value');
            var acti_tipo =   dijit.byNode(dojo.query('.selproac_actipo', row_parent)[0]).get('value'); 
            var acti_unmed =dijit.byNode(dojo.query('.selproac_actunmed', row_parent)[0]).get('value'); 
            var m_input = dojo.query('.input_progra1',row_parent);   
            var mensaje = '<ul class="ulerror_t1">', err =false;
            var meses = '';
            var meses_a = ['','Enero: ','Febrero: ','Marzo: ','Abril: ','Mayo: ','Junio: ','Julio: ','Agosto: ','Septiembre: ','Octubre: ','Noviembre: ', 'Diciembre: ']; 
            
             dojo.forEach(m_input, function(m,ind){
                    var v = dijit.byNode(m).get('value');
                   
                   if(v === undefined ||  v === null || v === ''  || isNaN(v) ){
                       mensaje += '<li> La cantidad de '+ meses_a[(ind+1)]+' no es valida</li>';
                       err= true;
                   }
                    meses +='_'+v;
                    
              });
         
            
            if(dojo.trim(accion_name)==''){
                  mensaje += '<li>Por favor complete el nombre de la actividad.</li>';
                  err= true;
            }
          
            if(dojo.trim(acti_unmed)==''){
                  mensaje += '<li>La unidad de medida seleccionada no es valida, si no encuentra su unidad de medida comuniquese con la Sg de Informatica.</li>';
                  err= true;
            }
             
            mensaje += '</ul>';
             
            if(err){
                
                  var myDialog = new dijit.Dialog({
                            title: "Atenci&oacute;n",
                            content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                            style: "width: 350px"
                        });
                 myDialog.show();
            } 
            else
            {
                    
                  if(confirm("Realmente desea registrar la actividad?")){
                            /*
                       var inputs_meses = dojo.query('.celda1','trproac_newactividad');  
                       
                       dojo.forEach(inputs_meses, function(el,ind){
                             
                            //  alert(el.innerHTML);
                             var caja =  dojo.query('dijit.form.TextBox',el); 
                            alert(caja); 
                           var w =  dijit.registry.findWidgets(el);
                           
                          alert(w);
                           
                       });
                       */
                       
                     
                        Actividades.registrar(taob,accion_name, acti_tipo, acti_unmed, meses);     
                  } 
            }
            //alert(acti_unmed);
        },
        
        
        btn_changeobj_click: function(btn,evt){
            
              Actividades.cancel_last_edit();
             var actividad = dojo.query('.hdproa_acti', btn.domNode.parentNode)[0].value; 
             Actividades.get_objetivo_info(actividad);
            // alert('Mover la actividad a otro objetivo');
        },
        
        btn_trasladaract_click: function(btn,evt){
             
             var actividad = dojo.query('.hdactobj_actik', btn.domNode.parentNode)[0].value;
             var tarea = dojo.query('.hdactobj_tareak', btn.domNode.parentNode)[0].value;
             var obj = dijit.byId('selproac_objesp').get('value');
             
             if( dojo.trim(obj)!= ''){ 
                 if(confirm('Realmente desea cambiar el objetivo al cual pertenece la actividad? ')){ 
                    Actividades.cambiar_objetivo(actividad, obj, tarea);
                 }

             }
             else{
                 alert('Verifique el objetivo especifico destino');
             }
        }
        
    },
    
    
    cancel_last_edit : function(){
         
          if( Actividades.last_edit_button != null && Actividades.last_edit_button.domNode != null ){ 
              
              var div_cancel_button =  Actividades.last_edit_button.domNode.parentNode.parentNode; 
              
              dijit.byNode(dojo.query('.btnproa_canceledit',div_cancel_button)[0]).onClick();
              //dojo.query('.btnproa_canceledit',div_cancel_button)[0].onClick(); 
              
          }
          
    },
    
    registrar: function(taob,nombre, tipo, unidad, meses){
        
         this._Models.new_actividad.send({
                   'taob' : taob,
                   'ajax' : '1',
                   'nombre' : nombre,
                   'tipo' : tipo,
                   'unidad' : unidad,
                   'meses' : meses
             
         });
    },
    
    get_actividades: function(obj,tipo){
            
          this.get_actividades_env = tipo;
          this.get_actividades_id = obj;
          // TIPO 1, get_actividades solo area, 2: get actividades ver programada click.
          
          this._Models.get_actividades.send( {'ajax' : '1', 'objmeta' : obj,'tipo' : tipo});
     },
     
     get_objetivo_info : function(acti){
         this._Models.get_objetivo.send( {'ajax' : '1', 'acti' : acti});  
     },
     
     actualizar: function(acti,actfi,n_nombre,n_unmed,meses,tipo){
      
          this._Models.rq_actualizar.send({
              
                'acti' : acti,
                'actfi' : actfi,
                'n_nombre' : n_nombre,
                'n_unmed' : n_unmed,
                'meses' : meses,
                'tipo' : tipo
          });  
            
     },
     
     
     eliminar : function(del){
         
         this._Models.rq_delete.send({'acti' : del});
         
     },
     
     
     calcular_total_row: function(row){
          
          var inputs = dojo.query('.input_progra1',row);
          
          var total_c = 0;
         
          dojo.forEach(inputs,function(input,ind){
                
                 var t = dijit.byNode(input).get('value');
                 
                 total_c+=t;
                  
          });
       
          dojo.query('.tdTotalAct',row)[0].innerHTML= total_c;
          
    },
    
    
    cambiar_objetivo : function(acti,obj,tarea){
          
          Actividades._Models.rq_trasladar_acti.send({'tarea' : tarea, 
                                                       'acti' : acti, 
                                                       'obje' : obj});
          
    }
    
    
    
     
}

 