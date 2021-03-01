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
        url: serverUrl + "functions/logout.php",
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

$('#checkboxlist').find('input:checkbox').on('click', function () {
    var showAll = true;
    $("#chat_list tr").hide();
    $('#checkboxlist').find('input:checkbox:checked').each(function() {
        showAll = false;
        var status = $(this).attr('rel');
        $("#chat_list tr").filter(function () {
            if($(this).find("input[type='hidden'][name='chatType']").val() === status)
            {
                $(this).show();
            }
        });
    });
    if (showAll) {
        $("#chat_list tr").show();
    }
});

$("#check_all_chats").click(function () {
    if($("#check_all_chats").is(":checked")) {
        $('#checkboxlist').find('input:checkbox').prop("disabled", "disabled");
        $('#search').prop("disabled", "disabled");
    }else{
        $('#checkboxlist').find('input:checkbox').prop("disabled", false);
        $('#search').prop("disabled", false);
    }
    $("#chat_list tr").filter(":visible").filter(function () {
    $(this).find("input[type=checkbox][name='user']").not(this).prop('checked', $("#check_all_chats").prop('checked'));
    });
});

$("input[type=checkbox][name='user']").click(() => {
    if ($("input[name='user']:checked:visible").length === $("input[name='user']:visible").length) {
        $("#check_all_chats").prop('checked', true);
        $('#checkboxlist').find('input:checkbox').prop("disabled", "disabled");
        $('#search').prop("disabled", "disabled");
    }
    else {
        $("#check_all_chats").prop('checked', false);
        $('#checkboxlist').find('input:checkbox').prop("disabled", false);
        $('#search').prop("disabled", false);
    }
});


$("#csv").on('click', _ => {
    if ($("#dataInizio").val() <= $("#dataFine").val()) {
        if (getCheckedChats().length > 0) {
            $('#md5_files').text('Errore');
            $('#sha_files').text('Errore');
            $('#report_url').prop('href', '').text('');
            $('#zip_url').prop('href', '').text('');
            $("#dataFine,#dataInizio").attr('class', 'form-control is-valid');
            $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
            $('#modalTitle').text('Creazione file csv in corso...');
            sendChats('csv');
        } else {
            $('#alertText').text('Seleziona almeno una chat.');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 3000);
        }
    } else {
        $("#dataFine,#dataInizio").attr('class', 'form-control is-invalid');
    }
});

$("#json").on('click', _ => {
    if ($("#dataInizio").val() <= $("#dataFine").val()) {
        if (getCheckedChats().length > 0) {
            $('#md5_files').text('Errore');
            $('#sha_files').text('Errore');
            $('#report_url').prop('href', '').text('');
            $('#zip_url').prop('href', '').text('');
            $("#dataFine,#dataInizio").attr('class', 'form-control is-valid');
            $('#modalLoading').modal({backdrop: 'true', keyboard: false, show: true, focus: true}).modal('show');
            $('#modalTitle').text('Creazione file csv in corso...');
            sendChats('json');
        } else {
            $('#alertText').text('Seleziona almeno una chat.');
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
        var showAll = true;
        $("#chat_list tr").hide();
        $('#checkboxlist').find('input:checkbox:checked').each(function() {
            showAll = false;
            var status = $(this).attr('rel');
            $("#chat_list tr").filter(function () {
                if($(this).find("input[type='hidden'][name='chatType']").val() === status)
                {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                }
            });
        });
        if (showAll) {
            $("#chat_list tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        }


    });
});

sendChats = (type = 'csv', chats = getCheckedChats()) => {
    $('#modalStripe').attr('aria-valuenow', 100).width('100%');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: serverUrl + "functions/getMessages.php",
        data: {
            chats: chats,
            media: $('#media').prop('checked'),
            users_groups: $('#user_list').prop('checked'),
            profile_photos: $('#profile_pic').prop('checked'),
            filetype: type == 'json' ? 0 : 1,
            dataInizio: $('input[name="dataInizio"]').val(),
            dataFine: $('input[name="dataFine"]').val()
        },
        timeout: 0,
        success: (result) => {
            if(!$('#media').prop('checked')){
                $('#modalLoading').modal('hide');
                $('#md5_files').text(result.report.md5);
                $('#sha_files').text(result.report.sha256);
                let href = window.location.href;
                let dir = href.substring(0, href.lastIndexOf('/'));
                $('#report_url').prop('href', result.report.url).text(dir + result.report.url.substring(1));
                $('#zip_url').prop('href', result.zip).text(dir + result.zip.substring(1));
                $('#modalHash').modal('show');
            }else{
                $('#modalTitle').text('Creazione della cartella contenente i media...');
                $('#modalStripe').addClass('bg-warning').attr('aria-valuenow', 0).width('0%');
                setTimeout(checkMediaDownloadStatus.bind(null, result.media.num_media, result.media.zip_name, result.report.url, result.report.name), 1000);
            }
        },
        error: (e) => {
            $('#modalLoading').modal('hide');
            $('#modalHash').modal('hide');
            $('#alertText').text('Si è verificato un errore durante l\'elaborazione.');
            $('#alertError').addClass('show');
            setTimeout(_ => $('#alertError').removeClass('show'), 4000);
        }
    });
}

getCheckedChats = () => {
    let chats = [];
    $('input[name="user"]:checked').each(function () {
        chats.push({
            "id": $(this).parent().parent().find("input[type='hidden'][name='chatID']").val(),
            "name": $(this).parent().parent().find("input[type='hidden'][name='chatName']").val(),
            "type": $(this).parent().parent().find("input[type='hidden'][name='chatType']").val(),
            "peer": {
                "peerType": $(this).parent().parent().find("input[type='hidden'][name='peerType']").val(),
                "peerIdType": $(this).parent().parent().find("input[type='hidden'][name='peerIdType']").val(),
                "peerId": $(this).parent().parent().find("input[type='hidden'][name='peerId']").val()
            }
        });
    });
    return chats;
}

checkMediaDownloadStatus = (media_num, zipName, reportUrl, reportName) => {
    $.ajax({
        url: './functions/downloadMediaStatus.php?media_num=' + media_num + '&zip_name=' + zipName + '&report_name=' + reportName,
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
                $('#md5_files').text(result.md5);
                $('#sha_files').text(result.sha256);
                let href = window.location.href;
                let dir = href.substring(0, href.lastIndexOf('/'));
                $('#report_url').prop('href', reportUrl).text(dir + reportUrl.substring(1));
                $('#zip_url').prop('href', result.url).text(dir + result.url.substring(1));
                $('#modalHash').modal('show');
            } else {
                setTimeout(checkMediaDownloadStatus.bind(null, media_num, zipName, reportUrl, reportName), 5000);
            }
        },
        error: function (code, textStatus, errorThrown) {
            setTimeout(checkMediaDownloadStatus.bind(null, media_num, zipName, reportUrl, reportName), 5000);
        }
    });
}

