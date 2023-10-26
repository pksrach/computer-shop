 <div class="app-wrapper">

 	<div class="app-content pt-3 p-md-3 p-lg-4">
 		<div class="container-xl">
 			<!-- Header -->
 			<div class="row g-3 mb-2 align-items-center justify-content-between">
 				<div class="col-auto">
 					<h1 class="app-page-title mb-0">ផ្ទាំងលក់</h1>
 				</div>

 				<!-- Search -->
 				<div class="col-auto">
 					<div class="page-utilities">
 						<form method="get" class="table-search-form row gx-1 align-items-center">
 							<input type="hidden" name="ch" value="cashier" />
 							<div class="col-auto">
 								<select class="form-select w-auto" name="txtCustomer" id="txtCustomer">
 									<option value="1">អតិថិជនទូទៅ</option>
 									<?php
										$sql = mysqli_query($conn, "SELECT *, concat(`name`,'-',phone_number) as customer FROM tbl_customer where name != 'General'");
										while ($row = mysqli_fetch_assoc($sql)) {
											echo "<option value='" . $row['id'] . "'>" . $row['customer'] . "</option>";
										}
										?>
 								</select>
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
				p.id as product_id,
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
				// mysqli_close($conn);
				?>

 			<?php
				if (isset($_POST['checkoutBtn'])) {
					if (isset($_SESSION['customer_id'])) {
						$customer_id = $_SESSION['customer_id'];
						echo "Selected Customer ID: " . $customer_id;
					} else {
						$customer_id = 1;
					}

					$total = $_POST['txtTotalAmount'];
					$discount = $_POST['txtDiscount'] ? $_POST['txtDiscount'] : 0;
					$grandTotal = $_POST['txtGrandTotal'];
					$cashReceived = $_POST['txtCashReceived'] ? $_POST['txtCashReceived'] : 0;
					$paymentMethod = $_POST['txtPaymentMethod'];
					$people_id = intval($_SESSION['user_people_id']);
					$sale_date = date('Y-m-d H:i:s');

					// Convert to float
					$total = floatval($total);
					$discount = intval($discount);
					$grandTotal = floatval($grandTotal);
					$cashReceived = floatval($cashReceived);
					$paymentMethod = $_POST['txtPaymentMethod'];
					$customer_id = intval($customer_id);

					// Check if the Cash Received field is empty
					if ($cashReceived == '') {
						$cashReceived = 0;
					}
					if ($discount == '') {
						$discount = 0;
					}

					// Console log
					echo "<script>console.log('Total: " . $total . "');</script>";
					echo "<script>console.log('Discount: " . $discount . "');</script>";
					echo "<script>console.log('Grand Total: " . $grandTotal . "');</script>";
					echo "<script>console.log('Cash Received: " . $cashReceived . "');</script>";
					echo "<script>console.log('customer_id: " . $customer_id . "');</script>";
					echo "<script>console.log('Payment Method: " . $paymentMethod . "');</script>";

					// validate if $cashReceived is less than $grandTotal
					if ($cashReceived < $grandTotal) {
						// Show the warning modal
						echo "<script>alert('ប្រាក់ទទូលពីអតិថិជនត្រូវធំជាង ឬស្មើតម្លៃសរុប');</script>";
					} else {
						// Retrieve cart data from the session
						if (isset($_SESSION['shoppingCart'])) {
							$cartData = $_SESSION['shoppingCart'];

							// Use transaction to ensure that all queries are executed
							mysqli_begin_transaction($conn);

							// Insert the data into the database
							$sqlInsertSale = "INSERT INTO tbl_sales (sale_date, total, discount, received, people_id, customer_id, payment_type) 
							VALUES (?, ?, ?, ?, ?, ?, ?);";

							$stmt = mysqli_prepare($conn, $sqlInsertSale);

							mysqli_stmt_bind_param($stmt, 'sdidiis', $sale_date, $total, $discount, $cashReceived, $people_id, $customer_id, $paymentMethod);
							if (!mysqli_stmt_execute($stmt)) {
								mysqli_rollback($conn);
								echo "<script>alert('Error executing import query: " . mysqli_stmt_error($stmt) . "');</script>";
								unset($_SESSION['shoppingCart']);
								throw new Exception("Error executing import query: " . mysqli_stmt_error($stmt));
							}

							$sale_id = mysqli_insert_id($conn); // Get the sale_id of the inserted row
							echo "<script>console.log('Sale ID: " . $sale_id . "');</script>";

							// Loop through the cart items and insert them into tbl_sale_details
							foreach ($cartData as $cartItem) {
								$productName = $cartItem['productName'];
								$product_id = $cartItem['productId'];
								echo "<script>console.log('Product: " . $product_id . "|" . $productName . "');</script>";

								$sale_qty = $cartItem['qty'];
								$price = $cartItem['price'];

								// Insert this sale detail into tbl_sale_details
								$sqlInsertSaleDetails = "INSERT INTO tbl_sale_details (sale_id, product_id, sale_qty, price) VALUES (?, ?, ?, ?)";
								$stmt = mysqli_prepare($conn, $sqlInsertSaleDetails);



								// Bind parameters and execute the query
								mysqli_stmt_bind_param($stmt, 'iiid', $sale_id, $product_id, $sale_qty, $price);
								if (!mysqli_stmt_execute($stmt)) {
									error_log("Error executing sale details query: " . mysqli_stmt_error($stmt));
									error_log("Problematic sale_id: $sale_id");

									unset($_SESSION['shoppingCart']);
									mysqli_rollback($conn); // Rollback the transaction in case of an error
									throw new Exception("Error executing sale details query: " . mysqli_stmt_error($stmt));
								}
							}
						}
						// Close the statement
						mysqli_stmt_close($stmt);

						// Commit transaction
						mysqli_commit($conn);
					}
				}
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
 						<input type="hidden" class="product-id" value="<?php echo $product['product_id']; ?>">
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

 		<!-- List Dropdown Customer -->
 		<div class="row g-3">

 		</div>

 		<div style="height: 100px;">
 			<div class="d-flex justify-content-center align-items-center h-100"> <!-- Use d-flex and justify-content-center classes -->
 				<button type="button" id="paymentButton" name="btnSave" class="col-12 btn btn-success h-60">
 					<h4>គិតប្រាក់</h4>
 				</button>
 			</div>
 		</div>
 	</div><!--//app-content-->

 </div><!--//app-wrapper-->

 <!-- ==================================================================================================== -->

 <!-- Modal Checkout -->
 <div class="modal fade" id="checkout_modal" tabindex="-1" aria-labelledby="checkout_modal" aria-hidden="true">
 	<div class="modal-dialog">
 		<div class="modal-content">
 			<form method="post" enctype="multipart/form-data" class="row g-3">
 				<div class="modal-header">
 					<h5 class="modal-title">ផ្ទាំងគិតប្រាក់</h5>
 					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 				</div>
 				<div class="modal-body">
 					<!-- Total Amount Display -->
 					<div class="mb-3">
 						<label for="totalAmount" class="form-label">Total Amount</label>
 						<input type="text" class="form-control" id="totalAmount" name="txtTotalAmount" readonly>
 					</div>

 					<!-- Discount Input -->
 					<div class="mb-2">
 						<label for="discount" class="form-label">Discount</label>
 						<input type="number" class="form-control" id="discountInput" name="txtDiscount" placeholder="Enter discount %">
 					</div>

 					<!-- Grand Total Display -->
 					<div class="mb-3">
 						<label for="grandTotal" class="form-label">Grand Total</label>
 						<input type="text" class="form-control" id="grandTotal" name="txtGrandTotal" readonly>
 					</div>

 					<!-- Cash Received Input -->
 					<div class="mb-3">
 						<label for="cashReceivedtt" class="form-label">Cash Received<span style="color: red;">*</span></label>
 						<input type="number" class="form-control" id="cashReceived" name="txtCashReceived" placeholder="Enter cash received">
 					</div>

 					<!-- Payment Method Dropdown -->
 					<div class="mb-3">
 						<label for="paymentMethod" class="form-label">ប្រភេទទូទាត់<span style="color: red;">*</span></label>
 						<select class="form-select" id="paymentMethod" name="txtPaymentMethod">
 							<option selected value="Cash">Cash</option>
 							<option value="Bank">Bank</option>
 						</select>
 					</div>
 				</div>
 				<div class="modal-footer">
 					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
 					<button type="submit" class="btn btn-primary" name="checkoutBtn" id="checkoutBtn" onclick="checkoutButtonClick()">ទូទាត់</button>
 				</div>
 			</form>
 		</div>
 	</div>
 </div>


 <!-- Modal Warning -->
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

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <script>
 	// Initialize a counter for row numbers
 	var rowNum = 1;

 	var shoppingCart = {};

 	// Function to handle the click event for adding a product
 	function addProductToTable(productId, productName, price, qty, maxQty) {
 		var table = document.getElementById('cartTable');
 		var row;

 		// Check if the product is already in the shopping cart
 		if (shoppingCart[productId]) {
 			row = shoppingCart[productId].row;
 			var qtyInput = row.cells[3].querySelector('input');
 			if (qtyInput) { // Check if the input element exists
 				var currentQty = parseInt(qtyInput.value);
 				if (currentQty < maxQty) {
 					qtyInput.value = currentQty + 1;
 					updateQty(qtyInput, maxQty); // Pass maxQty as an argument
 				}
 			}
 		} else {
 			row = table.insertRow();
 			shoppingCart[productId] = {
 				row: row,
 				productName: productName,
 				price: price,
 				qty: qty,
 				maxQty: maxQty
 			}

 			// Insert cells for row number, product name, price, quantity, amount, and a delete button
 			var cell0 = row.insertCell(0);
 			var cell1 = row.insertCell(1);
 			var cell2 = row.insertCell(2);
 			var cell3 = row.insertCell(3);
 			var cell4 = row.insertCell(4);
 			var cell5 = row.insertCell(5);

 			cell0.textContent = productId; // Set the product ID
 			cell1.innerHTML = productName;
 			cell2.innerHTML = formatCurrency(price);
 			cell3.innerHTML = '<input type="number" min="1" max="' + maxQty + '" value="1" oninput="updateQty(this, ' + maxQty + ')">';
 			cell4.textContent = formatCurrency(price.toFixed(2));
 			cell5.innerHTML = '<button class="btn btn-danger" type="button" onclick="removeProduct(this)"><i class="fas fa-eraser"></button>';

 			rowNum++;

 			// Add product store on session
 			$.ajax({
 				url: "pages/cashier/store_product.php",
 				method: "POST",
 				data: {
 					productId: productId,
 					productName: productName,
 					price: price,
 					qty: qty,
 					maxQty: maxQty
 				},
 				success: function(data) {
 					// Handle the response from the server if needed
 				}
 			});
 		}
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
 		var productId = row.cells[0].textContent;
 		row.parentNode.removeChild(row);

 		// Remove value from shopping cart
 		delete shoppingCart[productId];
 	}

 	// Function to update the quantity and calculate the amount
 	function updateQty(input, maxQty) {
 		var row = input.parentNode.parentNode;
 		var price = parseFloat(row.cells[2].textContent.replace('$', '').replace(',', ''));
 		var qty = parseInt(input.value);

 		// Validate that qty does not exceed maxQty
 		if (qty > maxQty) {
 			input.value = maxQty; // Set input value to maxQty
 			qty = maxQty; // Update qty to maxQty
 		}

 		var amount = (price * qty).toFixed(2); // Calculate the amount
 		row.cells[4].textContent = formatCurrency(amount); // Update the amount cell
 	}

 	// Add event listeners to each product card
 	var productCards = document.querySelectorAll('.card');
 	productCards.forEach(function(card) {
 		card.addEventListener('click', function() {
 			var productId = card.querySelector('.product-id').value;
 			var productName = card.querySelector('.card-title').textContent;
 			var price = parseFloat(card.querySelector('#txtPrice').textContent.split(':')[1].trim());

 			// Extract the quantity and unit name from the card's HTML
 			var qtyStr = card.querySelector('#txtQty').textContent;
 			var qtyStockInCard = parseInt(qtyStr.match(/\d+/)); // Extract the numerical quantity
 			var qty = 1; // Default quantity is 1

 			var maxQty = qtyStockInCard; // Maximum quantity available for a product based on the card's quantity
 			addProductToTable(productId, productName, price, qty, maxQty);
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
 			// Show the payment modal
 			var paymentModal = document.getElementById('checkout_modal');
 			console.log('Showing payment modal');
 			var modal = new bootstrap.Modal(paymentModal);
 			modal.show();
 		}
 	});

 	// Function to calculate and update the total amount
 	var totalAmount = 0;

 	function calculateTotalAmount() {
 		var table = document.getElementById('cartTable');
 		// Reset the total amount to 0
 		totalAmount = 0;

 		// Iterate through the table rows, skipping the header row
 		for (var i = 1; i < table.rows.length; i++) {
 			var amountCell = table.rows[i].cells[4];
 			var amount = parseFloat(amountCell.textContent.replace('$', '').replace(',', '')); // Parse the amount
 			totalAmount += amount;
 		}

 		// Update the "Total Amount" field in the modal
 		document.getElementById('totalAmount').value = formatCurrency(totalAmount.toFixed(2));
 		var discountInput = document.getElementById('discountInput');
 		var grandTotal = document.getElementById('grandTotal').value
 		if (grandTotal == '' || grandTotal == 0) {
 			document.getElementById('grandTotal').value = formatCurrency(totalAmount.toFixed(2));
 		} else if (grandTotal != totalAmount && discountInput.value == '') {
 			document.getElementById('grandTotal').value = formatCurrency(totalAmount.toFixed(2));
 		} else {
 			var calculateDiscount = totalAmount * (discountInput.value / 100);
 			document.getElementById('grandTotal').value = formatCurrency(calculateDiscount.toFixed(2));
 		}
 	}

 	// Add an event listener to the "Payment" or "Save" button
 	document.querySelector('button[name="btnSave"]').addEventListener('click', calculateTotalAmount);
 	document.getElementById('paymentButton').addEventListener('click', calculateTotalAmount);

 	// Select the discount input field
 	var discountInput = document.getElementById('discountInput');
 	var grandTotal = totalAmount;

 	// Add an event listener to the discount input field
 	discountInput.addEventListener('input', function() {
 		// Get the discount value from the input field
 		var discountValue = parseFloat(discountInput.value) || 0;

 		// Check if the discount value exceeds 100
 		if (discountValue > 100) {
 			// If it does, set it to 100
 			discountValue = 100;
 			discountInput.value = discountValue;
 		}

 		// Get the total amount value
 		var _totalAmount = totalAmount || 0;

 		// Get the discount value from the input field
 		var discountValue = parseFloat(discountInput.value) || 0;

 		// Calculate the Grand Total
 		grandTotal = _totalAmount - (_totalAmount * (discountValue / 100));

 		// Update the Grand Total field
 		document.getElementById('grandTotal').value = formatCurrency(grandTotal.toFixed(2)); // Format to two decimal places
 	});

 	var cashReceivedInput = document.getElementById('cashReceived');

 	cashReceivedInput.addEventListener('input', function() {
 		var cashReceivedValue = parseFloat(cashReceivedInput.value) || 0;
 		var grandTotalValue = parseFloat(grandTotal) || 0;

 		if (cashReceivedValue < grandTotalValue) {
 			// If Cash Received is less than Grand Total, change the input field's border color to red
 			cashReceivedInput.style.borderColor = 'red';
 		} else {
 			// Otherwise, reset the input field's border color
 			cashReceivedInput.style.borderColor = '';

 			// Calculate the change
 			var change = cashReceivedValue - grandTotalValue;

 		}
 	});

 	function isShoppingCartEmpty() {
 		return Object.keys(shoppingCart).length === 0;
 	}
 	if (isShoppingCartEmpty()) {
 		console.log("Shopping cart is empty.");
 	} else {
 		console.log("Shopping cart has data.");
 	}

 	function checkoutButtonClick() {
 		console.log('Checkout button clicked'); // Check if this message appears in the console

 		// Get values from input fields
 		var totalAmount = document.getElementById('totalAmount').value;
 		var discountInput = document.getElementById('discountInput').value;
 		var grandTotal = document.getElementById('grandTotal').value;
 		var cashReceived = document.getElementById('cashReceived').value;
 		var paymentMethod = document.getElementById('paymentMethod').value;

 		var selectedCustomer = document.getElementById('txtCustomer');
 		var customerId = selectedCustomer.value;
 		console.log('Customer ID: ' + customerId);

 		totalAmount = totalAmount.replace('$', '').replace(',', '');
 		grandTotal = parseFloat(grandTotal.replace('$', '').replace(',', ''));

 		// Check if discount input is empty, set the default value to 0
 		if (discountInput == '') {
 			discountInput = 0;
 		}

 		// Check if the Cash Received field is empty
 		if (cashReceived == '') {
 			cashReceived = 0;
 		}

 		// Set data to id fields
 		document.getElementById('totalAmount').value = totalAmount;
 		document.getElementById('grandTotal').value = grandTotal;

 		var cartTableData = [];
 		// Iterate through the keys (product names) in the shoppingCart object
 		for (var productId in shoppingCart) {
 			if (shoppingCart.hasOwnProperty(productId)) {
 				var row = shoppingCart[productId].row;
 				var cells = row.getElementsByTagName('td');
 				var qty = cells[3].getElementsByTagName('input')[0].value;
 				var price = cells[2].textContent.replace('$', '').replace(',', '');

 				cartTableData.push({
 					product_id: productId, // Use the product ID
 					qty: qty,
 					price: price
 				});
 			}
 		}

 		// Insert data into the database using AJAX
 		$.ajax({
 			url: "pages/cashier/cashier.php",
 			method: "POST",
 			data: {
 				totalAmount: totalAmount,
 				discountInput: discountInput,
 				grandTotal: grandTotal,
 				cashReceived: cashReceived,
 				paymentMethod: paymentMethod,
 				customer_id: customer_id,
 				cartTable: JSON.stringify(cartTableData),
 			}
 		});

 	}

 	function showWarningModal() {
 		var warningModal = document.getElementById('warning_exception');
 		var modalMessage = document.getElementById('modalMessage');
 		modalMessage.textContent = 'ប្រាក់ទទួលពីអតិថិជន ត្រូវធំជាងឬស្មើនឹងតម្លៃសរុបរបស់ការលក់';
 		var modal = new bootstrap.Modal(warningModal);
 		modal.show();
 	}

 	function showSuccessModal() {
 		var successModal = document.getElementById('succes_modal');
 		var modal = new bootstrap.Modal(successModal);
 		modal.show();
 	}

 	function clearSession() {
 		<?php unset($_SESSION['shoppingCart']); ?>
 	}

 	function reloadPage() {
 		location.reload();
 	}

 	document.getElementById('paymentButton').addEventListener('click', checkoutButtonClick);
 </script>