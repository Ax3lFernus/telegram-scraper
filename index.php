<?php
    require 'functions/checkToken.php';
    $style = "<link href=\"assets/css/signin.css\" rel=\"stylesheet\">";
    $page_title = "Login";
    require 'layouts/head.php';
?>

<body class="text-center">
<main class="form-signin">
    <form id="login">
        <img class="mb-4" src="<?php echo $link . '/assets/images/logo.svg'; ?>" alt="" width="100" height="100">
        <h1 class="h3 mb-3 fw-normal">Accedi</h1>
        <?php
        if(isset($_GET['ERROR'])) echo "<p class=\"text-danger font-weight-bold\">Si è verificato un errore.</p>";
        if(isset($_GET['PHONE_INVALID'])) echo "<p class=\"text-danger font-weight-bold\">Il numero di telefono è errato.</p>";
        if(isset($_GET['PHONE_CODE_INVALID'])) echo "<p class=\"text-danger font-weight-bold\">Il codice di verifica è errato.</p>";
        ?>
        <div class="form-floating">
            <input type="tel" id="inputPhone" class="form-control" pattern="^\+[0-9]{2,3}[0-9]{8,10}$"
               placeholder="+391234567890" required autofocus>
            <label for="inputPhone">Numero di telefono</label>
        </div>

        <div id="inputBox" class="form-floating mt-2" style="display:none">
            <input type="password" id="inputCode" class="form-control" placeholder="123456" pattern="^[0-9]{4,7}$">
            <label for="inputCode">Codice di sicurezza</label>
        </div>

        <button id="form-btn" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Invia codice</button>
        <p class="mt-5 mb-3 text-muted">TG Scraper &copy; 2020-<?php echo date('Y');?></p>
    </form>
</main>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/index.js"></script>
</body>
</html>