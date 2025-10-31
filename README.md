# Progetto-lato-server

Progetto lato server riguardo connessione client server,attraverso una chat dove c'è un login con nome e password e poi un scambio di messaggi con un protocollo deciso in classe. 
Il nostro progetto è stato realizzato in linguaggio di programmazione PHP usando la libreria Ratchet.
Per avviare il server su qualsiasi PC bisogna seguire questi passaggi:
1. Creare cartella con progetto su PC
2. Installare php e composer sul PC
3. Entrare nella cartella del progetto 
4. Aprire il terminale e fare il comando: php -/composer.phar require cboden/ratchet; Serve per installare la libreria dentro la cartella
5. Prendere il file server.php dal git e metterlo sulla cartella del progetto
6. Sapere l'indirizzo IP del PC dove vogliamo usare il server
7. Entrare nella cartella dove c'è il progetto,nella pagina index.js
8. Cambiare 'localhost' nel tuo indirizzo IP di macchina
9. scrivere su terminale il comando php -S 0.0.0.0:8000
10. Su un altra finestra del terminale,scrivere il comando php server.php che avvia il nostro websocket
11. I computer si collegano scrivendo http://indirizzoDelPC:8000

   
