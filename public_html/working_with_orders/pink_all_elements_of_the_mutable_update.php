<?php


#==============================================================
# Страница изменения какого то пункта в заказе переход в pink_all_elements_of_the_mutable_update_main.php
#==============================================================



session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';

//  -------------------------------
//  для проверки на создание заказа
$get_id = $_GET['id'];
$orders_test = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id` = '$get_id'");
$select_test_1 = mysqli_fetch_assoc($orders_test);
$id_order = $select_test_1['id_pink_order'];

$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_order'";
$select_test_2 = mysqli_query($connect, $sql);
$select_test_2 = mysqli_fetch_assoc($select_test_2);

if($select_test_2['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------



$get_id = $_GET['id'];
if(!$get_id){
    $get_id = $_POST['id'];
}

$orders = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id` = '$get_id'");
$orders = mysqli_fetch_assoc($orders);


?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Пример веб-страницы</title>

    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/update_pink_page.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_work_with_pink_page.css">

    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../assets/script/paint_update.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="../assets/script/app.js" defer></script>
</head>

<body style="background-color: #F3F3F3">
<header class="header">
    <?php
    include('../header.php');
    ?>
</header>


<div class="common_div_body">

    <div>
        <div class="back_title">
            Заказы / Заказ № <?= $orders['id_pink_order'] ?>
        </div>
        <a href="pink_all_elements_of_the_mutable.php?id_pink_order=<?= $orders['id_pink_order'] ?>" class="common_back_href">Назад</a>
    </div>

    <div class="main_field_div">

        <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>
        <h3 class="update_title">В заказе № <?= $orders['id_pink_order'] ?></h3>

        <form action="pink_all_elements_of_the_mutable_update_forArtic.php" method="post">
            <div class="place_find">
                <input type="hidden" name="id" value="<?= $orders['id'] ?>">
                <input type="hidden" name="id_pink_order" value="<?= $orders['id_pink_order'] ?>">
                <div class="update_product_all">
                    <div class="text_for_update">Наименование*</div>
                    <div>
                        <?= $orders['description'] ?>
                        <?php
                        if($orders['category'] != 'sewing' and $orders['category'] != 'modification'){
                            ?>
                            <a href="pink_all_elements_of_the_mutable_update_forArtic.php?id=<?= $orders['id'] ?>" class="common_back_href"> Изменить</a>
                        <?php
                        }
                        ?>
                    </div>
                </form>
        <br>
        <form action="pink_all_elements_of_the_mutable_update_main.php" method="post">
            <?php
            if($_SESSION['state'] == 'admin'){
                ?>
                <div class="text_for_update">Цена</div>
                <input type="text" name="price" class="input_style" value="<?= $orders['price'] ?>">
                    <?php
            }else{
                ?>
                <input type="hidden" name="price" value="<?= $orders['price'] ?>">
                <div class="text_for_update">Цена: <?= $orders['price'] ?></div>
            <?php
            }
            ?>
                <input type="hidden" name="id" value="<?= $orders['id'] ?>">
                <input type="hidden" name="id_pink_order" value="<?= $orders['id_pink_order'] ?>">
                <input type="hidden" name="id_paragraph" value="<?= $orders['id_paragraph'] ?>">

                <?php
                if($orders['category'] != 'services' and $orders['category'] != 'sewing' and $orders['category'] != 'modification'){
                    ?>
                    <div class="text_for_update">Цвет</div>
                    <input type="text" name="size" class="input_style" maxlength=12 value="<?= $orders['size'] ?>">
                    <?php
                }
                ?>
                <div class="text_for_update">Количество*</div>
                <input type="text" name="quantity" class="input_style" maxlength=6 value="<?= $orders['quantity'] ?>">
                <br>
                <br>
                <button type="submit" class="common_button">Изменить</button>
            </div>
        </form>

    </div>
</div>



</body>
</html>
