<?php 
include('../../../db_conn.php');
$id=isset($_POST['id'])?$_POST['id']:false;
$modulo=isset($_POST['nom_modulo'])?str_replace("'","''",$_POST['nom_modulo']):"";
$nom_modulo=mb_strtolower($modulo);
$nom_modulo=str_replace(',','&sbquo;', $nom_modulo);
$cant_per=isset($_POST['cant_per'])?$_POST['cant_per']:"";
$actividades=$cant_per;
$precio=isset($_POST['precio'])?$_POST['precio']:"";
$detalle=isset($_POST['detalle'])?str_replace("'","''",$_POST['detalle']):"";

if(trim($nom_modulo) != ""){
  $sql = "UPDATE membresias SET nom_modulo='$nom_modulo', actividades='$actividades', cant_per='$cant_per', detalle='$detalle', precio='$precio' WHERE idmembresias = '$id' ;";
  $result = $con->query($sql);
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='4;URL=../ver_membresias.php' /> ";	
} else {
    echo "<html><head><script>alert('El nombre no puede estar vacio.');</script><meta http-equiv='refresh' content='0; url=../modificar_membresias.php?id=$id'></head></html>";
  }
mysqli_close($con);
?>