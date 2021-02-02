<?php
    if (isset($_COOKIE['token'])) { //Verificare che il token corrisponda ad una sessione valida
        header('Location: ./message.php');
        die();
    }
    $style = "<link href=\"assets/css/signin.css\" rel=\"stylesheet\">";
    $page_title = "Login";
    require('layouts/head.php');
?>

<body class="text-center">
<main class="form-signin">
    <form id="login">
        <img class="mb-4" src="https://telegram.org/img/t_logo.svg?1" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Accedi</h1>

        <label for="inputPhone" class="visually-hidden">Numero di telefono:</label>
        <input type="tel" id="inputPhone" class="form-control" pattern="^\+[0-9]{2,3}[0-9]{8,10}$"
               placeholder="+391234567890" required autofocus>

        <label for="inputCode" class="visually-hidden">Codice di sicurezza:</label>
        <input type="text" id="inputCode" class="form-control" placeholder="123456" pattern="^[0-9]{4,7}$"
               style="display:none">

        <button id="form-btn" class="w-100 btn btn-lg btn-primary" type="submit">Invia codice</button>
        <p class="mt-5 mb-3 text-muted">TG Scraper &copy; 2020</p>
    </form>
</main>
<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/index.js"></script>
</body>
</html>