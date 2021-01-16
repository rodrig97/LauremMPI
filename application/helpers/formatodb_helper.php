<?php


function _get_date_pg($time,$type='fecha')
{
    
    $parts=explode(' ',trim($time));
    $fecha=$parts[0];
    $hour=$parts[1];
    $hour=explode('.',trim($hour));
    $hour=$hour[0];
    
    list($anho,$mes,$dia) = explode('-',$fecha);
    $fecha= $dia.'/'.$mes.'/'.$anho;
    
    if($type=='fecha') return $fecha;
    if($type=='hora')  return  $hour;
     
}

function get_fecha_larga($fecha, $params = array())
{
    $_MESES = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    $ft =  strtotime($fecha);
    $anio = date('Y', $ft );
    $mes = $_MESES[date('n', $ft)];
    $dia = date('d', $ft);
        
    $fecha_t = $dia.' de '.$mes.' del '.$anio;

    if($params['dia_adelante'] === true)
    {
        $dial = $_DIAS_L[date('N', $ft)];
        $fecha_larga =  $dial.', '.$fecha_t;
    }
    else
    {
        $fecha_larga =  $fecha_t;
    } 


    return $fecha_larga;
}
 
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}


function validar_fecha_postgres($fecha)
{
    list($anio, $mes, $dia) = explode('-', $fecha);

    if( strlen($anio) == 4 && strlen($mes) == 2 && strlen($dia) == 2 )
    {
         return true;
    }
    else
    {
         return false;
    }
}


/*
  CodeIgniter Function 2.4
*/
function get_date_variant()
{
   return $_SERVER[DATE_VARIANT];
}


function validar_dni($dni)
{
    if(is_numeric($dni) === FALSE || strlen($dni) != NUMERO_CARACTERES_DNI )
    {
         return false;
    }
    else
    {

         return true;
    }

} 

 
?>
