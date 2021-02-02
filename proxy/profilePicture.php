<?php
require __DIR__ . '/functions.php';

if (isset($_GET['token']) && isset($_GET['username'])) {
    $token = $_GET['token'];
    $username = $_GET['username'];
    $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?username=' . $username);
    if ($out->success) {
        $pictureInfo = json_encode(['media' => $out->response]);
        $picture = curlPost($baseUrl . 'api/users/' . $token . '/downloadToResponse', $pictureInfo);
        header("Content-Type: image/jpeg");
        echo $picture;
    } else {
        header('Content-Type: application/json');
        echo json_encode("{\"success\": false, \"error\": \"Error requesting user's profile picture\"}");
    }
}