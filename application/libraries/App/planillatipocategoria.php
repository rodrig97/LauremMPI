<?php

class planillatipocategoria extends Table{
    
    protected $_FIELDS=array(   
                                    'id'          => 'platica_id',
                                    'code'        => 'platica_key',
                                    'name'        => '',
                                    'descripcion' => '',
                                    'state'       => 'platica_estado'
                            );
    
    protected $_SCHEMA = 'planillas';
    protected $_TABLE = 'planilla_tipo_categoria';
    protected $_PREF_TABLE= 'ocuplas'; 
    
    
    public function __construct(){
          
        parent::__construct();
          
    }
    

    public function get_list($plati_id)
    {

        $sql = "SELECT * FROM planillas.planilla_tipo_categoria  
                WHERE platica_estado = 1 AND plati_id = ? 
                ORDER BY platica_id";

        $rs = $this->_CI->db->query($sql,array($plati_id))->result_array();

        return $rs;


    }

    public function get_tables()
    {
   
        $sql =" SELECT * FROM  planillas.variables_tabla_datos  WHERE vtd_estado = 1 ORDER BY vtd_nombre ";
        $rs=  $this->_CI->db->query($sql, array($vtd_id))->result_array();
        

        return $rs;
    }   

   
    public function get_table_montos($vtd_id)
    {
  

        $sql =" SELECT plati_id FROM  planillas.variables_tabla_datos  WHERE vtd_id = ? ";
        $rs=  $this->_CI->db->query($sql, array($vtd_id))->result_array();
        $plati_id = $rs[0]['plati_id'];
  

        $sql = ' SELECT platica_id,platica_nombre FROM planillas.planilla_tipo_categoria WHERE platica_estado = 1 AND plati_id = ? ORDER BY platica_id ';
        $rs = $this->_CI->db->query($sql, array($plati_id) )->result_array();

        $header='';   

        foreach($rs as $k => $cat)
        {

          if($k>0)  $header.=',';
          $header.=' "DATO_'.trim($cat['platica_id']).'_'.trim($cat['platica_nombre']).'" text'; 

        }


        if(sizeof($rs) == 0 )
        {
             //return array();
        }


        $sql = " SELECT  vars.vari_id, 
                         ( 'ver_' || vars.vari_id || '_' ||vars.vari_nombre )  as ver_variable,  
                         montos.* 

                FROM ( 
                SELECT  * FROM crosstab(' 
 
                        SELECT vari.vari_id, y_value, vsv_value

                        FROM planillas.variables vari
                        LEFT JOIN planillas.variables_staticvalues vsv ON vari.vari_id = vsv.vari_id AND vsv.vsv_estado = 1
                        WHERE  vari_estado = 1 AND vari.vtd_id = ".$vtd_id."

                        ORDER BY vari_id, y_value ',

                 ' SELECT platica_id FROM planillas.planilla_tipo_categoria WHERE platica_estado = 1 AND plati_id = ".$plati_id." ORDER BY platica_id ' 
                 ) AS (  vari_id integer,

                ".$header.")
                 ) as montos 
                
                LEFT JOIN planillas.variables vars ON montos.vari_id = vars.vari_id 
                ORDER BY vars.vari_nombre

            ";

        $rs = $this->_CI->db->query($sql,array())->result_array();

        return $rs;
 
    }
 
    public function actualizar_montos($params = array() , $vtd_id = 0 )
    {
    

        $sql =" SELECT * FROM  planillas.variables_staticvalues  WHERE vari_id = ? AND y_value = ? AND vtd_id = ?  AND  vsv_estado = 1 ";
        $rs =  $this->_CI->db->query($sql, array( $params['vari_id'] , $params['yvalue'], $vtd_id ))->result_array();

        if(sizeof($rs) > 0)
        {
 
            $sql = " UPDATE planillas.variables_staticvalues 
                        SET vsv_value = ? 
                        WHERE vari_id = ? AND y_value = ? AND vtd_id = ? ";     

            $rs =  $this->_CI->db->query($sql, array($params['value'], $params['vari_id'] , $params['yvalue'], $vtd_id ));

        }
        else
        {

            $sql = " INSERT INTO planillas.variables_staticvalues( vari_id, y_value, vtd_id, vsv_value ) VALUES(?,?,?,? ) ";   
            $rs = $this->_CI->db->query($sql, array( $params['vari_id'] , $params['yvalue'], $vtd_id, $params['value'] ));

        }

        return ($rs) ? true : false;

    }


    public function xx()
    {
 
    }

}