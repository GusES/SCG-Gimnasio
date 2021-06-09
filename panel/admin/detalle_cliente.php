<?php
include '../../db_conn.php'; session_start(); check_login();
$idcliente=$_GET['id']?$_GET['id']:"";
if(is_numeric($idcliente)){
	$sql = "SELECT * FROM clientes WHERE idclientes = $idcliente ;";
	$result = $con->query($sql);
	$row = $result->fetch_object();
}else{
	header('location: ../../index.php');
	exit();
}?>
<!DOCTYPE html>
<html lang="es">
<?php include 'head.php'?>

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
								<h5 class="m-0">Información de clientes</h5>
							</div>
							<div class="col-12">
								<a class='btn btn-outline-secondary btn-sm' href='ver_clientes.php'>Volver</a>
								<a class='btn btn-outline-secondary btn-sm' href='modificar_cliente.php?id=<?=$row->idclientes?>'>Editar</a>
								<a class='btn btn-outline-success btn-sm' href='asistencias_cliente.php?idcliente=<?=$row->idclientes?>'>Asistencia</a>
								<a class='btn btn-outline-warning text-dark btn-sm' href='modificar_asignar.php?idcliente=<?=$row->idclientes?>'>Asignaciones</a>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead class="thead-dark">
							<th colspan="2" class="border-bottom-0">
								Estado
								<?php
                if($row->estado == 1){
                  echo "<span class='rounded p-1 bg-success text-white'>Activo</span>";
                } else if($row->estado == 2){
                  echo "<span class='rounded p-1 bg-info text-white'>Moroso</span>";               
                } else if ($row->estado == 0){
                  echo "<span class='rounded p-1 bg-danger text-white'>Inactivo</span>";
                }?>
							</th>
						</thead>
						<tbody>
							<tr>
								<td>Nombre Completo: </td>
								<td class="text-capitalize"><?=$row->nombre?> <?=$row->apellido?></td>
							</tr>
							<tr>
								<td>Fecha de Nacimiento: </td>
								<td>
									<?=fechaCastellano($row->nacimiento)?>
								</td>
							</tr>
							<tr>
								<td>DNI: </td>
								<td>
									<?=dinero($row->dni)?>
								</td>
							</tr>
							<tr>
								<td>Genero: </td>
								<td class="text-capitalize"><?=$row->genero?></td>
							</tr>
							<tr>
								<td>Dirección: </td>
								<td><?=$row->direccion?></td>
							</tr>
							<tr>
								<td>Clave: </td>
								<td><?=$row->clave?></td>
							</tr>
							<tr>
								<td>Nota de Crédito: </td>
								<td>
									<?php	if($row->notacredito == 0){echo "$ 0 - ";}else{echo"$ ".dinero($row->notacredito)." - ";}?>		
									<a class="btn-link" href="modificar_credito.php?idcliente=<?=$row->idclientes?>">Modificar</a>
								</td>
							</tr>
							<tr>
								<td>Membresia(s): </td>
								<td>
									<?php                     
                    $q="SELECT * FROM membresias INNER JOIN asignaciones on membresias.idmembresias = asignaciones.idmembresia WHERE idcliente = '$idcliente'";
                    $rs=$con->query($q);
                    while($f=$rs->fetch_object()){echo ucwords($f->nom_modulo)."<br>";}
                    ?>
								</td>
							</tr>
							<tr>
								<td>Alta en el sistema: </td>
								<td>
									<?=fechaCastellano($row->fecha_alta)?>
								</td>
							</tr>
							<tr>
								<td>Ultimo Ingreso: </td>
								<td>
									<?=fechaCastellano($row->ult_ingreso)?>
								</td>
							</tr>
							<tr>
								<td>Teléfono: </td>
								<td><?=$row->telefono?></td>
							</tr>
							<tr>
								<td>Facebook: </td>
								<td><?=$row->facebook?></td>
							</tr>
							<tr>
								<td>Email: </td>
								<td><?=$row->correo?></td>
							</tr>
							<tr>
								<td class="w-50">Observaciones: </td>
								<td class="w-50">
									<div style="max-height:250px; overflow:auto;"><?=$row->observacion?></div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con)?>

</html>