<?php
include 'tcpdf/tcpdf.php';
include 'pdf/pdf.php';
include 'printing.php';

$data = array(
    array(
        'tracking_code' => 'EY337122195TH',
        'qr_url' => 'https://www.mhooeng.com/assets/images/qr-shippop-scg.jpg?v=1557739167',
        'master_code' => '110-01-88',
        'courier_code' => 'SCG',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'EY337122195TH',
        'courier_code' => 'NJV',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'SP050287118',
        'courier_code' => 'FLS',
        'sender' => array(
            'name' => 'ตาล จิรกานต์ ',
            'tel' => '081544470',
            'addr' => 'ตาล จิรกานต์ 178/109 ถนน ประชาสโมสร ตำบลในเมือง อำเภอเมือง ขอนแก่น 40000',
            'postcode' => '40000'

        ), 'receiver' => array(
            'name' => 'Nize Seasonings',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'SP050287120',
        'courier_code' => 'LLM',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'EY337122195TH',
        'courier_code' => 'CJE',
        'cjcode'=>'A1PNc',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'EY337122195TH',
        'courier_code' => 'THP',
        'cod' => '1',
        'cod_val' => '1,000.00',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'SP050287118',
        'courier_code' => 'FLS',
        'sender' => array(
            'name' => 'ตาล จิรกานต์ ',
            'tel' => '081544470',
            'addr' => 'ตาล จิรกานต์ 178/109 ถนน ประชาสโมสร ตำบลในเมือง อำเภอเมือง ขอนแก่น 40000',
            'postcode' => '40000'

        ), 'receiver' => array(
            'name' => 'Nize Seasonings',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'SP050287120',
        'courier_code' => 'LLM',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'EY337122195TH',
        'courier_code' => 'NJV',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => ' 9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์ 9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'EY337122195TH',
        'courier_code' => 'THP',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => '9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน -ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
    array(
        'tracking_code' => 'EY337122195TH',
        'courier_code' => 'TP2',
        'cod' => '1',
        'cod_val' => '100.00',
        'sender' => array(
            'name' => 'วิทวัส  คำภักดี',
            'tel' => '0879873789',
            'addr' => ' 9/9 ตำบลร่องคำ อำเภอร่องคำ จังหวัด กาฬสินธุ์',
            'postcode' => '46210'

        ), 'receiver' => array(
            'name' => 'ที่อยู่ผู้รับ  พัสดุEMS',
            'tel' => '0991565055',
            'addr' => '16/53 ถนนบางขุนเทียน-ชายทะเล ซ.เทียนทะเล 20 แสมดำ บางขุนเทียน กทม',
            'postcode' => '10150'

        )
    ),
);
$pdfdata = new Printing;
$size = $_GET['size'];

// if( method_exists( 'Printing', $size ) ){

// }
// $pdfdata->$size( $data );

switch ($size) {
    case 'a4':
        $dataOut = base64_decode($pdfdata->a4($data));
        break;
    case 'a5':
        $dataOut = base64_decode($pdfdata->a5($data));
        break;
    case 'a6':
        $dataOut = base64_decode($pdfdata->a6($data));
        break;
    case 'sticker':
        $dataOut = base64_decode($pdfdata->sticker8x8($data));
        break;
    case 'letter4x6':
        $dataOut = base64_decode($pdfdata->letter4x6($data));
        break;
    case 'letter':
        $dataOut = base64_decode($pdfdata->letter($data));
        break;
    case 'sticker4x6':
        $dataOut = base64_decode($pdfdata->sticker4x6($data));
        break;
}
// echo $pdfdata->getimageTemp('https://www.mhooeng.com/assets/images/qr-shippop-scg.jpg?v=1557739167');
// $dataOut = base64_decode($pdfdata->a6($data));
header('Content-Type: application/pdf');
// // echo $pdfdata->sticker8x8($data);
echo $dataOut;
// echo json_encode(array("status"=>"success",'pdf'=>$pdfdata->sticker8x8($data)));
