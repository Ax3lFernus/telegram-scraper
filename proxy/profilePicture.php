<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_GET['peer_type']) && isset($_GET['peer_id'])) {
    $token = $_COOKIE['token'];
    $type = $_GET['peer_type'];
    $id = $_GET['peer_id'];
    if($type == 'peerChat'){
        $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?peer[chat_id]=' . $id . '&peer[_]=' . $type);
    }elseif ($type == 'peerUser'){
        $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?peer[user_id]=' . $id . '&peer[_]=' . $type);
    }elseif ($type == 'peerChannel'){
        $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?peer[channel_id]=' . $id . '&peer[_]=' . $type);
    }

    if ($out->success) {
        $pictureInfo = json_encode(['media' => $out->response]);
        $picture = curlPOST($baseUrl . 'api/users/' . $token . '/downloadToResponse', $pictureInfo);
        header("Content-Type: image/jpeg");
        echo $picture;
    } else {
        http_response_code(404);
        die();
    }
}