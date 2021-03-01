<?php
$htmlReportPage = '
<table style="width: 100%">
    <tr>
        <td valign="top" style="width: 50%;">
            <img src="' . dirname(__DIR__, 1) . '/assets/images/logo.png" alt="Telegram Scraper Logo" width="50"/>
        </td>
        <td style="width: 50%;" align="right">
            <h3>Telegram Scraper v' . $telegramScraperVersion . '</h3>
            <p style="font-size: small;">
                Sorgente:
                <a href="https://github.com/ax3lfernus/telegram-scraper" style="margin-bottom: 5px;">https://github.com/ax3lfernus/telegram-scraper</a><br/>
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
                <li style="padding-bottom: 5px;">ID: ' . (isset($self->response->id) ? $self->response->id : ' ') . '</li>
                <li style="padding-bottom: 5px;">Numero di telefono: ' . (isset($self->response->phone) ? '+' . $self->response->phone : ' ') . '</li>
                <li style="padding-bottom: 5px;">Username: ' . (isset($self->response->username) ? '@' . $self->response->username : ' ') . '</li>
                <li style="padding-bottom: 5px;">Nome: ' . (isset($self->response->first_name) ? $self->response->first_name : ' ') . '</li>
                <li style="padding-bottom: 5px;">Cognome: ' . (isset($self->response->last_name) ? $self->response->last_name : ' ') . '</li>
                <li style="padding-bottom: 5px;">Ultimo accesso: ' . (isset($self->response->status->was_online) ? gmdate("d-m-Y H:i:s", $self->response->status->was_online) . ' GMT' : 'N.D.') . '</li>
            </ul>
        </td>
        <td style="width: 50%" align="right">
			<ul style="list-style-type:none;">
					<li style="padding-bottom: 5px;"><strong>Dati richiesti il:</strong> ' . $request_date . ' GMT</li>
					<li style="padding-bottom: 5px;"><strong>Download terminato il:</strong> ' . gmdate("d-m-Y H:i:s") . ' GMT</li>
					<li style="padding-bottom: 5px;"><strong>Totale messaggi scaricati: </strong> ' . count($messages) . '</li>
					<li><strong>Totale media scaricati: </strong> ' . ($media_id > 0 ? $media_id : "Non richiesti") . '</li>
			</ul>
        </td>
    </tr>
</table>
<hr/>
<strong>Info sul file zip: </strong>
<ul style="list-style-type:none;">
    <li style="padding-bottom: 5px;"><b>Nome:</b> ' . $zipName . '_' . $request_date_underscore . '.zip</li>
    <li style="padding-bottom: 5px;"><b>MD5:</b> ' . hash_file('md5', $tmpDir . '/' . $zipName . '_' . $request_date_underscore . '.zip') . '</li>
    <li><b>SHA256:</b> ' . hash_file('sha256', $tmpDir . '/' . $zipName . '_' . $request_date_underscore . '.zip') . '</li>
</ul>';