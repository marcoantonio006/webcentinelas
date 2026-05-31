<?php 

session_start();

$auth = $_SESSION['login'] ?? false;

if(!$auth){
    header('Location: /centinela/index.php');
    exit;
}

return [

    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => '',
    'db_name' => 'centinela',

];