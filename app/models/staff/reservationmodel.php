<?php

require_once config::getdbPath();
    /**
 * ReservationModel
 * Handles retrieval and search of book reservations.
 */
class ReservationModel
{       /**
     * Get all reservations with pagination.
     *
     * @param int $page
     * @param int $resultsPerPage
     * @return array
     */
    public static function getAllReservations($page, $resultsPerPage)
    {
        $offset = ($page - 1) * $resultsPerPage;
        $totalReservations = self::getTotalReservations();

        $query = "SELECT `reservation_id`, `id`, `member_id`, `reservation_book_id`, `title`, `reservation_date`, `expiration_date`, `reservation_status`.`status`
                  FROM `reservation`
                  INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`
                  INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
                  INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                  INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`
                  LIMIT ? OFFSET ?";

        $params = [$resultsPerPage, $offset];
        $types = "ii";

        $rs = Database::search($query, $params, $types);

        $reservations = [];
        while ($row = $rs->fetch_assoc()) {
            $reservations[] = $row;
        }

        return [
            'total' => $totalReservations,
            'results' => $reservations
        ];
    }


    private static function getTotalReservations()
    {
        $query = "SELECT COUNT(*) AS total
                  FROM `reservation`
                  INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`
                  INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
                  INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                  INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`";

        $rs = Database::search($query);
        $row = $rs->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function searchReservations($memberid, $bookid, $title, $status, $page, $resultsPerPage)
    {
        $offset = ($page - 1) * $resultsPerPage;

        $params = [];
        $types = '';

        $whereClauses = ["1"]; // Start with "1" to simplify appending

        if (!empty($memberid)) {
            $whereClauses[] = "`member_id` LIKE ?";
            $params[] = "%$memberid%";
            $types .= 's';
        }
        if (!empty($bookid)) {
            $whereClauses[] = "`reservation_book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types .= 's';
        }
        if (!empty($title)) {
            $whereClauses[] = "`title` LIKE ?";
            $params[] = "%$title%";
            $types .= 's';
        }
        if (!empty($status) && $status !== 'all') {
            $whereClauses[] = "`reservation_status`.`status` = ?";
            $params[] = $status;
            $types .= 's';
        }

        $whereSQL = implode(" AND ", $whereClauses);

        $sql = "SELECT `reservation_id`, `id`, `member_id`, `reservation_book_id`, `title`, `reservation_date`, `expiration_date`, `reservation_status`.`status`
                FROM `reservation`
                INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`
                INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
                INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`
                WHERE $whereSQL
                LIMIT ? OFFSET ?";

        $params[] = $resultsPerPage;
        $params[] = $offset;
        $types .= 'ii';

        $rs = Database::search($sql, $params, $types);

        $reservations = [];
        while ($row = $rs->fetch_assoc()) {
            $reservations[] = $row;
        }

        $total = self::getTotalSearchResults($memberid, $bookid, $title, $status);

        return ['results' => $reservations, 'total' => $total];
    }
        /**
     * Get total number of reservations matching search filters.
     *
     * @param string $memberId
     * @param string $bookId
     * @param string $title
     * @param string $status
     * @return int
     */

    private static function getTotalSearchResults($memberid, $bookid, $title, $status)
    {
        $params = [];
        $types = '';
        $whereClauses = ["1"];

        if (!empty($memberid)) {
            $whereClauses[] = "`member_id` LIKE ?";
            $params[] = "%$memberid%";
            $types .= 's';
        }
        if (!empty($bookid)) {
            $whereClauses[] = "`reservation_book_id` LIKE ?";
            $params[] = "%$bookid%";
            $types .= 's';
        }
        if (!empty($title)) {
            $whereClauses[] = "`title` LIKE ?";
            $params[] = "%$title%";
            $types .= 's';
        }
        if (!empty($status) && $status !== 'all') {
            $whereClauses[] = "`reservation_status`.`status` = ?";
            $params[] = $status;
            $types .= 's';
        }

        $whereSQL = implode(" AND ", $whereClauses);

        $countQuery = "SELECT COUNT(`reservation_id`) AS total
                       FROM `reservation`
                       INNER JOIN `book` ON `reservation`.`reservation_book_id` = `book`.`book_id`
                       INNER JOIN `member` ON `reservation`.`reservation_member_id` = `member`.`id`
                       INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId`
                       INNER JOIN `reservation_status` ON `reservation`.`status_id` = `reservation_status`.`status_id`
                       WHERE $whereSQL";

        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }
}
