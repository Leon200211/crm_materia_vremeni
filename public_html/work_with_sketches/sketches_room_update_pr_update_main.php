<?php


#============================================================================
# Исполняемый файл изменение строки в определенной странице эскиза
#============================================================================





session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';


$specification = $_POST['specification'];
$id = $_POST['id'];
$id_order = $_POST['id_order'];
$id_sketches_main = $_POST['id_sketches_main'];





if($specification == 'портьеры|тюли|подхваты|тп'){



    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';

    $count = $_POST['count'];
    if(empty($count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $assembled_width = $_POST['assembled_width'];
    if(empty($assembled_width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Ширина в собр. виде' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $assembled_width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Ширина в собр. виде' должно содержать только цифры и *, /, и тп");
        die;
    }
    $coefficient = $_POST['coefficient'];
    if(empty($coefficient)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Коэф. сборки' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $coefficient))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Коэф. сборки' должно содержать только цифры и *, /, и тп");
        die;
    }
    $unfolded_width = $_POST['unfolded_width'];
    if(empty($unfolded_width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Ширина в разверн. виде (крой) в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $unfolded_width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Ширина в разверн. виде (крой) в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $height = $_POST['height'];
    if(empty($height)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Высота в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $height))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Высота в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $scallop = $_POST['scallop'];
    if(empty($scallop)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Гребешок' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $scallop))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Гребешок' должно содержать только цифры и *, /, и тп");
        die;
    }



    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];

    $pattern = '/^[\d.,]+$/';
    if(empty($m_count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Количество основной ткани' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $m_count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }

    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];
    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];


    $pattern = '/^[\d* \\/+-.,]+$/';
    $bottom = $_POST['bottom'];
    if(empty($bottom)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Низ в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $bottom))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Низ в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $sides = $_POST['sides'];
    if(empty($sides)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Бока в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $sides))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Бока в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $bend = $_POST['bend'];
    if(empty($bend)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Тех-загиб бок. в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $bend))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .   "&state_add='Тех-загиб бок. в см.' должно содержать только цифры и *, /, и тп");
        die;
    }

    $ribbon = $_POST['ribbon'];



    mysqli_query($connect, "UPDATE `sketches_1` SET `vendor_code` = '$vendor_code', `count` = '$count', `assembled_width` = '$assembled_width', `coefficient` = '$coefficient', `unfolded_width` = '$unfolded_width', `height` = '$height', `scallop` = '$scallop', `main_cloth` = '$main_cloth', `m_count` = '$m_count', `lining` = '$lining', `l_count` = '$l_count', `finishing` = '$finishing', `f_count` = '$f_count', `bottom` = '$bottom', `sides` = '$sides', `bend` = '$bend', `ribbon` = '$ribbon' WHERE `sketches_1`.`id` = '$id'");

    header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);


}else if ($specification == 'римские|франц|австрийск|тп') {


    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';

    $count = $_POST['count'];
    if(empty($count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество в шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество в шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $eaves_width = $_POST['eaves_width'];
    if(empty($eaves_width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина по карнизу (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $eaves_width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина по карнизу (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $height = $_POST['height'];
    if(empty($height)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Высота изделия (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $height))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Высота изделия (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $scallop = $_POST['scallop'];
    if(empty($scallop)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Гребешок' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $scallop))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Гребешок' должно содержать только цифры и *, /, и тп");
        die;
    }


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if(empty($m_count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $m_count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }

    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];
    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count_fib = $_POST['count_fib'];
    if(empty($count_fib)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Тех-загиб бок. в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count_fib))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Тех-загиб бок. в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $count_drums = $_POST['count_drums'];
    if(empty($count_drums)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Шт. лента' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count_drums))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Шт. лента' должно содержать только цифры и *, /, и тп");
        die;
    }


    mysqli_query($connect, "UPDATE `sketches_2` SET `vendor_code` = '$vendor_code', `count` = '$count', `eaves_width` = '$eaves_width', `height` = '$height', `scallop` = '$scallop', `main_cloth` = '$main_cloth', `m_count` = '$m_count', `lining` = '$lining', `l_count` = '$l_count', `finishing` = '$finishing', `f_count` = '$f_count', `count_fib` = '$count_fib', `count_drums` = '$count_drums' WHERE `id` = '$id'");

    header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);

}else if ($specification == 'покрывала') {


    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Изделия' не должно быть пустым");
        die;
    }


    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $width = $_POST['width'];
    if(empty($width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $length = $_POST['length'];
    if(empty($length)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Длина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $length))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Длина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $stitch = $_POST['stitch'];
    $stitch_name = $_POST['stitch_name'];


    $stitch_step = $_POST['stitch_step'];
    if(empty($stitch_step)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Шаг стежки' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $stitch_step))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Шаг стежки' должно содержать только цифры и *, /, и тп");
        die;
    }

    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if(empty($m_count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $m_count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }

    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];
    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];
    $edges = $_POST['edges'];


    $centipone = $_POST['centipone'];

    $pattern = '/^[\d* \\/+-.,]+$/';
    $corner = $_POST['corner'];
    if(empty($corner)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Синтепон (100/200г)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $corner))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Синтепон (100/200г)' должно содержать только цифры и *, /, и тп");
        die;
    }



    mysqli_query($connect, "UPDATE `sketches_3` SET `vendor_code` = '$vendor_code', `count` = '$count', `width` = '$width', `length` = '$length', `stitch` = '$stitch', `stitch_name` = '$stitch_name', `stitch_step` = '$stitch_step', `main_cloth` = '$main_cloth', `m_count` = '$m_count', `lining` = '$lining', `l_count` = '$l_count', `finishing` = '$finishing', `f_count` = '$f_count', `edges` = '$edges', `centipone` = '$centipone', `corner` = '$corner' WHERE `id` = '$id'");

    header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);



}else if ($specification == 'подушки|наволочки|валики') {

    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $width = $_POST['width'];
    if(empty($width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Ширина габаритная / диаметр валика (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Ширина габаритная / диаметр валика (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $length = $_POST['length'];
    if(empty($length)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Длина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $length))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Длина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if(empty($m_count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Количество основной ткани' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $m_count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }
    $pillow = $_POST['pillow'];
    $p_count = $_POST['p_count'];
    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];



    mysqli_query($connect, "UPDATE `sketches_4` SET `vendor_code` = '$vendor_code', `count` = '$count', `width` = '$width', `length` = '$length', `main_cloth` = '$main_cloth', `m_count` = '$m_count', `pillow` = '$pillow', `p_count` = '$p_count', `finishing` = '$finishing', `f_count` = '$f_count' WHERE `id` = '$id'");

    header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);



}else if ($specification == 'сваги|джаботы|ламбрикены') {


    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification .  "&state_add='Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $eaves_width = $_POST['eaves_width'];
    if(empty($eaves_width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина по карнизу (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $eaves_width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина по карнизу (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $height = $_POST['height'];
    if(empty($height)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Высота в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $height))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Высота в см.' должно содержать только цифры и *, /, и тп");
        die;
    }



    $bottom_processing = $_POST['bottom_processing'];
    $velcro = $_POST['velcro'];
    $thermobando = $_POST['thermobando'];
    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if(empty($m_count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $m_count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }
    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];
    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];
    $ribbon = $_POST['ribbon'];



    $from = 'sketches_5';


    mysqli_query($connect, "UPDATE `sketches_5` SET `vendor_code` = '$vendor_code', `count` = '$count', `eaves_width` = '$eaves_width', `height` = '$height', `bottom_processing` = '$bottom_processing', `velcro` = '$velcro', `thermobando` = '$thermobando', `main_cloth` = '$main_cloth', `m_count` = '$m_count', `lining` = '$lining', `l_count` = '$l_count', `finishing` = '$finishing', `f_count` = '$f_count', `ribbon` = '$ribbon' WHERE `id` = '$id'");

    header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);



}else if ($specification == 'скатерти|салфетки') {


    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $width = $_POST['width'];
    if(empty($width)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина габаритная / диаметр валика (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $width))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Ширина габаритная / диаметр валика (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $length = $_POST['length'];
    if(empty($length)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Длина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $length))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Длина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if(empty($m_count)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $m_count))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }
    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];
    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];

    $pattern = '/^[\d* \\/+-.,]+$/';
    $edge = $_POST['edge'];
    if(empty($edge)){
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Обработка края' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $edge))
    {
        header("Location: sketches_room_update_pr_update.php?id=" . $id .  "&id_order=" . $id_order . "&specification=" . $specification . "&state_add='Обработка края' должно содержать только цифры и *, /, и тп");
        die;
    }


    $from = 'sketches_6';


    mysqli_query($connect, "UPDATE `sketches_6` SET `vendor_code` = '$vendor_code', `count` = '$count', `width` = '$width', `length` = '$length', `main_cloth` = '$main_cloth', `m_count` = '$m_count', `lining` = '$lining', `l_count` = '$l_count', `finishing` = '$finishing', `f_count` = '$f_count', `edge` = '$edge' WHERE `id` = '$id'");

    header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);



}






