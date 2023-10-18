 <div class="app-wrapper">

 	<div class="app-content pt-3 p-md-3 p-lg-4">
 		<div class="container-xl">
 			<!-- Header -->
 			<div class="row g-3 mb-4 align-items-center justify-content-between">
 				<div class="col-auto">
 					<h1 class="app-page-title mb-0">ផ្ទាំងលក់</h1>
 				</div>

 				<!-- Search -->
 				<div class="col-auto">
 					<div class="page-utilities">
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
 					</div>
 				</div>
 			</div>
 			<!-- End of header -->

 			<!-- Fetch product -->
 			<?php
				// Initialize an empty array to store products
				$products = [];

				// Define your SQL query to retrieve products
				$sql = "SELECT 
				p.product_name, 
				p.description, 
				s.stock_qty AS qty,
				um.unit_name,
				p.price, 
				p.attatchment_url 
				FROM tbl_stock s 
				INNER JOIN tbl_product p ON s.product_id = p.id
				INNER JOIN tbl_unit_measurement um ON p.unit_id = um.id;";

				// Execute the query and fetch the results
				$result = mysqli_query($conn, $sql);

				if ($result) {
					while ($row = mysqli_fetch_assoc($result)) {
						// Append each product as an associative array to the $products array
						$products[] = $row;
					}
				}

				// Close the database connection
				mysqli_close($conn);
				?>

 		</div><!--//container-fluid-->
 		<!-- Container -->
 		<div class="row g-4">
 			<!-- col 8 -->
 			<div class="row g-3 col-8" style="overflow-y: scroll; max-height: 600px;">

 				<?php
					foreach ($products as $product) {
					?>
 					<div class="card mx-auto" style="width: 15rem;">
 						<img src="<?= $product['attatchment_url'] ? "assets/images/img_data_store_upload/" . $product['attatchment_url'] : 'assets/images/X-ComShop Logo.svg' ?>" class="card-img-top">
 						<div class="card-body">
 							<h5 class="card-title"><?php echo $product['product_name']; ?></h5>
 							<div class="card-text" id="txtPrice">Price: <?php echo $product['price']; ?></div>
 							<div class="card-text" id="txtQty">Qty: <?php echo $product['qty']; ?> <?php echo $product['unit_name']; ?></div>
 							<p class="card-text">Description: <?= $product['description']; ?></p>
 							<!-- <button type="button" name="btnAdd" class="col-12 btn btn-info">បញ្ចូល</button> -->
 						</div>
 					</div>
 				<?php } ?>

 			</div>

 			<!-- col 4 -->
 			<div class="col-4">
 				<table class="table table-hover" id="cartTable">
 					<thead>
 						<tr>
 							<th scope="col">#</th>
 							<th scope="col">Product Name</th>
 							<th scope="col">Price$</th>
 							<th scope="col">Qty</th>
 							<th scope="col">Amount$</th>
 							<th scope="col">Action</th>
 						</tr>
 					</thead>
 					<tbody></tbody>
 				</table>
 			</div>

 		</div><!--//row-->

 		<div style="height: 100px;">
 			<div class="d-flex justify-content-center align-items-center h-100"> <!-- Use d-flex and justify-content-center classes -->
 				<button type="button" id="paymentButton" name="btnSave" class="col-12 btn btn-success h-60">
 					<h4>Payment</h4>
 				</button>
 			</div>
 		</div>



 	</div><!--//app-content-->

 </div><!--//app-wrapper-->

 <!-- Modal checkout payment -->
 <div class="modal-dialog modal-dialog-scrollable fade">
 	<div class="modal-content">
 		<div class="modal-header">
 			<h5 class="modal-title">Payment</h5>
 			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 		</div>
 		<div class="modal-body">
 			<!-- Payment Method Dropdown -->
 			<div class="mb-3">
 				<label for="paymentMethod" class="form-label">Payment Method</label>
 				<select class="form-select" id="paymentMethod">
 					<!-- You will populate this dropdown with options fetched from the database -->
 				</select>
 			</div>

 			<!-- Total Amount Display -->
 			<div class="mb-3">
 				<label for="totalAmount" class="form-label">Total Amount</label>
 				<input type="text" class="form-control" id="totalAmount" readonly>
 			</div>

 			<!-- Cash Received Input -->
 			<div class="mb-3">
 				<label for="cashReceived" class="form-label">Cash Received</label>
 				<input type="number" class="form-control" id="cashReceived">
 			</div>

 			<!-- Cash Return Display -->
 			<div class="mb-3">
 				<label for="cashReturn" class="form-label">Cash Return</label>
 				<input type="text" class="form-control" id="cashReturn" readonly>
 			</div>
 		</div>
 		<div class="modal-footer">
 			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
 			<button type="button" class="btn btn-primary" id="checkoutBtn">Checkout</button>
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


 <script>
 	// Initialize a counter for row numbers
 	var rowNum = 1;

 	// Function to handle the click event for adding a product
 	function addProductToTable(productName, price, qty, maxQty) {
 		// Assuming you have a table with the id 'cartTable'
 		var table = document.getElementById('cartTable');

 		// Check if the product already exists in the table
 		for (var i = 1; i < table.rows.length; i++) {
 			if (table.rows[i].cells[1].textContent === productName) {
 				var rowQtyInput = table.rows[i].cells[2].querySelector('input');
 				var rowQty = parseInt(rowQtyInput.value);
 				if (rowQty < maxQty) {
 					rowQtyInput.value = rowQty + 1;
 					updateQty(rowQtyInput);
 				}
 				return; // Product already exists, no need to add a new row
 			}
 		}

 		// Create a new row for the table
 		var row = table.insertRow();

 		// Insert cells for row number, product name, price, quantity, amount, and a delete button
 		var cell0 = row.insertCell(0);
 		var cell1 = row.insertCell(1);
 		var cell2 = row.insertCell(2);
 		var cell3 = row.insertCell(3);
 		var cell4 = row.insertCell(4);
 		var cell5 = row.insertCell(5);

 		cell0.textContent = rowNum; // Set the row number
 		cell1.innerHTML = productName;
 		cell2.innerHTML = formatCurrency(price);
 		cell3.innerHTML = '<input type="number" min="1" max="' + maxQty + '" value="' + qty + '" oninput="updateQty(this)">';
 		cell4.textContent = formatCurrency((price * qty).toFixed(2)); // Display the calculated amount
 		cell5.innerHTML = '<button class="btn btn-danger" type="button" onclick="removeProduct(this)"><i class="fas fa-eraser"></button>';

 		// Increment the row number counter
 		rowNum++;
 	}

 	// Function to format a number as currency
 	function formatCurrency(value) {
 		return new Intl.NumberFormat('en-US', {
 			style: 'currency',
 			currency: 'USD'
 		}).format(value);
 	}

 	// Function to remove a product from the table
 	function removeProduct(button) {
 		var row = button.parentNode.parentNode;
 		row.parentNode.removeChild(row);

 		// Decrement the row number counter
 		rowNum--;
 		// Update row numbers after removal
 		updateRowNumbers();
 	}

 	// Function to update the quantity and calculate the amount
 	function updateQty(input) {
 		var row = input.parentNode.parentNode;
 		var price = parseFloat(row.cells[2].textContent);
 		var qty = parseInt(input.value);

 		// Validate that qty does not exceed maxQty
 		if (qty > maxQty) {
 			input.value = maxQty; // Set input value to maxQty
 			qty = maxQty; // Update qty to maxQty
 		}

 		var amount = (price * qty).toFixed(2);
 		row.cells[4].textContent = formatCurrency(amount); // Update the amount cell
 	}

 	// Function to update row numbers after a row is removed
 	function updateRowNumbers() {
 		var table = document.getElementById('cartTable');
 		for (var i = 1; i < table.rows.length; i++) {
 			table.rows[i].cells[0].textContent = i;
 		}
 	}

 	// Add event listeners to each product card
 	var productCards = document.querySelectorAll('.card');
 	productCards.forEach(function(card) {
 		card.addEventListener('click', function() {
 			var productName = card.querySelector('.card-title').textContent;
 			var price = parseFloat(card.querySelector('#txtPrice').textContent.split(':')[1].trim());

 			// Extract the quantity and unit name from the card's HTML
 			var qtyStr = card.querySelector('#txtQty').textContent;
 			var qtyStockInCard = parseInt(qtyStr.match(/\d+/)); // Extract the numerical quantity
 			var qty = 1; // Default quantity is 1

 			var maxQty = qtyStockInCard; // Maximum quantity available for a product based on the card's quantity
 			addProductToTable(productName, price, qty, maxQty);
 		});
 	});


 	// Function to check if the cart table is empty
 	function isCartEmpty() {
 		var table = document.getElementById('cartTable');
 		return table.rows.length <= 1; // One row is the header, so an empty table has 1 row.
 	}

 	// Event listener for the "Payment" button
 	document.querySelector('button[name="btnSave"]').addEventListener('click', function() {
 		if (isCartEmpty()) {
 			// Show the warning modal if the cart is empty
 			var warningModal = document.getElementById('warning_exception');
 			var modalMessage = document.getElementById('modalMessage');
 			modalMessage.textContent = 'សូមបញ្ចូល ផលិតផលមុននឹងបន្តទៅកាន់ការបង់ប្រាក់';
 			var modal = new bootstrap.Modal(warningModal);
 			modal.show();
 		} else {
 			// Proceed with the payment logic here
 			// You can add your payment logic or redirect to a payment page.
 		}
 	});

 	// Event listener for the "Save" button
 	document.getElementById('btnSave').addEventListener('click', function() {
 		// Show the modal
 		var checkoutModal = new bootstrap.Modal(document.getElementById('paymentModal'));
 		checkoutModal.show();
 	});

 	document.querySelector('#checkoutBtn').addEventListener('click', function() {
 		// Get values from the input fields
 		const paymentMethodId = document.querySelector('#paymentMethod').value;
 		const cashReceived = parseFloat(document.querySelector('#cashReceived').value);
 		const totalAmount = parseFloat(document.querySelector('#totalAmount').value);

 		// Validate cash received
 		if (isNaN(cashReceived) || cashReceived < totalAmount) {
 			// Display an error message if cash received is insufficient
 			alert('Cash received is insufficient. Please enter a valid amount.');
 			return;
 		}

 		// Perform the checkout process
 		// You should use AJAX or a server-side script to save the data to the database using a transaction
 		// After saving the sale, decrease stock quantities accordingly

 		// Close the modal
 		var checkoutModal = new bootstrap.Modal(document.getElementById('paymentModal'));
 		checkoutModal.hide();
 	});

 	// Event listener to show the modal
 	document.getElementById('paymentButton').addEventListener('click', function() {
 		var paymentModal = new bootstrap.Modal(document.querySelector('.modal-dialog.modal-dialog-scrollable'));
 		paymentModal.show();
 	});
 </script>