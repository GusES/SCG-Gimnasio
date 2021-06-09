<?php
require('fpdf.php');
require('FPDF_class.php');
require ('../db_conn.php');
session_start();
check_login();
$idcliente=isset($_GET['idclientes'])?$_GET['idclientes']:false;
if($idcliente==false || empty($idcliente)==true){
	header('location: ../index.php');
	exit();
} 
$consulta="SELECT * FROM clientes WHERE idclientes='$idcliente'";
$resultado=$con->query($consulta);
$pdf = new FPDF_class('P','mm','A4');
$pdf->SetFont('Times','','10');
$pdf->AddPage();
// 1/3 Logo
$x=$pdf->GetX()+20;
$y=$pdf->GetY()-2;
$pdf->Image('../images/favicon.png',$x,$y,25,0);
$pdf->Cell(63.333,25,'',0,0);
// 2/3 Encabezado Medio
$pdf->SetFont('Arial','',24);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->Cell(63.333,$y,$pdf->Cell(25.66,$y,"",0,0)."".$pdf->Cell(12,$y,"",0,0,"C")."".$pdf->Cell(25.66,$y,"",0,0,"C"),0,0);
$pdf->ln(0);
$pdf->SetFont('Times','',7);
$pdf->Cell(63.33,25, "", 0, 0);
$pdf->Cell(63.33,25, "", 0, 0,'C');
// 3/3 Datos Bonarrigo
$pdf->SetFont('Times','B',12);
$pdf->Cell(63.33,5, utf8_decode("Gimnasio Bonarrigo"),0,2);
$pdf->SetFont('Times','',8);
$pdf->Cell(63.33,5, utf8_decode("Enrique Carbó 620 - 3100 Paraná, Entre Ríos"),0,2);
$pdf->Cell(63.33,5, utf8_decode("Teléfono: 343 622 3560"),0,2);
$pdf->Cell(63.33,10,'',0,2);
$pdf->ln(0);
$y=$pdf->GetY();
$pdf->line(10,$y,200,$y);
while($row=$resultado->fetch_assoc()){
$pdf->SetTitle('Nota de Credito',true);
$pdf->SetFont('Times','',10);
$pdf->Cell(95, 5, "Nombre: ".utf8_decode(ucwords($row['nombre']." ".$row['apellido'])), 0, 0);
$fecha = new DateTime('America/Argentina/Buenos_Aires');
$fecha = date_format($fecha,'d/m/Y H:i');
$pdf->Cell(0, 5, "Fecha Emision: ".$fecha."Hs.", 0, 1,"R");
$pdf->Cell(0, 5, "Documento: ".dinero($row['dni']), 0, 1);
$pdf->Cell(0, 5, "Direccion: ".utf8_decode(ucwords($row['direccion'])),0, 1);
$pdf->Cell(0, 5, "Telefono: ".$row['telefono'], 0, 1);
$pdf->ln(0);
$y=$pdf->GetY();
$pdf->line(10,$y,200,$y);	
$pdf->ln(2);
$pdf->SetFont('Times','B',20);
$pdf->ln(4);
$pdf->Cell(0, 6, utf8_decode("Nota de Crédito"),0,1,"C");	
$pdf->ln(4);
$pdf->SetTextColor(56, 61, 65);
$pdf->SetFillColor(226,227,229);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0, 6, utf8_decode("Términos y Condiciones"),0,1,"L",true);
$pdf->SetFont('Times','',10);
$pdf->Multicell(0, 6, utf8_decode("1) Las notas de crédito tienen una vigencia de 44 días desde su fecha de emisión, pasado esa fecha la misma se considerara fuera de vigencia.
2) La fecha de emisión de extenderá 44 días nuevamente al realiza un pago.
3) Las notas de crédito no son transferibles, bajo ninguna circunstancia.
4) Debe informar a administración que posee una nota de crédito, y la usara para saldar su pago mensual.
5) Si la nota de crédito es mayor al precio de su actividad, el restante se guardara para un próximo pago, este restante extiende su plazo de 44 días nuevamente.
6) Si existe un restante luego de realizar un pago, quedara registrado en el sistema, pero igual puede exigir un nuevo comprobante de nota de crédito con el monto actualizado."),0,1);
$y=$pdf->GetY()+2;
$pdf->line(10,$y,200,$y);		
$pdf->ln(4);	
$pdf->Cell(145,5,"",0,0);
$pdf->SetFont('Times','B',12);
$pdf->Cell(20,5,"Monto:",0,0,'R');
$pdf->SetFont('Times','',12);
$pdf->Cell(25,5,"$ ".dinero($row['notacredito']),0,1,'L');
$pdf->ln(2);		
$y=$pdf->GetY();
$pdf->line(10,$y,200,$y);		
} 
$pdf->Output('I','credito'.".pdf",true);
mysqli_close($con);
?>