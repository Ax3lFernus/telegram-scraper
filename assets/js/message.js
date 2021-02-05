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
/*
$("#csv").on('click', _ => {
    $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
    $('#modalTitle').text('Creazione file csv in corso...');
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: serverUrl + "proxy/getMessages.php",
        timeout: 120000,
        success: (result) => {
            console.log(result);
            window.location = 'message.php';
        },
        error: (e) => {
           // $('#modalTitle').text('Errore nella creazione del file...').css("color","red");
            window.location = 'message.php';
        }
    });
});

$("#json").on('click', _ => {
    $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
    $('#modalTitle').text('Creazione file json in corso...');
    $.ajax({
        type: "GET",
        dataType: "JSON",
        url: serverUrl + "proxy/json.php",
        timeout: 120000,
        success: (result) => {
            console.log(result);
            window.location = 'message.php';
        },
        error: (e) => {
           // $('#modalTitle').text('Errore nella creazione del file...').css("color","red");
            window.location = 'message.php';
        }
    });
});
*/
$("#csv,#json").click(function(){
    if($("#dataInizio").val() > $("#dataFine").val())
    {
        $("#dataFine,#dataInizio").toggleClass("is-invalid");
    }

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

$("#csv").on('click', function (e) {
    $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
    $('#modalTitle').text('Creazione file csv in corso...');
    $flag=0;
    let $name_list = [];
    let $id_list = [];
    $('input[name=name]').each(function(){
        if($(this).parent().find('input:checkbox').is(":checked")) {
            $name_list.push($(this).val());
        }
        });

    $('input[name=peerID]').each(function(){
        if($(this).parent().find('input:checkbox').is(":checked")) {
            $id_list.push($(this).val());
        }
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: serverUrl + "proxy/getMessages.php",
            data: {id_list: $id_list, name_list:$name_list, flag:$flag},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                window.location = 'message.php';
            },
                error: (e) => {
                    // $('#modalTitle').text('Errore nella creazione del file...').css("color","red");
                    window.location = 'message.php';
                }
        });
    });
});

$("#json").on('click', function (e) {
    $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
    $('#modalTitle').text('Creazione file json in corso...');
    $flag=1;
    let $name_list = [];
    let $id_list = [];
    $('input[name=name]').each(function(){
        if($(this).parent().find('input:checkbox').is(":checked")) {
            $name_list.push($(this).val());
        }
    });

    $('input[name=peerID]').each(function(){
        if($(this).parent().find('input:checkbox').is(":checked")) {
            $id_list.push($(this).val());
        }
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: serverUrl + "proxy/getMessages.php",
            data: {id_list: $id_list, name_list:$name_list,flag:$flag},
            timeout: 120000,
            success: (result) => {
                console.log(result);
                window.location = 'message.php';
            },
            error: (e) => {
                // $('#modalTitle').text('Errore nella creazione del file...').css("color","red");
                window.location = 'message.php';
            }
        });
    });
});

$('.card').on('click', function (e) {
    if (!$(e.target).is('input:checkbox')) {
        let $checkbox = $(this).find('input:checkbox');
        $checkbox.prop('checked', !$checkbox.prop('checked'));
    }
});




$(document).ready(function(){
    $('#modalLoading').modal({backdrop: 'static', keyboard: false, show: true, focus: true}).modal('show');

    if ($(window).width() > 992) {
        $(window).scroll(function(){
            if ($(this).scrollTop() > 5) {
                $('#navbar_top').addClass("fixed-top");
                // add padding top to show content behind navbar
                $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
            }else{
                $('#navbar_top').removeClass("fixed-top");
                // remove padding top from body
                $('body').css('padding-top', '0');
            }
        });
    }

    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

$(function() {
    function imageLoaded() {
        counter--;
        if( counter === 0 ) {
            $('#modalLoading').modal('hide');
            $('#page_body').show();
        }
    }
    let images = $('img');
    let counter = images.length;

    images.each(function() {
        if( this.complete ) {
            imageLoaded.call( this );
        } else {
            $(this).one('load', imageLoaded);
        }
    });
});