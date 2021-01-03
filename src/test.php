<?php
require '..\vendor\autoload.php';

use danog\MadelineProto\API;

$MadelineProto = new API('session.madeline');
$MadelineProto->updateSettings([
    'app_info' => [
        'api_id' => 2846104,
        'api_hash' => 'e21600b8ff695b771a2a8968eab84179'
    ]
]);
$MadelineProto->start();

$dialogs = $MadelineProto->getDialogs();
foreach ($dialogs as $peer) {
    if ($peer['_'] == 'peerUser') { //Chat con singoli utenti/bot
        $User = $MadelineProto->getPwrChat($peer);
        echo "\n\nCHAT CON UTENTE:\n";
        //Stampo informazioni riugardo l'utente/bot
        echo "ID: " . $User['id'];
        if (isset($User['first_name'])) echo "\nNome: " . $User['first_name'];
        if (isset($User['last_name'])) echo "\nCognome: " . $User['last_name'];
        if (isset($User['about'])) echo "\nInfo: " . $User['about'];
        if (isset($User['username'])) echo "\nUsername: " . $User['username'];
        echo "\n\nMessaggi:\n\n";
        //Recupero e stampo i messaggi
        $messages_Messages = $MadelineProto->messages->getHistory(['peer' => $peer, 'offset_id' => 0, 'offset_date' => 0, 'add_offset' => 0, 'limit' => 1000, 'max_id' => 0, 'min_id' => 0,]);
        foreach (array_reverse($messages_Messages['messages']) as $message) {
            $pref = $message['from_id'] == $User['id'] ? "Ricevuto" : "Inviato";
            echo "\n[" . date("d-m-Y H:i:s", $message['date']) . "] " . $pref . ": " . $message['message'];
        }
    } elseif ($peer['_'] == 'peerChat') { //Chat di gruppo
        $FullInfo = $MadelineProto->getFullInfo($peer);
        $Chat = $FullInfo['Chat'];
        echo "\n\n";
        //Stampo informazioni riugardo la chat
        echo "ID: " . $Chat['id'];
        if (isset($Channel['title'])) echo "\nTitolo: " . $Chat['title'];
        echo "\n\n";
    } elseif ($peer['_'] == 'peerChannel') { //Canali
        $FullInfo = $MadelineProto->getFullInfo($peer);
        $Channel = $FullInfo['Chat'];
        echo "\n\n";
        //Stampo informazioni riugardo il canale
        echo "ID: " . $Channel['id'];
        if (isset($Channel['title'])) echo "\nTitolo: " . $Channel['title'];
        if (isset($Channel['username'])) echo "\nUsername: " . $Channel['username'];
        echo "\n\n";
    }
    echo "\n\n-------------------------------------\n\n";
}