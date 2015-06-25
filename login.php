<?php

include $_SERVER["DOCUMENT_ROOT"] . "/lib/globals.php";
include $_SERVER["DOCUMENT_ROOT"] . "/lib/funcs.php";

if (array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
    $con = mysql_connect($dbHost, $dbUser, $dbPass);
    if (!$con) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($db, $con);

    if (count($_POST['username']) > 0 && count($_POST['username']) < 50 && count($_POST['password']) > 0 && count($_POST['password']) <= 16) {
        $username = mysql_real_escape_string($_POST['username']);
        $password = mysql_real_escape_string($_POST['password']);
        $passwordHashed = sha1($username . $password);
        $sql = "select password from users where username='$username'";
        //$sql = "insert into users(username, password, lastLoginDT, joinDT) values ('$username', '$passwordHashed', NOW(), NOW())";
        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_assoc($result);
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

    include $_SERVER["DOCUMENT_ROOT"] . "/lib/header.php";
    echo '<META HTTP_EQUIV="Refresh" CONTENT="5; URL=login.php?redirect=' . $_POST['redirect'] . '">';
    echo '<p>Please wait while you are logged in...</p>';
    include $_SERVER["DOCUMENT_ROOT"] . "/lib/footer.php";
} else {
    if (checkLogin()) {
        include $_SERVER["DOCUMENT_ROOT"] . "/lib/header.php";
        echo '<META HTTP-EQUIV="Refresh" CONTENT="5; URL=' . $_GET['redirect'] . '">';
        echo '<p>Redirecting you to your previous page...</p>';

        //if (array_key_exists('previous', $_GET))
        //	header("Location: ".$_GET['previous']);
        //else
        //	header("Location: /");
    } else {
        include $_SERVER["DOCUMENT_ROOT"] . "/lib/header.php";
        echo '<form action="login.php" method="post">';
        echo '	<input type="hidden" name="redirect" value="' . $_GET['redirect'] . '">';
        echo '	Username: <input type="text" name="username">';
        echo '	Password: <input type="password" name="password">';
        echo '	<input type="submit" value="Log In">';
        echo '</form>';
    }
    include $_SERVER["DOCUMENT_ROOT"] . "/lib/footer.php";
}
?>
