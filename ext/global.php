<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 23.04.16
 * Time: 23:54
 */

include('bin.php');
include('data.php');

$bin = new bin();

$mysql = new mysqli($mysqlhost, $mysqluser, $mysqlpass);

if($mysql->connect_error) {
    echo "error";
    header('Location: setup.php');
}

if(!$mysql->select_db($mysqldb)) {
    header('Location: setup.php');
}


// Tabelle für Benutzer (users)
$sql = 'CREATE TABLE IF NOT EXISTS `users` (
 `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `loginname` VARCHAR(50) NOT NULL COMMENT \'Name zum anmelden\',
 `password` TEXT NOT NULL COMMENT \'Passwort(sha-256)\',
 `firstname` VARCHAR(50) NOT NULL COMMENT \'Vorname\',
 `lastname` VARCHAR(50) NOT NULL COMMENT \'Nachname\',
/* `birthyear` INT(4) NOT NULL COMMENT \'Geburtsjahr\',*/
 `createdate` DATETIME NOT NULL COMMENT \'Erstellungsdatum\',
 `authority` INT(1) NOT NULL COMMENT \'Autorität(0=Bürger, 1=Betreuer, 2=Bankangestellter,  3=Admin, 4=Bund, 5=Entwickler)\',
/* `passid` INT(11) NOT NULL COMMENT \'Passnummer\',*/
 `locked` INT(1) NOT NULL COMMENT \'Gesperrt(0=nicht, 1=Bis Datum, 2=Permanent)\',
 `lockpriority` INT(11) NOT NULL,
 `locktime` DATETIME NOT NULL COMMENT \'Sperrung bis\'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1';

// Tabelle für RechtenGroupen (permissions_groups)
$sql2 = 'CREATE TABLE IF NOT EXISTS `permissions_groups` (
 `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `groupname` VARCHAR(50) NOT NULL,
 `createdate` datetime DEFAULT NULL,
 `allpermissions` int(1) NOT NULL,
 `editusers` int(1) NOT NULL,
 `allservices` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1';

// Tabelle für
$sql3 = 'CREATE TABLE IF NOT EXISTS `accounts` (
 `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `accountname` varchar(50) DEFAULT NULL,
 `comment` varchar(50) NOT NULL,
 `password` text COMMENT \'SHA-256\',
 `firstname` varchar(50) DEFAULT NULL,
 `lastname` varchar(50) DEFAULT NULL,
 `birthyear` int(4) DEFAULT NULL,
 `createdate` datetime DEFAULT NULL,
 `connection` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1';

$mysql->query($sql);
if($mysql->error) {
    die($mysql->error);
}
$mysql->query($sql2);
if($mysql->error) {
    die($mysql->error);
}
$mysql->query($sql3);
if($mysql->error) {
    die($mysql->error);
}

$bin->getData($mysql);



//newUsers($mysql);








function newUsers($mysql)
{
        //Personal Data
        $firstname = "SYSTEM";
        $lastname = "SYSTEM";
        //$birthyear = mysqli_real_escape_string($mysql, $_POST['birthyear']);
        //$passid = mysqli_real_escape_string($mysql, $_POST['passid']);

        //Login Data
        $loginname = strtolower(substr($firstname, 0, 1) . $lastname);
        //Passwort md5 gehasht
        $password = hash('sha256', "password");

        //Daten, die vom System erzeugt werden, erstellen
        $createdate = date('Y-m-d H:i:s');

        //Überprüfen, ob alle Daten nicht leer sind
        if ($firstname == '' || $lastname == '' || $loginname == '' || $password == '') {
            //$_SESSION['error'] = 'newuser';
            //header('Location: error.php');
            die('TEST');
        }

        //SQL-Befehl vorbereiten und ausführen
        $sql = 'INSERT INTO users (loginname, password, firstname, lastname, createdate)
                VALUES (\'' . $loginname . '\', \'' . $password . '\', \'' . $firstname . '\', \'' . $lastname . '\', \'' . $createdate . ');';

        //Umlaute ersetzen
        $umlaute = Array("/ä/", "/ö/", "/ü/", "/Ä/", "/Ö/", "/Ü/", "/ß/");
        $replace = Array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
        $sql = preg_replace($umlaute, $replace, $sql);


        $mysql->query($sql);

        if ($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
            //die($sql.'<br><br>'.$mysql->error);
        }
}

?>