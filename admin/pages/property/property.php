
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
                            <div class="col-auto">
                                <a class="btn app-btn-secondary" href="#">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-download me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                    Download CSV
                                </a>
                            </div>
                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->


            <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                <a class="flex-sm-fill text-sm-center nav-link active" id="property_type_list-tab" data-bs-toggle="tab" href="#property_type_list" role="tab" aria-controls="orders-all" aria-selected="true">បញ្ជីប្រភេទអចលនទ្រព្យ</a>
                <a class="flex-sm-fill text-sm-center nav-link"  id="create_property_type-tab" data-bs-toggle="tab" href="#create_property_type" role="tab" aria-controls="orders-paid" aria-selected="false">បង្កើតប្រភេទអចលនទ្រព្យថ្មី</a>

            </nav>
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
                                            <th class="cell">តម្លៃអចលនទ្រព្យ</th>
                                            <th class="cell">បរិយាយ</th>
                                            <th class="cell">រូបភាព</th>
                                            <th class="cell">ស្ថានភាព</th>
                                            <th class="cell"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql="SELECT
                                                p.property_id,
                                                p.property_name,
                                                p.property_price,
                                                p.property_desc,
                                                p.property_img,
                                                pt.property_type_kh
                                            FROM 
                                            tbl_property p
                                            INNER JOIN tbl_propery_type pt ON pt.property_type_id=p.property_type_id 
                                            ORDER BY p.property_id DESC ";
                                        $result=mysqli_query($conn,$sql);
                                        while ($row=mysqli_fetch_array($result)){
                                            ?>
                                            <tr>
                                                <td class="cell"><?=$row[0]?></td>
                                                <td class="cell"><?=$row['property_name']?></td>
                                                <td class="cell"><?=$row['property_price']?></td>
                                                <td class="cell"><?=$row['property_desc']?></td>
                                                <td class="cell"><?=$row['property_img']?></td>
                                                <td class="cell"><?=$row['property_type_kh']?></td>
                                                <td class="cell"><span class="badge bg-success">Paid</span></td>
                                                <td class="cell"><a class="btn-sm app-btn-secondary" href="#">View</a></td>
                                            </tr>
                                            <?php
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
        