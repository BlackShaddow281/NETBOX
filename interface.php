<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 24.04.16
 * Time: 00:58
 */

include('ext/global.php');

if(!$bin->isLoggedIn()) { header('Location: index.php'); }

if($bin->isLocked($mysql, $_SESSION['data']['id'])) {
    $_SESSION['error'] = 'locked';
    header('Location: error.php');
}



function controlpanel() {

    $cnp = "<li><a href='?menu=home'>Home</a></li>";

    if($_GET['menu'] == "services") {
        
    }

    $cnp .= "<li><a href="."?menu=services".">Dienste</a></li>";

    if($_GET['menu'] == "services") {
        $cnp .= "<ul class=" . "nav2" . ">";
            $cnp .= "<li><a href=" . "error.php?error=notfound" . ">Cloud</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">Mail-Server</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">MySQL</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">Netzwerklauf</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">Web-Server</a></li>";
        $cnp .= "</ul>";
    }

    $cnp .= "<li><a href="."?menu=network".">Netzwerk</a></li>";

    if($_GET['menu'] == "network") {
        $cnp .= "<ul class=" . "nav2" . ">";
            $cnp .= "<li><a href=" . "error.php" . ">DHCP</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">DNS</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">VPN</a></li>";
        $cnp .= "</ul>";
    }

    $cnp .= "<li><a href="."?menu=access".">Zugriff</a></li>";

    if($_GET['menu'] == "access") {
        $cnp .= "<ul class=" . "nav2" . ">";
            $cnp .= "<li><a href=" . "error.php" . ">Benutzer</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">Gruppen</a></li>";
            $cnp .= "<li><a href=" . "error.php" . ">Geräte</a></li>";
        $cnp .= "</ul>";
    }

    $cnp .= "<li><a href="."?menu=settings".">Einstellungen</a></li>";

    if($_GET['menu'] == "settings") {
        $cnp .= "<ul class=" . "nav2" . ">";
        $cnp .= "<li><a href=" . "error.php" . ">Systeminfo</a></li>";
        $cnp .= "<li><a href=" . "error.php" . ">DNS</a></li>";
        $cnp .= "<li><a href=" . "error.php" . ">VPN</a></li>";
        $cnp .= "</ul>";
    }

    return $cnp;
}



//Läd die control.html in das Interface und ersetzt die strings mit den Variabeln
$back = $bin->LoadHTML('control');
$back = str_replace('$CONTROLPANEL', controlpanel(), $back);
$back = str_replace('$USERNAME', $bin->getLastFirstName($mysql, $_SESSION['loggedonuser']), $back);
echo $back;



echo file_get_contents('html/interface/main.html');
echo $bin->getUsers($mysql);


/*
if($_GET['page'] == 'transfer') {

    $_SESSION['loggedIn'] = true;
    $_SESSION['id'] = $_SESSION['currentAccountID'];
    $_SESSION['accountname'] = $_SESSION['currentAccountName'];
    $_SESSION['fromInterface'] = true;

    header('Location: transfer.php?page=2');


} else if($_GET['page'] == 'control') {


include('sites/control.php');


} else if($_GET['page'] == 'federalcontrol') {


    include('sites/federal.php');


} else if($_GET['page'] == 'admin') {


    include('sites/admin.php');


} else {

    if($_GET['action'] == 'changeAccount') {
        $newid = intval(mysqli_real_escape_string($mysql, $_POST['account']));
        
        if(!$bin->isAccountValidAndBelongsTo($mysql, $newid, $_SESSION['data']['id'])) {
            unset($_SESSION['currentAccountID'], $_SESSION['currentAccountName']);
        } else {
            $_SESSION['currentAccountID'] = $newid;
            $_SESSION['currentAccountName'] = $bin->getAccountComment($mysql, $newid);
        }
    }
    
    if(!isset($_SESSION['currentAccountID'])) {
        $account = $bin->getStandardAccount($mysql, $_SESSION['data']['id']);
        //var_dump($account);
        //die();
        $_SESSION['currentAccountID'] = intval($account['id']);
        $_SESSION['currentAccountName'] = $account['accountname'];
    }

    $back = $bin->loadHTML('interface0');


    $back = str_replace('$CURRENTMONEY', $bin->getMoney($mysql, $_SESSION['currentAccountID']), $back);

    $back .= $bin->getTransfers($mysql, $_SESSION['currentAccountID']);
    $back .= $bin->loadHTML('interface2');

    $accounts = $bin->getAccounts($mysql, $_SESSION['data']['id']);

    $optionlist = '';

    foreach($accounts as $account) {

        $option = '<option value="'.$account['id'].'"';

        if(isset($_SESSION['currentAccountID'])) {
            if($account['id'] == $_SESSION['currentAccountID']) {
                $option .= ' selected';
            }
        }

        $option .= '>'.$account['comment'].'</option>';

        $optionlist .= $option;

    }

    $back = str_replace('$ACCOUNTLIST', $optionlist, $back);

    echo $back;

}
*/
?>