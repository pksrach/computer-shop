$(document).ready(function () {
  // Function to validate and format the input as a numeric value
  $("#txt_product_price").on("input", function () {
    var inputValue = $(this).val();

    // Replace ',' with '.' for decimal point
    inputValue = inputValue.replace(",", ".");

    // Use a regular expression to match numeric values (integers or floats)
    if (/^-?\d*\.?\d*$/.test(inputValue)) {
      // Set the formatted value back to the input
      $(this).val(inputValue);
    } else {
      // Clear the input if it doesn't match the numeric pattern
      $(this).val("");
    }
  });
});

$(document).ready(function () {
  // Function to validate and format the input as a numeric value
  $("#txt_price").on("input", function () {
    var inputValue = $(this).val();

    // Replace ',' with '.' for decimal point
    inputValue = inputValue.replace(",", ".");

    // Use a regular expression to match numeric values (integers or floats)
    if (/^-?\d*\.?\d*$/.test(inputValue)) {
      // Set the formatted value back to the input
      $(this).val(inputValue);
    } else {
      // Clear the input if it doesn't match the numeric pattern
      $(this).val("");
    }
  });
});
