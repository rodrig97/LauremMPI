/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var Tareas = {
    
    _Models :  {
        
    },
    
    Ui: {
         
    },
    //
    $M: {
        rq_add_detalle : {
            
        }
    },
    
    $V: {
         // Abrir una ventana
    },
    
    $C:{
        
       //  abrir_ventana('');
        ver_acciones : function([acti],{}){
             $V.rq_acciones.send({actividad: acti});
        }
    }
      
}


$H.open_out_page('imprimir_requerimiento',{ });
 
