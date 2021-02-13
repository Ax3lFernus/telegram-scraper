<?php
ob_start();
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_POST['chats']) && isset($_POST['media'])) {
    $token = $_COOKIE['token'];
    $chats = $_POST['chats'];
    $dataInizio=$_POST['dataInizio'];
    $dataFine=$_POST['dataFine'];
    $dataFine=date("Y-m-d",strtotime("$dataFine +1 day"));
    $messages = array(array('chat_id', 'chat_name', 'author', 'date', 'message', 'media_name'));
    $users_info = array();
    $getMedia = $_POST['media'] == 0 ? false : true;
    $zipName = 'null';
    $media_id = 0;
    if ($getMedia) {
        $media = array();
        $zipName = generateRandomString(15);
    }
    foreach ($chats as $chat) {
        $offset_msg_id = 0;
        do {
            $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $chat['id'] . '&data[offset_id]=' . $offset_msg_id . '&data[offset_date]=' . strtotime($dataFine) . '&data[add_offset]=0&data[limit]=100&data[max_id]=0&data[min_id]=0');
            if (count($chat_messages->response->messages) <= 0) break;
            foreach ($chat_messages->response->messages as $msg) {
                if (isset($msg->action)) continue;
                if (isset($msg->media) && $getMedia) {
                    array_push($media, [$chat['id'], $msg->id, ++$media_id]);
                }
                if ($msg->out) {
                    $author = "Me";
                } elseif ($chat['type'] == "chat") {
                    if (!isset($users_info[$msg->from_id->user_id])) {
                        $info = getPeerInfo($msg->from_id->user_id);
                        $users_info[$msg->from_id->user_id] = ($info == null) ? 'no_name' : $info['name'];
                        sleep(1);
                    }
                    $author = $users_info[$msg->from_id->user_id];
                } else {
                    $author = $chat['name'];
                }
                array_push($messages, [$chat['id'], $chat['name'], $author, date("Y-m-d H:i:s", $msg->date), $msg->message, (isset($msg->media) && $getMedia) ? $media_id : (isset($msg->media) ? 'true' : 'false')]);
            }
            $offset_msg_id = end($chat_messages->response->messages)->id;
            sleep(2);
        } while (true);
    }
    echo json_encode(array($messages, $zipName . '.zip', $media_id));
    $size = ob_get_length();
    header('Content-Type: application/json');
    header("Content-Encoding: none");
    header("Content-Length: {$size}");
    header("Connection: close");
    ob_end_flush();
    ob_flush();
    flush();
    if ($getMedia && count($media) > 0) {
        require __DIR__ . '/downloadMedia.php';
    }else die();
} else {
    http_response_code(500);
    die();
}
