
<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">ប្រភេទនៃអចលនទ្រព្យ</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">

                            <div class="col-auto" >
                                <form method="get"  class="table-search-form row gx-1 align-items-center" >
                                    <input type="hidden" name="p" value="property">
                                    <div class="col-auto">

                                        <select class="form-select w-auto" name='key_property_type' id="key_property_type">

                                            <option value="">---សូមជ្រើសរើសប្រភេទអចលនទ្រព្យ---</option>
                                            <?php
                                            $sql=mysqli_query($conn,"SELECT * FROM tbl_propery_type");
                                            while($r=mysqli_fetch_assoc($sql)){

                                                echo"<option value='".$r['property_type_id']."'>".$r['property_type_kh']."</option>";
                                            }
                                            ?>

                                        </select>

                                    </div>


                                    <div class="col-auto ">
                                        <select class="form-select w-auto" name='key_property_status' id="key_property_status" >

                                            <option value="">---សូមជ្រើសរើសស្ថានភាព---</option>
                                            <?php
                                            $sql=mysqli_query($conn,"SELECT * FROM tbl_property_status");
                                            while($r=mysqli_fetch_assoc($sql)){
                                                echo"<option value='".$r['property_status_id']."'>".$r['property_status']."</option>";
                                            }

                                            ?>
                                        </select>
                                    </div>



                                    <div class="col-auto" >
                                        <input type="text" id="keyinputdata" name='keyinputdata' class="form-control search-orders" placeholder="Search">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" name="btnSearch" class="btn app-btn-secondary">Search</button>
                                    </div>
                                </form>

                            </div><!--//col-->



                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->


            <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                <a class="flex-sm-fill text-sm-center nav-link active" id="property_type_list-tab" data-bs-toggle="tab" href="#property_type_list" role="tab" aria-controls="orders-all" aria-selected="true">បញ្ជីប្រភេទអចលនទ្រព្យ</a>
                <a class="flex-sm-fill text-sm-center nav-link"  id="create_property_type-tab" data-bs-toggle="tab" href="#create_property_type" role="tab" aria-controls="orders-paid" aria-selected="false">បង្កើតប្រភេទអចលនទ្រព្យថ្មី</a>

            </nav>

            <?php
            if(isset($_GET['msg'])){
                $msg=$_GET['msg'];
                if($msg==200){
                    echo msgstyle("កែប្រែបានជោគជ័យ","success");
                }else if($msg==202){
                    echo msgstyle("លុបបានជោគជ័យ","danger");
                }else{
                    echo msgstyle("មិនបានជោគជ័យ","info");
                }
            }
            ?>

<!--            --><?php
//                if (isset($_GET['msg'])){
//                    $msg=$_GET['msg'];
//                    if ($msg==202){
//                        echo msgstyle("លុបបានជោគជ័យ","success");
//                    }else
//                        echo msgstyle("លុបមិនបានជោគជ័យ","danger");
//                }
//            ?>

            <div class="tab-content" id="orders-table-tab-content">
                <div class="tab-pane fade show active" id="property_type_list" role="tabpanel" aria-labelledby="property_type_list-tab">
                    
                
                <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left">
                                    <thead>
                                        <tr>
                                            <th class="cell">#</th>
                                            <th class="cell">រូបភាពអចលនទ្រព្យ</th>
                                            <th class="cell">ឈ្មោះអចលនទ្រព្យ</th>
                                            <th class="cell">តម្លៃអចលនទ្រព្យ</th>
                                            <th class="cell">បរិយាយ</th>
                                            <th class="cell">ប្រភេទអចលនទ្រព្យ</th>
                                            <th class="cell">ស្ថានភាព</th>
                                            <th class="cell">សកម្មភាព</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        #searching data
                                        if(isset($_GET['btnSearch'])){
                                            $keyproperty_type=$_GET['key_property_type'];
                                            $keyproperty_status=$_GET['key_property_status'];
                                            $keyinputdata=$_GET['keyinputdata'];

                                            #pagination when searching
                                            $number_of_page=0;
                                            $s="SELECT count(*)
                                            FROM
                                                        tbl_property p
                                            INNER JOIN tbl_propery_type pt ON pt.property_type_id = p.property_type_id
                                            LEFT JOIN tbl_property_status ps ON ps.property_status_id = p.property_status_id
                                           ";
                                            $q=$conn->query($s);
                                            $r=mysqli_fetch_row($q);
                                            $row_per_page = 5;
                                            $number_of_page = ceil($r[0]/$row_per_page); #Round numbers up to the nearest integer
                                            if(!isset($_GET['pn'])){
                                                $current_page=0;
                                            }else{
                                                $current_page = $_GET['pn'];
                                                $current_page = ($current_page-1)*$row_per_page;
                                            }





                                            $sql_select="
                                                SELECT
                                                    p.property_id,
                                                    p.property_img,
                                                    p.property_name,                              
                                                    p.property_price,
                                                    p.property_desc,
                                                    p.property_img,
                                                    pt.property_type_kh,
                                                    ps.property_status
                                                FROM 
                                                    tbl_property p
                                                INNER JOIN tbl_propery_type pt ON pt.property_type_id=p.property_type_id 
                                                LEFT  JOIN tbl_property_status ps ON ps.property_status_id=p.property_status_id
                                                                                                 
                                            ";
                                            if($keyproperty_type=="" && $keyproperty_status=="" && $keyinputdata=="")
                                            {
                                                $sql=$sql_select."LIMIT $current_page,$row_per_page";
                                            }if($keyproperty_type) {
                                                $sql = $sql_select . " WHERE
                                                    pt.property_type_id='$keyproperty_type'
                                                ORDER BY p.property_id DESC LIMIT $current_page,$row_per_page";
                                            }
                                            if($keyproperty_status){
                                                $sql=$sql_select." WHERE
                                                    ps.property_status_id='$keyproperty_status'
                                                ORDER BY p.property_id DESC LIMIT $current_page,$row_per_page";
                                            }
                                            if($keyinputdata){
                                                $sql=$sql_select."WHERE
                                                p.property_name LIKE '%".$keyinputdata."%'
                                                OR p.property_price LIKE '%".$keyinputdata."%'
                                                OR p.property_desc LIKE '%".$keyinputdata."%'
                                                OR pt.property_type_kh LIKE '%".$keyinputdata."%'
                                                OR ps.property_status LIKE '%".$keyinputdata."%'                                
                                                ORDER BY p.property_id DESC LIMIT $current_page,$row_per_page";
                                            }
                                            #echo "search= ".$sql;
                                            $result=mysqli_query($conn,$sql);
                                            $num_row=$result->num_rows;
                                        }else{
                                            #load all data
                                            #pagination when first load
                                            $number_of_page=0;
                                            $s="SELECT count(*)
                                            FROM
                                                        tbl_property p
                                            INNER JOIN tbl_propery_type pt ON pt.property_type_id = p.property_type_id
                                            LEFT JOIN tbl_property_status ps ON ps.property_status_id = p.property_status_id
                                           ";
                                            $q=$conn->query($s);
                                            $r=mysqli_fetch_row($q);
                                            $row_per_page = 5;
                                            $number_of_page = ceil($r[0]/$row_per_page); #Round numbers up to the nearest integer
                                            if(!isset($_GET['pn'])){
                                                $current_page=0;
                                            }else{
                                                $current_page = $_GET['pn'];
                                                $current_page = ($current_page-1)*$row_per_page;
                                            }



                                            $sql="SELECT
                                                    p.property_id,
                                                    p.property_img,
                                                    p.property_name,                              
                                                    p.property_price,
                                                    p.property_desc,
                                                    p.property_img,
                                                    pt.property_type_kh,
                                                    ps.property_status
                                                FROM 
                                                    tbl_property p
                                                INNER JOIN tbl_propery_type pt ON pt.property_type_id=p.property_type_id 
                                                LEFT  JOIN tbl_property_status ps ON ps.property_status_id=p.property_status_id
                                                ORDER BY p.property_id DESC  LIMIT $current_page,$row_per_page
                                                ;";
                                        }
                                        $result=mysqli_query($conn,$sql);
                                        $num_row=$result->num_rows;
                                        #echo "$num_row record(s) found";
                                        #echo $sql;

                                        if($result->num_rows>0){

                                        $i=1;
                                        while ($row=mysqli_fetch_array($result)){
                                            ?>
                                            <tr>
                                                <td class="cell"><?=$row[0]?></td>
                                                <td class="cell"><img style="width: 50px" src="assets/images/img_uploaded/<?=$row['property_img']?>" alt="image"></td>
                                                <td class="cell"><?=$row['property_name']?></td>
                                                <td class="cell"><?=$row['property_price']?></td>
                                                <td class="cell"><?=$row['property_desc']?></td>
                                                <td class="cell"><?=$row['property_type_kh']?></td>
                                                <td class="cell">
                                                    <?php
                                                        if($row['property_status']=="Sale") {
                                                            echo ' <span class="badge bg-success">' . $row['property_status'] . '</span>';
                                                        }elseif ($row['property_status']=="Booked"){
                                                            echo ' <span class="badge bg-warning">' . $row['property_status'] . '</span>';
                                                        }elseif ($row['property_status']=="Available") {
                                                            echo ' <span class="badge bg-info">' . $row['property_status'] . '</span>';
                                                        }elseif ($row['property_status']=="Blocke") {
                                                            echo ' <span class="badge bg-danger">' . $row['property_status'] . '</span>';
                                                        }else{
                                                            echo ' <span class="badge bg-secondary">N/A</span>';
                                                        }
                                                            ?>
                                                </td>

                                                        <td class="cell">
                                                            <a class="btn btn-info" href="#"><i class="fas fa-eye"></i></a>
                                                            <!-- <button type="button" class="btn btn-primary"><i class="fas fa-edit"></i></button> -->
                                                            <a class="btn btn-primary" href="index.php?p=update_property&id=<?= $row[0] ?>"><i class="far fa-edit"></i></a>
                                                            <a href="pages/property/delete_property.php?id=<?= $row[0] ?>" class="btn btn-danger" onclick="return confirm('Are you sur to delete it!')"><i class="fas fa-trash-alt"></i></a>

                                                        </td>

                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                        }else{
                                            echo'
                                                <tr>
                                                    <td colspan="8" class="cell" style="color: red;text-align: center; font-weight: bold">Data not found!</td>
                                                </tr>
                                            ';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div><!--//table-responsive-->

                        </div><!--//app-card-body-->
                    </div><!--//app-card-->


                    <?php
                        require_once 'pages/pagin/paggin.php';




                    ?>

<!--                    Start pagination-->



<!--                    end pagination-->



                </div><!--//tab-pane-->

                <div class="tab-pane fade" id="create_property_type" role="tabpanel" aria-labelledby="create_property_type-tab">


                <?php
                if(isset($_POST['btnSave'])){
                    $property_type_kh=$_REQUEST['txt_property_type_kh'];
                    $property_type_en=$_POST['txt_property_type_en'];
                    $property_type_desc=$_POST['tar_property_type_desc'];
                    $sql="
                    INSERT INTO tbl_propery_type (property_type_kh, property_type_en,property_type_desc) 
                    VALUES('$property_type_kh','$property_type_en','$property_type_desc');
                    ";
                    if(mysqli_query($conn,$sql)){
                        echo"Data inserting successfully";
                    }else{
                        echo"Error Inserting $sql".mysqli_error($conn) ;
                    }
                }
                        
                ?> 
          


                    <div class="app-card app-card-orders-table mb-5">


          

                        <div class="app-card-body">

                                    <div class="container-xl">
                                        <h1 class="app-page-title">បំពេញព័ត៌មានប្រភេទនៃអចលនទ្រព្យ</h1>
                                        <div class="row">

                                            <div class="col-12 col-md-12">
                                                <div class="app-card app-card-settings shadow-sm p-4">

                                                    <div class="app-card-body">
                                                        <form class="settings-form" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                                                            <div class="mb-3">
                                                                <label for="txt_property_type_kh" class="form-label" name="txt_property_type_kh">ឈ្មោះប្រភេទអចលនទ្រព្យជាភាសាខ្មែរ<span style="color: red"> *</span></label>
                                                                <input type="text" class="form-control" id="txt_property_type_kh" value="" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="txt_property_type_en" class="form-label" name="txt_property_type_en">ឈ្មោះប្រភេទអចលនទ្រព្យជាភាសាអង់គ្លេស<span style="color: red"> *</span></label>
                                                                <input type="text" class="form-control" name="txt_property_type_en" id="txt_property_type_en" value="" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="tar_property_type_desc" class="form-label">បរិយាយ</label>
                                                                <textarea name="tar_property_type_desc" id="tar_property_type_desc" cols="30"
                                                                          rows="3" class="form-control" style="height: 100px"></textarea>
                                                            </div>
                                                            <button type="submit" name="btnSave" class="btn app-btn-primary" >រក្សាទុក</button>
                                                        </form>
                                                    </div><!--//app-card-body-->

                                                </div><!--//app-card-->
                                            </div>

                                        </div><!--//row-->
                                        <hr class="my-4">


                                    </div><!--//app-content-->
                        </div><!--//tab-pane-->
                    </div><!--//tab-content-->
                </div><!--//container-fluid-->
            </div><!--//app-content-->



            <script type="text/javascript">
                $(document).ready(function(){
                    $("#property_type_list-tab").click(function(){
                        //alert('testing');
                        window.location.href= "index.php?pt=property_type";
                    });
                });
            </script>
