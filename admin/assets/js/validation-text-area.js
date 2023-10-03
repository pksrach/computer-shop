// Get the textarea element by its ID
var textarea = document.getElementById("tar_desc");

// Add an input event listener to the textarea
textarea.addEventListener("input", function () {
  // Get the current value of the textarea
  var value = textarea.value;

  // Check if the length of the value exceeds 255 characters
  if (value.length > 255) {
    // If it exceeds 255 characters, truncate it to 255 characters
    textarea.value = value.slice(0, 255);

    // You can also provide a user-friendly error message
    alert("Description cannot exceed 255 characters.");
  }
});

// Get the textarea element by its ID
var textarea2 = document.getElementById("txt_description");

// Add an input event listener to the textarea
textarea2.addEventListener("input", function () {
  // Get the current value of the textarea
  var value = textarea.value;

  // Check if the length of the value exceeds 255 characters
  if (value.length > 255) {
    // If it exceeds 255 characters, truncate it to 255 characters
    textarea.value = value.slice(0, 255);

    // You can also provide a user-friendly error message
    alert("Description cannot exceed 255 characters.");
  }
});
