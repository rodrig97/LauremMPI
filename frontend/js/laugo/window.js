/* 
  */


// Laugo.Window
dojo.declare('Laugo.View.Window',null,{
    
    
    app: null, //Laugo.App Instance
    
    connect_url : '', // URL de la vista
       
    data_permanent: {}, // 
     
    last_query: {},
    
    loader_show: null, // mostrar loader
    
    loader_hide: null, // olcultar cargador
    
    title: '',
    
    wd_dialog: null,
    
    style :  {},
    
    style_txt : '',
    
    onLoad : function(){},
    
    onClose : function(){},
      
    constructor: function(opts){
        
        this.title=  (  opts.title  != null ) ? '<span style="font-size:12px">'+opts.title+'</span>' : '';
        
        if(this.app == null && opts.app != null){
             
             this.app = opts.app;
        }
        else if(this.app == null && opts.app != null){
            
             console.log('App no definido para view: '+this);
        } 
        
       if(opts.loader_show != null &&  dojo.isFunction(opts.loader_hide))  this.loader_show = opts.loader_show;
       if(opts.loader_hide != null &&  dojo.isFunction(opts.loader_hide))  this.loader_hide = opts.loader_hide; 
        
        var b = false;
        
        for (var style in opts.style ){
         
              if(b) this.style_txt+=';'; 
              this.style_txt += style+':'+opts.style[style] + '';
              b = true;
        }
         
       //  this.style_txt = 'width: 900px; height: 400px; background-color:#FFFFFF;';
     
       this.onLoad = ( dojo.isFunction(opts.onLoad ) )? opts.onLoad : function(){return true;}
       this.onClose = ( dojo.isFunction(opts.onClose ) )? opts.onClose : function(){return true;}

       this.connect_url =  this.app.getUrl()+opts.connect;
        if(this.app == null && opts.app != null){
             
             this.app = opts.app;
        }
        else if(this.app == null && opts.app != null){
            
             console.log('App no definido para view: '+this);
            
        } 
        
       if(opts.loader_show != null &&  dojo.isFunction(opts.loader_hide))  this.loader_show = opts.loader_show;
       if(opts.loader_hide != null &&  dojo.isFunction(opts.loader_hide))  this.loader_hide = opts.loader_hide; 
        
         
        
       this.onLoad = ( dojo.isFunction(opts.onLoad ) )? opts.onLoad : function(){return true;}
       this.onClose = ( dojo.isFunction(opts.onClose ) )? opts.onClose : function(){return true;}

       this.connect_url =  this.app.getUrl()+opts.connect;
        
    },
    
    
    load: function(data){
          
       this.last_query = data;  
     
       if(this.loader_show != null ) 
       {    
              this.loader_show();
       }
       else{
              this.app.loader_show();
       }
       
       
       //console.log('[MVC.MODEL.MESSAGE] Conectando con el modelo:'+this.connect_url); 
       var self = this;
    
       dojo.xhrPost({
            
            sync: true, // Syncrono: true, osea que ocupa el hilo principal de javascript
            
            url:  self.connect_url,
    
            content:  data,
            handleAs: 'text',
            
            load: function(reponseText){
   
   //              console.log('[MVC.MODEL.MESSAGE] Conexion finalizada con el modelo:'+self.connect_url); 
                 
                  self.wd_dialog = new dijit.Dialog({
                                title:  self.title,
                                content:  reponseText, 
                                style:  self.style_txt,
                                onCancel : function(){
                                         
                                        dijit.registry.destroy_childrens( self.wd_dialog.domNode ); // destruir los widgets DIJIT
                                        
                                        if(dojo.query('.window_container',self.wd_dialog.domNode)[0] != null){
                                            
                                            dojo.destroy( dojo.query('.window_container',self.wd_dialog.domNode)[0] ); // para destruir todo el contenido de la ventana sin peder el efecto de desvanecimiento
                                      
                                        }   

                                        return self.onClose();
                                        
                                }
                            });
                            
                 self.wd_dialog.show();           
                 self.onLoad(); 
                  
            },
            error: function(err){
                 console.log('[MVC.VIEW.MESSAGE] Ocurrio un error '+err+' durante el procesamiento de la vista:'+self.connect_url); 
 
            },
            handle: function(response, ioArgs){
                 
                  if(self.loader_hide != null ) 
                  {    
                      self.loader_hide();
                  }
                  else{
                      self.app.loader_hide();
                  }
                
            }

        });
        
       
       // return  (this.return_full_object || return_full_object )?  this.object_response : this.result ;
   
    },
    
    
    close: function(){
          
          this.wd_dialog.onCancel();
    },
    
    
    refresh: function(){
         
         
         this.close();
         
         this.load(  this.last_query );
         
    }
    
    
});
 