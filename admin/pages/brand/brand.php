<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <!-- Search -->
            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">ប្រេននៃផលិតផល</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <form class="table-search-form row gx-1 align-items-center">
                                    <div class="col-auto">
                                        <input type="text" id="keyinputdata" name="keyinputdata" class="form-control search-orders" placeholder="ស្វែងរកឈ្មោះ">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" name="btnSearch" class="btn app-btn-secondary">ស្វែងរក</button>
                                    </div>
                                </form>

                            </div><!--//col-->

                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->


            <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                <a class="flex-sm-fill text-sm-center nav-link active" id="brand_list-tab" data-bs-toggle="tab" href="#brand_list" role="tab" aria-controls="orders-all" aria-selected="true">បញ្ជីប្រេន</a>
                <a class="flex-sm-fill text-sm-center nav-link" id="create_brand_list-tab" data-bs-toggle="tab" href="#create_brand" role="tab" aria-controls="orders-paid" aria-selected="false">បង្កើតប្រេនថ្មី</a>

            </nav>

            <?php
            // update
            if (isset($_POST['btnUpdate'])) {
                $id = $_POST['u_id'];
                $txt_brand_name = $_POST['txt_brand_name'];
                $txt_description = $_POST['txt_description'];

                if (trim($txt_brand_name) != '') {
                    $sql = "
                                UPDATE tbl_brand
                                SET brand_name=' $txt_brand_name', description='$txt_description'
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
                                            <th class="cell">ឈ្មោះប្រេន</th>
                                            <th class="cell">បរិយាយ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // searching data
                                        if (isset($_GET['btnSearch'])) {
                                            $keyinputdata = $_GET['keyinputdata'];
                                            // Pagination when searching
                                            $number_of_page = 0;
                                            $s = "SELECT count(*) FROM tbl_brand";
                                            $q = $conn->query($s);
                                            $r = mysqli_fetch_row($q);
                                            $row_per_page = 5;
                                            $number_of_page = ceil($r[0] / $row_per_page); #Round numbers up to the nearest integer
                                            if (!isset($_GET['pn'])) {
                                                $current_page = 0;
                                            } else {
                                                $current_page = $_GET['pn'];
                                                $current_page = ($current_page - 1) * $row_per_page;
                                            }
                                            // End pagination

                                            $sql_select = "SELECT * FROM tbl_brand";

                                            if ($keyinputdata == "") {
                                                $sql = $sql_select . "LIMIT $current_page, $row_per_page;";
                                            } else {
                                                $sql = $sql_select . "
                                                    WHERE
                                                        brand_name LIKE '%" . $keyinputdata . "%'
                                                    ORDER BY
                                                        id DESC LIMIT $current_page, $row_per_page;";
                                            }

                                            $result = mysqli_query($conn, $sql);
                                            $num_row = $result->num_rows;
                                        } else {
                                            // Load all data
                                            // Pagination
                                            $number_of_page = 0;
                                            $s = "SELECT count(*) FROM tbl_brand";
                                            $q = $conn->query($s);
                                            $r = mysqli_fetch_row($q);
                                            $row_per_page = 5;
                                            $number_of_page = ceil($r[0] / $row_per_page); #Round numbers up to the nearest integer
                                            if (!isset($_GET['pn'])) {
                                                $current_page = 0;
                                            } else {
                                                $current_page = $_GET['pn'];
                                                $current_page = ($current_page - 1) * $row_per_page;
                                            }
                                            // End pagination

                                            $sql = "SELECT * FROM tbl_brand ORDER BY id DESC LIMIT $current_page, $row_per_page;";
                                            $result = mysqli_query($conn, $sql);
                                            $num_row = $result->num_rows;
                                        }
                                        if ($result->num_rows > 0) {
                                            $i = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                                <form method="get">
                                                    <input type="hidden" name="br" value="brand" id="">
                                                    <input type="hidden" name="txtid" id="" value="<?= $row['id'] ?>">
                                                    <tr>
                                                        <td class="cell"><?= $row['id'] ?></td>
                                                        <td class="cell"><?= $row['brand_name'] ?></td>
                                                        <td class="cell"><?= $row['description'] ?></td>
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
                                                                    <label for="lbl_name" class="form-label" >ឈ្មោះប្រេន<span style="color: red"> *</span></label>
                                                                    <input type="text" name="txt_brand_name" class="form-control" id="txt_brand_name" value="' . $row['brand_name'] . '" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="txt_description" class="form-label" >បរិយាយ</label>
                                                                    <textarea type="text" name="txt_description" id="txt_description' . $row['id'] . '" class="form-control" style="height: 100px"></textarea>
                                                                </div>
                                                                <button type="submit" name="btnUpdate" class="btn app-btn-primary" >កែប្រែ</button>
                                                            </form>
                                                            <script>
                                                                var textarea = document.getElementById("txt_description' . $row['id'] . '");
                                                                textarea.value = "' . $row['description'] . '";
                                                            </script>
                                                        </div><!--//app-card-body--> 
                                                    </div>
                                                </div>
                                                </div>
                                                </div>
                                                ';
                                                $i++;
                                            }
                                        } else {
                                            echo '
												<tr>
													<td colspan="10" style="text-align: center; color: red; font-size: 18pt;">មិនមានទិន្នន័យទេ</td>
												</tr>
											';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div><!--//table-responsive-->

                        </div><!--//app-card-body-->
                    </div><!--//app-card-->

                    <!-- Start pagination -->
                    <?php
                    require_once 'pages/pagin/pagin.php';
                    ?>
                    <!-- End pagination-->

                </div><!--//tab-pane-->

                <div class="tab-pane fade" id="create_brand" role="tabpanel" aria-labelledby="create_brand-tab">


                    <?php
                    // insert
                    if (isset($_POST['btnSave'])) {
                        $txt_brand_name = $_POST['txt_brand_name'];
                        $txt_description = $_POST['txt_description'];
                        // validate empty data
                        if (trim($txt_brand_name) == '') {
                            msgstyle('សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល', 'danger');
                            return;
                        }

                        $sql = "
                            INSERT INTO tbl_brand (brand_name, description) VALUES('$txt_brand_name', '$txt_description');
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
                        $sql = mysqli_query($conn, "DELETE FROM tbl_brand WHERE id=$id");
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
                                <h1 class="app-page-title">បំពេញព័ត៌មានប្រេន</h1>
                                <!-- <hr class="my-4"> -->
                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <div class="app-card app-card-settings shadow-sm p-4">

                                            <div class="app-card-body">
                                                <form class="settings-form" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">

                                                    <div class="mb-3">
                                                        <label for="lbl_name" class="form-label">ឈ្មោះប្រភេទ<span style="color: red"> *</span></label>
                                                        <input type="text" name="txt_brand_name" class="form-control" id="txt_brand_name" value="" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="txt_description" class="form-label">បរិយាយ</label>
                                                        <textarea name="txt_description" id="txt_description" class="form-control" style="height: 100px"></textarea>
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
                        window.location.href = "index.php?br=brand";
                    });
                });
            </script>