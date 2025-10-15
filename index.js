"use strict";

let ws;              // Variabile per la connessione WebSocket
let loggato = false; // Serve per sapere se l'utente ha fatto il login

// Prendo i vari elementi dalla pagina
const loginDiv = document.getElementById("loginDiv");
const chatDiv = document.getElementById("chatDiv");
const ricezione = document.getElementById("ricezione");

// --- LOGIN ---
function login() {
    // Leggo nome e password scritti dall’utente
    const nome = document.getElementById("nome").value.trim();
    const password = document.getElementById("password").value.trim();

    // Se i campi sono vuoti, fermo tutto
    if (nome === "" || password === "") {
        alert("Inserisci nome e password!");
        return;
    }

    // Apro la connessione al server WebSocket (porta 8080)
    ws = new WebSocket("ws://localhost:8080");

    // Quando la connessione è pronta
    ws.onopen = () => {
        console.log("Connesso al server");
        // Mando al server il login nel formato "nome:password"
        ws.send("log"+"|"+nome + "|" + password);
    };

    // Quando ricevo un messaggio dal server
    ws.onmessage = (event) => {
        const messaggio = event.data;
        ricezione.innerHTML += messaggio + "<br>"; // lo mostro nella chat
        ricezione.scrollTop = ricezione.scrollHeight; // scorro in basso

        // Se il login è corretto, passo alla chat
        if (messaggio.includes("Login effettuato")) {
            loggato = true;
            loginDiv.style.display = "none";
            chatDiv.style.display = "block";
        }
    };

    // Se c’è un errore nella connessione
    ws.onerror = (errore) => {
        console.error("Errore WebSocket:", errore);
    };

    // Quando il server chiude la connessione
    ws.onclose = () => {
        console.log("Connessione chiusa");
        loggato = false;
        loginDiv.style.display = "block";
        chatDiv.style.display = "none";
    };

    // Pulisco i campi del form
    document.getElementById("nome").value = "";
    document.getElementById("password").value = "";
}

// --- INVIO MESSAGGIO ---
function inviaMessaggio() {
    const testo = document.getElementById("messaggio").value.trim();
    if (testo === "" || !loggato) return;

    const data = new Date().toLocaleTimeString();

    // Se il campo è vuoto o non sei loggato, non fare nulla
    if (testo === "" || !loggato) return;

    // Mando il messaggio al server
     ws.send("rlo|" + nome + "|" + data + "|" + testo);

    // Mostro subito il messaggio anche nella mia chat
    ricezione.innerHTML += "Tu (" + data + "): " + testo + "<br>";
    ricezione.scrollTop = ricezione.scrollHeight;

    // Pulisco il campo di testo
    document.getElementById("messaggio").value = "";
}
