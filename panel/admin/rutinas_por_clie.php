<?php
include '../../db_conn.php'; session_start(); check_login();
if(isset($_GET['idcliente'])){
  $idcliente=$_GET['idcliente'];
  $sql = "SELECT * FROM clientes WHERE idclientes = '$idcliente'";
  $rs = $con->query($sql);
  $row = $rs->fetch_object();
} else {
  mysqli_close($con);
  header('location:ver_rutinas_clientes.php');  
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
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_membresias.php">Membresías</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_clientes.php">Clientes</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
					<a class="btn btn-dark d-block text-left active" href="ver_ejercicios.php">Entrenamiento</a>
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
								<h5 class="m-0">Rutinas de: <?=ucwords($row->nombre)?> <?=ucwords($row->apellido)?></h5>
							</div>
							<div class="col-12">
								<a class="btn-sm btn btn-outline-secondary" href="ver_rutinas_clientes.php">Volver</a>
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
							<table id="mitabla" class="table table-striped table-bordered" style="width:100%">
								<thead class="thead-dark">
									<tr>
										<th>Fecha de Creacion</th>
										<th>Nombre</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql="SELECT * FROM rutinas WHERE idcliente = $idcliente";
                    $result = $con->query($sql);
                    while ($row = $result->fetch_object()){  
                  ?>
									<tr>
										<td>
											<div style="width:0;height: 0;overflow: hidden;"><?=$row->fecha?></div>
											<?=fechaCastellano($row->fecha)?>
										</td>
										<td class="text-capitalize">
											<?php
											if($row->tipo=="personalizada"){echo "Rutina Personalizada";}else{echo($row->tipo);}
                      ?>
										</td>
										<td class="text-center">
											<a class='btn btn-secondary btn-sm mb-1' href='rutina_cliente.php?idcliente=<?=$row->idcliente?>&idrutina=<?=$row->idrutinas?>'>Mostrar</a>
											<a class="btn btn-secondary btn-sm mb-1" href='php/eliminar_rutina.php?idcliente=<?=$row->idcliente?>&idrutina=<?=$row->idrutinas?>' onClick="return confirmar('rutinas_por_clie.php?idcliente=<?=$row->idcliente?>')">Eliminar</a>
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
	<?php mysqli_close($con); ?>
</body>
<script src="../../js/confirmacion_eliminar.js"></script>
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
					"data": "Fecha de Creacion",
					"width": "250px",
				},
				{
					"data": "Nombre",
					"width": null,
				},
				{
					"data": "Acciones",
					"width": "139px",
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