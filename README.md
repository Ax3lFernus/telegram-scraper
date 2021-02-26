# Web-Based Telegram Scraper
Un semplice Scraper di Telegram scritto in PHP e completamente Web!

## Installazione
### Requisiti: 
1. `PHP 7.4.x`
    * Modifiche al file `php.ini`:
        * Valore di `max_execution_time` impostato a `0`
        * Estensione `curl`
        * Estensione `gd2`
        * Estensione `mbstring`
        * Estensione `openssl`
2. `xtrime-ru/TelegramApiServer` [-> Installazione](https://github.com/xtrime-ru/TelegramApiServer/blob/master/README.md#installation)
3. [Composer](https://getcomposer.org/)
4. [Git](https://git-scm.com/downloads) (Facoltativo)

### Windows
1. Installare un WebServer (Es: [IIS](https://www.microsoft.com/en-us/download/details.aspx?id=48264), [XAMPP](https://www.apachefriends.org/download.html), [UwAmp](http://www.uwamp.com/en/), [WampServer](https://www.wampserver.com/en/)...)
2. Scaricare l'ultima versione di [TelegramScraper](https://github.com/Ax3lFernus/telegram-scraper/releases/latest)
3. Estrarre la cartella compressa
4. Eseguire, all'interno della root della cartella, il comando `composer install`
5. Rinominare il file `.env.example` in `.env` e modificare l'indirizzo presente alla riga `TELEGRAM_API_SERVER_BASE_URL="http://127.0.0.1:9503"` con quello che punta al server di TelegramApiServer (installato e avviato in precedenza)
8. Spostare l'intero contenuto della cartella all'interno del WebServer
9. Recarsi tramite il browser all'url o all'IP del WebServer per avviare il tutto.

### Linux
1. Installare un WebServer (Es: Apache)
2. Posizionarsi nella cartella del WebServer ed eseguire il comando `git clone https://github.com/ax3lfernus/telegram-scraper`
3. Eseguire, all'interno della root della cartella scaricata, il comando `composer install`
4. Rinominare il file `.env.example` in `.env` e modificare l'indirizzo presente alla riga `TELEGRAM_API_SERVER_BASE_URL="http://127.0.0.1:9503"` con quello che punta al server di TelegramApiServer (installato e avviato in precedenza)
5. Recarsi tramite il browser all'url o all'IP del WebServer per avviare il tutto.
