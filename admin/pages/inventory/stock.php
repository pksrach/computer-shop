<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<!-- Search -->
			<div class="row g-3 mb-4 align-items-center justify-content-between">
				<div class="col-auto">
					<h1 class="app-page-title mb-0">ព័ត៌មាន ផលិតផល</h1>
				</div>
				<div class="col-auto">
					<div class="page-utilities">
						<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							<div class="col-auto">
								<form method="get" class="table-search-form row gx-1 align-items-center">
									<input type="hidden" name="st" value="stock" />

									<div class="col-auto">
										<select class="form-select w-auto" name="key_brand" id="sel_brand">
											<option value="">ជ្រើសរើសប្រេន</option>
											<?php
											$sql = mysqli_query($conn, "SELECT * FROM tbl_brand");
											while ($row = mysqli_fetch_assoc($sql)) {
												echo "<option value='" . $row['id'] . "'>" . $row['brand_name'] . "</option>";
											}
											?>
										</select>
									</div>

									<div class="col-auto">
										<select class="form-select w-auto" name='key_category' id='sel_category'>
											<option value="">ជ្រើសរើសប្រភេទ</option>
											<?php
											$sql = mysqli_query($conn, "SELECT * FROM tbl_category");
											while ($row = mysqli_fetch_assoc($sql)) {
												echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
											}
											?>
										</select>
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

			<button type="button" class="btn btn-info" id="generate-pdf-button">Print <i class="fa-solid fa-print"></i></button>
			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="product_list" role="tabpanel" aria-labelledby="product_list-tab">
					<div class="app-card app-card-orders-table shadow-sm mb-5">
						<div class="app-card-body">
							<div class="table-responsive">
								<table class="table app-table-hover mb-0 text-left" id="stock-table">
									<thead>
										<tr>
											<th class="cell">#</th>
											<th class="cell" style="text-align: center;">ប្រភេទផលិតផល</th>
											<th class="cell" style="text-align: center;">ប្រេនផលិតផល</th>
											<th class="cell" style="text-align: center;">ឈ្មោះផលិតផល</th>
											<th class="cell" style="text-align: center;">ចំនួនស្តុក</th>
											<th class="cell" style="text-align: center;">ថ្លៃដើម</th>
											<th class="cell" style="text-align: center;">ការធានា</th>
											<th class="cell" style="text-align: center;">លេខស៊េរី</th>
											<th class="cell" style="text-align: center;">ការប្រើប្រាស់</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$rowNumber = 1;

										// searching data
										if (isset($_GET['btnSearch'])) {
											$keycategory = $_GET['key_category'];
											$keybrand = $_GET['key_brand'];
											$keyinputdata = $_GET['keyinputdata'];

											// Pagination when searching
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM tbl_stock s
												INNER JOIN tbl_product p ON s.product_id = p.id
												LEFT JOIN tbl_unit_measurement um ON p.unit_id = um.id
												LEFT JOIN tbl_category c on p.category_id = c.id
												LEFT JOIN tbl_brand b on p.brand_id = b.id
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
												c.category_name,
												b.brand_name,
												p.product_name,
												um.unit_name,
												s.stock_qty,
												s.warranty,
												s.serial_number, 
												s.condition_type,
												s.cost
												FROM tbl_stock s
												INNER JOIN tbl_product p ON s.product_id = p.id
												LEFT JOIN tbl_unit_measurement um ON p.unit_id = um.id
												LEFT JOIN tbl_category c on p.category_id = c.id
												LEFT JOIN tbl_brand b on p.brand_id = b.id
											";
											if ($keycategory == "" && $keybrand == "" && $keyinputdata == "") {
												$sql = $sql_select . "WHERE p.`status` = 'Active' ORDER BY s.id DESC " . "LIMIT $current_page, $row_per_page;";
											}

											if ($keycategory) {
												$sql = $sql_select . "
												WHERE
													p.category_id = '$keycategory'
													AND p.`status` = 'Active' 
												ORDER BY
													S.id DESC LIMIT $current_page, $row_per_page;";
											}

											if ($keybrand) {
												$sql = $sql_select . "
												WHERE
													p.brand_id = '$keybrand'
													AND p.`status` = 'Active'
												ORDER BY
													S.id DESC LIMIT $current_page, $row_per_page;";
											}

											if ($keyinputdata) {
												$sql = $sql_select . "
												WHERE
													p.product_name LIKE '%" . $keyinputdata . "%'
													AND p.`status` = 'Active'
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
												FROM tbl_stock s
												INNER JOIN tbl_product p ON s.product_id = p.id
												LEFT JOIN tbl_unit_measurement um ON p.unit_id = um.id
												LEFT JOIN tbl_category c on p.category_id = c.id
												LEFT JOIN tbl_brand b on p.brand_id = b.id
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
												c.category_name,
												b.brand_name,
												p.product_name,
												um.unit_name,
												s.stock_qty,
												s.warranty,
												s.serial_number, 
												s.condition_type,
												s.cost
												FROM tbl_stock s
												INNER JOIN tbl_product p ON s.product_id = p.id
												LEFT JOIN tbl_unit_measurement um ON p.unit_id = um.id
												LEFT JOIN tbl_category c on p.category_id = c.id
												LEFT JOIN tbl_brand b on p.brand_id = b.id
												WHERE p.`status` = 'Active'
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
													<td class="cell" style="text-align: center;"><?= $row['category_name'] ? $row['category_name'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['brand_name'] ? $row['brand_name'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['product_name'] ?></td>
													<td class="cell" style="text-align: center;"><?= $row['stock_qty'] ?> <?= $row['unit_name'] ? $row['unit_name'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['cost'] ? $row['cost'] : "0" ?></td>
													<td class="cell" style="text-align: center;"><?php if ($row['warranty'] && $row['warranty'] !== '0000-00-00 00:00:00') echo  date('Y-m-d', strtotime($row['warranty']));
																									else echo "N/A"  ?></td>
													<td class="cell" style="text-align: center;"><?= $row['serial_number'] ? $row['serial_number'] : "N/A" ?></td>
													<td class="cell" style="text-align: center;"><?= $row['condition_type'] ? $row['condition_type'] : "N/A" ?></td>

													<td class="cell">
														<a href="pages/inventory/del_stock.php?id=<?= $row['id'] ?>" type="submit" name="btnDelete" class="btn btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ ?')"><i class="fas fa-eraser"></i></i></a>
													</td>
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

<script>
	document.getElementById('generate-pdf-button').addEventListener('click', function() {
		var tableToPrint = document.getElementById('stock-table');
		var printWindow = window.open('', '', 'width=1000, height=700');
		printWindow.document.open();
		printWindow.document.write('<html><head><title>របាយការណ៍ ស្តុក</title>');
		printWindow.document.write('<style>');
		printWindow.document.write('@page { size: A4; margin: 1cm; }');
		printWindow.document.write('body { font-size: 11px; font-family: khmer; }'); // Adjust font size as needed
		printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
		printWindow.document.write('table, th, td { border: 1px solid #000; }');
		printWindow.document.write('th, td { padding: 6px; }'); // Adjust padding as needed
		printWindow.document.write('</style>');
		printWindow.document.write('</head><body>');
		printWindow.document.write('<h1>របាយការណ៍ ស្តុក</h1>');
		printWindow.document.write(tableToPrint.outerHTML);
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.print();
		printWindow.close();
	});

	var tableToPrint = document.getElementById('stock-table');
	var incomeData = <?php echo json_encode($result); ?>;
</script>