<?php
session_start();

if (isset($_POST['productName']) && isset($_POST['price']) && isset($_POST['qty']) && isset($_POST['maxQty'])) {
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $maxQty = $_POST['maxQty'];

    // Create an array to store the product information
    $productInfo = array(
        'productName' => $productName,
        'price' => $price,
        'qty' => $qty,
        'maxQty' => $maxQty
    );

    // Check if the shopping cart exists in the session
    if (!isset($_SESSION['shoppingCart'])) {
        $_SESSION['shoppingCart'] = array();
    }

    // Add the product information to the shopping cart
    $_SESSION['shoppingCart'][$productName] = $productInfo;

    // You can send a response back to JavaScript if needed
    echo 'Product added to cart.';
} else {
    // Handle the case where no data was received
    echo 'No data received.';
}
