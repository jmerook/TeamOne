<?php
/**
 * Created by PhpStorm.
 * User: Jacob
 * Date: 2/21/18
 * Time: 5:49 PM
 */


$user = 'root';
$password = 'root';
$db = 'clueless';
$host = 'localhost';
$port = 3306;

$link = mysqli_init();
$success = mysqli_real_connect(
    $link,
    $host,
    $user,
    $password,
    $db,
    $port
);

echo $host;
?>