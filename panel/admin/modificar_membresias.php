<?php
include '../../db_conn.php'; session_start(); check_login();
$idmembresias=$_GET['id']?$_GET['id']:"";
if(is_numeric($idmembresias)){
	$sql = "SELECT * FROM membresias WHERE idmembresias = $idmembresias ;";
	$result = $con->query($sql);
	$row = $result->fetch_object();
} else {
  header('location: ../../index.php');
  exit();
} ?>
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
					<div class="col-12 mb-3">
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
								<h5 class="m-0">Modificar Membresía</h5>
							</div>
						</div>
					</div>
					<div class="col-12">
						<form method="POST" action="php/editar_membresia.php">
							<input type="text" name="id" value="<?=$row->idmembresias?>" hidden>
							<div class="form-group row mb-1">
								<label for="nom_modulo" class="col-sm-2 col-form-label">Nombre: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Nombre del Modulo" name="nom_modulo" id="nom_modulo" required value="<?=ucwords($row->nom_modulo)?>">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="actividades" class="col-sm-2 col-form-label"><span style="font-size:15.5px">Actividades: </span><i class="fal fa-question-circle" title="No se puede modificar la opción de actividades"></i> </label>
								<div class="col-sm-10">
									<select class="form-control text-capitalize" disabled>
										<option value="<?=$row->actividades?>" selected>
											<?php
											if($row->actividades=='1'){
												echo "Una actividad";
											}else{
												echo "Promocíon de actividades";}
											?>
										</option>
									</select>
									<input type="hidden" name="actividades" id="actividades" value="<?php if($row->actividades == '1'){echo "1";}else if($row->actividades == '2'){echo "2";}?>">
								</div>
							</div>
							<div class="form-group row mb-1" <?php if($row->cant_per=='1'){echo "hidden";}?>>
								<label for="actividades" class="col-sm-2 col-form-label">Nº de Personas: </label>
								<div class="col-sm-10 mt-2">
									<?php 
									if($row->cant_per == '1'){
									?>
									<!-- Default inline 1-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="op1" name="cant_per" value="1" checked required>
										<label class="custom-control-label" for="op1">1</label>
									</div>
									<?php } else if($row->cant_per > '1'){ ?>
									<!-- Default inline 2-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="op2" name="cant_per" value="2" <?php if($row->cant_per==2){echo "checked";}?>>
										<label class="custom-control-label" for="op2">2</label>
									</div>
									<!-- Default inline 3-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="op3" name="cant_per" value="3" <?php if($row->cant_per==3){echo "checked";}?>>
										<label class="custom-control-label" for="op3">3</label>
									</div>
									<!-- Default inline 4-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="op4" name="cant_per" value="4" <?php if($row->cant_per==4){echo "checked";}?>>
										<label class="custom-control-label" for="op4">4</label>
									</div>
									<!-- Default inline 5-->
									<div class="custom-control custom-radio custom-control-inline">
										<input type="radio" class="custom-control-input" id="op5" name="cant_per" value="5" <?php if($row->cant_per==5){echo "checked";}?>>>
										<label class="custom-control-label" for="op5">5</label>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="precio" class="col-sm-2 col-form-label">Precio: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control peso" placeholder="0" name="precio" id="precio" min="0" required value="<?=$row->precio?>">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="detalle" class="col-sm-2 col-form-label">Detalles: </label>
								<div class="col-sm-10">
									<textarea class="form-control" id="detalle" placeholder="Opcional: Informacion adicional" name="detalle" rows="3" maxlength="350"><?=$row->detalle?></textarea>
								</div>
							</div>
							<div class="col-sm-12 text-center my-4">
								<button type="submit" class="btn btn-sm btn-success text-center">Aceptar</button>
								<a class="btn btn-sm btn-secondary" href="ver_membresias.php">Cancelar</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con) ?>
<script type="text/javascript">
	$(function() {
		$("#actividades").change(function() {
			if ($(this).val() === "1") {
				$("#op1").prop("disabled", false);
				$("#op2").prop("disabled", true);
				$("#op2").prop("checked", false);
				$("#op3").prop("disabled", true);
				$("#op3").prop("checked", false);
				$("#op4").prop("disabled", true);
				$("#op4").prop("checked", false);
				$("#op5").prop("disabled", true);
				$("#op5").prop("checked", false);
			} else {
				$("#op1").prop("disabled", true);
				$("#op1").prop("checked", false);
				$("#op2").prop("disabled", false);
				$("#op3").prop("disabled", false);
				$("#op4").prop("disabled", false);
				$("#op5").prop("disabled", false);
			}
		});
	});
</script>

</html>