<?php
include '../../db_conn.php'; session_start(); check_login();
$idcliente = isset($_GET['idcliente'])?$_GET['idcliente']:false;
if($idcliente == false || empty($idcliente) == true){
 header('location: ../../index.php');
 exit();
} 
$query = "SELECT * FROM clientes WHERE idclientes = '$idcliente'";
$rs = $con->query($query);
$fila = $rs->fetch_object();
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
                <?php
                $sql2 = "SELECT SUM(precio)-SUM(abonado) as resultado FROM facturacion WHERE idcliente = $idcliente";
                $resultado = $con->query($sql2);
                $fila2 = $resultado->fetch_object();
                ?>
                <h5 class="m-0">
                  Facturación de: 
                  <?=ucwords($fila->nombre)?> <?=ucwords($fila->apellido)?> - Deuda Total: $ 
                  <?php if(isset($fila2->resultado)){echo dinero($fila2->resultado);}else{echo "-";}?>
                </h5>
              </div>
              <div class="col-12">
                <a class="btn-sm btn btn-outline-secondary" href="ver_facturacion.php">Volver</a>
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
                  <th>#</th>
                  <th>Actividad</th>
                  <th>Período</th>
                  <th>Deuda</th>
                  <th>Nº de Factura</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i=1;
                $sql = "SELECT * FROM facturacion WHERE idcliente = '$idcliente' and tipo = 'cliente' ORDER by fecha_pago DESC";
                $result = $con->query($sql);
                while ($row = $result->fetch_object()){ 
                ?>
                <tr>
                  <td><?=$i;?></td>
									<td
									<?php if($row->disciplina=="convertido a nota de credito"){echo " style='padding-bottom:4px;'" ;}?>
											>										
										<?php 
										$disciplinas=explode(',',$row->disciplina);
										if(count($disciplinas) == 1){
											echo ucwords($row->disciplina);
										} else {
											for($i=0; $i < count($disciplinas);$i++){
												echo ucwords($disciplinas[$i]);
												if(($i+1) < count($disciplinas)){
												echo " - ";
												}
											}
										}
										?>
									</td>									
                  <td>
										<div style="width:0;height: 0;overflow: hidden;"><?=$row->fecha_pago?></div>
                    <?php 
                    $date=date_create($row->fecha_pago); 
                    $hora = date_format($date,"H:i");
                    echo fechaCastellano($row->fecha_pago)." - ".$hora;
                    ?>
                  </td>
                  <td>$ <?=dinero($deuda=$row->precio-$row->abonado);?></td>
                  <td><?=$row->num_factura?></td>
									<td>
										<div
										<?php if($row->disciplina=="convertido a nota de credito"){echo " style='display:none;'" ;}?>
												 >
										<?php if($fila->estado != 0){ ?>   
										<a class='btn btn-secondary btn-sm mb-1' href='modificar_factura.php?id=<?=$row->idcliente?>&num_factura=<?=$row->num_factura?>'>Editar</a>
										<?php  } ?>
										<a class='btn btn-secondary btn-sm mb-1' href='../../fpdf/imprimir_factura.php?fecha_pago=<?=$row->fecha_pago?>&idcliente=<?=$row->idcliente?>' target="_blank">Imprimir</a>
										</div>
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
<script src="../../js/confirmacion_eliminar.js"> </script>
<script>
  $(document).ready(function() {
    $('#mitabla').DataTable({
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
          "data": "Actividad",
          "width": null,
        },
        {
          "data": "Período",
          "width": "240px",
        },
        {
          "data": "Deuda",
          "width": "49px",
        },
        {
          "data": "Nº de Factura",
          "width": "108px",
        },
        {
          "data": "Acciones",
          "width": "130px",
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