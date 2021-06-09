<?php 
include('../../../db_conn.php');
$idcliente=isset($_POST['idcliente'])?$_POST['idcliente']:"";
$idmembresias=isset($_POST['membresias'])?$_POST['membresias']:"";
 
$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d H:i:s');
$fecha = date( "Y-m-d H:i:s", strtotime( "$fecha +1 month" ) );

if(is_numeric($idcliente)){
  for($i=0; $i < count($idmembresias); $i++){
    $sql="INSERT INTO asignaciones (idcliente, idmembresia,fecha_vencimiento) VALUES ('$idcliente', '$idmembresias[$i]','$fecha')"; 
    $result = $con->query($sql); 
  }
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='4;URL=../ver_clientes.php' /> ";	
} else {
  print "<html><head><script>alert('Accion no permitida');</script><meta http-equiv='refresh' content='0; url=../ver_clientes.php'></head></html>";
} 
mysqli_close($con);
?>