# V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids


## Indice
1. [Descrizione](#descrizione)
2. [Installazione](#installazione)
3. [Avvio del Sistema](#avvio-del-sistema)
4. [Monitoraggio del Sistema](#monitoraggio-del-sistema)
5. [Demo](#demo-del-sistema)
6. [Licenza e Autore](#licenza-e-autore)

## Descrizione
Questo sistema è progettato per analizzare e confrontare i dati astronomici, con un focus sugli asteroidi che fanno approcci ravvicinati alla Terra, eventi astronomici storici come impatti registrati e modelli predittivi di possibili collisioni future.

Il suo obiettivo principale è fornire informazioni complete e dettagliate su questi oggetti, arricchite da spiegazioni intuitive generate tramite modelli di intelligenza artificiale, con particolare attenzione agli asteroidi potenzialmente pericolosi.

Una caratteristica chiave del progetto è la previsione degli impatti, che consente agli utenti di monitorare gli asteroidi di interesse e valutare i rischi associati basati su dati in tempo reale. Il sistema monitorerà le variazioni nella probabilità di impatto di oggetti noti, come l'asteroide ampiamente discusso 2024 YR4, offrendo una piattaforma preziosa per la ricerca e l'osservazione continua di questi corpi celesti.

___

## Installazione
Per prima cosa, clona il repository:
```bash
git clone https://github.com/Manuel-Cplusplus/Tesi_Carlucci.git
```

Dopo aver clonato il repository, spostati nella cartella del repository:
```bash
cd Tesi_Carlucci
```

E copia il file `.env.example` rinominandolo in `.env`:
```bash
cp .env.example .env
```

È importante sostituire i dati di esempio indicati come `CHANGEME` nel file `.env`.
In particolare, è necessario modificare i seguenti campi:
- APP_NAME
- DB_CONNECTION 
- DB_HOST 
- DB_PORT 
- DB_DATABASE 
- DB_USERNAME 
- DB_PASSWORD
- MAIL_MAILER
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION
- MAIL_FROM_ADDRESS
- MAIL_FROM_NAME
- QUEUE_CONNECTION
- EMAILJS_SERVICE_ID
- EMAILJS_TEMPLATE_ID
- EMAILJS_USER_ID
- GEMINI_API_KEY

Si consiglia di creare il database da collegare al sistema esternamente (per esempio tramite MySQL workbench) 
e di utilizzare il nome del database creato per il campo `DB_DATABASE` nel file `.env`.

Per l'Api di Gemini, puoi crearla dall'URL: https://aistudio.google.com/app/apikey
___
### Installazione

Requisiti:
- Php
- Composer
- Node.js
- Mysql

Prima di iniziare, è importante installare le dipendenze. Nella cartella del repository, esegui i seguenti comandi in una riga di comando:

Le dipendenze del backend:

```bash
composer install
```

Le dipendenze del frontend con Node.js:

```bash
npm install
```
oppure
```bash
npm i
```

Per migrare il database:

```bash
php artisan migrate
```

Per popolare il database:

```bash
php artisan db:seed
```

oppure usa questo comando per migrare e popolare:
```bash
php artisan migrate:refresh --seed
```

Per generare una chiave sicura nel file env:
```bash
php artisan key:generate
```

Infine, siccome è stato utilizzato il servizio esterno di EmailJS per l'invio delle email, è necessario registrarsi sul sito di EmailJS e generare le credenziali richieste nel file `.env`.


___
### Avvio del Sistema
Per avviare i server, è necessario eseguire i seguenti comandi in finestre di terminale separate.


Per avviare il backend:
```bash
php artisan serve
```

Per avviare il frontend:
```bash
npm run dev
```

Il sistema sarà disponibile su `localhost:8000` oppure `http://127.0.0.1:8000`.


___
### Monitoraggio del Sistema
Per monitorare i jobs del sistema, risulta importante eseguire il seguente comando
(da qualsiasi command line) dalla cartella della reposiory:
```bash
php artisan schedule:work
```

Per monitorare il server SMTP, è importante eseguire il seguente comando (da qualsiasi riga di comando) dalla cartella del repository:
```bash
php artisan queue:work
```

___
### Demo del Sistema
Consigliamo di visualizzare una Demo semplificativa del sistema, reperibile da:
_Video Demo/V.E.G.A. Demo.mp4.

[Scarica la demo del sistema](_Video%20Demo/V.E.G.A.%20Demo.mp4)

---
## Licenza e Autore

**Autore:** Manuel Carlucci  
**Anno:** 2025  
**Progetto:** V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids

Questo progetto è distribuito sotto la licenza **Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)**.  
Puoi:

- **Condividere** — copiare e ridistribuire il materiale con qualsiasi mezzo o formato
- **Adattare** — remixare, trasformare e costruire sul materiale

A condizione che:

- Venga fornita **attribuzione** adeguata
- Non venga utilizzato per **scopi commerciali**
- Le opere derivate vengano distribuite con la **stessa licenza**

 [Visualizza la licenza completa](https://creativecommons.org/licenses/by-nc-sa/4.0/)

Tutti i file del progetto sono soggetti alla licenza indicata, anche se alcuni file potrebbero non avere un'intestazione esplicita.
