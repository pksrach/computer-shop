<style>
    .validation_msg{
        color: red;
        font-size: 15px;
    }
</style>

<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">បង្កើតព័ត៌មានអចលនទ្រព្យថ្មី</h1>
                </div>



            <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">

                <a class="flex-sm-fill text-sm-center nav-link"  id="create_property-tab" data-bs-toggle="tab" href="#create_property" role="tab" aria-controls="create_property" aria-selected="false">បង្កើតអចលនទ្រព្យថ្មី</a>

            </nav>
            <div class="tab-content" id="orders-table-tab-content">


                <div class="tab-pane fade show active" id="create_property" role="tabpanel" aria-labelledby="create_property-tab">   
                    <?php
                        $property_nm_msg='<div class="validation_msg">សូមបញ្ចូលឈ្មោះអចលនទ្រព្យ</div>';
                        $property_price_msg='<div class="validation_msg">សូមបញ្ចូលតម្លៃអចលនទ្រព្យ</div>';
                        $property_img_msg='<div class="validation_msg">សូមជ្រើសរើសរូបភាពអចលនទ្រព្យ</div>';
                        $msg1=$msg2=$msg3='';
                        if(isset($_POST['btnSave'])){
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
                                $msg3=$property_img_msg;
                            }else{
                                if($file_size > 2097152) {
                                    echo msgstyle("File size must be less than 2MB","info");
                                }else{
                                    if(in_array($file_ext,$extensions)===false) {
                                        echo msgstyle("extension not allowed, please choose a JPEG or PNG file.", "info");
                                    }else{
                                        if($property_type_id !='' && $property_name !='' && $property_price !='' && $property_status_id !='' && $filename !=''){
                                            $newfilename = md5(time().$filename).'.'.$file_ext;
                                            move_uploaded_file($filetmp, "assets/images/img_uploaded/" . $newfilename);

                                            // find code resize image vdo part 22



                                      /*
                                       * insert data to database

                                      $sql="INSERT INTO tbl_property(property_type_id,property_name,property_price,property_img,property_status_id,property_desc) VALUES('$property_type','$property_name','$property_price','$newfilename','$property_status','$property_desc')";
                                              $result=mysqli_query($conn,$sql);
                                                if($result){
                                                    echo msgstyle("បង្កើតព័ត៌មានអចលនទ្រព្យថ្មីបានជោគជ័យ","success");
                                                }else{
                                                    echo msgstyle("បង្កើតព័ត៌មានអចលនទ្រព្យថ្មីមិនបានជោគជ័យ","danger");
                                                }
                                       */
                                            $sql="
                                                    INSERT INTO tbl_property(
                                                            property_type_id,
                                                            property_name,
                                                            property_price,
                                                            property_status_id,
                                                            property_img,
                                                            property_desc
                                                    )VALUES (
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?,
                                                        ?
                                                    )
                                            ";
                                            $stmt=$conn->prepare($sql);
                                            $stmt->bind_param("ississ",$property_type_id,$property_name,$property_price,$property_status_id,$newfilename,$property_desc);
                                            if($stmt->execute()) {
                                                echo msgstyle("បង្កើតព័ត៌មានអចលនទ្រព្យថ្មីបានជោគជ័យ", "success");
                                            }else
                                            {
                                                echo msgstyle("បង្កើតព័ត៌មានអចលនទ្រព្យថ្មីមិនបានជោគជ័យ", "danger");
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
                                                                while($row=mysqli_fetch_assoc($sql)){
                                                                    echo"<option value='".$row['property_type_id']."'>".$row['property_type_kh']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label  class="form-label">ឈ្មោះអចនលទ្រព្យ<span style="color: red"> *</span></label>
                                                        <input type="text" class="form-control" id="txt_property_name" name="txt_property_name" value="" >
                                                        <?= $msg1; ?>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" name="txt_property_price">តម្លៃអចលនទ្រព្យ<span style="color: red"> *</span></label>
                                                        <input type="text" class="form-control" name="txt_property_price" id="txt_property_price" value="">
                                                        <?= $msg2; ?>
                                                    </div>

                                                    <div class="mb-3">
                                                            <label class="form-label" >រូបភាពអចលនទ្រព្យ<span style="color: red"> *</span></label>
                                                            <input type="file" class="form-control" name="img_property" id="img_property" value="" >
                                                            <?= $msg3; ?>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" name="sel_property_status">ជ្រើសរើសស្ថានភាពអចលនទ្រព្យ<span style="color: red"> *</span></label>
                                                        <select class="form-select" name='sel_property_status' id='sel_property_status' required>
                                                            
                                                            <option value="">---សូមជ្រើសរើស---</option>
                                                            <?php
                                                                $sql=mysqli_query($conn,"SELECT * FROM tbl_property_status");
                                                                while($row=mysqli_fetch_assoc($sql)){
                                                                    echo"<option value='".$row['property_status_id']."'>".$row['property_status']."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label  class="form-label">បរិយាយ</label>
                                                        <textarea name="tar_desc" id="tar_desc" cols="30"
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
