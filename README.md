# prenotazioni-mediche

Repository contenente i sorgenti per il tema d'esame di Programmazione Distribuita I relativo all'appello di settembre a.a 2018/2019. Il progetto è strutturato per essere utilizzato con [Docker](https://www.docker.com/).

## Struttura progetto

* **docs:** contiene file `.pdf` utili alla stesura del codice, la cartella contiene inoltre i requisiti del progetto.

* **db:** mounted volume che contiene i file del db, questa cartella contiene inoltre i vari dump della base di dati.

> **NB:** i cambiamenti effettuati su questa cartella si riflettono sul db presente nel container.

* **www.prenotazioni.com:** directory contenente i file relativi al sito

## Attivazione/Disattivazione container

Attualmente per attivare e disattivare i container necessari al funzionamento dell'applicazione basta eseguire rispettivamente i seguenti comandi: `./start-services.sh`, `stop-services.sh`. In futuro verrà creato un apposito file `docker-compose.yml` per la gestione dei servizi. 

> **NB:** gli script sono eseguibili su macchina linux, assicurarsi inoltre che i file godano dei permessi `rwx`.
