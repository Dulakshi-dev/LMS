<?php
$member_id = $_SESSION["member"]["member_id"];
$current_action = isset($_GET['action']) ? $_GET['action'] : 'dashboard'; 
?>

<body>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="d-flex flex-column bg-light vh-100 p-3" style="width: 300px;">
      <!-- Menu Items -->
      <div class="list-group flex-grow-1">
        <a href="<?php echo Config::indexPathMember() ?>?action=dashboard" class="list-group-item list-group-item-action <?php echo ($current_action == 'dashboard') ? 'bg-dark text-white' : ''; ?>">
          <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=profile" class="list-group-item list-group-item-action <?php echo ($current_action == 'profile') ? 'bg-dark text-white' : ''; ?>">
          <i class="fas fa-user "></i> My Profile
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=viewMyLibrary" class="list-group-item list-group-item-action <?php echo ($current_action == 'viewMyLibrary') ? 'bg-dark text-white' : ''; ?>">
          <i class="fas fa-book "></i> My Library
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=viewborrowhistory" class="list-group-item list-group-item-action <?php echo ($current_action == 'viewborrowhistory') ? 'bg-dark text-white' : ''; ?>">
          <i class="fas fa-list-alt "></i> Borrow History
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=reservedbooks" class="list-group-item list-group-item-action <?php echo ($current_action == 'reservedbooks') ? 'bg-dark text-white' : ''; ?>">
          <i class="fas fa-eye"></i> Reserved Books
        </a>
        <a href="<?php echo Config::indexPathMember() ?>?action=aboutsoftware" class="list-group-item list-group-item-action <?php echo ($current_action == 'aboutsoftware') ? 'bg-dark text-white' : ''; ?>">
          <i class="fas fa-info-circle"></i> About Software
        </a>
      </div>

      <!-- User Section -->
      <div class="mt-auto text-center">
        <div class="d-flex align-items-center">
        <img src="index.php?action=serveprofimage&image=<?= !empty($profile_img) ? $profile_img : 'user.jpg'; ?>"
          alt="User" id="headerprofileimg" class="rounded-circle me-2" style="height: 40px; width: 40px;">
          <p class="mb-0"><?php echo $fname . " " . $lname; ?></p>
        </div>
        <div class="d-flex"><a href="index.php?action=logout" class="btn btn-outline-dark btn-sm mt-4 d-flex align-items-center">
            <i class="fas fa-sign-out-alt mx-2"></i> Log Out
          </a></div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
