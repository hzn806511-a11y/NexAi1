<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './index.php';

function insert($table) {
    global $connection;
    $sql = "INSERT INTO `$table` (`name`, `Email`, `password`, `lang`) VALUES ('ali', 'hzn806512@gmail.com', '147280021hzk@', 'Fa')";
    $result = $connection->query($sql);

    if ($result) {
        echo "رکورد با موفقیت درج شد!";
    } else {
        echo "خطا در درج رکورد: " . $connection->errorInfo()[2];
    }
}

insert('users');
?>