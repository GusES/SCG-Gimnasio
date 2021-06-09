<?php include '../../db_conn.php'; session_start(); check_login();?>
<!DOCTYPE html>
<html lang="es">
<?php include 'head.php' ?>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-2 p-0 sticky-top" style="height:100vh">
				<nav class="bg-dark" style="height:100%">
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="index.php">Inicio</a>
					<a class="btn btn-dark d-block text-left active" style="border-bottom:1px solid #6c757d;" href="ver_membresias.php">Membresías</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_clientes.php">Clientes</a>
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
								<h5 class="m-0">Nueva Membresía</h5>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="col-12 text-center my-2">
							<p class="m-0">Seleccione una Opción</p>
							<button type="button" class="btn btn-info btn-sm" id="btn1" onclick="btn1()">Una Actividad</button>
							<button type="button" class="btn btn-info btn-sm" id="btn2" onclick="btn2()">Promoción de Act.</button>
							<hr>
						</div>
						<!--FORMULARIO 1 PARA ACTIVIDADES-->
						<form method="POST" action="php/agregar_membresia.php" id="form1" style="display:none">
							<input type="hidden" name="actividades" value="1">
							<div class="form-group row mb-1">
								<label for="nom_modulo" class="col-sm-2 col-form-label">Nombre: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Nombre de la Actividad" name="nom_modulo" id="nom_modulo" required>
								</div>
							</div>
							<div class="form-group row mb-1" hidden>
								<label for="actividades" class="col-sm-2 col-form-label">Cantidad de Persona(s): </label>
								<div class="col-sm-10 mt-2">
									<!-- Default inline 1-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="op1" name="cant_per" value="1" checked required>
										<label class="custom-control-label" for="op1">1</label>
									</div>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="precio" class="col-sm-2 col-form-label">Precio: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control peso" placeholder="-" name="precio" id="precio" min="0" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="detalle" class="col-sm-2 col-form-label">Detalles: </label>
								<div class="col-sm-10">
									<textarea class="form-control" id="detalle" placeholder="Opcional: Informacion adicional" name="detalle" rows="3" maxlength="350"></textarea>
								</div>
							</div>
							<div class="col-sm-12 text-center my-4">
								<button type="submit" class="btn btn-success btn-sm text-center">Aceptar</button>
								<a class="btn btn-secondary btn-sm" href="ver_membresias.php">Cancelar</a>
							</div>
						</form>
						<!--FORMULARIO 2 PARA PROMOCIONES-->
						<form method="POST" action="php/agregar_membresia.php" id="form2" style="display:none">
							<input type="hidden" name="actividades" value="2">
							<div class="form-group row mb-1">
								<label for="nom_modulo" class="col-sm-2 col-form-label">Nombre: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Nombre de las Actividades" name="nom_modulo" id="nom_modulo" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="actividades" class="col-sm-2 col-form-label">Nº de Personas: <i class="fal fa-question-circle" title="Seleccionar el número de personas correspondiente al número de actividades promocionales"></i></label>
								<div class="col-sm-10 mt-2">
									<!-- Default inline 2-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="form2_op2" name="cant_per" value="2" checked>
										<label class="custom-control-label" for="form2_op2">2</label>
									</div>
									<!-- Default inline 3-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="form2_op3" name="cant_per" value="3">
										<label class="custom-control-label" for="form2_op3">3</label>
									</div>
									<!-- Default inline 4-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="form2_op4" name="cant_per" value="4">
										<label class="custom-control-label" for="form2_op4">4</label>
									</div>
									<!-- Default inline 5-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="form2_op5" name="cant_per" value="5">
										<label class="custom-control-label" for="form2_op5">5</label>
									</div>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="precio" class="col-sm-2 col-form-label">
									Precio: <i class="fal fa-question-circle" title="Establecer precio total por promoción"></i></label>
								<div class="col-sm-10">
									<input type="number" class="form-control peso" placeholder="-" name="precio" id="precio" min="0" required>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="detalle" class="col-sm-2 col-form-label">Detalles: </label>
								<div class="col-sm-10">
									<textarea class="form-control" id="detalle" placeholder="Opcional: Informacion adicional" name="detalle" rows="3" maxlength="350"></textarea>
								</div>
							</div>
							<div class="col-sm-12 text-center my-4">
								<button type="submit" class="btn-sm btn btn-success text-center">Aceptar</button>
								<a class="btn-sm btn btn-secondary" href="ver_membresias.php">Cancelar</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con); ?>
<script type="text/javascript">
	function btn1() {
		document.getElementById("btn1").classList.add("active");
		document.getElementById("btn2").classList.remove("active");
		document.getElementById("form1").style.display = "block";
		document.getElementById("form2").style.display = "none";
	}

	function btn2() {
		document.getElementById("btn1").classList.remove("active");
		document.getElementById("btn2").classList.add("active");
		document.getElementById("form1").style.display = "none";
		document.getElementById("form2").style.display = "block";
	}
</script>

</html>