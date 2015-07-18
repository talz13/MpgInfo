<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/globals.php';

startMpgSession();

Config::initDb();

$body = '';
if (!checkLogin()) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_ENCODE_HIGH);
    $remoteIp = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
    $redirect = filter_input(INPUT_POST, 'redirect', FILTER_VALIDATE_URL);
    if (!$redirect) {
        $redirect = filter_input(INPUT_GET, 'redirect', FILTER_VALIDATE_URL);
    }
    if (!$redirect) {
        $redirect = filter_input(INPUT_SERVER, 'HTTP_REFERER', FILTER_VALIDATE_URL);
    }
    if (!$redirect) {
        $redirect = buildLocalPath('');
    }
    if (!is_null($username) && !is_null($password)) {
        if (count($username) > 0 && count($username) < 50 && count($password) > 0) {
            $user = User::where('username', $username)->find_one();
            if ($user) {
                if (password_verify($password, $user->password)) {
                    setUserId($user->id);
                    $loginSession = Login::create();
                    $loginSession->user_id = $user->id;
                    $loginSession->ip_address = $remoteIp;
                    $loginSession->session = session_id();
                    $loginSession->save();
                }
            }
        }
        header(sprintf("Location: %s", $redirect));

        $body .= sprintf('<META HTTP_EQUIV="Refresh" CONTENT="2; URL=%s?redirect=%s">', 'login.php', $redirect);
        $body .= '<p>Please wait while you are logged in...</p>';
    } else {
        if (checkLogin()) {
            $body .= sprintf('<META HTTP-EQUIV="Refresh" CONTENT="2; URL=%s">', $redirect);
            $body .= '<p>Redirecting you to your previous page...</p>';
        } else {
            $body .= '<form method="post">';
            $body .= sprintf('	<input type="hidden" name="redirect" value="%s">', $redirect);
            $body .= '	Username: <input type="text" name="username">';
            $body .= '	Password: <input type="password" name="password">';
            $body .= '	<input type="submit" value="Log In">';
            $body .= '</form>';
        }
    }
} else {

}

include $_SERVER['DOCUMENT_ROOT'] . '/lib/header.php';
echo $body;
include $_SERVER['DOCUMENT_ROOT'] . '/lib/footer.php';