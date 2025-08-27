<?php

require_once config::getdbPath();

class LibrarySetupModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public static function changeOpeningHours($weekdaysfrom, $weekdaysto, $weekendfrom, $weekendto, $holidayfrom, $holidayto)
    {
        // Convert '00:00:00' to NULL
        $weekdaysfrom = $weekdaysfrom === '00:00:00' ? NULL : $weekdaysfrom;
        $weekdaysto = $weekdaysto === '00:00:00' ? NULL : $weekdaysto;
        $weekendfrom = $weekendfrom === '00:00:00' ? NULL : $weekendfrom;
        $weekendto = $weekendto === '00:00:00' ? NULL : $weekendto;
        $holidayfrom = $holidayfrom === '00:00:00' ? NULL : $holidayfrom;
        $holidayto = $holidayto === '00:00:00' ? NULL : $holidayto;

        $query = "UPDATE `opening_hours` SET 
                `open_time` = CASE 
                    WHEN `day` = 'Week Day' THEN ? 
                    WHEN `day` = 'Week End' THEN ? 
                    WHEN `day` = 'Holiday' THEN ? 
                    ELSE `open_time`
                END,
                `close_time` = CASE 
                    WHEN `day` = 'Week Day' THEN ? 
                    WHEN `day` = 'Week End' THEN ? 
                    WHEN `day` = 'Holiday' THEN ? 
                    ELSE `close_time`
                END
                WHERE `day` IN ('Week Day', 'Week End', 'Holiday')";

        $params = [$weekdaysfrom, $weekendfrom, $holidayfrom, $weekdaysto, $weekendto, $holidayto];
        $types = "ssssss";

        Database::ud($query, $params, $types);

        return true;
    }


    public static function changeNewsUpdates($boxId, $title, $date, $description, $image)
    {
        // Check if the record exists
        $rs = Database::search("SELECT * FROM `news` WHERE `id` = ?", [$boxId], "i");

        if ($rs && $rs->num_rows > 0) {
            // Update existing news
            $query = "UPDATE `news` SET 
                    `title` = ?, 
                    `date` = ?, 
                    `description` = ?, 
                    `image_path` = ? 
                  WHERE `id` = ?";
            $params = [$title, $date, $description, $image, $boxId];
            $types = "ssssi";

            Database::ud($query, $params, $types);
            return true;
        } else {
            // Insert new news
            $query = "INSERT INTO `news` (`id`, `title`, `date`, `description`, `image_path`) 
                  VALUES (?, ?, ?, ?, ?)";
            $params = [$boxId, $title, $date, $description, $image];
            $types = "issss";

            Database::insert($query, $params, $types);
            return false;
        }
    }


    public static function changeLibraryInfo($name, $address, $email, $phone, $fee, $logo, $fine)
    {
        $query = "UPDATE `library_info` 
              SET `name` = ?, 
                  `address` = ?, 
                  `email` = ?, 
                  `mobile` = ?, 
                  `membership_fee` = ?, 
                  `logo` = ?, 
                  `fine_amount` = ? 
              WHERE `id` = 1";

        $params = [$name, $address, $email, $phone, $fee, $logo, $fine];
        $types  = "ssssdss";

        Database::ud($query, $params, $types);

        return true;
    }


    public static function getAllActiveStaff()
    {
        // Search for active members only
        $query = "SELECT * FROM `staff` WHERE `status_id` = '1'";
        $rs = Database::search($query);
        // Check if any active members exist
        if ($rs->num_rows > 0) {
            return $rs;
        } else {
            return false;
        }
    }

    public static function getAllActiveMembers()
    {
        // Search for active members only
        $query = "SELECT * FROM `member` WHERE `status_id` = '1'";
        $rs = Database::search($query);

        // Check if any active members exist
        if ($rs->num_rows > 0) {
            return $rs;
        } else {
            return false;
        }
    }

    public static function getLibraryInfo()
    {
        $query = "SELECT * FROM `library_info` LIMIT 1";
        $result = Database::search($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public static function getOpeningHours()
    {
        // Execute the query and return the results
         $query = "SELECT 
            CASE 
                WHEN `day` = 'Week Day' THEN 'Weekday' 
                WHEN `day` = 'Week End' THEN 'Weekend' 
                WHEN `day` = 'Holiday' THEN 'Holiday' 
                ELSE `day` 
            END AS `day_label`,
            `open_time`, 
            `close_time`
        FROM `opening_hours`;";
        $result = Database::search($query);

        // Check if there are results and return them
        if ($result && $result->num_rows > 0) {
            $openingHours = [];

            // Fetch each row and add it to the result array
            while ($row = $result->fetch_assoc()) {
                $openingHours[] = [
                    'day_label' => $row['day_label'],
                    'open_time' => $row['open_time'],
                    'close_time' => $row['close_time']
                ];
            }

            return $openingHours; // Return the array of opening hours
        }

        return false; // If no results are found, return false
    }
}
