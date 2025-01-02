
function loadProfileData(id) {
    var formData = new FormData();
    formData.append("user_id", id);

    fetch("index.php?action=loadUserData", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                document.getElementById("staff_id").value = resp.user_id;
                document.getElementById("fname").value = resp.fname;
                document.getElementById("lname").value = resp.lname;
                document.getElementById("email").value = resp.email;
                document.getElementById("phone").value = resp.mobile;
                document.getElementById("address").value = resp.address;
                document.getElementById("nic").value = resp.nic;

                var profimg = resp.profile_img;
                var profileImgElement = document.getElementById("profileimg");

                if (profimg == "") {
                    profileImgElement.src = "index.php?action=serveprofimage&image=user.jpg";

                } else {
                    profileImgElement.src = "index.php?action=serveprofimage&image=" + profimg;

                }


            } else {
                alert("Failed to load user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}


function updateProfileDetails() {
    var staff_id = document.getElementById("staff_id").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var address = document.getElementById("address").value;
    var nic = document.getElementById("nic").value;
    var profimg = document.getElementById("uploadprofimg").files[0];

    var formData = new FormData();
    formData.append("staff_id", staff_id);
    formData.append("fname", fname);
    formData.append("lname", lname);
    formData.append("email", email);
    formData.append("mobile", phone);
    formData.append("address", address);
    formData.append("nic", nic);

    if (profimg) {
        formData.append("profimg", profimg);
    }

    fetch("index.php?action=updateprofile", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                location.reload();
            } else {
                alert("Failed to update user data. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
}

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