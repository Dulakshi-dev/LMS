<?php
require_once __DIR__ . '/../main.php';

require_once config::getdbPath();
require_once Config::getModelPath('paymentmodel.php');

// Call the function to deactivate expired reservations
PaymentModel::checkOverduePayments();

echo "Expired memberships deactivated successfully.";
?>
