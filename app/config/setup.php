<?php
$DB_DSN = 'mysql:host=mysql;port=3306';
$DB_USER = DB_USER;
$DB_PASSWORD = DB_PASS;
try {
    $conn = new PDO($DB_DSN,$DB_USER,$DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $conn->query(file_get_contents('app/config/camagru.sql'));
}
catch (PDOException $e){
    echo "Setup Error:".$e->getMessage();
}