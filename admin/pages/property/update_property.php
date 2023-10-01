<style>
    .validation_msg{
        color: red;
        font-size: 15px;
    }
</style>

<?php
    $id=$_GET['id'];
$sql="SELECT
             p.property_id,
             p.property_img,
             p.property_name,                              
             p.property_price,
             p.property_desc,
             p.property_img,
             pt.property_type_id,
             ps.property_status_id
        FROM 
        tbl_property p
            INNER JOIN tbl_propery_type pt ON pt.property_type_id=p.property_type_id 
            LEFT  JOIN tbl_property_status ps ON ps.property_status_id=p.property_status_id
        WHERE p.property_id=$id";
        //echo $sql;


        $result=$conn->query($sql);

        $row=mysqli_fetch_array($result);
        $property_id=$row['property_type_id'];
        $property_name=$row['property_name'];
        $property_price=$row['property_price'];
        $property_status_id=$row['property_status_id'];
        $property_oimg=$row['property_img'];
        $property_desc=$row['property_desc'];
?>

<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">កែប្រែព័ត៌មានអចលនទ្រព្យថ្មី</h1>
                </div>



            <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">

                <a class="flex-sm-fill text-sm-center nav-link"  id="create_property-tab" data-bs-toggle="tab" href="#create_property" role="tab" aria-controls="create_property" aria-selected="false">កែប្រែអចលនទ្រព្យថ្មី</a>

            </nav>



            <div class="tab-content" id="orders-table-tab-content">


                <div class="tab-pane fade show active" id="create_property" role="tabpanel" aria-labelledby="create_property-tab">   
                    <?php
                        $property_nm_msg='<div class="validation_msg">សូមបញ្ចូលឈ្មោះអចលនទ្រព្យ</div>';
                        $property_price_msg='<div class="validation_msg">សូមបញ្ចូលតម្លៃអចលនទ្រព្យ</div>';
                        #$property_img_msg='<div class="validation_msg">សូមជ្រើសរើសរូបភាពអចលនទ្រព្យ</div>';
                        $msg1=$msg2=$msg3='';
                        if(isset($_POST['btnUpdate'])){
                            $property_type_id=$_POST['sel_property_type'];
                            $property_name=$_POST['txt_property_name'];
                            $property_price=$_POST['txt_property_price'];
                            $property_status_id=$_POST['sel_property_status'];
                            $property_desc=$_POST['tar_desc'];

                            $filename = $_FILES['img_property']['name'];
                            $file_size = $_FILES['img_property']['size'];
                            $filetmp = $_FILES['img_property']['tmp_name'];
                            $filetype = $_FILES['img_property']['type'];

                            $filename_bstr = explode(".", $filename);
                            $file_ext = strtolower(end($filename_bstr));
                            $extensions=array("jpeg","jpg","png");
                            if ($filename==""){
                                #$msg3=$property_img_msg;
                                $sql="
                                        UPDATE tbl_property 
                                        SET property_type_id='$property_type_id',property_name='$property_name',property_price='$property_price',property_status_id='$property_status_id',property_img='$property_oimg' ,property_desc='$property_desc' 
                                        WHERE property_id=$id";
                                #echo $sql;
                                $result=$conn->query($sql);
                                if($result){
                                    echo msgstyle("កែប្រែបានជោគជ័យ","success");
                                }else{
                                    echo msgstyle("កែប្រែមិនបានជោគជ័យ","danger");
                                }



                            }else{

                                if($file_size > 2097152) {
                                    echo msgstyle(" File size must be less than 2MB","info");
                                }else{
                                    if(in_array($file_ext,$extensions)===false) {
                                        echo msgstyle("Extension not allowed, please choose a JPEG or PNG file.", "info");
                                    }else{
                                        if(file_exists("assets/images/img_uploaded/".$filename)){
                                            echo msgstyle("Your file'<b>$filename</b>' already exists... Please choose others file...","info");
                                        }else{
                                            if(file_exists("assets/images/img_uploaded/".$property_oimg)){
                                                unlink("assets/images/img_uploaded/".$property_oimg);
                                            }

                                            if($property_type_id !='' && $property_name !='' && $property_price !='' && $property_status_id !='' && $filename !=''){
                                                #$newfilename = md5(time().$filename).'.'.$file_ext;
                                                #move_uploaded_file($filetmp, "assets/images/img_uploaded/" . $newfilename);
                                                move_uploaded_file($filetmp, "assets/images/img_uploaded/" . $filename);
                                                $sql="
                                                UPDATE tbl_property 
                                                SET property_type_id='$property_type_id',property_name='$property_name',property_price='$property_price',property_status_id='$property_status_id',property_img='$filename' ,property_desc='$property_desc' 
                                                WHERE property_id=$id";
                                                echo $sql;
                                                    $result=$conn->query($sql);
                                                    if($result){
                                                        #echo msgstyle("កែប្រែបានជោគជ័យ","success");
                                                        echo "<script>window.location.replace('index.php?p=property&msg=200');
                                                        </script>";
                                                    }else{
                                                        echo msgstyle("កែប្រែមិនបានជោគជ័យ","danger");
                                                    }

                                                // find code resize image vdo part 22

                                        }


                                        }
                                    }
                                }
                            }
                            if(trim($property_name)==""){
                                $msg1=$property_nm_msg;
                            }
                            if(trim($property_price)==""){
                                $msg2=$property_price_msg;
                            }

                        }

                    ?>


                    <h4>ចូរបំពេញព័ត៌មានអចលនទ្រព្យថ្មី</h4>

                    <div class="app-card app-card-orders-table mb-5">
                        <div class="app-card-body">

                            <div class="container-xl">

                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <div class="app-card app-card-settings shadow-sm p-4">
                                  
                                            <div class="app-card-body">
                                                <form class="settings-form" method="POST" enctype="multipart/form-data" class="settings-form" ?>

                                                    <div class="mb-3">
                                                        <label class="form-label">ជ្រើសរើសប្រភេទអចលនទ្រព្យ<span style="color: red"> *</span></label>
                                                        <select class="form-select " name="sel_property_type"  id="sel_property_type" required>
                                                            
                                                            <option value="">---សូមជ្រើសរើស---</option>
                                                            <?php
                                                                $sql=mysqli_query($conn,"SELECT * FROM tbl_propery_type");
                                                                while($r=mysqli_fetch_assoc($sql)){

                                                                    $selected='';
                                                                    if ($property_id==$r['property_type_id']){
                                                                        $selected='selected';
                                                                    }
                                                                    echo"<option $selected value='".$r['property_type_id']."'>".$r['property_type_kh']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label  class="form-label">ឈ្មោះអចនលទ្រព្យ<span style="color: red"> *</span></label>
                                                        <input type="text" class="form-control" id="txt_property_name" name="txt_property_name" value="<?=$property_name ?>" >
                                                        <?= $msg1; ?>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" name="txt_property_price">តម្លៃអចលនទ្រព្យ<span style="color: red"> *</span></label>
                                                        <input type="text" class="form-control" name="txt_property_price" id="txt_property_price" value="<?=$property_price ?>">
                                                        <?= $msg2; ?>
                                                    </div>

                                                    <div class="mb-3">
                                                            <label class="form-label" >រូបភាពអចលនទ្រព្យ</label>
                                                            <input type="file" class="form-control" name="img_property" id="img_property" value="" >

                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" name="sel_property_status">ជ្រើសរើសស្ថានភាពអចលនទ្រព្យ<span style="color: red"> *</span></label>
                                                        <select class="form-select" name='sel_property_status' id='sel_property_status' required >
                                                            
                                                            <option value="">---សូមជ្រើសរើស---</option>
                                                            <?php
                                                                $sql=mysqli_query($conn,"SELECT * FROM tbl_property_status");
                                                                while($r=mysqli_fetch_assoc($sql)){
                                                                    $selected='';
                                                                    if ($property_status_id==$r['property_status_id']){
                                                                        $selected='selected';
                                                                    }

                                                                    echo"<option $selected value='".$r['property_status_id']."'>".$r['property_status']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label  class="form-label">បរិយាយ</label>
                                                        <textarea name="tar_desc" id="tar_desc" cols="30"
                                                                  rows="3" class="form-control" style="height: 100px"><?=$property_desc?></textarea>
                                                    </div>

                                                    <button type="submit" name="btnUpdate" class="btn app-btn-primary" >កែប្រែ</button>
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
