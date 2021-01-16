<?php

class planillatiposunat extends Table{
    
    protected $_FIELDS=array(   
                                    'id'    => 'platisu_id',
                                    'code'  => 'platisu_key',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => 'platisu_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_tipo_sunat';
    protected $_PREF_TABLE= 'PLATISU'; 
    
    
    public function __construct()
    {
          
        parent::__construct();
          
    }
    
    public function get($plati_id = 0)
    {

        $sql = " SELECT * FROM planillas.planilla_tipo_sunat WHERE platisu_estado = 1 AND plati_id = ?  LIMIT 1";

        list($rs) = $this->_CI->db->query($sql, array($plati_id))->result_array();

        return $rs;
    }   


    public function registrar($plati_id, $values = array())
    {

       $datos_actuales = $this->get($plati_id);

       if($datos_actuales['platisu_id'] != '')
       {

           $cambios_encontrados =  false;

           foreach($values as $key => $value)
           { 

                if( trim($datos_actuales[$key]) != '')
                {
                     if(  trim($value)  != trim($datos_actuales[$key]) )
                     {  
                         $cambios_encontrados = true;
                     }   
                }
           }    

        }
        else
        {
            $cambios_encontrados = true;
        }

       $ok = true;

       if($cambios_encontrados)
       {

             // Actualizar el registro a cero

            $sql =" UPDATE planillas.planilla_tipo_sunat SET platisu_estado = 0 WHERE plati_id = ? ";
            $this->_CI->db->query($sql, array($plati_id));

            $values_trabajadores = $values;

            $values['plati_id'] = $plati_id;

            // registramos los nuevos datos
            $ok =  parent::registrar($values, false);

            if($ok === TRUE)
            {   

                $this->_CI->load->library(array('App/individuosunat'));

                // actualizar los trabajadores
                $sql = " SELECT indiv.indiv_id, indiv.indiv_ruc,  indsu_id 
                         FROM public.individuo indiv 
                         INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla.persla_ultimo = 1
                         LEFT JOIN planillas.individuo_sunat insu ON indiv.indiv_id = insu.indiv_id AND  indsu_estado = 1 
                         WHERE indiv.indiv_estado = 1  AND persla.plati_id = ?
                         ORDER BY indiv_id 
                       ";

                $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();

                foreach ($rs as $reg)
                {
                    $values_trabajadores['ruc'] = $reg['indiv_ruc'];

                    $indiv_id = $reg['indiv_id'];
                    
                    /*if( trim($reg['indsu_id']) != '')
                    {
                        // ACTUALIZAR
                        $id = $reg['indsu_id'];

                        $this->_CI->individuosunat->actualizar($id, $values_trabajadores, false);

                    }
                    else
                    {   
                        $values_trabajadores['indiv_id'] = $reg['indiv_id'];
                        // REGISTRAR

                        $this->_CI->individuosunat->registrar($values_trabajadores, false);

                    }*/

                    $rs = $this->individuosunat->registrar($indiv_id, $values_trabajadores);     
                }


            }
 
 
       }

       return $ok;
    }
}