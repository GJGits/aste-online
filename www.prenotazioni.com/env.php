<?php 

    function isLocal() {
        return ($_SERVER["SERVER_SOFTWARE"] == "Apache/2.4.25 (Debian)") 
            && ($_SERVER["REMOTE_ADDR"] == "127.0.0.1");
    }

    function getDbHost() {
        return isLocal() ? "127.0.0.1" : "localhost"; 
    }

    function getDbUser() {
        return isLocal() ? "root" : "s255089";
    }

    function getDbPass() {
        return isLocal() ? "mypasswd" : "oudgmest";
    }

    function getDbName() {
        return isLocal() ? "s255089" : "s255089";
    }

?>