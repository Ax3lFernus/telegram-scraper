/*
* Telegram Scraper v1.1.0
* Content: message.php scripts
* Author: Alessandro Annese & Davide De Salvo
* Last update: 19/02/2021
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


$("#check_all_chats").click(function () {
    $("input[type=checkbox][name='user']").not(this).prop('checked', $(this).prop('checked'));
});

$("#select_all_chat_user").click(function (){
    $("#select_all_chat_channel").prop('checked', false);
    $("#select_all_chat_groups").prop('checked', false);
    if($("#select_all_chat_user").is(":checked")){
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).find("input[type='hidden'][name='chatType']").val()==="user");
        });
    }else{
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).find("input[type='hidden'][name='chatType']").val()>"");
        });
    }

});

$("#select_all_chat_channel").click(function (){
    $("#select_all_chat_user").prop('checked', false);
    $("#select_all_chat_groups").prop('checked', false);
    if($("#select_all_chat_channel").is(":checked")) {
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).find("input[type='hidden'][name='chatType']").val() === "channel");
        });
    }
    else{
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).find("input[type='hidden'][name='chatType']").val() >"");
        });
    }

});

$("#select_all_chat_groups").click(function (){
    $("#select_all_chat_channel").prop('checked', false);
    $("#select_all_chat_user").prop('checked', false);
    if($("#select_all_chat_groups").is(":checked")) {
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).find("input[type='hidden'][name='chatType']").val() === "chat");
        });
    }else{
        $("#chat_list tr").filter(function () {
            $(this).toggle($(this).find("input[type='hidden'][name='chatType']").val() > "");
        });
    }
});

$("input[type=checkbox]").click(() => {
    if ($("input[name='user']:checked").length === $("input[name='user']").length)
        $("#check_all_chats").prop('checked', true);
    else
        $("#check_all_chats").prop('checked', false);
});



$("#csv").on('click', _ => {
    if ($("#dataInizio").val() <= $("#dataFine").val()) {
        if(getCheckedChats().length > 0) {
            $('#md5_msg').text('Non richiesto');
            $('#sha_msg').text('Non richiesto');
            $('#md5_usr').text('Non richiesto');
            $('#sha_usr').text('Non richiesto');
            $("#dataFine,#dataInizio").attr('class', 'form-control is-valid');
            $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
            $('#modalTitle').text('Creazione file csv in corso...');
            sendChats('csv');
        }else{
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 3000);
        }
    } else {
        $("#dataFine,#dataInizio").attr('class', 'form-control is-invalid');
    }
});

$("#json").on('click', _ => {
    if ($("#dataInizio").val() <= $("#dataFine").val()) {
        if(getCheckedChats().length > 0) {
            $("#dataFine,#dataInizio").attr('class', 'form-control is-valid');
            $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
            $('#modalTitle').text('Creazione file csv in corso...');
            sendChats('json');
        }else{
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 3000);
        }
    } else {
        $("#dataFine,#dataInizio").attr('class', 'form-control is-invalid');
    }
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
    $('#modalStripe').attr('aria-valuenow', 100).width('100%');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: serverUrl + "proxy/getMessages.php",
        data: {
            chats: chats,
            media: $('#media').prop('checked'),
            users_groups: $('#user_list').prop('checked'),
            filetype: type == 'json' ? 0 : 1,
            dataInizio: $('input[name="dataInizio"]').val(),
            dataFine: $('input[name="dataFine"]').val()
        },
        timeout: 0,
        success: (result) => {
            $('#modalLoading').modal('hide');
            let newWin = window.open(result.messages.url);
            $('#md5_msg').text(result.messages.md5);
            $('#sha_msg').text(result.messages.sha256);
            if(!newWin || newWin.closed || typeof newWin.closed=='undefined')
            {
                alert("Consenti i popup dal tuo browser.");
            }
            if(result.users_in_groups != null) {
                window.open(result.users_in_groups.url);
                $('#md5_usr').text(result.users_in_groups.md5);
                $('#sha_usr').text(result.users_in_groups.sha256);
            }
            $('#modalHash').modal('show').on('hide.bs.modal', function () {
                if ($('input[name="Media"]:checked').val() === '1') {
                    $('#modalLoading').modal('show');
                    $('#modalTitle').text('Creazione della cartella contenente i media...');
                    $('#modalStripe').addClass('bg-warning').attr('aria-valuenow', 0).width('0%');
                    setTimeout(checkMediaDownloadStatus.bind(null, result[2], result[1]), 1000);
                } else
                    $('#modalLoading').modal('hide');
            });
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
    csvContent = array[0] + "\n";
    array.shift();
    array.forEach((element) => {
        dataString = element[0] + ',"' + element[1] + '","' + element[2] + '",' + element[3] + ',"' + element[4].replace(/\n/g, '","') + '",' + element[5] + "\n";
        csvContent += dataString;
    });
    let downloadLink = document.createElement("a");
    let date = new Date($.now());
    downloadLink.setAttribute("href", URL.createObjectURL(new Blob(["\ufeff", csvContent])));
    downloadLink.setAttribute("download", date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + "_" + date.getHours() + "-" + date.getMinutes() + "-" + date.getSeconds() + ".csv");
    document.body.appendChild(downloadLink);
    downloadLink.click();
    downloadLink.remove();
}

getJSONFromArray = (array) => {
    let jsonObj = [];
    array.shift();
    array.forEach((element) => {
        jsonObj.push({
            "chat_id": element[0],
            "chat_name": element[1],
            "author": element[2],
            "date": element[3],
            "message": element[4].replace(/\n/g, "\\n"),
            "media_name": element[5]
        });
    });
    let json = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(jsonObj));
    let downloadLink = document.createElement('a');
    let date = new Date($.now());
    downloadLink.setAttribute("href", json);
    downloadLink.setAttribute("download", date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + "_" + date.getHours() + "-" + date.getMinutes() + "-" + date.getSeconds() + ".json");
    document.body.appendChild(downloadLink); // required for firefox
    downloadLink.click();
    downloadLink.remove();
}

getCheckedChats = () => {
    let chats = [];
    $('input[name="user"]:checked').each(function () {
        chats.push({
            "id": $(this).parent().parent().find("input[type='hidden'][name='chatID']").val(),
            "name": $(this).parent().parent().find("input[type='hidden'][name='chatName']").val(),
            "type": $(this).parent().parent().find("input[type='hidden'][name='chatType']").val()
        });
    });
    return chats;
}

checkMediaDownloadStatus = (media_num, zipName) => {
    $.ajax({
        url: './proxy/downloadMediaStatus.php?media_num=' + media_num + '&zip_name=' + zipName,
        type: 'GET',
        timeout: 2000,
        success: (result) => {
            let percentage = parseFloat(result.percentage);
            let status = result.status;
            if (percentage < 99) {
                $('#modalStripe').attr('aria-valuenow', percentage).width(percentage + '%');
            }
            if (status) {
                $("#modalLoading").modal('hide');
                $('#modalStripe').removeClass('bg-warning');
                window.location.href = './tmp/' + zipName;
            } else {
                setTimeout(checkMediaDownloadStatus.bind(null, media_num, zipName), 5000);
            }
        },
        error: function (code, textStatus, errorThrown) {
            setTimeout(checkMediaDownloadStatus.bind(null, media_num, zipName), 5000);
        }
    });
}