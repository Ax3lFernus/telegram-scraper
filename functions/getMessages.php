<?php
ob_start();
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_POST['chats']) && isset($_POST['media']) && isset($_POST['users_groups']) && isset($_POST['filetype']) && isset($_POST['profile_photos'])) {
    $token = $_COOKIE['token'];
    $chats = $_POST['chats'];
    $dataInizio = strtotime($_POST['dataInizio']);
    $dataFine = $_POST['dataFine'];
    $dataFine = date("Y-m-d", strtotime("$dataFine +1 day"));
    $messages = array(array('chat_id', 'chat_name', 'author', 'timestamp', 'date', 'message', 'media_name'));
    $users_info = array();
    $media = array();
    $users_in_groups = array(array('chat_id', 'chat_name', 'chat_type', 'user_id', 'first_name', 'last_name', 'username', 'join_timestamp', 'join_date', 'role'));
    $getMedia = $_POST['media'] == 'true';
    $getUsersInGroups = $_POST['users_groups'] == 'true';
    $getProfilePhotos = $_POST['profile_photos'] == 'true';
    $filetype = $_POST['filetype'] == 0 ? false : true;
    $zipName = generateRandomString(15);
    $media_id = 0;
    $request_date = gmdate("d-m-Y H:i:s");
    $request_date_underscore = gmdate("d-m-Y_H-i-s");

    $tmpDir = dirname(__DIR__, 1) . '/tmp/' . $token;
    $filesDir = $tmpDir . '/files';
    $photoDir = $filesDir . '/profile_photos';

    create_folder($tmpDir);
    create_folder($filesDir);
    if ($getProfilePhotos)
        create_folder($photoDir);

    foreach ($chats as $chat) {
        $offset_msg_id = 0;
        if ($getProfilePhotos) {
            $out = curl($baseUrl . 'api/users/' . $token . '/getPropicInfo?peer[_]=' . $chat['peer']['peerType'] . '&peer[' . $chat['peer']['peerIdType'] . ']=' . $chat['peer']['peerId']);
            if ($out->success) {
                $media_info = $out->response;
                unset($media_info->InputFileLocation->peer);
                $media_info->InputFileLocation->peer = new stdClass();
                $media_info = json_decode(json_encode($media_info), true);
                $media_info['InputFileLocation']['peer'] = array('_' => $chat['peer']['peerType'], $chat['peer']['peerIdType'] => $chat['peer']['peerId']);
                $pictureInfo = json_encode(['media' => $media_info]);
                $picture = curlPOST($baseUrl . 'api/users/' . $token . '/downloadToResponse', $pictureInfo);
                file_put_contents($photoDir . '/' . preg_replace('/\s+/', '_', $chat['name']) . '.jpg', $picture);
            }
        }

        if ($getUsersInGroups) {
            $chat_info = curl($baseUrl . 'api/users/' . $token . '/getPwrChat?id=' . $chat['id']);
            if (isset($chat_info->response->participants)) {
                foreach ($chat_info->response->participants as $participant) {
                    $first_name = isset($participant->user->first_name) ? $participant->user->first_name : '';
                    $last_name = isset($participant->user->last_name) ? $participant->user->last_name : '';
                    $username = isset($participant->user->username) ? $participant->user->username : '';
                    $date = isset($participant->date) ? $participant->date : 0;
                    array_push($users_in_groups, [$chat_info->response->id, $chat_info->response->title, $chat_info->response->type, $participant->user->id, $first_name, $last_name, $username, $date, gmdate("d-m-Y H:i:s", $date), $participant->role]);
                }
            }
        }

        do {
            $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $chat['id'] . '&data[offset_id]=' . $offset_msg_id . '&data[offset_date]=' . strtotime($dataFine) . '&data[add_offset]=0&data[limit]=100&data[max_id]=0&data[min_id]=0');
            if (!isset($chat_messages->response)) die();
            if (count($chat_messages->response->messages) <= 0) break;
            foreach ($chat_messages->response->messages as $msg) {
                if ($msg->date >= $dataInizio) {
                    if (isset($msg->action)) continue;
                    if (isset($msg->media) && $getMedia) {
                        array_push($media, [$chat['id'], $msg->id, ++$media_id]);
                    }
                    if ($msg->out) {
                        $author = "Me";
                    } elseif ($chat['type'] == "chat") {
                        if (!isset($users_info[$msg->from_id->user_id])) {
                            $info = getPeerInfo($msg->from_id->user_id);
                            $users_info[$msg->from_id->user_id] = ($info == null) ? 'no_name' : $info['name'];
                        }
                        $author = $users_info[$msg->from_id->user_id];
                    } else {
                        $author = $chat['name'];
                    }
                    array_push($messages, [$chat['id'], $chat['name'], $author, $msg->date, gmdate("d-m-Y H:i:s", $msg->date), str_replace(array("\n", "\r"), '<br/>', $msg->message), (isset($msg->media) && $getMedia) ? $media_id : (isset($msg->media) ? 'true' : 'false')]);
                }
            }
            $offset_msg_id = end($chat_messages->response->messages)->id;
            sleep(1);
        } while (true);
    }
    if ($filetype) {
        //CSV
        $messages_csv = fopen($filesDir . '/messages_' . $request_date_underscore . '.csv', 'w');
        foreach ($messages as $fields) {
            fputcsv($messages_csv, $fields);
        }
        fclose($messages_csv);
        if ($getUsersInGroups) {
            $users_in_groups_csv = fopen($filesDir . '/users_in_groups_' . $request_date_underscore . '.csv', 'w');
            foreach ($users_in_groups as $fields) {
                fputcsv($users_in_groups_csv, $fields);
            }
            fclose($users_in_groups_csv);
        }
    } else {
        //JSON
        $json_msg = [];
        array_shift($messages);
        foreach ($messages as $fields) {
            array_push($json_msg, ['chat_id' => $fields[0], 'chat_name' => $fields[1], 'author' => $fields[2], 'timestamp' => $fields[3], 'date' => gmdate("d-m-Y H:i:s", $fields[3]), 'message' => $fields[4], 'media_name' => $fields[5]]);
        }
        file_put_contents($filesDir . '/messages_' . $request_date_underscore . '.json', json_encode($json_msg));
        if ($getUsersInGroups) {
            $json_usr = [];
            array_shift($users_in_groups);
            foreach ($users_in_groups as $fields) {
                array_push($json_usr, ['chat_id' => $fields[0], 'chat_name' => $fields[1], 'chat_type' => $fields[2], 'user_id' => $fields[3], 'first_name' => $fields[4], 'last_name' => $fields[5], 'username' => $fields[6], 'join_timestamp' => $fields[7], 'join_date' => gmdate("d-m-Y H:i:s", $fields[7]), 'role' => $fields[8]]);
            }
            file_put_contents($filesDir . '/users_in_groups_' . $request_date_underscore . '.json', json_encode($json_usr));
        }
    }

    if ($getMedia && count($media) > 0) {
        echo json_encode(['report' => ['url' => './tmp/' . $token . '/report_' . $request_date_underscore . '.pdf',
            'name' => 'report_' . $request_date_underscore . '.pdf'],
            'media' => ['zip_name' => $zipName . '_' . $request_date_underscore . '.zip', 'num_media' => $media_id]]);
        $size = ob_get_length();
        header('Content-Type: application/json');
        header("Content-Encoding: none");
        header("Content-Length: {$size}");
        header("Connection: close");
        ob_end_flush();
        ob_flush();
        flush();
        require __DIR__ . '/downloadMedia.php';
        die();
    } else {
        zipFolder($filesDir, $zipName . '_' . $request_date_underscore);
        if (file_exists($filesDir)) {
            delete_directory($filesDir);
        }
        require __DIR__ . '/getReport.php';
        echo json_encode(['report' => ['url' => './tmp/' . $token . '/report_' . $request_date_underscore . '.pdf',
            'md5' => hash_file('md5', $tmpDir . '/report_' . $request_date_underscore .'.pdf'),
            'sha256' => hash_file('sha256', $tmpDir . '/report_' . $request_date_underscore .'.pdf')],
            'zip' => './tmp/' . $token . '/' . $zipName . '_' . $request_date_underscore . '.zip']);
        $size = ob_get_length();
        header('Content-Type: application/json');
        header("Content-Encoding: none");
        header("Content-Length: {$size}");
        header("Connection: close");
        ob_end_flush();
        ob_flush();
        flush();
    }
} else {
    http_response_code(500);
    die();
}
