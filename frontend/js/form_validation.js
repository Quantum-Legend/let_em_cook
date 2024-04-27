// Function to validate a form
function validateForm(formId, actionUrl) {
    var form = document.getElementById(formId);
    var inputs = form.querySelectorAll("input, select, textarea");

    var isValid = true;

    // Loop through each form element and validate
    inputs.forEach(function(input) {
        // Check if the element is required
        if (input.hasAttribute("required")) {
            // Check if the element is empty
            if (input.value.trim() === "") {
                isValid = false;
                showError(input, "This field is required.");
            } else {
                hideError(input);
            }
        }

        // Check if password is retyped correctly
        if (input.id === "confirm-password") {
            var password = form.querySelector("#create-password").value;
            var confirmPassword = input.value;
            if (confirmPassword !== password) {
                isValid = false;
                showError(input, "Passwords do not match.");
            } else {
                hideError(input);
            }
        }
    });

    if (isValid) {
        var formData = new FormData(form);

        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", actionUrl, true); // Use actionUrl parameter
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        // Set up the callback function to handle the response
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Successful response
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Authentication successful, redirect to browse_recipes.html
                        window.location.href = "../frontend/browse_recipes.html";
                    } else {
                        // Authentication failed, display server-side error message
                        showError(form.querySelector("#username_or_email"), response.message);
                    }
                } else {
                    // Error handling for non-200 status codes
                    showError(form.querySelector("#username_or_email"), "An error occurred. Please try again later.");
                }
            }
        };

        // Send the form data
        xhr.send(formData);
    }

    // Prevent the default form submission
    return false;
}


// Function to show error message for a form element
function showError(input, message) {
    // Get the parent element of the input
    var parent = input.parentElement;

    // Check if an error message element already exists
    var errorElement = parent.querySelector(".error-message");
    if (!errorElement) {
        // Create a new error message element
        errorElement = document.createElement("div");
        errorElement.className = "error-message";
        parent.appendChild(errorElement);
    }

    // Set the error message
    errorElement.textContent = message;
}

// Function to hide error message for a form element
function hideError(input) {
    // Get the parent element of the input
    var parent = input.parentElement;

    // Check if an error message element exists
    var errorElement = parent.querySelector(".error-message");
    if (errorElement) {
        // Remove the error message
        parent.removeChild(errorElement);
    }
}

// Add event listener to the form submission event
document.addEventListener("submit", function(event) {
    // Check if the target of the event is a form
    if (event.target.tagName.toLowerCase() === "form") {
        // Prevent form submission if validation fails
        if (!validateForm(event.target.id)) {
            event.preventDefault();
        }
    }
});