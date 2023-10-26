<div class="app-wrapper">

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <h1 class="app-page-title">Overview</h1>

            <div class="row g-4 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="app-card app-card-stat shadow-sm h-100">
                        <div class="app-card-body p-3 p-lg-4">
                            <h4 class="stats-type mb-1">Total Sales</h4>
                            <div class="stats-figure">$
                                <?php

                                include_once("../config_db/config_db.php");

                                $sql = "select SUM(total) as sum from tbl_sales;";
                                $result = $conn->query($sql);

                                if ($result) {
                                    // Fetch the result
                                    $row = $result->fetch_assoc();

                                    // Display the result in an HTML <h1> tag
                                    if ($row) {
                                        echo $row['sum'] ? $row['sum'] : 0;
                                    } else {
                                        echo "<h1>No data found</h1>";
                                    }
                                }

                                ?>
                            </div>

                        </div><!--//app-card-body-->
                        <a class="app-card-link-mask" href="#"></a>
                    </div><!--//app-card-->
                </div><!--//col-->

                <div class="col-6 col-lg-3">
                    <div class="app-card app-card-stat shadow-sm h-100">
                        <div class="app-card-body p-3 p-lg-4">
                            <h4 class="stats-type mb-1">
                                Expenses


                            </h4>
                            <div class="stats-figure">
                                $<?php

                                    include_once("../config_db/config_db.php");

                                    $sql = "select sum(amount) as sum
                                FROM tbl_expense_details;";
                                    $result = $conn->query($sql);

                                    if ($result) {
                                        // Fetch the result
                                        $row = $result->fetch_assoc();

                                        // Display the result in an HTML <h1> tag
                                        if ($row) {
                                            echo $row['sum'] ? $row['sum'] : 0;
                                        } else {
                                            echo "<h1>No data found</h1>";
                                        }
                                    }

                                    ?>
                            </div>
                        </div><!--//app-card-body-->
                        <a class="app-card-link-mask" href="#"></a>
                    </div><!--//app-card-->
                </div><!--//col-->
                <div class="col-6 col-lg-3">
                    <div class="app-card app-card-stat shadow-sm h-100">
                        <div class="app-card-body p-3 p-lg-4">
                            <h4 class="stats-type mb-1">CustomerS</h4>
                            <div class="stats-figure">
                                <?php

                                include_once("../config_db/config_db.php");

                                $sql = "select count(name) as count
                                from tbl_customer;";
                                $result = $conn->query($sql);

                                if ($result) {
                                    // Fetch the result
                                    $row = $result->fetch_assoc();

                                    // Display the result in an HTML <h1> tag
                                    if ($row) {
                                        echo $row['count'];
                                    } else {
                                        echo "<h1>No data found</h1>";
                                    }
                                }

                                ?>
                            </div>
                        </div><!--//app-card-body-->
                        <a class="app-card-link-mask" href="#"></a>
                    </div><!--//app-card-->
                </div><!--//col-->
                <div class="col-6 col-lg-3">
                    <div class="app-card app-card-stat shadow-sm h-100">
                        <div class="app-card-body p-3 p-lg-4">
                            <h4 class="stats-type mb-1">Employees</h4>
                            <div class="stats-figure">
                                <?php

                                include_once("../config_db/config_db.php");

                                $sql = "select count(name) as count
                                from tbl_people;";
                                $result = $conn->query($sql);

                                if ($result) {
                                    // Fetch the result
                                    $row = $result->fetch_assoc();

                                    // Display the result in an HTML <h1> tag
                                    if ($row) {
                                        echo $row['count'];
                                    } else {
                                        echo "<h1>No data found</h1>";
                                    }
                                }

                                ?>
                            </div>
                        </div><!--//app-card-body-->
                        <a class="app-card-link-mask" href="#"></a>
                    </div><!--//app-card-->
                </div><!--//col-->
            </div><!--//row-->
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="app-card app-card-chart h-100 shadow-sm">
                        <div class="app-card-header p-3">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-auto">
                                    <h4 class="app-card-title">Sales Line Chart</h4> <!-- Update the chart title -->
                                </div><!--//col-->
                            </div><!--//row-->
                        </div><!--//app-card-header-->
                        <div class="app-card-body p-3 p-lg-4">
                            <div class="mb-3 d-flex">
                                <select class="form-select form-select-sm ms-auto d-inline-flex w-auto">
                                    <option value="1" selected>This week</option>
                                    <option value="2">Today</option>
                                    <option value="3">This Month</option>
                                    <option value="3">This Year</option>
                                </select>
                            </div>
                            <div class="chart-container">
                                <canvas id="canvas-linechart"></canvas>
                            </div>
                        </div><!--//app-card-body-->
                    </div><!--//app-card-->
                </div><!--//col-->

                <div class="col-12 col-lg-6">
                    <div class="app-card app-card-chart h-100 shadow-sm">
                        <div class="app-card-header p-3">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-auto">
                                    <h4 class="app-card-title">Bar Chart Example</h4>
                                </div><!--//col-->
                                <div class="col-auto">
                                    <div class="card-header-action">
                                        <a href="index.php?pg=charts">More charts</a>
                                    </div><!--//card-header-actions-->
                                </div><!--//col-->
                            </div><!--//row-->
                        </div><!--//app-card-header-->
                        <div class="app-card-body p-3 p-lg-4">
                            <div class="mb-3 d-flex">
                                <select class="form-select form-select-sm ms-auto d-inline-flex w-auto">
                                    <option value="1" selected>This week</option>
                                    <option value="2">Today</option>
                                    <option value="3">This Month</option>
                                    <option value="3">This Year</option>
                                </select>
                            </div>
                            <div class="chart-container">
                                <canvas id="canvas-barchart"></canvas>
                            </div>
                        </div><!--//app-card-body-->
                    </div><!--//app-card-->
                </div><!--//col-->

            </div><!--//row-->
        </div><!--//container-fluid-->
    </div><!--//app-content-->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Fetch sales data from your PHP script or API endpoint
        // Replace 'your_data_fetching_url' with the actual URL or script that fetches data
        fetch('your_data_fetching_url')
            .then(response => response.json())
            .then(data => {
                const salesData = data; // Assuming your data is an array of sales data

                // Create a Line Chart
                const ctx = document.getElementById('canvas-linechart').getContext('2d');
                const salesLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: salesData.map(item => item.date), // Date labels
                        datasets: [{
                            label: 'Sales',
                            data: salesData.map(item => item.amount), // Sales amount data
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1,
                        }, ],
                    },
                });
            });
    </script>