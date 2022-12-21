<?php


#=================================================================================
# Функция для записи информации в таблицу mail в бд
#=================================================================================

// запись информации об отправки в бд
//require_once('working_with_db/work_with_mail/writing_to_database.php');
//write_message_in_db($connect, $arg_1, $to, "Запрос поставки измененных товаров", $messeg, '-');



session_start();



function write_message_in_db($connect, $id_order, $recipient, $type, $text, $file)
{

    $text = addslashes($text); // автоэкранирование текста

    $sql = "INSERT INTO `mail_messages` (id, id_order, data, recipient, type, text, file) VALUES (NULL, $id_order, NOW(), '$recipient', '$type', '$text', '$file')";
    mysqli_query($connect, $sql);

    return 1;
}







