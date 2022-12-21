<?php


#========================================================================
# Добавление строки в одну из страниц эсказа
#========================================================================




session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';



// достаем инфу из sketches_main по ключу его id
$id_in_sketches = $_POST['id_in_sketches'];
$sql_sketches_main = "SELECT `specification` FROM `sketches_main` WHERE `id` = '$id_in_sketches'";
$sketches_main = mysqli_query($connect, $sql_sketches_main);
$sketches_main = mysqli_fetch_assoc($sketches_main);
$specification = $sketches_main['specification'];


function return_error($spec, $id, $error){

    if($spec == 1){
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">

            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">

        </head>
        <body style="background-color: #F3F3F3">
            <div class="find_info">
                <div class="body_result">
                    <div class="body_result_title">Ошибка <?=$error?></div>
                    <div>
                        <form action="sketches_room_update.php" method="post">
                            <input value="<?=$id?>" hidden name="id_in_sketches">
                            <input value="<?=@$_POST['vendor_code']?>" hidden name="vendor_code">
                            <input value="<?=@$_POST['count']?>" hidden name="count">
                            <input value="<?=@$_POST['assembled_width']?>" hidden name="assembled_width">
                            <input value="<?=@$_POST['coefficient']?>" hidden name="coefficient">
                            <input value="<?=@$_POST['unfolded_width']?>" hidden name="unfolded_width">
                            <input value="<?=@$_POST['height']?>" hidden name="height">
                            <input value="<?=@$_POST['scallop']?>" hidden name="scallop">
                            <input value="<?=@$_POST['main_cloth']?>" hidden name="main_cloth">
                            <input value="<?=@$_POST['m_count']?>" hidden name="m_count">
                            <input value="<?=@$_POST['lining']?>" hidden name="lining">
                            <input value="<?=@$_POST['l_count']?>" hidden name="l_count">
                            <input value="<?=@$_POST['finishing']?>" hidden name="finishing">
                            <input value="<?=@$_POST['f_count']?>" hidden name="f_count">
                            <input value="<?=@$_POST['bottom']?>" hidden name="bottom">
                            <input value="<?=@$_POST['sides']?>" hidden name="sides">
                            <input value="<?=@$_POST['bend']?>" hidden name="bend">
                            <input value="<?=@$_POST['ribbon']?>" hidden name="ribbon">

                            <input type="submit" class="common_back_href" value="Назад">
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
    else if($spec == 2){
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">
            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        </head>
        <body>
        <div class="find_info">
            <div class="body_result">
                <div class="body_result_title">Ошибка <?=$error?></div>
                <div>
                    <form action="sketches_room_update.php" method="post">
                        <input value="<?=$id?>" hidden name="id_in_sketches">
                        <input value="<?=@$_POST['vendor_code']?>" hidden name="vendor_code">
                        <input value="<?=@$_POST['count']?>" hidden name="count">
                        <input value="<?=@$_POST['eaves_width']?>" hidden name="eaves_width">
                        <input value="<?=@$_POST['height']?>" hidden name="height">
                        <input value="<?=@$_POST['scallop']?>" hidden name="scallop">
                        <input value="<?=@$_POST['main_cloth']?>" hidden name="main_cloth">
                        <input value="<?=@$_POST['m_count']?>" hidden name="m_count">
                        <input value="<?=@$_POST['lining']?>" hidden name="lining">
                        <input value="<?=@$_POST['l_count']?>" hidden name="l_count">
                        <input value="<?=@$_POST['finishing']?>" hidden name="finishing">
                        <input value="<?=@$_POST['f_count']?>" hidden name="f_count">
                        <input value="<?=@$_POST['count_fib']?>" hidden name="count_fib">
                        <input value="<?=@$_POST['count_drums']?>" hidden name="count_drums">

                        <input type="submit" class="common_back_href" value="Назад">
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
    else if($spec == 3){
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">
            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        </head>
        <body>
        <div class="find_info">
            <div class="body_result">
                <div class="body_result_title">Ошибка <?=$error?></div>
                <div>
                    <form action="sketches_room_update.php" method="post">
                        <input value="<?=$id?>" hidden name="id_in_sketches">
                        <input value="<?=@$_POST['vendor_code']?>" hidden name="vendor_code">
                        <input value="<?=@$_POST['count']?>" hidden name="count">
                        <input value="<?=@$_POST['width']?>" hidden name="width">
                        <input value="<?=@$_POST['length']?>" hidden name="length">
                        <input value="<?=@$_POST['stitch']?>" hidden name="stitch">
                        <input value="<?=@$_POST['stitch_name']?>" hidden name="stitch_name">
                        <input value="<?=@$_POST['stitch_step']?>" hidden name="stitch_step">
                        <input value="<?=@$_POST['main_cloth']?>" hidden name="main_cloth">
                        <input value="<?=@$_POST['m_count']?>" hidden name="m_count">
                        <input value="<?=@$_POST['lining']?>" hidden name="lining">
                        <input value="<?=@$_POST['l_count']?>" hidden name="l_count">
                        <input value="<?=@$_POST['finishing']?>" hidden name="finishing">
                        <input value="<?=@$_POST['f_count']?>" hidden name="f_count">
                        <input value="<?=@$_POST['edges']?>" hidden name="edges">
                        <input value="<?=@$_POST['centipone']?>" hidden name="centipone">
                        <input value="<?=@$_POST['corner']?>" hidden name="corner">

                        <td class="tb_title_info"><input type="submit" class="common_back_href" value="Назад"></td>
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
    else if($spec == 4){
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">
            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        </head>
        <body>
        <div class="find_info">
            <div class="body_result">
                <div class="body_result_title">Ошибка <?=$error?></div>
                <div>
                    <form action="sketches_room_update.php" method="post">
                        <input value="<?=$id?>" hidden name="id_in_sketches">
                        <input value="<?=@$_POST['vendor_code']?>" hidden name="vendor_code">
                        <input value="<?=@$_POST['count']?>" hidden name="count">
                        <input value="<?=@$_POST['width']?>" hidden name="width">
                        <input value="<?=@$_POST['length']?>" hidden name="length">
                        <input value="<?=@$_POST['main_cloth']?>" hidden name="main_cloth">
                        <input value="<?=@$_POST['m_count']?>" hidden name="m_count">
                        <input value="<?=@$_POST['pillow']?>" hidden name="pillow">
                        <input value="<?=@$_POST['p_count']?>" hidden name="p_count">
                        <input value="<?=@$_POST['finishing']?>" hidden name="finishing">
                        <input value="<?=@$_POST['f_count']?>" hidden name="f_count">
                        <input type="submit" class="common_back_href" value="Назад">
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
    else if($spec == 5){
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">
            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        </head>
        <body>
        <div class="find_info">
            <div class="body_result">
                <div class="body_result_title">Ошибка <?=$error?></div>
                <div>
                    <form action="sketches_room_update.php" method="post">
                        <input value="<?=$id?>" hidden name="id_in_sketches">
                        <input value="<?=@$_POST['vendor_code']?>" hidden name="vendor_code">
                        <input value="<?=@$_POST['count']?>" hidden name="count">
                        <input value="<?=@$_POST['eaves_width']?>" hidden name="eaves_width">
                        <input value="<?=@$_POST['height']?>" hidden name="height">
                        <input value="<?=@$_POST['bottom_processing']?>" hidden name="bottom_processing">
                        <input value="<?=@$_POST['velcro']?>" hidden name="velcro">
                        <input value="<?=@$_POST['thermobando']?>" hidden name="thermobando">
                        <input value="<?=@$_POST['main_cloth']?>" hidden name="main_cloth">
                        <input value="<?=@$_POST['m_count']?>" hidden name="m_count">
                        <input value="<?=@$_POST['lining']?>" hidden name="lining">
                        <input value="<?=@$_POST['l_count']?>" hidden name="l_count">
                        <input value="<?=@$_POST['finishing']?>" hidden name="finishing">
                        <input value="<?=@$_POST['f_count']?>" hidden name="f_count">
                        <input value="<?=@$_POST['ribbon']?>" hidden name="ribbon">
                        <input type="submit" class="common_back_href" value="Назад">
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
    else if($spec == 6){
        ?>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">
            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        </head>
        <body>
        <div class="find_info">
            <div class="body_result">
                <div class="body_result_title">Ошибка <?=$error?></div>
                <div>
                    <form action="sketches_room_update.php" method="post">
                        <input value="<?=$id?>" hidden name="id_in_sketches">
                        <input value="<?=@$_POST['vendor_code']?>" hidden name="vendor_code">
                        <input value="<?=@$_POST['count']?>" hidden name="count">
                        <input value="<?=@$_POST['width']?>" hidden name="eaves_width">
                        <input value="<?=@$_POST['length']?>" hidden name="height">
                        <input value="<?=@$_POST['main_cloth']?>" hidden name="main_cloth">
                        <input value="<?=@$_POST['m_count']?>" hidden name="m_count">
                        <input value="<?=@$_POST['lining']?>" hidden name="lining">
                        <input value="<?=@$_POST['l_count']?>" hidden name="l_count">
                        <input value="<?=@$_POST['finishing']?>" hidden name="finishing">
                        <input value="<?=@$_POST['f_count']?>" hidden name="f_count">
                        <input value="<?=@$_POST['edge']?>" hidden name="edge">
                        <input type="submit" class="common_back_href" value="Назад">
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }

    die;
}



if($specification == 'портьеры|тюли|подхваты|тп'){

    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        return_error(1, $id_in_sketches, "'Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';

    $count = $_POST['count'];
    if(empty($count)){
        return_error(1, $id_in_sketches, "'Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        return_error(1, $id_in_sketches, "'Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $assembled_width = $_POST['assembled_width'];
    if(empty($assembled_width)){
        return_error(1, $id_in_sketches, "'Ширина в собр. виде' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $assembled_width))
    {
        return_error(1, $id_in_sketches, "'Ширина в собр. виде' должно содержать только цифры и *, /, и тп");
        die;
    }
    $coefficient = $_POST['coefficient'];
    if(empty($coefficient)){
        return_error(1, $id_in_sketches, "'Коэф. сборки' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $coefficient))
    {
        return_error(1, $id_in_sketches, "'Коэф. сборки' должно содержать только цифры и *, /, и тп");
        die;
    }
    $unfolded_width = $_POST['unfolded_width'];
    if(empty($unfolded_width)){
        return_error(1, $id_in_sketches, "'Ширина в разверн. виде (крой) в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $unfolded_width))
    {
        return_error(1, $id_in_sketches, "'Ширина в разверн. виде (крой) в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $height = $_POST['height'];
    if(empty($height)){
        return_error(1, $id_in_sketches, "'Высота в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $height))
    {
        return_error(1, $id_in_sketches, "'Высота в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $scallop = $_POST['scallop'];
    if(empty($scallop)){
        return_error(1, $id_in_sketches, "'Гребешок' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $scallop))
    {
        return_error(1, $id_in_sketches, "'Гребешок' должно содержать только цифры и *, /, и тп");
        die;
    }



    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];

    $pattern = '/^[\d.,]+$/';
    if($main_cloth != "" and empty($m_count)){
        return_error(1, $id_in_sketches, "'Количество основной ткани' не должно быть пустым");
        die;
    }
    if($main_cloth != "" and !preg_match($pattern, $m_count))
    {
        return_error(1, $id_in_sketches, "'Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }

    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];

    $pattern = '/^[\d.,]+$/';
    if($lining != "" and empty($l_count)){
        return_error(1, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($lining != "" and !preg_match($pattern, $l_count))
    {
        return_error(1, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }

    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];


    $pattern = '/^[\d.,]+$/';
    if($finishing != "" and empty($f_count)){
        return_error(1, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($finishing != "" and !preg_match($pattern, $f_count))
    {
        return_error(1, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }


    $pattern = '/^[\d* \\/+-.,]+$/';
    $bottom = $_POST['bottom'];
    if(empty($bottom)){
        return_error(1, $id_in_sketches, "'Низ в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $bottom))
    {
        return_error(1, $id_in_sketches, "'Низ в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $sides = $_POST['sides'];
    if(empty($sides)){
        return_error(1, $id_in_sketches, "'Бока в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $sides))
    {
        return_error(1, $id_in_sketches, "'Бока в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $bend = $_POST['bend'];
    if(empty($bend)){
        return_error(1, $id_in_sketches, "'Тех-загиб бок. в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $bend))
    {
        return_error(1, $id_in_sketches, "'Тех-загиб бок. в см.' должно содержать только цифры и *, /, и тп");
        die;
    }

    $ribbon = $_POST['ribbon'];

    $sql = "SELECT `id_paragraph` FROM `sketches_1` WHERE `id_sketches_main` = '$id_in_sketches' ORDER BY `id_paragraph` DESC LIMIT 1";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    if(isset($select)){
        $id_paragraph = $select['id_paragraph'] + 1;
        $sql_req = "INSERT INTO `sketches_1` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `assembled_width`, `coefficient`, `unfolded_width`, `height`, `scallop`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `bottom`, `sides`, `bend`, `ribbon`) VALUES     (NULL, '$id_in_sketches', '$id_paragraph', '$vendor_code', '$count', '$assembled_width', '$coefficient', '$unfolded_width','$height', '$scallop', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$bottom', '$sides', '$bend', '$ribbon')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    } else {
        $sql_req = "INSERT INTO `sketches_1` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `assembled_width`, `coefficient`, `unfolded_width`, `height`, `scallop`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `bottom`, `sides`, `bend`, `ribbon`) VALUES     (NULL, '$id_in_sketches', '1', '$vendor_code', '$count', '$assembled_width', '$coefficient', '$unfolded_width','$height', '$scallop', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$bottom', '$sides', '$bend', '$ribbon')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    }
}
else if ($specification == 'римские|франц|австрийск|тп') {

    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        return_error(2, $id_in_sketches, "'Изделия' не должно быть пустым");

        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';

    $count = $_POST['count'];
    if(empty($count)){
        return_error(2, $id_in_sketches, "'Количество в шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        return_error(2, $id_in_sketches, "Количество в шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $eaves_width = $_POST['eaves_width'];
    if(empty($eaves_width)){
        return_error(2, $id_in_sketches, "'Ширина по карнизу (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $eaves_width))
    {
        return_error(2, $id_in_sketches, "'Ширина по карнизу (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $height = $_POST['height'];
    if(empty($height)){
        return_error(2, $id_in_sketches, "'Высота изделия (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $height))
    {
        return_error(2, $id_in_sketches, "'Высота изделия (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $scallop = $_POST['scallop'];
    if(empty($scallop)){
        return_error(2, $id_in_sketches, "'Гребешок' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $scallop))
    {
        return_error(2, $id_in_sketches, "'Гребешок' должно содержать только цифры и *, /, и тп");
        die;
    }


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if($main_cloth != "" and empty($m_count)){
        return_error(2, $id_in_sketches, "'Количество основной ткани' не должно быть пустым");
        die;
    }
    if($main_cloth != "" and !preg_match($pattern, $m_count))
    {
        return_error(2, $id_in_sketches, "'Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }


    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];

    $pattern = '/^[\d.,]+$/';
    if($lining != "" and empty($l_count)){
        return_error(2, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($lining != "" and !preg_match($pattern, $l_count))
    {
        return_error(2, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }

    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];


    $pattern = '/^[\d.,]+$/';
    if($finishing != "" and empty($f_count)){

        return_error(2, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($finishing != "" and !preg_match($pattern, $f_count))
    {
        return_error(2, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count_fib = $_POST['count_fib'];
    if(empty($count_fib)){
        return_error(2, $id_in_sketches, "'Тех-загиб бок. в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count_fib))
    {
        return_error(2, $id_in_sketches, "'Тех-загиб бок. в см.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $count_drums = $_POST['count_drums'];
    if(empty($count_drums)){
        return_error(2, $id_in_sketches, "'Шт. лента' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count_drums))
    {
        return_error(2, $id_in_sketches, "'Шт. лента' должно содержать только цифры и *, /, и тп");
        die;
    }


    $sql = "SELECT `id_paragraph` FROM `sketches_2` WHERE `id_sketches_main` = '$id_in_sketches' ORDER BY `id_paragraph` DESC LIMIT 1";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    if(isset($select)){
        $id_paragraph = $select['id_paragraph'] + 1;
        $sql_req = "INSERT INTO `sketches_2` (`id`, `id_sketches_main`,  `id_paragraph`, `vendor_code`, `count`, `eaves_width`, `height`, `scallop`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `count_fib`, `count_drums`) VALUES (NULL, '$id_in_sketches', '$id_paragraph', '$vendor_code', '$count', '$eaves_width', '$height', '$scallop', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$count_fib', '$count_drums')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    } else {
        $sql_req = "INSERT INTO `sketches_2` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `eaves_width`, `height`, `scallop`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `count_fib`, `count_drums`) VALUES (NULL, '$id_in_sketches', '1', '$vendor_code', '$count', '$eaves_width', '$height', '$scallop', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$count_fib', '$count_drums')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    }
}
else if ($specification == 'покрывала') {


    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        return_error(3, $id_in_sketches, "'Изделия' не должно быть пустым");
        die;
    }


    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        return_error(3, $id_in_sketches, "'Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        return_error(3, $id_in_sketches, "'Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $width = $_POST['width'];
    if(empty($width)){
        return_error(3, $id_in_sketches, "'Ширина габаритная (см)' не должно быть пустым");

        die;
    }
    if(!preg_match($pattern, $width))
    {
        return_error(3, $id_in_sketches, "'Ширина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $length = $_POST['length'];
    if(empty($length)){
        return_error(3, $id_in_sketches, "'Длина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $length))
    {
        return_error(3, $id_in_sketches, "'Длина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $stitch = $_POST['stitch'];
    $stitch_name = $_POST['stitch_name'];


    $stitch_step = $_POST['stitch_step'];
    if(empty($stitch_step)){
        return_error(3, $id_in_sketches, "'Шаг стежки' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $stitch_step))
    {
        return_error(3, $id_in_sketches, "'Шаг стежки' должно содержать только цифры и *, /, и тп");
        die;
    }

    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if($main_cloth != "" and empty($m_count)){
        return_error(3, $id_in_sketches, "'Количество основной ткани' не должно быть пустым");
        die;
    }
    if($main_cloth != "" and !preg_match($pattern, $m_count))
    {
        return_error(3, $id_in_sketches, "'Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }


    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];

    $pattern = '/^[\d.,]+$/';
    if($lining != "" and empty($l_count)){
        return_error(3, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($lining != "" and !preg_match($pattern, $l_count))
    {
        return_error(3, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }

    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];


    $pattern = '/^[\d.,]+$/';
    if($finishing != "" and empty($f_count)){

        return_error(3, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($finishing != "" and !preg_match($pattern, $f_count))
    {
        return_error(3, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }


    $edges = $_POST['edges'];


    $centipone = $_POST['centipone'];

    $pattern = '/^[\d* \\/+-.,]+$/';
    $corner = $_POST['corner'];
    if(empty($corner)){
        return_error(3, $id_in_sketches, "'Синтепон (100/200г)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $corner))
    {
        return_error(3, $id_in_sketches, "'Синтепон (100/200г)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $sql = "SELECT * FROM `sketches_3` WHERE `id_sketches_main` = '$id_in_sketches' ORDER BY `id_paragraph` DESC LIMIT 1";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    if(isset($select)){
        $id_paragraph = $select['id_paragraph'] + 1;
        $sql_req = "INSERT INTO `sketches_3` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `width`, `length`, `stitch`, `stitch_name`, `stitch_step`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `edges`, `centipone`, `corner`) VALUES (NULL, '$id_in_sketches', '$id_paragraph', '$vendor_code', '$count', '$width', '$length', '$stitch', '$stitch_name', '$stitch_step', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$edges', '$centipone', '$corner')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    } else {
        $sql_req = "INSERT INTO `sketches_3` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `width`, `length`, `stitch`, `stitch_name`, `stitch_step`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `edges`, `centipone`, `corner`) VALUES (NULL, '$id_in_sketches', '1', '$vendor_code', '$count', '$width', '$length', '$stitch', '$stitch_name', '$stitch_step', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$edges', '$centipone', '$corner')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    }
}
else if ($specification == 'подушки|наволочки|валики') {


    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        return_error(4, $id_in_sketches, "'Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        return_error(4, $id_in_sketches, "'Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        return_error(4, $id_in_sketches, "Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $width = $_POST['width'];
    if(empty($width)){
        return_error(4, $id_in_sketches, "'Ширина габаритная / диаметр валика (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $width))
    {
        return_error(4, $id_in_sketches, "'Ширина габаритная / диаметр валика (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $length = $_POST['length'];
    if(empty($length)){
        return_error(4, $id_in_sketches, "'Длина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $length))
    {
        return_error(4, $id_in_sketches, "'Длина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if($main_cloth != "" and empty($m_count)){
        return_error(4, $id_in_sketches, "'Количество основной ткани' не должно быть пустым");
        die;
    }
    if($main_cloth != "" and !preg_match($pattern, $m_count))
    {
        return_error(4, $id_in_sketches, "'Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }

    $pillow = $_POST['pillow'];
    $p_count = $_POST['p_count'];


    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];

    if($finishing != "" and empty($f_count)){
        return_error(4, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($finishing != "" and !preg_match($pattern, $f_count))
    {
        return_error(4, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }



    $sql = "SELECT * FROM `sketches_4` WHERE `id_sketches_main` = '$id_in_sketches' ORDER BY `id_paragraph` DESC LIMIT 1";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    if(isset($select)){
        $id_paragraph = $select['id_paragraph'] + 1;
        $sql_req = "INSERT INTO `sketches_4` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `width`, `length`, `main_cloth`, `m_count`, `pillow`, `p_count`, `finishing`, `f_count`) VALUES (NULL, '$id_in_sketches', '$id_paragraph', '$vendor_code', '$count', '$width', '$length', '$main_cloth', '$m_count', '$pillow', '$p_count', '$finishing', '$f_count')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    } else {
        $sql_req = "INSERT INTO `sketches_4` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `width`, `length`, `main_cloth`, `m_count`, `pillow`, `p_count`, `finishing`, `f_count`) VALUES (NULL, '$id_in_sketches', '1', '$vendor_code', '$count', '$width', '$length', '$main_cloth', '$m_count', '$pillow', '$p_count', '$finishing', '$f_count')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    }

}
else if ($specification == 'сваги|джаботы|ламбрикены') {

    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        return_error(5, $id_in_sketches, "'Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        return_error(5, $id_in_sketches, "'Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        return_error(5, $id_in_sketches, "'Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $eaves_width = $_POST['eaves_width'];
    if(empty($eaves_width)){
        return_error(5, $id_in_sketches, "'Ширина по карнизу (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $eaves_width))
    {
        return_error(5, $id_in_sketches, "'Ширина по карнизу (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $height = $_POST['height'];
    if(empty($height)){
        return_error(5, $id_in_sketches, "'Высота в см.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $height))
    {
        return_error(5, $id_in_sketches, "'Высота в см.' должно содержать только цифры и *, /, и тп");
        die;
    }



    $bottom_processing = $_POST['bottom_processing'];
    $velcro = $_POST['velcro'];
    $thermobando = $_POST['thermobando'];


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if($main_cloth != "" and empty($m_count)){
        return_error(5, $id_in_sketches, "'Количество основной ткани' не должно быть пустым");
        die;
    }
    if($main_cloth != "" and !preg_match($pattern, $m_count))
    {
        return_error(5, $id_in_sketches, "'Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }


    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];

    $pattern = '/^[\d.,]+$/';
    if($lining != "" and empty($l_count)){
        return_error(5, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($lining != "" and !preg_match($pattern, $l_count))
    {
        return_error(5, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }

    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];


    $pattern = '/^[\d.,]+$/';
    if($finishing != "" and empty($f_count)){

        return_error(5, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($finishing != "" and !preg_match($pattern, $f_count))
    {
        return_error(5, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }



    $ribbon = $_POST['ribbon'];




    $sql = "SELECT * FROM `sketches_5` WHERE `id_sketches_main` = '$id_in_sketches' ORDER BY `id_paragraph` DESC LIMIT 1";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    if(isset($select)){
        $id_paragraph = $select['id_paragraph'] + 1;
        $sql_req = "INSERT INTO `sketches_5` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `eaves_width`, `height`, `bottom_processing`, `velcro`, `thermobando`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `ribbon`) VALUES (NULL, '$id_in_sketches', '$id_paragraph', '$vendor_code', '$count', '$eaves_width', '$height', '$bottom_processing', '$velcro', '$thermobando', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$ribbon')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    } else {
        $sql_req = "INSERT INTO `sketches_5` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `eaves_width`, `height`, `bottom_processing`, `velcro`, `thermobando`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `ribbon`) VALUES (NULL, '$id_in_sketches', '1', '$vendor_code', '$count', '$eaves_width', '$height', '$bottom_processing', '$velcro', '$thermobando', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$ribbon')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    }


}
else if ($specification == 'скатерти|салфетки') {

    $vendor_code = $_POST['vendor_code'];
    if(empty($vendor_code)){
        return_error(6, $id_in_sketches, "'Изделия' не должно быть пустым");
        die;
    }

    $pattern = '/^[\d* \\/+-.,]+$/';
    $count = $_POST['count'];
    if(empty($count)){
        return_error(6, $id_in_sketches, "'Количество шт.' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $count))
    {
        return_error(6, $id_in_sketches, "'Количество шт.' должно содержать только цифры и *, /, и тп");
        die;
    }
    $width = $_POST['width'];
    if(empty($width)){
        return_error(6, $id_in_sketches, "'Ширина габаритная / диаметр валика (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $width))
    {
        return_error(6, $id_in_sketches, "'Ширина габаритная / диаметр валика (см)' должно содержать только цифры и *, /, и тп");
        die;
    }
    $length = $_POST['length'];
    if(empty($length)){
        return_error(6, $id_in_sketches, "'Длина габаритная (см)' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $length))
    {
        return_error(6, $id_in_sketches, "'Длина габаритная (см)' должно содержать только цифры и *, /, и тп");
        die;
    }


    $main_cloth = $_POST['main_cloth'];
    $m_count = $_POST['m_count'];
    $pattern = '/^[\d.,]+$/';
    if($main_cloth != "" and empty($m_count)){
        return_error(6, $id_in_sketches, "'Количество основной ткани' не должно быть пустым");
        die;
    }
    if($main_cloth != "" and !preg_match($pattern, $m_count))
    {
        return_error(6, $id_in_sketches, "'Количество основной ткани' должно содержать только цифры и *, /, и тп");
        die;
    }


    $lining = $_POST['lining'];
    $l_count = $_POST['l_count'];

    $pattern = '/^[\d.,]+$/';
    if($lining != "" and empty($l_count)){
        return_error(6, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($lining != "" and !preg_match($pattern, $l_count))
    {
        return_error(6, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }

    $finishing = $_POST['finishing'];
    $f_count = $_POST['f_count'];


    $pattern = '/^[\d.,]+$/';
    if($finishing != "" and empty($f_count)){

        return_error(6, $id_in_sketches, "'Количество' не должно быть пустым");
        die;
    }
    if($finishing != "" and !preg_match($pattern, $f_count))
    {
        return_error(6, $id_in_sketches, "'Количество' должно содержать только цифры и *, /, и тп");
        die;
    }


    $pattern = '/^[\d* \\/+-.,]+$/';
    $edge = $_POST['edge'];
    if(empty($edge)){
        return_error(6, $id_in_sketches, "'Обработка края' не должно быть пустым");
        die;
    }
    if(!preg_match($pattern, $edge))
    {
        return_error(6, $id_in_sketches, "'Обработка края' должно содержать только цифры и *, /, и тп");
        die;
    }


    $sql = "SELECT * FROM `sketches_6` WHERE `id_sketches_main` = '$id_in_sketches' ORDER BY `id_paragraph` DESC LIMIT 1";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    if(isset($select)){
        $id_paragraph = $select['id_paragraph'] + 1;
        $sql_req = "INSERT INTO `sketches_6` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `width`, `length`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `edge`) VALUES  (NULL, '$id_in_sketches', '$id_paragraph', '$vendor_code', '$count', '$width', '$length', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$edge')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    } else {
        $sql_req = "INSERT INTO `sketches_6` (`id`, `id_sketches_main`, `id_paragraph`, `vendor_code`, `count`, `width`, `length`, `main_cloth`, `m_count`, `lining`, `l_count`, `finishing`, `f_count`, `edge`) VALUES  (NULL, '$id_in_sketches', '1', '$vendor_code', '$count', '$width', '$length', '$main_cloth', '$m_count', '$lining', '$l_count', '$finishing', '$f_count', '$edge')";
        mysqli_query($connect, $sql_req);
        header("Location: sketches_room_update.php?id_in_sketches=$id_in_sketches");
    }
}







