<?php 
include('../../../db_conn.php');
$nom_grupoos=isset($_POST['nom_grupo'])?$_POST['nom_grupo']:"Sin Nombre";
$nom_grupos=ucwords($nom_grupoos);
$dni=isset($_POST['dni'])?$_POST['dni']:"";
$idgrupos=isset($_POST['id'])?$_POST['id']:0;

if(empty($dni[0])){
  $dni0 = 0;
} else{
    $dni0 = $dni[0];
}

if(empty($dni[1])){
  $dni1 = 0;
}else{
    $dni1 = $dni[1];
}

if(empty($dni[2])){
  $dni2 = 0;
}else{
    $dni2 = $dni[2];
}

if(empty($dni[3])){
  $dni3 = 0;
}else{
    $dni3 = $dni[3];
}

if(empty($dni[4])){
  $dni4 = 0; 
}else{
    $dni4 = $dni[4];
}

//Contamos la cantidad de clientes
$i=count($dni);

//comprobamos que llegue un nombre y apellido de cliente
if(trim($nom_grupos) != "" && $dni != 0 && $i > 1){  
  //Actualizamos todos los clientes
  $update="UPDATE grupos_clie SET nom_grupos = '$nom_grupos', num_clie = '$i', cliente1 = '$dni0', cliente2 = '$dni1', cliente3 = '$dni2', cliente4 = '$dni3', cliente5 = '$dni4' WHERE idgrupos = '$idgrupos'";
  $ejecutar = $con->query($update);   
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='4;URL=../clientes_grupos.php' /> ";
} else {
  print "<html><head><script>alert('Debe ingresar todos los campos correspondientes.');</script><meta http-equiv='refresh' content='0; url=../clientes_grupos.php'></head></html>";
} ?>