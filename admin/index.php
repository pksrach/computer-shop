<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../config_db/config_db.php';

if(!isset($_SESSION['user_login'])){
    header("location: login.php");
}
?>
<!--header-->
<?php
include_once "pages/menu.php";
?>
<!--end-header-->
<!--homepage-->
<?php
if (isset($_GET['pg'])) {
    include "pages/" . $_GET['pg'] . ".php";
} elseif (isset($_GET['im'])) {
    include "pages/import_stock/" . $_GET['im'] . ".php";
} elseif (isset($_GET['st'])) {
    include "pages/stock/" . $_GET['st'] . ".php";
} elseif (isset($_GET['um'])) {
    include "pages/unit_measurement/" . $_GET['um'] . ".php";
} elseif (isset($_GET['br'])) {
    include "pages/brand/" . $_GET['br'] . ".php";
} elseif (isset($_GET['pt'])) {
    include "pages/category/" . $_GET['pt'] . ".php";
} elseif (isset($_GET['p'])) {
    include "pages/product/" . $_GET['p'] . ".php";
} elseif (isset($_GET['agency'])) {
    include "pages/agency/" . $_GET['agency'] . ".php";
} else {
    include_once 'pages/homepage.php';
}
?>
<!--end-homepage-->

<!--footer-->
<?php
require_once 'pages/footer/footer.php';
?>
<!--end-footer-->