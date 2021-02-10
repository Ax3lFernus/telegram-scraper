<?php
ob_start();
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_POST['chats']) && isset($_POST['media'])) {
    $token = $_COOKIE['token'];
    $chats = $_POST['chats'];
    $messages = array(array('chat_id','chat_name','out', 'date', 'message', 'media_name'));
    $getMedia = $_POST['media'] == 0 ? false : true;
    if($getMedia) {
        $media = array();
        $media_id = 0;
        $zipName = generateRandomString(15);
    }
    foreach ($chats as $chat){
            $offset_msg_id = 0;
            do {
                $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $chat['id'] . '&data[offset_id]=' . $offset_msg_id . '&data[offset_date]=0&data[add_offset]=0&data[limit]=100&data[max_id]=0&data[min_id]=0');
                if (count($chat_messages->response->messages) <= 0) break;
                foreach ($chat_messages->response->messages as $msg) {
                    if (isset($msg->action)) continue;
                    if(isset($msg->media) && $getMedia) {
                        array_push($media, [$chat['id'], $msg->id, ++$media_id]);
                    }
                    //PRENDERE ID DELL'UTENTE CHE MANDA IL MESSAGGIO NELLA CHAT DI GRUPPO QUANDO OUT:false
                    array_push($messages, [$chat['id'], $chat['name'], $msg->out, date("Y-m-d H:i:s", $msg->date), $msg->message, isset($msg->media) ? 'YES' : 'NO']);
                }
                $offset_msg_id = end($chat_messages->response->messages)->id;
                sleep(3);
            }while(true);
    }
    echo json_encode($messages);
    $size = ob_get_length();
    header('Content-Type: application/json');
    header("Content-Encoding: none");
    header("Content-Length: {$size}");
    header("Connection: close");
    ob_end_flush();
    ob_flush();
    flush();
    if($getMedia){
        require  __DIR__ . '/downloadMedia.php';
    }
}else{
    http_response_code(500);
    die();
}
