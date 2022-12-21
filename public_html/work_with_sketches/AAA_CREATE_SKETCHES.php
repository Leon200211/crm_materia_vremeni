<?php

# !!!!!!!!!!!!!!!!!!!!!!======================================================
# Генерирует pdf файл содержащий информацию по эскизам и сохраняет его в бд
# ======================================================



session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';


$id_pink_order = $_GET['id'];



require_once("../for_pdf_gener/fpdf184/fpdf.php");
require_once("../for_pdf_gener/for_pdf.php");
define('FPDF_FONTPATH', "../for_pdf_gener/fpdf184/font/");



$pdf = new FPDF( 'L', 'mm', 'A4' );
$pdf->AddFont('Arial','','arial.php');



$pink_info = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'");
$pink_info = mysqli_fetch_assoc($pink_info);


$info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `sketches_main` WHERE `id_order` = '$id_pink_order'");
while ($info_pink_order_room_while = mysqli_fetch_assoc($info_pink_order_room)) {
    $room_main = $info_pink_order_room_while['room'];
    $info_pink_order = mysqli_query($connect, "SELECT * FROM `sketches_main` WHERE `id_order` = '$id_pink_order' AND `room` = '$room_main'");
    $info_pink_order_end_page = mysqli_query($connect, "SELECT * FROM `sketches_main` WHERE id_order = '$id_pink_order' AND `room` = '$room_main' ORDER BY page DESC LIMIT 1");
    $info_pink_order_end_page = mysqli_fetch_assoc($info_pink_order_end_page);

    while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
        $room = $info_pink_order_while['room'];
        $page = $info_pink_order_while['page'];

        // новая страница
        $pdf->AddPage();



        // создание шапок страниц
        $pdf->SetXY(0, 0);  // обнуляем координаты

        $pdf->SetMargins(0, 0, 0);
        // Логотип
        $pdf->Image("../assets/img/pink_img/price_top_new.png", 5, 3);

        // Материя времени
        $pdf->SetFont('Arial', '', 28);
        $pdf->Ln(13);
        $pdf->Cell(34);
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "МАТЕРИЯ ВРЕМЕНИ"), 0, 30, 'L', 0);

        // индексы листа
        $pdf->SetFont('Arial', '', 11);
        $pdf->Ln(-8);
        $pdf->Cell(180);
        $pdf->Cell(0, 5, iconv('utf-8', 'windows-1251', "№ листа: " . $page . ' из '. $info_pink_order_end_page['page']), 0, 30, 'L', 0);
        $pdf->Cell(0, 7, iconv('utf-8', 'windows-1251', "Доставка: " . $pink_info['address_additional']), 0, 30, 'L', 0);
        $pdf->Cell(0, 5, iconv('utf-8', 'windows-1251', "Комната: " . $room), 0, 30, 'L', 0);

        // спецификация
        $pdf->Ln(7);
        $pdf->Cell(5);
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "Спецификация на: " . $info_pink_order_while['specification']), 0, 30, 'L', 0);

        // первая строчка информации
        $pdf->Ln(7);
        $pdf->Cell(26);
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "Салон: " . $pink_info['salon']), 0, 30, 'L', 0);
        $pdf->Cell(83);
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "№ заказа: " . $pink_info['id_pink_order']), 0, 30, 'L', 0);
        $pdf->Cell(70);
        // достаем данные исполнителя
        $user_id = $pink_info['executor_id'];;
        $sql_executor = "SELECT `name`, `phone` FROM `users` WHERE `id` = '$user_id'";
        $select_executor = mysqli_query($connect, $sql_executor);
        $select_executor = mysqli_fetch_assoc($select_executor);

        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "Ф.И.О. , моб. Тел. Дизайнера: " . $select_executor['name'] . " | " . $select_executor['phone']), 0, 30, 'L', 0);

        // вторая строчка информации
        $pdf->Ln(7);
        $pdf->Cell(9);
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "Клиент (Ф.И.О.): " . $pink_info['customer_name']), 0, 30, 'L', 0);
        $pdf->Cell(100);
        // достаем дату
        $id_order = $pink_info['id_pink_order'];
        $sql_date = "SELECT `date_create`, `date_end_designer` FROM `orders_date` WHERE `id_order` = '$id_order'";
        $select_date = mysqli_query($connect, $sql_date);
        $select_date = mysqli_fetch_assoc($select_date);
        $date = DateTime::createFromFormat('Y-m-d', $select_date['date_create']);
        $date = $date->format('d-m-Y');
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "Дата принятия заказа: " . $date), 0, 30, 'L', 0);
        $pdf->Cell(70);
        $end_date_designer = DateTime::createFromFormat('Y-m-d', $select_date['date_end_designer']);
        $end_date_designer = $end_date_designer->format('d-m-Y');
        $pdf->Cell(0, 0, iconv('utf-8', 'windows-1251', "Дата готовности: " . $end_date_designer), 0, 30, 'L', 0);


        //создание таблиц
        $pdf->Ln(7);

        if($info_pink_order_while['specification'] == 'портьеры|тюли|подхваты|тп'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(3);
            $pdf->Cell(7, 16, iconv('utf-8', 'windows-1251', "п/п"), 1, 0, 'C', 0);
            $pdf->Cell(20, 16, iconv('utf-8', 'windows-1251', "Изделия"), 1, 0, 'C', 0);

            $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 5.35, iconv('utf-8', 'windows-1251', $count_text), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $assembled_width_text = "Ширина в" . "\n" . "собр." . "\n" . "Виде (см)";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(18, 5.35, iconv('utf-8', 'windows-1251', $assembled_width_text), 1, 'C', 0);
            $pdf->SetXY($x + 18, $y);

            $coefficient_text = "коэф." . "\n" . "Сборки";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(17, 8, iconv('utf-8', 'windows-1251', $coefficient_text), 1, 'C', 0);
            $pdf->SetXY($x + 17, $y);

            $unfolded_width_text = "Ширина" . "\n" . "в развер." . "\n" . "виде" . "\n" . "в см.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(17, 4.01, iconv('utf-8', 'windows-1251', $unfolded_width_text), 1, 'C', 0);
            $pdf->SetXY($x + 17, $y);

            $height_text = "Высота в см.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(17, 8, iconv('utf-8', 'windows-1251', $height_text), 1, 'C', 0);
            $pdf->SetXY($x + 17, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', "Гребешок"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(27, 16, iconv('utf-8', 'windows-1251', "Основная ткань"), 1, 'C', 0);
            $pdf->SetXY($x + 27, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(27, 16, iconv('utf-8', 'windows-1251', "Подклад"), 1, 'C', 0);
            $pdf->SetXY($x + 27, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(27, 16, iconv('utf-8', 'windows-1251', "Отделка"), 1, 'C', 0);
            $pdf->SetXY($x + 27, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(12, 8, iconv('utf-8', 'windows-1251', "Низ в см."), 1, 'C', 0);
            $pdf->SetXY($x + 12, $y);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(12, 8, iconv('utf-8', 'windows-1251', "Бока в см."), 1, 'C', 0);
            $pdf->SetXY($x + 12, $y);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.35, iconv('utf-8', 'windows-1251', "Тех-загиб бок. в см."), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(22, 16, iconv('utf-8', 'windows-1251', "Шт. лента"), 1, 'C', 0);
            $pdf->SetXY($x + 22, $y);

            $pdf->ln();
            $id_sketches_main = $info_pink_order_while['id'];
            $table_info = mysqli_query($connect,"SELECT * FROM `sketches_1` WHERE `id_sketches_main` = '$id_sketches_main'");
            $pdf->SetFont('Arial', '', 8);
            while ($table_info_while = mysqli_fetch_assoc($table_info)) {


                if(mb_strlen($table_info_while['main_cloth']) > 40){
                    $main_cloth = mb_substr($table_info_while['main_cloth'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['main_cloth']) < 2){
                    $main_cloth = str_pad($table_info_while['main_cloth'],  40, " ");
                }else{
                    $main_cloth = str_pad($table_info_while['main_cloth'],  80, " ");
                }
                if(mb_strlen($table_info_while['lining']) > 40){
                    $lining = mb_substr($table_info_while['lining'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['lining']) < 2){
                    $lining = str_pad($table_info_while['lining'],  40, " ");
                } else{
                    $lining = str_pad($table_info_while['lining'],  80, " ");
                }
                if(mb_strlen($table_info_while['finishing']) > 40){
                    $finishing = mb_substr($table_info_while['finishing'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['finishing']) < 2){
                    $finishing = str_pad($table_info_while['finishing'],  40, " ");
                } else{
                    $finishing = str_pad($table_info_while['finishing'],  80, " ");

                }


                $pdf->Cell(3);
                $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', $table_info_while['id_paragraph']), 1, 0, 'C', 0);
                $pdf->Cell(20, 10, iconv('utf-8', 'windows-1251', $table_info_while['vendor_code']), 1, 0, 'L', 0);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(18, 10, iconv('utf-8', 'windows-1251', $table_info_while['assembled_width']), 1, 'L', 0);
                $pdf->SetXY($x + 18, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(17, 10, iconv('utf-8', 'windows-1251', $table_info_while['coefficient']), 1, 'L', 0);
                $pdf->SetXY($x + 17, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(17, 10, iconv('utf-8', 'windows-1251', $table_info_while['unfolded_width']), 1, 'L', 0);
                $pdf->SetXY($x + 17, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(17, 10, iconv('utf-8', 'windows-1251', $table_info_while['height']), 1, 'L', 0);
                $pdf->SetXY($x + 17, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['scallop']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);


                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(27, 5, iconv('utf-8', 'windows-1251', $main_cloth), 1, 'L', 0);
                $pdf->SetXY($x + 27, $y);

                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['m_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(27, 5, iconv('utf-8', 'windows-1251', $lining), 1, 'L', 0);
                $pdf->SetXY($x + 27, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['l_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(27, 5, iconv('utf-8', 'windows-1251', $finishing), 1, 'L', 0);
                $pdf->SetXY($x + 27, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['f_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(12, 10, iconv('utf-8', 'windows-1251', $table_info_while['bottom']), 1, 'L', 0);
                $pdf->SetXY($x + 12, $y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(12, 10, iconv('utf-8', 'windows-1251', $table_info_while['sides']), 1, 'L', 0);
                $pdf->SetXY($x + 12, $y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['bend']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);
                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(22, 10, iconv('utf-8', 'windows-1251', $table_info_while['ribbon']), 1, 'L', 0);
                $pdf->SetXY($x + 22, $y);
                $pdf->SetFont('Arial', '', 8);
                $pdf->ln();
            }

            // добавление эскиза
            // меняем размер картинки на маленький
            $pdf->ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130);
            $pdf->Cell(0, -7, iconv('utf-8', 'windows-1251', "Эскиз: "), 0, 30, 'L', 0);
            $pdf->ln(8);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                $image = new Thumbs("../assets/img/img" . $info_pink_order_while['image']);
                $image->thumb(800, 300);
                $image->save("../assets/img/img_" . $info_pink_order_while['image']);
                $pdf->Image("../assets/img/img_" . $info_pink_order_while['image'], $x+100, $y);
            }

            // доп инфа
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(10);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y-10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Примечания: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['note']));
            $pdf->SetXY($x + 120, $y);

            $pdf->ln(28);
            $pdf->Cell(10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Лицевой стороной считать: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['font_side']));
            $pdf->SetXY($x + 120, $y);
        }
        else if($info_pink_order_while['specification'] == 'римские|франц|австрийск|тп'){
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(8);
            $pdf->Cell(7, 16, iconv('utf-8', 'windows-1251', "п/п"), 1, 0, 'C', 0);
            $pdf->Cell(20, 16, iconv('utf-8', 'windows-1251', "Изделия"), 1, 0, 'C', 0);

            $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 5.35, iconv('utf-8', 'windows-1251', $count_text), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);


            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(18, 5.35, iconv('utf-8', 'windows-1251', 'Ширина по карнизу (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 18, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(23, 8, iconv('utf-8', 'windows-1251', 'Высота изделия (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 23, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', "Гребешок"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Основная ткань"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Подклад"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Отделка"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 8, iconv('utf-8', 'windows-1251', "Кол-во фибер-вых (шт)"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 8, iconv('utf-8', 'windows-1251', "Кол-во барабанов (шт)"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $pdf->ln();
            $pdf->ln();
            $id_sketches_main = $info_pink_order_while['id'];
            $table_info = mysqli_query($connect,"SELECT * FROM `sketches_2` WHERE `id_sketches_main` = '$id_sketches_main'");
            $pdf->SetFont('Arial', '', 8);
            while ($table_info_while = mysqli_fetch_assoc($table_info)) {


                if(mb_strlen($table_info_while['main_cloth']) > 40){
                    $main_cloth = mb_substr($table_info_while['main_cloth'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['main_cloth']) < 2){
                    $main_cloth = str_pad($table_info_while['main_cloth'],  40, " ");
                }else{
                    $main_cloth = str_pad($table_info_while['main_cloth'],  80, " ");
                }
                if(mb_strlen($table_info_while['lining']) > 40){
                    $lining = mb_substr($table_info_while['lining'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['lining']) < 2){
                    $lining = str_pad($table_info_while['lining'],  40, " ");
                } else{
                    $lining = str_pad($table_info_while['lining'],  80, " ");
                }
                if(mb_strlen($table_info_while['finishing']) > 40){
                    $finishing = mb_substr($table_info_while['finishing'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['finishing']) < 2){
                    $finishing = str_pad($table_info_while['finishing'],  40, " ");
                } else{
                    $finishing = str_pad($table_info_while['finishing'],  80, " ");
                }


                $pdf->Cell(8);
                $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', $table_info_while['id_paragraph']), 1, 0, 'C', 0);
                $pdf->Cell(20, 10, iconv('utf-8', 'windows-1251', $table_info_while['vendor_code']), 1, 0, 'L', 0);



                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(18, 10, iconv('utf-8', 'windows-1251', $table_info_while['eaves_width']), 1, 'L', 0);
                $pdf->SetXY($x + 18, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(23, 10, iconv('utf-8', 'windows-1251', $table_info_while['height']), 1, 'L', 0);
                $pdf->SetXY($x + 23, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['scallop']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 5, iconv('utf-8', 'windows-1251', $main_cloth), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['m_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 5, iconv('utf-8', 'windows-1251', $lining), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['l_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 5, iconv('utf-8', 'windows-1251', $finishing), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['f_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 10, iconv('utf-8', 'windows-1251', $table_info_while['count_fib']), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 10, iconv('utf-8', 'windows-1251', $table_info_while['count_drums']), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->ln();
            }


            // добавление эскиза
            // меняем размер картинки на маленький
            $pdf->ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130);
            $pdf->Cell(0, -7, iconv('utf-8', 'windows-1251', "Эскиз: "), 0, 30, 'L', 0);
            $pdf->ln(8);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                $image = new Thumbs("../assets/img/img" . $info_pink_order_while['image']);
                $image->thumb(800, 300);
                $image->save("../assets/img/img_" . $info_pink_order_while['image']);
                $pdf->Image("../assets/img/img_" . $info_pink_order_while['image'], $x+100, $y);
            }

            // доп инфа
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(10);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y-10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Примечания: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['note']));
            $pdf->SetXY($x + 120, $y);

            $pdf->ln(28);
            $pdf->Cell(10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Лицевой стороной считать: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['font_side']));
            $pdf->SetXY($x + 120, $y);


        }
        else if($info_pink_order_while['specification'] == 'покрывала') {

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(8);
            $pdf->Cell(7, 16, iconv('utf-8', 'windows-1251', "п/п"), 1, 0, 'C', 0);
            $pdf->Cell(20, 16, iconv('utf-8', 'windows-1251', "Изделия"), 1, 0, 'C', 0);

            $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 5.35, iconv('utf-8', 'windows-1251', $count_text), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);


            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(18, 5.35, iconv('utf-8', 'windows-1251', 'Ширина габаритная (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 18, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(23, 5.35, iconv('utf-8', 'windows-1251', 'Длина габаритная (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 23, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 16, iconv('utf-8', 'windows-1251', "Стежка"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', "Наим. стежки"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', "Шаг стежки"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(27, 16, iconv('utf-8', 'windows-1251', "Основная ткань"), 1, 'C', 0);
            $pdf->SetXY($x + 27, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(27, 16, iconv('utf-8', 'windows-1251', "Подклад"), 1, 'C', 0);
            $pdf->SetXY($x + 27, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(27, 16, iconv('utf-8', 'windows-1251', "Отделка"), 1, 'C', 0);
            $pdf->SetXY($x + 27, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 4, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 16, iconv('utf-8', 'windows-1251', "Края"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(17, 16, iconv('utf-8', 'windows-1251', "Синтепон"), 1, 'C', 0);
            $pdf->SetXY($x + 17, $y);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', "Радиус или угол"), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $pdf->ln();
            $pdf->ln();
            $id_sketches_main = $info_pink_order_while['id'];
            $table_info = mysqli_query($connect,"SELECT * FROM `sketches_3` WHERE `id_sketches_main` = '$id_sketches_main'");
            $pdf->SetFont('Arial', '', 8);
            while ($table_info_while = mysqli_fetch_assoc($table_info)) {


                if(mb_strlen($table_info_while['main_cloth']) > 40){
                    $main_cloth = mb_substr($table_info_while['main_cloth'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['main_cloth']) < 2){
                    $main_cloth = str_pad($table_info_while['main_cloth'],  40, " ");
                }else{
                    $main_cloth = str_pad($table_info_while['main_cloth'],  80, " ");
                }
                if(mb_strlen($table_info_while['lining']) > 40){
                    $lining = mb_substr($table_info_while['lining'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['lining']) < 2){
                    $lining = str_pad($table_info_while['lining'],  40, " ");
                } else{
                    $lining = str_pad($table_info_while['lining'],  80, " ");
                }
                if(mb_strlen($table_info_while['finishing']) > 40){
                    $finishing = mb_substr($table_info_while['finishing'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['finishing']) < 2){
                    $finishing = str_pad($table_info_while['finishing'],  40, " ");
                } else{
                    $finishing = str_pad($table_info_while['finishing'],  80, " ");
                }


                if(mb_strlen($table_info_while['stitch']) < 10){
                    $stitch = str_pad($table_info_while['stitch'],  17, " ");
                }else{
                    $stitch = $table_info_while['stitch'];
                }
                if(mb_strlen($table_info_while['stitch_name']) < 10){
                    $stitch_name = str_pad($table_info_while['stitch_name'],  17, " ");
                }else{
                    $stitch_name = $table_info_while['stitch_name'];
                }


                $pdf->Cell(8);
                $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', $table_info_while['id_paragraph']), 1, 0, 'C', 0);
                $pdf->Cell(20, 10, iconv('utf-8', 'windows-1251', $table_info_while['vendor_code']), 1, 0, 'L', 0);

                $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(18, 10, iconv('utf-8', 'windows-1251', $table_info_while['width']), 1, 'L', 0);
                $pdf->SetXY($x + 18, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(23, 10, iconv('utf-8', 'windows-1251', $table_info_while['length']), 1, 'L', 0);
                $pdf->SetXY($x + 23, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 5, iconv('utf-8', 'windows-1251', $stitch), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 5, iconv('utf-8', 'windows-1251', $stitch_name), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['stitch_step']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(27, 5, iconv('utf-8', 'windows-1251', $main_cloth), 1, 'L', 0);
                $pdf->SetXY($x + 27, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['m_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(27, 5, iconv('utf-8', 'windows-1251', $lining), 1, 'L', 0);
                $pdf->SetXY($x + 27, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['l_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();



                $pdf->MultiCell(27, 5, iconv('utf-8', 'windows-1251', $finishing), 1, 'L', 0);
                $pdf->SetXY($x + 27, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['f_count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['edges']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(17, 10, iconv('utf-8', 'windows-1251', $table_info_while['centipone']), 1, 'L', 0);
                $pdf->SetXY($x + 17, $y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['corner']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);
                $pdf->ln();
            }

            // добавление эскиза
            // меняем размер картинки на маленький
            $pdf->ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130);
            $pdf->Cell(0, -7, iconv('utf-8', 'windows-1251', "Эскиз: "), 0, 30, 'L', 0);
            $pdf->ln(8);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                $image = new Thumbs("../assets/img/img" . $info_pink_order_while['image']);
                $image->thumb(800, 300);
                $image->save("../assets/img/img_" . $info_pink_order_while['image']);
                $pdf->Image("../assets/img/img_" . $info_pink_order_while['image'], $x+100, $y);
            }

            // доп инфа
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(10);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y-10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Примечания: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['note']));
            $pdf->SetXY($x + 120, $y);

            $pdf->ln(28);
            $pdf->Cell(10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Лицевой стороной считать: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['font_side']));
            $pdf->SetXY($x + 120, $y);



        }
        else if($info_pink_order_while['specification'] == 'подушки|наволочки|валики') {

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(8);
            $pdf->Cell(7, 16, iconv('utf-8', 'windows-1251', "п/п"), 1, 0, 'C', 0);
            $pdf->Cell(25, 16, iconv('utf-8', 'windows-1251', "Изделия"), 1, 0, 'C', 0);

            $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(12, 5.35, iconv('utf-8', 'windows-1251', $count_text), 1, 'C', 0);
            $pdf->SetXY($x + 12, $y);


            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(33, 5.35, iconv('utf-8', 'windows-1251', 'Ширина габаритная/диаметр валика (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 33, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 8, iconv('utf-8', 'windows-1251', 'Длина габаритная (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(40, 16, iconv('utf-8', 'windows-1251', "Основная ткань"), 1, 'C', 0);
            $pdf->SetXY($x + 40, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.33, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(40, 8, iconv('utf-8', 'windows-1251', "Внутрення подушка/чехол"), 1, 'C', 0);
            $pdf->SetXY($x + 40, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.33, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(40, 16, iconv('utf-8', 'windows-1251', "Отделка"), 1, 'C', 0);
            $pdf->SetXY($x + 40, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.33, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $pdf->ln();
            $pdf->ln();
            $pdf->ln();

            $id_sketches_main = $info_pink_order_while['id'];
            $table_info = mysqli_query($connect,"SELECT * FROM `sketches_4` WHERE `id_sketches_main` = '$id_sketches_main'");

            $pdf->SetFont('Arial', '', 8);



            while ($table_info_while = mysqli_fetch_assoc($table_info)) {


                if(mb_strlen($table_info_while['main_cloth']) > 60){
                    $main_cloth = mb_substr($table_info_while['main_cloth'], 0, 60, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['main_cloth']) < 2){
                    $main_cloth = str_pad($table_info_while['main_cloth'],  60, " ");
                }else{
                    $main_cloth = str_pad($table_info_while['main_cloth'],  90, " ");
                }
                if(mb_strlen($table_info_while['pillow']) > 60){
                    $pillow = mb_substr($table_info_while['pillow'], 0, 60, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['pillow']) < 2){
                    $pillow = str_pad($table_info_while['pillow'],  90, " ");
                } else{
                    $pillow = str_pad($table_info_while['pillow'],  90, " ");
                }
                if(mb_strlen($table_info_while['finishing']) > 60){
                    $finishing = mb_substr($table_info_while['finishing'], 0, 60, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['finishing']) < 2){
                    $finishing = str_pad($table_info_while['finishing'],  90, " ");
                } else{
                    $finishing = str_pad($table_info_while['finishing'],  90, " ");
                }



                $pdf->Cell(8);
                $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', $table_info_while['id_paragraph']), 1, 0, 'C', 0);
                $pdf->Cell(25, 10, iconv('utf-8', 'windows-1251', $table_info_while['vendor_code']), 1, 0, 'L', 0);

                $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(12, 10, iconv('utf-8', 'windows-1251', $table_info_while['count']), 1, 'L', 0);
                $pdf->SetXY($x + 12, $y);


                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(33, 10, iconv('utf-8', 'windows-1251', $table_info_while['width']), 1, 'L', 0);
                $pdf->SetXY($x + 33, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 10, iconv('utf-8', 'windows-1251', $table_info_while['length']), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(40, 5, iconv('utf-8', 'windows-1251', $main_cloth), 1, 'L', 0);
                $pdf->SetXY($x + 40, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['m_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(40, 5, iconv('utf-8', 'windows-1251', $pillow), 1, 'L', 0);
                $pdf->SetXY($x + 40, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['p_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(40, 5, iconv('utf-8', 'windows-1251', $finishing), 1, 'L', 0);
                $pdf->SetXY($x + 40, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['f_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);
                $pdf->ln();
            }


            // добавление эскиза
            // меняем размер картинки на маленький
            $pdf->ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130);
            $pdf->Cell(0, -7, iconv('utf-8', 'windows-1251', "Эскиз: "), 0, 30, 'L', 0);
            $pdf->ln(8);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                $image = new Thumbs("../assets/img/img" . $info_pink_order_while['image']);
                $image->thumb(800, 300);
                $image->save("../assets/img/img_" . $info_pink_order_while['image']);
                $pdf->Image("../assets/img/img_" . $info_pink_order_while['image'], $x+100, $y);
            }

            // доп инфа
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(10);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y-10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Примечания: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['note']));
            $pdf->SetXY($x + 120, $y);

            $pdf->ln(28);
            $pdf->Cell(10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Лицевой стороной считать: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['font_side']));
            $pdf->SetXY($x + 120, $y);



        }
        else if($info_pink_order_while['specification'] == 'сваги|джаботы|ламбрикены') {

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(8);
            $pdf->Cell(7, 16, iconv('utf-8', 'windows-1251', "п/п"), 1, 0, 'C', 0);
            $pdf->Cell(20, 16, iconv('utf-8', 'windows-1251', "Изделия"), 1, 0, 'C', 0);

            $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 5.35, iconv('utf-8', 'windows-1251', $count_text), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);


            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(18, 5.35, iconv('utf-8', 'windows-1251', 'Ширина по карнизу (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 18, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', 'Высота в (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', 'Обработка низа'), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 8, iconv('utf-8', 'windows-1251', 'Липучка'), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(15, 8, iconv('utf-8', 'windows-1251', 'Термобандо'), 1, 'C', 0);
            $pdf->SetXY($x + 15, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Основная ткань"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.34, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Подклад"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.34, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Отделка"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.34, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(22, 16, iconv('utf-8', 'windows-1251', "Шт. лента"), 1, 'C', 0);
            $pdf->SetXY($x + 22, $y);

            $pdf->ln();

            $id_sketches_main = $info_pink_order_while['id'];
            $table_info = mysqli_query($connect,"SELECT * FROM `sketches_5` WHERE `id_sketches_main` = '$id_sketches_main'");

            $pdf->SetFont('Arial', '', 8);

            while ($table_info_while = mysqli_fetch_assoc($table_info)) {


                if(mb_strlen($table_info_while['main_cloth']) > 40){
                    $main_cloth = mb_substr($table_info_while['main_cloth'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['main_cloth']) < 2){
                    $main_cloth = str_pad($table_info_while['main_cloth'],  40, " ");
                }else{
                    $main_cloth = str_pad($table_info_while['main_cloth'],  80, " ");
                }
                if(mb_strlen($table_info_while['lining']) > 40){
                    $lining = mb_substr($table_info_while['lining'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['lining']) < 2){
                    $lining = str_pad($table_info_while['lining'],  40, " ");
                } else{
                    $lining = str_pad($table_info_while['lining'],  80, " ");
                }
                if(mb_strlen($table_info_while['finishing']) > 40){
                    $finishing = mb_substr($table_info_while['finishing'], 0, 40, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['finishing']) < 2){
                    $finishing = str_pad($table_info_while['finishing'],  40, " ");
                } else{
                    $finishing = str_pad($table_info_while['finishing'],  80, " ");
                }


                $pdf->Cell(8);
                $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', $table_info_while['id_paragraph']), 1, 0, 'C', 0);
                $pdf->Cell(20, 10, iconv('utf-8', 'windows-1251', $table_info_while['vendor_code']), 1, 0, 'L', 0);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(18, 10, iconv('utf-8', 'windows-1251', $table_info_while['eaves_width']), 1, 'L', 0);
                $pdf->SetXY($x + 18, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['height']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 10, iconv('utf-8', 'windows-1251', $table_info_while['bottom_processing']), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['velcro']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(15, 10, iconv('utf-8', 'windows-1251', $table_info_while['thermobando']), 1, 'L', 0);
                $pdf->SetXY($x + 15, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 5, iconv('utf-8', 'windows-1251', $main_cloth), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['m_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 5, iconv('utf-8', 'windows-1251', $lining), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['l_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 5, iconv('utf-8', 'windows-1251', $finishing), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['f_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(22, 10, iconv('utf-8', 'windows-1251', $table_info_while['ribbon']), 1, 'L', 0);
                $pdf->SetXY($x + 22, $y);
                $pdf->SetFont('Arial', '', 8);

                $pdf->ln();

            }


            // добавление эскиза
            // меняем размер картинки на маленький
            $pdf->ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130);
            $pdf->Cell(0, -7, iconv('utf-8', 'windows-1251', "Эскиз: "), 0, 30, 'L', 0);
            $pdf->ln(8);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                $image = new Thumbs("../assets/img/img" . $info_pink_order_while['image']);
                $image->thumb(800, 300);
                $image->save("../assets/img/img_" . $info_pink_order_while['image']);
                $pdf->Image("../assets/img/img_" . $info_pink_order_while['image'], $x+100, $y);
            }

            // доп инфа
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(10);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y-10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Примечания: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['note']));
            $pdf->SetXY($x + 120, $y);

            $pdf->ln(28);
            $pdf->Cell(10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Лицевой стороной считать: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['font_side']));
            $pdf->SetXY($x + 120, $y);

        }
        else if($info_pink_order_while['specification'] == 'скатерти|салфетки') {

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(8);
            $pdf->Cell(7, 16, iconv('utf-8', 'windows-1251', "п/п"), 1, 0, 'C', 0);
            $pdf->Cell(20, 16, iconv('utf-8', 'windows-1251', "Изделия"), 1, 0, 'C', 0);

            $count_text = "Кол-" . "\n" . "во в" . "\n" . "шт.";
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(10, 5.35, iconv('utf-8', 'windows-1251', $count_text), 1, 'C', 0);
            $pdf->SetXY($x + 10, $y);


            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(33, 5.35, iconv('utf-8', 'windows-1251', 'Ширина габаритная/диаметр валика (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 33, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 8, iconv('utf-8', 'windows-1251', 'Длина габаритная (см)'), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(39, 16, iconv('utf-8', 'windows-1251', "Основная ткань"), 1, 'C', 0);
            $pdf->SetXY($x + 39, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.34, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(39, 16, iconv('utf-8', 'windows-1251', "Подклад"), 1, 'C', 0);
            $pdf->SetXY($x + 39, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.34, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(39, 16, iconv('utf-8', 'windows-1251', "Отделка"), 1, 'C', 0);
            $pdf->SetXY($x + 39, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(13, 5.34, iconv('utf-8', 'windows-1251', "Кол-во в метрах"), 1, 'C', 0);
            $pdf->SetXY($x + 13, $y);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(30, 16, iconv('utf-8', 'windows-1251', "Обработка края"), 1, 'C', 0);
            $pdf->SetXY($x + 30, $y);

            $pdf->ln();

            $id_sketches_main = $info_pink_order_while['id'];
            $table_info = mysqli_query($connect,"SELECT * FROM `sketches_6` WHERE `id_sketches_main` = '$id_sketches_main'");

            $pdf->SetFont('Arial', '', 8);

            while ($table_info_while = mysqli_fetch_assoc($table_info)) {


                if(mb_strlen($table_info_while['main_cloth']) > 60){
                    $main_cloth = mb_substr($table_info_while['main_cloth'], 0, 60, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['main_cloth']) < 2){
                    $main_cloth = str_pad($table_info_while['main_cloth'],  60, " ");
                }else{
                    $main_cloth = str_pad($table_info_while['main_cloth'],  90, " ");
                }
                if(mb_strlen($table_info_while['lining']) > 60){
                    $lining = mb_substr($table_info_while['lining'], 0, 60, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['lining']) < 2){
                    $lining = str_pad($table_info_while['lining'],  60, " ");
                } else{
                    $lining = str_pad($table_info_while['lining'],  90, " ");
                }
                if(mb_strlen($table_info_while['finishing']) > 60){
                    $finishing = mb_substr($table_info_while['finishing'], 0, 60, 'UTF-8') . '.';
                }else if(mb_strlen($table_info_while['finishing']) < 2){
                    $finishing = str_pad($table_info_while['finishing'],  60, " ");
                } else{
                    $finishing = str_pad($table_info_while['finishing'],  90, " ");
                }


                $pdf->Cell(8);
                $pdf->Cell(7, 10, iconv('utf-8', 'windows-1251', $table_info_while['id_paragraph']), 1, 0, 'C', 0);
                $pdf->Cell(20, 10, iconv('utf-8', 'windows-1251', $table_info_while['vendor_code']), 1, 0, 'L', 0);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(10, 10, iconv('utf-8', 'windows-1251', $table_info_while['count']), 1, 'L', 0);
                $pdf->SetXY($x + 10, $y);


                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(33, 10, iconv('utf-8', 'windows-1251', $table_info_while['width']), 1, 'L', 0);
                $pdf->SetXY($x + 33, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 10, iconv('utf-8', 'windows-1251', $table_info_while['length']), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(39, 5, iconv('utf-8', 'windows-1251', $main_cloth), 1, 'L', 0);
                $pdf->SetXY($x + 39, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['m_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(39, 5, iconv('utf-8', 'windows-1251', $lining), 1, 'L', 0);
                $pdf->SetXY($x + 39, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['l_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $pdf->SetFont('Arial', '', 6);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(39, 5, iconv('utf-8', 'windows-1251', $finishing), 1, 'L', 0);
                $pdf->SetXY($x + 39, $y);
                $pdf->SetFont('Arial', '', 8);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(13, 10, iconv('utf-8', 'windows-1251', $table_info_while['f_count']), 1, 'L', 0);
                $pdf->SetXY($x + 13, $y);

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 10, iconv('utf-8', 'windows-1251', $table_info_while['edge']), 1, 'L', 0);
                $pdf->SetXY($x + 30, $y);
                $pdf->ln();

            }


            // добавление эскиза
            // меняем размер картинки на маленький
            $pdf->ln();
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130);
            $pdf->Cell(0, -7, iconv('utf-8', 'windows-1251', "Эскиз: "), 0, 30, 'L', 0);
            $pdf->ln(8);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                $image = new Thumbs("../assets/img/img" . $info_pink_order_while['image']);
                $image->thumb(800, 300);
                $image->save("../assets/img/img_" . $info_pink_order_while['image']);
                $pdf->Image("../assets/img/img_" . $info_pink_order_while['image'], $x+100, $y);
            }

            // доп инфа
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(10);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x, $y-10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Примечания: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['note']));
            $pdf->SetXY($x + 120, $y);

            $pdf->ln(28);
            $pdf->Cell(10);
            $pdf->Cell(0, 8, iconv('utf-8', 'windows-1251', "Лицевой стороной считать: "), 0, 30, 'L', 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(119, 3, iconv('utf-8', 'windows-1251', $info_pink_order_while['font_side']));
            $pdf->SetXY($x + 120, $y);

        }



    }
}





/***
Выводим PDF
 ***/

#$output = $pdf->Output( "assets/price_pdf/price_pdf_" . $id_pink_order . ".pdf", "I" );


$output = $pdf->Output( "../assets/price_pdf/price_pdf_" . $id_pink_order . ".pdf", "F" );


if(@fopen("../assets/price_pdf/price_pdf_" . $id_pink_order . ".pdf", "r")) {
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
            <a href="sketches_room.php?id_pink_order=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>
    </div>
    </body>
    </html>
    <?php
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
            <a href="sketches_room.php?id_pink_order=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>
    </div>
    </body>
    </html>
    <?php
}



?>