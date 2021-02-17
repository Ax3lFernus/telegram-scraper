<?php
require __DIR__ . '/functions.php';

header('Content-Type: application/json');
if (isset($_COOKIE['token']) && isset($_GET['media_num']) && isset($_GET['zip_name'])) {
    $tmpDir = dirname(__DIR__, 1) . '/tmp/' . $_COOKIE['token'];
    $downloadDir = $tmpDir . '/medias';
    if(file_exists($downloadDir)) {
        $files = scandir($downloadDir);
        natsort($files);
        $last_file = preg_replace('/\\.[^.\\s]{3,4}$/', '', end($files));
        $percentage = $last_file / $_GET['media_num'];
        echo '{"percentage": ' . round($percentage * 100) . ', "status" : false}';
    }else{
        if(file_exists($tmpDir . '/' . $_GET['zip_name']))
            echo '{"percentage": 100, "status" : true, "md5": "'. hash_file('md5', $tmpDir . '/' . $_GET['zip_name']) . '", "sha256": "' . hash_file('sha256', $tmpDir . '/' . $_GET['zip_name']) . '"}';
        else
            echo '{"percentage": 0, "status" : false}';
    }
}else{
    echo '{"percentage": 0, "status" : false}';
}