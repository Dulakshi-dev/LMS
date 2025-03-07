<?php
require_once __DIR__ . '/../main.php';

require_once config::getdbPath();
require_once Config::getModelPath('paymentmodel.php');

PaymentModel::sendMembershipReminder();

        echo "Sending membership renewal emails";

?>
