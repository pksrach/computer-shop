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
									<input type="hidden" name="p" value="product" />

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

			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="product_list" role="tabpanel" aria-labelledby="product_list-tab">
					<div class="app-card app-card-orders-table shadow-sm mb-5">
						<div class="app-card-body">
							<div class="table-responsive">
								<table class="table app-table-hover mb-0 text-left">
									<thead>
										<tr>
											<th class="cell">#</th>
											<th class="cell" style="text-align: center;">រូបភាព</th>
											<th class="cell" style="text-align: center;">ឈ្មោះប្រេន</th>
											<th class="cell" style="text-align: center;">ប្រភេទ</th>
											<th class="cell" style="text-align: center;">ឈ្មោះផលិតផល</th>
											<th class="cell" style="text-align: center;">បរិយាយ</th>
											<th class="cell" style="text-align: center;">តម្លៃផលិតផល</th>
											<th class="cell" style="text-align: center;">ខ្នាតផលិតផល</th>
											<th class="cell" style="text-align: center;">បរិមាណខ្នាត</th>
											<th class="cell" style="text-align: center;">ស្ថានភាព</th>
										</tr>
									</thead>
									<tbody>
										<?php
										include_once 'check_status.php';
										$rowNumber = 1;

										// searching data
										if (isset($_GET['btnSearch'])) {
											$keycategory = $_GET['key_category'];
											$keybrand = $_GET['key_brand'];
											$keyinputdata = $_GET['keyinputdata'];

											// Pagination when searching
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM
												  tbl_product p
												  INNER JOIN tbl_brand b ON p.brand_id = b.id
												  INNER JOIN tbl_category c ON p.category_id = c.id
												  INNER JOIN tbl_unit_measurement u ON p.unit_id = u.id
											";
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

											$sql_select = "SELECT
												p.id,
												p.attatchment_url,
												b.brand_name,
												c.category_name,
												p.product_name,
												p.description,
												p.price,
												u.unit_name,
												u.rate,
												p.status
											FROM
												tbl_product p
												INNER JOIN tbl_brand b ON p.brand_id = b.id
												INNER JOIN tbl_category c ON p.category_id = c.id
												INNER JOIN tbl_unit_measurement u ON p.unit_id = u.id
											";
											if ($keycategory == "" && $keybrand == "" && $keyinputdata == "") {
												$sql = $sql_select . "LIMIT $current_page, $row_per_page;";
											}

											if ($keycategory) {
												$sql = $sql_select . "
												WHERE
													p.category_id = '$keycategory'
												ORDER BY
													p.id DESC LIMIT $current_page, $row_per_page;";
											}

											if ($keybrand) {
												$sql = $sql_select . "
												WHERE
													p.brand_id = '$keybrand'
												ORDER BY
													p.id DESC LIMIT $current_page, $row_per_page;";
											}

											if ($keyinputdata) {
												$sql = $sql_select . "
												WHERE
													p.product_name LIKE '%" . $keyinputdata . "%'
												ORDER BY
													p.id DESC LIMIT $current_page, $row_per_page;";
											}

											$result = mysqli_query($conn, $sql);
											$num_row = $result->num_rows;
										} else {
											// Load all data

											// Pagination
											#pagination when first load
											$number_of_page = 0;
											$s = "SELECT count(*) 
												FROM
												  tbl_product p
												  INNER JOIN tbl_brand b ON p.brand_id = b.id
												  INNER JOIN tbl_category c ON p.category_id = c.id
												  INNER JOIN tbl_unit_measurement u ON p.unit_id = u.id
											";
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

											$sql = "SELECT
													p.id,
													p.attatchment_url,
													b.brand_name,
													c.category_name,
													p.product_name,
													p.description,
													p.price,
													u.unit_name,
													u.rate,
													p.status
												FROM
													tbl_product p
													INNER JOIN tbl_brand b ON p.brand_id = b.id
													INNER JOIN tbl_category c ON p.category_id = c.id
													INNER JOIN tbl_unit_measurement u ON p.unit_id = u.id
												ORDER BY
													p.id DESC
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
													<td class="cell" style="text-align: center;"><img src="<?= $row['attatchment_url'] ? "assets/images/img_data_store_upload/" . $row['attatchment_url'] : 'assets/images/default_product.jpg' ?>" width="50px" height="50px"></td>
													<td class="cell" style="text-align: center;"><?= $row['brand_name'] ?></td>
													<td class="cell" style="text-align: center;"><?= $row['category_name'] ?></td>
													<td class="cell" style="text-align: left;"><?= $row['product_name'] ?></td>
													<td class="cell" style="text-align: left;"><?= $row['description'] ?></td>
													<td class="cell" style="text-align: right;">$<?= $row['price'] ?></td>
													<td class="cell" style="text-align: center;"><?= $row['unit_name'] ?></td>
													<td class="cell" style="text-align: center;"><?= $row['rate'] ?></td>
													<td class="cell" style="text-align: center;"><?= productStatus($row['status']) ?></td>

													<td class="cell">
														<a class="btn btn-primary" href="index.php?p=update_product&proid=<?= $row['id'] ?>"><i class="far fa-edit"></i></a>
														<a href="pages/product/del_product.php?id=<?= $row['id'] ?>" type="submit" name="btnDelete" class="btn btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ ?')"><i class="fas fa-eraser"></i></i></a>
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#product_list-tab', '#create_product').click(function() {
			window.location.href = "index.php?p=product";
		})
	})
</script>