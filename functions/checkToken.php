<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token'])) {
    $token = 'users/' . $_COOKIE['token'];
    $o = curl($baseUrl . 'system/getSessionList');
    $find = false;
    if ($o->success) {
        foreach ($o->response->sessions as $session) {
            if ($session->session == $token && $session->status == "LOGGED_IN") {
                $find = true;
                break;
            }
        }
        if ($find) {
            if (!str_contains($_SERVER['REQUEST_URI'], 'message.php')) {
                header('Location: message.php');
                die();
            }
        } else {
            setcookie("token", "", time() - 3600);
            if (!str_contains($_SERVER['REQUEST_URI'], 'index.php')) {
                header('Location: index.php');
                die();
            }
        }
    }
}else{
    if (!str_contains($_SERVER['REQUEST_URI'], 'index.php')) {
        header('Location: index.php');
        die();
    }
}
