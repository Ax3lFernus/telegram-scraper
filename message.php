<?php
require __DIR__ . '/proxy/checkToken.php';
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Messaggi";
require 'layouts/head.php';
?>

<body class="text-center">
<form id="SelectUser" name="SelectUser">
    <div class="container">
        <fieldset class="border mt-3 p-2">
            <legend>Seleziona le chat</legend>
            <div class="row mt-3">
                <div class="col-sm-4"></div>
                <div class="col-sm-2"><input type="radio" name="Select"
                                             onclick="SetAllCheckBoxes('SelectUser', 'myCheckBox', true);"> Seleziona
                    tutto
                </div>
                <div class="col-sm-2"><input type="radio" checked name="Select"
                                             onclick="SetAllCheckBoxes('SelectUser', 'myCheckBox', false);"> Deseleziona
                    tutto
                </div>
                <div class="col-sm-4"></div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
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
                                <input type="checkbox" name="myCheckBox">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
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
                                <input type="checkbox" name="myCheckBox">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </fieldset>
        <fieldset class="border mt-3 p-2">
            <legend>Seleziona qualcosa ma non so che nome darti</legend>
            <div class="row mt-4">
                <div class="col-sm-3"></div>
                <div class="col-sm-3"><label for="dataInizio">Data inizio:</label> <input id="dataInizio" type="date" value="<?php echo date('Y-m-d');?>" min='1999-01-01' max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-sm-3"><label for="dataFine">Data fine:</label> <input id="dataFine" type="date" value= <?php echo date('Y-m-d'); ?> min='1999-01-01' max="<?php echo date('Y-m-d'); ?>"></div>
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
</form>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/index.js"></script>
</body>
</html>


<script type="text/javascript">
    function SetAllCheckBoxes(FormName, FieldName, CheckValue) {
        if (!document.forms[FormName])
            return;
        var objCheckBoxes = document.forms[FormName].elements[FieldName];
        if (!objCheckBoxes)
            return;
        var countCheckBoxes = objCheckBoxes.length;
        if (!countCheckBoxes)
            objCheckBoxes.checked = CheckValue;
        else
            for (var i = 0; i < countCheckBoxes; i++)
                objCheckBoxes[i].checked = CheckValue;
    }
</script>

