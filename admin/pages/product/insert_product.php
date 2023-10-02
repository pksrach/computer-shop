<style>
	.validation_msg {
		color: red;
		font-size: 14px;
	}
</style>
<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<h1 class="app-page-title">បង្កើត ព័ត៌មាននៃផលិតផលថ្មី</h1>
			<hr class="mb-4">

			<!-- Tap Click -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link" id="create_product-tab" data-bs-toggle="tab" href="#create_product" role="tab" aria-controls="create_product" aria-selected="false">បង្កើតផលិតផលថ្មី</a>
			</nav>

			<!-- Insert -->
			<?php
			// Message
			$property_nm_msg = '<div class="validation_msg">សូមបញ្ចូលឈ្មោះផលិតផល</div>';
			$property_price_msg = '<div class="validation_msg">សូមបញ្ចូលតម្លៃោះផលិតផល</div>';
			$property_img_msg = '<div class="validation_msg">សូមជ្រើសរើសរូបភាពោះផលិតផល</div>';
			$msg1 = $msg2 = $msg3 = '';

			// Default fields values
			$product_name = "";
			$description = "";
			$price = "";
			$brand_id = "";
			$category_id = "";
			$unit_id = "";
			$attatchment_url = "";
			$status = "Active";


			if (isset($_POST['btnSave'])) {
				// Fields
				$product_name = $_POST['txt_product_name'];
				$description = $_POST['tar_desc'];
				$price = $_POST['txt_product_price'];
				$category_id = $_POST['sel_category'];
				$brand_id = $_POST['sel_brand'];
				$unit_id = $_POST['sel_um'];
				// $status = $_POST['sel_status'];

				// Files
				$filename = $_FILES['img_property']['name'];
				$file_size = $_FILES['img_property']['size'];
				$filetmp = $_FILES['img_property']['tmp_name'];
				$filetype = $_FILES['img_property']['type'];

				$filename_bstr = explode(".", $filename);
				$file_ext = strtolower(end($filename_bstr));
				$extensions = array("jpeg", "jpg", "png");

				if (trim($product_name) == '') {
					$msg1 = $property_nm_msg;
				}
				if (trim($price) == '') {
					$msg2 = $property_price_msg;
				}
				if ($filename == '') {
					$msg3 = $property_img_msg;
				} else {
					// 2MB = 2097152
					if ($file_size > 2097152) {
						echo msgstyle("ទំហំ File ត្រូវតែតូចជាង 2MB", "info");
					} else {
						if (in_array($file_ext, $extensions) === false) {
							echo msgstyle("extension not allowed, please choose a JPEG or PNG file.", "info");
						} else {
							$path_to_store_img = "assets/images/img_data_store_upload/" . $filename;
							move_uploaded_file($filetmp, $path_to_store_img);
							if (
								trim($brand_id) != '' &&
								trim($category_id) != '' &&
								trim($unit_id) != '' &&
								trim($product_name) != '' &&
								trim($price) != '' &&
								trim($status) != ''
							) {
								// Query insert
								$sql = '
									INSERT INTO tbl_product (
										brand_id,
										category_id,
										product_name,
										price,
										unit_id,
										attatchment_url,
										description
									)
									VALUES(?,?,?,?,?,?,?,?)
								';

								$stmt = $conn->prepare($sql);
								$stmt->bind_param("ississ", $brand_id, $category_id, $product_name,  $price, $unit_id, $filename, $description);
								if ($stmt->execute()) {
									echo msgstyle("បង្កើតព័ត៌មានផលិតផលថ្មីបានជោគជ័យ", "success");
								} else {
									echo msgstyle("បង្កើតព័ត៌មានផលិតផលថ្មីមិនបានជោគជ័យ", "danger");
								}
							}
						}
					}
				}

				// echo '<script type="text/javascript"> 
				// 		window.location.replace("index.php?p=product&msg=200");
				// 	 </script>
				// ';
			}
			?>
			<!-- End of Insert -->

			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="create_product" role="tabpanel" aria-labelledby="create_product-tab">


					<div class="app-card app-card-orders-table mb-5">
						<div class="app-card-body">


							<!-- Form create property -->
							<div class="app-content pt-3 p-md-3 p-lg-4">
								<div class="container-xl">
									<div class="row g-4 settings-section">
										<div class="col-12 col-md-12">
											<div class="app-card app-card-settings shadow-sm p-4">
												<div class="app-card-body">

													<form method="POST" enctype="multipart/form-data" class="settings-form" ?>
														<!-- Select Brand -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសប្រេនផលិតផល<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_brand' id='sel_brand' required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_brand");
																while ($row = mysqli_fetch_assoc($sql)) {
																	echo "<option value='" . $row['id'] . "'>" . $row['brand_name'] . "</option>";
																}
																?>
															</select>
														</div>

														<!-- Select Category -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសប្រភេទផលិតផល<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_category' id='sel_category' required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_category");
																while ($row = mysqli_fetch_assoc($sql)) {
																	echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">ឈ្មោះផលិតផល<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_product_name" id="txt_product_name" value="<?php echo $product_name; ?>">
														</div>

														<div class="mb-3">
															<label class="form-label">តម្លៃផលិតផល<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_product_price" id="txt_product_price" value="<?php echo $price; ?>">
														</div>

														<!-- Select Um -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសខ្នាតផលិតផល<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_um' id='sel_um' required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_unit_measurement");
																while ($row = mysqli_fetch_assoc($sql)) {
																	echo "<option value='" . $row['id'] . "'>" . $row['unit_name'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">រូបភាពផលិតផល</label>
															<input type="file" class="form-control" name="img_property" id="img_property" value="">
														</div>

														<!-- <div class="mb-3">
															<label class="form-label">ជ្រើសរើសស្ថានភាពអចលនទ្រព្យ<span style="color: red;">*</span></label>
															<select class="form-select " name="sel_status" id="sel_status" required>
																<option value="">---សូមជ្រើសរើស---</option>
																<option selected value="Active">Active</option>
																<option value="Deactive">Deactive</option>
															</select>
														</div> -->

														<div class="mb-3">
															<label class="form-label">បរិយាយ</label>
															<textarea class="form-control" rows="3" name="tar_desc" id="tar_desc" style="height: 100px;"></textarea>
														</div>

														<button type="submit" name="btnSave" class="btn app-btn-primary">រក្សាទុក</button>
													</form>

												</div><!--//app-card-body-->
											</div><!--//app-card-->
										</div>
									</div>
								</div>
							</div>
							<!-- End of Form for create property -->


						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//tab-pane-->

				<div class="tab-pane fade" id="orders-pending" role="tabpanel" aria-labelledby="orders-pending-tab">
					<div class="app-card app-card-orders-table mb-5">
						<div class="app-card-body">


							<div class="table-responsive">
								<table class="table mb-0 text-left">
									<thead>
										<tr>
											<th class="cell">Order</th>
											<th class="cell">Product</th>
											<th class="cell">Customer</th>
											<th class="cell">Date</th>
											<th class="cell">Status</th>
											<th class="cell">Total</th>
											<th class="cell"></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="cell">#15345</td>
											<td class="cell"><span class="truncate">Consectetur adipiscing elit</span></td>
											<td class="cell">Dylan Ambrose</td>
											<td class="cell"><span class="cell-data">16 Oct</span><span class="note">03:16 AM</span></td>
											<td class="cell"><span class="badge bg-warning">Pending</span></td>
											<td class="cell">$96.20</td>
											<td class="cell"><a class="btn-sm app-btn-secondary" href="#">View</a></td>
										</tr>
									</tbody>
								</table>
							</div><!--//table-responsive-->
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//tab-pane-->
				<div class="tab-pane fade" id="orders-cancelled" role="tabpanel" aria-labelledby="orders-cancelled-tab">
					<div class="app-card app-card-orders-table mb-5">
						<div class="app-card-body">
							<div class="table-responsive">
								<table class="table mb-0 text-left">
									<thead>
										<tr>
											<th class="cell">Order</th>
											<th class="cell">Product</th>
											<th class="cell">Customer</th>
											<th class="cell">Date</th>
											<th class="cell">Status</th>
											<th class="cell">Total</th>
											<th class="cell"></th>
										</tr>
									</thead>
									<tbody>

										<tr>
											<td class="cell">#15342</td>
											<td class="cell"><span class="truncate">Justo feugiat neque</span></td>
											<td class="cell">Reina Brooks</td>
											<td class="cell"><span class="cell-data">12 Oct</span><span class="note">04:23 PM</span></td>
											<td class="cell"><span class="badge bg-danger">Cancelled</span></td>
											<td class="cell">$59.00</td>
											<td class="cell"><a class="btn-sm app-btn-secondary" href="#">View</a></td>
										</tr>

									</tbody>
								</table>
							</div><!--//table-responsive-->
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//tab-pane-->
			</div><!--//tab-content-->

		</div><!--//container-fluid-->
	</div><!--//app-content-->

</div><!--//app-wrapper-->



<!-- Script -->
<!-- <script type="text/javascript">
	$(document).ready(function() {
		$("#btnSave").click(function() {
			//alert('testing');
			window.location.href = "index.php?p=property";
		});
	});
</script> -->