<?php
$tmpDir = dirname(__DIR__, 1) . '\\tmp\\' . $token;

if (file_exists($tmpDir)) {
    array_map( 'unlink', array_filter((array) glob($tmpDir . "/*") ) );
    rmdir($tmpDir);
}
mkdir($tmpDir, 0777, true);

foreach ($media as $m){
    downloadFileToDir($baseUrl . 'api/users/' . $token . '/getMedia?data[peer]=' . $m[0] . '&data[id][]=' . $m[1], $tmpDir . '\\' . $m[2]);
    sleep(3);
}
zipFolder($tmpDir, $zipName);
array_map( 'unlink', array_filter((array) glob($tmpDir . "/*") ) );
rmdir($tmpDir);
die();