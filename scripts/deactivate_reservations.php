<?php
require_once __DIR__ . '../database/connection.php';
require_once Config::getModelPath('reservationmodel.php');

// Call the function to deactivate expired reservations
ReservationModel::deactivateExpiredReservations();

echo "Expired reservations have been deactivated successfully.";
?>
