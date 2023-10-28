<?php
session_start();
// console log
echo "<script>console.log('user_people_id:" . $_SESSION['user_people_id'] . "')</script>";
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../config_db/config_db.php';

if (!isset($_SESSION['user_login'])) {
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
} elseif (isset($_GET['exp'])) {
    include "pages/Expense/" . $_GET['exp'] . ".php";
} elseif (isset($_GET['rep'])) {
    include "pages/report/" . $_GET['rep'] . ".php";
} elseif (isset($_GET['cexp'])) {
    include "pages/Expense/" . $_GET['cexp'] . ".php";
} elseif (isset($_GET['expt'])) {
    include "pages/Expense/" . $_GET['expt'] . ".php";
} elseif (isset($_GET['c'])) {
    include "pages/customer/" . $_GET['c'] . ".php";
} elseif (isset($_GET['ch'])) {
    include "pages/cashier/" . $_GET['ch'] . ".php";
} elseif (isset($_GET['imh'])) {
    include "pages/inventory/" . $_GET['imh'] . ".php";
} elseif (isset($_GET['im'])) {
    include "pages/inventory/" . $_GET['im'] . ".php";
} elseif (isset($_GET['st'])) {
    include "pages/inventory/" . $_GET['st'] . ".php";
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
} elseif (isset($_GET['pe'])) {
    include "pages/people/" . $_GET['pe'] . ".php";
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