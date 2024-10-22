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
    }else if(password === '') {
        document.getElementById('passwordError').innerText = 'Password is required.';
    }else if (!isValidPassword(password)) {
        document.getElementById('passwordError').innerText = 'Password must be at least 5 characters long, including at least one uppercase letter, one lowercase letter, and one number.';
    }  
    else{
        var form = new FormData();
        form.append("username",username);
        form.append("password",password);

        var req = new XMLHttpRequest();
        req.onreadystatechange = function(){
            if(req.readyState == 4 && req.status == 200){
                var resp = req.responseText;

                if(resp.trim() === "success"){
                    alert("success");
                    //window.location();
                
                }else{
                    document.getElementById("errormsg").innerHTML = resp;
                    document.getElementById("errormsgdiv").classList.remove("d-none");
                }
            }
        }

        req.open("POST","staff-login-process.php",true);
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
    }else if (password === '') {
        document.getElementById('passwordError').innerText = 'Password is required.';
    }else if (!isValidPassword(password)) {
        document.getElementById('passwordError').innerText = 'Password must be at least 5 characters long, including at least one uppercase letter, one lowercase letter, and one number.';
    } 
    else{
        var form = new FormData();
        form.append("username",username);
        form.append("password",password);
        form.append("rememberme", rememberMe.checked);
    
        var req = new XMLHttpRequest();
        req.onreadystatechange = function(){
            if(req.readyState == 4 && req.status == 200){
                var resp = req.responseText;
                if(resp.trim() === "success"){
                    showAlert("Success", "Login Success", "success").then(() => { 
                        window.location.reload(); 
                    });
                    //window.location = "#";
                }else{
                    document.getElementById("errormsg").innerHTML = resp;
                    document.getElementById("errormsgdiv").classList.remove("d-none");
                }
            }
        }
    
        req.open("POST","member-login-process.php",true);
        req.send(form);
    }   
}


function forgotPassword(){
    var email = document.getElementById('email').value;
    var req = new XMLHttpRequest();

    req.onreadystatechange = function(){
        if(req.readyState == 4 && req.status == 200){
         
            var resp = req.responseText;
            alert(resp);
        }
    }
    req.open("GET", "forgot-password-process.php?email="+email,true);
    req.send();

}

function resetPassword(){ 

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
    }else if (!isValidPassword(pw)) {
        pwError.innerText = 'Password must be at least 5 characters long, including at least one uppercase letter, one lowercase letter, and one number.';
    } 
     else {
        var pw = document.getElementById("pw").value; 
        var cpw = document.getElementById("cpw").value; 
        var vcode = document.getElementById("vcode").innerHTML; 
       
        var form = new FormData(); 
        form.append("pw",pw); 
        form.append("cpw",cpw); 
        form.append("vcode",vcode); 

        var req = new XMLHttpRequest(); 
        req.onreadystatechange = function(){ 
            if(req.readyState == 4 && req.status == 200){ 
                var resp = req.responseText; 
                alert(resp); 
                window.location="member-login.php";
            } 
        } 
        req.open("POST","reset-password-process.php",true); 
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
        req.onreadystatechange = function(){
           
            if(req.readyState == 4 && req.status == 200){
             
                var resp = req.responseText;
             
                if (resp.trim() === "success"){
              
                    document.getElementById("memerror").innerText = "";
                    document.getElementById("Box1").classList.add("d-none");
                    document.getElementById("Box2").classList.remove("d-none");
                }else{
                   
                    document.getElementById("validationerror").innerText = resp;
                }
               
            }
        }

        req.open("GET", "validation-process.php?memberID="+memId+"&nic="+nic,true);
        req.send();
    }
}

function box2(){
    var phonePattern = /^(07\d{8}|(\+94)7\d{8})$/; // Validates phone numbers starting with '07' or '+947' followed by 8 digits

    var address = document.getElementById("address").value;
    var phoneNumber = document.getElementById("phoneNumber").value;

    if (address === "") {
        document.getElementById("Addresserror").innerText = "Please enter Address";
    }
    else if (phoneNumber === "") {
        document.getElementById("Addresserror").innerText = "";
        document.getElementById("Pnumerror").innerText = "Please enter Phone Number";
    } else  if (!phonePattern.test(phoneNumber)) { // Validate phone number
        document.getElementById("Pnumerror").innerText="Invalid phone number. It must be 10 digits starting with '07' or 11 digits starting with '+947'.";
   }else{
        document.getElementById("Box2").classList.add("d-none");
        document.getElementById("Box3").classList.remove("d-none");
    } 
}

function box3(){
    alert("ll");
    var email = document.getElementById("email").value;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  
        if (email === "") {
            document.getElementById("Emailerror").innerText = "Please enter email address";
        } else if (!emailPattern.test(email)) {
            document.getElementById("Emailerror").innerText = "Invalid Email";
       
    } else{
        document.getElementById("Emailerror").innerText = "";
        var req = new XMLHttpRequest();   
        req.onreadystatechange = function(){
           
            if(req.readyState == 4 && req.status == 200){
             
                var resp = req.responseText;
                alert(resp);
                if(resp.trim() === "Check the email for otp"){
                    document.getElementById("Box3").classList.add("d-none");
                    document.getElementById("Box4").classList.remove("d-none");
                }
               
            }
        }

        req.open("GET", "generate-otp.php?email="+email,true);
        req.send();

        
    }
}

function box4(){
    var otp1 = document.getElementById("otp1").value;
    var otp2 = document.getElementById("otp2").value;
    var otp3 = document.getElementById("otp3").value;
    var otp4 = document.getElementById("otp4").value;
    var otp5 = document.getElementById("otp5").value;
    var otp6 = document.getElementById("otp6").value;

    var userotp = otp1+otp2+otp3+otp4+otp5+otp6;

    var form = new FormData(); 
    form.append("userotp",userotp);

    var req = new XMLHttpRequest(); 
    req.onreadystatechange = function(){ 
        if(req.readyState == 4 && req.status == 200){ 
            var resp = req.responseText; 
            if(resp ==='success'){
             
                document.getElementById("Box4").classList.add("d-none");
                document.getElementById("Box5").classList.remove("d-none");
            }
        } 
    } 
    req.open("POST","otp-varification.php",true); 
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

    if(password === ""){
        document.getElementById("perror").innerText = "Please Enter the password";
    }// Validate password
    else if (!passwordPattern.test(password)) {
        document.getElementById("perror").innerText = "Invalid password. It must be at least 5 characters long, and include at least one uppercase letter, one lowercase letter, and one number.";  
    }else if (password != cpassword){
        document.getElementById("cperror").innerText = "Password does not match";
        document.getElementById("perror").innerText = "";
    
    }else{
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
    req.onreadystatechange = function() { 
        if (req.readyState == 4 && req.status == 200) {
            var resp = req.responseText.trim();

            if (resp === "success") { 
                alert(resp);
                // showAlert("Success", "Successfully Registered", "success");
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



function back1(){
    document.getElementById("Box2").classList.add("d-none");
    document.getElementById("Box1").classList.remove("d-none");
}
function back2(){
    document.getElementById("Box3").classList.add("d-none");
    document.getElementById("Box2").classList.remove("d-none");
}
function back3(){
    document.getElementById("Box4").classList.add("d-none");
    document.getElementById("Box3").classList.remove("d-none");
}
//register end

//dash-board header script

const prof = document.getElementById("prof");
const signup = document.getElementById('signup');

// Add event listener to toggle dropdown visibility
prof.addEventListener('click', function(event) {
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

tog.addEventListener("click",function(event){
        if(sidepanel.style.display === "none"){
            sidepanel.style.display = "block";
        }
        else{
            sidepanel.style.display = "none";
        }
});

// showAlert function for alerts
function showAlert(title, message, type) {
    Swal.fire({
        title: title,
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info', 'question'
        confirmButtonText: 'OK'
    });
}

//dash_board_change_password
function dashboard_change_password(event){

    if (event) {
        event.preventDefault();
    }
    const box1 = document.getElementById("box1");
    const box2 = document.getElementById("box2");
    
    if(box1.style.display === "none"){
        box1.style.display = "block";  
        box2.style.display = "none"; 
    } else {
        box1.style.display = "none";
        box2.style.display = "block";
    }
}