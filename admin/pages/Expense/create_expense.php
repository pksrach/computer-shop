<?php
// Clear the session data
unset($_SESSION['addedRows']);
?>
<div class="app-wrapper">

	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">
			<h1 class="app-page-title">កត់ត្រាការចំណាយ</h1>
			<hr class="mb-4">

			<!-- Tap Click -->
			<nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				<a class="flex-sm-fill text-sm-center nav-link" id="import_stock-tab" data-bs-toggle="tab" href="#import_stock" role="tab" aria-controls="import_stock" aria-selected="false">ការចំណាយ</a>
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
					$insertExpenseSQL = "INSERT INTO tbl_expense (exp_date, people_id) VALUES (?, ?);";
					$stmt = mysqli_prepare($conn, $insertExpenseSQL);

					$exp_date = $_POST['txt_exp_date'];
					$people_id = $_SESSION['user_people_id'];

					// Check field import_date if do not assign value or value is 0000-00-00 00:00:00 need to show alert and do not refresh page
					mysqli_stmt_bind_param($stmt, "si", $exp_date, $people_id);

					mysqli_stmt_execute($stmt);
					if (mysqli_stmt_errno($stmt) !== 0) {
						throw new Exception("Error executing expense query: " . mysqli_stmt_error($stmt));
					}

					$expense_id = mysqli_insert_id($conn);
					echo "<script>console.log('expense_id: $expense_id');</script>";

					// Prepare the import detail statement outside the loop
					$insertExpenseDetailSQL = "INSERT INTO tbl_expense_details (exp_id, amount, note, exp_type_id)VALUES (?, ?, ?, ?)";
					$stmtExpenseDetail = mysqli_prepare($conn, $insertExpenseDetailSQL);

					foreach ($tableRows as $rowData) {
						$exp_type_info = explode('|', $rowData['exp_type_id']);
						$exp_type_id = $exp_type_info[0];

						$amount = $rowData['amount'];
						$note = $rowData['note'];

						echo "<script>console.log('exp_type_id: $exp_type_id');</script>";

						// Bind the variables to the placeholders
						mysqli_stmt_bind_param($stmtExpenseDetail, "idsi", $expense_id, $amount, $note, $exp_type_id);

						// Execute the import detail query
						if (!mysqli_stmt_execute($stmtExpenseDetail)) {
							throw new Exception("Error executing expense detail query: " . mysqli_stmt_error($stmtExpenseDetail));
						}
					}
					// End loop

					// Close the expense detail statement (optional)
					mysqli_stmt_close($stmt);
					mysqli_stmt_close($stmtExpenseDetail);

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
					isset($_POST['txt_exp_type']) &&
					isset($_POST['txt_amount']) &&
					isset($_POST['txt_note'])
				) {
					if (is_array($_POST['txt_exp_type']) || is_object($_POST['txt_exp_type'])) {
						$count = count($_POST['txt_exp_type']);
					} else {
						$count = 0; // or handle the case where $yourVariable is not an array or object
					}
					echo "<script>alert('count: $count');</script>";

					// Retrieve the submitted data
					$exp_types = $_POST['txt_exp_type'];
					$amounts = $_POST['txt_amount'];
					$notes = $_POST['txt_note'];

					// Initialize an empty array to hold the rows
					$addedRows = [];

					// Iterate through the product rows
					for ($i = 0; $i < count($exp_types); $i++) {
						$exp_type_info = explode('|', $exp_types[$i]);
						$exp_type_id = $exp_type_info[0];
						$exp_type_name = $exp_type_info[1];
						$amount = $amounts[$i];
						$note = $notes[$i];

						// Create a row data array
						$rowData = [
							'exp_type_id' => $exp_type_id,
							'exp_type_name' => $exp_type_name,
							'amount' => $amount,
							'note' => $note,
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
														<div>
															<label class="form-label">កាលបរិច្ឆេទ<span style="color: red;">*</span></label>
															<input type="date" class="form-control" name="txt_exp_date" id="txt_exp_date">
														</div>
														<!-- Select expense type -->
														<div class="col-md-3">
															<label class="form-label">ជ្រើសរើសប្រភេទ<span style="color: red;">*</span></label>
															<select class="form-select" id="txt_exp_type">
																<option value="">ជ្រើសរើសប្រភេទចំណាយ</option>
																<?php
																$sql = mysqli_query($conn, "SELECT * FROM tbl_expense_type;");
																while ($row = mysqli_fetch_assoc($sql)) {
																	echo "<option value='" . $row['id'] . "|" . $row['name'] . "'>" . $row['name'] . "</option>";
																}
																?>
															</select>
														</div>

														<!-- amount -->
														<div class="col-md-3">
															<label class="form-label">ថ្លៃចំណាយ<span style="color: red;">*</span></label>
															<input type="number" class="form-control" name="txt_amount" id="txt_amount">
														</div>
														<!-- description -->
														<div class="col-md-12">
															<label class="form-label">បរិយាយ</label>
															<textarea class="form-control" rows="3" name="txt_note" id="txt_note" style="height: 100px;"></textarea>
														</div>
														<div class="col-12 " style="padding: 5px; text-align:center;">
															<!-- <label class="form-label">សកម្មភាព</label> -->
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
																		<th class="cell" style="text-align: left;">ប្រភេទចំណាយ</th>
																		<th class="cell" style="text-align: left;">ថ្លៃចំណាយ</th>
																		<th class="cell" style="text-align: left;">បរិយាយ</th>
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
																		isset($data['exp_type']) &&
																		isset($data['amount']) &&
																		isset($data['note'])
																	) {
																		echo "exp_type: " . $data['exp_type'] . "<br>";
																		// Process the data and create the table row HTML
																		$newRowHTML = '<tr>';
																		$newRowHTML .= '<td class="cell">' . $data['rowNumber '] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['exp_type'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['amount'] . '</td>';
																		$newRowHTML .= '<td class="cell" style="text-align: center;">' . $data['note'] . '</td>';
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
		const expTypeDropdown = document.getElementById('txt_exp_type');
		const amount = document.getElementById('txt_amount');
		const note = document.getElementById('txt_note');

		// Create an object to store product data for the current row
		const rowData = {
			exp_type: expTypeDropdown.value,
			exp_type_id: expTypeDropdown.value,
			amount: amount.value,
			note: note.value,
		};

		// Get the table rows data
		let tableRows = JSON.parse(document.getElementById('tableRows').value);

		// Add the current row data to the table rows data
		tableRows.push(rowData);

		// Update the hidden input field with the updated table rows data
		document.getElementById('tableRows').value = JSON.stringify(tableRows);

		// Check if any of the required fields are empty
		if (
			expTypeDropdown.value === '' ||
			amount.value === ''
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
		const expTypeCell = document.createElement('td');
		expTypeCell.textContent = expTypeDropdown.options[expTypeDropdown.selectedIndex].text;
		newRow.appendChild(expTypeCell);

		// Get the selected value
		const selectedValue = expTypeDropdown.value;
		console.log('Selected Value: ' + selectedValue);

		const amountCell = document.createElement('td');
		amountCell.textContent = amount.value;
		newRow.appendChild(amountCell);

		const noteCell = document.createElement('td');
		noteCell.textContent = note.value;
		newRow.appendChild(noteCell);

		// Append the new row to the table
		tableBody.appendChild(newRow);

		// Clear input fields
		amount.value = '';
		note.value = '';
		expTypeDropdown.selectedIndex = 0; // Reset the select to its default option
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
		var exp_date = document.getElementById("txt_exp_date").value;

		if (exp_date.trim() === '' || exp_date === '0000-00-00') {
			var message = "សូមបញ្ចូលកាលបរិច្ឆេទ និងការចំណាយមុនពេលរក្សាទុក";
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