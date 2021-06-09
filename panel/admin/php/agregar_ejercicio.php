<?php 
include('../../../db_conn.php');

$nombre=isset($_POST['nombre'])?mb_strtolower($_POST['nombre']):"";
$musculo=isset($_POST['musculo'])?$_POST['musculo']:"";
$numero=isset($_POST['numero'])?$_POST['numero']:"";
$imagen=isset($_FILES['file'])?$_FILES['file']:"";
$detalles=isset($_POST['detalles'])?$_POST['detalles']:"";

if(trim($nombre) != "" and $_FILES['file']['name'] != ""){
 //Renombrar/Guardando Imagen
 if(basename($imagen['name']) != ""){
  $img=date('ymdHis_').basename($imagen['name']); 
  $newimg = str_replace(" ", "_", $img);
  $destino = "../../../upload/".$newimg;
  $path = "../../upload/".$newimg;
  move_uploaded_file($imagen['tmp_name'], $destino);  
 } 
 $sql = "INSERT INTO ejercicios (grupomuscular, nombre, numero, path, detalles) VALUES ('$musculo', '$nombre', '$numero', '$path', '$detalles')";
 $result = $con->query($sql);
	echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
    <meta http-equiv='refresh' content='4;URL=../ver_ejercicios.php' /> ";	
} else {
 print "<html><head><script>alert('Los campos nombre e imagen no pueden estar vacio.');</script><meta http-equiv='refresh' content='0; url=../ver_ejercicios.php'></head></html>";
}

mysqli_close($con);
?>
