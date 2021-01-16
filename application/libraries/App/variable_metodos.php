<?php

class variable_metodos
{
    
    private $_CI;
 
    public function __construct(){

        $this->_CI=& get_instance(); 
        $this->_CI->load->database(); 
     
    }
    

    public function numero_dias_planilla($params = array())
    {   
        if(sizeof($params) == 2 )
        {

              $fecha_fin = strtotime($params[1]);
              $fecha_ini = strtotime($params[0]);
              
              if ($fecha_fin < $fecha_ini) { $tmp = $fecha_ini; $fecha_ini = $fecha_fin; $fecha_fin = $tmp; }
              
              $resultado = ($fecha_fin - $fecha_ini);
              
              $resultado = $resultado / 60 / 60 / 24;  
              $resultado = round($resultado);
              
             
              return ($resultado + 1);
        }

        return 0;    
    }


    public function numero_domingos_planilla($params = array())
    {   
 
        if(sizeof($params) == 2 )
        {

              $num_domingos = 0;

              $fecha_fin = strtotime($params[1]);
              $fecha_ini = strtotime($params[0]);

              if ($fecha_fin < $fecha_ini) { $tmp = $fecha_ini; $fecha_ini = $fecha_fin; $fecha_fin = $tmp; }
             
              $fecha = $fecha_ini;
  
              while( $fecha <= $fecha_fin )
              {
              
                   if(date('w', $fecha ) == 0)
                   {
                        $num_domingos++;
                   }  

                   $dia = mktime(0, 0, 0, date("m", $fecha)  , date("d" , $fecha )+1, date("Y", $fecha));
                   $dia = date("d-m-Y", $dia);
                    
                   $fecha = strtotime($dia); 
                    
              }  
                
              return $num_domingos;

        }
        

        return 0;
    }



    public function multiplicar($params = array())
    { 
      
       $total = $params['valor'] * $params['multiplo'];
       return $total;
    }



    public function contar_hijos_escolaridad($params = array())
    {  

        $sql = " SELECT pers_id, 
                        count(pefa_id) as total_registros
                 FROM rh.persona_familia pefa
                 WHERE pefa_estado = 1 AND paren_id = 4 AND pefa_estudiante = 1
                       AND COALESCE(date_part('YEAR', age(now(), pefa.pefa_fechanace) ),0) <= ?
                       AND pefa.pers_id = ? 
                 GROUP BY pefa.pers_id ";

        $rs = $this->_CI->db->query($sql, array(EDAD_MAXIMA_ESCOLARIDAD, $params['indiv_id']))->result_array();
        
        if(sizeof($rs) == 1)
        {
           return ($rs[0]['total_registros'] * 1);
        }
        else
        {
           return 0;
        }
          
    }

}