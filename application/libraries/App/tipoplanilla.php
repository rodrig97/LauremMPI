<?php
 
class tipoplanilla extends Table
{
     
    protected $_FIELDS=array(   
                                    'id'          => 'plati_id',
                                    'code'        => 'plati_key',
                                    'name'        => 'plati_nombre',
                                    'descripcion' => 'plati_descripcion',
                                    'state'       => 'plati_estado'
                            );
    
    protected $_SCHEMA     = 'planillas';
    protected $_TABLE      = 'planilla_tipo';
    protected $_PREF_TABLE = 'platip'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }


    public function get($plati_id)
    {

        $sql = "SELECT * FROM planillas.planilla_tipo WHERE plati_id = ? LIMIT 1";
        $rs = $this->_CI->db->query($sql ,array($plati_id))->result_array();

        return $rs[0];

    }
    

    public function get_all($estado = 1, $params = array() )
    {
         
        $sql = 'SELECT plati.*,
                      
                      (CASE WHEN asiconf.hoplac_id is not null THEN 
                             1 
                       ELSE
                             0
                       END   )  as asistencia 

                FROM planillas.planilla_tipo  plati
                LEFT JOIN planillas.hojaasistencia_plati_config asiconf ON plati.plati_id = asiconf.plati_id AND hoplac_estado = 1 ';

        $sql.= ' WHERE plati_estado = 1  ';

        if($params['tipo_registro_asistencia'] != '' && $params['tipo_registro_asistencia'] != '0'){

            $sql.=" AND asiconf.tipo_registro_asistencia = ".$params['tipo_registro_asistencia'];
        }  

        $sql.= '  
                 ORDER BY  plati_nombre ';
        
        
        return $this->_CI->db->query($sql, array())->result_array();
        
    }

    public function get_list($params = array())
    {
        $sql =  "    SELECT plati.*, 
                            COALESCE(trabajadores.total_empleados,0) as total_empleados,

                            (CASE WHEN asiconf.hoplac_id is not null THEN 
                                   1 
                             ELSE
                                   0
                             END   )  as asistencia 

                     FROM planillas.planilla_tipo plati
                     LEFT JOIN planillas.hojaasistencia_plati_config asiconf ON plati.plati_id = asiconf.plati_id AND hoplac_estado = 1
                     LEFT JOIN (
                                 SELECT situ.plati_id, count(persla_id) as total_empleados
                                 FROM rh.persona_situlaboral situ 
                                 WHERE persla_estado = 1 AND persla_vigente = 1 
                                 GROUP BY situ.plati_id
                                 ORDER BY situ.plati_id
                        ) as trabajadores ON trabajadores.plati_id = plati.plati_id
                    
                     WHERE plati_estado = 1 

                     ORDER BY plati.plati_nombre
 
               ";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;
    }

    public function get_by_ids($ids){


        $in = implode(',', $ids);

        $sql = " SELECT plati_nombre FROM planillas.planilla_tipo WHERE plati_id IN (".$in.") AND plati_estado = 1";

        $rs = $this->_CI->db->query($sql, array())->result_array();

        return $rs;
    }
    
    
    public function get_variables_conceptos($tipo_planilla = '0',$nombre = '')
    {
        
        $sql = " SELECT   (  'va'|| plati_id ||'|'||  vari_id ) as codigo,
                    (    

                             ( CASE WHEN variables.gvc_id = 0 THEN 
                                        vari_nombre || ' (Variable)'
                               ELSE   
                                       vari_nombre || ' - ' || gr.gvc_nombre || ' (Variable)'
                               END )  ) as nombre,  

                         (  
                        
                             ( CASE WHEN variables.gvc_id = 0 THEN 
                                        vari_nombrecorto || ' (Variable)'
                               ELSE   
                                       vari_nombrecorto || ' - ' || gr.gvc_nombre || ' (Variable)'
                               END )  

                    )  as alias 

                FROM planillas.variables 
                LEFT JOIN planillas.grupos_vc gr ON variables.gvc_id = gr.gvc_id
                WHERE vari_estado = 1 AND plati_id = ? ";
       
         if($nombre != '') $sql.= "  AND vari_nombre like '%".$nombre."%' ";
         
         $sql.=" UNION ( SELECT  (  'co' || plati_id ||'|'||  conc_id ) as codigo,
                         
                      
                         (  
                       ( CASE WHEN conceptos.gvc_id = 0 THEN 
                                    conc_nombre || ' (Concepto)'
                           ELSE   
                                 conc_nombre || '-' || gr.gvc_nombre || ' (Concepto)'
                           END )

                        )  as nombre, 

                                     ( 
                           ( CASE WHEN conceptos.gvc_id = 0 THEN 
                                                conc_nombrecorto || ' (Concepto)'
                             ELSE   
                                             conc_nombrecorto || '-' || gr.gvc_nombre || ' (Concepto)'
                                         END )   
                         )  as alias 

                         FROM planillas.conceptos  
                         LEFT JOIN planillas.grupos_vc gr ON conceptos.gvc_id = gr.gvc_id    
                         WHERE conc_estado = 1 AND plati_id = ?  
                 ";
          if($nombre != '') $sql.= "  AND conc_nombre like '%".$nombre."%' "; 

             $sql.=  "  order by nombre )";
      // echo $sql;
       return  $this->_CI->db->query($sql,array($tipo_planilla,$tipo_planilla))->result_array();
        
    }


    public function view($plati_id)
    {

        $sql = " SELECT * FROM planillas.planilla_tipo 
                 WHERE plati_id = ? ";
        
        $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();

        return $rs[0];

    }
        

    public function registrar_categoria($params = array() )
    {


        $sql =" INSERT INTO planillas.planilla_tipo_categoria(platica_nombre, platica_descripcion, platica_nombrecompleto, plati_id ) VALUES(?,?,?, ? ) ";

        $rs = $this->_CI->db->query($sql, array( $params['nombre'], $params['descripcion'], $params['nombrecompleto'], $params['plati'] ));
 
        return ($rs) ? true : false;
    }

    public function get_categorias($plati_id)
    {

        $sql = "SELECT * FROM planillas.planilla_tipo_categoria
                WHERE plati_id = ? AND platica_estado = 1 ORDER BY platica_nombre ";
                
        $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();
        
        return $rs;
    }   

    public function get_conceptos_sistema($plati_id = 0)
    {

        $sql =" SELECT * FROM planillas.conceptos_sistema plati WHERE plati_id = ? ";

        $rs = $this->_CI->db->query($sql, array($plati_id))->result_array();

        return $rs[0];

   }


   public function get_horario_defecto($params = array())
   {
        $sql = "    SELECT 
                          det.plati_id,
                          det.dia_id,
                          dias.dia_nombre as dia,
                          htp.hatd_id,
                          pest.platide_laborable as laborable,
                          htp.hatd_key,
                          htp.hatd_nombre,
                          hor.hor_alias,
                          hor.hor_id,
                          hor.hor_key
                          
                    FROM (
                        SELECT plati_id, dia_id 
                        FROM planillas.planilla_tipo plati, public.dias  
                        WHERE plati.plati_id = ? 
                             
                    ) as det
                    LEFT JOIN planillas.planillatipo_dia_estado pest ON det.plati_id = pest.plati_id AND det.dia_id =  pest.dia_id 
                    LEFT JOIN planillas.planillatipo_dia_horario  phor ON det.plati_id = phor.plati_id AND det.dia_id =  phor.dia_id
                    LEFT JOIN planillas.hojadiario_tipo htp ON pest.hatd_id = htp.hatd_id 
                    LEFT JOIN planillas.hojaasistencia_horarios hor ON phor.hor_id = hor.hor_id 
                    LEFT JOIN public.dias ON det.dia_id = dias.dia_id
                    ORDER BY det.plati_id, det.dia_id 

               ";

        $rs = $this->_CI->db->query($sql, array($params['plati_id']))->result_array();

        return $rs;
   }

}