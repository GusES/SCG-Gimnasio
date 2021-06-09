<?php include '../../db_conn.php'; session_start(); check_login(); ?>
<!DOCTYPE html>
<html lang="es">
<?php include 'head.php' ?>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-2 p-0 sticky-top" style="height:100vh">
				<nav class="bg-dark" style="height:100%">
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="index.php">Inicio</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_membresias.php">Membresías</a>
					<a class="btn btn-dark d-block text-left active" style="border-bottom:1px solid #6c757d;" href="ver_clientes.php">Clientes</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
					<a class="btn btn-dark d-block text-left" href="ver_ejercicios.php">Entrenamiento</a>
				</nav>
			</div>
			<div class="col-10">
				<div class="row">
					<div class="col-12 mb-1">
						<div class="row bg-secondary" style="height:59%;">
							<div class="col-6">
								<p class="m-0 badge badge-secondary"><?=$_SESSION['rol'];?></p>
							</div>
							<div class="col-6 text-right">
								<a class="btn btn-danger btn-sm py-0 px-2" href="../logout.php">Cerrar Sesión</a>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<h5 class="m-0">Nuevo Cliente</h5>
							</div>
						</div>
					</div>
					<div class="col-12">
						<form method="POST" action="php/agregar_cliente.php">
							<div class="form-group row mb-1">
								<label for="nombre" class="col-sm-2 col-form-label">Nombre(s): </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Nombre(s)" name="nombre" id="nombre" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="apellido" class="col-sm-2 col-form-label">Apellido(s): </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Apellido(s)" name="apellido" id="apellido" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="nacimiento" class="col-sm-2 col-form-label">Fecha de Nacimiento: </label>
								<div class="col-sm-10">
									<input type="date" class="form-control" name="nacimiento" id="nacimiento" min="1920-1-1" max="2099-12-31" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="dni" class="col-sm-2 col-form-label">DNI: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="dni" id="dni" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" placeholder="12345678" min="1" max="99999999" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="genero" class="col-sm-2 col-form-label">Genero: </label>
								<div class="col-sm-10">
									<select class="form-control" id="genero" name="genero" required>
										<option value="" disabled selected>Seleccione una opcion</option>
										<option value="masculino">Masculino</option>
										<option value="femenino">Femenino</option>
										<option value="otro">Otro</option>
									</select>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="direccion" class="col-sm-2 col-form-label">Dirección: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Dirección" name="direccion" id="direccion">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="clave" class="col-sm-2 col-form-label">Clave: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="clave" id="clave" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" placeholder="1234" min="1" max="9999" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="telefono" class="col-sm-2 col-form-label">Teléfono: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="---" name="telefono" id="telefono">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="facebook" class="col-sm-2 col-form-label">Facebook: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="---" name="facebook" id="facebook">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="correo" class="col-sm-2 col-form-label">Email: </label>
								<div class="col-sm-10">
									<input type="email" class="form-control" placeholder="---" name="correo" id="correo">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="observacion" class="col-sm-2 col-form-label">Observaciones: </label>
								<div class="col-sm-10">
									<textarea class="form-control" id="observacion" placeholder="Opcional: Informacion adicional" name="observacion" rows="3" maxlength="1000"></textarea>
								</div>
							</div>
							<div class="col-sm-12 text-center my-4">
								<button type="submit" class="btn btn-sm btn-success text-center">Aceptar</button>
								<a class="btn btn-sm btn-secondary" href="ver_clientes.php">Cancelar</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	//Limitar caracteres y solo numeros
	function maxLengthCheck(object) {
		if (object.value.length > object.max.length)
			object.value = object.value.slice(0, object.max.length)
	}

	function isNumeric(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode(key);
		var regex = /[0-9]|\./;
		if (!regex.test(key)) {
			theEvent.returnValue = false;
			if (theEvent.preventDefault) theEvent.preventDefault();
		}
	}
</script>

</html>