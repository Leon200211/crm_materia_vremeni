<?php


#=================================================================
#  Исполняемый файл отправки письма об отмене или браке ткани
#=================================================================



session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin')){
    echo "Доступ запрещен";
    die;

}

require_once '../connect_to_database.php';


$email = $_POST['email'];
$text = $_POST['text'];
$type = $_POST['type'];
$id = $_POST['id_in_db'];
$id_order = $_POST['id_order'];

if($type == 'm'){
    $state = 'Брак';
} elseif($type == 'c'){
    $state = 'Отмена';
}





//--------------------------------------------------------------------------------------------------------------------------------

$filename = '../assets/Defective/img_'. $_POST['id_in_db'] . "_" . $_POST['id_order'] . "_" . $_POST['room'] . "_". $_POST['id_paragraph'] . '.png';
$file_name_for_db = $filename;

if (@fopen($filename, "r")) {
    $attach = array(
        $filename
    );

// чтобы отображалась картинка и ее не было в аттаче
// путь к картинке задается через CID: - Content-ID
// тестовый текст

    $from = "materiyavremeni@myb-workflow.ru";
    $to = "leon20022018@yandex.ru";
    $subject = "Тема письма";

// Заголовки письма === >>>
    $headers = "From: $from\r\n";

    $headers .= "Subject: $subject\r\n";
    $headers .= "Date: " . date("r") . "\r\n";
    $headers .= "X-Mailer: zm php script\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative;\r\n";



    $baseboundary = "------------" . strtoupper(md5(uniqid(rand(), true)));
    $headers .= "  boundary=\"$baseboundary\"\r\n";
// <<< ====================

// Тело письма === >>>
    $text_2  =  "--$baseboundary\r\n";
    $text_2 .= "Content-Type: text/plain;\r\n";
    $text_2 .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $text_2 .= "--$baseboundary\r\n";
    $newboundary = "------------" . strtoupper(md5(uniqid(rand(), true)));
    $text_2 .= "Content-Type: multipart/related;\r\n";
    $text_2 .= "  boundary=\"$newboundary\"\r\n\r\n\r\n";
    $text_2 .= "--$newboundary\r\n";
    $text_2 .= "Content-Type: text/html; charset=utf-8\r\n";
    $text_2 .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $text_2 .= $text . "\r\n\r\n";
// <<< ==============

// прикрепляем файлы ===>>>
    foreach($attach as $filename){
        $mimeType='image/png';
        $fileContent = file_get_contents($filename,true);
        $filename=basename($filename);
        $text_2.="--$newboundary\r\n";
        $text_2.="Content-Type: $mimeType;\r\n";
        $text_2.=" name=\"$filename\"\r\n";
        $text_2.="Content-Transfer-Encoding: base64\r\n";
        $text_2.="Content-ID: <$filename>\r\n";
        $text_2.="Content-Disposition: inline;\r\n";
        $text_2.=" filename=\"$filename\"\r\n\r\n";
        $text_2.=chunk_split(base64_encode($fileContent));
    }
// <<< ====================
    $text = $text_2;
// заканчиваем тело письма, дописываем разделители
    $text.="--$newboundary--\r\n\r\n";
    $text.="--$baseboundary--\r\n";

} else {
    // отправка письма
    $to = "leon20022018@yandex.ru";
    $headers = "From: materiyavremeni@myb-workflow.ru";
    $headers .= "\r\nReply-To: materiyavremeni@myb-workflow.ru";
    $headers .= "\r\nX-Mailer: PHP/" . phpversion();
    $subject = "Материя времени";

    // для записи в бд
    $file_name_for_db = "-";
}

//--------------------------------------------------------------------------------------------------------------------------------

if (mail($to, $subject, $text, $headers)) {

    require_once('../working_with_db/work_with_mail/writing_to_database.php');
    write_message_in_db($connect, $id_order, $to, $state, $_POST['text'], $file_name_for_db);

    if (mail('leon200207@yandex.ru', $subject, $text, $headers)) {
        mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `additional_info` = '$state' WHERE `description_of_pink_pages`.`id` = '$id'");
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
        </head>
        <body>
        <div class="body_result">
            <div class="body_result_title">Письмо отправлено</div>
            <a href="order_full_info_marriage.php?id=<?= $id_order ?>" class="common_back_href">Вернуться</a>
        </div>
        </body>
        </html>
        <?php
    }
} else {
    mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `additional_info` = '' WHERE `description_of_pink_pages`.`id` = '$id'");
    ?>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
        <link rel="stylesheet" href="../assets/css/other_features_from_status_page/style_for_with_defective.css">
    </head>
    <body>
    <div class="body_result">
        <div class="body_result_title">Ошибка отправки письма</div>
        <a href="order_full_info_marriage.php?id=<?= $id_order ?>" class="common_back_href">Вернуться</a>
    </div>
    </body>
    </html>
    <?php
}