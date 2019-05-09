<?php 
include("TCPDF/tcpdf.php");
include("PDF/pdf.php");
$pageSize = array(80,80);
$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageSize, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('wittawat');
$pdf->SetTitle('Sticker Example 8x8');
$pdf->SetSubject('Sticker');


// set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set auto page breaks
// $pdf->SetAutoPageBreak(TRUE,0);

for($i=0;$i<10;$i++){
    $txt = 'sticker 8x8 page'.($i+1);
    $pdf->AddPage('P',$pageSize);
    $pdf->SetY(40);
    $pdf->Cell(0,0, $txt, 1,1,'C');
}




$pdf->Output('example_001.pdf', 'I');
//============================================================+
// END OF FILE