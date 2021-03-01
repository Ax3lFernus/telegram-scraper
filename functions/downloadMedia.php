<?php
$mediaDir = $filesDir . '/medias';

if (file_exists($mediaDir)) {
    delete_directory($mediaDir);
}
mkdir($mediaDir, 0777, true);

foreach ($media as $m) {
    downloadFileToDir($baseUrl . 'api/users/' . $token . '/getMedia?data[peer]=' . $m[0] . '&data[id][]=' . $m[1], $mediaDir . '/' . $m[2]);
    sleep(1);
}
zipFolder($filesDir, $zipName . '_' . $request_date_underscore);
if (file_exists($filesDir)) {
    delete_directory($filesDir);
}
require __DIR__ . '/getReport.php';