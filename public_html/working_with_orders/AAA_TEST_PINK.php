<?php


# !!!!!!!!!!!!!!!!!!!!!!!!!!======================================================
# Генерирует pdf файл содержащий информацию по договору заказа и сохраняет его в бд
# ======================================================





session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


function convert($name){
    $rusMonthNames = [
        'cloth' => 'Ткань',
        'cornices' => 'Карниз',
        'blinds' => 'Жалюзи',
        'furniture' => 'Фурнитура',
        'services' => 'Услуга',
        'sewing' => 'Пошив',
        'modification' => 'Модификация'
    ];

    return $rusMonthNames[$name];
}


require_once '../connect_to_database.php';


$id_pink_order = $_GET['id'];


// конкретная инфа о страницах
$info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order'");
// общая сумма
$order_sum = 0;
while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
    $order_sum += $info_pink_order_while['quantity'] * $info_pink_order_while['price'];
}



// общая инфа о страницах
$info_pink_order_new = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'");
$info_pink_order_new = mysqli_fetch_assoc($info_pink_order_new);


// предоплата
$prepayment = $info_pink_order_new['prepayment'];


// доплата
$surcharge = $order_sum - $prepayment;







$sql_2 = "SELECT * FROM `orders_main_info` WHERE `orders_main_info`.`id_pink_order`='$id_pink_order'";
$select_2 = mysqli_query($connect, $sql_2);
$select_2 = mysqli_fetch_assoc($select_2);


$main_info = array();
$main_info[] = $select_2['id_pink_order'];
$main_info[] = $select_2['customer_name'];
$main_info[] = $select_2['customer_phone'];

// достаем данные исполнителя
$user_id = $select_2['executor_id'];;
$sql_executor = "SELECT `name` FROM `users` WHERE `id` = '$user_id'";
$select_executor = mysqli_query($connect, $sql_executor);
$select_executor = mysqli_fetch_assoc($select_executor);

$main_info[] = $select_executor['name'];
$main_info[] = $select_2['salon'];

// достаем данные исполнителя
$salon_name = $select_2['salon'];;
$sql_salon = "SELECT `phone` FROM `salons` WHERE `name` = '$salon_name'";
$select_salon = mysqli_query($connect, $sql_salon);
$select_salon = mysqli_fetch_assoc($select_salon);

$main_info[] = $select_salon['phone'];
$main_info[] = $select_2['prepayment'];
$main_info[] = $select_2['address_additional'];
$main_info[] = $_SESSION['phone'];







require_once("../for_pdf_gener/fpdf184/fpdf.php");


//$textColour = array( 0, 0, 0 );
//$headerColour = array( 100, 100, 100 );
//$tableHeaderTopTextColour = array( 255, 255, 255 );
//$tableHeaderTopFillColour = array( 125, 152, 179 );
//$tableHeaderTopProductTextColour = array( 0, 0, 0 );
//$tableHeaderTopProductFillColour = array( 143, 173, 204 );
//$tableHeaderLeftTextColour = array( 99, 42, 57 );
//$tableHeaderLeftFillColour = array( 184, 207, 229 );
//$tableBorderColour = array( 50, 50, 50 );
//$tableRowFillColour = array( 213, 170, 170 );
//$reportName = "2009 Widget Sales Report";
//$reportNameYPos = 160;
//$logoFile = "widget-company-logo.png";
//$logoXPos = 50;
//$logoYPos = 108;
//$logoWidth = 110;
//$columnLabels = array( "Q1", "Q2", "Q3", "Q4" );
//$rowLabels = array( "SupaWidget", "WonderWidget", "MegaWidget", "HyperWidget" );
//$chartXPos = 20;
//$chartYPos = 250;
//$chartWidth = 160;
//$chartHeight = 80;
//$chartXLabel = "Product";
//$chartYLabel = "2009 Sales";
//$chartYStep = 20000;


//$chartColours = array(
//    array( 255, 100, 100 ),
//    array( 100, 255, 100 ),
//    array( 100, 100, 255 ),
//    array( 255, 255, 100 ),
//);
//
//$data = array(
//    array( 9940, 10100, 9490, 11730 ),
//    array( 19310, 21140, 20560, 22590 ),
//    array( 25110, 26260, 25210, 28370 ),
//    array( 27650, 24550, 30040, 31980 ),
//);

// Конец конфигурации




// подключаем шрифты
define('FPDF_FONTPATH', "../for_pdf_gener/fpdf184/font/");



/**
Создаем титульную страницу
 **/

$pdf = new FPDF( 'P', 'mm', 'A4' );



// добавляем шрифт ариал
$pdf->AddFont('Arial','','arial.php');



//// первая страница
//$pdf->AddPage();
//// добавляем шрифт ариал
//$pdf->AddFont('Arial','','arial.php');
//// устанавливаем шрифт Ариал
//$pdf->SetFont('Arial');
//
//
////$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
//$pdf->SetFont( 'Arial', '', 20 );
//$pdf->Write( 19, "2009 Was A Good Year" );
//
//// вторая страница
//$pdf->AddPage();
//
//
////$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
////$pdf->SetFont( 'Arial', '', 17 );
////$pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );
//
////$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
//$pdf->SetFont( 'Arial', '', 20 );
//$pdf->Write( 19, "2009 Was A Good Year" );
//
//$pdf->Ln( 16 );
//$pdf->SetFont( 'Arial', '', 12 );
//$pdf->Write( 6, "LEON the economic downturn, WidgetCo had a strong year. Sales of the HyperWidget in particular exceeded expectations. The fourth quarter was generally the best performing; this was most likely due to our increased ad spend in Q3." );
//$pdf->Ln( 12 );
//$pdf->Write( 6, iconv('utf-8', 'windows-1251',"Коммерческое предложение"));  # СЮДА НАХУЙ
//
//


// 3 страница

$pdf->AddPage();

$pdf->Image("../assets/img/pink_img/top.jpg",6,6, 20, 20);
$pdf->Cell(30); // выводим пустую ячейку, ширина которой 30
$pdf->SetFont( 'Arial', '', 18);
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Материя времени"),0,0,'L',0); // выводим название компании
$pdf->SetFont( 'Arial', '', 12);


//$pdf->Cell(70);
//$pdf->SetFillColor(187,189,189);  // задаем цвет заливки следующих ячеек (R,G,B)
//$pdf->Cell(50,4, iconv('utf-8', 'windows-1251',"Договор"),0,0,'C',1); // выводим наименование компании


$pdf->ln(); // переходим на следующую строку
$pdf->Ln( 5 );
$pdf->Cell(35);
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Салон: Коптевский бульвар, 16к1, Москва, Россия,"),0,30,'L',0); // выводим адрес компании
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Телефон из БД (89250038432)"),0,10,'L',0); // выводим телефон компании
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"www.materia-vremeny.ru"),0,10,'L',0); // выводим адрес сайта компании


$pdf->Ln( 10 );
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Договор-заказ № " . $id_pink_order),0,10,'L',0); // выводим телефон компании
$pdf->Cell(70);
$customer_name = "Заказчик: " . $info_pink_order_new['customer_name'];
$pdf->Cell(40, 0, iconv('utf-8', 'windows-1251', $customer_name),0,10,'L',0); // выводим телефон компании

$pdf->Ln( 10 );
$data =  "Дата: " . date('Y-m-d');
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251', $data), 0, 10, 'L', 0); // выводим телефон компании
$pdf->Cell(70);
$telephone = "Телефон: " . $info_pink_order_new['customer_phone'];
$pdf->Cell(40, 0, iconv('utf-8', 'windows-1251',$telephone),0,10,'L',0); // выводим телефон компании

$pdf->Ln( 10 );
$name_ispol = "Исполнитель " . $_SESSION['name'];
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',$name_ispol),0,10,'L',0); // выводим телефон компании
$pdf->Cell(70);
$address_additional = "Адрес: " . $info_pink_order_new['address_additional'];
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',$address_additional),0,10,'L',0); // выводим телефон компании


// ---------------------------------------


$pdf->Ln( 20 );
$pdf->Cell(80);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Расчет стоимости"),0,10,'L',0); // выводим телефон компании


//$pdf->Cell(10);
//$pdf->Cell(30,4, iconv('utf-8', 'windows-1251',"Россия") ,1,0,'C');
//$pdf->Ln();

$pdf->SetFont( 'Arial', '', 9);


$pdf->Ln( 5 );




// Заполнение таблицы
$info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `room` != '--'");
while ($info_pink_order_room_while = mysqli_fetch_assoc($info_pink_order_room)) {
    $room = $info_pink_order_room_while['room'];
    $info_pink_order = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `room` = '$room'";



    //$sql = "SELECT * FROM `description_of_pink_pages` WHERE `description_of_pink_pages`.`id_pink_order`='$id_pink_order'";
    $arr_for_id = array();
    $arr_for_description = array();
    $arr_for_size = array();
    $arr_for_quantity = array();
    $arr_for_price = array();
    if ($result = $connect->query($info_pink_order)) {
        $rowsCount = $result->num_rows; // количество полученных строк
        foreach ($result as $row) {
            $arr_for_id[] = $row['id_paragraph'];
            $arr_for_description[] = mb_substr(convert($row['category']) . "|" . $row['description'], 0 ,45, 'UTF-8') . '.';
            $arr_for_size[] = $row['size'];
            $arr_for_quantity[] = $row['quantity'];
            $arr_for_price[] = $row['price'];
        }
    }

    $pdf->Ln( 6 );
    $pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Комната: " . $room),0,10,'L',0); // выводим телефон компании

    $price_pink_title = "Цена" . "\n" . "за единицу";
    $pdf->Cell(1);
    $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', "№"), 1, 0, 'L', 0);
    $pdf->Cell(80, 10, iconv('utf-8', 'windows-1251', "Наименование"), 1, 0, 'L', 0);
    $pdf->Cell(23, 10, iconv('utf-8', 'windows-1251', "Цвет"), 1, 0, 'L', 0);
    $pdf->Cell(23, 10, iconv('utf-8', 'windows-1251', "Кол-во"), 1, 0, 'L', 0);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(23, 5, iconv('utf-8', 'windows-1251', $price_pink_title), 1, 'L', 0);
    $pdf->SetXY($x + 23, $y);
    $pdf->Cell(25, 10, iconv('utf-8', 'windows-1251', "Сумма в рублях"), 1, 0, 'L', 0);
    $pdf->ln();

    $price_room = 0;
    for ($i = 0; $i < count($arr_for_id); $i++) {
        $pdf->Cell(1);
        $pdf->Cell(7, 5, iconv('utf-8', 'windows-1251', $arr_for_id[$i]), 1, 0, 'L', 0);
        $pdf->Cell(80, 5, iconv('utf-8', 'windows-1251', $arr_for_description[$i]), 1, 0, 'L', 0);
        $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $arr_for_size[$i]), 1, 0, 'L', 0);
        $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $arr_for_quantity[$i] . ' м.п.'), 1, 0, 'L', 0);
        $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $arr_for_price[$i] . ' руб.'), 1, 0, 'L', 0);
        $pdf->Cell(25, 5, iconv('utf-8', 'windows-1251', $arr_for_price[$i] * $arr_for_quantity[$i] . ' руб.'), 1, 0, 'L', 0);
        $price_room += $arr_for_price[$i] * $arr_for_quantity[$i];
        $pdf->ln();
    }
    $pdf->SetFont( 'Arial', '', 12);
    $pdf->Ln( 2 );
    $pdf->Cell(40,2, iconv('utf-8', 'windows-1251',"Стоимость: " . $price_room),0,10,'L',0); // выводим телефон компании
    $pdf->SetFont( 'Arial', '', 9);
}


// услуги
$price_room = 0;
$info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `category` = 'services'");


if($info_pink_order->num_rows > 0){

    $pdf->Ln( 6 );
    $pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Услуги"),0,10,'L',0); // выводим телефон компании

    $price_pink_title = "Цена" . "\n" . "за единицу";
    $pdf->Cell(1);
    $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', "№"), 1, 0, 'C', 0);
    $pdf->Cell(80, 10, iconv('utf-8', 'windows-1251', "Наименование"), 1, 0, 'C', 0);
    $pdf->Cell(23, 10, iconv('utf-8', 'windows-1251', "Кол-во"), 1, 0, 'C', 0);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(23, 5, iconv('utf-8', 'windows-1251', $price_pink_title), 1, 'C', 0);
    $pdf->SetXY($x + 23, $y);
    $pdf->Cell(25, 10, iconv('utf-8', 'windows-1251', "Сумма в рублях"), 1, 0, 'C', 0);
    $pdf->ln();



    while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
        $pdf->Cell(1);
        $pdf->Cell(7, 5, iconv('utf-8', 'windows-1251', $info_pink_order_while['id_paragraph']), 1, 0, 'L', 0);
        $pdf->Cell(80, 5, iconv('utf-8', 'windows-1251', $info_pink_order_while['description']), 1, 0, 'L', 0);
        $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $info_pink_order_while['quantity']), 1, 0, 'L', 0);
        $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $info_pink_order_while['price'] . " руб."), 1, 0, 'L', 0);
        $pdf->Cell(25, 5, iconv('utf-8', 'windows-1251', $info_pink_order_while['quantity'] * $info_pink_order_while['price'] . " руб."), 1, 0, 'C', 0);
        $price_room += $info_pink_order_while['quantity'] * $info_pink_order_while['price'];
        $pdf->ln();
    }
    $pdf->SetFont( 'Arial', '', 12);
    $pdf->Ln( 2 );
    $pdf->Cell(40,2, iconv('utf-8', 'windows-1251',"Стоимость: " . $price_room),0,10,'L',0); // выводим телефон компании
    $pdf->SetFont( 'Arial', '', 9);

}





//    $pdf->Cell(1);
//    $pdf->Cell(7, 15, iconv('utf-8', 'windows-1251', $arr_for_id[$i]), 1, 0, 'C', 0);
//    $x = $pdf->GetX();
//    $y = $pdf->GetY();
//    $pdf->MultiCell(80, 5, iconv('utf-8', 'windows-1251', $arr_for_description[$i]), 1, 'C', 0);
//    $pdf->SetXY($x + 80, $y);
//    $pdf->Cell(23, 15, iconv('utf-8', 'windows-1251', $arr_for_size[$i]), 1, 0, 'C', 0);
//    $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $arr_for_quantity[$i]), 1, 0, 'C', 0);
//    $pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', $arr_for_price[$i]), 1, 0, 'C', 0);
//    $pdf->Cell(25, 5, iconv('utf-8', 'windows-1251', $arr_for_price[$i] * $arr_for_quantity[$i]), 1, 0, 'C', 0);
//    $pdf->ln();

//$pdf->Cell(1);
//$pdf->Cell(7, 5, iconv('utf-8', 'windows-1251', "1"), 1, 0, 'C', 0);
//$pdf->Cell(80, 5, iconv('utf-8', 'windows-1251', "бульвар"), 1, 0, 'C', 0);
//$pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', "Коптевский"), 1, 0, 'C', 0);
//$pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', "Москва"), 1, 0, 'C', 0);
//$pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', "Россия"), 1, 0, 'C', 0);
//$pdf->Cell(25, 5, iconv('utf-8', 'windows-1251', "Россия"), 1, 0, 'C', 0);
//$pdf->ln();
//
//$pdf->Cell(1);
//$pdf->Cell(7, 5, iconv('utf-8', 'windows-1251', "2"), 1, 0, 'C', 0);
//$pdf->Cell(80, 5, iconv('utf-8', 'windows-1251', "бульвар"), 1, 0, 'C', 0);
//$pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', "Коптевский"), 1, 0, 'C', 0);
//$pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', "Москва"), 1, 0, 'C', 0);
//$pdf->Cell(23, 5, iconv('utf-8', 'windows-1251', "Россия"), 1, 0, 'C', 0);
//$pdf->Cell(25, 5, iconv('utf-8', 'windows-1251', "Россия"), 1, 0, 'C', 0);
//$pdf->ln();



$pdf->SetFont( 'Arial', '', 14);
$pdf->Ln( 10 );
$pdf->Cell(135);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"ИТОГО " . $order_sum  . " руб."),0,10,'L',0); // выводим телефон компании


$pdf->SetFont( 'Arial', '', 10);
$pdf->Ln( 10 );
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Предоплата " . $prepayment . " руб."),0,10,'L',0); // выводим телефон компании
$pdf->Cell(120);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Доплата " . $surcharge  . " руб."),0,10,'L',0); // выводим телефон компании


$pdf->SetFont( 'Arial', '', 12);
$pdf->Ln( 10 );
$pdf->Cell(80);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Подписи"),0,10,'L',0); // выводим телефон компании


$pdf->SetFont( 'Arial', '', 10);
$pdf->Ln( 10 );
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Исполнитель________________"),0,10,'L',0); // выводим телефон компании
$pdf->Cell(120);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Заказчик________________"),0,10,'L',0); // выводим телефон компании




/***
Выводим PDF
 ***/

#$output = $pdf->Output( "assets/pdf_file/report.pdf", "I" ); // просмотр
$output = $pdf->Output( "../assets/pdf_file/Pdf_file_for_"  . $id_pink_order . ".pdf", "F" );  // сохранение


if(@fopen("../assets/pdf_file/Pdf_file_for_"  . $id_pink_order . ".pdf", "r")) {
        ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">

        <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
        <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">

        <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
        <script src="../assets/script/app.js" defer></script>
    </head>
    <body>
    <div class="find_info">
        <div class="body_result">
            <div class="body_result_title">Файл успешно создан</div>
            <a href="pink_all_elements_of_the_mutable.php?id_pink_order=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>
    </div>

    </body>
    </html>
    <?php
    $href_pdf = "Pdf_file_for_"  . $id_pink_order . ".pdf";
    mysqli_query($connect, "UPDATE `orders_main_info` SET `pink_image` = '$href_pdf', `total_cost` = '$order_sum', `surcharge` = '$surcharge' WHERE `id_pink_order` = '$id_pink_order'");
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">

        <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
        <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">

        <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
        <script src="../assets/script/app.js" defer></script>
    </head>
    <body>
    <div class="find_info">
        <div class="body_result">
            <div class="body_result_title">Ошибка создания файла</div>
            <a href="pink_all_elements_of_the_mutable.php?id_pink_order=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>
    </div>
    </body>
    </html>
    <?php
}

#file_put_contents("assets/pdf_file/file.pdf", $output);




?>