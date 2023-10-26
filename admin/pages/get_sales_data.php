<?php
// Include the database connection script (e.g., config_db.php)
include_once("../config_db/config_db.php");

// Initialize the response array
$response = array();

// Query to retrieve sales data
$sql = "SELECT sale_date, total FROM tbl_sales";

// Execute the query
$result = $conn->query($sql);

if ($result) {
    $salesData = array();

    while ($row = $result->fetch_assoc()) {
        $salesData[] = $row;
    }

    if (!empty($salesData)) {
        $response['success'] = true;
        $response['data'] = $salesData;
    } else {
        $response['success'] = false;
        $response['message'] = "No data found";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Database error: " . $conn->error;
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Return the response as JSON
echo json_encode($response);
