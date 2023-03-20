<?php
include 'connect.php';

$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$funcs = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';


include $funcs . "function.php";
include $tpl . "header.php";
include $lang . "english.php";

if (!isset($noNavbar)) {
    include $tpl . "navbar.php";
}
