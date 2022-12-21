<?php



#============================================================
# Создание рекламации по заказу дизайнер -> цех ( брак )
# Переход в generate_complaint.php
#============================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer')){
    echo "Доступ запрещен";
    die;
}

require_once '../../connect_to_database.php';


if(!empty($_GET['id_order'])){
    $id_order = $_GET['id_order'];
}else if(!empty($_POST['id_order'])) {
    $id_order = $_POST['id_order'];
}

if(!empty($_POST['name'])){
    $name = $_POST['name'];
}else{
    $name = "";
}
if(!empty($_POST['room'])){
    $room = $_POST['room'];
}else{
    $room = "";
}




#====================================================
# БЛОК СОХРАНЕНИЯ КАРТИНКИ В БД
#====================================================





// File upload.php
// Если в $_FILES существует "image" и она не NULL
if (isset($_FILES['image'])) {
// Получаем нужные элементы массива "image"
    $fileTmpName = $_FILES['image']['tmp_name'];
    $errorCode = $_FILES['image']['error'];
// Проверим на ошибки
    if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($fileTmpName)) {
        // Массив с названиями ошибок
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
            UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
            UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
            UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
            UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
            UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
            UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
        ];
        // Зададим неизвестную ошибку
        $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';
        // Если в массиве нет кода ошибки, скажем, что ошибка неизвестна
        $outputMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;
        // Выведем название ошибки
        die($outputMessage);
    } else {
        // Создадим ресурс FileInfo
        $fi = finfo_open(FILEINFO_MIME_TYPE);
        // Получим MIME-тип
        $mime = (string) finfo_file($fi, $fileTmpName);
        // Проверим ключевое слово image (image/jpeg, image/png и т. д.)
        if (strpos($mime, 'image') === false) die('Можно загружать только изображения.');

        // Результат функции запишем в переменную
        $image = getimagesize($fileTmpName);


        // Сгенерируем новое имя файла через функцию getRandomFileName()
        //$name = getRandomFileName($fileTmpName);
        $name_file = 'img_complaint_' . $id_order . "_" . $room . "_" . $name;


        // Сгенерируем расширение файла на основе типа картинки
        $extension = image_type_to_extension($image[2]);

        // Сократим .jpeg до .jpg
        $format = str_replace('jpeg', 'jpg', $extension);

        $format = '.png';

        // Переместим картинку с новым именем и расширением в папку /upload
        if (!move_uploaded_file($fileTmpName, '../../assets/complaint/' . $name_file . $format)) {
            die('При записи изображения на диск произошла ошибка.');
        }

    }
};
// File functions.php
function getRandomFileName($path)
{
    $path = $path ? $path . '/' : '';
    do {
        $name = md5(microtime() . rand(0, 9999));
        $file = $path . $name;
    } while (file_exists($file));

    return $name;
}



?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">


    <link rel="stylesheet" href="../../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../../assets/css/other_features_from_status_page/mail_sending_form.css">





    <title>Pink</title>

    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../../assets/script/paint.js" defer></script>
    <script src="../../assets/script/app.js" defer></script>
</head>

<body>

<header class="header">
    <?php
    include('../../header.php');
    ?>
</header>

<div class="common_div_body">


    <div>
        <a href="../order_full_info_new.php?id=<?=$id_order?>" class="common_back_href">Назад</a>
    </div>


    <div class="main_field_div">

        <form method="post" enctype="multipart/form-data">

            <input type="hidden" name="id_order" value="<?= $id_order ?>">
            <div class="text_for_update">Комната</div>
            <input class="sending_input" name="room" value="<?=$room?>">
            <br>
            <br>
            <div class="text_for_update">Изделие</div>
            <input class="sending_input" name="name" value="<?=$name?>">
            <br>


            <div class="input-file-row">
                <label class="input-file">
                    <input type="file" name="image">
                    <span>Выберите файл</span>
                </label>
                <div class="input-file-list"></div>
            </div>
            <script src="https://snipp.ru/cdn/jquery/2.1.1/jquery.min.js"></script>
            <script src="../../assets/script/image_upload_form.js" defer></script>
            <br>
            <button class="common_button" type="submit">Загрузить</button>

        </form>


        <br>
        <div>
            <?php
            $filename = '../../assets/complaint/img_complaint_'. $id_order . "_" . $room . "_" . $name . '.png';
            $filename_2 = '../../assets/complaint/img_complaint_'. $id_order . "_" . $room . "_" . $name . '.jpg';
            if (@fopen($filename, "r") or @fopen($filename_2, "r")) {
                ?> Имя загруженного файла <?= 'img_complaint_'. $id_order . "_" . $name ?> <?php
            } else {
                ?> Вы еще не загрузили файл <?php
            }
            ?>
        </div>


        <form action="generate_complaint.php" method="post">
            <input type="hidden" name="id_order" value=<?= $id_order ?>>
            <input type="hidden" name="name" value="<?=$name?>">
            <input type="hidden" name="room" value="<?=$room?>">
            <div class="sending_text_main">
                <div class="sending_text_title">Текст</div>
                <textarea class="sending_text" name="text"></textarea>
            </div>

            <?php
            if(isset($_GET['error'])){
                ?>
                <div style="color: red; margin-bottom: 10px;">
                    <?=$_GET['error']?>
                </div>
            <?php
            }
            ?>

            <button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Отправить</button>
        </form>

    </div>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>