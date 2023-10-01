<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">ខ្នាតនៃផលិតផល</h1>
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

                                <select class="form-select w-auto">
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
                <a class="flex-sm-fill text-sm-center nav-link active" id="brand_list-tab" data-bs-toggle="tab" href="#brand_list" role="tab" aria-controls="orders-all" aria-selected="true">បញ្ជីខ្នាត</a>
                <a class="flex-sm-fill text-sm-center nav-link" id="create_brand_list-tab" data-bs-toggle="tab" href="#create_brand" role="tab" aria-controls="orders-paid" aria-selected="false">បង្កើតខ្នាតថ្មី</a>
            </nav>

            <?php
            // update
            if (isset($_POST['btnUpdate'])) {
                $id = $_POST['u_id'];
                $txt_um_name = $_POST['txt_um_name'];
                $txt_rate = $_POST['txt_rate'];

                if (trim($txt_um_name) != '') {
                    $sql = "
                        UPDATE tbl_unit_measurement
                        SET unit_name=' $txt_um_name', rate='$txt_rate'
                        WHERE id=$id      
                    ";
                    // echo $sql;
                    if (mysqli_query($conn, $sql)) {
                        echo msgstyle('Data Update sucess!', 'success');
                    } else {
                        echo msgstyle('Data Update unsucess!', 'info');
                    }
                }
            }

            ?>
            <div class="tab-content" id="orders-table-tab-content">
                <div class="tab-pane fade show active" id="brand_list" role="tabpanel" aria-labelledby="brand_list-tab">
                    <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left">
                                    <thead>
                                        <tr>
                                            <th class="cell">លេខសម្គាល់#</th>
                                            <th class="cell">ឈ្មោះខ្នាត</th>
                                            <th class="cell">បរិមាណ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM tbl_unit_measurement ORDER BY id DESC";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <form method="get">
                                                <input type="hidden" name="um" value="unit_measurement" id="">
                                                <input type="hidden" name="txtid" id="" value="<?= $row['id'] ?>">
                                                <tr>
                                                    <td class="cell"><?= $row['id'] ?></td>
                                                    <td class="cell"><?= $row['unit_name'] ?></td>
                                                    <td class="cell"><?= $row['rate'] ?></td>
                                                    <!-- Button action -->
                                                    <td class="cell">
                                                        <!-- <a class="btn btn-info" href="#"><i class="fas fa-eye"></i></a> -->
                                                        <a class="btn btn-primary" href="#" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="far fa-edit"></i></a>
                                                        <button type="submit" name="btnDelete" class="btn btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបវាមែនទេ ?')"><i class="fas fa-trash-alt"></i></button>

                                                    </td>
                                                </tr>
                                            </form>

                                        <?php
                                            echo '                                        
                                                <!-- Modal -->
                                                <div class="modal fade bd-example-modal-lg" id="editModal' . $row['id'] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                    
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">កែប្រែ</h5>
                                                            </div>
                                                        <div class="modal-body">
                                                        
                                                        <div class="app-card-body">
                                                            <form class="settings-form" method="POST">
                                                                <input type="hidden" name="u_id" id="" value="' . $row['id'] . '">
                                                                <div class="mb-3">
                                                                    <label for="lbl_name" class="form-label" >ឈ្មោះខ្នាត<span style="color: red"> *</span></label>
                                                                    <input type="text" name="txt_um_name" class="form-control" id="txt_um_name" value="' . $row['unit_name'] . '" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="txt_rate" class="form-label" >បរិមាណ<span style="color: red"> *</span></label>
                                                                    <input type="number" name="txt_rate" class="form-control" id="txt_rate' . $row['id'] . '" value="" required>
                                                                </div>
                                                                <button type="submit" name="btnUpdate" class="btn app-btn-primary" >កែប្រែ</button>
                                                            </form>
                                                            <script>
                                                                var textarea = document.getElementById("txt_rate' . $row['id'] . '");
                                                                textarea.value = "' . $row['rate'] . '";
                                                            </script>
                                                        </div><!--//app-card-body--> 
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

                    <!-- Pagination -->
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

                <div class="tab-pane fade" id="create_brand" role="tabpanel" aria-labelledby="create_brand-tab">


                    <?php
                    // insert
                    if (isset($_POST['btnSave'])) {
                        $txt_um_name = $_POST['txt_um_name'];
                        $txt_rate = $_POST['txt_rate'];
                        // validate empty data
                        if (trim($txt_um_name) == '') {
                            msgstyle('សូមបញ្ចូលឈ្មោះខ្នាតផលិតផល', 'danger');
                            return;
                        } else if (trim($txt_rate) < 0) {
                            msgstyle('សូមបញ្ចូលបរិមាណរបស់ខ្នាត', 'danger');
                            return;
                        }

                        $sql = "
                            INSERT INTO tbl_unit_measurement (unit_name, rate) VALUES('$txt_um_name', '$txt_rate');
                        ";
                        if (mysqli_query($conn, $sql)) {
                            // echo"Data inserting successfully";
                            echo msgstyle('Data inserting successfully', 'success');
                            include 'refresh_page.php';
                        } else {
                            echo "Error Inserting $sql" . mysqli_error($conn);
                        }
                        // close connection
                        mysqli_close($conn);
                    }
                    ?>

                    <!-- Delete -->
                    <?php
                    // delete
                    if (isset($_GET['btnDelete'])) {
                        $id = $_GET['txtid'];
                        $sql = mysqli_query($conn, "DELETE FROM tbl_unit_measurement WHERE id=$id");
                        if ($sql) {
                            echo msgstyle('Data Delete sucess!', 'success');
                            include 'refresh_page.php';
                        } else {
                            echo msgstyle('Data Delete unsucess!', 'info');
                        }
                    }

                    ?>
                    <div class="app-card app-card-orders-table mb-5">
                        <div class="app-card-body">
                            <div class="container-xl">
                                <h1 class="app-page-title">បំពេញព័ត៌មានខ្នាត</h1>
                                <!-- <hr class="my-4"> -->
                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <div class="app-card app-card-settings shadow-sm p-4">

                                            <div class="app-card-body">
                                                <form class="settings-form" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">

                                                    <div class="mb-3">
                                                        <label for="lbl_name" class="form-label">ឈ្មោះខ្នាត<span style="color: red"> *</span></label>
                                                        <input type="text" name="txt_um_name" class="form-control" id="txt_um_name" value="" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="txt_rate" class="form-label">បរិមាណ<span style="color: red"> *</span></label>
                                                        <input type="number" name="txt_rate" class="form-control" id="txt_rate" value="" required>
                                                    </div>
                                                    <button id="btnSave" type="submit" name="btnSave" class="btn app-btn-primary">រក្សាទុក</button>
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
                $(document).ready(function() {
                    $("#brand_list-tab").click(function() {
                        // alert('Test click tap');
                        window.location.href = "index.php?um=unit_measurement";
                    });
                });
            </script>