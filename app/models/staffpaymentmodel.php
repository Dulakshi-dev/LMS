<?php

require_once config::getdbPath();



class StaffPaymentModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getAllPayments($page, $resultsPerPage)
    {

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalPayments = self::getTotalPayments();

        $rs = Database::search("SELECT `transaction_id`,`member_login`.`member_id`,`amount`,`payed_at`,`next_due_date` 
                                FROM `payment`
                                JOIN `member` ON `member`.`id`=`payment`.`member_id`
                                JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                                LIMIT $resultsPerPage OFFSET $pageResults");

        $payments = [];

        while ($row = $rs->fetch_assoc()) {
            $payments[] = $row;
        }

        return [
            'total' => $totalPayments,
            'results' => $payments
        ];
    }

    private static function getTotalPayments()
    {
        $result = Database::search("SELECT COUNT(*) AS total 
                                FROM `payment`
                                JOIN `member` ON `member`.`id`=`payment`.`member_id`
                                JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchPayments($memberId, $transactionid, $page, $resultsPerPage)
    {

        $pageResults = ($page - 1) * $resultsPerPage;
        $totalSearch = self::getTotalSearchResults($memberId, $transactionid);

        $sql = "SELECT `transaction_id`,`member_login`.`member_id`,`amount`,`payed_at`,`next_due_date` 
                                FROM `payment`
                                JOIN `member` ON `member`.`id`=`payment`.`member_id`
                                JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE 1";

        if (!empty($memberId)) {
            $sql .= " AND `member_login`.`member_id` LIKE '%$memberId%'";
        }
        if (!empty($transactionid)) {
            $sql .= " AND `transaction_id` LIKE '%$transactionid%'";
        }

        $sql .= " LIMIT $resultsPerPage OFFSET $pageResults";

        $rs = Database::search($sql);

        $payments = [];
        while ($row = $rs->fetch_assoc()) {
            $payments[] = $row;
        }
        return ['results' => $payments, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($memberId, $transactionid)
    {
        $countQuery = "SELECT COUNT(*) as total 
                                FROM `payment`
                                JOIN `member` ON `member`.`id`=`payment`.`member_id`
                                JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE 1";

        if (!empty($memberId)) {
            $countQuery .= " AND `member_login`.`member_id` LIKE '%$memberId%'";
        }
        if (!empty($transactionid)) {
            $countQuery .= " AND `nic` LIKE '%$transactionid%'";
        }

        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
