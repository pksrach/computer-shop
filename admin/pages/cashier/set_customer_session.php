<?php
session_start();
echo "<script>alert('Customer')</script>";

if (isset($_POST['customerId'])) {
    $_SESSION['customer_id'] = $_POST['customerId'];
    echo "<script>alert('Customer id=>" . $_SESSION['customer_id'] . "')</script>";
}
