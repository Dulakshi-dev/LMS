function updateProfileDetails() {
    let valid = true;
    const fields = ["fname", "lname", "phone", "address", "nic", "district", "city"];
    fields.forEach(field => {
      const value = document.getElementById(field).value.trim();
      const errorSpan = document.getElementById(`${field}error`);
      errorSpan.textContent = value === "" ? `${field} is required.` : "";
      if (value === "") valid = false;
    });
    if (valid) alert("Profile updated successfully!");
  }

  function showProfilePreview() {
    const file = document.getElementById("uploadprofimg").files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => document.getElementById("profileimg").src = e.target.result;
      reader.readAsDataURL(file);
    }
  }

  function goToChangePassword() {
    document.getElementById("box1").classList.add("d-none");
    document.getElementById("box2").classList.remove("d-none");
  }

  function validateCurrentPassword() {
    const password = document.getElementById("current-password").value.trim();
    if (!password) document.getElementById("errormsg-current").textContent = "Current password is required.";
    else {
      document.getElementById("box2").classList.add("d-none");
      document.getElementById("box3").classList.remove("d-none");
    }
  }

  function saveNewPassword() {
    const newPassword = document.getElementById("new-password").value.trim();
    const confirmPassword = document.getElementById("confirm-password").value.trim();
    if (newPassword.length < 8 || newPassword.length > 15) {
      document.getElementById("errormsg-new").textContent = "Password must be 8-15 characters.";
    } else if (newPassword !== confirmPassword) {
      document.getElementById("errormsg-new").textContent = "Passwords do not match.";
    } else {
      alert("Password changed successfully!");
      document.getElementById("box1").classList.remove("d-none");
      document.getElementById("box3").classList.add("d-none");
    }
  }

  function goBack() {
    document.getElementById("box2").classList.add("d-none");
    document.getElementById("box1").classList.remove("d-none");
  }

  function goBackToCurrent() {
    document.getElementById("box3").classList.add("d-none");
    document.getElementById("box2").classList.remove("d-none");
  }