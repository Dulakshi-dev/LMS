<?php
require_once __DIR__ . '/../main.php';

require_once config::getdbPath();
require_once Config::getModelPath('memberreservationmodel.php');

// Call the function to deactivate expired reservations
MemberReservationModel::deactivateExpiredReservations();

echo "Expired reservations have been deactivated successfully.";
?>
