<?PHP


class tareo  extends Table{
    
    
    protected $_FIELDS=array(   
                                    'id'    => '',
                                    'code'  => '',
                                    'name'  => '',
                                    'descripcion' => '',
                                    'state' => ''
                            );
    
    protected $_SCHEMA = '';
    protected $_TABLE = 'actividades';
    protected $_PREF_TABLE= 'TAREO'; 
    
    public function __construct(){
        
        parent::__construct();
        
    }


    public function get_list($tarea_id){

          
        $sql = "SELECT t.*, vt.cod_tarea,(tarea.sec_func || '-'|| tarea.tarea) as tarea_codigo, tarea.nombre as tarea_nombre 

                        FROM tareo.tareos t 
                        LEFT JOIN tareo.tareas x ON t.tarea_id = x.tarea_id
                        LEFT JOIN public.v003_tarea vt ON x.cod_v003_tarea  = vt.cod_v003_tarea
                        LEFT JOIN sag.tarea ON vt.cod_tarea = tarea.cod_tarea

                  WHERE vt.cod_tarea = ?      

                 ";


        $rs = $this->_CI->db->query($sql, array($tarea_id))->result_array();


        return $rs;
 
    }       


    public function get_info($tareo_id)
    {

        $sql = "SELECT t.*, vt.cod_tarea,(tarea.sec_func || '-'|| tarea.tarea) as tarea_codigo, tarea.nombre as tarea_nombre 

                        FROM tareo.tareos t 
                        LEFT JOIN tareo.tareas x ON t.tarea_id = x.tarea_id
                        LEFT JOIN public.v003_tarea vt ON x.cod_v003_tarea  = vt.cod_v003_tarea
                        LEFT JOIN sag.tarea ON vt.cod_tarea = tarea.cod_tarea

                  WHERE t.tareo_id = ?      

                 ";


        $rs = $this->_CI->db->query($sql, array($tareo_id))->result_array();


        return $rs[0];


    }


    public function importado_en($tareo_id){

       /*

        FALTA IMPLEMENTAR
       */
        $sql = "  
                SELECT * FROM tareo.tareo_historial 

               "; 

        $rs = $this->_CI->db->query($sql, array($tareo_id))->result_array();


        return $rs[0];
    }


    public function get_tarea_by_tareo($tareo_id){

        $sql ="SELECT tarea_id FROM tareo.tareos WHERE tareo_id = ? ";
        $rs = $this->_CI->db->query($sql, array($tareo_id))->result_array();

        return $rs[0]['tarea_id'];
    }


    public function get_detalle($tareo_id){

         
        $sql = " 
            SELECT   
               p_t.indiv_id,
               indiv.indiv_dni,
               (indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
               p_t.personal_cargo_id,
               carg.cargo_planilla,
               ocu.platica_nombre as ocupacion,
               detalle.*
            FROM
            ( 
                SELECT personal_tarea_id ,count(dia) as dias FROM tareo.tareo_asistencias 
                WHERE tareo_id = ? AND tareo_descanso_id = 0
                GROUP BY personal_tarea_id 
            ) as detalle 
            LEFT JOIN tareo.personal_tareas p_t ON detalle.personal_tarea_id = p_t.personal_tarea_id
            LEFT JOIN tareo.personal_cargos carg ON p_t.personal_cargo_id = carg.personal_cargo_id
            LEFT JOIN planillas.planilla_tipo_categoria ocu ON  carg.cargo_planilla =  ocu.platica_id AND ocu.plati_id = 9
            LEFT JOIN public.individuo indiv ON p_t.indiv_id = indiv.indiv_id

            order by trabajador

        ";


        $rs = $this->_CI->db->query($sql, array($tareo_id))->result_array();

        return $rs;

    }

    public function importar($tareo_id, $planilla_id){


        $this->_CI->load->library(array('App/planillaempleado','App/planillaempleadovariable'));

        $sql = "  SELECT   
                     p_t.indiv_id,
                     indiv.indiv_dni,
                     (indiv.indiv_appaterno || ' ' || indiv.indiv_apmaterno || ' ' || indiv.indiv_nombres ) as trabajador,
                     p_t.personal_cargo_id,
                     carg.cargo_planilla,
                     ocu.platica_nombre as ocupacion,
                     detalle.*
                  
                  FROM
                  ( 
                      SELECT personal_tarea_id ,count(dia) as dias FROM tareo.tareo_asistencias 
                      WHERE tareo_id = ? AND tareo_descanso_id = 0
                      GROUP BY personal_tarea_id 
                  ) as detalle 
                  LEFT JOIN tareo.personal_tareas p_t ON detalle.personal_tarea_id = p_t.personal_tarea_id
                  LEFT JOIN tareo.personal_cargos carg ON p_t.personal_cargo_id = carg.personal_cargo_id
                  LEFT JOIN planillas.planilla_tipo_categoria ocu ON  carg.cargo_planilla =  ocu.platica_id AND ocu.plati_id = 9
                  LEFT JOIN public.individuo indiv ON p_t.indiv_id = indiv.indiv_id

                  order by trabajador

        ";

        $rs_tareo = $this->_CI->db->query($sql , array($tareo_id))->result_array();
 
        $this->_CI->db->trans_begin();

        foreach($rs_tareo as $reg_tareo)
        {
   
            list($plaemp_id, $plaemp_key) = $this->_CI->planillaempleado->registrar($planilla_id, $reg_tareo['indiv_id'], $reg_tareo['cargo_planilla'], true);
            $this->_CI->planillaempleadovariable->set_valor($plaemp_id, TAREO_VARIABLE_ASISTENCIA, $reg_tareo['dias'] );
        }


        if($this->_CI->db->trans_status() === FALSE) 
        {
 
            $this->_CI->db->trans_rollback();
            return false;
                
        }
        else
        {
         
            $sql = " INSERT INTO tareo.tareo_historial(tareo_id, tareo_estado,pla_id) VALUES (?,?,?)  ";
            $this->_CI->db->query($sql, array($tareo_id,TAREO_IMPORTADO, $planilla_id));
       
            $this->_CI->db->trans_commit();
            return true;
        } 


    }

}