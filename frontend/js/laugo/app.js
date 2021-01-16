 

dojo.declare('Laugo.App',null,{
   
     name : '',
     
     url : 'Http://localhost',
     
     version : 'v0.00',
     
     cortina: 'ge_cortina',
     loader:  'ge_loader',

     progressbar_div : 'dv_loader_progressbar',
     
     trace : true,
     
     options : {

         zindex_loader : 10000,
         interface_view: {
              header_height : 65,
              body_height   : 0,
              footer_height : 10
         }
     },
     
     page_struct: {
                container   : 'page',
                header      : 'header',
                body        : 'bodi',
                body_content : 'bodi_content',
                footer      : 'footer'
     }, 
     
     _consts : {}, // contenedor de constantes
     
     constructor : function(opts){
     
        if(opts.name != null && opts.name != undefined) this.name = opts.name;
        if(opts.url != null && opts.url != undefined)  this.url = opts.url;
        if(opts.version != null && opts.version != undefined)  this.version = opts.version;
       
       
     },
     
     getUrl : function(){
          return this.url;
     },
     
     loader_show: function(){
            
            dojo.style(this.cortina, {'z-index' : this.options.zindex_loader  });
            dojo.style(this.loader, {'z-index' : this.options.zindex_loader +1  });    
            dojo.style(this.cortina, {'display' : 'block'  });
            dojo.style(this.loader, {'display' : 'block'   });
            
            $_setCenter(this.loader);
                 
     },
     
     loader_hide: function(){
          
           dojo.style(this.cortina, {'display' : 'none'  });
           dojo.style(this.loader, {'display' : 'none'   });
         
     },

     loader_pb_show: function(){
        /*    
            dojo.style(this.progressbar_div, {'z-index' : this.options.zindex_loader  });
            dojo.style(this.loader, {'z-index' : this.options.zindex_loader +1  });    
            dojo.style(this.progressbar_div, {'display' : 'block'  });
            dojo.style(this.loader, {'display' : 'block'   });*/
            if(dijit.byId('pbimportarexcel') != null)
            {
              dijit.byId('pbimportarexcel').set('value',0);
              dijit.byId('pbimportarexcel').set('maximum', 100);
            }
 
            dojo.style(this.cortina, {'z-index' : this.options.zindex_loader  });
            dojo.style(this.progressbar_div, {'z-index' : this.options.zindex_progressbar_div +1  });    
            dojo.style(this.cortina, {'display' : 'block'  });
            dojo.style(this.progressbar_div, {'display' : 'block'   });
            
            $_setCenter(this.progressbar_div);
                 
     },
     
     loader_pb_hide: function(){
          
           dojo.style(this.progressbar_div, {'display' : 'none'  });
           dojo.style(this.loader, {'display' : 'none'   });
         
     },

     alert: function(mensaje){

           var myDialog = new dijit.Dialog({
                                            title: "Atenci&oacute;n",
                                            content:  '<div style="padding: 4px 4px 4px 4px;"> ' +mensaje + '</div>',
                                            style: "width: 350px"
                                        });
           
           myDialog.show();

     },
     
     render_bodi: function(){
           
           if(dojo.byId('bodi') != null ){
               
               var window_dims = dojo.window.getBox();
               var nb = window_dims.h - ( this.options.interface_view.header_height + this.options.interface_view.footer_height   );  
               dojo.setStyle(dojo.byId('bodi'),'height',nb+'px'); 
               
               if(dojo.byId('bodi_content') != null){
                   var bbch = nb -40;
                   dojo.setStyle(dojo.byId('bodi_content'),'height',bbch+'px'); 
               }
               
           }
           
        
        
     },
     
     show_message_error : function(){
         
     },
           
       views_loaded_historial: [],
       views_loaded_historial_i : 0,
       views_current_loaded : null,
       views_current_fn_exec: null,
       views_last_loaded_params : null, 
       views_last_loaded_firein : null,


       ready_view_plattaform : function(){

            var parent = this; 
            this.rq = new Request({


                                    method: 'post',

                                    onRequest: function(){
                                           app.loader_show();
                                    },

                                    onSuccess: function(responseText){

                                           app.loader_hide();

                                           dijit.registry.destroy_childrens( dojo.byId(parent.page_struct.body_content) );  

                                           if(dojo.byId(parent.page_struct.body)!=null) dojo.attr(dojo.byId(parent.page_struct.body_content),'innerHTML',responseText);

                                           dojo.parser.parse( dojo.byId(parent.page_struct.body_content) );

                                           if( dojo.isFunction(parent.views_current_fn_exec)) parent.views_current_fn_exec();

                                    },

                                    onFailure: function(){
                                           app.loader_hide();

                                    }


                                });

       }, 

      view_load: function(fireIn,params){

         this.views_last_loaded_params                              = params; 
         this.views_last_loaded_firein                              = (fireIn != null) ? fireIn : null;
         this.views_current_loaded                                  = (params.view != null && params.view != undefined ) ? dojo.trim(params.view) : '';
         this.views_loaded_historial[this.views_loaded_historial_i] =  this.views_current_loaded ;
         this.views_loaded_historial_i++;
         
         this.views_current_fn_exec = ( dojo.isFunction(params.fn)) ?  params.fn  : function(){ };  

         this.rq.url = params.view;
         if(params.data == null || params.data == undefined) params.data = {}
         var send_me = params.data;
         send_me.ajax = 'true';
          
         this.rq.send(send_me);

     },

     view_refresh: function(){

        if(this.views_last_loaded_firein != null) dojo.byId(this.views_last_loaded_firein).click;
     },
     
      
     register_operation: function(){
         
     },
     
     unregister_operation: function(){
         
     },
       
     get_dims_body_app : function(){
             
          var obj = dojo.contentBox(dojo.byId(this.page_struct.body_content));
 
          return obj;
           
     },
     
     set_enviorement : function(c){
          
          this.current_enviorement = c;
          
     },
     
     get_enviorement: function(c){
      
          return  this.current_enviorement;
      
     },
      
     write : function(mensaje, tipo){
         
         mensaje =  mensaje || '';
         if(this.trace){
              console.log(mensaje);
         }
         
     },
     
     saveLog : function(mensaje ){
         
         mensaje = mensaje || {}
         // GUARDAR EN LOCAL STORAGE 
     },
     
     sendLog : function(){
         
     },
      
     listenProcess : function(){  //  app.listenProcess(app.loader, 30000);
         
     },
     
     online : function(){
          
     }
      
});
 