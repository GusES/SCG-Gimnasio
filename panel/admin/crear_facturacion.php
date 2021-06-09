<?php
include '../../db_conn.php'; session_start(); check_login();
$idcliente=isset($_GET['id'])?$_GET['id']:null;
if(is_numeric($idcliente)){
	$sql="SELECT * FROM clientes WHERE idclientes = '$idcliente';";
	$result=$con->query($sql);
	$row=$result->fetch_object();
}else{
  header('location: ../../index.php');
}?>
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
								<h5 class="m-0">Nuevo Pago</h5>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="alert alert-info <?=$d=($row->estado==2)?"d-block":"d-none"?>">
							<div id="esconder-php">
								<?php
								$zona = new DateTime('America/Argentina/Buenos_Aires');
								$fecha = $zona->format('Y-m-d H:i:s');
								$sql_membresias="SELECT * FROM asignaciones INNER JOIN membresias WHERE asignaciones.idmembresia = membresias.idmembresias AND idcliente = '$idcliente'";
								$rs_membresias = $con->query($sql_membresias);
								while($row_membresias = $rs_membresias->fetch_object()){$disciplinas[]=$row_membresias->nom_modulo;$datos[]=$row_membresias;}
								echo "<h6 class='text-capitalize m-0'>El cliente presenta pagos retrasados por:</h6>";
								for($i=0; $i < count($disciplinas); $i++){
									$sql_dueda="SELECT * FROM facturacion INNER JOIN ingresos_mem on facturacion.idcliente=ingresos_mem.idcliente WHERE ingresos_mem.idcliente = '$idcliente' and facturacion.disciplina LIKE '%$disciplinas[$i]%' ORDER BY num_factura DESC LIMIT 1";
									$rs_deuda = $con->query($sql_dueda);
									$sql_ult_ingreso="SELECT * FROM ingresos_mem INNER JOIN membresias WHERE ingresos_mem.idmembresia = membresias.idmembresias AND idcliente = '$idcliente' AND nom_modulo = '$disciplinas[$i]'";
									$rs_ult_ingreso = $con->query($sql_ult_ingreso);
									$row_ult_ingrso = $rs_ult_ingreso->fetch_object();
									$cnt_deuda = $rs_deuda->num_rows;
									if($cnt_deuda>0){
										while($row_deuda = $rs_deuda->fetch_object()){
											if($row_deuda->ult_ingreso > $row_deuda->fecha_vence or $fecha >= $row_deuda->fecha_vence){
												echo "<hr class='my-1'>";
												echo "<b>Membresia</b>: ".ucwords($disciplinas[$i])." - <b>Ultimo Pago</b>: ".fechaCastellano($row_deuda->fecha_pago)." - <b>Ultimo Ingreso</b>: ".fechaCastellano($row_ult_ingrso->ult_ingreso)."<br>";
											}
										}										
									}else{
										for($i=0; $i < count($datos); $i++){
											if($fecha > $datos[$i]->fecha_vencimiento){
												echo "<hr class='my-1'>";
												echo "<b>Membresia</b>: ".ucwords($disciplinas[$i])." - <b>Ultimo Pago</b>: No se registran pagos - <b>Vencido</b>: ".fechaCastellano($datos[$i]->fecha_vencimiento)."<br>";
											}
										}
									}
								}
								?>
							</div>
						</div>
						<form method="POST" action="php/agregar_facturacion.php">
							<input type="text" name="idcliente" value="<?=$row->idclientes?>" hidden>
							<div id="fecha-franco">
							<?php
							$zona = new DateTime('America/Argentina/Buenos_Aires');
							$fecha = $zona->format('Y-m');
							?>
							<input type="text" name="fecha_fact" value="<?=$fecha?>" hidden>
							</div>
							<div class="form-group row mb-1">
								<?php
								function number_pad($number,$n){return str_pad((int) $number,$n,"0",STR_PAD_LEFT);}                   
								$consulta="SELECT num_factura FROM facturacion ORDER BY idfacturacion DESC LIMIT 1";
								$resultado = $con->query($consulta);
								$cnt = $resultado->num_rows;
								$fila = $resultado->fetch_object();
								if($cnt>0){$numero=$fila->num_factura+1;}else{$numero = 1;}
								$num_factura= number_pad($numero, 10);               
								?>
								<label for="num_factura" class="col-sm-2 col-form-label">Nº. de Factura: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="num_factura" id="num_factura" value="<?=$num_factura?>" readonly>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="nombre" class="col-sm-2 col-form-label">Nombre Completo: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control text-capitalize" name="nombre" id="nombre" value="<?=$row->nombre?> <?=$row->apellido?>" readonly>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="nom_modulo" class="col-sm-2 col-form-label">Membresía: </label>
								<div class="col-sm-8">
									<select class="form-control" id="nom_modulo" name="nom_modulo" onchange='cambioOpciones()' required>
										<option value="" selected disabled>Seleccione una opcion</option>
										<?php
										$query="SELECT * FROM membresias inner join asignaciones on membresias.idmembresias=asignaciones.idmembresia WHERE actividades = 1 AND asignaciones.idcliente = $idcliente GROUP BY nom_modulo";
                    $resultado=$con->query($query);
                    while($fila=$resultado->fetch_object()){ ?>
										<option value="<?=$fila->precio?>"><?=ucwords($fila->nom_modulo)?></option>
										<?php } ?>
									</select>
									<input type="text" id="modulo" name="modulo" class="form-control" readonly hidden>
								</div>
								<div class="col-sm-2">
									<button class="btn btn-info btn-block" type="button" onclick="agregarOpcion()">Agregar</button>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="precio" class="col-sm-2 col-form-label">Total a Pagar: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="precio" id="precio" min="0" readonly placeholder="0">
									<input type="text" class="form-control" name="valores" id="valores" readonly hidden>
								</div>
							</div>
							<div class="form-group row mb-1">
								<label for="abonado" class="col-sm-2 col-form-label">Abona: </label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="abonado" id="abonado" placeholder="0" min="0" required>
									<input type="hidden" class="form-control" name="msg" id="msg" placeholder="Nota(s) de credito cubre el valor de la cuota, presione aceptar." disabled>
								</div>
							</div>
							<div id="extra" class="row m-0" style="display:none">
								<div class="col-12 mx-auto mt-2 alert alert-warning">
									<h4 class="alert-heading m-0">Importante!</h4>
									<h5 class="alert-heading my-1">Nota de Credito</h5>
									<p class='m-0'>
										<b>El cliente posee nota de credito, el monto se usara para el pago.</b>
									</p>
									<p style='font-size:13px'>Si existiese restante, este se guardara en la cuenta del cliente, que tenia credito.</p>
									<div class="form-group row mb-1">
										<label for="notacredito" class="col-sm-3 col-form-label">Monto $:</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" name="notacredito" id="notacredito" readonly placeholder="0" value="<?=$row->notacredito?>">
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="restante" class="col-sm-3 col-form-label">Monto Restante $:</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" name="restante" id="restante" readonly placeholder="-">
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive">
							<table class="table table-striped text-center" id="tabla" style="display:none">
								<thead class="thead-dark"><th class="text-left">Facturado</th><th></th><th></th></thead>
								<tbody id="tbody" class="text-capitalize"></tbody>
							</table>
							</div>
							<div class="col-sm-12 text-center my-3">
								<button style="display:none" id="enviar" type="submit" class="btn btn-sm btn-success text-center" onClick="return confirmar('../ver_facturacion.php')">Aceptar</button>
								<a class="btn btn-sm btn-secondary" href="ver_facturacion.php">Cancelar</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php mysqli_close($con)?>
<script src="../../js/confirmacion.js"></script>
<script type="text/javascript">
	function cambioOpciones() {document.getElementById('precio').value = document.getElementById('nom_modulo').value;}
	var creditodisponible = document.getElementById("notacredito").value;
	if (creditodisponible > 0) {document.getElementById("extra").style.display = "block"}
</script>
<script>
	var membresias = new Array;
	var precios = new Array;
	
	function sum(array) {
		var acum = 0;
		for (var i = 0; i < array.length; i++) {
			acum = acum + Number(array[i]);
		}
		return acum;
	}

	function agregarOpcion() {
		var seleccionado = document.getElementById("nom_modulo");
		var texto = seleccionado.options[seleccionado.selectedIndex].text.toLowerCase();
		var info = "";
		if (texto != "seleccione una opcion") {
		document.getElementById("enviar").style.display="inline-block";
			document.getElementById("tabla").style.display = "table";
			membresias.push(texto);
			precios.push(document.getElementById("nom_modulo").value);
			for (var i = 0; i < membresias.length; i++) {
				quitar = "<button class='btn btn-sm p-0 text-danger' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
				if (info == "") {
					info = "<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>";
				} else {
					info = info + ["<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>"];
				}
				document.getElementById("tbody").innerHTML = info;
			}
		total = sum(precios);
		
		document.getElementById("modulo").value = membresias;
		document.getElementById("valores").value = precios;
		document.getElementById("precio").value = total;
				
		var num1 = total;
		var num2 = <?=$row->notacredito?>;
		var msg = document.getElementById("msg");
		var cobro = document.getElementById("abonado");
		var resultado = num1 - num2;
			if (num1 <= num2) {
			msg.type = "text";
			cobro.type = "hidden";
			document.getElementById('abonado').value = num1;
			document.getElementById("abonado").max = num1
		} else if (num1 > num2) {
			msg.type = "hidden";
			cobro.type = "number";
			document.getElementById("abonado").value = resultado;
			document.getElementById("abonado").max = resultado
		} else if (num2 == 0) {
			document.getElementById('abonado').value = num1;
			document.getElementById("abonado").max = num1
		}else{
			document.getElementById("abonado").value = resultado;
			document.getElementById("abonado").max = resultado
		}
		var disponible = document.getElementById("notacredito").value;
		var restante = disponible - num1;
		if (restante < 0) {
			document.getElementById('restante').value = 0
		} else {
			document.getElementById('restante').value = restante
		}
	}
	}

	function eliminar(i) {
		var info = "";
		var x = i;
		membresias.splice(x, 1);
		precios.splice(x, 1);
		for (var i = 0; i < membresias.length; i++) {
			quitar = "<button class='btn btn-sm p-0 text-danger' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
			if (info == "") {
				info = "<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>";
			} else {
				info = info + ["<tr><td class='text-left'>" + membresias[i] + "</td><td>$ " + precios[i] + "</td><td>" + quitar + "</td></tr>"];
			}
			document.getElementById("tbody").innerHTML = info;
		}
		total = sum(precios);
		
		document.getElementById("modulo").value = membresias;
		document.getElementById("precio").value = total;
		var num1 = total;
		var num2 = <?=$row->notacredito?>;
		var msg = document.getElementById("msg");
		var cobro = document.getElementById("abonado");
		var resultado = num1 - num2;
			if (num1 <= num2) {
			msg.type = "text";
			cobro.type = "hidden";
			document.getElementById('abonado').value = num1;
			document.getElementById("abonado").max = num1
		} else if (num1 > num2) {
			msg.type = "hidden";
			cobro.type = "number";
			document.getElementById("abonado").value = resultado;
			document.getElementById("abonado").max = resultado
		} else if (num2 == 0) {
			document.getElementById('abonado').value = num1;
			document.getElementById("abonado").max = num1
		}else{
			cobro.value = resultado;
			document.getElementById("abonado").max = resultado
		}
		var disponible = document.getElementById("notacredito").value;
		var restante = disponible - num1;
		if (restante < 0) {
			document.getElementById('restante').value = 0
		} else {
			document.getElementById('restante').value = restante
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