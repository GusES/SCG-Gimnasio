<?php include '../../db_conn.php'; session_start(); check_login(); 
if(isset($_GET['idgrupo'])){
  $idgrupo=$_GET['idgrupo'];
  $query = "SELECT * FROM grupos_clie WHERE idgrupos = $idgrupo;";
  $rs = $con->query($query);
  $row = $rs->fetch_object();
} else {
  header('location: ../../index.php');
  exit();  
} ?>

<!DOCTYPE html>
<html lang="es">
<?php include 'head.php' ?>

<body>
  <style>
    .select2-selection__rendered {
      padding: 0 .75rem !important;
      height: 62px;
    }    
  </style>
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
                <h5 class="m-0">Asignar Membresias Grupos</h5>
              </div>
            </div>
          </div>
          <div class="col-12">
            <form method="POST" action="php/agregar_asignar_grupo.php">
              <div class="form-group row mb-1">
                <label for="grupo" class="col-sm-2 col-form-label">Grupo: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="grupo" value="<?=ucwords($row->nom_grupos)?>" readonly>
                  <input type="text" name="idgrupo" value="<?=$row->idgrupos?>" hidden>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="membresias" class="col-sm-2 col-form-label">Membresias: </label>
                <div class="col-sm-10">
                  <select class="js-example-responsive form-control" name="membresias" id="membresias" style="width:100%; height:10px!important;left:50%!important" multiple required >
                    <?php 
                      $query = "SELECT * FROM membresias WHERE actividades = '$row->num_clie' AND actividades > 1";
                      $resultado = $con->query($query);
                      while ($ver=$resultado->fetch_object()){ 
                    ?>
                    <option value="<?=$ver->idmembresias?>">
                      $ <?=dinero($ver->precio)?> - <?=ucwords($ver->nom_modulo)?>
                    </option>
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
<script>
  $(document).ready(function() {
    $('.js-example-responsive').select2({
      placeholder: "Escriba en nombre de la promocion para buscar (Maximo 1 a la vez)",
      maximumSelectionLength: 1,
      language: {
        maximumSelected: function() {
          return "Solo puedes seleccionar 1 promocion a la vez";
        },
        noResults: function() {
          return "No se han encontrado resultados";
        },
        searching: function() {
          return "Buscando...";
        },
        loadingMore: function() {
          return "Cargando más resultados ...";
        },
      }
    });
  });
</script>

</html>