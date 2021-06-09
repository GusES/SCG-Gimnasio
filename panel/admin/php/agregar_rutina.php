<?php 
include('../../../db_conn.php');
$idcliente=isset($_POST['idcliente'])?$_POST['idcliente']:null;
$nombrecliente=isset($_POST['nombrecliente'])?$_POST['nombrecliente']:null;
$idejercicios=isset($_POST['idejercicios'])?$_POST['idejercicios']:null;
$series=isset($_POST['series'])?$_POST['series']:null;
$rep=isset($_POST['rep'])?$_POST['rep']:null;
$dia=isset($_POST['dia'])?$_POST['dia']:null;
$tipo=isset($_POST['tipo'])?$_POST['tipo']:null;
$nombreplan=isset($_POST['nombreplan'])?mb_strtolower($_POST['nombreplan']):null;
$planselected=isset($_POST['asignar'])?mb_strtolower($_POST['asignar']):null;

$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d');

if($tipo != null){
 if($tipo == 1){
        $consulta="SELECT * FROM planes WHERE nombre = '$nombreplan'";
        $execute = $con->query($consulta);  
        $rows = $execute->num_rows;
        $m="(Nombre repetido)";
        if($rows > 0){
          $nombreplan = $nombreplan.$m;
        }
        $sql="INSERT INTO planes (nombre, idejercicio, series, rep, dia) VALUES ('$nombreplan', '$idejercicios', '$series', '$rep', '$dia');";
        $result = $con->query($sql);  
        echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
        <meta http-equiv='refresh' content='4;URL=../ver_rutinas.php' /> ";
  } else if($tipo == 2){
      $sql="INSERT INTO rutinas (tipo, idcliente, idejercicio, fecha, series, rep, dia) VALUES ('personalizada', '$idcliente', '$idejercicios', '$fecha', '$series', '$rep', '$dia');";
      $result = $con->query($sql);  
    echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
    <meta http-equiv='refresh' content='4;URL=../rutina_cliente.php?idcliente=$idcliente' /> ";	
  } else if($tipo == 3){
    $sql="SELECT * FROM planes WHERE nombre = '$planselected'";
    $result = $con->query($sql); 
    while($line = mysqli_fetch_object($result)){
      $results[] = $line;
    };
    for ($i = 0; $i < count($results); $i++) {
      $idejercicio = $results[$i]->idejercicio;
      $rep = $results[$i]->rep;
      $series = $results[$i]->series;
      $dia = $results[$i]->dia;
      $nombre = $results[$i]->nombre;
      $sql="INSERT INTO rutinas (tipo, idcliente, fecha, idejercicio, rep, series, dia) VALUES ('$nombre', '$idcliente', '$fecha', '$idejercicio', '$rep', '$series', '$dia')";
      $result = $con->query($sql);
    }  
    echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
    <meta http-equiv='refresh' content='4;URL=../rutina_cliente.php?idcliente=$idcliente' /> ";	
  }
} else {
  echo "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='200; url=../crear_plan.php'></head></html>";
}
mysqli_close($con);
?>