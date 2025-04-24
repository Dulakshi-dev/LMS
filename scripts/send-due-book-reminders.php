<?php
require_once __DIR__ . '/../main.php';

require_once config::getdbPath();
require_once Config::getModelPath('member', 'circulationmodel.php');

CirculationModel::getBooksAboutToDue();

error_log("Due book reminders sent successfully.");
?>

