<style>
	.validation_msg {
		color: red;
		font-size: 14px;
	}
</style>

<?php
// Get data from database by param proid
$id = $_GET['proid'];
$sql = "SELECT p.* FROM tbl_product p WHERE p.id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Assing data in db to set on variable
$product_name = $row['product_name'];
$description = $row['description'];
$price = $row['price'];
$category_id = $row['category_id'];
$brand_id = $row['brand_id'];
$unit_id = $row['unit_id'];
$attatchment_url = $row['attatchment_url'];
$status = $row['status'];

?>

<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<h1 class="app-page-title">កែប្រែ ព័ត៌មាននៃផលិតផល</h1>
			<hr class="mb-4">

			<!-- Tap Click -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link" id="update_product-tab" data-bs-toggle="tab" href="#update_product" role="tab" aria-controls="update_product" aria-selected="false">កែប្រែផលិតផល</a>
			</nav>
			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="update_product" role="tabpanel" aria-labelledby="update_product-tab">
					<?php
					if (isset($_POST['btnUpdate'])) {
						// Assing value from form to update data and save to db
						$product_name = $_POST['txt_product_name'];
						$description = $_POST['txt_description'];
						$price = $_POST['txt_price'];
						$category_id = $_POST['sel_category'];
						$brand_id = $_POST['sel_brand'];
						$unit_id = $_POST['sel_um'];
						$status = $_POST['sel_status'];

						// Files
						$filename = $_FILES['p_attatchment_url']['name'];
						$file_size = $_FILES['p_attatchment_url']['size'];
						$filetmp = $_FILES['p_attatchment_url']['tmp_name'];
						$filetype = $_FILES['p_attatchment_url']['type'];

						$filename_bstr = explode(".", $filename);
						$file_ext = strtolower(end($filename_bstr));
						$extensions = array("jpeg", "jpg", "png");

						//2mb
						$maxsize = 2 * 1024 * 1024;
						if ($file_size > $maxsize) {
							echo msgstyle("File size must be less than 2MB", "info");
						} else {
							// Check extension of file
							if (in_array($file_ext, $extensions) === false && ($filename != '' || $filename != null)) {
								echo msgstyle("Extension not allowed, please choose a JPEG, JPG or PNG file.", "info");
								return;
							} else {
								if (
									trim($product_name) != '' &&
									(trim($price) != '' || trim($price) != null) &&
									trim($category_id) != '' &&
									trim($brand_id) != '' &&
									trim($unit_id) != '' &&
									trim($status) != ''
								) {
									$newGenFileName = md5(time() . $filename) . '.' . $file_ext;
									$path_new_img_store_local = "assets/images/img_data_store_upload/" . $newGenFileName;
									$path_img_in_db = "assets/images/img_data_store_upload/" . $attatchment_url;

									// ករណី filename mean nv db hz 
									if ($filename != null || $filename != "") {
										if (file_exists($path_img_in_db) && ($attatchment_url != "" || $attatchment_url != null)) {
											// Delete old file
											unlink($path_img_in_db);
										}
										// Store file to local path
										move_uploaded_file($filetmp, $path_new_img_store_local);
									} else if ($attatchment_url == '' || $attatchment_url == null) {
										$newGenFileName = null;
									} else {
										$newGenFileName = $attatchment_url; // ករណីមិនមានរូបភាពថ្មី
									}

									// query to update
									if ($filename != "" || $filename != null) {
										echo "hello";
										$sql = "UPDATE tbl_product SET
												product_name = '$product_name',
												description = '$description',
												price = $price,
												category_id = $category_id,
												brand_id = $brand_id,
												unit_id = $unit_id,
												attatchment_url = '$newGenFileName',
												status = '$status'
											WHERE
												id = $id
										";
									} else {
										$sql = "UPDATE tbl_product SET
											product_name = '$product_name',
											description = '$description',
											price = $price,
											category_id = $category_id,
											brand_id = $brand_id,
											unit_id = $unit_id,
											status = '$status'
										WHERE
											id = $id
									";
									}
								}
							}
						}


						$result = $conn->query($sql);
						if ($result) {
							// Load page jol table
							echo '<script type="text/javascript"> 
									window.location.replace("index.php?p=product&msg=200");
								</script>
							';
							// echo msgstyle("កែប្រែព័ត៌មានជោគជ័យ", "success");
						} else {
							echo msgstyle("កែប្រែព័ត៌មានបរាជ័យ", "danger");
						}
					}
					?>
					<!-- End of Update -->


					<div class="app-card app-card-orders-table mb-5">
						<div class="app-card-body">


							<!-- Form update property -->
							<div class="app-content pt-3 p-md-3 p-lg-4">
								<div class="container-xl">
									<div class="row g-4 settings-section">
										<div class="col-12 col-md-12">
											<div class="app-card app-card-settings shadow-sm p-4">
												<div class="app-card-body">

													<form class="settings-form" method="POST" enctype="multipart/form-data" class="settings-form" ?>
														<!-- Brand -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសប្រេនផលិតផល<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_brand' id='sel_brand' <?= $brand_id ?> required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_brand");
																while ($row1 = mysqli_fetch_array($sql)) {
																	$selected = '';
																	if ($row1['id'] == $brand_id) {
																		$selected = 'selected';
																	}
																	echo "<option $selected value='" . $row1['id'] . "'>" . $row1['brand_name'] . "</option>";
																}
																?>
															</select>
														</div>
														<!-- Category -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសប្រភេទផលិតផល<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_category' id='sel_category' <?= $category_id ?> required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_category");
																while ($row2 = mysqli_fetch_array($sql)) {
																	$selected = '';
																	if ($row2['id'] == $category_id) {
																		$selected = 'selected';
																	}
																	echo "<option $selected value='" . $row2['id'] . "'>" . $row2['category_name'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">ឈ្មោះផលិតផល<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_product_name" id="txt_product_name" value="<?= $product_name ?>" required>
														</div>

														<div class="mb-3">
															<label class="form-label">តម្លៃផលិតផល<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_price" id="txt_price" value="<?= $price ?>" required>
														</div>

														<!-- Unit measurement -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសខ្នាតផលិតផល<span style="color: red;">*</span></label>
															<select class="form-select " name="sel_um" id="sel_um" value="<?= $unit_id ?>" required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_unit_measurement");
																while ($row3 = mysqli_fetch_array($sql)) {
																	$selected = '';
																	if ($row3['id'] == $unit_id) {
																		$selected = 'selected';
																	}
																	echo "<option $selected value='" . $row3['id'] . "'>" . $row3['unit_name'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">រូបភាពផលិតផល</label>
															<input type="file" class="form-control" name="p_attatchment_url" id="p_attatchment_url" value="<?= $attatchment_url ?>">
														</div>

														<!-- Status -->
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសស្ថានភាព<span style="color: red;">*</span></label>
															<select class="form-select " name="sel_status" id="sel_status" value="<?= $unit_id ?>" required>
																<option value="Active">---សូមជ្រើសរើស---</option>
																<?php
																$selected = '';
																if ($status == 'Active') {
																	$selected = 'selected';
																	echo "<option value=" . "Deactive" . ">Deactive</option>";
																} else if ($status == 'Deactive') {
																	$selected = 'selected';
																	echo "<option value=" . "Active" . ">Active</option>";
																}
																echo "<option $selected value='" . $status . "'>" . $status . "</option>";
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">បរិយាយ</label>
															<textarea class="form-control" rows="3" name="txt_description" id="txt_description" style="height: 300px;"><?= $description ?></textarea>
														</div>

														<button type="submit" name="btnUpdate" class="btn app-btn-primary">កែប្រែ</button>
													</form>

												</div><!--//app-card-body-->
											</div><!--//app-card-->
										</div>
									</div>
								</div>
							</div>
							<!-- End of Form for update property -->


						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//tab-pane-->

			</div><!--//tab-content-->

		</div><!--//container-fluid-->
	</div><!--//app-content-->

	<!-- Copyright -->

</div><!--//app-wrapper-->

<script src="assets/js/validation-text-area.js"></script>
<script src="assets/js/validation-input-price.js"></script>