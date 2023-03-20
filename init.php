<?php
include 'admin/connect.php';

$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}
$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$funcs = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';


include $funcs . "function.php";
include $tpl . "header.php";
include $lang . "english.php";
include $tpl . "navbar.php";

