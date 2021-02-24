<?php
if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $chats = curl($baseUrl . 'api/users/' . $token . '/getFullDialogs');
    $chat_list = array();
    if ($chats->success) {
        foreach ($chats->response as $id => $chat) {
            $info = getPeerInfo($id);
            if ($info == null) continue;
            array_push($chat_list, ['name' => $info['name'], 'type' => $info['type'], 'id' => $id, 'peer' => json_decode(json_encode($chat->peer), true)]);
        }
        $chat_list = array_reverse($chat_list);
    }
}