<?php
// Log start time
echo "=== Task Execution Started: " . date("Y-m-d H:i:s") . " ===\n";

// Deactivate expired reservations
echo "Running: expire_reservations.php\n";
error_log( "Running: expire_reservations.php\n");
include 'expire_reservations.php';

// Deactivate expired memberships
echo "Running: deactivate-membership.php\n";
error_log( "Running: deactivate-membership.php\n");
include 'deactivate-membership.php';

// Send renewal reminder emails
echo "Running: send-membership-reminders.php\n";
error_log( "Running: send-membership-reminders.php\n");
include 'send-membership-reminders.php';

echo "Running: send-due-reservation-reminders.php\n";
error_log( "Running: send-due-reservation-reminders.php\n");
include 'send-due-reservation-reminders.php';

echo "Running: send-due-book-reminders.php\n";
error_log( "Running: send-due-book-reminders.php\n");
include 'send-due-book-reminders.php';


echo "=== Task Execution Completed: " . date("Y-m-d H:i:s") . " ===\n";
?>
