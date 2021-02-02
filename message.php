<?php
require __DIR__ . '/proxy/checkToken.php';
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Messaggi";
require 'layouts/head.php';
?>

<body class="text-center">
<nav class="navbar navbar-dark bg-dark px-3">
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
            <div class="col-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="check_all_chats">
                    <label class="form-check-label" for="check_all_chats">Seleziona tutte le chat</label>
                </div>
            </div>
            <div class="col"></div>
        </div>
        <!-- Lista chat -->
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-4">
                <div class="card mb-3 text-center">
                    <div class="row g-0">
                        <div class="col-md-2" style=" margin:auto 0">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <p class="card-text">
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
            <div class="col-4">
                <div class="card mb-3 text-center">
                    <div class="row g-0">
                        <div class="col-md-2" style=" margin:auto 0">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <p class="card-text">
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
    </fieldset>
    <fieldset class="border mt-3 p-2">
        <legend>Seleziona qualcosa ma non so che nome darti</legend>
        <div class="row mt-4">
            <div class="col-sm-3"></div>
            <div class="col-sm-3"><label for="dataInizio">Data inizio:</label> <input id="dataInizio" type="date"
                                                                                      value="<?php echo date('Y-m-d'); ?>"
                                                                                      min='1999-01-01'
                                                                                      max="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-sm-3"><label for="dataFine">Data fine:</label> <input id="dataFine" type="date"
                                                                                  value= <?php echo date('Y-m-d'); ?> min='1999-01-01'
                                                                                  max="<?php echo date('Y-m-d'); ?>">
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
</div>

<!-- Modal -->
<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Caricamento...</h5>
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
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/message.js"></script>
</body>
</html>