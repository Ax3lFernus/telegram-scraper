<?php
require __DIR__ . '/proxy/checkToken.php';
require __DIR__ . '/proxy/getDialogs.php';
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Messaggi";
require 'layouts/head.php';
?>

<body class="text-center">
<nav id="navbar_top" class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">
        <img src="<?php echo $link . '/assets/images/logo.svg'; ?>" width="30" height="30"
             class="d-inline-block align-top" alt="">
        Telegram Scraper
    </a>
    <button id="logout" class="btn btn-danger" type="button">Logout</button>
</nav>
<div id="page_body" class="container" style="display:none;">
    <fieldset class="border mt-3 p-2">
        <legend>Seleziona le chat</legend>
        <div class="row mt-3">
            <div class="col-3 ms-5" style="margin: auto 0;">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="check_all_chats">
                    <label class="form-check-label" for="check_all_chats">Seleziona tutte le chat</label>
                </div>
            </div>
            <div class="col"></div>
            <div class="col-3"><input class="form-control" id="search" type="text" placeholder="Cerca tra le chat...">
            </div>
        </div>
        <div class="row mt-3" style="height: 300px;overflow: auto;">
            <div class="col tableFixHead">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Immagine profilo</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Seleziona</th>
                    </tr>
                    </thead>
                    <tbody id="chat_list">
                    <?php
                    for ($i = 0; $i < count($chat_list); $i++) {
                        echo ' <tr>
                                <td><img src="./proxy/profilePicture.php?peer_id=' . $chat_list[$i]['peerID'] . '" onerror="this.onerror=null;this.src=\'./assets/images/default_user.png\';" style="border-radius: 50%" width="30px" height="30px"></td>
                                <td> <p>' . $chat_list[$i]['name'] . ' </p></td>
                                <td><input type="checkbox" name="user"></td>
                                <input type="hidden" value="' . $chat_list[$i]['peerID'] . '" name="chatID"><input type="hidden" value="' . htmlspecialchars($chat_list[$i]['name']) . '" name="chatName"><input type="hidden" value="' . $chat_list[$i]['peerType'] . '" name="chatType">
                              </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>

    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend>Imposta i parametri</legend>
        <div class="row mt-4">
            <div class="col-sm-3"></div>
            <div class="col-sm-3"><label for="dataInizio">Data inizio:</label> <input id="dataInizio" name="dataInizio" type="date"
                                                                                      value='2013-08-14'
                                                                                      min='2013-08-14'
                                                                                      class="form-control is-valid"
                                                                                      max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
            <div class="col-sm-3"><label for="dataFine">Data fine:</label> <input id="dataFine" name="dataFine" type="date"
                                                                                  value= <?php echo date('Y-m-d'); ?> min='2013-08-14'
                                                                                  class="form-control is-valid"
                                                                                  max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-5" style="text-align: right">Includere i media:</div>
            <div class="col-sm-1"><input type="radio" name="Media" value="1"> Si</div>
            <div class="col-sm-1"><input type="radio" name="Media" value="0" checked> No</div>
            <div class="col-sm-5"></div>
        </div>
    </fieldset>
    <div class="row mt-3">
        <div class="alert alert-danger " id="alertError" style="display:none;margin-top: 5px;" role="alert"> <!--alert-dismissible fade show!-->
            <strong>Errore!</strong> Seleziona almeno una chat.
            <!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>!-->
        </div>
        <div class="col-sm-4"></div>
        <div class="col-sm-2">
            <button id="csv" class="btn btn-success" type="button">Download csv</button>
        </div>
        <div class="col-sm-2">
            <button id="json" class="btn btn-success" type="button">Download json</button>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <p class="mt-5 pb-2 text-muted">TG Scraper &copy; 2020-<?php echo date('Y'); ?></p>
</div>

<!-- Modal -->
<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Caricamento delle chat in corso...</h5>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div id="modalStripe" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/message.js"></script>
</body>
</html>