<?php
// Log start time
echo "=== Task Execution Started: " . date("Y-m-d H:i:s") . " ===\n";

// Deactivate expired reservations
echo "Running: deactivate_reservations.php\n";
include 'deactivate_reservations.php';

// Deactivate expired memberships
echo "Running: deactivate_membership.php\n";
include 'deactivate_membership.php';

// Send renewal reminder emails
echo "Running: send_renewal_emails.php\n";
include 'send_renewal_emails.php';

echo "=== Task Execution Completed: " . date("Y-m-d H:i:s") . " ===\n";
?>
