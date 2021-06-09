<?php 
include('../../../db_conn.php');
$dni=isset($_GET['dni'])?$_GET['dni']:null;
$idcliente=isset($_GET['idcliente'])?$_GET['idcliente']:null;
$estado=isset($_GET['estado'])?$_GET['estado']:null;

$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d H:i:s');
$fecha = date( "Y-m-d H:i:s", strtotime( "$fecha +1 month" ) );
//comprobamos que llegue un nombre y apellido de cliente
if($dni != null && $estado != null && $idcliente != null){
  if($estado == 3){
    $sql = "UPDATE clientes SET estado = '1', ult_ingreso = '$fecha', ult_fecha_pago = '$fecha' WHERE dni = '$dni' ";	
    $sqli = "UPDATE asignaciones SET fecha_vencimiento = '$fecha' WHERE idcliente = '$idcliente' ";	
  } else {
    print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../clientes_baja.php'></head></html>";
    exit();
  }
  $result = $con->query($sql);
  $result = $con->query($sqli);
  echo " <meta http-equiv='refresh' content='0;URL=../clientes_baja.php' /> ";	
} else {
  print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../clientes_baja.php'></head></html>";
}
?>