<?php
require_once config::getdbPath();
/**
 * StaffModel
 * Handles CRUD operations, search, and status management for staff members.
 */
class StaffModel
{     /**
     * Get all staff members with pagination and status filter.
     *
     * @param int $page
     * @param int $resultsPerPage
     * @param string $status
     * @return array
     */
    public static function getAllStaff($page, $resultsPerPage, $status = 'Active')
    {
        $statusId = ($status === 'Active') ? 1 : 2;
        $offset = ($page - 1) * $resultsPerPage;
        $totalUsers = self::getTotalStaff($statusId);

        $query = "SELECT * FROM `staff`
                  JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
                  JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
                  WHERE `status_id` = ?
                  LIMIT ? OFFSET ?";

        $params = [$statusId, $resultsPerPage, $offset];
        $types = "iii";

        $rs = Database::search($query, $params, $types);

        $users = [];
        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }

        return ['total' => $totalUsers, 'results' => $users];
    }

    private static function getTotalStaff($statusId)
    {
        $query = "SELECT COUNT(*) AS total FROM `staff`
                  JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
                  JOIN `role` ON `staff`.`role_id` = `role`.`role_id`
                  WHERE `status_id` = ?";

        $params = [$statusId];
        $types = "i";

        $rs = Database::search($query, $params, $types);
        $row = $rs->fetch_assoc();
        return $row['total'] ?? 0;
    }
    /**
     * Search staff members with filters and pagination.
     *
     * @param string $memberId
     * @param string $nic
     * @param string $userName
     * @param string $status
     * @param int $page
     * @param int $resultsPerPage
     * @return array
     */
    public static function searchStaff($memberId, $nic, $userName, $status = 'Active', $page, $resultsPerPage)
    {
        $statusId = ($status === 'Active') ? 1 : 2;
        $offset = ($page - 1) * $resultsPerPage;

        $whereClauses = ["`staff`.`status_id` = ?"];
        $params = [$statusId];
        $types = "i";

        if (!empty($memberId)) {
            $whereClauses[] = "`staff_id` LIKE ?";
            $params[] = "%$memberId%";
            $types .= "s";
        }
        if (!empty($nic)) {
            $whereClauses[] = "`nic` LIKE ?";
            $params[] = "%$nic%";
            $types .= "s";
        }
        if (!empty($userName)) {
            $whereClauses[] = "(`fname` LIKE ? OR `lname` LIKE ?)";
            $params[] = "%$userName%";
            $params[] = "%$userName%";
            $types .= "ss";
        }

        $whereSQL = implode(" AND ", $whereClauses);

        $sql = "SELECT * FROM `staff`
                JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
                WHERE $whereSQL
                LIMIT ? OFFSET ?";

        // Add LIMIT and OFFSET params
        $params[] = $resultsPerPage;
        $params[] = $offset;
        $types .= "ii";

        $rs = Database::search($sql, $params, $types);

        $users = [];
        while ($row = $rs->fetch_assoc()) {
            $users[] = $row;
        }
    /**
     * Get total search results for filtered staff.
     *
     * @param string $memberId
     * @param string $nic
     * @param string $userName
     * @param int $statusId
     * @return int
     */
        $totalSearch = self::getTotalSearchResults($memberId, $nic, $userName, $statusId);

        return ['results' => $users, 'total' => $totalSearch];
    }

    private static function getTotalSearchResults($memberId, $nic, $userName, $statusId)
    {
        $whereClauses = ["`staff`.`status_id` = ?"];
        $params = [$statusId];
        $types = "s";

        if (!empty($memberId)) {
            $whereClauses[] = "`staff_id` LIKE ?";
            $params[] = "%$memberId%";
            $types .= "s";
        }
        if (!empty($nic)) {
            $whereClauses[] = "`nic` LIKE ?";
            $params[] = "%$nic%";
            $types .= "s";
        }
        if (!empty($userName)) {
            $whereClauses[] = "(`fname` LIKE ? OR `lname` LIKE ?)";
            $params[] = "%$userName%";
            $params[] = "%$userName%";
            $types .= "ss";
        }

        $whereSQL = implode(" AND ", $whereClauses);

        $countQuery = "SELECT COUNT(*) as total FROM `staff`
                       JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
                       WHERE $whereSQL";

        $result = Database::search($countQuery, $params, $types);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function loadStaffDetails($id)
    {
        $sql = "SELECT * FROM `staff`
            INNER JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
            WHERE `staff_id` = ?";

        $params = [$id];
        $types = "s";

        return Database::search($sql, $params, $types);
    }

    public static function getStaffbyID($user_id)
    {
        $sql = "SELECT * FROM `staff`
            JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
            WHERE `staff_id` = ?";

        $params = [$user_id];
        $types = "s";

        $rs = Database::search($sql, $params, $types);
        return $rs->fetch_assoc();
    }


    public static function UpdateStaffDetails($user_id, $fname, $lname, $email, $phone, $address, $nic)
    {
        $sql = "UPDATE `staff`
            INNER JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
            SET `fname` = ?, 
                `lname` = ?, 
                `mobile` = ?,  
                `address` = ?, 
                `nic` = ?, 
                `email` = ?
            WHERE `staff_id` = ?";

        $params = [$fname, $lname, $phone, $address, $nic, $email, $user_id];
        $types = "sssssss";

        Database::ud($sql, $params, $types);
        return true;
    }

    public static function loadMailDetails($id)
    {
        $sql = "SELECT * FROM `staff`
            INNER JOIN `staff_login` ON `staff`.`id` = `staff_login`.`staffId`
            WHERE `staff_id` = ?";

        $params = [$id];
        $types = "s";

        return Database::search($sql, $params, $types);
    }

    public static function deactivateStaff($id)
    {
        $sql = "UPDATE `staff` SET `status_id` = 2 WHERE `id` = ?";
        $params = [$id];
        $types = "i";

        Database::ud($sql, $params, $types);
        return true;
    }


    public static function activateStaff($id)
    {
        $sql = "UPDATE `staff` SET `status_id` = 1 WHERE `id` = ?";
        $params = [$id];
        $types = "i"; 

        Database::ud($sql, $params, $types);
        return true;
    }

    public static function generateKey($email, $role_id)
    {
        // Generate a random key (32-character, 16 bytes)
        $key = strtoupper(bin2hex(random_bytes(16)));

        $sql = "INSERT INTO `staff_key` (`email`, `key_value`, `role_id`) VALUES (?, ?, ?)";
        $params = [$email, $key, $role_id];
        $types = "ssi"; 

        Database::insert($sql, $params, $types);
        return $key;
    }
}
