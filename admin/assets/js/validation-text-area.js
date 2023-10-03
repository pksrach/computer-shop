// Create a function to handle textarea input
function handleTextareaInput(textareaId) {
  var textarea = document.getElementById(textareaId);

  if (!textarea) {
    console.error("Textarea element not found for ID: " + textareaId);
    return;
  }

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
}

// Call the function for both textarea elements
handleTextareaInput("tar_desc");
handleTextareaInput("txt_description");
