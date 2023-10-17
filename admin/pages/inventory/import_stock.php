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
			if (isset($_POST['btnSave'])) {
				$tableRowsJSON = $_POST['tableRows'];
				$tableRows = json_decode($tableRowsJSON, true);

				if (empty($tableRows)) {
					echo "<script>alert('Please add at least one product to the table.');</script>";
					return;
				}

				mysqli_begin_transaction($conn);

				try {
					$insertImportSQL = "INSERT INTO tbl_import (import_date, note, people_id) VALUES (?, ?, ?)";
					$stmt = mysqli_prepare($conn, $insertImportSQL);

					$import_date = $_POST['txt_import_date'];
					$note = $_POST['txt_note'];
					$people_id = $_SESSION['user_people_id'];

					// Check field import_date if do not assign value or value is 0000-00-00 00:00:00 need to show alert and do not refresh page


					mysqli_stmt_bind_param($stmt, "ssi", $import_date, $note, $people_id);
					mysqli_stmt_execute($stmt);
					if (!mysqli_stmt_execute($stmt)) {
						throw new Exception("Error executing import query: " . mysqli_stmt_error($stmt));
					}

					$import_id = mysqli_insert_id($conn);
					echo "import_id: $import_id";

					// Prepare the import detail statement outside the loop
					$insertImportDetailSQL = "INSERT INTO tbl_import_detail (import_id, product_id, import_qty, cost)
											VALUES (?, ?, ?, ?)";
					$stmtImportDetail = mysqli_prepare($conn, $insertImportDetailSQL);

					foreach ($tableRows as $rowData) {
						$productInfo = explode("|", $rowData['product_id']);
						$product_id = $productInfo[0];
						$import_qty = $rowData['import_qty'];
						$cost = $rowData['import_cost'];
						$warranty = $rowData['warranty'];
						$serial_number = $rowData['serial_number'];
						$condition_type = $rowData['condition_type'];

						echo "<script>console.log('product_id: $product_id');</script>";

						// Bind the variables to the placeholders
						mysqli_stmt_bind_param($stmtImportDetail, "iiid", $import_id, $product_id, $import_qty, $cost);

						// Execute the import detail query
						if (!mysqli_stmt_execute($stmtImportDetail)) {
							throw new Exception("Error executing import detail query: " . mysqli_stmt_error($stmtImportDetail));
						}

						// Check if a record with the same product ID exists in the tbl_stock table
						$checkStockSQL = "SELECT * FROM tbl_stock WHERE product_id = ?";
						$stmtCheckStock = mysqli_prepare($conn, $checkStockSQL);
						mysqli_stmt_bind_param($stmtCheckStock, "i", $product_id);
						mysqli_stmt_execute($stmtCheckStock);

						$result = mysqli_stmt_get_result($stmtCheckStock);

						// Stock
						if (mysqli_num_rows($result) > 0) {
							// Update the SQL statement with five placeholders
							$updateStockSQL = "UPDATE tbl_stock 
							SET stock_qty = stock_qty + ?, 
								cost = (cost * stock_qty + ? * ?) / (stock_qty + ?) 
							WHERE product_id = ?";
							$stmtUpdateStock = mysqli_prepare($conn, $updateStockSQL);
							// Bind the five variables to the placeholders
							mysqli_stmt_bind_param($stmtUpdateStock, "ddddi", $import_qty, $cost, $import_qty, $import_qty, $product_id);
							mysqli_stmt_execute($stmtUpdateStock);
						} else {
							// No record with the same product ID exists, insert a new record
							$insertStockSQL = "INSERT INTO tbl_stock (product_id, stock_qty, warranty, serial_number, condition_type, cost)
											   VALUES (?, ?, ?, ?, ?, ?)";
							$stmtStock = mysqli_prepare($conn, $insertStockSQL);
							mysqli_stmt_bind_param($stmtStock, "iisssd", $product_id, $import_qty, $warranty, $serial_number, $condition_type, $cost);
							mysqli_stmt_execute($stmtStock);
						}
					}
					// End loop

					// Close the import detail statement (optional)
					mysqli_stmt_close($stmtImportDetail);

					mysqli_commit($conn);

					// Set a session variable to indicate success
					$_SESSION['save_success'] = true;

					// Clear the session data
					unset($_SESSION['addedRows']);
				} catch (mysqli_sql_exception $e) {
					mysqli_rollback($conn);
					echo "Error: " . $e->getMessage();
				}
			}
			?>

			<!-- Add data into table but not save into db yet -->
			<?php
			if (isset($_POST['btnAdd'])) {
				if (
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

													<!-- Form -->
													<form method="POST" enctype="multipart/form-data" class="row g-3" onsubmit="return validateForm()">
														<!-- Import date -->
														<div class="col-md-6">
															<label class="form-label">ថ្ងៃនាំចូល<span style="color: red;">*</span></label>
															<input type="date" class="form-control" name="txt_import_date" id="txt_import_date">
														</div>
														<div class="mb-3">
															<label class="form-label">បរិយាយ</label>
															<textarea class="form-control" rows="3" name="txt_note" id="txt_note" style="height: 100px;"></textarea>
														</div>

														<!-- Select product -->
														<div class="col-md-3">
															<label class="form-label">ជ្រើសរើសផលិតផលនាំចូល<span style="color: red;">*</span></label>
															<select class="form-select" name='sel_product_id' id='sel_product_id'>
																<option value="">---ជ្រើសរើសផលិតផលនាំចូល---</option>
																<?php
																$sql = mysqli_query($conn, "SELECT p.*, b.brand_name, c.category_name, u.unit_name
																			FROM tbl_product p 
																				INNER JOIN tbl_brand b ON p.brand_id = b.id
																				INNER JOIN tbl_category c ON p.category_id = c.id
																				INNER JOIN tbl_unit_measurement u ON p.unit_id = u.id
																			WHERE p.status = 'Active'");
																while ($row = mysqli_fetch_assoc($sql)) {
																	echo "<option value='" . $row['id'] . "|" . $row['product_name'] . "'>" . $row['id'] . "||" . $row['product_name'] . " || " . $row['brand_name'] . " || " . $row['unit_name'] . "</option>";
																}
																?>
															</select>
														</div>

														<div class="col-md-1">
															<label class="form-label">ចំនួននាំចូល<span style="color: red;">*</span></label>
															<input type="number" class="form-control" name="txt_import_qty" id="txt_import_qty">
														</div>

														<div class="col-md-1">
															<label class="form-label">ថ្លៃដើម<span style="color: red;">*</span></label>
															<input type="text" class="form-control" name="txt_import_cost" id="txt_import_cost">
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
															<button type="button" name="btnAdd" class="col-12 btn btn-info" onclick="addTableRow()">បញ្ចូល</button>
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
																	}

																	if (
																		isset($data['product']) &&
																		isset($data['qty']) &&
																		isset($data['cost']) &&
																		isset($data['warranty']) &&
																		isset($data['serial']) &&
																		isset($data['condition'])
																	) {
																		echo "product: " . $data['product'] . "<br>";
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
														<!-- Hidden input field to store table rows data as JSON -->
														<input type="hidden" name="tableRows" id="tableRows" value="[]">

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
													<!-- End of Form -->
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

<!-- Modal -->
<div class="modal fade" id="warning_exception" tabindex="-1" aria-labelledby="warningException" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="warningException">បំរាម <i class="fa-solid fa-triangle-exclamation" style="color: #ffc800;"></i></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modalMessage">
				<!-- Dynamic message will be inserted here -->
			</div>
		</div>
	</div>
</div>

<!-- Modal Succes -->
<div class="modal fade" id="succes_modal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="successModalLabel">ព័តមាន <i class="fa-solid fa-square-check" style="color: #00e02d;"></i></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				ទិន្នន័យត្រូវបានរក្សាទុកដោយជោគជ័យ
			</div>
		</div>
	</div>
</div>

<!-- <script src="assets/js/validation-text-area.js"></script> -->
<script src="assets/js/validation-input-price.js"></script>

<script>
	let rowNumber = 0; // Initialize rowNumber as 1

	function addTableRow() {
		// Get the selected product and other input values
		const productDropdown = document.getElementById('sel_product_id');
		const importQty = document.getElementById('txt_import_qty');
		const importCost = document.getElementById('txt_import_cost');
		const warranty = document.getElementById('txt_warranty');
		const serialNumber = document.getElementById('txt_serial_number');
		const conditionType = document.getElementById('sel_condition_type');

		// Create an object to store product data for the current row
		const rowData = {
			product_id: productDropdown.value,
			import_qty: importQty.value,
			import_cost: importCost.value,
			warranty: warranty.value,
			serial_number: serialNumber.value,
			condition_type: conditionType.value,
		};

		// Get the table rows data
		let tableRows = JSON.parse(document.getElementById('tableRows').value);

		// Add the current row data to the table rows data
		tableRows.push(rowData);

		// Update the hidden input field with the updated table rows data
		document.getElementById('tableRows').value = JSON.stringify(tableRows);

		// Check if any of the required fields are empty
		if (
			productDropdown.value === '' ||
			importQty.value === '' ||
			importCost.value === ''
		) {
			var message = "សូមបញ្ចូលព័តមាន មុនពេលបន្ថែមទៅតារាង";
			setModalMessage(message);
			$('#warning_exception').modal('show');
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
		console.log("productDropdown: ", productDropdown.options[productDropdown.selectedIndex].text);

		// Get the selected value
		const selectedValue = productDropdown.value;
		console.log('Selected Value: ' + selectedValue);

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

	$(document).ready(function() {
		<?php
		// Check if the session variable is set and show the modal if it is
		if (isset($_SESSION['save_success']) && $_SESSION['save_success'] === true) {
			echo "$('#succes_modal').modal('show');";
			// Reset the session variable to prevent the modal from showing on page refresh
			$_SESSION['save_success'] = false;
		}
		?>
	});

	function validateForm() {
		var importDate = document.getElementById("txt_import_date").value;

		if (importDate.trim() === '' || importDate === '0000-00-00') {
			var message = "សូមបញ្ចូលថ្ងៃនាំចូល និងផលិផលមុនពេលរក្សាទុក";
			setModalMessage(message);
			$('#warning_exception').modal('show'); // Show the Bootstrap modal
			return false; // Prevent form submission
		}

		return true; // Allow form submission
	}

	function setModalMessage(message) {
		var modalMessage = document.getElementById("modalMessage");
		modalMessage.textContent = message;
	}
</script>