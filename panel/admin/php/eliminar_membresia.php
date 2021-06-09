<?php 
include('../../../db_conn.php');
$id=(isset($_GET['id']))?$_GET['id']:"";
$sql = "DELETE from membresias WHERE idmembresias = $id ";
if($rs = $con->query($sql)){
  header("location:../ver_membresias.php");
}else{
  $m="ERROR en la eliminación";
  header("location:../ver_membresias.php?m=$m");
}
?>