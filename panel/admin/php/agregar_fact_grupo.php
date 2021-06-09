<?php 
include('../../../db_conn.php');
$idcliente=isset($_POST['idcliente'])?$_POST['idcliente']:"";
$idgrupos=isset($_POST['idgrupos'])?$_POST['idgrupos']:"";
$fecha_fact=isset($_POST['fecha_fact_grupo'])?$_POST['fecha_fact_grupo']:"";
$num_factura=isset($_POST['num_factura'])?$_POST['num_factura']:"";
$nom_grupos=isset($_POST['nom_grupos'])?str_replace("'","''",$_POST['nom_grupos']):"";
$modulo=isset($_POST['modulo'])?$_POST['modulo']:"";
$precio=isset($_POST['precio'])?$_POST['precio']:"";
$abonado=isset($_POST['abonado'])?$_POST['abonado']:"";
$acumulado=isset($_POST['notacredito'])?$_POST['notacredito']:"";
$restante=isset($_POST['restante'])?$_POST['restante']:0;
if($restante == 0 && $abonado != $precio){ $abonado = $abonado + $acumulado; }
$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d H:i:s');
$vencimieto = date( "Y-m-d H:i:s", strtotime( "$fecha +1 month" ) );
$credito=isset($_POST['newcredito'])?$_POST['newcredito']:null;
$newcredito = explode(",",$credito[0]); 
$indice=isset($_POST['id'])?$_POST['id']:null;
$id = explode(",",$indice[0]); 
if($fecha_fact != NULL){
   $fecha_factura = new DateTime($fecha_fact);
   $mes = $fecha_factura->format("m");
   $anio = $fecha_factura->format("Y");
}
function nombremes($mes){
 setlocale(LC_TIME, 'spanish');  
 $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
 return $nombre;
}
$mes_nombre=nombremes($mes);
$query = "SELECT COUNT(disciplina) AS cont_disciplina FROM facturacion WHERE idgrupos = $idgrupos AND disciplina = '$modulo' AND MONTH(fecha_pago)= $mes AND YEAR(fecha_pago)= $anio";
$result = mysqli_query($con, $query);
$contador = mysqli_fetch_row($result);
$sql = "SELECT *, fecha_pago AS fecha FROM facturacion where idgrupos = $idgrupos AND disciplina = '$modulo' ORDER BY fecha_pago DESC LIMIT 1";
$resultado = $con->query($sql);
$row = $resultado->fetch_object();
  //control de ingreso al sistema
 if (is_null($row)){
 $fecha_pago="No registra pagos";
 } else {
 $fecha_pago=fechaCastellano($row->fecha_pago);
      
 }; 
if($fecha != NULL){
   $fecha_factura = new DateTime($fecha);
   $fecha_alert = $fecha_factura->format("d-m-Y");
}
if($contador[0] >= 1){
	echo "<html>";
	echo "<!-- meta character set -->";
	echo "<meta charset='UTF-8'>";
	echo "<!-- Jquery -->";
	echo "<script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script>";
	echo "<!-- bootstrap -->";
	echo "<link rel='stylesheet' href='../../../css/bootstrap.min.css'>";
	echo "<script type='text/javascript' src='../../../js/bootstrap.min.js'></script>";
	echo "<body>";
	echo "<div class='alert alert-danger' role='alert'><h4 class='alert-heading'>¡No es posible realizar la acción!</h4><p>
	<b>Grupo</b>: "; 
	echo ucwords($nom_grupos)."<br>"; 
	echo "<b>Abono</b>: "; 
	echo ucwords($modulo)."<br>"; 
	echo "<b>Pagado</b>: "; 
	echo $fecha_pago; 
	echo ".</p>
				<hr>
				<p class='mb-0'>Sera redireccionado en segundos.</p>
				</div>
				<meta http-equiv='refresh' content='3;URL=../ver_gruposclientes.php' />";
	echo "</body>";
	echo "</html>";
} else {
  if($idgrupos != ""){
  switch (count($newcredito)){
    case 1:
      $update="UPDATE clientes SET notacredito = '$newcredito[0]' WHERE idclientes = '$id[0]'";     
      $ejecutar = $con->query($update);    
      break;  
    case 2:
      $update="UPDATE clientes SET notacredito = '$newcredito[0]' WHERE idclientes = '$id[0]'";
      $ejecutar = $con->query($update);                
      $update="UPDATE clientes SET notacredito = '$newcredito[1]' WHERE idclientes = '$id[1]'";
      $ejecutar = $con->query($update);                    
      break;
    case 3:
      $update="UPDATE clientes SET notacredito = '$newcredito[0]' WHERE idclientes = '$id[0]'";
      $ejecutar = $con->query($update);                
      $update="UPDATE clientes SET notacredito = '$newcredito[1]' WHERE idclientes = '$id[1]'";
      $ejecutar = $con->query($update);                    
      $update="UPDATE clientes SET notacredito = '$newcredito[2]' WHERE idclientes = '$id[2]'";
      $ejecutar = $con->query($update);      
      break;
    case 4:
      $update="UPDATE clientes SET notacredito = '$newcredito[0]' WHERE idclientes = '$id[0]'";
      $ejecutar = $con->query($update);                
      $update="UPDATE clientes SET notacredito = '$newcredito[1]' WHERE idclientes = '$id[1]'";
      $ejecutar = $con->query($update);                    
      $update="UPDATE clientes SET notacredito = '$newcredito[2]' WHERE idclientes = '$id[2]'";
      $ejecutar = $con->query($update);      
      $update="UPDATE clientes SET notacredito = '$newcredito[3]' WHERE idclientes = '$id[3]'";
      $ejecutar = $con->query($update);      
      break;
    case 5:
      $update="UPDATE clientes SET notacredito = '$newcredito[0]' WHERE idclientes = '$id[0]'";
      $ejecutar = $con->query($update);                
      $update="UPDATE clientes SET notacredito = '$newcredito[1]' WHERE idclientes = '$id[1]'";
      $ejecutar = $con->query($update);                    
      $update="UPDATE clientes SET notacredito = '$newcredito[2]' WHERE idclientes = '$id[2]'";
      $ejecutar = $con->query($update);      
      $update="UPDATE clientes SET notacredito = '$newcredito[3]' WHERE idclientes = '$id[3]'";
      $ejecutar = $con->query($update);  
      $update="UPDATE clientes SET notacredito = '$newcredito[4]' WHERE idclientes = '$id[4]'";
      $ejecutar = $con->query($update);  
      break;
  }  
  foreach($idcliente as $valor){ 
    $query = "SELECT * FROM clientes where idclientes = $valor";     
    $resultado = $con->query($query);
    $fila = $resultado->fetch_object(); 
    $nombre = "$fila->nombre " . "$fila->apellido";
    $sql = "INSERT INTO facturacion(tipo, idcliente, idgrupos, nom_completo, nom_grupos, disciplina, precio, abonado, num_factura, fecha_pago, fecha_vence) VALUES('grupo','$valor', '$idgrupos', '$nombre', '$nom_grupos', '$modulo', '$precio',    '$abonado', '$num_factura', '$fecha', '$vencimieto')";
    $result = $con->query($sql);     
    $sql = "UPDATE clientes SET ult_fecha_pago = '$fecha' WHERE idclientes = '$valor' ";
    $result = $con->query($sql);
  }
   echo "<html>";
   echo "<!-- meta character set -->";
   echo "<meta charset='UTF-8'>";
   echo "<!-- Jquery -->";
   echo "<script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script>";
   echo "<!-- bootstrap -->";
   echo "<link rel='stylesheet' href='../../../css/bootstrap.min.css'>";
   echo "<script type='text/javascript' src='../../../js/bootstrap.min.js'></script>";
   echo "<body>";
   echo "<div class='alert alert-success' role='alert'>
           <h4 class='alert-heading'>¡Tarea realizada con éxito!</h4>
					 <p><b>Grupo</b>: "; 
	echo ucwords($nom_grupos)."<br>"; 
	echo "<b>Abono</b>: "; 
	echo ucwords($modulo); 
		echo ".</p>
           <hr>
           <p class='mb-0'>Sera redireccionado en segundos.</p>
         </div>
         <meta http-equiv='refresh' content='4;URL=../ver_gruposclientes.php' />";
   echo "</body>";
   echo "</html>";
}
} ?>