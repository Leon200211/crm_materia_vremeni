<?php



#=============================================================
# Удаляем комнату целиком
#=============================================================



session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once "../connect_to_database.php";


//  -------------------------------
//  для проверки на создание заказа
$id = $_GET['id_pink_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------




$id_pink_order = $_GET['id_pink_order'];
$room = $_GET['room'];


mysqli_query($connect, "DELETE FROM `description_of_pink_pages` WHERE `description_of_pink_pages`.`id_pink_order` = '$id_pink_order' AND `description_of_pink_pages`.`room` = '$room'");

header("Location: pink_all_elements_of_the_mutable.php?id_pink_order=$id_pink_order");