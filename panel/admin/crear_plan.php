<?php include '../../db_conn.php'; session_start(); check_login(); ?>

<!DOCTYPE html>
<html lang="es">
<?php include 'head.php' ?>
<style>
  .select2-selection,
  .select2-selection--single {
    height: 38px !important;
  }
  .select2-selection__rendered,
  .select2-selection__arrow {
    height: 38px !important;
    line-height: 38px !important;
  }
</style>

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
                <h5 class="m-0">Nuevo Plan</h5>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="col-12 text-center my-2">
              <p class="m-0">Seleccione una Opcion</p>
              <button type="button" class="btn btn-info btn-sm" id="btn1" onclick="btn1()">Nuevo Plan<span style="font-size:11px"> (Predefinido)</span></button>
              <button type="button" class="btn btn-info btn-sm" id="btn2" onclick="btn2()">Nuevo Plan<span style="font-size:11px"> (Personalizado)</span></button>
              <button type="button" class="btn btn-info btn-sm" id="btn3" onclick="btn3()">Asignar un Plan</button>
              <hr>
            </div>
            <div id="contenido" style="display:none">
              <div class="col-sm-12 mb-3">
                <form method="POST" action="php/agregar_rutina.php">
                  <div class="form-group row mb-1" id="selectcliente">
                    <label for="nombrecliente" class="col-sm-2 col-form-label">Cliente:</label>
                    <div class="col-sm-10">
                      <select class="js-example-responsive form-control" id="nombrecliente" name="nombrecliente" style="width:100%" onchange="cliente();">
                        <option value="" selected disabled>Seleccione un Cliente</option>
                        <?php 
                        $sql="SELECT * FROM clientes";
                        $rs=$con->query($sql);                    
                        while ($row=$rs->fetch_object()){ 
                        ?>
                        <option value="<?=$row->idclientes?>"><?=ucwords($row->dni." - ".$row->nombre . " " .$row->apellido)?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-4" id="selectplan">
                    <label for="listaplan" class="col-sm-2 col-form-label">Plan:</label>
                    <div class="col-sm-10">
                      <select class="js-example-responsive form-control" id="listaplan" name="listaplan" style="width:100%" onchange="selectplan();">
                        <option value="" selected disabled>Seleccione un Plan</option>
                        <?php 
                        $sql="SELECT * FROM planes";
                        $rs=$con->query($sql);                    
                        while ($row=$rs->fetch_object()){ 
                        ?>
                        <option value="<?=$row->nombre?>"><?=ucwords($row->nombre)?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-1" id="inputnombre">
                    <label for="nameplan" class="col-sm-2 col-form-label">Nombre:</label>
                    <div class="col-sm-10">
                      <input class="form-control" id="nameplan" name="nameplan" placeholder="Nombre del Plan" onkeyup="plan()">
                    </div>
                  </div>
                  <div class="alert alert-secondary p-1" id="alert">
                    <div class="form-group row mb-1">
                      <label for="ejercicio" class="col-sm-2 col-form-label">Ejercicio:</label>
                      <div class="col-sm-10">
                        <select class="js-example-responsive form-control" id="ejercicio" name="ejercicio" style="width:100%">
                          <option value="" selected disabled>Seleccione un Ejercicio</option>
                          <?php 
                          $query = "SELECT * FROM ejercicios order by nombre";
                          $resultado = $con->query($query);
                          while ($ver= $resultado->fetch_object()){ ?>
                          <option value="<?=$ver->idejercicios?>"><?=ucfirst(utf8_encode($ver->nombre))?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-1">
                      <div class="col-sm-2 col-form-label"></div>
                      <div class="col-sm-5">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <label class="input-group-text" for="serie">Series</label>
                          </div>
                          <input type="text" class="form-control" value="4" name="serie" id="serie">
                        </div>
                      </div>
                      <div class="col-sm-5">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <label class="input-group-text" for="repeticiones">Repeticiones</label>
                          </div>
                          <input type="text" class="form-control" value="10" name="repeticiones" id="repeticiones">
                        </div>
                      </div>
                    </div>
                    <div class="form-group row mb-1">
                      <div class="col-sm-2 col-form-label"></div>
                      <div class="col-sm-10">
                        <div class="form-control d-inline" style="padding:7px 0px 8px 0px;">
                          <div class="d-inline-block pr-2">
                          <div class="input-group-prepend">
                            <label class="input-group-text" for="1">Dia</label>
                          </div>
                        </div>
                          <div class="custom-control custom-radio custom-control-inline">
                          <input class="custom-control-input" type="radio" name="dia" id="1" value="1" checked>
                          <label class="custom-control-label" for="1">1</label>
                        </div>
                          <div class="custom-control custom-radio custom-control-inline">
                          <input class="custom-control-input" type="radio" name="dia" id="2" value="2">
                          <label class="custom-control-label" for="2">2</label>
                        </div>
                          <div class="custom-control custom-radio custom-control-inline">
                          <input class="custom-control-input" type="radio" name="dia" id="3" value="3">
                          <label class="custom-control-label" for="3">3</label>
                        </div>
                          <div class="custom-control custom-radio custom-control-inline">
                          <input class="custom-control-input" type="radio" name="dia" id="4" value="4">
                          <label class="custom-control-label" for="4">4</label>
                        </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="dia" id="5" value="5">
                            <label class="custom-control-label" for="5">5</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="dia" id="6" value="6">
                            <label class="custom-control-label" for="6">6</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="dia" id="7" value="7">
                            <label class="custom-control-label" for="7">7</label>
                          </div>
                          </div>                        
                      </div>
                    </div>
                    <div class="form-group row mb-0">
                      <div class="col-12 text-center">
                        <button type="button" class="btn btn-info btn-sm" onclick='agregarOpcion()'>Añadir</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-sm btn-success text-center" id="aceptar">Aceptar</button>
                    <a class="btn btn-sm btn-secondary" href="ver_ejercicios.php">Cancelar</a>
                    <div class="text-left">
                      <input type="hidden" name="idcliente" id="idcliente">
                      <input type="hidden" name="idejercicios" id="idejercicios">
                      <input type="hidden" name="series" id="series">
                      <input type="hidden" name="rep" id="rep">
                      <input type="hidden" name="dia" id="dia">
                      <input type="hidden" name="nombreplan" id="nombreplan">
                      <input type="hidden" name="asignar" id="asignar">
                      <input type="hidden" name="tipo" id="tipo">
                    </div>
                  </div>
                </form>
              </div>
              <div class="table-responsive" id="tabla" style="height:340px;overflow-y:visible; display:none">
                <table class="table table-striped table-bordered text-center" style="font-size:12px">
                  <thead class="thead-dark">
                    <th class="w-75 text-left">Ejercicios</th>
                    <th>Series</th>
                    <th>Rep.</th>
                    <th>Dia</th>
                    <th>Eliminar</th>
                  </thead>
                  <tbody id="tbody">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php mysqli_close($con); ?>
</body>
<script>
  var nombre = new Array;
  var id = new Array;
  var series = new Array;
  var rep = new Array;
  var dia = new Array;

  function agregarOpcion() {
    var seleccionado = document.getElementById("ejercicio");
    var texto = seleccionado.options[seleccionado.selectedIndex].text;
    var info = "";
    if (texto != "Seleccione un Ejercicio") {
      document.getElementById("tabla").style.display = "block";
      nombre.push(texto);
      id.push(document.getElementById("ejercicio").value);
      series.push(document.getElementById("serie").value);
      rep.push(document.getElementById("repeticiones").value);
      dia.push(document.querySelector('input[name=dia]:checked').value);
      for (i = 0; i < id.length; i++) {
        quitar = "<button class='btn btn-sm p-0 text-danger' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
        if (info == "") {
          info = "<tr><td class='text-left'>" + nombre[i] + "</td><td>" + series[i] + "</td><td>" + rep[i] + "</td><td>" + dia[i] + "</td><td>" + quitar + "</td></tr>";
        } else {
          info = info + ["<tr><td class='text-left'>" + nombre[i] + "</td><td>" + series[i] + "</td><td>" + rep[i] + "</td><td>" + dia[i] + "</td><td>" + quitar + "</td></tr>"];
        }
        document.getElementById("tbody").innerHTML = info;
      }
    }
    document.getElementById("idejercicios").value = id;
    document.getElementById("series").value = series;
    document.getElementById("rep").value = rep;
    document.getElementById("dia").value = dia;
  }

  function eliminar(i) {
    var info = "";
    var x = i;
    nombre.splice(x, 1);
    id.splice(x, 1);
    series.splice(x, 1);
    rep.splice(x, 1);
    dia.splice(x, 1);
    for (i = 0; i < id.length; i++) {
      document.getElementById("tabla").style.display = "block";
      quitar = "<button class='btn btn-sm p-0 text-danger' onclick='eliminar(" + i + ")'><i class='far fa-trash-alt'></i></button>";
      if (info == "") {
        info = "<tr><td class='text-left'>" + nombre[i] + "</td><td>" + series[i] + "</td><td>" + rep[i] + "</td><td>" + dia[i] + "</td><td>" + quitar + "</td></tr>";
      } else {
        info = info + ["<tr><td class='text-left'>" + nombre[i] + "</td><td>" + series[i] + "</td><td>" + rep[i] + "</td><td>" + dia[i] + "</td><td>" + quitar + "</td></tr>"];
      }
      document.getElementById("tbody").innerHTML = info;
    }
    if (id.length == 0) {
      $('#tbody').empty();
      document.getElementById("tabla").style.display = "none";
    }
    document.getElementById("idejercicios").value = id;
    document.getElementById("series").value = series;
    document.getElementById("rep").value = rep;
    document.getElementById("dia").value = dia;
  }

  function cliente() {
    document.getElementById("idcliente").value = document.getElementById("nombrecliente").value;
  }

  function btn1() {
    document.getElementById("tipo").value = 1;
    document.getElementById("btn1").classList.add("active");
    document.getElementById("btn2").classList.remove("active");
    document.getElementById("btn3").classList.remove("active");
    document.getElementById("contenido").style.display = "block";
    //document.getElementById("tabla").style.display = "block";
    document.getElementById("inputnombre").style.display = "flex";
    document.getElementById("selectcliente").style.display = "none";
    document.getElementById("selectplan").style.display = "none";
    document.getElementById("alert").style.display = "block";
    document.getElementById("alert").style.display = "block";
    document.getElementById("nameplan").required = true;
    document.getElementById("ejercicio").required = true;
    document.getElementById("nombrecliente").required = false;
  }

  function btn2() {
    document.getElementById("tipo").value = 2;
    document.getElementById("btn1").classList.remove("active");
    document.getElementById("btn2").classList.add("active");
    document.getElementById("btn3").classList.remove("active");
    document.getElementById("contenido").style.display = "block";
    document.getElementById("selectcliente").style.display = "flex";
    document.getElementById("inputnombre").style.display = "none";
    document.getElementById("selectplan").style.display = "none";
    document.getElementById("alert").style.display = "block";
    document.getElementById("nameplan").required = false;
    document.getElementById("ejercicio").required = true;
    document.getElementById("nombrecliente").required = true;
  }

  function btn3() {
    document.getElementById("tipo").value = 3;
    document.getElementById("btn1").classList.remove("active");
    document.getElementById("btn2").classList.remove("active");
    document.getElementById("btn3").classList.add("active");
    document.getElementById("contenido").style.display = "block";
    document.getElementById("selectcliente").style.display = "flex";
    document.getElementById("selectplan").style.display = "flex";
    document.getElementById("inputnombre").style.display = "none";
    document.getElementById("alert").style.display = "none";
    //document.getElementById("tabla").style.display = "none";
    document.getElementById("nombrecliente").required = true;
    document.getElementById("listaplan").required = true;
    document.getElementById("ejercicio").required = false;
    document.getElementById("nameplan").required = false;
  }

  function selectplan() {
    document.getElementById("asignar").value = document.getElementById("listaplan").value;
  }

  function plan() {
    document.getElementById("nombreplan").value = document.getElementById("nameplan").value;
  }
</script>
<script>
  $(document).ready(function() {
    $('.js-example-responsive').select2({
      language: {
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