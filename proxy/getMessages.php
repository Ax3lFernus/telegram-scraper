<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_POST['chats'])) {
    $token = $_COOKIE['token'];
    $chats = $_POST['chats'];
    $messages = array(array('chat_id','chat_name','out', 'date', 'message', 'media_name'));
    foreach ($chats as $chat){
        $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $chat['id'] . '&data[offset_id]=0&data[offset_date]=0&data[add_offset]=0&data[limit]=10&data[max_id]=0&data[min_id]=0');
        foreach ($chat_messages->response->messages as $msg){
            if(isset($msg->action)) continue;
            //if(isset($msg->media)) $msg->message = "MEDIA"; //RECUPERA IL MEDIA
            //PRENDERE ID DELL'UTENTE CHE MANDA IL MESSAGGIO NELLA CHAT DI GRUPPO QUANDO OUT:false
            array_push($messages, [$chat['id'], $chat['name'], $msg->out, date("Y-m-d H:i:s", $msg->date), $msg->message, isset($msg->media) ? 'YES' : 'NO']);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
}else{
    http_response_code(500);
    die();
}
