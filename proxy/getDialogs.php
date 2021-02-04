<?php
if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $chats = curl($baseUrl . 'api/users/' . $token . '/getFullDialogs');
    $chat_list = array();
    if ($chats->success) {
        foreach ($chats->response as $id => $chat) {
            $info = curl($baseUrl . 'api/users/' . $token . '/getInfo?peer=' . $id);
            if (key($info->response) == 'Chat') {
                //Chat di gruppo/Canali
                $name = $info->response->Chat->title;
            } else {
                //Utente/Bot
                if (isset($info->response->User->first_name)) {
                    $name = $info->response->User->first_name;
                } elseif (isset($info->response->User->username)) {
                    $name = "@" . $info->response->User->username;
                } else {
                    continue;
                }
                if (isset($info->response->User->last_name)) {
                    $name .= " " . $info->response->User->last_name;
                }
            }
            array_push($chat_list, ['name' => $name, 'peerID' => $id]);
        }
    }
}