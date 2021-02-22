<?php
$htmlReportPage = '
<table style="width: 100%">
    <tr>
        <td valign="top" style="width: 50%;">
            <img src="' . dirname(__DIR__, 1) . '/assets/images/logo.png" alt="Telegram Scraper Logo" width="50"/>
        </td>
        <td style="width: 50%;" align="right">
            <h3>Telegram Scraper v1.1.0</h3>
            <p style="font-size: small;">
                Sorgente:
                <a href="https://github.com/ax3lfernus/telegram-scraper" style="margin-bottom: 5px;">https://github.com/ax3lfernus/telegram-scraper</a>
                TelegramApiServer v1.10.5:
                <a href="https://github.com/xtrime-ru/TelegramApiServer">https://github.com/xtrime-ru/TelegramApiServer</a>
            </p>
        </td>
    </tr>

</table>
<hr style="border: 1px solid #000; margin: 10px 0 20px 0;"/>
<table style="width: 100%">
    <tr>
        <td style="width: 50%">
            <strong>Dati utente loggato: </strong>
            <ul style="list-style-type:none;">
                <li>ID: ' . (isset($self->response->id) ? $self->response->id : ' ') . '</li>
                <li>Numero di telefono: ' . (isset($self->response->phone) ? '+' . $self->response->phone : ' ') . '</li>
                <li>Username: ' . (isset($self->response->username) ? '@' . $self->response->username : ' ') . '</li>
                <li>Nome: ' . (isset($self->response->first_name) ? $self->response->first_name : ' ') . '</li>
                <li>Cognome: ' . (isset($self->response->last_name) ? $self->response->last_name : ' ') . '</li>
                <li>Ultimo accesso: ' . (isset($self->response->status->was_online) ? date("d-m-Y h:i:s", $self->response->status->was_online) : 'N.D.') . '</li>
            </ul>
        </td>
        <td style="width: 50%" align="right">
            <strong>Dati richiesti il:</strong> ' . $request_date .'
            <br/>
            <strong>Download terminato il:</strong> ' . date("d-m-Y h:i:s") .'
            <br/>
            <strong>Totale messaggi scaricati: </strong> '. count($messages) .'
            <br/>
            <strong>Totale media scaricati: </strong> '. ($media_id > 0 ? $media_id : "Non richiesti") . '
        </td>
    </tr>
</table>';