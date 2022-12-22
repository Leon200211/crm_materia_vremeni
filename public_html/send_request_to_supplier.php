<?php


#=================================================================================
# Обычный запрос всех тканий по заказу у поставщика
#=================================================================================




session_start();





function send_request_to_supplier($connect, $arg_1)
{


    $array = [];
    $array_2 = [];


    // создаем массив с именами поставщиков ( можно сделать с почтами )
    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$arg_1' AND `category` != 'services' AND `category` != 'sewing' AND `category` != 'modification'";
    $select = mysqli_query($connect, $sql);
    while ($select_while = mysqli_fetch_assoc($select)) {
        if (!(in_array($select_while['provider'], $array_2)) and $select_while['provider'] != '') {
            array_push($array_2, $select_while['provider']);
        }

    }
    // создаем шаблон массива ключ => значение
    for ($i = 0; $i<count($array_2); $i++) {
        $array[$array_2[$i]] = [];
    }

    // заполняем массив ключ => значение
    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$arg_1' AND `category` != 'services' AND `category` != 'sewing' AND `category` != 'modification'";
    $select = mysqli_query($connect, $sql);
    while ($select_while = mysqli_fetch_assoc($select)) {
        if($select_while['provider'] != '') {
            array_push($array[$select_while['provider']], $select_while['description'] . "|???|" . $select_while['quantity']. "|???|" . $select_while['size']);
        }
    }

    //проверка на отправленное письмо
    $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$arg_1'";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    // массив который будет хранить почты, на которые не было оправлено письмо
    $errors = array();
    if($select['state_of_fabric_order'] == 0) {
        // в этом цикле мы будем отправлять письмо

        foreach ($array as $key => $value) {
            $messeg = "";

            if (count($value) > 1) {
                foreach ($value as $v) {
                    // генерируем текст письма
                    $mas_1 = explode("|???|", $v);  // разбиваем на название и количество
                    $messeg = $messeg . "Продукт " . $mas_1[0] . " цвет: " . $mas_1[2] . " необходим в количестве: " . $mas_1[1] . "\n";
                }

                $messeg .= "Кому: $key";


                // отправка письма
                $to = "leon20022018@yandex.ru";
                $headers = "From: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nReply-To: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nX-Mailer: PHP/" . phpversion();
                $subject = "Материя времени";
                if (mail($to, $subject, $messeg, $headers)) {

                    // запись информации об отправки в бд
                    require_once('working_with_db/work_with_mail/writing_to_database.php');
                    write_message_in_db($connect, $arg_1, $to, "Запрос поставки товаров", $messeg, '-');

                    $messege_to_admin = $messeg . "\nЗаказ № $arg_1";

                    if (!mail('leon200207@yandex.ru', $subject, $messeg, $headers) AND
                        !mail('leon200207@yandex.ru', $subject, $messege_to_admin, $headers)) {
                        array_push($errors, $key);
                    }
                } else {
                    array_push($errors, $key);
                }
            } else {
                // генерируем текст письма
                $mas_1 = explode("|???|", $value[0]);  // разбиваем на название и количество
                $messeg = $messeg . "Продукт " . $mas_1[0] . " цвет: " . $mas_1[2] ." необходим в количестве: " . $mas_1[1] . "\n";

                $messeg .= "Кому: $key";


                // отправка письма
                $to = "leon20022018@yandex.ru";
                $headers = "From: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nReply-To: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nX-Mailer: PHP/" . phpversion();
                $subject = "Материя времени";
                if (mail($to, $subject, $messeg, $headers)) {

                    // запись информации об отправки в бд
                    require_once('working_with_db/work_with_mail/writing_to_database.php');
                    write_message_in_db($connect, $arg_1, $to, "Запрос поставки товаров", $messeg, '-');

                    $messege_to_admin = $messeg . "\nЗаказ № $arg_1";

                    if (!mail('leon200207@yandex.ru', $subject, $messeg, $headers) AND
                        !mail('leon200207@yandex.ru', $subject, $messege_to_admin, $headers)) {
                        array_push($errors, $key);
                    }
                } else {
                    array_push($errors, $key);
                }
            }

        }
    }
    // если не было вызвано ошибок, поменять статус
    if(count($errors) == 0){
        $sql = "UPDATE `orders_main_info` SET `state_of_fabric_order` = '1' WHERE `orders_main_info`.`id_pink_order` = '$arg_1'";
        mysqli_query($connect, $sql);
    } else {
        $sql = "UPDATE `orders_main_info` SET `state_of_fabric_order` = 'error' WHERE `orders_main_info`.`id_pink_order` = '$arg_1'";
        mysqli_query($connect, $sql);
    }

    return 1;
}


?>



