<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 24.04.16
 * Time: 00:47
 */

echo file_get_contents('html/error.html');

if($_GET['error'] == "notfound") {
    echo "Ursache: Angefragte Dienst oder Objekt nicht gefunden";
} else {
    echo "Ursache nicht gefunden!";
	echo "test";
}
?>