<?php
 
if ( !defined('BASEPATH')) exit('<br/><b>Esta trantando de ingresar de manera indebida a un portal del estado peruano, tu IP ha sido registrado</b>');

 
class remote extends CI_Controller
{
    
    public $usuario;
    
    public function __construct()
    {
        
        parent::__construct();

	//*
         
        if($this->input->get('ajax')=='1')
        {
            $this->usuario = $this->user->pattern_islogin_a(); //SI no esta logeado, automaticamente redirije a 
        }
        else
        {
            $this->usuario = $this->user->pattern_islogin(); //SI no esta logeado, automaticamente redirije a 
        }  

        $this->user->set_keys( $this->usuario['syus_id'] );    

	//*/

        $this->load->library(array('App/persona','App/planilla','App/tipoplanilla', 'App/anioeje'));
    }

    public function index()
    {
         echo 'generar_horarios, sync_horarios';
    }

    public function sync_horarios()
    {


            $sql = " -- DIAS LABORABLES 
                       -- LUNES A VIERNES LABORABLE DOMINGO NO LABORABLE 
                       
                    UPDATE planillas.hojaasistencia_emp_dia 
                    SET hoaed_laborable = 1 
                    WHERE EXTRACT( 'dow' FROM(hoaed_fecha)) IN (1,2,3,4,5,6);

                    UPDATE planillas.hojaasistencia_emp_dia 
                    SET hoaed_laborable = 0
                    WHERE EXTRACT( 'dow' FROM(hoaed_fecha)) IN (0); 

                    -- HORARIOS DE TRABAJO 
                        --  LUNES A VIERNES 1 SABADO 2 

                    UPDATE planillas.hojaasistencia_emp_dia 
                    SET hor_id = 1 
                    WHERE EXTRACT( 'dow' FROM(hoaed_fecha)) IN (1,2,3,4,5);

                    UPDATE planillas.hojaasistencia_emp_dia 
                    SET hor_id = 2
                    WHERE EXTRACT( 'dow' FROM(hoaed_fecha)) IN (6); 

                    --  DNI 04646725  14 122 -9
                     
                    UPDATE planillas.hojaasistencia_emp_dia dia
                    SET hoa_id = det.hoa_id, hoae_id = det.hoae_id 
                    FROM planillas.hojaasistencia_emp det, planillas.hojaasistencia hoa    
                    WHERE dia.indiv_id = det.indiv_id AND hoa.hoa_id = det.hoa_id AND ( hoaed_fecha BETWEEN hoa_fechaini AND hoa_fechafin ) AND hoa_estado = 1 AND hoae_estado = 1 
                     AND dia.registro_id is not null AND dia.registro_id != 0  
                     
                 "; 

          $rs =  $this->db->query($sql);

          echo ($rs) ? 'Proceso completado' : 'ocurrio un error';

    }


    public function generar_horarios()
    {

         $sql =" UPDATE planillas.individuo_dia_estado ide
                SET hatd_id = d.hatd_id, ide_laborable = d.platide_laborable
                FROM ( 
                    SELECT indiv_id
                    FROM public.individuo indiv
                    INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                    ORDER BY indiv_id 
                ) as indiv, (SELECT pde.dia_id, pde.hatd_id, platide_laborable FROM planillas.planillatipo_dia_estado pde WHERE plati_id = ? ) as d
                WHERE ide.indiv_id = indiv.indiv_id AND ide.dia_id = d.dia_id AND ide.ide_personalizado = 0
              ";

        $this->db->query($sql, array(9,9));


    
        $sql ="  INSERT INTO planillas.individuo_dia_estado(indiv_id, dia_id, hatd_id, ide_laborable )  
                 SELECT data.indiv_id, data.dia_id, data.hatd_id, data.platide_laborable  
                 FROM
                 (
                    ( SELECT t1.indiv_id,
                           d.dia_id,
                           d.hatd_id,
                           d.platide_laborable

                    FROM (
                        SELECT indiv_id
                        FROM public.individuo indiv
                        INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                        ORDER BY indiv_id 

                    ) as t1, (SELECT pde.dia_id, pde.hatd_id, pde.platide_laborable FROM planillas.planillatipo_dia_estado pde WHERE plati_id = ? ) as d

                    ORDER BY t1.indiv_id,
                           d.dia_id,
                           hatd_id )
                 ) as data
                 LEFT JOIN  planillas.individuo_dia_estado de ON data.indiv_id = de.indiv_id AND data.dia_id = de.dia_id         
                 WHERE de.ide_id is null 
                 ORDER BY data.indiv_id, data.dia_id  ";

        $this->db->query($sql, array(9,9));
    


      /* HORARIOOO */   

       $sql =" UPDATE planillas.individuo_dia_horario idh
                SET hor_id = d.hor_id 
                FROM ( 
                    SELECT indiv_id
                    FROM public.individuo indiv
                    INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                    ORDER BY indiv_id 
                ) as indiv, (SELECT pdh.dia_id, pdh.hor_id FROM planillas.planillatipo_dia_horario pdh WHERE plati_id = ? ) as d
                WHERE idh.indiv_id = indiv.indiv_id AND idh.dia_id = d.dia_id AND idh.idh_personalizado = 0
              ";

        $this->db->query($sql, array(9,9));


 
        $sql ="  INSERT INTO planillas.individuo_dia_horario(indiv_id, dia_id, hor_id )  
                 SELECT data.indiv_id, data.dia_id, data.hor_id 
                 FROM
                 (
                    ( SELECT t1.indiv_id,
                           d.dia_id,
                           d.hor_id 

                    FROM (
                        SELECT indiv_id
                        FROM public.individuo indiv
                        INNER JOIN rh.persona_situlaboral persla ON indiv.indiv_id = persla.pers_id AND persla.persla_estado = 1 AND persla_ultimo = 1 AND persla.plati_id = ?
                        ORDER BY indiv_id 

                    ) as t1, (SELECT pdh.dia_id, pdh.hor_id FROM planillas.planillatipo_dia_horario pdh WHERE plati_id = ? ) as d

                    ORDER BY t1.indiv_id,
                           d.dia_id,
                           hor_id )
                 ) as data
                 LEFT JOIN  planillas.individuo_dia_horario dh ON data.indiv_id = dh.indiv_id AND data.dia_id = dh.dia_id         
                 WHERE dh.idh_id is null 
                 ORDER BY data.indiv_id, data.dia_id  ";

        $this->db->query($sql, array(9,9));




        echo 'Horarios generados';




    }


    public function troll()
    {

       

    }
}
