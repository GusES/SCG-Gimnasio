<?php include '../../db_conn.php'; session_start(); check_login();
$idcliente=isset($_GET['id'])?$_GET['id']:null;
$num_factura=isset($_GET['num_factura'])?$_GET['num_factura']:null;
if($num_factura != null && is_numeric($idcliente)){
  $sql = "SELECT * FROM facturacion INNER JOIN clientes ON clientes.idclientes = facturacion.idcliente WHERE idcliente = '$idcliente' AND num_factura = '$num_factura' ";
  $result = $con->query($sql);
  $row = $result->fetch_object();
} else {
  header('location: ../../index.php');
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
					<a class="btn btn-dark d-block text-left active" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
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
								<h5 class="m-0">Modificar Comprobante</h5>
							</div>
						</div>
					</div>
					<div class="col-12">
						<form method="POST" action="php/editar_facturacion.php">
							<input name="id" type="text" value="<?=$row->idcliente?>" hidden>
							<div class="form-group row mb-1">
								<label for="num_factura" class="col-sm-2 col-form-label">Nº. de Factura: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="num_factura" id="num_factura" value="<?=$num_factura?>" readonly>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="nom_completo" class="col-sm-2 col-form-label">Nombre Completo: </label>
								<div class="col-sm-10">
									<input type="text" class="text-capitalize form-control" name="nom_completo" id="nom_completo" placeholder="Nombre Completo" value="<?=$row->nom_completo?>">
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="nom_modulo" class="col-sm-2 col-form-label">Membresía: </label>
								<div class="col-sm-8">
									<select class="form-control" id="nom_modulo" name="nom_modulo" onchange='cambioOpciones()'>
										<option value="" selected disabled>Seleccione una opcion</option>
										<?php
                    $query="SELECT * FROM membresias inner join asignaciones on membresias.idmembresias= asignaciones.idmembresia WHERE actividades = 1 GROUP BY nom_modulo";
                    $resultado=$con->query($query);
                    while($fila=$resultado->fetch_object()){ 
                    ?>
										<option class="text-capitalize" value="<?=$fila->precio?>"><?=ucwords($fila->nom_modulo)?></option>
										<?php
                    } ?>
									</select>
									<input class="form-control" type="text" id="modulo" name="modulo" readonly hidden>
								</div>
								<div class="col-sm-2">
									<button class="btn btn-info btn-block" type="button" onclick="agregarOpcion()">Agregar</button>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="precio" class="col-sm-2 col-form-label">Total a Pagar: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="precio" id="precio" min="0" value="<?=$row->precio?>" placeholder="0" readonly>
									<input type="text" class="form-control" name="valores" id="valores" value="<?=$row->valores?>" readonly hidden>
								</div>
							</div>
							<div class="form-group row mb-1" id="ocultar">
								<label for="previo2" class="col-sm-2 col-form-label">Pagado: </label>
								<div class="col-sm-10">
									<input type="number" id="previo" name="previo" value="<?=$row->abonado?>" hidden>
									<input type="number" class="form-control" name="previo2" id="previo2" min="0" readonly>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="actual" class="col-sm-2 col-form-label">Abona: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="abonado" id="actual" min="0" placeholder="0" required>
									<input type="hidden" class="form-control" name="msg" id="msg" placeholder="Lo abonado coincide con el precio." disabled>
								</div>
							</div>
							<div id="extra" class="row m-0" style="display:none">
								<div class="col-12 mx-auto mt-2 alert alert-warning">
									<h4 class="alert-heading m-0">Importante!</h4>
									<h5 class="alert-heading my-1">Nota de Crédito</h5>
									<p class="m-0"><b>Se <?=$d=($row->notacredito>0)?"sumara a":"creara una"?> nota de crédito:</b></p>
									<p style="font-size:13px">
										Se efectuó un aporte previo superior al precio de la nueva membresía, la diferencia se guardara como crédito para el cliente, este se usara para el o los próximo(s) pago(s) hasta finalizar el monto.
										<br>
										La misma puede imprimirse desde el menú lateral Facturación, y luego la opción superior Notas De Crédito.
									</p>
									<div class="form-group row mb-1">
										<label for="notacredito" class="col-sm-3 col-form-label">Monto $:</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" name="notacredito" id="notacredito" readonly placeholder="-">
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive">
							<table class="table table-striped text-center" id="tabla">
								<thead class="thead-dark">
									<th class="text-left">Facturado</th>
									<th></th>
									<th></th>
								</thead>
								<tbody id="tbody" class="text-capitalize"></tbody>
							</table>
						</div>
							<div class="col-sm-12 text-center my-3">
								<button type="submit" class="btn-sm btn btn-success text-center">Aceptar</button>
								<a class="btn-sm btn btn-secondary" href="facturas_cliente.php?idcliente=<?=$row->idcliente?>">Cancelar</a>
							</div>
						</form>
					</div>
					<?php 	
					$disciplinas=explode(",",$row->disciplina); 
					$precios=explode(",",$row->valores);
					?>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con)?>
<script type="text/javascript">
	var disciplinas = new Array;
	var membresias = new Array;
	var valores = new Array;
	var precios = new Array;
	disciplinas = <?=json_encode($disciplinas)?>;
	valores = <?=json_encode($precios)?>;
	var info = "";
	for (var i = 0; i < disciplinas.length; i++) {
		membresias.push(disciplinas[i]);
		precios.push(valores[i]);
		quitar = "<button class='btn btn-sm p-0 text-danger mb-1' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
		info += "<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>";
	}
	document.getElementById("tbody").innerHTML = info;
	total = sum(precios);
	document.getElementById("modulo").value = membresias;
	document.getElementById("precio").value = total;
	var previo = document.getElementById("previo").value;
	document.getElementById('previo2').value = previo;
	var num1 = document.getElementById("precio").value;
	var num2 = document.getElementById("previo").value;
	var resultado = num1 - num2;
	var msg = document.getElementById("msg");
	var cobro = document.getElementById("actual");
	if (num1 == "<?=$row->abonado?>") {
		msg.type = "text";
		cobro.type = "hidden"
	}
	if (resultado <= 0) {
		if (num1 == num2) {
			document.getElementById('actual').value = 0;
			document.getElementById("actual").max = 0
		} else {
			document.getElementById('actual').value = num1;
			document.getElementById("actual").max = num1
		}
	} else {
		document.getElementById('actual').value = resultado;
		document.getElementById("actual").max = resultado
	}

	function sum(array) {
		var acum = 0;
		for (var i = 0; i < array.length; i++) {
			acum = acum + Number(array[i]);
		}
		return acum;
	}

	function cambioOpciones() {
		document.getElementById('precio').value = document.getElementById('nom_modulo').value;
	}

	function agregarOpcion() {
		var seleccionado = document.getElementById("nom_modulo");
		var texto = seleccionado.options[seleccionado.selectedIndex].text.toLowerCase();
		var msg = document.getElementById("msg");
		var cobro = document.getElementById("actual");
		var info = "";
		if (texto != "seleccione una opcion") {
			document.getElementById("tabla").style.display = "table";
			membresias.push(texto);
			precios.push(document.getElementById("nom_modulo").value);
			for (var i = 0; i < membresias.length; i++) {
				quitar = "<button class='btn btn-sm p-0 text-danger mb-1' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
				info += "<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>";
			}
			document.getElementById("tbody").innerHTML = info;
		}
		total = sum(precios);
		document.getElementById("modulo").value = membresias;
		document.getElementById("precio").value = total;
		document.getElementById("valores").value = precios;
		var previo = document.getElementById("previo").value;
		var resultado = total - previo;
		if (resultado < 0) {
			resultado = Math.abs(resultado);
			document.getElementById("extra").style.display = "block";
			document.getElementById('notacredito').value = resultado;
			document.getElementById('actual').value = 0;
			document.getElementById('actual').max = 0;
			document.getElementById('previo2').value = total;
			document.getElementById("ocultar").style.display = "none"
		} else {
			document.getElementById("extra").style.display = "none";
			document.getElementById('actual').value = resultado;
			document.getElementById('actual').max = resultado;
			document.getElementById('previo2').value = previo;
			document.getElementById("ocultar").style.display = "flex"
		}
		if (total != "<?=$row->abonado?>") {
			msg.type = "hidden";
			cobro.type = "number"
		}
	}

	function eliminar(i) {
		var x = i;
		var msg = document.getElementById("msg");
		var cobro = document.getElementById("actual");
		var info = "";
		membresias.splice(x, 1);
		precios.splice(x, 1);
		for (var i = 0; i < membresias.length; i++) {
			quitar = "<button class='btn btn-sm p-0 text-danger mb-1' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
			info += "<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>";
		}
		document.getElementById("tbody").innerHTML = info;
		
		total = sum(precios);
		document.getElementById("modulo").value = membresias;
		document.getElementById("precio").value = total;
		document.getElementById("valores").value = precios;
		var previo = document.getElementById("previo").value;
		var resultado = total - previo;
		if (resultado < 0) {
			resultado = Math.abs(resultado);
			document.getElementById("extra").style.display = "block";
			document.getElementById('notacredito').value = resultado;
			document.getElementById('actual').value = 0;
			document.getElementById('actual').max = 0;
			document.getElementById('previo2').value = total;
			document.getElementById("ocultar").style.display = "none"
		} else {
			document.getElementById("extra").style.display = "none";
			document.getElementById('actual').value = resultado;
			document.getElementById('actual').max = resultado;
			document.getElementById('previo2').value = previo;
			document.getElementById("ocultar").style.display = "flex"
		}
		if (total != "<?=$row->abonado?>") {
			msg.type = "hidden";
			cobro.type = "number"
		}
		if (membresias.length == 0) {
			$('#tbody').empty();
			document.getElementsByTagName("option")[0].selected = true;
			document.getElementById("tabla").style.display = "none";
			document.getElementById("enviar").style.display = "none";
			document.getElementById("msg").type = "hidden";
			document.getElementById("abonado").type = "number";
		}
	}
</script>

</html>