<?php


#============================================================
# Файл для создания акта приемки\сдачи
#============================================================




session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';


$id_pink_order = $_GET['id'];




// общая инфа о страницах
$info_pink_order_new = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'");
$info_pink_order_new = mysqli_fetch_assoc($info_pink_order_new);


// предоплата
$prepayment = $info_pink_order_new['prepayment'];

$customer = $info_pink_order_new['customer_name'];
$order_sum = $info_pink_order_new['total_cost'];

// достаем данные исполнителя
$user_id = $info_pink_order_new['executor_id'];;
$sql_executor = "SELECT `name` FROM `users` WHERE `id` = '$user_id'";
$select_executor = mysqli_query($connect, $sql_executor);
$select_executor = mysqli_fetch_assoc($select_executor);
$executor = $select_executor['name'];


// достаем данные исполнителя
$salon_name = $info_pink_order_new['salon'];;
$sql_salon = "SELECT `phone` FROM `salons` WHERE `name` = '$salon_name'";
$select_salon = mysqli_query($connect, $sql_salon);
$select_salon = mysqli_fetch_assoc($select_salon);









require_once("../for_pdf_gener/fpdf184/fpdf.php");



// подключаем шрифты
define('FPDF_FONTPATH', "../for_pdf_gener/fpdf184/font/");



/**
Создаем титульную страницу
 **/

$pdf = new FPDF( 'P', 'mm', 'A4' );



// добавляем шрифт ариал
$pdf->AddFont('Arial','','arial.php');



$pdf->AddPage();

$pdf->Image("../assets/img/pink_img/top.jpg",6,6, 20, 20);
$pdf->Cell(30); // выводим пустую ячейку, ширина которой 30
$pdf->SetFont( 'Arial', '', 18);
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"МАТЕРИЯ ВРЕМЕНИ"),0,0,'L',0); // выводим название компании
$pdf->SetFont( 'Arial', '', 12);




$pdf->ln(); // переходим на следующую строку
$pdf->Ln( 5 );
$pdf->Cell(35);
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Салон: Коптевский бульвар, 16к1, Москва, Россия,"),0,30,'L',0); // выводим адрес компании
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"Телефон из БД (89250038432)"),0,10,'L',0); // выводим телефон компании
$pdf->Cell(40,4, iconv('utf-8', 'windows-1251',"www.materia-vremeny.ru"),0,10,'L',0); // выводим адрес сайта компании



$pdf->Ln( 10 );
$pdf->Cell(0,0, iconv('utf-8', 'windows-1251',"________________________________________________________________________________"),0,10,'L',0); // выводим телефон компании
$pdf->Cell(70);


$pdf->Ln( 10 );
$pdf->Cell(90);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"АКТ"),0,10,'L',0);
$pdf->Ln( 6 );
$pdf->Cell(70);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"СДАЧИ-ПРИЕМКИ РАБОТЫ"),0,10,'L',0);
$pdf->Ln( 6 );
$pdf->Cell(80);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"От: " . date("d/m/Y")),0,10,'L',0);





$pdf->Ln( 10 );
$pdf->Cell(0,0, iconv('utf-8', 'windows-1251',"Исполнитель, в лице "  . $executor),0,10,'L',0);

$pdf->Ln( 8 );
$pdf->Cell(0,0, iconv('utf-8', 'windows-1251',"сдал, а Заказчик в лице "  . $customer),0,10,'L',0);

$pdf->Ln( 8 );
$pdf->Cell(0,0, iconv('utf-8', 'windows-1251',"по договору-заказа № "  . $id_pink_order . " принял и оплатил указанные работы на"),0,10,'L',0);

$pdf->Ln( 8 );
$pdf->Cell(0,0, iconv('utf-8', 'windows-1251',"общую сумму "  . $order_sum . " рублей."),0,10,'L',0);





$pdf->Ln( 16 );
$pdf->Cell(15);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Доплату получил_________________________________________"),0,10,'L',0); // выводим телефон компании




$pdf->SetFont( 'Arial', '', 12);
$pdf->Ln( 20 );
$pdf->Cell(80);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Подписи:"),0,10,'L',0); // выводим телефон компании


$pdf->SetFont( 'Arial', '', 10);
$pdf->Ln( 10 );
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Исполнитель________________"),0,10,'L',0); // выводим телефон компании
$pdf->Cell(120);
$pdf->Cell(40,0, iconv('utf-8', 'windows-1251',"Заказчик________________"),0,10,'L',0); // выводим телефон компании




/***
Выводим PDF
 ***/

$output = $pdf->Output( "assets/pdf_file/report.pdf", "I" ); // просмотр


?>



