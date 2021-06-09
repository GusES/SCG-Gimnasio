<?php
include '../../db_conn.php'; session_start(); check_login();
if(isset($_GET['idplanes'])){
	$idplanes=$_GET['idplanes'];
	$sql="SELECT * FROM planes WHERE idplanes = '$idplanes'";
  $rs=$con->query($sql);
  $row=$rs->fetch_object();
} else {
	mysqli_close($con);
	header('location:ver_rutinas.php');
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
					<div class="col-12">
						<div class="row bg-secondary" style="height:33%;">
							<div class="col-6">
								<p class="m-0 badge badge-secondary"><?=$_SESSION['rol'];?></p>
							</div>
							<div class="col-6 text-right">
								<a class="btn btn-danger btn-sm py-0 px-2" href="../logout.php">Cerrar Sesión</a>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<h5 class="m-0"><?=ucwords($row->nombre)?></h5>
							</div>
							<div class="col-12 mb-2">
								<a class="btn-sm btn btn-outline-secondary" href="ver_rutinas.php">Volver</a>
								<a class="btn-sm btn btn-outline-secondary" href="../../fpdf/imprimir_rutinas.php?idplanes=<?=$idplanes?>" target="_blank">Imprimir</a>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" style="font-size:12px">
								<thead class="thead-dark">
									<th style="width:132px;"></th>
									<th>Ejercicio</th>
									<th class="text-center">Identificacion</th>
									<th class="text-center">Series</th>
									<th class="text-center">Rep.</th>
									<th class="text-center">Descanso</th>
								</thead>
								<tbody>
									<?php
										$comprobar="comprobar";
										$query="SELECT * FROM planes WHERE idplanes = $idplanes";
										$execute = $con->query($query);
										$row = $execute->fetch_object();
										//Convirtiendo nuevamente en arrays
										$idejercicios = explode(",", $row->idejercicio);
										$series = explode(",", $row->series);
										$rep = explode(",", $row->rep);
										$dia = explode(",", $row->dia);
										$prueba = array_count_values($dia);
										//DIA 1
										if(isset($prueba[1])) {
											$dias = "Dia 1";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 1){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[1]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
										//DIA 2
										if(isset($prueba[2])) {
                    $dias = "Dia 2";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 2){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[2]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
										//DIA 3
										if(isset($prueba[3])) {
                    $dias = "Dia 3";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 3){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[3]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
										//DIA 4
										if(isset($prueba[4])) {
                    $dias = "Dia 4";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 4){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[4]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
										//DIA 5
										if(isset($prueba[5])) {
                    $dias = "Dia 5";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 5){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[5]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
										//DIA 6
										if(isset($prueba[6])) {
                    $dias = "Dia 6";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 6){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[6]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
										//DIA 7
										if(isset($prueba[7])) {
                    $dias = "Dia 7";
											if($dias != $comprobar){
												echo "<tr class='bg-secondary text-white'><td class='pb-1 border-secondary' colspan='8'><b>$dias</b></td></tr>";
												$comprobar="comprobar";
												$newidejercicios=array();
												$newseries=array();
												$newrep=array();
												for ($i=0; $i<count($dia); $i++){
													if($dia[$i] == 7){
														$newidejercicios[] = $idejercicios[$i];
														$newseries[] = $series[$i];
														$newrep[] = $rep[$i];
													}}            
												for ($i = 0; $i < $prueba[7]; $i++) {
													$sqli = "SELECT * FROM ejercicios WHERE idejercicios = $newidejercicios[$i]";               
													$rs = $con->query($sqli);
													$resultado = $rs->fetch_object();
									?>
									<tr>
										<td class="p-0">
											<img style="width:132px" src="<?=$resultado->path?>">
										</td>
										<td class="text-capitalize">
											<div style="display:flex;flex-direction:column-reverse;">
												<p class="m-0"><?=$resultado->nombre?></p>
												<p class="m-0 text-capitalize" style="font-size:15px;line-height:15px">
													<span class="badge badge-info" style="padding:2px">
														<?php														
														if($resultado->grupomuscular == 'gluteos' or $resultado->grupomuscular == 'quadriceps' or $resultado->grupomuscular == 'isquiotibiales' or $resultado->grupomuscular == 'aductores/abductores' or $resultado->grupomuscular == 'gemelos'){
															echo "Piernas: ".$resultado->grupomuscular;
														} else {
															echo $resultado->grupomuscular;
														} ?>
													</span>
												</p>
											</div>
										</td>
										<td class="text-center">
											# <?=$resultado->numero?>
										</td>
										<td class="text-center"><?=$newseries[$i]?></td>
										<td class="text-center"><?=$newrep[$i]?></td>
										<td class="text-center">10 a 90 Segundos</td>
									</tr>
									<?php 
                      }
                    } 
                  }
									?>
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

</html>