<?php
include '../../db_conn.php'; session_start(); check_login();
$idcliente=$_GET['id']?$_GET['id']:null;
if(is_numeric($idcliente)){
  $sql = "SELECT * FROM clientes WHERE idclientes = $idcliente ;";
  $result = $con->query($sql);
  $row = $result->fetch_object();
} else {
  header('location: ../../index.php');
  exit();
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
                <h5 class="m-0">Modificar Informacion</h5>
              </div>
            </div>
          </div>
          <div class="col-12">
            <form method="POST" action="php/editar_cliente.php">
              <input type="text" name="id" value="<?=$row->idclientes?>" hidden>
              <div class="form-group row mb-1">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre(s): </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Nombre(s)" name="nombre" id="nombre" value="<?=$row->nombre?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="apellido" class="col-sm-2 col-form-label">Apellido(s): </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Apellido(s)" name="apellido" id="apellido" value="<?=$row->apellido?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="nacimiento" class="col-sm-2 col-form-label">Fecha de Nacimiento: </label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" placeholder="Avenida Ejemplo 2600" name="nacimiento" id="nacimiento" min="1920-1-1" max="2099-12-31" value="<?=$row->nacimiento?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="dni" class="col-sm-2 col-form-label">DNI: </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="dni" id="dni" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" placeholder="12345678" min="1" max="99999999" value="<?=$row->dni?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="genero" class="col-sm-2 col-form-label">Genero: </label>
                <div class="col-sm-10">
                  <select class="form-control" id="genero" name="genero" required>
                    <option value="" disabled>Seleccione una opcion</option>
                    <option value="masculino" <?php if($row->genero == 'masculino'){echo "selected";}?>>Masculino</option>
                    <option value="femenino" <?php if($row->genero == 'femenino'){echo "selected";}?>>Femenino</option>
                    <option value="otro" <?php if($row->genero == 'otro'){echo "selected";}?>>Otro</option>
                  </select>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="direccion" class="col-sm-2 col-form-label">Dirección: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="Avenida Ejemplo 2600" name="direccion" id="direccion" value="<?=$row->direccion?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="clave" class="col-sm-2 col-form-label">Clave: </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="clave" id="clave" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" placeholder="1234" min="1" max="9999" value="<?=$row->clave?>">
                </div>
              </div>                
							<div class="form-group row mb-1">
                <label for="telefono" class="col-sm-2 col-form-label">Teléfono: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="---" name="telefono" id="telefono" value="<?=$row->telefono?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="facebook" class="col-sm-2 col-form-label">Facebook: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" placeholder="---" name="facebook" id="facebook" value="<?=$row->facebook?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="correo" class="col-sm-2 col-form-label">Email: </label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" placeholder="---" name="correo" id="correo" value="<?=$row->correo?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="observacion" class="col-sm-2 col-form-label">Observaciones: </label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="observacion" placeholder="Opcional: Informacion adicional" name="observacion" rows="3" maxlength="1000"><?=$row->observacion?></textarea>
                </div>
              </div>
							<div class="col-sm-12 text-center my-4">
                <button type="submit" class="btn-sm btn btn-success text-center">Aceptar</button>
                <a class="btn-sm btn btn-secondary" href="ver_clientes.php">Cancelar</a>
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

</html>