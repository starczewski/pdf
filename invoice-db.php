<?php
require('tfpdf.php');
$con=mysqli_connect('localhost','root','');
mysqli_select_db($con,'invoicedb');

$query=mysqli_query($con,"select * from invoice
	inner join clients using(clientID)
	where
	invoiceID = '".$_GET['invoiceID']."'");
$invoice=mysqli_fetch_array($query);


//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

$pdf = new tFPDF('P','mm','A4');

$pdf->AddPage();

//set font to arial, bold, 14pt
$pdf->AddFont('Poppinsbold','','Poppins-Bold.ttf',true);
$pdf->SetFont('Poppinsbold','',14);

//Cell(width , height , text , border , end line , [align] )




//set font to arial, regular, 12pt




$pdf->Cell(0	,7,'Zestawienie przeglądów urządzeń',0,1,'C');
$pdf->Cell(0	,7,'fiskalnych dla Urzędu Skarbowego',0,1,'C');
$pdf->Cell(0	,7,'za okres 01-11-2020 do 30-11-2020',0,1,'C');





$pdf->AddFont('Poppinsbold','','Poppins-Bold.ttf',true);
$pdf->SetFont('Poppinsbold','',11);
//make a dummy empty cell as a vertical spacer
$pdf->Cell(190	,10,'',0,1);//end of line

//billing address
$pdf->Cell(1	,6,'',0,0);
$pdf->Cell(130	,6,'Wystawca zestawienia:',0,0,'L');
$pdf->Cell(0	,6,'Odbiorca zestawienia:',0,1,'R');
$pdf->AddFont('Poppins','','Poppins-Light.ttf',true);
$pdf->SetFont('Poppins','',10);
//add dummy cell at beginning of each line for indentation
$pdf->Cell(1	,6,'',0,0);
$pdf->Cell(130	,6,$invoice['name'],0,0,'L');
$pdf->Cell(0	,6,$invoice['name'],0,1,'R');

$pdf->Cell(1	,6,'',0,0);
$pdf->Cell(130	,6,$invoice['company'],0,0,'L');
$pdf->Cell(0	,6,$invoice['company'],0,1,'R');

$pdf->Cell(1	,6,'',0,0);
$pdf->Cell(130	,6,$invoice['address'],0,0,'L');
$pdf->Cell(0	,6,$invoice['address'],0,1,'R');

$pdf->Cell(1	,6,'',0,0);
$pdf->Cell(130	,6,$invoice['phone'],0,0,'L');
$pdf->Cell(0	,6,$invoice['phone'],0,1,'R');

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//invoice contents
$pdf->AddFont('Poppinsbold','','Poppins-Bold.ttf',true);
$pdf->SetFont('Poppinsbold','',8);

$pdf->Cell(8	,5,'Lp.',1,0);
$pdf->Cell(30	,5,'NIP Podatnika',1,0);
$pdf->Cell(30	,5,'Nazwa Podatnika',1,0);
$pdf->Cell(30	,5,'Adres podatnika',1,0);
$pdf->Cell(30	,5,'Numer seryjny i unikatowy',1,0);
$pdf->Cell(30	,5,'Numer ewidencyjny',1,0);
$pdf->Cell(34	,5,'Data przeglądu',1,1);//end of line

$pdf->AddFont('Poppins','','Poppins-Light.ttf',true);
$pdf->SetFont('Poppins','',10);

//Numbers are right-aligned so we give 'R' after new line parameter

//items
$query=mysqli_query($con,"select * from item where invoiceID = '".$invoice['invoiceID']."'");
$tax=0;
$amount=0;
while($item=mysqli_fetch_array($query)){
	$pdf->Cell(8	,5,$item['ItemID'],1,0);
	$pdf->Cell(30	,5,number_format($item['tax']),1,0);
	$pdf->Cell(30	,5,number_format($item['tax']),1,0);
	$pdf->Cell(34	,5,number_format($item['amount']),1,1,'R');//end of line
	$tax+=$item['tax'];
	$amount+=$item['amount'];
}

$pdf->Output();
?>
