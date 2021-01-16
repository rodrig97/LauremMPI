 
dojo.declare('Request', null,
{
      
      method: 0,
      url : '',
      
      type : 'text', // JSON or XML parsecontent
      
      onRequest: null,
      
      onSuccess: null,
      
      onFailure: null,
      
      onComplete : null,
      
      timeout: 600000, // 20 segundos de espera
      
      content: {},
      
      isRunning : false,
      
      last_req : null,
      
      sync: false,
      
      constructor: function(opts)
      {
           
          if(  opts.method != null && opts.method != undefined  &&  opts.method.toLowerCase() == 'post'  ) this.method = 1;
          if( opts.url != null  && opts.url != undefined ) this.url =  opts.url;
          if( opts.timeout != null && opts.timeout != undefined ) this.timeout =  opts.timeout;
          if( opts.type != null && opts.type != undefined && (opts.type == 'json' || opts.type == 'xml' || opts.type === false)) this.type = opts.type;
          this.onRequest = (opts.onRequest != null) ? opts.onRequest  : function(){};
          this.onSuccess = (opts.onSuccess != null) ? opts.onSuccess  : function(){};
          this.onFailure = (opts.onFailure != null) ? opts.onFailure  : function(){};
          this.onComplete  = (opts.onComplete != null) ? opts.onComplete  : function(){};
          this.sync  = (opts.sync != null) ? opts.sync  : false;
      },
      
      send: function(req)
      {
          
          this.isRunning= true;
          this.onRequest();
          var params= this;  
          
          this.last_req = req;
          
          if(this.method===1){  // SI POST
           
              dojo.xhrPost({
                    sync: params.sync, 
                    url:  params.url,
                    timeout: params.timeout,
                    content:  req,
                    handleAs: params.type,
                    load: function(response) {
                         params.onSuccess(response);
                    },
                    error: function(err){
                         params.onFailure(err);

                         var datos_str = '';
                         for(x in req)
                         {
                            datos_str+=' '+x+': '+req[x];
                         }

                         app.alert('Algo no salio bien durante esta operación: <br/> '+ params.url+' <br/> Con estos datos: <br/>'+datos_str);
                    },
                    handle: function(response, ioArgs){
                        params.isRunning = false;
                        params.onComplete(response, ioArgs);
                    }
                     
                });
              
          }
          else{
               dojo.xhrGet({
                    sync: params.sync, 
                    url:  params.url,
                    timeout: params.timeout,
                    content:  req,
                    handleAs: params.type,
                    load: function(response) {
                         params.onSuccess(response);
                    },
                    error: function(err){
                         params.onFailure(err);
                         
                        
                         var datos_str = '';
                         for(x in req)
                         {
                            datos_str+=' '+x+': '+req[x];
                         }
                         
                         app.alert('Algo no salio bien durante esta operación: <br/> '+ params.url+' <br/> Con estos datos: <br/>'+datos_str+' ');

                    },
                    handle: function(response, ioArgs){
                        params.isRunning = false;
                        params.onComplete(response, ioArgs);
                    }
                     
                });
          }
          
      },


      reload : function(){

          this.send(this.last_req);
      }

        
});
