<?php include '../../db_conn.php'; session_start(); check_login();
$idgrupos=isset($_GET['idgrupos'])?$_GET['idgrupos']:null;
if($idgrupos != ""){
  $arrayNC=array();
  $arrayID=array();
  $sql = "SELECT * FROM grupos_clie WHERE idgrupos = '$idgrupos';";
  $result = $con->query($sql);
  $row = $result->fetch_object();
} else {
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
                <p class="m-0 badge badge-secondary"><?=$_SESSION['rol'];?></p>
              </div>
              <div class="col-6 text-right">
                <a class="btn btn-danger btn-sm py-0 px-2" href="../logout.php">Cerrar Sesión</a>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h5 class="m-0">Nuevo Pago Grupal</h5>
              </div>
            </div>
          </div>
          <div class="col-12">
						<?php
						$zona = new DateTime('America/Argentina/Buenos_Aires');
						$fecha = $zona->format('Y-m-d H:i:s');
						$sql_membresias="SELECT * FROM asignaciones INNER JOIN membresias WHERE asignaciones.idmembresia = membresias.idmembresias AND idgrupo = '$idgrupos'";
						$rs_membresias = $con->query($sql_membresias);
						$row_membresias = $rs_membresias->fetch_object();
						$disciplinas=(!empty($row_membresias->nom_modulo))?$row_membresias->nom_modulo:"";
						$ultimo_ingreso=(!empty($row_ult_ingreso->ult_ingreso))?$row_ult_ingreso->ult_ingreso:"0000-00-00";
						$sql_dueda="SELECT * FROM facturacion INNER JOIN membresias on membresias.nom_modulo=facturacion.disciplina WHERE facturacion.disciplina='$disciplinas' GROUP BY num_factura ORDER BY num_factura DESC LIMIT 1";
						$rs_deuda = $con->query($sql_dueda);
						$sql_ult_ingreso="SELECT * FROM ingresos_mem INNER JOIN membresias WHERE ingresos_mem.idmembresia=membresias.idmembresias AND nom_modulo='$disciplinas' ORDER BY ingresos_mem.ult_ingreso DESC LIMIT 1";
						$rs_ult_ingreso = $con->query($sql_ult_ingreso);
						$row_ult_ingreso = $rs_ult_ingreso->fetch_object();
               
                        //control de ingreso al sistema
                        if (is_null($row_ult_ingreso)){
                              $ultimo_ingreso = "No ingreso al sistema";
                        } else {
                              $ultimo_ingreso = fechaCastellano($row_ult_ingreso->ult_ingreso);    
                        };
						while($row_deuda = $rs_deuda->fetch_object()){
							if($ultimo_ingreso > $row_deuda->fecha_vence or $fecha >= $row_deuda->fecha_vence){
								echo "<div class='alert alert-info'>";
								echo "<h6 class='text-capitalize m-0'>El grupo presenta pagos retrasados por:</h6>";
								echo "<hr class='my-1'>";
								echo "<b>Membresia</b>: ".ucwords($disciplinas)." - <b>Ultimo Pago</b>: ".fechaCastellano($row_deuda->fecha_pago)." - <b>Ultimo Ingreso</b>: ".$ultimo_ingreso."<br>";
								echo "</div>";
							}
						}
						?>
            <form method="POST" action="php/agregar_fact_grupo.php">
              <input type="text" name="idgrupos" value="<?=$row->idgrupos?>" hidden>
              <?php
              $zona = new DateTime('America/Argentina/Buenos_Aires');
              $fecha = $zona->format('Y-m');
              ?>
              <input type="text" name="fecha_fact_grupo" value="<?=$fecha?>" hidden>
                <?php
								function number_pad($number,$n){return str_pad((int) $number,$n,"0",STR_PAD_LEFT);}                   
								$consulta="SELECT num_factura FROM facturacion ORDER BY idfacturacion DESC LIMIT 1";
								$resultado = $con->query($consulta);
								$cnt = $resultado->num_rows;
								$fila = $resultado->fetch_object();
								if($cnt>0){$numero=$fila->num_factura+1;}else{$numero = 1;}
								$num_factura= number_pad($numero, 10);               
								?>
							<div class="form-group row mb-1">
                <label for="num_factura" class="col-sm-2 col-form-label">Nº. de Factura: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="num_factura" id="num_factura" value="<?=$num_factura?>" readonly>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="nom_grupos" class="col-sm-2 col-form-label">Nombre Grupo: </label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nom_grupos" id="nom_grupos" value="<?=$row->nom_grupos?>" readonly>
                </div>
              </div>
              <div class="form-group row mb-0">
                <label for="nombre" class="col-sm-2 col-form-label">Integrantes: </label>
                <div class="col-sm-6">
                  <?php
                  for ($i = 1; $i <= $row->num_clie; $i++) {
                    $cliente= "cliente". $i; $dni = $row->$cliente;
                    $query = "SELECT * FROM clientes where dni = $dni";     
                    $resultado = $con->query($query);
                    $fila = $resultado->fetch_object();
                    echo "<input type='text' class='form-control mb-1 text-capitalize' name='nombre[]' id='nombre' value='$fila->nombre $fila->apellido' readonly>";
                  } ?>
                </div>
                <label for="dni_cliente" class="col-sm-1 col-form-label">DNI: </label>
                <div class="col-sm-3">
                  <?php
                  $notacredito = 0;
                  for ($i = 1; $i <= $row->num_clie; $i++) {
										$cliente= "cliente". $i; $dni = $row->$cliente;
                    $uno = substr($dni, 0, 2); $dos = substr($dni, 2, 3);
                    $tres = substr($dni, 5, 3); $formato=$uno.".".$dos.".".$tres;
                    $query = "SELECT * FROM clientes where dni = $dni";     
                    $resultado = $con->query($query);
                    $fila = $resultado->fetch_object();
                    echo "<input type='text' class='form-control mb-1' name='dni_clientes' id='dni_cliente' value='$formato' readonly>";
                    echo "<input type='text' class='form-control' name='idcliente[]' id='idcliente' value='$fila->idclientes' hidden>";
                    if($fila->notacredito > 0){
                      $notacredito = $notacredito + $fila->notacredito;
                      $arrayNC[]=$fila->notacredito;
                      $arrayID[]=$fila->idclientes;                      
                    }
                  } ?>
                  <input type='text' class='form-control' name='notacredito' id='notacredito' value='<?=$notacredito?>' hidden>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="nom_modulo" class="col-sm-2 col-form-label">Membresía: </label>
                <div class="col-sm-10">
                  <select class="form-control" id="nom_modulo" name="nom_modulo" onchange='cambioOpciones()' required>
                    <option value="" selected disabled>Seleccione una opcion</option>
                    <?php
                    $query = "SELECT * FROM membresias inner join asignaciones on membresias.idmembresias= asignaciones.idmembresia WHERE actividades = $row->num_clie AND asignaciones.idgrupo = $idgrupos GROUP BY nom_modulo";
                    $resultado = $con->query($query);
                    while ($fila = $resultado->fetch_object()){ ?>
                    <option value="<?=$fila->precio?>"><?=ucwords($fila->nom_modulo)?></option>
                    <?php } ?>
                  </select>
                  <input type="text" id="modulo" name="modulo" hidden>
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="precio" class="col-sm-2 col-form-label">Precio Membresía: </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="precio" id="precio" min="0" readonly placeholder="0">
                </div>
              </div>
              <div class="form-group row mb-1">
                <label for="abonado" class="col-sm-2 col-form-label">Total a pagar: </label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="abonado" id="abonado" min="0" placeholder="0" required>
                  <input type="hidden" class="form-control" name="msg" id="msg" placeholder="Nota(s) de credito cubre el valor de la cuota, presione aceptar." disabled>
                </div>
              </div>
              <div id="extra" class="row m-0" style="display:none">
                <div class="col-12 mx-auto mt-2 alert alert-warning">
                  <h4 class="alert-heading m-0">Importante!</h4>
                  <h5 class="alert-heading my-1">Nota(s) de Crédito</h5>
                  <?php 
                  if($arrayNC != null && count($arrayNC) == 1){
                    echo "<p class='m-0'><b>".count($arrayNC). " Cliente posee nota de crédito, el monto se usara para el pago..</b></p>
                    <p style='font-size:13px'>Si existiese restante, este se guardara en la cuenta del cliente, que tenia crédito..</p>";
                  } else if ($arrayNC != null) {
                    echo "<p class='m-0'><b>".count($arrayNC) . " Clientes poseen notas de crédito, se usaran para el pago.</b></p>                          
                    <p style='font-size:13px'>El precio se dividirá equitativamente entre las notas de crédito. Si existiese restante, este se dividirá entre los clientes, siempre que su crédito allá sido mayor a su fracción de pago.
										</p>";
                  } ?>
                  <div class="form-group row mb-1">
                    <label for="notacredito" class="col-sm-3 col-form-label">Monto $:</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="notacredito" id="notacredito" readonly placeholder="0" value="<?=$notacredito?>">
                    </div>
                  </div>
                  <div class="form-group row mb-1">
                    <label for="restante" class="col-sm-3 col-form-label">Monto Restante $:</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="restante" id="restante" readonly placeholder="-">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 text-center my-4">
                <button type="submit" class="btn-sm btn btn-success text-center" onClick="return confirmar('../ver_gruposclientes.php')">Aceptar</button>
                <a class="btn-sm btn btn-secondary" href="ver_gruposclientes.php">Cancelar</a>
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
<script src="../../js/confirmacion.js"></script>
<?php mysqli_close($con)?>
<script type="text/javascript">
var creditodisponible=document.getElementById("notacredito").value;if(creditodisponible>0){document.getElementById("extra").style.display="block"}
function cambioOpciones(){document.getElementById('precio').value=document.getElementById('nom_modulo').value;var texto=document.getElementById("nom_modulo");var seleccionado=texto.options[texto.selectedIndex].text.toLowerCase();document.getElementById('modulo').value=seleccionado;var num1=document.getElementById("precio").value;var num2=document.getElementById("notacredito").value;var msg=document.getElementById("msg");var cobro=document.getElementById("abonado");var resultado=num1-num2;if(num1>num2){msg.type="hidden";cobro.type="text";document.getElementById('abonado').value=resultado;document.getElementById("abonado").max=resultado}else if(num1<=num2){msg.type="text";cobro.type="hidden";document.getElementById('abonado').value=num1;document.getElementById("abonado").max=num1}else if(num2==0){document.getElementById('abonado').value=num1;document.getElementById("abonado").max=num1}
var disponible=document.getElementById("notacredito").value;var restante=disponible-num1;if(restante<0){document.getElementById('restante').value=0}else{document.getElementById('restante').value=restante}
if(<?=$notacredito?>>0){var arrayNC=<?=json_encode($arrayNC)?>;var arrayID=<?=json_encode($arrayID)?>;switch(arrayNC.length){case 1:var precio=num1;var credito1=Number(arrayNC[0]);if(credito1>=precio){var pago1=precio}else{var pago1=credito1}
pago1=Math.round(pago1);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var credito=new Array();var id=new Array();credito=[newcredito1];id=[arrayID[0]];document.getElementById("id").value=id;document.getElementById("newcredito").value=credito;break;case 2:var precio=num1/arrayNC.length;var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
if(credito2>=precio){var pago2=precio;var excedente2=0;var bucle2=!0;cnt++}else{var pago2=credito2;var excedente2=Math.abs(precio-credito2);var bucle2=!1}
sumaexcedente=(excedente1+excedente2)/cnt;newprecio=precio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
sumaexcedente=(excedente1+excedente2)/cnt;newprecio=newprecio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}}}
pago1=Math.round(pago1);pago2=Math.round(pago2);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var credito=new Array();var id=new Array();credito=[newcredito1,newcredito2];id=[arrayID[0],arrayID[1]];document.getElementById("id").value=id;document.getElementById("newcredito").value=credito;break;case 3:var precio=num1/arrayNC.length;var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var credito3=Number(arrayNC[2]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
if(credito2>=precio){var pago2=precio;var excedente2=0;var bucle2=!0;cnt++}else{var pago2=credito2;var excedente2=Math.abs(precio-credito2);var bucle2=!1}
if(credito3>=precio){var pago3=precio;var excedente3=0;var bucle3=!0;cnt++}else{var pago3=credito3;var excedente3=Math.abs(precio-credito3);var bucle3=!1}
sumaexcedente=(excedente1+excedente2+excedente3)/cnt;newprecio=precio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}
sumaexcedente=(excedente1+excedente2+excedente3)/cnt;newprecio=newprecio+sumaexcedente;if(cnt>0){cnt=0;if((bucle1==!0)&&(credito1>=newprecio)){pago1=newprecio;excedente1=0;bucle1=!0;cnt++}else if((bucle1==!0)&&(credito1<newprecio)){pago1=credito1;excedente1=Math.abs(newprecio-credito1);bucle1=!1}else if(bucle1==!1){excedente1=0}
if((bucle2==!0)&&(credito2>=newprecio)){pago2=newprecio;excedente2=0;bucle2=!0;cnt++}else if((bucle2==!0)&&(credito2<newprecio)){pago2=credito2;excedente2=Math.abs(newprecio-credito2);bucle2=!1}else if(bucle2==!1){excedente2=0}
if((bucle3==!0)&&(credito3>=newprecio)){pago3=newprecio;excedente3=0;bucle3=!0;cnt++}else if((bucle3==!0)&&(credito3<newprecio)){pago3=credito3;excedente3=Math.abs(newprecio-credito3);bucle3=!1}else if(bucle3==!1){excedente3=0}}}
pago1=Math.round(pago1);pago2=Math.round(pago2);pago3=Math.round(pago3);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var newcredito3=(credito3>pago3)?Math.ceil(credito3-pago3):0;var credito=new Array();var id=new Array();credito=[newcredito1,newcredito2,newcredito3];id=[arrayID[0],arrayID[1],arrayID[2]];document.getElementById("id").value=id;document.getElementById("newcredito").value=credito;break;case 4:var precio=num1/arrayNC.length;var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var credito3=Number(arrayNC[2]);var credito4=Number(arrayNC[3]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
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
pago1=Math.round(pago1);pago2=Math.round(pago2);pago3=Math.round(pago3);pago4=Math.round(pago4);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var newcredito3=(credito3>pago3)?Math.ceil(credito3-pago3):0;var newcredito4=(credito4>pago4)?Math.ceil(credito4-pago4):0;var credito=new Array();var id=new Array();credito=[newcredito1,newcredito2,newcredito3,newcredito4];id=[arrayID[0],arrayID[1],arrayID[2],arrayID[3]];document.getElementById("id").value=id;document.getElementById("newcredito").value=credito;break;case 5:var precio=num1/arrayNC.length;var credito1=Number(arrayNC[0]);var credito2=Number(arrayNC[1]);var credito3=Number(arrayNC[2]);var credito4=Number(arrayNC[3]);var credito5=Number(arrayNC[4]);var cnt=0;var sumaexcedente;var newprecio;if(credito1>=precio){var pago1=precio;var excedente1=0;var bucle1=!0;cnt++}else{var pago1=credito1;var excedente1=Math.abs(precio-credito1);var bucle1=!1}
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
pago1=Math.round(pago1);pago2=Math.round(pago2);pago3=Math.round(pago3);pago4=Math.round(pago4);pago5=Math.round(pago5);var newcredito1=(credito1>pago1)?Math.ceil(credito1-pago1):0;var newcredito2=(credito2>pago2)?Math.ceil(credito2-pago2):0;var newcredito3=(credito3>pago3)?Math.ceil(credito3-pago3):0;var newcredito4=(credito4>pago4)?Math.ceil(credito4-pago4):0;var newcredito5=(credito5>pago5)?Math.ceil(credito5-pago5):0;var credito=new Array();var id=new Array();credito=[newcredito1,newcredito2,newcredito3,newcredito4,newcredito5];id=[arrayID[0],arrayID[1],arrayID[2],arrayID[3],arrayID[4]];document.getElementById("id").value=id;document.getElementById("newcredito").value=credito;break}}}
</script>
</html>