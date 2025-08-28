<?php

require_once config::getdbPath();

/**
 * PaymentModel
 * Handles all payment-related operations including fines and membership payments.
 */

class PaymentModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
        /**
     * Get all payments with pagination.
     *
     * @param int $page
     * @param int $resultsPerPage
     * @return array
     */

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
    LIMIT ?, ?";

        // Use prepared statement parameters for LIMIT and OFFSET
        $params = [$pageResults, $resultsPerPage];
        $types = "ii";

        $rs = Database::search($query, $params, $types);

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
        $query = "SELECT 
        (SELECT COUNT(*) FROM payment) + 
        (SELECT COUNT(*) FROM fines 
            JOIN borrow ON fines.fine_borrow_id = borrow.borrow_id) AS total";

        // No parameters here, but using the Database::search method keeps it consistent
        $rs = Database::search($query);

        $row = $rs->fetch_assoc();
        return $row['total'] ?? 0;
    }


    public static function searchPayments($memberId, $transactionid, $paymentType, $page, $resultsPerPage)
    {
        $offset = ($page - 1) * $resultsPerPage;
        $params = [];
        $types = '';

        $whereClauses = [];

        if (!empty($memberId)) {
            $whereClauses[] = "`member_id` LIKE ?";
            $params[] = "%$memberId%";
            $types .= 's';
        }

        if (!empty($transactionid)) {
            $whereClauses[] = "`transaction_id` LIKE ?";
            $params[] = "%$transactionid%";
            $types .= 's';
        }

        if (!empty($paymentType)) {
            if ($paymentType === "fine") {
                $whereClauses[] = "`transaction_id` LIKE ?";
                $params[] = "F%";
                $types .= 's';
            } elseif ($paymentType === "membership") {
                $whereClauses[] = "`transaction_id` NOT LIKE ?";
                $params[] = "F%";
                $types .= 's';
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
    LIMIT ? OFFSET ?";

        // Add LIMIT and OFFSET parameters
        $params[] = $resultsPerPage;
        $params[] = $offset;
        $types .= 'ii';

        $rs = Database::search($sql, $params, $types);

        $payments = [];
        $totalAmount = 0;

        while ($row = $rs->fetch_assoc()) {
            $payments[] = $row;
            $totalAmount += floatval($row['amount']);
        }

        $total = self::getTotalSearchResults($memberId, $transactionid, $paymentType);

        return ['results' => $payments, 'total' => $total, 'totalAmount' => $totalAmount];
    }

    /**
     * Get total count of payments based on search filters.
     *
     * @param string|null $memberId
     * @param string|null $transactionId
     * @param string|null $paymentType
     * @return int
     */
    private static function getTotalSearchResults($memberId, $transactionid, $paymentType)
    {
        $params = [];
        $types = '';
        $whereClauses = [];

        if (!empty($memberId)) {
            $whereClauses[] = "`member_id` LIKE ?";
            $params[] = "%$memberId%";
            $types .= 's';
        }

        if (!empty($transactionid)) {
            $whereClauses[] = "`transaction_id` LIKE ?";
            $params[] = "%$transactionid%";
            $types .= 's';
        }

        if (!empty($paymentType)) {
            if ($paymentType === "fine") {
                $whereClauses[] = "`transaction_id` LIKE ?";
                $params[] = "F%";
                $types .= 's';
            } elseif ($paymentType === "membership") {
                $whereClauses[] = "`transaction_id` NOT LIKE ?";
                $params[] = "F%";
                $types .= 's';
            }
        }

        $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

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
    $whereSQL";

        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
