<?php

#===========================================================
# Исполняемый файл Добавление новой комнаты в заказ для работы с эсказами
#===========================================================







session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';

//  -------------------------------
//  для проверки на создание заказа
$id_pink_order = $_POST['id_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------


$id_order = $_POST['id_order'];
$room = $_POST['room'];
$specification = $_POST['specification'];




if(isset($specification)){

    $sql = "SELECT * FROM `sketches_main` WHERE `id_order` = '$id_order' AND `room` = '$room' ORDER BY `page` DESC LIMIT 1";
    $id_page = mysqli_query($connect, $sql);
    $id_page = mysqli_fetch_assoc($id_page);

    if(isset($id_page)) {
        $id_page = $id_page['page'] + 1;
        mysqli_query($connect, "INSERT INTO `sketches_main` (`id`, `id_order`, `room`, `page`, `specification`, `note`, `font_side`, `image`) VALUES (NULL, '$id_order', '$room', '$id_page', '$specification', '', '', '')");
        header("Location: sketches_room.php?id_pink_order=" . $id_order);
    }else {
        mysqli_query($connect, "INSERT INTO `sketches_main` (`id`, `id_order`, `room`, `page`, `specification`, `note`, `font_side`, `image`) VALUES (NULL, '$id_order', '$room', '1', '$specification', '', '', '')");
        header("Location: sketches_room.php?id_pink_order=" . $id_order);
    }
}

?>