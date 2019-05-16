<?php


class PDF extends TCPDF
{
    public $FONT_NAME = "thsarabun";
    public $FONT_SIZE = 13;
    public $CELL_H = 5;

    function __construct($orientation = "P", $unit = "mm", $format = "A4", $unicode = true, $encoding = "UTF8", $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        // remove default header/footer
//        $this->setPrintHeader(false);
//        $this->setPrintFooter(false);

        // add font TH Sarabun
        $this->AddFont($this->FONT_NAME, "", "thsarabun.php");
        $this->AddFont($this->FONT_NAME, "b", "thsarabunb.php");
        $this->AddFont($this->FONT_NAME, "i", "thsarabuni.php");
        $this->AddFont($this->FONT_NAME, "bi", "thsarabunbi.php");

        // set font
        $this->setCellHeightRatio(1);
        $this->SetFont($this->FONT_NAME, '', $this->FONT_SIZE);
        $this->setHeaderFont(array($this->FONT_NAME, '', $this->FONT_SIZE));
        $this->setFooterFont(array($this->FONT_NAME, '', $this->FONT_SIZE));

        // set margin
        $this->lMargin = 5;
        $this->rMargin = 5;
        $this->tMargin = 23;
    }

    public function getLMargin()
    {
        return $this->lMargin;
    }
    public function Footer(){
        $this->setY(-5.2);
        $this->SetFont('helvetica', 'I', 6);
        $this->Cell(0,8,'www.shippop.com',0,0);
        $this->Image('logo.png', 70, 78, 8.73, 1.6, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
    }
    public function Header(){
        // $this->setY(-5.2);
        $this->SetFont('helvetica', 'I', 6);
        $this->Cell(5,0,'www.shippop.com',0,0);
        $this->Image('logo.png', 70, 1, 8.73, 1.6, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
    }
}