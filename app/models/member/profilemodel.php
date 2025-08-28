<?php

require_once config::getdbPath();

/**
 * ProfileModel
 * Handles all member profile-related functionalities such as loading details, updating profile, and password management.
 */

class ProfileModel
{       /**
     * loadMemberDetails
     * Loads all member details for a given member ID.
     * @param string $id Member login ID
     * @return mysqli_result Result set containing member details
     */
    public static function loadMemberDetails($id)
    {
        $query = "SELECT `id`,`member_id`,`nic`,`fname`,`lname`,`address`,`mobile`,`email`,`profile_img` FROM `member` INNER JOIN `member_login` ON `member`.`id` = `member_login`.`memberId` WHERE `member_id` = ?";
        $params = [$id];
        $types = "s";
        $rs = Database::search($query, $params, $types);

        return $rs;
    }
     
        /**
     * updateMemberDetails
     * Updates member details including a new profile image.
     * @param string $nic Member NIC
     * @param string $fname First name
     * @param string $lname Last name
     * @param string $address Member address
     * @param string $mobile Mobile number
     * @param string $fileName Profile image filename
     * @return bool True on successful update
     */
    public static function updateMemberDetails($nic, $fname, $lname, $address, $mobile, $fileName)
    {
        $query = "UPDATE `member` SET `fname`=?,`lname`=?,`address`=?, `mobile`=?, `profile_img`=? WHERE `nic` = ?";
        $params = [$fname, $lname, $address, $mobile, $fileName, $nic];
        $types = "ssssss";
        Database::ud($query, $params, $types);

        return true;
    }
    /**
     * updateMemberDetailsWithoutImage
     * Updates member details without changing the profile image.
     * @param string $nic Member NIC
     * @param string $fname First name
     * @param string $lname Last name
     * @param string $address Address
     * @param string $mobile Mobile number
     * @return bool True on successful update
     */
    public static function updateMemberDetailsWithoutImage($nic, $fname, $lname, $address, $mobile)
    {
        $query = "UPDATE `member` SET `fname`=?,`lname`=?,`address`=?, `mobile`=? WHERE `nic` = ?";
        $params = [$fname, $lname, $address, $mobile, $nic];
        $types = "sssss";
        Database::ud($query, $params, $types);

        return true;
    }

    public static function getMemberCurrentProfileImage($nic)
    {
        $query = "SELECT `profile_img` FROM `member` WHERE `nic` = ?";
        $params = [$nic];
        $types = "s";
        $result = Database::search($query, $params, $types);

        if ($row = $result->fetch_assoc()) {
            return $row['profile_img'];
        }
        return null;
    }

        /**
     * validateCurrentPassword
     * Validates if the provided password matches the current password for a member.
     * @param string $memid Member login ID
     * @param string $password Plain text password
     * @return bool True if password is correct, false otherwise
     */
    public static function validateCurrentPassword($memid, $password)
    {
        $query = "SELECT * from `member_login` WHERE `member_id`=?";
        $params = [$memid];
        $types = "s";
        $result = Database::search($query, $params, $types);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
        return false;
    }


    public static function resetPassword($memid, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE `member_login` SET `password` = ? WHERE `member_id` = ?";
        $params = [$hashedPassword, $memid];
        $types = "ss";

        Database::ud($query, $params, $types);
        return true;
    }
}
