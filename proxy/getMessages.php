<?php
ob_start();
require __DIR__ . '/functions.php';

if (isset($_COOKIE['token']) && isset($_POST['chats']) && isset($_POST['media']) && isset($_POST['users_groups']) && isset($_POST['filetype'])) {
    $token = $_COOKIE['token'];
    $chats = $_POST['chats'];
    $dataInizio = $_POST['dataInizio'];
    $dataFine = $_POST['dataFine'];
    $dataFine = date("Y-m-d", strtotime("$dataFine +1 day"));
    $messages = array(array('chat_id', 'chat_name', 'author', 'date', 'message', 'media_name'));
    $users_info = array();
    $users_in_groups = array(array('chat_id', 'chat_name', 'chat_type', 'user_id', 'first_name', 'last_name', 'username', 'join_date', 'role'));
    $getMedia = $_POST['media'] == 0 ? false : true;
    $getUsersInGroups = $_POST['users_groups'] == 0 ? false : true;
    $filetype = $_POST['filetype'] == 0 ? false : true;
    $zipName = 'null';
    $media_id = 0;

    $tmpDir = dirname(__DIR__, 1) . '/tmp/' . $token;

    if (file_exists($tmpDir)) {
        delete_directory($tmpDir);
    }
    mkdir($tmpDir, 0777, true);

    if ($getMedia) {
        $media = array();
        $zipName = generateRandomString(15);
    }
    foreach ($chats as $chat) {
        $offset_msg_id = 0;
        if ($getUsersInGroups) {
            $chat_info = curl($baseUrl . 'api/users/' . $token . '/getPwrChat?id=' . $chat['id']);
            if (isset($chat_info->response->participants)) {
                foreach ($chat_info->response->participants as $participant) {
                    $first_name = isset($participant->user->first_name) ? $participant->user->first_name : '';
                    $last_name = isset($participant->user->last_name) ? $participant->user->last_name : '';
                    $username = isset($participant->user->username) ? $participant->user->username : '';
                    $date = isset($participant->date) ? $participant->date : 0;
                    array_push($users_in_groups, [$chat_info->response->id, $chat_info->response->title, $chat_info->response->type, $participant->user->id, $first_name, $last_name, $username, $date, $participant->role]);
                }
            }
        }

        do {
            $chat_messages = curl($baseUrl . 'api/users/' . $token . '/messages.getHistory?data[peer]=' . $chat['id'] . '&data[offset_id]=' . $offset_msg_id . '&data[offset_date]=' . strtotime($dataFine) . '&data[add_offset]=0&data[limit]=100&data[max_id]=0&data[min_id]=0');
            if (count($chat_messages->response->messages) <= 0) break;
            foreach ($chat_messages->response->messages as $msg) {
                if (date("Y-m-d", $msg->date) >= $dataInizio) {
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
                            sleep(1);
                        }
                        $author = $users_info[$msg->from_id->user_id];
                    } else {
                        $author = $chat['name'];
                    }
                    array_push($messages, [$chat['id'], $chat['name'], $author, $msg->date, $msg->message, (isset($msg->media) && $getMedia) ? $media_id : (isset($msg->media) ? 'true' : 'false')]);
                }
            }
            $offset_msg_id = end($chat_messages->response->messages)->id;
            sleep(2);
        } while (true);
    }
    if ($filetype) {
        //CSV
        $file_ext = '.csv';
        $messages_csv = fopen($tmpDir . '/messages.csv', 'w');
        foreach ($messages as $fields) {
            fputcsv($messages_csv, $fields);
        }
        fclose($messages_csv);
        if ($getUsersInGroups) {
            $users_in_groups_csv = fopen($tmpDir . '/users_in_groups.csv', 'w');
            foreach ($users_in_groups as $fields) {
                fputcsv($users_in_groups_csv, $fields);
            }
            fclose($users_in_groups_csv);
        }
    } else {
        //JSON
        $file_ext = '.json';
        $json_msg = [];
        array_shift($messages);
        foreach ($messages as $fields) {
            array_push($json_msg, ['chat_id' => $fields[0], 'chat_name' => $fields[1], 'author' => $fields[2], 'date' => $fields[3], 'message' => $fields[4], 'media_name' => $fields[5]]);
        }
        file_put_contents($tmpDir . '/messages.json', json_encode($json_msg));
        if ($getUsersInGroups) {
            $json_usr = [];
            array_shift($users_in_groups);
            foreach ($users_in_groups as $fields) {
                array_push($json_usr, ['chat_id' => $fields[0], 'chat_name' => $fields[1], 'chat_type' => $fields[2], 'user_id' => $fields[3], 'first_name' => $fields[4], 'last_name' => $fields[5], 'username' => $fields[6], 'join_date' => $fields[7], 'role' => $fields[8]]);
            }
            file_put_contents($tmpDir . '/users_in_groups.json', json_encode($json_usr));
        }
    }

    echo json_encode(['messages' => ['url' => './tmp/' . $token . '/messages' . $file_ext,
        'md5' => hash_file('md5', $tmpDir . '/messages' . $file_ext),
        'sha256' => hash_file('sha256', $tmpDir . '/messages' . $file_ext)],
        'users_in_groups' => $getUsersInGroups ? ['url' => './tmp/' . $token . '/users_in_groups' . $file_ext,
            'md5' => hash_file('md5', $tmpDir . '/users_in_groups' . $file_ext),
            'sha256' => hash_file('sha256', $tmpDir . '/users_in_groups' . $file_ext)] : null,
        'media' => $getMedia ? ['zip_name' => $zipName . '.zip', 'num_media' => $media_id] : null]);

    $size = ob_get_length();
    header('Content-Type: application/json');
    header("Content-Encoding: none");
    header("Content-Length: {$size}");
    header("Connection: close");
    ob_end_flush();
    ob_flush();
    flush();
    if ($getMedia && count($media) > 0) {
        require __DIR__ . '/downloadMedia.php';
    } else die();
} else {
    http_response_code(500);
    die();
}
