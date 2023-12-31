<?php
session_start();

if (isset($_POST['productId']) && isset($_POST['productName']) && isset($_POST['price']) && isset($_POST['qty']) && isset($_POST['maxQty'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $maxQty = $_POST['maxQty'];

    // Create an array to store the product information
    $productInfo = array(
        'productId' => $productId,
        'productName' => $productName,
        'price' => $price,
        'qty' => $qty,
        'maxQty' => $maxQty
    );

    // Check if the shopping cart exists in the session
    if (!isset($_SESSION['shoppingCart'])) {
        $_SESSION['shoppingCart'] = array();
    }

    // Check if the product is already in the shopping cart
    if (isset($_SESSION['shoppingCart'][$productId])) {
        // If the product is already in the cart, increase the quantity
        $_SESSION['shoppingCart'][$productId]['qty'] += $qty;
    } else {
        // Add the product information to the shopping cart
        $_SESSION['shoppingCart'][$productId] = $productInfo;
    }

    // You can send a response back to JavaScript if needed
    echo 'Product added to cart.';
} else {
    // Handle the case where no data was received
    echo 'No data received.';
}
