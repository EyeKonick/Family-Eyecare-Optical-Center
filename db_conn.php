<?php

$sourceName = 'mysql:host=localhost:3307;dbname=eyecare;';
$username = 'root';
$password = 'admin';
$options = [];

try {
    $connection = new PDO($sourceName, $username, $password, $options);
} catch(PDOException $exception) {
    echo $messageFailed = $exception->getMessage();
}