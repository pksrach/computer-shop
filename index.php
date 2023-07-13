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
	if(isset($_GET['p'])){
		include "pages/".$_GET['p'].".php";
	}else{
		include_once 'pages/homepage.php';
	}
	?>
<!--END-HOMEPAGE-->

<!--FOOTER-->
<?php
include_once 'pages/footer/footer.php';
?>
<!--END-FOOTER-->
