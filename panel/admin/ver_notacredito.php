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
					<a class="btn btn-dark d-block text-left active" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
					<a class="btn btn-dark d-block text-left" href="ver_ejercicios.php">Entrenamiento</a>
				</nav>
			</div>
			<div class="col-10">
				<div class="row">
					<div class="col-12 mb-2">
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
								<h5 class="mb-0">Nota(s) de Credito</h5>
							</div>
							<div class="col-12">
								<a class="btn-sm btn btn-outline-secondary" href="ver_facturacion.php">Volver</a>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="table-responsive">
							<table id="mitabla" class="table table-striped table-bordered" style="width:100%;">
								<thead class="thead-dark">
									<tr>
										<th>#</th>
										<th>Nombre Completo</th>
										<th>DNI</th>
										<th>Monto</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    $i=1;
									$sql = "SELECT * FROM clientes WHERE estado='1' and notacredito > '0'";
									$result = $con->query($sql);
									while ($row = $result->fetch_object()){          
									?>
									<tr>
										<td><?=$i?></td>
										<td class="text-capitalize"><?=$row->nombre?> <?=$row->apellido?></td>
										<td><?=dinero($row->dni)?></td>
										<td>$ <?=dinero($row->notacredito)?></td>
										<td>
											<a class="btn btn-secondary mb-1 btn-sm" href='../../fpdf/imprimir_nota.php?idclientes=<?=$row->idclientes?>' target="_blank">Imprimir</a>
										</td>
									</tr>
									<?php $i++; } ?>
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
					"data": "#",
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
					"data": "Monto",
					"width": "49px",
				},
				{
					"data": "Acciones",
					"orderable": false,
					"width": "71px",
				},
			],
		});
	});
</script>
</1