<?php include '../../db_conn.php'; session_start(); check_login(); ?>

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
                <h5 class="m-0">Nuevo Grupo</h5>
              </div>
            </div>
          </div>
          <div class="col-12">
            <form method="POST" action="php/agregar_grupo.php">
              <div class="form-group row mb-1">
                <label for="nom_grupos" class="col-sm-2 col-form-label">Nombre: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Nombre del Grupo" name="nom_grupoos" id="nom_grupos" required>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="clientes" class="col-sm-2 col-form-label">Integrantes: </label>
                <div class="col-sm-10">
                  <select class="js-example-responsive form-control" multiple="multiple" name="dni[]" id="dni" required style="width: 100%">
                    <?php 
                    $query = "SELECT * FROM clientes WHERE estado = 1 or estado = 2";
                    $resultado = $con->query($query);
                    while ($ver= $resultado->fetch_object()){ ?>
                    <option value="<?=$ver->dni?>"><?=$ver->dni?> - <?=ucwords($ver->nombre)?> <?=ucwords($ver->apellido)?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-12 text-center my-4">
                <button type="submit" class="btn btn-sm btn-success text-center">Aceptar</button>
                <a class="btn btn-sm btn-secondary" href="ver_clientes.php">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  //Limitar caracteres y solo numeros
  function maxLengthCheck(object) {
    if (object.value.length > object.max.length)
      object.value = object.value.slice(0, object.max.length)
  }
  function isNumeric(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) theEvent.preventDefault();
    }
  }
</script>
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