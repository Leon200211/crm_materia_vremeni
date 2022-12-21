<?php


#=================================================================================
# Живой поиск по БД
#=================================================================================


$mysqli = new mysqli("127.0.0.1:3307", "root", "root", "crm_materia_vremeni");
#$mysqli = new mysqli("localhost", "a0558948_crud", "2u27Ub9h", "a0558948_crud");


$query = "SELECT * FROM `{$_POST['name']}` WHERE `title` LIKE '%{$_POST['value']}%'";
$result = $mysqli->query($query);


$num_rows = $result->num_rows;
if ($num_rows < 150) {
    $i_f = $num_rows;
} else {
    $i_f = 150;
}


if ($result = $mysqli->query($query)) {
    for ($i = 0; $i < $i_f; $i++) {
        $row = $result->fetch_assoc();
        //echo $row['id'] . ' ' . $row['title'] . ' ' . $row['title'] . ' ' . $row['price/rulon'] . '<br>';
        if($_POST['name'] == 'cloth'){
            echo '<option value=' . $row['id'] . '>' . $row['title'] . ' | ' . $row['collection'] . ' | ' . $row['provider'] . '</option>';
        }else if($_POST['name'] == 'cornices'){
            echo '<option value=' . $row['id'] . '>' . $row['title'] . ' | ' . $row['type'] . ' | ' . $row['provider'] . '</option>';
        }else if($_POST['name'] == 'blinds'){
            echo '<option value=' . $row['id'] . '>' . $row['title'] . ' | ' . $row['provider'] . '</option>';
        }else if($_POST['name'] == 'furniture'){
            echo '<option value=' . $row['id'] . '>' . $row['title'] . ' | ' . $row['collection'] . ' | ' . $row['provider'] . '</option>';
        }else{
            echo '<option value=' . $row['id'] . '>' . $row['title'] . '</option>';
        }
    }
} else{
    echo '<option value=1>' . 7777777777777777777 . '</option>';
}

