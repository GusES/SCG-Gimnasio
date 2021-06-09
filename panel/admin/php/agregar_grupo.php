<?php 
include('../../../db_conn.php');
$nom_grupoos=isset($_POST['nom_grupoos'])?$_POST['nom_grupoos']:"Sin Nombre";
$nom_grupos=ucwords($nom_grupoos);
$dni=isset($_POST['dni'])?$_POST['dni']:0;
//Contamos la cantidad de clientes
$i=count($dni);
//comprobamos que llegue un nombre y apellido de cliente


if(trim($nom_grupos) != "" && $dni != 0){  
  //Adaptamos la query a cantidad de clientes
  switch ($i) {
    case 1:
      $sql = "INSERT INTO grupos_clie(nom_grupos, num_clie, cliente1) VALUES ('$nom_grupos', '$i', '$dni[0]');";
      break;    
    case 2:
      $sql = "INSERT INTO grupos_clie(nom_grupos, num_clie, cliente1, cliente2) VALUES ('$nom_grupos', '$i', '$dni[0]', '$dni[1]');";
      break;    
    case 3:
      $sql = "INSERT INTO grupos_clie(nom_grupos, num_clie, cliente1, cliente2, cliente3) VALUES ('$nom_grupos', '$i', '$dni[0]', '$dni[1]', '$dni[2]');";
      break;    
    case 4:
      $sql = "INSERT INTO grupos_clie(nom_grupos, num_clie, cliente1, cliente2, cliente3, cliente4) VALUES ('$nom_grupos', '$i', '$dni[0]', '$dni[1]', '$dni[2]', '$dni[3]');";
      break;    
    case 5:
      $sql = "INSERT INTO grupos_clie(nom_grupos, num_clie, cliente1, cliente2, cliente3, cliente4, cliente5) VALUES ('$nom_grupos', '$i', '$dni[0]', '$dni[1]', '$dni[2]', '$dni[3]', '$dni[4]');";
      break;    
    default:
      $sql = "";
  }
  $result = $con->query($sql);  
  //Obtener IDGRUPOS del grupo recien creado  
  $q = "SELECT * FROM grupos_clie WHERE nom_grupos = '$nom_grupos' AND num_clie = '$i' ORDER by idgrupos desc LIMIT 1";
  $r = $con->query($q);  
  $row = $r->fetch_object();  
	echo "<meta http-equiv='refresh' content='0;URL=../crear_asignar_grupo.php?idgrupo=$row->idgrupos' /> ";
} else {
  print "<html><head><script>alert('Debe ingresar todos los campos correspondientes.');</script><meta http-equiv='refresh' content='0; url=../ver_clientes.php'></head></html>";
}
?>