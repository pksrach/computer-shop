<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<h1 class="app-page-title">នាំចូលផលិតផល</h1>
			<hr class="mb-4">

			<!-- Tap Click -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link" id="import_stock-tab" data-bs-toggle="tab" href="#import_stock" role="tab" aria-controls="import_stock" aria-selected="false">នាំចូលផលិតផល</a>
			</nav>

			<?php
			// Check if the Save button is clicked
			if (isset($_POST['btnSave'])) {
				// Check if all required fields are set and not empty
				if (
					isset($_POST['txt_import_date']) &&
					isset($_POST['sel_product_id']) &&
					isset($_POST['txt_import_qty']) &&
					isset($_POST['txt_import_cost'])
				) {
					// Retrieve the submitted data
					$import_date = $_POST['txt_import_date'];
					$product_info = explode('|', $_POST['sel_product_id']);
					$product_id = $product_info[0];
					$import_qty = $_POST['txt_import_qty'];
					$import_cost = $_POST['txt_import_cost'];

					// Get the current date and time in the DATETIME format
					$currentDatetime = date("Y-m-d H:i:s");

					// Insert data into tbl_import
					$insertImportQuery = "INSERT INTO tbl_import (import_date, note, people_id) VALUES ('$currentDatetime', 'Note', 1)";
					$resultImport = mysqli_query($conn, $insertImportQuery);

					if ($resultImport) {
						// Get the import_id of the newly inserted record
						$import_id = mysqli_insert_id($conn);

						// Insert data into tbl_import_detail with the valid import_id
						$insertImportDetailQuery = "INSERT INTO tbl_import_detail (import_id, product_id, import_qty, cost) VALUES ('$import_id', '$product_id', '$import_qty', '$import_cost')";
						$resultImportDetail = mysqli_query($conn, $insertImportDetailQuery);

						if ($resultImportDetail) {
							// Data successfully inserted into tbl_import_detail

							// Update stock in tbl_stock or insert a new record if it doesn't exist
							$updateStockQuery = "INSERT INTO tbl_stock (product_id, stock_qty) VALUES ('$product_id', $import_qty)
                            ON DUPLICATE KEY UPDATE stock_qty = stock_qty + $import_qty";

							$resultUpdateStock = mysqli_query($conn, $updateStockQuery);

							if ($resultUpdateStock) {
								echo "<script>alert('Data inserted successfully, stock updated.');</script>";
							} else {
								// Handle the case where the upsert operation fails
								echo "<script>alert('Error updating stock.');</script>";
							}
						} else {
							// Handle the case where insertion into tbl_import_detail fails
							echo "<script>alert('Error inserting data into tbl_import_detail: " . mysqli_error($conn) . "');</script>";
						}
					} else {
						// Handle the case where insertion into tbl_import fails
						echo "<script>alert('Error inserting data into tbl_import: " . mysqli_error($conn) . "');</script>";
					}
				} else {
					// Handle the case where not all required fields are filled in
					echo "<script>alert('Please fill in all required fields.');</script>";
				}
			}
			?>

			<!-- Add data into table but not save into db yet -->
			<?php
			if (isset($_POST['btnAdd'])) {
				if (
					isset($_POST['txt_import_date']) &&
					isset($_POST['sel_product_id']) &&
					isset($_POST['txt_import_qty']) &&
					isset($_POST['txt_import_cost'])
				) {
					$addedRows = [];
					// Retrieve the submitted data
					$import_dates = $_POST['txt_import_date'];
					$product_ids = $_POST['sel_product_id'];
					$import_qtys = $_POST['txt_import_qty'];
					$import_costs = $_POST['txt_import_cost'];
					// Iterate through the product rows
					for ($i = 0; $i < count($product_ids); $i++) {
						$import_date = $import_dates[$i];
						$product_info = explode('|', $product_ids[$i]);
						$product_id = $product_info[0];
						$product_name = $product_info[1];
						$import_qty = $import_qtys[$i];
						$import_cost = $import_costs[$i];

						// Create a row data array
						$rowData = [
							'product_id' => $product_id,
							'product_name' => $product_name,
							'import_qty' => $import_qty,
							'import_cost' => $import_cost,
						];

						// Add the new row data to the session variable
						$_SESSION['addedRows'][] = $rowData;
					}
					// Update the session variable
					$_SESSION['addedRows'] = $addedRows;
				}
			}
			// Clear the added rows by unsetting the session variable
			if (isset($_POST['btnClear'])) {
				unset($_SESSION['addedRows']);
			}
			?>

			<!-- Clear the added rows by unsetting the session variable -->
			<?php

			if (isset($_POST['btnClear'])) {
				echo "<script>alert('btnClear')</script>";
				session_destroy();
			}
			?>
			<div class="tab-content" id="orders-table-tab-content">
				<div class="tab-pane fade show active" id="import_stock" role="tabpanel" aria-labelledby="import_stock-tab">
					<div class="app-card app-card-orders-table mb-5">
						<div class="app-card-body">
							<!-- Form -->
							<div class="app-content pt-3 p-md-3 p-lg-4">
								<div class="container-xl">
									<div class="row g-4 settings-section">
										<div class="col-12 col-md-12">
											<div class="app-card app-card-settings shadow-sm p-4">
												<div class="app-card-body">
													<div class="col-md-12">
														<label class="form-label">ថ្ងៃនាំចូល<span style="color: red;">*</span></label>
														<input type="date" class="form-control" name="txt_import_date" id="txt_import_date">
													</div>

													<!-- Header -->
													<div class="col-md-12" style="display: flex; padding: 5px;">
														<div class="col-md-5" style="display: flex; padding: 5px;">
															<label class="form-label">ជ្រើសរើសផលិតផលនាំចូល<span style="color: red;">*</span></label>

														</div>
														<div class="col-md-2" style="display: flex; padding: 5px;">
															<label class="form-label">ចំនួននាំចូល<span style="color: red;">*</span></label>
														</div>
														<div class="col-md-2" style="display: flex; padding: 5px;">
															<label class="form-label">ថ្លៃដើម<span style="color: red;">*</span></label>
														</div>
														<div class="col-md-2" style="display: flex; padding: 5px;">
															<label class="form-label">សរុបថ្លៃដើម<span style="color: red;">*</span></label>
														</div>
													</div>
													<!-- End of Header -->
													<form method="POST" enctype="multipart/form-data" class="row g-3" id="import-form">
														<div id="product-rows">
															<div class="product-row col-md-12" style="display: flex; padding: 5px;">
																<!-- Select product -->
																<div class="col-md-5" style="display: flex; padding: 5px;">
																	<select class="form-select" name='sel_product_id' id='sel_product_id' required>
																		<option value="">---ជ្រើសរើសផលិតផលនាំចូល---</option>
																		<?php
																		$sql = mysqli_query($conn, "SELECT *
																			FROM tbl_product p 
																				INNER JOIN tbl_brand b ON p.brand_id = b.id
																				INNER JOIN tbl_category c ON p.category_id = c.id
																				INNER JOIN tbl_unit_measurement u ON p.unit_id = u.id
																			WHERE p.status = 'Active'");
																		while ($row = mysqli_fetch_assoc($sql)) {
																			echo "<option value='" . $row['id'] . "|" . $row['product_name'] . "'>" . $row['product_name'] . " || " . $row['brand_name'] . " || " . $row['unit_name'] . "</option>";
																		}
																		?>
																	</select>
																</div>

																<div class="col-md-2" style="display: flex; padding: 5px;">
																	<input type="number" class="form-control" name="txt_import_qty" id="txt_import_qty" required>
																</div>

																<div class="col-md-2" style="display: flex; padding: 5px;">
																	<input type="text" class="form-control" name="txt_import_cost" id="txt_import_cost" required>
																</div>

																<div class="col-md-2" style="display: flex; padding: 5px;">
																	<input type="text" class="form-control" name="txt_amount" id="txt_amount">
																</div>

																<!-- Remove button -->
																<div class="col-1" style="display: flex; padding: 5px;">
																	<a href="#" class="btn btn-danger" onclick="removeRow(this)"><i class="fas fa-eraser"></i></a>
																</div>

															</div>
														</div>

														<div class="col-md-12">
															<label class="form-label">ថ្ងៃនាំចូល<span style="color: red;">*</span></label>
															<input type="date" class="form-control" name="txt_import_date" id="txt_import_date">
														</div>

														<!-- Button add -->
														<div style="justify-content: space-between; display: inline-flex;">
															<div class="col-6 " style="padding: 5px;">
																<button type="submit" name="btnAdd" class="col-12 btn app-btn-primary" onclick="addProductRow()">បញ្ចូល</button>

															</div>
															<!-- Button clear all -->
															<!-- <div class="col-6 " style="padding: 5px;">
																<button type="button" name="btnClear" class="col-12 btn btn-danger" onclick="confirmClear()">សម្អាត</button>
															</div> -->
															<div class="col-6" style="padding: 5px;">
																<button type="button" name="btnClear" class="col-12 btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmClearModal">សម្អាត</button>
															</div>
														</div>

														<!-- Button save  -->
														<button type="submit" name="btnSave" class="btn app-btn-primary">រក្សាទុក</button>
													</form>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End of Form -->


						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//tab-pane-->
			</div><!--//tab-content-->

		</div><!--//container-fluid-->
	</div><!--//app-content-->

</div><!--//app-wrapper-->
<div class="modal fade" id="confirmClearModal" tabindex="-1" aria-labelledby="confirmClearModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="confirmClearModalLabel">Confirm Clear</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				តើអ្នកពិតជាចង់លុបទាំងអស់មែនទេ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="confirmClearButton">Clear All</button>
			</div>
		</div>
	</div>
</div>


<!-- javascrip validation links -->

<!-- <script src="assets/js/validation-text-area.js"></script> -->
<script src="assets/js/validation-input-price.js"></script>

<script>
	function confirmClear() {
		// Disable the Clear button to prevent rapid clicks
		var clearButton = document.querySelector('[name="btnClear"]');
		clearButton.disabled = true;

		// Show a confirmation dialog
		var confirmation = confirm("តើអ្នកពិតជាចង់លុបមែនទេ ?");

		// Enable the Clear button again
		clearButton.disabled = false;

		// If the user confirms, remove all rows
		if (confirmation) {
			var productRows = document.querySelectorAll('.product-row');
			productRows.forEach(function(row) {
				row.remove();
			});
		}
	}


	function addProductRow() {
		// Check if there are existing rows
		const productRows = document.querySelectorAll('.product-row');

		if (productRows.length === 0) {
			// If no rows exist, clone the initial product row and append it to the product-rows div
			const productRow = document.querySelector('.product-row');
			const newRow = productRow.cloneNode(true);
			document.getElementById('product-rows').appendChild(newRow);
		} else {
			// If rows exist, clone the last row and append it to the product-rows div
			const lastRow = productRows[productRows.length - 1];
			const newRow = lastRow.cloneNode(true);
			document.getElementById('product-rows').appendChild(newRow);
		}
	}

	function removeRow(button) {
		// Find the parent row and remove it
		const row = button.closest('.product-row');
		row.remove();
	}

	// When the modal confirmation button is clicked
	document.getElementById('confirmClearButton').addEventListener('click', function() {
		// Remove all rows
		var productRows = document.querySelectorAll('.product-row');
		productRows.forEach(function(row) {
			row.remove();
		});

		// Close the modal using Bootstrap's method
		var modal = new bootstrap.Modal(document.getElementById('confirmClearModal'));
		modal.hide();
	});
</script>