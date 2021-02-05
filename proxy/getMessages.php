<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) /*&& isset($_POST['chats']*/) {
    $token = $_COOKIE['token'];
    $chats = curl($baseUrl . 'api/users/' . $token . '/getFullDialogs');
    foreach ($chats->response as $id => $chat){
        if ($id == -1001026940062) continue;
        $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $id . '&data[offset_id]=0&data[offset_date]=0&data[add_offset]=0&data[limit]=10&data[max_id]=0&data[min_id]=0');
        $messages = array(array('chat_id','chat_name','out', 'date', 'message', 'media_name'));
        foreach ($chat_messages->response->messages as $msg){
            if(isset($msg->action)) continue;
            //if(isset($msg->media)) $msg->message = "MEDIA"; //RECUPERA IL MEDIA
            //PRENDERE ID DELL'UTENTE CHE MANDA IL MESSAGGIO NELLA CHAT DI GRUPPO QUANDO OUT:false
            array_push($messages, [$id, 'nomeChatQUI', $msg->out, date("d-m-Y H:i:s", $msg->date), str_replace('""', '',json_encode($msg->message)), isset($msg->media) ? 'YES' : 'NO']);
        }
        sleep(5);
    }
    print_r($messages);
    //array_to_csv_download($messages,$token . '.csv');
}
