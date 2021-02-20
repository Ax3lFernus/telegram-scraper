<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_GET['peer_id'])) {
    $token = $_COOKIE['token'];
    $id = $_GET['peer_id'];
    $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?peer=' . $id );

    if ($out->success) {
        $pictureInfo = json_encode(['media' => $out->response]);
        $picture = curlPOST($baseUrl . 'api/users/' . $token . '/downloadToResponse', $pictureInfo);
        header("Content-Type: image/jpeg");
        echo $picture;
    } else {
        header("Content-Type: image/png");
        $filename = dirname(__DIR__, 1) . '/assets/images/default_user.png';
        $file = fopen($filename, "rb");
        $contents = fread($file, filesize($filename));
        fclose($file);
        echo $contents;
    }
}