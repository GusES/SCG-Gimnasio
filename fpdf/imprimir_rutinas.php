<?php
require('../db_conn.php');
require('fpdf.php');
session_start();
/*check_login();*/
if(isset($_GET['idplanes'])){
	$idplan=isset($_GET['idplanes'])?$_GET['idplanes']:null;
	$q="SELECT * FROM planes WHERE idplanes='$idplan'";
	$mostrar=1;
} else if(isset($_GET['idcliente']) && isset($_GET['idrutina'])){
	$idcliente=isset($_GET['idcliente'])?$_GET['idcliente']:null;
	$idrutina=isset($_GET['idrutina'])?$_GET['idrutina']:null;
	$q="SELECT * FROM rutinas WHERE idcliente = '$idcliente' AND idrutinas = '$idrutina'";
	$qc="SELECT * FROM clientes WHERE idclientes='$idcliente'";
	$rc=$con->query($qc);
	$rc=$rc->fetch_object();
	$mostrar=2;
} else{
	header('location: ../index.php');
	exit();
}
$rst=$con->query($q);
$row=$rst->fetch_object();
$idejercicios=explode(",", $row->idejercicio);
$series=explode(",", $row->series);
$rep=explode(",", $row->rep);
$dia=explode(",", $row->dia);
$max=max($dia)+1;
$unico=1;
$cnt=1;
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
if($mostrar==1){
	$pdf->SetFont('Times','','10');
	$pdf->Cell(0,4,'Tipo: '.utf8_decode(ucfirst($row->nombre)),0,1);
	$pdf->ln(1);	
} else if($mostrar==2) {
	$fecha=date_create($row->fecha);
	$fecha=date_format($fecha,'d/m/Y');
	$pdf->SetFont('Times','','10');
	$pdf->Cell(95,4,'Cliente: '.utf8_decode(ucwords($rc->nombre.' '.$rc->apellido)),0,0);
	$pdf->Cell(95,4,'Fecha de Creacion: '.$fecha,0,1,"R");
	$pdf->Cell(95,4,'Documento: '.dinero($rc->dni),0,);
	$pdf->Cell(95,4,'Tipo: '.ucfirst($row->tipo),0,1,"R");
	$pdf->ln(1);	
}
$pdf->SetFont('Arial','','9');
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(52, 58, 64);
$pdf->Cell(85,5,'Descipcion',0,0,"L",true);
$pdf->Cell(15,5,'Equipo',0,0,"L",true);
$pdf->Cell(20,5,'Series',0,0,"L",true);
$pdf->Cell(25,5,'Repetciones',0,0,"L",true);
$pdf->Cell(20,5,'Descanso',0,0,"L",true);
$pdf->Cell(25,5,'Demostracion',0,0,"L",true);
$pdf->ln();
$pdf->SetFont('Times','','9');
$pdf->SetDrawColor(222, 226, 230);
while($cnt < $max){
	for($i=0; $i<count($dia); $i++){
		if($dia[$i]==$cnt){
			$newidejercicios[]=$idejercicios[$i];
			$newseries[]=$series[$i];
			$newrep[]=$rep[$i];
		}
	}
	for($i=0; $i<count($newidejercicios); $i++){
		$q="SELECT * FROM ejercicios WHERE idejercicios=$newidejercicios[$i]";
		$rst=$con->query($q);
		$row=$rst->fetch_object();	
		switch($row->grupomuscular){
			case "gluteos":
				$musculo="Piernas: ".$row->grupomuscular;;
				break;
			case "quadriceps":
				$musculo="Piernas: ".$row->grupomuscular;;
				break;
			case "isquiotibiales":
				$musculo="Piernas: ".$row->grupomuscular;;
				break;
			case "aductores/abductores":
				$musculo="Piernas: ".$row->grupomuscular;;
				break;
			case "gemelos":
				$musculo="Piernas: ".$row->grupomuscular;;
				break;
			default:
				$musculo=$row->grupomuscular;
				break;
		}
		//INICIO CUERPO FPDF
		if($unico == 1){
			$unico=2;
			$days="Dia ".$cnt;
			$pdf->SetTextColor(56, 61, 65);
			$pdf->SetFillColor(226,227,229);
			$pdf->Cell(190,4,$days,1,0,"L",true);
			$pdf->ln(6.3);
		}	
		$pdf->Cell(85,10,utf8_decode(ucfirst($row->nombre)),0);
		$pdf->ln(0);
		$pdf->SetFont('Times','B',7);
		$pdf->Cell(85,4,'Musculo: '.utf8_decode(ucwords($musculo)),0);
		$pdf->SetFont('Times','',9);
		$pdf->Cell(15,10,'# '.$row->numero,0);
		$pdf->Cell(20,10,utf8_decode(ucfirst($newseries[$i])),0);
		$pdf->Cell(25,10,utf8_decode(ucfirst($newrep[$i])),0);
		$pdf->Cell(20,10,'10 a 90 s',0);
		$x = $pdf->GetX();
		$y = $pdf->GetY()+0.5;
		$pdf->Image(substr($row->path,3),$x,$y,20,8);
		$pdf->ln(0);
		$yb = $pdf->GetY();
		if($i>0){
			$pdf->line(10,$yb,200,$yb);
		}
		if($i+1==count($newidejercicios)){	
			$pdf->ln(9);
		} else {
			$pdf->ln(9);
		}
		//FIN CUERPO FPDF
	}	
	$cnt++;
	$unico=1;	
	$newidejercicios=array();
	$newseries=array();
	$newrep=array();	
}
$fecha = new DateTime('America/Argentina/Buenos_Aires');
$fecha = date_format($fecha,'dmyHs');
$pdf->Output('I',$fecha.'_Rutina.pdf',true);
mysqli_close($con);
?>