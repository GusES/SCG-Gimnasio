<?php 
include('../../../db_conn.php');
$idplanes=(isset($_GET['idplanes']))?$_GET['idplanes']:"";
$sql = "DELETE FROM planes WHERE idplanes = '$idplanes'";

if($rs = $con->query($sql)){
  header("location:../ver_rutinas.php");
}else{
  $m="ERROR en la eliminación";
  header("location:../ver_ejercicios.php?m=$m");
}
?>