<?php
require dirname(__DIR__, 1) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();
$dotenv->required('TELEGRAM_API_SERVER_BASE_URL')->notEmpty();

$baseUrl = rtrim($_ENV['TELEGRAM_API_SERVER_BASE_URL'], '/') . '/';

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

function curlPOST($url, $body)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function curl($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output);
}