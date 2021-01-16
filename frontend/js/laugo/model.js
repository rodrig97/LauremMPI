
/* 
 *  PATTERN Laugo Model

 */

dojo.declare('Laugo.Model',null,{
    
    
    app: null, //Laugo.App Instance
    
    connect_url : '', // URL del MODELO
      
    mensaje: '', // ultimo mensaje (responseJSON.mensaje) recibido
    
    send_datat: {}, // 
    
    result: 0, //ultimo resultado obtenido
     
    return_full_object: false,
    
    object_response: {},
    
    data : {},
    
    last_query: {},
    
    data_permanent : {},
    
    loader_show: null, // mostrar loader
    
    loader_hide: null, // olcultar cargador

    message_function: function(mensaje,data_response){
    
             alert(mensaje);
    },

    constructor: function(opts){
        
        
        if(this.app == null && opts.app != null){
             
             this.app = opts.app;
        }
        else if(this.app == null && opts.app != null){
            
             console.log('App no definido para Model: '+this);
        } 
        
        if(opts.loader_show != null &&  dojo.isFunction(opts.loader_hide))  this.loader_show = opts.loader_show;
        if(opts.loader_hide != null &&  dojo.isFunction(opts.loader_hide))  this.loader_hide = opts.loader_hide; 
       
        if(opts.message_function != null &&  dojo.isFunction(opts.message_function))  this.message_function = opts.message_function; 

        this.timeout = ( opts.timeout != null ) ? opts.timeout : 100000;
 
         this.connect_url =  this.app.getUrl()+opts.connect;
        
    },
     
    process: function(data, return_full_object){
         
       data = data || {}  
       return_full_object  = (arguments[1] != null) ? return_full_object : false; 
       
       for(var d in this.data_permanent){
           data[d] = this.data_permanent[d];
       }
       
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
       
       var n_data = {}
        
       var on_error = false;
       
       dojo.xhrPost({
            
            sync: true, // Syncrono: true, osea que ocupa el hilo principal de javascript
            
            url:  self.connect_url,
            timeout: self.timeout,
            content:  data,
            handleAs: 'json',
            
            load: function(responseJSON){
    
                  self.object_response = responseJSON;
                  self.data = responseJSON.data;
                  self.mensaje = responseJSON.mensaje;

                  if(responseJSON.result=='1')
                  {
                  
                       self.result = true;

                  }
                  else if(responseJSON.result=='0')
                  {

                      self.result = false;

                  }
                  else
                  {
                       self.result = null;
                       
                  }
                  
                  self.mensaje = responseJSON.mensaje;
                  self.data = responseJSON.data;
                  
                  if(self.message_function != null && dojo.isFunction(self.message_function)) self.message_function(self.mensaje, self.object_response);
                  
                  
            },
            error: function(err){

                 on_error = true;

                 var datos_str = '';
                 
                 for(x in data)
                 {
                    datos_str+=' '+x+': '+data[x];
                 }

                 app.alert('Algo no salio bien durante esta operación: <br/> '+ self.connect_url +' <br/> Con estos datos: <br/>'+datos_str);
                 
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
        
       
        console.log(on_error);
       
       if(!on_error){


          return  (  this.return_full_object || return_full_object ) ?   this.object_response : this.result ;
   
       }
       else{

           return false;
       }
   
    }
    
    
});
 