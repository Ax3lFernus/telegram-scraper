<?php
require dirname(__DIR__, 1) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();
$dotenv->required('TELEGRAM_API_SERVER_BASE_URL')->notEmpty();

$baseUrl = rtrim($_ENV['TELEGRAM_API_SERVER_BASE_URL'], '/') . '/';

header('Content-Type: application/json');

if(isset($_POST['token']) && isset($_POST['code'])){
    $token = $_POST['token'];
    $code = $_POST['code'];
    $output = curl($baseUrl . "api/users/" . $token . "/completePhoneLogin?code=" . $code);
    if($output->success)
        echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
    else
        echo json_encode("{\"success\": false, \"error\": \"Wrong verification code or token\"}");
}else{
    if(isset($_POST['tel'])){
        $tel = $_POST['tel'];
        //Genera token
        $token = generateRandomString(24);
        //Crea la sessione sul TelegramApiServer
        $output = curl($baseUrl . "system/addSession?session=users/" . $token);
        if($output->success){
            //Collegamento Cellulare
            $output = curl($baseUrl . "api/users/" . $token . "/phoneLogin?phone=" . $tel);
            if($output->success)
                echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
            else
                echo json_encode("{\"success\": false, \"error\": \"Error sending verification code\"}");
        }else
            echo json_encode("{\"success\": false, \"error\": \"Error while creating the session\"}");
    }else{
        echo json_encode("{\"success\": false, \"error\": \"No phone number or (code, token) pair passed\"}");
    }
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function curl($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output);
}