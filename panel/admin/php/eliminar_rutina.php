<?php 
include('../../../db_conn.php');
$idcliente=(isset($_GET['idcliente']))?$_GET['idcliente']:"";
$idrutina=(isset($_GET['idrutina']))?$_GET['idrutina']:"";
$sql = "DELETE FROM rutinas WHERE idcliente = $idcliente and idrutinas = $idrutina";

if($rs = $con->query($sql)){
  header("location:../rutinas_por_clie.php?idcliente=$idcliente");
}else{
  $m="ERROR en la eliminación";
  header("location:../ver_ejercicios.php?m=$m");
}
?>