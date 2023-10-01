<?php
    session_start();
    date_default_timezone_set("Asia/Phnom_Penh");
    include_once '../../../config_db/config_db.php';

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE FROM tbl_property WHERE property_id = '$id'";
        $query = mysqli_query($conn, $sql);

        if($query){
            header("location: ../../index.php?p=property&msg=202");
        }else{
            echo "Error: in deleting a record!".mysqli_error($conn);
        }
    }



?>