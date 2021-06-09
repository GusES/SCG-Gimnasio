<?php include '../../db_conn.php'; session_start(); check_login(); ?>
<!DOCTYPE html>
<html lang="es">
<?php include 'head.php'; ?>
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
						<div class="row bg-secondary" style="height:36%;">
							<div class="col-6">
								<p class="m-0 badge badge-secondary"><?=$_SESSION['rol'];?></p>
							</div>
							<div class="col-6 text-right">
								<a class="btn btn-danger btn-sm py-0 px-2" href="../logout.php">Cerrar Sesión</a>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<h5 class="m-0">Planes de Entrenamiento</h5>
							</div>
							<div class="col-12">
								<a class="btn-sm btn btn-outline-secondary" href="crear_ejercicio.php">Nuevo Ejercicio</a>
								<a class="btn-sm btn btn-outline-secondary" href="crear_plan.php">Nuevo Plan</a>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div id="cortina">
							<center class="text-center" style="margin-top:-15px">
								<div>
									<div class="spinner-border" style="width:1rem;height:1rem" role="status"></div>
									Cargando...
								</div>
							</center>
						</div>
						<nav class="d-flex flex-row my-1">
							<div class="d-inline-flex">
								<a class="d-inline-flex px-0 text-muted">Mostrar:</a>
							</div>
							<div class="d-inline-flex mx-2">
								<div data-toggle="tooltip" data-placement="top">
									<a class="d-inline-flex text-secondary" href="ver_ejercicios.php">Ejercicios</a>
									<i class="fal fa-question-circle" title="Muestra la lista completa de ejercicios."></i>
								</div>
							</div>
							<i class="d-inline-flex">|</i>
							<div class="d-inline-flex mx-2">
								<div data-toggle="tooltip" data-placement="top">
									<a class="d-inline-flex text-secondary" href="ver_rutinas.php">Rutinas Predefinidas</a>
									<i class="fal fa-question-circle" title="Muestra una lista con rutinas generales, previamente definidas."></i>
								</div>
							</div>
							<i class="d-inline-flex">|</i>
							<div class="d-inline-flex mx-2">
								<div data-toggle="tooltip" data-placement="top">
									<a class="d-inline-flex text-primary font-weight-bold" href="ver_rutinas_clientes.php">Rutinas de Clientes</a>
									<i class="fal fa-question-circle" title="Muestra una lista con clientes desde la cual se podra acceder a las rutinas de los mismos."></i>
								</div>
							</div>
						</nav>
						<div class="table-responsive">
							<table id="mitabla" class="table table-striped table-bordered" style="width:100%;">
								<thead class="thead-dark">
									<tr>
										<th>ID</th>
										<th>Nombre Completo</th>
										<th>DNI</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php
                  $sql = "SELECT * FROM clientes INNER JOIN rutinas ON rutinas.idcliente = clientes.idclientes GROUP BY idcliente";
                  $result = $con->query($sql);
                  while ($row = $result->fetch_object()){          
                  ?>
									<tr>
										<td><?=$row->idclientes?></td>
										<td><?=ucwords($row->nombre)?> <?=ucwords($row->apellido)?></td>
										<td><?=dinero($row->dni)?></td>
										<td>
											<a class="btn btn-secondary btn-sm mb-1" href='rutinas_por_clie.php?idcliente=<?=$row->idclientes?>'>Mostrar</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con)?>
<script>
	$(document).ready(function() {
		$('#mitabla').DataTable({
			"stateSave": true,
			"language": {
				"sLengthMenu": "<span style='font-size:13px'>Mostrar _MENU_ registros</span>",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "<span style='font-size:13px'>Mostrando _START_ a _END_, de _TOTAL_ registros</span>",
				"sInfoEmpty": "<span style='font-size:13px'>Mostrando del 0 al 0, de 0 registros<span>",
				"sInfoFiltered": "(filtrado de _MAX_ registros)",
				"sSearch": "Buscar:",
				"sLoadingRecords": "Cargando",
				"sProcessing": "Procesando",
				"oPaginate": {
					"sNext": ">",
					"sPrevious": "<"
				},
			},
			"columns": [{
					"data": "ID",
					"width": "17px",
				},
				{
					"data": "Nombre Completo",
					"width": null,
				},
				{
					"data": "DNI",
					"width": "50px",
				},
				{
					"data": "Acciones",
					"width": "68px",
					"orderable": false,
				},
			],
		});
	});
	window.onload = function() {
		document.getElementById("cortina").style.opacity = "0";
		document.getElementById("cortina").style.zIndex = "-999999";
	}
</script>

</html>