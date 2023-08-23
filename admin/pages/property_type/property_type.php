
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
                            <div class="col-auto">
                                <form class="table-search-form row gx-1 align-items-center">
                                    <div class="col-auto">
                                        <input type="text" id="search-orders" name="searchorders" class="form-control search-orders" placeholder="Search">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn app-btn-secondary">Search</button>
                                    </div>
                                </form>

                            </div><!--//col-->
                            <div class="col-auto">

                                <select class="form-select w-auto" >
                                    <option selected value="option-1">All</option>
                                    <option value="option-2">This week</option>
                                    <option value="option-3">This month</option>
                                    <option value="option-4">Last 3 months</option>

                                </select>
                            </div>
                         
                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->


            <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                <a class="flex-sm-fill text-sm-center nav-link active" id="property_type_list-tab" data-bs-toggle="tab" href="#property_type_list" role="tab" aria-controls="orders-all" aria-selected="true">បញ្ជីប្រភេទអចលនទ្រព្យ</a>
                <a class="flex-sm-fill text-sm-center nav-link"  id="create_property_type-tab" data-bs-toggle="tab" href="#create_property_type" role="tab" aria-controls="orders-paid" aria-selected="false">បង្កើតប្រភេទអចលនទ្រព្យថ្មី</a>

            </nav>

            <?php
                // update
                if(isset($_POST['btnUpdte'])){
                    $id=$_POST['u_id'];
                    $property_type_kh=$_POST['txt_property_type_kh'];
                    $property_type_en=$_POST['txt_property_type_en'];
                    $property_type_desc=$_POST['tar_property_type_desc'];

                    if(trim($property_type_kh) !='' && trim($property_type_en) !=''){
                        $sql="
                                UPDATE tbl_propery_type
                                SET property_type_kh=' $property_type_kh', property_type_en='$property_type_en', property_type_desc='$property_type_desc' 
                                WHERE property_type_id=$id      
                        ";
                         // echo $sql;
                        if(mysqli_query($conn,$sql)){
                            echo msgstyle('Data Update sucess!','success');
                        }else{
                            echo msgstyle('Data Update unsucess!','info');
                        }
                    }
                    
                }     

            ?>
            <div class="tab-content" id="orders-table-tab-content">
                <div class="tab-pane fade show active" id="property_type_list" role="tabpanel" aria-labelledby="property_type_list-tab">             
                <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left">
                                    <thead>
                                        <tr>
                                            <th class="cell">#</th>
                                            <th class="cell">ឈ្មោះអចលនទ្រព្យជាភាសាខ្មែរ</th>
                                            <th class="cell">ឈ្មោះអចលនទ្រព្យជាភាសាអង់គ្លេស</th>
                                            <th class="cell">បរិយាយ</th>
                                            <th class="cell">សកម្មភាព</th>                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
											$sql="SELECT * FROM tbl_propery_type ORDER BY property_type_id DESC";
											$result=mysqli_query($conn,$sql);
											while ($row=mysqli_fetch_array($result)){
										?>
                                        <form method="get">
                                            <input type="hidden" name="pt" value="property_type" id="">
                                            <input type="hidden" name="txtid" id="" value="<?=$row[0]?>">
											<tr>
												<td class="cell"><?=$row[0]?></td>
												<td class="cell"><?=$row[1]?></td>
												<td class="cell"><?=$row['property_type_en']?></td>
												<td class="cell"><?=$row['property_type_desc']?></td>
												<td class="cell">
                                                    <a class="btn btn-info" href="#"><i class="fas fa-eye"></i></a>
                                                    <!-- <button type="button" class="btn btn-primary"><i class="fas fa-edit"></i></button> -->
                                                    <a class="btn btn-primary" href="#" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#editModal<?=$row[0]?>"><i class="far fa-edit"></i></a>
                                                    <button type="submit" name="btnDelete" class="btn btn-danger" onclick="return confirm('Are you sur to delete it!')"><i class="fas fa-trash-alt"></i></button>
                                                   
                                            </td>
											</tr>
                                        </form>
                                            
										<?php
                                            
                                                echo'
                                                                                                
                                                    <!-- Modal -->
                                                    <div class="modal fade bd-example-modal-lg" id="editModal'.$row[0].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">កែប្រប្រភេទអចលនទ្រព្យ</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                        
                                                        <div class="app-card-body">
                                                        <form class="settings-form" method="POST" ">

                                                        <input type="hidden" name="u_id" id="" value="'.$row[0].'">

                                                            <div class="mb-3">
                                                                <label for="txt_property_type_kh" class="form-label" >ឈ្មោះប្រភេទអចលនទ្រព្យជាភាសាខ្មែរ<span style="color: red"> *</span></label>
                                                                <input type="text" name="txt_property_type_kh" class="form-control" id="txt_property_type_kh" value="'.$row['property_type_kh'].'" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="txt_property_type_en" class="form-label" name="txt_property_type_en">ឈ្មោះប្រភេទអចលនទ្រព្យជាភាសាអង់គ្លេស<span style="color: red"> *</span></label>
                                                                <input type="text" class="form-control" name="txt_property_type_en" id="txt_property_type_en" value="'.$row['property_type_en'].'" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="tar_property_type_desc" class="form-label">បរិយាយ</label>
                                                                <textarea name="tar_property_type_desc" id="tar_property_type_desc" cols="30"
                                                                          rows="3" class="form-control" style="height: 100px">'.$row['property_type_desc'].'</textarea>
                                                            </div>
                                                            <button type="submit" name="btnUpdte" class="btn app-btn-primary" >កែប្រែ</button>
                                                        </form>
                                                    </div><!--//app-card-body--> 
                                                        </div>
                                                        <div class="modal-footer">
                                                       <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                        </div>
                                                    </div>
                                                    </div>
                                                    </div>
                                                ';
											}
										?>
                                    </tbody>
                                </table>
                            </div><!--//table-responsive-->

                        </div><!--//app-card-body-->
                    </div><!--//app-card-->
                    <nav class="app-pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav><!--//app-pagination-->

                </div><!--//tab-pane-->

                <div class="tab-pane fade" id="create_property_type" role="tabpanel" aria-labelledby="create_property_type-tab">


                <?php
                // insert
                if(isset($_POST['btnSave'])){
                    $property_type_kh=$_POST['txt_property_type_kh'];
                    $property_type_en=$_POST['txt_property_type_en'];
                    $property_type_desc=$_POST['tar_property_type_desc'];
                    $sql="
                    INSERT INTO tbl_propery_type (property_type_kh, property_type_en,property_type_desc) 
                    VALUES('$property_type_kh','$property_type_en','$property_type_desc');
                    ";
                    if(mysqli_query($conn,$sql)){
                        // echo"Data inserting successfully";
                        echo msgstyle('Data inserting successfully','success');
                    }else{
                        echo"Error Inserting $sql".mysqli_error($conn) ;
                    }
                    // close connection
                    mysqli_close($conn);
                }
                        
                ?> 
                       <?php
                 // delete
                 if(isset($_GET['btnDelete'])){
                    $id=$_GET['txtid'];
                    $sql=mysqli_query($conn,"DELETE FROM tbl_propery_type WHERE property_type_id=$id");
                    if($sql){
                        echo msgstyle('Data Delete sucess!','success');
                    }else{
                        echo msgstyle('Data Delete unsucess!','info');
                    }
                }
                
            ?>
                    <div class="app-card app-card-orders-table mb-5">
                        <div class="app-card-body">
                                    <div class="container-xl">
                                        <h1 class="app-page-title">បំពេញព័ត៌មានប្រភេទនៃអចលនទ្រព្យ</h1>
                                        <!-- <hr class="my-4"> -->
                                        <div class="row">

                                            <div class="col-12 col-md-12">
                                                <div class="app-card app-card-settings shadow-sm p-4">

                                                    <div class="app-card-body">
                                                        <form class="settings-form" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                                                            
                                                        
                                                        
                                                        
                                                        <div class="mb-3">
                                                                <label for="txt_property_type_kh" class="form-label" >ឈ្មោះប្រភេទអចលនទ្រព្យជាភាសាខ្មែរ<span style="color: red"> *</span></label>
                                                                <input type="text" name="txt_property_type_kh" class="form-control" id="txt_property_type_kh" value="" required>
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
                        // alert('testing');
                        window.location.href= "index.php?pt=property_type";
                    });
                });
            </script>
        