<?php

require_once __DIR__ . '../../../database/connection.php';

class PaymentModel
{
    public static function insertPayment($transaction_id, $id)
    {

        Database::insert("INSERT INTO `payment` (`amount`, `trasaction_id`, `payed_at`, `next_due_date`, `member_id`) 
VALUES ('1000', '$transaction_id', NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), '$id');");
        return true;
    }

}