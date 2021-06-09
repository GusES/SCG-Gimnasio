<?php 
include('../../../db_conn.php');
$idgrupo=isset($_POST['idgrupo'])?$_POST['idgrupo']:"";
$idmembresias=isset($_POST['membresias'])?$_POST['membresias']:"";

 
if(is_numeric($idgrupo)){
  $sql="INSERT INTO asignaciones (idgrupo, idmembresia) VALUES ('$idgrupo', '$idmembresias')"; 
  $result = $con->query($sql); 
  
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='2;URL=../clientes_grupos.php' /> ";	
} else {
  print "<html><head><script>alert('Accion no permitida');</script><meta http-equiv='refresh' content='0; url=../ver_clientes.php'></head></html>";
} 
mysqli_close($con);
?>


