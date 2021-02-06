<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_GET['id'])) {
    $token = $_COOKIE['token'];
    $id = $_GET['id'];
    $offset_msg_id = 0;
    $count = 0;
    do {
        $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $id . '&data[offset_id]=' . $offset_msg_id . '&data[offset_date]=0&data[add_offset]=0&data[limit]=100&data[max_id]=0&data[min_id]=0');
        if (count($chat_messages->response->messages) <= 0) break;
        $offset_msg_id = end($chat_messages->response->messages)->id;
        $count += count($chat_messages->response->messages);
        echo "COUNT: " . $count . "<br/>";
        echo "END MSG ID: " . $offset_msg_id . "<br/>-------<br/>";
        sleep(2);
    }while(true);
}else{
    http_response_code(500);
    die();
}
