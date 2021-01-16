<?php


class Resources{
    
    private $CI;
    private $_paths;
     
    public function __construct()
    {
        // Do something with $params
        $this->CI=&get_instance(); 
       
        
        $this->_paths=array( 'css'=> 'frontend/css/',
                             'js' => 'frontend/js/', 
                             'images'=> 'frontend/images/',
                             'flash' => 'frontend/flash'
                           );
        
        
    }
    
    public function url($f,$t = false) // 'css' , 'js' , 'images' , 'flash'   
    {
              $this->CI->load->helper('url');
            
              return  base_url().$this->_paths[$f];
    }
 
     public function getCss($file){

                //@alias  => nombreAtributo
                $aliasOpsCss=array('atri1'=> 'prueba-atributo', 'atri2'=> 'atributo2');
                $params=func_get_args();
                $opsDef=null;
                if(func_num_args()<=2 )
                {
                    
                    if(func_num_args()>1) $opsDef=$params[1];

                    if(is_array($params[0]))
                    {
                        //Recorremos los parametros enviados    
                        foreach($params[0] as $file => $opts ){

                              $l='<link rel="stylesheet" ';

                              if(is_array($opts))
                              {
                                  foreach($opts as $opt => $v ) $l.= $aliasOpsCss[$opt].' = "'.$v.'"';   //Si se especifica un array con los parametros del link CSS
                              }
                              else if( $opts != false )  {
                                  $file=$opts;
                                  foreach($opsDef as $opt => $v ) $l.= $aliasOpsCss[$opt].' = "'.$v.'"'; // Si no se especifica el segundo parametro ni se coloca false, carga los atributos x defecto

                              }
                              else{
                                 // $file=$opts;
                              }
                              $l.=' href="'.$this->url('css',false).$file.'.css"> ';

                              echo $l;
                        }
                    }
                    else if(func_num_args()==2 && !is_array($params[1])) 
                    {
                          foreach($params as $file) echo '<link rel="stylesheet" href="'.$this->url('css',false).$file.'.css"> ';
                    }
                    else{
                          $l='<link rel="stylesheet" ';  
                          if(is_array($opsDef)) foreach($opsDef as $opt => $v ) $l.= $aliasOpsCss[$opt].' = "'.$v.'"';   
                          $l.=' href="'.$this->url('css',false).$params[0].'.css">';
                          echo $l;
                    }

                }
                else{ // Si hay mas de 2 parametros, supone que cada parametro es un archivo sin configuracion extra
                    foreach($params as $file) echo '<link rel="stylesheet" href="'.$this->url('css',false).$file.'.css"> ';
                }

            
        }

        public function getJs($files ,$opts=array() ){
            
            if(!is_array($files)) $files=func_get_args ();
            foreach($files as $file){
                echo '<script src="'.$this->url('js',false).$file.'.js" '; 
                foreach($opts as $opt => $v ) echo  $opt.'="'.$v.'"';
                echo '> </script>';
            }
        }
        
        public function getDojo (){
            
        }
       
        public function getFlash(){
            //parametros de objeto flash x investigar    
        }

        public function getImage($file,$opts=array(),$print = true){
            $l='<img src="'.$this->url('images',false).$file.'" ';
            foreach($opts as $opt => $v ) $l.=  $opt.' = "'.$v.'"';
            $l.='/>';
            
            if($print){
                echo $l;
            }
            else{
                return $l;
            }
          
        }
        
    
    

}

?>