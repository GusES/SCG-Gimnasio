<?php 
include('../../../db_conn.php');
$nombree=isset($_POST['nombre'])?$_POST['nombre']:"";
$nombre=mb_strtolower($nombree);
$apellidoo=isset($_POST['apellido'])?$_POST['apellido']:"";
$apellido=mb_strtolower($apellidoo);
$direccionn=isset($_POST['direccion'])?str_replace("'","''",$_POST['direccion']):"";
$direccion=mb_strtolower($direccionn);
$nacimiento=isset($_POST['nacimiento'])?$_POST['nacimiento']:"";
$telefono=isset($_POST['telefono'])?$_POST['telefono']:"";
$correo=isset($_POST['correo'])?str_replace("'","''",$_POST['correo']):"";
$facebook=isset($_POST['facebook'])?str_replace("'","''",$_POST['facebook']):"";
$genero=isset($_POST['genero'])?$_POST['genero']:"";
$observacion=isset($_POST['observacion'])?str_replace("'","''",$_POST['observacion']):"";
$dni=isset($_POST['dni'])?$_POST['dni']:0;
$clave=isset($_POST['clave'])?$_POST['clave']:0;

$query = "SELECT dni FROM clientes WHERE dni = $dni";
$rs_dni=$con->query($query);
$cnt_dni=$rs_dni->num_rows;

if ($cnt_dni == 0){
     if(trim($nombre) != "" && trim($apellido) != ""){
       $sql = "INSERT INTO clientes(nombre, apellido, direccion, nacimiento, dni, telefono, correo, facebook, genero, observacion, clave)      VALUES ('$nombre', '$apellido', '$direccion', '$nacimiento', '$dni', '$telefono', '$correo', '$facebook', '$genero',      '$observacion','$clave');";
       $result = $con->query($sql);
       $sql="SELECT * FROM clientes WHERE dni = '$dni'";
       //*****************************************************************//
       $rs=$con->query($sql);
       $row=$rs->fetch_object();
       echo "<meta http-equiv='refresh' content='0;URL=../crear_asignar.php?idcliente=$row->idclientes'/>";	
     } else {
       print "<html><head><script>alert('Debe ingresar el nombre y apellido del nuevo cliente.');</script><meta http-equiv='refresh' content='0; url=../crear_cliente.php'></head></html>";
     }
} else {
 print "<html><head><script>alert('¡Error! El DNI coincide con un cliente ya existente.');</script><meta http-equiv='refresh' content='0; url=../crear_cliente.php'></head></html>";
}
?>