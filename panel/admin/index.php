<?php include '../../db_conn.php'; session_start(); check_login(); ?>

<!DOCTYPE html>
<html lang="es">
<?php include 'head.php' ?>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-2 p-0 sticky-top" style="height:100vh">
				<nav class="bg-dark" style="height:100%">
					<a class="btn btn-dark d-block text-left active" style="border-bottom:1px solid #6c757d;" href="index.php">Inicio</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_membresias.php">Membresías</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_clientes.php">Clientes</a>
					<a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
					<a class="btn btn-dark d-block text-left" href="ver_ejercicios.php">Entrenamiento</a>
				</nav>
			</div>
			<div class="col-10">
				<div class="row mb-1">
					<div class="col-12">
						<div class="row bg-secondary" style="height:58%;">
							<div class="col-6">
								<p class="m-0 badge badge-secondary"><?=$_SESSION['rol'];?></p>
							</div>
							<div class="col-6 text-right">
								<a class="btn btn-danger btn-sm py-0 px-2" href="../logout.php">Cerrar Sesión</a>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<h5 class="mb-0">Panel de Información</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="row mb-1 text-center jumbotron p-0 mx-auto">
					<div class="col-12">
						<?php
						if(gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR']))!='127.0.0.1'){
							echo "<h6 class='mb-0'>Enlace de Conexion al Sistema <span class='text-primary'>" 
							.gethostbyname(gethostbyaddr($_SERVER['REMOTE_ADDR'])).":8080</span></h6>";		
						} else {
							echo "<p>Fallo al obtener enlace, compruebe que el router este activo y ambos dispositivos conectados al mismo.</p>";
						}
						?>
					</div>
				</div>
				<div class="row mx-0 mb-1">
					<?php
					$zona = new DateTime('America/Argentina/Buenos_Aires');
					$fecha_actual = $zona->format('d/m/Y');
          $estado1="SELECT COUNT(idclientes) as activo FROM clientes WHERE estado = '1';";
          $estado2="SELECT COUNT(idclientes) as moroso FROM clientes WHERE estado = '2';";
          $execute = $con->query($estado1);
          $rsestado1 = $execute->fetch_object();
          $execute2 = $con->query($estado2);
          $rsestado2 = $execute2->fetch_object();
          ?>
					<div class="jumbotron col-12 p-1 m-0 text-center">
						<div class="d-inline-block border border-dark rounded">
							<div class="d-inline-block p-2 bg-dark text-white">
								<i class="fas fa-users"></i>
								Estado de Clientes al <?=$fecha_actual?>
							</div>
							<div class="d-inline-block p-2 border-right border-dark">
								<span class="badge badge-dark"><?=$rsestado1->activo?></span>
								&nbsp;Activos
							</div>
							<div class="d-inline-block p-2">
								<span class="badge badge-dark"><?=$rsestado2->moroso?></span>
								&nbsp;Deudores
							</div>
						</div>
					</div>
				</div>
				<div class="row mx-0 mb-1 p-1 jumbotron text-center">
					<?php          
					$hoy = $zona->format('Y-m-d')."T".$zona->format('H:i');
					if(isset($_POST['desde']) && isset($_POST['hasta'])){
						$desde=isset($_POST['desde'])?$_POST['desde']:"";
						$hasta=isset($_POST['hasta'])?$_POST['hasta']:"";
					} else {
						$desde = $zona->format('Y-m')."-01T00:00";
						$hasta = $zona->format('Y-m-d')."T23:59";
					}
					$sqlgrupoprecio="SELECT((SELECT SUM(precio)from (SELECT precio FROM facturacion WHERE tipo = 'grupo' AND fecha_pago BETWEEN '$desde' AND '$hasta' GROUP BY num_factura) tmp)) as preciogrupos";
					$sqlgrupoabono="SELECT((SELECT SUM(abonado)from (SELECT abonado FROM facturacion WHERE tipo = 'grupo' AND fecha_pago BETWEEN '$desde' AND '$hasta' GROUP BY num_factura) tmp)) as abonogrupos";
          $sqlclienteprecio="SELECT((SELECT SUM(precio) FROM facturacion WHERE tipo = 'cliente' AND fecha_pago BETWEEN '$desde' AND '$hasta')) as preciocliente";
          $sqlclienteabono="SELECT((SELECT SUM(abonado) FROM facturacion WHERE tipo = 'cliente' AND fecha_pago BETWEEN '$desde' AND '$hasta')) as abonocliente";
          //PRECIO GRUPO
          $rs1=$con->query($sqlgrupoprecio);
          $row1=$rs1->fetch_object();
          $preciogrupos=$row1->preciogrupos;
          //ABONO GRUPO
          $rs2=$con->query($sqlgrupoabono);
          $row2=$rs2->fetch_object();
          $abonogrupos=$row2->abonogrupos;
          //PRECIO CLIENTE
          $rs3=$con->query($sqlclienteprecio);
          $row3=$rs3->fetch_object();
          $preciocliente=$row3->preciocliente;
          //ABONO CLIENTE
          $rs4=$con->query($sqlclienteabono);
          $row4=$rs4->fetch_object();
          $abonocliente=$row4->abonocliente; 
          //Fechas para mostrar
          $muestra_desde = date("d/m/Y", strtotime($desde));
          $muestra_hasta = date("d/m/Y", strtotime($hasta));              
          ?>
					<div class="col-12">
						<div class=" d-inline-block mb-1">
							<h6 class="mb-1 text-center">Personalizar Recaudación:</h6>
							<form class="form-inline" method="POST" action="index.php">
								<div class="form-group px-1">
									<label class="pr-1">Desde:</label>
									<input type="datetime-local" class="form-control form-control-sm" name="desde" value="<?=$desde?>" required max="<?=$hoy?>">
								</div>
								<div class="form-group px-1">
									<label class="pr-1">Hasta:</label>
									<input type="datetime-local" class="form-control form-control-sm" name="hasta" value="<?=$hasta?>" required max="<?=$hoy?>">
								</div>
								<div class="form-group mx-1">
									<input type="submit" class="btn btn-sm btn-dark" value="Buscar">
								</div>
							</form>
						</div>
					</div>
					<div class="col-12">
						<div class="card card bg-light d-inline-block border border-success" style="max-width: 18rem;min-width: 238px;">
							<div class="card-header bg-success text-white text-center p-2" style="font-weight:400">
								<i class="fas fa-calendar-alt"></i> <?=$muestra_desde?> - <?=$muestra_hasta?></div>
							<div class="card-body p-2">
								<h5 class="card-title mb-1 text-left">Recaudación de Clientes</h5>
								<table class="w-100">
									<tr>
										<td class="text-left">Facturado:</td>
										<td class="text-right">$ <?=dinero($preciocliente)?></td>
									</tr>
									<tr>
										<td class="text-left">Abonado:</td>
										<td class="text-right">$ <?=dinero($abonocliente)?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="card card bg-light d-inline-block border border-info" style="max-width: 18rem;min-width: 238px;">
							<div class="card-header bg-info text-white text-center p-2" style="font-weight:400">
								<i class="fas fa-calendar-alt"></i> <?=$muestra_desde?> - <?=$muestra_hasta?></div>
							<div class="card-body p-2">
								<h5 class="card-title mb-1 text-left">Recaudación de Grupos</h5>
								<table class="w-100">
									<tr>
										<td class="text-left">Facturado:</td>
										<td class="text-right">$ <?=dinero($preciogrupos)?></td>
									</tr>
									<tr>
										<td class="text-left">Abonado:</td>
										<td class="text-right">$ <?=dinero($abonogrupos)?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="card card bg-light d-inline-block border border-danger" style="max-width: 18rem;min-width: 238px;">
							<div class="card-header bg-danger text-white text-center p-2" style="font-weight:400">
								<i class="fas fa-calendar-alt"></i> <?=$muestra_desde?> - <?=$muestra_hasta?>
							</div>
							<div class="card-body p-2">
								<h5 class="card-title mb-1 text-left">Recaudación Total</h5>
								<table class="w-100">
									<tr>
										<td class="text-left">Facturado:</td>
										<td class="text-right">$ <?=dinero($preciogrupos+$preciocliente)?></td>
									</tr>
									<tr>
										<td class="text-left">Abonado:</td>
										<td class="text-right">$ <?=dinero($abonogrupos+$abonocliente)?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>