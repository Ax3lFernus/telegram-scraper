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
        <img src="https://telegram.org/img/t_logo.svg?1" width="30" height="30" class="d-inline-block align-top" alt="">
        Telegram Scraper
    </a>
    <button id="logout" class="btn btn-danger" type="button">Logout</button>
</nav>
<div class="container">
    <fieldset class="border mt-3 p-2">
        <legend>Seleziona le chat</legend>
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-2" style="margin: auto 0;">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="check_all_chats">
                    <label class="form-check-label" for="check_all_chats">Seleziona tutte le chat</label>
                </div>
            </div>
            <div class="col-3"><input class="form-control" id="search" type="text" placeholder="Cerca tra le chat...">
            </div>
            <div class="col-3"></div>
        </div>
        <?php
        echo '<div class="row mt-3"><div class="col"></div>';
 echo '<div class="col-6"><table class="table table-striped">
  <thead>
  <tr>
    <th scope="col">Immagine profilo</th>
    <th scope="col">Nome</th>
    <th scope="col">Seleziona</th>
  </tr>
  </thead>
  <tbody id="myTable">';
        for ($i = 0; $i < count($chat_list);$i++) {
    echo' <tr>
    <td><img src="./proxy/profilePicture.php?peer_id=' . $chat_list[$i]['peerID'] . '" onerror="this.onerror=null;this.src=\'./assets/images/default_user.png\';" style="border-radius: 50%" width="30px" height="30px"></td>
    <td> <p>' . $chat_list[$i]['name'] . ' </p></td>
    <td><input type="checkbox" name="user"></td>
  </tr>';
 }
        echo '</tbody>
</table></div>';
        echo '<div class="col"></div>
</div>';
        ?>
        <!-- Lista chat
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-4">
                <div class="card mb-3 text-center" role="button">
                    <div class="row g-0">
                        <div class="col-md-2" style=" margin:auto 0">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <p class="card-text user-select-none">
                                    <?php echo $chat_list[0]['name']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-2 form-check">
                            <input type="checkbox" name="user">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card mb-3 text-center" role="button">
                    <div class="row g-0">
                        <div class="col-md-2" style=" margin:auto 0">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <p class="card-text user-select-none">
                                    Vita Barletta
                                </p>
                            </div>
                        </div>
                        <div class="col-md-2 form-check">
                            <input type="checkbox" name="user">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
        Fine Lista chat -->
    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend>Seleziona qualcosa ma non so che nome darti</legend>
        <div class="row mt-4">
            <div class="col-sm-3"></div>
            <div class="col-sm-3"><label for="dataInizio">Data inizio:</label> <input id="dataInizio" type="date"
                                                                                      value='2013-08-14'
                                                                                      min='1999-01-01'
                                                                                      class="form-control is-valid"
                                                                                      max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
            <div class="col-sm-3"><label for="dataFine">Data fine:</label> <input id="dataFine" type="date"
                                                                                  value= <?php echo date('Y-m-d'); ?> min='1999-01-01'
                                                                                  class="form-control is-valid"
                                                                                  max="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Inserisci una data inizio minore della data fine</div>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-5" style="text-align: right">Includere i media:</div>
            <div class="col-sm-1"><input type="radio" name="Media"> Si</div>
            <div class="col-sm-1"><input type="radio" checked name="Media"> No</div>
            <div class="col-sm-5"></div>
        </div>
    </fieldset>
    <div class="row mt-3">
        <div class="col-sm-4"></div>
        <div class="col-sm-2">
            <button id="csv" class="btn btn-success" type="button">Download csv</button>
        </div>
        <div class="col-sm-2">
            <button id="json" class="btn btn-success" type="button">Download json</button>
        </div>
        <div class="col-sm-4"></div>
    </div>
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
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<p class="mt-5 pb-2 text-muted">TG Scraper &copy; 2020</p>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/message.js"></script>
</body>
</html>