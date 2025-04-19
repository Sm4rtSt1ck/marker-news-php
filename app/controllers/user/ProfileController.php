<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}
header("Location: /user/view?user_id=" . $_SESSION['user']['id']);
exit;
