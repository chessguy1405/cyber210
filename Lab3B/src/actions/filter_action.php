<?php
session_start();

if ($_SESSION['filtered']) {
    $_SESSION['filtered'] = false;
} else {
    $_SESSION['filtered'] = true;
}

header("Location: /");

?>