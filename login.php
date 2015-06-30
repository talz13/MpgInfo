<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/funcs.php';

$body = '';

if (array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
    if (count($_POST['username']) > 0 && count($_POST['username']) < 50 && count($_POST['password']) > 0 && count($_POST['password']) <= 16) {
        $db = new MpgDb();
        $username = $db->escapeString($_POST['username']);
        $password = $db->escapeString($_POST['password']);
        $passwordHashed = sha1($username . $password);
        $sql = sprintf("select password from users where username='%s'", $username);
        //$sql = "insert into users(username, password, lastLoginDT, joinDT) values ('$username', '$passwordHashed', NOW(), NOW())";
        $db->runQuery($sql);

        if ($db->getRowCount() == 1) {
            $row = $db->getRow();
            if ($passwordHashed == $row['password']) {
                // Make login cookie!
                $time = time();
                //$session = sha1($passwordHashed.$time);
                $session = $passwordHashed;
                //echo "<br>session for pw: ".$session;
                //$sql = "update users set session='$session' where username='$username'";
                //mysql_query($sql);
                $loginArray = array($username, $session);
                setcookie("login", serialize($loginArray), time() + (60 * 60 * 24 * 365 * 2));
            }
        }
    }
    //echo '<META HTTP_EQUIV="Refresh" CONTENT="5; URL="login.php?redirect='.$_POST['previous'].'">';
    header("Location: login.php?redirect=" . $_POST['redirect']);

    $body .= sprintf('<META HTTP_EQUIV="Refresh" CONTENT="5; URL=login.php?redirect=%s">', $_POST['redirect']);
    $body .= '<p>Please wait while you are logged in...</p>';
} else {
    if (checkLogin()) {
        $body .= sprintf('<META HTTP-EQUIV="Refresh" CONTENT="5; URL=%s">', $_GET['redirect']);
        $body .= '<p>Redirecting you to your previous page...</p>';
    } else {
        $body .= '<form action="login.php" method="post">';
        $body .= sprintf('	<input type="hidden" name="redirect" value="%s">', $_GET['redirect']);
        $body .= '	Username: <input type="text" name="username">';
        $body .= '	Password: <input type="password" name="password">';
        $body .= '	<input type="submit" value="Log In">';
        $body .= '</form>';
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';
echo $body;
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';