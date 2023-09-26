<?php
 $dbhost='localhost'; #localhost:3306, 127.0.0.1
    $dbuser='root';
    $dbpwd='';
    $conn=mysqli_connect($dbhost,$dbuser,$dbpwd);
    if(!$conn){
        die("Connection Failed.!".mysqli_connect_error());
        exit();
    }
    mysqli_select_db($conn,"computer-shop-db") or die("Error selecting from database");
    printf("Your Connection Successfully");
?>
<?php
    function msgstyle($msg, $type){
        switch($type){
            case 'success':
                echo'
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong>  '.$msg.'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
                break;
            case 'warning':
                echo'
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Warning!</strong> '.$msg.'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
                break;
            case 'danger':
                echo'
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Danger!</strong> '.$msg.'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
                break;
            case 'info':
                echo'
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Info!</strong> '.$msg.'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
                break;
        }
    }
?>