<?php
if(isset($_GET['idcliente']) && isset($_GET['idrutina'])){
	$idcliente=$_GET['idcliente'];
	$idrutina=$_GET['idrutina'];
  } else {
	mysqli_close($con);
	header('location:../panel/admin/ver_rutinas_clientes.php'); 
};

$idcliente = base64_encode($idcliente);
$idrutina = base64_encode($idrutina);

if(gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']))!='127.0.0.1'){
        $ip = gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $info="";
		} else {
			echo "<p>Fallo al obtener enlace, compruebe que el router este activo y ambos dispositivos conectados al mismo.</p>";
            $info = " d-none";
            $ip="";
		};

$i = base64_encode($ip);

require 'phpqrcode/qrlib.php';
 
$dir = 'temp/';
 
if(!file_exists($dir)){ mkdir($dir); };
   
$filename = $dir.'test.png';

$tamanio = 5;
$level = 'M';
$frameSize = 3;
$contenido = "http://$ip:8080/xampp/maqueta/codigo_qr/index_usuario.php?idcliente=$idcliente&idrutina=$idrutina&i=$i";

QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);

echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../js/bootstrap.min.js'></script><script type='text/javascript' src='../js/font-all.min.js'></script><link rel='stylesheet' href='../css/bootstrap.min.css'></head>";
echo "<body><div class='alert alert-success text-center $info'><h4 class='alert-heading'>¡Codigo QR generado con éxito!</h4><p class='mb-0'>¡Cuando se active la cámara de su lector QR, apunta tu teléfono hacia esta pantalla para escanear el código!</p><hr>";
echo '<img src="'.$dir.basename($filename).'" /><hr/>'; 
echo "</div></body></html>";
?>