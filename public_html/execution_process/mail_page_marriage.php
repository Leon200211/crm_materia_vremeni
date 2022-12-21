<?php



#============================================================
# Страница с заполнение информации для письма брак или отмена
# Переход в mail_page_marriage_main
#============================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';


$href = 'mail_page.php';
$provider = "Почта";
$text = "Сообщение";
$href = "order_full_info_marriage.php?id=" . $_POST['id'];

if(!empty($_POST['provider']) and !empty($_POST['description']) and !empty($_POST['quantity']) and $_POST['type'] == 'm'){
    $provider = $_POST['provider'];
    $text = "Товар " . $_POST['description'] . " в цвете: " . $_POST['size'] . " в количестве " . $_POST['quantity'] . " шт.\n" .
        "Пришел с браком 'число доставки'\n 'Какая то еще информация'";
    $href = "order_full_info_marriage.php?id=" . $_POST['id'];
}
else if(!empty($_POST['provider']) and !empty($_POST['description']) and !empty($_POST['quantity']) and $_POST['type'] == 'c'){
    $provider = $_POST['provider'];
    $text = "Товар " . $_POST['description'] . " в цвете: " . $_POST['size'] .  " в количестве " . $_POST['quantity'] . " шт.\n" .
        "Отменить доставку\n 'Какая то еще информация'";
    $href = "order_full_info_marriage.php?id=" . $_POST['id'];
} else {
    $text = 'Ошибка';
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



//        // Зададим ограничения для картинок
//        $limitBytes  = 1024 * 1024 * 5;
//        $limitWidth  = 1920;
//        $limitHeight = 1080;
//
//        // Проверим нужные параметры
//        if (filesize($fileTmpName) > $limitBytes) die('Размер изображения не должен превышать 5 Мбайт.');
//        if ($image[1] > $limitHeight)             die('Высота изображения не должна превышать 768 точек.');
//        if ($image[0] > $limitWidth)              die('Ширина изображения не должна превышать 1280 точек.');



        // Сгенерируем новое имя файла через функцию getRandomFileName()
        //$name = getRandomFileName($fileTmpName);
        $name = 'img_' . $_POST['id_in_db'] . "_" . $_POST['id'] . "_" . $_POST['room'] . "_" . $_POST['id_paragraph'];

        // Сгенерируем расширение файла на основе типа картинки
        $extension = image_type_to_extension($image[2]);

        // Сократим .jpeg до .jpg
        $format = str_replace('jpeg', 'jpg', $extension);

        $format = '.png';

        // Переместим картинку с новым именем и расширением в папку /upload
        if (!move_uploaded_file($fileTmpName, '../assets/Defective/' . $name . $format)) {
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





//# can_upload – производит все проверки: возвращает true либо строку с сообщением об ошибке
//function can_upload($file){
//    // если имя пустое, значит файл не выбран
//    if($file['image']['name'] == '')
//        return 'Вы не выбрали файл.';
//
//    /* если размер файла 0, значит его не пропустили настройки
//    сервера из-за того, что он слишком большой */
//    if($file['image']['size'] == 0)
//        return 'Файл слишком большой.';
//
//    // разбиваем имя файла по точке и получаем массив
//    $getMime = explode('.', $file['image']['name']);
//    // нас интересует последний элемент массива - расширение
//    $mime = strtolower(end($getMime));
//    // объявим массив допустимых расширений
//    $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
//
//    // если расширение не входит в список допустимых - return
//    if(!in_array($mime, $types))
//        return 'Недопустимый тип файла.';
//
//    return true;
//}
//# make_upload – производит загрузку файла на сервер
//function make_upload($file){
//    // формируем уникальное имя картинки: случайное число и name
//    $name = $_POST['id_in_db'] . "_" . $_POST['id'] . "_" . $_POST['id_paragraph'];
//    var_dump('/assets/Defective/img_' . $name . '.png');
//    copy($file['image']['tmp_name'], '\assets\Defective\img_' . $name . '.png');
//}
//// если была произведена отправка формы
//if (isset($_FILES) and !empty($_POST['proverca'])) {
//    // проверяем, можно ли загружать изображение
//    $check = can_upload($_FILES);
//
//    if ($check === true) {
//        // загружаем изображение на сервер
//        make_upload($_FILES);
//        echo "<strong>Файл успешно загружен!</strong>";
//    } else {
//        // выводим сообщение об ошибке
//        echo "<strong>$check</strong>";
//    }
//}




?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">


<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->
<!--    <link rel="stylesheet" href="../assets/css/updata_accountant.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_messag.css">-->
<!--    <link rel="stylesheet" href="../assets/css/full_info_css.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->


    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/other_features_from_status_page/mail_sending_form.css">



    <title>Pink</title>

    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="assets/script/paint.js" defer></script>
    <script src="../assets/script/app.js" defer></script>
</head>

<body>

<header class="header">
<?php
include('../header.php');
?>
</header>

<div class="common_div_body">
    <div class="container_for_info">
        <br>
        <div class="info_about_update_state">
            <a href="<?= $href ?>" class="common_back_href">Назад</a>
        </div>


        <div class="main_field_div">
            <?php
            if($_POST['type'] == 'm'){
                ?>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_in_db" value="<?= $_POST['id_in_db'] ?>">
                    <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
                    <input type="hidden" name="id_paragraph" value="<?= $_POST['id_paragraph'] ?>">
                    <input type="hidden" name="provider" value="<?= $_POST['provider'] ?>">
                    <input type="hidden" name="description" value="<?= $_POST['description'] ?>">
                    <input type="hidden" name="quantity" value="<?= $_POST['quantity'] ?>">
                    <input type="hidden" name="size" value="<?= $_POST['size'] ?>">
                    <input type="hidden" name="type" value="<?= $_POST['type'] ?>">
                    <input type="hidden" name="room" value="<?= $_POST['room'] ?>">


                    <input type="hidden" name="proverca" value="<?= 1 ?>">


                    <div class="input-file-row">
                        <label class="input-file">
                            <input type="file" name="image">
                            <span>Выберите файл</span>
                        </label>
                        <div class="input-file-list"></div>
                    </div>
                    <script src="https://snipp.ru/cdn/jquery/2.1.1/jquery.min.js"></script>
                    <script src="../assets/script/image_upload_form.js" defer></script>
                    <br>
                    <button class="common_button" type="submit">Загрузить</button>


                </form>
                <?php
            }


            ?>

             <br>
            <div>
                <?php
                if($_POST['type'] == 'm'){
                    $filename = '../assets/Defective/img_'. $_POST['id_in_db'] . "_" . $_POST['id'] . "_" . $_POST['room'] . "_" . $_POST['id_paragraph'] . '.png';
                    $filename_2 = '../assets/Defective/img_'. $_POST['id_in_db'] . "_" . $_POST['id'] . "_" . $_POST['room'] . "_" . $_POST['id_paragraph'] . '.jpg';
                    if (@fopen($filename, "r") or @fopen($filename_2, "r")) {
                        ?> Имя загруженного файла <?= 'img_'. $_POST['id_in_db'] . "_" . $_POST['id'] . "_" . $_POST['room'] . "_" . $_POST['id_paragraph'] ?> <?php
                    } else {
                        ?> Вы еще не загрузили файл <?php
                    }
                }
                ?>
            </div>

            <br>
            <br>
            <br>
            <form action="mail_page_marriage_main.php" method="post">
                <div class="text_for_update">Почта</div>
                <input class="sending_input" type="text" name="email" maxlength=6 value=<?= $provider ?>>
                <input type="hidden" name="type" value=<?= $_POST['type'] ?>>
                <input type="hidden" name="id_in_db" value=<?= $_POST['id_in_db'] ?>>
                <input type="hidden" name="id_order" value=<?= $_POST['id'] ?>>


                <input type="hidden" name="id_paragraph" value=<?= $_POST['id_paragraph'] ?>>
                <input type="hidden" name="room" value=<?= $_POST['room'] ?>>

                <br>
                <br>
                <br>

                <div class="text_for_update">Текст</div>
                <textarea class="sending_text" name="text" style="width:300px; height:150px;"><?=$text?></textarea>
                <br>
                <br>
                <br>
                <button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Отправить</button>
            </form>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>