<?php
session_start();

if ($_SESSION['sorted']) {
    $_SESSION['sorted'] = false;
} else {
    $_SESSION['sorted'] = true;
}

header("Location: /");

?>