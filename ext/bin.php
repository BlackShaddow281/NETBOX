<?php

/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 24.04.16
 * Time: 20:25
 */

session_start();

class bin
{
    public function isLoggedIn() {
        if(isset($_SESSION['username'])) {
            return true;
        } else { 
            return false; 
        }
    }

    public function isDataSet() {
        if(isset($_SESSION['authority'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getData($mysql) {
        if($this->isLoggedIn() && !$this->isDataSet()) {



            $query = $mysql->query('SELECT * FROM users WHERE id='.$_SESSION['currentid']);

            if($mysql->error) {
                $_SESSION['error'] = 'mysql';
                header('Location: error.php');
            }

            $result = mysqli_fetch_row($query);

            $newkeys = array('id', 'loginname', 'password', 'firstname', 'lastname', 'birthyear', 'createdate', 'authority','passid', 'locked', 'lockpriority', 'locktime');

            $result = array_combine($newkeys, array_values($result));

            unset($result['password']);
            unset($result['firstname']);
            unset($result['lastname']);
            unset($result['birthyear']);
            unset($result['createdate']);
            unset($result['passid']);
            
            $_SESSION['data'] = $result;

        }
    }

    public function isLocked($mysql, $id) {

        $result = $mysql->query('SELECT locked, lockpriority, locktime FROM users WHERE id='.$id);

        if($mysql->error) {
            die($mysql->error);
        }

        $row = mysqli_fetch_row($result);

        if($row[0] == 0) {
            return false;
        } else if($row[0] == 2) {
            return true;
        } else if($row[0] == 1) {
            $diff = time() - strtotime($row[2]);
            if($diff < 0) {
                return true;
            } else {
                $mysql->query('UPDATE users SET locked = 0 WHERE id='.$id);
                return false;
            }
            return false;
        } else {
            return false;
        }


    }

    public function getLockTime($mysql, $id) {

        $result = $mysql->query('SELECT locked, locktime FROM users WHERE id='.$id);

        if($mysql->error) {
            die($mysql->error);
        }

        $row = mysqli_fetch_row($result);

        if($row[0] == 2) {
            return 'premanent';
        } else if($row[0] == 1) {
            return strtotime($row[1]);
        } else {
            return 'permanent';
        }

    }

    /**
     * @param mysqli
     * @param int
     * @return int
     *
     */
    public function getMoney($mysql, $id) {

        $query = $mysql->query('SELECT mcurrent FROM book_'.$id.' ORDER BY id DESC LIMIT 1');

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        return mysqli_fetch_row($query)[0];

    }

    /**
     * @param mysqli
     * @param int
     * @return int
     *
     */
    public function getMoneyAccount($mysql, $id) {

        $query = $mysql->query('SELECT mcurrent FROM book_'.$id.' ORDER BY id DESC LIMIT 1');

        if($mysql->error) {
            //$_SESSION['error'] = 'mysql';
            //header('Location: error.php');
            die($mysql->error);
        }

        return mysqli_fetch_row($query)[0];

    }

    public function getFirstName($mysql, $id) {
        $query = $mysql->query('SELECT firstname,lastname FROM users WHERE id='.$id);

        if($mysql->error) {
            //$_SESSION['error'] = 'mysql';
            //header('Location: error.php');
            die($mysql->error);
        }

        $result = mysqli_fetch_row($query);

        $back = $result[0];

        return $back;
    }

    public function getLastName($mysql, $id) {
        $query = $mysql->query('SELECT firstname,lastname FROM users WHERE id='.$id);

        if($mysql->error) {
            //$_SESSION['error'] = 'mysql';
            //header('Location: error.php');
            die($mysql->error);
        }

        $result = mysqli_fetch_row($query);

        $back = $result[1];

        return $back;
    }

    public function getFirstNameAccount($mysql, $id) {
        $query = $mysql->query('SELECT firstname,lastname FROM accounts WHERE id='.$id);

        if($mysql->error) {
            //$_SESSION['error'] = 'mysql';
            //header('Location: error.php');
            die($mysql->error);
        }

        $result = mysqli_fetch_row($query);

        $back = $result[0];

        return $back;
    }

    public function getLastNameAccount($mysql, $id) {
        $query = $mysql->query('SELECT firstname,lastname FROM accounts WHERE id='.$id);

        if($mysql->error) {
            //$_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $result = mysqli_fetch_row($query);

        $back = $result[1];

        return $back;
    }

    public function getLastFirstName($mysql, $id) {

        $result = array(
            0 => $this->getFirstName($mysql, $id),
            1 => $this->getLastName($mysql, $id)
        );

        $back = $result[1].', '.$result[0];
        if($result[1] == null || $result[1] == '') $back = $result[0];

        return $back;

    }

    public function getLastFirstNameAccount($mysql, $id) {

        $result = array(
            0 => $this->getFirstNameAccount($mysql, $id),
            1 => $this->getLastNameAccount($mysql, $id)
        );

        $back = $result[1].', '.$result[0];
        if($result[1] == null || $result[1] == '') $back = $result[0];

        return $back;

    }

    public function getBirthYear($mysql, $id) {
        $query = $mysql->query('SELECT birthyear FROM users WHERE id='.$id);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $result = mysqli_fetch_row($query);

        $back = $result[0];

        return $back;
    }

    public function getCreateDate($mysql, $id) {
        $query = $mysql->query('SELECT createdate FROM users WHERE id='.$id);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $result = mysqli_fetch_row($query);

        $back = date('j.m.Y \u\m G:i', strtotime($result[0]));

        return $back;
    }

    public function getTransfers($mysql, $id)
    {
        $back = '';

        $query = $mysql->query('SELECT * FROM book_' . $id . ' ORDER BY id DESC');


        //var_dump(mysqli_fetch_array($query));
        //die();

        if ($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        while ($row = mysqli_fetch_array($query)) {
            $string = $this->loadHTML('interface1');

            $string = str_replace('$TRANSFERID', $row['transferid'], $string);
            $string = str_replace('$AMOUNT', $row['mtransfered'], $string);
            $string = str_replace('$FROM', $this->getLastFirstNameAccount($mysql, $row['idfrom']), $string);
            $string = str_replace('$TO', $this->getLastFirstNameAccount($mysql, $row['idto']), $string);
            $string = str_replace('$MBEFORE', $row['mbefore'], $string);
            $string = str_replace('$MAFTER', $row['mcurrent'], $string);

            $back .= $string;
        }

        return $back;

    }

    public function getHidden($back) {

        //$back = str_replace('$GROUPHIDDEN', ($_SESSION['data']['authority'] == 1 || $_SESSION['data']['authority'] >= 4 ) ? '' : 'hidden', $back);
        $back = str_replace('$GROUPHIDDEN', 'hidden', $back);
        $back = str_replace('$CONTROLHIDDEN', ($_SESSION['data']['authority'] < 2) ? 'hidden' : '', $back);
        $back = str_replace('$FEDERALHIDDEN', ($_SESSION['data']['authority'] < 3) ? 'hidden' : '', $back);
        $back = str_replace('$ADMINHIDDEN', ($_SESSION['data']['authority'] < 4) ? 'hidden' : '', $back);
        $back = str_replace('$DEVHIDDEN', ($_SESSION['data']['authority'] < 5) ? 'hidden' : '', $back);

        return $back;
    }

    public function loadHTML($filename) {
        $back = file_get_contents('html/interface/'.$filename.'.html');

        $back = $this->getHidden($back);

        return $back;
    }

    public function isIDvalid($mysql, $id) {

        $query = $mysql->query('SELECT * FROM users WHERE id='.$id);

        if (mysqli_num_rows($query) == 0) {
            return false;
        } else {
            return true;
        }

    }

    public function getAuthority($mysql, $id) {

        $query = $mysql->query('SELECT authority FROM users WHERE id='.$id);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $result = mysqli_fetch_row($query);

        return $back = $result[0];

    }

    public function getAuthorityString($mysql, $id) {
        $authority = $this->getAuthority($mysql, $id);

        if($authority == 5) return 'Entwickler';
        if($authority == 4) return 'Administrator';
        if($authority == 3) return 'Bundmitarbeiter';
        if($authority == 2) return 'Bankmitarbeiter';
        if($authority == 1) return 'Betreuer';
        if($authority == 0) return 'Nutzer';
    }

    public function getLoginName($mysql, $id) {

        $query = $mysql->query('SELECT loginname FROM users WHERE id='.$id);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $result = mysqli_fetch_row($query);

        return $result[0];

    }

    public function getPassID($mysql, $id) {

        $query = $mysql->query('SELECT passid FROM users WHERE id='.$id);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $result = mysqli_fetch_row($query);

        return $result[0];

    }

    public function getAccounts($mysql, $id) {

        $sql = 'SELECT * FROM accounts WHERE connection='.$id;

        $query = $mysql->query($sql);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $back = array();

        while($row = mysqli_fetch_array($query)) {
            $account = array();
            $account['id'] = $row['id'];
            $account['name'] = $row['accountname'];
            $account['firstname'] = $row['firstname'];
            $account['lastname'] = $row['lastname'];
            $account['comment'] = $row['comment'];
            $account['birthyear'] = $row['birthyear'];
            $account['createdate'] = $row['createdate'];

            $back[] = $account;
        }

        return $back;

    }

    public function getUsers($mysql) {

        $sql = 'SELECT * FROM users';

        $query = $mysql->query($sql);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $back = array();

        while($row = mysqli_fetch_array($query)) {
            $account = array();
            $account['id'] = $row['id'];
            $account['loginname'] = $row['loginname'];
            $account['firstname'] = $row['firstname'];
            $account['lastname'] = $row['lastname'];
            
            $back[] = $account;
        }

        return $back;

    }

    public function getStandardAccount($mysql, $id) {
        $sql = 'SELECT id, accountname FROM accounts WHERE connection='.$id.' ORDER BY createdate ASC LIMIT 1';

        $result = $mysql->query($sql);

        if($mysql->error) {
            $_SESSION['error'] = 'mysql';
            header('Location: error.php');
        }

        $row = mysqli_fetch_array($result);

        $row['id'] = intval($row['id']);

        return $row;

    }

    public function isAccountValidAndBelongsTo($mysql, $accountid, $userid) {

        $sql = 'SELECT connection FROM accounts WHERE id='.$accountid;

        $result = $mysql->query($sql);

        $row = mysqli_fetch_array($result);

        if($row['connection'] == $userid) {
            return true;
        } else {
            return false;
        }

    }

    public function getAccountComment($mysql, $id) {

        $sql = 'SELECT comment FROM accounts WHERE id='.$id;

        $result = $mysql->query($sql);

        return mysqli_fetch_array($result)['comment'];

    }

    public function getAccountName($mysql, $id) {

        $sql = 'SELECT accountname FROM accounts WHERE id='.$id;

        $result = $mysql->query($sql);

        return mysqli_fetch_array($result)['accountname'];

    }

    function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

}