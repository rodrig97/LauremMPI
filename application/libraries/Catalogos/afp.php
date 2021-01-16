<?php

 
class afp extends Table{
     
     
    protected $_FIELDS=array(   
                                    'id'    => 'afp_id',
                                    'code'  => 'afp_key',
                                    'name'  => 'afp_nombre',
                                    'descripcion' => '',
                                    'state' => 'afp_estado'
                            );
    
    protected $_SCHEMA = 'rh';
    protected $_TABLE = 'afp';
    protected $_PREF_TABLE= 'AFPMPI'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }


    public function get_porcentajes($afp_id){


          $sql = " SELECT afp_id, 
                         afcv_aportobli as jubilacion, 
                         afcv_comvar as comision,  
                         afcv_seguros as invalides, 
                         afcv_aportobli_cc as jubilacion_cc,
                         afcv_comsaldo as saldo 
                   FROM planillas.afps_porcentajes WHERE  afcv_estado = 1 AND  afp_id = ? ";
          
          $rs  = $this->_CI->db->query($sql,array($afp_id))->result_array();
          return $rs[0];

    }
 
    public function get_list(){

        $sql = " select * from rh.afp order by afp_nombre";
        $rs  = $this->_CI->db->query($sql,array())->result_array();

        return $rs;
    }   

    public function get_table()
    {

        $sql =" SELECT ac.*, afp.afp_nombre 
                FROM planillas.afps_porcentajes ac
                INNER JOIN rh.afp ON ac.afp_id = afp.afp_id AND afp_estado = 1
                WHERE afcv_estado = 1
                ORDER BY afp.afp_nombre ";

        $rs = $this->_CI->db->query($sql, array() )->result_array();

        return $rs;

    }


    public function actualizar_tabla($afp_id, $values = array())
    {   


        $this->_CI->db->trans_begin();

        $sql = " UPDATE planillas.afps_porcentajes 
                 SET afcv_estado = 0 
                 WHERE afp_id = ? ";

        $this->_CI->db->query($sql, array($afp_id));

        $sql =" INSERT INTO planillas.afps_porcentajes 
                            (afp_id, 
                             afcv_aportobli, 
                             afcv_comvar, 
                             afcv_seguros, 
                             afcv_aportobli_cc, 
                             afcv_comsaldo, 
                             afcv_comanu, 
                             afcv_estado, 
                             afcv_comisionmixta, 
                             afcv_max_asegurable  ) 
                    
                    VALUES(?,?,?,?,
                           ?,?,?,'1',?, ? ) 
              ";

        $comision_mixta = '1';
        
        
        $this->_CI->db->query($sql, array(
                                          $afp_id, 
                                          $values['obligatorio'], 
                                          $values['comision'],
                                          $values['seguro'],
                                          $values['cc'],
                                          $values['saldo'],
                                          $values['flujo'], 
                                          $comision_mixta, 
                                          $values['max_asegurable']
                                          ));


        /* ACTUALIZACION DE LOS MONTOS DE LAS PLANILLAS ELABORADAS */ 
 
    

        $this->actualizar_valores_planillas(array('afp' => $afp_id));

/*        $valores_afp = $this->_CI->afp->get_porcentajes($afp_id); 
        

        $sql = " SELECT   pla.pla_id, pla.plati_id, plaemp.plaemp_id, pepe.afp_id, pepe.afm_id, 
                          avt.vars_aportobli, avt.vars_comvar, avt.vars_seguros  

                          FROM planillas.planilla_empleados plaemp 
                          INNER JOIN rh.persona_pension pepe ON plaemp.peaf_id = pepe.peaf_id AND pepe.pentip_id = ".PENSION_AFP." AND pepe.afp_id = ?
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1
                          INNER JOIN planillas.planilla_movimiento mov ON pla.pla_id = mov.pla_id  AND  mov.plamo_estado = 1 AND mov.plaes_id = ".ESTADOPLANILLA_ELABORADA."
                          INNER JOIN planillas.afps_vars_tipoplanilla avt ON avt.plati_id = pla.plati_id AND avt.avt_estado = 1
                 WHERE plaemp.plaemp_estado = 1 

                 ORDER BY pla.pla_id, plaemp.plaemp_id

               ";


        $planilla_empleados = $this->_CI->db->query($sql, array($afp_id))->result_array(); 
 

        foreach ($planilla_empleados as $reg)
        {

            $valor_comision = ($reg['afm_id'] == AFP_FLUJO) ? $valores_afp['comision'] : $valores_afp['saldo'] ; 

            $tasa_jubilacion = ($reg['plati_id'] != TIPOPLANILLA_CONSCIVIL ) ? $valores_afp['jubilacion'] : $valores_afp['jubilacion_cc'];
 
            $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $reg['vars_aportobli'] , $tasa_jubilacion   );
            $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $reg['vars_seguros'] , $valores_afp['invalides']   );
            $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $reg['vars_comvar'] , $valor_comision   );

        }
*/

        if ($this->_CI->db->trans_status() === FALSE)
        {
            $this->_CI->db->trans_rollback();
            return false;
        }
        else
        {
            $this->_CI->db->trans_commit();
            return true;
        }


    }


    public function actualizar_valores_planillas($params = array() ) 
    {
 
        $this->_CI->load->library(array('App/planillaempleadovariable'));

        $values= array();

        $sql = " SELECT   pla.pla_id, pla.plati_id, 
                          plaemp.plaemp_id, pepe.afp_id, pepe.afm_id, 
                          avt.vars_aportobli, avt.vars_comvar, avt.vars_seguros,

                          ap.afcv_aportobli as jubilacion, 
                          ap.afcv_comvar as comision,  
                          ap.afcv_seguros as invalides, 
                          ap.afcv_aportobli_cc as jubilacion_cc,
                          ap.afcv_comsaldo as saldo   

                          FROM planillas.planilla_empleados plaemp 
                          INNER JOIN rh.persona_pension pepe ON plaemp.peaf_id = pepe.peaf_id AND pepe.pentip_id = ".PENSION_AFP." 
                          INNER JOIN planillas.afps_porcentajes ap ON pepe.afp_id = ap.afp_id AND afcv_estado = 1
                          INNER JOIN planillas.planillas pla ON plaemp.pla_id = pla.pla_id AND pla.pla_estado = 1
                          INNER JOIN planillas.planilla_movimiento plamo ON pla.pla_id = plamo.pla_id AND plamo.plamo_estado = 1 AND plamo.plaes_id = ".ESTADOPLANILLA_ELABORADA." 
                          INNER JOIN planillas.afps_vars_tipoplanilla avt ON avt.plati_id = pla.plati_id AND avt.avt_estado = 1
                 WHERE plaemp.plaemp_estado = 1  ";

          if($params['planilla'] != '')
          {

             $sql.=" AND plaemp.pla_id = ? ";
             $values[] = $params['planilla'];
          }

          if($params['afp']!= '')
          {
              $sql.="  AND pepe.afp_id = ? ";
              $values[] = $params['afp'];
          }

          $sql.="   ORDER BY pla.pla_id, plaemp.plaemp_id  ";
         
        $planilla_empleados = $this->_CI->db->query($sql, $values )->result_array(); 
         

        foreach ($planilla_empleados as $reg)
        {

            $valor_comision = ($reg['afm_id'] == AFP_FLUJO) ? $reg['comision'] : $reg['saldo'] ; 

            $tasa_jubilacion = ($reg['plati_id'] != TIPOPLANILLA_CONSCIVIL ) ? $reg['jubilacion'] : $reg['jubilacion_cc'];
        
            $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $reg['vars_aportobli'] , $tasa_jubilacion   );
            $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $reg['vars_seguros'] , $reg['invalides']   );
            $this->_CI->planillaempleadovariable->set_valor($reg['plaemp_id'], $reg['vars_comvar'] , $valor_comision   );

        }

        return true;

    }

}
