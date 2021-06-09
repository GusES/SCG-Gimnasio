<?php 
include('../../../db_conn.php');
$creditoprevio=0;
$id=isset($_POST['id'])?$_POST['id']:"";
if(is_numeric($id)){
	$query = "SELECT * FROM clientes WHERE idclientes = $id";
  $resultado = $con->query($query);
  $fila = $resultado->fetch_object();
	$creditoprevio=(int)$fila->notacredito;
}
$num_factura=isset($_POST['num_factura'])?$_POST['num_factura']:null;
$nombre=isset($_POST['nom_completo'])?str_replace("'","''",$_POST['nom_completo']):"";
$modulo=isset($_POST['modulo'])?str_replace("'","''",$_POST['modulo']):"";
$modulo_array=explode(",",$modulo);
$valores=isset($_POST['valores'])?$_POST['valores']:"";
$precio=(int)isset($_POST['precio'])?(int)$_POST['precio']:(int)0;
$previo=(int)isset($_POST['previo2'])?(int)$_POST['previo2']:(int)0;
$newcredito=(int)isset($_POST['notacredito'])?(int)$_POST['notacredito']:(int)0;
$notacredito = $creditoprevio + $newcredito;
if($previo >= $precio){
  $abonado = $precio;
} else if($previo < $precio) {
  $abonado=(int)isset($_POST['abonado'])?(int)$_POST['abonado']:(int)0;
  $abonado= $abonado+$previo;
}else{
  $abonado=(int)isset($_POST['abonado'])?(int)$_POST['abonado']:(int)0;  
}

if(empty(trim($modulo))){
	$modulo="convertido a nota de credito";
	$valores=0;
	$precio=0;
	$abonado=0;
}

if($num_factura != ""){
	$sql = "UPDATE facturacion SET nom_completo = '$nombre', disciplina = '$modulo', precio = '$precio', abonado = '$abonado', valores = '$valores' WHERE num_factura = '$num_factura' AND idcliente = '$id'";
  $result = $con->query($sql);
  $sql = "UPDATE clientes SET notacredito = '$notacredito' WHERE idclientes = '$id' ";
  $result = $con->query($sql);
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='4;URL=../facturas_cliente.php?idcliente=$id' />";	
} else {
  print "<html><head><script>alert('Ocurrio un error inesperado.');</script><meta http-equiv='refresh' content='0; url=../facturas_cliente.php'></head></html>";
} 
?>