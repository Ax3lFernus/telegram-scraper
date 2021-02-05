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

$("#csv,#json").click(function () {
    if ($("#dataInizio").val() > $("#dataFine").val()) {
        $("#dataFine,#dataInizio").toggleClass("is-invalid");
    }

});

$("#check_all_chats").click(function () {
    $("input[type=checkbox]").not(this).prop('checked', $(this).prop('checked'));
});

$("input[type=checkbox]").click(() => {
    if ($("input[name='user']:checked").length === $("input[name='user']").length)
        $("#check_all_chats").prop('checked', true);
    else
        $("#check_all_chats").prop('checked', false);
});

$("#csv").on('click', _ => {
    //$('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
    //$('#modalTitle').text('Creazione file json in corso...');
    sendChats('csv');
});

$("#json").on('click', _ => {
    $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
    $('#modalTitle').text('Creazione file json in corso...');
    sendChats('json');
});

$('.card').on('click', function (e) {
    if (!$(e.target).is('input:checkbox')) {
        let $checkbox = $(this).find('input:checkbox');
        $checkbox.prop('checked', !$checkbox.prop('checked'));
    }
});

$(document).ready(function () {
    $('#modalLoading').modal({backdrop: 'static', keyboard: false, show: true, focus: true}).modal('show');

    function imageLoaded() {
        counter--;
        if (counter === 0) {
            $('#modalLoading').modal('hide');
            $('#page_body').show();
        }
    }

    let images = $('img');
    let counter = images.length;

    images.each(function () {
        if (this.complete) {
            imageLoaded.call(this);
        } else {
            $(this).one('load', imageLoaded);
        }
    });

    if ($(window).width() > 992) {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 5) {
                $('#navbar_top').addClass("fixed-top");
                // add padding top to show content behind navbar
                $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
            } else {
                $('#navbar_top').removeClass("fixed-top");
                // remove padding top from body
                $('body').css('padding-top', '0');
            }
        });
    }

    $("#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

sendChats = (type = 'csv', chats = getCheckedChats()) => {
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: serverUrl + "proxy/getMessages.php",
        data: {chats: chats, fileType: type},
        timeout: 120000,
        success: (result) => {
            if(type == 'csv')
                getCSVFromArray(result);
            else
                getJSONFromArray(result);
            //window.location = 'message.php';
            $('#modalLoading').modal('hide');
            //RICHIESTA AJAX PER DOWNLOAD MEDIA ASINCRONO (?)
        },
        error: (e) => {
            // $('#modalTitle').text('Errore nella creazione del file...').css("color","red");
            //window.location = 'message.php';
            //MESSAGGIO DI ERRORE TEMPORIZZATO
        }
    });
}

getCSVFromArray = (array) => {
    let dataString, csvContent = "";
    array.forEach((infoArray) => {
        dataString = infoArray.join(",");
        csvContent += dataString.replace(/\n/g, "\\n") + "\n";
    });
    let downloadLink = document.createElement("a");
    downloadLink.href = URL.createObjectURL(new Blob(["\ufeff", csvContent]));
    let date = new Date($.now());
    downloadLink.download = date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate()+"_"+date.getHours()+"-"+date.getMinutes()+"-"+date.getSeconds()+".csv";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

getJSONFromArray = (array) => {}

getCheckedChats = () => {
    let chats = [];
    $('input[name="user"]:checked').each(function () {
        chats.push({
            "id": $(this).parent().parent().find("input[type='hidden'][name='chatID']").val(),
            "name": $(this).parent().parent().find("input[type='hidden'][name='chatName']").val()
        });
    });
    return chats;
}

