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
    protected $online = 0;
    protected $loggati = array(); // connessione -> nome

    // funzione quando si apre connessione
    public function onOpen(ConnectionInterface $conn) {
        echo "Nuova connessione ID: {$conn->resourceId}\n";
        // non aggiungo subito alla lista, prima deve fare login
    }

    // funzione di invio messagio in chat
    public function onMessage(ConnectionInterface $from, $messaggio) {
        $parti = explode("|", $messaggio);
        $sin=$parti[0] ?? "";

        // controllo se utente è logato
        if (!isset($this->loggati[$from->resourceId])) {
            // significa che sta cercando di fare login
            $nome = $parti[1] ?? "";
            $password = $parti[2] ?? "";
            if($sin=="log"){
            $this->login($from, $nome, $password);
            }
            return;
        }

        // se è loggato, allora invio messaggio agli altri utenti
        $nomeMittente = $this->loggati[$from->resourceId];

        for ($i = 0; $i < count($this->utenti); $i++) {
            $utente = $this->utenti[$i];
            
            $data = $parti[2] ?? "";
            $testo = $parti[3] ?? "";

            if ($utente !== $from && $sin=="rlo") {
                 foreach ($this->utenti as $utente) {
                $utente->send("msg|$nomeMittente dice|($data):|$testo");
            }

            }
        }
    }

    // funzione quando si chiude connessione
    public function onClose(ConnectionInterface $conn) {
        if (isset($this->loggati[$conn->resourceId])) {
            $nome = $this->loggati[$conn->resourceId];
            unset($this->loggati[$conn->resourceId]);

            // rimuovo utente da mia array utenti
            for ($i = 0; $i < count($this->utenti); $i++) {
                if ($this->utenti[$i] === $conn) {
                    unset($this->utenti[$i]);
                    $this->utenti = array_values($this->utenti); // ricompongo array
                    break;
                }
            }

         // Elenco utenti online
            $chiavi = array_keys($this->loggati);
            $listaUtentiLog = "ele";

            // Costruisci la lista completa
            for ($i = 0; $i < count($chiavi); $i++) {
                $id = $chiavi[$i];
                $nomeUtente = $this->loggati[$id];
                $listaUtentiLog .="|". $nomeUtente;
            }

            // Poi inviala a tutti gli utenti loggati
            foreach ($this->utenti as $utente) {
                $utente->send($listaUtentiLog);
            }

            $this->online--;
            echo "$nome si è disconnesso. Online: {$this->online}\n";
            echo "Utenti online: $listaUtentiLog \n";
        }
    }

    // funzione di errore legato a connessione
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Errore: {$e->getMessage()}\n";
        $conn->close();
        
    }

    // funzione di login
    public function login(ConnectionInterface $conn, $nome, $password) {
        $trovato = false;

        // apro file utenti
        $myfile = fopen("ListaUtenti.txt", "r") or die("Unable to open file!");

        while(!feof($myfile)) {
            $riga=fgets($myfile);
            if(trim($riga) === "") continue; // ignoro righe vuote

            $riga=explode(",", trim($riga));

            if ($nome === $riga[0] && $password === $riga[1]) {
                $trovato = true;
                break;
            }
        }
        
        fclose($myfile);

        if ($trovato) {
            $this->utenti[] = $conn;
            $this->loggati[$conn->resourceId] = $nome;
            $this->online++;
            $conn->send("rlo|Login effettuato");

            // Elenco utenti online
            $chiavi = array_keys($this->loggati);
            $listaUtentiLog = "ele|";

            // Costruisci la lista completa
            for ($i = 0; $i < count($chiavi); $i++) {
                $id = $chiavi[$i];
                $nomeUtente = $this->loggati[$id];
                $listaUtentiLog .= $nomeUtente . "|";
            }

            // Poi inviala a tutti gli utenti loggati
            foreach ($this->utenti as $utente) {
                $utente->send($listaUtentiLog);
            }

            echo "$nome si è connesso. Online: {$this->online}\n";
            echo "Utenti online: $listaUtentiLog \n";
        } else {
            $conn->send("rlo|Login errato");
            $conn->close();
        }
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