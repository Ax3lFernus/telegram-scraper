<?php
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_GET['peerIdType']) && isset($_GET['peerType']) && isset($_GET['peerId'])) {
    $token = $_COOKIE['token'];
    $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?peer[_]=' . $_GET['peerType'] . '&peer['. $_GET['peerIdType'] .']=' . $_GET['peerId']);
    if ($out->success) {
        $media_info = $out->response;
        unset($media_info->InputFileLocation->peer);
        $media_info->InputFileLocation->peer = new stdClass();
        $media_info = json_decode(json_encode($media_info), true);
        $media_info['InputFileLocation']['peer'] = array('_' => $_GET['peerType'], $_GET['peerIdType'] => $_GET['peerId']);
        $pictureInfo = json_encode(['media' => $media_info]);
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