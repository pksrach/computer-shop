<?php
session_start();

if (isset($_POST['customerId'])) {
    $_SESSION['customer_id'] = $_POST['customerId'];
    echo "Customer ID set in PHP session.";
}
