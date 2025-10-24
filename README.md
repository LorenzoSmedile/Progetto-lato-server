# Progetto-lato-server
Progetto lato server riguardo connessione client server,attraverso una chat dove c'è un login con nome e password e poi un scambio di messaggi con un protocollo deciso in classe. 
Il nostro progetto è stato realizzato in linguaggio di programmazione PHP usando la libreria Ratchet.
Per avviare il server su qualsiasi PC bisogna seguire questi passaggi:
1. Sapere l'indirizzo IP del PC dove vogliamo usare il server
2. Entrare nella cartella dove c'è il progetto,nella pagina index.js
3. Cambiare 'localhost' nel tuo indirizzo IP di macchina
4. scrivere su terminale il comando php -S 0.0.0.0:8080
5. Su un altra finestra del terminale,scrivere il comando php server.php che avvia il nostro websocket
6. I computer si collegano scrivendo http://indirizzoDelPC:8000