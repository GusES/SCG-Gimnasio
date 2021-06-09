<?php
require('fpdf.php');
require('FPDF_class.php');
require ('../db_conn.php');
session_start();
check_login();
$fecha_pago = isset($_GET['fecha_pago'])?$_GET['fecha_pago']:false;
if($fecha_pago == false || empty($fecha_pago) == true){
	header('location: ../index.php');
	exit();
} 
$consulta = "SELECT * FROM facturacion INNER JOIN clientes ON facturacion.idcliente = clientes.idclientes WHERE facturacion.fecha_pago='$fecha_pago'";
$resultado=$con->query($consulta);
$pdf = new FPDF_class('P','mm','A4');
while($row=$resultado->fetch_assoc()){
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
$pdf->Cell(63.333,$y,$pdf->Cell(25.66,$y,"",0,0)."".$pdf->Cell(12,$y,"X",1,0,"C")."".$pdf->Cell(25.66,$y,"",0,0,"C"),0,0);
$pdf->ln(0);
$pdf->SetFont('Times','',7);
$pdf->Cell(63.33,25, "", 0, 0);
$pdf->Cell(63.33,25, "DOCUMENTO NO VALIDO COMO FACTURA", 0, 0,'C');
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
$pdf->SetTitle('Comprobante: '.$row['num_factura'],true);
$numeracion=$row['num_factura'];
$actividades=explode(",",$row['disciplina']);
$pdf->SetFont('Times','',10);
$pdf->Cell(95, 5, "Nombre: ".utf8_decode(ucwords($row['nom_completo'])), 0, 0);
$pdf->Cell(95, 5, "Numero: ".$row['num_factura'],0,1,"R");
$pdf->Cell(95, 5, "Documento: ".dinero($row['dni']), 0, 0);
$pdf->Cell(0, 5, "Fecha Emision: ".date('d/m/Y', strtotime($fecha_pago)), 0, 1,"R");
$pdf->Cell(0, 5, "Direccion: ".utf8_decode(ucwords($row['direccion'])),0, 1);
$pdf->Cell(0, 5, "Telefono: ".$row['telefono'], 0, 1);
$pdf->ln(0);
$y=$pdf->GetY();
$pdf->line(10,$y,200,$y);	
$pdf->ln(2);
$pdf->SetTextColor(56, 61, 65);
$pdf->SetFillColor(226,227,229);
$pdf->SetFont('Times','B',10);
$pdf->Cell(95, 6, utf8_decode("     Descripción"),0,0,"L",true);
$pdf->Cell(95, 6, utf8_decode("Importe     "),0,1,"R",true);
$pdf->SetFont('Times','',10);
$cnt=0;
for($i=0;$i<count($actividades);$i++)	{
	$actividad=str_replace('&sbquo;',',',htmlentities($actividades[$i]));
	$pdf->Cell(95,5,"     ".utf8_decode(ucwords($actividad)),0,0,'L');	
	$pdf->Cell(95,5,"$ ".utf8_decode(dinero($row['precio']))."     ",0,1,'R');
	$cnt=$cnt+5;
	if($i+1==count($actividades)){
		$altomin=80-$cnt;
		if($altomin <= 0){
			$altomin=0;
		}
		$pdf->ln($altomin);
	}
}
$y=$pdf->GetY()+2;
$pdf->line(10,$y,200,$y);		
$pdf->ln(4);	
$pdf->Cell(158,5,"",0,0);
$pdf->SetFont('Times','B',10);
$pdf->Cell(17,5,"Total:",0,0,'R');
$pdf->SetFont('Times','',10);
$pdf->Cell(15,5,"$ ".dinero($row['precio']),0,1,'R');
$pdf->Cell(158,5,"",0,0);
$pdf->SetFont('Times','B',10);
$pdf->Cell(17,5,"Abonado:",0,0,'R');
$pdf->SetFont('Times','',10);
$pdf->Cell(15,5,"$ ".dinero($row['abonado']),0,2,'R');
$pdf->ln(2);		
$y=$pdf->GetY();
$pdf->line(10,$y,200,$y);		
$pdf->ln(10);		
$y=$pdf->GetY();
$x=$pdf->GetX()+63.33;
$pdf->i25($x,$y, $row['num_factura'],1.5,6);//CODIGO DE BARRA
$pdf->ln(20);		
$pdf->SetFont('Times','',7);
$pdf->Cell(0,5,"Los comprobantes de los grupos poseen la misma numeracion, solo variando los datos del cliente en cada uno.",0,2,'C');
} 
$pdf->Output('I','comp_'.$numeracion.".pdf",true);
mysqli_close($con);
?>