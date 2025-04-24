<?php
require_once __DIR__ . '/../main.php';

require_once config::getdbPath();
require_once Config::getModelPath('member', 'paymentmodel.php');

PaymentModel::getMembersToRenewMembership();

error_log("Sending membership renewal emails");
