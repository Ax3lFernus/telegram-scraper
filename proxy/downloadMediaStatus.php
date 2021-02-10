<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_GET['media_num'])) {
    $tmpDir = dirname(__DIR__, 1) . '\\tmp\\' . $_COOKIE['token'];
    $files = scandir($tmpDir);
    natsort($files);
    $last_file = preg_replace('/\\.[^.\\s]{3,4}$/', '', end($files));
    $percentage = $last_file/$_GET['media_num'];
    echo round($percentage*100);
}else{
    return 100;
}