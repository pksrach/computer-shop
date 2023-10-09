 
<!-- Connectiong  -->
<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once './config_db/config_db.php';

?>

<!--HEADER-->
<?php
include 'pages/header/header.php';
?>
<!--end HEADER-->
<!--MENU-->
<?php
require_once 'pages/menu.php';
?>
<!--END-MENU-->
<!--HOMEPAGE-->
<?php
if (isset($_GET['p'])) {
	include "pages/" . $_GET['p'] . ".php";
} else {
	include_once 'pages/homepage.php';
}
?>
<!--END-HOMEPAGE-->

<!--FOOTER-->
<?php
include_once 'pages/footer/footer.php';
?>
<!--END-FOOTER-->