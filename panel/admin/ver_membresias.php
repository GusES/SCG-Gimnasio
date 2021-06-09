<?php include '../../db_conn.php'; session_start(); check_login() ?>
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
								<h5 class="mb-0">Membresías</h5>
							</div>
							<div class="col-12">
								<a class="btn btn-sm btn-outline-secondary" href="crear_membresia.php">Nueva Membresía</a>
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
						<div class="table-responsive">
							<table id="mitabla" class="table table-striped table-bordered" style="width:100%;">
								<thead class="thead-dark">
									<tr>
										<th>#</th>
										<th>Nombre</th>
										<th>Detalles</th>
										<th>Promoción</th>
										<th>Precio</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    $i = 1;
									$sql="SELECT * FROM membresias";
									$result = $con->query($sql);
									while ($row = $result->fetch_object()){ 
									?>
									<tr>
										<td>
											<?=$i;?>
										</td>
										<td class="text-capitalize">
											<?=$row->nom_modulo?>
										</td>
										<td>
											<?php
											if(empty($row->detalle)){
												echo "&nbsp;";
											} else {											
												$texto = $row->detalle; 
												$indicejs = $row->idmembresias; 
												$limitetexto = 25 ;
												if($texto && strlen($texto) > $limitetexto){ 
													echo "<p class='text-dark m-0'>";
													echo substr(ucfirst($texto), 0, $limitetexto)."<span id='dots$indicejs'>...</span><span style='display:none' id='more$indicejs'>".substr($texto, $limitetexto)."</span>";
													echo "<br><button class='btn btn-link p-0' id='myBtn$indicejs' onclick='leerMas($indicejs)'>Leer Mas</button></p>";
												} else { 
													echo "<p class='text-dark m-0'>".ucfirst($texto)."</p>";
												}
											}
											?>
										</td>
										<td class="text-center text-success" style="font-size:14px; font-weight:500">
											<?php
											if($row->actividades > 1){
												echo "<span>$row->cant_per Persona(s)</span>";
											}
											?>
										</td>
										<td>
											$
											<?php 
											if(strlen($row->precio) > 3){
												echo dinero($row->precio);											
											} else {
												echo "<span style='font-size:0px'>.</span>".$row->precio;
											}
											?>
										</td>
										<td>
											<a class="btn btn-sm btn-secondary mb-1" href='modificar_membresias.php?id=<?=$row->idmembresias?>'>Editar</a>
											<a class="btn btn-sm btn-secondary mb-1" href='php/eliminar_membresia.php?id=<?=$row->idmembresias?>' onClick="return confirmar('ver_membresias.php')">Eliminar</a>
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
<?php mysqli_close($con) ?>
<script src="../../js/confirmacion_eliminar.js"></script>
<script src="../../js/leerMas.js"></script>
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
					"width": "17px"
				},
				{
					"data": "Nombre",
					"width": null,
				},
				{
					"data": "Detalles",
					"width": null,
					"orderable": false,
				},
				{
					"data": "Promoción",
					"width": "85px",
					"orderable": false,
				},
				{
					"data": "Precio",
					"width": "40px"
				},
				{
					"data": "Acciones",
					"width": "126px",
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