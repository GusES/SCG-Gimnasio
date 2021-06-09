<?php include '../../db_conn.php'; session_start(); check_login(); 
$idcliente=$_GET['idcliente']?$_GET['idcliente']:null;
if(is_numeric($idcliente)){
	$query = "SELECT * FROM clientes WHERE idclientes = $idcliente;";
	$rs = $con->query($query);
	$fila = $rs->fetch_object();
}else{
	header('location: ../../index.php');
	exit();	
}
?>
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
								<h5 class="mb-0">Asistencias</h5>
							</div>
							<div class="col-12">
								<a class='btn btn-outline-secondary btn-sm' href='detalle_cliente.php?id=<?=$fila->idclientes?>'>Volver</a>
							</div>
						</div>
					</div>
					<?php
					$sql="SELECT * FROM asistencias WHERE idcliente = '$idcliente'";
					$result = $con->query($sql);
					$cnt = $result->num_rows;
					while($row = $result->fetch_object()){
						$date = new DateTime($row->fecha_asistencia);
						$ingresos[] = $date->format('Y-m-d');
					}           
					?>
					<div class="col mx-3 px-0 py-1 bg-secondary <?=$d=($cnt>0)?" d-flex":"d-none";?>">
						<table id="calendar">
							<caption></caption>
							<thead>
								<tr>
									<th>Lu</th>
									<th>Ma</th>
									<th>Mi</th>
									<th>Ju</th>
									<th>Vi</th>
									<th>Sa</th>
									<th>Do</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div class="col mx-3 px-0">
						<table style="font-size:14px" class="table table-hover w-100">
							<thead class="thead-dark">
								<tr>
									<th colspan="2">
										Cliente
									</th>
								</tr>
							</thead>
							<tr>
								<td style="font-weight:600">Nombre</td>
								<td><?=ucwords($fila->nombre)?> <?=ucwords($fila->apellido)?></td>
							</tr>
							<tr>
								<td style="font-weight:600">DNI</td>
								<td><?=dinero($fila->dni)?></td>
							</tr>
						</table>
						<?php 
						$sql="SELECT COUNT(idingresos) AS resultado FROM ingresos_mem WHERE idcliente = '$idcliente'";
						$resultado = $con->query($sql);
            $row = $resultado->fetch_object();
						if ($row->resultado != 0){
						?>
						<table style="font-size:14px" class="table table-hover w-100">
							<thead class="thead-dark">
								<tr>
									<th colspan="4">
										Informacion de Actividades
									</th>
								</tr>
							</thead>
							<?php
							$sql="SELECT * FROM ingresos_mem INNER JOIN membresias INNER JOIN asignaciones WHERE ingresos_mem.idmembresia = membresias.idmembresias AND ingresos_mem.idmembresia = asignaciones.idmembresia AND ingresos_mem.idcliente = '$idcliente' GROUP by membresias.nom_modulo";
							$result = $con->query($sql);
							while($row = $result->fetch_object()){ ?>
							<tr>
								<td style="font-weight:600">Actividad</td>
								<td><?=ucwords($row->nom_modulo)?></td>
								<td style="font-weight:600">Ultimo Ingreso</td>
								<td><?=fechaCastellano($row->ult_ingreso)?></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con)?>
<script>
	//Script de Tabla a Calendario
	var anio, mes, fecha, diaaux, clase = "",
		ingresos = new Array();
	ingresos = <?=json_encode($ingresos)?>;
	var actual = new Date();

	function mostrarCalendario(year, month) {
		var now = new Date(year, month - 1, 1);
		var last = new Date(year, month, 0);
		var primerDiaSemana = (now.getDay() == 0) ? 7 : now.getDay();
		var ultimoDiaMes = last.getDate();
		var dia = 0;
		var resultado = "<tr>";
		var diaActual = 0;
		var last_cell = primerDiaSemana + ultimoDiaMes;
		var meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		anio = now.getFullYear();
		if (now.getMonth() >= 9) {
			mes = now.getMonth() + 1;
		} else {
			mes = "0" + (now.getMonth() + 1);
		}
		for (var i = 1; i <= 42; i++) {
			if (i == primerDiaSemana) {
				diaaux = "0" + dia;
				//determinamos en que dia empieza
				dia = 1;
			}
			if (i < primerDiaSemana || i >= last_cell) {
				diaaux = "xx";
				//celda vacia
				resultado += "<td class='bg-light'>&nbsp;</td>";
			} else {
				if (dia <= 9) {
					diaaux = "0" + dia;
				} else {
					diaaux = dia;
				}
				fecha = anio + "-" + mes + "-" + diaaux;
				for (var x = 0; x < ingresos.length; x++) {
					if (fecha == ingresos[x]) {
						clase = " class='hoy'";
					}
				}
				resultado += "<td" + clase + ">" + dia + "</td>";
				dia++;
				clase = "";
			}
			if (i % 7 == 0) {
				if (dia > ultimoDiaMes)
					break;
				resultado += "</tr><tr>\n";
			}
		}
		resultado += "</tr>";
		//Calculamos el siguiente mes y anio
		nextMonth = month + 1;
		nextYear = year;
		if (month + 1 > 12) {
			nextMonth = 1;
			nextYear = year + 1;
		}
		//Calculamos el anterior mes y anio
		prevMonth = month - 1;
		prevYear = year;
		if (month - 1 < 1) {
			prevMonth = 12;
			prevYear = year - 1;
		}
		document.getElementById("calendar").getElementsByTagName("caption")[0].innerHTML = "<div class='col-2 d-inline-block text-left'><a onclick='mostrarCalendario(" + prevYear + "," + prevMonth + ")'><i class='fas fa-chevron-square-left'></i></a></div><div class='col-8 d-inline-block text-center'>" + meses[month - 1] + "&nbsp;" + year + "</div><div class='col-2 d-inline-block text-right'><a onclick='mostrarCalendario(" + nextYear + "," + nextMonth + ")'><i class='fas fa-chevron-square-right'></i></a></div>";
		document.getElementById("calendar").getElementsByTagName("tbody")[0].innerHTML = resultado;
	}
	mostrarCalendario(actual.getFullYear(), actual.getMonth() + 1);
</script>

</html>