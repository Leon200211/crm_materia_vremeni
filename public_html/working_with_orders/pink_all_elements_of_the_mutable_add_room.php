<?php


#==========================================================
# Добавление комнаты в заказ
#==========================================================


session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';



//  -------------------------------
//  для проверки на создание заказа
$id_pink_order = $_GET['id_pink_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------



$room = $_POST['room'];



$sql_prov = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `room` = '$room'";
$prov = mysqli_query($connect, $sql_prov);
$prov = mysqli_fetch_assoc($prov);

if(empty($room) OR $prov != NULL) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">


    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">



    <title>Pink</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../assets/script/app.js" defer></script>
</head>

<body>
<div class="body_result">
    <div class="body_result_title">Ошибка <br> Неверное название комнаты</div>
    <a href="pink_all_elements_of_the_mutable.php?id_pink_order=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
</div>
</body>
<?php
die;
}else{
    $sql = "INSERT INTO `description_of_pink_pages` (`id`, `id_pink_order`, `room`, `id_paragraph`, `description`, `size`, `quantity`, `price`, `category`, `supplier_price`, `provider`, `additional_info`) VALUES (NULL, '$id_pink_order', '$room', '0', '-', '-', '0', '0', NULL, NULL, NULL, NULL);";
    mysqli_query($connect, $sql);

    header("Location: pink_all_elements_of_the_mutable.php?id_pink_order=$id_pink_order");
}

?>

