<?php
require __DIR__.'/proxy/checkToken.php';
$style = "<link href=\"assets/css/message.css\" rel=\"stylesheet\">";
$page_title = "Messaggi";
require 'layouts/head.php';
?>

<body class="text-center">

<div class="container">
<div class="row mt-3">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="card card-active-listener text-center" style="border-radius: 20px;">
            <div class="card-body">

                <div class="media">


                    <div class="avatar mr-5">
                        <img class="avatar-img" src="assets/images/avatars/11.jpg" alt="Bootstrap Themes">
                    </div>

                    <div class="media-body overflow-hidden">
                        <div class="d-flex align-items-center mb-1">
                            <h6 class="text-truncate mb-0 mr-auto">Bootstrap Themes</h6>
                            <p class="small text-muted text-nowrap ml-4">10:42 am</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div>
    <div class="row mt-3">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="card mb-3 text-center" style="border-radius: 20px;">
                <div class="row g-0">
                    <div class="col-md-1">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <p class="card-text" style="text-align: left">
                                Vita Barletta
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
</div>

<?php require('layouts/scripts.php'); ?>
<script src="./assets/js/index.js"></script>
</body>
</html>
