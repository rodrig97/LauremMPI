 
 /*Interfaz de Usuario acciones/eventos */ 


 Persona.Ui = {
        
         wd_new_persona : null,
         
         
         Grids: {},
         
       //  Persona.Ui.Forms.info_persona.ready();
         Forms: {
             
             trabajador_info : new Laugo.Form({ 
                 
                  form_id : 'form_info_personal',
                  
                  onReady: function(entorno){
                      
                           if(dojo.byId('wdnuevo_viewcontainer')!= null) dojo.setStyle(dojo.byId('wdnuevo_viewcontainer'), 'top', '0px');
                           if(dojo.byId('wdnuevo_viewcontainer')!= null) dojo.attr(dojo.byId('wdnuevo_viewcontainer'), 'scrollTop', '0');


                             if(dojo.byId('fip_selhasruc')!= null){    
                                 dojo.connect(  dijit.byId('fip_selhasruc'),'onChange',function(e){
                                        var parent = dijit.byId('fip_selhasruc').domNode.parentNode;
                                           if(dijit.byId('fip_selhasruc').get('value')=='1'){

                                                dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');

                                                var dni_v = dojo.trim(dijit.byId('fip_txtdni').get('value'));
                                                var ruc_v = '10'+dni_v+'6';
                                                dijit.byId('fip_txtruc').set('value', ruc_v);

                                           }else{

                                               dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                           }
                                  }); 
                             }


                          if(dojo.byId('fip_selhaslib')!= null){    

                                  dojo.connect(  dijit.byId('fip_selhaslib'),'onChange',function(e){
                                           var parent = dijit.byId('fip_selhaslib').domNode.parentNode;
                                           if(dijit.byId('fip_selhaslib').get('value')=='1'){

                                                dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
                                               if(entorno!=2)  dijit.byId('fip_txtlibtip').set('value','0');
                                               if(entorno!=2)  dijit.byId('fip_txtlibcod').set('value','');

                                           }else{

                                               dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                           }
                                  }); 

                          }

                           if(dojo.byId('fip_hasbrevete')!= null){    

                              dojo.connect(  dijit.byId('fip_hasbrevete'),'onChange',function(e){
                                       var parent = dijit.byId('fip_hasbrevete').domNode.parentNode;
                                       if(dijit.byId('fip_hasbrevete').get('value')=='1'){

                                            dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
                                            if(entorno!=2)  dijit.byId('fip_tipobrevete').set('value','0');
                                            if(entorno!=2)   dijit.byId('fip_codbrevete').set('value','');

                                       }else{

                                           dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                       }
                              }); 
                           }


                         /*   if(dojo.byId('fip_hasessalud')!= null){ 
                               dojo.connect(  dijit.byId('fip_hasessalud'),'onChange',function(e){
                                       var parent = dijit.byId('fip_hasessalud').domNode.parentNode;
                                       if(dijit.byId('fip_hasessalud').get('value')=='1'){

                                            dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');

                                             if(entorno!=2) dijit.byId('fip_essaludcod').set('value','');

                                       }else{

                                           dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                       }
                              }); 
                            }*/

                          if(dojo.byId('fip_hascbanco')!= null){  

                                  dojo.connect(  dijit.byId('fip_hascbanco'),'onChange',function(e){
                                           var parent = dijit.byId('fip_hascbanco').domNode.parentNode;
                                           if(dijit.byId('fip_hascbanco').get('value')=='1'){

                                                dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
                                                if(entorno!=2) dijit.byId('fip_bancocod').set('value','');
                                                 if(entorno!=2) dijit.byId('fip_banco').set('value','0');

                                           }else{

                                               dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                           }
                                  }); 

                          }

                      /*   if(dojo.byId('fip_haspension')!= null){  
                               dojo.connect(  dijit.byId('fip_haspension'),'onChange',function(e){
                                       var parent = dijit.byId('fip_haspension').domNode.parentNode;
                                       if(dijit.byId('fip_haspension').get('value')=='1'){

                                            dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'inline');
                                             if(entorno!=2) dijit.byId('fip_codpension').set('value','');
                                              if(entorno!=2) dijit.byId('fip_afp').set('value','0');

                                       }else{

                                           dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                       }
                              }); 
                         }*/

                          if(dojo.byId('fip_tipopension')!= null){  
                              dojo.connect(  dijit.byId('fip_tipopension'),'onChange',function(e){
                                       var parent = dijit.byId('fip_tipopension').domNode.parentNode;
                                       if(dijit.byId('fip_tipopension').get('value')=='2'){

                                            dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'block');
                                              if(entorno!=2) dijit.byId('fip_afp').set('value','0');
                                       }else{

                                           dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');
                                       }
                              }); 
                          }

                          if(dojo.byId('txtemp_fono')!= null){   
                                dojo.connect(  dijit.byId('txtemp_fono'), 'onKeyDown', function(e){


                                        if( e.keyCode==8  || $_keycode_isnumber(e.keyCode)   )
                                        {

                                        }
                                        else{

                                            e.preventDefault();  
                                        }


                                });
                          }

                          if(dojo.byId('txtemp_celu')!= null){   
                                dojo.connect(  dijit.byId('txtemp_celu'), 'onKeyDown', function(e){


                                        if( e.keyCode==8  || $_keycode_isnumber(e.keyCode)   )
                                        {

                                        }
                                        else{

                                            e.preventDefault();  
                                        }


                                });
                          } 

                          if(dojo.byId('selper_tipopension')!= null){       
                              dojo.connect(  dijit.byId('selper_tipopension'),'onChange',function(e){
                                               var parent = dijit.byId('selper_tipopension').domNode.parentNode;

                                               if(dijit.byId('selper_tipopension').get('value')=='2'){

                                                   dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'block');



                                               }else{

                                                 dojo.setStyle(dojo.query('.containerItems',parent)[0],'display', 'none');


                                               }
                              }); 
                          }


                          $_chain_inputs(['fip_situlaboral','fip_txtnombres','fip_txtpaterno','fip_txtmaterno','fip_selsexo','fip_txtciudad','fip_selnacionalidad',
                                          'fip_fechanac','fip_txtdni','fip_selhasruc','fip_selhaslib','fip_hasbrevete','fip_direccion1','fip_direccion2','fip_fono',
                                          'fip_celular','fip_email', 'fip_hascbanco']);


                           var calendars = ['calcomidesde','calcomihasta',
                                            'caldescansodesde','caldescansohasta',
                                            'callicedesde','callicehasta',
                                            'calPermdesde',
                                            'calvacadesde','calvacahasta',
                                            'calfalta_desde','calfalta_hasta',
                                            'caltard_dia',
                                            'calacadesde','calacahasta','calacafecha'];
                                         
                                         
                            var fecha = $_currentDate();
                             
                            dojo.forEach(calendars, function(cal,ind){
                                
                                if(dijit.byId(cal) != null) dijit.byId(cal).set('value',  fecha   );
                               
                            });


                  }
                 
                 
             })
              
         },
         
        
         ready_new_licencia:  function(){
              
              
              
              if(dojo.byId('sellicencia_motivo') != null ){
                  
                      
                       dojo.connect(  dijit.byId('sellicencia_motivo'),'onChange',function(e){
                            
                                       if(dijit.byId('sellicencia_motivo').get('value')=='2'){

                                            dojo.setStyle(dojo.byId('txtlicenciamotivo_des'),'display', 'inline');
 

                                       }else{

                                           dojo.setStyle(dojo.byId('txtlicenciamotivo_des'),'display', 'none');
                                       }
                       }); 
                  
                  
                  
              }
             
             
         },
        
         form_espddni_reset: function(){
           
             dijit.byId('txtdni_especificar').set('value','');
             dojo.byId('dvinfopers_container').innerHTML = '';
           
         },
          
         btn_buscardni_click: function(btn,e){
           
             
             if(dojo.byId('txtdni_especificar')!= null){ 
                  
                var dni =  dijit.byId('txtdni_especificar').get('value');
                
                if($_comprobar_dni(dni))
                {
                    var q =  Persona.consultar_dni({'dni': dni});
                     
                    if(dojo.byId('dvinfopers_container') != null){ 
                       dojo.byId('dvinfopers_container').innerHTML = q.data.html;
                       
                       
                       dojo.parser.parse( dojo.byId('dvinfopers_container'));
                     /*  alert(dijit.focus);
                       dijit.focus(dijit.byId('btndni_buscar').domNode);
                       dijit.byId('btndni_buscar').domNode.focus();*/
                       
                    }else{
                         Console.log('ID: dvinfopers_container no encontrado');
                    }
                }
                else{
                    alert('El DNI especificado no es valido');
                }
                
                
             }else{
                  Console.log('ID: txtdni_especificar no encontrado');
             }
           
         },
      
         //Boton registrar nueva persona  de la vista especifique DNI
         btn_showregister_click: function(btn,e){
             
              
               var parent = btn.domNode.ParentNode; 
               
               var dni = dojo.query('.hddata_container',parent)[0].value;
               dni  = (dni != null) ? dojo.trim(dni) : '';
               
               Persona._V.registrar_nuevo.load({'dni' : dni});
               
               if( dijit.byId('dvescala_especidni') != null){ 
                    Persona.Ui.form_espddni_reset();
               }
           
         },
         
         //boton mostrar form de informacion completa
         btn_showinffullper_click : function(btn,e){
               
              // alert(btn.domNode.parentNode);
               var empk =  dojo.query('.hddata_container', btn.domNode.parentNode)[0].value;
               // alert(empk);
               Persona._V.full_info_persona.load({'empkey' : empk});
                
               if( dijit.byId('dvescala_especidni') != null){ 
                    Persona.Ui.form_espddni_reset();
               }
                
         },
         
          
         /*betas*/ 
         
         
         btn_fipeditar_click: function(btn,e){
            
              dojo.setStyle(  dojo.query('.btncanceledicion', btn.domNode.parentNode)[0],'display','inline' );
              dojo.setStyle(  dojo.query('.btnguardar', btn.domNode.parentNode)[0],'display','inline' );
              dojo.setStyle(  btn.domNode ,'display','none' );
              Persona.Ui.Forms.trabajador_info.enabled();
                 
         },
         
         
         btn_fipcanceledit_click : function(btn,e){
                
              dojo.setStyle(  dojo.query('.btneditar', btn.domNode.parentNode)[0],'display','inline' );
              dojo.setStyle(  dojo.query('.btnguardar', btn.domNode.parentNode)[0],'display','none' );
              dojo.setStyle(  btn.domNode ,'display','none' ); 
                
               Persona.Ui.Forms.trabajador_info.restore();
               Persona.Ui.Forms.trabajador_info.disabled();
              
         },
         
         //vtb actualizar info
         btn_fipactualizar_click: function(btn,e){
              
              
              var u_k = dojo.query('.hduserkey', btn.domNode.parentNode)[0].value;
              var data = {}
            
             
              data = Persona.Ui.Forms.trabajador_info.get_data();  // dojo.formToObject('form_info_nuevo');

              for( var  field  in data){data[field] = dojo.trim(data[field]);}

              data.empkey = u_k;

              var err =false;
             
             var mensaje = '<ul>';
              
             if(data.nombres==''){
                 err = true;
                 
                 mensaje += '<li>El campo "Nombres" es obligatorio</li>'; 
                 
             } 
             
             if(data.paterno==''){
                 err = true;
                 
                 mensaje += '<li>El campo "Paterno" es obligatorio</li>'; 
                 
             }
             
             if(data.materno==''){
                 err = true;
                 
                 mensaje += '<li>El campo "Materno" es obligatorio</li>'; 
                 
             }
             
             if(data.sexo== 0){
                 err = true;
                 
                 mensaje += '<li>Debe especificar un valor en elcampo "Sexo" es obligatorio</li>'; 
                 
             }
             
             if( data.departamento == 0 || data.provincia == 0  || data.ciudad == 0  ){
                 err = true;
                 
                 mensaje += '<li> El lugar de origen no esta especificado.</li>'; 
                 
             }
             
              if( data.dni == '' || data.dni.length != 8 ){
                 err = true;
                 
                 mensaje += '<li> El DNI es invalido.</li>'; 
                 
             } 
             
     
             if( data.hasruc == 1 && data.ruc.length != 11   ){
                 err = true;
                 
                 mensaje += '<li> El RUC es invalido.</li>'; 
                 
             } 
             
             if( data.haslibreta == 1 && ( data.codlibreta.length == 0 || data.tipolibreta == 0 ) ){
                 
                 err = true;
                 mensaje += '<li> Verifique los datos de la libreta militar.</li>'; 
                 
             } 
             
             if( data.direccion1 == '' && data.direccion2 == '' ){
                 
                 err = true;
                 mensaje += '<li> Debe especificar por lo menos una direccion.</li>'; 
                 
             } 
             
             
            /* if( data.hasessalud == 1 && ( data.essaludcod.length == 0 ) ){
                 
                 err = true;
                 mensaje += '<li> Espec&iacuote;fique el codigo de ESSALUD.</li>'; 
                 
             } */
             
             if( data.hascbanco == 1 && ( data.bancocod.length == 0 || data.banco == 0 ) ){
                 
                 err = true;
                 mensaje += '<li> Verifique los datos de la cuenta de bancaria.</li>'; 
                 
             } 
             
          
             if(  data.tipopension == 2 && ( data.afp == 0 ||  data.codpension.length == 0 ) )
             { 
              
                 err = true;
                 mensaje += '<li> Verifique los datos del sistema de pensiones.</li>'; 
             }
          

             
             if( data.tipopension == '0'  ){
                 
                 err = true;
                 mensaje += '<li> Tiene que especificar un tipo de pensión.</li>'; 
                 
             } 
             

            mensaje +='</ul>';

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
                 if(confirm('Realmente desea actualizar la informacion del trabajador?')){
                     
              
                          var rs =  Persona.actualizar_info(data, true);

                          if(rs.result){

                                Persona._V.full_info_persona.refresh();

                          }else{

                           }
                }
             
            }
             // Persona._V. 
              
         },
          
          
         //btn registrar persona
         btn_registrar_persona : function(btn,e){
             // alert('OH SI');
               
             // console.log('resullt: '+ Persona.registrar({'nombre' : 'giordhano'}));
             var data = {}
             
             data = Persona.Ui.Forms.trabajador_info.get_data();  // dojo.formToObject('form_info_nuevo');
             
            // this.Ui.Memory.form_info_persona = data;
             
             for( var  field  in data)
             {
                 data[field] = dojo.trim(data[field]); 
             }

             /*
             for( var  field  in data){
                 
                 data[field] = dojo.trim(data[field]); 
                alert( field + ' : '+data[field]);
 
             }
             */
             var err =false;
             
             var mensaje = '<ul>';
             


             if( data.tipoindividuo == app._consts.tipoindividuo_trabajador  && data.situlaboral =='0' )
             {
                 err = true;
                 
                 mensaje += '<li>Por favor especifique la situacion laboral actual</li>'; 
                 
             } 


             if( data.tipoindividuo == app._consts.tipoindividuo_trabajador && (data.fechaini=='' || data.fechaini==null ))
             {
                 err = true;
                 
                 mensaje += '<li>Por favor especifique la fecha de inicio del contrato </li>';
                 
             }
             
             if(data.nombres==''){
                 err = true;
                 
                 mensaje += '<li>El campo "Nombres" es obligatorio</li>'; 
                 
             } 
             
             if(data.paterno==''){
                 err = true;
                 
                 mensaje += '<li>El campo "Paterno" es obligatorio</li>'; 
                 
             }
             
             if(data.materno==''){
                 err = true;
                 
                 mensaje += '<li>El campo "Materno" es obligatorio</li>'; 
                 
             }
             
             if(data.sexo== 0)
             {
                 err = true;
                 
                 mensaje += '<li>Debe especificar un valor en elcampo "Sexo" es obligatorio</li>'; 
                 
             }
             
             if( data.tipoindividuo == app._consts.tipoindividuo_trabajador && ( data.departamento == 0 || data.provincia == 0  || data.ciudad == 0  ) ){
                 err = true;
                 
                 mensaje += '<li> El lugar de origen no esta especificado.</li>'; 
                 
             }
             
              if( data.dni == '' || data.dni.length != 8 ){
                 err = true;
                 
                 mensaje += '<li> El DNI es invalido.</li>'; 
                 
             } 
             
     
             if( data.hasruc == 1 && data.ruc.length != 11   ){
                 err = true;
                 
                 mensaje += '<li> El RUC es invalido.</li>'; 
                 
             } 
             
             if( data.tipoindividuo == app._consts.tipoindividuo_trabajador && data.haslibreta == 1 && ( data.codlibreta.length == 0 || data.tipolibreta == 0 ) ){
                 
                 err = true;
                 mensaje += '<li> Verifique los datos de la libreta militar.</li>'; 
                 
             } 
             
             if( data.tipoindividuo == app._consts.tipoindividuo_trabajador && data.direccion1 == '' && data.direccion2 == '' ){
                 
                 err = true;
                 mensaje += '<li> Debe especificar por lo menos una direccion.</li>'; 
                 
             } 
             
         
             if( data.hascbanco == 1 && ( data.bancocod.length == 0 || data.banco == 0 ) ){
                 
                 err = true;
                 mensaje += '<li> Verifique los datos de la cuenta de bancaria.</li>'; 
                 
             } 

             
             if( data.tipoindividuo == app._consts.tipoindividuo_trabajador   )
             {
            
                 if( data.tipopension == 2  && ( data.afp == 0 || data.codpension.length == 0 ) )
                 { 
                  
                     err = true;
                     mensaje += '<li> Verifique los datos del sistema de pensiones.</li>'; 
                 }
             }  
              
             mensaje +='</ul>';
              
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
             
                 if(confirm('Realmente desea proceder con el registro?'))
                 {
                      var rs =  Persona.registrar(data);
                      
                      if(rs)
                      {
                         
                          if( Persona._M.registrar.data.key != null)
                          {
                           
                                if(dojo.byId('hdnuevoindiv_tipo').value == app._consts.tipoindividuo_trabajador ){ 
                                     Persona._V.registrar_nuevo.close();   
                                     if(dojo.byId('hdpermiso_gestionrapida') === null){      
                                        Persona._V.full_info_persona.load({'empkey': Persona._M.registrar.data.key});
                                     }
                                     else
                                     {
                                       if(dojo.byId('hdpermiso_gestionrapida').value=='1') Persona._V.gestion_rapida_deconceptos.load({'empkey': Persona._M.registrar.data.key});
                                     }     
                                     //
                                }
                                else if(dojo.byId('hdnuevoindiv_tipo').value == app._consts.tipoindividuo_beneficiario ){ 

                                   
                                      dijit.byId('selvb_persona').set('value', Persona._M.registrar.data.key);   
                                      Persona._V.registrar_nuevo.close();

                                }
                                else{

                                }

                          }
                          else{
                               
                          } 
                      }
                      else{
                        
                      }
                     
                 }
            }
          
         },

 
         
         
         
        // Btn registrar historia

        /*
         */
         btn_historiaregister_click : function(btn,e){
               
              // alert(btn.domNode.parentNode);
               var empk =  dojo.query('.hduserkey', btn.domNode.parentNode)[0].value;
               
               var data = dojo.formToObject('form_info_historial');
             
                for(f in data){
                 
                  data[f] = dojo.trim(data[f]);
                 
                   //alert(f+' : '+data[f]);
                }
                 
                //data.proyecto_label = dojo.trim(dijit.byId('selhis_proyecto').get('displayedValue'));
 
                if(data.ocupacion == '')
                {
                   data.ocupacion_label =  dojo.trim(dijit.byId('selhis_cargo').get('displayedValue'));
                }
                 
                if(data.situlaboral==0)
                {
                     
                     alert('Especifique una situacion laboral');
                    
                }
                else{
                     
                         var mensaje = '<ul>';
                         var err = false;
                         
                         if(data.fechaini=='' || data.fechatermino ==''){
                             err = true;
                             mensaje += '<li>Verifique el Intervalo de fechas especificado.</li>'; 
                         }  
                               
 
                         if(data.sisgedo_doc == '' || data.sisgedo_doc == undefined  ){   

                         
                           if(  data.autoriza == '' || data.documento  == ''    ){
                               err = true;

                               mensaje += '<li>La informacion del documento que autoriza esta incompleta</li>'; 

                           } 
                         } 



                         if( data.situlaboral == 9 && document.getElementById('chcarnet_presento') != null){

                             
                             if (data.carnet_presento == '1') {
                         
                                if(  data.fechacarnet_desde == '' || data.fechacarnet_desde  == null  || data.fechacarnet_hasta == '' || data.fechacarnet_hasta  == null    ){
                                    err = true;

                                    mensaje += '<li> Verifique el periodo de vigencia del carnet </li>'; 

                                } 

                             } 
                              
                         }
                         
                         /* 
                         if( data.situlaboral != app._consts.sitlab_construccion && data.situlaboral != app._consts.sitlab_pensionistas  &&  (data.dependencia == '' || data.dependencia == '0') ){

                            err = true;
                            mensaje+= " <li>Debe especificar la dependencia de trabajo</li> ";
                         }*/

                         if(data.dependencia == ''){

                            err = true;
                            mensaje+= " <li> Verifique el area de trabajo. </li> ";

                         }


 

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
                            
                              if(confirm('Realmente desea proceder con el registro?')){

                                     var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                                     data.empkey = empkey;


                                     if( Persona._M.registrar_situlaboral.process(data) ){

                                          Persona._V.nueva_situlaboral.close();  
                                          Persona.Ui.Grids.historial_laboral.refresh();


                                     }
                                    
                              }

                        }
                    
                    
                    
                    
                }
                
         },
         


         btn_historia_actualizar_click : function(btn,e){
            

                var data = dojo.formToObject('form_info_historial');
             
                for(f in data){
                 
                    data[f] = dojo.trim(data[f]);
                }
                 
               // data.proyecto_label = dojo.trim(dijit.byId('selhis_proyecto').get('displayedValue'));

                if(data.ocupacion == '')
                {
                   data.ocupacion_label =  dojo.trim(dijit.byId('selhis_cargo').get('displayedValue'));
                }
                 
                if(data.situlaboral==0){
                     
                     alert('Especifique una situacion laboral');
                    
                }
                else{
                     
                         var mensaje = '<ul>';
                         var err = false;
                         
                         if(data.fechaini=='' || data.fechaini=='fechafin'){
                             err = true;

                             mensaje += '<li>Verifique el Intervalo de fechas especificado.</li>'; 

                         }  
                               
 
                         if(data.sisgedo_doc == '' || data.sisgedo_doc == undefined  ){   

                         
                           if(  data.autoriza == '' || data.documento  == ''    ){
                               err = true;

                               mensaje += '<li>La informacion del documento que autoriza esta incompleta</li>'; 

                           } 
                         } 


                         if( data.situlaboral == 9 && document.getElementById('chcarnet_presento') != null){

                             
                             if (data.carnet_presento == '1') {
 
                                if(  data.fechacarnet_desde == '' || data.fechacarnet_desde  == null  || data.fechacarnet_hasta == '' || data.fechacarnet_hasta  == null    ){
                                    err = true;

                                    mensaje += '<li> Verifique el periodo de vigencia del carnet </li>'; 

                                } 

                             } 
                              
                         }
                          
                          /*
                         if( data.situlaboral != app._consts.sitlab_construccion && data.situlaboral != app._consts.sitlab_pensionistas  &&  (data.dependencia == '' || data.dependencia == '0') ){

                            err = true;
                            mensaje+= " <li>Debe especificar la dependencia de trabajo</li> ";
                         }
                         */

                         if(data.dependencia == ''){

                            err = true;
                            mensaje+= " <li> Verifique el area de trabajo. </li> ";

                         }
                        
 

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
                            
                              if(confirm('Realmente desea proceder con el registro?')){
 
                                     if( Persona._M.actualizar_situlaboral.process(data) ){
                                       
                                          
                                          Persona._V.editar_situlaboral.close();
                                          Persona.Ui.Grids.historial_laboral.refresh();

                                     }    
                                    
                              }

                        }
                    
                    
                    
                    
                }
                
         },


         // registrar comision servicio
         btn_registrarcomserv_click:  function(btn,e){
             
                
             var data = dojo.formToObject('form_info_comision');
             
             for(f in data){
                 
                 data[f] = dojo.trim(data[f]);
                 
                // alert(data[f]);
             }
               //alert('comision');
              
             
                 // var err =false;
                
               

                var err = false, mensaje = '<ul>';

                 if(data.motivo == ''){

                     err = true;
                     mensaje+=' <li> El motivo de la comision de servicio es obligatorio</li> ';
                 }

                 if(data.sisgedo_doc == '' || data.sisgedo_doc == undefined  ){   

                         
                           if(  data.autoriza == '' || data.documento  == ''    ){
                               err =  true,

                               mensaje += '<li>La informacion del documento que autoriza esta incompleta</li>'; 

                           } 
                 } 

                 
                 if(err){

                      mensaje += '<ul>';
                     var myDialog = new dijit.Dialog({
                                            title: "Atenci&oacute;n",
                                            content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                            style: "width: 350px"
                                        });
                      myDialog.show();

                 }
                 else{ 

                       var ok =  dijit.byId('form_info_comision').validate();

                       if(ok){
                           
                            if(confirm('Realmente desea proceder con el registro?')){
                               
                                      
                           //btn.domNode.parentNode;
                                    var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                                    data.empkey = empkey;

                                   if( Persona._M.registrar_comision.process(data) ){

                                        dijit.byId('form_info_comision').reset();
                                        
                                        Sisgedo.desvincular_doc('trcs_infosisgedo');

                                        Persona.get_comisiones({'empkey': empkey});
                                        Persona.Ui.Grids.comisiones.refresh();

                                         var fecha = $_currentDate();
                                         
                                         dojo.forEach( ['calcomidesde','calcomihasta'], function(cal,ind){
                                            
                                            dijit.byId(cal).set('value',  fecha   );
                                           
                                         });
                                    }

                           /*
                                var rs =  Persona.registrar(data);

                                if(rs){
                                    // alert(dijit.byId('form_info_personal'));
                                    // dijit.byId('form_info_personal').reset();
                                    if( Persona._M.registrar.data.key != null)
                                    {
                                       //     alert(Persona._M.registrar.data.key);
                                            Persona._V.registrar_nuevo.close();
                                            Persona._V.full_info_persona.load({'empkey': Persona._M.registrar.data.key});
                                    }
                                    else{

                                    } 
                                }
                                else{

                                }

                           }*/
                           }
                           
                       } 
                }
                 
                 /*
                 alert(err);
                
                 var mensaje = '<ul>';
 
                 if(data.documento==''){
                     err = true;

                     mensaje += '<li>El campo "Documento" es obligatorio</li>'; 

                 } 
                 if(data.autoriza==''){
                     err = true;

                     mensaje += '<li>El campo "Autoriza" es obligatorio</li>'; 

                 } 
                 if(data.motivo==''){
                     err = true;

                     mensaje += '<li>El campo "Motivo" es obligatorio</li>'; 

                 } 
                 if(data.fechaini=='' || data.fechaini=='fechafin'){
                     err = true;

                     mensaje += '<li>Verifique el Intervalo de fechas especificado.</li>'; 

                 }  
             
             
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
                    
                } */
                 
             
      
         }, 
         
         //btn registrar licencia
         btn_register_descansomedico_click: function(btn,evt){
             


             var data = dojo.formToObject('form_info_descansomedico');
             
             for(f in data){
                 data[f] = dojo.trim(data[f]);
                // aler
             } 
             
             var err =false;

             var mensaje = '<ul>';

              
                  if(data.sisgedo_doc == '' || data.sisgedo_doc == undefined  )
                  {   
 
                     if(  data.documento  == ''    )
                     {
                         err = true;
                         mensaje += '<li>La informacion del documento esta incompleta</li>'; 
                     } 
                 } 

                 

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
                   
                   var ok =  dijit.byId('form_info_descansomedico').validate();      
                   if(ok)
                   {
                        if(confirm('Realmente desea proceder con el registro?'))
                        {
                           
                             var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                             data.empkey = empkey;

                             if( Persona._M.registrar_descansomedico.process(data) )
                             {

                                  dijit.byId('form_info_descansomedico').reset();
                                  Persona.Ui.Grids.descansos.refresh(); 

                                  dijit.byId('caldescansodesde').set('value', new Date() );
                             }
         
                        }
                   }
               }
             
         },
         
         //btn registrar licencia
         btn_registerlicencia_click: function(btn,evt){
             
             var data = dojo.formToObject('form_info_licencias');
             
             for(f in data){
                 data[f] = dojo.trim(data[f]);
                // aler
             }
             
          
             var err =false;

             var mensaje = '<ul>';

              
                  if(data.sisgedo_doc == '' || data.sisgedo_doc == undefined  ){   

                         
                           if(  data.autoriza == '' || data.documento  == ''    ){
                               err = true;

                               mensaje += '<li>La informacion del documento que autoriza esta incompleta</li>'; 

                           } 
                 } 

                 if(data.tipo=='0'){
                     err = true;

                     mensaje += '<li> Debe especificar el tipo de licencia</li>'; 

                 } 
            /*     
                 if(  data.motivo=='0' || ( data.motivo=='2' && data.motivo_des == '' ) ){
                     err = true;

                     mensaje += '<li> Debe especificar el motivo de la Licencia</li>'; 
   
                 } */

                 if(err){
                
                          var myDialog = new dijit.Dialog({
                                    title: "Atenci&oacute;n",
                                    content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                    style: "width: 350px"
                                });
                         myDialog.show();
                 }
                 else{                  
                   var ok =  dijit.byId('form_info_licencias').validate();      
                   if(ok){
                             if(confirm('Realmente desea proceder con el registro?')){
                               
                                      
                           //btn.domNode.parentNode;
                                    var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                                    data.empkey = empkey;

                                   if( Persona._M.registrar_licencia.process(data) ){

                                        dijit.byId('form_info_licencias').reset();
                                        Persona.Ui.Grids.licencias.refresh();
                                       // Sisgedo.desvincular_doc('trli_infosisgedo');

                                        dijit.byId('callicedesde').set('value', new Date() );

                                       // Persona.get_comisiones({'empkey': empkey});
                                        // Persona._Ui.table_comisiones.refresh
                                   }
       
                           }
                   }
               }
             
         },
         
           btn_permiso_click: function(btn,evt){
             
             var data = dojo.formToObject('form_info_permisos');
             
             for(f in data){
                 
                 data[f] = dojo.trim(data[f]);
                 
                // alert(data[f]);
             }
             
             
                 var err =false;

                 var mensaje = '<ul>';

              /*
                 if(data.tipo=='0'){
                     err = true;

                     mensaje += '<li> Debe especificar el tipo de licencia</li>'; 

                 } 
                 
                 if(  data.motivo=='0' || ( data.motivo=='2' && data.motivo_des == '' ) ){
                     err = true;

                     mensaje += '<li> Debe especificar el motivo de la Licencia</li>'; 
   
                 } */

                if(data.sisgedo_doc == '' || data.sisgedo_doc == undefined  ){   

                         
                           if(  data.autoriza == '' || data.documento  == ''    ){
                               err = true;

                               mensaje += '<li>La informacion del documento que autoriza esta incompleta</li>'; 

                           } 
                 }


                 if(err){

                       var myDialog = new dijit.Dialog({
                                    title: "Atenci&oacute;n",
                                    content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                    style: "width: 350px"
                                });
                       myDialog.show();
                 }else{

                        var ok =  dijit.byId('form_info_permisos').validate();   
                      if(ok){
                             if(confirm('Realmente desea proceder con el registro?')){
                               
                                      
                           //btn.domNode.parentNode;
                                    var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                                    data.empkey = empkey;

                                   if( Persona._M.registrar_permiso.process(data) ){

                                        dijit.byId('form_info_permisos').reset();
                                          Persona.Ui.Grids.permisos.refresh();
                                           Sisgedo.desvincular_doc('trperm_infosisgedo');
                                       // Persona.get_comisiones({'empkey': empkey});
                                        // Persona._Ui.table_comisiones.refresh
                                   }
       
                           }
                       }

                 } 

           
            
               
         },


         //btn registrar licencia
         btn_register_vacaciones_click: function(btn,evt){
 
             var data = dojo.formToObject('form_info_vacaciones');
             
             for(f in data){
                 data[f] = dojo.trim(data[f]);
                // aler
             } 
             
             var err =false;

             var mensaje = '<ul>';

             
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
                   
                   var ok =  dijit.byId('form_info_vacaciones').validate();      
                   if(ok)
                   {
                        if(confirm('Realmente desea proceder con el registro?'))
                        {
                           
                             var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                             data.empkey = empkey;

                             if( Persona._M.registrar_vacaciones.process(data) )
                             {

                                  dijit.byId('form_info_vacaciones').reset();
                                  Persona.Ui.Grids.vacaciones.refresh(); 

                                  dijit.byId('calvacadesde').set('value' , new Date() );
                             }
         
                        }
                   }
               }
             
         },
         
         
         btn_registerfaltar_click:  function(btn,evt){
             
             
              var data = dojo.formToObject('form_info_faltas');
             
             for(f in data){
                 
                  data[f] = dojo.trim(data[f]);
                 
                
             }
             
                 var ok =  dijit.byId('form_info_faltas').validate();   
                 var err =false;

                 var mensaje = '<ul>';
 
           
                 if(!err && ok){
                       if(confirm('Realmente desea proceder con el registro?')){
                         
                                
                     //btn.domNode.parentNode;
                              var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                              data.empkey = empkey;

                             if( Persona._M.registrar_falta.process(data) ){

                                  dijit.byId('form_info_faltas').reset();
                                  Persona.Ui.Grids.faltas.refresh();
                                 // Persona.get_comisiones({'empkey': empkey});
                                  // Persona._Ui.table_comisiones.refresh
                             }
 
                     }
                 }
                 else if(err){
                
                          var myDialog = new dijit.Dialog({
                                    title: "Atenci&oacute;n",
                                    content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                    style: "width: 350px"
                                });
                         myDialog.show();
                } 
                else
                {
                   
                }
             
               
             
         },
         

         btn_registrartardanza:  function(btn,evt){
             
             
              var data = dojo.formToObject('form_info_tardanzas');
             
              for(f in data){
                  
                  data[f] = dojo.trim(data[f]);
                  
              }
             
                 var ok =  dijit.byId('form_info_tardanzas').validate();   
                 var err =false;

                 var mensaje = '<ul>';
         
           
                 if(!err && ok)
                 {
                 
                      if(confirm('Realmente desea proceder con el registro?'))
                      {
                    
                              var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                              data.empkey = empkey;

                             if( Persona._M.registrar_tardanza.process(data) ){

                                  dijit.byId('form_info_tardanzas').reset();
                                  Persona.Ui.Grids.tardanzas.refresh();
                 
                             }
         
                     }
                 }
                 else if(err){
                
                          var myDialog = new dijit.Dialog({
                                    title: "Atenci&oacute;n",
                                    content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                    style: "width: 350px"
                                });
                         myDialog.show();
                } 
                else
                {
                   
                }
             
               
             
         },
         
         
          btn_registeracademico_click:  function(btn,evt){
             
             
             var data = dojo.formToObject('form_info_academica');
             
             for(f in data){
                  data[f] = dojo.trim(data[f]);
             }
             
          // var ok =  dijit.byId('form_info_academica').validate();   
             var err =false;
             var mensaje = '<ul>';
 


            if(data.tipo == '0'){
  
                 err = true;
                 mensaje+=' <li>Debe especificar un tipo de estudio  y completar los datos</li> ';

             }



             if( [11,13,14,15].includes(Number(data.tipo)) ){  

                 if(data.carrera == ''){

                    if(dijit.byId('selAcademico_carrera').get('displayedValue') == ''){

                        err = true;
                        mensaje+=' <li>Debe especificar la CARRERA PROFESIONAL</li> ';
 
                    }

                     data.carrera_nombre  = dijit.byId('selAcademico_carrera').get('displayedValue');
 
                 }

             }else{
                     data.carrera = '0';
                   
             }  


             if( [9,17,18,20,21].includes(Number(data.tipo)) ){ 

                 if(data.especialidad == ''){

                     if(dijit.byId('selAcademico_especialidad').get('displayedValue') == ''){

                        err = true;
                        mensaje+=' <li>Debe especificar la ESPECIALIDAD</li> ';
 
                    }

                    data.especialidad_nombre  = dijit.byId('selAcademico_especialidad').get('displayedValue');

                 }
               

             }
            else{
                    data.especialidad = '0';
             } 
                   

             if( [11,13,14,15,17,18,20,21].includes(Number(data.tipo)) ){ 

                if(data.centroestudios == '' || dijit.byId('selAcademico_centroestudios').get('displayedValue') == '' ){

                    //console.log('Centro de estudios aqui !'); 

                    if(dijit.byId('selAcademico_centroestudios').get('displayedValue') == ''){

                            err = true;
                            mensaje+=' <li>Debe especificar el CENTRO DE ESTUDIOS</li> ';
    
                        }

                        data.centroestudios_nombre  = dijit.byId('selAcademico_centroestudios').get('displayedValue');

                }

            }

            



             if( [3,5,7,9].includes(Number(data.tipo)) ){
                  
                  if( data.nombre == '' ){  

                    err = true;
                    mensaje+=' <li>Debe completar el campo  NOMBRE</li> ';
                  }
             }
             else{

                  data.nombre = '';
             }


            /*
             if( [3,4,5,6,7,8,9,12].indexOf(parseInt(data.tipo)) > -1  ){


                  if(data.modalidad == '0' ){ 

                      err = true;
                      mensaje+=' <li>Debe especificar la MODALIDAD</li> ';
                  }
             }
             else{

                 data.modalidad  = '0';

             }


             if( [3,4,5,6,7,8].indexOf(parseInt(data.tipo)) > -1  ){

                 if(data.situacion == '0'){ 
                    err = true;
                   mensaje+=' <li>Debe especificar la SITUACION</li> ';
                }
             }
             else{

                  data.situacion = '0';
             }


             if( [3,4,5,6,7,8].indexOf(parseInt(data.tipo)) > -1  ){

                 if(data.titulo == '0'){ 
                   err = true;
                   mensaje+=' <li>Debe especificar el estado del TITULO O CERTIFICADO </li> ';
                }
             }
             else{
                 data.titulo = '0';
             }


              if( [9,10,11,12].indexOf(parseInt(data.tipo)) > -1  ){


                 if(data.horasacademicas == ''){ 
                   err = true;
                   mensaje+=' <li>Debe especificar la cantidad de HORAS ACADEMICAS</li> ';
                 }
             }
             else{
                 data.horasacademicas = '0';
             }
            */

             mensaje += " </ul> ";


             if(err){

                   new dijit.Dialog({
                                    title: "Atenci&oacute;n",
                                    content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                    style: "width: 350px"
                                }).show(); 

             } 
             else{   
     
                 if(confirm('Realmente desea proceder con el registro?')){
                       
                     var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                         data.empkey = empkey;
                                  
                      for(x in data ){
                                            console.log(x + ': '+ data[x]);
                                         
                       }
                                    
                     if( Persona._M.registrar_academico.process(data) ){

                            dijit.byId('form_info_academica').reset();
                            Persona.Ui.Grids.academico.refresh();
                      }
     
                  } 
             }
 
             
               
             
         },
         
         
          btn_registerfamilia_click:  function(btn,evt)
          {
             
             
                 var data = dojo.formToObject('form_info_familiar');
               
                 for(f in data){
                     
                      data[f] = dojo.trim(data[f]);
                      
                 }
                
                 var err     = false;
                 var mensaje = '<ul>';


                 if(data.parentesco == '0')
                 {

                    err = true;
                    mensaje += " <li> Debe especificar un parentesco </li> ";

                 }
                 
                 if(data.nombres == '' || data.paterno == '' || data.materno == '' )
                 {
                     err = true;
                     mensaje+="<li> Debe especificar el nombre y apellidos del familiar </li> ";
                 }

                 if(data.dni == ''  )
                 {

                     err = true;
                     mensaje +=" <li>El DNI ingresado no es valido</li>";

                 }
                 
                 /*
                 if(data.sexo == '0')
                 {

                     err =  true;
                     mensaje += " <li> Debe especificar el SEXO de la persona </li>";

                 }
                 */


                 if(data.ocupacion == '')
                 {

                     data.ocupacion_nombre= dijit.byId('selfami_ocupacion').get('displayedValue');

                 }

           
                 if(!err)
                 {

                       if(confirm('Realmente desea proceder con el registro?'))
                       {
                                  
                              var empkey =  dojo.trim(dojo.query('.hduserkey',btn.domNode.parentNode )[0].value);
                              data.empkey = empkey;

                             if( Persona._M.registrar_familiar.process(data) )
                             {

                                  dijit.byId('form_info_familiar').reset();
                                  Persona.Ui.Grids.familia.refresh();
                   
                             }
 
                        }


                 }
                 else if(err)
                 {
                
                        var myDialog = new dijit.Dialog({
                                    title: "Atenci&oacute;n",
                                    content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                    style: "width: 350px"
                                });


                         myDialog.show();
                 
                 } 
                 else
                 {
                   
                 }
             
               
             
         },
         
         
         btn_tblhistolaboral_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.historial_laboral.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_situacion_laboral.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 


         btn_tblhistolaboral_editar_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.historial_laboral.selection){
                  codigo = i;
            }
    
            if(codigo != '')      
            {
                Persona._V.editar_situlaboral.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 


         btn_tblinfoper_retirar_view_click : function(btn, e){
            
            var codigo = '';      
                    
           for(var i in Persona.Ui.Grids.historial_laboral.selection){
                  codigo = i;
            }
    
            if(codigo != '')      
            {   
                Persona._V.retirar.load({'view' : codigo}); 
            }
            else{
                alert('Debe seleccionar un registro');
            }
          
         },

          
         
         btn_tblcomi_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.comisiones.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_comision.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         
         
         btn_tbllice_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.licencias.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_licencia.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 


         btn_vacaciones_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.vacaciones.selection){
                  codigo = i;
            }
         
            if(codigo != '')      
            {
                Persona._V.view_vacaciones.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 


         btn_descanso_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.descansos.selection){
                  codigo = i;
            }
         
            if(codigo != '')      
            {
                Persona._V.view_descanso.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         btn_tblpermiso_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.permisos.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_permiso.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
          btn_tblfaltar_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.faltas.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_falta.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                  
         },   


             btn_tbltardanza_ver_click: function(){
                   
               var codigo = '';      
                       
               for(var i in Persona.Ui.Grids.tardanzas.selection){
                     codigo = i;
               }
            
               if(codigo != '')      
               {
                   Persona._V.view_tardanzas.load({'codigo' : codigo});    
               }
               else{
                   alert('Debe seleccionar un registro');
               }
                   
            }, 
         
           btn_tblestudio_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.academico.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_estudios.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         btn_tblfamilia_ver_click: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.familia.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                Persona._V.view_familiar.load({'codigo' : codigo});    
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         },   



         btn_estudiantes_activo_desactivo: function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.familia.selection){
                  codigo = i;
            }
         
            if(codigo != '')      
            {
                if(confirm('Realmente desea realizar esta operacion? '))
                { 
                     if(Persona._M.activar_desactivar_estudiante.process({'codigo' : codigo}))
                     {
                        Persona.Ui.Grids.familia.refresh();
                     }
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         
         btn_tblsilab_del_clic : function(){
                
            var codigo = '';
                    
            for(var i in Persona.Ui.Grids.historial_laboral.selection){
                  codigo = i;
            }
		
            if(codigo != '')
            {
                if(confirm('Realmente desea eliminar el registro de la situacion laboral ?')){
                     Persona._M.delete_situlaboral.process({'codigo' : codigo});
                     Persona.Ui.Grids.historial_laboral.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
          btn_tblcomi_del_clic : function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.comisiones.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {   
                if(confirm('Realmente desea eliminar el registro de comision de servicio ?')){ 
                     Persona._M.delete_comision.process({'codigo' : codigo});    
                     Persona.Ui.Grids.comisiones.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
          btn_tbllice_del_clic : function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.licencias.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {   
                if(confirm('Realmente desea eliminar el registro de la Licencia ?')){ 
                     Persona._M.delete_licencia.process({'codigo' : codigo});    
                     Persona.Ui.Grids.licencias.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 


         btn_vacaciones_del_clic : function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.vacaciones.selection){
                  codigo = i;
            }
    
            if(codigo != '')      
            {   
                if(confirm('Realmente desea eliminar el registro de la Licencia ?')){ 
                     Persona._M.delete_vacaciones.process({'codigo' : codigo});    
                     Persona.Ui.Grids.vacaciones.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
          }, 


          btn_descanso_del_clic : function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.descansos.selection){
                  codigo = i;
            }
         
            if(codigo != '')      
            {   
                if(confirm('Realmente desea eliminar el registro de la Licencia ?')){ 
                     Persona._M.delete_descanso.process({'codigo' : codigo});    
                     Persona.Ui.Grids.descansos.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         btn_tblperm_del_clic : function(){
                
            var codigo = '';
                    
            for(var i in Persona.Ui.Grids.permisos.selection){
                  codigo = i;
            }
		
            if(codigo != '')
            {
                if(confirm('Realmente desea eliminar el registro del permiso ?')){ 
                     Persona._M.delete_permiso.process({'codigo' : codigo});
                     Persona.Ui.Grids.permisos.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         btn_tblfaltar_del_clic : function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.faltas.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {   
                if(confirm('Realmente desea eliminar el registro?')){ 
                     Persona._M.delete_falta.process({'codigo' : codigo});    
                     Persona.Ui.Grids.faltas.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 


         btn_tbltardanza_del_clic : function(){
                
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.tardanzas.selection){
                  codigo = i;
            }
         
            if(codigo != '')      
            {   
                if(confirm('Realmente desea eliminar el registro ?')){ 
                     Persona._M.delete_tardanza.process({'codigo' : codigo});    
                     Persona.Ui.Grids.tardanzas.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         btn_tblestudio_del_clic : function(){
                
            var codigo = '';
                    
            for(var i in Persona.Ui.Grids.academico.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                if(confirm('Realmente desea eliminar el registro academico ?')){ 
                     Persona._M.delete_academico.process({'codigo' : codigo});    
                     Persona.Ui.Grids.academico.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
          btn_tblfamiliar_del_clic : function(){
                
            var codigo = '';
                    
            for(var i in Persona.Ui.Grids.familia.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {
                if(confirm('Realmente desea eliminar el registro familiar ?')){ 
                     Persona._M.delete_familiar.process({'codigo' : codigo});    
                     Persona.Ui.Grids.familia.refresh();
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
                
         }, 
         
         
         btn_tblinfoper_click : function(btn, e){
           
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.trabajadores.selection){
                  codigo = i;
            }
		
            if(codigo != '')
            {   
               // alert(codigo);
                
                Persona._V.full_info_persona.load({'empkey' : codigo});
            }
            else{
                alert('Debe seleccionar un registro');
            }
          
         },

        
        crearPDFDatos: function (){
            // pipe = new DatePipe('en-US');
            // //AFP Seguro
            // let tipoSeguro;
            // if((this.formPersonas.value.itiposegid==null)){
            // this.formPersonas.value.itiposegid = '';
            // tipoSeguro='';
            // } else {
            // let numTipoSeguroF = this.formPersonas.controls.itiposegid.value;
            // let tipoSeguro1 = this.seguro[numTipoSeguroF-1];
            // tipoSeguro = tipoSeguro1.ctiposegdsc;
            // }

            // //Departamento
            // let departamento;
            // if((this.formPersonas.value.idptoid==null)){
            // this.formPersonas.value.idptoid = '';
            // departamento='';
            // } else {
            // let numDepart = this.formPersonas.controls.idptoid.value;
            // let departamento1 = this.departamentos[numDepart-1];
            // departamento = departamento1.cdptodsc;
            // }

            // //Provincia
            // let provincia;
            // if((this.formPersonas.value.iprovid==null)){
            // this.formPersonas.value.iprovid = '';
            // provincia='';
            // } else {
            // let numProv = this.formPersonas.controls.iprovid.value;
            // let prov1 = this.provincias[numProv-1];
            // provincia = prov1.cprovdsc;
            // }

            // //Distrito
            // let distrito;
            // if((this.formPersonas.value.idistid==null)){
            // this.formPersonas.value.idistid = '';
            // distrito='';
            // } else {
            // let numDist = this.formPersonas.controls.idistid.value;
            // let dist1 = this.distritos[numDist-1];
            // distrito = dist1.cdistdsc;
            // }

            // //Grupo Sanguineo
            // let gSanguineo;
            // if((this.formPersonas.value.igruposangid==null)){
            // this.formPersonas.value.igruposangid = '';
            // gSanguineo='';
            // } else {
            // let numSang = this.formPersonas.controls.igruposangid.value;
            // let sang1 = this.sanguineo[numSang-1];
            // gSanguineo = sang1.cgruposangdsc;
            // }
            

            // if((this.formPersonas.value.cpersdireccion==null)){
            // this.formPersonas.value.cpersdireccion = ''
            // }
            // if((this.formPersonas.value.cperslibreta==null)){
            // this.formPersonas.value.cperslibreta = ''
            // }
            // if((this.formPersonas.value.cpersruc==null)){
            // this.formPersonas.value.cpersruc = ''
            // }
            // if((this.formPersonas.value.cnroseg==null)){
            // this.formPersonas.value.cnroseg = ''
            // }
            // if((this.formPersonas.value.cperslicencia==null)){
            // this.formPersonas.value.cperslicencia = ''
            // }
            // if((this.formPersonas.value.cperscorreo==null)){
            // this.formPersonas.value.cperscorreo = ''
            // }
            // if((this.formPersonas.value.cperstelefono==null)){
            // this.formPersonas.value.cperstelefono = ''
            // }
            // if((this.formPersonas.value.cperscelular==null)){
            // this.formPersonas.value.cperscelular = ''
            // }
            // if((this.formPersonas.value.fam_instit==null)){
            // this.formPersonas.value.fam_instit = ''
            // }
            // if((this.formPersonas.value.dfechanac==null)){
            // this.formPersonas.value.dfechanac = ''
            // }
            // if((this.formPersonas.value.cestado==null)){
            // this.formPersonas.value.cestado = ''
            // }
            // if((this.formPersonas.value.nro_hijos==null)){
            // this.formPersonas.value.nro_hijos = ''
            // }
            // if((this.formPersonas.value.ape_nom_cony==null)){
            // this.formPersonas.value.ape_nom_cony = ''
            // }
            // if((this.formPersonas.value.ingr_por==null)){
            // this.formPersonas.value.ingr_por = ''
            // }
            // if((this.formPersonas.value.reposicion==null)){
            // this.formPersonas.value.reposicion = ''
            // }
            // if((this.formPersonas.value.fecha_ingr==null)){
            // this.formPersonas.value.fecha_ingr = ''
            // }
            // if((this.formPersonas.value.fecha_nomb==null)){
            // this.formPersonas.value.fecha_nomb = ''
            // }
            // if((this.formPersonas.value.nro_resol==null)){
            // this.formPersonas.value.nro_resol = ''
            // }
            // if((this.formPersonas.value.fecha_resol==null)){
            // this.formPersonas.value.fecha_resol = ''
            // }
            // if((this.formPersonas.value.nro_contr==null)){
            // this.formPersonas.value.nro_contr = ''
            // }

            let fecha = this.pipe.transform((new Date), 'dd/MM/yyyy');

            const pdfDefinition = {
            content: [
                { text: 'Hoja de Datos Personales N° ' + this.FormDatosPers.value.ipersid + ' - ' + 'Creado: ' + fecha, alignment: 'left', fontSize: 11 },
                { text: ' ' },
                //{ text: 'Recepcionado: ' + fechaRevisado, alignment: 'right', fontSize: 11},
                { text: 'Municipalidad Provincial de ILO', bold: true, style: 'header', fontSize: 19, alignment: 'center' },
                { text: ' ' },
                { text: 'Legajo', style: 'subheader', fontSize: 16, alignment: 'left' },
                { text: 'Este documento es a fines de proveer informacion sobre el siguiente usuario: ' + this.FormDatosPers.value.cpersnombre + ' ' + this.FormDatosPers.value.indiv_appaterno + ' ' + this.FormDatosPers.value.indiv_apmaterno + ', con direccion: ' + this.FormDatosPers.value.cpersdireccion + '.', style: 'subheader', fontSize: 12, alignment: 'left' },
                { text: ' ' },
                {
                style: 'tableExample',
                table: {
                    headerRows: 1,
                    widths: [ '*', '*', '*'],
                    body: [
                    [{text:'Documentos personales', style:'header1', fontSize: 13}, {text: 'Lugar, Fecha de nacimiento, Grupo sanguineo y Estado Civil', style:'header1', fontSize: 13}, {text: 'Ingreso a la Entidad', style:'header1', fontSize: 13}],
                    [
                        {
                        stack: [
                            [
                            {text: 'DNI/RUC:  '+ this.FormDatosPers.value.indiv_dni},
                            {text: 'Libreta Militar:  '+ this.FormDatosPers.value.cperslibreta},
                            {text: 'RUC:  '+ this.FormDatosPers.value.cpersruc},
                            {text: 'AFP:  '+ tipoSeguro},
                            {text: 'Codigo AFP:  '+ this.FormDatosPers.value.cnroseg},
                            {text: 'Licencia de Conducir:  '+ this.FormDatosPers.value.cperslicencia},
                            {text: 'Email: '+ this.FormDatosPers.value.cperscorreo},
                            {text: 'Telefono Fijo:  '+ this.FormDatosPers.value.cperstelefono},
                            {text: 'Celular:  '+ this.FormDatosPers.value.cperscelular},
                            {text: 'Familiares en institucion:  '+ this.FormDatosPers.value.fam_instit},
                            ]
                            
                        ]
                        },
                        
                        [
                        {text: 'Departamento:  ' + departamento},
                        {text: 'Provincia:  ' + provincia},
                        {text: 'Distrito:  ' + distrito},
                        {text: 'Fecha Nacimiento:  ' + this.FormDatosPers.value.dfechanac},
                        {text: 'Grupo Sanguineo:  ' + gSanguineo},
                        {text: 'Estado Civil:  ' + this.FormDatosPers.value.cestado},
                        {text: 'Numero de hijos:  ' + this.FormDatosPers.value.nro_hijos},
                        {text: 'Apellidos y Nombres de conyugue:  ' + this.FormDatosPers.value.ape_nom_cony},
                        ],
                        [
                        {text: 'Ingreso mediante:  ' + this.FormDatosPers.value.ingr_por},
                        {text: 'Reposicion:  ' + this.FormDatosPers.value.reposicion},
                        {text: 'Fecha Ingreso:  ' + this.FormDatosPers.value.fecha_ingr},
                        {text: 'Fecha Nombramiento:  ' + this.FormDatosPers.value.fecha_nomb},
                        {text: 'Nro Resolucion:  ' + this.FormDatosPers.value.nro_resol},
                        {text: 'Fecha Resolucion:  ' + this.FormDatosPers.value.fecha_resol},
                        {text: 'Nro Contrato:  ' + this.FormDatosPers.value.nro_contr},
                    ]
                    ]
                    ]
                }
                },
            ],
            styles: {
                header1: {
                fillColor: 'grey',
                    fillOpacity: 0.3
                }
            }
            
            }
            const pdf = pdfMake.createPdf(pdfDefinition);
            // pdf.getDataUrl((data) => {
            //   this.formCargaFam.controls['url'].setValue(data);
            
            // });
            pdf.open();
        },

         btn_tblinfoper_click_legajo : function(modal,indiv_id){
            if(indiv_id != '')
            {
               // alert(codigo);
                Persona._V.full_info_persona_legajo.load({'modal':modal,'indiv_id' : indiv_id});
            }
            else{
                alert('Debe seleccionar un registro');
            }
          
         },

         btn_personal_accion : function(indiv_id,ipersid,accion,form){
            
            let personal = {};
            switch(accion){
                
                case 'actualizar':

                    var frm_elements = form.elements;
                    for(i=0; i<frm_elements.length; i++)
                            {   
                               
                                switch (frm_elements[i].id)
                                {
                                    case "igruposangid":    
                                    var igruposangid = frm_elements[i].value;
                                    break;  
                                    case "itiposegid":    
                                    var itiposegid = frm_elements[i].value;
                                    break;  
                                    case "cnroseg":    
                                    var cnroseg = frm_elements[i].value;
                                    break;
                                    case "cpersruc":    
                                    var cpersruc = frm_elements[i].value;
                                    break;  
                                    case "cpersdireccion":    
                                    var cpersdireccion = frm_elements[i].value;
                                    break;  
                                    case "dfechanac":    
                                    var dfechanac = frm_elements[i].value;
                                    break;  
                                    case "cperslibreta":    
                                    var cperslibreta = frm_elements[i].value;
                                    break;  
                                    case "cperslicencia":    
                                    var cperslicencia = frm_elements[i].value;
                                    break;  
                                    case "dirigidoa":    
                                    var dirigidoa = frm_elements[i].value;
                                    break;    
                                    case "area":    
                                    var area = frm_elements[i].value;
                                    break;    
                                    case "creferencia":    
                                    var creferencia = frm_elements[i].value;
                                    break;  
                                    case "reposicion":    
                                    var reposicion = frm_elements[i].value;
                                    break;    
                                    case "ingr_por":    
                                    var ingr_por = frm_elements[i].value;
                                    break;    
                                    case "acta_repos":    
                                    var acta_repos = frm_elements[i].value;
                                    break;    
                                    case "cond_labor":    
                                    var cond_labor = frm_elements[i].value;
                                    break;  
                                    case "mediante1":    
                                    var mediante1 = frm_elements[i].value;
                                    break;    
                                    case "de_fecha":    
                                    var de_fecha = frm_elements[i].value;
                                    break;    
                                    case "cargo_nombro":    
                                    var cargo_nombro = frm_elements[i].value;
                                    break;    
                                    case "unidad_org_nomb":    
                                    var unidad_org_nomb = frm_elements[i].value;
                                    break;  
                                    case "dependiente":    
                                    var dependiente = frm_elements[i].value;
                                    break;    
                                    case "regimen_lab":    
                                    var regimen_lab = frm_elements[i].value;
                                    break;    
                                    case "apartirdel":    
                                    var apartirdel = frm_elements[i].value;
                                    break;    
                                    case "rotadoa":    
                                    var rotadoa = frm_elements[i].value;
                                    break;  
                                    case "mediante2":    
                                    var mediante2 = frm_elements[i].value;
                                    break;    
                                    case "profesion":    
                                    var profesion = frm_elements[i].value;
                                    break;    
                                    case "comentario":    
                                    var comentario = frm_elements[i].value;
                                    break;    
                                }
                            }
                            
                    vacio = 0

                    personal = {
                            accion:accion,
                            ipersid:ipersid,

                            igruposangid : igruposangid ? igruposangid : vacio++,
                            itiposegid : itiposegid ? itiposegid : vacio++,
                            cnroseg : cnroseg ? cnroseg : vacio++,
                            cpersruc : cpersruc ? cpersruc : vacio++,
                            cpersdireccion : cpersdireccion ? cpersdireccion : vacio++,
                            dfechanac : dfechanac ? dfechanac : vacio++,
                            cperslibreta : cperslibreta ? cperslibreta : vacio++,
                            cperslicencia : cperslicencia ? cperslicencia : vacio++,
                            dirigidoa : dirigidoa ? dirigidoa : vacio++,
                            area : area ? area : vacio++,
                            creferencia : creferencia ? creferencia :  vacio++,
                            reposicion : reposicion ? reposicion : vacio++,
                            ingr_por : ingr_por ? ingr_por : vacio++,
                            acta_repos : acta_repos ? acta_repos : vacio++,
                            cond_labor : cond_labor ? cond_labor : vacio++,
                            mediante1 : mediante1 ? mediante1 : vacio++,
                            de_fecha : de_fecha ? de_fecha : vacio++,
                            cargo_nombro : cargo_nombro ? cargo_nombro : vacio++,
                            unidad_org_nomb : unidad_org_nomb ? unidad_org_nomb : vacio++,
                            dependiente : dependiente ? dependiente : vacio++,
                            regimen_lab : regimen_lab ? regimen_lab : vacio++,
                            apartirdel : apartirdel ? apartirdel : vacio++,
                            rotadoa : rotadoa ? rotadoa : vacio++,
                            mediante2 : mediante2 ? mediante2 : vacio++,
                            profesion : profesion ? profesion : vacio++,
                            comentario : comentario ? comentario : vacio++,
                        }
                        if(vacio==0){
                        var rs =  Persona.personal_accion(personal);
                    
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se guardó exitosamente.',
                                'success'
                              )

                           
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'personal','indiv_id' : indiv_id});
                        }}
                        else{
                            Swal.fire(
                                'ATENCIÓN!',
                                'Debe de completar todos los campos del formulario.',
                                'warning'
                              )
                        }
                    
                break;
                case 'imprimir': //aqui puedes hacer el tema de imprimir
                break;
                default: alert('No se encontró acción'); break;

            }
            
         },


         btn_estudios_click_legajo : function(indiv_id,ipersid,accion,iperstipoestudid){
         
            switch(accion){
                case 'agregar':
                  
                    Persona._V.agregar_estudios_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'iperstipoestudid' : null});
                  
                break;
                case 'actualizar':
                    
                    if(iperstipoestudid!=''){
                       
                        Persona._V.actualizar_estudios_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'iperstipoestudid' : iperstipoestudid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    
                break;
                case 'eliminar':
                   
                    if(iperstipoestudid!=''){
                        

                        Swal.fire({
                            title: 'Seguro desea eliminar?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                          }).then((result) => {
                            if (result.isConfirmed) {
                                Persona.Ui.btn_estudios_accion(indiv_id,iperstipoestudid,ipersid,'eliminar'); 
                            }
                          })
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
            
         },

         btn_estudios_accion : function(indiv_id,iperstipoestudid,ipersid,accion,form){
            
            let estudio = {};
            if(accion!='eliminar'){
            var frm_elements = form.elements;
            for(i=0; i<frm_elements.length; i++)
                    {   
                       
                        switch (frm_elements[i].id)
                        {
                            case "e_ccentroestudios":    
                                var ccentroestudios = frm_elements[i].value;
                            break;  
                            case "e_dfechainicio":    
                            var dfechainicio = frm_elements[i].value;
                            break;  
                            case "e_dfechatermino":    
                            var dfechatermino = frm_elements[i].value;
                            break;    
                            case "e_cgrado_titulo":    
                            var cgrado_titulo = frm_elements[i].value;
                            break;    
                            case "e_ccolegiaturanro":    
                            var ccolegiaturanro = frm_elements[i].value;
                            break;    
                        }
                    }
                }
            switch(accion){
                case 'agregar':
                          vacio = 0
                           estudio = {
                                accion:accion,
                                ipersid:ipersid,
                                ccentroestudios:ccentroestudios ? ccentroestudios : vacio++,
                                dfechatermino:dfechatermino ? dfechatermino : vacio++,
                                cgrado_titulo:cgrado_titulo ? cgrado_titulo : vacio++,
                                ccolegiaturanro:ccolegiaturanro ? ccolegiaturanro : vacio++,
                                dfechainicio:dfechainicio ? dfechainicio : vacio++,
                            }
                            
                            if(vacio==0){
                                var rs =  Persona.estudios_accion(estudio);
                           
                                if(rs){
                                    Swal.fire(
                                        'ESTUPENDO!',
                                        'Se guardó exitosamente.',
                                        'success'
                                      )
                                    Persona._V.agregar_estudios_legajo.close();
                                    Persona._V.full_info_persona_legajo.close();
                                    Persona._V.full_info_persona_legajo.load({'modal':'estudios','indiv_id' : indiv_id});
                                } 
                            }
                            else{
                                //alert("Debe de completar todos los campos del formulario.");
                                Swal.fire(
                                    'ATENCIÓN!',
                                    'Debe de completar todos los campos del formulario.',
                                    'warning'
                                  )
                            }
                           
                  
                break;
                case 'actualizar':

                    vacio = 0

                    estudio = {
                            accion:accion,
                            ipersid:ipersid,
                            iperstipoestudid:iperstipoestudid ? iperstipoestudid : vacio++,
                            ccentroestudios:ccentroestudios ? ccentroestudios : vacio++,
                            dfechatermino:dfechatermino ? dfechatermino : vacio++,
                            cgrado_titulo:cgrado_titulo ? cgrado_titulo : vacio++,
                            ccolegiaturanro:ccolegiaturanro ? ccolegiaturanro : vacio++,
                            dfechainicio:dfechainicio ? dfechainicio : vacio++,
                        }
                        if(vacio==0){
                        var rs =  Persona.estudios_accion(estudio);
                    
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se actualizó exitosamente.',
                                'success'
                              )

                           
                            Persona._V.actualizar_estudios_legajo.close();
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'estudios','indiv_id' : indiv_id});
                        }}
                        else{
                            Swal.fire(
                                'ATENCIÓN!',
                                'Debe de completar todos los campos del formulario.',
                                'warning'
                              )
                        }
                    
                break;
                case 'eliminar':
                   
                    if(iperstipoestudid!=''){

                        estudio = {
                            accion:accion,
                            ipersid:ipersid,
                            iperstipoestudid:iperstipoestudid
                        }
                       
                        var rs =  Persona.estudios_accion(estudio);
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se eliminó exitosamente.',
                                'success'
                              )
                            Persona._V.full_info_persona_legajo.close();   
                            Persona._V.full_info_persona_legajo.load({'modal':'estudios','indiv_id' : indiv_id});
                        }
                       
                    }
                    else{
                        
                        Swal.fire(
                            'ATENCIÓN!',
                            'Debe seleccionar un registro.',
                            'warning'
                          )
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
            
         },

         btn_capacitacion_click_legajo : function(indiv_id,ipersid,accion,iperstipocapacid){
           
            switch(accion){
                case 'agregar':
                    
                    Persona._V.agregar_capacitacion_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'iperstipocapacid' : null});
                break;
                case 'actualizar':
                    if(iperstipocapacid!=''){
                        Persona._V.actualizar_capacitacion_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'iperstipocapacid' : iperstipocapacid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    
                break;
                case 'eliminar':
                   
                    if(iperstipocapacid!=''){

                        Swal.fire({
                            title: 'Seguro desea eliminar?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                          }).then((result) => {
                            if (result.isConfirmed) {
                                Persona.Ui.btn_capacitacion_accion(indiv_id,iperstipocapacid,ipersid,'eliminar'); 
                            }
                          })
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
            
         },

         btn_capacitacion_accion : function(indiv_id,iperstipocapacid,ipersid,accion,form){

            let capacitacion = {};
            if(accion!='eliminar'){
                var frm_elements = form.elements;
                for(i=0; i<frm_elements.length; i++)
                        {   
                           
                            switch (frm_elements[i].id)
                            {
                                case "c_itipocapacid":    
                                var itipocapacid = frm_elements[i].value;
                                break;  
                                case "c_ccentroestudios":    
                                var ccentroestudios = frm_elements[i].value;
                                break;  
                                case "c_cdenominacion":    
                                var cdenominacion = frm_elements[i].value;
                                break;  
                                case "c_dfechainicio":    
                                var dfechainicio = frm_elements[i].value;
                                break;    
                                case "c_dfechatermino":    
                                var dfechatermino = frm_elements[i].value;
                                break;    
                                case "c_ihoras":    
                                var ihoras = frm_elements[i].value;
                                break;    
                            }
                        }
                    }

            switch(accion){
                case 'agregar':
                    vacio = 0
                           capacitacion = {
                                accion:accion,
                                ipersid:ipersid,
                                itipocapacid:itipocapacid ? itipocapacid : vacio++,
                                ccentroestudios:ccentroestudios ? ccentroestudios : vacio++,
                                cdenominacion:cdenominacion ? cdenominacion : vacio++,
                                dfechainicio:dfechainicio ? dfechainicio : vacio++,
                                dfechatermino:dfechatermino ? dfechatermino : vacio++,
                                ihoras:ihoras ? ihoras : vacio++,
                            }
                            
                            if(vacio==0){
                                var rs =  Persona.capacitacion_accion(capacitacion);
                           
                                if(rs){
                                    Swal.fire(
                                        'ESTUPENDO!',
                                        'Se guardó exitosamente.',
                                        'success'
                                      )
                                    Persona._V.agregar_capacitacion_legajo.close();
                                    Persona._V.full_info_persona_legajo.close();
                                    Persona._V.full_info_persona_legajo.load({'modal':'capacitacion','indiv_id' : indiv_id});
                                } 
                            }
                            else{
                                //alert("Debe de completar todos los campos del formulario.");
                                Swal.fire(
                                    'ATENCIÓN!',
                                    'Debe de completar todos los campos del formulario.',
                                    'warning'
                                  )
                            }

                break;
                case 'actualizar':
                        vacio = 0;
                   
                        capacitacion = {
                            accion:accion,
                            ipersid:ipersid,
                            iperstipocapacid:iperstipocapacid ? iperstipocapacid : vacio++,
                            itipocapacid:itipocapacid ? itipocapacid : vacio++,
                                ccentroestudios:ccentroestudios ? ccentroestudios : vacio++,
                                cdenominacion:cdenominacion ? cdenominacion : vacio++,
                                dfechainicio:dfechainicio ? dfechainicio : vacio++,
                                dfechatermino:dfechatermino ? dfechatermino : vacio++,
                                ihoras:ihoras ? ihoras : vacio++,
                        }

                        
                        if(vacio==0){
                            var rs =  Persona.capacitacion_accion(capacitacion);
                        
                            if(rs){
                                Swal.fire(
                                    'ESTUPENDO!',
                                    'Se actualizó exitosamente.',
                                    'success'
                                  )
    
                               
                                Persona._V.actualizar_capacitacion_legajo.close();
                                Persona._V.full_info_persona_legajo.close();
                                Persona._V.full_info_persona_legajo.load({'modal':'capacitacion','indiv_id' : indiv_id});
                            }}
                            else{
                                Swal.fire(
                                    'ATENCIÓN!',
                                    'Debe de completar todos los campos del formulario.',
                                    'warning'
                                  )
                            }

                        
                break;
                case 'eliminar':
                   
                    if(iperstipocapacid!=''){
                        capacitacion = {
                            accion:accion,
                            ipersid:ipersid,
                            iperstipocapacid:iperstipocapacid
                        }
                       
                        var rs =  Persona.capacitacion_accion(capacitacion);
                        if(rs){
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'capacitacion','indiv_id' : indiv_id});
                        }
                       
                    }
                    else{
                        Swal.fire(
                            'ATENCIÓN!',
                            'Debe seleccionar un registro.',
                            'warning'
                          )
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
            
         },

         btn_laboral_click_legajo : function(indiv_id,ipersid,accion,iexp_laboralid){
            switch(accion){
                case 'agregar':
                    Persona._V.agregar_laboral_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'iexp_laboralid' : null});
                   
                break;
                case 'actualizar':
                    if(iexp_laboralid!=''){
                        Persona._V.actualizar_laboral_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'iexp_laboralid' : iexp_laboralid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    
                break;
                case 'eliminar':
                    if(iexp_laboralid!=''){
                        Swal.fire({
                            title: 'Seguro desea eliminar?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                          }).then((result) => {
                            if (result.isConfirmed) {
                                Persona.Ui.btn_laboral_accion(indiv_id,iexp_laboralid,ipersid,'eliminar'); 
                            }
                          })

                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    break;
                default: alert('No se encontró acción'); break;
            }
         },

        btn_laboral_accion : function(indiv_id,iexp_laboralid,ipersid,accion,form){
            
        let laboral = {};
          if(accion!='eliminar'){
            var frm_elements = form.elements;
            for(i=0; i<frm_elements.length; i++)
                    {   
                       
                        switch (frm_elements[i].id)
                        {
                            case "ccargos_desempenados":    
                                var ccargos_desempenados = frm_elements[i].value;
                            break;  
                            case "dfechainicio":    
                            var dfechainicio = frm_elements[i].value;
                            break;  
                            case "dfechatermino":    
                            var dfechatermino = frm_elements[i].value;
                            break;    
                            case "lugar_laburo":    
                            var lugar_laburo = frm_elements[i].value;
                            break;    
                             
                        }
                    }
                }
            switch(accion){
                case 'agregar':
                          vacio = 0
                          laboral = {
                                accion:accion,
                                ipersid:ipersid,
                                ccargos_desempenados:ccargos_desempenados ? ccargos_desempenados : vacio++,
                                dfechainicio:dfechainicio ? dfechainicio : vacio++,
                                dfechatermino:dfechatermino ? dfechatermino : vacio++,
                                lugar_laburo:lugar_laburo ? lugar_laburo : vacio++,
                               
                            }
                            
                            if(vacio==0){
                                var rs =  Persona.laboral_accion(laboral);
                           
                                if(rs){
                                    Swal.fire(
                                        'ESTUPENDO!',
                                        'Se guardó exitosamente.',
                                        'success'
                                      )
                                    Persona._V.agregar_laboral_legajo.close();
                                    Persona._V.full_info_persona_legajo.close();
                                    Persona._V.full_info_persona_legajo.load({'modal':'experiencia','indiv_id' : indiv_id});
                                } 
                            }
                            else{
                                //alert("Debe de completar todos los campos del formulario.");
                                Swal.fire(
                                    'ATENCIÓN!',
                                    'Debe de completar todos los campos del formulario.',
                                    'warning'
                                  )
                            }
                           
                  
                break;
                case 'actualizar':

                    vacio = 0

                    laboral = {
                            accion:accion,
                            ipersid:ipersid,
                            iexp_laboralid:iexp_laboralid ? iexp_laboralid : vacio++,
                            ccargos_desempenados:ccargos_desempenados ? ccargos_desempenados : vacio++,
                            dfechainicio:dfechainicio ? dfechainicio : vacio++,
                            dfechatermino:dfechatermino ? dfechatermino : vacio++,
                            lugar_laburo:lugar_laburo ? lugar_laburo : vacio++,
                            
                        }

                        
                        if(vacio==0){
                        var rs =  Persona.laboral_accion(laboral);
                    
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se actualizó exitosamente.',
                                'success'
                              );
                            
                            Persona._V.actualizar_laboral_legajo.close();
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'experiencia','indiv_id' : indiv_id});
                            }
                        }
                        else{
                            Swal.fire(
                                'ATENCIÓN!',
                                'Debe de completar todos los campos del formulario.',
                                'warning'
                              )
                        }
                    
                break;
                case 'eliminar':
                   
                    if(iexp_laboralid!=''){

                        laboral = {
                            accion:accion,
                            ipersid:ipersid,
                            iexp_laboralid:iexp_laboralid
                        }
                       
                        var rs =  Persona.laboral_accion(laboral);

                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se eliminó exitosamente.',
                                'success'
                              )
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'experiencia','indiv_id' : indiv_id});
                        }
                       
                    }
                    else{
                        
                        Swal.fire(
                            'ATENCIÓN!',
                            'Debe seleccionar un registro.',
                            'warning'
                          )
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
        },


         btn_meritos_click_legajo : function(indiv_id,ipersid,accion,imeritosid){
            switch(accion){
                case 'agregar':
                    Persona._V.agregar_meritos_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'imeritosid' : null});
                   
                break;
                case 'actualizar':
                    if(imeritosid!=''){
                        Persona._V.actualizar_meritos_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'imeritosid' : imeritosid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    
                break;
                case 'eliminar':
                    if(imeritosid!=''){
                        Swal.fire({
                            title: 'Seguro desea eliminar?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                          }).then((result) => {
                            if (result.isConfirmed) {
                                Persona.Ui.btn_meritos_accion(indiv_id,imeritosid,ipersid,'eliminar'); 
                            }
                          })

                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    break;
                default: alert('No se encontró acción'); break;
            }
         },

         btn_meritos_accion : function(indiv_id,imeritosid,ipersid,accion,form){
            
        let meritos = {};
          if(accion!='eliminar'){
            var frm_elements = form.elements;
            for(i=0; i<frm_elements.length; i++)
                    {   
                       
                        switch (frm_elements[i].id)
                        {
                            case "ctipomerito":    
                                var ctipomerito = frm_elements[i].value;
                            break;  
                            case "cdocumentotipo":    
                            var cdocumentotipo = frm_elements[i].value;
                            break;  
                            case "cdocumentonro":    
                            var cdocumentonro = frm_elements[i].value;
                            break;    
                            case "cdocumentofecha":    
                            var cdocumentofecha = frm_elements[i].value;
                            break;    
                            case "cmotivo":    
                            var cmotivo = frm_elements[i].value;
                            break;    
                        }
                    }
                }
            switch(accion){
                case 'agregar':
                          vacio = 0
                          meritos = {
                                accion:accion,
                                ipersid:ipersid,
                                ctipomerito:ctipomerito ? ctipomerito : vacio++,
                                cdocumentotipo:cdocumentotipo ? cdocumentotipo : vacio++,
                                cdocumentonro:cdocumentonro ? cdocumentonro : vacio++,
                                cdocumentofecha:cdocumentofecha ? cdocumentofecha : vacio++,
                                cmotivo:cmotivo ? cmotivo : vacio++,
                            }
                            
                            if(vacio==0){
                                var rs =  Persona.meritos_accion(meritos);
                           
                                if(rs){
                                    Swal.fire(
                                        'ESTUPENDO!',
                                        'Se guardó exitosamente.',
                                        'success'
                                      )
                                    Persona._V.agregar_meritos_legajo.close();
                                    Persona._V.full_info_persona_legajo.close();
                                    Persona._V.full_info_persona_legajo.load({'modal':'meritos','indiv_id' : indiv_id});
                                } 
                            }
                            else{
                                //alert("Debe de completar todos los campos del formulario.");
                                Swal.fire(
                                    'ATENCIÓN!',
                                    'Debe de completar todos los campos del formulario.',
                                    'warning'
                                  )
                            }
                           
                  
                break;
                case 'actualizar':

                    vacio = 0

                    meritos = {
                            accion:accion,
                            ipersid:ipersid,
                            imeritosid:imeritosid ? imeritosid : vacio++,
                            ctipomerito:ctipomerito ? ctipomerito : vacio++,
                            cdocumentotipo:cdocumentotipo ? cdocumentotipo : vacio++,
                            cdocumentonro:cdocumentonro ? cdocumentonro : vacio++,
                            cdocumentofecha:cdocumentofecha ? cdocumentofecha : vacio++,
                            cmotivo:cmotivo ? cmotivo : vacio++,
                        }

                        
                        if(vacio==0){
                        var rs =  Persona.meritos_accion(meritos);
                    
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se actualizó exitosamente.',
                                'success'
                              );
                            
                            Persona._V.actualizar_meritos_legajo.close();
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'meritos','indiv_id' : indiv_id});
                            }
                        }
                        else{
                            Swal.fire(
                                'ATENCIÓN!',
                                'Debe de completar todos los campos del formulario.',
                                'warning'
                              )
                        }
                    
                break;
                case 'eliminar':
                   
                    if(imeritosid!=''){

                        meritos = {
                            accion:accion,
                            ipersid:ipersid,
                            imeritosid:imeritosid
                        }
                       
                        var rs =  Persona.meritos_accion(meritos);

                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se eliminó exitosamente.',
                                'success'
                              )
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'meritos','indiv_id' : indiv_id});
                        }
                       
                    }
                    else{
                        
                        Swal.fire(
                            'ATENCIÓN!',
                            'Debe seleccionar un registro.',
                            'warning'
                          )
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
        },

        btn_demeritos_click_legajo : function(indiv_id,ipersid,accion,idemeritosid){
            switch(accion){
                case 'agregar':
                    Persona._V.agregar_demeritos_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'idemeritosid' : null});
                   
                break;
                case 'actualizar':
                    if(idemeritosid!=''){
                        Persona._V.actualizar_demeritos_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'idemeritosid' : idemeritosid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    
                break;
                case 'eliminar':
                    if(idemeritosid!=''){
                        Swal.fire({
                            title: 'Seguro desea eliminar?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                          }).then((result) => {
                            if (result.isConfirmed) {
                                Persona.Ui.btn_demeritos_accion(indiv_id,idemeritosid,ipersid,'eliminar'); 
                            }
                          })

                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    break;
                default: alert('No se encontró acción'); break;
            }
         },

         btn_demeritos_accion : function(indiv_id,idemeritosid,ipersid,accion,form){
            
            let demeritos = {};
          if(accion!='eliminar'){
            var frm_elements = form.elements;
            for(i=0; i<frm_elements.length; i++)
                    {   
                       
                        switch (frm_elements[i].id)
                        {
                            case "sancion":    
                                var sancion = frm_elements[i].value;
                            break;  
                            case "cdocumentoresolucion":    
                            var cdocumentoresolucion = frm_elements[i].value;
                            break;  
                            case "cdocumentonro":    
                            var cdocumentonro = frm_elements[i].value;
                            break;    
                            case "cfecha_ini":    
                            var cfecha_ini = frm_elements[i].value;
                            break;    
                            case "cfecha_fin":    
                            var cfecha_fin = frm_elements[i].value;
                            break;    
                        }
                    }
                }
            switch(accion){
                case 'agregar':
                          vacio = 0
                          demeritos = {
                                accion:accion,
                                ipersid:ipersid,
                                sancion:sancion ? sancion : vacio++,
                                cdocumentoresolucion:cdocumentoresolucion ? cdocumentoresolucion : vacio++,
                                cdocumentonro:cdocumentonro ? cdocumentonro : vacio++,
                                cfecha_ini:cfecha_ini ? cfecha_ini : vacio++,
                                cfecha_fin:cfecha_fin ? cfecha_fin : vacio++,
                            }
                            
                            if(vacio==0){
                                var rs =  Persona.demeritos_accion(demeritos);
                           
                                if(rs){
                                    Swal.fire(
                                        'ESTUPENDO!',
                                        'Se guardó exitosamente.',
                                        'success'
                                      )
                                    Persona._V.agregar_demeritos_legajo.close();
                                    Persona._V.full_info_persona_legajo.close();
                                    Persona._V.full_info_persona_legajo.load({'modal':'demeritos','indiv_id' : indiv_id});
                                } 
                            }
                            else{
                                //alert("Debe de completar todos los campos del formulario.");
                                Swal.fire(
                                    'ATENCIÓN!',
                                    'Debe de completar todos los campos del formulario.',
                                    'warning'
                                  )
                            }
                           
                  
                break;
                case 'actualizar':

                    vacio = 0

                    demeritos = {
                            accion:accion,
                            ipersid:ipersid,
                            idemeritosid:idemeritosid ? idemeritosid : vacio++,
                            sancion:sancion ? sancion : vacio++,
                            cdocumentoresolucion:cdocumentoresolucion ? cdocumentoresolucion : vacio++,
                            cdocumentonro:cdocumentonro ? cdocumentonro : vacio++,
                            cfecha_ini:cfecha_ini ? cfecha_ini : vacio++,
                            cfecha_fin:cfecha_fin ? cfecha_fin : vacio++,
                        }
                        if(vacio==0){
                        var rs =  Persona.demeritos_accion(demeritos);
                    
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se actualizó exitosamente.',
                                'success'
                              )

                           
                            Persona._V.actualizar_demeritos_legajo.close();
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'demeritos','indiv_id' : indiv_id});
                        }}
                        else{
                            Swal.fire(
                                'ATENCIÓN!',
                                'Debe de completar todos los campos del formulario.',
                                'warning'
                              )
                        }
                    
                break;
                case 'eliminar':
                   
                    if(idemeritosid!=''){

                        demeritos = {
                            accion:accion,
                            ipersid:ipersid,
                            idemeritosid:idemeritosid
                        }
                       
                        var rs =  Persona.demeritos_accion(demeritos);
                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se eliminó exitosamente.',
                                'success'
                              )
                              Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'demeritos','indiv_id' : indiv_id});
                        }
                       
                    }
                    else{
                        
                        Swal.fire(
                            'ATENCIÓN!',
                            'Debe seleccionar un registro.',
                            'warning'
                          )
                    }
                    break;
                default: alert('No se encontró acción');
                break;

            }
        },

        btn_cargaFam_click_legajo : function(indiv_id,ipersid,accion,icarga_familiarid){
        
            switch(accion){
                case 'agregar':
                    Persona._V.agregar_cargaFam_legajo.load({'indiv_id':indiv_id, 'accion':accion,'ipersid':ipersid,'icarga_familiarid' : null});
                break;
                case 'actualizar':
                    if(icarga_familiarid!=''){
                        Persona._V.actualizar_cargaFam_legajo.load({'indiv_id':indiv_id,'accion':accion,'ipersid':ipersid,'icarga_familiarid' : icarga_familiarid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    
                break;
                case 'eliminar':
                   
                    if(icarga_familiarid!=''){
                        Swal.fire({
                            title: 'Seguro desea eliminar?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                          }).then((result) => {
                            if (result.isConfirmed) {
                                Persona.Ui.btn_cargaFam_accion(indiv_id,icarga_familiarid,ipersid,'eliminar'); 
                            }
                          })
                        //Persona._V.eliminar_cargaFam_legajo.load({'ipersid':ipersid,'icarga_familiarid' : icarga_familiarid});
                    }
                    else{
                        alert('Debe seleccionar un registro');
                    }
                    break;
                default: alert('No se encontró acción'); break;

            }
            
         },

         btn_cargaFam_accion : function(indiv_id,icarga_familiarid,ipersid,accion,form){
            let familiar = {};
            if(accion!='eliminar'){
                var frm_elements = form.elements;
                for(i=0; i<frm_elements.length; i++)
                {
                    switch (frm_elements[i].id){
                        case "cape_nom_conyug":
                            var cape_nom_conyug = frm_elements[i].value;
                        break;
                        case "ccel_conyug":
                            var ccel_conyug = frm_elements[i].value;
                        break;
                        case "cape_nom_hijos":
                            var cape_nom_hijos = frm_elements[i].value;
                        break;
                        case "cfechanac_hijos":
                            var cfechanac_hijos = frm_elements[i].value;
                        break;
                        case "ape_nom_padre":
                            var ape_nom_padre = frm_elements[i].value;
                        break;
                        case "cel_padre":
                            var cel_padre = frm_elements[i].value;
                        break;
                        case "ape_nom_madre":
                            var ape_nom_madre = frm_elements[i].value;
                        break;
                        case "cel_madre":
                            var cel_madre = frm_elements[i].value;
                        break;
                        case "dfechasis":
                            var dfechasis = frm_elements[i].value;
                        break;
                    }
                }
            }
            switch(accion){
                case 'agregar':
                    vacio = 0
                    familiar = {
                        accion:accion,
                        ipersid:ipersid,
                        cape_nom_conyug:cape_nom_conyug ? cape_nom_conyug : vacio++,
                        ccel_conyug:ccel_conyug ? ccel_conyug: vacio++,
                        cape_nom_hijos:cape_nom_hijos ? cape_nom_hijos: vacio++,
                        cfechanac_hijos:cfechanac_hijos ? cfechanac_hijos: vacio++,
                        ape_nom_padre:ape_nom_padre ? ape_nom_padre:vacio++,
                        cel_padre:cel_padre ? cel_padre:vacio++,
                        ape_nom_madre:ape_nom_madre ? ape_nom_madre:vacio++,
                        cel_madre:cel_madre ? cel_madre:vacio++,
                        dfechasis:dfechasis ? dfechasis:vacio++
                    }

                    if(vacio==0){
                        var rs = Persona.cargaFam_accion(familiar);

                        if(rs){
                            Swal.fire(
                                'ESTUPENDO!',
                                'Se guardó exitosamente.',
                                'success'
                              )
                            Persona._V.agregar_cargaFam_legajo.close();
                            Persona._V.full_info_persona_legajo.close();
                            Persona._V.full_info_persona_legajo.load({'modal':'familiar','indiv_id' : indiv_id});
                        } 
                    }else {
                        Swal.fire(
                            'ATENCION!',
                            'Debe de completar todos los campos del formulario.',
                            'warning'
                        )
                    }
            break;
            case 'actualizar':
                vacio=0;
                familiar = {
                    accion:accion,
                        ipersid:ipersid,
                        cape_nom_conyug:cape_nom_conyug ? cape_nom_conyug : vacio++,
                        ccel_conyug:ccel_conyug ? ccel_conyug: vacio++,
                        cape_nom_hijos:cape_nom_hijos ? cape_nom_hijos: vacio++,
                        ape_nom_padre:ape_nom_padre ? ape_nom_padre:vacio++,
                        cel_padre:cel_padre ? cel_padre:vacio++,
                        ape_nom_madre:ape_nom_madre ? ape_nom_madre:vacio++,
                        cel_madre:cel_madre ? cel_madre:vacio++,
                        dfechasis:dfechasis ? dfechasis:vacio++
                }
                if(vacio==0){
                    var rs = Persona.cargaFam_accion(familiar);
                    if(rs){
                        Swal.fire(
                            'ESTUPENDO!',
                            'Se actualizó exitosamente.',
                            'success'
                          )
                        Persona._V.actualizar_cargaFam_legajo.close();
                        Persona._V.full_info_persona_legajo.close();
                        Persona._V.full_info_persona_legajo.load({'modal':'familiar','indiv_id' : indiv_id});
                    }
                }
                else{
                    Swal.fire(
                        'ATENCIÓN!',
                        'Debe de completar todos los campos del formulario.',
                        'warning'
                      )
                }
            break;
            case 'eliminar':
                if(icarga_familiarid!=''){
                    familiar={
                        accion:accion,
                        ipersid:ipersid,
                        icarga_familiarid:icarga_familiarid
                    }
                    var rs = Persona.cargaFam_accion(familiar);
                    if (rs){
                        Swal.fire(
                            'ESTUPENDO!',
                            'Se eliminó exitosamente.',
                            'success'
                          )
                        Persona._V.full_info_persona_legajo.close();
                        Persona._V.full_info_persona_legajo.load({'modal':'familiar','indiv_id' : indiv_id});
                    }
                }else {
                    Swal.fire(
                        'ATENCIÓN!',
                        'Debe seleccionar un registro.',
                        'warning'
                    )
                }
                break;
                default:alert('No se encontró acción');
                break;
            }
         },
         

         btn_tblinfoper_legajo_click : function(btn, e){
            
            var codigo = '';      
                    
            for(var i in Persona.Ui.Grids.trabajadores.selection){
                  codigo = i;
            }
		
            if(codigo != '')      
            {   
               // alert(codigo);
                
                Persona._V.full_info_legajo.load({'empkey' : codigo});
            }
            else{
                alert('Debe seleccionar un registro');
            }
            
          
         },

          btn_tblinfoper_2_click : function(btn, e){
            
            var codigo = '';      
                    
            for(var i in Planillas.Ui.Grids.trabajadores_gestionardata.selection){
                  codigo = i;
            }
    
            if(codigo != '')      
            {   
               // alert(codigo);
                
                Persona._V.full_info_persona.load({'empkey' : codigo});
            }
            else{
                alert('Debe seleccionar un registro');
            }
            
          
         },


         btn_tblinfoper_activar_view_click : function(btn, e){
            
            var codigo = '';      
                    
            for(var i in Planillas.Ui.Grids.trabajadores_gestionardata.selection){
                  codigo = i;
            }
    
            if(codigo != '')      
            {   
                if(Persona._M.activar_directo.process({'empkey' : codigo}))
                {
                    var mensaje = Persona._M.activar_directo.data.mensaje;
                    dojo.byId('ptrabajadores_opnotice').innerHTML = mensaje;
                }
            }
            else{
                alert('Debe seleccionar un registro');
            }
          
         },

         btn_cesartrabajador_click : function(btn,e){

              var data = dojo.formToObject('form_info_cesar'), 
                    ok = true;


              if( data.fechacese == null || data.fechacese == '' ) ok = false; 
          

              if(ok)
              {

                if(confirm('Realmente desea cesar al trabajador? ')){ 

                  if(Persona._M.cesar_trabajador.process(data)){

                      Persona._V.retirar.close();
                      Persona.Ui.Grids.historial_laboral.refresh();
                  }

                }

              }
              else{
                 alert('Verifique la fecha de cese.');
              }


         },
         
         btn_regtrabaj_filtrar_click: function(btn,e){
             
                 
             var data = dojo.formToObject('form_registro_consulta');
              /*
              for( f in data){
                   alert(f+' : '+ data[f]);
              }*/
              //JsonRest.prototype.query.call(this, query, options);
              //Persona.Ui.Grids.trabajadores.store.query(data);
              //Persona.Ui.Grids.trabajadores.cleanup();
               Persona.Ui.Grids.trabajadores.refresh();
         },
         

         btn_registrar_beneficiario : function(btn,e){

             Persona._V.registrar_nuevo.load({'tipoindividuo' : app._consts.tipoindividuo_beneficiario });              

         },


         btn_explorarxfechas_click : function(btn,e){

 
            var data = dojo.formToObject('form_filtrarxfechas');


            //Armar las tablas y los recursos

            //var tipo = dojo.byId('hdexplorarxfechas_tipo').value;
              
            var tipo = dijit.byId('dvexplorarfechas_tipoview').get('value');

            var agruparpor = dijit.byId('selxf_agruparpor').get('value');

            var columns_agrupado = {

                numeral: {label:'#'},
                trabajador_nombre: {label: 'Trabajador', sortable: false},
                trabajador_dni: {label: 'DNI', sortable: false},
                trabajador_regimen: {label: 'Regimen Actual', sortable: false},
                periodo : {label: 'Año', sortable: false},
                total : {label: 'Nro de Dias', sortable: false}

            }

 
            if(tipo == 1 )
            {
   
                 controller =  "escalafon/get_comisiones";            

                 dojo.byId('spn_explorarfechas_titulo').innerHTML = ' Comisiones de servicio ';
                 
                 if(agruparpor == 0)
                 {
                   columns = {  

                                       numeral: {label:'#'},
                                       trabajador_nombre: {label: 'Trabajador', sortable: false},
                                       trabajador_dni: {label: 'DNI', sortable: false},
                                       documento: {label: 'Documento', sortable: false},
                                       col3: {label: 'Destino', sortable: false},
                                       desde: {label: 'Desde', sortable: false},
                                       hasta: {label: 'Hasta', sortable: false},
                                       col6: {label: 'Motivo', sortable: false}
                                       
                    };
                  
                 }
                 else
                 {
                     columns =  columns_agrupado 
                     if(agruparpor == 2)
                     {
                        columns.periodo.label = 'Mes';
                     }
                 }
    
            }

           if(tipo == 2 ){
   
                     controller =  "escalafon/get_licencias";            
                    
                    dojo.byId('spn_explorarfechas_titulo').innerHTML = ' Licencias '; 
                      if(agruparpor == 0)
                      {
                           columns = {  
                                     numeral: {label:'#'},
                                     trabajador_nombre: {label: 'Trabajador', sortable: false},
                                     trabajador_dni: {label: 'DNI', sortable: false},
                                     trabajador_regimen: {label: 'Regimen Actual', sortable: false},
                                     documento: {label: 'Documento', sortable: false},
                                     desde: {label: 'Desde', sortable: false},
                                     hasta: {label: 'Hasta', sortable: false},
                                     tipo: {label: 'Tipo de Licencia', sortable: false}
                                               
                            };

                       
                      }
                      else
                      {
                          columns =  columns_agrupado 
                          if(agruparpor == 2)
                          {
                             columns.periodo.label = 'Mes';
                          }
                      }
                           
           }

          if(tipo == 3 ){
   
                      controller =  "escalafon/get_permisos";            
                      
                      dojo.byId('spn_explorarfechas_titulo').innerHTML = ' Permisos';

                      if(agruparpor == 0)
                      { 

                        columns = {  
                                     numeral: {label:'#'},
                                     trabajador_nombre: {label: 'Trabajador', sortable: false},
                                     trabajador_dni: {label: 'DNI', sortable: false},
                                     trabajador_regimen: {label: 'Regimen Actual', sortable: false},
                                     documento: {label: 'Documento', sortable: false},
                                     desde: {label: 'Fecha', sortable: false},
                                     hsalida: {label: 'Hora salida', sortable: false},
                                     hingreso: {label: 'Hora regreso', sortable: false} 
                                    
                                           
                        };
                       
                      }
                      else
                      {
                          columns =  columns_agrupado 
                          columns.total.label = 'Nro de Minutos';
                          if(agruparpor == 2)
                          {
                             columns.periodo.label = 'Mes';

                          }
                      }
           }



           if(tipo == 5 ){
           
                   controller =  "escalafon/descansos";            
                       
                   dojo.byId('spn_explorarfechas_titulo').innerHTML = 'Descansos Médicos ';

                  if(agruparpor == 0)
                  {
                        columns = {  
                                 numeral: {label:'#'},
                                 trabajador_nombre: {label: 'Trabajador', sortable: false},
                                 trabajador_dni: {label: 'DNI', sortable: false},
                                 trabajador_regimen: {label: 'Régimen Actual', sortable: false},
                                 documento: {label: 'Documento', sortable: false},
                                 desde: {label: 'Desde', sortable: false},
                                 hasta: {label: 'Hasta', sortable: false}, 
                                 dias: {label: 'Dias', sortable: false} 
                                           
                        };
                   
                  }
                  else
                  {
                      columns =  columns_agrupado 
                      if(agruparpor == 2)
                      {
                         columns.periodo.label = 'Mes';
                      }
                  }
                            
            } 



            if(tipo == 6 ){
                      
                      dojo.byId('spn_explorarfechas_titulo').innerHTML = ' Faltas ';
                        if(agruparpor == 0)
                        {
                              columns = {  
                                         numeral: {label:'#'},
                                         trabajador_nombre: {label: 'Trabajador', sortable: false},
                                         trabajador_dni: {label: 'DNI', sortable: false},
                                         desde: {label: 'Desde', sortable: false},
                                         hasta: {label: 'Hasta', sortable: false}, 
                                         justificada: {label: 'Justificada', sortable: false},
                                         justificacion: {label: 'Justificacion', sortable: false}
                                                 
                              };
                         
                        }
                        else
                        {
                            columns =  columns_agrupado 
                            if(agruparpor == 2)
                            {
                               columns.periodo.label = 'Mes';
                            }
                        }     
             } 
            


            if(tipo == 7 ){
            
                        controller =  "escalafon/tardanzas";            
                        dojo.byId('spn_explorarfechas_titulo').innerHTML = ' Tardanzas ';

                        if(agruparpor == 0)
                        {
                             columns = {  
                                           numeral: {label:'#'},
                                           trabajador_nombre: {label: 'Trabajador', sortable: false},
                                           trabajador_dni: {label: 'DNI', sortable: false},
                                           desde: {label: 'Fecha', sortable: false},
                                           minutos: {label: 'Minutos', sortable: false},
                                           justificacion: {label: 'Justificacion', sortable: false}
                                                   
                                };
                         
                        }
                        else
                        {
                            columns =  columns_agrupado 
                            columns.total.label = 'Nro de Minutos';
                            if(agruparpor == 2)
                            {
                               columns.periodo.label = 'Mes';
                            }
                        }
                             
             } 
              

            if(agruparpor == 0)
            {
               dijit.byId('btn_explorarfechas_verdetalle').set('disabled', false);
            }
            else
            {
                dijit.byId('btn_explorarfechas_verdetalle').set('disabled', true);
            }

/*            console.log(Persona.Ui.Grids.explorar_por_fechas.store);

            Persona.Ui.Grids.explorar_por_fechas.store.target = '../www.google.com.pe';*/

            Persona.Ui.Grids.explorar_por_fechas.set('columns', columns);
 
          //  Persona._M.get_view_explorarxfechas.send(data);
         

         },


         btn_exfverdetalle_click : function(btn, e){
            
              var codigo = '', tipo = dijit.byId('dvexplorarfechas_tipoview').get('value');     
                      
              for(var i in  Persona.Ui.Grids.explorar_por_fechas.selection){
                    codigo = i;
              }
              
      
              if(codigo != '')      
              {   
                  
               
                 if(tipo == '1'){
                      Persona._V.view_comision.load({'codigo' : codigo});      
                 }
                 else if(tipo == '2'){
                      Persona._V.view_licencia.load({'codigo' : codigo}); 
                 }
                 else if(tipo == '3'){
                      Persona._V.view_permiso.load({'codigo' : codigo});   
                 }
                 else if(tipo == '5'){
                      Persona._V.view_descanso.load({'codigo' : codigo});    
                 }
                 else if(tipo == '6'){
                      Persona._V.view_falta.load({'codigo' : codigo});    
                 }
                 else if(tipo == '7'){
                      Persona._V.view_tardanzas.load({'codigo' : codigo});    
                 }
                
                  

              }
              else{
                  alert('Debe seleccionar un registro');
              }
              
          
         },


         btn_viewadjuntar_click : function(btn, evt, window_view){
              var data = {'data' : dojo.query('.data',btn.parentNode)[0].value  }
              Persona.window_upload_file = window_view;
              Persona._V.view_adjuntar_doc.load(data);
         },
          


         btn_subirfile_click : function(btn, evt){

              var data = {'data' : dojo.query('.data',btn.parentNode)[0].value  }
              if(confirm('Realmente desea adjuntar el archivo? ')){ 
                   dojo.setStyle(dojo.byId('dv_files_buttonsubir'), 'display', 'none');
                   dojo.setStyle(dojo.byId('dv_files_subir'), 'display', 'block');
                   dojo.byId('formadjfiles_newviatico').submit();
              }
              
         },

 

         reset_forms : function(){
             
             var forms = [''];
             
             
             
         },
         
         reset_form_historial: function(){
              
               
              var hide_fields = ['trhis_periodo', 'trhis_terminocontrato', 'trhis_plaza', 'trhis_monto', 'trhis_cat', 'trhis_doc', 'trhis_infosisgedo',
                                 'trhis_depe',  'trhis_cargo', 'trhis_obs' ]; // 'trhis_proy',
              
              dojo.forEach(hide_fields, function(f,ind){
                  
                   dojo.setStyle(f,'display','none');
                  
              });
              
               dijit.byId('selhis_depe').set('readOnly',false);
               dijit.byId('selhis_cargo').set('readOnly',false);

               Sisgedo.desvincular_doc('trhis_infosisgedo');
               
            //   dijit.byId('chhis_proyecto').set('checked',false);
              
         }
         
 }