<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31.05.2016
 * Time: 19:14
 */

$username;

define('FILE_INCLUDED', 'true');
include('ext/global.php');

//echo file_get_contents('html/login/login.html');
if($_GET['login'] == 1) {

    if(isset($_SESSION['username'])) header('Location: interface.php');

    $username = strtolower(mysqli_real_escape_string($mysql, $_POST['loginname']));
    $password = hash('sha256', mysqli_real_escape_string($mysql, $_POST['loginpass']));


    $query = $mysql->query('SELECT id,password FROM users WHERE loginname=\''.$username.'\' LIMIT 1');

    if($mysql->error) {
        $_SESSION['error'] = 'mysql';
        header('Location: error.php');
    }

    $result = mysqli_fetch_row($query);

    if($password == $result[1]) {
        if($bin->isLocked($mysql, $result[0])) {
            header('Location: index.php?login=3');
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['currentid'] = $result[0];
            global $loggedonuser;
            $loggedonuser = $username;
            $_SESSION['loggedonuser'] = 2;
            header('Location: interface.php?menu=home');
        }
    } else {
        header('Location: index.php?login=2');
    }

    //$bin->$loggedonuser = &$username;

} else if($_GET['login'] == 2) {

    echo file_get_contents('html/login/loginfail_wrong.html');

} else if($_GET['login'] == 3) {

    echo file_get_contents('html/login/loginfail_locked.html');

} else {
    if(isset($_SESSION['username'])) header('Location: interface.php');

    echo file_get_contents('html/login/login.html');

}
?>