<?php
require __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (isset($_POST['token']) && isset($_POST['code'])) {
    $token = $_POST['token'];
    $code = $_POST['code'];
    $output = curl($baseUrl . "api/users/" . $token . "/completePhoneLogin?code=" . $code);
    if ($output->success)
        echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
    else {
        deleteMadelineSession($token);
        echo json_encode("{\"success\": false, \"error\": \"Wrong verification code or token\"}");
    }
} else {
    if (isset($_POST['tel'])) {
        $tel = $_POST['tel'];
        //Genera token
        $token = generateRandomString(24);
        //Crea la sessione sul TelegramApiServer
        $output = curl($baseUrl . "system/addSession?session=users/" . $token);
        if ($output->success) {
            //Collegamento Cellulare
            $output = curl($baseUrl . "api/users/" . $token . "/phoneLogin?phone=" . $tel);
            if ($output->success)
                echo json_encode("{\"success\": true, \"token\": \"" . $token . "\"}");
            else {
                deleteMadelineSession($token);
                echo json_encode("{\"success\": false, \"error\": \"Error sending verification code\"}");
            }
        } else
            echo json_encode("{\"success\": false, \"error\": \"Error while creating the session\"}");
    } else {
        echo json_encode("{\"success\": false, \"error\": \"No phone number or (code, token) pair passed\"}");
    }
}