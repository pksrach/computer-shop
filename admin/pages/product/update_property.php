<style>
	.validation_msg {
		color: red;
		font-size: 14px;
	}
</style>

<?php
$id = $_GET['proid'];
$sql = "
	SELECT
		p.property_id,
		p.property_img,
		p.property_name,
		p.property_price,
		p.property_desc,
		pt.property_type_id,
		ps.property_status_id
	FROM
		tbl_property p
		INNER JOIN tbl_property_type pt ON p.property_type_id = pt.property_type_id 
		LEFT JOIN tbl_property_status ps on p.property_status_id = ps.property_status_id
	WHERE
		p.property_id = $id
";

// Get data from database by param proid
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$pt_id = $row['property_type_id'];
$p_name = $row['property_name'];
$p_price = $row['property_price'];
$ps_id = $row['property_status_id'];
$p_oimage = $row['property_img'];
$p_desc = $row['property_desc'];

?>

<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<h1 class="app-page-title">កែប្រែ ព័ត៌មាននៃអចលនទ្រព្យ</h1>
			<hr class="mb-4">

			<!-- Tap Click -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link" id="create_property-tab" data-bs-toggle="tab" href="#create_property" role="tab" aria-controls="create_property" aria-selected="false">កែប្រែអចលនទ្រព្យ</a>
			</nav>
			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="create_property" role="tabpanel" aria-labelledby="create_property-tab">
					<?php
					$property_nm_msg = '<div class="validation_msg">សូមបញ្ចូលឈ្មោះអចលនទ្រព្យ</div>';
					$property_price_msg = '<div class="validation_msg">សូមបញ្ចូលតម្លៃអចលនទ្រព្យ</div>';
					$property_img_msg = '<div class="validation_msg">សូមជ្រើសរើសរូបភាពអចលនទ្រព្យ</div>';
					$msg1 = $msg2 = $msg3 = '';

					if (isset($_POST['btnUpdate'])) {
						// Fields
						$property_type_id = $_POST['sel_property_type'];
						$property_name = $_POST['txt_property_name'];
						$property_price = $_POST['txt_property_price'];
						$property_status_id = $_POST['sel_property_status'];
						$property_desc = $_POST['tar_desc'];

						// Files
						$filename = $_FILES['img_property']['name'];
						$file_size = $_FILES['img_property']['size'];
						$filetmp = $_FILES['img_property']['tmp_name'];
						$filetype = $_FILES['img_property']['type'];

						$filename_bstr = explode(".", $filename);
						$file_ext = strtolower(end($filename_bstr));
						$extensions = array("jpeg", "jpg", "png");
						if ($filename == "") {
							$sql = "
								UPDATE tbl_property SET
									property_type_id = $property_type_id,
									property_name = '$property_name',
									property_price = $property_price,
									property_img = '$p_oimage',
									property_status_id = $property_status_id,
									property_desc = '$property_desc'
								WHERE
									property_id = $id
							";
							$result = $conn->query($sql);
							if ($result) {
								// Load page jol table
								echo '<script type="text/javascript"> 
									window.location.replace("index.php?p=property&msg=200");
									</script>
								';
							} else {
								echo msgstyle("កែប្រែព័ត៌មានបរាជ័យ", "danger");
							}
						} else {
							//2mb
							$maxsize = 2 * 1024 * 1024;
							if ($file_size > $maxsize) {
								echo msgstyle("File size must be less than 2MB", "info");
							} else {
								if (in_array($file_ext, $extensions) === false) {
									echo msgstyle("extension not allowed, please choose a JPEG or PNG file.", "info");
								} else {
									$path_img_store_local = "assets/images/img_data_store_upload/" . $filename;
									$path_img_in_db = "assets/images/img_data_store_upload/" . $p_oimage;
									echo "<script>console.log(`Path stroe img: $path_img_store_local`)</script>";
									if (file_exists($path_img_store_local) && !file_exists($path_img_in_db)) {
										echo msgstyle("រូបភាព " . $filename . " មានរួចហើយ", "info");
										// Update
										$sql = "
											UPDATE tbl_property SET
												property_type_id = $pt_id,
												property_name = '$p_name',
												property_price = $p_price,
												property_img = '$filename',
												property_status_id = $ps_id,
												property_desc = '$p_desc'
											WHERE
												property_id = $id
										";
										echo $sql;
										$result = $conn->query($sql);
										if ($result) {
											// Load page jol table
											echo '<script type="text/javascript"> 
													window.location.replace("index.php?p=property&msg=200");
												</script>
											';
										} else {
											echo msgstyle("កែប្រែព័ត៌មានបរាជ័យ", "danger");
										}
									} else {
										// ករណី filename mean nv db hz 
										if (file_exists($path_img_in_db)) {
											unlink($path_img_in_db);

											if (
												trim($property_type_id) != '' &&
												trim($property_name) != '' &&
												trim($property_price) != '' &&
												trim($property_status_id) != '' &&
												trim($filename) != ''
											) {
												// Update
												move_uploaded_file($filetmp, $path_img_store_local);
												$sql = "
													UPDATE tbl_property SET
														property_type_id = $pt_id,
														property_name = '$p_name',
														property_price = $p_price,
														property_img = '$filename',
														property_status_id = $ps_id,
														property_desc = '$p_desc'
													WHERE
														property_id = $id
												";
												echo $sql;
												$result = $conn->query($sql);
												if ($result) {
													// Load page jol table
													echo '<script type="text/javascript"> 
															window.location.replace("index.php?p=property&msg=200");
														</script>
													';
												} else {
													echo msgstyle("កែប្រែព័ត៌មានបរាជ័យ", "danger");
												}
											}
										}
									}
								}
							}
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
														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសប្រភេទអចលនទ្រព្យ<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_property_type' id='sel_property_type' <?= $pt_id ?> required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_property_type");
																while ($row = mysqli_fetch_array($sql)) {
																	$selected = '';
																	if ($row['property_type_id'] == $pt_id) {
																		$selected = 'selected';
																	}
																	echo "<option $selected value='" . $row['property_type_id'] . "'>" . $row['property_type_kh'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">ឈ្មោះអចលនទ្រព្យ<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_property_name" id="txt_property_name" value="<?= $p_name ?>" required>
														</div>

														<div class="mb-3">
															<label class="form-label">តម្លៃអចលនទ្រព្យ<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_property_price" id="txt_property_price" value="<?= $p_price ?>" required>
														</div>

														<div class="mb-3">
															<label class="form-label">រូបភាពអចលនទ្រព្យ</label>
															<input type="file" class="form-control" name="img_property" id="img_property" value="<?= $p_oimage ?>">
														</div>

														<div class="mb-3">
															<label class="form-label">ជ្រើសរើសស្ថានភាពអចលនទ្រព្យ<span style="color: red;">*</span></label>
															<select class="form-select " name="sel_property_status" id="sel_property_status" value="<?= $ps_id ?>" required>
																<option value="">---សូមជ្រើសរើស---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_property_status");
																while ($row2 = mysqli_fetch_array($sql)) {
																	$selected = '';
																	if ($row2['property_status_id'] == $ps_id) {
																		$selected = 'selected';
																	}
																	echo "<option $selected value='" . $row2['property_status_id'] . "'>" . $row2['property_status'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">បរិយាយ</label>
															<textarea class="form-control" rows="3" name="tar_desc" id="tar_desc" style="height: 100px;"><?= $p_desc ?></textarea>
														</div>

														<button type="submit" name="btnUpdate" class="btn app-btn-primary">កែប្រែ</button>
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

	<!-- Copyright -->
	<?php include_once 'pages/copyright.php'; ?>

</div><!--//app-wrapper-->