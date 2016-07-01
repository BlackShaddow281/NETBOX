<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 18.05.16
 * Time: 22:00
 */

if($_GET['page'] == 1) {
    
    if(!isset($_POST['host']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['db'])) {
        header('Location: setup.php');
        die();
    }

    $mysqlhost = mysql_real_escape_string($_POST['host']);
    $mysqluser = mysql_real_escape_string($_POST['username']);
    $mysqlpass = mysql_real_escape_string($_POST['password']);
    $mysqldb = mysql_real_escape_string($_POST['db']);
    //echo $_SERVER['DOCUMENT_ROOT'];

    //New Data
    $newcontent = "<?php
    \r/**
    \r* NETBOX
    \r* @author Developer
    \r* @version 1.4
    \r* 
    \r* In this file the MySQL Data is saved
    \r*/
    \r\r\$mysqlhost='".$mysqlhost."';
    \r\$mysqluser='".$mysqluser."';
    \r\$mysqlpass='".$mysqlpass."';
    \r\$mysqldb='".$mysqldb."';
    \r\r?>";

    $path1 = 'DOCUMENT_ROOT';
    $path2 = 'NETBOX/ext/data.php';
    echo $path1.$path2;
    file_put_contents($_SERVER[$path1].$path2, $newcontent);

    ?>

    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Tobias Schwarz">
        <meta http-equiv="refresh" content="5; URL=index.php">
        <title>Setup</title>
        <!-- Bootstrap core CSS -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="starter-template.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
    <h1>Setup erfolgreich abgeschlossen!</h1>
    </body>
    </html>

    <?php

    //$fhandle = fopen($fname,"w");
    //fwrite($fhandle,$content);
    //fclose($fhandle);

    
} else {
?>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Tobias Schwarz">
        <title>Setup</title>
        <!-- Bootstrap core CSS -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="starter-template.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
<body>

<form method="post" action="setup.php?page=1">
    <p class="lead">Bitte Datenbankinformationen angeben:</p>
    Host: <input type="text" name="host" placeholder="Host">
    <br>
    Datenbank: <input type="text" name="db" placeholder="Datenbank">
    <br>
    Benutzername: <input type="text" name="username" placeholder="Benutzername">
    <br>
    Passwort: <input type="password" name="password" placeholder="Passwort">
    <br><br>
    <input type="submit" value="Abschicken">
</form>

</body>


    </html>


<?php
}