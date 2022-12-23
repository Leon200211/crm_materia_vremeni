<?php


#===========================================================================================
# Здесь содержаться различные функции, которые используются в разных местах системы
#===========================================================================================




// считает стоимость пошива салона
function calculate_the_cost_of_sewing($connect, $id_order){

    $sum = 0;

    $sql = "SELECT `quantity`, `price` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND (`category` = 'sewing' OR `category` = 'modification')";
    $select = mysqli_query($connect, $sql);
    while ($select_while = mysqli_fetch_assoc($select)) {
        $sum += $select_while['price'] * $select_while['quantity'];
    }

    return $sum;

}


// умножает цену на коэффициент и переводит валюту
function show_normal_price($connect, $price, $provider, $currency, $type){


    // перевод валюты
    if($currency == '€'){
        $sql_currency = "SELECT `euro` FROM `exchange_rate` WHERE `provider` = '$provider'";
        $euro = mysqli_query($connect, $sql_currency);
        $euro = mysqli_fetch_assoc($euro);

        $price *= $euro['euro'];
    }else if($currency == '$'){
        $sql_currency = "SELECT `dollars` FROM `exchange_rate` WHERE `provider` = '$provider'";
        $dollar = mysqli_query($connect, $sql_currency);
        $dollar = mysqli_fetch_assoc($dollar);

        $price *= $dollar['dollars'];
    }



    // получение табличного коэффициента
    $sql_coff = "SELECT `coefficient` FROM `coefficients_table` WHERE `type` = '$type' 
                                     AND `provider` = '$provider'";
    $table_coff = $connect->query($sql_coff);
    $table_coff = $table_coff->fetch_all();
    if(count($table_coff) == 1){
        $table_coff = $table_coff[0][0];
    }else if(count($table_coff) == 0){
        $sql_coff = "SELECT `coefficient` FROM `coefficients_table` WHERE `type` = '$type' 
                                     AND `provider` = 'all_the_rest'";
        $table_coff = $connect->query($sql_coff);
        $table_coff = $table_coff->fetch_all();
        if(count($table_coff) == 1){
            $table_coff = $table_coff[0][0];
        }else{
            $table_coff = -1;
        }
    }else{
        $table_coff = -1;
    }

    // умножение на коэффициент
    return $price * $table_coff;

}


// добавляет уведомление
function set_notice($connect, $id_user, $id_order){

    // проверяем, существует ли уведомление
    $prov_notice = mysqli_query($connect, "SELECT * FROM `notice` WHERE `id_user` = '$id_user' and `id_order` = '$id_order'");

    if(empty(mysqli_fetch_assoc($prov_notice)['id_user'])){ // если еще нет уведомлений по этому заказу
        mysqli_query($connect, "INSERT INTO `notice` (`id`, `id_user`, `id_order`) VALUES (NULL, '$id_user', '$id_order')");
    }

}


// Добавление важных уведомлений
function set_notice_important($connect, $id_user, $id_order, $note, $remove){

    // проверяем, существует ли уведомление
    $prov_notice = mysqli_query($connect, "SELECT * FROM `notice_important` WHERE `id_user` = '$id_user' AND 
                             `id_order` = '$id_order' AND `note` = '$note' AND `remove` = '$remove'");

    if(empty(mysqli_fetch_assoc($prov_notice)['id_user'])){ // если еще нет уведомлений по этому заказу
        mysqli_query($connect, "INSERT INTO `notice_important` (`id`, `id_user`, `id_order`, `note`, `remove`)
        VALUES (NULL, '$id_user', '$id_order', '$note', '$remove')");
    }

}



// возвращает имя дизайнера
function get_designer_name($connect, $id_designer){

    $sql = "SELECT `name` FROM `users` WHERE `id` = '$id_designer'";
    $id_designer = mysqli_query($connect, $sql);
    $id_designer = mysqli_fetch_assoc($id_designer);

    return $id_designer['name'];

}

// возвращает имя закройщика
function get_performer_name($connect, $id_performer){

    $sql_performer = "SELECT `name` FROM `performers` WHERE `id` = '$id_performer'";
    $id_performer = mysqli_query($connect, $sql_performer);
    $id_performer = mysqli_fetch_assoc($id_performer);

    if($id_performer){
        return $id_performer['name'];
    }else{
        return '-';
    }

}
