document.getElementById("adminLoginForm").addEventListener("submit", function (event) {
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMessage = document.getElementById("error-message");

    errorMessage.textContent = ""; // Clear previous error messages

    if (email === "" || password === "") {
        errorMessage.textContent = "All fields are required!";
        event.preventDefault(); // Stop form submission
    } else if (!email.includes("@")) {
        errorMessage.textContent = "Invalid email format!";
        event.preventDefault();
    } else if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters!";
        event.preventDefault();
    }
});
