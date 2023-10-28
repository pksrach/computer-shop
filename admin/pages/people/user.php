<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">អ្នកប្រើប្រាស់</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
                                <form class="table-search-form row gx-1 align-items-center">
                                    <input type="hidden" name="pe" value="user" />
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
                <a class="flex-sm-fill text-sm-center nav-link active" id="brand_list-tab" data-bs-toggle="tab" href="#brand_list" role="tab" aria-controls="orders-all" aria-selected="true">អ្នកប្រើ</a>
                <a class="flex-sm-fill text-sm-center nav-link" id="create_brand_list-tab" data-bs-toggle="tab" href="#create_brand" role="tab" aria-controls="orders-paid" aria-selected="false">បង្កើតអ្នកប្រើ</a>
            </nav>

            <?php
            // update
            if (isset($_POST['btnUpdate'])) {
                $id = $_POST['u_id'];
                $username = $_POST['txt_username'];
                $password = $_POST['txt_password'];
                $role = $_POST['txt_role'];

                if (trim($username) != '') {
                    $sql = "
                        UPDATE tbl_user
                        SET username=' $username', password='$password', role='$role'
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
                                            <th class="cell">ឈ្មោះអ្នកប្រើ</th>
                                            <th class="cell">តួនាទី</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // searching data
                                        if (isset($_GET['btnSearch'])) {
                                            $keyinputdata = $_GET['keyinputdata'];
                                            // Pagination when searching
                                            $number_of_page = 0;
                                            $s = "SELECT count(*) FROM tbl_unit_measurement";
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

                                            $sql_select = "SELECT * FROM tbl_user ";

                                            if ($keyinputdata == "") {
                                                $sql = $sql_select . "LIMIT $current_page, $row_per_page;";
                                            } else {
                                                $sql = $sql_select . "
                                                    WHERE
                                                        unit_name LIKE '%" . $keyinputdata . "%'
                                                    ORDER BY
                                                        id DESC LIMIT $current_page, $row_per_page;";
                                            }

                                            $result = mysqli_query($conn, $sql);
                                            $num_row = $result->num_rows;
                                        } else {
                                            // Load all data
                                            // Pagination
                                            $number_of_page = 0;
                                            $s = "SELECT count(*) FROM tbl_unit_measurement";
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
                                            $sql = "SELECT * FROM tbl_user ";
                                            $result = mysqli_query($conn, $sql);
                                            $num_row = $result->num_rows;
                                        }
                                        $result = mysqli_query($conn, $sql);
                                        if ($result->num_rows > 0) {
                                            $i = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                                <form method="get">
                                                    <input type="hidden" name="pe" value="user" id="">
                                                    <input type="hidden" name="txtid" id="" value="<?= $row['id'] ?>">
                                                    <tr>
                                                        <td class="cell"><?= $row['id'] ?></td>
                                                        <td class="cell"><?= $row['username'] ?></td>
                                                        <td class="cell"><?= $row['role'] ?></td>
                                                        <!-- Button action -->
                                                        <td class="cell">
                                                            <!-- <a class="btn btn-info" href="#"><i class="fas fa-eye"></i></a> -->
                                                            <a class="btn btn-primary" href="#" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"><i class="far fa-edit"></i></a>
                                                            <a class="btn btn-info" href="#" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#changePwdModal<?= $row['id'] ?>"><i class="fa-solid fa-key"></i></a>
                                                            <button type="submit" name="btnDelete" class="btn btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបវាមែនទេ ?')"><i class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                </form>

                                        <?php

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
                        $people_id = $_POST['txt_people'];
                        $username = $_POST['txt_username'];
                        $password = md5($_POST['txt_password']);
                        if (isset($_POST['txt_role'])) {
                            $role = $_POST['txt_role'];
                        } else {
                            $role = 'Staff';
                        }

                        // Console log
                        echo "<script>console.log('Create')</script>";
                        echo "<script>console.log('people_id: $people_id')</script>";
                        echo "<script>console.log('username: $username')</script>";
                        echo "<script>console.log('password: $password')</script>";
                        echo "<script>console.log('role: $role')</script>";

                        // validate empty data
                        if (trim($username) == '') {
                            msgstyle('សូមបញ្ចូលឈ្មោះខ្នាតផលិតផល', 'danger');
                            return;
                        } else if (trim($password) < 0) {
                            msgstyle('សូមបញ្ចូលលេខសម្ងាត់', 'danger');
                            return;
                        }

                        $sql = "
                            INSERT INTO tbl_user (username, password, role, people_id) VALUES('$username', '$password', '$role', $people_id);
                        ";
                        if (mysqli_query($conn, $sql)) {
                            // echo"Data inserting successfully";
                            echo msgstyle('Data inserting successfully', 'success');
                            include 'refresh_page_user.php';
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
                        $sql = mysqli_query($conn, "DELETE FROM tbl_user WHERE id=$id");
                        if ($sql) {
                            echo msgstyle('Data Delete sucess!', 'success');
                            include 'refresh_page_user.php';
                        } else {
                            echo msgstyle('Data Delete unsucess!', 'info');
                        }
                    }

                    ?>
                    <div class="app-card app-card-orders-table mb-5">
                        <div class="app-card-body">
                            <div class="container-xl">
                                <h1 class="app-page-title">បំពេញព័ត៌អ្នកប្រើប្រាស់</h1>
                                <!-- <hr class="my-4"> -->
                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <div class="app-card app-card-settings shadow-sm p-4">

                                            <div class="app-card-body">
                                                <form class="settings-form" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                                                    <div class="mb-3">
                                                        <!-- Drop down people -->
                                                        <label for="lbl_name" class="form-label">តួនាទី<span style="color: red"> *</span></label>
                                                        <select class="form-select" name="txt_people" id="txt_people" required>
                                                            <option value="">ជ្រើសរើសបុគ្គលិក</option>
                                                            <?php
                                                            $sql = "SELECT * FROM tbl_people left join tbl_user on tbl_people.id = tbl_user.people_id where tbl_user.people_id is null";
                                                            $result = mysqli_query($conn, $sql);
                                                            if ($result->num_rows > 0) {
                                                                while ($row = mysqli_fetch_array($result)) {
                                                                    $show = $row['id'];
                                                                    echo "<script>console.log('show: $show')</script>";
                                                                    echo '<option value="' . $row['tbl_people.id'] . '">' . $row['name'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <script>
                                                            document.getElementById('txt_people').addEventListener('change', function() {
                                                                var selectedValue = this.value;
                                                                console.log('Selected value: ' + selectedValue);
                                                            });
                                                        </script>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="lbl_name" class="form-label">ឈ្មោះអ្នកប្រើ<span style="color: red"> *</span></label>
                                                        <input type="text" name="txt_username" class="form-control" id="txt_username" value="" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="lbl_name" class="form-label">លេខសម្ងាត់<span style="color: red"> *</span></label>
                                                        <input type="text" name="txt_password" class="form-control" id="txt_password" value="" required>
                                                    </div>

                                                    <div class="d-inline">
                                                        <label for="user_role" class="form-label">តួនាទី<span style="color: red">*</span></label>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="txt_role" id="adminRole" value="Admin">
                                                            <label class="form-check-label" for="adminRole">Admin</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="txt_role" id="staffRole" value="Staff">
                                                            <label class="form-check-label" for="staffRole">Staff</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="txt_role" id="cashierRole" value="Cashier">
                                                            <label class="form-check-label" for="cashierRole">Cashier</label>
                                                        </div>
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
                        window.location.href = "index.php?pe=user";
                    });
                });
            </script>