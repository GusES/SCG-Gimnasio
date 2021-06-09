<?php include '../../db_conn.php'; session_start(); check_login(); 
$idplanes=(isset($_GET['idplanes']))?$_GET['idplanes']:"";
if(is_numeric($idplanes)){
  $sql = "SELECT * FROM planes WHERE idplanes = '$idplanes'";
  $result = $con->query($sql);
  $row = $result->fetch_object();
} else {
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
          <a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_clientes.php">Clientes</a>
          <a class="btn btn-dark d-block text-left" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
         <a class="btn btn-dark d-block text-left active" href="ver_ejercicios.php">Entrenamiento</a>
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
                <h5 class="m-0">Renombrar Plan</h5>
              </div>
            </div>
          </div>
          <div class="col-10 mx-auto">
            <form method="POST" action="php/renombrar_rutina.php">
              <div class="form-group row mb-1">
                <label for="renombrar" class="col-sm-2 col-form-label">Nombre: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nombre" id="renombrar" value="<?=ucwords($row->nombre)?>" required >
                  <input type="hidden" class="form-control" name="idplanes" id="idplanes" value="<?=$row->idplanes?>" required >
                </div>
              </div>
              <div class="col-sm-12 text-center my-4">
                <button type="submit" class="btn btn-success text-center">Aceptar</button>
                <a class="btn btn-secondary" href="ver_rutinas.php">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<?php mysqli_close($con)?>
</html>