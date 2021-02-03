<?php
if(isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $chats = curl($baseUrl . 'api/users/' . $token . '/getDialogs');
    $chat_list = array();
    if($chats->success) {
        foreach ($chats->response as $chat) {
            $type = $chat->_;
            if ($type == 'peerChat') {
                //Chat di gruppo
                $id = $chat->chat_id;
                $info = curl($baseUrl . 'api/users/' . $token . '/getInfo?peer[chat_id]=' . $id . '&peer[_]=' . $type);
                $name = $info->response->Chat->title;
            } elseif ($type == 'peerUser') {
                //Utente/Bot
                $id = $chat->user_id;
                $info = curl($baseUrl . 'api/users/' . $token . '/getInfo?peer[user_id]=' . $id . '&peer[_]=' . $type);
                if(isset($info->response->User->first_name)){
                    $name = $info->response->User->first_name;
                }else{
                    $name = "@" . $info->response->User->username;
                }
                if(isset($info->response->User->last_name)){
                    $name .= " " . $info->response->User->last_name;
                }
            } elseif ($type == 'peerChannel') {
                //Canale
                $id = $chat->channel_id;
                $info = curl($baseUrl . 'api/users/' . $token . '/getInfo?peer[channel_id]=' . $id . '&peer[_]=' . $type);
                $name = $info->response->Chat->title;
            }
            array_push($chat_list, ['name' => $name, 'peerType' => $type, 'peerID' => $id]);
        }
    }
}