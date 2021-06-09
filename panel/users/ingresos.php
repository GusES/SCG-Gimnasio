<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Registro de Ingresos</title>
	<link rel="shortcut icon" href="../../images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="../../js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
	<style>
		@font-face {
			font-family: 'password';
			src: url(../../font/password.ttf);
		}

		.password {
			font-family: 'password';
		}

		input[type=number]::-webkit-inner-spin-button,
		input[type=number]::-webkit-outer-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>
</head>

<body>
	<h1>
		<center>Bienvenido/a</center>
	</h1>
	<form action="php/ingresos_login.php" method="POST" class="col-lg-4 offset-lg-4 col-md-8 offset-md-2 col-sm-10 offset-sm-1 mb-5">
		<div class="form-group">
			<label for="dni">Numero de Documento</label>
			<input type="number" name="dni" class="form-control" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" placeholder="DNI" max="99999999" required />
		</div>
		<div class="form-group">
			<label for="clave">Clave</label>
			<input type="number" name="clave" class="form-control password" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" placeholder="----" max="9999" required />
		</div>
		<div class="text-center">
			<button type="submit" class="btn btn-secondary">Aceptar</button>
		</div>
		<hr>
		<small class="form-text text-muted">
			1) Nº de documento sin espacios, comas o puntos.
		</small>
		<small class="form-text text-muted">
			2) La clave es un valor numérico de 4 caracteres.
		</small>
	</form>
	<script>
		//Limita caracteres
		function maxLengthCheck(object) {
			if (object.value.length > object.max.length)
				object.value = object.value.slice(0, object.max.length)
		}
		//Acepta solo numero 0 al 9
		function isNumeric(evt) {
			var theEvent = evt || window.event;
			var key = theEvent.keyCode || theEvent.which;
			key = String.fromCharCode(key);
			var regex = /[0-9]/;
			if (!regex.test(key)) {
				theEvent.returnValue = false;
				if (theEvent.preventDefault) theEvent.preventDefault();
			}
		}
	</script>
</body>

</html>