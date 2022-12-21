<?php


#=======================================================
# Исполняемый файл Выбор изменения именно товара(а не информации о нем) в заказе
#=======================================================



session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';


//  -------------------------------
//  для проверки на создание заказа
$get_id = $_POST['id'];
$orders_test = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id` = '$get_id'");
$select_test_1 = mysqli_fetch_assoc($orders_test);
$id_order = $select_test_1['id_pink_order'];

$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_order'";
$select_test_2 = mysqli_query($connect, $sql);
$select_test_2 = mysqli_fetch_assoc($select_test_2);
$now_state = $select_test_2['pink_state'];
if($select_test_2['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------




$id_in_db = $_POST['id'];

$description = $_POST['description'];



// в зависимости от типа ткани
// будет вывод из определенной базы данных
$table = $select_test_1['category'];
if($table == 'cloth') {
    $sql = "SELECT * FROM `cloth`";
    $from = 'cloth';
    $ft_select_1 = "title";
    $ft_select_2 = "collection";
    $name_price = "price";
    $name = 'cloth';
    $mas_1 = ['id', 'Артикул', 'Размер', 'Вертикальный', 'Горизонтальный', 'Состав', 'Утяжелитель', 'Цена за рулон', 'Коллекция', 'Поставщик'];
    $mas_2 = ['id', 'title', 'width', 'vertical', 'horizontal', 'compound', 'weighter', 'price/rulon', 'collection', 'provider'];
    $provider = 'provider';
} else if($table == 'cornices') {
    $search = "Карниз";
    $sql = "SELECT * FROM `cornices`";
    $from = 'cornices';
    $ft_select_1 = "title";
    $ft_select_2 = "type";
    $name = 'cornices';
    $name_price = "price";
    $mas_1 = ['id', 'Артикул', 'Цена', 'Тип', 'Валюта', 'Поставщик'];
    $mas_2 = ['id', 'title', 'price', 'type', 'currency', 'provider'];
    $provider = 'provider';
}else if($table == 'blinds') {
    $search = "Жалюзи";
    $sql = "SELECT * FROM `blinds`";
    $from = 'blinds';
    $ft_select_1 = "title";
    $ft_select_2 = "type";
    $name = 'blinds';
    $name_price = "price";
    $mas_1 = ['id', 'Артикул', 'Высота', 'Ширина', 'Тип', 'Цвет', 'Цена', 'Валюта', 'Поставщик'];
    $mas_2 = ['id', 'title', 'height', 'width', 'type', 'color', 'price', 'currency', 'provider'];
    $provider = 'provider';
}else if($table == 'furniture') {
    $search = "Фурнитура";
    $sql = "SELECT * FROM `furniture`";
    $ft_select_1 = "title";
    $ft_select_2 = "collection";
    $name = 'furniture';
    $from = 'furniture';
    $name_price = "price_opt";
    $mas_1 = ['id', 'Артикул', 'Коллекция', 'Цена', 'Цена_опт', 'Поставщик', 'Валюта'];
    $mas_2 = ['id', 'title', 'collection', 'price', 'price_opt', 'provider', 'currency'];
    $provider = 'provider';
}else if ($table == 'services') {
    $search = "услуг";
    $sql = "SELECT * FROM `services`";
    $ft_select_1 = "title";
    $ft_select_2 = "type";
    $from = 'services';
    $name = 'services';
    $name_price = "price";
    $mas_1 = ['id', 'Название', 'Цена', 'Тип', 'Валюта'];
    $mas_2 = ['id', 'title', 'price', 'type', 'currency'];
}
// =======================================================================

$sql = "SELECT * FROM `$from` WHERE `id` = '$description'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);

if($table != 'services') {
    $supplier = $select[$provider];
}else {
    $supplier = 'mv';
}

$price = $select[$name_price];
$description = $select[$ft_select_1] . "|" . $select[$ft_select_2];

if($now_state == 'Возврат дизайнеру' or $now_state == 'Перевыбор ткани в салоне' or $now_state == 'Возврат ткани'){
    mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `description` = '$description', `price` = '$price', `provider` = '$supplier', `additional_info` = 'Заменено', `supplier_price` = 0 WHERE `id` = '$id_in_db'");
}else {
    mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `description` = '$description', `price` = '$price', `provider` = '$supplier' WHERE `id` = '$id_in_db'");
}
header("Location: pink_all_elements_of_the_mutable_update.php?id=$id_in_db");
