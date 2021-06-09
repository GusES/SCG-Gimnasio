<?php 
include('../../../db_conn.php');
//Variables sin uso real en el php, solo de js
$montocredito=(int)isset($_POST['montocredito'])?$_POST['montocredito']:0;
$restante=(int)isset($_POST['restante'])?$_POST['restante']:0;
if($restante == ""){$restante = 0;}
//fin de Variables sin uso real en el php

// Variables que se usan o podrian usarse
$idgrupos=isset($_POST['idgrupos'])?$_POST['idgrupos']:"";
$num_factura=isset($_POST['num_factura'])?$_POST['num_factura']:"";
$nom_grupos=isset($_POST['nom_grupos'])?str_replace("'","''",$_POST['nom_grupos']):"";
$modulo=isset($_POST['modulo'])?$_POST['modulo']:"";
$precio=(int)isset($_POST['precio'])?$_POST['precio']:0;
$previo=(int)isset($_POST['previo'])?$_POST['previo']:0;
$abonado=(int)isset($_POST['abonado'])?$_POST['abonado']:0;
$showrestante=(int)isset($_POST['showrestante'])?abs($_POST['showrestante']):0;
//Arrays comunes
$nombre=isset($_POST['nombre'])?$_POST['nombre']:"";
$idcliente=isset($_POST['idcliente'])?$_POST['idcliente']:"";
//Arrays complejos sin separar
$credito=isset($_POST['newcredito'])?$_POST['newcredito']:"";
$indice=isset($_POST['id'])?$_POST['id']:"";
//Arrays complejos separados
@$newcredito = explode(",",$credito[0]); 
@$id = explode(",",$indice[0]); 

//Calculos para el nuevo abonado
if($precio == $abonado){$subtotal = $abonado;}else{$subtotal = $previo+$abonado;}
if($showrestante != "" && $abonado < $precio){$subtotal = $subtotal + $showrestante;}

if($idgrupos != "" && $num_factura != ""){
  if(!empty($id[0])){
    for ($i = 0; $i < count($newcredito); $i++){
      $update = "UPDATE clientes SET notacredito = '$newcredito[$i]' WHERE idclientes = '$id[$i]'";
      $execute = $con->query($update);
    }
  }
  $sql = "UPDATE facturacion SET nom_grupos = '$nom_grupos', disciplina = '$modulo', precio = '$precio', abonado = '$subtotal' WHERE num_factura = '$num_factura';";
  $result = $con->query($sql);
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='4;URL=../facturas_grupo.php?idgrupos=$idgrupos' />
        ";
} else {
  print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../index.php'></head></html>";
} ?>