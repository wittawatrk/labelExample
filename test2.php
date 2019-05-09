<?php
include("TCPDF/tcpdf.php");
include("PDF/pdf.php");
$pageSize = array(80, 80);
$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageSize, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('wittawat');
$pdf->SetTitle('Sticker Example 8x8');
$pdf->SetSubject('Sticker');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);
$pdf->SetAutoPageBreak(FALSE, 0);
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => true,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);
// set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(2, 2, 2);

// set auto page breaks
// $pdf->SetAutoPageBreak(TRUE,0);
$pdf->AddPage();

$pdf->Image('logo.png', 5, 4, 31.75, 5.749, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
$pdf->Image('flash_express.png', 41.75, 2, 31.75, 9.617604167, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);

$pdf->write1DBarcode('SP050287118', 'C128', '8', '11', '', 18, 0.4, $style, 'C');
$html = <<<EOD
<table cellspacing="0" cellpadding="2">
    <tr>
        <td colspan="4" style = "border: 1px solid black;font-size:12px"><b>ผู้ส่ง</b> ตาล จิรกานต์( โทร. 081544470 )
            <br> ตาล จิรกานต์ 178/109 ถนน ประชาสโมสร ตำบลในเมือง อำเภอเมือง ขอนแก่น 40000 40000
        </td>
    </tr>
    <tr>
        <td colspan="4" style = "border-left: 1px solid black;border-right: 1px solid black;font-size:16px ">(ผู้รับ) Nize Seasonings (0991565055)
            <br> 16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม</td>
    </tr>
    <tr>
        <td colspan="3" style = "border-left: 1px solid black;width:82%"></td>
        <td style="width:18%;font-size:18px;border: 1px solid black;text-align:right;border-top:1px solid black">10150</td>
    </tr>
    <tr >
        <td style = "border: 1px solid black;" colspan="2"></td>
        <td style = "border: 1px solid black; font-size:17px;text-align:center" colspan="2"><b>ไม่เก็บเงินปลายทาง</b></td>
    </tr>

</table>
EOD;
$pdf->SetY(28);
$pdf->writeHTML($html, true, false, false, false, '');





$pdf->Output('example_label_sticker.pdf');
//============================================================+
// END OF FILE
