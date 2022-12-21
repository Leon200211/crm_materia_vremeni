<?php


# !!!!!!!!!!!!!!!!!!!!!!!!!!======================================================
# Генерирует pdf файл для рекламации и отправляет данные на почту
# ======================================================





session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer')){
    echo "Доступ запрещен";
    die;
}


require_once '../../connect_to_database.php';


$id_pink_order = $_POST['id_order'];



if(!empty($_POST['name'])){
    $name = $_POST['name'];
}else{
    $name = "";
    header("Location: create_complaint.php?id_order=$id_pink_order&error=Заполните все обязательные поля!");
}
if(!empty($_POST['room'])){
    $room = $_POST['room'];
}else{
    $room = "";
    header("Location: create_complaint.php?id_order=$id_pink_order&error=Заполните все обязательные поля!");
}



$text = $_POST['text'];




$sql_2 = "SELECT `date_create` FROM `orders_date` WHERE `orders_date`.`id_order`='$id_pink_order'";
$select_2 = mysqli_query($connect, $sql_2);
$select_2 = mysqli_fetch_assoc($select_2);
$date_create_order = $select_2['date_create'];

$sql_3 = "SELECT `executor_id` FROM `orders_main_info` WHERE `orders_main_info`.`id_pink_order`='$id_pink_order'";
$select_3 = mysqli_query($connect, $sql_3);
$select_3 = mysqli_fetch_assoc($select_3);
// достаем данные исполнителя
$user_id = $select_3['executor_id'];;
$sql_executor = "SELECT `name` FROM `users` WHERE `id` = '$user_id'";
$select_executor = mysqli_query($connect, $sql_executor);
$select_executor = mysqli_fetch_assoc($select_executor);
$executor_name = $select_executor['name'];





require_once("../../for_pdf_gener/fpdf184/fpdf.php");



// подключаем шрифты
define('FPDF_FONTPATH', "../../for_pdf_gener/fpdf184/font/");



/**
Создаем титульную страницу
 **/

$pdf = new FPDF( 'P', 'mm', 'A4' );



// добавляем шрифт ариал
$pdf->AddFont('Arial','','arial.php');
$pdf->AddPage();
$pdf->Image("../../assets/img/pink_img/price_top_new.png",6,6, 20, 20);
$pdf->Cell(30); // выводим пустую ячейку, ширина которой 30
$pdf->SetFont( 'Arial', '', 18);
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Материя времени"),0,0,'L',0); // выводим название компании
$pdf->SetFont( 'Arial', '', 12);

$pdf->ln(); // переходим на следующую строку
$pdf->Ln( 5 );
$pdf->Cell(35);
// дата создания
$today = date("d-m-Y");
$designer_date = date("d-m-Y");
$designer_date = date_create($designer_date);
$designer_date = date_format($designer_date,"d-m-Y");

$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Рекламация по пошиву от " . $designer_date),0,30,'L',0); // выводим адрес компании



// ---------------------------------------

$pdf->Ln( 20 );
$pdf->Cell(1);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Заказ №" . $id_pink_order . "/" . $date_create_order),0,10,'L',0); // выводим телефон компании

$pdf->Ln( 10 );
$pdf->Cell(1);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Комната: " . $room),0,10,'L',0); // выводим телефон компании

$pdf->Ln( 10 );
$pdf->Cell(1);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Изделие: " . $name),0,10,'L',0); // выводим телефон компании




// вывод описания брака
$pdf->Ln( 10 );
$pdf->Cell(1);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251', "Описание брака:"),0,10,'L',0);

$new_font_side = "";
$len_text = strlen($text);

$flag = 0;
if ($len_text > 120) {
    $i = 0;
    while ($i < $len_text) {
        $b = 120;
        if ($i + 120 < strlen($text)) {
            while ($text[$i + $b] != " ") {
                $b -= 1;
                if ($b < 0) {
                    $flag = 1;
                    break;
                }
            }
        }
        if ($flag == 0) {
            if ($i - (120 - $b) < 0) {
                $new_font_side = substr($text, $i, $b);

                $pdf->Ln( 4);
                $pdf->Cell(1);
                $pdf->Cell(40,0, iconv('utf-8', 'windows-1251', $new_font_side),0,10,'L',0);

                $i = $b;
            } else {
                $new_font_side = substr($text, $i, $b);

                $pdf->Ln( 4 );
                $pdf->Cell(1);
                $pdf->Cell(40,0, iconv('utf-8', 'windows-1251', $new_font_side),0,10,'L',0);

                $i += $b;
            }
        } else {
            $new_font_side = substr($text, $i, 80);

            $pdf->Ln( 4 );
            $pdf->Cell(1);
            $pdf->Cell(40,0, iconv('utf-8', 'windows-1251', $new_font_side),0,10,'L',0);

            $flag = 0;
            $i += 120;
        }
    }
} else {
    $pdf->Ln( 10 );
    $pdf->Cell(1);
    $pdf->Cell(40,0, iconv('utf-8', 'windows-1251', $text),0,10,'L',0);
}





$pdf->Ln( 10 );
$pdf->Cell(1);
$filename = '../../assets/complaint/img_complaint_' . $id_pink_order . "_" . $room . "_" . $name . '.png';
if (@fopen($filename, "r")) {
    $pdf->Cell( 40, 40, $pdf->Image($filename, $pdf->GetX(), $pdf->GetY(), 170, 140), 0, 0, 'L', false );

}



/***
Выводим PDF
 ***/

$file = "Pdf_file_complaint_"  . $id_pink_order . "_" . $room . "_" . $name . ".pdf";

#$output = $pdf->Output( "assets/pdf_file/report.pdf", "I" ); // просмотр
$output = $pdf->Output( "../../assets/complaint/pdf/Pdf_file_complaint_"  . $id_pink_order . "_" . $room . "_" . $name . ".pdf", "F" );  // сохранение


if(@fopen("../../assets/complaint/pdf/Pdf_file_complaint_"  . $id_pink_order . "_" . $room . "_" . $name . ".pdf", "r")) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../assets/css/common_styles/style_result.css">
        <link rel="stylesheet" href="../../assets/css/common_styles/common_style.css">
        <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
        <script src="../../assets/script/app.js" defer></script>
    </head>
    <body>
    <div class="find_info">
        <div class="body_result">
            <div class="body_result_title">Файл успешно создан</div>
            <a href="../order_full_info_new.php?id=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>
    </div>
    </body>
    </html>
    <?php
    // добавляем информацию в таблицу complaint_table
    mysqli_query($connect, "INSERT INTO `complaint_table` (`id`, `id_order`, `date_create`, `name`, `room`, `text`, `file`) VALUES (NULL, '$id_pink_order', '$designer_date', '$name', '$room', '$text', '$file')");

    // добавляем уведомление
    $my_id = $_SESSION['id_user'];
    $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `state` = 'admin' OR `id` = '$my_id' OR `state` = 'workshop'");
    while ($select_while = mysqli_fetch_assoc($select)) {
        $id_user = $select_while['id'];
        // проверяем есть ли уведомление по этому заказу?
        $prov_notice = mysqli_query($connect, "SELECT * FROM `notice` WHERE `id_user` = '$id_user' and `id_order` = '$id_pink_order'");
        if(empty(mysqli_fetch_assoc($prov_notice)['id_user'])){ // если еще нет уведомлений по этому заказу
            mysqli_query($connect, "INSERT INTO `notice` (`id`, `id_user`, `id_order`) VALUES (NULL, '$id_user', '$id_pink_order')");
        }
    }


} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../assets/css/common_styles/style_result.css">
        <link rel="stylesheet" href="../../assets/css/common_styles/common_style.css">
        <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
        <script src="../../assets/script/app.js" defer></script>
    </head>
    <body>
    <div class="find_info">
        <div class="body_result">
            <div class="body_result_title">Ошибка создания файла</div>
            <a href="../order_full_info_new.php?id=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>
    </div>
    </body>
    </html>
    <?php
}

#file_put_contents("assets/pdf_file/file.pdf", $output);




?>