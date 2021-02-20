<?php
require __DIR__ . '/functions.php';

header('Content-Type: application/json');
if(isset($_COOKIE['token'])){
    $token = $_COOKIE['token'];
    deleteMadelineSession($token);
    echo json_encode("{\"success\": true}");
}else{
    echo json_encode("{\"success\": false, \"error\": \"No token found in cookies.\"}");
}