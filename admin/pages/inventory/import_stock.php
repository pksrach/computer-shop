<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<h1 class="app-page-title">នាំចូលផលិតផល</h1>
			<hr class="mb-4">

			<!-- Tap Click -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link" id="import_stock-tab" data-bs-toggle="tab" href="#import_stock" role="tab" aria-controls="import_stock" aria-selected="false">នាំចូលផលិតផល</a>
			</nav>

			<!-- Add data into table but not save into db yet -->
			<?php
			if (isset($_POST['btnAdd'])) {
				if (
					isset($_POST['txt_import_date']) &&
					isset($_POST['sel_product_id']) &&
					isset($_POST['txt_import_qty']) &&
					isset($_POST['txt_import_cost'])
				) {
					if (is_array($_POST['sel_product_id']) || is_object($_POST['sel_product_id'])) {
						$count = count($_POST['sel_product_id']);
					} else {
						$count = 0; // or handle the case where $yourVariable is not an array or object
					}
					echo "<script>alert('count: $count');</script>";

					// Retrieve the submitted data
					$import_date = $_POST['txt_import_date'];
					$product_ids = $_POST['sel_product_id'];
					$import_qtys = $_POST['txt_import_qty'];
					$import_costs = $_POST['txt_import_cost'];

					// Initialize an empty array to hold the rows
					$addedRows = [];

					// Iterate through the product rows
					for ($i = 0; $i < count($product_ids); $i++) {
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
						$addedRows[] = $rowData;
					}

					// Append the new rows to the existing session data (if any)
					if (isset($_SESSION['addedRows']) && is_array($_SESSION['addedRows'])) {
						$_SESSION['addedRows'] = array_merge($_SESSION['addedRows'], $addedRows);
					} else {
						$_SESSION['addedRows'] = $addedRows;
					}
				}
			}
			?>

			<?php
			// Check if the Save button is clicked
			if (isset($_POST['btnSave'])) {
				echo "<script>alert('start import');</script>";

				// Check if the session variable exists and is an array
				if (isset($_SESSION['addedRows']) || is_array($_SESSION['addedRows'])) {
					echo "<script>alert('addedRows having');</script>";
					// Get the current date and time in the DATETIME format
					$currentDatetime = date("Y-m-d H:i:s");

					// Initialize a variable to track the success of the transaction
					$transactionSuccess = true;

					// Start the transaction
					mysqli_begin_transaction($conn);

					foreach ($_SESSION['addedRows'] as $rowData) {
						// Check if all required fields are set
						if (
							isset($rowData['import_date']) &&
							isset($rowData['product_id']) &&
							isset($rowData['import_qty']) &&
							isset($rowData['import_cost'])
						) {
							// Retrieve data from the session
							$import_date = $rowData['import_date'];
							$product_id = $rowData['product_id'];
							$import_qty = $rowData['import_qty'];
							$import_cost = $rowData['import_cost'];

							// Insert data into tbl_import
							$insertImportQuery = "INSERT INTO tbl_import (import_date, note, people_id) VALUES ('$currentDatetime', 'Note demo', 5)";
							$resultImport = mysqli_query($conn, $insertImportQuery);

							if (!$resultImport) {
								$transactionSuccess = false;
								break; // Exit the loop if an insertion fails
							}

							// Get the import_id of the newly inserted record
							$import_id = mysqli_insert_id($conn);

							// Insert data into tbl_import_detail with the valid import_id
							$insertImportDetailQuery = "INSERT INTO tbl_import_detail (import_id, product_id, import_qty, cost) VALUES ($import_id, $product_id, $import_qty, $import_cost)";
							$resultImportDetail = mysqli_query($conn, $insertImportDetailQuery);

							if (!$resultImportDetail) {
								$transactionSuccess = false;
								break; // Exit the loop if an insertion fails
							}

							// Update stock in tbl_stock or insert a new record if it doesn't exist
							$updateStockQuery = "INSERT INTO tbl_stock (product_id, stock_qty) VALUES ($product_id, $import_qty)
							ON DUPLICATE KEY UPDATE stock_qty = stock_qty + $import_qty";

							$resultUpdateStock = mysqli_query($conn, $updateStockQuery);

							if (!$resultUpdateStock) {
								$transactionSuccess = false;
								break; // Exit the loop if an insertion fails
							}
						} else {
							$transactionSuccess = false;
							break; // Exit the loop if required fields are missing for a specific row
						}
					}

					// Commit or rollback the transaction based on success
					if ($transactionSuccess) {
						mysqli_commit($conn);
						echo "<script>alert('Transaction successfully committed.');</script>";
					} else {
						mysqli_rollback($conn);
						echo "<script>alert('Transaction rolled back due to errors.');</script>";
					}
				}
				echo "<script>alert('end import');</script>";
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
													<div class="col-md-6">
														<label class="form-label">ថ្ងៃនាំចូល<span style="color: red;">*</span></label>
														<input type="date" class="form-control" name="txt_import_date" id="txt_import_date">
													</div>
													<div class="mb-3">
														<label class="form-label">បរិយាយ</label>
														<textarea class="form-control" rows="3" name="txt_note" id="txt_note" style="height: 100px;"></textarea>
													</div>

													<!-- Form -->
													<form method="POST" enctype="multipart/form-data" class="row g-3" ?>

														<!-- Select product -->
														<div class="col-md-3">
															<label class="form-label">ជ្រើសរើសផលិតផលនាំចូល<span style="color: red;">*</span></label>
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

														<div class="col-md-1">
															<label class="form-label">ចំនួននាំចូល<span style="color: red;">*</span></label>
															<input type="number" class="form-control" name="txt_import_qty" id="txt_import_qty" required>
														</div>

														<div class="col-md-1">
															<label class="form-label">ថ្លៃដើម<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_import_cost" id="txt_import_cost" required>
														</div>
														<div class="col-md-2">
															<label class="form-label">ការធានា</label>
															<input type="date" class="form-control" name="txt_warranty" id="txt_warranty">
														</div>
														<div class="col-md-2">
															<label class="form-label">លេខស៊េរី</label>
															<input type="text" class="form-control" name="txt_serial_number" id="txt_serial_number">
														</div>
														<div class="col-md-2">
															<label class="form-label">ការប្រើប្រាស់</label>
															<select class="form-select " name="sel_condition_type" id="sel_condition_type">
																<option selected value="New">New</option>
																<option value="Used">Used</option>
															</select>
														</div>
														<div class="col-1 " style="padding: 5px; text-align:center;">
															<label class="form-label">សកម្មភាព</label>
															<button type="submit" name="btnAdd" class="col-12 btn btn-info" onclick="addTableRow()">បញ្ចូល</button>
														</div>

														<!-- Remove button -->
														<!-- <div class="col-1">
															<a href="#" class="btn btn-danger" onclick="removeRow(this)"><i class="fas fa-eraser"></i></a>
														</div> -->

														<div class="table-responsive">
															<table class="table app-table-hover mb-0 text-left">
																<thead>
																	<tr>
																		<th class="cell">#</th>
																		<th class="cell" style="text-align: center;">ផលិតផល</th>
																		<!-- <th class="cell" style="text-align: center;">ប្រភេទ</th>
																		<th class="cell" style="text-align: center;">ប្រេន</th> -->
																		<th class="cell" style="text-align: center;">ថ្លៃដើម</th>
																		<th class="cell" style="text-align: center;">ចំនួននាំចូល</th>
																		<th class="cell" style="text-align: center;">ការធានា</th>
																		<th class="cell" style="text-align: center;">លេខស៊េរី</th>
																		<th class="cell" style="text-align: center;">ការប្រើប្រាស់</th>
																		<th class="cell" style="text-align: center;">សរុបថ្លៃដើម</th>
																	</tr>
																</thead>
																<tbody id="table-body">
																	<?php
																	// Retrieve the data from the AJAX request
																	if (isset($_POST['rowData'])) {
																		// Process the data
																		$data = json_decode($_POST['rowData'], true);
																		// Rest of your processing logic
																	}

																	if (
																		isset($data['product']) &&
																		isset($data['qty']) &&
																		isset($data['cost']) &&
																		isset($data['warranty']) &&
																		isset($data['serial']) &&
																		isset($data['condition'])
																	) {
																		// Process the data and create the table row HTML
																		$newRowHTML = '<tr>';
																		$newRowHTML .= '<td class="cell">' . $data['rowNumber '] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['product'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['qty'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['cost'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['warranty'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['serial'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['condition'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['qty'] * $data['cost'] . '</td>';
																		$newRowHTML .= '</tr>';
																	}
																	?>
																</tbody>
															</table>
														</div>
														<!-- Button-->
														<div style="justify-content: space-between; display: inline-flex;">
															<!-- Button clear all -->
															<div class="col-12" style="padding: 5px;">
																<!-- Button save  -->
																<button type="submit" name="btnSave" class="col-12 btn app-btn-primary">រក្សាទុក</button>
																<!-- <button type="button" name="btnClear" class="col-12 btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmClearModal">សម្អាត</button> -->
															</div>
														</div>
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
				<h5 class="modal-title" id="confirmClearModalLabel">បញ្ជាក់</h5>
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

	let rowNumber = 0; // Initialize rowNumber as 1

	function addTableRow() {
		// Get the selected product and other input values
		const productDropdown = document.getElementById('sel_product_id');
		const importQty = document.getElementById('txt_import_qty');
		const importCost = document.getElementById('txt_import_cost');
		const warranty = document.getElementById('txt_warranty');
		const serialNumber = document.getElementById('txt_serial_number');
		const conditionType = document.getElementById('sel_condition_type');

		// Check if any of the required fields are empty
		if (
			productDropdown.value === '' ||
			importQty.value === '' ||
			importCost.value === ''
		) {
			alert('Please fill in all required fields (Product, Quantity, and Cost) before adding to the table.');
			return;
		}

		// Create a new table row element
		const tableBody = document.getElementById('table-body');
		const newRow = document.createElement('tr');

		// Increment the rowNumber
		rowNumber++;
		const rowNumberCell = document.createElement('td');
		rowNumberCell.textContent = rowNumber; // Use the incremented rowNumber
		newRow.appendChild(rowNumberCell);

		// Create table data (td) elements for each cell
		const productCell = document.createElement('td');
		productCell.textContent = productDropdown.options[productDropdown.selectedIndex].text;
		newRow.appendChild(productCell);

		const qtyCell = document.createElement('td');
		qtyCell.textContent = importQty.value;
		newRow.appendChild(qtyCell);

		const costCell = document.createElement('td');
		costCell.textContent = importCost.value;
		newRow.appendChild(costCell);

		const warrantyCell = document.createElement('td');
		warrantyCell.textContent = warranty.value;
		newRow.appendChild(warrantyCell);

		const serialNumberCell = document.createElement('td');
		serialNumberCell.textContent = serialNumber.value;
		newRow.appendChild(serialNumberCell);

		const conditionCell = document.createElement('td');
		conditionCell.textContent = conditionType.value;
		newRow.appendChild(conditionCell);

		// Calculate the total cost
		const totalCost = parseFloat(importQty.value) * parseFloat(importCost.value);
		const totalCostCell = document.createElement('td');
		totalCostCell.textContent = totalCost;
		newRow.appendChild(totalCostCell);

		// Append the new row to the table
		tableBody.appendChild(newRow);

		// Clear input fields
		importQty.value = '';
		importCost.value = '';
		warranty.value = '';
		serialNumber.value = '';
		conditionType.selectedIndex = 0; // Reset the select to its default option
		productDropdown.selectedIndex = 0; // Reset the select to its default option
	}
</script>