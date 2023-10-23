<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<!-- Search -->
			<div class="row g-3 mb-4 align-items-center justify-content-between">
				<div class="col-auto">
					<h1 class="app-page-title mb-0">របាយការណ៍ចំណាយ</h1>
				</div>
				<div class="col-auto">
					<div class="page-utilities">
						<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							<div class="col-auto">
								<form method="get" class="table-search-form row gx-1 align-items-center">
									<input type="hidden" name="exp" value="expense" />

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

			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="product_list" role="tabpanel" aria-labelledby="product_list-tab">
					<div class="app-card app-card-orders-table shadow-sm mb-5">
						<div class="app-card-body">
							<div class="table-responsive">
								<table class="table app-table-hover mb-0 text-left">
									<thead>
										<tr>
											<th class="cell">#</th>
											<th class="cell" style="text-align: center;">កាលបរិច្ឆេទ</th>
											<th class="cell" style="text-align: center;">ប្រភេទចំណាយ</th>
											<th class="cell" style="text-align: center;">ថ្លៃចំណាយ</th>
											<th class="cell" style="text-align: center;">បរិយាយ</th>
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
												FROM tbl_expense_details expd
												INNER JOIN tbl_expense exp ON expd.exp_id = exp.id
												INNER JOIN tbl_expense_type expt ON expd.exp_type_id = expt.id
												INNER JOIN tbl_people pp ON exp.people_id = pp.id
											";
											$q = $conn->query($s);
											$r = mysqli_fetch_row($q);
											$row_per_page = 10;
											$number_of_page = ceil($r[0] / $row_per_page); #Round numbers up to the nearest integer
											if (!isset($_GET['pn'])) {
												$current_page = 0;
											} else {
												$current_page = $_GET['pn'];
												$current_page = ($current_page - 1) * $row_per_page;
											}
											// End pagination

											$sql_select = "SELECT 
												expd.exp_id, 
												exp.exp_date,
												expt.`name` AS exp_type,
												expd.amount,
												expd.note,
												pp.`name` AS staff
												FROM tbl_expense_details expd
												INNER JOIN tbl_expense exp ON expd.exp_id = exp.id
												INNER JOIN tbl_expense_type expt ON expd.exp_type_id = expt.id
												INNER JOIN tbl_people pp ON exp.people_id = pp.id
											";
											if ($keystartdate == "" && $keyenddate == "") {
												$sql = $sql_select . "ORDER BY expd.id DESC " . "LIMIT $current_page, $row_per_page;";
											}

											if ($keystartdate && $keyenddate) {
												echo "<script>console.log('Jol start and end')</script>";
												$sql = $sql_select . "
												WHERE
													DATE(exp.exp_date) BETWEEN '$keystartdate' AND '$keyenddate'
												ORDER BY
													expd.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keystartdate) {
												echo "<script>console.log('Jol start')</script>";
												$sql = $sql_select . "
												WHERE
													DATE(exp.exp_date) BETWEEN '$keystartdate' AND DATE(NOW())
												ORDER BY
													expd.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keyenddate) {
												echo "<script>console.log('Jol end')</script>";
												$sql = $sql_select . "
												WHERE
													DATE(exp.exp_date) BETWEEN DATE(NOW()) AND '$keyenddate'
												ORDER BY
													expd.id DESC LIMIT $current_page, $row_per_page;";
											}

											$result = mysqli_query($conn, $sql);
											$num_row = $result->num_rows;
										} else {
											// Load all data

											// Pagination
											#pagination when first load
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM tbl_expense_details expd
												INNER JOIN tbl_expense exp ON expd.exp_id = exp.id
												INNER JOIN tbl_expense_type expt ON expd.exp_type_id = expt.id
												INNER JOIN tbl_people pp ON exp.people_id = pp.id
											";
											$q = $conn->query($s);
											$r = mysqli_fetch_row($q);
											$row_per_page = 10;
											$number_of_page = ceil($r[0] / $row_per_page); #Round numbers up to the nearest integer
											if (!isset($_GET['pn'])) {
												$current_page = 0;
											} else {
												$current_page = $_GET['pn'];
												$current_page = ($current_page - 1) * $row_per_page;
											}
											// End pagination

											$sql = "SELECT 
												expd.exp_id, 
												exp.exp_date,
												expt.`name`AS exp_type,
												expd.amount,
												expd.note,
												pp.`name` AS staff
												FROM tbl_expense_details expd
												INNER JOIN tbl_expense exp ON expd.exp_id = exp.id
												INNER JOIN tbl_expense_type expt ON expd.exp_type_id = expt.id
												INNER JOIN tbl_people pp ON exp.people_id = pp.id
												ORDER BY
													expd.id DESC
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
													<td class="cell" style="text-align: center;"><?= date('Y-m-d', strtotime($row['exp_date'])) ?></td>
													<td class="cell" style="text-align: center;"><?= $row['exp_type'] ? $row['exp_type'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;">$<?= $row['amount'] ? $row['amount'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['note'] ?></td>
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