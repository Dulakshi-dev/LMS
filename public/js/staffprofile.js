// function updateProfileDetails() {
//     let valid = true;
//     const fields = ["fname", "lname", "phone", "address", "nic", "district", "city"];
//     fields.forEach(field => {
//       const value = document.getElementById(field).value.trim();
//       const errorSpan = document.getElementById(`${field}error`);
//       errorSpan.textContent = value === "" ? `${field} is required.` : "";
//       if (value === "") valid = false;
//     });
//     if (valid) alert("Profile updated successfully!");
//   }

//   function showProfilePreview() {
//     const file = document.getElementById("uploadprofimg").files[0];
//     if (file) {
//       const reader = new FileReader();
//       reader.onload = e => document.getElementById("profileimg").src = e.target.result;
//       reader.readAsDataURL(file);
//     }
//   }


//   function showAlert(title, message, type) {
//     return Swal.fire({
//         title: title,
//         text: message,
//         icon: type, // 'success', 'error', 'warning', 'info', 'question'
//         confirmButtonText: 'OK'
//     });
// }
