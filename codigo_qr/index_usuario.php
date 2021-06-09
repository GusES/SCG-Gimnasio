<?php
if(isset($_GET['idcliente']) && isset($_GET['idrutina']) && isset($_GET['i'])){
	$idcliente= base64_decode($_GET['idcliente']);
	$idrutina= base64_decode($_GET['idrutina']);
	$i= base64_decode($_GET['i']);
} else {
	mysqli_close($con);
	header('location:../panel/admin/ver_rutinas_clientes.php'); 
}

/*if(gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']))!='127.0.0.1'){
	$ip = gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']));
	$info="";
}; else {
	echo "<p>Fallo al obtener enlace, compruebe que el router este activo y ambos dispositivos conectados al mismo.</p>";
	$info = " d-none";
	$ip="";
} */

echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../js/bootstrap.min.js'></script><script type='text/javascript' src='../js/font-all.min.js'></script><link rel='stylesheet' href='../css/bootstrap.min.css'></head>";
echo "<body>
      <div class='alert alert-success text-center' style='height:100%;margin:0;padding:0;'><h1 class='alert-heading' style='margin-top:20%;padding:0;'>¡Ya es posible realizar la descarga!</h1><br><h5>¡Presione el botón Descargar PDF!</h5><hr>
      <a style='margin-top:10%;margin-bottom:10%;' class='btn-sm btn btn-success text-center' href='http://$i:8080/xampp/maqueta/fpdf/imprimir_rutinas.php?idcliente=$idcliente&idrutina=$idrutina' download='pdf_rutina'>
      <h1 class='alert-heading'>Descargar PDF</h1>
      </a><hr>
      <h5>Si luego de presionar en el botón Descargar PDF no se realizo la descarga, consulte con algún encargado.</h5>
      </div>
      </body></html>";
?>