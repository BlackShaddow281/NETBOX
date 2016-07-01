<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 30.04.16
 * Time: 17:15
 *
 * Dieses Script loggt einen User wieder aus
 */

session_start();

session_destroy();

header('Location: index.php');

?>