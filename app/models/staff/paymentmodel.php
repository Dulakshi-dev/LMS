<?php

require_once config::getdbPath();



class PaymentModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public static function getAllPayments($page, $resultsPerPage)
{
    $pageResults = ($page - 1) * $resultsPerPage;

    $query = "SELECT * FROM (
        SELECT
            payment.transaction_id AS transaction_id,
            member_login.member_id AS member_id,
            payment.amount AS amount,
            payment.payed_at AS payed_at,
            payment.next_due_date AS next_due_date
        FROM payment
        JOIN member ON member.id = payment.memberId
        JOIN member_login ON member.id = member_login.memberId
        UNION ALL
        SELECT
            fines.fine_id AS transaction_id,
            member_login.member_id AS member_id,
            fines.amount AS amount,
            fines.payed_on AS payed_at,
            NULL AS next_due_date
        FROM fines
        JOIN borrow ON fines.fine_borrow_id = borrow.borrow_id
        JOIN member ON member.id = borrow.borrow_member_id
        JOIN member_login ON member.id = member_login.memberId
    ) AS combined
    ORDER BY payed_at IS NULL, payed_at DESC
    LIMIT $pageResults, $resultsPerPage";

    $rs = Database::search($query);
    $payments = [];

    $totalAmount = 0;

    while ($row = $rs->fetch_assoc()) {
        $payments[] = $row;
        $totalAmount += floatval($row['amount']);
    }

    $totalPayments = self::getTotalPayments();

    return [
        'total' => $totalPayments,
        'results' => $payments,
        'totalAmount' => $totalAmount
    ];
}


    private static function getTotalPayments()
    {
        $result = Database::search("SELECT 
        (SELECT COUNT(*) FROM payment) + 
        (SELECT COUNT(*) FROM fines JOIN borrow ON fines.fine_borrow_id = borrow.borrow_id) AS total
    ");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchPayments($memberId, $transactionid, $paymentType, $page, $resultsPerPage)
{
    $offset = ($page - 1) * $resultsPerPage;

    $whereClauses = [];

    if (!empty($memberId)) {
        $whereClauses[] = "`member_id` LIKE '%$memberId%'";
    }

    if (!empty($transactionid)) {
        $whereClauses[] = "`transaction_id` LIKE '%$transactionid%'";
    }

    if (!empty($paymentType)) {
        if ($paymentType == "fine") {
            $whereClauses[] = "`transaction_id` LIKE 'F%'";
        } elseif ($paymentType == "membership") {
            $whereClauses[] = "`transaction_id` NOT LIKE 'F%'";
        }
    }

    $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

    $sql = "SELECT * FROM (
        SELECT 
            payment.transaction_id AS transaction_id,
            member_login.member_id AS member_id,
            payment.amount,
            payment.payed_at,
            payment.next_due_date
        FROM payment
        JOIN member ON member.id = payment.memberId
        JOIN member_login ON member.id = member_login.memberId

        UNION ALL

        SELECT 
            fines.fine_id AS transaction_id,
            member_login.member_id AS member_id,
            fines.amount,
            fines.payed_on AS payed_at,
            NULL AS next_due_date
        FROM fines
        JOIN borrow ON fines.fine_borrow_id = borrow.borrow_id
        JOIN member ON member.id = borrow.borrow_member_id
        JOIN member_login ON member.id = member_login.memberId
    ) AS combined
    $whereSQL
    ORDER BY payed_at IS NULL, payed_at DESC
    LIMIT $resultsPerPage OFFSET $offset";

    $rs = Database::search($sql);
    $payments = [];
    $totalAmount = 0;

    while ($row = $rs->fetch_assoc()) {
        $payments[] = $row;
        $totalAmount += floatval($row['amount']);
    }

    $total = self::getTotalSearchResults($memberId, $transactionid, $paymentType);

    return ['results' => $payments, 'total' => $total, 'totalAmount' => $totalAmount];
}

    private static function getTotalSearchResults($memberId, $transactionid, $paymentType)
    {
        $whereClauses = [];

        if (!empty($memberId)) {
            $whereClauses[] = "`member_id` LIKE '%$memberId%'";
        }

        if (!empty($transactionid)) {
            $whereClauses[] = "`transaction_id` LIKE '%$transactionid%'";
        }

        if (!empty($paymentType)) {
            if ($paymentType == "fine") {
                $whereClauses[] = "`transaction_id` LIKE 'F%'";
            } elseif ($paymentType == "membership") {
                $whereClauses[] = "`transaction_id` NOT LIKE 'F%'";
            }
        }

        $whereSQL = "";
        if (!empty($whereClauses)) {
            $whereSQL = "WHERE " . implode(" AND ", $whereClauses);
        }

        $countQuery = "SELECT COUNT(*) AS total FROM (
            SELECT 
                payment.transaction_id AS transaction_id,
                member_login.member_id
            FROM payment
            JOIN member ON member.id = payment.memberId
            JOIN member_login ON member.id = member_login.memberId

            UNION ALL

            SELECT 
                fines.fine_id AS transaction_id,
                member_login.member_id
            FROM fines
            JOIN borrow ON fines.fine_borrow_id = borrow.borrow_id
            JOIN member ON member.id = borrow.borrow_member_id
            JOIN member_login ON member.id = member_login.memberId
        ) AS combined
        $whereSQL
    ";

        $result = Database::search($countQuery);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
