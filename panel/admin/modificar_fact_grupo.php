<?php 
include '../../db_conn.php';session_start();check_login();
$idgrupos=isset($_GET['idgrupos'])?$_GET['idgrupos']:null; 
$num_factura=isset($_GET['num_factura'])?$_GET['num_factura']:null;
if($num_factura != null && is_numeric($idgrupos)){
  $sql = "SELECT *, facturacion.nom_grupos AS nombregrupal FROM facturacion INNER JOIN clientes ON clientes.idclientes = facturacion.idcliente INNER JOIN grupos_clie ON facturacion.idgrupos=grupos_clie.idgrupos WHERE num_factura = $num_factura";
  $result = $con->query($sql);
  $results=array();
  $arrayNC=array(); 
  $arrayID=array();  
  $arrayIDRAW=array(); 
  while($line = mysqli_fetch_object($result)){$results[] = $line;}
  $result->close();  
}else{
  header('location: ../../index.php');
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
          <a class="btn btn-dark d-block text-left active" style="border-bottom:1px solid #6c757d;" href="ver_facturacion.php">Facturación</a>
          <a class="btn btn-dark d-block text-left" href="ver_ejercicios.php">Entrenamiento</a>
        </nav>
      </div>
      <div class="col-10">
        <div class="row">
          <div class="col-12 mb-1">
            <div class="row bg-secondary" style="height:59%;">
              <div class="col-6">
                <p class="m-0 badge badge-secondary">
                  <?=$_SESSION['rol'];?>
                </p>
              </div>
              <div class="col-6 text-right">
                <a class="btn btn-danger btn-sm py-0 px-2" href="../logout.php">Cerrar Sesión
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h5 class="m-0">Modificar Comprobante Grupal
                </h5>
              </div>
            </div>
          </div>
          <div class="col-12">
            <form method="POST" action="php/editar_fact_grupo.php">
              <input type="hidden" name="idgrupos" value="<?=$idgrupos?>">
              <div class="form-group row mb-1">
                <label for="num_factura" class="col-sm-2 col-form-label">Nº. de Factura:
                </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="num_factura" id="num_factura" value="<?=$num_factura?>" readonly>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="nom_grupos" class="col-sm-2 col-form-label">Nombre Grupo:
                </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nom_grupos" id="nom_grupos" value="<?=$results[0]->nombregrupal?>">
                </div>
              </div>
              <div class="form-group row mb-0">
                <label for="nombre" class="col-sm-2 col-form-label">Integrantes:
                </label>
                <div class="col-sm-6">
                  <?php
                    for ($i = 0; $i < $results[0]->num_clie; $i++) {
                      $cliente = "cliente".$i; 
                      $dni = $results[$i]->dni;
                      $query = "SELECT * FROM clientes where dni = $dni";     
                      $resultado = $con->query($query);
                      $fila = $resultado->fetch_object();
                      $resultado->close();
                      echo "<input type='text' class='form-control mb-1' name='nombre[]' id='nombre' value='$fila->nombre $fila->apellido' readonly>";
                      } ?>
                </div>
                <label for="dni_cliente" class="col-sm-1 col-form-label">DNI:
                </label>
                <div class="col-sm-3">
                  <?php
                    $notacredito = 0;
                    for ($i = 0; $i < $results[0]->num_clie; $i++) {
                      $cliente = "cliente".$i; 
                      $dni = $results[$i]->dni;
                      $query = "SELECT * FROM clientes where dni = $dni";     
                      $resultado = $con->query($query);
                      $fila = $resultado->fetch_object();
                      $resultado->close();
                      $formato= dinero($results[$i]->dni);
                      echo "<input type='text' class='form-control mb-1' name='dni_clientes' id='dni_cliente' value='$formato' readonly>";
                      echo "<input type='text' class='form-control' name='idcliente[]' id='idcliente' value='$fila->idclientes' hidden>";
                      if($fila->notacredito > 0){
                        $notacredito = $notacredito + $fila->notacredito;
                        $arrayNC[]=$fila->notacredito;
                        $arrayID[]=$fila->idclientes;
                      } else {
                        $arrayIDRAW[]=$fila->idclientes;
                      }
                    } ?>
                  <input type='text' class='form-control' name='notacredito' id='notacredito' value='<?=$notacredito?>' hidden>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="nom_modulo" class="col-sm-2 col-form-label">Membresía:
                </label>
                <div class="col-sm-10">
                  <select class="form-control" id="nom_modulo" name="nom_modulo" onchange='cambioOpciones()' required>
                    <option value="" disabled>Seleccione una opcion
                    </option>
                    <?php	
                    $numero_cliente = $results[0]->num_clie;
                    $sqloption = "SELECT * FROM membresias WHERE cant_per = '$numero_cliente'";
                    $execute = $con->query($sqloption);
                    while ($linea = $execute->fetch_object()){
                    ?>
                    <option value="<?=$linea->precio?>" <?php if(mb_strtolower($linea->nom_modulo) == $results[0]->disciplina){echo 'selected';}?>>
                      <?=ucwords($linea->nom_modulo)?>
                    </option>
                    <?php } $execute->close() ; ?>
                  </select>
                  <input type="hidden" id="modulo" name="modulo" value="<?=$results[0]->disciplina?>">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="precio" class="col-sm-2 col-form-label">Precio Membresía:
                </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="precio" id="precio" placeholder="0" readonly value="<?=$results[0]->precio?>">
                </div>
              </div>
              <div class="form-group row mb-1" id="ocultar">
                <label for="previo" class="col-sm-2 col-form-label">Aporte Previo:
                </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="previo" id="previo" value="<?=$results[0]->abonado?>" readonly>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="abonado" class="col-sm-2 col-form-label">Total a pagar:
                </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="abonado" id="abonado" min="0" placeholder="0">
                  <input type="hidden" class="form-control" name="msg" id="msg" placeholder="Lo abonado coincide con el precio." disabled>
                  <input type="hidden" class="form-control" name="msg2" id="msg2" placeholder="Nota(s) de credito cubre el valor de la cuota, presione aceptar." disabled>
                </div>
              </div>
              <div id="crear" class="row m-0" style="display:none">
                <div class="col-12 mx-auto mt-2 alert alert-warning">
                  <h4 class="alert-heading m-0">Importante!
                  </h4>
                  <h5 class="alert-heading my-1">Nota(s) de Crédito
                  </h5>
                  <p class="m-0">
                    <b>Se crearan nota(s) de crédito:
                    </b>
                  </p>
                  <p style="font-size:13px">
                    Se efectuó un aporte previo superior al precio de la nueva membresía, la diferencia se guardara como crédito para los clientes, este se usara para el o los próximo(s) pago(s) hasta finalizar el monto.
                    <br>
                    <b>El monto se dividirá equitativamente entre los clientes.
                    </b>
                    <br>
                    La misma puede imprimirse desde el menú lateral Facturación, y luego la opción superior Notas De Crédito.
                  </p>
                  <div class="form-group row mb-1">
                    <label for="notacredito" class="col-sm-3 col-form-label">Monto $:
                    </label>
                    <div class="col-sm-9">
                      <input type="hidden" class="form-control" name="montocredito" id="montocredito" readonly placeholder="-">
                      <input type="number" class="form-control" name="showrestante" id="showrestante" readonly placeholder="-">
                    </div>
                  </div>
                </div>
              </div>
              <div id="extra" class="row m-0" style="display:none">
                <div class="col-12 mx-auto mt-2 alert alert-warning">
                  <h4 class="alert-heading m-0">Importante!
                  </h4>
                  <h5 class="alert-heading my-1">Nota(s) de Crédito
                  </h5>
                  <?php 
                    if($arrayNC != null && count($arrayNC) == 1){
                      echo "<p class='m-0'><b>".count($arrayNC). " Cliente posee nota de crédito.</b></p><p style='font-size:13px'>Si existiese restante, este se guardara en la cuenta del cliente, que tenia crédito..</p>";
                    } else if ($arrayNC != null) {
                      echo "<p class='m-0'><b>".count($arrayNC). " Clientes poseen notas de crédito.</b></p><p style='font-size:13px'>El precio se dividirá equitativamente entre las notas de crédito. Si existiese restante, este se dividirá entre los clientes, siempre que su crédito allá sido mayor a su fracción de pago.</p>";
                    } ?>
                  <div class="form-group row mb-1">
                    <label for="notacredito" class="col-sm-3 col-form-label">Monto $:
                    </label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="notacredito" id="notacredito" readonly placeholder="0" value="<?=$notacredito?>">
                    </div>
                  </div>
                  <div class="form-group row mb-1">
                    <label for="restante" class="col-sm-3 col-form-label">Monto Restante $:
                    </label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="restante" id="restante" readonly placeholder="-">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 text-center my-4">
                <button type="submit" class="btn btn-sm btn-success text-center" onClick="return confirmar('../ver_gruposclientes.php')">Aceptar
                </button>
                <a class="btn btn-sm btn-secondary" href="ver_gruposclientes.php">Cancelar
                </a>
              </div>
              <input type="hidden" name="newcredito[]" id="newcredito">
              <input type="hidden" name="id[]" id="id">
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
//Esta minificado para que no tarde 3000 años en cargar, usa algún formatter de js online si querés verlo.
cambioOpciones();
function cambioOpciones(){document.getElementById('precio').value=document.getElementById('nom_modulo').value;var texto=document.getElementById("nom_modulo");var seleccionado=texto.options[texto.selectedIndex].text.toLowerCase();document.getElementById('modulo').value=seleccionado;var num1=Number(document.getElementById("precio").value);var num2=Number(document.getElementById("notacredito").value);var previo=Number(document.getElementById("previo").value);var credito=new Array();var id=new Array()
var nuevototal;var mantienecredito;var calculocredito;var nuevocredito;var extra=document.getElementById("extra");var abonado=document.getElementById("abonado");var ocular=document.getElementById("ocultar");var msg=document.getElementById("msg");var msg2=document.getElementById("msg2");if((num1==previo)&&(num2==0)){nuevototal=num1;nuevocredito=0;document.getElementById("abonado").value=nuevototal;document.getElementById("abonado").max=nuevototal;document.getElementById("montocredito").value=nuevocredito;msg.type='text';msg2.type='hidden';abonado.type='hidden';ocular.style.display='flex'}else if((num1<previo)&&(num2==0)){nuevocredito=previo-num1;nuevototal=num1;document.getElementById("abonado").value=nuevototal;document.getElementById("abonado").max=nuevototal;document.getElementById("montocredito").value=nuevocredito;msg.type='hidden';msg2.type='text';abonado.type='hidden';ocular.style.display='none'}else if((num1>previo)&&(num2==0)){nuevototal=num1-previo;nuevocredito=0;document.getElementById("abonado").value=nuevototal;document.getElementById("abonado").max=nuevototal;document.getElementById("montocredito").value=nuevocredito;msg.type='hidden';msg2.type='hidden';abonado.type='number';ocular.style.display='flex'}else if((num1==previo)&&(num2>0)){nuevototal=num1;mantienecredito=num2;calculocredito=0;nuevocredito=mantienecredito+calculocredito;document.getElementById("abonado").value=nuevototal;document.getElementById("abonado").max=nuevototal;document.getElementById("restante").value=nuevocredito;msg.type='text';msg2.type='hidden';abonado.type='hidden';ocular.style.display='flex'}else if((num1<=(previo+num2))&&(num2>0)){nuevototal=num1;mantienecredito=num2;calculocredito=previo-num1;nuevocredito=mantienecredito+calculocredito;document.getElementById("abonado").value=nuevototal;document.getElementById("abonado").max=nuevototal;document.getElementById("restante").value=nuevocredito;msg.type='hidden';msg2.type='text';abonado.type='hidden';ocular.style.display='none'}else if((num1>(previo+num2))&&(num2>0)){nuevototal=num1-(previo+num2);nuevocredito=0;document.getElementById("abonado").value=nuevototal;document.getElementById("abonado").max=nuevototal;document.getElementById("restante").value=nuevocredito;msg.type='hidden';msg2.type='hidden';abonado.type='number';ocular.style.display='flex'}
document.getElementById("montocredito").value=nuevocredito;var aux=nuevocredito-num2;document.getElementById("showrestante").value=aux;var arrayNC=<?=json_encode($arrayNC)?>;var arrayID=<?=json_encode($arrayID)?>;var arrayIDRAW=<?=json_encode($arrayIDRAW)?>;var newcredito1=0;var newcredito2=0;var newcredito3=0;var newcredito4=0;var newcredito5=0;if('<?=$notacredito?>'>0){switch(arrayNC.length){case 1:if(previo>0){var precio=num1-previo}else{var precio=num1}
var credito1=Number(arrayNC[0]);if(credito1>=precio){var pago1=precio}else{var pago1=credito1}
pago1=Math.round(pago1);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;credito=[newcredito1];id=[arrayID[0]];break
case 2:if(previo>0){var precio=(num1-previo)/arrayNC.length}else{var precio=num1/arrayNC.length}
var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
if(credito2>=precio){var pago2=precio;var excedente2=0;var bucle2=!0;cnt++}else{var pago2=credito2;var excedente2=Math.abs(precio-credito2);var bucle2=!1}
sumaexcedente=(excedente1+excedente2)/cnt;newprecio=precio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
sumaexcedente=(excedente1+excedente2)/cnt;newprecio=newprecio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}}}
pago1=Math.round(pago1);pago2=Math.round(pago2);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var credito=new Array();var id=new Array();credito=[newcredito1,newcredito2];id=[arrayID[0],arrayID[1]];break;case 3:if(previo>0){var precio=(num1-previo)/arrayNC.length}else{var precio=num1/arrayNC.length}
var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var credito3=Number(arrayNC[2]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
if(credito2>=precio){var pago2=precio;var excedente2=0;var bucle2=!0;cnt++}else{var pago2=credito2;var excedente2=Math.abs(precio-credito2);var bucle2=!1}
if(credito3>=precio){var pago3=precio;var excedente3=0;var bucle3=!0;cnt++}else{var pago3=credito3;var excedente3=Math.abs(precio-credito3);var bucle3=!1}
sumaexcedente=(excedente1+excedente2+excedente3)/cnt;newprecio=precio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}
sumaexcedente=(excedente1+excedente2+excedente3)/cnt;newprecio=newprecio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}}}
pago1=Math.round(pago1);pago2=Math.round(pago2);pago3=Math.round(pago3);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var newcredito3=(credito3>pago3)?Math.ceil(credito3-pago3):0;credito=[newcredito1,newcredito2,newcredito3];id=[arrayID[0],arrayID[1],arrayID[2]];break;case 4:if(previo>0){var precio=(num1-previo)/arrayNC.length}else{var precio=num1/arrayNC.length}
var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var credito3=Number(arrayNC[2]);var credito4=Number(arrayNC[3]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
if(credito2>=precio){var pago2=precio;var excedente2=0;var bucle2=!0;cnt++}else{var pago2=credito2;var excedente2=Math.abs(precio-credito2);var bucle2=!1}
if(credito3>=precio){var pago3=precio;var excedente3=0;var bucle3=!0;cnt++}else{var pago3=credito3;var excedente3=Math.abs(precio-credito3);var bucle3=!1}
if(credito4>=precio){var pago4=precio;var excedente4=0;var bucle4=!0;cnt++}else{var pago4=credito4;var excedente4=Math.abs(precio-credito4);var bucle4=!1}
sumaexcedente=(excedente1+excedente2+excedente3+excedente4)/cnt;newprecio=precio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}
if((bucle4==!0)&&(credito4>=newprecio)){pago4=newprecio;excedente4=0;bucle4=!0;cnt++}else if((bucle4==!0)&&(credito4<newprecio)){pago4=credito4;excedente4=Math.abs(newprecio-credito4);bucle4=!1}else if(bucle4==!1){excedente4=0}
sumaexcedente=(excedente1+excedente2+excedente3+excedente4)/cnt;newprecio=newprecio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}
if((bucle4==!0)&&(credito4>=newprecio)){pago4=newprecio;excedente4=0;bucle4=!0;cnt++}else if((bucle4==!0)&&(credito4<newprecio)){pago4=credito4;excedente4=Math.abs(newprecio-credito4);bucle4=!1}else if(bucle4==!1){excedente4=0}}}
pago1=Math.round(pago1);pago2=Math.round(pago2);pago3=Math.round(pago3);pago4=Math.round(pago4);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var newcredito3=(credito3>pago3)?Math.ceil(credito3-pago3):0;var newcredito4=(credito4>pago4)?Math.ceil(credito4-pago4):0;credito=[newcredito1,newcredito2,newcredito3,newcredito4];id=[arrayID[0],arrayID[1],arrayID[2],arrayID[3]];break;case 5:if(previo>0){var precio=(num1-previo)/arrayNC.length}else{var precio=num1/arrayNC.length}
var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var credito3=Number(arrayNC[2]);var credito4=Number(arrayNC[3]);var credito5=Number(arrayNC[4]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
if(credito2>=precio){var pago2=precio;var excedente2=0;var bucle2=!0;cnt++}else{var pago2=credito2;var excedente2=Math.abs(precio-credito2);var bucle2=!1}
if(credito3>=precio){var pago3=precio;var excedente3=0;var bucle3=!0;cnt++}else{var pago3=credito3;var excedente3=Math.abs(precio-credito3);var bucle3=!1}
if(credito4>=precio){var pago4=precio;var excedente4=0;var bucle4=!0;cnt++}else{var pago4=credito4;var excedente4=Math.abs(precio-credito4);var bucle4=!1}
if(credito5>=precio){var pago5=precio;var excedente5=0;var bucle5=!0;cnt++}else{var pago5=credito5;var excedente5=Math.abs(precio-credito5);var bucle5=!1}
sumaexcedente=(excedente1+excedente2+excedente3+excedente4+excedente5)/cnt;newprecio=precio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}
if((bucle4==!0)&&(credito4>=newprecio)){pago4=newprecio;excedente4=0;bucle4=!0;cnt++}else if((bucle4==!0)&&(credito4<newprecio)){pago4=credito4;excedente4=Math.abs(newprecio-credito4);bucle4=!1}else if(bucle4==!1){excedente4=0}
if((bucle5==!0)&&(credito5>=newprecio)){pago5=newprecio;excedente5=0;bucle5=!0;cnt++}else if((bucle5==!0)&&(credito5<newprecio)){pago5=credito5;excedente5=Math.abs(newprecio-credito5);bucle5=!1}else if(bucle5==!1){excedente5=0}
sumaexcedente=(excedente1+excedente2+excedente3+excedente4+excedente5)/cnt;newprecio=newprecio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}
if((bucle4==!0)&&(credito4>=newprecio)){pago4=newprecio;excedente4=0;bucle4=!0;cnt++}else if((bucle4==!0)&&(credito4<newprecio)){pago4=credito4;excedente4=Math.abs(newprecio-credito4);bucle4=!1}else if(bucle4==!1){excedente4=0}
if((bucle5==!0)&&(credito5>=newprecio)){pago5=newprecio;excedente5=0;bucle5=!0;cnt++}else if((bucle5==!0)&&(credito5<newprecio)){pago5=credito5;excedente5=Math.abs(newprecio-credito5);bucle5=!1}else if(bucle5==!1){excedente5=0}}}
pago1=Math.round(pago1);pago2=Math.round(pago2);pago3=Math.round(pago3);pago4=Math.round(pago4);pago5=Math.round(pago5);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var newcredito3=(credito3>pago3)?Math.ceil(credito3-pago3):0;var newcredito4=(credito4>pago4)?Math.ceil(credito4-pago4):0;var newcredito5=(credito5>pago5)?Math.ceil(credito5-pago5):0;credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayID[1],arrayID[2],arrayID[3],arrayID[4]];break}}
if(nuevocredito>num2){var totalrestante;var miembros=Number('<?=$results[0]->num_clie?>');if(num2>=nuevocredito){totalrestante=0}else{totalrestante=(nuevocredito-num2)/miembros}
switch(arrayNC.length){case 0:newcredito1=Math.round(totalrestante);newcredito2=Math.round(totalrestante);newcredito3=Math.round(totalrestante);newcredito4=Math.round(totalrestante);newcredito5=Math.round(totalrestante);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayIDRAW[0],arrayIDRAW[1],arrayIDRAW[2],arrayIDRAW[3],arrayIDRAW[4]];break;case 1:newcredito1=Math.round(credito1+totalrestante);newcredito2=Math.round(totalrestante);newcredito3=Math.round(totalrestante);newcredito4=Math.round(totalrestante);newcredito5=Math.round(totalrestante);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayIDRAW[0],arrayIDRAW[1],arrayIDRAW[2],arrayIDRAW[3]];break;case 2:newcredito1=Math.round(credito1+totalrestante);newcredito2=Math.round(credito2+totalrestante);newcredito3=Math.round(totalrestante);newcredito4=Math.round(totalrestante);newcredito5=Math.round(totalrestante);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayID[1],arrayIDRAW[0],arrayIDRAW[1],arrayIDRAW[2]];break;case 3:newcredito1=Math.round(credito1+totalrestante);newcredito2=Math.round(credito2+totalrestante);newcredito3=Math.round(credito3+totalrestante);newcredito4=Math.round(totalrestante);newcredito5=Math.round(totalrestante);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayID[1],arrayID[2],arrayIDRAW[0],arrayIDRAW[1]];break;case 4:newcredito1=Math.round(credito1+totalrestante);newcredito2=Math.round(credito2+totalrestante);newcredito3=Math.round(credito3+totalrestante);newcredito4=Math.round(credito4+totalrestante);newcredito5=Math.round(totalrestante);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayID[1],arrayID[2],arrayID[3],arrayIDRAW[0]];break;case 5:newcredito1=Math.round(credito1+totalrestante);newcredito2=Math.round(credito2+totalrestante);newcredito3=Math.round(credito3+totalrestante);newcredito4=Math.round(credito4+totalrestante);newcredito5=Math.round(credito5+totalrestante);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayID[1],arrayID[2],arrayID[3],arrayID[4]];break;default:newcredito1=Math.round(0);newcredito2=Math.round(0);newcredito3=Math.round(0);newcredito4=Math.round(0);newcredito5=Math.round(0);credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayIDRAW[0],arrayIDRAW[1],arrayIDRAW[2],arrayIDRAW[3],arrayIDRAW[4]];break}}
document.getElementById("id").value=id;document.getElementById("newcredito").value=credito;if((num1==previo)&&('<?=$results[0]->disciplina?>'==seleccionado)){document.getElementById("crear").style.display="none";document.getElementById("extra").style.display="none"}else if((num2>0)&&(num2>nuevocredito)){document.getElementById("crear").style.display="none";document.getElementById("extra").style.display="block"}else if((num2>0)&&(num1<previo)){document.getElementById("crear").style.display="block";document.getElementById("extra").style.display="none"}
if((num1<previo)&&(num2<=0)){document.getElementById("crear").style.display="block"}else if((num1>=previo)&&(num2<=0)){document.getElementById("crear").style.display="none"}}
</script>
</html>
