/*
* Telegram Scraper v1.0.0
* Content: message.php scripts
* Author: Alessandro Annese & Davide De Salvo
* Last update: 02/02/2021
*/
$("#logout").on('click', _ => {
    $('#modalLoading').modal({backdrop: 'static', keyboard: false, show: true, focus: true}).modal('show');
    $('#modalTitle').text('Logout in corso...');
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: serverUrl + "proxy/logout.php",
        timeout: 120000,
        success: (result) => {
            console.log(result);
            Cookies.remove('token');
            window.location = 'index.php';
        },
        error: (e) => {
            Cookies.remove('token');
            window.location = 'index.php';
        }
    });
});


$("#check_all_chats").click(function(){
    $("input[type=checkbox]").not(this).prop('checked', $(this).prop('checked'));
});

$("input[type=checkbox]").click(() => {
    if($("input[name='user']:checked").length === $("input[name='user']").length)
        $("#check_all_chats").prop('checked', true);
    else
        $("#check_all_chats").prop('checked', false);
});