

Helpers = {$: null}

// ELEMENT HELPERS

function $_(el){
     
    return dojo.byId(el);
}

function $__(el){
    
    return dijit.byId(el);
    
}

function $_hide(el){
       dojo.style(el, {'display' : 'none'});
      
}

function $_show(el){
       dojo.style(el, {'display' : 'block'});
}

function $_setCenter(el){
      
      var dims=dojo.window.getBox();
       
      var coors=dojo.position(el,true);
      if(el != null && el != undefined){ 
          dojo.setStyle(el ,{ 
                                        'top'  : ((dims.h-coors.h)/2)+'px',
                                        'left' : ((dims.w-coors.w)/2)+'px',
                                        'position' : 'fixed'
          });  
      }
      
}


// FORM HELPER
function $_chain_inputs(els){
    
    var ele = null;
    var n_ele = null;
    dojo.forEach(els,function(el,it){
        
       ele = (dijit.byId(el)!= null ) ? dijit.byId(el)  : ( (dojo.byId(el) != null ) ? dojo.byId(el)  : null );
       
       if(ele != null){ 
           dojo.connect( ele ,'onKeyUp',function(e){
                if(e.keyCode == dojo.keys.ENTER){
                     n_ele = dojo.byId(els[it+1]); 
                     if( n_ele != null && n_ele != undefined) n_ele.focus();
                }
           });
       }
       
    });
    
}


// MATH HELPERS

function $_random(min, max){
    return Math.floor(Math.random() * (max - min + 1) + min);
}



/* Otthers helpers */

function $_comprobar_dni(dni){
    
    if(dni.length==8){
        return true;
    }
    else{
        return false;
    }
    
}
 

function $_keycode_isnumber(code){
    return ((code>=48 && code<=57)  || (code>=96 && code<=105) ) ? true : false;
}
  
  
  
function $_currentDate()
{
    var fechaActual = new Date();
 
    var dia = fechaActual.getDate();
    var mes = fechaActual.getMonth() +1;
    var anno = fechaActual.getFullYear();
   
 
    if (dia <10) dia = "0" + dia;
    if (mes <10) mes = "0" + mes;  
 
    var fechaHoy = dia + "/" + mes + "/" + anno;
   
    return fechaActual;
}
