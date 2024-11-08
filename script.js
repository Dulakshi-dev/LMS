// Function to validate the password pattern
function isValidPassword(password) {
    // Regular expression for password validation
    var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/;
    return pattern.test(password);
}
function staffLogin() {

    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();

    document.getElementById('usernameError').innerText = '';
    document.getElementById('passwordError').innerText = '';

    if (username === '') {
        document.getElementById('usernameError').innerText = 'Username is required.';
    } else if (password === '') {
        document.getElementById('passwordError').innerText = 'Password is required.';
    } else if (!isValidPassword(password)) {
        document.getElementById('passwordError').innerText = 'Password must be at least 5 characters long, including at least one uppercase letter, one lowercase letter, and one number.';
    }
    else {
        var form = new FormData();
        form.append("username", username);
        form.append("password", password);

        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                var resp = req.responseText;

                if (resp.trim() === "success") {
                    alert("success");
                    //window.location();

                } else {
                    document.getElementById("errormsg").innerHTML = resp;
                    document.getElementById("errormsgdiv").classList.remove("d-none");
                }
            }
        }

        req.open("POST", "staff-login-process.php", true);
        req.send(form);
    }
}

function memberLogin() {

    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var rememberMe = document.getElementById("rememberme");

    document.getElementById('usernameError').innerText = '';
    document.getElementById('passwordError').innerText = '';

    if (username === '') {
        document.getElementById('usernameError').innerText = 'Username is required.';
    } else if (password === '') {
        document.getElementById('passwordError').innerText = 'Password is required.';
    }
    else {
        var form = new FormData();
        form.append("username", username);
        form.append("password", password);
        form.append("rememberme", rememberMe.checked);

        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                var resp = req.responseText;
                if (resp.trim() === "success") {
                    window.location = "dashboard.php";
                } else {
                    document.getElementById("errormsg").innerHTML = resp;
                    document.getElementById("errormsgdiv").classList.remove("d-none");
                }
            }
        }
        req.open("POST", "member-login-process.php", true);
        req.send(form);
    }
}


function forgotPassword() {
    var email = document.getElementById('email').value;
    var req = new XMLHttpRequest();

    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {

            var resp = req.responseText;
            alert(resp);
        }
    }
    req.open("GET", "forgot-password-process.php?email=" + email, true);
    req.send();

}

function resetPassword() {

    var pw = document.getElementById('pw').value.trim();
    var pwError = document.getElementById('pwError');

    var cpw = document.getElementById('cpw').value.trim();
    var cpwError = document.getElementById('cpwError');

    pwError.innerText = '';
    cpwError.innerText = '';

    if (pw === '') {
        pwError.innerText = 'Password is required.';
    } else if (cpw === '') {
        cpwError.innerText = 'Password confirmation is required.';
    } else if (pw !== cpw) {
        pwError.innerText = 'Passwords do not match.';
    } else if (!isValidPassword(pw)) {
        pwError.innerText = 'Password must be at least 5 characters long, including at least one uppercase letter, one lowercase letter, and one number.';
    }
    else {
        var pw = document.getElementById("pw").value;
        var cpw = document.getElementById("cpw").value;
        var vcode = document.getElementById("vcode").innerHTML;

        var form = new FormData();
        form.append("pw", pw);
        form.append("cpw", cpw);
        form.append("vcode", vcode);

        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                var resp = req.responseText;
                alert(resp);
                window.location = "member-login.php";
            }
        }
        req.open("POST", "reset-password-process.php", true);
        req.send(form);
    }
}

//register start 
function box1() {
    var memId = document.getElementById("membershipID").value;
    var nic = document.getElementById("NICNumber").value;
    var memIdPattern = /^U-\d{4}-\d{4}$/; // Adjust this pattern to match your required ID format

    if (memId === "" || nic === "") {

        if (memId === "") {
            document.getElementById("memerror").innerText = "Please enter Membership ID";
        } else {
            document.getElementById("memerror").innerText = "";
        }

        if (nic === "") {
            document.getElementById("nicnumerror").innerText = "Please enter NIC Number";
        } else {
            document.getElementById("nicnumerror").innerText = "";
        }

    } else if (!memIdPattern.test(memId)) {

        document.getElementById("memerror").innerText = "Invalid Membership ID format.\n Expected format: U-XXXX-XXXX";
    } else {

        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {

            if (req.readyState == 4 && req.status == 200) {

                var resp = req.responseText;

                if (resp.trim() === "success") {

                    document.getElementById("memerror").innerText = "";
                    document.getElementById("Box1").classList.add("d-none");
                    document.getElementById("Box2").classList.remove("d-none");
                } else {

                    document.getElementById("validationerror").innerText = resp;
                }

            }
        }

        req.open("GET", "validation-process.php?memberID=" + memId + "&nic=" + nic, true);
        req.send();
    }
}

function box2() {
    var phonePattern = /^(07\d{8}|(\+94)7\d{8})$/; // Validates phone numbers starting with '07' or '+947' followed by 8 digits

    var address = document.getElementById("address").value;
    var phoneNumber = document.getElementById("phoneNumber").value;

    if (address === "") {
        document.getElementById("Addresserror").innerText = "Please enter Address";
    }
    else if (phoneNumber === "") {
        document.getElementById("Addresserror").innerText = "";
        document.getElementById("Pnumerror").innerText = "Please enter Phone Number";
    } else if (!phonePattern.test(phoneNumber)) { // Validate phone number
        document.getElementById("Pnumerror").innerText = "Invalid phone number. It must be 10 digits starting with '07' or 11 digits starting with '+947'.";
    } else {
        document.getElementById("Box2").classList.add("d-none");
        document.getElementById("Box3").classList.remove("d-none");
    }
}

function box3() {
    alert("ll");
    var email = document.getElementById("email").value;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    if (email === "") {
        document.getElementById("Emailerror").innerText = "Please enter email address";
    } else if (!emailPattern.test(email)) {
        document.getElementById("Emailerror").innerText = "Invalid Email";

    } else {
        document.getElementById("Emailerror").innerText = "";
        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {

            if (req.readyState == 4 && req.status == 200) {

                var resp = req.responseText;
                alert(resp);
                if (resp.trim() === "Check the email for otp") {
                    document.getElementById("Box3").classList.add("d-none");
                    document.getElementById("Box4").classList.remove("d-none");
                }

            }
        }

        req.open("GET", "generate-otp.php?email=" + email, true);
        req.send();


    }
}

function box4() {
    var otp1 = document.getElementById("otp1").value;
    var otp2 = document.getElementById("otp2").value;
    var otp3 = document.getElementById("otp3").value;
    var otp4 = document.getElementById("otp4").value;
    var otp5 = document.getElementById("otp5").value;
    var otp6 = document.getElementById("otp6").value;

    var userotp = otp1 + otp2 + otp3 + otp4 + otp5 + otp6;

    var form = new FormData();
    form.append("userotp", userotp);

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var resp = req.responseText;
            if (resp === 'success') {

                document.getElementById("Box4").classList.add("d-none");
                document.getElementById("Box5").classList.remove("d-none");
            }
        }
    }
    req.open("POST", "otp-varification.php", true);
    req.send(form);

}

function register() {
    var memId = document.getElementById("membershipID").value;
    var nic = document.getElementById("NICNumber").value;
    var address = document.getElementById("address").value;
    var phoneNumber = document.getElementById("phoneNumber").value;
    var email = document.getElementById("email").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var password = document.getElementById("password").value;
    var cpassword = document.getElementById("cpassword").value;

    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/; // At least one uppercase letter, one lowercase letter, one digit, and minimum 5 characters

    if (password === "") {
        document.getElementById("perror").innerText = "Please Enter the password";
    }// Validate password
    else if (!passwordPattern.test(password)) {
        document.getElementById("perror").innerText = "Invalid password. It must be at least 5 characters long, and include at least one uppercase letter, one lowercase letter, and one number.";
    } else if (password != cpassword) {
        document.getElementById("cperror").innerText = "Password does not match";
        document.getElementById("perror").innerText = "";

    } else {
        document.getElementById("cperror").innerText = "";

        var form = new FormData();
        form.append("memID", memId);
        form.append("nic", nic);
        form.append("address", address);
        form.append("phoneNumber", phoneNumber);
        form.append("email", email);
        form.append("fname", fname);
        form.append("lname", lname);
        form.append("password", password);

        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                var resp = req.responseText.trim();

                if (resp === "success") {
                    showAlert("Success", "Successfully Registered", "success").then(() => {
                        // Redirect to member login page after the alert is closed
                        window.location.href = "member-login.php";
                    });
                } else {
                    // Display error messages returned by the server
                    showAlert("Error", resp, "error");
                }
            }
        };
        req.open("POST", "register-process.php", true);
        req.send(form);
    }
}



function back1() {
    document.getElementById("Box2").classList.add("d-none");
    document.getElementById("Box1").classList.remove("d-none");
}
function back2() {
    document.getElementById("Box3").classList.add("d-none");
    document.getElementById("Box2").classList.remove("d-none");
}
function back3() {
    document.getElementById("Box4").classList.add("d-none");
    document.getElementById("Box3").classList.remove("d-none");
}
//register end

//dash-board header script

const prof = document.getElementById("prof");
const signup = document.getElementById('signup');

// Add event listener to toggle dropdown visibility
prof.addEventListener('click', function (event) {
    event.preventDefault();
    if (signup.style.display === "none") {
        signup.style.display = "block";
    } else {
        signup.style.display = "none";
    }
});

//dash_board toggler js

const tog = document.getElementById("tog");
const sidepanel = document.getElementById("sidepanel");
const style = window.getComputedStyle(sidepanel);

tog.addEventListener("click", function (event) {
    if (sidepanel.style.display === "none") {
        sidepanel.style.display = "block";
    }
    else {
        sidepanel.style.display = "none";
    }
});

// showAlert function for alerts
function showAlert(title, message, type) {
    return Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

//dash_board_change_password
function dashboard_change_password(event) {

    if (event) {
        event.preventDefault();
    }
    const box1 = document.getElementById("box1");
    const box2 = document.getElementById("box2");

    if (box1.style.display === "none") {
        box1.style.display = "block";
        box2.style.display = "none";
    } else {
        box1.style.display = "none";
        box2.style.display = "block";
    }
}

function loadUserData(id) {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var resp = req.responseText;
            var data = JSON.parse(resp);


            document.getElementById("membership-id").value = data.member_id;
            document.getElementById("first-name").value = data.fname;
            document.getElementById("last-name").value = data.lname;
            document.getElementById("email").value = data.email;
            document.getElementById("phone").value = data.mobile;
            document.getElementById("address").value = data.address;
            document.getElementById("nic").value = data.nic;

            var profileImg = data.profile_img && data.profile_img.trim() !== "" ? data.profile_img : 'assets/profimg/user.jpg'; 
 
          document.getElementById("profileimg").src = profileImg; 

        }
    };

    req.open("GET", "get-member-details.php?id=" + id, true);
    req.send();
}

// Function to preview selected image
function previewImage() {
    var imgfile = document.getElementById("uploadimg");
    var profimg = document.getElementById("profileimg");

    if (imgfile.files && imgfile.files[0]) {
        // Generate a URL for the selected file
        var previewUrl = URL.createObjectURL(imgfile.files[0]);
        profimg.src = previewUrl; // Update the profile image preview
        console.log("Preview URL:", previewUrl); // For debugging
    }
}


function updateProfile() {
    var membershipId = document.getElementById("membership-id").value;
    var firstName = document.getElementById("first-name").value;
    var lastName = document.getElementById("last-name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var address = document.getElementById("address").value;
    var nic = document.getElementById("nic").value;
    var imgfile = document.getElementById("uploadimg");

    // Create FormData and append all necessary fields
    var form = new FormData();
    form.append("membership-id", membershipId);
    form.append("first-name", firstName);
    form.append("last-name", lastName);
    form.append("email", email);
    form.append("phone", phone);
    form.append("address", address);
    form.append("nic", nic);

    // If a file is selected, append it to FormData
    if (imgfile.files.length > 0) {
        form.append("img", imgfile.files[0]);
    }

    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var resp = req.responseText.trim();
            if (resp === "success") {
                showAlert("Success", "Profile Updated Successfully!", "success").then(() => {
                    window.location.reload();
                });
            } else {
                showAlert("Error", resp, "error");
            }
        }
    };
    req.open("POST", "update-profile-process.php", true);
    req.send(form);
}


function changepassword(event) {
    // Prevent the default form submission
    if (event) {
        event.preventDefault();
    }

   
    var memberID = document.getElementById("membership-id").value;
    var newPassword = document.getElementById("new-password").value;
    var confirmPassword = document.getElementById("confirm-password").value;

    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/; // At least one uppercase letter, one lowercase letter, one digit, and minimum 5 characters
   

    // Password validation
    if (newPassword == "") {
        document.getElementById("errormsg").innerText = "Please Enter the password";
    } else if (!passwordPattern.test(newPassword)) {
        document.getElementById("errormsg").innerText = "Invalid password. It must be at least 5 characters long, and include at least one uppercase letter, one lowercase letter, and one number.";
    } else if (newPassword != confirmPassword) {
        document.getElementById("errormsg").innerText = "Password does not match";
    } else {
    
        document.getElementById("errormsg").innerText = "";
        var form = new FormData();
        form.append("memberID", memberID);
        form.append("new-password", newPassword);
        form.append("confirm-password", confirmPassword);

        var req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                var resp = req.responseText.trim();
                if (resp === "success") {
                    showAlert("Success","Password Changed", "success").then(() => {
                        window.location.reload();
                    });

                } 
            }
        };

        req.open("POST", "changepassword-process.php", true);
        req.send(form);
    }
}

function loadUsers(page){  
    var req = new XMLHttpRequest(); 
    req.onreadystatechange = function(){ 
        if(req.readyState == 4 && req.status== 200){ 
        
            document.getElementById("content").innerHTML = req.responseText; 
          
        } 
    }; 
    req.open("GET","load-user-process.php?page="+page,true); 
    req.send(); 
} 

function changeUserStatus(id, status){ 
    var req = new XMLHttpRequest(); 
     
   req.onreadystatechange = function(){ 
       if(req.readyState == 4 && req.status== 200){ 
           var resp = req.responseText; 
           if(req.responseText.trim() === "success"){ 
               window.location.reload(); 
           }else{ 
               alert(resp); 
           } 
       } 
   }; 
   req.open("GET","change-user-status-process.php?id="+id+"&s="+status,true); 
   req.send(); 
} 


function searchUsers(){ 
   
    var memberId = document.getElementById("memberId"); 
    var nic = document.getElementById("nic"); 
    var userName = document.getElementById("userName"); 
    
   
    var form = new FormData(); 
    form.append("memberId",memberId.value); 
    form.append("nic",nic.value); 
    form.append("userName",userName.value); 

    var req = new XMLHttpRequest(); 
    req.onreadystatechange = function(){ 
      
        if(req.readyState == 4 && req.status == 200){ 
          var resp = req.responseText; 
          document.getElementById("content").innerHTML = resp; 
        } 
    } 
    req.open("POST","search-users-process.php",true); 
    req.send(form); 
} 
   
function loadUserDataUpdate(id) {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var resp = req.responseText;
            var data = JSON.parse(resp);
            
            document.getElementById("membership-id").value = data.member_id;
            document.getElementById("first-name").value = data.fname;
            document.getElementById("last-name").value = data.lname;
            document.getElementById("email").value = data.email;
            document.getElementById("phone").value = data.mobile;
            document.getElementById("address").value = data.address;
            document.getElementById("nic").value = data.nic;

            var profileImg = data.profile_img && data.profile_img.trim() !== "" ? data.profile_img : 'assets/profimg/user.jpg'; 
 
          document.getElementById("profileimg").src = profileImg; 

        }
    };

    req.open("GET", "get-member-details.php?id=" + id, true);
    req.send();
}

  













