<?php
require_once __DIR__ . '/../main.php';

require_once config::getdbPath();
require_once Config::getModelPath('member','reservationmodel.php');

// Call the function to deactivate expired reservations
ReservationModel::getReservationsToExpire();


error_log("Reminders for reservations about to expire sent.");
?>
