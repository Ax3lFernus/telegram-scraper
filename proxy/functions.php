<?php

require dirname(__DIR__, 1) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();
$dotenv->required('TELEGRAM_API_SERVER_BASE_URL')->notEmpty();

$mimes = new Mimey\MimeTypes;

$baseUrl = rtrim($_ENV['TELEGRAM_API_SERVER_BASE_URL'], '/') . '/';

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
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

function downloadFileToDir($url, $fileDir)
{
    global $mimes;
    $fp = fopen($fileDir, 'w');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $data = curl_exec($ch);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    if (fwrite($fp, $data)) {
        fclose($fp);
        $ext = $mimes->getExtension($contentType) == '' ? '' : '.' . $mimes->getExtension($contentType);
        if ($ext != '') rename($fileDir, $fileDir . $ext);
        return true;
    }
    return false;
}

function zipFolder($path, $zipName)
{
    $rootPath = realpath($path);

    $zip = new ZipArchive();
    $zip->open(dirname($path, 1) . '\\' . $zipName . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
    return $zipName;
}

function getPeerInfo($id)
{
    global $baseUrl, $token;
    $info = curl($baseUrl . 'api/users/' . $token . '/getInfo?peer=' . $id);
    if (key($info->response) == 'Chat') {
        $type = $info->response->Chat->_;
        //Chat di gruppo/Canali
        if (isset($info->response->Chat->title))
            $name = $info->response->Chat->title;
        else
            return null;
    } else {
        $type = $info->response->User->bot ? "bot" : "user";
        //Utente/Bot
        if (isset($info->response->User->first_name)) {
            $name = $info->response->User->first_name;
        } elseif (isset($info->response->User->username)) {
            $name = "@" . $info->response->User->username;
        } else {
            return null;
        }
        if (isset($info->response->User->last_name)) {
            $name .= " " . $info->response->User->last_name;
        }
    }
    $out = ['name' => $name, 'type' => $type];
    return $out;
}

function deleteMadelineSession($token)
{
    global $baseUrl;
    curl($baseUrl . "system/removeSession?session=users/" . $token);
    curl($baseUrl . "system/unlinkSessionFile?session=users/" . $token);
}