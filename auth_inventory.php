<?php

require_once "auth.php";

if (
    $_SESSION['role'] != 'owner' &&
    $_SESSION['role'] != 'inventory_manager'
) {
    die("Access Denied! You are not allowed to manage inventory.");
}

?>