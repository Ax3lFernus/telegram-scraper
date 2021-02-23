<?php
$tmpDir = dirname(__DIR__, 1) . '/tmp/' . $token. '/medias';

if (file_exists($tmpDir)) {
    delete_directory($tmpDir);
}
mkdir($tmpDir, 0777, true);

foreach ($media as $m) {
    downloadFileToDir($baseUrl . 'api/users/' . $token . '/getMedia?data[peer]=' . $m[0] . '&data[id][]=' . $m[1], $tmpDir . '/' . $m[2]);
    sleep(2);
}
zipFolder($tmpDir, $zipName . '_medias');
if (file_exists($tmpDir)) {
    delete_directory($tmpDir);
}
die();