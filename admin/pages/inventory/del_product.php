<?php
include_once '../../../config_db/config_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM tbl_product WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Failed to delete data: " . mysqli_error($conn));
    } else {
        header("Location: ../../index.php?p=product&msg=202");
    }
}
