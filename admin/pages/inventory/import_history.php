<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<!-- Search -->
			<div class="row g-3 mb-4 align-items-center justify-content-between">
				<div class="col-auto">
					<h1 class="app-page-title mb-0">របាយការណ៍នាំចូល</h1>
				</div>
				<div class="col-auto">
					<div class="page-utilities">
						<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							<div class="col-auto">
								<form method="get" class="table-search-form row gx-1 align-items-center">
									<input type="hidden" name="imh" value="import_history" />

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
										<input type="text" id="keyinputdata" name="keyinputdata" class="form-control search-orders" placeholder="ស្វែងរកឈ្មោះផលិតផល">
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
											<th class="cell" style="text-align: center;">ថ្ងៃនាំចូល</th>
											<th class="cell" style="text-align: center;">ប្រភេទផលិតផល</th>
											<th class="cell" style="text-align: center;">ប្រេនផលិតផល</th>
											<th class="cell" style="text-align: center;">ឈ្មោះផលិតផល</th>
											<th class="cell" style="text-align: center;">ចំនួននាំចូល</th>
											<th class="cell" style="text-align: center;">ថ្លៃដើម</th>
											<th class="cell" style="text-align: center;">បុគ្គលិក</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$rowNumber = 1;

										// searching data
										if (isset($_GET['btnSearch'])) {
											$keyinputdata = $_GET['keyinputdata'];
											$keystartdate = $_GET['keystartdate'];
											$keyenddate = $_GET['keyenddate'];

											echo "<script>console.log('keyinputdata:$keyinputdata')</script>";
											echo "<script>console.log('keystartdate:$keystartdate')</script>";
											echo "<script>console.log('keyenddate:$keyenddate')</script>";

											// Pagination when searching
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM tbl_import_detail impd 
												INNER JOIN  tbl_import imp ON impd.import_id = imp.id 
												INNER JOIN tbl_people pp ON imp.people_id = pp.id 
												INNER JOIN tbl_product p ON impd.product_id = p.id 
												INNER JOIN tbl_unit_measurement um ON p.unit_id = um.id
												INNER JOIN tbl_category c on p.category_id = c.id
												INNER JOIN tbl_brand b on p.brand_id = b.id
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
												impd.import_id,
												imp.import_date,
												c.category_name,
												b.brand_name,
												p.product_name,
												impd.import_qty,
												um.unit_name,
												impd.cost,
												pp.name as staff

												FROM tbl_import_detail impd 
												INNER JOIN  tbl_import imp ON impd.import_id = imp.id 
												INNER JOIN tbl_people pp ON imp.people_id = pp.id 
												INNER JOIN tbl_product p ON impd.product_id = p.id 
												INNER JOIN tbl_unit_measurement um ON p.unit_id = um.id
												INNER JOIN tbl_category c on p.category_id = c.id
												INNER JOIN tbl_brand b on p.brand_id = b.id
											";
											if ($keystartdate == "" && $keyenddate == "" && $keyinputdata == "") {
												$sql = $sql_select . "ORDER BY impd.id DESC " . "LIMIT $current_page, $row_per_page;";
											}

											if ($keystartdate && $keyenddate) {
												$sql = $sql_select . "
												WHERE
													DATE(imp.import_date) BETWEEN '$keystartdate' AND '$keyenddate'
												ORDER BY
													impd.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keystartdate && $keyenddate && $keyinputdata != "") {
												$sql = $sql_select . "
												WHERE
													DATE(imp.import_date) BETWEEN '$keystartdate' AND '$keyenddate'
													AND p.product_name LIKE '%" . $keyinputdata . "%'
												ORDER BY
													impd.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keystartdate) {
												$sql = $sql_select . "
												WHERE
													DATE(imp.import_date) BETWEEN '$keystartdate' AND DATE(NOW())
												ORDER BY
													impd.id DESC LIMIT $current_page, $row_per_page;";
											} else if ($keyenddate) {
												$sql = $sql_select . "
												WHERE
													DATE(imp.import_date) BETWEEN DATE(NOW()) AND '$keyenddate'
												ORDER BY
													impd.id DESC LIMIT $current_page, $row_per_page;";
											}

											if ($keyinputdata) {
												$sql = $sql_select . "
												WHERE
													p.product_name LIKE '%" . $keyinputdata . "%'
												ORDER BY
													impd.id DESC LIMIT $current_page, $row_per_page;";
											}

											$result = mysqli_query($conn, $sql);
											$num_row = $result->num_rows;
										} else {
											// Load all data

											// Pagination
											#pagination when first load
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM tbl_import_detail impd 
												INNER JOIN  tbl_import imp ON impd.import_id = imp.id 
												INNER JOIN tbl_people pp ON imp.people_id = pp.id 
												INNER JOIN tbl_product p ON impd.product_id = p.id 
												INNER JOIN tbl_unit_measurement um ON p.unit_id = um.id
												INNER JOIN tbl_category c on p.category_id = c.id
												INNER JOIN tbl_brand b on p.brand_id = b.id
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
												impd.import_id,
												imp.import_date,
												c.category_name,
												b.brand_name,
												p.product_name,
												impd.import_qty,
												um.unit_name,
												impd.cost,
												pp.name as staff

												FROM tbl_import_detail impd 
												INNER JOIN tbl_import imp ON impd.import_id = imp.id 
												INNER JOIN tbl_people pp ON imp.people_id = pp.id 
												INNER JOIN tbl_product p ON impd.product_id = p.id 
												INNER JOIN tbl_unit_measurement um ON p.unit_id = um.id
												INNER JOIN tbl_category c on p.category_id = c.id
												INNER JOIN tbl_brand b on p.brand_id = b.id
												ORDER BY
													impd.id DESC
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
													<td class="cell" style="text-align: center;"><?= date('Y-m-d', strtotime($row['import_date'])) ?></td>
													<td class="cell" style="text-align: center;"><?= $row['category_name'] ? $row['category_name'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['brand_name'] ? $row['brand_name'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['product_name'] ?></td>
													<td class="cell" style="text-align: center;"><?= $row['import_qty'] ?> <?= $row['unit_name'] ? $row['unit_name'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['cost'] ? $row['cost'] : "0" ?></td>
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