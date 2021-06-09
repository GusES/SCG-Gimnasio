<?php
include '../../db_conn.php'; session_start(); check_login();
$idgrupos=$_GET['idgrupos']?$_GET['idgrupos']:null;
if(is_numeric($idgrupos)){
  $sql = "SELECT * FROM grupos_clie WHERE idgrupos = $idgrupos ;";
  $result = $con->query($sql);
  $row = $result->fetch_object();
} else {
  header('location: ../../index.php');
  exit();
} ?>

<!DOCTYPE html>
<html lang="es">
<?php include 'head.php' ?>
<style>
  .select2-selection__rendered {
    padding: 0 .75rem !important;
    height: 62px;
  }
</style>

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
                <h5 class="m-0">Modificar Grupo</h5>
              </div>
            </div>
          </div>
          <div class="col-12">
            <form method="POST" action="php/editar_grupo_clie.php">
              <input type="text" name="id" value="<?=$row->idgrupos?>" hidden>
              <div class="form-group row mb-1">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nom_grupo" id="nom_grupo" value="<?=$row->nom_grupos?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="nombre" class="col-sm-2 col-form-label">Integrantes: </label>
                <div class="col-sm-10">
                  <select class="js-example-responsive form-control" multiple="multiple" name="dni[]" id="dni" required style="width: 100%">
                    <?php 
                    $query = "SELECT * FROM clientes WHERE estado = 1 or estado = 2";
                    $resultado = $con->query($query);
                    while ($ver= $resultado->fetch_object()){ ?>
                    <option value="<?=$ver->dni?>" <?php if($row->cliente1 == $ver->dni){echo "selected";} else if($row->cliente2 == $ver->dni){echo "selected";} else if($row->cliente3 == $ver->dni){echo "selected";} else if($row->cliente4 == $ver->dni){echo "selected";} else if($row->cliente5 == $ver->dni){echo "selected";} ?>>
                      <?=$ver->dni?> - <?=ucwords($ver->nombre)?> <?=ucwords($ver->apellido)?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-12 text-center my-4">
                <button type="submit" class="btn btn-sm btn-success text-center">Aceptar</button>
                <a class="btn btn-sm btn-secondary" href="clientes_grupos.php">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
  <?php mysqli_close($con)?>
<script>
  $(document).ready(function() {
    $('.js-example-responsive').select2({
      placeholder: "Escribe nombre, apellido o DNI para buscar. (Maximo 5 Integrantes)",
      maximumSelectionLength: 5,
			language: {
        noResults: function() {
          return "No se han encontrado resultados";
        },
        searching: function() {
          return "Buscando...";
        },
        maximumSelected: function() {
          return "Solo puedes seleccionar 5 integrantes";
        },
        loadingMore: function() {
          return "Cargando más resultados ...";
        },
      }
    });
  });
</script>
<script>
$('.js-example-responsive').select2();

$('form').on('submit', function(){
     var minimum = 2;

     if($('.js-example-responsive').select2('data').length>=minimum){
         return true;
     }else {
     	 alert('Selecciona al menos 2 integrantes.')
         return false;
     }
});
</script>
</html>