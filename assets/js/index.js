/*
* Telegram Scraper v1.0.0
* Content: index.php scripts
* Author: Alessandro Annese & Davide De Salvo
* Last update: 02/02/2021
*/
$("#login").submit(function (e) {
    e.preventDefault();
    if ($("#inputCode").is(":hidden")) {
        $("#form-btn").prop("disabled", true).text("Invio il codice via Telegram...");
        $("#inputPhone").prop("disabled", true);
        //Creo sessione & richiedo il codice di verifica
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: serverUrl + "proxy/login.php",
            data: {tel: $("#inputPhone").val()},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                let json = JSON.parse(result);
                if (json.success) {
                    Cookies.set('token', json.token);
                    $("#inputPhone").css('border-bottom-left-radius', 0).css('border-bottom-right-radius', 0).css('margin-bottom', '-1px');
                    $("#inputCode").prop("required", true).show();
                    $("#form-btn").prop("disabled", false).text("Accedi");
                } else {
                    //ERRORE DI RICHIESTA CODICE TELEGRAM
                }
            },
            error: (e) => {
                console.log("Error", e);
            }
        });
    } else {
        $("#form-btn").prop("disabled", true).text("Accesso in corso...");
        $("#inputCode").prop("disabled", true);
        //Accedo con il codice
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: serverUrl + "proxy/login.php",
            data: {token: Cookies.get('token'), code: $("#inputCode").val()},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                let json = JSON.parse(result);
                if (json.success) {
                    $("#form-btn").text("Fatto!");
                    location.href = 'message.php'
                } else {
                    //ERRORE DI VERIFICA CODICE
                    $("#form-btn").text("Codice errato");
                }
            },
            error: (e) => {
                Cookies.remove('token');
                console.log("Error", e);
            }
        });
    }
});