<?php

class reniec extends Table{

    protected $_FIELDS = array(
        'id'    => 'ebanco_id',
        'code'  => 'ebanco_key',
        'name'  => 'ebanco_nombre',
        'descripcion' => '',
        'state' => 'ebanco_estado'
    );

    protected $_SCHEMA = '';
    protected $_TABLE = '';
    protected $_PREF_TABLE= ''; 

    public function __construct()
    {    
        parent::__construct();   
    }

    public function buscar_dni($dni)
    {
        $base = "10.0.0.10/apps/webapp/api/consulta_dni/index.php";
        $params = array(
            'consultar' => $dni,
        );
        $url = $base . '?' . http_build_query($params);
        
        $dc = curl_init();
        curl_setopt($dc, CURLOPT_URL, $url);
        curl_setopt($dc, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($dc, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($dc, CURLINFO_HEADER_OUT, true);

        if ( curl_error($dc) ) {
            $respuesta['error'] = true;
        } else {
            $rsp = curl_exec($dc);
            $rsp = json_decode($rsp);
            $respuesta['apellido_primero'] = trim( $rsp->apPrimer );
            $respuesta['apellido_segundo'] = trim( $rsp->apSegundo );
            $respuesta['nombres'] = trim( $rsp->prenombres );
            $respuesta['direccion'] = trim( $rsp->direccion );
        }

        curl_close($dc);

        return $respuesta;
    }

}