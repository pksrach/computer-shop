<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<!-- Search -->
			<div class="row g-3 mb-4 align-items-center justify-content-between">
				<div class="col-auto">
					<h1 class="app-page-title mb-0">របាយការណ៍ចំណូល</h1>
				</div>
				<div class="col-auto">
					<div class="page-utilities">
						<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							<div class="col-auto">
								<form method="get" class="table-search-form row gx-1 align-items-center">
									<input type="hidden" name="rep" value="income" />

									<!-- Start date -->
									<label class="col-auto">ចាប់ពីថ្ងៃ</label>
									<div class="col-auto">
										<input type="date" id="keystartdate" name="keystartdate" class="form-control search-orders">
									</div>
									<!-- End date -->
									<label class="col-auto">ដល់ថ្ងៃ</label>
									<div class="col-auto">
										<input type="date" id="keyenddate" name="keyenddate" class="form-control search-orders">
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

			<!-- Tab -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link active" id="product_list-tab" data-bs-toggle="tab" href="#product_list" role="tab" aria-controls="orders-all" aria-selected="true">បញ្ជីផលិតផល</a>
			</nav>

			<!-- Msg response -->
			<?php
			if (isset($_GET['msg'])) {
				#delete= 200, #updated=200, created =200
				if ($_GET['msg'] == 202) {
					echo msgstyle($DELETE, "danger");
				} elseif ($_GET['msg'] == 200) {
					echo msgstyle($UPDATE, "success");
				} elseif ($_GET['msg'] == 201) {
					echo msgstyle($CREATE, "success");
				} else {
					echo msgstyle($ERROR, "danger");
				}
			}
			?>

			<button type="button" class="btn btn-info" id="generate-pdf-button">Print <i class="fa-solid fa-print"></i></button>
			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="product_list" role="tabpanel" aria-labelledby="product_list-tab">
					<div class="app-card app-card-orders-table shadow-sm mb-5">
						<div class="app-card-body">
							<div class="table-responsive">
								<table class="table app-table-hover mb-0 text-left" id="income-table">
									<thead>
										<tr>
											<th class="cell">#</th>
											<th class="cell" style="text-align: center;">កាលបរិច្ឆេទ</th>
											<th class="cell" style="text-align: center;">អតិថិជន</th>
											<th class="cell" style="text-align: center;">សរុប</th>
											<th class="cell" style="text-align: center;">បញ្ចុះតម្លៃ</th>
											<th class="cell" style="text-align: center;">ប្រាក់ទទួល</th>
											<th class="cell" style="text-align: center;">ប្រាក់សរុប</th>
											<th class="cell" style="text-align: center;">វិធីទូទាត់</th>
											<th class="cell" style="text-align: center;">បុគ្គលិក</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$rowNumber = 1;

										// searching data
										if (isset($_GET['btnSearch'])) {
											$keystartdate = $_GET['keystartdate'];
											$keyenddate = $_GET['keyenddate'];

											echo "<script>console.log('keystartdate:$keystartdate')</script>";
											echo "<script>console.log('keyenddate:$keyenddate')</script>";

											// Pagination when searching
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM tbl_sales s
												INNER JOIN tbl_customer c ON s.customer_id = c.id
												INNER JOIN tbl_people p ON s.people_id = p.id
											";
											$q = $conn->query($s);
											$r = mysqli_fetch_row($q);
											$row_per_page = 100;
											$number_of_page = ceil($r[0] / $row_per_page); #Round numbers up to the nearest integer
											if (!isset($_GET['pn'])) {
												$current_page = 0;
											} else {
												$current_page = $_GET['pn'];
												$current_page = ($current_page - 1) * $row_per_page;
											}
											// End pagination

											$sql_select = "SELECT 
												s.id,
												s.sale_date,
												c.`name` AS customer,
												s.total,
												s.discount,
												s.received,
												ROUND((s.total - ((s.discount/100) * s.total)),2) AS grand_total,
												s.payment_type,
												p.`name` AS staff
												FROM tbl_sales s
												INNER JOIN tbl_customer c ON s.customer_id = c.id
												INNER JOIN tbl_people p ON s.people_id = p.id
											";
											if ($keystartdate == "" && $keyenddate == "") {
												$sql = $sql_select . "ORDER BY s.id DESC " . "LIMIT $current_page, $row_per_page;";
											}

											if ($keystartdate && $keyenddate) {
												echo "<script>console.log('Jol start and end')</script>";
												$sql = $sql_select . "
												WHERE
													DATE(s.sale_date) BETWEEN '$keystartdate' AND '$keyenddate'
												ORDER BY
													s.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keystartdate) {
												echo "<script>console.log('Jol start')</script>";
												$sql = $sql_select . "
												WHERE
													DATE(s.sale_date) BETWEEN '$keystartdate' AND DATE(NOW())
												ORDER BY
													s.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keyenddate) {
												echo "<script>console.log('Jol end')</script>";
												$sql = $sql_select . "
												WHERE
													DATE(s.sale_date) BETWEEN DATE(NOW()) AND '$keyenddate'
												ORDER BY
													s.id DESC LIMIT $current_page, $row_per_page;";
											}

											$result = mysqli_query($conn, $sql);
											$num_row = $result->num_rows;
										} else {
											// Load all data

											// Pagination
											#pagination when first load
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM tbl_sales s
												INNER JOIN tbl_customer c ON s.customer_id = c.id
												INNER JOIN tbl_people p ON s.people_id = p.id
											";
											$q = $conn->query($s);
											$r = mysqli_fetch_row($q);
											$row_per_page = 100;
											$number_of_page = ceil($r[0] / $row_per_page); #Round numbers up to the nearest integer
											if (!isset($_GET['pn'])) {
												$current_page = 0;
											} else {
												$current_page = $_GET['pn'];
												$current_page = ($current_page - 1) * $row_per_page;
											}
											// End pagination

											$sql = "SELECT 
												s.id,
												s.sale_date,
												c.`name` AS customer,
												s.total,
												s.discount,
												s.received,
												ROUND((s.total - ((s.discount/100) * s.total)),2) AS grand_total,
												s.payment_type,
												p.`name` AS staff
												FROM tbl_sales s
												INNER JOIN tbl_customer c ON s.customer_id = c.id
												INNER JOIN tbl_people p ON s.people_id = p.id
												ORDER BY
													s.id DESC
												LIMIT $current_page, $row_per_page;
											";
											$result = mysqli_query($conn, $sql);
											$num_row = $result->num_rows;
										}

										if ($result->num_rows > 0) {
											$i = 1;
											while ($row = mysqli_fetch_array($result)) {
										?>
												<tr>
													<td class="cell"><?= $rowNumber++ ?></td>
													<td class="cell" style="text-align: center;"><?= date('Y-m-d', strtotime($row['sale_date'])) ?></td>
													<td class="cell" style="text-align: center;"><?= $row['customer'] ? $row['customer'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;">$<?= $row['total'] ? $row['total'] : "0" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['discount'] ? $row['discount'] : "0" ?>%</td>
													<td class="cell" style="text-align: center;">$<?= $row['received'] ? $row['received'] : "0" ?></td>
													<td class="cell" style="text-align: center;">$<?= $row['grand_total'] ? $row['grand_total'] : "0" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['payment_type'] ? $row['payment_type'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['staff'] ? $row['staff'] : "N/A" ?></td>
												</tr>
										<?php
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
					<?php
					?>


					<!-- Start pagination -->
					<?php
					require_once 'pages/pagin/pagin.php';
					?>
					<!-- End pagination-->

				</div><!--//tab-pane-->
			</div><!--//tab-content-->

		</div><!--//container-fluid-->
	</div><!--//app-content-->

</div><!--//app-wrapper-->

<script>
	document.getElementById('generate-pdf-button').addEventListener('click', function() {
		var tableToPrint = document.getElementById('income-table');
		var printWindow = window.open('', '', 'width=1000, height=700');
		printWindow.document.open();
		printWindow.document.write('<html><head><title>របាយការណ៍ ចំណូល</title>');
		printWindow.document.write('<style>');
		printWindow.document.write('@page { size: A4; margin: 1cm; }');
		printWindow.document.write('body { font-size: 11px; font-family: khmer; }'); // Adjust font size as needed
		printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
		printWindow.document.write('table, th, td { border: 1px solid #000; }');
		printWindow.document.write('th, td { padding: 6px; }'); // Adjust padding as needed
		printWindow.document.write('</style>');
		printWindow.document.write('</head><body>');
		printWindow.document.write('<h1>របាយការណ៍ ចំណូល</h1>');
		printWindow.document.write(tableToPrint.outerHTML);
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.print();
		printWindow.close();
	});

	var tableToPrint = document.getElementById('income-table');
	var incomeData = <?php echo json_encode($result); ?>;
</script>