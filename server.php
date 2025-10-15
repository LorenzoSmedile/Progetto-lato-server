<?php
// importo libreria Ratchet con tutto quello che serve
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

// Creo classe Chat che permette di avere funzioni di MessageComponentInterface
class Chat implements MessageComponentInterface {

    // le mie proprietà
    protected $utenti = array();
    protected $nomi = array("Alessandro", "Anwar", "Mattia");
    protected $password = array("1234", "4321", "cdcdd");
    protected $online = 0;
    protected $loggati = array(); // connessione -> nome

    // funzione quando si apre connessione
    public function onOpen(ConnectionInterface $conn) {
       
    }

    // funzione di invio messagio in chat
    public function onMessage(ConnectionInterface $from, $messaggio) {
       
    }

    // funzione quando si chiude connessione
    public function onClose(ConnectionInterface $conn) {
       
    }

    // funzione di errore legato a connessione
    public function onError(ConnectionInterface $conn, \Exception $e) {
       
    }

}
// creo server WebSocket su porta 8080
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "Server WebSocket avviato sulla porta 8080...\n";
$server->run();
