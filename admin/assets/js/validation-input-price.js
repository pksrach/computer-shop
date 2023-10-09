$(document).ready(function () {
  // Function to validate and format the input as a numeric value
  function handleNumericInput(inputSelector) {
    $(inputSelector).on("input", function () {
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
  }

  // Call the function for each input element with a different ID
  handleNumericInput("#txt_product_price");
  handleNumericInput("#txt_price");
  handleNumericInput("#txt_import_cost");
});
