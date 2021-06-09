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
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_clientes.php">Clientes</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
					<a class="btn btn-dark d-block text-left active" href="ver_ejercicios.php">Entrenamiento</a>
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
								<h5 class="m-0">Nuevo Ejercicio</h5>
							</div>
						</div>
					</div>
					<div class="col-12">
						<form method="POST" action="php/agregar_ejercicio.php" enctype="multipart/form-data">
							<div class="form-group row mb-1">
								<label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Nombre del Ejercicio" name="nombre" id="nombre" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="numero" class="col-sm-2 col-form-label">Identificación:</label>
								<div class="col-sm-10">
									<input type="number" class="form-control" placeholder="Nº de Identificación del Banco/Maquina" name="numero" id="numero">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="file" class="col-sm-2 col-form-label">Imagen:</label>
								<div class="col-sm-10">
									<div class="form-group mb-1">
										<input type="file" class="form-control-file" id="file" name="file" accept="image/*" required>
									</div>
									<div id="list" style="text-align:center" class="bg-secondary rounded">
									</div>
								</div>
							</div>
							<div class="form-group row mb-2">
								<label for="grupomuscular" class="col-sm-2 col-form-label">Grupo Muscular Principal:</label>
								<div class="col-sm-10">
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="pecho" value="pecho">
										<label class="custom-control-label" for="pecho">Pecho</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="espalda" value="espalda">
										<label class="custom-control-label" for="espalda">Espalda</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="biceps" value="biceps">
										<label class="custom-control-label" for="biceps">Bíceps</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="triceps" value="triceps">
										<label class="custom-control-label" for="triceps">Tríceps</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="hombros" value="hombros">
										<label class="custom-control-label" for="hombros">Hombros</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="trapecios" value="trapecios">
										<label class="custom-control-label" for="trapecios">Trapecios</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="gluteos" value="gluteos">
										<label class="custom-control-label" for="gluteos">Glúteos</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="quadriceps" value="quadriceps">
										<label class="custom-control-label" for="quadriceps">Quadríceps</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="isquiotibiales" value="isquiotibiales">
										<label class="custom-control-label" for="isquiotibiales">Isquiotibiales</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="aductores/abductores" value="aductores/abductores">
										<label class="custom-control-label" for="aductores/abductores">Aductores/Abductores</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="gemelos" value="gemelos">
										<label class="custom-control-label" for="gemelos">Gemelos</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="abdomen" value="abdomen">
										<label class="custom-control-label" for="abdomen">Abdomen</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="oblicuos" value="oblicuos">
										<label class="custom-control-label" for="oblicuos">Oblicuos</label>
									</div>
									<div class="custom-control custom-radio custom-control-inline">
										<input class="custom-control-input" type="radio" name="musculo" id="otros" value="otros" checked>
										<label class="custom-control-label" for="otros">Otros</label>
									</div>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="detalles" class="col-sm-2 col-form-label">Detalles:</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="detalles" placeholder="Opcional: Informacion adicional" name="detalles" rows="3" maxlength="350"></textarea>
								</div>
							</div>
							<div class="col-sm-12 text-center my-4">
								<button type="submit" class="btn-sm btn btn-success text-center">Aceptar</button>
								<a class="btn-sm btn btn-secondary" href="ver_ejercicios.php">Cancelar</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	function archivo(evt) {
		var files = evt.target.files; // FileList object
		//Obtenemos la imagen del campo "file". 
		for (var i = 0, f; f = files[i]; i++) {
			//Solo admitimos imágenes.
			if (!f.type.match('image.*')) {
				continue;
			}
			var reader = new FileReader();
			reader.onload = (function(theFile) {
				return function(e) {
					// Creamos la imagen.
					document.getElementById("list").innerHTML = ['<img style="padding: .375rem 0rem;width: 100%;max-width: 350px;" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
				};
			})(f);
			reader.readAsDataURL(f);
		}
	}
	document.getElementById('file').addEventListener('change', archivo, false);
</script>

</html>