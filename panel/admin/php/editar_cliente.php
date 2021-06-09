<?php 
include('../../../db_conn.php');
$id=isset($_POST['id'])?$_POST['id']:null;
$nombree=isset($_POST['nombre'])?str_replace("'","''",$_POST['nombre']):"";
$nombre=mb_strtolower($nombree);
$apellidoo=isset($_POST['apellido'])?str_replace("'","''",$_POST['apellido']):"";
$apellido=mb_strtolower($apellidoo);
$direccionn=isset($_POST['direccion'])?str_replace("'","''",$_POST['direccion']):"";
$direccion=mb_strtolower($direccionn);
$nacimiento=isset($_POST['nacimiento'])?$_POST['nacimiento']:"";
$telefono=isset($_POST['telefono'])?$_POST['telefono']:"";
$correo=isset($_POST['correo'])?$_POST['correo']:"";
$facebook=isset($_POST['facebook'])?str_replace("'","''",$_POST['facebook']):"";
$genero=isset($_POST['genero'])?$_POST['genero']:"";
$observacion=isset($_POST['observacion'])?str_replace("'","''",$_POST['observacion']):"";
$dni=isset($_POST['dni'])?$_POST['dni']:0;
$clave=isset($_POST['clave'])?$_POST['clave']:0;

//comprobamos que llegue un nombre y apellido de cliente
if($id != null && !empty($id) == true && $nombre != ""){
  $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', direccion='$direccion', nacimiento='$nacimiento', dni='$dni',telefono='$telefono', correo='$correo', facebook='$facebook', genero='$genero', observacion='$observacion', clave='$clave' WHERE idclientes = '$id' ;";
  $result = $con->query($sql);
	echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
    <meta http-equiv='refresh' content='4;URL=../ver_clientes.php' /> ";	
} else {
  print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../ver_clientes.php'></head></html>";
}
?>