<?php
    session_start();
    date_default_timezone_set("Asia/Phnom_Penh");
    include_once '../config_db/config_db.php';
?>
<!--header-->
<?php
include_once "pages/menu.php";
?>
<!--end-header-->
<!--homepage-->
<?php
if(isset($_GET['pg'])){
    include "pages/".$_GET['pg'].".php";
}
elseif(isset($_GET['pt'])){
    include "pages/property_type/".$_GET['pt'].".php";
}
elseif(isset($_GET['p'])){
    include "pages/property/".$_GET['p'].".php";
}
elseif(isset($_GET['agency'])){
    include "pages/agency/".$_GET['agency'].".php";
}else{
    include_once 'pages/homepage.php';
}
?>
<!--end-homepage-->

<!--footer-->
<?php
    require_once 'pages/footer/footer.php';
?>
<!--end-footer-->
