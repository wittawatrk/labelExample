<?php
class Printing
{
    public $pageSize = array(80, 80);
    public $barcode_heigth = 13; //ความสูง barcode หน่วยเป็น mm
    public $style1d = array(
        'position' => '',
        'align' => 'C',
        'stretch' => true,
        'fitwidth' => true,
        'cellfitalign' => true,
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255),
        'text' => false,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4
    );
    public $style2d = array(
        'border' => 0,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255)
        'module_width' => 1, // width of a single module in points
        'module_height' => 1 // height of a single module in points
    );
    public function getimageTemp($url)
    {
        $image = base64_encode(file_get_contents($url));

        if ($image != '') {

            return base64_decode($image);
        } else {
            return 'error 404 file not found';
        }
    }
    public function findLogoName($courier_code)
    {
        switch ($courier_code) {
            case 'LLM':
                $vender_logo = 'lalamove.png';
                break;
            case 'FLS':
                $vender_logo = 'flash_express.png';
                break;
            case 'KRY':
                $vender_logo = 'kerry_express.png';
                break;
            case 'CJE':
                $vender_logo = 'cjlogistics.png';
                break;
            case 'NKS':
                $vender_logo = '';
                break;
            case 'SKT':
                $vender_logo = 'skootar.png';
                break;
            case 'APF':
                $vender_logo = 'alphafast.png';
                break;
            case 'NJV':
                $vender_logo = 'ninjavan.png';
                break;
            case 'THP':
                $vender_logo = 'thailandpost_ems.png';
                break;
            case 'TP2':
                $vender_logo = 'thailandpost_reg.png';
                break;
            case 'SCG':
                $vender_logo = 'scg-express-yamato.png';
                break;
            case 'SCGC':
                $vender_logo = 'scg-cool.png';
                break;
            case 'SCGF':
                $vender_logo = 'scg-frozen.png';
                break;
        }
        return $vender_logo;
    }
    public function isDropOff($courier_code)
    {
        if ($courier_code == 'THP' || $courier_code == 'TP2' || $courier_code == 'JNT') {
            return TRUE;
        } else return FALSE;
    }
    public function getPosition($courier_code)
    {
        if ($courier_code == 'THP' || $courier_code == 'TP2' || $courier_code == 'JNT') {
            return array(
                'a4' => array('logoSPx' => 63, 'logoVDy' => 43, 'barcodePosY' => 94, 'barcodePosX' => 203),
                'a5' => array('logoSPx' => 63, 'logoVDx' => 157, 'logoVDy' => 43, 'barcodePosY' => 95, 'barcodePosX' => 150),
                'a6' => array('logoSPx' => 47, 'logoVDx' => 117, 'logoVDy' => 37, 'barcodePosY' => 73, 'barcodePosX' => 85)
            );
        } else return array(
            'a4' => array('logoSPx' => 28, 'logoVDy' => 8, 'barcodePosY' => 64, 'barcodePosX' => 200),
            'a5' => array('logoSPx' => 30, 'logoVDx' => 155, 'logoVDy' => 8, 'barcodePosY' => 64, 'barcodePosX' => 150),
            'a6' => array('logoSPx' => 17, 'logoVDx' => 115, 'logoVDy' => 8, 'barcodePosY' => 73, 'barcodePosX' => 85)
        );
    }
    public function sticker8x8($data)
    {
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80, 80), true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('wittawat');
        $pdf->SetTitle('Sticker Example 8x8');
        $pdf->SetSubject('Sticker');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetAutoPageBreak(FALSE, 0);
        $pdf->SetMargins(1, 1, 1);

        // set auto page breaks
        // $pdf->SetAutoPageBreak(TRUE,0);

        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $cod = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " COD: " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            # code...

            if ($val['courier_code'] == 'THP' || $val['courier_code'] == 'TP2') {

                $pdf->AddPage();
                $pdf->Image($vender_logo, 3, 5, 34, 6, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->write1DBarcode($val['tracking_code'], 'C128', '5', '60', '', $this->barcode_heigth + 3, 0.4, $this->style1d, 'C');
                $pdf->setXY(0, 75);
                $pdf->Cell(80, 0, $val['tracking_code'], 0, 1, 'C');
                $pdf->setFont('thsarabun');
                 //render table from html
                $file = fopen(dirname(__FILE__) . "/html_template/sticker8x8TableThaipost.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/sticker8x8TableThaipost.html"));
                $html = mb_eregi_replace("\>\s+\<", "><", $html);
                $html = mb_eregi_replace("{{sender_name}}", $val['sender']['name'], $html);
                $html = mb_eregi_replace("{{sender_addr}}", $val['sender']['addr'], $html);
                $html = mb_eregi_replace("{{sender_tel}}", $val['sender']['tel'], $html);
                $html = mb_eregi_replace("{{sender_postcode}}", $val['sender']['postcode'], $html);
                $html = mb_eregi_replace("{{receiver_name}}", $val['receiver']['name'], $html);
                $html = mb_eregi_replace("{{receiver_addr}}", $val['receiver']['addr'], $html);
                $html = mb_eregi_replace("{{receiver_tel}}", $val['receiver']['tel'], $html);
                $html = mb_eregi_replace("{{receiver_postcode}}", $val['receiver']['postcode'], $html);
                $html = mb_eregi_replace("{{cod}}", $cod, $html);
                $pdf->SetY(3);
                $pdf->writeHTML($html, true, false, false, false, '');
            } elseif ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->AddPage();
                $pdf->Image($vender_logo, 10, 6, 39, 7, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
                $pdf->Image('@' . $qr, 55, 0.5, 18, 18);
                $pdf->setXY(7, 13);
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->Cell(45, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $pdf->write1DBarcode($val['tracking_code'], 'C128', '2.5', '16', '', $this->barcode_heigth, 0.5, $this->style1d, 'C');
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->setXY(0, 27.5);
                $pdf->Cell(80, 0, $val['tracking_code'], 0, 1, 'C');
                //render table from html
                $pdf->setFont('thsarabun');
                $file = fopen(dirname(__FILE__) . "/html_template/sticker8x8TableSCG.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/sticker8x8TableSCG.html"));
                $html = mb_eregi_replace("\>\s+\<", "><", $html);
                $html = mb_eregi_replace("{{sender_name}}", $val['sender']['name'], $html);
                $html = mb_eregi_replace("{{sender_addr}}", $val['sender']['addr'], $html);
                $html = mb_eregi_replace("{{sender_tel}}", $val['sender']['tel'], $html);
                $html = mb_eregi_replace("{{sender_postcode}}", $val['sender']['postcode'], $html);
                $html = mb_eregi_replace("{{receiver_name}}", $val['receiver']['name'], $html);
                $html = mb_eregi_replace("{{receiver_addr}}", $val['receiver']['addr'], $html);
                $html = mb_eregi_replace("{{receiver_tel}}", $val['receiver']['tel'], $html);
                $html = mb_eregi_replace("{{receiver_postcode}}", trim($val['receiver']['postcode']), $html);
                $html = mb_eregi_replace("{{cod}}", $cod, $html);
                $pdf->SetY(32);
                $pdf->writeHTML($html, true, false, false, false, '');
            } elseif ($val['courier_code'] == 'NJV') {

                $pdf->AddPage();
                $pdf->Image('logo.png', 8, 6, 39, 7, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
                $pdf->Image($vender_logo, 9, 14, 34, 15, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 53, 4, 20, 20, $this->style2d, 'N');
                // $pdf->Text(20, 25,$val['tracking_code']);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->setXY(45, 25);
                $pdf->Cell(36, 0, $val['tracking_code'], 0, 1, 'C');

                $pdf->setFont('thsarabun');
                 //render table from html
                $file = fopen(dirname(__FILE__) . "/html_template/sticker8x8TableNinjavan.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/sticker8x8TableNinjavan.html"));
                $html = mb_eregi_replace("\>\s+\<", "><", $html);
                $html = mb_eregi_replace("{{sender_name}}", $val['sender']['name'], $html);
                $html = mb_eregi_replace("{{sender_addr}}", $val['sender']['addr'], $html);
                $html = mb_eregi_replace("{{sender_tel}}", $val['sender']['tel'], $html);
                $html = mb_eregi_replace("{{sender_postcode}}", $val['sender']['postcode'], $html);
                $html = mb_eregi_replace("{{receiver_name}}", $val['receiver']['name'], $html);
                $html = mb_eregi_replace("{{receiver_addr}}", $val['receiver']['addr'], $html);
                $html = mb_eregi_replace("{{receiver_tel}}", $val['receiver']['tel'], $html);
                $html = mb_eregi_replace("{{receiver_postcode}}", $val['receiver']['postcode'], $html);
                $html = mb_eregi_replace("{{cod}}", $cod, $html);

                $pdf->SetY(30);
                $pdf->writeHTML($html, true, false, false, false, '');
            } else {

                $postcode = $val['courier_code'] == 'CJE' ? $val['receiver']['postcode'] : '';
                $postcode2 = $val['courier_code'] == 'CJE' ? $val['cjcode'] : $val['receiver']['postcode'];


                $pdf->AddPage();
                $pdf->Image('logo.png', 1, 0.5, 31.75, 5.749, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
                $pdf->Image($vender_logo, 46.75, -1, 31.75, 9.617604167, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                $pdf->write1DBarcode($val['tracking_code'], 'C128', '8', '7', '', $this->barcode_heigth, 0.4, $this->style1d, 'C');
                $pdf->SetY(18);
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->Cell(0, 0, $val['tracking_code'], 0, 1, 'C');
                $pdf->setFont('thsarabun');
                 //render table from html
                $file = fopen(dirname(__FILE__) . "/html_template/sticker8x8TableOther.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/sticker8x8TableOther.html"));
                $html = mb_eregi_replace("\>\s+\<", "><", $html);
                $html = mb_eregi_replace("{{sender_name}}", $val['sender']['name'], $html);
                $html = mb_eregi_replace("{{sender_addr}}", $val['sender']['addr'], $html);
                $html = mb_eregi_replace("{{sender_tel}}", $val['sender']['tel'], $html);
                $html = mb_eregi_replace("{{sender_postcode}}", $val['sender']['postcode'], $html);
                $html = mb_eregi_replace("{{receiver_name}}", $val['receiver']['name'], $html);
                $html = mb_eregi_replace("{{receiver_addr}}", $val['receiver']['addr'], $html);
                $html = mb_eregi_replace("{{receiver_tel}}", $val['receiver']['tel'], $html);
                $html = mb_eregi_replace("{{postcode}}", $postcode, $html);
                $html = mb_eregi_replace("{{postcode2}}", $postcode2, $html);
                $html = mb_eregi_replace("{{cod}}", $cod, $html);
                $pdf->SetY(24);
                $pdf->writeHTML($html, true, false, false, false, '');
            }
        }
        $output = $pdf->Output('sticker8x8.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }

    public function a4($data)
    {
        // $style3 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2', 'color' => array(11, 157, 209));
        $style1 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(11, 157, 209));
        $style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));
        $pdf = new PDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('shippop');
        $pdf->SetTitle('ใบปะหน้าขนาด A4');
        $pdf->SetSubject('A4');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(1, 1, 1);
        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $dropOff = $this->isDropOff($val['courier_code']) ? "(บริการ Drop off)" : " ";
            $cod1 = $val['cod'] && $val['cod'] == 1 ? " *โทรศัพท์นัดผู้รับก่อนจัดส่ง" : "";
            $cod2 = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " เก็บเงินมูลค่า : " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            $logoPos = $this->getPosition($val['courier_code']);

            $pdf->AddPage();
            // $pdf->Rect(5, 3, 287, 204, 'D', array('all' => $style3));
            $pdf->Image('logo.png', 10, 5, 66, 12, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            // ------Sender--------
            $pdf->SetFont('thsarabun', 'B', 26);
            $pdf->SetXY(20, 30);
            $pdf->Cell(180, 0, 'ชื่อที่อยู่ผู้ฝากส่ง' . $dropOff, 0, 1);
            $pdf->SetXY(20, 40);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 20);
            $pdf->Cell(60, 0, 'คุณ ' . $val['sender']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 18);
            $pdf->SetXY(20, 50);
            $pdf->MultiCell(150, 22, $val['sender']['addr'], 0, 1);
            $pdf->setXY(22, 76);
            $pdf->SetFont('thsarabun', 'B', 22);
            $pdf->SetLineStyle($style2);
            $pdf->Cell(7, 0, $val['sender']['postcode'][0], 1, 1, 'C');
            $pdf->setXY(31, 76);
            $pdf->Cell(7, 0, $val['sender']['postcode'][1], 1, 1, 'C');
            $pdf->setXY(40, 76);
            $pdf->Cell(7, 0, $val['sender']['postcode'][2], 1, 1, 'C');
            $pdf->setXY(49, 76);
            $pdf->Cell(7, 0, $val['sender']['postcode'][3], 1, 1, 'C');
            $pdf->setXY(58, 76);
            $pdf->Cell(7, 0, $val['sender']['postcode'][4], 1, 1, 'C');
            $pdf->setXY(75, 76);
            $pdf->SetFont('thsarabun', '', 20);
            $pdf->Cell(15, 0, 'โทร. ' . $val['sender']['tel'], 0, 1);
            //------ Receiver -----
            $pdf->SetFont('thsarabun', 'B', 28);
            $pdf->SetXY(20, 105);
            $pdf->Cell(180, 0, 'ชื่อที่อยู่ผู้รับ' . $cod1, 0, 1);
            $pdf->SetXY(20, 115);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 26);
            $pdf->Cell(60, 0, 'คุณ ' . $val['receiver']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 24);
            $pdf->SetXY(20, 125);
            $pdf->MultiCell(170, 30, $val['receiver']['addr'], 0, 1);
            $pdf->setXY(22, 160);
            $pdf->SetFont('thsarabun', 'B', 26);
            $pdf->SetLineStyle($style2);
            $pdf->Cell(12, 12, $val['receiver']['postcode'][0], 1, 1, 'C');
            $pdf->setXY(36, 160);
            $pdf->Cell(12, 12, $val['receiver']['postcode'][1], 1, 1, 'C');
            $pdf->setXY(50, 160);
            $pdf->Cell(12, 12, $val['receiver']['postcode'][2], 1, 1, 'C');
            $pdf->setXY(64, 160);
            $pdf->Cell(12, 12, $val['receiver']['postcode'][3], 1, 1, 'C');
            $pdf->setXY(78, 160);
            $pdf->Cell(12, 12, $val['receiver']['postcode'][4], 1, 1, 'C');
            $pdf->setXY(100, 160);
            $pdf->SetFont('thsarabun', '', 20);
            $pdf->Cell(15, 12, 'โทร. ' . $val['receiver']['tel'], 0, 1);
             //render table from html
            if ($val['courier_code'] == 'THP' || $val['courier_code'] == 'TP2') {
                $file = fopen(dirname(__FILE__) . "/html_template/a4TableThaipost.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/a4TableThaipost.html"));
            } else {
                $file = fopen(dirname(__FILE__) . "/html_template/a4TableOther.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/a4TableOther.html"));
            }
            $html = mb_eregi_replace("\>\s+\<", "><", $html);
            $html = mb_eregi_replace("{{cod2}}", $cod2, $html);
            $pdf->SetXY(200, 5);
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->Image('logo.png', 213, $logoPos['a4']['logoSPx'], 66, 12, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($vender_logo, 211, $logoPos['a4']['logoVDy'], 66, 12, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            if ($val['courier_code'] == 'NJV') {
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 225, 64, 40, 40, $this->style2d, 'N');
                $pdf->SetXY(215, 100);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            } else {
                $pdf->write1DBarcode($val['tracking_code'], 'C128', $logoPos['a4']['barcodePosX'], $logoPos['a4']['barcodePosY'], 85, $this->barcode_heigth + 15, 0.6, $this->style1d, 'C');
                $pdf->SetXY(212, $logoPos['a4']['barcodePosY'] + 27);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            }
            if ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $pdf->SetXY(212, $logoPos['a4']['barcodePosY'] + 35);
                $pdf->Cell(60, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->Image('@' . $qr, 190, 175, 30, 30);
            }
            if ($val['courier_code'] == 'CJE') {
                $writecode = '<span style="color:red;font-size:36pt"><b>' . $val['cjcode'] . '</b></span>';
                $pdf->SetXY(230, 120);
                $pdf->writeHTML($writecode, true, false, false, false, '');
            }
            $pdf->SetLineStyle($style1);
            $pdf->SetXY(20, 180);
            $pdf->MultiCell(130, 22, 'หมายเหตุ', 1, 1);
        }


        $output = $pdf->Output('a4.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }

    public function a5($data)
    {
        // $style3 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2', 'color' => array(11, 157, 209));
        $style1 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(11, 157, 209));
        $style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));
        $pdf = new PDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('shippop');
        $pdf->SetTitle('ใบปะหน้าขนาด A5');
        $pdf->SetSubject('A5');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(1, 1, 1);
        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $dropOff = $this->isDropOff($val['courier_code']) ? "(บริการ Drop off)" : " ";
            $cod1 = $val['cod'] && $val['cod'] == 1 ? " *โทรศัพท์นัดผู้รับก่อนจัดส่ง" : "";
            $cod2 = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " เก็บเงินมูลค่า : " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            $logoPos = $this->getPosition($val['courier_code']);

            $pdf->AddPage();
            // $pdf->Rect(5, 3, 204, 144, 'D', array('all' => $style3));
            $pdf->Image('logo.png', 10, 5, 66, 12, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            // ------Sender--------
            $pdf->SetFont('thsarabun', 'B', 20);
            $pdf->SetXY(15, 20);
            $pdf->Cell(110, 0, 'ชื่อที่อยู่ผู้ฝากส่ง' . $dropOff, 0, 1);
            $pdf->SetXY(15, 27);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 18);
            $pdf->Cell(60, 0, 'คุณ ' . $val['sender']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 16);
            $pdf->SetXY(15, 32);
            $pdf->MultiCell(110, 22, $val['sender']['addr'], 0, 1);
            $pdf->setXY(17, 52);
            $pdf->SetFont('thsarabun', 'B', 22);
            $pdf->SetLineStyle($style2);
            $pdf->Cell(7, 0, $val['sender']['postcode'][0], 1, 1, 'C');
            $pdf->setXY(26, 52);
            $pdf->Cell(7, 0, $val['sender']['postcode'][1], 1, 1, 'C');
            $pdf->setXY(35, 52);
            $pdf->Cell(7, 0, $val['sender']['postcode'][2], 1, 1, 'C');
            $pdf->setXY(44, 52);
            $pdf->Cell(7, 0, $val['sender']['postcode'][3], 1, 1, 'C');
            $pdf->setXY(53, 52);
            $pdf->Cell(7, 0, $val['sender']['postcode'][4], 1, 1, 'C');
            $pdf->setXY(65, 52);
            $pdf->SetFont('thsarabun', '', 20);
            $pdf->Cell(15, 0, 'โทร. ' . $val['sender']['tel'], 0, 1);
            //------ Receiver -----
            $pdf->SetFont('thsarabun', 'B', 20);
            $pdf->SetXY(15, 70);
            $pdf->Cell(180, 0, 'ชื่อที่อยู่ผู้รับ' . $cod1, 0, 1);
            $pdf->SetXY(15, 78);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 18);
            $pdf->Cell(60, 0, 'คุณ ' . $val['receiver']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 18);
            $pdf->SetXY(15, 85);
            $pdf->MultiCell(130, 27, $val['receiver']['addr'], 0, 1);
            $pdf->setXY(17, 113);
            $pdf->SetFont('thsarabun', 'B', 26);
            $pdf->SetLineStyle($style2);
            $pdf->Cell(10, 10, $val['receiver']['postcode'][0], 1, 1, 'C');
            $pdf->setXY(29, 113);
            $pdf->Cell(10, 10, $val['receiver']['postcode'][1], 1, 1, 'C');
            $pdf->setXY(41, 113);
            $pdf->Cell(10, 10, $val['receiver']['postcode'][2], 1, 1, 'C');
            $pdf->setXY(53, 113);
            $pdf->Cell(10, 10, $val['receiver']['postcode'][3], 1, 1, 'C');
            $pdf->setXY(65, 113);
            $pdf->Cell(10, 10, $val['receiver']['postcode'][4], 1, 1, 'C');
            $pdf->setXY(85, 113);
            $pdf->SetFont('thsarabun', '', 20);
            $pdf->Cell(15, 12, 'โทร. ' . $val['receiver']['tel'], 0, 1);

            if ($val['courier_code'] == 'THP' || $val['courier_code'] == 'TP2') {
                $file = fopen(dirname(__FILE__) . "/html_template/a5TableThaipost.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/a5TableThaipost.html"));
            } else {
                $file = fopen(dirname(__FILE__) . "/html_template/a5TableOther.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/a5TableOther.html"));
            }
            $html = mb_eregi_replace("\>\s+\<", "><", $html);
            $html = mb_eregi_replace("{{cod2}}", $cod2, $html);

            $pdf->SetXY(150, 5);
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->Image('logo.png', 155, $logoPos['a5']['logoSPx'], 45, 9.5, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($vender_logo, $logoPos['a5']['logoVDx'], $logoPos['a5']['logoVDy'], 45, 9.5, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
            if ($val['courier_code'] == 'NJV') {
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 165, 64, 30, 30, $this->style2d, 'N');
                $pdf->SetXY(150, 90);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            } else {
                $pdf->write1DBarcode($val['tracking_code'], 'C128', $logoPos['a5']['barcodePosX'], $logoPos['a5']['barcodePosY'], 56, $this->barcode_heigth + 5, 0.4, $this->style1d, 'C');
                $pdf->SetXY(150, $logoPos['a5']['barcodePosY'] + 17);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            }
            if ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $pdf->SetXY(150, $logoPos['a5']['barcodePosY'] + 25);
                $pdf->Cell(60, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->Image('@' . $qr, 165, 115, 30, 30);
            }
            if ($val['courier_code'] == 'CJE') {
                $writecode = '<span style="color:red;font-size:20pt"><b>' . $val['cjcode'] . '</b></span>';
                $pdf->SetXY(175, 100);
                $pdf->writeHTML($writecode, true, false, false, false, '');
            }
            $pdf->SetLineStyle($style1);
            $pdf->SetXY(15, 125);
            $pdf->MultiCell(130, 20, 'หมายเหตุ', 1, 1);
        }


        $output = $pdf->Output('a5.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }

    public function a6($data)
    {
        // $style3 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2', 'color' => array(11, 157, 209));
        $style1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(11, 157, 209));
        // $style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));
        $pdf = new PDF('L', PDF_UNIT, 'A6', true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('shippop');
        $pdf->SetTitle('ใบปะหน้าขนาด A6');
        $pdf->SetSubject('A6');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(1, 1, 1);
        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $dropOff = $this->isDropOff($val['courier_code']) ? "(บริการ Drop off)" : " ";
            $cod1 = $val['cod'] && $val['cod'] == 1 ? " *โทรศัพท์นัดผู้รับก่อนจัดส่ง" : "";
            $cod2 = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " เก็บเงินมูลค่า : " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            $logoPos = $this->getPosition($val['courier_code']);

            $pdf->AddPage();
            // $pdf->Rect(5, 3, 142, 101, 'D', array('all' => $style3));
            $pdf->Image('logo.png', 5, 5, 33, 6, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            // ------Sender--------
            $pdf->SetFont('thsarabun', 'B', 16);
            $pdf->SetXY(5, 12);
            $pdf->Rect(5, 12, 40, 50, 'F', 0, array(211, 211, 211));
            $pdf->Rect(47, 12, 55, 50, 'F', 0, array(245, 245, 245));
            $pdf->Cell(110, 0, 'ผู้ส่ง' . $dropOff, 0, 1);
            $pdf->SetXY(5, 17);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->Cell(60, 0, 'คุณ ' . $val['sender']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 12);
            $pdf->SetXY(5, 26);
            $pdf->MultiCell(40, 15, $val['sender']['addr'], 0, 1);
            $pdf->setXY(5, 22);
            $pdf->SetFont('thsarabun', '', 12);
            $pdf->Cell(15, 0, 'โทร. ' . $val['sender']['tel'], 0, 1);
            $pdf->setXY(25, 55);
            $pdf->SetFont('helvetica', '', 18);
            $pdf->Cell(15, 0, $val['sender']['postcode'], 0, 1);
            //------ Receiver -----
            $pdf->SetFont('thsarabun', 'B', 16);
            $pdf->SetXY(47, 12);
            $pdf->Cell(60, 0, 'ผู้รับ' . $cod1, 0, 1);
            $pdf->SetXY(47, 17);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->Cell(60, 0, 'คุณ ' . $val['receiver']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(47, 26);
            $pdf->MultiCell(55, 15, $val['receiver']['addr'], 0, 1);
            $pdf->setXY(47, 22);
            $pdf->SetFont('thsarabun', '', 13);
            $pdf->Cell(15, 0, 'โทร. ' . $val['receiver']['tel'], 0, 1);
            $pdf->setXY(80, 55);
            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->Cell(15, 0, $val['receiver']['postcode'], 0, 1);
            if ($val['courier_code'] == 'THP' || $val['courier_code'] == 'TP2') {
                $file = fopen(dirname(__FILE__) . "/html_template/a6TableThaipost.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/a6TableThaipost.html"));
            } else {
                $file = fopen(dirname(__FILE__) . "/html_template/a6TableOther.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/a6TableOther.html"));
            }
            $html = mb_eregi_replace("\>\s+\<", "><", $html);
            $html = mb_eregi_replace("{{cod2}}", $cod2, $html);

            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(110, 5);
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->Image('logo.png', 115, $logoPos['a6']['logoSPx'], 26.45, 4.78, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($vender_logo, $logoPos['a6']['logoVDx'], $logoPos['a6']['logoVDy'], 23, 4.5, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
            if ($val['courier_code'] == 'NJV') {
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 110, 65, 30, 30, $this->style2d, 'N');
                $pdf->SetXY(95, 93);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            } else {
                $pdf->write1DBarcode($val['tracking_code'], 'C128', $logoPos['a6']['barcodePosX'], $logoPos['a6']['barcodePosY'], 60, $this->barcode_heigth + 5, 0.4, $this->style1d, 'C');
                $pdf->SetXY(85, $logoPos['a6']['barcodePosY'] + 17);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            }
            if ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $pdf->SetXY(85, $logoPos['a6']['barcodePosY'] + 22);
                $pdf->Cell(60, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->Image('@' . $qr, 120, 45, 20, 20);
            }
            if ($val['courier_code'] == 'CJE') {
                $writecode = '<span style="color:red;font-size:20pt"><b>' . $val['cjcode'] . '</b></span>';
                $pdf->SetXY(120, 50);
                $pdf->writeHTML($writecode, true, false, false, false, '');
            }
            $pdf->SetLineStyle($style1);
            $pdf->SetXY(5, 75);
            $pdf->MultiCell(65, 22, 'หมายเหตุ', 1, 1);
        }


        $output = $pdf->Output('a6.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }


    public function letter4x6($data)
    {
        $style3 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2', 'color' => array(11, 157, 209));
        $style1 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(11, 157, 209));
        $style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));
        $pdf = new PDF('L', PDF_UNIT, array(101.6, 152.4), true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('shippop');
        $pdf->SetTitle('ใบปะหน้าขนาด Letter 4x6');
        $pdf->SetSubject('Letter 4x6');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(1, 1, 1);
        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $dropOff = $this->isDropOff($val['courier_code']) ? "(บริการ Drop off)" : " ";
            $cod1 = $val['cod'] && $val['cod'] == 1 ? " *โทรศัพท์นัดผู้รับก่อนจัดส่ง" : "";
            $cod2 = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " เก็บเงินมูลค่า : " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            $logoPos = $this->getPosition($val['courier_code']);

            $pdf->AddPage();
            $pdf->Image('logo.png', 5, 5, 33, 6, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            // ------Sender--------
            $pdf->SetFont('thsarabun', 'B', 16);
            $pdf->SetXY(5, 12);
            $pdf->Rect(5, 12, 40, 50, 'F', 0, array(211, 211, 211));
            $pdf->Rect(47, 12, 55, 50, 'F', 0, array(245, 245, 245));
            $pdf->Cell(110, 0, 'ผู้ส่ง' . $dropOff, 0, 1);
            $pdf->SetXY(5, 17);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->Cell(60, 0, 'คุณ ' . $val['sender']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 12);
            $pdf->SetXY(5, 26);
            $pdf->MultiCell(40, 15, $val['sender']['addr'], 0, 1);
            $pdf->setXY(5, 22);
            $pdf->SetFont('thsarabun', '', 12);
            $pdf->Cell(15, 0, 'โทร. ' . $val['sender']['tel'], 0, 1);
            $pdf->setXY(25, 55);
            $pdf->SetFont('helvetica', '', 18);
            $pdf->Cell(15, 0, $val['sender']['postcode'], 0, 1);
            //------ Receiver -----
            $pdf->SetFont('thsarabun', 'B', 16);
            $pdf->SetXY(47, 12);
            $pdf->Cell(60, 0, 'ผู้รับ' . $cod1, 0, 1);
            $pdf->SetXY(47, 17);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->Cell(60, 0, 'คุณ ' . $val['receiver']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(47, 26);
            $pdf->MultiCell(55, 15, $val['receiver']['addr'], 0, 1);
            $pdf->setXY(47, 22);
            $pdf->SetFont('thsarabun', '', 13);
            $pdf->Cell(15, 0, 'โทร. ' . $val['receiver']['tel'], 0, 1);
            $pdf->setXY(80, 55);
            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->Cell(15, 0, $val['receiver']['postcode'], 0, 1);
            if ($val['courier_code'] == 'THP' || $val['courier_code'] == 'TP2') {
                $file = fopen(dirname(__FILE__) . "/html_template/letter4x6TableThaipost.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/letter4x6TableThaipost.html"));
            } else {
                $file = fopen(dirname(__FILE__) . "/html_template/letter4x6TableOther.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/letter4x6TableOther.html"));
            }
            $html = mb_eregi_replace("\>\s+\<", "><", $html);
            $html = mb_eregi_replace("{{cod2}}", $cod2, $html);

            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(110, 5);
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->Image('logo.png', 115, $logoPos['a6']['logoSPx'], 26.45, 4.78, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($vender_logo, $logoPos['a6']['logoVDx'], $logoPos['a6']['logoVDy'], 23, 4.5, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
            if ($val['courier_code'] == 'NJV') {
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 110, 65, 30, 30, $this->style2d, 'N');
                $pdf->SetXY(95, 93);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            } else {
                $pdf->write1DBarcode($val['tracking_code'], 'C128', $logoPos['a6']['barcodePosX'], $logoPos['a6']['barcodePosY'], 60, $this->barcode_heigth + 5, 0.4, $this->style1d, 'C');
                $pdf->SetXY(85, $logoPos['a6']['barcodePosY'] + 17);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            }
            if ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $pdf->SetXY(85, $logoPos['a6']['barcodePosY'] + 22);
                $pdf->Cell(60, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->Image('@' . $qr, 120, 50, 20, 20);
            }
            if ($val['courier_code'] == 'CJE') {
                $writecode = '<span style="color:red;font-size:20pt"><b>' . $val['cjcode'] . '</b></span>';
                $pdf->SetXY(120, 50);
                $pdf->writeHTML($writecode, true, false, false, false, '');
            }
            $pdf->SetLineStyle($style1);
            $pdf->SetXY(5, 75);
            $pdf->MultiCell(75, 22, 'หมายเหตุ', 1, 1);
        }


        $output = $pdf->Output('letter4x6.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }

    public function sticker4x6($data)
    {
        // $style3 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2', 'color' => array(11, 157, 209));
        // $style1 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(11, 157, 209));
        $style2 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));
        $pdf = new PDF('P', PDF_UNIT, array(101.6, 152.4), true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('shippop');
        $pdf->SetTitle('ใบปะหน้าขนาด sticker 4x6');
        $pdf->SetSubject('sticker 4x6');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(1, 1, 1);
        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $dropOff = $this->isDropOff($val['courier_code']) ? "(บริการ Drop off)" : " ";
            $cod1 = $val['cod'] && $val['cod'] == 1 ? " *โทรศัพท์นัดผู้รับก่อนจัดส่ง" : "";
            $cod2 = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " เก็บเงินมูลค่า : " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            // $logoPos = $this->getPosition($val['courier_code']);

            $pdf->AddPage();
            $pdf->Rect(2, 2, 97.6, 40, 'D', array('all' => $style2));
            $pdf->Rect(2, 42, 97.6, 106.4, 'D', array('all' => $style2));
            $pdf->Image('logo.png', 58, 50, 40, 8, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($vender_logo, 58, 8, 40, 9, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            // ------Sender--------
            $pdf->SetFont('thsarabun', 'B', 16);
            $pdf->SetXY(4, 4);
            $pdf->Cell(110, 0, 'ผู้ส่ง' . $dropOff, 0, 1);
            $pdf->SetXY(4, 10);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->Cell(60, 0, 'คุณ ' . $val['sender']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 12);
            $pdf->SetXY(10, 16);
            $pdf->MultiCell(45, 15, $val['sender']['addr'], 0, 1);
            $pdf->setXY(4, 38);
            $pdf->SetFont('thsarabun', 'B', 12);
            $pdf->Cell(15, 0, 'โทร. ' . $val['sender']['tel'], 0, 1);
            $pdf->setXY(4, 32);
            $pdf->SetFont('helvetica', '', 18);
            $pdf->Cell(15, 0, $val['sender']['postcode'], 0, 1);
            //------ Receiver -----
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->SetXY(4, 44);
            $pdf->Cell(45, 0, 'ชื่อที่อยู่ผู้รับ' . $cod1, 0, 1);
            $pdf->SetXY(4, 50);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->Cell(60, 0, 'คุณ ' . $val['receiver']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(4, 56);
            $pdf->MultiCell(55, 15, $val['receiver']['addr'], 0, 1);
            $pdf->setXY(4, 71);
            $pdf->SetFont('thsarabun', 'B', 13);
            $pdf->Cell(15, 0, 'โทร. ' . $val['receiver']['tel'], 0, 1);
            // $pdf->setXY(4, 110);
            // $pdf->SetFont('helvetica', 'B', 18);
            // $pdf->Cell(15, 0, $val['receiver']['postcode'], 0, 1);



            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(110, 5);

            if ($val['courier_code'] == 'NJV') {
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 64.5, 70, 30, 30, $this->style2d, 'N');
                $pdf->SetXY(55, 100);
                $pdf->SetFont('thsarabun', 'B', 13);
                $pdf->Cell(48.5, 0, $val['tracking_code'], 0, 1, 'C');
                $pdf->setXY(4, 130);
                $pdf->SetFont('helvetica', 'B', 18);
                $pdf->Cell(15, 0, $val['receiver']['postcode'], 0, 1);
            } else {
                $pdf->write1DBarcode($val['tracking_code'], 'C128', 6, 117, 89.6, $this->barcode_heigth + 5, 0.5, $this->style1d, 'C');
                $pdf->SetXY(6, 133);
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->Cell(89.6, 0, $val['tracking_code'], 0, 1, 'C');
                $pdf->setXY(4, 110);
                $pdf->SetFont('helvetica', 'B', 18);
                $pdf->Cell(15, 0, $val['receiver']['postcode'], 0, 1);
            }
            if ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $pdf->SetXY(50.85, 110);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(48.5, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->Image('@' . $qr, 68.5, 90, 20, 20);
            }
            if ($val['courier_code'] == 'CJE') {
                $writecode = '<span style="color:red;font-size:20pt"><b>' . $val['cjcode'] . '</b></span>';
                $pdf->SetXY(68.5, 80);
                $pdf->writeHTML($writecode, true, false, false, false, '');
            }
            $pdf->SetLineStyle($style2);
            $pdf->SetXY(2, 140);
            $pdf->SetFont('thsarabun', '', 14);
            $pdf->MultiCell(48.85, 8.4, 'หมายเหตุ', 1, 1);
            // $pdf->SetXY();
            $pdf->SetFont('thsarabun', 'B', 14);
            $pdf->MultiCell(48.85, 8.4, $cod2, 1, 'C', false, 1, 50.85, 140, true, 0, false, true, 0, 'B');
        }


        $output = $pdf->Output('sticker4x6.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }

    public function letter($data)
    {
        // $style3 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '2,2', 'color' => array(11, 157, 209));
        // $style1 = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(11, 157, 209));
        $style2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));
        $pdf = new PDF('L', PDF_UNIT, array(231, 107), true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('shippop');
        $pdf->SetTitle('ใบปะหน้าขนาด Letter');
        $pdf->SetSubject('Letter');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(1, 1, 1);
        foreach ($data as  $val) {
            $vender_logo = $this->findLogoName($val['courier_code']);
            $dropOff = $this->isDropOff($val['courier_code']) ? "(บริการ Drop off)" : " ";
            $cod1 = $val['cod'] && $val['cod'] == 1 ? " *โทรศัพท์นัดผู้รับก่อนจัดส่ง" : "";
            $cod2 = $val['cod'] && $val['cod'] == 1 && $val['cod_val'] ? " เก็บเงินมูลค่า : " . $val['cod_val'] . "บาท" : "ไม่เก็บเงินปลายทาง";
            $logoPos = $this->getPosition($val['courier_code']);

            $pdf->AddPage();

            $pdf->Image('logo.png', 10, 5, 33, 6, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            // ------Sender--------
            $pdf->SetFont('thsarabun', 'B', 18);
            $pdf->SetXY(10, 12);

            $pdf->Cell(110, 0, 'ผู้ส่ง' . $dropOff, 0, 1);
            $pdf->SetXY(10, 17);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 16);
            $pdf->Cell(60, 0, 'คุณ ' . $val['sender']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 14);
            $pdf->setXY(10, 22);
            $pdf->SetFont('thsarabun', '', 14);
            $pdf->Cell(15, 0, 'โทร. ' . $val['sender']['tel'], 0, 1);
            $pdf->SetXY(10, 26);
            $pdf->MultiCell(75, 0, $val['sender']['addr'], 0, 1);
            $pdf->setXY(10, $pdf->GetY());
            $pdf->SetFont('helvetica', '', 18);
            $pdf->Cell(15, 0, $val['sender']['postcode'], 0, 1);
            //------ Receiver -----
            $pdf->SetFont('thsarabun', 'B', 22);
            $pdf->SetXY(60, 50);
            $pdf->Cell(60, 0, 'ชื่อที่อยู่ผู้รับ' . $cod1, 0, 1);
            $pdf->SetXY(60, 57);
            // $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('thsarabun', 'B', 20);
            $pdf->Cell(60, 0, 'คุณ ' . $val['receiver']['name'], 0, 1);
            $pdf->SetFont('thsarabun', '', 18);
            $pdf->SetXY(60, 68);
            $pdf->MultiCell(100, 20, $val['receiver']['addr'], 0, 1);
            $pdf->setXY(60, 63);
            $pdf->SetFont('thsarabun', '', 18);
            $pdf->Cell(15, 0, 'โทร. ' . $val['receiver']['tel'], 0, 1);
            $pdf->setXY(60, 93);
            $pdf->SetFont('helvetica', '', 18);
            $pdf->SetLineStyle($style2);
            $pdf->Cell(9, 9, $val['receiver']['postcode'][0], 1, 1, 'C');
            $pdf->setXY(72, 93);
            $pdf->Cell(9, 9, $val['receiver']['postcode'][1], 1, 1, 'C');
            $pdf->setXY(84, 93);
            $pdf->Cell(9, 9, $val['receiver']['postcode'][2], 1, 1, 'C');
            $pdf->setXY(96, 93);
            $pdf->Cell(9, 9, $val['receiver']['postcode'][3], 1, 1, 'C');
            $pdf->setXY(108, 93);
            $pdf->Cell(9, 9, $val['receiver']['postcode'][4], 1, 1, 'C');
            // $pdf->SetFont('helvetica', '', 18);
            // $pdf->Cell(15, 0, $val['receiver']['postcode'], 0, 1);

            if ($val['courier_code'] == 'THP' || $val['courier_code'] == 'TP2') {
                $file = fopen(dirname(__FILE__) . "/html_template/letterTableThaipost.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/letterTableThaipost.html"));
            } else {
                $file = fopen(dirname(__FILE__) . "/html_template/letterTableOther.html", "r");
                $html = fread($file, filesize(dirname(__FILE__) . "/html_template/letterTableOther.html"));
            }
            $html = mb_eregi_replace("\>\s+\<", "><", $html);
            $html = mb_eregi_replace("{{cod2}}", $cod2, $html);

            $pdf->SetFont('thsarabun', '', 13);
            $pdf->SetXY(160, 5);
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->Image('logo.png', 179, $logoPos['a6']['logoSPx'], 26.45, 4.78, 'PNG', 'www.shippop.com', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($vender_logo, 179, $logoPos['a6']['logoVDy'], 25, 5.5, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
            if ($val['courier_code'] == 'NJV') {
                $pdf->write2DBarcode($val['tracking_code'], 'QRCODE,L', 180, 65, 30, 30, $this->style2d, 'N');
                $pdf->SetXY(165, 93);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            } else {
                $pdf->write1DBarcode($val['tracking_code'], 'C128', $logoPos['a6']['barcodePosX'] + 80, $logoPos['a6']['barcodePosY'], 60, $this->barcode_heigth + 5, 0.4, $this->style1d, 'C');
                $pdf->SetXY(165, $logoPos['a6']['barcodePosY'] + 17);
                $pdf->Cell(60, 0, $val['tracking_code'], 0, 1, 'C');
            }
            if ($val['courier_code'] == 'SCG' || $val['courier_code'] == 'SCGF' || $val['courier_code'] == 'SCGC') {
                $pdf->SetXY(165, $logoPos['a6']['barcodePosY'] + 22);
                $pdf->Cell(60, 0, 'Master Code: ' . $val['master_code'], 0, 1, 'C');
                $qr = $this->getimageTemp($val['qr_url']);
                $pdf->Image('@' . $qr, 10, 50, 20, 20);
            }
            if ($val['courier_code'] == 'CJE') {
                $writecode = '<span style="color:red;font-size:20pt"><b>' . $val['cjcode'] . '</b></span>';
                $pdf->SetXY(190, 50);
                $pdf->writeHTML($writecode, true, false, false, false, '');
            }
        }


        $output = $pdf->Output('a4.pdf', 'E');
        $arr = explode('pdf"', $output);


        return $arr[2];
    }
}


//============================================================+
// END OF FILE
