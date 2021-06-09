<?php
session_start();
if(@$_SESSION['autenticado']==true){
	header('location:panel/admin/index.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<link rel="shortcut icon" href="images/favicon.png">
	<meta charset="UTF-8">
	<title>Iniciar Sesion</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>

<body>
	<center>
		<?php
		if(gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']))!='127.0.0.1'){
			echo "<h5 class='mb-0'>Enlace de Conexion al Sistema <span class='text-primary'>" 
				.gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR'])).":8080</span></h5>";		
		} else {
			echo "<p>Fallo al obtener enlace, compruebe que el router este activo y ambos 	dispositivos conectados al mismo.</p>";
		}
		?>
		<hr class="p-0 m-0">
	</center>
	<h1 class="mt-2">
		<center>Bienvenido</center>
	</h1>
	<form action="panel/login.php" method='POST' id="login" class="col-lg-4 offset-lg-4 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
		<div class="form-group">
			<label for="usuario">Nombre de Usuario</label>
			<input type="text" placeholder="Usuario" name="usuario" class="form-control">
		</div>
		<div class="form-group">
			<label for="contrasena">Contraseña</label>
			<input type="password" name="contrasena" placeholder="******" class="form-control">
		</div>
		<div class="text-center">
			<button type="submit" class="btn btn-secondary">Iniciar Sesion</button>
		</div>
		<hr>
	</form>
	<div class="text-center mb-5">
		<a class="btn btn-outline-secondary" href="panel/users/ingresos.php">Ingreso de Clientes</a>
	</div>
</body>

</html>