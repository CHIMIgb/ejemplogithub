<?php
//datos de la conexion 
$host="localhost";
$usuario="root";
$clave="";
$base_datos="sesiones";

//abrimos una nueva conexion a sql
//
$mysqli= new mysqli($host,$usuario,$clave,$base_datos);


if ($mysqli->connect_errno){
    echo "Falló la conexión a MySQL: (". $mysqli -> connect_errno.") ".$mysqli->connect_error;
}

return $mysqli;
?>
