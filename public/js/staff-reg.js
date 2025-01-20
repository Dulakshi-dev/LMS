document.getElementById("registrationForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Clear previous error messages
    document.querySelectorAll("span").forEach(span => span.textContent = "");

    let isValid = true;

    // Validate First Name
    const firstName = document.getElementById("firstName").value.trim();
    if (firstName === "") {
        document.getElementById("firstNameError").textContent = "First Name is required.";
        isValid = false;
    }

    // Validate Last Name
    const lastName = document.getElementById("lastName").value.trim();
    if (lastName === "") {
        document.getElementById("lastNameError").textContent = "Last Name is required.";
        isValid = false;
    }

    // Validate Address
    const address = document.getElementById("address").value.trim();
    if (address === "") {
        document.getElementById("addressError").textContent = "Address is required.";
        isValid = false;
    }

    // Validate Phone
    const phone = document.getElementById("phone").value.trim();
    if (!/^\d{10}$/.test(phone)) {
        document.getElementById("phoneError").textContent = "Phone number must be 10 digits.";
        isValid = false;
    }

    // Validate Email
    const email = document.getElementById("email").value.trim();
    if (!/^\S+@\S+\.\S+$/.test(email)) {
        document.getElementById("emailError").textContent = "Invalid email format.";
        isValid = false;
    }

    // Validate NIC
    const nic = document.getElementById("nic").value.trim();
    if (nic === "") {
        document.getElementById("nicError").textContent = "NIC is required.";
        isValid = false;
    }

    // Validate Role
    const roleSelected = document.querySelector('input[name="role"]:checked');
    if (!roleSelected) {
        document.getElementById("roleError").textContent = "Please select a role.";
        isValid = false;
    }

    // Validate Password
    const password = document.getElementById("password").value.trim();
    const cpassword = document.getElementById("cpassword").value.trim();
    if (password.length < 6) {
        document.getElementById("passwordError").textContent = "Password must be at least 6 characters.";
        isValid = false;
    } else if (password !== cpassword) {
        document.getElementById("cpasswordError").textContent = "Passwords do not match.";
        isValid = false;
    }

    if (isValid) {
        // Submit form if valid
        this.submit();
    }
});